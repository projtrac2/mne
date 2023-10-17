<?php 
require('includes/head.php');
if ($permission) {

    try {
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

            // calculation method
            $query_calculation_method = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id ='$indicator_calculation_method'");
            $query_calculation_method->execute();
            $row_query_calculation_method = $query_calculation_method->fetch();
            $calculation_method = $row_query_calculation_method['method'];

            // get form details 
            $query_survey_form = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid = :resultstypeid and resultstype = :resultstype and form_name = :fname");
            $query_survey_form->execute(array(":resultstypeid" => $resultstypeid, ":resultstype" => $resultstype, ":fname" => $surveytype));
            $row_enumtype = $query_survey_form->fetch();

            $enumtype = $row_enumtype['enumerator_type'];
			$targetdescription = $row_enumtype['respondent_description'];
            $sample = $row_enumtype['sample_size'];
            $sdate = date_create($row_enumtype['startdate']);
            $startdate = date_format($sdate, "d M Y");
            $edate = date_create($row_enumtype['enddate']);
            $enddate = date_format($edate, "d M Y");

            if ($enumtype == 1) {
                $enumeratortype = "In-house";
            } else {
                $enumeratortype = "Out-Sourced";
            }
        }
		$pageTitle = "Survey Form Preview";
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
                        <div class="btn-group" style="float:right">
                            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                Go Back
                            </button>
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
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<ul class="list-group">
									<li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
									<li class="list-group-item"><strong>Project <?= $formtype ?> : </strong> <?= $resultstobemeasured ?> </li>
									<li class="list-group-item"><strong>Indicator: </strong> <?= $indname ?> </li>
									<li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit ?> </li>
									<li class="list-group-item"><strong>Source of Data: </strong> <?= $datasource ?> </li>
									<li class="list-group-item"><strong>Calculation Method: </strong> <?= $calculation_method ?> </li>
								</ul>
							</div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="" id="" class="control-label">Target Respondents Description *:</label>
										<div class="form-input">
											<div class="form-control"><?php echo $targetdescription; ?></div>
										</div>
									</div>
                                    <?php
                                    if ($data_source  == 1) {
                                    ?>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" id="">
                                            <label for="" id="" class="control-label">Sample Size Per Location:</label>
                                            <div class="form-input">
                                                <div class="form-control"><?php echo $sample; ?></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label"><strong>Main Question: </strong></label>
											<?php
											if($resultstype == 2){
												$query_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=2 AND questiontype=1 ORDER BY id ASC");
											}else{
												$query_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=1 AND questiontype=1 ORDER BY id ASC");
											}
											$query_questions->execute(array(":projid" => $projid));
											$row = $query_questions->fetch();
											$answertype = $row["answertype"];
											
											if($answertype == 1){$answername = "Number";} elseif($answertype == 2){$answername = "Multiple Choice";} elseif($answertype == 3){$answername = "Checkboxes";} elseif($answertype == 4){$answername = "Dropdown";} elseif($answertype == 5){$answername = "Text";} elseif($answertype == 6){$answername = "File Upload";}
											
											?>
                                            <div>
                                                <table class="table table-bordered table-striped table-hover dataTable">
                                                    <thead>
                                                        <tr>
														
															<th width="60%">Question</th>
															<th width="15%">Answer Type</th>
															<th width="25%">Answer Labels</th>
														
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
														$question = '<td>' . $row['question'] . '</td>';
														$answerdatatype = '<td>' . $answername . '</td>';
														$answerlabel = "";
															
														if($answertype == 1){$answername = "Number";} elseif($answertype == 2){$answername = "Multiple Choice";} elseif($answertype == 3){$answername = "Checkboxes";} elseif($answertype == 4){$answername = "Dropdown";} elseif($answertype == 5){$answername = "Text";} elseif($answertype == 6){$answername = "File Upload";}
														
														$question = '<td>' . $row['question'] . '</td>';
														$answerdatatype = '<td>' . $answername . '</td>';
														$answerlabel = '<td>N/A</td>';
														if($answertype == 2 || $answertype == 3 || $answertype == 4){
															$answerlabel = '<td>' . $row['answerlabels'] . '</td>';
														}
														$data = $question.$answerdatatype.$answerlabel;
												
														echo '
														<tr>
															' . $data . '
														</tr>';
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label"><strong>Other Question/s: </strong></label>
											<?php
											if($resultstype == 2){
												$query_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=2 AND questiontype=2 ORDER BY id ASC");
											}else{
												$query_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=1 AND questiontype=2 ORDER BY id ASC");
											}
											$query_questions->execute(array(":projid" => $projid));
											
											?>
                                            <div>
                                                <table class="table table-bordered table-striped table-hover dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%">#</th>
															<th width="55%">Question</th>
															<th width="15%">Answer Type</th>
															<th width="25%">Answer Labels</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $counter = 0;
                                                        while ($row = $query_questions->fetch()) {
                                                            $counter++;
															$count = '<td>' .$counter.'</td>';
															$answertype = $row["answertype"];
															
															if($answertype == 1){$answername = "Number";} elseif($answertype == 2){$answername = "Multiple Choice";} elseif($answertype == 3){$answername = "Checkboxes";} elseif($answertype == 4){$answername = "Dropdown";} elseif($answertype == 5){$answername = "Text";} elseif($answertype == 6){$answername = "File Upload";}
															
															$question = '<td>' . $row['question'] . '</td>';
															$answerdatatype = '<td>' . $answername . '</td>';
															$answerlabel = '<td>N/A</td>';
															if($answertype == 2 || $answertype == 3 || $answertype == 4){
																$answerlabel = '<td>' . $row['answerlabels'] . '</td>';
															}
															$data = $count.$question.$answerdatatype.$answerlabel;
													
															echo '
															<tr>
																' . $data . '
															</tr>';
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="" id="" class="control-label">Key Question:</label>
                                            <div class="form-input">
                                                <div class="form-control"><?php echo $unit . " of " . $indname; ?></div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label>Enumerator Type *:</label>
                                        <div class="form-line">
                                            <div class="form-control"><?php echo $enumeratortype; ?></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="" id="" class="control-label">Survey Start Date *:</label>
                                        <div class="form-input">
                                            <div class="form-control"><?php echo $startdate; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="" id="" class="control-label">Survey End Date *:</label>
                                        <div class="form-input">
                                            <div class="form-control"><?php echo $enddate; ?></div>
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
?>