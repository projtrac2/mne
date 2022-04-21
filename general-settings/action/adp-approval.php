<?php

include_once "controller.php";
include_once '../../system-labels.php';

if (isset($_POST["approveProj"]) && $_POST["approveProj"] == "approveProj") {
	$itemId = $_POST['itemId'];
	$TargetB = '';

	$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$itemId'");
	$query_rsProjects->execute();
	$row_rsProjects = $query_rsProjects->fetch();
	$totalRows_rsProjects = $query_rsProjects->rowCount();

	$progid = $row_rsProjects['progid'];
	$projname = $row_rsProjects['projname'];
	$projdurationInDays = $row_rsProjects['projduration'];
	$projfscyear = $row_rsProjects['projfscyear'];
	$projcode = $row_rsProjects['projcode'];
	$projbudget = $row_rsProjects['projbudget'];
	$projenddate = $row_rsProjects['projenddate'];
	$projstartdate = $row_rsProjects['projstartdate'];
	$projduration = $row_rsProjects['projduration'];
	$projid = $row_rsProjects['projid'];

	$query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$projfscyear'");
	$query_rsYear->execute();
	$row_rsYear = $query_rsYear->fetch();
	$projstartyear =  $row_rsYear['yr'];
	$projstart = $projstartyear  . '-07-01';

	//fetch program details 
	$query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
	$query_item->execute();
	$row_item = $query_item->fetch();

	$progstartDate = $row_item['syear'] . '-07-01'; //program start date  
	$progduration = $row_item['years']; //program duration in years 
	$sdate = $row_item['syear'] . '-06-30'; //for calculating program end year   
	$progendDate = date('Y-m-d', strtotime($sdate . " + {$progduration} years"));  //program end date 
	$projectendDate = date('Y-m-d', strtotime($projstart . " + {$projdurationInDays} days"));

	$yr = date("Y");
	$mnth = date("m");
	//$mnth = $mnth + 8;

	if ($mnth >= 7 && $mnth <= 12) {
		$year = $yr;
	} elseif ($mnth >= 1 && $mnth <= 6) {
		$year = $yr - 1;
	}

	$yearnxt = $year + 1;
	$finyear = $year . "/" . $yearnxt;

	//fetch program details 
	$query_projects = $db->prepare("SELECT sum(b.amount) as amount FROM tbl_project_approved_yearly_budget b INNER JOIN tbl_projects p ON p.projid = b.projid WHERE progid = '$progid' AND year='$year'");
	$query_projects->execute();
	$row_projects = $query_projects->fetch();
	$project_budget = $row_projects['amount'];

	//fetch program details 
	$query_programs = $db->prepare("SELECT  sum(budget) as budget FROM tbl_programs_based_budget WHERE progid = '$progid' and finyear='$year'");
	$query_programs->execute();
	$row_programs = $query_programs->fetch();
	$program_budget = $row_programs['budget'];

	$project_budget_ceiling = $program_budget - $project_budget;

	$TargetB .= '
	  <div class="row clearfix">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			  <div class="card"> 
				  <div class="header" >
					  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
						<h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5> 
					  </div>
				  </div>
				  <div class="body"> 
					<div class="row clearfix">
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program Start Date *:</label>  
						<div class="form-line">
						  <input type="hidden"  name="progid" id="progid" class="form-control" value="' . $progid . '" required>
						  <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $itemId . '" required>
						  <input type="hidden" name="projfscyear[]" id="projfscyear" value="' . $projfscyear . '" />
						  <input type="text" name="progstartyear" id="progstartyear" value="' . date('d M Y', strtotime($progstartDate)) . '"  class="form-control" disabled>
						</div>
					  </div>  
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program End Date *:</label>  
						<div class="form-line">
						  <input type="text" name="programendyear" id="programendyear" value="' . date('d M Y', strtotime($progendDate)) . '"  class="form-control" disabled>
						</div>
					  </div>
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program Duration (Years)*:</label>  
						<div class="form-line">
						  <input type="text" name="programduration" id="programduration" value="' . $progduration . '"  class="form-control" disabled>
						</div>
					  </div>
					</div> 
					<div class="row clearfix">
					  <div class="col-md-4">
						  <label for="projstartYear">Project Tentative Start Date *:</label>
						  <div class="form-line">
							<input type="text" name="projstartYears" id="startYear1" class="form-control"  value="' . date('d M Y', strtotime($projstart)) . '" disabled>
						  </div>
					  </div> 
					  <div class="col-md-4">
						  <label for="projendYear">Project Tentative End Date *:</label>
						  <div class="form-line"> 
							<input type="text" name="projendYears" id="projendyearDate" class="form-control"  value="' . date('d M Y', strtotime($projectendDate)) . '"  disabled>
						  </div>
					  </div> 
					  <div class="col-md-4">
						<label for="projduration">Project Duration (Days)*:</label>
						<div class="form-line">
						  <input type="text"  name="projduration" id="projduration1" class="form-control" value="' . $projduration . '" required disabled>
						</div>
					  </div>
					</div>
				  </div>
			  </div>
		  </div>
	  </div>';


	$query_Data = $db->prepare("SELECT * FROM  tbl_project_details  WHERE projid = '$itemId'");
	$query_Data->execute();
	$rows_OutpuData = $query_Data->rowCount();
	$row_Data =  $query_Data->fetch();
	$Ocounter = 0;
	$TargetB .= '
	  <div class="row clearfix">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			  <div class="card"> 
				  <div class="header" >
					  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
						<h5 class="list-group-item list-group-item list-group-item-action active"><strong> Output Budget and Duration Details</strong> </h5> 
					  </div>  
				  </div>
				  <div class="body">
					  <div class="table-responsive">
						  <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
							  <thead>
								  <tr>
									  <th width="5%">#</th>
									  <th width="25%">Output</th> 
									  <th width="10%">Start Year</th>
									  <th width="10%">Duration (Days) </th>
									  <th width="10%">Ceiling (Ksh) </th>
									  <th width="10%">Budget (Ksh) </th> 
									  <th width="10%">Ceiling</th>
									  <th width="10%">Target </th>
									  </tr>
							  </thead>
							  <tbody id="funding_table_body" >';
									$rowno = 0;
									do {
										$Ocounter++;
										$rowno++;
										$year =  $row_Data['year'];
										$projoutputID =  $row_Data['id'];
										$duration =  $row_Data['duration'];
										$outputid =  $row_Data['outputid'];
										$budget =  $row_Data['budget'];
										$indicatorId =  $row_Data['indicator'];
										$target =  $row_Data['total_target'];

										$query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$year'");
										$query_rsYear->execute();
										$row_rsYear = $query_rsYear->fetch();
										$projstartyear =  $row_rsYear['yr'];
										$projend = $projstartyear + 1;

										$query_dep = $db->prepare("SELECT tbl_indicator.indicator_name FROM tbl_indicator   WHERE indid ='$indicatorId' ");
										$query_dep->execute();
										$row_dep = $query_dep->fetch();
										$indname =  $row_dep['indicator_name'];

										$query_Indicator_ben = $db->prepare("SELECT * FROM tbl_indicator_beneficiaries WHERE indicatorid ='$indicatorId' ");
										$query_Indicator_ben->execute();
										$row_ben_ind = $query_Indicator_ben->fetch();
										$row_count = $query_Indicator_ben->rowCount();
										$ben_diss = '';
										if ($row_count > 0) {
											$ben_diss = $row_ben_ind['dissagragated'];
										} else {
											$ben_diss = 0;
										}

										$query_getprogbudget = $db->prepare("SELECT * FROM tbl_progdetails  WHERE indicator ='$indicatorId' AND year='$projstartyear' AND progid='$progid' ");
										$query_getprogbudget->execute();
										$row_rsprogbudget = $query_getprogbudget->fetch();
										$outputname = $row_rsprogbudget['output'];
										$progbudget = $row_rsprogbudget['budget'];

										$query_projcost = $db->prepare("SELECT SUM(budget) as projectbudget FROM  tbl_project_details WHERE  outputid ='$outputid' and year='$year' AND progid='$progid' LIMIT 1 ");
										$query_projcost->execute();
										$rowproj = $query_projcost->fetch();

										$totalUsed  =  $rowproj['projectbudget'];
										$projbudget = $progbudget - $totalUsed;
										$projceiling = $projbudget + $budget;

										$TargetB .= ' 
																		<tr id="row' . $rowno . '">
																			<td width="5%">' . $Ocounter  . '</td>
																			<td>' . $outputname  . '
																				<input type="hidden" name="projoutput[]" id="projoutputrow' . $rowno . '"  value="' . $outputid . '" >
																				<input type="hidden" name="projoutputid[]" id="projoutputidrow' . $rowno . '"  value="' . $projoutputID . '" >
																				<input type="hidden" name="ben_diss[]" value="' . $ben_diss . '" id="ben_diss' . trim($projoutputID) . '">
																				<input type="hidden" name="indicator[]" id="indicatorrow' . $rowno . '"  value="' . $indicatorId . '" >
																			</td> 
																			<td>' . $projstartyear . '/' . $projend  . '
																				<input type="hidden" name="opstaryear[]" id="opstaryearrow' . $rowno . '"  value="' . $year . '" > 
																			</td>
																			<td> 
																				<div class="form-input">
																				<input type="hidden" name="hopduration[]" id="hopduration' . $projoutputID . '"  value= ' . $duration . '  >
																				<input type="number" name="opduration[]" id="opduration' . $projoutputID . '"  value="' . $duration . '" 
																				placeholder="Enter"  onkeyup= change_duration("' . $projoutputID . '") onchange= change_duration("' . $projoutputID . '")
																					class="form-control" required /> 
																				</div> 
																			</td> 
																			<td> 
																			<div class="form-input">
																				<input type="hidden" name="outputbudgetceil[]" id="outputbudgetceil' . $projoutputID . '"  value= ' . $budget . '  >
																				<span id="projoutputceiling' . $projoutputID . '" class="projoutputdetailsC" style="color:coral">' . number_format(0, 2) . '</span>
																			</div>
																			</td> 
																			<td> 
																				<div class="form-input">
																				<input type="number" name="projcost[]" onkeyup= onKeyUpBudget("' . $projoutputID . '") onchange= onKeyUpBudget("' . $projoutputID . '") id="projcost' . $projoutputID .  '" value="' . $budget . '"  class="form-control sum_budget" required />
																				</div> 
																			</td>
																			<td> 
																			<div class="form-input">
																				<input type="hidden" name="projoutputtargetceilingValue[]" id="projoutputtargetceilingValue' . $projoutputID . '"  value= ' . $target . '  >
																				<span id="projoutputtargetceilingrow' . $projoutputID . '" class="" style="color:coral">' . number_format($target, 2) . '</span>
																			</div> 
																			</td> 
																			<td> 
																				<div class="form-input">
																				<input type="number" name="projtarget[]" onchange= target_change("' . $projoutputID . '") onkeyup= target_change("' . $projoutputID . '") id="optotaltarget' . $projoutputID .  '" value="' . $target . '"  class="form-control" required />
																				</div> 
																			</td>    
																		</tr>';
									} while ($row_Data =  $query_Data->fetch());
									$TargetB .= '
							  </tbody> 
						  </table> 
					  </div>
				  </div>
			  </div>
		  </div>
	  </div>';


	$query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$itemId' ");
	$query_OutputData->execute();
	$rows_OutpuData = $query_OutputData->rowCount();
	$row_OutputData =  $query_OutputData->fetch();
	$counter = 0;
	$location_Targets = '';

	$Targets = '
		<div class="row clearfix " id="rowcontainerrow' . $counter . '">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			  <div class="card">
				  <div class="header">  
					<div class="" style="margin-top:5px; margin-bottom:5px">
					  <h5 class="list-group-item list-group-item list-group-item-action active"><strong> Output Targets</strong></h5>
					</div>
				  </div>
				  <div class="body">';
						do {
							$counter++;
							// get indicator name 
							$indicator = $row_OutputData['indicator'];
							$t_target = $row_OutputData['total_target'];
							$projoutputID = $row_OutputData['id'];

							$query_rsIndicator = $db->prepare("SELECT indicator_name, indid, indicator_unit FROM tbl_indicator WHERE indid ='$indicator'");
							$query_rsIndicator->execute();
							$row_rsIndicator = $query_rsIndicator->fetch();
							$indname = $row_rsIndicator['indicator_name'];
							$unit_id = $row_rsIndicator['indicator_unit'];

							// get unit 
							$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE  id='$unit_id' ");
							$query_Indicator->execute();
							$row = $query_Indicator->fetch();
							$unit = $row['unit'];

							// Get outputstart year
							$year = $row_OutputData['year'];
							$query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
							$query_rsIndicatorYear->execute();
							$row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
							$projstartyear = $row_rsIndicatorYear['yr'];

							// get output name 
							$outputid = $row_OutputData['outputid'];
							$query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
							$query_rsOutput->execute();
							$row_rsOutput = $query_rsOutput->fetch();
							$count_rsOutput = $query_rsOutput->rowCount();
							$outputName = $count_rsOutput > 0 ?  $row_rsOutput['output'] : "N/A";

							$Targets .= '
										<div class="elementT" id="target_div_' . trim($projoutputID)  . '">
													<div class="header">  
														<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															<h5 style="color:#FF5722"><strong> Output ' . $counter . ': ' .  $outputName . '</strong></h5>
														</div>
														<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															<h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
														</div> 
													</div>
														<div class="row">
															<div class="col-md-12">
																<div class="spanYears">';
							//get financial years with specific outputid 
							$query_projYear = $db->prepare("SELECT * FROM  tbl_project_output_details WHERE projid = '$projid' and projoutputid = '$projoutputID' ORDER BY year");
							$query_projYear->execute();
							$rows_OutpuprojYear = $query_projYear->rowCount();
							$row_projYear =  $query_projYear->fetch();

							$TargetPlan = "";
							$containerTH = "";
							$containerTH2 = "";
							$containerTB = "";
							do {
								$Pyear =  $row_projYear['year'];
								$target =  $row_projYear['target'];
								$Fyear =  $Pyear + 1;

								// get program targets
								$query_getProgTarget = $db->prepare("SELECT * FROM tbl_progdetails WHERE indicator ='$indicator' AND year='$Pyear' AND progid='$progid' ");
								$query_getProgTarget->execute();
								$row_rsProgTarget = $query_getProgTarget->fetch();
								$totalRows_ProgTarget = $query_getProgTarget->rowCount();
								$progtarget  =  $row_rsProgTarget['target'];

								// get sum of all used program targets under specific indicator 
								$query_rsprojTarget = $db->prepare("SELECT SUM(target) as projtarget FROM  tbl_project_output_details  WHERE progid='$progid' AND indicator ='$indicator' and year='$Pyear'  LIMIT 1 ");
								$query_rsprojTarget->execute();
								$row_rsprojTarget = $query_rsprojTarget->fetch();
								$totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
								$totalUsedTarget  =  $row_rsprojTarget['projtarget'];


								// get sum of the given project indicator targets
								$query_proTargetSum = $db->prepare("SELECT SUM(target) as projtargets FROM  tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicator' and year='$Pyear' and projid='$projid'  LIMIT 1 ");
								$query_proTargetSum->execute();
								$rowprojSum = $query_proTargetSum->fetch();
								$targetSum = $rowprojSum['projtargets'];

								$projTarget = ($progtarget - $totalUsedTarget)  + $targetSum;
								$projTargetB = ($progtarget - $totalUsedTarget);

								$containerTH .= ' <th>
																		' . $Pyear . '/' . $Fyear . '
																		<input type="hidden" class="output_years' . $projoutputID  . '" name="output_years' . $projoutputID  . '[]" value="' . $Pyear . ' " >
																		<input type="hidden" name="dboutputId[]" value="' . $outputName . ' " >  
																		<input type="hidden" id="outputName' . $projoutputID . '" name="outputName[]" value="' . $projoutputID . ' " > 
																		<input type="hidden"   id="cyear_target' . $projoutputID .  $Pyear . '" name="cyear_target' . $projoutputID . '[]" value="' . $projTarget . ' " >
																		<span>Program Target Bal: </span>(<span style="color:red" id="year_target' . $projoutputID .  $Pyear . '" >' . number_format($projTargetB, 2) . '</span>) ' . $unit . '
																		</th>';

								$containerTB .= '<td> 
																		<input type="number" data-id=""  name="target_year' . $projoutputID . '[]" placeholder="target" value="' . $target . '"  id="target_year' . $projoutputID . $Pyear . '" class="form-control workplanTarget' . $projoutputID . '"
																			onkeyup=get_op_sum_target(' . $projoutputID . ',' . $Pyear . ') required >
																		</td>';
							} while ($row_projYear =  $query_projYear->fetch());


							$Targets .= '</div>
															</div>
														</div>
														<div class="table-responsive">
															<table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
																<thead>
																	<tr>
																		<th colspan="' . $rows_OutpuprojYear . '" >
																		<input type="hidden"   id="opid_name' . $projoutputID . '" name="opid_name' . $projoutputID . '[]" value="' . $outputName . ' " >
																		<input type="hidden"   id="coptarget_target' . $projoutputID . '" name="coptarget_target' . $projoutputID . '[]" value="' . $t_target . ' " >
																			<span>Output Target Bal: </span>
																			<span style="color:red" id="op_target' . $projoutputID . '" >
																				' . number_format(0, 2) . '
																			</span>
																		</th>
																	</tr>
																	<tr id="target_headrow' .  $counter  .  '">
																		' . $containerTH . '
																	</tr>
																</thead>
																<tbody>
																	<tr id="target_bodyheadrow' .  $counter  .  '">
																		' . $containerTH2 . '
																	</tr>
																	<tr id="target_bodyrow' .  $counter  .  '">
																		' . $containerTB . '
																	</tr>
																</tbody>
															</table>
														</div> 
														</div>';

							$query_rsOpDiss =  $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid = '$projoutputID' and projid='$projid'");
							$query_rsOpDiss->execute();
							$row_rsOpDiss = $query_rsOpDiss->fetch();
							$count_down  = $query_rsOpDiss->rowCount();

							if ($count_down > 0) {
								$location_Targets .= '
												<div class"element" id="div_' . $projoutputID . '">
														<div class="header">  
															<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
																<h5 style="color:#FF5722"><strong> Output ' . $counter . ': ' .  $outputName . '</strong></h5>
															</div>
															<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
																<h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
															</div> 
															<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
																<h5 style="color:#2196F3"><strong> Indicator: ' . $indname . '</strong></h5>
																<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($projoutputID) . '">
																<input type="hidden" value="' . $unit . '" id="unitNameL' . trim($projoutputID) . '">
															</div> 
														</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="spanYears">';
																		$containerTHL = '<tr>';
																		$containerTH23  = "<tr>";
																		$containerTBL = "<tr>";
																		do {
																			$state = $row_rsOpDiss['outputstate'];
																			$total_target = $row_rsOpDiss['total_target'];

																			$query_rsOpDiss_val =  $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation  WHERE projid = '$projid' and projoutputid = '$projoutputID' AND  opstate='$state' AND  type=3 ");
																			$query_rsOpDiss_val->execute();
																			$row_rsOpDiss_val = $query_rsOpDiss_val->fetch();
																			$locations = $query_rsOpDiss_val->rowCount();

																			// get the forest 
																			$query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$state'");
																			$query_ward->execute();
																			$row_ward = $query_ward->fetch();
																			$level3 = $row_ward['state'];

																			if ($locations > 0) {
																				$containerTHL .= '<th colspan="' . $locations . '">
																															<input type="hidden"   name="locate_output_name[]" id="locate_opid' . $projoutputID . '" value="' . $outputName . '"/>  
																															<input type="hidden"   name="level3label' . $state . $projoutputID . '[]" id="level3label' . $state . $projoutputID . '" value="' . $level3 . '"/>  
																															<input type="hidden"   name="unitName' . $state . $projoutputID . '[]" id="unitName' . $state . $projoutputID . '" value="' . $unit . '"/>  
																															<input type="hidden" data-id="' . $level3 . '"  name="outputstate' . $projoutputID . '[]" class="outputstate' . $projoutputID . '" value="' . $state . '" /> 
																															' . $level3label . ': ' . $level3 . '
																															<input type="hidden"  class="form-control" id="ceilinglocation_target' . $state . $projoutputID . '"  name="ceiloutputlocationtarget' . $projoutputID . '[]" value="' . $total_target . '" />
																															(<span id="state_ceil' . $state . $projoutputID . '" style="color:red" > ' . number_format($total_target, 2) . '</span>) ' . $unit . '</th>';
																				$p = 0;
																				do {
																					$name = $row_rsOpDiss_val['name'];
																					$value = $row_rsOpDiss_val['value'];
																					$p++;
																					$gen_number =  mt_rand(15, 500);
																					$number = $p . $gen_number;
																					$containerTH23 .= '<th>' . $name . '</th> ';
																					$containerTBL .= '
																																	<td>
																																		<input type="hidden"   name="outputlocation' . $state . $projoutputID . '[]" id="locate' . $number . '" value="' . $name . '"/>  
																																		<input type="number" value="' . $value . '" data-loc="' . $name . '"  data-id="' . $projoutputID . '" id="locate_numb' . $number . '" placeholder="' . $unit . '" class="form-control locate_total' . $state .  $projoutputID . '" onkeyup=get_sum("' . $state . '","' . $number . '") onchange=get_sum("' . $state . '","' . $number . '")  name="outputlocationtarget' . $state . $projoutputID . '[]" value="" required />  
																																	</td>';
																				} while ($row_rsOpDiss_val = $query_rsOpDiss_val->fetch());
																			} else {
																				$containerTHL .= '<th colspan="">
																														<input type="hidden"   name="locate_output_name[]" id="locate_opid' . $projoutputID . '" value="' . $outputName . '"/>  
																														<input type="hidden"   name="level3label' . $state . $projoutputID . '[]" id="level3label' . $state . $projoutputID . '" value="' . $level3 . '"/>  
																														<input type="hidden"   name="unitName' . $state . $projoutputID . '[]" id="unitName' . $state . $projoutputID . '" value="' . $unit . '"/>  
																														<input type="hidden" data-id="' . $level3 . '"  name="outputstate' . $projoutputID . '[]" class="outputstate' . $projoutputID . '" value="' . $state . '" /> 
																														' . $level3label . ': ' . $level3 . '
																														<input type="hidden"  class="form-control" id="ceilinglocation_target' . $state . $projoutputID . '"  name="ceiloutputlocationtarget' . $projoutputID . '[]" value="' . $total_target . '" />
																														(<span id="state_ceil' . $state . $projoutputID . '" style="color:red" > ' . number_format($total_target, 2) . '</span>) ' . $unit . '</th>';
																				$p = 0;
																				$gen_number =  mt_rand(15, 500);
																				$number = $p . $gen_number;
																				$containerTBL .= '
																														<td>
																															<input type="number" value="' . $total_target . '"  data-id="' . $projoutputID . '" id="locate_numb' . $number . '" placeholder="' . $unit . '" class="form-control locate_total' . $state .  $projoutputID . '" onkeyup=get_sum("' . $state . '","' . $number . '") onchange=get_sum("' . $state . '","' . $number . '")  name="outputlocationtarget' . $state . $projoutputID . '[]" value="" required />  
																														</td>';
																			}
																		} while ($row_rsOpDiss = $query_rsOpDiss->fetch());
																		$containerTHL .= '</tr>';
																		$containerTH23  .= "</tr>";
																		$containerTBL .= "</tr>";
																		$location_Targets .=
																'</div>
															</div>
														</div>
														<div class="row clearfix" >
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																		<thead>  
																				' . $containerTHL . ' 
																		</thead>
																		<tbody>
																			' . $containerTH23 . $containerTBL . '
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
												</div>';
							}
						} while ($row_OutputData =  $query_OutputData->fetch());
						$Targets .= '
					</div>
				</div>
			</div>
		</div>';

	// $query_rsOpDiss_val =  $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE projid = :projid");
	// $query_rsOpDiss_val->execute(array(":projid" => $projid));
	// $row_rsOpDiss_val = $query_rsOpDiss_val->fetch();
	// $count_down_val  = $query_rsOpDiss_val->rowCount();

	// if($count_down_val>0){
	// $Targets .= '
	// 	<div class="row clearfix " id="rowcontainerrow' . $counter . '">
	// 		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	// 			  <div class="card">
	// 				  <div class="header">
	// 					<div class="" style="margin-top:5px; margin-bottom:5px">
	// 					  <h5 class="list-group-item list-group-item list-group-item-action active"><strong> Output Target/s Disaggregation</strong></h5>
	// 					</div>
	// 				  </div>
	// 				  <div class="body">
	// 					' . $location_Targets . '
	// 				  </div>
	// 			  </div>
	// 		  </div>
	// 	</div>';
	// }

	echo $TargetB . $Targets;

	$query_rsprojcost =  $db->prepare("SELECT sum(amountfunding) as projbudget FROM  tbl_projfunding WHERE projid =:projid");
	$query_rsprojcost->execute(array(":projid" => $itemId));
	$row_rsprojcost = $query_rsprojcost->fetch();
	$totalRows_rsprojcost = $query_rsprojcost->rowCount();
	$projbudget1 = $row_rsprojcost['projbudget'];

	echo ' 
	  <div class="row clearfix" id="">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
			  <div class="header"> 
					<h5 class="list-group-item list-group-item list-group-item-action active"><strong> Add Financiers </strong></h5> 
					<label for="projindirectbeneficiary" id="projindirectbeneficiary" class="control-label">Total Output Cost *:</label>
					<div class="form-input">
						<input type="hidden" name="financierceiling" id="financierceiling" value="' . $projbudget1 . '">
						<input type="text" name="outputcost" id="outputcost" value="" placeholder="' . number_format($projbudget1, 2) . '" class="form-control" disabled>
					</div>  
			  </div>
			  <div class="body">  
				<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="approve_financier_table" style="width:100%">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="25%">Source Category</th>
									<th width="25%">Financier</th>
									<th width="10%">Category Ceiling (Ksh)</th>
									<th width="10%">Financier Ceiling (Ksh)</th> 
									<th width="15%">Amount (Ksh)</th> 
									<th width="5%">
									  <button type="button" name="addplus" id="addplus" onclick="add_row_approve_financier();" class="btn btn-success btn-sm">
										  <span class="glyphicon glyphicon-plus"></span>
									  </button>
									</th> 
								</tr>
							</thead>
							<tbody id="approve_financier_table_body">
							  <tr></tr>
							  <tr id="remove_approve_tr"><td colspan="7">Add Financier </td></tr>
							</tbody>
						</table>
					</div>
				</div> 
			  </div>
			</div>
		  </div>
	  </div>';

	$stage = 1;
	$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
	$query_rsFile->execute(array(":stage" => $stage, ":projid" => $itemId));
	$row_rsFile = $query_rsFile->fetch();
	$totalRows_rsFile = $query_rsFile->rowCount();


	echo ' 
	  <div class="row clearfix " id="">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
			  <div class="header">
				  <h5 class="list-group-item list-group-item list-group-item-action active">
				  <strong> Add new file/s </strong></h5> 
			  </div>
			  <div class="body"> 
				<div class="body table-responsive">
				  <table class="table table-bordered" style="width:100%">
					<thead>
					  <tr>
						<th style="width:2%">#</th>
						<th style="width:30%">Attachment</th>
						<th style="width:66%">Purpose</th>
						<th style="width:2%">
						  <button type="button" name="addplus1" onclick="add_row_files();" title="Add another document" class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-plus">
							</span>
						  </button>
						</th>
					  </tr>
					</thead>
					<tbody id="meetings_table">
					  <tr></tr> 
					  <tr id="add_new_file">
						<td colspan="4"> Add file </td>
					  </tr>  
					</tbody>
				  </table>
				</div>
			  </div>
			</div>
		  </div>
	  </div>';
}


if (isset($_POST["approveBudget"]) && $_POST["approveBudget"] == "approveBudget") {
	$itemId = $_POST['itemId'];
	$TargetB = '';

	$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$itemId'");
	$query_rsProjects->execute();
	$row_rsProjects = $query_rsProjects->fetch();
	$totalRows_rsProjects = $query_rsProjects->rowCount();

	$progid = $row_rsProjects['progid'];
	$projname = $row_rsProjects['projname'];
	$projdurationInDays = $row_rsProjects['projduration'];
	$projfscyear = $row_rsProjects['projfscyear'];
	$projcode = $row_rsProjects['projcode'];
	$projbudget = $row_rsProjects['projbudget'];
	$projenddate = $row_rsProjects['projenddate'];
	$projstartdate = $row_rsProjects['projstartdate'];
	$projduration = $row_rsProjects['projduration'];
	$projid = $row_rsProjects['projid'];

	$query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$projfscyear'");
	$query_rsYear->execute();
	$row_rsYear = $query_rsYear->fetch();
	$projstartyear =  $row_rsYear['yr'];
	$projstart = $projstartyear  . '-07-01';

	//fetch program details 
	$query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
	$query_item->execute();
	$row_item = $query_item->fetch();

	$progstartDate = $row_item['syear'] . '-07-01'; //program start date  
	$progduration = $row_item['years']; //program duration in years 
	$sdate = $row_item['syear'] . '-06-30'; //for calculating program end year   
	$progendDate = date('Y-m-d', strtotime($sdate . " + {$progduration} years"));  //program end date

	$projectendDate = date('Y-m-d', strtotime($projstart . " + {$projdurationInDays} days"));
	$yr = date("Y");
	$mnth = date("m");
	//$mnth = $mnth + 8;

	if ($mnth >= 7 && $mnth <= 12) {
		$year = $yr;
	} elseif ($mnth >= 1 && $mnth <= 6) {
		$year = $yr - 1;
	}

	$yearnxt = $year + 1;
	$finyear = $year . "/" . $yearnxt;


	$TargetB .= '   
	  <div class="row clearfix">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			  <div class="card"> 
				  <div class="header" >
					  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
						<h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5> 
					  </div>  
				  </div>
				  <div class="body"> 
					<div class="row clearfix">
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program Start Date *:</label>  
						<div class="form-line">
						  <input type="hidden"  name="progid" id="progid" class="form-control" value="' . $progid . '" required>
						  <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $itemId . '" required>
						  <input type="hidden" name="projfscyear[]" id="projfscyear" value="' . $projfscyear . '" />
						  <input type="text" name="progstartyear" id="progstartyear" value="' . date('d M Y', strtotime($progstartDate)) . '"  class="form-control" disabled>
						</div>
					  </div>  
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program End Date *:</label>  
						<div class="form-line">
						  <input type="text" name="programendyear" id="programendyear" value="' . date('d M Y', strtotime($progendDate)) . '"  class="form-control" disabled>
						</div>
					  </div>
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label for="syear">Program Duration (Years)*:</label>  
						<div class="form-line">
						  <input type="text" name="programduration" id="programduration" value="' . $progduration . '"  class="form-control" disabled>
						</div>
					  </div>
					</div> 
					<div class="row clearfix"> 
					  <div class="col-md-6">
						<label for="projduration">Project Duration (Days)*:</label>
						<div class="form-line">
						  <input type="text"  name="projduration" id="projduration1" class="form-control" value="' . $projduration . '" required disabled>
						</div>
					  </div>
					  <div class="col-md-6">
						<label for="projduration">Final Approved Budget for the Financial Year ' . $finyear . '  33333333*:</label>
						<div class="form-line">
						  <input type="text"  name="projapprovedbudget" value="" id="projapprovedbudget" placeholder="Enter the project approved budget for the financial year ' . $finyear . '" class="form-control" class="form-control" required />					  
						  <input type="hidden"  name="budgetyear" id="budgetyear" value="' . $year . '"> 
						</div>
					  </div>
					</div>  
				  </div>
			  </div>
		  </div>
	  </div>';
	echo $TargetB;
}
