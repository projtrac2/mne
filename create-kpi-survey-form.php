<?php
try {
	require('includes/head.php');
	if ($permission) {

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



		if (isset($_GET['kpi']) && !empty($_GET['kpi'])) {
			$encoded_kpi_id = $_GET['kpi'];
			$decode_kpi_id = base64_decode($encoded_kpi_id);
			$kpi_id_array = explode("kpi254", $decode_kpi_id);
			$kpi_id = $kpi_id_array[1];

			$query_kpi_details = $db->prepare("SELECT * FROM tbl_kpi k left join tbl_indicator i on i.indid=k.outcome_indicator_id left join tbl_datacollectionfreq f on f.fqid=k.data_frequency left join tbl_strategic_plan_objectives o on o.id=k.strategic_objective_id left join tbl_indicator_calculation_method m on m.id=i.indicator_calculation_method WHERE k.id=:kpi_id");
			$query_kpi_details->execute(array(":kpi_id" => $kpi_id));
			$row_kpi_details = $query_kpi_details->fetch();
			$formtype = "KPI ";
			$indid = $row_kpi_details['outcome_indicator_id'];
			$strategic_objective = $row_kpi_details["objective"];
			$kpi = $row_kpi_details['indicator_name'];
			$responsible_id = $row_kpi_details["responsible"];
			$data_frequency = $row_kpi_details["frequency"];
			$calculation_method = $row_kpi_details["method"];

			$formname = "KPI Data Collection Survey Form";
			$formid = "kpiSurveyForm";


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
			//$pageTitle = "Create KPI Survey Form";
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
									<li class="list-group-item list-group-item list-group-item-action active">KPI: <?= $kpi ?> </li>
									<li class="list-group-item"><strong>Strategic Objective : </strong> <?= $strategic_objective ?> </li>
									<li class="list-group-item"><strong>Data Collection Frequency: </strong> <?= $data_frequency ?> </li>
									<li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit ?> </li>
									<li class="list-group-item"><strong>Calculation Method: </strong> <?= $calculation_method ?> </li>
								</ul>
							</div>
							<input id="kpi_id" type="hidden" value="<?php echo $kpi_id; ?>" />
							<div class="body">
								<form id="<?= $formid ?>" method="POST" name="<?= $formid ?>" action="" enctype="multipart/form-data" autocomplete="off">
									<?= csrf_token_html(); ?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="" id="" class="control-label">Target Respondents Description *:</label>
										<div class="form-input">
											<input type="text" name="targetrespondent" placeholder="Describe the target respondent/s" class="form-control" require="required">
										</div>
									</div>

									<div class="col-md-3" id="">
										<label for="" id="" class="control-label">Sample Size Per Location *:</label>
										<div class="form-input">
											<input type="number" name="sample" id="sample" value="" placeholder="Number of Submissions" class="form-control" required>
										</div>
									</div>

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
																<button type="button" name="addplus" id="addplus" class="btn btn-success btn-sm" data-toggle="modal" id="addQuestionsModalBtn" onclick="count_kpi_questions(<?= $kpi_id ?>)" data-target="#addQuestionsModal">
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
											<input name="kpiid" type="hidden" id="kpiid" value="<?php echo $kpi_id; ?>" />
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
											<form class="form-horizontal" id="add_kpi_evaluation_questions_form" method="POST" name="add_kpi_evaluation_questions_form" action="" enctype="multipart/form-data" autocomplete="off">
												<?= csrf_token_html(); ?>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label class="control-label" style="color:#0b548f; font-size:16px">KPI: <u><?php echo $kpi; ?></u></label>
												</div>
												<br />
												<div id="questions">
													<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
														<label for="impactName" class="control-label">Question Type *:</label>
														<div class="form-input">
															<select data-id="0" name="kpi_question_type" id="kpi_question_type" onchange="kpi_add_question_type()" class="form-control kpi_main_question_count">
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
															<select data-id="0" name="kpi_main_question" id="kpi_main_question" class="form-control main_question_count">
															</select>
														</div>
													</div>
												</div>
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
														<input type="hidden" name="add_kpi_evaluation_questions" id="add_kpi_evaluation_questions" value="add">
														<input type="hidden" name="question_status" id="question_status" value="0">
														<input type="hidden" name="kpiid" id="kpiid" value="<?= $kpi_id ?>" />
														<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
														<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="kpi-evaluation-questions-form" value="Save" />
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/custom js/indicator-details.js"></script>
<!--<script src="assets/custom js/baseline-survey.js"></script>-->
<script src="assets/js/mneplan/survey.js"></script>