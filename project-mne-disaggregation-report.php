<?php
try {
	//code...

require('includes/head.php');

if ($permission) {
	$decode_resultsid = (isset($_GET['results']) && !empty($_GET["results"])) ? base64_decode($_GET['results']) : "";
	$resultsid_array = explode("resultsid", $decode_resultsid);
	$resultsid = $resultsid_array[1];
	
	$decode_resultstype = (isset($_GET['resultstype']) && !empty($_GET["resultstype"])) ? base64_decode($_GET['resultstype']) : "";
	$resultstype_array = explode("resultstype", $decode_resultstype);
	$resultstype = $resultstype_array[1];

	$pageName ="Indicator Disaggregation Report";

	$pageTitle = $planlabel;
	

	if($resultstype == 1){
		$query_results_type_data = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE id=:resultsid");
	}else{
		$query_results_type_data = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultsid");
	}
	$query_results_type_data->execute(array(":resultsid" => $resultsid));
	$row_results_type_data = $query_results_type_data->fetch();
	$data_source = $row_results_type_data["data_source"];
	$projid = $row_results_type_data["projid"];
	$indid = $row_results_type_data["indid"];
	$proj = base64_encode("rept321{$projid}");
	
	$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
	$query_proj->execute(array(":projid" => $projid));
	$row_proj = $query_proj->fetch();
	$project = $row_proj["projname"];
	$projstage = $row_proj["projstage"];
	$projstatus = $row_proj["projstatus"];
	$proj_locations = $row_proj["projlga"];
	$projlocations = explode(",",$proj_locations);
	$proj_location_count= count($projlocations);

	if($projstage > 8 && $projstage < 11 && $projstatus != 5){
		$evaluationtype = "Baseline";
		$query_concluded_baseline_evaluations = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=:resultstype AND resultstypeid=:resultstypeid AND survey_type=:surveytype");
		$query_concluded_baseline_evaluations->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":surveytype" => $evaluationtype));
		$row_concluded_baseline_evaluations = $query_concluded_baseline_evaluations->fetch();
		$baseline_report_date = $row_concluded_baseline_evaluations["date_created"];
	}elseif($projstage > 10 && $projstatus == 5){
		$evaluationtype = "Endline";
		$query_concluded_endline_evaluations = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=:resultstype AND resultstypeid=:resultstypeid AND survey_type=:surveytype");
		$query_concluded_endline_evaluations->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":surveytype" => $evaluationtype));
		$row_endline_concluded_evaluations = $query_concluded_endline_evaluations->fetch();
		$endline_report_date = $row_endline_concluded_evaluations["date_created"];
	}

	$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
	$query_indicator->execute(array(":indid" => $indid));
	$row_indicator = $query_indicator->fetch();

	$rowspan = "";
	$colspan = "";
	if(!empty($row_indicator)){
		$indicator = $row_indicator["indicator_name"];
		$unit = $row_indicator["unit"];
		$calculation_method_id = $row_indicator["indicator_calculation_method"];
		$disaggregated = $row_indicator["indicator_disaggregation"];
		$expected_change = $unit." of ".$indicator;
		$count_disaggregations = '';
		
		$query_calculation_method = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id=:calculation_method_id");
		$query_calculation_method->execute(array(":calculation_method_id" => $calculation_method_id));
		$row_calculation_method = $query_calculation_method->fetch();
		$calculation_method = $row_calculation_method["method"];

		if($disaggregated == 1){
			$variable_category = array();
			$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
			$query_indicator_disag_type->execute(array(":indid" => $indid));
			$rowspan = $query_indicator_disag_type->rowCount();
			
			while($row_indicator_disag_type = $query_indicator_disag_type->fetch()){
				$variable_category[] = $row_indicator_disag_type["category"];
			}
			//$variablecategory = explode(",",$variable_category);

			$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
			$query_indicator_disaggregations->execute(array(":indid" => $indid));
			$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
			$count_disaggregations = $query_indicator_disaggregations->rowCount();	
			$colspan = $count_disaggregations;
			//$rowspan = count($variable_category);
		}
	}	
?>
<script src="ckeditor/ckeditor.js"></script>
<section class="content">
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
												<input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='project-mne-report.php?proj=<?=$proj?>'" id="btnback">
											</div>
										</div>
									</div>
								</div>
								<div class="body" style="margin-top:5px">
									<div class="row">					
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: <?=$project?></strong></h5>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="control-label">Indicator:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $indicator; ?></strong>
													</div>
												</div>
											</div>
											<?php
											echo '
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">   		
												<label class="control-label">Disaggregation Type:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong>'; foreach($variable_category as $cat_variable){ echo $cat_variable; } echo '</strong>
													</div>
												</div>
											</div>';
											?>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<label class="control-label">Baseline Report Date:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $baseline_report_date; ?></strong>
													</div>
												</div>
											</div>
											<?php
											if($projstage > 10 && $projstatus == 5){
												$report = $endline_report_date;
											} else {
												$report = "Pending";
											}
											?>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<label class="control-label">Endline Report Date:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $report; ?></strong>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<label class="control-label">Data Measurement Unit:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $unit; ?></strong>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<label class="control-label">Results Calculation Method:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
														<strong><?php echo $calculation_method; ?></strong>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead> 
																<tr class="bg-light-blue">
																	<th colspan="" rowspan="2" style="width:20%">Results</th>
																	<th colspan="" rowspan="2" style="width:10%">Location</th>
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
																	<td class="bg-light-green" rowspan="<?php echo $proj_location_count+1; ?>" style="width:20%">
																		<?php echo $indicator?>
																	</td>
																</tr>
																<?php
																foreach($projlocations as $locations){ 
																	if($projstage ==6){
																		$query_baseline_survey= $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.measurement, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.resultstype=:resultstype and c.resultstypeid=:resultstypeid and level3=:location");
																		$query_baseline_survey->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":location" => $locations));
																		$count_baseline_survey = $query_baseline_survey->rowCount();	
																		$rows_baseline_survey = $query_baseline_survey->fetchAll();
																	}else{
																		$query_baseline_survey= $db->prepare("SELECT disaggregation, measurement FROM tbl_survey_conclusion c inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.resultstype=:resultstype and c.resultstypeid=:resultstypeid and level3=:location");
																		$query_baseline_survey->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":location" => $locations));	
																		$count_baseline_survey = $query_baseline_survey->rowCount();
																		$rows_baseline_survey = $query_baseline_survey->fetchAll();
																	}																			
					
																	if($projstage > 10){
																		$query_endline_survey= $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.measurement, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.resultstype=:resultstype and c.resultstypeid=:resultstypeid and level3=:location");
																		$query_endline_survey->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":location" => $locations));
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
																			$measurement = $row["measurement"];
																			
																			$baseline = number_format($measurement, 2); 
																			
																			echo '<td class="bg-lime text-center"><font color="#f7070b">'.$baseline.'</font></td>';																			
																		}
																		
																		if($projstage > 8 && $projstage < 11 && $projstatus != 5){
																			for($j=0; $j<$count_baseline_survey; $j++){
																				echo '<td class="bg-lime text-center"><font color="#f7070b">Pending</font></td><td class="bg-lime text-center"><font color="#f7070b">Pending</font></td>
																				';
																			}
																		}
																		
																		if($projstage > 10 && $projstatus==5){
																			foreach($rows_endline_survey as $row){
																				if($count_endline_surveys > 0){					
																					$endlinemeasurement = $row["measurement"];
																					$endline = number_format($endlinemeasurement, 2); 
																				}
																				echo '<td class="bg-lime text-center"><font color="#f7070b">'.$endline.'</font></td>';
																			}
																			
																			foreach($rows_endline_survey as $rows){
																				if($count_endline_surveys > 0){					
																					$endline_measurement = $rows["measurement"];
																					$disaggregation = $rows["disaggregation"];
																					 
																					$query_baseline= $db->prepare("SELECT measurement FROM tbl_survey_conclusion WHERE survey_type='Baseline' and c.resultstype=:resultstype and c.resultstypeid=:resultstypeid and disaggregation=:disaggregation");
																					$query_baseline->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid, ":disaggregation" => $disaggregation));	
																					$rows_baseline = $query_baseline->fetch();
																					$baseline_measurement = $rows_baseline["measurement"];
																					
																					$measurement_difference = $endline_measurement - $baseline_measurement;
																					
																					if($calculation_method_id == 2){
																						$change = number_format(($measurement_difference / $baseline_measurement) * 100, 2); 
																					} else {
																						$change = $measurement_difference;
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
																	<td class="bg-green" colspan="2" align="left">Total <?=$indicator?></td>
																	<?php 
																	$query_baseline_survey= $db->prepare("SELECT SUM(measurement) as measurement  FROM tbl_survey_conclusion WHERE survey_type='Baseline' and resultstype=:resultstype and resultstypeid=:resultstypeid");
																	$query_baseline_survey->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid));	
																	$count_baseline_survey = $query_baseline_survey->rowCount();
																	$rows_baseline_survey = $query_baseline_survey->fetchAll();
																	
																	$query_baseline_count= $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE survey_type='Baseline' and resultstype=:resultstype and resultstypeid=:resultstypeid");
																	$query_baseline_count->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid));	
																	$count_baseline_count = $query_baseline_count->rowCount();
																	
																	$combined_baseline = 0;
																	foreach($rows_baseline_survey as $row){
																		$baseline_measurement = $row["measurement"];
																		//$combined_baseline = $combined_baseline + $baseline_measurement;
																		$combined_baseline += $baseline_measurement;																			
																	}
																	echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.number_format($combined_baseline, 2).'</td>';
																	
																		
																	if($projstage > 8 && $projstage < 11 && $projstatus != 5){
																		echo '<td class="bg-green text-center" colspan="'.$colspan.'">Pending</td><td class="bg-green text-center" colspan="'.$colspan.'">Pending</td>';
																	}
																	
																	if($projstage > 10 && $projstatus == 5){
																		$combined_endline = 0;
																		$combined_change = 0;
																		
																		$query_combined_endline= $db->prepare("SELECT SUM(measurement) as measurement FROM tbl_survey_conclusion WHERE survey_type='Endline' and resultstype=:resultstype and resultstypeid=:resultstypeid");
																		$query_combined_endline->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid));
																		$rows_combined_endline = $query_combined_endline->fetch();
																		$count_combined_endline = $query_combined_endline->rowCount();
																		
																		
																		if($count_combined_endline > 0){					
																			$endline_measurement = $rows_combined_endline["measurement"];
																			$combined_endline = number_format($endline_measurement, 2);
																		}
																		echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.$combined_endline.'</td>';
																		
																		
																		$query_combined_change= $db->prepare("SELECT SUM(measurement) AS measurement FROM tbl_survey_conclusion WHERE survey_type='Endline' and resultstype=:resultstype and resultstypeid=:resultstypeid");
																		$query_combined_change->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid));
																		$rows_combined_change = $query_combined_change->fetch();
																		$count_combined_change = $query_combined_change->rowCount();
																		
																		if($count_combined_change > 0){					
																			$endline_measurement = $rows_combined_change["measurement"];
																			 
																			$query_baseline= $db->prepare("SELECT SUM(measurement) AS measurement FROM tbl_survey_conclusion WHERE survey_type='Baseline' and resultstype=:resultstype and resultstypeid=:resultstypeid");
																			$query_baseline->execute(array(":resultstype" => $resultstype, ":resultstypeid" => $resultsid));	
																			$rows_baseline = $query_baseline->fetch();
																			$baseline_measurement = $rows_baseline["measurement"];
																			
																			$measurement_difference = $endline_measurement - $baseline_measurement;
																			
																			if($calculation_method_id == 2){
																				$change = number_format(($measurement_difference / $baseline_measurement) * 100, 2); 
																			} else {
																				$change = number_format($numeratordifference, 2);
																			}
																			
																			$combined_change = $change;
																		}
																		echo '<td class="bg-green text-center" colspan="'.$colspan.'">'.$combined_change.'</td>';
																	}
																	?>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

<?php
} else {
  $results =  restriction();
  echo $results;
}

require('includes/footer.php');

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>