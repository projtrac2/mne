<?php
$formid = (isset($_GET['frm'])) ? base64_decode($_GET['frm']) : header("Location: project-survey"); 
//$formid = base64_encode($frmid);
$pageName ="Project Survey Data";

$Id = 7;
$subId = 23;

require('includes/head.php');
$pageTitle = $planlabel;
?>
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
							$category = $row_indicator_disag_type["category"];

							$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
							$query_indicator_disaggregations->execute(array(":indid" => $indid));
							$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
							$count_disaggregations = $query_indicator_disaggregations->rowCount();	
							$colspan = 2 * $count_disaggregations;
							$rowspan = 2 + 1;
						}
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
												<label class="control-label">Number of forms (Sample Size) :</label>
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
														<strong>'.$category.'</strong>
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
									</div>
								</div>
							</div>
						</div>
					</div>
<?php
    require('includes/footer.php');
?>