		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> INDICATOR BASELINE SURVEY CONCLUSION
				</h4>
				<?php
				echo $recommendationresults;
				?>
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body" style="margin-top:5px">
							<div class="wizard">
								<div class="wizard-inner" style="margin-top:-20px">
									<div class="connecting-line"></div>
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Project Survey Summary">
												<span class="round-tab">
													SUMMARY
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Survey Form Responses">
												<span class="round-tab">
													FORM <span class="badge bg-green"><?php echo $totalRows_rsSubmission; ?></span>
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Survey Raw Data">
												<span class="round-tab">
													DATA
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step4" data-toggle="tab" aria-controls="step3" role="tab" title="Baseline Information">
												<span class="round-tab">
													BASELINE
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step5" data-toggle="tab" aria-controls="step4" role="tab" title="Survey Conclusion & Recommendations">
												<span class="round-tab">
													CONCLUSION
												</span>
											</a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane active" role="tabpanel" id="step1">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Survey Summary
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="col-md-12">
														<strong>Indicator Name: <font color="#3F51B5"><?php echo $indicator; ?></font></strong>
													</div>
													<div class="col-md-6">
														<label class="control-label">Indicator Category:</label>
														<div class="form-line">
															<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
																<strong><?php echo $indcategory; ?></strong>
																<input name="indid" id="indid" type="hidden" value="<?= $indid ?>">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<label class="control-label">Indicator Unit of Measure:</label>
														<div class="form-line">
															<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
																<strong><?php echo $indunitdesc . " (" . $indunit . ")"; ?></strong>
																<input name="indid" id="indid" type="hidden" value="<?= $indid ?>">
															</div>
														</div>
													</div>

													<?php
													if ($totalRows_surveysummary > 0) {

														$sn = 0;
														while ($summary = $query_surveysummary->fetch()) {
															$sn = $sn + 1;
															$obj = $summary["section"];
															$objid = $summary["id"];

															$query_objquestions =  $db->prepare("SELECT * FROM `tbl_indicator_baseline_survey_answers` a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id =a.fieldid WHERE q.formid = '$formid' AND q.sectionid = '$objid' GROUP BY a.fieldid ORDER BY q.id ASC");
															$query_objquestions->execute();
															$row_objquestions = $query_objquestions->fetchAll();
													?>
															<div class="col-md-12">
																<?php
																echo '<strong><u><h5 style="color:blue">Objective ' . $sn . ': <font color="green">' . $obj . '</font></h5></u></strong>';
																?>
															</div>

															<?php
															foreach ($row_objquestions as $row) {
																$fieldid = $row['fieldid'];
																$type = $row['fieldtype'];
																if ($type == "select" || $type == "radio-group") {
																	$query_rsAnswers = $db->prepare("SELECT q.label AS label, v.label AS answer  FROM `tbl_indicator_baseline_survey_answers` a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id =a.fieldid INNER JOIN tbl_indicator_baseline_survey_form_question_field_values v ON v.id =a.answer WHERE q.sectionid=:objectiveid AND a.fieldid=:fieldid");
																	$query_rsAnswers->execute(array(":objectiveid" => $objid, ":fieldid" => $fieldid));
																	$row_rsAnswers = $query_rsAnswers->fetchAll();
																	$totalRows_rsAnswers = $query_rsAnswers->rowCount();
																	$answer = array();
																	foreach ($row_rsAnswers as $data) {
																		$answer[] = $data['answer'];
																	}
																	$data = array_count_values($answer);
															?>
																	<div class="col-md-6" style="height: 600px; border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5" id="chart_div<?php echo $objid . $fieldid; ?>"> </div>
																	<script type="text/javascript">
																		google.load("visualization", "1", {
																			packages: ["corechart"]
																		});
																		google.setOnLoadCallback(drawChart);

																		function drawChart() {
																			var data = google.visualization.arrayToDataTable([
																				['Label', 'Count'],
																				<?php
																				foreach ($data as $key => $value) {
																					echo "['" . $key . "'," . $value . "],";
																				}
																				?>
																			]);
																			var options = {
																				title: '<?php echo $row['label']; ?>',
																				is3D: true,
																				pieSliceTextStyle: {
																					color: 'black',
																				}
																			};
																			var chart = new google.visualization.PieChart(document.getElementById("chart_div<?php echo $objid . $fieldid; ?>"));
																			chart.draw(data, options);
																		}
																	</script>
																<?php
																} else {
																?>
																	<div class="col-md-6">
																		<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="section<?php echo $objid . $fieldid ?>">
																			<thead>
																				<tr class="bg-light-blue">
																					<th style="width:3%">ANS\QST</th>
																					<?php
																					$question = $row["label"];
																					//$questionid = $query["id"];
																					?>
																					<th><?= $question ?></th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				//$fieldid =$query['fieldid'];
																				$query_rsFieldquestion = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields q INNER JOIN  tbl_indicator_baseline_survey_answers a ON a.fieldid=q.id WHERE q.sectionid=:objectiveid AND a.fieldid=:fieldid ORDER BY q.id");
																				$query_rsFieldquestion->execute(array(":objectiveid" => $objid, ":fieldid" => $fieldid));
																				$row_rsFieldquestion = $query_rsFieldquestion->fetchAll();
																				$totalRows_rsFieldquestion = $query_rsFieldquestion->rowCount();

																				$nmb = 0;
																				foreach ($row_rsFieldquestion as $group) {
																					$nmb++;
																				?>
																					<tr>
																						<td align="center">
																							<?php echo $nmb; ?>
																						</td>
																						<?php
																						$fieldtype = $group['fieldtype'];
																						$vlid = $group['answer'];
																						if ($fieldtype == "checkbox-group" || $fieldtype == "radio-group" || $fieldtype == "select") {
																							$query_rsValueName = $db->prepare("SELECT label FROM `tbl_indicator_baseline_survey_form_question_field_values` WHERE id=:valid");
																							$query_rsValueName->execute(array(":valid" => $vlid));
																							$row_rsValueName = $query_rsValueName->fetch();
																							$answer = $row_rsValueName["label"];
																						} else {
																							$answer = $group["answer"];
																						}
																						?>
																						<td>
																							<?php echo $answer; ?>
																						</td>
																					</tr>
																				<?php
																				}
																				?>
																			</tbody>
																		</table>
																		<script>
																			$(document).ready(function() {
																				$('#section<?php echo $objid . $fieldid ?>').DataTable();
																			});
																		</script>
																	</div>
													<?php
																}
															}
														}
													} else {
														echo '<div class="col-md-12">
															<h5 style="color:red">Sorry no data found for this evaluation form<h5>
														</div>';
													}
													?>
												</div>
											</div>
										</fieldset>
									</div>
									<div class="tab-pane" role="tabpanel" id="step2">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Form Submissions</legend>
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
									<div class="tab-pane" role="tabpanel" id="step3">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Data</legend>

											<?php
											if ($totalRows_surveyobjs > 0) {
											?>
												<?php
												$sn = 0;
												while ($row_surveyobjs = $query_surveyobjs->fetch()) {
													$sn++;
													$objective = $row_surveyobjs["section"];
													$objectiveid = $row_surveyobjs["id"];

													$query_objquestions =  $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields WHERE formid = '$formid' AND sectionid = '$objectiveid' ORDER BY id");
													$query_objquestions->execute();
													$row_objquestions = $query_objquestions->fetchAll();
												?>
													<div class="col-md-12">
														<?php
														echo '<strong><u><h5 style="color:blue">Objective ' . $sn . ': <font color="green">' . $objective . '</font></h5></u></strong>';
														?>
													</div>
													<div class="col-md-12">
														<?php
														$query_rsFieldquestions = $db->prepare("SELECT * FROM `tbl_indicator_baseline_survey_answers` a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id =a.fieldid WHERE q.sectionid=:objectiveid GROUP BY q.id");
														$query_rsFieldquestions->execute(array(":objectiveid" => $objectiveid));
														$row_rsFieldquestions = $query_rsFieldquestions->fetchAll();
														$totalRows_rsFieldquestions = $query_rsFieldquestions->rowCount();
														$nm = 0;
														?>
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="section<?php echo $sectionid ?>">
															<thead class="thead-inverse">
																<tr class="bg-light-blue">
																	<th style="width:3%">ANS\QST</th>
																	<?php
																	foreach ($row_objquestions as $query) {
																		$question = $query["label"];
																		//$questionid = $query["id"];
																	?>
																		<th><?= $question ?></th>
																	<?php } ?>
																</tr>
															</thead>
															<tbody>
																<?php
																//$fieldid =$query['fieldid'];
																$query_rsFieldquestion = $db->prepare("SELECT * FROM `tbl_indicator_baseline_survey_answers` a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id =a.fieldid WHERE q.sectionid=:objectiveid ORDER BY a.id ASC");
																$query_rsFieldquestion->execute(array(":objectiveid" => $objectiveid));
																$row_rsFieldquestion = $query_rsFieldquestion->fetchAll();
																$totalRows_rsFieldquestion = $query_rsFieldquestion->rowCount();
																$grouped_rsFieldquestion = array_chunk($row_rsFieldquestion, $totalRows_rsFieldquestions);

																$nmb = 0;
																foreach ($grouped_rsFieldquestion as $group) {
																	$nmb++;
																?>
																	<tr>
																		<td align="center">
																			<?php echo $nmb; ?>
																		</td>
																		<?php
																		foreach ($group as $row_rsFieldquestion) {
																			$fieldtype = $row_rsFieldquestion['fieldtype'];
																			$vlid = $row_rsFieldquestion['answer'];
																			if ($fieldtype == "checkbox-group" || $fieldtype == "radio-group" || $fieldtype == "select") {
																				$query_rsValueName = $db->prepare("SELECT label FROM `tbl_indicator_baseline_survey_form_question_field_values` WHERE id=:valid");
																				$query_rsValueName->execute(array(":valid" => $vlid));
																				$row_rsValueName = $query_rsValueName->fetch();
																				$answer = $row_rsValueName["label"];
																			} else {
																				$answer = $row_rsFieldquestion["answer"];
																			}
																		?>
																			<td>
																				<?php echo $answer; ?>
																			</td>
																		<?php
																		}
																		?>
																	</tr>
																<?php
																}
																?>
															</tbody>
														</table>
														<script>
															$(document).ready(function() {
																$('#section<?php echo $objectiveid ?>').DataTable();
															});
														</script>
													</div>
											<?php
												}
											} else {
												echo '<div class="col-md-12">
													<h5 style="color:red">Sorry no data found for this evaluation form<h5>
												</div>';
											}
											?>
										</fieldset>
									</div>

									<div class="tab-pane" role="tabpanel" id="step4">
										<form class="form-horizontal" id="baselinesurveyForm" action="indicator-baseline-survey-form-processing.php" method="POST" enctype="multipart/form-data" autocomplete="off">
											<?= csrf_token_html(); ?>
											<div id="baselineupdate">
												<?php
												if ($row_formdetails["status"] == 2) {
												?>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Baseline Values</legend>
														<!--<div class="block-header" id="sweetalert">
															<?php //echo $results;
															?>
														</div>-->
														<div class="col-md-3">
															<label>Indicator Code: <?php echo $row_rsIndicator["indcode"]; ?></label>
														</div>
														<div class="col-md-12">
															<label>Indicator Name: <?php echo $row_rsIndicator["indname"]; ?></label>
														</div>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
															<input name="indid" type="hidden" value="<?= $indid ?>">
															<input name="formid" type="hidden" value="<?= $formid ?>">
															<input name="type" type="hidden" value="time">
															<div class="col-md-4">
																<label>Base-Year *: <?php echo $baseyr; ?></label>
																<input type="hidden" name="baseyear" value="<?= $baseyear ?>">
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover" style="width:100%">
																	<thead>
																		<tr id="colrow">
																			<th width="4%"><strong id="colhead">#</strong></th>
																			<th width="76%">Location</th>
																			<th width="20%">Base Value</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$query_location_level1 =  $db->prepare("SELECT * FROM tbl_state WHERE id <> 1 and state NOT LIKE 'All%' AND parent IS NULL ORDER BY state ASC");
																		$query_location_level1->execute();

																		$nm = 0;
																		$xy = 50;
																		while ($rows_level1 = $query_location_level1->fetch()) {
																			$nm++;
																			$lv1id = $rows_level1["id"];
																			$level1 = $rows_level1["state"];
																			echo '<tr style="background-color:#607D8B; color:#FFF">
																				<td>' . $nm . '</td>
																				<td>' . $level1 . ' ' . $level1label . '</td>
																				<td></td>
																				<input type="hidden" name="lvid[]" value="' . $lv1id . '">
																			</tr>';

																			$query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'All%' ORDER BY state ASC");
																			$query_location_level2->execute();
																			$sr = 0;
																			while ($rows_level2 = $query_location_level2->fetch()) {
																				$sr++;
																				$lv2id = $rows_level2["id"];
																				$level2 = $rows_level2["state"];
																				echo '<tr style="background-color:#9E9E9E; color:#FFF">
																					<td>' . $nm . '.' . $sr . '</td>
																					<td>' . $level2 . ' ' . $level2label . '</td>
																					<td><input type="hidden" name="lvid' . $nm . '[]" value="' . $lv2id . '"></td>

																				</tr>';

																				$query_location_level3 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv2id' and state NOT LIKE 'All%' ORDER BY state ASC");
																				$query_location_level3->execute();

																				$nmb = 0;
																				while ($rows_level3 = $query_location_level3->fetch()) {
																					$nmb++;
																					$xy++;
																					$lv3id = $rows_level3["id"];
																					$level3 = $rows_level3["state"];
																					echo '<tr>
																						<td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
																						<td>' . $level3 . ' ' . $level3label . '</td>
																						<td><input type="number" name="basevalue' . $nm . $sr . '[]" value="' . $xy . '" class="form-control" required>
																						<input type="hidden" name="lvid' . $nm . $sr . '[]" value="' . $lv3id . '"></td>
																					</tr>';
																				}
																			}
																		}
																		?>
																	</tbody>
																</table>
															</div>
														</diV>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
															<input type="hidden" name="MM_insert" value="addindfrm" />
															<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
															<input type="hidden" name="insertbaseline" id="insertbaseline" value="1">
															<ul class="list-inline" style="margin-top:20px" align="center">
																<li><input name="submit" type="submit" class="btn btn-primary next-step" id="evalconclusion"></li>
															</ul>
														</div>
													</fieldset>
												<?php
												} else {
												?>
													<!--updating data to tbl_indicator_details-->
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Baseline Values</legend>
														<div class="block-header" id="sweetalert">
														</div>
														<div class="col-md-3">
															<label>Indicator Code: <?php echo $row_rsIndicator["indcode"]; ?></label>
														</div>
														<div class="col-md-12">
															<label>Indicator Name: <?php echo $row_rsIndicator["indname"]; ?></label>
														</div>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
															<input name="indid" type="hidden" value="<?= $indid ?>">
															<input name="formid" type="hidden" value="<?= $formid ?>">
															<input name="type" type="hidden" value="time">
															<div class="col-md-4">
																<label>Base-Year *: <?php echo $baseyr; ?></label>
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover" style="width:100%">
																	<thead>
																		<tr id="colrow">
																			<th width="4%"><strong id="colhead">#</strong></th>
																			<th width="76%">Location</th>
																			<th width="20%">Base Value </th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$query_location_level1 =  $db->prepare("SELECT d.id, level1, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level1 WHERE indid='$indid' GROUP BY d.level1 ORDER BY d.id ASC");
																		$query_location_level1->execute();

																		$nm = 0;
																		while ($rows_level1 = $query_location_level1->fetch()) {
																			$nm++;
																			$dtid = $rows_level1["id"];
																			$lv1id = $rows_level1["level1"];
																			$level1 = $rows_level1["state"];
																			echo '
																			<tr style="background-color:#607D8B; color:#FFF">
																				<td>' . $nm . '</td>
																				<td>' . $level1 . ' ' . $level1label . '</td>
																				<td></td>
																			</tr>';

																			$query_location_level2 =  $db->prepare("SELECT d.id, level2, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level2 WHERE indid='$indid' and level1='$lv1id' GROUP BY level2 ORDER BY d.id ASC");
																			$query_location_level2->execute();
																			$sr = 0;
																			while ($rows_level2 = $query_location_level2->fetch()) {
																				$sr++;
																				$dt2id = $rows_level2["id"];
																				$lv2id = $rows_level2["level2"];
																				$level2 = $rows_level2["state"];
																				echo '
																				<tr style="background-color:#9E9E9E; color:#FFF">
																					<td>' . $nm . '.' . $sr . '</td>
																					<td>' . $level2 . ' ' . $level2label . '</td>
																					<td></td>
																				</tr>';

																				$query_location_level3 =  $db->prepare("SELECT d.id, level3, state, basevalue FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level3 WHERE indid='$indid' and level1='$lv1id' and level2='$lv2id' ORDER BY d.id ASC");
																				$query_location_level3->execute();

																				$nmb = 0;
																				while ($rows_level3 = $query_location_level3->fetch()) {
																					$nmb++;
																					$xy++;
																					$dt3id = $rows_level3["id"];
																					$lv3id = $rows_level3["level3"];
																					$level3 = $rows_level3["state"];
																					$basevalue = $rows_level3["basevalue"];
																					echo '<tr>
																						<td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
																						<td>' . $level3 . ' ' . $level3label . '</td>
																						<td><input type="number" name="basevalue[]" value="' . $basevalue . '" class="form-control" required>
																						<input type="hidden" name="dt3id[]" value="' . $dt3id . '"></td>
																					</tr>';
																				}
																			}
																		}
																		?>
																	</tbody>
																</table>
															</div>
														</diV>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
															<input type="hidden" name="MM_update" value="updateindfrm" />
															<input name="username" type="hidden" id="username" value="<?= $user_name ?>" />
															<input type="hidden" name="updatebaseline" id="updatebaseline" value="1">
															<ul class="list-inline" style="margin-top:20px" align="center">
																<li><input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="bssurvey" value="Update" /></li>
															</ul>
														</div>
													</fieldset>

												<?php
												}
												?>
											</div>
										</form>
									</div>

									<div class="tab-pane" role="tabpanel" id="step5">

										<form action="" method="POST" role="form" id="conclusionform">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Conclusion & Recommendations</legend>
												<div class="block-header" id="sweetalert">
													<?php echo $results; ?>
												</div>
												<div class="col-md-12">
													<label class="control-label">Indicator Name *:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
															<strong><?php echo $indicator; ?></strong>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<label class="control-label">Indicator Category *:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
															<strong><?php echo $indcategory; ?></strong>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<label class="control-label">Indicator Unit of Measure *:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
															<strong><?php echo $indunitdesc . " (" . $indunit . ")"; ?></strong>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<label class="control-label">Evaluation Conclusion <font align="left" style="background-color:#CDDC39">(Explain your conclusion on this evaluation)</font>*:</label>
													<p align="left">
														<textarea name="conclusion" cols="45" rows="5" class="txtboxes" id="evalconcl" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
														<script>
															CKEDITOR.replace('evalconcl', {
																on: {
																	instanceReady: function(ev) {
																		// Output paragraphs as <p>Text</p>.
																		this.dataProcessor.writer.setRules('p', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('ol', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('ul', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('li', {
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
												<?php if ($evaltype == 3) {
												} else { ?>
													<div class="col-md-12">
														<label class="control-label">Recommendations <font align="left" style="background-color:#CDDC39">(Give a descriptive recommendations on your conclusion indicated above)</font> *:</label>
														<p align="left">
															<textarea name="recommendation" cols="45" rows="5" class="txtboxes" id="evalrecomm" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Give a descriptive recommendations on your conclusion indicated above"></textarea>
															<script>
																CKEDITOR.replace('evalrecomm', {
																	on: {
																		instanceReady: function(ev) {
																			// Output paragraphs as <p>Text</p>.
																			this.dataProcessor.writer.setRules('p', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ol', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ul', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('li', {
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
												<?php } ?>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
													<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
													<input name="formid" type="hidden" id="formid" value="<?php echo $formid; ?>" />
													<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
													<ul class="list-inline" style="margin-top:20px">
														<li><input name="submit" type="submit" class="btn btn-primary next-step" id="evalconclusion"></li>
													</ul>
												</div>
											</fieldset>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>