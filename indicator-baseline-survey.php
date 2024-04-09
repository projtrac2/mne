<?php
	try {

require('includes/head.php');
if ($permission) {
		$query_survey = $db->prepare("SELECT * FROM tbl_indicator WHERE data_source=2 AND baseline=0 AND surveystatus=0 AND active='1' ORDER BY indid ASC");
		$query_survey->execute();
		$count_survey = $query_survey->rowCount();

		$query_survey_form = $db->prepare("SELECT I.indid, id, indname, indcategory, fullname, title, form_name, limit_type, responses_number, startdate, enddate, date_created FROM tbl_indicator I INNER JOIN tbl_indicator_baseline_survey_forms F on F.indid=I.indid inner join  tbl_projteam2 M on M.ptid=F.responsible WHERE I.baseline=0 AND I.surveystatus=1 AND F.status=0 ORDER BY F.id ASC");
		$query_survey_form->execute();
		$count_survey_form = $query_survey_form->rowCount();

		$query_active_survey = $db->prepare("SELECT * FROM tbl_indicator i INNER JOIN tbl_indicator_baseline_survey_forms f on f.indid=i.indid WHERE i.surveystatus=2 AND f.status=1 ORDER BY f.id");
		$query_active_survey->execute();
		$count_active_survey = $query_active_survey->rowCount();

		$query_surveyed = $db->prepare("SELECT * FROM tbl_indicator i INNER JOIN tbl_indicator_baseline_survey_forms f on f.indid=i.indid WHERE i.baseline=0 AND f.status=2 ORDER BY f.indid");
		$query_surveyed->execute();
		$count_surveyed = $query_surveyed->rowCount();
	
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
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home"><i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Indicators Requiring Survey &nbsp;<span class="badge bg-orange"><?php echo $count_survey; ?></span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Forms Ready For Deployment&nbsp;<span class="badge bg-blue-grey"><?php echo $count_survey_form; ?></span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2"><i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Active Indicator Surveys&nbsp;<span class="badge bg-light-blue"><?php echo $count_active_survey; ?></span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu3"><i class="fa fa-check-square-o bg-light-green" aria-hidden="true"></i> Completed Indicator Surveys&nbsp;<span class="badge bg-light-green"><?php echo $count_surveyed; ?></span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="table-responsive">
								<div class="tab-content">
									<div id="home" class="tab-pane fade in active">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:3%">#</th>
													<th style="width:16%">Indicator Category</th>
													<th style="width:52%">Indicator</th>
													<th style="width:12%">Overdue date</th>
													<th style="width:10%">Status</th>
													<th style="width:7%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$nm = 0;
												while ($row_survey = $query_survey->fetch()) {
													$nm = $nm + 1;
													$indid = $row_survey['indid'];
													$indicator = $row_survey['indname'];
													$indcategory = $row_survey['indcategory'];
													$mntry = $row_survey['indsector'];
													$dept = $row_survey['indcategory'];
													$inddate = $row_survey['date_changed'];

													$overduedate = strtotime($inddate . " + 60 days");
													$svyoverduedate = date('Y-m-d', $overduedate);
													$surveyoverduedate = date("d M Y", $overduedate);

													$query_ministry =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mntry'");
													$query_ministry->execute();
													$row_ministry = $query_ministry->fetch();
													$ministry = $row_ministry["sector"];

													$query_dept =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$dept'");
													$query_dept->execute();
													$row_dept = $query_dept->fetch();
													$department = $row_dept["sector"];

													$current_date = date("Y-m-d");

													if ($current_date <= $svyoverduedate) {
														$status = "Open";
													} elseif ($current_date > $svyoverduedate) {
														$status = "Overdue";
													}

												?>
													<tr style="background-color:#eff9ca">
														<td align="center"><?php echo $nm; ?></td>
														<td><?php echo $indcategory; ?></td>
														<td><?php echo $indicator; ?></td>
														<td><?php echo $surveyoverduedate; ?></td>
														<?php if ($current_date <= $svyoverduedate) { ?>
															<td style="color:green"><strong><?php echo $status; ?></strong></td>
														<?php } elseif ($current_date > $svyoverduedate) { ?>
															<td style="color:red"><strong><?php echo $status; ?></strong></td>
														<?php } ?>
														<td>
															<div align="center">
																<a href="create-indicator-survey-form?ind=<?php echo $indid; ?>" alt="Create Indicator Baseline Survey Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Create Indicator Baseline Survey Form"><i class="fa fa-american-sign-language-interpreting fa-2x text-success" aria-hidden="true"></i></a>
															</div>
														</td>
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
									</div>
									<div id="menu1" class="tab-pane fade">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-blue-grey">
													<th style="width:3%">#</th>
													<th style="width:15%">Form Name</th>
													<th style="width:30%">Indicator</th>
													<th style="width:10%">Category</th>
													<th style="width:15%">Responsible</th>
													<th style="width:10%">Due date</th>
													<th style="width:10%">Status</th>
													<th style="width:7%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$nm = 0;
												while ($row_survey_form = $query_survey_form->fetch()) {
													$nm = $nm + 1;

													$indid = $row_survey_form['indid'];
													$indicator = $row_survey_form['indname'];
													$indcategory = $row_survey_form['indcategory'];
													$formid = $row_survey_form['id'];
													$formname = $row_survey_form['form_name'];
													$title = $row_survey_form['title'];
													$fullname = $row_survey_form['fullname'];
													$responsible = $title . "." . $fullname;
													$limittype = $row_survey_form["limit_type"];
													$responsesnumber = $row_survey_form["responses_number"];
													$surveysdate = $row_survey_form["startdate"];
													$surveyedate = $row_survey_form["enddate"];
													$datecreated = $row_survey_form["date_created"];

													$overduedate = strtotime($datecreated . " + 30 days");
													$svyoverduedate = date('Y-m-d', $overduedate);
													$surveyoverduedate = date("d M Y", $overduedate);

													if ($current_date <= $svyoverduedate) {
														$status = "Pending";
													} elseif ($current_date > $svyoverduedate) {
														$status = "Overdue";
													}
												?>
													<tr style="background-color:#eff9ca">
														<td style="width:3%" align="center"><?php echo $nm; ?></td>
														<td style="width:15%"><?php echo $formname; ?></td>
														<td style="width:30%"><?php echo $indicator; ?></td>
														<td style="width:10%"><?php echo $indcategory; ?></td>
														<td style="width:15%"><?php echo $responsible; ?></td>
														<td style="width:10%"><?php echo $surveyoverduedate; ?></td>
														<td style="width:10%"><span data-toggle="tooltip" data-placement="bottom" <?php if ($limittype == 1) { ?> title="<?php echo 'Start Date: ' . date("d M Y", strtotime($surveysdate)) . '; End Date: ' . date("d M Y", strtotime($surveyedate)); ?>" <?php } else { ?> title="<?php echo 'Maximum Required Responses: ' . $responsesnumber; ?>" <?php } ?> style="color:#2196F3"><?php echo $status; ?></span></td>
														<td style="width:7%">
															<div align="center">
																<a href="deploy-survey-form?ind=<?php echo $indid; ?>&frm=<?php echo $formid; ?>" alt="Deploy this Indicator Baseline Survey Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Deploy this Indicator Baseline Survey Form" style="color:#9C27B0">
																	<i class="fa fa-paper-plane-o" aria-hidden="true"></i> DEPLOY
																</a>
															</div>
														</td>
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
									</div>
									<div id="menu2" class="tab-pane fade">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-light-blue">
													<th style="width:3%">#</th>
													<th style="width:27%">Form Name</th>
													<th style="width:40%">Indicator</th>
													<th style="width:10%">Status</th>
													<th style="width:10%">Responses</th>
													<th style="width:10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$nm = 0;
												while ($row_active_survey = $query_active_survey->fetch()) {
													$nm = $nm + 1;
													$indid = $row_active_survey['indid'];
													$indicator = $row_active_survey['indname'];
													$indcategory = $row_active_survey['indcategory'];
													$svystartdate = $row_active_survey['startdate'];
													$svyenddate = $row_active_survey['enddate'];
													$formid = $row_active_survey['id'];
													$formname = $row_active_survey['form_name'];
													$svytype = $row_active_survey["type"];
													$limittype = $row_active_survey["limit_type"];
													$responsesnumber = $row_active_survey["responses_number"];

													$query_TotalSub = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid ='$indid' AND formid='$formid' GROUP BY submission_code");
													$query_TotalSub->execute();
													$totalRows_TotalSub = $query_TotalSub->rowCount();

													$query_TotalLoc = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_details WHERE formid='$formid'");
													$query_TotalLoc->execute();
													$totalRows_TotalLoc = $query_TotalLoc->rowCount();

													//$overduedate = strtotime($datecreated. " + 30 days");
													$svyoverduedate = date('Y-m-d',  strtotime($svyenddate));
													//$surveyoverduedate = date("d M Y", $overduedate);

													$current_date = date("Y-m-d");

													if ($current_date <= $svyoverduedate) {
														$status = '<span class="badge bg-green" style="margin-bottom:2px">On Going</span>';
													} elseif ($current_date > $svyoverduedate) {
														$status = '<span class="badge bg-red" style="margin-bottom:2px">Overdue</span>';
													}
												?>
													<tr style="background-color:#eff9ca">
														<td style="width:3%" align="center"><?php echo $nm; ?></td>
														<td style="width:27%"><span width="16" height="16" style="color:blue" data-toggle="tooltip" data-placement="bottom" title="<?php if ($limittype == 1) {
																																														echo "Start Date: " . date("d M Y", strtotime($svystartdate)) . "; End Date: " . date("d M Y", strtotime($svyenddate));
																																													} else {
																																														echo "Required Responses: " . $responsesnumber;
																																													} ?>"><?php echo $formname; ?></span></td>
														<td style="width:40%"><span width="16" height="16" style="color:blue" data-toggle="tooltip" data-placement="bottom" title="<?php echo "Indicator Category: " . $indcategory . "; Survey Locations: " . $totalRows_TotalLoc . " " . $level3label . "/s"; ?>"><?php echo $indicator; ?></td>
														<td style="width:10%"><?php echo $status; ?></td>
														<td style="width:10%">
															<div align="center"><a href="indicator-survey-submissions?ind=<?php echo $indid; ?>&frm=<?php echo $formid; ?>" alt="Click to view Indicator Baseline Survey Submissions" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Click to view Indicator Baseline Survey Submissions">
																	<span class="badge bg-purple" style="margin-bottom:2px" id="<?php echo "resp" . $indid; ?>"><?php echo $totalRows_TotalSub; ?></span></a>
															</div>
														</td>
														<td style="width:10%" align="center">
															<div class="btn-group">
																<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Options <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li>
																		<a type="button" data-toggle="modal" id="closeSurveyModalBtn" data-target="#closeSurveyModal" onclick="closesurvey(<?= $formid ?>)"> <i class="glyphicon glyphicon-edit"></i>Close</a>
																	</li>
																	<!--<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>       
											<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-project.php?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
											<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>  -->
																</ul>
															</div>
														</td>
														<input type="hidden" name="username" value="<?= $user_name ?>" id="username">
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
									</div>
									<div id="menu3" class="tab-pane fade">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-light-green">
													<th style="width:3%">#</th>
													<th style="width:27%">Form Name</th>
													<th style="width:40%">Indicator</th>
													<th style="width:10%">Type</th>
													<th style="width:10%">Responses</th>
													<th style="width:10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$nm = 0;
												while ($row_surveyed = $query_surveyed->fetch()) {
													$nm = $nm + 1;
													$indid = $row_surveyed['indid'];
													$indicator = $row_surveyed['indname'];
													$svystatus = $row_surveyed['surveystatus'];
													$svystartdate = $row_surveyed['startdate'];
													$svyenddate = $row_surveyed['enddate'];
													$formid = $row_surveyed['id'];
													$formname = $row_surveyed['form_name'];
													$indtype = $row_surveyed["indcategory"];
													$limittype = $row_surveyed["limit_type"];
													$responsesnumber = $row_surveyed["responses_number"];

													$query_survey = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE formid='$formid'");
													$query_survey->execute();
													$count_survey = $query_survey->rowCount();
												?>
													<tr style="background-color:#eff9ca">
														<td style="width:3%" align="center"><?php echo $nm; ?></td>
														<td style="width:27%"><?php echo $formname; ?></td>
														<td style="width:40%"><?php echo $indicator; ?></td>
														<td style="width:10%"><?php echo $indtype; ?></td>
														<td style="width:10%" align="center"><span class="badge bg-purple" style="margin-bottom:2px" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="<?php if ($limittype == 1) {
																																																										echo "Survey Start Date: " . date("d M Y", strtotime($svystartdate)) . "; Survey End Date: " . date("d M Y", strtotime($svyenddate));
																																																									} else {
																																																										echo "Survey Required Responses: " . $responsesnumber;
																																																									} ?>"><?= $count_survey ?></span></td>
														<td style="width:10%">
															<div align="center">
																<a href="indicator-baseline-survey-conclusion?ind=<?= $indid ?>&fm=<?= $formid ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Indicator Survey Conclusion and Recommendation/s"><i class="fa fa-pencil-square-o fa-2x text-primary" aria-hidden="true"></i></a>
															</div>
														</td>
													</tr>
												<?php
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
	</section>
	<!-- end body  -->
	<!-- Start Modal Item approve -->
	<div class="modal fade" id="closeSurveyModal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Close Indicator Baseline Survey</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="baselinesurveyForm" action="indicator-baseline-survey-form-processing.php" method="POST" enctype="multipart/form-data" autocomplete="off">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exchange" aria-hidden="true"></i> Survey Information</legend>
								<br />
								<div class="col-md-12" id="surveyform"></div>
								<div class="modal-footer approveItemFooter">
									<div class="col-md-12 text-center">
										<input type="hidden" name="closesurvey" id="closesurvey" value="1">
										<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel Action</button>
										<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="survey-form-submit" value="Save & Close Survey" />
									</div>
								</div> <!-- /modal-footer -->
							</fieldset>
						</form> <!-- /.form -->
					</div>
				</div> <!-- /modal-body -->
			</div>
			<!-- /modal-content -->
		</div>
	</div>
	<!-- /.modal -->
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="assignModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Milestone Inspection Assignment</font>
					</h3>
				</div>
				<form class="tagForm" action="inspectorassignment" method="post" id="assign-inspection-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="checklistassignment">
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
<?php
} else {
	$results =  restriction();
	echo $results;
}
} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$('#assign-inspection-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "inspectorassignment.php",
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

	function CallChecklistAssignment(msid) {
		$.ajax({
			type: 'post',
			url: 'callchecklistassignment.php',
			data: {
				msid: msid
			},
			success: function(data) {
				$('#checklistassignment').html(data);
				$("#assignModal").modal({
					backdrop: "static"
				});
			}
		});
	}
	$(document).ready(function() {

		function load_evaluation_responses(view = '') {
			$.ajax({
				url: "evaluation-responses-count.php",
				method: "POST",
				data: {
					view: view
				},
				dataType: "json",
				success: function(data) {
					if (data.all_responses > 0) {
						$('#resp' + data.projid).html(data.all_responses);
					}
				}
			});
		}

		load_evaluation_responses();

		setInterval(function() {
			load_evaluation_responses();
		}, 2000);

	});

	function closesurvey(formid = null) {
		if (formid) {
			var username = $("#username").val();
			$.ajax({
				url: "general-settings/selected-items/fetch-selected-indicator-survey.php",
				type: "post",
				data: {
					formid: formid,
					username: username
				},
				dataType: "html",
				success: function(response) {
					$("#surveyform").html(response);
				} // /success function
			}); // /ajax to fetch Project Main Menu  image
		} else {
			alert("error please refresh the page");
		}
	}

	$(document).ready(function() {
		$('#baselinesurveyForm').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type: "post",
				url: "indicator-baseline-survey-form-processing.php",
				data: $(this).serialize(),
				dataType: "html",
				success: function(response) {
					if (response) {
						$('#closeSurveyModal').modal('hide');
						$("#processingid").html(response);
					}
				}
			});
		});
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
</script>