<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: projects");
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];

$original_projid = $_GET['proj'];
require('includes/head.php');

if ($permission) {
	try {
		$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_projdetails->execute(array(":projid" => $projid));
		$row_projdetails = $query_projdetails->fetch();
		$projname = $row_projdetails['projname'];
		$projcategory = $row_projdetails['projcategory'];
		$percent2 = number_format(calculate_project_progress($projid, $projcategory), 2);


		$query_issues = $db->prepare("SELECT c.catid, c.category, i.id, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE projid = :projid");
		$query_issues->execute(array(":projid" => $projid));
		$count_issues = $query_issues->rowCount();

		function get_inspection_status($status_id)
		{
			global $db;
			$sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE statuskey = :status_id");
			$sql->execute(array(":status_id" => $status_id));
			$row = $sql->fetch();
			$rows_count = $sql->rowCount();
			return ($rows_count > 0) ? $row['status'] : "";
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
?>
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<link rel="stylesheet" href="css/highcharts.css">
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?php echo $pageTitle ?>

					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Activities Monitoring" class="btn btn-warning pull-right" onclick="location.href='project-output-monitoring-checklist.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
								<a href="myprojectdash.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Dashboard</a>
								<a href="myprojectmilestones.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Performance</a>
								<a href="myproject-key-stakeholders.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Issues</a>
								<a href="myprojectfiles.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?= $percent2 ?>%
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr class="bg-orange">
											<th style="width:3%">#</th>
											<th style="width:35%">Issue</th>
											<th style="width:10%">Category</th>
											<th style="width:10%">Issue Area</th>
											<th style="width:10%">Priority</th>
											<th style="width:10%">Impact</th>
											<th style="width:10%">Resolution</th>
											<th style="width:12%">Date Recorded</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($count_issues > 0) {
											$nm = 0;

											while ($row_issues = $query_issues->fetch()) {
												$nm = $nm + 1;
												$issueid = $row_issues["id"];
												$issueareaid = $row_issues["issue_area"];
												$category = $row_issues["category"];
												$issue = $row_issues["issue_description"];
												$impactid = $row_issues["issue_impact"];
												$priorityid = $row_issues["issue_priority"];
												$status_id = $row_issues["status"];
												$issuedate = $row_issues["date_created"];
												$status = get_inspection_status($status_id);

												$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE id=:impactid");
												$query_risk_impact->execute(array(":impactid" => $impactid));
												$row_risk_impact = $query_risk_impact->fetch();
												$impact = $row_risk_impact["description"];

												if ($priorityid == 1) {
													$priority = "High";
													$priorityclass = 'bg-red';
												} elseif ($priorityid == 2) {
													$priority = "Medium";
													$priorityclass = 'bg-blue';
												} else {
													$priority = "Low";
													$priorityclass = 'bg-green';
												}

												$query_issue_area =  $db->prepare("SELECT * FROM tbl_issue_areas WHERE id=:issueareaid");
												$query_issue_area->execute(array(":issueareaid" => $issueareaid));
												$row_issue_area = $query_issue_area->fetch();
												$issue_area = $row_issue_area["issue_area"];

												$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=1 and active=1");
												$query_timeline->execute();
												$row_timeline = $query_timeline->fetch();
												$timelineid = $row_timeline["id"];
												$time = $row_timeline["time"];
												$units = $row_timeline["units"];
												$stgstatus = $row_timeline["status"];

												$duedate = strtotime($issuedate . "+ " . $time . " " . $units);
												$actionnduedate = date("d M Y", $duedate);

												$current_date = date("Y-m-d");
												$actduedate = date("Y-m-d", $duedate);

												$styled = 'style="color:blue"';
												if ($status_id == 1 && $actduedate < $current_date) {
													$actionstatus = "Behind Schedule";
													$styled = 'style="color:red"';
												}
										?>
												<tr style="background-color:#fff">
													<td align="center"><?php echo $nm; ?></td>
													<td><?php echo $issue; ?></td>
													<td><?php echo $category; ?></td>
													<td><?php echo $issue_area; ?></td>
													<td><?php echo $impact; ?></td>
													<td><span class="badge <?= $priorityclass; ?>"><?php echo $priority; ?></span></td>
													<?php if ($status_id == 0) { ?>
														<td <?= $styled ?>><?php echo $status; ?></td>
													<?php } elseif ($status_id == 7) { ?>
														<td>
															<a data-toggle="modal" data-target="#closedIssueDetailsModal" data-backdrop="static" data-keyboard="false" onclick="closed_project_issue(<?= $issueid ?>)" style="color:green">
																<strong><?php echo $status; ?></strong>
															</a>
														</td>
													<?php } else { ?>
														<td>
															<a data-toggle="modal" data-target="#issueClosureModal" data-backdrop="static" data-keyboard="false" onclick="close_project_issue(<?= $issueid ?>, <?= $projid ?>)" <?= $styled ?>>
																<strong><?php echo $status; ?></strong>
															</a>
														</td>
													<?php } ?>
													<td><?php echo date("d M Y", strtotime($issuedate)); ?></td>
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
		</div>
	</section>
	<!-- end body  -->


	<!-- start issues modal  -->
	<div class="modal fade" id="issueClosureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> PROJECT ISSUE</span></h3>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item list-group-item list-group-item-action" style="height: auto"><strong>Project Name: <span id="project_name"></span></strong></li>
					</ul>
					<div id="issue_details">
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<form class="form-horizontal" id="add_issue_closure" action="" method="POST">
							<?= csrf_token_html(); ?>
							<fieldset class="scheduler-border" id="specification_issues">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Issue Closure
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Remarks</label>
											<textarea name="closing_remarks" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:100%" placeholder="Describe the issue" required></textarea>
										</div>
									</div>
								</div>
								<!-- Task Checklist Questions -->
							</fieldset>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<input type="hidden" name="close_issue" id="close_issue" value="close_issue">
						<input type="hidden" name="projid" id="projid">
						<input type="hidden" name="issueid" id="issueid">
						<input type="hidden" name="user_name" value="<?= $user_name ?>">
						<input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="add_issue_closure-form-submit" value="Close Issue" />
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
					</div>
				</div> <!-- /modal-footer -->
				</form>

			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>


	<!-- start issues modal  -->
	<div class="modal fade" id="closedIssueDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-green">
					<h3 class="modal-title" style="color:#fff" align="center"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> CLOSED PROJECT ISSUE</span></h3>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item list-group-item list-group-item-action" style="height: auto"><strong>Project Name: <span id="closed_issue_project_name"></span></strong></li>
					</ul>
					<div id="closed_issue_details">
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
					</div>
				</div> <!-- /modal-footer -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

	<script src="assets/js/monitoring/issues.js"></script>

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>