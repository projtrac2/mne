<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
	try {
		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
			$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}

		if (isset($_GET['projid'])) {
			$projid = $_GET['projid'];
		}
		$query_rsMyP =  $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE tbl_projects.deleted='0' AND user_name = '$user_name' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$projcategory = $row_rsMyP["projcategory"];

		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
		$query_rsProjects->execute();
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
		$projname = $row_rsProjects['projname'];
		$projcode = $row_rsProjects['projcode'];
		$projcost = $row_rsProjects['projcost'];
		$progid = $row_rsProjects['progid'];
		$projstartdate = $row_rsProjects['projstartdate'];
		$projenddate = $row_rsProjects['projenddate'];

		$query_rsOutputs = $db->prepare("SELECT p.output as output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
		$query_rsOutputs->execute();
		$row_rsOutputs = $query_rsOutputs->fetch();
		$totalRows_rsOutputs = $query_rsOutputs->rowCount();

		// query the 
		$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid ='$projid' ORDER BY amountfunding desc");
		$query_rsProjFinancier->execute();
		$row_rsProjFinancier = $query_rsProjFinancier->fetch();
		$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

		$currentStatus =  $row_rsMyP['projstatus'];

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
		$query_rsMlsProg->execute(array(":projid" => $projid));
		$row_rsMlsProg = $query_rsMlsProg->fetch();

		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

		$percent2 = round($prjprogress, 2);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="header" style="padding-bottom:0px">
						<div class="button-demo" style="margin-top:-15px">
							<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
							<a href="myprojectdash.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
							<a href="myprojectmilestones.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
							<a href="myprojectworkplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Quarterly Targets </a>
							<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
							<a href="myproject-key-stakeholders.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
							<a href="projectissueslist.php?proj=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
							<a href="myprojectfiles.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
							<a href="projreports.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h4>
							<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
							</div>
							<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
									<div class="bar hundred cornflowerblue">
										<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> INDICATIVE FINANCIAL DETAILS &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> ACTUAL FINANCIAL DETAILS &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<?php
									$query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projplanstatus='1' and projid='$projid' ");
									$query_rsProjects->execute();
									$row_rsProjects = $query_rsProjects->fetch();
									$totalRows_rsProjects = $query_rsProjects->rowCount();
									$projname = $row_rsProjects['projname'];
									$projcode = $row_rsProjects['projcode'];
									$projcost = $row_rsProjects['projcost'];
									$progid = $row_rsProjects['progid'];
									$projstartdate = $row_rsProjects['projstartdate'];
									$projenddate = $row_rsProjects['projenddate'];

									$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o   INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
									$query_rsOutputs->execute();
									$row_rsOutputs = $query_rsOutputs->fetch();
									$totalRows_rsOutputs = $query_rsOutputs->rowCount();

									// query the 
									$query_rsProjFinancier =  $db->prepare("SELECT *, f.financier FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier WHERE projid ='$projid' ORDER BY amountfunding desc");
									$query_rsProjFinancier->execute();
									$row_rsProjFinancier = $query_rsProjFinancier->fetch();
									$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

									?>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
												<div class="header" align="center">
													<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="100%" height="40" style="padding-left:5px; background-color:#000; color:#FFF; font-size:16px">
																	<div align="left"><strong><i class="fa fa-pencil-square" aria-hidden="true"></i> Details</strong></div>
																</td>
															</tr>
														</table>
													</div>
												</div>
												<div class="body">
													<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
														<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
															<label class="control-label">Project Name:</label>
															<div class="form-line">
																<input type="text" class="form-control" value="<?= $projname ?>" readonly>
															</div>
														</div>
														<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															<label class="control-label">Project Code:</label>
															<div class="form-line">
																<input type="text" class="form-control" value="<?= $projcode ?>" readonly>
															</div>
														</div>
														<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															<label class="control-label">Project Approved Budget:</label>
															<div class="form-line">
																<input type="text" class="form-control" value="Ksh. <?= $projcost ?>" readonly>
															</div>
														</div>
													</div>

													<fieldset class="scheduler-border" style="border-radius:3px">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
															<i class="fa fa-university" aria-hidden="true"></i> Funding Details
														</legend>
														<div class="body">
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																	<thead>
																		<tr>
																			<th width="4%">#</th>
																			<th width="80%">Financier</th>
																			<th width="16%">Amount (Ksh)</th>
																		</tr>
																	</thead>
																	<tbody id="">
																		<?php
																		$projectPlan = '';
																		$rowno = 0;
																		$totalAmount = 0;
																		if ($totalRows_rsProjFinancier > 0) {
																			do {
																				$rowno++;
																				// $progfundid =  $row_rsProjFinancier['progfundid'];
																				$financier = $row_rsProjFinancier['financier'];
																				$projamountfunding =  $row_rsProjFinancier['amountfunding'];
																				$totalAmount = $projamountfunding + $totalAmount;

																				$inputs = '<span>' . $financier . '</span>';

																				$projectPlan .= ' 
																				<tr id="row<?= $rowno ?>">
																					<td>
																						' . $rowno . '
																					</td>
																					<td>
																						' .  $inputs . '
																					</td>
																					<td>
																						' .  number_format($projamountfunding, 2) . '
																					</td>
																				</tr>';
																			} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																		}

																		$projectPlan .= ' 
																							<tfoot>
																								<tr>
																									<td colspan="2"><strong>Total Amount</strong></td>
																									<td><strong>' . number_format($totalAmount, 2) . '</strong></td>
																								</tr>
																							</tfoot>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</fieldset>
																		
																		<fieldset class="scheduler-border" style="border-radius:3px">
																			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																				<i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> Financial Plan
																			</legend>
																			';
																		$Ocounter = 0;
																		$summary = '';
																		$output_cost_val = [];
																		if ($totalRows_rsOutputs > 0) {
																			do {
																				$Ocounter++;
																				//get indicator
																				$outputName = $row_rsOutputs['output'];
																				$outputCost = $row_rsOutputs['budget'];
																				$outputid = $row_rsOutputs['opid'];
																				$output_cost_val[] = $outputid;
																				$output_remeinder = 0;

																				$projectPlan .= '
																			<div class="row clearfix">
																				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																					<div class="card"> 
																						<div class="panel panel-info">
																							<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output' . $outputid . '">
																								<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
																								<strong> Output' . $Ocounter . ': 
																									<span class="">
																										' . $outputName . '
																									</span>
																								</strong>
																							</div>
																							<div class="body collapse output' . $outputid . '">
																								<h4>' . $Ocounter . '.1) Direct Project Cost</h4>';

																				$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid and outputid =:outputid ORDER BY sdate");
																				$query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
																				$row_rsMilestones = $query_rsMilestones->fetch();
																				$totalRows_rsMilestones = $query_rsMilestones->rowCount();
																				$mcounter = 0;
																				$sum = 0;
																				if ($totalRows_rsMilestones > 0) {
																					$projectPlan .= '  
																									<div class="table-responsive">
																										<table class="table table-bordered" id="funding_table">
																											<thead>
																												<tr>
																													<th style="width:2%"></th>
																													<th style="width:2%"># </th> 
																													<th style="width:10%">Description </th>
																													<th style="width:10%">Unit</th>
																													<th style="width:10%">Unit Cost (Ksh)</th>
																													<th style="width:10%">No. of Units</th>
																													<th style="width:10%">Total Cost (Ksh)</th>
																												</tr>
																											</thead>
																											<tbody>';
																					do {
																						$mcounter++;
																						$milestone = $row_rsMilestones['msid'];
																						$milestoneName = $row_rsMilestones['milestone'];
																						$query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate ");
																						$query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
																						$row_rsTasks = $query_rsTasks->fetch();
																						$totalRows_rsTasks = $query_rsTasks->rowCount();

																						if ($totalRows_rsTasks > 0) {
																							$projectPlan .= '  
																														<tr>  
																															<td>' . $Ocounter . "." . 1 . "." . $mcounter   . '</td>
																															<td colspan="7"><strong> Milestone: ' . $milestoneName . '</strong> </td>
																														</tr>';
																							$tcounter = 0;
																							do {
																								$tcounter++;
																								$task =  $row_rsTasks['task'];
																								$tkid =  $row_rsTasks['tkid'];
																								$edate =  date_create($row_rsTasks['edate']);
																								$sdate =  date_create($row_rsTasks['sdate']);
																								$taskid = $outputid . $tkid; // to distinguish between different outputs
																								$cost_type = 1;
																								$projectPlan .= '
																															<tr>  
																																<td>' .  $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  . '</td>
																																<td colspan="3">Task:<strong> ' .  $task . '</strong> </td>
																																<td colspan="2">Start Date:<strong> ' . date_format($sdate, 'd M Y') . ' </strong></td>
																																<td colspan="2">End Date:<strong> ' .  date_format($edate, 'd M Y') . ' </strong></td>
																															</tr>';

																								$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid");
																								$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																								$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																								$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();

																								if ($totalRows_rsDirect_cost_plan > 0) {

																									do {
																										$unit = $row_rsDirect_cost_plan['unit'];
																										$comments = $row_rsDirect_cost_plan['comments'];
																										$description = $row_rsDirect_cost_plan['description'];
																										$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																										$units_no = $row_rsDirect_cost_plan['units_no'];
																										$rmkid = $row_rsDirect_cost_plan['id'];
																										$total_cost = $unit_cost * $units_no;

																										$sum = $sum + $total_cost;
																										$output_remeinder = $output_remeinder + $total_cost;

																										$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																										$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																										$row_rs_cost_funders = $query_rs_cost_funders->fetch();
																										$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
																										$fund_id = [];
																										do {
																											$fund_id[] = $row_rs_cost_funders['id'];
																										} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
																										$fnid = implode(",", $fund_id);
																										$projectPlan .= '  
																																	<tr>
																																		<td align="center" class="mb-0" >
																																			<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".task' . $tkid . $rmkid . '">
																																				<i class="fa fa-plus-square" style="font-size:16px"></i>
																																			</button> 
																																		</td>
																																		<td>
																																			' . $tcounter . '
																																		</td>  
																																		<td>
																																		' .  $description . '
																																		</td>
																																		<td>
																																			' .  $unit . '
																																		</td>
																																		<td>
																																			' .  number_format($unit_cost, 2) . '
																																		</td>
																																		<td>
																																			' .  number_format($units_no, 2) . '
																																		</td>
																																		<td>
																																			' .  number_format($total_cost, 2) . '
																																		</td>  
																																	</tr>   
																																	<tr class="collapse task' . $tkid . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																																		<th style="width: 2%">#</th>
																																		<th  style="width:78%"  colspan="5">Financiers</th>
																																		<th  style="width: 20%"  colspan="2">Amount</th>
																																	</tr>';
																										$rowno = 0;
																										$totalAmount = 0;
																										// query the
																										$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
																										$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
																										$row_rsProjFinancier = $query_rsProjFinancier->fetch();
																										$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																										if ($totalRows_rsProjFinancier > 0) {
																											do {
																												$rowno++;
																												$progfundid = $row_rsProjFinancier['funder'];
																												$projamountfunding = $row_rsProjFinancier['amount'];
																												$totalAmount = $projamountfunding + $totalAmount;

																												//$inputs = '';
																												$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$progfundid'");
																												$query_rsFunder->execute();
																												$row_rsFunder = $query_rsFunder->fetch();
																												$row_count = $query_rsFunder->rowCount();

																												$inputs = $row_count > 0 ?  '<span>' .  $row_rsFunder['financier'] . '</span>' : "";
																												// if ($row_rsFunding['id'] == $progfundid) {
																												// 	$inputs = '<span>' . $funder . '</span>';
																												// }

																												$projectPlan .= '
																																			<tr class="collapse task' . $tkid . $rmkid . '"">
																																				<td>
																																					' . $rowno . '
																																				</td>
																																				<td colspan="5">
																																					' . $inputs . '
																																				</td>
																																				<td style="text-align:left" colspan="2">
																																					' . number_format($projamountfunding, 2) . '
																																				</td>
																																			</tr>';
																											} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																											$projectPlan .= '   
																																		<tr class="collapse task' .  $tkid . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																																			<th style="width: 2%">#</th>
																																			<th  style="width: 96%"  colspan="7">Remarks</th>
																																		</tr>
																																		<tr class="collapse task' . $tkid . $rmkid . '"  style="">  
																																			<td>=></td>
																																			<td colspan="7"> ' . $comments . ' </td>
																																		</tr>';
																										}
																									} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
																								}
																							} while ($row_rsTasks = $query_rsTasks->fetch());
																						}
																					} while ($row_rsMilestones = $query_rsMilestones->fetch());
																					$per  = ($sum / $outputCost) * 100;
																					$percent = number_format($per, 2);
																					$projectPlan .= ' 
																											</tbody>
																											<tfoot>
																												<tr>
																													<td colspan="2"><strong>Sub Total</strong></td>
																													<td colspan="1">
																														<strong>' . number_format($sum, 2) . '</strong>
																													</td>
																													<td colspan="1"> <strong>% Sub Total</strong></td>
																													<td colspan="1">
																														<strong>' . $percent . ' %</strong>
																													</td>
																													<td colspan="1"> <strong>Output Budget Bal</strong></td>
																													<td colspan="2">
																														<strong> ' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
																													</td>
																												</tr>
																											</tfoot> 
																										</table>
																									</div> ';
																				}

																				$cost_type = 2;
																				$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid ");
																				$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid));
																				$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																				$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
																				$prowno = "";
																				$pcounter = 1;
																				$personnel_sum = 0;
																				if ($totalRows_rsDirect_cost_plan > 0) {
																					$projectPlan .= ' 
																									<h4>' . $Ocounter . '.2) Personnel</h4>
																									<div class="table-responsive">
																										<table class="table table-bordered">
																											<thead>
																												<tr>
																													<th style="width: 4%"></th>
																													<th style="width: 2%">#</th>
																													<th style="width: 20%">Personnel </th>
																													<th style="width: 10%">Unit</th>
																													<th style="width: 20%">Unit Cost</th>
																													<th style="width: 20%">No. of Units</th>
																													<th style="width: 20%">Total Cost</th> 
																												</tr>
																											</thead>
																											<tbody>';
																					do {
																						$pcounter++;
																						$unit = $row_rsDirect_cost_plan['unit'];
																						$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																						$units_no = $row_rsDirect_cost_plan['units_no'];
																						$rmkid = $row_rsDirect_cost_plan['id'];
																						$personnel = $row_rsDirect_cost_plan['personnel'];
																						$total_cost = $unit_cost * $units_no;
																						$personnel_sum = $personnel_sum + $total_cost;
																						$output_remeinder = $output_remeinder + $total_cost;
																						$prowno = $pcounter . $outputid;

																						$query_rs_timeline_id =  $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																						$query_rs_timeline_id->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																						$row_rs_timeline_id = $query_rs_timeline_id->fetch();
																						$totalRows_rs_timeline_id = $query_rs_timeline_id->rowCount();
																						$timeline_id = $row_rs_timeline_id['id'];

																						$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																						$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																						$row_rs_cost_funders = $query_rs_cost_funders->fetch();
																						$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
																						$fund_id = [];
																						do {
																							$fund_id[] = $row_rs_cost_funders['id'];
																						} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
																						$fnid = implode(",", $fund_id);


																						$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:personnel ");
																						$query_rsPersonel->execute(array(":personnel" => $personnel));
																						$row_rsPersonel = $query_rsPersonel->fetch();
																						$totalRows_rsPersonel = $query_rsPersonel->rowCount();
																						$ptnname = $totalRows_rsPersonel > 0 ? $row_rsPersonel['fullname']: "";

																						$query_rsTimeline = $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE plan_id=:plan_id ");
																						$query_rsTimeline->execute(array(":plan_id" => $rmkid));
																						$row_rsTimeline = $query_rsTimeline->fetch();
																						$totalRows_rsTimeline = $query_rsTimeline->rowCount();
																						$disbursement_date = date_create($row_rsTimeline['disbursement_date']);

																						$projectPlan .= '
																													<tr id="row">
																														<td>
																															<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".personnel' . $rmkid . '">
																															<i class="fa fa-plus-square" style="font-size:16px"></i>
																															</button> 
																														</td>
																														<td>
																															' . $Ocounter . ".2." . $pcounter . '
																														</td>
																														<td>
																															' . $ptnname . '
																														</td>
																														<td>
																															' . $unit . '
																														</td>
																														<td>
																															' . number_format($unit_cost, 2) . '
																														</td>
																														<td>
																															' . number_format($units_no, 2) . '
																														</td>
																														<td>
																															' . number_format($total_cost, 2) . '
																														</td>
																													</tr> 
																													<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																														<th style="width: 2%">#</th>
																														<th  style="width: 96%"  colspan="8">Disbursement Date</th>
																													</tr>
																													<tr class="collapse personnel' . $rmkid . '"  style="">  
																														<td>=></td>
																														<td colspan="8"> ' . date_format($disbursement_date, 'd M Y') . ' </td>
																													</tr> 
																													<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																														<th style="width: 2%">#</th>
																														<th  style="width:78%"  colspan="5">Financiers</th>
																														<th  style="width: 20%"  colspan="3">Amount</th>
																													</tr>';
																						$rowno = 0;
																						$totalAmount = 0;
																						// query the
																						$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
																						$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
																						$row_rsProjFinancier = $query_rsProjFinancier->fetch();
																						$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																						if ($totalRows_rsProjFinancier > 0) {
																							do {
																								$rowno++;
																								$progfundid = $row_rsProjFinancier['funder'];
																								$projamountfunding = $row_rsProjFinancier['amount'];
																								$totalAmount = $projamountfunding + $totalAmount;


																								$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$progfundid'");
																								$query_rsFunder->execute();
																								$row_rsFunder = $query_rsFunder->fetch();
																								$totalRows_rsFunder = $query_rsFunder->rowCount();
																								$inputs = $row_count > 0 ?  '<span>' .  $row_rsFunder['financier'] . '</span>' : "";

																								// $funder = $row_rsFunder['financier'];
																								// if ($row_rsFunding['id'] == $progfundid) {
																								// 	$inputs .= '<span>' . $funder . '</span>';
																								// }

																								$projectPlan .= '
																															<tr class="collapse personnel' . $rmkid . '"">
																																<td>
																																	' . $rowno . '
																																</td>
																																<td colspan="5">
																																	' . $inputs . '
																																</td>
																																<td style="text-align:left" colspan="3">
																																	' . number_format($projamountfunding, 2) . '
																																</td>
																															</tr>';
																							} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());

																							$projectPlan .= ' 
																														<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																															<th style="width: 2%">#</th>
																															<th  style="width: 96%"  colspan="8">Remarks</th>
																														</tr>
																														<tr class="collapse personnel' . $rmkid . '"  style="">
																															<td>=></td>
																															<td colspan="8"> ' . $comments . ' </td>
																														</tr>';
																						}
																					} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());

																					$personnel_per  = ($personnel_sum / $outputCost) * 100;
																					$personnel_percent = number_format($personnel_per, 2);
																					$projectPlan .= ' 
																											</tbody>
																											<tfoot id="pfoot<?= $outputid ?>">
																												<tr>
																													<td colspan="1"><strong>Sub Total</strong></td>
																													<td colspan="2">
																														<strong>' . number_format($personnel_sum, 2) . '</strong>
																													</td>
																													<td colspan="1"> <strong>% Sub Total</strong></td>
																													<td colspan="1">
																														<strong>' . $personnel_percent . ' %</strong>
																													</td>
																													<td colspan="1"> <strong>Output Budget Bal</strong></td>
																													<td colspan="2">
																														<strong>' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
																													</td>
																												</tr>
																											</tfoot>
																										</table>
																									</div>';
																				}
																				// query budget lines 
																				$query_rsBilling = $db->prepare("SELECT * FROM tbl_budget_lines");
																				$query_rsBilling->execute();
																				$row_rsBilling = $query_rsBilling->fetch();
																				$totalRows_rsBilling = $query_rsBilling->rowCount();
																				$counter = 2;
																				do {
																					$counter++;
																					$budget_line = $row_rsBilling['name'];
																					$budget_line_id = $row_rsBilling['id'];
																					$budget_lineid = $budget_line_id . $outputid;
																					$cost_type = 3;
																					$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
																					$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
																					$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
																					$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

																					$rowno = 1;
																					$other_sum = 0;

																					if ($totalRows_rsOther_cost_plan > 0) {
																						$projectPlan .= ' 
																										<h4>' . $Ocounter . '.' . $counter . ") " . $budget_line . '</h4> 
																										<div class="table-responsive">
																											<table class="table table-bordered">
																												<thead>
																													<tr>
																														<th width="3%"> # </th>
																														<th width="27%">Description </th>
																														<th width="10%">Unit</th>
																														<th width="15%">Unit Cost</th>
																														<th width="10%">No. of Units</th>
																														<th width="20%">Total Cost</th>
																														<th width="15%">Other Details</th>
																													</tr>
																												</thead>
																												<tbody">';
																						do {
																							$pcounter++;
																							$rowno++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$total_cost = $unit_cost * $units_no;
																							$other_sum = $other_sum + $total_cost;
																							$output_remeinder = $output_remeinder + $total_cost;


																							$query_rs_timeline_id =  $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																							$query_rs_timeline_id->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																							$row_rs_timeline_id = $query_rs_timeline_id->fetch();
																							$totalRows_rs_timeline_id = $query_rs_timeline_id->rowCount();
																							$disbursement_date = date_create($row_rs_timeline_id['disbursement_date']);
																							$personnel = $row_rs_timeline_id['responsible'];

																							$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:personnel ");
																							$query_rsPersonel->execute(array(":personnel" => $personnel));
																							$row_rsPersonel = $query_rsPersonel->fetch();
																							$totalRows_rsPersonel = $query_rsPersonel->rowCount();
																							$ptnname = $totalRows_rsPersonel > 0 ? $row_rsPersonel['fullname']: 0;

																							$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																							$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																							$row_rs_cost_funders = $query_rs_cost_funders->fetch();
																							$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
																							$fund_id = [];

																							do {
																								$fund_id[] = $row_rs_cost_funders['id'];
																							} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
																							$fnid = implode(",", $fund_id);
																							$budget_line_rowno = $rowno + $budget_lineid;

																							$projectPlan .= '
																														<tr>
																															<td>
																															<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".budget_line' . $rmkid . '">
																																<i class="fa fa-plus-square" style="font-size:16px"></i>
																															</button> 
																															</td>
																																<td>
																																' . $Ocounter . ". " . $counter . '
																																</td>
																																<td>
																																	' . $description . " rmkd" . $rmkid . ' 
																																</td>
																																<td>
																																	' . $unit . '
																																</td>
																																<td>
																																	' . number_format($unit_cost) . '
																																</td>
																																<td>
																																	' . number_format($units_no, 2) . '
																																</td>
																																<td>
																																	' . number_format($total_cost) . '
																																</td>
																														</tr>
																														<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																															<th style="width: 2%">#</th>
																															<th  style="width: 43%"  colspan="5">Disbursement Date</th>
																															<th  style="width: 43%"  colspan="3">Responsible</th>
																														</tr>
																														<tr class="collapse budget_line' . $rmkid . '"  style="">  
																															<td>=></td>
																															<td colspan="5"> ' . date_format($disbursement_date, 'd M Y') . ' </td>
																															<td colspan="3"> ' . $ptnname . ' </td>
																														</tr>
																														<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																															<th style="width: 2%">#</th>
																															<th  style="width:78%"  colspan="5">Financiers</th>
																															<th  style="width: 20%"  colspan="3">Amount</th>
																														</tr>';
																							$rowno = 0;
																							$totalAmount = 0;
																							// query the
																							$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
																							$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
																							$row_rsProjFinancier = $query_rsProjFinancier->fetch();
																							$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																							if ($totalRows_rsProjFinancier > 0) {
																								do {
																									$rowno++;
																									$progfundid = $row_rsProjFinancier['funder'];
																									$projamountfunding = $row_rsProjFinancier['amount'];
																									$totalAmount = $projamountfunding + $totalAmount;
																									$query_rsFunding = $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
																									$query_rsFunding->execute();
																									$row_rsFunding = $query_rsFunding->fetch();
																									$totalRows_rsFunding = $query_rsFunding->rowCount();

																									$inputs = '';
																									do {
																										$source = $row_rsFunding['sourceid'];
																										$progfundids = $row_rsFunding['id'];

																										if ($row_rsFunding['sourcecategory'] == "donor") {
																											$query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid='$source'");
																											$query_rsDonor->execute();
																											$row_rsDonor = $query_rsDonor->fetch();
																											$totalRows_rsDonor = $query_rsDonor->rowCount();
																											$donor = $row_rsDonor['donorname'];

																											if ($row_rsFunding['id'] == $progfundid) {
																												$inputs .= '<span>' . $donor . '</span>';
																											}
																										} else if ($row_rsFunding['sourcecategory'] == "others") {
																											$query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id='$source'");
																											$query_rsFunder->execute();
																											$row_rsFunder = $query_rsFunder->fetch();
																											$totalRows_rsFunder = $query_rsFunder->rowCount();
																											$funder = $row_rsFunder['name'];
																											if ($row_rsFunding['id'] == $progfundid) {
																												$inputs .= '<span>' . $funder . '</span>';
																											}
																										}
																									} while ($row_rsFunding = $query_rsFunding->fetch());
																									$projectPlan .= '
																																<tr class="collapse budget_line' . $rmkid . '">
																																	<td> 
																																		' . $rowno . '
																																	</td>
																																	<td colspan="5"> 
																																		' . $inputs . '
																																	</td>
																																	<td style="text-align:left" colspan="3">
																																		' . number_format($projamountfunding, 2) . '
																																	</td>
																																</tr>';
																								} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																								$projectPlan .= ' 
																															<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																																<th style="width: 2%">#</th>
																																<th  style="width: 96%"  colspan="8">Remarks</th>
																															</tr>
																															<tr class="collapse budget_line' . $rmkid . '"  style="">
																																<td>=></td>
																																<td colspan="8"> ' . $comments . ' </td>
																															</tr>';
																							}
																						} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																						$other_per  = ($other_sum / $outputCost) * 100;
																						$other_percent = number_format($other_per, 2);
																						$projectPlan .= '
																												</tbody">
																												<tfoot>
																													<tr>
																														<td colspan="1"><strong>Sub Total</strong></td>
																														<td colspan="2">
																															<strong>' . number_format($other_sum, 2) . '</strong>
																														</td>
																														<td colspan="1"> <strong>% Sub Total</strong></td>
																														<td colspan="1">
																															<strong>' . $other_percent . ' %</strong>
																														</td> 
																														<td colspan="1"> <strong>Output Budget Bal</strong></td>
																														<td colspan="2">
																															<strong>' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
																														</td>
																													</tr>
																												</tfoot>
																											</table>
																										</div>';
																					}
																				} while ($row_rsBilling = $query_rsBilling->fetch());
																				$projectPlan .= '   
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<script>
																				$(".collapsed").click(function(e) {
																					e.preventDefault(); 
																					$(this)
																					.find("i")
																					.toggleClass("fa-plus-square fa-minus-square");
																				});
																				$(".careted").click(function(e) {
																					e.preventDefault(); 
																					$(this)
																					.find("i")
																					.toggleClass("fa fa-caret-down fa fa-caret-up");
																				});
																			</script>';

																				$summary  .= '<tr>
																				<td>' . $Ocounter . '</td>
																				<td>' . $outputName . '</td>
																				<td style="text-align:left">' . number_format($outputCost, 2) . '</td>
																				<td id="summaryOutput' . $outputid . '"  style="text-align:left">' . number_format($output_remeinder, 2) . '</td>
																				<td id="perc' . $outputid .  '"  style="text-align:left">' . number_format((($output_remeinder / $outputCost) * 100), 2) . ' %</td>
																			</tr>';
																			} while ($row_rsOutputs = $query_rsOutputs->fetch());
																		}
																		$projectPlan .= ' 
																				</fieldset>
																				<fieldset class="scheduler-border" style="border-radius:3px">
																					<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																						<i class="fa fa-bar-chart" aria-hidden="true"></i> <strong>Financial Plan Summary</strong>
																					</legend>
																					<div class="body">
																						<div class="table-responsive">
																							<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																								<thead>
																									<tr>
																										<th width="2%">#</th>
																										<th width="58%">Output</th>
																										<th width="15%">Output Budget</th>
																										<th width="15%">Amount Planned(Ksh)</th>
																										<th width="10%">% Planned</th>
																									</tr>
																								</thead>
																								<tbody id="">
																									' . $summary . '
																									<tfoot>
																										<tr>
																											<td colspan="2">
																												<strong>
																													Total Amount 
																												</strong>
																											</td>
																											<td style="text-align:left">
																												<strong>
																													' . number_format($projcost, 2) . '
																												</strong>
																											</td>
																											<td style="text-align:left">
																												<strong id="summary_total" >
																												' . number_format($projcost, 2) . '
																												</strong>
																											</td>
																											<td style="text-align:left">
																												<strong id="summary_percentage"  >
																												' . number_format(100, 2) . ' %
																												</strong>
																											</td>
																										</tr>
																									</tfoot>
																								</tbody>
																							</table>
																						</div>
																					</div> ';
																		echo $projectPlan;
																		?>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<?php
									$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
									$query_rsProjects->execute();
									$row_rsProjects = $query_rsProjects->fetch();
									$totalRows_rsProjects = $query_rsProjects->rowCount();

									$projname = $row_rsProjects['projname'];
									$projcode = $row_rsProjects['projcode'];
									$projcost = $row_rsProjects['projcost'];
									$progid = $row_rsProjects['progid'];
									$projstartdate = $row_rsProjects['projstartdate'];
									$projtenderid = $row_rsProjects['projtender'];
									$projenddate = $row_rsProjects['projenddate'];
									$projcategory = $row_rsProjects['projcategory'];
									?>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
												<div class="header" align="center">
													<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="100%" height="40" style="padding-left:5px; background-color:#000; color:#FFF; font-size:16px">
																	<div align="left"><strong><i class="fa fa-pencil-square" aria-hidden="true"></i> Details</strong></div>
																</td>
															</tr>
														</table>
													</div>
												</div>
												<div class="header" align="" style="color:#000000">
													<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
														<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
															<label class="control-label">Project Code:</label>
															<div class="form-line">
																<input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
															</div>
														</div>
														<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
															<label class="control-label">Project Name:</label>
															<div class="form-line">
																<input type="text" class="form-control" value=" <?= $projname ?>" readonly>
															</div>
														</div>
													</div>
												</div>
												<div class="body">
													<?php
													$query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND type = :type");
													$query_rsTender->execute(array(":projid" => $projid, ":type" => 1));
													$row_plan = $query_rsTender->fetch();
													$totalRows_Tender = $query_rsTender->rowCount();
													$contribution_val = $row_plan['funds'];

													if ($projcategory == 2) {

														$query_tenderdetails = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid AND td_id = :tdid");
														$query_tenderdetails->execute(array(":projid" => $projid, ":tdid" => $projtenderid));
														$row_tenderdetails = $query_tenderdetails->fetch();
														$totalRows_tenderdetails = $query_tenderdetails->rowCount();
														$tenderid = $row_tenderdetails["td_id"];
														$tendertypeid = $row_tenderdetails["tendertype"];
														$tendercat = $row_tenderdetails["tendercat"];
														$tendercost = $row_tenderdetails["tenderamount"];
														$procurementmethod = $row_tenderdetails["procurementmethod"];
														$contractor = $row_tenderdetails["contractor"];

														$query_contractordetail = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid='$contractor'");
														$query_contractordetail->execute();
														$row_contractordetail = $query_contractordetail->fetch();
														$biztypeid = $row_contractordetail["businesstype"];

														$query_biztype = $db->prepare("SELECT type FROM tbl_contractorbusinesstype WHERE id = :biztypeid");
														$query_biztype->execute(array(":biztypeid" => $biztypeid));
														$row_biztype = $query_biztype->fetch();
														$biztype = $row_biztype["type"];
													?>
														<input type="hidden" name="tenderid" id="tenderid" value="<?= $tenderid ?>">
														<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-cogs" style="color:#F44336" aria-hidden="true"></i> Contractor Details</legend>
															<div class="col-md-12">
																<div class="form-inline">
																	<label for="">Contractor Name</label>
																	<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:100%" value="<?= $row_contractordetail["contractor_name"] ?>" disabled="disabled">
																</div>
															</div>
															<div id="contrinfo">
																<div class="col-md-4">
																	<label for="">Pin Number</label>
																	<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $row_contractordetail["pinno"] ?>" disabled="disabled">
																</div>
																<div class="col-md-4">
																	<label for="">Business Reg No.</label>
																	<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $row_contractordetail["busregno"] ?>" disabled="disabled">
																</div>
																<div class="col-md-4">
																	<label for="">Business Type</label>
																	<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $biztype ?>" disabled="disabled">
																</div>
															</div>
															<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
														</fieldset>
														<fieldset class="scheduler-border" style="border-radius:3px">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-shopping-bag" style="color:#F44336" aria-hidden="true"></i> Tender Details
															</legend>
															<div class="col-md-12">
																<label for="Title">Tender Title *:</label>
																<div class="form-line">
																	<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:100%" value="<?= $row_tenderdetails["tendertitle"] ?>" disabled="disabled">
																</div>
															</div>
															<!--<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
														<script type="text/javascript">
														
															/* webshims.setOptions('forms-ext', {
																replaceUI: 'auto',
																types: 'number'
															}); */
															//webshims.polyfill('forms forms-ext');
														</script>-->
															<div class="col-md-12">
																<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
																	<tr>
																		<th style="width:34%">Tender Category *</th>
																		<th style="width:33%">Tender Type *</th>
																		<th style="width:33%"> Procurement Method *</th>
																	</tr>
																	<tr>
																		<td>
																			<?php
																			$query_rscategory = $db->prepare("SELECT * FROM tbl_tender_category where id='$tendercat'");
																			$query_rscategory->execute();
																			$row_rscategory = $query_rscategory->fetch();
																			$contractorcat = $row_rscategory['category'];
																			?>
																			<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $contractorcat ?>" disabled="disabled">
																		</td>
																		<td>
																			<?php
																			$query_rstender = $db->prepare("SELECT * FROM tbl_tender_type where id='$tendertypeid'");
																			$query_rstender->execute();
																			$row_rstender = $query_rstender->fetch();
																			$tndtype = $row_rstender['type'];
																			?>
																			<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $tndtype ?>" disabled="disabled">
																		</td>
																		<td>
																			<?php
																			$query_rsprocurementmethod = $db->prepare("SELECT * FROM tbl_procurementmethod where id='$procurementmethod'");
																			$query_rsprocurementmethod->execute();
																			$row_rsprocurementmethod = $query_rsprocurementmethod->fetch();
																			$method = $row_rsprocurementmethod['method'];
																			?>
																			<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?= $method ?>" disabled="disabled">
																		</td>
																	</tr>
																</table>
															</div>

															<div class="col-md-12">
																<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
																	<tr>
																		<th style="width:30%">Contract Reference Number *</th>
																		<th style="width:30%">Tender Number *</th>
																		<th style="width:20%">Tender Technical Score *</th>
																		<th style="width:20%">Tender Financial Score *</th>
																	</tr>
																	<tr>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $row_tenderdetails["contractrefno"] ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $row_tenderdetails["tenderno"] ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="number" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $row_tenderdetails["technicalscore"] ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="number" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $row_tenderdetails["financialscore"] ?>" disabled="disabled">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<div class="col-md-12">
																<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
																	<tr>
																		<th style="width:33.3%">Tender Evaluation Date *</th>
																		<th style="width:33.3%">Tender Award Date *</th>
																		<th style="width:33.4%">Tender Notification Date *</th>
																	</tr>
																	<tr>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails["evaluationdate"]), "d M Y"); ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails["awarddate"]), "d M Y"); ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails["notificationdate"]), "d M Y"); ?>" disabled="disabled">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<div class="col-md-12">
																<table class="table table-bordered table-striped table-hover" id="item_table" style="width:100%">
																	<tr>
																		<th style="width:33.3%">Contract Signature Date *</th>
																		<th style="width:33.3%">Contract Start Date *</th>
																		<th style="width:33.4%">Contract End Date *</th>
																	</tr>
																	<tr>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails["signaturedate"]), "d M Y"); ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails['startdate']), 'd M Y'); ?>" disabled="disabled">
																			</div>
																		</td>
																		<td>
																			<div class="form-line">
																				<input type="text" class="form-control" value="<?php echo date_format(date_create($row_tenderdetails["enddate"]), 'd M Y'); ?>" disabled="disabled">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>

															<div class="col-md-12">
																<label class="control-label">Contract/Tender Comments *:</label>
																<p align="left">
																	<textarea cols="45" rows="5" class="form-control" disabled="disabled"><?= $row_tenderdetails["comments"] ?></textarea>
																</p>
															</div>
														</fieldset>
													<?php
													}
													?>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
															<i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> Procurement Cost
														</legend>
														<input type="hidden" name="contributed_amount" id="contributed_amount" value="<?= $contribution_val ?>">
														<?php
														$Ocounter = 0;
														$summary = '';
														$output_cost_val = [];
														$total_amount = 0;

														$query_Outputs = $db->prepare("SELECT p.output as output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid=:projid");
														$query_Outputs->execute(array(":projid" => $projid));
														$row_Outputs = $query_Outputs->fetch();
														$totalRows_Outputs = $query_Outputs->rowCount();
														if ($totalRows_Outputs > 0) {
															do {
																$Ocounter++;
																//get indicator
																$outputName = $row_Outputs['output'];
																$outputCost = $row_Outputs['budget'];
																$outputid = $row_Outputs['opid'];
																$output_cost_val[] = $outputid;
																$output_remeinder = 0;
																$poutput_remeinder = 0;

																$query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND outputid=:opid AND type=:type");
																$query_rsTender->execute(array(":projid" => $projid, ":opid" => $outputid, ":type" => 1));
																$row_plan = $query_rsTender->fetch();
																$totalRows_Tender = $query_rsTender->rowCount();
																$contribution_amount = $row_plan['funds'];
														?>

																<div class="panel panel-primary">
																	<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
																		<i class="fa fa-caret-down" aria-hidden="true"></i>
																		<strong> Output <?= $Ocounter ?>:
																			<span class="">
																				<?= $outputName ?>
																			</span>
																		</strong>
																	</div>
																	<div class="collapse output<?php echo $outputid ?>" style="padding:5px">
																		<div class="col-md-4 bg-brown">
																			<h5>
																				<strong> Procurement Budget (Ksh):
																					<span class="">
																						<?= number_format($contribution_amount, 2) ?>
																					</span>
																				</strong>
																			</h5>
																		</div>
																		<div class="col-md-4">
																		</div>
																		<div class="col-md-4 bg-brown">
																			<h5>
																				<strong> Tender Cost (Ksh):
																					<span class="">
																						<?= number_format($tendercost, 2) ?>
																					</span>
																				</strong>
																			</h5>
																		</div>
																		<div class="row clearfix" style="margin-top:3px; margin-bottom:3px">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<input type="hidden" name="projcost" id="projcost" value="<?= $projcost ?>">
																				<input type="hidden" name="projid" id="projid" value="<?= $hash ?>">
																				<input type="hidden" name="opid[]" id="opid<?= $outputid ?>" value="<?= $outputid ?>">
																				<input type="hidden" name="contribution_amount[]" id="contribution_amount<?= $outputid ?>" class="contribution_amount" value="<?= $contribution_amount ?>">
																				<input type="hidden" name="outputcost" id="outputcost<?= $outputid ?>" class="outputcost" value="<?= $outputCost ?>">
																				<input type="hidden" name="output_name" id="output_name<?= $outputid ?>" class="output_name" value="<?= $outputName ?>">
																				<div class="table-responsive">
																					<table class="table table-bordered" id="funding_table">
																						<thead>
																							<tr>
																								<th style="width:2%"># </th>
																								<th style="width:33%">Description </th>
																								<th style="width:12%">Unit</th>
																								<th style="width:17%">Unit Cost (Ksh)</th>
																								<th style="width:18%">No. of Units</th>
																								<th style="width:18%">Total Cost (Ksh)</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$query_Milestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid='$projid' and outputid ='$outputid' ORDER BY sdate ");
																							$query_Milestones->execute();
																							$row_Milestones = $query_Milestones->fetch();
																							$totalRows_Milestones = $query_Milestones->rowCount();
																							$mcounter = 0;
																							$sum = 0;
																							if ($totalRows_Milestones > 0) {
																								do {
																									$mcounter++;
																									$milestone = $row_Milestones['msid'];
																									$msid = $outputid . $milestone;
																									$milestoneName = $row_Milestones['milestone'];
																									$medate = date_format(date_create($row_Milestones['edate']), "d M Y");
																									$msdate = date_format(date_create($row_Milestones['sdate']), "d M Y");

																									$query_Tasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid='$projid' and msid='$milestone' ORDER BY sdate ");
																									$query_Tasks->execute();
																									$row_Tasks = $query_Tasks->fetch();
																									$totalRows_Tasks = $query_Tasks->rowCount();
																									if ($totalRows_Tasks > 0) {
																							?>
																										<input type="hidden" name="mileid<?= $outputid ?>[]" id="mileid<?= $milestone ?>" value="<?= $milestone ?>">
																										<tr class="bg-blue-grey">
																											<td><?= $Ocounter . "." . 1 . "." . $mcounter   ?></td>
																											<td colspan="3"><strong>Milestone: <?= $milestoneName ?></strong> </td>
																											<td colspan="1">
																												<strong>Start Date</strong>
																												<input type="text" name="mpsdate<?= $msid ?>" readonly id="mpsdate<?= $msid ?>" value="<?= $msdate ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																											</td>
																											<td colspan="1">
																												<strong>End Date</strong>
																												<input type="text" name="mpedate<?= $msid ?>" readonly id="mpedate<?= $msid ?>" value="<?= $medate ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																											</td>
																										</tr>
																										<?php
																										$tcounter = 0;
																										do {
																											$tcounter++;
																											$task =  $row_Tasks['task'];
																											$tkid =  $row_Tasks['tkid'];
																											$edate =  $row_Tasks['edate'];
																											$sdate =  $row_Tasks['sdate'];
																											$taskid = $outputid . $tkid; // to distinguish between different outputs
																											$cost_type = 1;
																											$datetime1 = new DateTime($sdate);
																											$datetime2 = new DateTime($edate);
																											$difference = $datetime1->diff($datetime2);
																											$duration = $difference->d;
																										?>
																											<tr class="bg-grey">
																												<td><?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?></td>
																												<td colspan="3"><strong> Task: <?= $task ?></strong> </td>
																												<td colspan="1">
																													<span><strong>Start Date:</strong></span>

																													<input type="text" value="<?php echo date_format(date_create($sdate), "d M Y"); ?>" value="" class="form-control mile_start<?= $msid ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																												</td>
																												<td colspan="1">
																													<strong>Duration (Days):</strong>

																													<input type="number" value="<?= $duration  ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																												</td>
																											</tr>
																											<?php
																											$query_Direct_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
																											$query_Direct_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																											$row_Direct_cost_plan = $query_Direct_cost_plan->fetch();
																											$totalRows_Direct_cost_plan = $query_Direct_cost_plan->rowCount();

																											if ($totalRows_Direct_cost_plan > 0) {
																												$plan_counter = 0;
																												do {
																													$plan_counter++;
																													$new_id = $taskid . $plan_counter;
																													$unit = $row_Direct_cost_plan['unit'];
																													$unit_cost = $row_Direct_cost_plan['unit_cost'];
																													$units_no = $row_Direct_cost_plan['units_no'];
																													$costlineid = $row_Direct_cost_plan['id'];
																													$total_cost = $unit_cost * $units_no;
																													$output_remeinder = $output_remeinder + $total_cost;

																													$query_Procurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid =:projid AND outputid=:outputid AND tasks=:tkid AND costlineid=:costlineid");
																													$query_Procurement->execute(array(":projid" => $projid, ":outputid" => $outputid, ":tkid" => $tkid, ':costlineid' => $costlineid));
																													$row_Procurement = $query_Procurement->fetch();
																													$totalRows_Procurement = $query_Procurement->rowCount();

																													$punit = $row_Procurement['unit'];
																													$punit_cost = $row_Procurement['unit_cost'];
																													$punits_no = $row_Procurement['units_no'];
																													$prmkid = $row_Procurement['id'];
																													$description = $row_Procurement['description'];
																													$ptotal_cost = $punit_cost * $punits_no;
																													$poutput_remeinder = $poutput_remeinder + $ptotal_cost;
																													$total_amount = $total_amount + $ptotal_cost;
																											?>
																													<tr>
																														<td>
																															<?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?>
																														</td>
																														<td>
																															<?= $description ?>
																															<input type="hidden" value="<?= $description ?>" readonly class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td>
																															<?= $unit ?>
																															<input type="hidden" name="hunit<?= $taskid ?>[]" id="hunit<?= $new_id ?>" value="<?= $unit ?>">
																															<input type="hidden" name="dunit<?= $taskid ?>[]" value="<?= $unit ?>" id="unit<?= $new_id ?>" readonly class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																															<input type="text" value="<?php echo number_format($punit_cost) ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td>
																															<input type="text" value="<?php echo number_format($units_no) ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td>
																															<input type="text" value="<?php echo number_format($ptotal_cost) ?>" class="form-control totalCost summarytotal  output_cost<?= $outputid ?> direct_sub_total_amount1<?= $outputid ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																													</tr>
																							<?php
																												} while ($row_Direct_cost_plan = $query_Direct_cost_plan->fetch());
																											}
																										} while ($row_Tasks = $query_Tasks->fetch());
																									}
																								} while ($row_Milestones = $query_Milestones->fetch());
																								$sub_per = number_format(($poutput_remeinder / $output_remeinder) * 100, 2);
																								$balance = number_format(($output_remeinder - $poutput_remeinder), 2);
																							}
																							?>
																						<tfoot class="bg-brown">
																							<tr>
																								<td colspan="3"></strong></td>
																								<td colspan="2"><strong>Sub Total</strong></td>
																								<td colspan="2">
																									<input type="text" name="d_sub_total_amount" value="<?= number_format($poutput_remeinder, 2) ?>" id="sub_total_amount1<?= $outputid ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																								</td>
																							</tr>
																							<tr>
																								<td colspan="3"></strong></td>
																								<td colspan="2"> <strong>% Sub Total</strong></td>
																								<td colspan="1">
																									<input type="text" name="d_sub_total_percentage" value="<?= number_format($sub_per, 2) ?> %" id="sub_total_percentage1<?= $outputid ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																								</td>
																							</tr>
																							<tr>
																								<td colspan="3"></strong></td>
																								<td colspan="2"> <strong>Planned Amount Balance (Ksh)</strong></td>
																								<td colspan="1">
																									<input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= $balance ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																								</td>
																							</tr>
																						</tfoot>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
														<?php
																$summary  .= '<tr>
															<td>' . $Ocounter . '</td>
															<td>' . $outputName . '</td>
															<td style="text-align:left">' . number_format($contribution_amount, 2) . '</td>
															<td id="summaryOutput' . $outputid . '"  style="text-align:left">' . number_format($poutput_remeinder, 2) . '</td>
															<td id="perc' . $outputid .  '"  style="text-align:left">' . number_format((($poutput_remeinder / $output_remeinder) * 100), 2) . ' %</td>
														</tr>';
															} while ($row_Outputs = $query_Outputs->fetch());
														}
														?>
													</fieldset>
													<fieldset class="scheduler-border" style="background-color:#ebedeb; border-radius:3px">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
															<i class="fa fa-file-text" style="color:#F44336" aria-hidden="true"></i> Procurement Cost Summary
														</legend>
														<div class="table-responsive">
															<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																<thead>
																	<tr>
																		<th width="2%">#</th>
																		<th width="64%">Output</th>
																		<th width="12%">Planned Amount (Ksh)</th>
																		<th width="12%">Procurement Amount (Ksh)</th>
																		<th width="10%">% Procurement </th>
																	</tr>
																</thead>
																<tbody id="">
																	<?php echo $summary ?>
																<tfoot>
																	<tr>
																		<td colspan="2">
																			<strong>
																				Total Amount
																			</strong>
																		</td>
																		<td style="text-align:left">
																			<strong>
																				<?= number_format($contribution_val, 2) ?>
																			</strong>
																		</td>
																		<td style="text-align:left">
																			<strong id="summary_total">
																				<?= number_format($total_amount, 2) ?>
																			</strong>
																		</td>
																		<td style="text-align:left">
																			<strong id="summary_percentage">
																				<?= number_format((($total_amount / $contribution_val) * 100), 2) ?>%
																			</strong>
																		</td>
																	</tr>
																</tfoot>
																</tbody>
															</table>
														</div>
													</fieldset>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-paperclip" style="color:#F44336" aria-hidden="true"></i> Procurement Documents/Files Attachment</legend>
														<?php
														$stage = 7;
														$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
														$query_rsFile->execute(array(":stage" => $stage, ":projid" => $projid));
														$row_rsFile = $query_rsFile->fetch();
														$totalRows_rsFile = $query_rsFile->rowCount();
														?>
														<div class="row clearfix " id="rowcontainerrow">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="card">
																	<div class="header">
																		<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
																			<h5 style="color:#FF5722"><strong> Attached Files </strong></h5>
																		</div>
																	</div>
																	<div class="body">
																		<div class="body table-responsive">
																			<table class="table table-bordered" style="width:100%">
																				<thead>
																					<tr>
																						<th style="width:2%">#</th>
																						<th style="width:68%">Purpose</th>
																						<th style="width:28%">Attachment</th>
																						<th style="width:2%">
																							Delete
																						</th>
																					</tr>
																				</thead>
																				<tbody id="attachment_table">
																					<?php
																					if ($totalRows_rsFile > 0) {
																						$counter = 0;
																						do {
																							$pdfname = $row_rsFile['filename'];
																							$filecategory = $row_rsFile['fcategory'];
																							$ext = $row_rsFile['ftype'];
																							$filepath = $row_rsFile['floc'];
																							$fid = $row_rsFile['fid'];
																							$attachmentPurpose = $row_rsFile['reason'];
																							$counter++;
																					?>
																							<tr id="mtng<?= $fid ?>">
																								<td>
																									<?= $counter ?>
																								</td>
																								<td>
																									<?= $attachmentPurpose ?>
																									<input type="hidden" name="fid[]" id="fid" class="" value="<?= $fid  ?>">
																									<input type="hidden" name="ef[]" id="t" class="eattachment_purpose" value="<?= $attachmentPurpose  ?>">
																								</td>
																								<td>
																									<?= $pdfname ?>
																									<input type="hidden" name="adft[]" id="fid" class="eattachment_file" value="<?= $pdfname  ?>">
																								</td>
																								<td>
																									<button type="button" class="btn btn-danger btn-sm" onclick=delete_attachment("mtng<?= $fid ?>")>
																										<span class="glyphicon glyphicon-minus"></span>
																									</button>
																								</td>
																							</tr>
																					<?php
																						} while ($row_rsFile = $query_rsFile->fetch());
																					}
																					?>
																				</tbody>
																			</table>
																		</div>
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