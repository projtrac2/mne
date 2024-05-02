<?php
try {
	require('includes/head.php');
	if ($permission) {

		$query_escalated_issues = $db->prepare("SELECT i.id, i.issue_area, i.issue_priority, i.issue_impact, category, issue_description, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate, i.projid, projname FROM tbl_projissues i INNER JOIN tbl_projects p on i.projid=p.projid inner join tbl_projrisk_categories c ON c.catid=i.risk_category WHERE (status=0 OR status=2) AND 	issue_area<>5");
		$query_escalated_issues->execute();
		$totalRows_escalated_issues = $query_escalated_issues->rowCount();

		function string_length($x, $length)
		{
			$y = "";
			if (strlen($x) <= $length) {
				$y = $x;
				return $y;
			} else {
				$y = substr($x, 0, $length) . ' <span class="text-danger"><strong>...</strong></span>';
				return $y;
			}
		}


		function super_user($roleid)
		{
			$x = false;
			if ($roleid == 1) {
				$x = true;
			}
			return $x;
		}

		$superuser = super_user($designation_id);

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
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<th style="width:4%">#</th>
														<th style="width:25%">Issue Description</th>
														<th style="width:25%">Project Name</th>
														<th style="width:12%">Issue Priority</th>
														<th style="width:12%">Date Escalated</th>
														<th style="width:12%">Issue Status</th>
														<th style="width:10%" data-orderable="false">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if ($totalRows_escalated_issues > 0) {
														$nm = 0;
														while ($row_issues = $query_escalated_issues->fetch()) {
															$nm = $nm + 1;
															$issueid = $row_issues['id'];
															$projid = $row_issues['projid'];
															$project = $row_issues['projname'];
															$risk_category = $row_issues['category'];
															$issue_description = $row_issues['issue_description'];
															$issue_priority = $row_issues['issue_priority'];
															$issue_areaid = $row_issues['issue_area'];
															$issue_impact = $row_issues['issue_impact'];
															$monitorid = $row_issues['monitor'];
															$recommendation = $row_issues['recommendation'];
															$issuedate = $row_issues['issuedate'];
															$issuestatus = $row_issues['status'];

															if ($superuser || $monitorid == $user_name) {
																$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=1 and active=1");
																$query_timeline->execute();
																$row_timeline = $query_timeline->fetch();
																$timelineid = $row_timeline["id"];
																$time = $row_timeline["time"];
																$units = $row_timeline["units"];
																$stgstatus = $row_timeline["status"];

																if ($issue_priority == 1) {
																	$priority = "High";
																} elseif ($issue_priority == 2) {
																	$priority = "Medium";
																} elseif ($issue_priority == 3) {
																	$priority = "Low";
																} else {
																	$priority = "Unknown";
																}

																if ($issue_areaid == 1) {
																	$issue_area = "Quality";
																} elseif ($issue_areaid == 2) {
																	$issue_area = "Scope";
																} elseif ($issue_areaid == 3) {
																	$issue_area = "Schedule";
																} else {
																	$issue_area = "Cost";
																}

																$duedate = strtotime($issuedate . "+ " . $time . " " . $units);
																$actionnduedate = date("d M Y", $duedate);

																$current_date = date("Y-m-d");
																$actduedate = date("Y-m-d", $duedate);

																if ($issuestatus == 0) {
																	if ($actduedate >= $current_date) {
																		$actionstatus = $stgstatus;
																		$styled = 'style="color:blue"';
																	} elseif ($actduedate < $current_date) {
																		$actionstatus = "Behind Schedule";
																		$styled = 'style="color:red"';
																	}
																} else {
																	$query_issue_status = $db->prepare("SELECT status FROM tbl_issue_status WHERE statuskey=:statuskey");
																	$query_issue_status->execute(array(":statuskey" => $issuestatus));
																	$row_issue_status = $query_issue_status->fetch();

																	$actionstatus = $row_issue_status["status"];
																	$styled = 'style="color:green"';
																}

																$actiondate = $actionnduedate;
																$query_owner = $db->prepare("SELECT tt.title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid='$monitorid'");
																$query_owner->execute();
																$row_owner = $query_owner->fetch();
																$monitor = $row_owner["title"] . '.' . $row_owner["fullname"];

																$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and issueid='$issueid'");
																$query_escalationstage->execute();
																$escalationstage_count = $query_escalationstage->rowCount();
																$assessmentcomments = array();
																while ($row_escalationstage = $query_escalationstage->fetch()) {
																	$assessmentcomments[] = $row_escalationstage["issue_status"];
																}

																$issuedescription = string_length($issue_description, 100);

																$issueid_encrypted = base64_encode("issueid254{$issueid}");
													?>
																<tr style="background-color:#eff9ca">
																	<td align="center"><?php echo $nm; ?></td>
																	<td><?php echo $issuedescription; ?></td>
																	<td><?php echo $project; ?></td>
																	<td><?php echo $priority; ?></td>
																	<td class="text-primary"><span data-toggle="tooltip" data-placement="bottom" title="Recorded By: <?= $monitor ?>"><?php echo date("d M Y", strtotime($issuedate)); ?></span></td>
																	<td <?= $styled ?>><?= $actionstatus ?></td>
																	<td align="center">
																		<?php
																		if ($escalationstage_count > 0) {
																		?>
																			<a href="project-escalated-issue.php?issue=<?= $issueid_encrypted ?>"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Issue assessment report ready"></i></a>
																		<?php
																		} else {
																		?>
																			<a href="project-escalated-issue.php?issue=<?= $issueid_encrypted ?>"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Issue requiring your ACTION!!"></i></a>
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
					</div>
				</div>
			</div>
		</section>
		<!-- end body  -->
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
</script>