<?php
$formid = (isset($_GET['frm'])) ? base64_decode($_GET['frm']) : header("Location: project-survey"); 
//$formid = base64_encode($frmid);
$pageName ="Project Survey Data";

$Id = 7;
$subId = 23;

require('includes/head.php');
$pageTitle = $planlabel;

try{
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

/* 	if ($category == 2) {  
		$denominator = $_POST['denominator'];	
		
		$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :category, :numerator, :denominator, :comments, :user, :date)");
		$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
	} else {
		$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :category, :numerator, :comments, :user, :date)");
		$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
	} */
	
	for($j=0; $j < count($_POST['location']); $j++){
		$level3 = $_POST['location'][$j];
		if($disaggregation){
			$disaggregationid = $_POST['disaggregationid'];
			for($i=0; $i < count($_POST['numerator'.$level3]); $i++){
				$numerator = $_POST['numerator'.$level3][$i];
				if ($category == 2) { 
					$denominator = $_POST['denominator'.$level3][$i]; 	
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, level3, disaggregation, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :level3, :disaggregation, :category, :numerator, :denominator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				} else {
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, level3, disaggregation, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :level3, :disaggregation, :category, :numerator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				}
			}		
		} else {
			if ($category == 2) {  
				$denominator = $_POST['denominator'];	
				$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, level3, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :level3, :category, :numerator, :denominator, :comments, :user, :date)");
				$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
			} else {
				$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, survey_type, level3, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :surveytype, :level3, :category, :numerator, :comments, :user, :date)");
				$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
			}
		}
	}
	
	if ($result) { 
		$currstatus = 3;
		$formstatus = 4;
		//$evalstatus = 3;
		
		$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
		$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus)); 
		
		$query_projstageupdate = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid");
		$query_projstageupdate->execute(array(":projstage" => $projstage, ":projid" => $projid)); 
		
		$url='project-survey'; 
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
}
catch (PDOException $ex){
  function flashMessage($data){
    return $data;
  }
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}
?>
<script src="ckeditor/ckeditor.js"></script>
<section class="content" style="">
    <div class="container-fluid">
        <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
                <i class="fa fa-columns" aria-hidden="true"></i> 
                Project Survey Data and Analysis
            </h4>
        </div>
        <!-- body  -->
        <div class="row clearfix">
            <div class="block-header">
                <?php
                    echo $results;
                ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<?php 
					
					$query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:frmid");
					$query_rsEvalDates->execute(array(":frmid" => $formid));
					$row_rsrsEvalDates = $query_rsEvalDates->fetch();
					$totalRows_rsEvalDates = $query_rsEvalDates->rowCount();
					
					$formname = $row_rsrsEvalDates["form_name"];
					$projid = $row_rsrsEvalDates["projid"];
					$enumeratortype = $row_rsrsEvalDates["enumerator_type"];
					$sample = $row_rsrsEvalDates["sample_size"];
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
					$indid = $row_proj["outcome_indicator"];
					$projstage = $row_proj["projstage"];
					$proj_locations = $row_proj["projstate"];
					$projlocations = explode(",",$proj_locations);
					$proj_location_count= count($projlocations);
					
					$query_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid");
					$query_questions->execute(array(":projid" => $projid));
					//$row_questions = $query_questions->fetchAll();
					$count_questions = $query_questions->rowCount();
					
					$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
					$query_indicator->execute(array(":indid" => $indid));
					$row_indicator = $query_indicator->fetch();
					$rowspan = 2;
					$colspan = 2;
					if(!empty($row_indicator)){
						$change = $row_indicator["indicator_name"];
						$unit = $row_indicator["unit"];
						$disaggregated = $row_indicator["indicator_disaggregation"];
						$indicator = $unit." of ".$change;
						$count_disaggregations = '';
						
						if($disaggregated == 1){
							$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
							$query_indicator_disag_type->execute(array(":indid" => $indid));
							$row_indicator_disag_type = $query_indicator_disag_type->fetch();
							$variable_category = $row_indicator_disag_type["category"];

							$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
							$query_indicator_disaggregations->execute(array(":indid" => $indid));
							$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
							$count_disaggregations = $query_indicator_disaggregations->rowCount();	
							$colspan = 2 * $count_disaggregations;
							$rowspan = 2 + 1;
						}
					}
					
					$query_data_source = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
					$query_data_source->execute(array(":projid" => $projid));
					$row_data_source = $query_data_source->fetch();
					$datasource = $row_data_source["data_source"];
					
																
					$query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
					$query_variables_cat->execute();
					$row_variables_cat = $query_variables_cat->fetch();
					$category = $row_variables_cat["indicator_calculation_method"];
					
					
					$evaluationtype = "Baseline";
					if($projstage == 11){
						$evaluationtype = "Endline";
					}
					?>

					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="header">
									<div class="row clearfix">
										<div class="col-md-12"> 
											<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:5px; line-height:35px !important;">
												<strong>Project Name: <?=$project?></strong>
													<input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='project-survey'" id="btnback">
											</h5>
										</div>
									</div>
								</div>
								<div class="body" style="margin-top:5px">
									<div class="row">					
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<!--<div class="col-md-9">
												<label class="control-label">Change to be measured:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php// echo $change; ?></strong>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<label class="control-label">Unit of Measure:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php// echo $unit; ?></strong>
													</div>
												</div>
											</div>-->
											<div class="col-md-4">
												<label class="control-label">Sample Size :</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $sample; ?></strong>
													</div>
												</div>
											</div>
											<?php
											if($disaggregated == 1){
											echo '
											<div class="col-md-4">   		
												<label class="control-label">Disaggregation Type:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong>'.$variable_category.'</strong>
													</div>
												</div>
											</div>';
											}
											?> 
											<div class="col-md-4">
												<label class="control-label">Survey Start Date:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $startdate; ?></strong>
													</div>
												</div>
											</div>
											<div class="col-md-4">
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
												<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Data: </strong>
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-md-12 table-responsive">
															<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																<thead> 
																	<tr class="bg-light-blue">
																		<th colspan="" rowspan="<?=$rowspan?>" style="width:30%">Questions</th>
																		<th colspan="" rowspan="<?=$rowspan?>" style="width:20%">Project&nbsp;Location/s</th>
																		<th colspan="<?=$colspan?>" rowspan="">Answers</th>
																	</tr>
																	<?php 
																	if($disaggregated == 1){ ?>
																		<tr class="bg-light-blue">
																			<?php 
																			foreach($row_indicator_disaggregations as $disaggregations){ ?>
																				<th colspan="2"><?php echo $disaggregations["disaggregation"] ?></th>
																				<?php 
																			}
																			?>
																		</tr>
																		<tr class="bg-light-blue">
																			<?php 
																			foreach($row_indicator_disaggregations as $disaggregations){ ?>
																				<th>Yes </th>
																				<th>No </th>
																			<?php 
																			}?>
																		</tr>
																		<?php
																	} else {
																	?>
																		<tr class="bg-light-blue">
																			<th>Yes </th>
																			<th>No </th>
																		</tr>
																		<?php 
																	}
																	?>
																</thead>
																<tbody>
																	<?php 												
																	if($count_questions > 0){
																		$query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=1");
																		$query_answers_yes_total->execute();
																		$count_answers_yes_total = $query_answers_yes_total->rowCount();
																		
																		$query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=0");
																		$query_answers_no_total->execute();
																		$count_answers_no_total = $query_answers_no_total->rowCount();
																		
																		while($row_questions = $query_questions->fetch()){
																			$question = $row_questions["question"];
																			$questionid = $row_questions["id"];
																			
																			$query_proj_location =  $db->prepare("SELECT projstate FROM tbl_projects WHERE projid='$projid'");
																			$query_proj_location->execute();
																			$row_locatios = $query_proj_location->fetch();
																			$proj_locations = $row_locatios["projstate"];
																			$projlocations = explode(",",$proj_locations);
																			$proj_location_count= count($projlocations);
																			
																			?>
																			<tr class="bg-lime">
																				<td class="bg-light-green" rowspan="<?=$proj_location_count?>"><?php echo $question?></td>
																				<?php
																				foreach($projlocations as $locations){ 
																					$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																					$query_location->execute();
																					$row_location = $query_location->fetch();
																					$location = $row_location["state"];
																					?>
																					<td class="bg-lime"><font color="#000"><?php echo $location;?></font></td>
																					<?php 
																					if($disaggregated == 1){
																						foreach($row_indicator_disaggregations as $rows){
																							$disaggregationid = $rows["disid"]; 
																							
																							$query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=1");
																							$query_answers_yes->execute();
																							$count_answers_yes = $query_answers_yes->rowCount();
																							
																							$query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=0");
																							$query_answers_no->execute();
																							$count_answers_no = $query_answers_no->rowCount();
																							?>
																							<td class="bg-lime text-center"><font color="#f7070b"><?=$count_answers_yes?></font></td>
																							<td class="bg-lime text-center"><font color="#f7070b"><?=$count_answers_no?></font></td>
																						<?php 
																						} 
																					} else {
																						$query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=1");
																						$query_answers_yes->execute();
																						$count_answers_yes = $query_answers_yes->rowCount();
																						
																						$query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=0");
																						$query_answers_no->execute();
																						$count_answers_no = $query_answers_no->rowCount();
																						?>
																						<td class="bg-lime text-center"><font color="#f7070b"><?=$count_answers_yes?></font></td>
																						<td class="bg-lime text-center"><font color="#f7070b"><?=$count_answers_no?></font></td>
																						<?php 
																					}
																					echo "</tr>";
																				}
																				?>
																			</tr>
																			<?php
																		}
																		echo '
																		<tr class="bg-lime">
																			<td class="bg-green" colspan="2" align="right">Total</td>';
																			if($disaggregated == 1){
																				foreach($row_indicator_disaggregations as $rows){
																					$disaggregationid = $rows["disid"]; 
																					$query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and a.answer=1 and disaggregation='$disaggregationid'");
																					$query_answers_yes_total->execute();
																					$count_answers_yes_total = $query_answers_yes_total->rowCount(); 
																					
																					$query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=0 and disaggregation='$disaggregationid'");
																					$query_answers_no_total->execute();
																					$count_answers_no_total = $query_answers_no_total->rowCount();
																					
																					echo '<td class="bg-green" align="center">'.$count_answers_yes_total.'</td>
																					<td class="bg-green" align="center">'.$count_answers_no_total.'</td>';
																				}
																			}
																			else {
																				echo '<td class="bg-green" align="center">'.$count_answers_yes_total.'</td>
																				<td class="bg-green" align="center">'.$count_answers_no_total.'</td>';
																			}?>
																		</tr>	
																		<?php
																	}
																	?>
																</tbody>
															</table>
														</div>
													</div>
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
													$n=0;
													foreach($projlocations as $locations){
														$n++;
														$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
														$query_location->execute();
														$row_location = $query_location->fetch();
														$location = $row_location["state"];
														echo '<input name="location[]" type="hidden" value="'.$locations.'"/>';
														?>
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Location <?=$n?>: <?=$location?></strong>
															</legend>
															<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Measurement Variables</strong></h5>
															<?php
															if($disaggregated == 1){ 
																foreach($row_indicator_disaggregations as $disaggregations){
																	foreach($row_variable as $row_variables){
																		//$category = $row_variables["category"];
																		$type = $row_variables["type"];
																		//$indcalculation = $_POST['indcalculation'];
																		$variable = $row_variables["measurement_variable"];
																		$variableid = $row_variables["id"];
																		$disaggregationid=$disaggregations["disid"];
																		echo '<input name="disaggregationid[]" type="hidden" value="'.$disaggregationid.'"/>';
																		if($category ==2){
																			if($type == "n"){
																				?>
																				<div class="col-md-6">
																					<label class="control-label"><?=$variable?> (<?=$disaggregations["disaggregation"]?>):</label>
																					<div class="form-line">
																						<input name="numerator<?=$locations?>[]" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" required />
																					</div>
																				</div>
																				<?php
																			} elseif($type == "d"){ ?>
																				<div class="col-md-6">
																					<label class="control-label"><?=$variable?> (<?=$disaggregations["disaggregation"]?>):</label>
																					<div class="form-line">
																						<input name="denominator<?=$locations?>[]" type="text" class="form-control" placeholder="Enter denominator value" style="border:#CCC thin solid; border-radius: 5px" required />
																					</div>
																				</div>
																				<?php
																			} 
																		} 
																		elseif($category ==1 || $category ==3){
																			if($type == "n"){
																				?>
																				<div class="col-md-6">
																					<label class="control-label"><?=$variable?> (<?=$disaggregations["disaggregation"]?>):</label>
																					<div class="form-line">
																						<input name="numerator<?=$locations?>[]" type="text" class="form-control" placeholder="Enter value" style="border:#CCC thin solid; border-radius: 5px" required />
																					</div>
																				</div>
																				<?php
																			}
																		} 
																	}
																}
															} else {
																foreach($row_variable as $row_variables){
																	//$category = $row_variables["category"];
																	$type = $row_variables["type"];
																	//$indcalculation = $_POST['indcalculation'];
																	$variable = $row_variables["measurement_variable"];
																	$variableid = $row_variables["id"];
																	//echo '<input name="variableid" type="hidden" value="'.$variableid.'"/>';
																	if($category ==2){
																		if($type == "n"){
																			?>
																			<div class="col-md-6">
																				<label class="control-label"><?=$variable?>:</label>
																				<div class="form-line">
																					<input name="numerator" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" required />
																				</div>
																			</div>
																			<?php
																		} elseif($type == "d"){ ?>
																			<div class="col-md-6">
																				<label class="control-label"><?=$variable?>:</label>
																				<div class="form-line">
																					<input name="denominator" type="text" class="form-control" placeholder="Enter denominator value" style="border:#CCC thin solid; border-radius: 5px" required />
																				</div>
																			</div>
																			<?php
																		} 
																	} 
																	elseif($category ==1 || $category ==3){
																		if($type == "n"){
																			?>
																			<div class="col-md-6">
																				<label class="control-label"><?=$variable?>:</label>
																				<div class="form-line">
																					<input name="numerator" type="text" class="form-control" placeholder="Enter value" style="border:#CCC thin solid; border-radius: 5px" required />
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
														<textarea name="comments" cols="45" rows="3"
															class="txtboxes" id="evalconcl"
															style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
															></textarea>
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
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">	
															<a href="project-survey" class="btn btn-warning" style="margin-right:10px">Cancel</a>
															<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
															<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
															<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
															<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
															<input name="category" type="hidden" class="form-control" value="<?php echo $category; ?>" />
															<input name="surveytype" type="hidden" value="<?php echo $evaluationtype; ?>" />
															<input name="disaggregation" type="hidden" value="<?php echo $disaggregated; ?>" />
															<input name="projstage" type="hidden" value="<?php echo $projstage+1; ?>" />
														</div>
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
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
<?php
    require('includes/footer.php');
?>