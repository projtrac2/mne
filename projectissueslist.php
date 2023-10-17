<?php 
require('includes/head.php');
$projid = isset($_GET['proj']) ? $_GET['proj'] : "";
if ($permission) {
	try {
		$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_projdetails->execute(array(":projid" => $projid));
		$row_projdetails = $query_projdetails->fetch();
		$projname = $row_projdetails['projname'];
		$projcategory = $row_projdetails['projcategory'];
		$percent2 = $row_projdetails['progress'];
		$count_open_issues = "";
		$count_analysis_issues = '';
		$count_assignedissues = "";
 

		$responsible = 1;

		$query_issues = $db->prepare("SELECT i.id, i.origin, p.projid, p.projname AS projname,p.projcategory, category, observation, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category WHERE p.projid=:projid and i.status=1");
		$query_issues->execute(array(":projid" => $projid));
		$count_issues = $query_issues->rowCount();


		$query_issuesanalysis = $db->prepare("SELECT i.id, i.origin, p.projid, p.projname AS projname, p.projcategory, risk_category, observation, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate, i.date_assigned, i.priority, i.assigned_by FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid WHERE p.projid=:projid and i.status=2");
		$query_issuesanalysis->execute(array(":projid" => $projid));
		$count_issuesanalysis = $query_issuesanalysis->rowCount();

		$query_assignedissues = $db->prepare("SELECT i.id, p.projname, c.category, i.observation, i.status as status, i.created_by AS monitor, i.owner as owner, i.date_escalated, i.date_assigned, i.date_created AS issuedate, i.date_closed AS dateclosed, pr.priority FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_priorities pr on pr.id=i.priority WHERE p.projid=:projid and i.status<>1");
		$query_assignedissues->execute(array(":projid" => $projid));
		$count_assignedissues = $query_assignedissues->rowCount();
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
?>
    <link href="css/popper.css" rel="stylesheet">
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader" style="color:white">
					<span class="text-warning"> <?= $icon ?> </span>
					<?= $pageTitle ?>
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
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home">
										<i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Open&nbsp;
										<span class="badge bg-orange"><?php echo $count_issues; ?></span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1">
										<i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true">
										</i> Analysis&nbsp;<span class="badge bg-blue-grey"><?php echo $count_issuesanalysis; ?></span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2">
										<i class="fa fa-file-text-o bg-primary" aria-hidden="true">
										</i> Resolution&nbsp;<span class="badge bg-primary"><?php echo $count_assignedissues; ?></span>
									</a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:3%">#</th>
													<th style="width:28%">Issue</th>
													<th style="width:7%">Origin</th>
													<th style="width:20%">Output</th>
													<th style="width:13%">Recorded By</th>
													<th style="width:13%">Date Recorded</th>
													<th style="width:11%">Action Date</th>
													<?php if ($responsible == $user_name ||  ($role_group == 4 && $designation == 1)) { ?>
														<th style="width:7%">Action</th>
													<?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php
												if ($count_issues > 0) {
													$nm = 0;
													while ($row_issues = $query_issues->fetch()) {
														$nm = $nm + 1;
														$id = $row_issues['id'];
														$projid = $row_issues['projid'];
														$project = $row_issues['projname'];
														$origin_id = $row_issues['origin'];
														$risk = $row_issues['category'];
														$observation = $row_issues['observation'];
														$monitorid = $row_issues['monitor'];
														$recommendation = $row_issues['recommendation'];
														$issuedate = $row_issues['issuedate'];

														$query_Issues_Category = $db->prepare("SELECT * FROM `tbl_inspection_monitoring_data_origin` WHERE id=:origin_id");
														$query_Issues_Category->execute(array(":origin_id" => $origin_id));
														$check_Issues_Category = $query_Issues_Category->fetch();
														$count_check_Issues_Category = $query_Issues_Category->rowCount();
														$origin = $count_check_Issues_Category > 0 ? $check_Issues_Category['origin'] : "";


														$query_issue_output = $db->prepare("SELECT output FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_project_details d on d.id=i.output_id inner join tbl_progdetails o on o.id=d.outputid WHERE i.id='$id'");
														$query_issue_output->execute();
														$row_issue_output = $query_issue_output->fetch();
														$count_issue_output = $query_issue_output->rowCount();
														$output = "Not attached";
														if ($row_issue_output) {
															$output = $row_issue_output['output'];
														}

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

														if ($actduedate >= $current_date) {
															$actionstatus = $stgstatus;
															$styled = 'style="color:blue"';
														} elseif ($actduedate < $current_date) {
															$actionstatus = "Behind Schedule";
															$styled = 'style="color:red"';
														}

														$actiondate = $actionnduedate;
														$query_owner = $db->prepare("SELECT title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid='$monitorid'");
														$query_owner->execute();
														$row_owner = $query_owner->fetch();
														$monitor = $row_owner["title"] . '.' . $row_owner["fullname"];
												?>
														<tr style="background-color:#fff">
															<td align="center"><?php echo $nm; ?></td>
															<td><?php echo $observation; ?></td>
															<td><?php echo $origin; ?></td>
															<td><?php echo $output; ?></td>
															<td><?php echo $monitor; ?></td>
															<td><?php echo date("d M Y", strtotime($issuedate)); ?></td>
															<td <?= $styled ?>><span data-toggle="tooltip" data-placement="bottom" title="Status: <?= $actionstatus ?>"><?php echo $actiondate; ?></span></td>
															<?php if ($responsible == $user_name || ($role_group == 4 && $designation == 1)) { ?>
																<td>
																	<div align="center">
																		<a onclick="javascript:CallIssueAssignment(<?php echo $row_issues['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Assign Issue Owner"><i class="fa fa-user-plus fa-2x text-success" aria-hidden="true"></i></a>
																	</div>
																</td>
															<?php } ?>

														</tr>
												<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:3%">#</th>
													<th style="width:23%">Issue</th>
													<th style="width:7%">Origin</th>
													<th style="width:24%">Output</th>
													<th style="width:7%">Priority</th>
													<th style="width:10%">Date Recorded</th>
													<th style="width:10%">Due Date</th>
													<th style="width:9%">Status</th>
													<th style="width:7%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$nm = 0;
												if ($count_issuesanalysis > 0) {
													while ($rows = $query_issuesanalysis->fetch()) {
														$nm = $nm + 1;
														$id = $rows['id'];
														$project = $rows['projname'];
														$origin_id = $rows['origin'];
														$riskid = $rows['risk_category'];
														$observation = $rows['observation'];
														$priorityid = $rows['priority'];
														$monitor = $rows['monitor'];
														$issuedate = $rows['issuedate'];
														$assignee = $rows['assigned_by'];
														$dateassigned = $rows['date_assigned'];
														$status = $rows['status'];
														$query_Issues_Category = $db->prepare("SELECT * FROM `tbl_inspection_monitoring_data_origin` WHERE id=:origin_id");
														$query_Issues_Category->execute(array(":origin_id" => $origin_id));
														$check_Issues_Category = $query_Issues_Category->fetch();
														$count_check_Issues_Category = $query_Issues_Category->rowCount();
														$origin = $count_check_Issues_Category > 0 ? $check_Issues_Category['origin'] : "";

														$query_issuesrisk = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid='$riskid'");
														$query_issuesrisk->execute();
														$row_issuesrisk = $query_issuesrisk->fetch();
														$risk = $row_issuesrisk['category'];

														$query_issuespriority = $db->prepare("SELECT priority FROM tbl_priorities WHERE id='$priorityid'");
														$query_issuespriority->execute();
														$row_issuespriority = $query_issuespriority->fetch();
														$priority = $row_issuespriority['priority'];

														$query_issue_output = $db->prepare("SELECT i.id, p.projid, p.projcategory, projname, category, observation, recommendation, i.status as status, i.created_by AS monitor, i.date_assigned , i.date_created AS issuedate, i.assigned_by, pr.priority, output FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_priorities pr on pr.id=i.priority  inner join tbl_project_details d on d.id=i.output_id inner join tbl_progdetails o on o.id=d.outputid WHERE i.id='$id'");
														$query_issue_output->execute();
														$row_issue_output = $query_issue_output->fetch();

														$output = "No defined";
														if ($row_issue_output) {
															$output = $row_issue_output['output'];
														}

														$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=2 and active=1");
														$query_timeline->execute();
														$row_timeline = $query_timeline->fetch();
														$timelineid = $row_timeline["id"];
														$time = $row_timeline["time"];
														$units = $row_timeline["units"];
														$stgstatus = $row_timeline["status"];

														$duedate = strtotime($dateassigned . "+ " . $time . " " . $units);
														$actionnduedate = date("d M Y", $duedate);

														$current_date = date("Y-m-d");
														$actduedate = date("Y-m-d", $duedate);

														$query_score = $db->prepare("SELECT score,date_analysed FROM tbl_project_riskscore WHERE issueid='$id'");
														$query_score->execute();
														$check_score = $query_score->fetch();
														$dateanalysed = $check_score ? date("d M Y", strtotime($check_score["date_analysed"])) : "";

														$dateassigned = date("d M Y", strtotime($dateassigned));

														if ($status == 2) {
															if ($actduedate >= $current_date) {
																$actionstatus = "Analysis";
																$styled = 'style="color:blue"';
															} elseif ($actduedate < $current_date) {
																$actionstatus = "Overdue";
																$styled = 'style="color:red"';
															}
															$actiondate = $actionnduedate;
															$dateaction = "Date Issue Assigned";
														} elseif ($status == 3) {
															$actionstatus = "Analysed";
															$styled = 'style="color:blue"';
															$actiondate = $dateanalysed;
															$dateaction = "Date Issue Analysed";
														} elseif ($status == 4) {
															$query_escduedate =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=4 and active=1");
															$query_escduedate->execute();
															$row_escduedate = $query_escduedate->fetch();
															$timelineid = $row_escduedate["id"];
															$time = $row_escduedate["time"];
															$units = $row_escduedate["units"];
															$stgstatus = $row_escduedate["status"];

															$duedate = strtotime($dateassigned . "+ " . $time . " " . $units);
															$actionnduedate = date("d M Y", $duedate);

															$actionstatus = "Escalated";
															$styled = 'style="color:orange"';
															$actiondate = $actionnduedate;
															$dateaction = "Date Issue Escalated";
														} elseif ($status == 5) {
															$query_date_on_hold = $db->prepare("SELECT date_on_hold FROM tbl_escalations WHERE itemid='$id'");
															$query_date_on_hold->execute();
															$row_date_on_hold = $query_date_on_hold->fetch();

															$date_on_hold = date("d M Y", strtotime($row_date_on_hold["date_on_hold"]));
															$actionstatus = "On Hold";
															$styled = 'style="color:red; width:9%"';
															$actiondate = $date_on_hold;
														} elseif ($status == 6) {
															$query_date_continue = $db->prepare("SELECT date_continue FROM tbl_escalations WHERE itemid='$id'");
															$query_date_continue->execute();
															$row_date_continue = $query_date_continue->fetch();

															$date_continue = date("d M Y", strtotime($row_date_continue["date_continue"]));
															$actionstatus = "Continue";
															$styled = 'style="color:blue; width:9%"';
															$actiondate = $date_continue;
														} elseif ($status == 7) {
															$query_dateclosed = $db->prepare("SELECT date_closed FROM tbl_projissues WHERE id='$id'");
															$query_dateclosed->execute();
															$row_dateclosed = $query_dateclosed->fetch();

															$dateclosed = date("d M Y", strtotime($row_dateclosed["date_closed"]));
															$actionstatus = "Closed";
															$styled = 'style="color:green"';
															$actiondate = $dateclosed;
															$dateaction = "Date Issue Close";
														}

												?>
														<tr style="background-color:#fff">
															<td align="center"><?php echo $nm; ?></td>
															<td><?php echo $risk; ?></td>
															<td><?php echo $origin; ?></td>
															<td><?php echo $output; ?></td>
															<td><?php echo $priority; ?></td>
															<td><?php echo date("d M Y", strtotime($issuedate)); ?></td>
															<td><span data-toggle="tooltip" data-placement="bottom" title="<?php echo $dateaction; ?>"><?php echo $dateassigned; ?></span></td>
															<td <?php echo $styled; ?>><strong><?php echo $actionstatus; ?></strong></td>
															<td>
																<div align="center">
																	<?php
																	$query_issuediscussion =  $db->prepare("SELECT d.status as dstatus FROM `tbl_projissues` i INNER JOIN tbl_projissues_discussions d ON d.issueid =i.id WHERE d.projid =:projid and i.status ='$status' and i.id ='$id' and parent=0");
																	$query_issuediscussion->execute(array(":projid" => $projid));
																	$rows_issuediscussion = $query_issuediscussion->fetch();
																	$rows_count = $query_issuediscussion->rowCount();
																	$discstatus = $rows_count > 0 ? $rows_issuediscussion['dstatus'] : "";

																	if ($status == 2) {
																		if (empty($discstatus)) {
																	?>
																			<a href="project-issue-discussion.php?issueid=<?= $id ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Create a new discussion for this issue"><i class="fa fa-comment-o fa-2x text-primary" aria-hidden="true"></i></a>
																		<?php
																		} elseif ($discstatus == 1) {
																		?>
																			<a href="project-issue-discussion.php?issueid=<?= $id ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Collaborate on this Issue"><i class="fa fa-comments-o fa-2x text-primary" aria-hidden="true"></i></a>
																		<?php
																		} elseif ($discstatus == 2) {
																		?>
																			<a href="project-issue-discussion.php?issueid=<?= $id ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="View Issue discussions"><i class="fa fa-comments fa-2x text-success" aria-hidden="true"></i></a>
																			<a onclick="javascript:CallRiskAction(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Analyse this Issue"><i class="fa fa-folder-open fa-2x text-primary" aria-hidden="true"></i></a>
																		<?php
																		}
																	} elseif ($status !== 1 && $status !== 2) { ?>
																		<a href="project-issue-discussion.php?issueid=<?= $id ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="View Issue discussions"><i class="fa fa-comments fa-2x text-success" aria-hidden="true"></i></a>
																		<a onclick="javascript:CallRiskAnalysis(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Issue Analysis Report"><i class="fa fa-bar-chart fa-2x text-primary" aria-hidden="true"></i></a>
																	<?php } ?>
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
								<div id="menu2" class="tab-pane fade">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-blue-grey">
													<th style="width:3%">#</th>
													<th style="width:25%">Issue</th>
													<th style="width:20%">Description</th>
													<th style="width:7%">Priority</th>
													<th style="width:10%">Status</th>
													<th style="width:12%">Issue Owner</th>
													<th style="width:8%">Status&nbsp;Date</th>
													<th style="width:8%">Due&nbsp;Date</th>
													<th style="width:7%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												function duedate($actiondate, $stage)
												{
													global $db;
													$query_timeline = $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage='$stage' and active=1");
													$query_timeline->execute();
													$row_timeline = $query_timeline->fetch();
													$totalrows_timeline = $query_timeline->rowCount();

													$timelineid = $row_timeline["id"];
													$time = $row_timeline["time"];
													$units = $row_timeline["units"];
													$stgstatus = $row_timeline["status"];

													$duedate = strtotime($actiondate . "+ " . $time . " " . $units);
													$actionnduedate = date("d M Y", $duedate);

													//var_dump($actionnduedate);
													if ($totalrows_timeline > 0) {
														return $actionnduedate;
													} else {
														return false;
													}
												}

												if ($count_assignedissues > 0) {
													$nm = 0;
													while ($rows = $query_assignedissues->fetch()) {
														$nm = $nm + 1;
														$id = $rows['id'];
														$project = $rows['projname'];
														$risk = $rows['category'];
														$observation = $rows['observation'];
														$monitor = $rows['monitor'];
														$ownerid = $rows['owner'];
														$priority = $rows['priority'];
														$status = $rows['status'];
														$issuedate = $rows['issuedate'];
														$dateassigned = $rows['date_assigned'];
														$dateclosed = $rows['dateclosed'];
														$issuestatus = $rows["status"];

														$query_score = $db->prepare("SELECT score,date_analysed,notes FROM tbl_project_riskscore WHERE issueid='$id'");
														$query_score->execute();
														$check_score = $query_score->fetch();
														$count_check_score = $query_score->rowCount();
														$dateanalysed = $count_check_score > 0 ? date("d M Y", strtotime($check_score["date_analysed"])) : "";
														$date_analysed = $count_check_score > 0 ? $check_score["date_analysed"] : "";
														$riskscore = $count_check_score > 0 ? $check_score["score"] : "";
														$risknotes = $count_check_score > 0 ? $check_score["notes"] : "";


														if ($status == 2) {
															$actionstatus = "Analysis";
															$styled = 'style="color:blue; width:9%"';

															$statusdate = date("d M Y", strtotime($dateassigned));
															$actionnduedate = duedate($dateassigned, $status);
														} elseif ($status == 3) {
															$actionstatus = "Analysed";
															$styled = 'style="color:blue"';

															$statusdate = $dateanalysed;
															$actionnduedate = duedate($date_analysed, $status);
														} elseif ($status == 4) {
															$actionstatus = "Escalated";
															$styled = 'style="color:#FFC107; width:9%"';

															$date_escalated	= $rows["date_escalated"];
															$statusdate = date("d M Y", strtotime($date_escalated));
															$actionnduedate = duedate($date_escalated, $status);
														} elseif ($status == 5) {
															$query_date_on_hold = $db->prepare("SELECT date_on_hold FROM tbl_escalations WHERE itemid='$id'");
															$query_date_on_hold->execute();
															$row_date_on_hold = $query_date_on_hold->fetch();

															$date_on_hold	= $row_date_on_hold["date_on_hold"];
															$actionstatus = "On Hold";
															$styled = 'style="color:red; width:9%"';

															$statusdate = date("d M Y", strtotime($date_on_hold));
															$actionnduedate = duedate($date_on_hold, $status);
														} elseif ($status == 6) {
															$query_date_continue = $db->prepare("SELECT date_continue FROM tbl_escalations WHERE itemid='$id'");
															$query_date_continue->execute();
															$row_date_continue = $query_date_continue->fetch();

															$date_continue	= $row_date_continue["date_continue"];
															$actionstatus = "Continue";
															$styled = 'style="color:blue; width:9%"';

															$statusdate = date("d M Y", strtotime($date_continue));
															$actionnduedate = duedate($date_continue, $status);
														} elseif ($status == 7) {
															$actionstatus = "Closed";
															$styled = 'style="color:green; width:9%"';

															$statusdate = date("d M Y", strtotime($dateclosed));
															$actionnduedate = duedate($dateclosed, $status);
														}

														if ($riskscore == 1) {
															$level = "Negligible";
															$style = 'style="width:20%; background-color:#4CAF50; color:#fff"';
															$style2 = 'style="background-color:#4CAF50; color:#fff"';
														} elseif ($riskscore == 2) {
															$level = "Minor";
															$style = 'style="width:20%; background-color:#CDDC39; color:#fff"';
															$style2 = 'style="background-color:#CDDC39; color:#fff"';
														} elseif ($riskscore == 3) {
															$level = "Moderate";
															$style = 'style="width:20%; background-color:#FFEB3B; color:#000"';
															$style2 = 'style="background-color:#FFEB3B; color:#000"';
														} elseif ($riskscore == 4) {
															$level = "Significant";
															$style = 'style="width:20%; background-color:#FF9800; color:#fff"';
															$style2 = 'style="background-color:#FF9800; color:#fff"';
														} elseif ($riskscore == 5) {
															$level = "Severe";
															$style = 'style="width:20%; background-color:#F44336; color:#fff"';
															$style2 = 'style="background-color:#F44336; color:#fff"';
														}

												?>
														<tr style="background-color:#fff">
															<td style="width:3%" align="center"><?php echo $nm; ?></td>
															<?php if ($issuestatus == 2) { ?>
																<td style="width:25%"><?php echo $risk; ?></td>
																<td style="width:20%"><?php echo $observation; ?></td>
															<?php } else { ?>
																<td style="width:25%"><a onclick="javascript:CallRiskAnalysis(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Issue Analysis Report"><?php echo $risk; ?></a></td>
																<td <?= $style ?>>
																	<span class="mytooltip tooltip-effect-1">
																		<span class="tooltip-item2">
																			<?php echo $level; ?>
																		</span>
																		<span class="tooltip-content4 clearfix" <?= $style2 ?>>
																			<span class="tooltip-text2">
																				<h4 align="center"><u>SEVERITY COMMENTS</u></h4> <?php echo $risknotes; ?>
																			</span>
																		</span>
																	</span>
																</td>
															<?php } ?>
															<td style="width:7%"><?php echo $priority; ?></td>
															<td <?php echo $styled; ?> style="width:10%"><strong><?php echo $actionstatus; ?></strong></td>
															<?php if ($issuestatus == 2 || $issuestatus == 3 || $issuestatus == 7) {
																$query_owner = $db->prepare("SELECT tt.title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid='$ownerid'");
																$query_owner->execute();
																$row_owner = $query_owner->fetch();
																$count_row_owner = $query_owner->rowCount();
																$owner = $count_row_owner > 0 ? $row_owner["title"] . '.' . $row_owner["fullname"] : "";
															?>
																<td style="width:12%"><?php echo $owner; ?></td>
															<?php
															} elseif ($issuestatus == 4 || $issuestatus == 5 || $issuestatus == 6) {
																$query_manager = $db->prepare("SELECT tt.title, fullname FROM tbl_escalations e inner join users u on u.userid=e.owner inner join tbl_projteam2 t on t.ptid=u.pt_id inner join tbl_titles tt on tt.id=t.title WHERE e.itemid='$id'");
																$query_manager->execute();
																$row_manager = $query_manager->fetch();
																$manager = $row_manager ? $row_manager["title"] . '.' . $row_manager["fullname"] : "";
															?>
																<td style="width:12%"><?php echo $manager; ?></td>
															<?php } ?>
															<td style="width:9%"><?php echo $statusdate; ?></td>
															<td style="width:9%"><?php echo $actionnduedate; ?></td>
															<td style="width:7%">
																<div align="center">
																	<?php if ($issuestatus == 3) { ?>
																		<a onclick="javascript:CallIssueEscalate(<?php echo $id; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Escalate This Issue"><i class="fa fa-reply fa-2x text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
																		<a onclick="javascript:CallIssueClosure(<?php echo $id; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Close This Issue"><i class="fa fa-dot-circle-o fa-2x text-success" aria-hidden="true"></i></a>
																	<?php } elseif ($issuestatus == 6) { ?>
																		<a onclick="javascript:CallIssueClosure(<?php echo $id; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Close This Issue"><i class="fa fa-dot-circle-o fa-2x text-success" aria-hidden="true"></i></a>
																	<?php } elseif ($issuestatus == 7) { ?>
																		<a onclick="javascript:CallRiskAnalysis(<?php echo $id; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Closed Issue History Report"><i class="fa fa-folder fa-2x text-success" aria-hidden="true"></i></a>
																	<?php } else { ?>
																		<i class="fa fa-hourglass-half fa-2x text-warning" aria-hidden="true"></i>
																	<?php } ?>
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
			</div>
		</div>
	</section>
	<!-- end body  -->


	<!-- Modal Issue Escalation -->
	<div class="modal fade" id="issueEscalateModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#795548">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Issue Escalation</font>
					</h3>
				</div>
				<form class="tagForm" action="issueescalation" method="post" id="issue-escalation-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="issueescalation">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<div class="col-md-3">
						</div>
						<div class="col-md-6" align="center">
							<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Escalate" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
							<input type="hidden" name="stchange" value="1" />
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Escalation -->
	<!-- Modal Issue Closure -->
	<div class="modal fade" id="issueClosureModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#795548">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Issue Closure</font>
					</h3>
				</div>
				<form class="tagForm" action="issueclosure" method="post" id="issue-closure-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="issueclosure">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<div class="col-md-3">
						</div>
						<div class="col-md-6" align="center">
							<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Close Issue" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
							<input type="hidden" name="stchange" value="1" />
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Closure -->
	<!-- Modal Assign Owner -->
	<div class="modal fade" id="assignModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Assign Issue Owner</font>
					</h3>
				</div>
				<form class="tagForm" action="issueassignment" method="post" id="assign-issues-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="issueassignment">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Assign Owner -->
	<!-- Modal Issue Action -->
	<div class="modal fade" id="riskModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Project Issue Analysis</font>
					</h3>
				</div>
				<form class="tagForm" action="issueanalysis" method="post" id="issue-analysis-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="riskaction">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Action -->
	<!-- Modal Issue Analysis -->
	<div class="modal fade" id="riskAnalysisModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Project Issue Analysis Report</font>
					</h3>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="body">
									<div class="table-responsive" style="background:#eaf0f9">
										<div id="riskanalysis">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<div class="col-md-4">
					</div>
					<div class="col-md-4" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
					<div class="col-md-4">
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#issue-analysis-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueanalysis",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert('Record Successfully Saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});

		$('#assign-issues-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueassignment",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert('Record successfully saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});

	function CallIssueAssignment(id) {
		$.ajax({
			type: 'post',
			url: 'callissueaction',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#issueassignment').html(data);
				$("#assignModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAnalysis(id) {
		$.ajax({
			type: 'post',
			url: 'callriskanalysis',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskanalysis').html(data);
				$("#riskAnalysisModal").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$('#issue-closure-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueclosure",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert('Issue Closed Successfully');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
		$('#issue-escalation-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueescalation",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert('Issue Escalated Successfully');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
		// $('#issue-analysis-form').on('submit', function(event) {
		// 	event.preventDefault();
		// 	var form_data = $(this).serialize();
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "issueanalysis",
		// 		data: form_data,
		// 		dataType: "json",
		// 		success: function(response) {
		// 			if (response) {
		// 				alert('Record Successfully Saved');
		// 				window.location.reload();
		// 			}
		// 		},
		// 		error: function() {
		// 			alert('Error');
		// 		}
		// 	});
		// 	return false;
		// });


		// $('#assign-issues-form').on('submit', function(event) {
		// 	event.preventDefault();
		// 	var form_data = $(this).serialize();
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "issueassignment",
		// 		data: form_data,
		// 		dataType: "json",
		// 		success: function(response) {
		// 			if (response) {
		// 				alert('Record successfully saved');
		// 				window.location.reload();
		// 			}
		// 		},
		// 		error: function() {
		// 			alert('Error');
		// 		}
		// 	});
		// 	return false;
		// });
	});

	function CallIssueAssignment(id) {
		$.ajax({
			type: 'post',
			url: 'callissueaction',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#issueassignment').html(data);
				$("#assignModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAnalysis(id) {
		$.ajax({
			type: 'post',
			url: 'callriskanalysis',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskanalysis').html(data);
				$("#riskAnalysisModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallIssueEscalate(id) {
		$.ajax({
			type: 'post',
			url: 'assets/processor/callissueescalate',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#issueescalation').html(data);
				$("#issueEscalateModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallIssueClosure(id) {
		$.ajax({
			type: 'post',
			url: 'assets/processor/callissueclosure',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#issueclosure').html(data);
				$("#issueClosureModal").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>