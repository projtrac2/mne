<?php
require('includes/head.php');
if ($permission) {
    try {

        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

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
				if($resultstype == 1){
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
			
			if($resultstype == 2){
				$query_results = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultstypeid");
				$query_results->execute(array(":resultstypeid" => $resultstypeid));
				$row_results = $query_results->fetch();
				$formtype = "Outcome ";
				$resultstobemeasured = $row_results['outcome'];
			} else{
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
			$pageTitle = "Define ". $formtype . $surveytype . " Survey Details";
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
                    <div class="btn-group" style="float:right">
                        
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
                                <li class="list-group-item"><strong>Change to be measured: </strong> <?= $indname ?> </li>
                                <li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit ?> </li>
                                <li class="list-group-item"><strong>Source of Data: </strong> <?= $datasource ?> </li>
                            </ul>
                        </div>
						<div class="body">
							<form id="<?= $formid ?>" method="POST" name="<?= $formid ?>" action="" enctype="multipart/form-data" autocomplete="off">
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
								<!--<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Survey Form Questions</legend>
								
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Main Question</label>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" style="width:100%">
												<thead>
													<tr>
														<th width="55%">Question</th>
														<th width="15%">Answer Type</th>
														<th width="30%">Answer Labels</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="text" name="outcomemainquestion" id="outcomemainquestion" placeholder="Enter main outcome evaluation question" class="form-control querry" />
														</td>
														<td>
															<select data-id="0" name="outcomemainanswertype" id="outcomemainanswertype" class="form-control querry">
																<?php
																/* $input = '<option value="">... Select ...</option>';
																$input .= '<option value="1">Number</option>';
																$input .= '<option value="2">Mutiple Choice</option>';
																echo $input; */
																?>
															</select>
														</td>
														<td>
															<input type="text" name="outcome_main_answer_labels" placeholder="Enter comma seperated labels" class="form-control querry" />
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Other Question/s</label>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="outcome_questions_table" style="width:100%">
												<thead>
													<tr>
														<th width="5%">#</th>
														<th width="50%">Question</th>
														<th width="15%">Answer Type</th>
														<th width="25%">Answer Labels</th>
														<th width="5%">
															<button type="button" name="addplus" id="addplus" onclick="add_row_question();" class="btn btn-success btn-sm">
																<span class="glyphicon glyphicon-plus"></span>
															</button>
														</th>
													</tr>
												</thead>
												<tbody id="questions_table_body">
													<?php
													//main  evaluation questions
													/* $query_outcomemainevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid='$projid' AND resultstype=2 AND questiontype=1");
													$query_outcomemainevalqstns->execute();
													$row_outcomemainevalqstns = $query_outcomemainevalqstns->fetch();
													$count_outcomemainevalqstns = $query_outcomemainevalqstns->rowCount();

													//Outcome  evaluation questions
													$query_outcomeevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid='$projid' AND resultstype=2 AND questiontype=2");
													$query_outcomeevalqstns->execute();
													$row_outcomeevalqstns = $query_outcomeevalqstns->fetch();
													$count_outcomeevalqstns = $query_outcomeevalqstns->rowCount();
													$orowno = 0;
													if ($count_outcomeevalqstns > 0) {
														do {
															$question = $row_outcomeevalqstns['question'];
															$orowno++; */
													?>
															<tr id="questionrow<?= $orowno ?>">
																<td> <?= $orowno ?> </td>
																<td>
																	<input type="text" name="outcomeotherquestions[]" id="questions<?= $orowno ?>" value="<?= $question ?>" placeholder="Enter any other outcome evaluation question" class="form-control querry" />
																</td>
																<td>
																	<select data-id="0" name="outcomeotheranswertype[]" id="answertype<?= $orowno ?>" class="form-control querry">
																		<?php
																		/* $input = '<option value="">... Select ...</option>';
																		$input .= '<option value="1">Number</option>';
																		$input .= '<option value="2">Multiple Choice</option>';
																		$input .= '<option value="3">Checkboxes</option>';
																		$input .= '<option value="4">Dropdown</option>';
																		$input .= '<option value="5">Text</option>';
																		$input .= '<option value="6">File Upload</option>';

																		echo $input; */
																		?>
																	</select>
																</td>
																<td>
																	<input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label<?= $orowno ?>" placeholder="Enter comma seperated labels" class="form-control querry" />
																</td>

																<td>
																	<?php
																	//if ($orowno != 1) {
																	?>
																		<button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_question("questionrow<?= $orowno ?>")'>
																			<span class="glyphicon glyphicon-minus"></span>
																		</button>
																	<?php
																	//}
																	?>
																</td>
															</tr>
														<?php
														/* } while ($row_outcomeevalqstns = $query_outcomeevalqstns->fetch());
													} else {
														$orowno++; */
														?>
														<tr id="questionrow90">
															<td> 1 </td>
															<td>
																<input type="text" name="outcomeotherquestions[]" id="questions90" value="" placeholder="Enter any other outcome evaluation question" class="form-control querry" />
															</td>
															<td>
																<select data-id="0" name="outcomeotheranswertype[]" id="answertype<?= $orowno ?>" class="form-control querry">
																	<?php
																	/* $input = '<option value="">... Select ...</option>';
																	$input .= '<option value="1">Number</option>';
																	$input .= '<option value="2">Multiple Choice</option>';
																	$input .= '<option value="3">Checkboxes</option>';
																	$input .= '<option value="4">Dropdown</option>';
																	$input .= '<option value="5">Text</option>';
																	$input .= '<option value="6">File Upload</option>';

																	echo $input; */
																	?>
																</select>
															</td>
															<td>
																<input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label<?= $orowno ?>" placeholder="Enter comma seperated labels" class="form-control querry" />
															</td>
															<td>

															</td>
														</tr>
													<?php
													//}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</fieldset>-->
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
											<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
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
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/indicator-details.js"></script>
<script src="assets/custom js/baseline-survey.js"></script>
<script src="assets/js/mneplan/add-project-mne-plan.js"></script>