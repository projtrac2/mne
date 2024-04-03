<?php 
require('includes/head.php');
if ($permission) {
	try {
		$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
		$projid_array = explode("projrisk047", $decode_projid);
		$projid = $projid_array[1];
		
		$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_projdetails->execute(array(":projid" => $projid));
		$row_projdetails = $query_projdetails->fetch();
		$projname = $row_projdetails['projname'];
		$projcategory = $row_projdetails['projcategory'];
		$percent2 = $row_projdetails['progress'];

		$query_issues = $db->prepare("SELECT i.id, i.issue_area, i.issue_priority, i.issue_impact, category, issue_description, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate FROM tbl_projissues i LEFT JOIN tbl_projrisk_categories c ON c.catid=i.risk_category WHERE i.projid=:projid");
		$query_issues->execute(array(":projid" => $projid));
		$count_issues = $query_issues->rowCount();
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
	?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader" style="color:white">
					<span class="text-warning"> <?= $icon ?> </span>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right; padding-right:5px">
						<input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='projects-issues'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h4>
							<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?=$percent2?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent2?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?=$percent2?>%
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
										<tr id="colrow">
											<th style="width:4%">#</th>
											<th style="width:40%">Issue</th>
											<th style="width:10%">Issue Area</th>
											<th style="width:10%">Priority</th>
											<th style="width:13%">Date Recorded</th>
											<th style="width:11%">Resolution</th>
											<th style="width:12%">Other Details</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($count_issues > 0) {
											$nm = 0;
											$priority = "";
											while ($row_issues = $query_issues->fetch()) {
												$nm = $nm + 1;
												$issueid = $row_issues['id'];
												$risk_category = $row_issues['category'];
												$issue_description = $row_issues['issue_description'];
												$issue_priority = $row_issues['issue_priority'];
												$issue_areaid = $row_issues['issue_area'];
												$issue_impact = $row_issues['issue_impact'];
												$monitorid = $row_issues['monitor'];
												$recommendation = $row_issues['recommendation'];
												$issuedate = $row_issues['issuedate'];
												$issuestatusis = $row_issues["status"];

												$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=1 and active=1");
												$query_timeline->execute();
												$row_timeline = $query_timeline->fetch();
												$timelineid = $row_timeline["id"];
												$time = $row_timeline["time"];
												$units = $row_timeline["units"];
												
												if($issue_priority == 1){
													$priority = "High";
												}elseif($issue_priority == 2){
													$priority = "Medium";
												}elseif($issue_priority == 3){
													$priority = "Low";
												}
			
												$query_issue_area =  $db->prepare("SELECT * FROM tbl_issue_areas WHERE id=:issue_areaid");
												$query_issue_area->execute(array(":issue_areaid" => $issue_areaid));		
												$rows_issue_area = $query_issue_area->fetch();
												$issue_area = $rows_issue_area["issue_area"];

												$duedate = strtotime($issuedate . "+ " . $time . " " . $units);
												$actionnduedate = date("d M Y", $duedate);

												$current_date = date("Y-m-d");
												$actduedate = date("Y-m-d", $duedate);

												$styled = 'style="color:blue"';
												
												if ($issuestatusis == 0) {
													$issuestatus = "Pending Action";
												} elseif ($issuestatusis == 1) {
													$issuestatus = "Ignore the Issue and Continue";
												} elseif ($issuestatusis == 2) {
													$issuestatus = "Project Put On Hold";
												} elseif ($issuestatusis == 3) {
													$issuestatus = "Project Restored";
												} elseif ($issuestatusis == 4) {
													$issuestatus = "Request Approved";
												} elseif ($issuestatusis == 5) {
													$issuestatus = "Project Restored & Request Approved";
												} elseif ($issuestatusis == 2) {
													$issuestatus = "Project Cancelled";
												}elseif ($issuestatusis == 7) {
													$issuestatus = "Issue Closed";
													$styled = 'style="color:green"';
												}

												$actiondate = $actionnduedate;
												$query_owner = $db->prepare("SELECT tt.title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid='$monitorid'");
												$query_owner->execute();
												$row_owner = $query_owner->fetch();
												$monitor = $row_owner["title"] . '.' . $row_owner["fullname"];
												?>
												<tr style="background-color:#fff">
													<td align="center"><?php echo $nm; ?></td>
													<td><?php echo $issue_description; ?></td>
													<td><?php echo $issue_area; ?></td>
													<td><?php echo $priority; ?></td>
													<td class="text-primary"><span data-toggle="tooltip" data-placement="bottom" title="Recorded By: <?= $monitor ?>"><?php echo date("d M Y", strtotime($issuedate)); ?></span></td>
													<td <?= $styled ?>><?= $issuestatus ?></td>
													<td>
														<div align="center" class="btn-group">
															<a type="button" data-toggle="modal" data-target="#issuemoreinfo" id="moreModalBtn" onclick="issuemoreinfo(<?= $issueid ?>)" class="btn btn-default"><i class="fa fa-info-circle fa-2x text-success" aria-hidden="true"></i> More Info</a>
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
		</div>
	</section>
	<!-- end body  -->

	<!-- Modal Issue Analysis -->
	<div class="modal fade" id="issuemoreinfo" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Issue More Info</font>
					</h3>
				</div>
				<div class="modal-body">
					<div id="moreinfo">
					</div>
				</div>

				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Analysis -->

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script src="assets/js/issues/index.js"></script>