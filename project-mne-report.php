<?php
require('includes/head.php');
if ($permission) {
	$decode_proj = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: {$sourceurl}");
	$proj_array = explode("rept321", $decode_proj);
	$projid = $proj_array[1];
	//$projid = 1;

	$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
	$query_proj->execute(array(":projid" => $projid));
	$row_proj = $query_proj->fetch();
	$project = $row_proj["projname"];
	$projstage = $row_proj["projstage"];
	$projstatus = $row_proj["projstatus"];
	$projoutcome = $row_proj["projevaluation"];
	$projimpact = $row_proj["projimpact"];
	$indid = $row_proj["outcome_indicator"];
	$proj_locations = $row_proj["projlga"];
	$projlocations = explode(",", $proj_locations);
	$proj_location_count = count($projlocations);

	$tab1 = $projstage > 6 && $projstatus != 3 ? "home" : "";
	$class1 = $projstage > 6 && $projstatus != 3 ? "active" : "";
	$inactive1 = $projstage > 6 && $projstatus != 3 ? "in active" : "";

	$tab2 = $projstage == 6 || ($projstage > 6 && $projstatus == 3) ? "home" : "menu2";
	$class2 = $projstage == 6 || ($projstage > 6 && $projstatus == 3) ? "active" : "";
	$inactive2 = $projstage == 6 || ($projstage > 6 && $projstatus == 3) ? "in active" : "";

	function get_checklist_score($mapping_type, $task_id, $site_id)
	{
		global $db;
		$query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE task_id=:task_id");
		$query_rsMonitoring->execute(array(":task_id" => $task_id));
		$totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
		$rate = 0;
		$percentage = 0;
		if ($totalRows_rsMonitoring > 0) {
			while ($row_rsMonitoring = $query_rsMonitoring->fetch()) {
				$checklist_id = $row_rsMonitoring['checklist_id'];
				$target = $row_rsMonitoring['target'];

				$query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND task_id=:task_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
				$query_rsMonitoring_Achieved->execute(array(":checklist_id" => $checklist_id, ":task_id" => $task_id, ":site_id" => $site_id));
				if ($mapping_type == 2 || $mapping_type == 3) {
					$query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND task_id=:task_id ORDER BY id DESC LIMIT 1");
					$query_rsMonitoring_Achieved->execute(array(":checklist_id" => $checklist_id, ":task_id" => $task_id));
				}

				$Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
				$totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();
				$achieved = $totalRows_rsMonitoring_Achieved > 0 ? $Rows_rsMonitoring_Achieved['achieved'] : 0;

				$percentage += $achieved > 0 && $target > 0 ? ($achieved / $target) * 100 : 0;
			}
		}

		return $percentage > 0 && $totalRows_rsMonitoring > 0 ? $percentage / $totalRows_rsMonitoring : 0;
	}
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group pull-right">
							<input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='mne-reports'" id="btnback">
						</div>
					</div>
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
								<div class="header">
									<h5 class="text-align-center bg-grey" style="border-radius:4px; padding:7px; height:35px">
										<strong>Project Name: <?= $project ?></strong>
									</h5>
								</div>
								<div class="header">
									<ul class="nav nav-tabs" style="font-size:14px">
										<?php
										if ($projstage > 6) {
										?>
											<li class="<?= $class1 ?>">
												<a data-toggle="tab" href="#<?= $tab1 ?>"><i class="fa fa-file-text-o bg-orange" aria-hidden="true"></i> Activities Monitoring &nbsp;<span class="badge bg-orange"></span></a>
											</li>
											<li>
												<a data-toggle="tab" href="#menu1"><i class="fa fa-file-text-o bg-light-blue" aria-hidden="true"></i> Output Monitoring &nbsp;<span class="badge bg-light-blue"></span></a>
											</li>
										<?php
										}
										if ($projoutcome == 1) {
										?>
											<li class="<?= $class2 ?>">
												<a data-toggle="tab" href="#<?= $tab2 ?>"><i class="fa fa-file-text-o bg-light-green" aria-hidden="true"></i> Outcome Evaluation &nbsp;<span class="badge bg-light-green"></span></a>
											</li>
											<li>
												<a data-toggle="tab" href="#menu3"><i class="fa fa-file-text-o bg-green" aria-hidden="true"></i> Impact Evaluation &nbsp;<span class="badge bg-green"></span></a>
											</li>
										<?php } ?>
									</ul>
								</div>
								<div class="row clearfix">
									<div class="table-responsive">
										<div class="tab-content">
											<?php if ($projstage > 6) { ?>
												<div id="<?= $tab1 ?>" class="tab-pane fade <?= $inactive1 ?>">
													<div class="body">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<fieldset class="scheduler-border">
																<legend class="scheduler-border bg-orange" style="border-radius:3px">
																	<i class="fa fa-list-ol" aria-hidden="true"></i> <strong> Project Activities Results</strong>
																</legend>
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<?php
																	$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ORDER BY d.id");
																	$query_Output->execute(array(":projid" => $projid));
																	$total_Output = $query_Output->rowCount();
																	if ($total_Output > 0) {
																		$counter = 0;
																		while ($row_rsOutput = $query_Output->fetch()) {
																			$counter++;
																			$output_id = $row_rsOutput['id'];
																			$indicator_name = $row_rsOutput['indicator_name'];
																			$target = $row_rsOutput['total_target'];
																			$indicator_mapping_type = $row_rsOutput['indicator_mapping_type'];
																	?>
																			<fieldset class="scheduler-border">
																				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																					<i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $indicator_name ?>
																				</legend>
																				<?php
																				if ($indicator_mapping_type == 1 || $indicator_mapping_type == 3) {
																					$query_OutputSites = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
																					$query_OutputSites->execute(array(":output_id" => $output_id));
																					$total_OutputSites = $query_Output->rowCount();
																					if ($total_OutputSites > 0) {
																						$scounter = 0;
																						while ($row_rsOutputSites = $query_OutputSites->fetch()) {
																							$scounter++;
																							$site_name = $row_rsOutputSites['site'];
																							$site_id = $row_rsOutputSites['site_id'];
																				?>
																							<fieldset class="scheduler-border">
																								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																									<i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $scounter ?> : <?= $site_name ?>
																								</legend>

																								<?php
																								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
																								$query_rsMilestone->execute(array(":output_id" => $output_id));
																								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																								if ($totalRows_rsMilestone > 0) {
																									while ($row_rsMilestone = $query_rsMilestone->fetch()) {
																										$milestone = $row_rsMilestone['milestone'];
																										$msid = $row_rsMilestone['msid'];
																								?>
																										<div class="row clearfix">
																											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																												<div class="card-header">
																													<div class="row clearfix">
																														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																															<ul class="list-group">
																																<li class="list-group-item list-group-item list-group-item-action active">Task: <?= $milestone ?>
																																	<div class="btn-group" style="float:right">
																																		<div class="btn-group" style="float:right">
																																		</div>
																																	</div>
																																</li>
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
																																<th style="width:10%">Last Record Date</th>
																																<th style="width:10%">Target</th>
																																<th style="width:10%">Achieved</th>
																																<th style="width:10%">Rate (%)</th>
																															</tr>
																														</thead>
																														<tbody>
																															<?php
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
																																	$units_no =  ($totalRows_rsTask_parameters > 0) ?  $row_rsTask_parameters['units_no'] : 0;

																																	$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																																	$query_rsIndUnit->execute(array(":unit_id" => $unit));
																																	$row_rsIndUnit = $query_rsIndUnit->fetch();
																																	$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																																	$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

																																	$query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE  site_id=:site_id AND task_id=:task_id AND subtask_id=:subtask_id ORDER BY id DESC LIMIT 1");
																																	$query_rsMonitoring_Achieved->execute(array(":site_id" => $site_id, ":task_id" => $msid, ":subtask_id" => $task_id));

																																	$Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
																																	$totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();
																																	$achieved = $totalRows_rsMonitoring_Achieved > 0 ? $Rows_rsMonitoring_Achieved['achieved'] : 0;
																																	$record_date = $totalRows_rsMonitoring_Achieved > 0 ? date("d M Y", strtotime($Rows_rsMonitoring_Achieved['created_at'])) : "";
																																	$rate = $units_no > 0 && $achieved > 0 ? $achieved / $units_no * 100 : 0;
																															?>
																																	<tr id="row<?= $tcounter ?>">
																																		<td style="width:5%"><?= $tcounter ?></td>
																																		<td style="width:40%"><?= $task_name ?></td>
																																		<td style="width:25%"><?= $unit_of_measure ?></td>
																																		<td style="width:25%"><?= $record_date ?></td>
																																		<td style="width:10%"><?= number_format($units_no, 2) ?></td>
																																		<td style="width:10%"><?= number_format($achieved, 2) ?></td>
																																		<td style="width:10%"><?= number_format($rate, 2) ?></td>
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
																				} else {
																					$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
																					$query_rsMilestone->execute(array(":output_id" => $output_id));
																					$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																					if ($totalRows_rsMilestone > 0) {
																						while ($row_rsMilestone = $query_rsMilestone->fetch()) {
																							$milestone = $row_rsMilestone['milestone'];
																							$msid = $row_rsMilestone['msid'];
																						?>
																							<div class="row clearfix">
																								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																									<div class="card-header">
																										<div class="row clearfix">
																											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																												<ul class="list-group">
																													<li class="list-group-item list-group-item list-group-item-action active">Task: <?= $milestone ?>
																														<div class="btn-group" style="float:right">
																															<div class="btn-group" style="float:right">
																															</div>
																														</div>
																													</li>
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
																													<th style="width:10%">Last Record Date</th>
																													<th style="width:10%">Target</th>
																													<th style="width:10%">Achieved</th>
																													<th style="width:10%">Rate (%)</th>
																												</tr>
																											</thead>
																											<tbody>
																												<?php
																												$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
																												$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
																												$totalRows_rsTasks = $query_rsTasks->rowCount();
																												$site_id = 0;
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
																														$units_no =  ($totalRows_rsTask_parameters > 0) ?  $row_rsTask_parameters['units_no'] : 0;

																														$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																														$query_rsIndUnit->execute(array(":unit_id" => $unit));
																														$row_rsIndUnit = $query_rsIndUnit->fetch();
																														$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																														$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

																														$query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE  site_id=:site_id AND task_id=:task_id AND subtask_id=:subtask_id ORDER BY id DESC LIMIT 1");
																														$query_rsMonitoring_Achieved->execute(array(":site_id" => $site_id, ":task_id" => $msid, ":subtask_id" => $task_id));

																														$Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
																														$totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();
																														$achieved = $totalRows_rsMonitoring_Achieved > 0 ? $Rows_rsMonitoring_Achieved['achieved'] : 0;
																														$record_date = $totalRows_rsMonitoring_Achieved > 0 ? date("d M Y", strtotime($Rows_rsMonitoring_Achieved['created_at'])) : "";
																														$rate = $units_no > 0 && $achieved > 0 ? $achieved / $units_no * 100 : 0;
																												?>
																														<tr id="row<?= $tcounter ?>">
																															<td style="width:5%"><?= $tcounter ?></td>
																															<td style="width:40%"><?= $task_name ?></td>
																															<td style="width:25%"><?= $unit_of_measure ?></td>
																															<td style="width:25%"><?= $record_date ?></td>
																															<td style="width:10%"><?= number_format($units_no, 2) ?></td>
																															<td style="width:10%"><?= number_format($achieved, 2) ?></td>
																															<td style="width:10%"><?= number_format($rate, 2) ?></td>
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
																				}
																				?>
																			</fieldset>
																	<?php
																		}
																	} else {
																		echo "Sorry Project Has no outputs";
																	}
																	?>
																</div>
															</fieldset>
														</div>
													</div>
												</div>
												<div id="menu1" class="tab-pane fade">
													<div class="body">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<fieldset class="scheduler-border">
																<legend class="scheduler-border bg-light-blue" style="border-radius:3px">
																	<i class="fa fa-list-ol" aria-hidden="true"></i> <strong> Project Output Results</strong>
																</legend>
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<table class="table table-bordered table-striped table-hover js-basic-example" id="direct_table<?= $outputid = 1 ?>">
																			<thead>
																				<tr>
																					<th style="width:5%"># </th>
																					<th style="width:35%">Output</th>
																					<th style="width:15%"><?= $level2label ?></th>
																					<th style="width:15%">Site</th>
																					<th style="width:10%">Target</th>
																					<th style="width:10%">Achieved</th>
																					<th style="width:10%">Rate (%)</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				//$projid = 1;
																				$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ORDER BY d.id");
																				$query_Output->execute(array(":projid" => $projid));
																				$total_Output = $query_Output->rowCount();
																				if ($total_Output > 0) {
																					$counter = 0;
																					while ($row_rsOutput = $query_Output->fetch()) {
																						$output_id = $row_rsOutput['id'];
																						$indicator_name = $row_rsOutput['indicator_name'];
																						$unitid = $row_rsOutput['indicator_unit'];
																						$target = $row_rsOutput['total_target'];
																						$indicator_mapping_type = $row_rsOutput['indicator_mapping_type'];
																						$unit_type = $row_rsOutput['unit_type'];


																						$query_indicator_unit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id = :unitid");
																						$query_indicator_unit->execute(array(":unitid" => $unitid));
																						$row_indicator_unit = $query_indicator_unit->fetch();
																						$indicatorunit = $row_indicator_unit['unit'];

																						if ($indicator_mapping_type == 1 || $indicator_mapping_type == 3) {
																							$query_rsSite_details = $db->prepare("SELECT * FROM tbl_output_disaggregation b INNER JOIN tbl_project_sites p ON p.site_id = b.output_site INNER JOIN tbl_state s ON s.id = p.state_id WHERE outputid = :outputid");
																							$query_rsSite_details->execute(array(":outputid" => $output_id));
																							$total_rsSite_details = $query_rsSite_details->rowCount();
																							if ($total_rsSite_details > 0) {
																								while ($rows_rsSite_details = $query_rsSite_details->fetch()) {
																									$counter++;
																									$site = $rows_rsSite_details['site'];
																									$state = $rows_rsSite_details['state'];
																									$site_id = $rows_rsSite_details['site_id'];
																									$target = $unit_type == 1 ? $rows_rsSite_details['total_target'] : 1;

																									$query_rsSite_cummulative = $db->prepare("SELECT SUM(achieved) as cummulative FROM tbl_monitoringoutput  WHERE site_id = :site_id AND output_id=:output_id AND record_type=1");
																									$query_rsSite_cummulative->execute(array(":site_id" => $site_id, ':output_id' => $output_id));
																									$rows_rsSite_cummulative = $query_rsSite_cummulative->fetch();
																									$cummulative = $rows_rsSite_cummulative['cummulative'] != "" ? $rows_rsSite_cummulative['cummulative'] : 0;
																									$rate = $target > 0 && $cummulative > 0 ? ($cummulative / $target) * 100 : 0;

																				?>
																									<tr>
																										<td><?= $counter ?></td>
																										<td><?= $indicator_name ?></td>
																										<td><?= $state ?></td>
																										<td><?= $site ?></td>
																										<td><?php echo number_format($target, 2) . ' ' . $indicatorunit ?></td>
																										<td><?php echo number_format($cummulative, 2) . ' ' . $indicatorunit ?></td>
																										<td><?= $rate ?></td>
																									</tr>
																								<?php

																								}
																							}
																						} else {
																							$query_rsSite_details = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
																							$query_rsSite_details->execute(array(":output_id" => $output_id));
																							$total_rsSite_details = $query_rsSite_details->rowCount();

																							if ($total_rsSite_details > 0) {
																								$mcounter = 0;
																								while ($rows_rsSite_details = $query_rsSite_details->fetch()) {
																									$state = $rows_rsSite_details['state'];
																									$state_id = $rows_rsSite_details['outputstate'];
																									$target = $rows_rsSite_details['total_target'];
																									$query_rsSite_cummulative = $db->prepare("SELECT SUM(achieved) as cummulative FROM tbl_monitoringoutput  WHERE site_id = :site_id AND output_id=:output_id AND record_type=1");
																									$query_rsSite_cummulative->execute(array(":site_id" => $site_id, ':output_id' => $output_id));
																									$rows_rsSite_cummulative = $query_rsSite_cummulative->fetch();
																									$cummulative = $rows_rsSite_cummulative['cummulative'] != "" ? $rows_rsSite_cummulative['cummulative'] : 0;
																									$rate = $target > 0 && $cummulative > 0 ? ($cummulative / $target) * 100 : 0;
																									$counter++;
																								?>
																									<tr>
																										<td><?= $counter ?></td>
																										<td><?= $indicator_name ?></td>
																										<td><?= $state ?></td>
																										<td><?= "N/A" ?></td>
																										<td><?php echo number_format($target, 2) . ' ' . $indicatorunit ?></td>
																										<td><?php echo number_format($cummulative, 2) . ' ' . $indicatorunit ?></td>
																										<td><?= number_format($rate, 2) ?></td>
																									</tr>
																				<?php
																								}
																							}
																						}
																					}
																				}
																				?>
																			</tbody>
																		</table>
																	</div>
																</div>
															</fieldset>
														</div>
													</div>
												</div>
											<?php
											}
											if ($projoutcome == 1) { ?>
												<div id="<?= $tab2 ?>" class="tab-pane fade <?= $inactive2 ?>">
													<div class="body">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<fieldset class="scheduler-border">
																<legend class="scheduler-border bg-light-green" style="border-radius:3px">
																	<i class="fa fa-list-ol" aria-hidden="true"></i> <strong> Project Outcome Results</strong>
																</legend>
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<table class="table table-bordered">
																			<thead>
																				<?php
																				$query_results = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE p.projid=:projid");
																				$query_results->execute(array(":projid" => $projid));

																				?>
																				<tr class="bg-light-green">
																					<th style="width:3%"># </th>
																					<th style="width:18%">Outcome</th>
																					<th style="width:19%">Indicator</th>
																					<th style="width:10%">Disaggregated</th>
																					<th style="width:10%">Location</th>
																					<th style="width:10%">Baseline</th>
																					<th style="width:10%">Endline</th>
																					<th style="width:10%">Change</th>
																					<th style="width:10%">Remarks</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				$nmb = 0;
																				while ($row_results = $query_results->fetch()) {
																					$nmb++;
																					$outcome = $row_results["outcome"];
																					$indid = $row_results["indid"];
																					$resultstypeid = $row_results["id"];

																					$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
																					$query_indicator->execute(array(":indid" => $indid));
																					$row_indicator = $query_indicator->fetch();

																					$indicator = $row_indicator["indicator_name"];
																					$unit = $row_indicator["unit"];
																					$calculation_method = $row_indicator["indicator_calculation_method"];
																					$disaggregated = $row_indicator["indicator_disaggregation"];
																					$indicator_disaggregated = $disaggregated == 1 ? "Yes" : "No";

																					$query_report_remarks = $db->prepare("SELECT comments FROM tbl_survey_conclusion WHERE projid=:projid and resultstype=2 and resultstypeid=:resultstypeid");
																					$query_report_remarks->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid));
																					$row_report_remarks = $query_report_remarks->fetch();
																					$remarks = $row_report_remarks["comments"];
																				?>

																					<tr class="bg-lime">
																						<td rowspan="<?php echo $proj_location_count + 1; ?>">
																							<?php echo $nmb ?>
																						</td>
																						<td rowspan="<?php echo $proj_location_count + 1; ?>">
																							<?php echo $outcome ?>
																						</td>
																						<td rowspan="<?php echo $proj_location_count + 1; ?>">
																							<?php
																							if ($disaggregated == 0) {
																								echo $indicator;
																							} else {
																								$resultsid = base64_encode("resultsid{$resultstypeid}");
																								$resulttype = base64_encode("resultstype2");
																								echo '<a href="project-mne-disaggregation-report?results=' . $resultsid . '&resultstype=' . $resulttype . '">' . $indicator . '</a>';
																							}
																							?>
																						</td>
																						<td rowspan="<?php echo $proj_location_count + 1; ?>">
																							<?php echo $indicator_disaggregated ?>
																						</td>
																					</tr>
																					<?php
																					foreach ($projlocations as $locations) {
																						$query_result = $db->prepare("SELECT c.numerator, c.denominator FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and resultstype=2 and resultstypeid=:resultstypeid and level3=:location");
																						$query_result->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));

																						$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																						$query_location->execute();
																						$row_location = $query_location->fetch();
																						$location = $row_location["state"];

																						$total_baseline_numerator = 0;
																						$total_baseline_denominator = 0;

																						while ($rows_result = $query_result->fetch()) {
																							$numerator = $rows_result["numerator"];
																							$denominator = $rows_result["denominator"];
																							$total_baseline_numerator += $numerator;
																							$total_baseline_denominator += $denominator;
																						}
																						//$baseline = '';
																						if ($calculation_method == 2) {
																							$baseline = number_format(($total_baseline_numerator / $total_baseline_denominator) * 100, 2);
																						} else {
																							$baseline = $total_baseline_numerator;
																						}

																						$query_endline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and resultstype=2 and resultstypeid=:resultstypeid and level3=:location");
																						$query_endline_survey->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));
																						$count_endline_surveys = $query_endline_survey->rowCount();

																						if ($count_endline_surveys == 0) {
																							$endline = 'Pending';
																							$difference = 'Pending';
																						} else {
																							$total_endline_numerator = 0;
																							$total_endline_denominator = 0;
																							while ($rows_endline_survey = $query_endline_survey->fetch()) {
																								$endnumerator = $rows_endline_survey["numerator"];
																								$enddenominator = $rows_endline_survey["denominator"];

																								$total_endline_numerator += $endnumerator;
																								$total_endline_denominator += $enddenominator;
																							}
																							if ($calculation_method == 2) {
																								$endline = number_format(($total_endline_numerator / $total_endline_denominator) * 100, 2);
																							} else {
																								$endline = $total_endline_numerator;
																							}
																							$difference = ($endline - $baseline);
																						}

																						$query_report_remarks = $db->prepare("SELECT comments FROM tbl_survey_conclusion WHERE projid=:projid and resultstype=2 and resultstypeid=:resultstypeid and level3=:location");
																						$query_report_remarks->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));
																						$unit = $calculation_method == 2 ? "%" : $unit;
																					?>
																						<tr class="bg-lime">
																							<td><?php echo $location; ?></td>
																							<td><?php echo $baseline . " " . $unit; ?></td>
																							<td><?php echo $endline;
																								if ($count_endline_surveys > 0) {
																									echo $unit;
																								} ?></td>
																							<td><?php echo $difference;
																								if ($count_endline_surveys > 0) {
																									echo $unit;
																								} ?></td>
																							<td>
																								<?php
																								while ($row_report_remarks = $query_report_remarks->fetch()) {
																									echo $row_report_remarks["comments"];
																								}
																								?>
																							</td>
																						</tr>
																				<?php
																					}
																				}
																				?>
																			</tbody>
																		</table>
																	</div>
																</div>
															</fieldset>
														</div>
													</div>
												</div>
												<?php if ($projimpact == 1) {
													$query_results = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_impact_details o on o.projid=p.projid WHERE p.projid=:projid");
													$query_results->execute(array(":projid" => $projid));
													$results_count = $query_results->rowCount();
													if ($results_count > 0) {
												?>
														<div id="menu3" class="tab-pane fade">
															<div class="body">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<fieldset class="scheduler-border">
																		<legend class="scheduler-border bg-green" style="border-radius:3px">
																			<i class="fa fa-list-ol" aria-hidden="true"></i> <strong> Project Impact Results</strong>
																		</legend>
																		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																			<div class="table-responsive">
																				<table class="table table-bordered" id="direct_table<?= $outputid ?>">
																					<thead>
																						<tr class="bg-green">
																							<th style="width:3%"># </th>
																							<th style="width:18%">Impact</th>
																							<th style="width:19%">Indicator</th>
																							<th style="width:10%">Disaggregated</th>
																							<th style="width:10%">Location</th>
																							<th style="width:10%">Baseline</th>
																							<th style="width:10%">Endline</th>
																							<th style="width:10%">Change</th>
																							<th style="width:10%">Remarks</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$nmb = 0;
																						while ($row_results = $query_results->fetch()) {
																							$nmb++;
																							$impact = $row_results["impact"];
																							$indid = $row_results["indid"];
																							$resultstypeid = $row_results["id"];

																							$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
																							$query_indicator->execute(array(":indid" => $indid));
																							$row_indicator = $query_indicator->fetch();

																							$indicator = $row_indicator["indicator_name"];
																							$unit = $row_indicator["unit"];
																							$calculation_method = $row_indicator["indicator_calculation_method"];
																							$disaggregated = $row_indicator["indicator_disaggregation"];
																							$indicator_disaggregated = $disaggregated == 1 ? "Yes" : "No";
																						?>

																							<tr class="bg-lime">
																								<td rowspan="<?php echo $proj_location_count + 1; ?>">
																									<?php echo $nmb ?>
																								</td>
																								<td rowspan="<?php echo $proj_location_count + 1; ?>">
																									<?php echo $impact ?>
																								</td>
																								<td rowspan="<?php echo $proj_location_count + 1; ?>">
																									<?php
																									if ($disaggregated == 0) {
																										echo $indicator;
																									} else {
																										$resultsid = base64_encode("resultsid{$resultstypeid}");
																										$resulttype = base64_encode("resultstype1");
																										echo '<a href="project-mne-disaggregation-report?results=' . $resultsid . '&resultstype=' . $resulttype . '">' . $indicator . '</a>';
																									}
																									?>
																								</td>
																								<td rowspan="<?php echo $proj_location_count + 1; ?>">
																									<?php echo $indicator_disaggregated ?>
																								</td>
																							</tr>
																							<?php
																							foreach ($projlocations as $locations) {
																								$query_result = $db->prepare("SELECT c.numerator, c.denominator FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and resultstype=1 and resultstypeid=:resultstypeid and level3=:location");
																								$query_result->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));


																								$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																								$query_location->execute();
																								$row_location = $query_location->fetch();
																								$location = $row_location["state"];

																								$total_baseline_numerator = 0;
																								$total_baseline_denominator = 0;

																								while ($rows_result = $query_result->fetch()) {
																									$numerator = $rows_result["numerator"];
																									$denominator = $rows_result["denominator"];
																									$total_baseline_numerator += $numerator;
																									$total_baseline_denominator += $denominator;
																								}
																								//$baseline = '';
																								if ($calculation_method == 2) {
																									$baseline = $total_baseline_numerator > 0 && $total_baseline_denominator > 0 ? number_format(($total_baseline_numerator / $total_baseline_denominator) * 100, 2) : 0;
																								} else {
																									$baseline = $total_baseline_numerator;
																								}

																								$query_endline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and resultstype=1 and resultstypeid=:resultstypeid and level3=:location");
																								$query_endline_survey->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));
																								$count_endline_surveys = $query_endline_survey->rowCount();

																								if ($count_endline_surveys == 0) {
																									$endline = 'Pending';
																									$difference = 'Pending';
																								} else {
																									$total_endline_numerator = 0;
																									$total_endline_denominator = 0;
																									while ($rows_endline_survey = $query_endline_survey->fetch()) {
																										$endnumerator = $rows_endline_survey["numerator"];
																										$enddenominator = $rows_endline_survey["denominator"];

																										$total_endline_numerator += $endnumerator;
																										$total_endline_denominator += $enddenominator;
																									}
																									if ($calculation_method == 2) {
																										$endline = number_format(($total_endline_numerator / $total_endline_denominator) * 100, 2);
																									} else {
																										$endline = $total_endline_numerator;
																									}
																									$difference = ($endline - $baseline);
																								}

																								$query_report_remarks = $db->prepare("SELECT comments FROM tbl_survey_conclusion WHERE projid=:projid and resultstype=1 and resultstypeid=:resultstypeid and level3=:location");
																								$query_report_remarks->execute(array(":projid" => $projid, ":resultstypeid" => $resultstypeid, ":location" => $locations));
																								$unit = $calculation_method == 2 ? "%" : $unit;
																							?>
																								<tr class="bg-lime">
																									<td><?php echo $location; ?></td>
																									<td><?php echo $baseline . " " . $unit; ?></td>
																									<td><?php echo $endline;
																										if ($count_endline_surveys > 0) {
																											echo $unit;
																										} ?></td>
																									<td><?php echo $difference;
																										if ($count_endline_surveys > 0) {
																											echo $unit;
																										} ?></td>
																									<td>
																										<?php
																										while ($row_report_remarks = $query_report_remarks->fetch()) {
																											echo $row_report_remarks["comments"];
																										}
																										?>
																									</td>
																								</tr>
																						<?php
																							}
																						}
																						?>
																					</tbody>
																				</table>
																			</div>
																		</div>
																	</fieldset>
																</div>
															</div>
														</div>
											<?php
													}
												}
											} ?>
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
					?>