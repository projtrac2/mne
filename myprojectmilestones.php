<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
if ($permission) {
	try {
		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$implimentation_type = $row_rsMyP["projcategory"];
		$projname = $row_rsMyP['projname'];
		$percent2 = number_format(calculate_project_progress($projid, $implimentation_type), 2);

		$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget, o.total_target FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
		$query_rsOutputs->execute(array(":projid" => $projid));
		$row_rsOutputs = $query_rsOutputs->fetch();
		$totalRows_rsOutputs = $query_rsOutputs->rowCount();
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
						<input type="button" VALUE="Go Back to Projects Activity Monitoring" class="btn btn-warning pull-right" onclick="location.href='project-output-monitoring-checklist.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
								<a href="myprojectdash.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Dashboard</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Performance</a>
								<a href="myproject-key-stakeholders.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team</a>
								<a href="my-project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues</a>
								<a href="myprojectfiles.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Media</a>
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
									<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Finance &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Progress &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="row clearfix">
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
													<fieldset class="scheduler-border row setup-content" id="step-2">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output <?= $counter ?>: <?= $indicator_name ?></legend>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																	<thead>
																		<tr class="bg-grey">
																			<th style="width:5%" align="center">#</th>
																			<th style="width:40%">Site/Location</th>
																			<th style="width:20%">Budget</th>
																			<th style="width:20%">Expense</th>
																			<th style="width:15%">Spent (%)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		if ($indicator_mapping_type == 1) {
																			$query_rsSite_details = $db->prepare("SELECT * FROM tbl_output_disaggregation b INNER JOIN tbl_project_sites s ON s.site_id = b.output_site WHERE outputid = :output_id");
																			$query_rsSite_details->execute(array(":output_id" => $output_id));
																			$total_rsSite_details = $query_rsSite_details->rowCount();
																			if ($total_rsSite_details > 0) {
																				$mcounter = 0;
																				while ($rows_rsSite_details = $query_rsSite_details->fetch()) {
																					$site = $rows_rsSite_details['site'];
																					$site_id = $rows_rsSite_details['site_id'];

																					$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id");
																					if ($implimentation_type == 2) {
																						$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_tender_details WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id");
																					}
																					$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
																					$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																					$planned_amount = !is_null($row_rsDirect_cost_plan['amount']) ? $row_rsDirect_cost_plan['amount'] : 0;

																					$query_consumed =  $db->prepare("SELECT SUM(amount_requested) AS consumed FROM tbl_payments_request WHERE status=3 AND projid = :projid AND output_id=:output_id and site_id=:site_id");
																					$query_consumed->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
																					$row_consumed = $query_consumed->fetch();
																					$consumed = !is_null($row_consumed['consumed']) ? $row_consumed["consumed"] : 0;
																					$rate  = $consumed > 0 && $planned_amount > 0 ? ($consumed / $planned_amount) * 100 : 0;
																					$mcounter++;
																		?>
																					<tr style="background-color:#FFFFFF">
																						<td align="center"><?= $mcounter ?></td>
																						<td><?= $site ?></td>
																						<td><?= number_format($planned_amount, 2) ?></td>
																						<td><?= number_format($consumed, 2) ?></td>
																						<td><?= number_format($rate, 2) ?></td>
																					</tr>
																			<?php
																				}
																			}
																		} else {
																			$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id");
																			if ($implimentation_type == 2) {
																				$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_tender_details WHERE projid =:projid AND outputid=:output_id");
																			}
																			$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":output_id" => $output_id));
																			$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																			$planned_amount = !is_null($row_rsDirect_cost_plan['amount']) ? $row_rsDirect_cost_plan['amount'] : 0;

																			$query_consumed =  $db->prepare("SELECT SUM(amount_requested) AS consumed FROM tbl_payments_request WHERE status=3 AND projid = :projid AND output_id=:output_id");
																			$query_consumed->execute(array(":projid" => $projid, ":output_id" => $output_id));
																			$row_consumed = $query_consumed->fetch();
																			$consumed = !is_null($row_consumed['consumed']) ? $row_consumed["consumed"] : 0;
																			$rate  = $consumed > 0 && $planned_amount ? ($consumed / $planned_amount) * 100 : 0;

																			$query_Output_states = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
																			$query_Output_states->execute(array(":output_id" => $output_id));
																			$total_Output_states = $query_Output_states->rowCount();
																			$site_name = [];
																			if ($total_Output_states > 0) {
																				while ($row_rsOutput_states = $query_Output_states->fetch()) {
																					$site_name[] = $row_rsOutput_states['state'];
																				}
																			}

																			$states = implode(",", $site_name);
																			?>
																			<tr style="background-color:#FFFFFF">
																				<td align="center"><?= 1 ?></td>
																				<td><?= $states ?></td>
																				<td><?= number_format($planned_amount, 2) ?></td>
																				<td><?= number_format($consumed, 2) ?></td>
																				<td><?= number_format($rate, 2) ?></td>
																			</tr>
																		<?php
																		}
																		?>
																	</tbody>
																</table>
															</div>
														</div>
													</fieldset>
											<?php
												}
											} else {
												echo "Sorry Project Has no outputs";
											}
											?>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="header">
										<h2>
											USE BELOW SELECTION TO FILTER THE RECORDS BY DATE RANGE
										</h2>
										<div class="row clearfix">
											<form id="searchform" name="searchform" method="get" style="margin-top:10px" action="">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<label class="control-label">From *:</label>
													<input type="date" name="start_date" id="start_date" class="form-control" onchange="get_records(<?= $projid ?>, <?= $implimentation_type ?>)">
												</div>
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<label class="control-label">To *:</label>
													<input type="date" name="end_date" id="end_date" class="form-control" onchange="get_records(<?= $projid ?>, <?= $implimentation_type ?>)">
												</div>
											</form>
										</div>
									</div>
									<div class="row clearfix" id="filter_data">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<?php
											$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
											$query_Sites->execute(array(":projid" => $projid));
											$rows_sites = $query_Sites->rowCount();
											if ($rows_sites > 0) {
												$counter = 0;
												while ($row_Sites = $query_Sites->fetch()) {
													$site_id = $row_Sites['site_id'];
													$site = $row_Sites['site'];
													$query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id");
													$query_Site_score->execute(array(":site_id" => $site_id));
													$rows_site_score = $query_Site_score->rowCount();
													if ($rows_site_score > 0) {
														$counter++;
														$progress = number_format(calculate_site_progress($implimentation_type, $site_id), 2);

														$site_progress = '
														<div class="progress" style="height:20px; font-size:10px; color:black">
															<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																' . $progress . '%
															</div>
														</div>';

														if ($progress == 100) {
															$site_progress = '
															<div class="progress" style="height:20px; font-size:10px; color:black">
																<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																' . $progress . '%
																</div>
															</div>';
														}
											?>
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> :
															</legend>
															<div class="card-header">
																<div class="row clearfix">
																	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																		<ul class="list-group">
																			<li class="list-group-item list-group-item list-group-item-action active">Site : <?= $site ?></li>
																			<li class="list-group-item">Progress : <?= $site_progress ?></li>
																		</ul>
																	</div>
																</div>
															</div>
															<?php
															$query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
															$query_Site_Output->execute(array(":site_id" => $site_id));
															$rows_Site_Output = $query_Site_Output->rowCount();
															if ($rows_Site_Output > 0) {
																$output_counter = 0;
																while ($row_Site_Output = $query_Site_Output->fetch()) {
																	$output_counter++;
																	$output_id = $row_Site_Output['outputid'];
																	$query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
																	$query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
																	$rows_output_score = $query_output_score->rowCount();
																	if ($rows_output_score > 0) {
																		$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
																		$query_Output->execute(array(":outputid" => $output_id));
																		$row_Output = $query_Output->fetch();
																		$total_Output = $query_Output->rowCount();
																		if ($total_Output) {
																			$output_id = $row_Output['id'];
																			$output = $row_Output['indicator_name'];
																			$progress = number_format(calculate_output_site_progress($output_id, $implimentation_type, $site_id), 2);
																			$output_progress = '
																			<div class="progress" style="height:20px; font-size:10px; color:black">
																				<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																					' . $progress . '%
																				</div>
																			</div>';

																			if ($progress == 100) {
																				$output_progress = '
																				<div class="progress" style="height:20px; font-size:10px; color:black">
																					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																					' . $progress . '%
																					</div>
																				</div>';
																			}
																			$query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
																			$query_rsTargetUsed->execute(array(":output_id" => $output_id));
																			$Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
																			$output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
															?>
																			<fieldset class="scheduler-border">
																				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																					<i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> :
																				</legend>
																				<div class="row clearfix">
																					<div class="card-header">
																						<div class="row clearfix">
																							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																								<ul class="list-group">
																									<li class="list-group-item list-group-item list-group-item-action active">Output : <?= $output ?></li>
																									<li class="list-group-item">Achieved : <?= number_format($output_achieved, 2) ?></li>
																									<li class="list-group-item">Progress : <?= $output_progress ?></li>
																								</ul>
																							</div>
																						</div>
																					</div>
																					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																						<div class="table-responsive">
																							<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
																								<thead>
																									<tr>
																										<th style="width:5%">#</th>
																										<th style="width:35%">Item</th>
																										<th style="width:15%">Target</th>
																										<th style="width:20%">Achieved</th>
																										<th style="width:10%">Status</th>
																										<th style="width:10%">Progress</th>
																									</tr>
																								</thead>
																								<tbody>
																									<?php
																									$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
																									$query_rsTasks->execute(array(":output_id" => $output_id));
																									$totalRows_rsTasks = $query_rsTasks->rowCount();
																									if ($totalRows_rsTasks > 0) {
																										$tcounter = 0;
																										while ($row_rsTasks = $query_rsTasks->fetch()) {
																											$task_name = $row_rsTasks['task'];
																											$task_id = $row_rsTasks['tkid'];
																											$unit =  $row_rsTasks['unit_of_measure'];

																											$query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
																											$query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
																											$rows_site_score = $query_Site_score->rowCount();
																											$row_site_score = $query_Site_score->fetch();
																											if ($row_site_score['achieved'] != null) {
																												$units_no =  $row_site_score['achieved'];
																												$tcounter++;
																												$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																												$query_rsIndUnit->execute(array(":unit_id" => $unit));
																												$row_rsIndUnit = $query_rsIndUnit->fetch();
																												$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																												$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

																												$query_rsOther_cost_plan_budget =  $db->prepare("SELECT units_no FROM tbl_project_direct_cost_plan WHERE site_id=:site_id AND subtask_id=:subtask_id");
																												$query_rsOther_cost_plan_budget->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
																												$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
																												$target_units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
																												$progress = number_format(($units_no / $target_units) * 100);

																												$subtask_progress = '
																												<div class="progress" style="height:20px; font-size:10px; color:black">
																													<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																														' . $progress . '%
																													</div>
																												</div>';

																												if ($progress == 100) {
																													$subtask_progress = '
																												<div class="progress" style="height:20px; font-size:10px; color:black">
																													<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																													' . $progress . '%
																													</div>
																												</div>';
																												}

																												$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
																												$query_Projstatus->execute(array(":projstatus" => 11));
																												$row_Projstatus = $query_Projstatus->fetch();
																												$total_Projstatus = $query_Projstatus->rowCount();
																												$status = "";
																												if ($total_Projstatus > 0) {
																													$status_name = $row_Projstatus['statusname'];
																													$status_class = $row_Projstatus['class_name'];
																													$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
																												}
																									?>
																												<tr id="row<?= $tcounter ?>">
																													<td style="width:5%"><?= $tcounter ?></td>
																													<td style="width:35%"><?= $task_name ?></td>
																													<td style="width:15%"><?= number_format($target_units, 2) . " " . $unit_of_measure  ?></td>
																													<td style="width:20%"><?= number_format($units_no, 2) . " " . $unit_of_measure ?></td>
																													<td style="width:10%"><?= $status ?></td>
																													<td style="width:10%"><?= $subtask_progress ?></td>
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
																			</fieldset>
															<?php
																		}
																	}
																}
															}
															?>
														</fieldset>
														<?php
													}
												}
											}

											$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type=2 AND projid = :projid");
											$query_Output->execute(array(":projid" => $projid));
											$total_Output = $query_Output->rowCount();
											$outputs = '';
											if ($total_Output > 0) {
												$outputs = '';
												if ($total_Output > 0) {
													$counter = 0;
													$site_id = 0;
													while ($row_rsOutput = $query_Output->fetch()) {
														$output_id = $row_rsOutput['id'];
														$output = $row_rsOutput['indicator_name'];
														$progress = number_format(calculate_output_site_progress($output_id, $implimentation_type, $site_id), 2);
														$query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
														$query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
														$rows_output_score = $query_output_score->rowCount();
														if ($rows_output_score > 0) {
															$counter++;
															$output_progress = '
															<div class="progress" style="height:20px; font-size:10px; color:black">
																<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																	' . $progress . '%
																</div>
															</div>';

															if ($progress == 100) {
																$output_progress = '
																<div class="progress" style="height:20px; font-size:10px; color:black">
																	<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																	' . $progress . '%
																	</div>
																</div>';
															}

															$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
															$query_Projstatus->execute(array(":projstatus" => 11));
															$row_Projstatus = $query_Projstatus->fetch();
															$total_Projstatus = $query_Projstatus->rowCount();
															$status = "";
															if ($total_Projstatus > 0) {
																$status_name = $row_Projstatus['statusname'];
																$status_class = $row_Projstatus['class_name'];
																$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
															}

															$query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
															$query_rsTargetUsed->execute(array(":output_id" => $output_id));
															$Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
															$output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
														?>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																	<i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
																</legend>

																<div class="card-header">
																	<div class="row clearfix">
																		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																			<ul class="list-group">
																				<li class="list-group-item list-group-item list-group-item-action active">Output : <?= $output ?></li>
																				<li class="list-group-item">Achieved : <?= number_format($output_achieved, 2) ?></li>
																				<li class="list-group-item">Progress : <?= $output_progress ?></li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
																		<thead>
																			<tr>
																				<th style="width:5%">#</th>
																				<th style="width:35%">Item</th>
																				<th style="width:15%">Target</th>
																				<th style="width:20%">Achieved</th>
																				<th style="width:10%">Status</th>
																				<th style="width:10%">Progress</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
																			$query_rsTasks->execute(array(":output_id" => $output_id));
																			$totalRows_rsTasks = $query_rsTasks->rowCount();
																			if ($totalRows_rsTasks > 0) {
																				$tcounter = 0;
																				while ($row_rsTasks = $query_rsTasks->fetch()) {
																					$task_name = $row_rsTasks['task'];
																					$task_id = $row_rsTasks['tkid'];
																					$unit =  $row_rsTasks['unit_of_measure'];

																					$query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
																					$query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
																					$rows_site_score = $query_Site_score->rowCount();
																					$row_site_score = $query_Site_score->fetch();
																					if ($row_site_score['achieved'] != null) {
																						$units_no =  $row_site_score['achieved'];
																						$tcounter++;
																						$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
																						$query_rsIndUnit->execute(array(":unit_id" => $unit));
																						$row_rsIndUnit = $query_rsIndUnit->fetch();
																						$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
																						$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

																						$query_rsOther_cost_plan_budget =  $db->prepare("SELECT units_no FROM tbl_project_direct_cost_plan WHERE site_id=:site_id AND subtask_id=:subtask_id");
																						$query_rsOther_cost_plan_budget->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
																						$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
																						$target_units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
																						$progress = number_format(($units_no / $target_units) * 100);

																						$query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND subtask_id=:subtask_id AND complete=1");
																						$query_rsProgramOfWorks->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
																						$row_rsProgramOfWorks = $query_rsProgramOfWorks->fetch();

																						$subtask_progress = '
																						<div class="progress" style="height:20px; font-size:10px; color:black">
																							<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																								' . $progress . '%
																							</div>
																						</div>';

																						if ($progress == 100 || $row_rsProgramOfWorks) {
																							$subtask_progress = '
																							<div class="progress" style="height:20px; font-size:10px; color:black">
																								<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																								' . $progress . '%
																								</div>
																							</div>';
																						}

																						$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
																						$query_Projstatus->execute(array(":projstatus" => 11));
																						$row_Projstatus = $query_Projstatus->fetch();
																						$total_Projstatus = $query_Projstatus->rowCount();
																						$status = "";
																						if ($total_Projstatus > 0) {
																							$status_name = $row_Projstatus['statusname'];
																							$status_class = $row_Projstatus['class_name'];
																							$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
																						}
																			?>
																						<tr id="row<?= $tcounter ?>">
																							<td style="width:5%"><?= $tcounter ?></td>
																							<td style="width:35%"><?= $task_name ?></td>
																							<td style="width:15%"><?= number_format($target_units, 2) . " " . $unit_of_measure  ?></td>
																							<td style="width:20%"><?= number_format($units_no, 2) . " " . $unit_of_measure ?></td>
																							<td style="width:10%"><?= $status ?></td>
																							<td style="width:10%"><?= $subtask_progress ?></td>
																						</tr>
																			<?php
																					}
																				}
																			}
																			?>
																		</tbody>
																	</table>
																</div>
															</fieldset>
											<?php
														}
													}
												}
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
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script>
	const ajax_url = "ajax/monitoring/checklist-report";

	function get_records(projid, implimentation_type) {
		if (projid) {
			let start_date = $("#start_date").val();
			let end_date = $("#end_date").val();
			$.ajax({
				type: "get",
				url: ajax_url,
				data: {
					get_filter_record: "get_filter_record",
					projid: projid,
					implimentation_type: implimentation_type,
					start_date: start_date,
					end_date: end_date,
				},
				dataType: "json",
				success: function(response) {
					if (response.success) {
						$("#filter_data").html(response.data);
					} else {
						console.log("Error no record found")
					}
				}
			});
		}
	}
</script>