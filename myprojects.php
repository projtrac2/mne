<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "My Projects";

if ($permission) {
	try {
		$query_rsTP = $db->prepare("SELECT COUNT(projname) FROM tbl_projects");
		$query_rsTP->execute();
		$row_rsTP = $query_rsTP->fetch();

		$query_rsTPList = $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects GROUP BY projname");
		$query_rsTPList->execute();
		$row_rsTPList = $query_rsTPList->fetch();

		/* $query_rsTPM = $db->prepare("SELECT COUNT(projname) FROM tbl_monitoring");
		$query_rsTPM->execute();		
		$row_rsTPM = $query_rsTPM->fetch(); */

		$query_rsComm = $db->prepare("SELECT DISTINCT projcommunity FROM tbl_projects WHERE tbl_projects.deleted='0' ORDER BY projcommunity ASC");
		$query_rsComm->execute();
		$row_rsComm = $query_rsComm->fetch();
		$totalRows_rsComm = $query_rsComm->rowCount();

		$query_rsState = $db->prepare("SELECT DISTINCT projlga FROM tbl_projects WHERE tbl_projects.deleted='0'");
		$query_rsState->execute();
		$row_rsState = $query_rsState->fetch();
		$totalRows_rsState = $query_rsState->rowCount();

		$query_srcSector = $db->prepare("SELECT DISTINCT projsector FROM tbl_programs");
		$query_srcSector->execute();
		//$row_srcSector = $query_srcSector->fetch();

		$query_srcDept = $db->prepare("SELECT DISTINCT projdept FROM tbl_programs");
		$query_srcDept->execute();

		$query_rsFSYear = $db->prepare("SELECT * FROM tbl_fiscal_year");
		$query_rsFSYear->execute();
		//$row_rsFSYear = $query_rsFSYear->fetch();

		$query_rsStatus = $db->prepare("SELECT statusname FROM tbl_status");
		$query_rsStatus->execute();
		//$row_rsStatus = $query_rsStatus->fetch();

		$currentdatetime = date("Y-m-d H:i:s");
		$currentdate = date("Y-m-d");

		$query_rsMyP = $db->prepare("SELECT p.* FROM tbl_projects p INNER JOIN tbl_fiscal_year y ON y.id = p.projfscyear WHERE p.deleted='0' AND p.user_name = '$user_name' AND p.projstage = 10 ORDER BY p.projid ASC");

		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$rows_count = $query_rsMyP->rowCount();

		$query_rsUsers = $db->prepare("SELECT * FROM tbl_projteam2 t inner join tbl_users u ON u.pt_id=t.ptid WHERE username = '$user_name'");
		$query_rsUsers->execute();
		$row_rsUsers = $query_rsUsers->fetch();
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>

	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
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
					<?php
					if (isset($_GET["msg"]) && $_GET["type"] == "fail") {
					?>
						<div class="alert alert-warning">
							<strong>Warning!</strong> <?php echo $_GET["msg"]; ?>
						</div>
					<?php
					} elseif (isset($_GET["msg"]) && $_GET["type"] == "success") {
					?>
						<div class="alert alert-success">
							<strong>Success!</strong> <?php echo $_GET["msg"]; ?>
						</div>
					<?php
					}
					?>
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
											<th width="12%"><strong>Activities</strong></th>
											<th width="10%"><strong>More Details</strong></th>
										</tr>
									</thead>
									<tbody>
										<?php
										try {
											if ($rows_count > 0) {
												$sn = 0;
												do {
													$sn = $sn + 1;
													$projectID =  $row_rsMyP['projid'];
													$currentStatus =  $row_rsMyP['projstatus'];
													$projcat = $row_rsMyP["projcategory"];
													$currentdate = date("Y-m-d");
													$statusdate = date("Y-m-d H:i:s");

													$query_milestones = $db->prepare("SELECT COUNT(CASE WHEN m.status = 5 THEN 1 END) AS `Completed`, COUNT(CASE WHEN m.status = 11 THEN 1 END) AS `Behind Schedule`, COUNT(CASE WHEN m.status = 4 THEN 1 END) AS `In Progress`, COUNT(CASE WHEN m.status = 3 THEN 1 END) AS `Pending`, COUNT(CASE WHEN m.status = 2 THEN 1 END) AS `Cancelled`, COUNT(CASE WHEN m.status = 6 THEN 1 END) AS `On Hold`, COUNT(m.status) AS 'Total Status' FROM tbl_milestone m WHERE m.projid = :projid");
													$query_milestones->execute(array(":projid" => $projectID));
													$row_milestones = $query_milestones->fetch();
													$rows_milestones = $query_milestones->rowCount();

													$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND origin='monitoring'");
													$query_rsProjissues->execute(array(":projid" => $projectID));
													$totalRows_rsProjissues = $query_rsProjissues->rowCount();

													if ($projcat == '2') {
														$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
														$query_rsContractDates->execute(array(":projid" => $projectID));
														$row_rsContractDates = $query_rsContractDates->fetch();
														$totalRows_rsContractDates = $query_rsContractDates->rowCount();

														if ($totalRows_rsContractDates > 0) {
															$pjstdate = date("d M Y", strtotime($row_rsContractDates["startdate"]));
															$pjendate = date("d M Y", strtotime($row_rsContractDates["enddate"]));
														} else {
															$pjstdate = date("d M Y", strtotime($row_rsMyP["projstartdate"]));
															$pjendate = date("d M Y", strtotime($row_rsMyP["projenddate"]));
														}
														$projcost = number_format($row_rsContractDates['tenderamount'], 2);
													} else {
														$pjstdate = date("d M Y", strtotime($row_rsMyP["projstartdate"]));
														$pjendate = date("d M Y", strtotime($row_rsMyP["projenddate"]));
														$projcost = number_format($row_rsMyP['projcost'], 2);
													}

													$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
													$query_rsMlsProg->execute(array(":projid" => $projectID));
													$row_rsMlsProg = $query_rsMlsProg->fetch();

													$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

													$percent2 = round($prjprogress, 2);

													$query_rsProjDetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid =:projid");
													$query_rsProjDetails->execute(array(":projid" => $projectID));
													$row_rsProjDetails = $query_rsProjDetails->fetch();

													$pstartdate = $pjstdate;
													$penddate = $pjendate;
													$pjstatus = $row_rsProjDetails["projstatus"];
													$pjtype = $row_rsProjDetails["projtype"];
													$projcode = $row_rsProjDetails["projcode"];
													$statususer = $row_rsProjDetails["user_name"];
													$myprjname = $row_rsProjDetails["projname"];
													$myWardID = $row_rsMyP['projlga'];
													$mySubCountyID = $row_rsMyP['projcommunity'];


													$myLoc = [];
													$mystates = explode(",", $row_rsMyP['projstate']);
													foreach ($mystates as $mystate) {
														$query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
														$query_rsLoc->execute(array(":mystate" => $mystate));
														$row_rsLoc = $query_rsLoc->fetch();
														$totalRows_rsLoc = $query_rsLoc->rowCount();
														$myLoc[] = $row_rsLoc['state'];
													}

													$myWard = [];
													$myWardIDs = explode(",", $myWardID);
													foreach ($myWardIDs as $WardID) {
														$query_rsWard = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:ward");
														$query_rsWard->execute(array(":ward" => $WardID));
														$row_rsWard = $query_rsWard->fetch();
														$totalRows_rsWard = $query_rsWard->rowCount();
														$myWard[] = $row_rsWard['state'];
													}

													$fscyear = $row_rsMyP['projfscyear'];
													$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:yr");
													$query_FY->execute(array(":yr" => $fscyear));
													$row_FY = $query_FY->fetch();
													$totalRows_rsFY = $query_FY->rowCount();

													$mySubCounty = [];
													$SubCounties = explode(",", $mySubCountyID);
													foreach ($SubCounties as $SubCounty) {
														$query_SC = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:level1");
														$query_SC->execute(array(":level1" => $SubCounty));
														$row_SC = $query_SC->fetch();
														$totalRows_rsSC = $query_SC->rowCount();
														$mySubCounty[] = $row_SC['state'];
													}

													if (count($SubCounties) == 1) {
														$level1 = implode(",", $mySubCounty) . ' ' . $level1label;
													} else {
														$level1 = implode(",", $mySubCounty) . ' ' . $level1labelplural;
													}

													if (count($myWardIDs) == 1) {
														$level2 = implode(",", $myWard) . ' ' . $level2label;
													} else {
														$level2 = implode(",", $myWard) . ' ' . $level2labelplural;
													}

													if (count($mystates) == 1) {
														$level3 = implode(",", $myLoc) . ' ' . $level3label;
													} else {
														$level3 = implode(",", $myLoc) . ' ' . $level3labelplural;
													}

													$myLocation = $level1 . '; ' . $level2 . '; ' . $level3;
													$query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
													$query_dates->execute(array(":projid" => $projectID));
													$row_dates = $query_dates->fetch();

													$now = time();
													$prjsdate = strtotime($row_dates['projstartdate']);
													$prjedate = strtotime($row_dates['projenddate']);
													$prjdatediff = $prjedate - $prjsdate;
													$prjnowdiff = $now - $prjsdate;
													//$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
													$prjtimelinerate = round(($prjnowdiff / $prjdatediff) * 100, 1);
													if ($prjtimelinerate > 100) :
														$prjtimelinerate = 100;
													else :
														$prjtimelinerate = $prjtimelinerate;
													endif;

													if ($row_dates['projcategory'] == 2) {
														$projcontractor = $row_dates['contractor_name'];
														$projcontractor_id = $row_dates['contrid'];
													} else {
														$projcontractor = "In House";
													}
										?>
													<tr id="rows" style="padding-bottom:1px">
														<td><?php echo $sn; ?></td>
														<td style="padding-right:0px; padding-left:0px; padding-top:0px">
															<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
																<a href="myprojectdash.php?projid=<?php echo $row_rsMyP['projid']; ?>" style="color:#FFF; font-weight:bold"><?php echo $row_rsMyP['projname']; ?></a>
															</div>
															<div style="padding:5px; font-size:11px">
																<b>Project Code:</b> <?php echo $row_rsMyP['projcode']; ?>
																<br />
																<b>Project Cost:</b> Ksh.<?php echo $projcost; ?><br />
																<b>Start Date:</b> <?php echo $pjstdate; ?><br />
																<b>End Date: </b> <?php echo $pjendate; ?><br />
																<b>Implementer: </b>
																<font color="#4CAF50">
																	<?php
																	if ($projcontractor != "In House") {
																	?>
																		<a href="view-contractor-info.php?contrid=<?= $projcontractor_id ?>" style="color:#4CAF50"><?php echo $projcontractor; ?></a>
																	<?php
																	} else {
																	?>
																		<?php echo $projcontractor; ?>
																</font>
															<?php
																	}
															?>
															</div>
														</td>
														<td style="padding-right:0px; padding-left:0px">
															<?php
															$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
															$query_Projstatus->execute(array(":projstatus" => $row_rsMyP['projstatus']));
															$row_Projstatus = $query_Projstatus->fetch();
															$projstatus = $row_Projstatus["statusname"];
															if ($row_rsMyP['projstatus'] == 3) {
																echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 4) {
																echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 11) {
																echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 5) {
																echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 1) {
																echo '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 2) {
																echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $projstatus . '</button>';
															} else if ($row_rsMyP['projstatus'] == 6) {
																echo '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $projstatus . '</button>';
															}
															?><input type="hidden" id="scardprog" value="<?php echo $percent2; ?>">
															<?php
															if ($percent2 < 100) {
																echo '
																<div class="progress" style="height:20px; font-size:10px; color:black">
																	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
																		' . $percent2 . '%
																	</div>
																</div>';
															} elseif ($percent2 == 100) {
																echo '
																<div class="progress" style="height:20px; font-size:10px; color:black">
																	<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
																	' . $percent2 . '%
																	</div>
																</div>';
															}
															?>
														</td>
														<td align="center">
															<a href="#" onclick="javascript:GetProjIssues(<?php echo $row_rsMyP['projid']; ?>)" style="color:#FF5722"><?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i> <font size="5px">' . $totalRows_rsProjissues . '</font>'; ?></a>
														</td>
														<input type="hidden" name="myprojid" id="myprojid" value="<?php echo $row_rsMyP['projid']; ?>">
														<td>
															<a href="view-project-maps.php?projid=<?php echo $row_rsMyP['projid']; ?>" id="" class="" style="color:indigo"><?php echo $myLocation; ?></a>
														</td>
														<td><?php echo $row_FY['year']; ?></td>
														<td><strong><a href="myprojectmilestones.php?projid=<?php echo $row_rsMyP['projid']; ?>" style="color:#2196F3"><?php echo $row_milestones['Total Status']; ?> Milestones</a></strong><br />
															<?php
															if ($pjstatus == 2) {
																if ($row_milestones['Cancelled'] == 0) {
																} else {
															?><span class="badge bg-brown-grey" style="margin-bottom:2px"><?php echo $row_milestones['Cancelled']; ?></span> Cancelled<br />
																<?php
																}
															} elseif ($pjstatus == 'On Hold') {
																if ($row_milestones['On Hold'] == 0) {
																} else {
																?><span class="badge bg-pink" style="margin-bottom:2px"><?php echo $row_milestones['On Hold']; ?></span> On Hold<br />
																<?php
																}
															} else {
																// if ($row_milestones['Approved'] == 0) {

																// } else {
																?>
																<!-- <span class="badge bg-blue-grey" style="margin-bottom:2px"><?php // echo $row_milestones['Approved']; ?></span> Approved<br /> -->
																<?php
																// }
																if ($row_milestones['Pending'] == 0) {
																} else {
																?><span class="badge bg-yellow" style="margin-bottom:2px"><?php echo $row_milestones['Pending']; ?></span> Pending<br />
																<?php
																}
																if ($row_milestones['In Progress'] == 0) {
																} else {
																?><span class="badge bg-blue" style="margin-bottom:2px"> <?php echo $row_milestones['In Progress']; ?></span> On Track<br />
																<?php
																}
																if ($row_milestones['Behind Schedule'] == 0) {
																} else {
																?><span class="badge bg-deep-orange" style="margin-bottom:2px"><?php echo $row_milestones['Behind Schedule']; ?></span> Behind Schedule<br />
																<?php
																}
																if ($row_milestones['Completed'] == 0) {
																} else {
																?><span class="badge bg-green" style="margin-bottom:2px"><?php echo $row_milestones['Completed']; ?></span> Completed<br />
															<?php
																}
															}
															?>
														</td>

														<?php
														if ($pjstdate <= $currentdate && ($row_rsMyP['projstatus'] == 4)) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
																<a type="button" class="btn bg-deep-orange waves-effect" href="#" data-toggle="tooltip" data-placement="bottom" title="Click to view all your notifications for this project" style="width:100%; margin-bottom:5px">Notifications</a>
															</td>
														<?php
														} elseif ($row_rsMyP['projstatus'] == 1) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
															</td>
														<?php
														} elseif ($pjstdate <= $currentdate && $row_rsMyP['projstatus'] == 5) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
																<a type="button" class="btn bg-deep-orange waves-effect" href="#" data-toggle="tooltip" data-placement="bottom" title="Click to view all your notifications for this project" style="width:100%; margin-bottom:5px">Notifications</a>
															</td>
														<?php
														} elseif ($row_rsMyP['projstatus'] == 3) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
															</td>
														<?php
														} elseif ($pjstdate <= $currentdate && $row_rsMyP['projstatus'] == 11) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
																<a type="button" class="btn bg-deep-orange waves-effect" href="#" data-toggle="tooltip" data-placement="bottom" title="Click to view all your notifications for this project" style="width:100%; margin-bottom:5px">Notifications</a>
															</td>
														<?php
														} elseif ($row_rsMyP['projstatus'] == 6) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
															</td>
														<?php
														} elseif ($row_rsMyP['projstatus'] == 1) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
															</td>
														<?php
														} elseif ($row_rsMyP['projstatus'] == 2) {
														?>
															<td style="padding-right:0px; padding-left:0px">
																<a type="button" href="view-project-gallery.php?projid=<?= $projectID ?>" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">
																	Gallery
																</a>
															</td>
														<?php
														}


														?>
													</tr>
										<?php
												} while ($row_rsMyP = $query_rsMyP->fetch());
											}
										} catch (PDOException $ex) {
											$result = flashMessage("An error occurred: " . $ex->getMessage());
											echo $result;
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
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:indigo">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ALIGN="center">
						<font color="#FFF">PROJECT SCORECARD</font>
					</h4>
				</div>
				<div class="modal-body" id="formcontent">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div id="projectModal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h4 class="modal-title new-title" ALIGN="center" style="color:#FFF">Modal Title</h4>
				</div>
				<div class="modal-body">
					<div id="map"></div>
					<div id="photo"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Project Issues -->
	<div class="modal fade" id="projIssues" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title" align="center" style="color:#FF5722; font-size:24px">Project Issues</h2>
				</div>
				<div class="modal-body" id="detailscontent">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script type="text/javascript">
	$("#advanced").click(function(e) {
		e.preventDefault();
		$(".caret-icon").toggleClass('fa-caret-up fa-caret-down');
		$(".advanced-search").toggle();
	});
	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');

			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {

				$(".submenus").show();
				$(this).attr('id', '1');
			}

		});

		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});


		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});

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