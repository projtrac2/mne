<?php
try {
	require('includes/head.php');

	echo '<title>Result-Based Monitoring &amp; Evaluation System: Escalated Project Issues</title>';

	if ($permission) {
		$projcategory = "";
		if (isset($_GET["proj"]) && !empty($_GET["proj"])) {
			$prjid = $_GET["proj"];

			$query_issuesanalysis = $db->prepare("SELECT i.id, i.origin, p.projid, p.projname AS projname, p.projcategory, risk_category, observation, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate, i.date_assigned, i.priority, i.assigned_by FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid WHERE p.projid='$prjid' and i.status=2");
			$query_issuesanalysis->execute();
			$rows = $query_issuesanalysis->fetch();
			$count_issuesanalysis = $query_issuesanalysis->rowCount();

			$issuestatus = $rows["status"];
		}

		$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid='$prjid'");
		$query_projdetails->execute();
		$row_projdetails = $query_projdetails->fetch();
		$projname = $row_projdetails['projname'];
		$projcategory = $row_projdetails['projcategory'];

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$prjid'");
		$query_rsMlsProg->execute();
		$row_rsMlsProg = $query_rsMlsProg->fetch();

		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

		$percent2 = round($prjprogress, 2);

?>

		<link href="css/project-progress.css" rel="stylesheet">
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
						<div class="btn-group" style="float:right">
							<div class="btn-group" style="float:right">
							</div>
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<?= $results; ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="header" style="padding-bottom:0px">
								<div class="button-demo" style="margin-top:-15px">
									<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
									<a href="myprojectdash.php?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
									<a href="myprojectmilestones.php?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
									<a href="myprojectfinancialplan.php?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
									<a href="myproject-key-stakeholders.php?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
									<?php if ($projcategory == '2') { ?>
										<div class="btn-group" style="background-color: transparent; border-color: transparent; box-shadow: none;">
											<button type="button" class="btn bg-grey waves-effect dropdown-toggle" style="margin-top:10px; margin-left:-9px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Issues</span>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
												<li style="width:100%"><a href="projectissueslist.php?proj=<?= $prjid ?>">Issues Log</a></li>
												<li style="width:100%"><a href="#">Issues Analysis</a></li>
												<li style="width:100%"><a href="project-escalated-issues.php?proj=<?= $prjid ?>">Issues Escalated</a></li>
											</ul>
										</div>
									<?php } else { ?>
										<div class="btn-group" style="background-color: transparent; border-color: transparent; box-shadow: none;">
											<button type="button" class="btn bg-grey waves-effect dropdown-toggle" style="margin-top:10px; margin-left:-9px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Project Issues</span>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
												<li style="width:100%"><a href="projectissueslist.php?proj=<?= $prjid ?>">Issues Log</a></li>
												<li style="width:100%"><a href="projectissuesanalysis.php?proj=<?= $prjid ?>">Issues Analysis</a></li>
												<li style="width:100%"><a href="project-escalated-issues.php?proj=<?= $prjid ?>">Issues Escalated</a></li>
											</ul>
										</div>
									<?php } ?>
									<a href="myprojectfiles.php?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
								</div>
							</div>

							<h4>
								<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
									Project Name: <font color="white"><?php echo $projname; ?></font>
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
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
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
														do {
															$nm = $nm + 1;
															$id = $rows['id'];
															$projid = $rows['projid'];
															$project = $rows['projname'];
															$origin = $rows['origin'];
															$riskid = $rows['risk_category'];
															$observation = $rows['observation'];
															$priorityid = $rows['priority'];
															$monitor = $rows['monitor'];
															$issuedate = $rows['issuedate'];
															$assignee = $rows['assigned_by'];
															$dateassigned = $rows['date_assigned'];
															$status = $rows['status'];

															$query_issuesrisk = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid='$riskid'");
															$query_issuesrisk->execute();
															$row_issuesrisk = $query_issuesrisk->fetch();
															$risk = $row_issuesrisk['category'];

															$query_issuespriority = $db->prepare("SELECT priority FROM tbl_priorities WHERE id='$priorityid'");
															$query_issuespriority->execute();
															$row_issuespriority = $query_issuespriority->fetch();
															$priority = $row_issuespriority['priority'];

															$query_issue_output = $db->prepare("SELECT i.id, p.projid, p.projcategory, projname, category, observation, recommendation, i.status as status, i.created_by AS monitor, i.date_assigned , i.date_created AS issuedate, i.assigned_by, pr.priority, output FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_priorities pr on pr.id=i.priority  inner join tbl_project_details d on d.id=i.opid inner join tbl_progdetails o on o.id=d.outputid WHERE i.id='$id'");
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
																		$query_issuediscussion =  $db->prepare("SELECT d.status as dstatus FROM `tbl_projissues` i INNER JOIN tbl_projissues_discussions d ON d.issueid =i.id WHERE d.projid ='$prjid' and i.status ='$status' and i.id ='$id' and parent=0");
																		$query_issuediscussion->execute();
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
														} while ($rows = $query_issuesanalysis->fetch());
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
		<!-- Modal Receive Payment -->
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
		<!-- #END# Modal Receive Payment-->
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
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
	});
	$(document).ready(function() {
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