<?php 
$resultstypeid = $_GET['resultstype'];
$sourceurl = $resultstypeid == 1 ? "view-project-impact-evaluation" : "view-project-survey";
$resultstype = $resultstypeid == 1 ? "Impact" : "Outcome";

$decode_results = (isset($_GET['results']) && !empty($_GET["results"])) ? base64_decode($_GET['results']) : header("Location: {$sourceurl}"); 
$resultsid_array = explode("resultssecdata", $decode_results);
$resultsid = $resultsid_array[1];

require('includes/head.php');
if ($permission) {
  try {
	$project = $projid = $formname = $enumeratortype = $sample = $evalstartdate = $evalenddate =  $formid = $indid = $datasource = $projstage = $category = $disaggregated = $calculationmethod = $startdate = $enddate = $projlocations = "";
    if ((isset($_POST["submit"]))) {
      $results = "";
      $indid = $_POST['indid'];
      $category = $_POST['category'];
      $numerator = $_POST['numerator'];
      $comments = $_POST['comments'];
      $surveytype = $_POST['surveytype'];
      $projstage = $_POST['projstage'];
      $disaggregation = $_POST['disaggregation'];
      $projid = $_POST['projid'];
		$rtstype = $_POST['resultstype'];
		$rtstypeid = $_POST['resultstypeid'];
      $user = $_POST['user_name'];
      $current_date = date("Y-m-d");


      function submission()
      {
        $digits = "";
        $length = 6;
        $numbers = range(0, 9);
        shuffle($numbers);
        for ($i = 0; $i < $length; $i++) {
          $digits .= $numbers[$i];
        }
        return $digits;
      }
      $submissionid = submission();
		for ($j = 0; $j < count($_POST['location']); $j++) {
			$level3 = $_POST['location'][$j];
			if ($disaggregation) {
				$disaggregationid = $_POST['disaggregationid'];
				for ($i = 0; $i < count($_POST['numerator' . $level3]); $i++) {
					$numerator = $_POST['numerator' . $level3][$i];
					if ($category == 2) {
						$denominator = $_POST['denominator' . $level3][$i];
						$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, disaggregation, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :disaggregation, :category, :numerator, :denominator, :comments, :user, :date)");
						$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$rtstype, ':resultstypeid' => $rtstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
					} else {
						$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, disaggregation, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :disaggregation, :category, :numerator, :comments, :user, :date)");
						$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$rtstype, ':resultstypeid' => $rtstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
					}
				}
			} else {
				$numerator = $_POST['numerator' . $level3];
				if ($category == 2) {
					$denominator = $_POST['denominator' . $level3];
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :category, :numerator, :denominator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$rtstype, ':resultstypeid' => $rtstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				} else {
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :category, :numerator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$rtstype, ':resultstypeid' => $rtstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				}
			}
		}

		if ($result) {
			$query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$impact = $row_project["projimpact"];
			$rows_impacts = 0;
			$rows_outcomes = 0;
			
			$query_evaluation_data_row_count = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid GROUP BY resultstypeid");
			$query_evaluation_data_row_count->execute(array(":projid" => $projid));
			$evaluation_data_row_count = $query_evaluation_data_row_count->rowCount();
			
			if($impact == 1){
				$query_impacts = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid=:projid");
				$query_impacts->execute(array(":projid" => $projid));
				$rows_impacts = $query_impacts->rowCount();
			}
			
			$query_outcomes = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
			$query_outcomes->execute(array(":projid" => $projid));
			$rows_outcomes = $query_outcomes->rowCount();
			
			$total_evaluation_count = $rows_outcomes + $rows_impacts;
			
			if($total_evaluation_count == $evaluation_data_row_count){
				$query_projstageupdate = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid");
				$query_projstageupdate->execute(array(":projstage" => $projstage, ":projid" => $projid));
			}
			
			$url = $sourceurl;
			$msg = 'Successfully saved.';
			$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 3000,
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = \"$url\";
				}, 3000);
			</script>";
		} else {
			$msg = 'Failed!! Could not save your data!!';
			$results = "<script type=\"text/javascript\">
				swal({
					title: \"Error!\",
					text: \" $msg\",
					type: 'Danger',
					timer: 5000,
					showConfirmButton: false });
			</script>";
      }
    }

	if($resultstypeid == 1){
		$query_results = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE id=:resultsid");
		$query_results->execute(array(":resultsid" => $resultsid));
	} else {
		$query_results = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultsid");
		$query_results->execute(array(":resultsid" => $resultsid));
	}
	
	$row_results = $query_results->fetch();
	if($row_results){
		$projid = $row_results["projid"];
		$indid = $row_results["indid"];
	}
	
    $query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype=:resultstype and resultstypeid=:resultstypeid");
    $query_rsEvalDates->execute(array(":resultstype" => $resultstypeid,":resultstypeid" => $resultsid));
    $row_rsrsEvalDates = $query_rsEvalDates->fetch();
    $totalRows_rsEvalDates = $query_rsEvalDates->rowCount();

	if($row_rsrsEvalDates){
		$formname = $row_rsrsEvalDates["form_name"];
		$projid = $row_rsrsEvalDates["projid"];
		$enumeratortype = $row_rsrsEvalDates["enumerator_type"];
		$sample = $row_rsrsEvalDates["sample_size"];
		$evalstartdate = $row_rsrsEvalDates["startdate"];
		$evalenddate = $row_rsrsEvalDates["enddate"];
		$current_date = date("Y-m-d");
		$formid = $row_rsrsEvalDates["id"];
		$indid = $row_rsrsEvalDates["indid"];
		$sdate = date_create($evalstartdate);
		$startdate = date_format($sdate, "d M Y");
		$edate = date_create($evalenddate);
		$enddate = date_format($edate, "d M Y");
	}

    $query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
    $query_proj->execute(array(":projid" => $projid));
    $row_proj = $query_proj->fetch();
	if($row_proj){
		$project = $row_proj["projname"];
		$projstage = $row_proj["projstage"];
		$proj_locations = $row_proj["projlga"];
		$projlocations = explode(",", $proj_locations);
		$proj_location_count = count($projlocations);
	}

    $query_yesno_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=:resultstype AND resultstypeid=:resultstypeid AND answertype=1");
    $query_yesno_questions->execute(array(":projid" => $projid, ":resultstype" => $resultstype,":resultstypeid" => $resultstypeid));
    //$row_main_questions = $query_main_questions->fetch();
    $count_yesno_questions = $query_yesno_questions->rowCount();

    $query_other_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype=:resultstype AND resultstypeid=:resultstypeid AND  answertype<>1");
    $query_other_questions->execute(array(":projid" => $projid, ":resultstype" => $resultstype,":resultstypeid" => $resultstypeid));
    //$row_other_questions = $query_other_questions->fetchAll();
    $count_other_questions = $query_other_questions->rowCount();

    $query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
    $query_indicator->execute(array(":indid" => $indid));
    $row_indicator = $query_indicator->fetch();
    $rowspan = 2;
    $colspan = 2;
    if (!empty($row_indicator)) {
      $change = $row_indicator["indicator_name"];
		$expected_change = $row_indicator["indicator_name"];
      $unit = $row_indicator["unit"];
      $disaggregated = $row_indicator["indicator_disaggregation"];
      $indicator = $unit . " of " . $change;
		$calculation_method = $row_indicator["indicator_calculation_method"];
      $count_disaggregations = '';
	  
        $query_calculation_method = $db->prepare("SELECT method FROM tbl_indicator_calculation_method WHERE id=:calmethod");
        $query_calculation_method->execute(array(":calmethod" => $calculation_method));
        $row_calculation_method = $query_calculation_method->fetch();
		$calculationmethod = $row_calculation_method["method"];

      if ($disaggregated == 1) {
        $query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
        $query_indicator_disag_type->execute(array(":indid" => $indid));
        $row_indicator_disag_type = $query_indicator_disag_type->fetch();
        $variable_category = $row_indicator_disag_type["category"];

        $query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
        $query_indicator_disaggregations->execute(array(":indid" => $indid));
        $row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
        $count_disaggregations = $query_indicator_disaggregations->rowCount();
        /* $colspan = 2 * $count_disaggregations;
        $rowspan = 2 + 1; */
		$rowspan = 3;
		$colspan = $count_disaggregations;
      }
    }

    $query_data_source = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
    $query_data_source->execute(array(":projid" => $projid));
    $row_data_source = $query_data_source->fetch();
	if($row_data_source){
		$datasource = $row_data_source["data_source"];
	}


    $query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
    $query_variables_cat->execute();
    $row_variables_cat = $query_variables_cat->fetch();
	if($row_variables_cat){
		$category = $row_variables_cat["indicator_calculation_method"];
	}


	$evaluationtype = "Baseline";
	if ($projstage == 10) {
		$evaluationtype = "Endline";
	}
	
	$pageTitle = "Project ".$resultstype." ".$evaluationtype." Evaluation Conclusion";
  }catch (PDOException $ex){
      $result = flashMessage("An error occurred: " .$ex->getMessage());
      print($result);
  }
?>
  <script src="ckeditor/ckeditor.js"></script>

  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?= $pageTitle ?>
          <div class="btn-group" style="float:right">
            <div class="btn-group" style="float:right">
              <input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='view-project-survey.php'" id="btnback">
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
            <div class="header">
              <div class="row clearfix">
                <div class="col-md-12">
                  <h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:5px; line-height:35px !important;">
                    <strong>Project Name: <?= $project ?></strong>
                  </h5>
                </div>
              </div>
            </div>
            <div class="body">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="control-label">Sample Size :</label>
                    <div class="form-line">
                      <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                        <strong><?php echo $sample; ?> Per Ward</strong>
                      </div>
                    </div>
                  </div>
                  <?php
                  if ($disaggregated == 1) {
                    echo '
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					  <label class="control-label">Disaggregation Type:</label>
					  <div class="form-line">
						<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
						  <strong>' . $variable_category . '</strong>
						</div>
					  </div>
					</div>';
                  }
                  ?>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					  <label class="control-label">Calculation Method:</label>
					  <div class="form-line">
						<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
						  <strong><?php echo $calculationmethod; ?></strong>
						</div>
					  </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					  <label class="control-label">Survey Start Date:</label>
					  <div class="form-line">
						<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
						  <strong><?php echo $startdate; ?></strong>
						</div>
					  </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					  <label class="control-label">Survey End Date:</label>
					  <div class="form-line">
						<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
						  <strong><?php echo $enddate; ?></strong>
						</div>
					  </div>
					</div>
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<fieldset class="scheduler-border">
						<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> All Answers Per Question: </strong>
						</legend>
						<?php	
						$query_form_locations =  $db->prepare("SELECT level3 FROM tbl_indicator_baseline_survey_details WHERE formid=:formid ORDER BY id ASC");
						$query_form_locations->execute(array(":formid" => $formid));
						$rows_form_locations = $query_form_locations->fetchAll();
						
						$query_answers_type =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid and resultstype=:resultstype and resultstypeid=:resultstypeid ORDER BY id ASC");
						$query_answers_type->execute(array(":projid" => $projid, ":resultstype" => $resultstypeid, ":resultstypeid" => $resultsid));
						$count_answers_type = $query_answers_type->rowCount();
						?>
						
						<div class="row clearfix">
							<?php
							if($count_answers_type > 0){
								$nb=0;
								while($rows_answers_type = $query_answers_type->fetch()){
									$questionid = $rows_answers_type["id"];
									$question = $rows_answers_type["question"];
									$questiontype = $rows_answers_type["questiontype"];
									$answertype = $rows_answers_type["answertype"];
									$answerlabels = $rows_answers_type["answerlabels"];
									$nb++;
									echo '
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Question '.$nb.': '.$question.'</label>';
										
										if($answertype == 1){
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<td style="width:5%">#</td>
													<th style="width:50%">'.$level2label.'</th>
													<th style="width:45%">Answer</th>
												</tr>
											  </thead>
											  <tbody>';
											  
											  $totalanswer = $sr=0;
												foreach($rows_form_locations as $locationid){
													$sr++;
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
													echo '<tr class="bg-lime">
														<td>'.$sr.'</td>
														<td>'.$location.'</td>';
														
														$query_answers =  $db->prepare("SELECT answer FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid");
														$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid));
														$count_answers = $query_answers->rowCount();
														
														if($calculation_method == 1){
															$question_answer = 0;
															while($rows_answers = $query_answers->fetch()){
																$question_answer +=$rows_answers["answer"];
															}
															$totalanswer += $question_answer;
															$totalamount = $totalanswer;
														}elseif($calculation_method == 2){
															$summation = 0;
															while($rows_answers = $query_answers->fetch()){
																$summation +=$rows_answers["answer"];
															}																
														}elseif($calculation_method == 3){
															$question_answer = $summation = 0;
															while($rows_answers = $query_answers->fetch()){
																$summation +=$rows_answers["answer"];
															}
															$question_answer = $summation / $count_answers;
															$totalanswer += $question_answer;
															$totalamount = $totalanswer / $sr;
														}
														
														echo '<td>'.$question_answer.' '.$unit.'</td>
													</tr>';
												}
												echo '<tr class="bg-green">
													<td></td>
													<td><strong>';
														if($calculation_method == 1){
															echo 'Total Number';
														}elseif($calculation_method == 3){
															echo 'Average Number';
														}
													echo '</strong></td>
													<td><strong>'.$totalamount.' '.$unit.'</strong></td>
												</tr>';
											  echo '</tbody>
											</table>';
										}
										elseif($answertype == 2){
											$answerlabels = explode(",",$answerlabels);
											$answerlabelscount = count($answerlabels);
											$colwidth = number_format((80/$answerlabelscount),2);
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<td style="width:5%">#</td>
													<th style="width:20%">'.$level2label.'</th>';
													foreach($answerlabels as $answerlabel){
														echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
													}
												echo '</tr>
											  </thead>
											  <tbody>';
											  $sr=0;
												foreach($rows_form_locations as $locationid){
													$sr++;
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
													echo '<tr class="bg-lime">
														<td class="bg-light-green">'.$sr.'</td>
														<td class="bg-light-green">'.$location.'</td>';
														foreach($answerlabels as $answerlabel){
															$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and answer=:answerlabel");
															$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":answerlabel" => $answerlabel));
															$count_answers = $query_answers->rowCount();
															
															echo '<td class="bg-light-green">'.$count_answers.'</td>';
														}
													echo '</tr>';
												}
											  echo '</tbody>
											</table>';											
										}elseif($answertype == 3){
											$answerlabels = explode(",",$answerlabels);
											$answerlabelscount = count($answerlabels);
											$colwidth = number_format((80/$answerlabelscount),2);
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<td style="width:5%">#</td>
													<th style="width:20%">'.$level2label.'</th>';
													foreach($answerlabels as $answerlabel){
													  echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
													}
												echo '</tr>
											  </thead>
											  <tbody>';
												$sr=0;
												foreach($rows_form_locations as $locationid){
													$sr++;
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
											  
													echo '<tr class="bg-lime">
														<td class="bg-light-green">'.$sr.'</td>
														<td class="bg-light-green">'.$location.'</td>';
														foreach($answerlabels as $answerlabel){
															$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid");
															$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid));
															$countanswers = 0;
															while($rows_answers = $query_answers->fetch()){
																$answerarray = explode(",",$rows_answers["answer"]);
																if(in_array($answerlabel,$answerarray)){
																	$countanswers++;
																}
															}
															echo '<td class="bg-light-green">'.$countanswers.'</td>';
														}
													echo '</tr>';
												}
											  echo '</tbody>
											</table>';
										}elseif($answertype == 4){
											$answerlabels = explode(",",$answerlabels);
											$answerlabelscount = count($answerlabels);
											$colwidth = number_format((80/$answerlabelscount),2);
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<td style="width:5%">#</td>
													<th style="width:20%">'.$level2label.'</th>';
													foreach($answerlabels as $answerlabel){
													  echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
													}
												echo '</tr>
											  </thead>
											  <tbody>';
												$sr=0;
												foreach($rows_form_locations as $locationid){
													$sr++;
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
											  
													echo '
													<tr class="bg-lime">
														<td class="bg-light-green">'.$sr.'</td>
														<td class="bg-light-green">'.$location.'</td>';
														foreach($answerlabels as $answerlabel){
															$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid");
															$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid));
															$count_answers = $query_answers->rowCount();
															
															echo '<td class="bg-light-green">'.$count_answers.'</td>';
														}
													echo '</tr>';
												}
											  echo '</tbody>
											</table>';
											
										}elseif($answertype == 5){
											
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<th style="width:5%">#</th>
													<th style="width:20%">'.$level2label.'</th>
													<th style="width:75%">Description</th>
												</tr>
											  </thead>
											  <tbody>';
												$sr=0;
												foreach($rows_form_locations as $locationid){
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
													
													$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid");
													$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid));
													$count_answers = $query_answers->rowCount();
													
													while($rows_answers = $query_answers->fetch()){
														$sr++;
														$answer = $rows_answers["answer"];
														echo '<tr class="bg-lime">
															<td class="bg-light-green">'.$sr.'</td>
															<td class="bg-light-green">'.$location.'</td>
															<td class="bg-light-green" style="color:black;">
																<textarea style="border:#CCC thin solid; border-radius:5px; color:black; padding:5px; width:100%" colspan="" rowspan="" readonly>'.$answer.'</textarea>
															</td>
														</tr>';
													}
												}
											  echo '</tbody>
											</table>';
										}elseif($answertype == 6){												
											echo '
											<table class="table table-bordered table-striped">
											  <thead>
												<tr class="bg-light-blue">
													<th style="width:5%">#</th>
													<th style="width:20%">'.$level2label.'</th>
													<th style="width:75%">File</th>
												</tr>
											  </thead>
											  <tbody>';
												$sr=0;
												foreach($rows_form_locations as $locationid){
													$locationid = $locationid["level3"];
													$query_answer_location = $db->prepare("SELECT * FROM tbl_state WHERE id=:locationid");
													$query_answer_location->execute(array(":locationid" => $locationid));
													$rows_answer_location = $query_answer_location->fetch();
													$location = $rows_answer_location["state"];
													
													$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid");
													$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid));
													$count_answers = $query_answers->rowCount();
													
													while($rows_files = $query_answers->fetch()){
														$sr++;
														$answer = $rows_files["answer"];
														echo '<tr class="bg-lime">
															<td class="bg-light-green">'.$sr.'</td>
															<td class="bg-light-green">'.$location.'</td>
															<td class="bg-light-green"><a href="'.$answer.'" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new">'.str_replace("uploads/evaluation/", "",$answer).'</a></td>
														</tr>';
													}
												}
											  echo '</tbody>
											</table>';
										}
									echo '</div>';
								}
							}
							?>		
						</div>
					</fieldset>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <fieldset class="scheduler-border">
                    <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Conclusion: </strong>
                    </legend>
                    <form method="POST" name="submitevalfrm" action="" enctype="multipart/form-data" autocomplete="off">
                      <?php
                      $query_variables_cat =  $db->prepare("SELECT category FROM tbl_indicator_measurement_variables WHERE indicatorid='$indid' GROUP BY indicatorid LIMIT 1");
                      $query_variables_cat->execute();
                      $row_variables_cat = $query_variables_cat->fetch();

                      $query_variables =  $db->prepare("SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid='$indid' order by id ASC");
                      $query_variables->execute();
                      $row_variable = $query_variables->fetchAll();
                      $n = 0;
                      foreach ($projlocations as $locations) {
                        $n++;
                        $query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
                        $query_location->execute();
                        $row_location = $query_location->fetch();
                        $location = $row_location["state"];
						
						$query_loc_yes_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions q left join tbl_project_evaluation_answers a on a.questionid=q.id left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE q.projid='$projid' and formid='$formid' and q.questiontype=1 and answer=1 and level3='$locations'");
						$query_loc_yes_answers->execute();
						$count_loc_yes_answers = $query_loc_yes_answers->rowCount();
						
						$query_loc_no_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions q left join tbl_project_evaluation_answers a on a.questionid=q.id left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE q.projid='$projid' and formid='$formid' and q.questiontype=1 and answer=0 and level3='$locations'");
						$query_loc_no_answers->execute();
						$count_loc_no_answers = $query_loc_no_answers->rowCount();
						$count_loc_total_answers = $count_loc_yes_answers + $count_loc_no_answers;
						
                        echo '<input name="location[]" type="hidden" value="' . $locations . '"/>';
                      ?>
                        <fieldset class="scheduler-border">
                          <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Location <?= $n ?>: <?= $location ?></strong>
                          </legend>
                          <h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Measurement Variables</strong></h5>
                          <?php
                          if ($disaggregated == 1) {
                            foreach ($row_indicator_disaggregations as $disaggregations) {
                              foreach ($row_variable as $row_variables) {
                                $type = $row_variables["type"];
                                $variable = $row_variables["measurement_variable"];
                                $variableid = $row_variables["id"];
                                $disaggregationid = $disaggregations["disid"];
                                echo '<input name="disaggregationid[]" type="hidden" value="' . $disaggregationid . '"/>';
                                if ($category == 2) {
                                  if ($type == "n") {
									?>
                                    <div class="col-md-5">
                                      <label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
                                      <div class="form-line">
                                        <input name="numerator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" value="<?=$count_loc_yes_answers?>" required />
                                      </div>
                                    </div>
                                  <?php
                                  } elseif ($type == "d") { ?>
                                    <div class="col-md-5">
                                      <label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
                                      <div class="form-line">
                                        <input name="denominator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter denominator value" value="<?=$count_loc_total_answers?>" style="border:#CCC thin solid; border-radius: 5px" required />
                                      </div>
                                    </div>
                                  <?php
                                  }
                                } elseif ($category == 1 || $category == 3) {
                                  if ($type == "n") {
                                  ?>
                                    <div class="col-md-6">
                                      <label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
                                      <div class="form-line">
                                        <input name="numerator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter value" value="<?=$count_loc_yes_answers?>" style="border:#CCC thin solid; border-radius: 5px" required />
                                      </div>
                                    </div>
                                  <?php
                                  }
                                }
                              }
                            }
                          } else {
                            foreach ($row_variable as $row_variables) {
                              $type = $row_variables["type"];
                              $variable = $row_variables["measurement_variable"];
                              $variableid = $row_variables["id"];
                              if ($category == 2) {
                                if ($type == "n") {
                                  ?>
                                  <div class="col-md-5">
                                    <label class="control-label"><?= $variable ?>:</label>
                                    <div class="form-line">
                                      <input name="numerator<?= $locations ?>" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" value="<?=$count_loc_yes_answers?>" required />
                                    </div>
                                  </div>
                                <?php
                                } elseif ($type == "d") {
                                ?>
                                  <div class="col-md-5">
                                    <label class="control-label"><?= $variable ?>:</label>
                                    <div class="form-line">
                                      <input name="denominator<?= $locations ?>" type="text" class="form-control" placeholder="Enter denominator value" value="<?=$count_loc_total_answers?>" style="border:#CCC thin solid; border-radius: 5px" required />
                                    </div>
                                  </div>
                                <?php
                                }
                              } elseif ($category == 1 || $category == 3) {
                                if ($type == "n") {
                                ?>
                                  <div class="col-md-6">
                                    <label class="control-label"><?= $variable ?>:</label>
                                    <div class="form-line">
                                      <input name="numerator<?= $locations ?>" type="text" class="form-control" placeholder="Enter value" style="border:#CCC thin solid; border-radius: 5px" required />
                                    </div>
                                  </div>
                          <?php
                                }
                              }
                            }
                          }
                          ?>
                        </fieldset>
                      <?php
                      }
                      ?>
                      <label class="control-label">Evaluation Conclusion <font align="left" style="background-color:#CDDC39">(Explain your conclusion on this evaluation)</font>*:</label>
                      <p align="left">
                        <textarea name="comments" cols="45" rows="3" class="txtboxes" id="evalconcl" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
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
                      <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                          <a href="project-survey" class="btn btn-warning" style="margin-right:10px">Cancel</a>
                          <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
                          <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
                          <input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
							<input name="resultstypeid" type="hidden" id="resultstypeid" value="<?php echo $resultsid; ?>" />
							<input name="resultstype" type="hidden" id="resultstype" value="<?php echo $resultstypeid; ?>" />
                          <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                          <input name="category" type="hidden" class="form-control" value="<?php echo $category; ?>" />
                          <input name="surveytype" type="hidden" value="<?php echo $evaluationtype; ?>" />
                          <input name="disaggregation" type="hidden" value="<?php echo $disaggregated; ?>" />
                          <input name="projstage" type="hidden" value="<?php echo $projstage + 1; ?>" />
                       
                        </div>
                      </div>
                    </form>
                  </fieldset>
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