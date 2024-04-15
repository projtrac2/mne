<?php 
try {
$decode_frm = (isset($_GET['frm']) && !empty($_GET["frm"])) ? base64_decode($_GET['frm']) : header("Location: view-project-impact-evaluation"); 
$formid_array = explode("surveydata", $decode_frm);
$formid = $formid_array[1];

require('includes/head.php'); 
if ($permission) {
    $query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:frmid");
    $query_rsEvalDates->execute(array(":frmid" => $formid));
    $row_rsrsEvalDates = $query_rsEvalDates->fetch();
    $totalRows_rsEvalDates = $query_rsEvalDates->rowCount();

    $formname = $row_rsrsEvalDates["form_name"];
    $projid = $row_rsrsEvalDates["projid"];
    $enumeratortype = $row_rsrsEvalDates["enumerator_type"];
    $sample = $row_rsrsEvalDates["sample_size"];
    $resultstype = $row_rsrsEvalDates["resultstype"];
    $resultstypeid = $row_rsrsEvalDates["resultstypeid"];
    $evalstartdate = $row_rsrsEvalDates["startdate"];
    $evalenddate = $row_rsrsEvalDates["enddate"];
    $evalstartdate = $row_rsrsEvalDates["startdate"];
    $evalenddate = $row_rsrsEvalDates["enddate"];
    $current_date = date("Y-m-d");
    $evalid = $row_rsrsEvalDates["id"];
    $indid = $row_rsrsEvalDates["indid"];
    $sdate = date_create($evalstartdate);
    $startdate = date_format($sdate, "d M Y");
    $edate = date_create($evalenddate);
    $enddate = date_format($edate, "d M Y");

    $query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
    $query_proj->execute(array(":projid" => $projid));
    $row_proj = $query_proj->fetch();
    $project = $row_proj["projname"];

    $query_yesno_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND answertype=1");
    $query_yesno_questions->execute(array(":projid" => $projid));
    //$row_main_questions = $query_main_questions->fetch();
    $count_yesno_questions = $query_yesno_questions->rowCount();

    $query_other_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND answertype<>1");
    $query_other_questions->execute(array(":projid" => $projid));
    //$row_other_questions = $query_other_questions->fetchAll();
    $count_other_questions = $query_other_questions->rowCount();

    $query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
    $query_indicator->execute(array(":indid" => $indid));
    $row_indicator = $query_indicator->fetch();
    $rowspan = 2;
    $colspan = 2;
    if(!empty($row_indicator)){
      $indicator = $row_indicator["indicator_name"];
      $ind_calculation_method = $row_indicator["indicator_calculation_method"];
      $unit = $row_indicator["unit"];
      $disaggregated = $row_indicator["indicator_disaggregation"];
      //$indicator = $unit." of ".$change;
      $count_disaggregations = '';
	  
        $query_calculation_method = $db->prepare("SELECT method FROM tbl_indicator_calculation_method WHERE id=:calmethod");
        $query_calculation_method->execute(array(":calmethod" => $ind_calculation_method));
        $row_calculation_method = $query_calculation_method->fetch();
		$calculation_method = $row_calculation_method["method"];
		
		
      if($disaggregated == 1){
        $query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
        $query_indicator_disag_type->execute(array(":indid" => $indid));
        $row_indicator_disag_type = $query_indicator_disag_type->fetch();
        $category = $row_indicator_disag_type["category"];

        $query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
        $query_indicator_disaggregations->execute(array(":indid" => $indid));
        $row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
        $count_disaggregations = $query_indicator_disaggregations->rowCount();
        $colspan = 2 * $count_disaggregations;
        $rowspan = 2 + 1;
      }
    }
	$surveytype = $resultstype == 1 ? "Impact" : "Outcome";
	$evaluationurl = $resultstype == 1 ? "view-project-impact-evaluation.php" : "view-project-survey.php";
  

?>
   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
				<?= $icon ?>
				<?php echo "Project ".$surveytype." Evaluation" ?>
				<div class="btn-group" style="float:right">
					<input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='<?=$evaluationurl?>'" id="btnback">
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
                         <strong>Project Name: <?=$project?></strong>
                       </h5>
                     </div>
                   </div>
                 </div>
                  <div class="body">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                          <label class="control-label">Number of forms (Sample Size) :</label>
                          <div class="form-line">
                            <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                              <strong><?php echo $sample; ?> Per Location</strong>
                            </div>
                          </div>
                        </div>
                        <?php
                        if($disaggregated == 1){
                        echo '
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                          <label class="control-label">Disaggregation Type:</label>
                          <div class="form-line">
                            <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                              <strong>'.$category.'</strong>
                            </div>
                          </div>
                        </div>';
                        }
                        ?>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                          <label class="control-label">Calculation Method:</label>
                          <div class="form-line">
                            <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                              <strong><?php echo $calculation_method; ?></strong>
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
							
							$query_answers_type =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid and resultstype=:resultstype and resultstypeid=:resultstypeid and questiontype = 1 ORDER BY id ASC");
							$query_answers_type->execute(array(":projid" => $projid, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid));
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
										$question_calculation_method = $rows_answers_type["question_calculation_method"];
										$nb++;
										echo '
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="control-label">Question '.$nb.': '.$question.'</label>';
											
											if($answertype == 1){
												echo '
												<table class="table table-bordered table-striped">
												  <thead>
													<tr class="bg-light-blue">';
														if($disaggregated == 1){
															echo '
															<td rowspan="2" style="width:5%">#</td>
															<th rowspan="2" style="width:50%">'.$level2label.'</th>
															<th colspan="'.$colspan.'" style="width:45%">Answer</th>';
														} else {
															echo '
															<td style="width:5%">#</td>
															<th style="width:50%">'.$level2label.'</th>
															<th style="width:45%">Answer</th>';
														}
														echo '
													</tr>';
													if($disaggregated == 1){
														echo '<tr class="bg-light-blue">';
																foreach($row_indicator_disaggregations as $disaggregations){ 
																	echo '<th>'.$disaggregations["disaggregation"].'</th>';
																}
														echo '</tr>';
													}
												  echo '</thead>
												  <tbody>';
												  
												  $sr=0;
												  if($disaggregated == 1){
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
															foreach($row_indicator_disaggregations as $disaggregations){ 
																$disaggregationid = $disaggregations["disid"];
																$query_answers =  $db->prepare("SELECT answer FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregationid");
																$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregationid" => $disaggregationid));
																$count_answers = $query_answers->rowCount();
																$question_answer = $summation = 0;
																
																if($question_calculation_method == 1){ //summation or aggregation
																	while($rows_answers = $query_answers->fetch()){
																		$question_answer +=$rows_answers["answer"];
																	}
																}elseif($question_calculation_method == 3){ //average
																	$summation = 0;
																	while($rows_answers = $query_answers->fetch()){
																		$summation +=$rows_answers["answer"];
																	}	
																	$question_answer = $summation / $count_answers;
																}
																
																echo '<td>'.$question_answer.'</td>';
															}
														echo '</tr>';
													}
													echo '<tr class="bg-green">
														<td></td>
														<td><strong>';
															if($question_calculation_method == 1){
																echo 'Total Number';
															}elseif($question_calculation_method == 3){
																echo 'Average Number';
															}
														echo '</strong></td>';
														foreach($row_indicator_disaggregations as $disaggregations){ 
															$disaggregationid = $disaggregations["disid"];
															$query_disag_answers =  $db->prepare("SELECT answer FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and questionid=:questionid and disaggregation=:disaggregationid");
															$query_disag_answers->execute(array(":formid" => $formid, ":questionid" => $questionid, ":disaggregationid" => $disaggregationid));
															$count_disag_answers = $query_disag_answers->rowCount();
															$question_answer = $summation = $totalamount = $totalanswer = 0;
															if($question_calculation_method == 1){ //summation or aggregation
																while($rows_answers = $query_disag_answers->fetch()){
																	$question_answer +=$rows_answers["answer"];
																}
																$totalanswer += $question_answer;
															}elseif($question_calculation_method == 3){ //average
																$summation = 0;
																while($rows_answers = $query_disag_answers->fetch()){
																	$summation +=$rows_answers["answer"];
																}	
																$question_answer = $summation / $count_disag_answers;
																$totalanswer += $question_answer;
															}
															$totalamount = $totalanswer / $sr;
															echo '<td><strong>'.$totalamount.'</strong></td>';
														}
														echo '
													</tr>';
												  } else {
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
															$question_answer = $summation = $totalamount = $totalanswer = 0;
															if($question_calculation_method == 1){
																while($rows_answers = $query_answers->fetch()){
																	$question_answer +=$rows_answers["answer"];
																}
																$totalanswer += $question_answer;
																$totalamount = $totalanswer;
															}elseif($question_calculation_method == 3){
																while($rows_answers = $query_answers->fetch()){
																	$summation +=$rows_answers["answer"];
																}
																$question_answer = $summation / $count_answers;
																$totalanswer += $question_answer;
																$totalamount = $totalanswer / $sr;
															}
															
															echo '<td>'.$question_answer.'</td>
														</tr>';
													}
													echo '<tr class="bg-green">
														<td></td>
														<td><strong>';
															if($question_calculation_method == 1){
																echo 'Total Number';
															}elseif($question_calculation_method == 3){
																echo 'Average Number';
															}
														echo '</strong></td>
														<td><strong>'.$totalamount.'</strong></td>
													</tr>';
												  }
												  echo '</tbody>
												</table>';
											}
											elseif($answertype == 2){
												$answerlabels = explode(",",$answerlabels);
												$answerlabelscount = count($answerlabels);
												
												if($disaggregated == 1){
													$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
													echo '
													<table class="table table-bordered table-striped">
													  <thead>
														<tr class="bg-light-blue">
															<td style="width:5%">#</td>
															<th style="width:20%">'.$level2label.'</th>';
															foreach($row_indicator_disaggregations as $disaggregations){ 
																foreach($answerlabels as $answerlabel){
																	echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																}
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
																
																foreach($row_indicator_disaggregations as $disaggregations){ 
																	$disaggregationid = $disaggregations["disid"];
																	foreach($answerlabels as $answerlabel){
																		$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and answer=:answerlabel and disaggregation=:disaggregation");
																		$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":answerlabel" => $answerlabel, ":disaggregation" => $disaggregationid));
																		$count_answers = $query_answers->rowCount();
																		
																		echo '<td class="bg-light-green">'.$count_answers.'</td>';
																	}
																}
															echo '</tr>';
														}
													  echo '</tbody>
													</table>';
													
													
												} else {
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
												}												
											}elseif($answertype == 3){
												$answerlabels = explode(",",$answerlabels);
												$answerlabelscount = count($answerlabels);
												
												if($disaggregated == 1){
													$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
													echo '
													<table class="table table-bordered table-striped">
													  <thead>
														<tr class="bg-light-blue">
															<td style="width:5%">#</td>
															<th style="width:20%">'.$level2label.'</th>';
															foreach($row_indicator_disaggregations as $disaggregations){ 
																foreach($answerlabels as $answerlabel){
																  echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																}
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
																foreach($row_indicator_disaggregations as $disaggregations){ 
																	$disaggregationid = $disaggregations["disid"];
																	foreach($answerlabels as $answerlabel){
																		$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregation");
																		$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregation" => $disaggregationid));
																		$countanswers = 0;
																		while($rows_answers = $query_answers->fetch()){
																			$answerarray = explode(",",$rows_answers["answer"]);
																			if(in_array($answerlabel,$answerarray)){
																				$countanswers++;
																			}
																		}
																		echo '<td class="bg-light-green">'.$countanswers.'</td>';
																	}
																}
															echo '</tr>';
														}
													  echo '</tbody>
													</table>';
												} else {
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
												}
											}elseif($answertype == 4){
												$answerlabels = explode(",",$answerlabels);
												$answerlabelscount = count($answerlabels);
												
												if($disaggregated == 1){
													$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
													echo '
													<table class="table table-bordered table-striped">
														<thead>
															<tr class="bg-light-blue">
																<td style="width:5%">#</td>
																<th style="width:20%">'.$level2label.'</th>';
																foreach($row_indicator_disaggregations as $disaggregations){ 
																	foreach($answerlabels as $answerlabel){
																		echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																	}
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
																	foreach($row_indicator_disaggregations as $disaggregations){ 
																		$disaggregationid = $disaggregations["disid"];
																		foreach($answerlabels as $answerlabel){
																			$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregation and answer=:answerlabel");
																			$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregation" => $disaggregationid, ":answerlabel" => $answerlabel));
																			$count_answers = $query_answers->rowCount();
																			
																			echo '<td class="bg-light-green">'.$count_answers.'</td>';
																		}
																	}
																echo '</tr>';
															}
														echo '
														</tbody>
													</table>';
												} else {
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
																	$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and answer=:answerlabel");
																	$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":answerlabel" => $answerlabel));
																	$count_answers = $query_answers->rowCount();
																	
																	echo '<td class="bg-light-green">'.$count_answers.'</td>';
																}
															echo '</tr>';
														}
													  echo '</tbody>
													</table>';
												}
											}elseif($answertype == 5){
												
												echo '
												<table class="table table-bordered table-striped">
												  <thead>
													<tr class="bg-light-blue">
														<th style="width:5%">#</th>
														<th style="width:20%">'.$level2label.'</th>
														<th style="width:75%">Answer</th>
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
										
											
										//Follow up questions
										if($questiontype == 1){
											//evaluation follow-up questions
											$query_survey_follow_up_questions =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE parent_question=:parentquestionid");
											$query_survey_follow_up_questions->execute(array(":parentquestionid" => $questionid));
											$count_survey_follow_up_questions = $query_survey_follow_up_questions->rowCount();
											$sn = 0;
											if ($count_survey_follow_up_questions > 0) {
												while ($row_survey_follow_up_questions = $query_survey_follow_up_questions->fetch()) {
													$sn++;
													$followupsquestionid = $row_survey_follow_up_questions['id'];
													$followupsquestion = $row_survey_follow_up_questions['question'];
													$followupsquestiontype = "Follow Up";
													$followupsparent_question = $row_survey_follow_up_questions['parent_question'];
													$followupsanswertypeid = $row_survey_follow_up_questions['answertype'];
													$answerlabels = $row_survey_follow_up_questions['answerlabels'];
													$followupscalculation_method = $followupsanswerlabels = "N/A";
													$question_calculation_method = $row_survey_follow_up_questions['question_calculation_method'];
													
													echo '
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border bg-orange" style="border:#000 thin solid;; color:white"><strong>Follow Up Question '.$sn.': '.$followupsquestion.'</strong>
															</legend>';
														
														if($followupsanswertypeid == 1){
															echo '
															<table class="table table-bordered table-striped">
															  <thead>
																<tr class="bg-light-blue">';
																	if($disaggregated == 1){
																		echo '
																		<td rowspan="2" style="width:5%">#</td>
																		<th rowspan="2" style="width:50%">'.$level2label.'</th>
																		<th colspan="'.$colspan.'" style="width:45%">Answer</th>';
																	} else {
																		echo '
																		<td style="width:5%">#</td>
																		<th style="width:50%">'.$level2label.'</th>
																		<th style="width:45%">Answer</th>';
																	}
																	echo '
																</tr>';
																if($disaggregated == 1){
																	echo '<tr class="bg-light-blue">';
																			foreach($row_indicator_disaggregations as $disaggregations){ 
																				echo '<th>'.$disaggregations["disaggregation"].'</th>';
																			}
																	echo '</tr>';
																}
															  echo '</thead>
															  <tbody>';
															  
															  $sr=0;
															  if($disaggregated == 1){
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
																		foreach($row_indicator_disaggregations as $disaggregations){ 
																			$disaggregationid = $disaggregations["disid"];
																			$query_answers =  $db->prepare("SELECT answer FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregationid");
																			$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregationid" => $disaggregationid));
																			$count_answers = $query_answers->rowCount();
																			$question_answer = $summation = 0;
																			
																			if($question_calculation_method == 1){ //summation or aggregation
																				while($rows_answers = $query_answers->fetch()){
																					$question_answer +=$rows_answers["answer"];
																				}
																			}elseif($question_calculation_method == 3){ //average
																				$summation = 0;
																				while($rows_answers = $query_answers->fetch()){
																					$summation +=$rows_answers["answer"];
																				}	
																				$question_answer = $summation / $count_answers;
																			}
																			
																			echo '<td>'.$question_answer.'</td>';
																		}
																	echo '</tr>';
																}
																echo '<tr class="bg-green">
																	<td></td>
																	<td><strong>';
																		if($question_calculation_method == 1){
																			echo 'Total Number';
																		}elseif($question_calculation_method == 3){
																			echo 'Average Number';
																		}
																	echo '</strong></td>';
																	foreach($row_indicator_disaggregations as $disaggregations){ 
																		$disaggregationid = $disaggregations["disid"];
																		$query_disag_answers =  $db->prepare("SELECT answer FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and questionid=:questionid and disaggregation=:disaggregationid");
																		$query_disag_answers->execute(array(":formid" => $formid, ":questionid" => $questionid, ":disaggregationid" => $disaggregationid));
																		$count_disag_answers = $query_disag_answers->rowCount();
																		$question_answer = $summation = 0;
																		
																		$totalamount = $totalanswer = 0;
																		if($question_calculation_method == 1){ //summation or aggregation
																			while($rows_answers = $query_disag_answers->fetch()){
																				$question_answer +=$rows_answers["answer"];
																			}
																			$totalanswer += $question_answer;
																		}elseif($question_calculation_method == 3){ //average
																			$summation = 0;
																			while($rows_answers = $query_disag_answers->fetch()){
																				$summation +=$rows_answers["answer"];
																			}	
																			$question_answer = $summation / $count_disag_answers;
																			$totalanswer += $question_answer;
																		}
																		$totalamount = $totalanswer / $sr;
																		echo '<td><strong>'.$totalamount.'</strong></td>';
																	}
																	echo '
																</tr>';
															  } else {
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
																		$question_answer = $summation = 0;
																		if($question_calculation_method == 1){
																			while($rows_answers = $query_answers->fetch()){
																				$question_answer +=$rows_answers["answer"];
																			}
																			$totalanswer += $question_answer;
																			$totalamount = $totalanswer;
																		}elseif($question_calculation_method == 3){
																			while($rows_answers = $query_answers->fetch()){
																				$summation +=$rows_answers["answer"];
																			}
																			$question_answer = $summation / $count_answers;
																			$totalanswer += $question_answer;
																			$totalamount = $totalanswer / $sr;
																		}
																		
																		echo '<td>'.$question_answer.'</td>
																	</tr>';
																}
																echo '<tr class="bg-green">
																	<td></td>
																	<td><strong>';
																		if($question_calculation_method == 1){
																			echo 'Total Number';
																		}elseif($question_calculation_method == 3){
																			echo 'Average Number';
																		}
																	echo '</strong></td>
																	<td><strong>'.$totalamount.'</strong></td>
																</tr>';
															  }
															  echo '</tbody>
															</table>';
														}
														elseif($followupsanswertypeid == 2){
															$answerlabels = explode(",",$answerlabels);
															$answerlabelscount = count($answerlabels);
															
															if($disaggregated == 1){
																$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
																echo '
																<table class="table table-bordered table-striped">
																  <thead>
																	<tr class="bg-light-blue">
																		<td style="width:5%">#</td>
																		<th style="width:20%">'.$level2label.'</th>';
																		foreach($row_indicator_disaggregations as $disaggregations){ 
																			foreach($answerlabels as $answerlabel){
																				echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																			}
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
																			
																			foreach($row_indicator_disaggregations as $disaggregations){ 
																				$disaggregationid = $disaggregations["disid"];
																				foreach($answerlabels as $answerlabel){
																					$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and answer=:answerlabel and disaggregation=:disaggregation");
																					$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":answerlabel" => $answerlabel, ":disaggregation" => $disaggregationid));
																					$count_answers = $query_answers->rowCount();
																					
																					echo '<td class="bg-light-green">'.$count_answers.'</td>';
																				}
																			}
																		echo '</tr>';
																	}
																  echo '</tbody>
																</table>';
																
																
															} else {
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
															}											
														}elseif($followupsanswertypeid == 3){
															$answerlabels = explode(",",$answerlabels);
															$answerlabelscount = count($answerlabels);
															
															if($disaggregated == 1){
																$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
																echo '
																<table class="table table-bordered table-striped">
																  <thead>
																	<tr class="bg-light-blue">
																		<td style="width:5%">#</td>
																		<th style="width:20%">'.$level2label.'</th>';
																		foreach($row_indicator_disaggregations as $disaggregations){ 
																			foreach($answerlabels as $answerlabel){
																			  echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																			}
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
																			foreach($row_indicator_disaggregations as $disaggregations){ 
																				$disaggregationid = $disaggregations["disid"];
																				foreach($answerlabels as $answerlabel){
																					$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregation");
																					$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregation" => $disaggregationid));
																					$countanswers = 0;
																					while($rows_answers = $query_answers->fetch()){
																						$answerarray = explode(",",$rows_answers["answer"]);
																						if(in_array($answerlabel,$answerarray)){
																							$countanswers++;
																						}
																					}
																					echo '<td class="bg-light-green">'.$countanswers.'</td>';
																				}
																			}
																		echo '</tr>';
																	}
																  echo '</tbody>
																</table>';
															} else {
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
															}
														}elseif($followupsanswertypeid == 4){
															$answerlabels = explode(",",$answerlabels);
															$answerlabelscount = count($answerlabels);
															
															if($disaggregated == 1){
																$colwidth = number_format((80/($answerlabelscount * $colspan)),2);
																echo '
																<table class="table table-bordered table-striped">
																	<thead>
																		<tr class="bg-light-blue">
																			<td style="width:5%">#</td>
																			<th style="width:20%">'.$level2label.'</th>';
																			foreach($row_indicator_disaggregations as $disaggregations){ 
																				foreach($answerlabels as $answerlabel){
																					echo '<th style="width:'.$colwidth.'%">'.$answerlabel.'</th>';
																				}
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
																				foreach($row_indicator_disaggregations as $disaggregations){ 
																					$disaggregationid = $disaggregations["disid"];
																					foreach($answerlabels as $answerlabel){
																						$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and disaggregation=:disaggregation and answer=:answerlabel");
																						$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":disaggregation" => $disaggregationid, ":answerlabel" => $answerlabel));
																						$count_answers = $query_answers->rowCount();
																						
																						echo '<td class="bg-light-green">'.$count_answers.'</td>';
																					}
																				}
																			echo '</tr>';
																		}
																	echo '
																	</tbody>
																</table>';
															} else {
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
																				$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_submission s left join tbl_project_evaluation_answers a on a.submissionid=s.id WHERE formid=:formid and level3=:location and questionid=:questionid and answer=:answerlabel");
																				$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $questionid, ":answerlabel" => $answerlabel));
																				$count_answers = $query_answers->rowCount();
																				
																				echo '<td class="bg-light-green">'.$count_answers.'</td>';
																			}
																		echo '</tr>';
																	}
																  echo '</tbody>
																</table>';
															}
														}elseif($followupsanswertypeid == 5){
															
															echo '
															<table class="table table-bordered table-striped">
															  <thead>
																<tr class="bg-grey">
																	<th style="width:5%">#</th>
																	<th style="width:20%">'.$level2label.'</th>
																	<th style="width:75%">Answer</th>
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
																	$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $followupsquestionid));
																	$count_answers = $query_answers->rowCount();
																	
																	while($rows_answers = $query_answers->fetch()){
																		$sr++;
																		$answer = $rows_answers["answer"];
																		echo '<tr class="bg-light-grey">
																			<td class="bg-light-grey">'.$sr.'</td>
																			<td class="bg-light-grey">'.$location.'</td>
																			<td class="bg-light-grey" style="color:black;">
																				<textarea style="border:#CCC thin solid; border-radius:5px; color:black; padding:5px; width:100%" colspan="" rowspan="" readonly>'.$answer.'</textarea>
																			</td>
																		</tr>';
																	}
																}
															  echo '</tbody>
															</table>';
														}elseif($followupsanswertypeid == 6){												
															echo '
															<table class="table table-bordered table-striped">
															  <thead>
																<tr class="bg-grey">
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
																	$query_answers->execute(array(":formid" => $formid, ":location" => $locationid, ":questionid" => $followupsquestionid));
																	$count_answers = $query_answers->rowCount();
																	
																	while($rows_files = $query_answers->fetch()){
																		$sr++;
																		$answer = $rows_files["answer"];
																		echo '<tr class="bg-light-grey">
																			<td class="bg-light-grey">'.$sr.'</td>
																			<td class="bg-light-grey">'.$location.'</td>
																			<td class="bg-light-grey"><a href="'.$answer.'" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new">'.str_replace("uploads/evaluation/", "",$answer).'</a></td>
																		</tr>';
																	}
																}
															  echo '</tbody>
															</table>';
														}
													echo '</fieldset>
													</div>';
												}
											}
										}
									}
								}
								?>		
				            </div>
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
}catch (PDOException $th){
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>
