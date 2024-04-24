<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
if ($permission) {
	try {
		$back_url = $_SESSION['back_url'];

		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$projname = $row_rsMyP['projname'];
		$projstage = $row_rsMyP["projstage"];
		$projcat = $row_rsMyP["projcategory"];
		$projevaluation = $row_rsMyP['projevaluation'];
		$administrative_cost = $row_rsMyP['administrative_cost'];


		$percent2 = number_format(calculate_project_progress($projid, $projcat), 2);

		$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget, o.total_target FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
		$query_rsOutputs->execute(array(":projid" => $projid));
		$row_rsOutputs = $query_rsOutputs->fetch();
		$totalRows_rsOutputs = $query_rsOutputs->rowCount();


		$query_Mapping = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid AND indicator_mapping_type <> 0 ORDER BY id ASC");
		$query_Mapping->execute(array(":projid" => $projid));
		$countrows_Mapping = $query_Mapping->rowCount();
		$mappingactivite = $countrows_Mapping > 0 ? true : false;
		$outcomeactive = $projevaluation == 1 ? true : false;

		function get_measurement($unit)
		{
			global $db;
			$sql = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
			$sql->execute(array(":unit_id" => $unit));
			$row = $sql->fetch();
			$rows_count = $sql->rowCount();
			return ($rows_count > 0) ?   $row['unit'] : "";
		}


		function get_task_compliance($state_id, $site_id, $task_id)
		{
			global $db;
			$compliance = [];
			$query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE task_id=:task_id");
			$query_rsSpecifions->execute(array(":task_id" => $task_id));
			$totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
			if ($totalRows_rsSpecifions > 0) {
				while ($row_rsSpecifions = $query_rsSpecifions->fetch()) {
					$specification_id = $row_rsSpecifions['id'];
					$query_rsCompliance = $db->prepare("SELECT * FROM tbl_project_inspection_specification_compliance WHERE state_id=:state_id AND site_id=:site_id AND specification_id=:specification_id  ORDER BY id DESC LIMIT 1");
					$query_rsCompliance->execute(array(":state_id" => $state_id, ":site_id" => $site_id, ":specification_id" => $specification_id));
					$Rows_rsCompliance = $query_rsCompliance->fetch();
					$totalRows_rsCompliance = $query_rsCompliance->rowCount();
					$compliance[] = ($totalRows_rsCompliance > 0) ? $Rows_rsCompliance['compliance'] : 0;
				}
			}

			$task_compliance = "";
			if (in_array(1, $compliance)) {
				$task_compliance = "Compliant";
			} else if (in_array(2, $compliance)) {
				$task_compliance = "Non-Compliant";
			} else if (in_array(2, $compliance)) {
				$task_compliance = "On-Track";
			}
			return $task_compliance;
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>

					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='<?= $back_url ?>'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="" style="margin-top:-15px">
								<a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
								<a href="project-mne-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px"> M&E </a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<?php if ($projcat == 2 && $projstage > 4) { ?>
									<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<?php } ?>
								<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
								<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Map</a>
								<a href="project-media.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?= $percent2 ?>%
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
									<a data-toggle="tab" href="#home">
										<i class="fa fa-book bg-blue" aria-hidden="true"></i> Financial Plan &nbsp;<span class="badge bg-blue">|</span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1">
										<i class="fa fa-filter bg-green" aria-hidden="true"></i> Funding &nbsp;<span class="badge bg-green">|</span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2"><i class="fa fa-money bg-orange" aria-hidden="true"></i> Payment &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="card-header">
										<ul class="nav nav-tabs" style="font-size:14px">
											<li class="active">
												<a data-toggle="tab" href="#direct_cost">
													<i class="fa fa-tasks bg-blue" aria-hidden="true"></i> Direct Project Cost &nbsp;<span class="badge bg-blue"></span>
												</a>
											</li>
											<?php
											if ($administrative_cost > 0) {
											?>
												<li>
													<a data-toggle="tab" href="#administrative"><i class="fa fa-pencil-square-o bg-green" aria-hidden="true"></i> Administrative/Operational Cost&nbsp;<span class="badge bg-green"></span></a>
												</li>
											<?php
											}
											?>
										</ul>
									</div>
									<div class="tab-content">
										<div id="direct_cost" class="tab-pane fade in active">
											<div class="body">
												<?php
												$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
												$query_Sites->execute(array(":projid" => $projid));
												$rows_sites = $query_Sites->rowCount();
												if ($rows_sites > 0) {
													$counter = 0;
													while ($row_Sites = $query_Sites->fetch()) {
														$site_id = $row_Sites['site_id'];
														$site = $row_Sites['site'];
														$counter++;
												?>
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> : <?= $site ?>
															</legend>
															<?php
															$query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
															$query_Site_Output->execute(array(":site_id" => $site_id));
															$rows_Site_Output = $query_Site_Output->rowCount();
															if ($rows_Site_Output > 0) {
																$output_counter = 0;
																while ($row_Site_Output = $query_Site_Output->fetch()) {
																	$output_counter++;
																	$output_id = $row_Site_Output['outputid'];
																	$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
																	$query_Output->execute(array(":outputid" => $output_id));
																	$row_Output = $query_Output->fetch();
																	$total_Output = $query_Output->rowCount();
																	if ($total_Output) {
																		$output_id = $row_Output['id'];
																		$output = $row_Output['indicator_name'];
															?>
																		<fieldset class="scheduler-border">
																			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																				<i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $output_counter ?> : <?= $output ?>
																			</legend>
																			<?php
																			$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
																			$query_rsMilestone->execute(array(":output_id" => $output_id));
																			$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																			if ($totalRows_rsMilestone > 0) {
																				while ($row_rsMilestone = $query_rsMilestone->fetch()) {
																					$milestone = $row_rsMilestone['milestone'];
																					$msid = $row_rsMilestone['msid'];
																					$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 AND site_id=:site_id AND tasks=:tasks");
																					$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ':site_id' => $site_id, ":tasks" => $msid));
																					$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
																					$sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
																					$total_cost = 0;
																			?>
																					<div class="row clearfix">
																						<input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
																						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																							<div class="card-header">
																								<div class="row clearfix">
																									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																										<ul class="list-group">
																											<li class="list-group-item list-group-item list-group-item-action active">
																												Task: <?= $milestone ?>
																											</li>
																											<li class="list-group-item"><strong>Task Cost: </strong> <?= number_format($sum_cost, 2) ?> </li>
																										</ul>
																									</div>
																								</div>
																							</div>
																							<div class="table-responsive">
																								<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
																									<thead>
																										<tr>
																											<th style="width:5%">#</th>
																											<th style="width:40%">Item </th>
																											<th style="width:25%">Unit of Measure</th>
																											<th style="width:10%">No. of Units</th>
																											<th style="width:10%">Unit Cost (Ksh)</th>
																											<th style="width:10%">Total Cost (Ksh)</th>
																										</tr>
																									</thead>
																									<tbody>
																										<?php
																										if ($sum_cost > 0) {
																											$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id ");
																											$query_rsOther_cost_plan->execute(array(":task_id" => $msid, ':site_id' => $site_id));
																											$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
																											if ($totalRows_rsOther_cost_plan > 0) {
																												$table_counter = 0;
																												while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
																													$table_counter++;
																													$rmkid = $row_rsOther_cost_plan['id'];
																													$description = $row_rsOther_cost_plan['description'];
																													$financial_year = $row_rsOther_cost_plan['financial_year'];
																													$unit = $row_rsOther_cost_plan['unit'];
																													$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																													$units_no = $row_rsOther_cost_plan['units_no'];
																													$total_cost = $unit_cost * $units_no;

																													$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																													$query_rsIndUnit->execute(array(":unit_id" => $unit));
																													$row_rsIndUnit = $query_rsIndUnit->fetch();
																													$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																													$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
																										?>
																													<tr id="row">
																														<td style="width:5%"><?= $table_counter ?></td>
																														<td style="width:40%"><?= $description ?></td>
																														<td style="width:25%"><?= $unit_of_measure ?></td>
																														<td style="width:10%"><?= number_format($units_no) ?></td>
																														<td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
																														<td style="width:10%"><?= number_format($total_cost, 2) ?></td>
																													</tr>
																												<?php
																												}
																											}
																										} else {
																											$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
																											$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
																											$totalRows_rsTasks = $query_rsTasks->rowCount();
																											if ($totalRows_rsTasks > 0) {
																												$tcounter = 0;
																												while ($row_rsTasks = $query_rsTasks->fetch()) {
																													$tcounter++;
																													$task_name = $row_rsTasks['task'];
																													$task_id = $row_rsTasks['tkid'];
																													$unit =  $row_rsTasks['unit_of_measure'];
																													$query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id");
																													$query_rsTask_parameters->execute(array(":task_id" => $msid, ':site_id' => $site_id));
																													$totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
																													$row_rsTask_parameters = $query_rsTask_parameters->fetch();
																													$unit_cost = $units_no = 0;
																													if ($totalRows_rsTask_parameters > 0) {
																														$unit_cost = $row_rsTask_parameters['unit_cost'];
																														$units_no =  $row_rsTask_parameters['units_no'];
																														$total_cost = $unit_cost * $units_no;
																													}

																													$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																													$query_rsIndUnit->execute(array(":unit_id" => $unit));
																													$row_rsIndUnit = $query_rsIndUnit->fetch();
																													$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																													$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
																												?>
																													<tr id="row<?= $tcounter ?>">
																														<td style="width:5%"><?= $tcounter ?></td>
																														<td style="width:40%"><?= $task_name ?></td>
																														<td style="width:25%"><?= $unit_of_measure ?></td>
																														<td style="width:10%"><?= number_format($units_no) ?></td>
																														<td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
																														<td style="width:10%"><?= number_format($total_cost, 2) ?></td>
																													</tr>
																										<?php
																												}
																											}
																										}
																										?>
																									</tbody>
																								</table>
																							</div>
																						</div>
																					</div>
																			<?php
																				}
																			}
																			?>
																		</fieldset>
															<?php
																	}
																}
															}
															?>
														</fieldset>
														<?php
													}
												}

												$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1  AND projid = :projid");
												$query_Output->execute(array(":projid" => $projid));
												$total_Output = $query_Output->rowCount();
												$outputs = '';
												if ($total_Output > 0) {
													$outputs = '';
													if ($total_Output > 0) {
														$counter = 0;
														while ($row_rsOutput = $query_Output->fetch()) {
															$output_id = $row_rsOutput['id'];
															$output = $row_rsOutput['indicator_name'];
															$counter++;
														?>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																	<i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
																</legend>
																<?php
																$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
																$query_rsMilestone->execute(array(":output_id" => $output_id));
																$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																if ($totalRows_rsMilestone > 0) {
																	while ($row_rsMilestone = $query_rsMilestone->fetch()) {
																		$milestone = $row_rsMilestone['milestone'];
																		$msid = $row_rsMilestone['msid'];
																		$site_id = 0;
																		$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 AND tasks=:tasks ");
																		$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":tasks" => $msid));
																		$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
																		$sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
																		$total_cost = 0;
																?>
																		<div class="row clearfix">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<div class="card-header">
																					<div class="row clearfix">
																						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																							<ul class="list-group">
																								<li class="list-group-item list-group-item list-group-item-action active">
																									Task: <?= $milestone ?>
																								</li>
																								<li class="list-group-item"><strong>Task Cost: </strong> <?= number_format($sum_cost, 2) ?> </li>
																							</ul>
																						</div>
																					</div>
																				</div>
																				<div class="table-responsive">
																					<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
																						<thead>
																							<tr>
																								<th style="width:5%">#</th>
																								<th style="width:40%">Item</th>
																								<th style="width:25%">Unit of Measure</th>
																								<th style="width:10%">No. of Units</th>
																								<th style="width:10%">Unit Cost (Ksh)</th>
																								<th style="width:10%">Total Cost (Ksh)</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id ");
																							$query_rsOther_cost_plan->execute(array(":task_id" => $msid, ':site_id' => $site_id));
																							$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
																							if ($totalRows_rsOther_cost_plan > 0) {
																								$table_counter = 0;
																								while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
																									$table_counter++;
																									$rmkid = $row_rsOther_cost_plan['id'];
																									$description = $row_rsOther_cost_plan['description'];
																									$financial_year = $row_rsOther_cost_plan['financial_year'];
																									$unit = $row_rsOther_cost_plan['unit'];
																									$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																									$units_no = $row_rsOther_cost_plan['units_no'];
																									$total_cost = $unit_cost * $units_no;

																									$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																									$query_rsIndUnit->execute(array(":unit_id" => $unit));
																									$row_rsIndUnit = $query_rsIndUnit->fetch();
																									$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																									$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
																							?>
																									<tr id="row">
																										<td style="width:5%"><?= $table_counter ?></td>
																										<td style="width:40%"><?= $description ?></td>
																										<td style="width:25%"><?= $unit_of_measure ?></td>
																										<td style="width:10%"><?= number_format($units_no) ?></td>
																										<td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
																										<td style="width:10%"><?= number_format($total_cost, 2) ?></td>
																									</tr>
																							<?php
																								}
																							}

																							?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																<?php
																	}
																}
																?>
															</fieldset>
												<?php
														}
													}
												}
												?>
											</div>
										</div>
										<?php
										if ($administrative_cost > 0) {
										?>
											<div id="administrative" class="tab-pane fade">
												<?php
												$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type ");
												$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => 2));
												$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();


												$cost_type = $budget_line_id = 2;
												$query_rsOther_cost_plan1 =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
												$query_rsOther_cost_plan1->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
												$totalRows_rsOther_cost_plan1 = $query_rsOther_cost_plan1->rowCount();
												$row_rsOther_cost_plan1 = $query_rsOther_cost_plan1->fetch();
												$plan_id = $totalRows_rsOther_cost_plan1 > 0 ? $row_rsOther_cost_plan1['plan_id'] : 0;


												$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
												$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
												$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
												$totalRows_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->rowCount();
												$sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
												?>
												<div class="header">
													<h4 class="contentheader"> Administrative/Operational Cost </h4>
												</div>
												<div class="body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr>
																	<th style="width:5%">#</th>
																	<th style="width:40%">Item</th>
																	<th style="width:25%">Unit of Measure</th>
																	<th style="width:10%">No. of Units</th>
																	<th style="width:10%">Unit Cost (Ksh)</th>
																	<th style="width:10%">Total Cost (Ksh)</th>
																</tr>
															</thead>
															<tbody id="budget_lines_table2">
																<?php
																if ($totalRows_rsOther_cost_plan > 0) {
																	$table_counter = 0;
																	while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
																		$table_counter++;
																		$rmkid = $row_rsOther_cost_plan['id'];
																		$description = $row_rsOther_cost_plan['description'];
																		$financial_year = $row_rsOther_cost_plan['financial_year'];
																		$unit = $row_rsOther_cost_plan['unit'];
																		$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																		$units_no = $row_rsOther_cost_plan['units_no'];
																		$total_cost = $unit_cost * $units_no;

																		$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																		$query_rsIndUnit->execute(array(":unit_id" => $unit));
																		$row_rsIndUnit = $query_rsIndUnit->fetch();
																		$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																		$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
																?>
																		<tr id="row">
																			<td style="width:5%"><?= $table_counter ?></td>
																			<td style="width:40%"><?= $description ?></td>
																			<td style="width:25%"><?= $unit_of_measure ?></td>
																			<td style="width:10%"><?= number_format($units_no) ?></td>
																			<td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
																			<td style="width:10%"><?= number_format($total_cost, 2) ?></td>
																		</tr>
																<?php
																	}
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										<?php
										}
										?>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border" style="border-radius:3px">
												<legend class="scheduler-border" style="color:white; background-color:green; border-radius:3px">
													<i class="fa fa-university" aria-hidden="true"></i> Funding Details
												</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
														<thead>
															<tr>
																<th width="4%">#</th>
																<th width="80%">Financier</th>
																<th width="16%" align="right">Amount (Ksh)</th>
															</tr>
														</thead>
														<tbody id="">
															<tr></tr>
															<?php
															// query the
															$query_rsProjFinancier =  $db->prepare("SELECT *, f.financier FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier WHERE projid = :projid ORDER BY amountfunding desc");
															$query_rsProjFinancier->execute(array(":projid" => $projid));
															$row_rsProjFinancier = $query_rsProjFinancier->fetch();
															$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();
															$rowno = 0;
															$totalAmount = 0;
															if ($totalRows_rsProjFinancier > 0) {
																do {
																	$rowno++;
																	$sourcat =  $row_rsProjFinancier['sourcecategory'];
																	$source = $row_rsProjFinancier['id'];
																	$financier = $row_rsProjFinancier['financier'];
																	$projamountfunding =  $row_rsProjFinancier['amountfunding'];
																	$totalAmount = $projamountfunding + $totalAmount;
																	$inputs = '';
																	$inputs .= '<span>' . $financier . '</span>';
															?>
																	<tr id="row<?= $rowno ?>">
																		<td>
																			<?= $rowno ?>
																		</td>
																		<td>
																			<?php echo $inputs ?>
																		</td>
																		<td align="left">
																			<?php echo number_format($projamountfunding, 2); ?>
																		</td>
																	</tr>
																<?php
																} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
															} else {
																?>
																<tr>
																	<td colspan="5">No Financier Found</td>
																</tr>
															<?php
															}
															?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="2"><strong>Total Amount</strong></td>
																<td align="left"><strong><?= number_format($totalAmount, 2) ?></strong></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</fieldset>
										</div>
									</div>
								</div>
								<div id="menu2" class="tab-pane fade">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border row setup-content" id="step-2">
												<legend class="scheduler-border bg-orange" style="border-radius:3px"><i class="fa fa-credit-card" aria-hidden="true"></i> Payment Details</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr>
																<th style="width:5%" align="center">#</th>
																<th style="width:15%">Receipt No.</th>
																<th style="width:20%">Amount</th>
																<th style="width:15%">Date Paid</th>
																<th style="width:30%">Paid By</th>
																<th style="width:15%">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid, d.receipt, d.receipt_no FROM tbl_payments_request r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.id WHERE status = 3 AND r.projid=:projid");
															$query_rsPayement_reuests->execute(array(":projid" => $projid));
															$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
															if ($total_rsPayement_reuests > 0) {
																$counter = 0;
																while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch()) {
																	$projid = $rows_rsPayement_reuests['projid'];
																	$date_requested = $rows_rsPayement_reuests['date_requested'];
																	$request_id = $rows_rsPayement_reuests['id'];
																	$date_paid = $rows_rsPayement_reuests['date_paid'];
																	$created_by = $rows_rsPayement_reuests['created_by'];
																	$created_at = $rows_rsPayement_reuests['created_at'];
																	$receipt = $rows_rsPayement_reuests['receipt'];
																	$receipt_no = $rows_rsPayement_reuests['receipt_no'];

																	$query_rsPayment_request_details =  $db->prepare("SELECT SUM(unit_cost * no_of_units) as amount_paid FROM tbl_payments_request_details WHERE request_id=:request_id");
																	$query_rsPayment_request_details->execute(array(":request_id" => $request_id));
																	$rows_rsPayment_request_details = $query_rsPayment_request_details->fetch();
																	$amount_paid = $rows_rsPayment_request_details['amount_paid'];


																	$get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
																	$get_user->execute(array(":user_id" => $created_by));
																	$count_user = $get_user->rowCount();
																	$user = $get_user->fetch();
																	$officer = $user['fullname'];

																	$query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
																	$query_rsprojects->execute(array('projid' => $projid));
																	$rows_rsprojects = $query_rsprojects->fetch();
																	$total_rsprojects = $query_rsprojects->rowCount();

																	$progid = $rows_rsprojects['progid'];
																	$project_name = $rows_rsprojects['projname'];
																	$counter++;
															?>
																	<tr>
																		<td style="width:5%" align="center"><?= $counter ?></td>
																		<td style="width:15%"><?= $receipt_no ?></td>
																		<td style="width:20%"><?= number_format($amount_paid, 2) ?></td>
																		<td style="width:15%"><?= date("Y-m-d", strtotime($date_paid)) ?> </td>
																		<td style="width:30%"><?= $officer ?></td>
																		<td style="width:15%">
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a type="button" href="<?= $receipt ?>" target="_blank">
																							<i class="fa fa-info"></i> Receipt
																						</a>
																					</li>
																				</ul>
																			</div>
																		</td>
																	</tr>
															<?php

																}
															}
															?>
														</tbody>
													</table>
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
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>