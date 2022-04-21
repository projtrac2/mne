<?php
$Id = 7;
$subId = 24;
require('includes/head.php');

$projid = (isset($_GET['prjid'])) ? base64_decode($_GET['prjid']) : header("Location: project-concluded-evaluations"); 
//$formid = base64_encode($frmid);
$pageName ="Project Secondary Data Evaluation Report";

$pageTitle = $planlabel;
	
$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
$query_proj->execute(array(":projid" => $projid));
$row_proj = $query_proj->fetch();
$project = $row_proj["projname"];
$projstage = $row_proj["projstage"];
$indid = $row_proj["outcome_indicator"];
$proj_locations = $row_proj["projstate"];
$projlocations = explode(",",$proj_locations);
$proj_location_count= count($projlocations);

$query_evaluation_data_source = $db->prepare("SELECT data_source FROM tbl_project_expected_outcome_details WHERE projid=:projid");
$query_evaluation_data_source->execute(array(":projid" => $projid));
$row_evaluation_data_source = $query_evaluation_data_source->fetch();
$data_source = $row_evaluation_data_source["data_source"];

if($projstage==10){
	$evaluationtype = "Baseline";

	$query_concluded_evaluations = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid AND survey_type=:surveytype");
	$query_concluded_evaluations->execute(array(":projid" => $projid,":surveytype" => $evaluationtype));
	$row_concluded_evaluations = $query_concluded_evaluations->fetch();
}else{
	$query_concluded_evaluations = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid");
	$query_concluded_evaluations->execute(array(":projid" => $projid));
	$row_concluded_evaluations = $query_concluded_evaluations->fetch();
}

$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
$query_indicator->execute(array(":indid" => $indid));
$row_indicator = $query_indicator->fetch();

$rowspan = "";
$colspan = "";
if(!empty($row_indicator)){
	$expected_change = $row_indicator["indicator_name"];
	$unit = $row_indicator["unit"];
	$calculation_method = $row_indicator["indicator_calculation_method"];
	$disaggregated = $row_indicator["indicator_disaggregation"];
	$indicator = $unit." of ".$expected_change;
	$count_disaggregations = '';

	if($disaggregated == 1){
		$variable_category = array();
		$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
		$query_indicator_disag_type->execute(array(":indid" => $indid));
		$rowspan = $query_indicator_disag_type->rowCount();
		while($row_indicator_disag_type = $query_indicator_disag_type->fetch()){
			$variable_category[] = $row_indicator_disag_type["category"];
		}
		$variablecategory = explode(",",$variable_category);

		$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
		$query_indicator_disaggregations->execute(array(":indid" => $indid));
		$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
		$count_disaggregations = $query_indicator_disaggregations->rowCount();	
		$colspan = $count_disaggregations;
		//$rowspan = count($variable_category);
	}
}	

$query_conclusion_comments = $db->prepare("SELECT comments FROM tbl_survey_conclusion WHERE projid=:projid and survey_type=:surveytype");
$query_conclusion_comments->execute(array(":projid" => $projid, ":surveytype" => $evaluationtype));
$row_conclusion_comments = $query_conclusion_comments->fetch();
$comments = $row_conclusion_comments["comments"];	
?>
<script src="ckeditor/ckeditor.js"></script>
<section class="content" style="">
    <div class="container-fluid">
        <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
                <i class="fa fa-columns" aria-hidden="true"></i> 
                Project Evaluation Conclusion Report
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
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="header">
									<div class="row clearfix" style="margin-top:5px">
										<div class="col-md-12"> 
											<div class="btn-group pull-right">
												<input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='project-concluded-evaluations'" id="btnback">
											</div>
										</div>
									</div>
								</div>
								<div class="body" style="margin-top:5px">
									<div class="row">					
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12">
												<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: <?=$project?></strong></h5>
											</div>
											<div class="col-md-12">
												<label class="control-label">Outcome Indicator:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $unit." of ".$expected_change; ?></strong>
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
														<strong>'; foreach($variable_category as $cat_variable){ echo $cat_variable; } echo '</strong>
													</div>
												</div>
											</div>';
											}
											?>
										</div>
									</div>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Beneficiaries: </strong>
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="table-responsive">
														<?php
														if($disaggregated == 0){
															?>
															<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																<thead> 
																	<tr class="bg-light-blue">
																		<th style="width:40%">Beneficiary</th>
																		<th style="width:15%">Location</th>
																		<th style="width:15%">Baseline</th>
																		<th style="width:15%">Endline</th>
																		<th style="width:15%">Change</th>
																	</tr>
																</thead>
																<tbody>
																	<tr class="bg-lime">
																		<td class="bg-light-green" rowspan="<?php echo $proj_location_count+1; ?>">
																			<?php echo $expected_change?>
																		</td>
																	</tr>
																	<?php 
																	foreach($projlocations as $locations){
																		$query_baseline_survey= $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
																		$query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));	
																		$rows_baseline_survey = $query_baseline_survey->fetch();
																		
																		$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																		$query_location->execute();
																		$row_location = $query_location->fetch();
																		$location = $row_location["state"];
																		
																		//$cat = $rows_baseline_survey["cat"];
																		$numerator = $rows_baseline_survey["numerator"];
																		$denominator = $rows_baseline_survey["denominator"];
																		$baseline = '';
																		if($calculation_method == 2){
																			$baseline = number_format(($numerator / $denominator) * 100, 2); 
																		} else {
																			$baseline = $numerator;
																		}
																		
																		$query_endline_survey= $db->prepare("SELECT variable_category AS cat, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and level3=:location");
																		$query_endline_survey->execute(array(":projid" => $projid, ":location" => $locations));	
																		$rows_endline_survey = $query_endline_survey->fetch();
																		$count_endline_surveys = $query_endline_survey->rowCount();
																		
																		$endline = 'Pending';
																		$difference = 'Pending';
																		if($count_endline_surveys > 0){															
																			//$endcategory = $rows_endline_survey["cat"];
																			$endnumerator = $rows_endline_survey["numerator"];
																			$enddenominator = $rows_endline_survey["denominator"];
																			if($calculation_method == 2){
																				$endline = number_format(($endnumerator / $enddenominator) * 100, 2); 
																			} else {
																				$endline = $endnumerator;
																			}
																			$difference = $endline - $baseline;
																		}
																				
																		?>
																		<tr class="bg-lime">
																			<td class="bg-light-green"><?php echo $location; ?></td>
																			<td class="bg-light-green"><?php echo $baseline; ?></td>
																			<td class="bg-light-green"><?php echo $endline; ?></td>
																			<td class="bg-light-green"><?php echo $difference; ?></td>
																		</tr>
																		<?php
																	}
																	?>
																</tbody>
															</table>
														<?php
														} else{
															?>
															<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																<thead> 
																	<tr class="bg-light-blue">
																		<th colspan="" rowspan="2" style="width:20%">Beneficiaries</th>
																		<th colspan="" rowspan="2" style="width:20%">Location</th>
																		<th colspan="<?=$colspan?>" rowspan="" style="width:20%">Baseline</th>
																		<th colspan="<?=$colspan?>" rowspan="" style="width:20%">Endline</th>
																		<th colspan="<?=$colspan?>" rowspan="" style="width:20%">Change</th>
																	</tr>
																	<tr class="bg-light-blue">
																		<?php 
																		for($i=0; $i<3; $i++){
																			foreach($row_indicator_disaggregations as $disaggregations){ ?>
																				<th><?php echo $disaggregations["disaggregation"] ?></th>
																				<?php 
																			}
																		}
																		?>
																	</tr>
																</thead>
																<tbody>
																	<tr class="bg-lime">
																		<td class="bg-light-green" rowspan="<?php echo $proj_location_count+1; ?>">
																			<?php echo $expected_change?>
																		</td>
																	</tr>
																	<?php
																	foreach($projlocations as $locations){ 
																		if($projstage ==10){
																			$query_baseline_survey= $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
																			$query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));
																			$count_baseline_survey = $query_baseline_survey->rowCount();	
																			$rows_baseline_survey = $query_baseline_survey->fetchAll();
																		}else{
																			$query_baseline_survey= $db->prepare("SELECT  disaggregation, numerator, denominator FROM tbl_survey_conclusion inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
																			$query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));	
																			$count_baseline_survey = $query_baseline_survey->rowCount();
																			$rows_baseline_survey = $query_baseline_survey->fetchAll();
																		}																			
						
																		if($projstage ==11){
																			$query_endline_survey= $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and level3=:location");
																			$query_endline_survey->execute(array(":projid" => $projid, ":location" => $locations));
																			$rows_endline_survey = $query_endline_survey->fetchAll();
																			$count_endline_surveys = $query_endline_survey->rowCount();
																		}
																		
																		$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																		$query_location->execute();
																		$row_location = $query_location->fetch();
																		$location = $row_location["state"];
																		?>
																		<tr class="bg-lime">
																			<td class="bg-lime"><font color="#000">
																				<?php echo $location;?></font>
																			</td>
																			<?php 
																			foreach($rows_baseline_survey as $row){
																				$numerator = $row["numerator"];
																				$denominator = $row["denominator"];
																				$baseline = '';
																				if($calculation_method == 2){
																					$baseline = number_format(($numerator / $denominator) * 100, 2); 
																				} else {
																					$baseline = $numerator;
																				}
																				echo '<td class="bg-lime text-center"><font color="#f7070b">'.$baseline.'</font></td>';																			
																			}
																			
																			if($projstage ==10){
																				for($j=0; $j<$count_baseline_survey; $j++){
																					echo '<td class="bg-lime text-center"><font color="#f7070b">Pending</font></td><td class="bg-lime text-center"><font color="#f7070b">Pending</font></td>
																					';
																				}
																			}
																			
																			if($projstage ==11){
																				foreach($rows_endline_survey as $row){
																					if($count_endline_surveys > 0){					
																						$endnumerator = $row["numerator"];
																						$enddenominator = $row["denominator"];
																						if($calculation_method == 2){
																							$endline = number_format(($endnumerator / $enddenominator) * 100, 2); 
																						} else {
																							$endline = $endnumerator;
																						}
																					}
																					echo '<td class="bg-lime text-center"><font color="#f7070b">'.$endline.'</font></td>';
																				}
																				
																				foreach($rows_endline_survey as $rows){
																					if($count_endline_surveys > 0){					
																						$endnumerator = $rows["numerator"];
																						$enddenominator = $rows["denominator"];
																						$disaggregation = $rows["disaggregation"];
																						 
																						$query_baseline= $db->prepare("SELECT numerator, denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid and disaggregation=:disaggregation");
																						$query_baseline->execute(array(":projid" => $projid, ":disaggregation" => $disaggregation));	
																						$rows_baseline = $query_baseline->fetch();
																						$baseline_numerator = $rows_baseline["numerator"];
																						$baseline_denominator = $rows_baseline["denominator"];
																						
																						$numeratordifference = $endnumerator - $baseline_numerator;
																						$denominatordifference = $enddenominator - $baseline_denominator;
																						
																						if($calculation_method == 2){
																							$change = number_format(($numeratordifference / $denominatordifference) * 100, 2); 
																						} else {
																							$change = $numeratordifference;
																						}
																						echo '<td class="bg-lime text-center"><font color="#f7070b">'.$change.'</font></td>';
																					}
																				}
																			}
																			?>
																		</tr>
																	<?php
																	}
																	?>
																	<tr class="bg-lime">
																		<td class="bg-green" colspan="2" align="left">Total <?=$expected_change?></td>
																		<?php 
																		$query_baseline_survey= $db->prepare("SELECT SUM(numerator) as numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
																		$query_baseline_survey->execute(array(":projid" => $projid));	
																		$count_baseline_survey = $query_baseline_survey->rowCount();
																		$rows_baseline_survey = $query_baseline_survey->fetchAll();
																		
																		$query_baseline_count= $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
																		$query_baseline_count->execute(array(":projid" => $projid));	
																		$count_baseline_count = $query_baseline_count->rowCount();
																		
																		$combined_baseline = 0;
																		foreach($rows_baseline_survey as $row){
																			$numerator = $row["numerator"];
																			$denominator = $row["denominator"];
																			$baseline = '';
																			if($calculation_method == 2){
																				$baseline = number_format(($numerator / $denominator) * 100, 2); 
																			} else {
																				$baseline = $numerator;
																			}
																			$combined_baseline = $combined_baseline + $baseline;																			
																		}
																		echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.$combined_baseline.'</td>';
																		
																			
																		if($projstage == 10){
																			echo '<td class="bg-green text-center" colspan="'.$colspan.'">Pending</td><td class="bg-green text-center" colspan="'.$colspan.'">Pending</td>';
																		}
																		
																		if($projstage ==11){
																			$combined_endline = 0;
																			$combined_change = 0;
																			
																			$query_combined_endline= $db->prepare("SELECT SUM(numerator) as numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Endline' and projid=:projid");
																			$query_combined_endline->execute(array(":projid" => $projid));
																			$rows_combined_endline = $query_combined_endline->fetch();
																			$count_combined_endline = $query_combined_endline->rowCount();
																			
																			
																			if($count_combined_endline > 0){					
																				$endnumerator = $rows_combined_endline["numerator"];
																				$enddenominator = $rows_combined_endline["denominator"];
																				if($calculation_method == 2){
																					$endline = number_format(($endnumerator / $enddenominator) * 100, 2); 
																				} else {
																					$endline = $endnumerator;
																				}
																				$combined_endline = $endline;
																			}
																			echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.$combined_endline.'</td>';
																			
																			
																			$query_combined_change= $db->prepare("SELECT SUM(numerator) AS numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Endline' and projid=:projid");
																			$query_combined_change->execute(array(":projid" => $projid));
																			$rows_combined_change = $query_combined_change->fetch();
																			$count_combined_change = $query_combined_change->rowCount();
																			
																			if($count_combined_change > 0){					
																				$endnumerator = $rows_combined_change["numerator"];
																				$enddenominator = $rows_combined_change["denominator"];
																				 
																				$query_baseline= $db->prepare("SELECT SUM(numerator) AS numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
																				$query_baseline->execute(array(":projid" => $projid));	
																				$rows_baseline = $query_baseline->fetch();
																				$baseline_numerator = $rows_baseline["numerator"];
																				$baseline_denominator = $rows_baseline["denominator"];
																				
																				$numeratordifference = $endnumerator - $baseline_numerator;
																				$denominatordifference = $enddenominator - $baseline_denominator;
																				
																				if($calculation_method == 2){
																					$change = number_format(($numeratordifference / $denominatordifference) * 100, 2); 
																				} else {
																					$change = $numeratordifference;
																				}
																				
																				$combined_change = $change;
																			}
																			echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.$combined_change.'</td>';
																		}
																		?>
																	</tr>
																</tbody>
															</table>
															<?php
														} 
														?>
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Conclusion Remarks: </strong>
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="form-line">
															<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
																<strong><?php echo $comments; ?></strong>
															</div>
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