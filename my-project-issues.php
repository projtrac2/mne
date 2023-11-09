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
		$percent2 = calculate_project_progress($projid, $projcategory);


		$query_issues = $db->prepare("SELECT c.catid, c.category, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE projid = :projid");
		$query_issues->execute(array(":projid" => $projid));
		$count_issues = $query_issues->rowCount();

		function get_inspection_status($status_id)
		{
			global $db;
			$sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE id = :status_id");
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
						<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='projects.php'" id="btnback">
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
											<th style="width:37%">Issue</th>
											<th style="width:10%">Category</th>
											<th style="width:10%">Issue Area</th>
											<th style="width:10%">Priority</th>
											<th style="width:10%">Impact</th>
											<th style="width:8%">Status</th>
											<th style="width:12%">Date Recorded</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($count_issues > 0) {
											$nm = 0;

											while ($row_issues = $query_issues->fetch()) {
												$nm = $nm + 1;
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
												
												if($priorityid == 1){
													$priority = "High";
													$priorityclass = 'bg-red';
												}elseif($priorityid == 2){
													$priority = "Medium";
													$priorityclass = 'bg-blue';
												}else{
													$priority = "Low";
													$priorityclass = 'bg-green';
												}
												
												if($issueareaid == 1){
													$issue_area = "Quality";
												}elseif($issueareaid == 2){
													$issue_area = "Scope";
												}elseif($issueareaid == 3){
													$issue_area = "Schedule";
												}else{
													$issue_area = "Cost";
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
													<td <?= $styled ?>><?php echo $status; ?></td>
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


	<!-- Modal Issue Escalation -->
	<div class="modal fade" id="issueOwnerModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#795548">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Issue Owner Details</font>
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