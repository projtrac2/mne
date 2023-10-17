<?php
require('includes/head.php');
if ($permission) {
	try {
		$query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
		$query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
		$totalRows_rsProjects = $query_rsProjects->rowCount();

		function check_if_edit($projid)
		{
			global $db;
			$query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type > 2");
			$query_rs_output_cost_plan->execute(array(":projid" => $projid));
			$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
			$results[] = $totalRows_rs_output_cost_plan > 0 ? true : false;

			$query_rsOutcome =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid =:projid");
			$query_rsOutcome->execute(array(':projid' => $projid));
			$totalRows_rsOutcome = $query_rsOutcome->rowCount();
			$results[] = $totalRows_rsOutcome > 0 ? true : false;

			$query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid");
			$query_rsDetails->execute(array(":projid" => $projid));
			$totalRows_rsDetails = $query_rsDetails->rowCount();
			$results[] = $totalRows_rsDetails > 0 ? true : false;

			$query_impact_details = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid=:projid");
			$query_impact_details->execute(array(":projid" => $projid));
			$totalRows_impact_details = $query_impact_details->rowCount();
			$results[] = $totalRows_impact_details > 0 ? true : false;

			$query_rsProject_Members =  $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND team_type <= 3");
			$query_rsProject_Members->execute(array(":projid" => $projid));
			$totalRows_rsProject_Members = $query_rsProject_Members->rowCount();
			$results[] = $totalRows_rsProject_Members > 0 ? true : false;

			return in_array(true, $results) ? true : false;
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
											<th style="width:60%">Project </th>
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
												$hashproc = base64_encode("encodeprocprj{$projid}");
												$implementation = $row_rsProjects['projcategory'];
												$sub_stage = $row_rsProjects['proj_substage'];
												$project_department = $row_rsProjects['projsector'];
												$project_section = $row_rsProjects['projdept'];
												$project_directorate = $row_rsProjects['directorate'];

												$edit = check_if_edit($projid);

												$timeline_details =  get_timeline_details($workflow_stage, $sub_stage, $today);
												$hashproc = base64_encode("projid04{$projid}");
												$assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
												$assign_responsible = (in_array("assign_data_entry_responsible", $page_actions) && $sub_stage == 0) || (in_array("assign_approval_responsible", $page_actions) && $sub_stage == 2) ? true : false;

												$counter++;
												$details = "{
														get_edit_details: 'details',
														projid:$projid,
														workflow_stage:$workflow_stage,
														sub_stage:$sub_stage,
														project_directorate:$project_directorate,
													}";

												$activity_status = $activity = '';
												$activity = $edit ? "Edit" : "Add";
												if ($sub_stage == 0) {
													$activity_status = "Pending";
												} else if ($sub_stage == 1) {
													$activity_status = "Assigned";
												} else if ($sub_stage > 1) {
													$activity_status = "Pending Approval";
													$activity = "Approve";
												}
										?>
												<tr>
													<td align="center"><?= $counter ?></td>
													<td><?php echo $row_rsProjects['projcode'] ?></td>
													<td><?php echo $row_rsProjects['projname'] ?></td>
													<td><?php echo date('Y M d') ?></td>
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
																if ($assign_responsible) {
																?>
																	<li>
																		<a type="button" data-toggle="modal" data-target="#assign_modal" id="assignModalBtn" onclick="get_responsible_options(<?= $details ?>)">
																			<i class="fa fa-file-text"></i> Assign
																		</a>
																	</li>
																<?php
																}
																if ($assigned_responsible) {
																?>
																	<li>
																		<a type="button" href="add-project-mne-plan?proj=<?= $hashproc ?>" id="addFormModalBtn">
																			<i class="fa fa-plus-square-o"></i> <?= $activity ?> M&E Plan
																		</a>
																	</li>
																<?php
																}
																?>
															</ul>
														</div>
													</td>
												</tr>
										<?php

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
							<input type="hidden" name="assign_responsible" id="assign_responsible" value="new">
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
<script src="assets/js/master/index.js"></script>