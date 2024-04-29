<?php
require('includes/head.php');
if ($permission) {
	try {

		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
			$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}

		if (isset($_GET["ind"]) && !empty($_GET["ind"])) {
			$indid = $_GET["ind"];
		}
		if (isset($_GET["frm"]) && !empty($_GET["frm"])) {
			$formid = $_GET["frm"];
		}

		//get the location and date
		$query_rsSubLoc = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission s inner join tbl_state t on t.id=s.level3id WHERE indid='$indid' AND formid='$formid' GROUP BY level3id");
		$query_rsSubLoc->execute();
		$totalRows_rsSubLoc = $query_rsSubLoc->rowCount();

		//get the project name and form Name
		$query_rsFormDetails = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission s INNER JOIN tbl_indicator_baseline_survey_forms f ON f.id=s.formid INNER JOIN tbl_indicator i ON i.indid =s.indid WHERE s.indid=:indid AND s.formid=:formid GROUP BY i.indid");
		$query_rsFormDetails->execute(array(":indid" => $indid, ":formid" => $formid));
		$row_rsFormDetails = $query_rsFormDetails->fetch();
		$totalRows_rsFormDetails = $query_rsFormDetails->rowCount();
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
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
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> All Submissions <span class="badge bg-pink" style="margin-bottom:2px" id="responses"></span></legend>
								<input type="hidden" value="<?php echo $indid; ?>" id="specprojid">
								<input type="hidden" value="<?php echo $formid; ?>" id="specformid">
								<div style="color:#3F51B5; font-size:16px"><strong>Indicator Name:</strong> <?= $row_rsFormDetails["indname"] ?></div>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr class="bg-orange">
												<th style="width:4%"></th>
												<th style="width:4%">#</th>
												<th style="width:30%"><?= $level3label ?></th>
												<th style="width:52%">Correspondent/s</th>
												<th style="width:10%">Submissions</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($totalRows_rsSubLoc == 0) {
											?>
												<tr>
													<td colspan="4">
														<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
													</td>
												</tr>
												<?php } else {
												$nm = 0;
												while ($row_rsSubLoc = $query_rsSubLoc->fetch()) {
													$nm = $nm + 1;
													$lv3id = $row_rsSubLoc["level3id"];
													//get the submission number and date
													$query_rsSubmEmail = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND level3id='$lv3id' GROUP BY email");
													$query_rsSubmEmail->execute();
													$row_rsSubmEmail = $query_rsSubmEmail->fetchAll();
													$totalRows_rsSubmEmail = $query_rsSubmEmail->rowCount();

													//get the number submissions
													$query_rsSubmissions = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND level3id='$lv3id'");
													$query_rsSubmissions->execute();
													$svySubmissions = $query_rsSubmissions->rowCount();

													$submitteremails = [];
													foreach ($row_rsSubmEmail as $row) {
														$submitteremails[] = $row['email'];
													}
													$submitters = implode(",", $submitteremails);
												?>
													<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
														<td align="center" class="mb-0">
															<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">
																<i class="fa fa-plus-square" style="font-size:16px"></i>
															</button>
														</td>
														<td align="center"><?php echo $nm; ?></td>
														<td><?php echo $row_rsSubLoc["state"]; ?></td>
														<td><?php echo $submitters; ?></td>
														<td><?php echo $svySubmissions; ?></td>
													</tr>
													<tr class="collapse order<?php echo $nm; ?>" style="background-color:#FFC107; color:#FFF">
														<th></th>
														<th>#</th>
														<th>Submission</th>
														<th>Correspondent</th>
														<th style="width:30%">Submission Date</th>
													</tr>
													<?php

													$sr = 0;
													$submitterEmail = Explode(",", $submitters);
													foreach ($submitterEmail as $submitter) {
														//get the submission number and date
														$query_rsSubmission = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND email='$submitter' AND level3id='$lv3id'");
														$query_rsSubmission->execute();
														$totalRows_rsSubmission = $query_rsSubmission->rowCount();

														if ($totalRows_rsSubmission == 0) {
													?>
															<tr class="collapse order<?php echo $nm; ?>">
																<td colspan="5">
																	<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
																</td>
															</tr>
															<?php } else {
															$nb = 0;
															while ($row_rsSubmission = $query_rsSubmission->fetch()) {
																$sr++;
																$submissionDate = date("d M Y", strtotime($row_rsSubmission['submission_date']));
																$submission = $row_rsSubmission['submission_code'];
																$submissionid = $row_rsSubmission['id'];

																$nb++;

															?>
																<tr data-toggle="collapse" data-target=".topic<?php echo $nm . $sr; ?>" class="collapse order<?php echo $nm; ?>" style="background-color:#CDDC39">
																	<td align="center" class="mb-0" style="background-color:#FFC107">
																		<button class="btn btn-link">
																			<i class="more-less fa fa-plus-square" style="font-size:16px"></i>
																		</button>
																	</td>
																	<td align="center"> <?php echo $nm . "." . $sr; ?></td>
																	<td> <?php echo "Submission " . $sr; ?></td>
																	<td> <?php echo $row_rsSubmission['email']; ?></td>
																	<td colspan="2"><?php echo $submissionDate; ?></td>
																</tr>
																<?php
																$query_rsSection = $db->prepare("SELECT q.sectionid, s.section  FROM tbl_indicator_baseline_survey_form_question_fields q INNER JOIN tbl_indicator_baseline_survey_form_sections s on s.id =q.sectionid  WHERE q.formid='$formid' GROUP BY q.sectionid");
																$query_rsSection->execute();
																$totalRows_rsSection = $query_rsSection->rowCount();
																?>
																<tr class="collapse topic<?php echo $nm . $sr; ?>" style="background-color:#FFEB3B; color:#000">
																	<th style="background-color:#FFC107"></th>
																	<th>#</th>
																	<th colspan="3">Objectives</th>
																</tr>
																<?php
																if ($totalRows_rsSection == 0) {
																?>
																	<tr class="collapse topic<?php echo $nm . $sr; ?>">
																		<td colspan="5">
																			<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
																		</td>
																	</tr>
																	<?php } else {
																	$num = 0;
																	while ($row_rsSection = $query_rsSection->fetch()) {
																		$sectionid = $row_rsSection['sectionid'];
																		$section = $row_rsSection['section'];
																		$num = $num + 1;
																		$query_rsFieldAnswer = $db->prepare("SELECT a.fieldid, a.answer, q.fieldtype, q.label, q.multiple FROM  tbl_indicator_baseline_survey_answers a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q on q.id =a.fieldid WHERE a.submissionid='$submissionid' AND q.sectionid='$sectionid'");
																		$query_rsFieldAnswer->execute();
																		$totalRows_rsFieldAnswer = $query_rsFieldAnswer->rowCount();

																	?>
																		<tr data-toggle="collapse" data-target=".qry<?php echo $nm . $sr . $num; ?>" class="collapse topic<?php echo $nm . $sr; ?>" style="background-color:#CDDC39">
																			<td align="center" class="mb-0" style="background-color:#FFC107">
																				<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!"> <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
																				</button>
																			</td>
																			<td align="center" style="background-color:#FFEB3B"> <?php echo $nm . "." . $sr . "." . $num; ?></td>
																			<td colspan="3"><?php echo $section; ?></td>
																		</tr>
																		<tr class="collapse qry<?php echo $nm . $sr . $num; ?>" style="background-color:#b8f9cb; color:#FFF">
																			<th style="background-color:#FFC107"></th>
																			<th style="background-color:#FFEB3B">#</th>
																			<th style="width:40%">Question </th>
																			<th colspan="2" style="width:40%">Answer</th>
																		</tr>
																		<?php
																		$nmb = 0;
																		while ($row_rsFieldAnswer = $query_rsFieldAnswer->fetch()) {
																			$nmb++;
																			$answerVal = $row_rsFieldAnswer['answer'];
																			$label = $row_rsFieldAnswer['label'];
																			$ftype = $row_rsFieldAnswer['fieldtype'];
																			$fid = $row_rsFieldAnswer['fieldid'];
																			$multiple = $row_rsFieldAnswer['multiple'];

																			if ($ftype == "checkbox-group" || $ftype == "radio-group" || $ftype == "select") {
																				if ($multiple == 1) {
																					$answerArr = [];
																					$arrAnswers = Explode(",", $answerVal);
																					foreach ($arrAnswers as $arrAnswer) {
																						$query_rsFieldID = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_form_question_field_values WHERE id='$arrAnswer'");
																						$query_rsFieldID->execute();
																						$row_rsFieldID = $query_rsFieldID->fetch();
																						$answerArr[] = $row_rsFieldID["label"];
																					}
																					$answer = implode(",", $answerArr);
																				} else {
																					$query_rsFieldID = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_form_question_field_values WHERE id='$answerVal'");
																					$query_rsFieldID->execute();
																					$row_rsFieldID = $query_rsFieldID->fetch();
																					$answer = $row_rsFieldID["label"];
																				}
																			} else {
																				$answer = $answerVal;
																			}
																		?>
																			<tr class="collapse qry<?php echo $nm . $sr . $num; ?>" style="background-color:#FFF">
																				<td style="background-color:#FFC107"></td>
																				<td align="center" style="background-color:#FFEB3B"><?php echo $nm . "." . $sr . "." . $num . "." . $nmb; ?></td>
																				<td><?php echo $label; ?></td>
																				<td colspan="2"> <?php echo $answer; ?></td>
																			</tr>
											<?php
																		}
																	}
																}
															}
														}
													}
												}
											}
											?>
										</tbody>
										<script type="text/javascript">
											/*******************************
											 * ACCORDION WITH TOGGLE ICONS
											 *******************************/
											function toggleIcon(e) {
												$(e.target)
													.find(".more-less")
													.toggleClass('fa fa-plus-square fa fa-minus-square');
											}
											$('.mb-0').on('hidden.bs.collapse', toggleIcon);
											$('.mb-0').on('shown.bs.collapse', toggleIcon);
										</script>
									</table>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
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
					<?= csrf_token_html(); ?>
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
	$(document).ready(function() {

		function load_evaluation_responses(view = '') {
			var prjid = $("#specprojid").val();
			var frmid = $("#specformid").val();
			$.ajax({
				url: "project-evaluation-responses.php",
				method: "POST",
				data: {
					view: view,
					projid: prjid,
					formid: frmid
				},
				dataType: "json",
				success: function(data) {
					if (data.all_responses > 0) {
						$('#responses').html(data.all_responses);
					}
				}
			});
		}

		load_evaluation_responses();

		setInterval(function() {
			load_evaluation_responses();
		}, 2000);

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