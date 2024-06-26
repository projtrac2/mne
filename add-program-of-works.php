<?php
try {
	require('includes/head.php');
	if ($permission) {
		$query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
		$query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
		$totalRows_rsProjects = $query_rsProjects->rowCount();

		function daily_team($projid, $workflow_stage, $role, $sub_stage)
		{
			global $db,  $user_name, $workflow_stage, $user_designation;
			$responsible = false;
			if ($user_designation == 1) {
				$responsible = true;
			} else {
				if ($sub_stage == 2 || $sub_stage == 4) {
					$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
					$query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $user_name));
					$total_rsOutput = $query_rsOutput->rowCount();
					$responsible = $total_rsOutput > 0 ? true : false;
				} else {
					$workflow_stage = 9;
					$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
					$query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 4, ":responsible" => $user_name, ":role" => $role));
					$total_rsOutput = $query_rsOutput->rowCount();
					$output_responsible = $total_rsOutput > 0 ? true : false;

					$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1");
					$query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name));
					$row_rsOutput_standin = $query_rsOutput_standin->fetch();
					$total_rsOutput_standin = $query_rsOutput_standin->rowCount();

					if ($total_rsOutput_standin > 0) {
						$owner_id = $row_rsOutput_standin['owner'];
						$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
						$query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 4, ":responsible" => $owner_id, ":role" => $role));
						$total_rsOutput = $query_rsOutput->rowCount();
						$standin_responsible = $total_rsOutput > 0 ? true : false;
					}
					$responsible = $standin_responsible || $output_responsible ? true : false;
				}
			}
			return $responsible;
		}

		function check_standin_responsible($projid)
		{
			global $db, $user_name;
			$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1");
			$query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name));
			$row_rsOutput_standin = $query_rsOutput_standin->fetch();
			$total_rsOutput_standin = $query_rsOutput_standin->rowCount();
			return $total_rsOutput_standin > 0 ? true : false;
		}


		$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE active = 1");
		$query_risk_impact->execute();

		$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
		$query_risk_categories->execute();

?>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon ?>
						<?php echo $pageTitle ?>
						<div class="btn-group" style="float:right">
							<div class="btn-group" style="float:right">
							</div>
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<?= $results; ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr style="background-color:#0b548f; color:#FFF">
												<th style="width:5%" align="center">#</th>
												<th style="width:10%">Code</th>
												<th style="width:40%">Project </th>
												<th style="width:10%">Project Category </th>
												<th style="width:10">Due Date</th>
												<th style="width:10">Status</th>
												<th style="width:5%">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($totalRows_rsProjects > 0) {
												$counter = 0;
												while ($row_rsProjects = $query_rsProjects->fetch()) {
													$projid = $row_rsProjects['projid'];
													$projid_hashed = base64_encode("projid54321{$projid}");
													$projname = $row_rsProjects['projname'];
													$implementation = $row_rsProjects['projcategory'];
													$sub_stage = $row_rsProjects['proj_substage'];
													$project_department = $row_rsProjects['projsector'];
													$project_section = $row_rsProjects['projdept'];
													$project_directorate = $row_rsProjects['directorate'];
													$start_date = date('Y-m-d');

													$query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid = :projid");
													$query_rsPlan->execute(array(":projid" => $projid));
													$totalRows_plan = $query_rsPlan->rowCount();
													$totalRows_plan = $query_rsPlan->fetch();

													$query_rsPlan_frequency = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid = :projid AND frequency_id IS NOT NULL");
													$query_rsPlan_frequency->execute(array(":projid" => $projid));
													$totalRows_plan_frequency = $query_rsPlan_frequency->rowCount();

													$filter_department = view_record($project_department, $project_section, $project_directorate);

													$details = "{
														get_edit_details: 'details',
														projid:$projid,
														workflow_stage:$workflow_stage,
														sub_stage:$sub_stage,
														project_directorate:$project_directorate,
													}";

													$assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
													$assign_responsible = (in_array("assign_data_entry_responsible", $page_actions) && $sub_stage == 0) || (in_array("assign_approval_responsible", $page_actions) && $sub_stage == 2) ? true : false;

													if ($filter_department) {
														$counter++;
														$today = date('Y-m-d');
														$assigned = ($sub_stage == 2 || $sub_stage == 4) ? true : false;
														$due_date = get_due_date($projid, $workflow_stage);


														$activity = "Add";
														if ($totalRows_plan > 0) {
															$activity = $sub_stage == 3 ? "Approve"  : "Edit";
														}

														$activity_status =	get_program_of_works_status($projid, $workflow_stage, $sub_stage);

														//0 1 2 3 (Data entry)
														// 4 5 6 7 (Frequency)
														// 8 (Target Breakdown)



														$responsible = daily_team($projid, 9, 2, $sub_stage);
														$project_category = $implementation == 1 ? "In-House" : "Contractor";

														$program_of_works  = false;
														if ($implementation == 1) {
															$program_of_works = true;
														} else {
															if ($sub_stage >= 3) {
																$program_of_works = true;
															}
														}
											?>
														<tr>
															<td align="center"><?= $counter ?></td>
															<td><?= $row_rsProjects['projcode'] ?></td>
															<td><?= $row_rsProjects['projname'] ?></td>
															<td><?= $project_category ?></td>
															<td><?= date('Y M d', strtotime($due_date)) ?></td>
															<td><?= $activity_status; ?></td>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		Options <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																		<li>
																			<a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
																				<i class="fa fa-file-text"></i> View More
																			</a>
																		</li>
																		<?php
																		if ($responsible) {
																		?>
																			<li>
																				<a type="button" data-toggle="modal" data-target="#assign_modal" id="assignModalBtn" onclick="get_responsible_options(<?= $details ?>)">
																					<i class="fa fa-users"></i> <?= !$assigned ? "Assign" : "Reassign" ?>
																				</a>
																			</li>
																			<?php
																			if ($implementation == 2) {
																				if ($sub_stage > 1 && $sub_stage < 4) {
																			?>
																					<li>
																						<a type="button" href="add-work-program.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																							<i class="fa fa-plus-square-o"></i> Approve Program of Works
																						</a>
																					</li>
																				<?php
																				} else if ($sub_stage > 3 && $sub_stage  < 8) {
																				?>
																					<li>
																						<a type="button" href="add-activity-frequency.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																							<i class="fa fa-plus-square-o"></i> <?= $totalRows_plan_frequency > 0 ? "Edit" : "Add" ?> Frequency
																						</a>
																					</li>
																				<?php
																				}
																			} else {
																				if ($sub_stage <= 3) {
																				?>
																					<li>
																						<a type="button" href="add-work-program.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																							<i class="fa fa-plus-square-o"></i> <?= $activity ?> Program of Works
																						</a>
																					</li>
																				<?php
																				} else if ($sub_stage <= 7) {
																				?>
																					<li>
																						<a type="button" href="add-activity-frequency.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																							<i class="fa fa-plus-square-o"></i> <?= $totalRows_plan_frequency > 0 ? "Edit" : "Add" ?> Frequency
																						</a>
																					</li>
																				<?php
																				} else if ($sub_stage == 8) {
																				?>
																					<li>
																						<a type="button" href="add-target-breakdown.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																							<i class="fa fa-plus-square-o"></i> <?= $activity ?> Target Breakdown
																						</a>
																					</li>
																		<?php
																				}
																			}
																		}
																		?>
																		<li>
																			<a type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_project_issues('<?= $projid_hashed ?>', '<?= htmlspecialchars($projname) ?>')">
																				<i class="fa fa-exclamation-triangle text-danger"></i> Issues
																			</a>
																		</li>
																	</ul>
																</div>
															</td>
														</tr>
											<?php

													}
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- end body  -->

		<!-- Start Item more -->
		<div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
					</div>
					<div class="modal-body" id="moreinfo">
					</div>
					<div class="modal-footer">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		<!-- End Item more -->
		<!-- Start Modal Item approve -->

		<!-- Start Modal Item approve -->
		<div class="modal fade" id="assign_modal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Assign Project</h4>
					</div>
					<form class="form-horizontal" id="assign_responsible" action="" method="POST">
						<?= csrf_token_html(); ?>
						<div class="modal-body" style="max-height:450px; overflow:auto;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="projduration">Responsible *:</label>
								<div class="form-line">
									<select name="responsible" id="responsible" class="form-control" required="required">
									</select>
								</div>
							</div>
						</div> <!-- /modal-body -->
						<div class="modal-footer approveItemFooter">
							<div class="col-md-12 text-center">
								<input type="hidden" name="projid" id="projid" value="">
								<input type="hidden" name="workflow_stage" id="workflow_stage" value="<?= $workflow_stage ?>">
								<input type="hidden" name="sub_stage" id="sub_stage" value="">
								<input type="hidden" name="assign_responsible" id="assign_responsible_data" value="new">
								<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Assign" />
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
							</div>
						</div> <!-- /modal-footer -->
					</form> <!-- /.form -->
				</div>
				<!-- /modal-content -->
			</div>
		</div>

		<!-- start issues modal  -->
		<div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> PROJECT ISSUES</span></h3>
					</div>
					<div class="modal-body">
						<div class="col-md-12">
							<ul class="list-group">
								<li class="list-group-item list-group-item list-group-item-action active">Project : <span id="project_name"></span> </li>
							</ul>
						</div>
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home"><i class="fa fa-pencil bg-orange" aria-hidden="true"></i> Record Issue &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-eye bg-blue" aria-hidden="true"></i> View Issues &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
							</ul>
						</div>
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<form class="form-horizontal" id="add_items" action="" method="POST">
									<?= csrf_token_html(); ?>
									<fieldset class="scheduler-border" id="specification_issues">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-exclamation-circle" aria-hidden="true"></i> New Issue
										</legend>
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
												<div class="form-inline">
													<label for="">Issue Description</label>
													<input name="issue_description" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:100%" placeholder="Describe the issue" required>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
												<div class="form-inline">
													<label for="">Issue Area</label>
													<div class="form-control" style="border:#CCC thin solid; border-radius:5px; width:100%">Others</div>
													<input name="issue_area" type="hidden" value="1">
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
												<div class="form-inline">
													<label for="">Issue Impact</label>
													<select name="issue_impact" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
														<option value="">.... Select impact ....</option>
														<?php
														while ($row_risk_impact = $query_risk_impact->fetch()) {
														?>
															<font color="black">
																<option value="<?php echo $row_risk_impact['id'] ?>"><?php echo $row_risk_impact['description'] ?></option>
															</font>
														<?php
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
												<div class="form-inline">
													<label for="">Issue Priority</label>
													<select name="issue_priority" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
														<option value="" selected="selected" class="selection">... Select Issue Priority ...</option>
														<option value="1" class="selection">High</option>
														<option value="2" class="selection">Medium</option>
														<option value="3" class="selection">Low</option>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
												<div class="form-inline">
													<label for="">Risk Category</label>
													<select name="risk_category" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
														<option value="">.... Select Risk Category ....</option>
														<?php
														while ($row_risk_categories = $query_risk_categories->fetch()) {
														?>
															<font color="black">
																<option value="<?php echo $row_risk_categories['catid'] ?>"><?php echo $row_risk_categories['category'] ?></option>
															</font>
														<?php
														}
														?>
													</select>
												</div>
											</div>

											<div id="issue_type">
											</div>
										</div>
										<!-- Task Checklist Questions -->
									</fieldset>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-paperclip" aria-hidden="true"></i> Attachments
										</legend>
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered">
														<thead>
															<tr>
																<th style="width:2%">#</th>
																<th style="width:40%">Attachment</th>
																<th style="width:58%">Attachment Purpose</th>
																<th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
														</thead>
														<tbody id="attachments_table">
															<tr>
																<td>1</td>
																<td>
																	<input type="file" name="monitorattachment[]" id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																</td>
																<td>
																	<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																</td>
																<td></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>
									<div class="modal-footer">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
											<input type="hidden" name="store_checklists" id="store_checklists" value="store_checklists">
											<input type="hidden" name="projid" id="issue_projid">
											<input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
											<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
										</div>
									</div> <!-- /modal-footer -->
								</form>
							</div>
							<div id="menu1" class="tab-pane fade">
								<div id="previous_issues">
									<h4 class="text-danger">No records found!!</h4>
								</div>
								<div class="modal-footer">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
										<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
									</div>
								</div>
							</div>
						</div>

					</div> <!-- /modal-footer -->

				</div> <!-- /modal-content -->
			</div> <!-- /modal-dailog -->
		</div>
		<!-- end issues modal  -->
<?php
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/monitoring/issues.js"></script>
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/programofWorks/index.js"></script>
<script src="assets/js/master/index.js"></script>