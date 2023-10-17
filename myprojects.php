<?php
require('includes/head.php');
include_once('projects-functions.php');

if ($permission) {
	try {
		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projstage = 9 AND (projstatus=4 OR projstatus=11) ORDER BY projid DESC");
		$query_rsProjects->execute();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
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
					<?= $pageTitle ?>
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
										<tr id="colrow">
											<th width="3%"><strong>#</strong></th>
											<th width="30%"><strong>Project</strong></th>
											<th width="11%"><strong>Status & Progress</strong></th>
											<th width="8%"><strong>Issues</strong></th>
											<th width="16%"><strong>Location</strong></th>
											<th width="10%"><strong>Financial Year</strong></th>
											<th width="10%"><strong>More Details</strong></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($totalRows_rsProjects > 0) {
											$counter = 0;
											while ($row_rsMyP = $query_rsProjects->fetch()) {
												$projid =  $row_rsMyP['projid'];
												$projstatus =  $row_rsMyP['projstatus'];
												$projname =  $row_rsMyP['projname'];
												$projcode =  $row_rsMyP['projcode'];
												$projcost =  $row_rsMyP['projcost'];
												$projduration =  $row_rsMyP['projduration'];
												$project_start_date =  $row_rsMyP['projstartdate'];
												$project_end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . $projduration . ' days'));
												$projcontractor =  $row_rsMyP['projcategory'];
												$mystates = explode(",", $row_rsMyP['projlga']);
												$fscyear = $row_rsMyP['projfscyear'];
												$percent2 = $row_rsMyP['progress'];
												$percent2 = $row_rsMyP['progress'];
												$workflow_stage = $row_rsMyP['projstage'];

												$query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
												$query_rsTask_Start_Dates->execute(array(':projid' => $projid));
												$rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
												$total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

												if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
													$project_start_date =  $rows_rsTask_Start_Dates['start_date'];
													$project_end_date =  $rows_rsTask_Start_Dates['end_date'];
												} else {
													if ($projcontractor == 2) {
														$query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
														$query_rsTender_start_Date->execute(array(':projid' => $projid));
														$rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
														$total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
														if ($total_rsTender_start_Date > 0) {
															$project_start_date =  $rows_rsTender_start_Date['startdate'];
															$project_end_date =  $rows_rsTender_start_Date['enddate'];
														}
													}
												}

												$currentdate = date("Y-m-d");
												$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
												$query_Projstatus->execute(array(":projstatus" => $projstatus));
												$row_Projstatus = $query_Projstatus->fetch();
												$total_Projstatus = $query_Projstatus->rowCount();
												$status = "";
												if ($total_Projstatus > 0) {
													$status_name = $row_Projstatus['statusname'];
													$status_class = $row_Projstatus['class_name'];
													$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
												}

												$project_progress = '
												<div class="progress" style="height:20px; font-size:10px; color:black">
													<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
														' . $percent2 . '%
													</div>
												</div>';
												if ($percent2 == 100) {
													$project_progress = '
													<div class="progress" style="height:20px; font-size:10px; color:black">
														<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
														' . $percent2 . '%
														</div>
													</div>';
												}

												$locations = [];
												foreach ($mystates as $mystate) {
													$query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
													$query_rsLoc->execute(array(":mystate" => $mystate));
													$row_rsLoc = $query_rsLoc->fetch();
													$totalRows_rsLoc = $query_rsLoc->rowCount();
													$locations[] = $row_rsLoc['state'];
												}

												$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:yr");
												$query_FY->execute(array(":yr" => $fscyear));
												$row_FY = $query_FY->fetch();
												$totalRows_rsFY = $query_FY->rowCount();
												$financial_year = $totalRows_rsFY > 0 ? $row_FY['year'] : "";

												$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND origin >= 1 AND origin <= 4");
												$query_rsProjissues->execute(array(":projid" => $projid));
												$totalRows_rsProjissues = $query_rsProjissues->rowCount();


												$query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
												$query_dates->execute(array(":projid" => $projid));
												$row_dates = $query_dates->fetch();

												$projcontractor = "In House";
												if ($row_dates['projcategory'] == 2) {
													$contractor = $row_dates['contractor_name'];
													$projcontractor_id = $row_dates['contrid'];
													$projcontractor_ids = base64_encode("projid54321{$projcontractor_id}");
													$projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_ids . '" style="color:#4CAF50">' . $contractor . '</a>';
												}
												$specialized_stage = 1;
												$general_stage = 2;

												$member = check_if_member($projid, $workflow_stage, $general_stage, $specialized_stage, 1);
												if ($member) {
													$counter++;
													$projectid = base64_encode("projid54321{$projid}");
										?>
													<tr>
														<td><?= $counter ?></td>
														<td style="padding-right:0px; padding-left:0px; padding-top:0px">
															<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
																<a href="myprojectdash.php?projid=<?php echo $projectid; ?>" style="color:#FFF; font-weight:bold"><?= $projname ?></a>
															</div>
															<div style="padding:5px; font-size:11px">
																<b>Project Code:</b> <?= $projcode ?>
																<br />
																<b>Project Cost:</b> Ksh.<?php $projcost ?><br />
																<b>Start Date:</b> <?php echo $project_start_date; ?><br />
																<b>End Date: </b> <?php echo $project_end_date; ?><br />
																<b>Implementer: </b>
																<font color="#4CAF50">
																	<?= $projcontractor; ?>
																</font>
															</div>
														</td>
														<td><?= $status . $project_progress ?></td>
														<td align="center">
															<a href="projectissueslist.php?proj=<?= $projid ?>" style="color:#FF5722"><?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i> <font size="5px">' . $totalRows_rsProjissues . '</font>'; ?></a>
														</td>
														<td>
															<a href="view-project-maps.php?projid=<?= base64_encode($projid); ?>" id="" class="" style="color:indigo"><?= implode(",", $locations); ?></a>
														</td>
														<td><?= $financial_year ?></td>
														<td style="padding-right:0px; padding-left:0px">
															<a type="button" href="view-project-gallery.php?projid=<?= $projectid ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																Gallery
															</a>
															<?php
															if ($project_start_date < $currentdate && ($projstatus == 4 || $projstatus == 5 || $projstatus == 11)) {
															?>
																<a type="button" href="myprojectdash.php?projid=<?php echo $projectid; ?>" class="btn btn-success btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">Details</a>
															<?php
															}
															?>
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
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script type="text/javascript">
	function GetScorecard(projid) {
		var prog = $("#scardprog").val();
		$.ajax({
			type: 'post',
			url: 'getscorecard',
			data: {
				prjid: projid,
				scprog: prog
			},
			success: function(data) {
				$('#formcontent').html(data);
				$("#myModal").modal({
					backdrop: "static"
				});
			}
		});
	}


	function GetProjIssues(projid) {
		$.ajax({
			type: 'post',
			url: 'getprojissues',
			data: {
				prjid: projid
			},
			success: function(data) {
				$('#detailscontent').html(data);
				$("#projIssues").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>
<script src="projinfolive.js"></script>