<?php
require('includes/head.php');
if ($permission) {
	try { 

		if (isset($_GET["ind"]) && !empty($_GET["ind"])) {
			$encoded_indid = $_GET["ind"];
			$decode_ind = base64_decode($encoded_indid);
			$ind_array = explode("svyind", $decode_ind);
			$indid = $ind_array[1];
		}
		if (isset($_GET["fm"]) && !empty($_GET["fm"])) {
			$encoded_formid = $_GET["fm"];
			$decode_fm = base64_decode($encoded_formid);
			$fm_array = explode("svyind", $decode_fm);
			$formid = $fm_array[1];
		}
		if (isset($_GET["em"]) && !empty($_GET["em"])) {
			$encoded_email = $_GET["em"];
			$decode_em = base64_decode($encoded_email);
			$em_array = explode("svyind", $decode_em);
			$email = $em_array[1];
		}
		if (isset($_GET["lc"]) && !empty($_GET["lc"])) {
			$encoded_loc = $_GET["lc"];
			$decode_loc = base64_decode($encoded_loc);
			$loc_array = explode("svyind", $decode_loc);
			$locid = $loc_array[1];
		}

		$query_rsEmail = $db->prepare("SELECT * FROM  tbl_projteam2 WHERE email='$email'");
		$query_rsEmail->execute();
		$row_rsEmail = $query_rsEmail->fetch();
		$formuser = $row_rsEmail["ptid"];

		$query_rsSurveyDetails = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_details WHERE formid='$formid' and level3='$locid'");
		$query_rsSurveyDetails->execute();
		$row_rsSurveyDetails = $query_rsSurveyDetails->fetch();

		$query_rsSvyDates = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_forms f inner join tbl_indicator i on i.indid=f.indid WHERE f.id=:frmid AND f.indid=:indid");
		$query_rsSvyDates->execute(array(":frmid" => $formid, ":indid" => $indid));
		$row_rsSvyDates = $query_rsSvyDates->fetch();
		$totalRows_rsSvyDates = $query_rsSvyDates->rowCount();
		$indicator = $row_rsSvyDates["indname"];
		$svylimittype = $row_rsSvyDates["limit_type"];
		$svyrespnumber = $row_rsSvyDates["responses_number"];
		$svystartdate = $row_rsSvyDates["startdate"];
		$svyenddate = $row_rsSvyDates["enddate"];
		$current_date = date("Y-m-d");
		//$email ="denkytheka@gmail.com";

		function submission()
		{
			$digits = "";
			$length = 3;
			$numbers = range(0, 9);
			shuffle($numbers);
			for ($i = 0; $i < $length; $i++) {
				$digits .= $numbers[$i];
			}
			return $digits;
		}
		$submission = submission();

		if (isset($_POST['submit'])) {
			$indid =  $_POST['indid'];
			$formid = $_POST['formid'];
			$lev3id = $_POST['locid'];
			$sectionid = $_POST['sectionid'];
			$subcode = $formid . $submission;

			$sqlinsert = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_submission(indid, formid, level3id, submission_code, email, submission_date) VALUES (:indid, :formid, :level3, :subcode, :email, :date)");
			$submit = $sqlinsert->execute(array(':indid' => $indid, ':formid' => $formid, ':level3' => $lev3id, ':subcode' => $subcode, ':email' => $email, ':date' => $current_date));
			if ($submit) {
				$submissionid = $db->lastInsertId();
				if (isset($_POST['answer']) && !empty($_POST['answer'])) {
					$count = count($_POST['answer']);
					for ($j = 0; $j < $count; $j++) {
						$fieldid = $_POST['fieldid'][$j];
						$answer = $_POST['answer'][$j];

						$sql = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
						$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
					}
				}

				$query_rsFieldRadio = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields WHERE formid=:frmid");
				$query_rsFieldRadio->execute(array(":frmid" => $formid));
				$row_rsFieldRadio = $query_rsFieldRadio->fetchAll();
				$totalRows_rsFieldRadio = $query_rsFieldRadio->rowCount();

				foreach ($row_rsFieldRadio as $frow) {
					if ($frow["fieldtype"] == 'radio-group') {
						$fieldid = $frow['id'];
						$answer = $_POST['rd' . $fieldid];
						if (!empty($answer)) {
							$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
							$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
						}
					} elseif ($frow["fieldtype"] == 'checkbox-group') {
						$fieldid = $frow['id'];
						$answer = $_POST['chk' . $fieldid];
						if (!empty($answer)) {
							for ($i = 0; $i < count($answer); $i++) {
								$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
								$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer[$i]));
							}
						}
					} elseif ($frow["fieldtype"] == 'select' && $frow["multiple"] == 1) {
						$fieldid = $frow['id'];
						$answer = implode(",", $_POST['sm' . $fieldid]);
						if (!empty($answer)) {
							$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
							$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
						}
					}
				}
				$msg = 'The form successfully submitted.';
				$results = "<script type=\"text/javascript\">
								swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 2000,
								showConfirmButton: false });
								setTimeout(function(){
									window.location.href = 'baseline-survey-form?ind=$encoded_indid&fm=$encoded_formid&em=$encoded_email&lc=$encoded_loc';
								}, 2000);
							</script>";
			}
		}
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
						<div class="card-header">
							<h5 class="text-align-center bg-light-blue" style="border-radius:4px; padding:10px"><strong>INDICATOR NAME: <?= $indicator ?></strong></h5>
						</div>
						<div class="body">
							<?php
							$level3 = $row_rsSurveyDetails["level3"];
							$respondents = $row_rsSurveyDetails["respondents"];
							$respondentids = explode(",", $respondents);
							foreach ($respondentids as $respondentid) {
								if ($respondentid == $formuser) {
									$query_surveyLevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id='$level3'");
									$query_surveyLevel3->execute();
									$row_surveyLevel3 = $query_surveyLevel3->fetch();
									$surveyLevel3 = $row_surveyLevel3["state"];
									$surveyLevel3parent = $row_surveyLevel3["parent"];

									$query_surveyLevel2 = $db->prepare("SELECT * FROM tbl_state WHERE id='$surveyLevel3parent'");
									$query_surveyLevel2->execute();
									$row_surveyLevel2 = $query_surveyLevel2->fetch();
									$surveyLevel2 = $row_surveyLevel2["state"];
									$surveyLevel2parent = $row_surveyLevel2["parent"];

									$query_surveyLevel1 = $db->prepare("SELECT * FROM tbl_state WHERE id='$surveyLevel2parent'");
									$query_surveyLevel1->execute();
									$row_surveyLevel1 = $query_surveyLevel1->fetch();
									$surveyLevel1 = $row_surveyLevel1["state"];

									if ($svylimittype == 1) {
										if ($current_date < $svystartdate) {
											echo '<h4 style="color:red"> PLEASE NOTE THAT THIS INDICATOR BASELINE SURVEY START DATE IS NOT YET!!</h4>';
										} elseif ($current_date > $svyenddate) {

											$currstatus = 1;
											$formstatus = 2;
											$svystatus = 3;
											$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
											$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));

											$query_svystatusupdate = $db->prepare("UPDATE tbl_indicator SET surveystatus=:svystatus WHERE indid=:indid");
											$query_svystatusupdate->execute(array(":svystatus" => $svystatus, ":indid" => $indid));

											echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE THAT THIS INDICATOR BASELINE SURVEY PERIOD HAS ENDED AND YOU CAN NOT ACCESS THE SURVEY FORM!!</strong></h5>';
										} elseif ($current_date >= $svystartdate || $current_date <= $svyenddate) {
											require_once("baseline-survey-form-inner.php");
										}
									} else {
										$query_TotalSub = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid ='$indid' AND formid='$formid' GROUP BY submission_code");
										$query_TotalSub->execute();
										$row_TotalSub = $query_TotalSub->fetch();
										$totalRows_TotalSub = $query_TotalSub->rowCount();

										if ($svyrespnumber == $totalRows_TotalSub) {
											$currstatus = 1;
											$formstatus = 2;
											$svystatus = 3;

											$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
											$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));

											$query_svystatusupdate = $db->prepare("UPDATE tbl_indicator SET surveystatus=:svystatus WHERE indid=:indid");
											$query_svystatusupdate->execute(array(":svystatus" => $svystatus, ":indid" => $indid));

											echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE THAT RESPONSES FOR THIS INDICATOR BASELINE SURVEY HAS REACHED THE REQUIRED NUMBER HENCE YOU CAN NOT ACCESS THE SURVEY FORM!!</strong></h5>';
										} elseif ($svyrespnumber > $totalRows_TotalSub) {
											require_once("baseline-survey-form-inner.php");
										}
									}
								}
							}
							?>
							<form method="POST" name="submitsvyfrm" action="" enctype="multipart/form-data" autocomplete="off">
								<?= csrf_token_html(); ?>
								<?php

								if (isset($_GET['fm']) && !empty($_GET['fm'])) {
									$query_rsForm = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:formid");
									$query_rsForm->execute(array(":formid" => $formid));
									$row_rsForm = $query_rsForm->fetch();

									$query_rsSection = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE formid=:formid");
									$query_rsSection->execute(array(":formid" => $formid));
									$row_rsSection = $query_rsSection->fetchAll();
									$totalRows_rsSection = $query_rsSection->rowCount();

									echo '<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>FORM NAME: ' . $row_rsForm["form_name"] . '</strong></h5>
											<h5 class="text-align-center bg-brown" style="border-radius:4px; padding:10px"><strong>SURVEY LOCATION: ' . $surveyLevel1 . ' ' . $level1label . ' - ' . $surveyLevel2 . ' ' . $level2label . ' - ' . $surveyLevel3 . ' ' . $level3label . '</strong></h5>';
									if ($totalRows_rsSection == 0) {
										echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
									} else {
										$count = 0;
										foreach ($row_rsSection as $key) {
											$section = $key['section'];
											$count++;
											$sectionid = $key['id'];
											$query_rsFormfield = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields WHERE formid=:formid AND sectionid=:sectionid ORDER BY id");
											$query_rsFormfield->execute(array(":formid" => $formid, ":sectionid" => $sectionid));
											$row_rsFormfield = $query_rsFormfield->fetchAll();
											$totalRows_rsFormfield = $query_rsFormfield->rowCount();

											if ($totalRows_rsFormfield == 0) {
												echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
											} else {
												$cnt = 0;
												echo '
													<fieldset class="scheduler-border">
														<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Objective ' . $count . ':</label> ' . $section . '</legend>';
												echo '
													<input type="hidden" name="sectionid[]" id="sectionid[]" value="' . $sectionid . '">
													<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover" style="color:#000">
															<thead>
																<tr style="background-color:#607D8B; color:#FFF">
																	<th width="3%">SN</th>
																	<th width="97%">Question</th>
																</tr>
															</thead>
															<tbody>';
												foreach ($row_rsFormfield as $field) {
													$cnt++;
													$type = $field['fieldtype'];
													$requireValidOption = $field['requirevalidoption'];
													$label = $field['label'];
													$subtype = $field['subtype'];
													$style = $field['style'];
													$name = $field['fieldname'];
													$placeholder = $field['placeholder'];
													$description = $field['fielddesc'];
													$other = $field['other'];
													$maxlength = $field['fieldmaxlength'];
													$max = $field['fieldmin'];
													$min = $field['fieldmax'];
													$multiple = $field['multiple'];
													$fieldid = $field['id'];
													//echo '<input type="hidden" name="fieldid[]" id="fieldid" value="'.$fieldid.'">';
													if ($type == "textarea") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*: <font align="left" style="background-color:#eff2f4">
												(' .  $placeholder . '.) </font></label>
												<div class="form-line">
													<p align="left">
														<textarea name="answer[]" cols="45" rows="5" class="txtboxes" id="' . $name . $fieldid . '" required="required"
															style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
															placeholder="' . $placeholder . '"></textarea>
														<script>
														CKEDITOR.replace("' . $name . $fieldid . '", {
															on: {
																instanceReady: function(ev) {
																	// Output paragraphs as <p>Text</p>.
																	this.dataProcessor.writer.setRules("p", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("ol", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("ul", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("li", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																}
															}
														});
														</script>
													</p>
												</div>
											</td>
										</tr>';
													} else if ($type == "radio-group") {
														echo '
										<tr><input type="hidden" name="rdid' . $fieldid . '" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*:</label>
												<div class="form-line">';
														$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
														$query_rsValue->execute(array(":field" => $fieldid));
														$row_rsValue = $query_rsValue->fetchAll();
														$totalRows_rsValue = $query_rsValue->rowCount();
														if ($totalRows_rsValue == 0) {
															echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
														} else {
															$nm = 0;
															echo '
														<div class="demo-radio-button">';
															foreach ($row_rsValue as $row) {
																$nm++;
																$lab = $row['label'];
																$option = $row['val'];
																$vlid = $row['id'];
																echo '
																<input type="radio" name="rd' . $fieldid . '" id="rd' . $sectionid . $fieldid . $vlid . '" value="' . $vlid . '" class="with-gap radio-col-green"  required/>
																<label for="rd' . $sectionid . $fieldid . $vlid . '">' . $lab . '</label>';
															}
															echo '</div>';
														}
														echo '</div>
											</td>
										</tr>';
													} else if ($type == "select" || $type == "autocomplete") {
														$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
														$query_rsValue->execute(array(":field" => $fieldid));
														$row_rsValue = $query_rsValue->fetchAll();
														$totalRows_rsValue = $query_rsValue->rowCount();
														if ($totalRows_rsValue == 0) {
															echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
														} else {
															if ($multiple == 1) {
																echo '
												<tr><input type="hidden" name="smid[]" id="fieldid" value="' . $fieldid . '">
													<td>' . $cnt . '</td>
													<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
														<div class="form-line">
															<select name="sm' . $fieldid . '[]" id="' . $name . '" class="selectpicker" multiple style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">....Select ' . $name . ' first....</option>';
																foreach ($row_rsValue as $row) {
																	$desc = $row['label'];
																	$option = $row['val'];
																	$vlid = $row['id'];
																	echo '<option value="' . $vlid . '">' . $desc . '</option>';
																}
																echo '</select>
														</div>
													</td>
												</tr>';
															} else {
																echo '
												<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
													<td>' . $cnt . '</td>
													<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
														<div class="form-line">
															<select name="answer[]" id="' . $name . '" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">....Select ' . $name . ' first....</option>';
																foreach ($row_rsValue as $row) {
																	$desc = $row['label'];
																	$option = $row['val'];
																	$vlid = $row['id'];
																	echo '<option value="' . $vlid . '">' . $desc . '</option>';
																}
																echo '</select>
														</div>
													</td>
												</tr>';
															}
														}
													} else if ($type == "date") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="date" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId"">
												</div>
											</td>
										</tr>';
													} else if ($type == "header") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
												<' . $subtype . '>' . $label . '</' . $subtype . '>
												</div>
											</td>
										</tr>';
													} else if ($type == "number") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="number" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId" max="' . $max . '" min=" ' . $min . ' ">
													<small id="helpId" class="text-muted">' . $label . '</small>
												</div>
											</td>
										</tr>';
													} else if ($type == "paragraph") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<' . $subtype . '> </' . $subtype . '>
												</div>
											</td>
										</tr>';
													} else if ($type == "checkbox-group") {
														echo '
										<tr><input type="hidden" name="chkid[]" id="chkid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*:</label>
												<div class="form-line">';
														$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
														$query_rsValue->execute(array(":field" => $fieldid));
														$row_rsValue = $query_rsValue->fetchAll();
														$totalRows_rsValue = $query_rsValue->rowCount();
														if ($totalRows_rsValue == 0) {
															echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
														} else {
															$nm = 0;
															echo '
														<div class="demo-checkbox">';
															foreach ($row_rsValue as $row) {
																$nm++;
																$lab = $row['label'];
																$option = $row['val'];
																$vlid = $row['id'];
																echo '
																<input type="checkbox" name="chk' . $fieldid . '[]" id="' . $sectionid . $fieldid . $vlid . '" value="' . $vlid . '" class="filled-in chk-col-light-blue"/>
																<label for="' . $sectionid . $fieldid . $vlid . '">' . $lab . '</label>';
															}
															echo '</div>';
														}
														echo '</div>
											</td>
										</tr>';
													} else if ($type == "autocomplete") {
														$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
														$query_rsValue->execute(array(":field" => $fieldid));
														$row_rsValue = $query_rsValue->fetchAll();
														$totalRows_rsValue = $query_rsValue->rowCount();
														if ($totalRows_rsValue == 0) {
															echo	'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
														} else {
															echo '<input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">';
														}
													} elseif ($type == "text") {
														echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type=" ' . $subtype . '" name="answer[]" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
												</div>
											</td>
										</tr>';
													} elseif ($type == "file") {
														if ($multiple == 1) {
															echo '
											<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
												<td>' . $cnt . '</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<input type="file" name="answer[]" id="' . $name . '" multiple class="form-control form-control-file">
														<small id="helpId" class="text-muted">' . $placeholder . '</small>
													</div>
												</td>
											</tr>';
														} else {
															echo '
											<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
												<td>' . $cnt . '</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<input type="file" name="answer[]" id="' . $name . '"  class="form-control form-control-file">
														<small id="helpId" class="text-muted">' . $placeholder . '</small>
													</div>
												</td>
											</tr>';
														}
													}
												}
												echo '
														</tbody>
													</table>
												</div>
												</div>';
											}
											echo '</fieldset>';
										}
									}
								}
								?>
								<div class="row clearfix">
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
										<a href="baseline-survey-form?ind=<?php echo $encoded_indid; ?>&fm=<?php echo $encoded_formid; ?>&em=<?php echo $encoded_email; ?>" class="btn btn-warning" style="margin-right:10px">Cancel</a>
										<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
										<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
										<input name="locid" type="hidden" id="locid" value="<?php echo $locid; ?>" />
										<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
										<input name="formid" type="hidden" value="<?php echo $formid; ?>" />
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
								</div>
							</form>
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
?>