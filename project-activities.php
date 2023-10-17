<?php 
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: project-dashboard"); 
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];

$original_projid = $_GET['proj'];
require('includes/head.php');
if ($permission) {
	try {
		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstatus, projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE deleted='0' AND projid = :projid");
		$query_rsMyP->execute(array(":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$projcategory = $row_rsMyP["projcategory"];
		$currentStatus =  $row_rsMyP['projstatus'];

		$query_proj =  $db->prepare("SELECT projname, projid FROM tbl_projects WHERE deleted='0' AND projid = :projid");
		$query_proj->execute(array(":projid" => $projid));
		$row_proj = $query_proj->fetch();

		$query_rsMilestone =  $db->prepare("SELECT msid, milestone, progress, status FROM tbl_milestone WHERE projid = :projid");
		$query_rsMilestone->execute(array(":projid" => $projid));
		$row_rsMilestone = $query_rsMilestone->fetch();

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
		$query_rsMlsProg->execute(array(":projid" => $projid));
		$row_rsMlsProg = $query_rsMlsProg->fetch();

		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

		$percent2 = round($prjprogress, 2);

		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid = :projid");
		$query_rsTender->execute(array(":projid" => $projid));
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<!-- start body  -->
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					
					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to All Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='projects.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px">Project Dashboard</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Project Activities</a>
								<a href="project-indicators.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Indicators</a>
								<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Map</a>
								<!--<a href="project-gantt-chart.php?proj=<?php //echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Gantt Chart</a>-->
								<a href="project-gallery.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Gallery</a>
							</div>
						</div>
						<h4>
							<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
							</div>
							<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
									<div class="bar hundred cornflowerblue">
										<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
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
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr id="colrow" class="milestones">
											<th width="5%"></th>
											<th width="3%"><strong> # </strong></th>
											<th width="33%"><strong>Milestone</strong></th>
											<th width="12%"><strong>Status & Progress</strong></th>
											<th width="12%"><strong>No of Tasks</strong></th>
											<th width="9%"><strong>Budget (Ksh)</strong></th>
											<th width="9%"><strong>Start Date</strong></th>
											<th width="9%"><strong>End Date</strong></th>
										</tr>
									</thead>
									<tbody>
										<!-- =========================================== -->
										<?php
										$sn = 0;
										do {
											$sn = $sn + 1;
											$milestoneID =  $row_rsMilestone['msid'];
											$mlStatus =  $row_rsMilestone['status'];
											$mlprogress =  $row_rsMilestone['progress'];
											$milestone =  $row_rsMilestone['milestone'];
											// $prjid = $projectid_rsMyP;

											$query_milestonetatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid = '$mlStatus'");
											$query_milestonetatus->execute();
											$row_milestonetatus = $query_milestonetatus->fetch();
											$milestonetatus = $row_milestonetatus['statusname'];

											$query_tasks_count = $db->prepare("SELECT * FROM tbl_task WHERE msid = '$milestoneID'");
											$query_tasks_count->execute();
											$row_tasks_count = $query_tasks_count->fetch();
											$total_tasks_count = $query_tasks_count->rowCount();

											$query_rsMSStatus = $db->prepare("SELECT *, MIN(start_date) AS SmallestDate, MAX(end_date) AS BiggestDate, COUNT(CASE WHEN status = 4 THEN 1 END) AS `Task In Progress`, COUNT(CASE WHEN status = 5 THEN 1 END) AS `Completed Task`, COUNT(CASE WHEN status = 3 THEN 1 END) AS `Pending Task`, COUNT(CASE WHEN status = 11 THEN 1 END) AS `Behind Schedule`, COUNT(CASE WHEN status = 9 THEN 1 END) AS `Overdue Task`,  COUNT(status) AS 'Total Task', sum(progress) AS tskprogress FROM tbl_program_of_works w left join tbl_task t on t.tkid=w.task_id WHERE msid  = '$milestoneID' GROUP BY tkid");
											$query_rsMSStatus->execute();
											$row_rsMSStatus = $query_rsMSStatus->fetch();

											$SmallestStartDate = $row_rsMSStatus["SmallestDate"];
											$BiggestEndDate = $row_rsMSStatus["BiggestDate"];

											$mlstartdate = date("d M Y", strtotime($SmallestStartDate));
											$mlenddate = date("d M Y", strtotime($BiggestEndDate));
											$milestonebudget = 0;
											
											if ($projcategory == 1) {
												$query_milestonebudget =  $db->prepare("SELECT t.tkid, t.task, t.status, t.progress, w.start_date, w.end_date, d.unit_cost, d.units_no FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid left join tbl_project_direct_cost_plan d on d.tasks=t.tkid WHERE msid = :msid ORDER BY t.sdate ASC");
												$query_milestonebudget->execute(array(":msid" => $milestoneID));

												while ($row_milestonebudget = $query_milestonebudget->fetch()) {
													$uno = $row_milestonebudget['units_no'];
													$ucost = $row_milestonebudget['unit_cost'];
													$tkbudget = $uno * $ucost;
													$milestonebudget = $milestonebudget + $tkbudget;
												}
											} else {
												$query_milestonebudget = $db->prepare("SELECT d.unit_cost, d.units_no FROM tbl_task t inner join tbl_project_tender_details d on d.tasks=t.tkid WHERE t.msid = :msid");
												$query_milestonebudget->execute(array(":msid" => $milestoneID));

												while ($row_milestonebudget = $query_milestonebudget->fetch()) {
													$uno = $row_milestonebudget['units_no'];
													$ucost = $row_milestonebudget['unit_cost'];
													$tkbudget = $uno * $ucost;
													$milestonebudget = $milestonebudget + $tkbudget;
												}
											}
											?>
											<tr class="milestones">
												<td align="center" id="milestones<?php echo $milestoneID ?>" class="mb-0" data-toggle="collapse" data-target=".milestone<?php echo $milestoneID ?>">
													<button class="btn btn-link " title="To expand click this Plus sign and to collapse click the Minus sign!!">
														<i class="fa fa-plus-square" style="font-size:16px"></i>
													</button>
												</td>
												<td><?php echo $sn; ?></td>
												<td><?php echo $milestone; ?></td>
												<td>
													<?php
													if ($mlStatus == 3) {
														echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 1) {
														echo '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 4) {
														echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 11) {
														echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 5) {
														echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 2) {
														echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													} else if ($mlStatus == 6) {
														echo '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $milestonetatus . '</button>';
													}

													if ($mlprogress < 100) {
														echo '
														<div class="progress" style="height:20px; font-size:10px; color:black">
															<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $mlprogress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $mlprogress . '%; height:20px; font-size:10px; color:black">
																' . $mlprogress . '%
															</div>
														</div>';
													} elseif ($mlprogress == 100) {
														echo '
														<div class="progress" style="height:20px; font-size:10px; color:black">
															<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $mlprogress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $mlprogress . '%; height:20px; font-size:10px; color:black">
															' . $mlprogress . '%                                   
															</div>
														</div>';
													}
													?>
												</td>
												<td><strong><a href="myprojecttaskfilter?projid=<?php echo $row_rsMilestone['projid']; ?>&amp;msid=<?php echo $row_rsMilestone['msid']; ?>" style="color:#2196F3"><?php echo $total_tasks_count; ?> Tasks</a></strong><br />
													<?php
													if ($mlStatus == 2) {
														if ($row_rsMilestone['Cancelled Task'] == 0) {
														} else {
													?><span class="badge bg-brown-grey" style="margin-bottom:2px"><?php echo $row_rsMilestone['Cancelled Task']; ?></span> Cancelled<br />
														<?php
														}
													} elseif ($mlStatus == 6) {
														if ($row_rsMSStatus['On Hold Task'] == 0) {
														} else {
														?><span class="badge bg-pink" style="margin-bottom:2px"><?php echo $row_rsMSStatus['On Hold Task']; ?></span> On Hold<br />
														<?php
														}
													} else {
														if ($row_rsMSStatus['Pending Task'] == 0) {
														} else {
														?><span class="badge bg-yellow" style="margin-bottom:2px"><?php echo $row_rsMSStatus['Pending Task']; ?></span> Pending<br />
														<?php
														}
														if ($row_rsMSStatus['Task In Progress'] == 0) {
														} else {
														?><span class="badge bg-blue" style="margin-bottom:2px"> <?php echo $row_rsMSStatus['Task In Progress']; ?></span> On Track<br />
														<?php
														}
														if ($row_rsMSStatus['Behind Schedule'] == 0) {
														} else {
														?><span class="badge bg-red" style="margin-bottom:2px"><?php echo $row_rsMSStatus['Behind Schedule']; ?></span> Behind Schedule<br />
														<?php
														}
														if ($row_rsMSStatus['Completed Task'] == 0) {
														} else {
														?><span class="badge bg-green" style="margin-bottom:2px"><?php echo $row_rsMSStatus['Completed Task']; ?></span> Completed<br />
													<?php
														}
													}
													?>
												</td>
												<td><?php echo number_format($milestonebudget, 2); ?></td>
												<td><?php echo $mlstartdate; ?></td>
												<td><?php echo $mlenddate; ?></td>
											</tr>
											<tr class="collapse milestone<?php echo $milestoneID ?> bg-light-blue" data-parent="milestones<?php echo $milestoneID ?>">
												<th width="5%"><strong></strong></th>
												<th width="3%"><strong> # </strong></th>
												<th width="33%"><strong>Task</strong></th>
												<th width="12%"><strong>Status</strong></th>
												<th width="12%"><strong>Progress</strong></th>
												<th width="9%"><strong>Budget</strong></th>
												<th width="9%"><strong>Start Date</strong></th>
												<th width="9%"><strong>End Date</strong></th>
											</tr>
											<!-- =========================================== -->
											<?php
											if ($projcategory == 1) {
												$query_rsMSTask =  $db->prepare("SELECT t.tkid, t.task, t.status, t.progress, w.start_date, w.end_date, d.unit_cost, d.units_no FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid left join tbl_project_direct_cost_plan d on d.tasks=t.tkid WHERE msid = :msid ORDER BY w.start_date ASC");
												$query_rsMSTask->execute(array(":msid" => $milestoneID));
												$row_rsMSTask = $query_rsMSTask->fetch();
											} else {
												$query_rsMSTask =  $db->prepare("SELECT t.tkid, t.task, t.status, t.progress, w.start_date, w.end_date, d.unit_cost, d.units_no FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid left join tbl_project_tender_details d on d.tasks=t.tkid WHERE msid = :msid ORDER BY w.start_date ASC");
												$query_rsMSTask->execute(array(":msid" => $milestoneID));
												$row_rsMSTask = $query_rsMSTask->fetch();
											}

											$nm = 0;
											do {
												$nm = $nm + 1;
												$taskid = $row_rsMSTask['tkid'];
												$current_date = date("Y-m-d");
												$tkstartdate = date("d M Y", strtotime($row_rsMSTask['start_date']));
												$tkenddate = date("d M Y", strtotime($row_rsMSTask['end_date']));
												$tkstatus = $row_rsMSTask['status'];
												$task = $row_rsMSTask['task'];

												$query_tasktatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid  = :tkstatus");
												$query_tasktatus->execute(array(":tkstatus" => $tkstatus));
												$row_tasktatus = $query_tasktatus->fetch();
												$tasktatus = $row_tasktatus['statusname'];

												$totaltasksbudget = 0;

												if ($projcategory == 1) {
													$query_taskbudget = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :taskid");
													$query_taskbudget->execute(array(":taskid" => $taskid));
													while ($row_taskbudget = $query_taskbudget->fetch()) {
														$unitsno = $row_taskbudget['units_no'];
														$unitcost = $row_taskbudget['unit_cost'];
														$taskbudget = $unitsno * $unitcost;
														$totaltasksbudget = $totaltasksbudget + $taskbudget;
													}
													$totaltasksbudget = number_format($totaltasksbudget, 2);
												} else {
													$query_taskbudget = $db->prepare("SELECT * FROM tbl_project_tender_details WHERE tasks = :taskid");
													$query_taskbudget->execute(array(":taskid" => $taskid));
													while ($row_taskbudget = $query_taskbudget->fetch()) {
														$unitsno = $row_taskbudget['units_no'];
														$unitcost = $row_taskbudget['unit_cost'];
														$taskbudget = $unitsno * $unitcost;
														$totaltasksbudget = $totaltasksbudget + $taskbudget;
													}
													$totaltasksbudget = number_format($totaltasksbudget, 2);
												}

											?>
												<tr class="collapse milestone<?php echo $milestoneID  ?>" data-parent="milestones<?php echo $milestoneID ?>">
													<td class="bg-light-blue"></td>
													<td><?php echo $sn . "." . $nm; ?></td>
													<td><?php echo $task; ?></td>
													<td>
														<?php
														if ($tkstatus == 3) {
															echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $tasktatus . '</button>';
														} else if ($tkstatus == 4) {
															echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $tasktatus . '</button>';
														} else if ($tkstatus == 5) {
															echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $tasktatus . '</button>';
														} else if ($tkstatus == 11) {
															echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $tasktatus . '</button>';
														} elseif ($tkstatus == 2) {
															echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $tasktatus . '</button>';
														} elseif ($tkstatus == 6) {
															echo '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $tasktatus . '</button>';
														}
														?>
													</td>
													<td>
														<?php
														$tskprogress = $row_rsMSTask['progress'];
														if ($tskprogress < 100) {
															echo '
															<div class="progress" style="height:20px; font-size:10px; color:black">
																<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $tskprogress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $tskprogress . '%; height:20px; font-size:10px; color:black">
																	' . $tskprogress . '%
																</div>
															</div>';
														} elseif ($tskprogress == 100) {
															echo '
															<div class="progress" style="height:20px; font-size:10px; color:black">
																<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $tskprogress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $tskprogress . '%; height:20px; font-size:10px; color:black">
																' . $tskprogress . '%                                   
																</div>
															</div>';
														}
														?>
													</td>
													<td><?php echo $totaltasksbudget; ?></td>
													<td><?php echo $tkstartdate; ?></td>
													<td><?php echo $tkenddate; ?></td>
												</tr>
										<?php } while ($row_rsMSTask = $query_rsMSTask->fetch());
										} while ($row_rsMilestone = $query_rsMilestone->fetch()); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
	<!-- Modal Issue Action -->
	<div class="modal fade" id="ganttModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Project Issue Analysis</font>
					</h3>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="body">
									<div class="table-responsive" style="background:#eaf0f9">
										<div id="chart_gantt" style="width:100%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-5">
					</div>
					<div class="col-md-2" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
					<div class="col-md-5">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Action -->

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>
<script src="general-settings/js/fetch-selected-project-activities.js"></script>