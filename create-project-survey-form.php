<?php
require('includes/head.php');
if ($permission) {
	try {

		$results = "";
		if (isset($_POST["MM_insert"])) {
			$indid = $_POST['indid'];
			$projid = $_POST['projid'];
			$resultstype = $_POST['resultstype'];
			$resultstypeid = $_POST['resultstypeid'];
			$form_name = $_POST['form_name'];
			$respondentdescription = $_POST['targetrespondent'];
			$enddate = $_POST['enddate'];
			$startdate = $_POST['startdate'];
			$enumerator_type = $_POST['enumerator_type'];
			$created_by = $_POST['user_name'];
			$date_created = date("Y-m-d");
			$sample = NULL;

			if (isset($_POST['sample']) && !empty($_POST['sample'])  && $_POST['sample'] != 0) {
				$sample = $_POST['sample'];
			}

			$status = 1;
			$form_type = $_POST['surveytype']; // baseline survey

			/* $insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_forms(projid, indid, form_name, enumerator_type, status, sample_size, startdate, enddate, created_by, date_created) VALUES(:projid, :indid, :form_name, :enumerator_type, :status, :sample_size, :startdate, :enddate, :created_by, :date_created)");
            $result2  = $insertBeneficiary->execute(array(":projid" => $projid, ":indid" => $indid, ":form_name" => $form_name, ":enumerator_type" => $enumerator_type, ":status" => $status, ":sample_size" => $sample, ":startdate" => $startdate, ":enddate" => $enddate, ":created_by" => $created_by, ":date_created" => $date_created)); */


			$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_forms(projid, resultstype, resultstypeid, indid, form_name, respondent_description, enumerator_type, status, sample_size, startdate, enddate, created_by, date_created) VALUES(:projid, :resultstype, :resultstypeid, :indid, :form_name, :respondentdescription, :enumerator_type, :status, :sample_size, :startdate, :enddate, :created_by, :date_created)");
			$result2  = $insertBeneficiary->execute(array(":projid" => $projid, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid, ":indid" => $indid, ":form_name" => $form_name, ":respondentdescription" => $respondentdescription, ":enumerator_type" => $enumerator_type, ":status" => $status, ":sample_size" => $sample, ":startdate" => $startdate, ":enddate" => $enddate, ":created_by" => $created_by, ":date_created" => $date_created));

			if ($result2) {
				/* if ($data_source == 1) {
					$insertmainquestion = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
					$result1  = $insertmainquestion->execute(array(":projid" => $projid, ":question" => $mainquestion, ":resultstype" => $resultstype, ":resultstypeid" => $impactid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $mainanswerlabels));

					for ($j = 0; $j < count($_POST['impactquestions']); $j++) {
						$question = $_POST['impactquestions'][$j];
						$answertype = $_POST['impactanswertype'][$j];
						$answerlabels = $_POST['impact_other_answer_label'][$j];
						$questiontype = 2;

						if (!empty($question)) {
							$insertSQL1 = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
							$result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question, ":resultstype" => $resultstype, ":resultstypeid" => $impactid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $answerlabels));
						}
					}
				} */
				$msg = 'Survey form created successfully.';
				$url = 'view-project-survey';
				if ($resultstype == 1) {
					$url = 'view-project-impact-evaluation';
				}
				$results = "
                <script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 3000,
                        showConfirmButton: false });

                    setTimeout(function(){
                        window.location.href = \" $url\";
                    }, 3000);
                </script>";
			} else {
				$msg = 'Could not create the survey form';
				$results = "
                <script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 3000,
                        showConfirmButton: false });

                    setTimeout(function(){
                        window.location.href = 'create-project-survey-form?projid=\" $msg\"';
                    }, 3000);
                </script>";
			}
		}



		if (isset($_GET['resultstypeid']) && !empty($_GET['resultstypeid'])) {
			$resultstype = $_GET['resultstype'];
			$encoded_resultsid = $_GET['resultstypeid'];
			$decode_resultsid = base64_decode($encoded_resultsid);
			$resultsid_array = explode("results", $decode_resultsid);
			$resultstypeid = $resultsid_array[1];

			if ($resultstype == 2) {
				$query_results = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultstypeid");
				$query_results->execute(array(":resultstypeid" => $resultstypeid));
				$row_results = $query_results->fetch();
				$formtype = "Outcome ";
				$resultstobemeasured = $row_results['outcome'];
			} else {
				$query_results = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE id=:resultstypeid");
				$query_results->execute(array(":resultstypeid" => $resultstypeid));
				$row_results = $query_results->fetch();
				$formtype = "Impact ";
				$resultstobemeasured = $row_results['impact'];
			}

			$projid = $row_results['projid'];
			$indid = $row_results['indid'];
			$data_source  = $row_results['data_source'];

			$query_rs_projects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
			$query_rs_projects->execute(array(":projid" => $projid));
			$row_rs_projects = $query_rs_projects->fetch();

			$projname = $row_rs_projects['projname'];
			$progid = $row_rs_projects['progid'];
			$projstage = $row_rs_projects['projstage'];

			if ($projstage == 9) {
				$surveytype = "Baseline";
			} else {
				$surveytype = "Endline";
			}

			if ($data_source  == 1) {
				$datasource  = "Primary";
			} else {
				$datasource  = "Secondary";
			}

			$formname = "Project " . $formtype . $surveytype . " Survey";
			$formid = "addoutcomebasefrm";


			$query_rsIndicator = $db->prepare("SELECT indid, indicator_name, indicator_disaggregation, indicator_calculation_method, indicator_unit FROM tbl_indicator WHERE indid ='$indid'");
			$query_rsIndicator->execute();
			$row_rsIndicator = $query_rsIndicator->fetch();
			$indname = $row_rsIndicator['indicator_name'];
			$disaggregated = $row_rsIndicator['indicator_disaggregation'];
			$indicator_calculation_method  = $row_rsIndicator['indicator_calculation_method'];
			$indunit  = $row_rsIndicator['indicator_unit'];

			// get unit
			$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id ='$indunit'");
			$query_Indicator->execute();
			$row = $query_Indicator->fetch();
			$unit = $row['unit'];
			$pageTitle = "Define " . $formtype . $surveytype . " Survey Details";
		}
	} catch (PDOException $ex) {

		function flashMessage($flashMessages)
		{
			return $flashMessages;
		}

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
					<div class="btn-group" style="float:right; padding-right:5px">
						<div class="btn-group" style="float:right">
							<a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
								Go Back
							</a>
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
							<ul class="list-group">
								<li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
								<li class="list-group-item"><strong>Project <?= $formtype ?> : </strong> <?= $resultstobemeasured ?> </li>
								<li class="list-group-item"><strong>Indicator: </strong> <?= $indname ?> </li>
								<li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit ?> </li>
								<li class="list-group-item"><strong>Source of Data: </strong> <?= $datasource ?> </li>
							</ul>
						</div>
						<div class="body">
							<form id="<?= $formid ?>" method="POST" name="<?= $formid ?>" action="" enctype="multipart/form-data" autocomplete="off">
								<?= csrf_token_html(); ?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="" id="" class="control-label">Target Respondents Description *:</label>
									<div class="form-input">
										<input type="text" name="targetrespondent" placeholder="Describe the target respondent/s" class="form-control" require="required">
									</div>
								</div>

								<?php
								if ($data_source  == 1) {
								?>
									<div class="col-md-3" id="">
										<label for="" id="" class="control-label">Sample Size Per Location:</label>
										<div class="form-input">
											<input type="number" name="sample" id="sample" value="" placeholder="Number of Submissions" class="form-control" required>
										</div>
									</div>
								<?php
								}
								?>

								<div class="col-md-3" id="respondent">
									<label>Enumerator Type *:</label>
									<div class="form-line">
										<select name="enumerator_type" id="enumerator_type" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
											<option value="" selected="selected" class="selection">....Select Type First....</option>
											<option value="1" class="selection">Inhouse</option>
											<option value="2" class="selection">Outsourced</option>
										</select>
									</div>
								</div>

								<div class="col-md-3" id="">
									<label for="" id="" class="control-label">Survey Start Date *:</label>
									<div class="form-input">
										<input type="date" name="startdate" id="startdate" value="" onchange="start()" placeholder="Start Date" class="form-control" require="required">
									</div>
								</div>
								<div class="col-md-3" id="">
									<label for="" id="" class="control-label">Survey End Date *:</label>
									<div class="form-input">
										<input type="date" name="enddate" id="enddate" value="" onchange="end()" placeholder="End Date" class="form-control" require="required">
									</div>
								</div>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Survey Form Questions</legend>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Add Question/s</label>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="survey_questions_table">
												<thead>
													<tr>
														<th width="3%">#</th>
														<th width="37%">Question</th>
														<th width="12%">Question Type</th>
														<th width="12%">Answer Type</th>
														<th width="12%">Answer Labels</th>
														<th width="12%">Calculation Method</th>
														<th width="10%">
															<button type="button" name="addplus" id="addplus" class="btn btn-success btn-sm" data-toggle="modal" id="addQuestionsModalBtn" onclick="count_questions(<?= $projid ?>,<?= $resultstype ?>,<?= $resultstypeid ?>)" data-target="#addQuestionsModal">
																<span class="glyphicon glyphicon-plus"></span>
															</button>
														</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</fieldset>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
										<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
										<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
										<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
										<input name="resultstype" type="hidden" value="<?php echo $resultstype; ?>" />
										<input name="resultstypeid" type="hidden" value="<?php echo $resultstypeid; ?>" />
										<input name="form_name" type="hidden" id="form_name" value="<?= $surveytype ?>" />
										<input name="surveytype" type="hidden" id="surveytype" value="<?= $projstage ?>" />
										<div class="btn-group">
											<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit_evaluation_form" value="Submit" />
										</div>
										<input type="hidden" name="MM_insert" value="<?= $formid ?>" />
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<!-- start Add Survey Questions Modal -->

	<div class="modal fade" id="addQuestionsModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetModalContent()">
					<span>&times;</span></button>
					<h4 class="modal-title questionmodaltitle" style="color:#fff" align="center" id="modal-title">Add <?= $formtype ?> Survey Question</h4>
				</div>
				<div class="modal-body">
					<div class="card">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="body">
									<div class="div-result">
										<form class="form-horizontal" id="add_evaluation_questions_form" method="POST" name="add_evaluation_questions_form" action="" enctype="multipart/form-data" autocomplete="off">
											<?= csrf_token_html(); ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="control-label" style="color:#0b548f; font-size:16px"><?= $formtype ?> Indicator: <u><?php echo $indname; ?></u></label>
											</div>
											<br />
											<?php
											//evaluation main questions
											$query_survey_questions =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=:resultstype AND resultstypeid=:resultstypeid AND questiontype = 1");
											$query_survey_questions->execute(array(":projid" => $projid, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid));
											$count_survey_questions = $query_survey_questions->rowCount();
											//$count_survey_questions = 1;
											//if ($count_survey_questions > 0) {
											?>
											<div id="questions">
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label for="impactName" class="control-label">Question Type *:</label>
													<div class="form-input">
														<select data-id="0" name="question_type" id="question_type" onchange="add_question_type()" class="form-control main_question_count">
															<?php
															$question_type = '<option value="">... Select ...</option>';
															$question_type .= '<option value="1">Main Question</option>';
															$question_type .= '<option value="2">Follow Question</option>';
															echo $question_type;
															?>
														</select>
													</div>
												</div>
												<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="mainquestion">
													<label for="main_question" class="control-label">Main Question *:</label>
													<div class="form-input">
														<select data-id="0" name="main_question" id="main_question" class="form-control main_question_count">
															<?php
															$query_main_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=:resultstype AND resultstypeid=:resultstypeid AND questiontype=1");
															$query_main_questions->execute(array(":projid" => $projid, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid));
															$main_question = '<option value="">... Select ...</option>';
															while ($row_main_questions = $query_main_questions->fetch()) {
																$question_id = $row_main_questions["id"];
																$question = $row_main_questions["question"];
																$main_question .= '<option value="' . $question_id . '">' . $question . '</option>';
															}
															echo $main_question;
															?>
														</select>
													</div>
												</div>
											</div>
											<?php
											//}
											?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label for="question" class="control-label">Question Description *:</label>
												<div class="form-line">
													<input type="text" name="question" id="question" placeholder="Enter the question" class="form-control" required>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
												<label for="impactName" class="control-label">Answer Type *:</label>
												<div class="form-input">
													<select data-id="0" name="answertype" id="answertype" onchange="add_answer_type()" class="form-control impactquerry" required="required">
														<?php
														$impactotherinput = '<option value="">... Select ...</option>';
														$impactotherinput .= '<option value="1">Number</option>';
														$impactotherinput .= '<option value="2">Mutiple Choice</option>';
														$impactotherinput .= '<option value="3">Checkboxes</option>';
														$impactotherinput .= '<option value="4">Dropdown</option>';
														$impactotherinput .= '<option value="5">Text</option>';
														$impactotherinput .= '<option value="6">File Upload</option>';
														echo $impactotherinput;
														?>
													</select>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" id="answer_label">
												<label class="control-label">Answer Label <span id="impunit"></span>*:</label>
												<div class="form-input">
													<input type="text" name="answer_label" id="answerlabel" class="form-control" placeholder="Enter answer lable/s seperated by comma" required="required">
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" id="calculation_method">
												<label for="calc_method" class="control-label">Summarization Techniques *:</label>
												<div class="form-input">
													<select data-id="0" name="calculation_method" id="calc_method" class="form-control impactquerry" required="required">
														<?php
														$query_calculation_method = $db->prepare("SELECT * FROM tbl_numbers_aggregation_method WHERE active = 1");
														$query_calculation_method->execute();

														$calculation_method = '<option value="">... Select ...</option>';
														while ($row_calculation_method = $query_calculation_method->fetch()) {
															$calculation_method .= '<option value="' . $row_calculation_method["id"] . '">' . $row_calculation_method["method"] . ' [' . $row_calculation_method["description"] . ']</option>';
														}
														echo $calculation_method;
														?>
													</select>
												</div>
											</div>
											<div class="modal-footer">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
													<input type="hidden" name="add_evaluation_questions" id="add_evaluation_questions" value="add">
													<input type="hidden" name="question_status" id="question_status" value="0">
													<input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
													<input name="resultstype" id="resultstype" type="hidden" value="<?php echo $resultstype; ?>" />
													<input name="resultstypeid" id="resultstypeid" type="hidden" value="<?php echo $resultstypeid; ?>" />
													<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
													<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="question-tag-form-submit" value="Save" />
													<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal" onclick="resetModalContent()"> Cancel</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /modal-body -->
			</div>
			<!-- /modal-content -->
		</div>
		<!-- /modal-dailog -->
	</div>
	<!-- End Add Survey Questions Modal -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/indicator-details.js"></script>
<!--<script src="assets/custom js/baseline-survey.js"></script>-->
<script src="assets/js/mneplan/survey.js"></script>