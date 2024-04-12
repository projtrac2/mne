<?php 

try {
?>
	<div class="container-fluid">
		<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
			<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> OUTPUT REPORT
			</h4>
		</div>
		<!-- Draggable Handles -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<div class="row clearfix" style="margin-top:5px">
							<div class="col-md-12"> 
								<div class="btn-group pull-right">
									<input type="button" VALUE="Go Back" class="btn btn-warning" id="btnback">
								</div>
							</div>
						</div>
					</div>
					<div class="body" style="margin-top:5px">  					
						<div class="card">
							<div class="card-header">
								<ul class="nav nav-tabs txt-cyan" role="tablist">
									<li class="active">
										<a href="#1" role="tab" data-toggle="tab"><div style="color:#673AB7">Yearly Reports</div></a>
									</li>  
									<li>
										<a href="#2" role="tab" data-toggle="tab"><div style="color:#673AB7">Quarterly Reports</div></a>
									</li> 
								</ul>
							</div>
							<div class="card-body tab-content"> 
								<div class="tab-pane active" id="1">
									<div class="row clearfix " id="rowcontainerrow"> 
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
											<div class="header">
												<div class="row clearfix "> 
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px">
														<ul class="list-inline pull-right">
															<li>
																<a href="reports/output-reports-year-doc-one.php?year=<?=$program_year?>" class="btn btn-warning">
																	<i class="fa fa-file-word-o" aria-hidden="true"></i>
																</a>
															</li>
															<li>
																<a href="reports/output-reports-year-one-pdf.php?year=<?=$program_year?>" class="btn btn-primary btn-sm">
																	<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
																</a> 
															</li>
														</ul>
													</div>
												</div> 
											</div>
											<div class="body"> 
												<div class="row clearfix "> 
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
														<?php 
															if($totalRows_rs_program > 0){
																$program_counter=0;
																do{ 
																	$progid = $row_rs_program['progid'];
																	$progname =$row_rs_program['progname'];
																	$outcome =$row_rs_program['outcome']; 

																	$query_rs_program_projects = $db->prepare("SELECT * FROM tbl_projects WHERE progid=:progid ");
																	$query_rs_program_projects->execute(array(":progid"=>$progid));
																	$row_rs_program_projects = $query_rs_program_projects->fetch();
																	$totalRows_rs_program_projects = $query_rs_program_projects->rowCount();
														
																	if($totalRows_rs_program_projects > 0){
																		$program_counter++; 
																		?>
																		<fieldset class="scheduler-border">
																			<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white">
																				<strong>Program Name: </strong><?= $program_counter . "" .$progname?>
																			</legend>
																				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-blue-grey" style="margin-bottom:7px">
																					<strong>Outcome: </strong> <?=$outcome?>
																				</div>  
																					<div class="row clearfix">
																						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																						<div class="col-md-12 table-responsive">
																						<h4><u>Output Analysis</u></h4>
																						<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																							<thead> 
																								<tr class="bg-light-blue">
																									<th colspan="" rowspan="2">Output </th>
																									<th colspan="" rowspan="2">Indicator </th>
																									<th colspan="" rowspan="2">Unit of measure </th>
																									<?php 
																										$years = $target_rows  ='';
																										$noofyears =1;
																										$minyear = $program_year;
																										
																										for($i=0; $i<$noofyears; $i++){
																											$years .= '
																											<th colspan="3" align="center"> 
																												'.$minyear.' 
																											</th>';
																												
																												$target_rows .= '
																												<th>Target</th>
																												<th>Achieved</th>
																												<th>Rate (%)</th>';																											
																											$minyear++;
																										}
																									?>  
																										<?=$years?>
																								</tr>  
																								<tr class="bg-light-blue">
																									<?=$target_rows?>
																								</tr>
																							</thead>
																							<tbody>  
																								<?php 
																									$project_counter=0;
																									do{ 
																										$project_counter++;
																										$projname = $row_rs_program_projects['projname'];
																										$projid = $row_rs_program_projects['projid']; 
																										$colspan = 2 + ($noofyears *3);
																									?>
																									<tr>
																										<td colspan="<?=$colspan?>"><?= $program_counter .".". $project_counter .$projname?></td>
																									</tr>
																									<?php 
																										$query_rs_program_project_details = $db->prepare("SELECT * FROM tbl_project_details  WHERE progid=:progid  AND projid=:projid");
																										$query_rs_program_project_details->execute(array(":progid"=>$progid, ":projid"=>$projid));
																										$row_rs_program_project_details = $query_rs_program_project_details->fetch();
																										$totalRows_rs_program_project_details = $query_rs_program_project_details->rowCount();

																										if($totalRows_rs_program_project_details >0 ){
																											$output_counter="";
																											do{ 
																												$output_counter++;   
																												$outputid =$row_rs_program_project_details['outputid'];  
																												$opid =$row_rs_program_project_details['id'];  
																												$indicatorID =$row_rs_program_project_details['indicator'];  
																												$workplan_interval =$row_rs_program_project_details['workplan_interval']; 

																												$query_rs_program_details = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid=:progid AND id=:outputid");
																												$query_rs_program_details->execute(array(":progid"=>$progid, ":outputid"=>$outputid));
																												$row_rs_program_details = $query_rs_program_details->fetch();
																												$totalRows_rs_program_details = $query_rs_program_details->rowCount();
																												$outputName = $row_rs_program_details['output']; 

																												$query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorID' ");
																												$query_Indicator->execute();
																												$row = $query_Indicator->fetch();
																												$indname = $row['indicator_name'];

																												$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorID' AND baseline=1 AND indicator_category='Output' ");
																												$query_Indicator->execute();
																												$row = $query_Indicator->fetch();
																												$unit = $row['unit'];

																												?>
																												<tr>
																													<td colspan=""><?= $program_counter .".". $project_counter . "." .$output_counter ." ". $outputName?></td>
																													<td colspan=""><?=  $indname?></td>
																													<td colspan=""><?=  $unit?></td>

																												<?php 
																												$start_year = $program_year; 
																												$endyear = $start_year +1;
																												$part ='';
																												$enddate = "-06-30";
																												$startdate = "-07-01";

																												for($i=0; $i<$noofyears; $i++){ 
																													$start = $start_year . $startdate;    
																													$end = $endyear . $enddate;  
																													$achieved ="N/A";
																													$target ="N/A";
																													$rate ="N/A"; 

																													$query_rs_targets = $db->prepare("SELECT sum(target) AS target FROM tbl_workplan_targets WHERE projid=:projid AND outputid=:outputid AND year=:minyear");
																													$query_rs_targets->execute(array(":projid"=>$projid,":outputid"=>$opid, ":minyear"=>$start_year));
																													$row_rs_targets = $query_rs_targets->fetch();
																													$totalRows_rs_targets = $query_rs_targets->rowCount();
																													$target = $row_rs_targets['target'];  

																													$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																													$query_opachievedq4->execute();
																													$row_opachievedq4 = $query_opachievedq4->fetch();
																													$row_opachievedq_row = $query_opachievedq4->rowCount();
																													$achieved = $row_opachievedq4["achieved"];
																													
																													if($achieved == NULL){
																														$achieved =0;
																													}

																													if($target != NULL ){	
																														$rate = number_format($achieved/$target, 2);																														 
																													} else { 
																														if($achieved !=0 ){
																															$achieved =$achieved;
																															$rate ="N/A";
																															$target ="N/A";
																														}else{
																															$target ="N/A";
																															$achieved ="N/A";
																															$rate ="N/A";
																														}
																													}

																													$part .='
																													<td>'.$target.'</td>
																													<td>'.$achieved.'</td>
																													<td>'.$rate.'</td>';
																													
																													$start_year++;
																													$endyear++;
																												}   
																													echo $part;
																													?>
																													
																												</tr>

																													<?php 																	
																											}while( $row_rs_program_project_details = $query_rs_program_project_details->fetch());
																										} 
																									}while($row_rs_program_projects = $query_rs_program_projects->fetch()); 
																								?>
																							</tbody>
																						</table>
																					</div> 
																				</div>
																			</div>
																		</fieldset>
																		<?php 
																	}
																}while($row_rs_program = $query_rs_program->fetch());
															}else{
																echo "No records found";
															}
														?>
													</div>
												</div>
											</div>
											</div>
										</div>
									</div> 
								</div> 
								<div class="tab-pane" id="2">  
									<div class="row clearfix" id="rowcontainerrow"> 
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
											<div class="header">
												<div class="row clearfix "> 
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px">
														<ul class="list-inline pull-right">
															<li>
																<a href="reports/output-reports-quarter-doc.php" class="btn btn-warning">
																	<i class="fa fa-file-word-o" aria-hidden="true"></i>
																</a>
															</li>
															<li>
																<a href="reports/output-reports-quarter-pdf.php" class="btn btn-primary btn-sm" type="button">
																	<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
															 	</a> 
															</li>
														</ul>
													</div>
												</div> 
											</div>
											<div class="body"> 
												<div class="row clearfix "> 
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
													<?php 
														$query_rs_program = $db->prepare("SELECT * FROM tbl_programs WHERE syear=:syear ");
														$query_rs_program->execute(array(":syear"=> $program_year));
														$row_rs_program = $query_rs_program->fetch();
														$totalRows_rs_program = $query_rs_program->rowCount(); 

														if($totalRows_rs_program > 0){
															$program_counter=0;
															do{ 
																$progid = $row_rs_program['progid'];
																$progname =$row_rs_program['progname'];
																$outcome =$row_rs_program['outcome']; 
																
																$query_rs_program_projects = $db->prepare("SELECT * FROM tbl_projects WHERE progid=:progid ");
																$query_rs_program_projects->execute(array(":progid"=>$progid));
																$row_rs_program_projects = $query_rs_program_projects->fetch();
																$totalRows_rs_program_projects = $query_rs_program_projects->rowCount();
													
																if($totalRows_rs_program_projects > 0){
																	$program_counter++;
																	$noofyears=  1;  
																	?>
																	<fieldset class="scheduler-border">
																		<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white">
																			<strong>Program Name: </strong><?= $program_counter . "" .$progname?>
																		</legend>
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-blue-grey" style="margin-bottom:7px">
																				<strong>Outcome: </strong> <?=$outcome?>
																			</div>  
																				<div class="row clearfix">
																					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																					<div class="col-md-12 table-responsive">
																					<h4><u>Output Analysis</u></h4>
																					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																						<thead> 
																							<tr class="bg-light-blue">
																								<th colspan="" rowspan="3">Output </th>
																								<th colspan="" rowspan="3">Indicator </th>
																								<th colspan="" rowspan="3">Unit of measure </th>																									
																								<?php 
																									$years = $target_rows = $target_quarters ='';
																									$minyear = $program_year;

																									for($i=0; $i<$noofyears; $i++){
																										$years .= '
																										<th colspan="12" align="center">  
																												'.$minyear.' 
																										</th>';

																										$target_quarters .= '
																										<th colspan="3">Q1</th>
																										<th colspan="3">Q2</th>
																										<th colspan="3">Q3</th>
																										<th colspan="3">Q4</th>';

																										for($jp=0; $jp < 4; $jp++){																												
																											$target_rows .= '
																											<th>Target</th>
																											<th>Achieved</th>
																											<th>Rate (%)</th>';
																										}
																										$minyear++;
																									}
																								?>  
																									<?=$years?>
																							</tr>
																							<tr class="bg-light-blue">
																								<?=$target_quarters?>
																							</tr>
																							<tr class="bg-light-blue">
																								<?=$target_rows?>
																							</tr> 
																						</thead>
																						<tbody>  
																							<?php 
																								$project_counter=0;
																								do{ 
																									$project_counter++;
																									$projname = $row_rs_program_projects['projname'];
																									$projid = $row_rs_program_projects['projid']; 
																									$colspan = 3 + ($noofyears * 4 * 3);
																								?>

																								<tr>
																									<td colspan="<?=$colspan?>"><?= $program_counter .".". $project_counter .$projname?></td>
																								</tr>

																								<?php 
																									$query_rs_program_project_details = $db->prepare("SELECT * FROM tbl_project_details  WHERE progid=:progid  AND projid=:projid");
																									$query_rs_program_project_details->execute(array(":progid"=>$progid, ":projid"=>$projid));
																									$row_rs_program_project_details = $query_rs_program_project_details->fetch();
																									$totalRows_rs_program_project_details = $query_rs_program_project_details->rowCount();

																									if($totalRows_rs_program_project_details >0 ){
																										$output_counter="";
																										do{ 
																											$output_counter++;   
																											$outputid =$row_rs_program_project_details['outputid'];  
																											$opid =$row_rs_program_project_details['id'];  
																											$indicatorID =$row_rs_program_project_details['indicator'];  
																											$workplan_interval =$row_rs_program_project_details['workplan_interval']; 

																											$query_rs_program_details = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid=:progid AND id=:outputid");
																											$query_rs_program_details->execute(array(":progid"=>$progid, ":outputid"=>$outputid));
																											$row_rs_program_details = $query_rs_program_details->fetch();
																											$totalRows_rs_program_details = $query_rs_program_details->rowCount();
																											$outputName = $row_rs_program_details['output']; 

																											$query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorID' ");
																											$query_Indicator->execute();
																											$row = $query_Indicator->fetch();
																											$indname = $row['indicator_name'];

																											$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorID' AND baseline=1 AND indicator_category='Output' ");
																											$query_Indicator->execute();
																											$row = $query_Indicator->fetch();
																											$unit = $row['unit'];

																											?>
																											<tr>
																												<td colspan=""><?= $program_counter .".". $project_counter . "." .$output_counter ." ". $outputName?></td>
																												<td colspan=""><?=  $indname?></td>
																												<td colspan=""><?=  $unit?></td>																										
																											<?php 
																												$start_year = $program_year; 
																												$endyear = $start_year + 1;
																												$part =''; 

																												for($i=0; $i<$noofyears; $i++){   
																													$achieved ="N/A";
																													$target ="N/A";
																													$rate ="N/A";  

																													if($workplan_interval == 6){  
																														$k=1; 
																														$jp=0;
																														for($j=0; $j < 4; $j++){ 
																															$query_rs_targets = $db->prepare("SELECT target FROM tbl_workplan_targets WHERE projid=:projid AND outputid=:outputid AND year=:minyear");
																															$query_rs_targets->execute(array(":projid"=>$projid,":outputid"=>$opid, ":minyear"=>$start_year));
																															$row_rs_targets = $query_rs_targets->fetch();
																															$totalRows_rs_targets = $query_rs_targets->rowCount();
																															$target = $row_rs_targets['target'];

																															if($jp > 2){ 																																		
																																$start = $endyear . $dates_arr[$jp];
																																$end =  $endyear . $dates_arr[$k];

																																$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																$query_opachievedq4->execute();
																																$row_opachievedq4 = $query_opachievedq4->fetch();
																																$row_opachievedq_row = $query_opachievedq4->rowCount();
																																$achieved = $row_opachievedq4["achieved"];

																																if($achieved ==NULL){
																																	$achieved =0;
																																}																															

																																if($target != NULL && $target  !=0){ 
																																	$target  = $target/4;
																																	$rate = number_format($achieved/$target, 2);																														 
																																} else { 
																																	if($achieved !=0 ){
																																		$achieved =$achieved;
																																		$rate ="N/A";
																																		$target ="N/A";
																																	}else{
																																		$target ="N/A";
																																		$achieved ="N/A";
																																		$rate ="N/A";
																																	}
																																}	
																																$part .='
																																<td>'.$target.'</td>
																																<td>'.$achieved.'</td>
																																<td>'.$rate.'</td>';
																															}else{ 
																																$start = $start_year . $dates_arr[$jp];
																																$end =  $start_year . $dates_arr[$k];

																																$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																$query_opachievedq4->execute();
																																$row_opachievedq4 = $query_opachievedq4->fetch();
																																$row_opachievedq_row = $query_opachievedq4->rowCount();
																																$achieved = $row_opachievedq4["achieved"];

																																if($achieved ==NULL){
																																	$achieved =0;
																																}

																																if($target != NULL && $target !=0 ){
																																	$target  = $target/4;
																																	$rate = number_format($achieved/$target, 2);																														 
																																} else { 
																																	if($achieved !=0 ){
																																		$achieved =$achieved;
																																		$rate ="N/A";
																																		$target ="N/A";
																																	}else{
																																		$target ="N/A";
																																		$achieved ="N/A";
																																		$rate ="N/A";
																																	}
																																}
																																$part .='
																																<td>'.$target.'</td>
																																<td>'.$achieved.'</td>
																																<td>'.$rate.'</td>';
																															}

																															$jp =+2;
																															$k =+2;
																														}  
																													}else if($workplan_interval == 4){   
																														$query_rs_targets = $db->prepare("SELECT *  FROM tbl_workplan_targets WHERE projid=:projid AND outputid=:outputid AND year=:minyear ORDER BY id");
																														$query_rs_targets->execute(array(":projid"=>$projid,":outputid"=>$opid, ":minyear"=>$start_year));
																														$row_rs_targets = $query_rs_targets->fetch();
																														$totalRows_rs_targets = $query_rs_targets->rowCount(); 

																														if($totalRows_rs_targets > 0){
																															if($totalRows_rs_targets == 4){
																																$k=1;
																																$jp=0;
																																do{
																																	$target = $row_rs_targets['target']; 																			
																																	if($jp > 2 ){ 
																																		$start = $endyear . $dates_arr[$jp];
																																		$end =  $endyear . $dates_arr[$k];

																																		$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																		$query_opachievedq4->execute();
																																		$row_opachievedq4 = $query_opachievedq4->fetch();
																																		$row_opachievedq_row = $query_opachievedq4->rowCount();
																																		$achieved = $row_opachievedq4["achieved"];

																																		if($achieved ==NULL){
																																			$achieved =0;
																																		}																															

																																		if($target != NULL && $target !=0 ){
																																			$rate = number_format($achieved/$target, 2);																														 
																																		} else { 
																																			if($achieved !=0 ){
																																				$achieved =$achieved;
																																				$rate ="N/A";
																																				$target ="N/A";
																																			}else{
																																				$target ="N/A";
																																				$achieved ="N/A";
																																				$rate ="N/A";
																																			}
																																		}	

																																		$part .='
																																		<td>'.$target.'</td>
																																		<td>'.$achieved.'</td>
																																		<td>'.$rate.'</td>';
																																	}else{
																																		$start = $start_year . $dates_arr[$jp];
																																		$end =  $start_year . $dates_arr[$k]; 
																																		$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																		$query_opachievedq4->execute();
																																		$row_opachievedq4 = $query_opachievedq4->fetch();
																																		$row_opachievedq_row = $query_opachievedq4->rowCount();
																																		$achieved = $row_opachievedq4["achieved"];

																																		if($achieved ==NULL){
																																			$achieved =0;
																																		}

																																		if($target != NULL && $target !=0 ){ 
																																			$rate = number_format($achieved/$target, 2);																														 
																																		} else { 
																																			if($achieved !=0 ){
																																				$achieved =$achieved;
																																				$rate ="N/A";
																																				$target ="N/A";
																																			}else{
																																				$target ="N/A";
																																				$achieved ="N/A";
																																				$rate ="N/A";
																																			}
																																		}
																																		$part .='
																																		<td>'.$target.'</td>
																																		<td>'.$achieved.'</td>
																																		<td>'.$rate.'</td>';
																																	}
																																	$jp +=2;
																																	$k +=2;
																																}while($row_rs_targets = $query_rs_targets->fetch());
																															}else{
																																if($i == 0){
																																	$remaining_quarters = 4 - $totalRows_rs_targets;

																																	if($remaining_quarters > 0 ){
																																		$k = $jp =0;
																																		if($remaining_quarters ==1){
																																			$k=1;
																																			$jp=0;
																																		}else if($remaining_quarters ==2){
																																			$k=3;
																																			$jp=2;
																																		}else if($remaining_quarters ==3){
																																			$k=5;
																																			$jp=4;
																																		}

																																		for($j =0; $j <$remaining_quarters; $j++){ 
																																			$target ="N/A";
																																			$achieved ="N/A";
																																			$rate ="N/A";

																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																			$k +=2;
																																			$jp +=2;
																																		}
																																	}


																																	$jp =0 ;
																																	$k =0;
																																	if($remaining_quarters == 1){
																																		$k=3;
																																		$jp=2;
																																	}else if($remaining_quarters == 2){
																																		$k=5;
																																		$jp=4;
																																	}else if($remaining_quarters ==3){
																																		$k=7;
																																		$jp=6;
																																	}

																																	do{
																																		$target = $row_rs_targets['target']; 																		
																																		if($jp > 2 ){ 
																																			$start = $endyear . $dates_arr[$jp];
																																			$end =  $endyear . $dates_arr[$k]; 
																																			$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																			$query_opachievedq4->execute();
																																			$row_opachievedq4 = $query_opachievedq4->fetch();
																																			$row_opachievedq_row = $query_opachievedq4->rowCount();
																																			$achieved = $row_opachievedq4["achieved"];

																																			if($achieved ==NULL){
																																				$achieved =0;
																																			}																															

																																			if($target != NULL && $target !=0){
																																				$rate = number_format($achieved/$target, 2);																														 
																																			} else { 
																																				if($achieved !=0 ){
																																					$achieved =$achieved;
																																					$rate ="N/A";
																																					$target ="N/A";
																																				}else{
																																					$target ="N/A";
																																					$achieved ="N/A";
																																					$rate ="N/A";
																																				}
																																			}	
																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																		}else{
																																			$start = $start_year . $dates_arr[$jp];
																																			$end =  $start_year . $dates_arr[$k];

																																			$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																			$query_opachievedq4->execute();
																																			$row_opachievedq4 = $query_opachievedq4->fetch();
																																			$row_opachievedq_row = $query_opachievedq4->rowCount();
																																			$achieved = $row_opachievedq4["achieved"];

																																			if($achieved ==NULL){
																																				$achieved =0;
																																			}

																																			if($target != NULL && $target !=0 ){ 
																																				$rate = number_format($achieved/$target, 2);																														 
																																			} else { 
																																				if($achieved !=0 ){
																																					$achieved =$achieved;
																																					$rate ="N/A";
																																					$target ="N/A";
																																				}else{
																																					$target ="N/A";
																																					$achieved ="N/A";
																																					$rate ="N/A";
																																				}
																																			}
																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																		}
																																		$jp +=2;
																																		$k +2;
																																	}while($row_rs_targets = $query_rs_targets->fetch());																																	
																																}else{
																																	$remaining_quarters = 4 - $totalRows_rs_targets;
																																	$k=1;
																																	$jp=0;

																																	do{
																																		$target = $row_rs_targets['target']; 																		
																																		if($jp > 2 ){ 
																																			$start = $endyear . $dates_arr[$jp];
																																			$end =  $endyear . $dates_arr[$k]; 
																																			$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																			$query_opachievedq4->execute();
																																			$row_opachievedq4 = $query_opachievedq4->fetch();
																																			$row_opachievedq_row = $query_opachievedq4->rowCount();
																																			$achieved = $row_opachievedq4["achieved"];

																																			if($achieved ==NULL){
																																				$achieved =0;
																																			}																															

																																			if($target != NULL  && $target !=0){
																																				$rate = number_format($achieved/$target, 2);																														 
																																			} else { 
																																				if($achieved !=0 ){
																																					$achieved =$achieved;
																																					$rate ="N/A";
																																					$target ="N/A";
																																				}else{
																																					$target ="N/A";
																																					$achieved ="N/A";
																																					$rate ="N/A";
																																				}
																																			}	
																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																		}else{
																																			$start = $start_year . $dates_arr[$jp];
																																			$end =  $start_year . $dates_arr[$k]; 
																																			$query_opachievedq4 = $db->prepare("SELECT sum(actualoutput) AS achieved FROM tbl_monitoringoutput  WHERE opid = '$outputid' AND projid='$projid' AND date_created BETWEEN '$start' AND '$end'");
																																			$query_opachievedq4->execute();
																																			$row_opachievedq4 = $query_opachievedq4->fetch();
																																			$row_opachievedq_row = $query_opachievedq4->rowCount();
																																			$achieved = $row_opachievedq4["achieved"];

																																			if($achieved ==NULL){
																																				$achieved =0;
																																			}

																																			if($target != NULL && $target !=0 ){ 
																																				$rate = number_format($achieved/$target, 2);																														 
																																			} else { 
																																				if($achieved !=0 ){
																																					$achieved =$achieved;
																																					$rate ="N/A";
																																					$target ="N/A";
																																				}else{
																																					$target ="N/A";
																																					$achieved ="N/A";
																																					$rate ="N/A";
																																				}
																																			}
																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																		}
																																		$jp +=2;
																																		$k +2;
																																	}while($row_rs_targets = $query_rs_targets->fetch());


																																	if($remaining_quarters > 0 ){
																																		if($remaining_quarters ==1){
																																			$k=7;
																																			$jp=6;
																																		}else if($remaining_quarters ==2){
																																			$k=5;
																																			$jp=4;
																																		}else if($remaining_quarters ==3){
																																			$k=3;
																																			$jp=2;
																																		}

																																		for($j =0; $j <$remaining_quarters; $j++){ 
																																			$target ="N/A";
																																			$achieved ="N/A";
																																			$rate ="N/A";
																																			$part .='
																																			<td>'.$target.'</td>
																																			<td>'.$achieved.'</td>
																																			<td>'.$rate.'</td>';
																																			$k +=2;
																																			$jp +=2;
																																		}
																																	}	
																																}
																															}
																														}else {					
																															for($j =0; $j <4; $j++){ 
																																$target ="N/A";
																																$achieved ="N/A";
																																$rate ="N/A";
																																$part .='
																																<td>'.$target.'</td>
																																<td>'.$achieved.'</td>
																																<td>'.$rate.'</td>'; 
																															}
																														}  
																													}

																													$start_year++;
																													$endyear++;
																												}  
																												echo $part;																												
																												?>
																												
																											</tr>

																												<?php 																	
																										}while( $row_rs_program_project_details = $query_rs_program_project_details->fetch());
																									}  
																									
																								}while($row_rs_program_projects = $query_rs_program_projects->fetch()); 
																							?>
																						</tbody>
																					</table>
																				</div> 
																			</div>
																		</div>
																	</fieldset>
																	<?php 
																}
															}while($row_rs_program = $query_rs_program->fetch());
														}else{
															echo "No records found";
														}
													?>
													</div>
												</div>
											</div>
											</div>
										</div>
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
}catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>