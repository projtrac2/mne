<?php
require('includes/head.php');
// 0 pending frwquency  1) Pending assignments 2) pending data entry 3) Pending Approval 4 (Assigned ) Pending Approval

if ($permission) {
	try {
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


		function view_project($department, $section, $directorate)
		{
			global $db, $user_department, $user_section, $user_directorate, $user_designation;
			$grant_access = false;
			if ($user_designation == 1) {
				$grant_access = true;
			} else {
				if ($user_designation >= 5 && $user_designation <= 7) {
					if ($user_department == $department) {
						if ($section == $user_section) {
							if ($directorate == $user_directorate) {
								$grant_access = true;
							} else {
								$grant_access = ($user_designation == 6) ? true : false;
							}
						} else {
							$grant_access = ($user_designation == 5) ? true : false;
						}
					}
				}
			}

			return $grant_access;
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
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
												$implementation = $row_rsProjects['projcategory'];
												$sub_stage = $row_rsProjects['proj_substage'];
												$project_department = $row_rsProjects['projsector'];
												$project_section = $row_rsProjects['projdept'];
												$project_directorate = $row_rsProjects['directorate'];
												$monitoring_frequency = ($row_rsProjects['monitoring_frequency'] != '') ? $row_rsProjects['monitoring_frequency'] : 0;
												$activity_monitoring_frequency = ($row_rsProjects['activity_monitoring_frequency']  != '') ? $row_rsProjects['activity_monitoring_frequency'] : 0;
												$start_date = date('Y-m-d');

												$query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid = :projid");
												$query_rsPlan->execute(array(":projid" => $projid));
												$totalRows_plan = $query_rsPlan->rowCount();


												$timeline_details =  get_timeline_details($workflow_stage, $sub_stage, $start_date);
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
													$due_date = get_master_data_due_date($projid, $workflow_stage, $sub_stage);

													$activity = "Add";
													if ($totalRows_plan > 0) {
														$activity = $sub_stage == 3 ? "Approve"  : "Edit";
													}

													$activity_status = "Pending Approval";
													if ($sub_stage < 3) {
														$activity_status = "Pending Frequency";
														if ($today > $due_date) {
															$activity_status = "Behind Schedule";
														} else {
															if ($sub_stage == 1) {
																$activity_status = "Pending ";
															} else if ($sub_stage == 2) {
																$activity_status = "Assigned";
															}
														}
													}

													$responsible = daily_team($projid, 9, 2, $sub_stage);
													$project_category = $implementation == 1 ? "In-House" : "Contractor";

													$program_of_works  = false;
													if ($sub_stage >= 1) {
														if ($implementation == 1 && $sub_stage <= 2) {
															$program_of_works = true;
														} else {
															if ($sub_stage >= 3) {
																$program_of_works = true;
															}
														}
													}


										?>
													<tr>
														<td align="center"><?= $counter ?></td>
														<td><?= $row_rsProjects['projcode'] ?></td>
														<td><?= $row_rsProjects['projname'] ?></td>
														<td><?= $project_category ?></td>
														<td><?= date('Y M d', strtotime($due_date)) ?></td>
														<td><label class='label label-success'><?= $activity_status; ?></label></td>
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
																		if ($totalRows_plan == 0) {
																	?>
																			<li>
																				<a type="button" data-toggle="modal" data-target="#add_project_frequency_modal" id="add_project_frequency_ModalBtn" onclick="add_project_frequency_data(<?= $projid ?>, <?= $activity_monitoring_frequency ?>, <?= $monitoring_frequency ?>)">
																					<i class="fa fa-file-text"></i> <?= $activity_monitoring_frequency == '' ? "Add" : "Edit" ?> Frequency
																				</a>
																			</li>
																		<?php
																		}

																		if ($program_of_works) {
																		?>
																			<li>
																				<a type="button" data-toggle="modal" data-target="#assign_modal" id="assignModalBtn" onclick="get_responsible_options(<?= $details ?>)">
																					<i class="fa fa-users"></i> <?= !$assigned ? "Assign" : "Reassign" ?>
																				</a>
																			</li>
																			<li>
																				<a type="button" href="add-work-program.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
																					<i class="fa fa-plus-square-o"></i> <?= $activity ?> Program of Works
																				</a>
																			</li>
																	<?php
																		}
																	}
																	?>
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
	<div class="modal fade" id="add_project_frequency_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Project Frequency</h4>
				</div>
				<form class="form-horizontal" id="add_project_frequency_data" action="" method="POST">
					<div class="modal-body" style="max-height:450px; overflow:auto;">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Activity Target breakdown Frequency *: </label>
							<div class="form-line">
								<select name="activity_monitoring_frequency" onchange="get_monitoring_frequency()" id="activity_monitoring_frequency" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
									<option value="">.... Select from list ....</option>
									<?php
									$query_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE status=1 ");
									$query_frequency->execute();
									$totalRows_frequency = $query_frequency->rowCount();
									$input = '';
									if ($totalRows_frequency > 0) {
										while ($row_frequency = $query_frequency->fetch()) {
											$input .= '<option value="' . $row_frequency['fqid'] . '" >' . $row_frequency['frequency'] . ' </option>';
										}
									}
									echo $input;
									?>
								</select>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Monitoring Frequency *: </label>
							<div class="form-line">
								<select name="monitoring_frequency" id="monitoring_frequency" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
									<option value="">.... Select from list ....</option>
								</select>
							</div>
						</div>
					</div> <!-- /modal-body -->
					<div class="modal-footer frequencyItemFooter">
						<div class="col-md-12 text-center">
							<input type="hidden" name="projid" id="projid" value="">
							<input type="hidden" name="store_project_frequency" id="store_project_frequency" value="new">
							<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-frequency" value="Submit" />
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						</div>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div>
			<!-- /modal-content -->
		</div>
	</div>
	<!-- End Item more -->

	<!-- Start Modal Item approve -->
	<div class="modal fade" id="assign_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Assign Project</h4>
				</div>
				<form class="form-horizontal" id="assign_responsible" action="" method="POST">
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
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/programofWorks/index.js"></script>
<script src="assets/js/master/index.js"></script>