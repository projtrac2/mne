<?php
try {
	require('includes/head.php');
	if ($permission && (isset($_GET['proj']) && !empty($_GET["proj"]))) {
		$decode_projid = base64_decode($_GET['proj']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];
		$original_projid = $_GET['proj'];

		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = :projid");
		$query_rsMyP->execute(array(":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();

		if ($row_rsMyP) {
			$implimentation_type = $row_rsMyP["projcategory"];
			$projname = $row_rsMyP['projname'];
			$administrative_cost = $row_rsMyP['administrative_cost'];
			$percent2 = number_format(calculate_project_progress($projid, $implimentation_type), 2);

			$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget, o.total_target FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
			$query_rsOutputs->execute(array(":projid" => $projid));
			$row_rsOutputs = $query_rsOutputs->fetch();
			$totalRows_rsOutputs = $query_rsOutputs->rowCount();

			function get_consumed($output_id, $site_id)
			{
				global $db, $projid;
				$consumed_amount = 0;
				$query_consumed =  $db->prepare("SELECT SUM(s.no_of_units * s.unit_cost) as consumed  FROM tbl_payments_request r INNER JOIN tbl_payments_request_details s ON s.request_id=r.id  INNER JOIN tbl_project_direct_cost_plan d ON s.direct_cost_id=d.id   WHERE status=3 AND r.projid = :projid AND outputid=:output_id AND site_id=:site_id");
				$query_consumed->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
				$row_consumed = $query_consumed->fetch();
				$consumed_amount = $row_consumed['consumed'] != null ? $row_consumed['consumed'] : 0;
				return $consumed_amount;
			}

			function get_planned_amount($output_id, $site_id)
			{
				global $db, $projid, $implimentation_type;
				$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id");
				if ($implimentation_type == 2) {
					$query_rsDirect_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as amount FROM tbl_project_tender_details WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id");
				}
				$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
				$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
				$planed_amount =  !is_null($row_rsDirect_cost_plan['amount']) ? $row_rsDirect_cost_plan['amount'] : 0;
				return $planed_amount;
			}
?>
			<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
			<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
			<section class="content">
				<div class="container-fluid">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<?= $icon . ' ' . $pageTitle ?>
							<div class="btn-group" style="float:right; margin-right:10px">
								<input type="button" VALUE="Go Back to Activities Monitoring" class="btn btn-warning pull-right" onclick="location.href='project-output-monitoring-checklist.php'" id="btnback">
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
																														<th style="width:50%">Item </th>
																														<th style="width:25%">Budget (Ksh)</th>
																														<th style="width:10%">Expense (Ksh)</th>
																														<th style="width:10%">Rate (%)</th>
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
																															$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																															$units_no = $row_rsOther_cost_plan['units_no'];
																															$total_cost = $unit_cost * $units_no;

																															$expense = 0;
																															$rate = $total > 0 && $expense > 0 ? $expense / $total * 100 : 0;
																													?>
																															<tr id="row">
																																<td style="width:5%"><?= $table_counter ?></td>
																																<td style="width:50%"><?= $description ?></td>
																																<td style="width:25%"><?= number_format($total_cost, 2) ?></td>
																																<td style="width:10%"><?= number_format($expense, 2) ?></td>
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
																											<th style="width:50%">Item </th>
																											<th style="width:25%">Budget (Ksh)</th>
																											<th style="width:10%">Expense (Ksh)</th>
																											<th style="width:10%">Rate (%)</th>
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

																												$expense = 0;
																												$rate = $total_cost > 0 && $expense > 0 ? $expense / $total_cost * 100 : 0;
																										?>
																												<tr id="row">
																													<td style="width:5%"><?= $table_counter ?></td>
																													<td style="width:50%"><?= $description ?></td>
																													<td style="width:25%"><?= number_format($total_cost, 2) ?></td>
																													<td style="width:10%"><?= number_format($expense, 2) ?></td>
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
																				<th style="width:50%">Item </th>
																				<th style="width:25%">Budget (Ksh)</th>
																				<th style="width:10%">Expense (Ksh)</th>
																				<th style="width:10%">Rate (%)</th>
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
																					$expense = 0;
																					$rate = $total_cost > 0 && $expense > 0 ? $expense / $total_cost * 100 : 0;
																			?>
																					<tr id="row">
																						<td style="width:5%"><?= $table_counter ?></td>
																						<td style="width:50%"><?= $description ?></td>
																						<td style="width:25%"><?= number_format($total_cost, 2) ?></td>
																						<td style="width:10%"><?= number_format($expense, 2) ?></td>
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
														<?= csrf_token_html(); ?>
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
																												<th style="width:30%">Item</th>
																												<th style="width:15%">Target</th>
																												<th style="width:20%">Achieved</th>
																												<th style="width:10%">Status</th>
																												<th style="width:15%">%&nbsp;&nbsp;Achieved</th>
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

																														$query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND subtask_id=:subtask_id ");
																														$query_rsProgramOfWorks->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
																														$row_rsProgramOfWorks = $query_rsProgramOfWorks->fetch();

																														if ($row_rsProgramOfWorks > 0) {
																															$subtask_status = $row_rsProgramOfWorks['status'];
																															$complete = $row_rsProgramOfWorks['complete'];

																															$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
																															$query_Projstatus->execute(array(":projstatus" => $subtask_status));
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

													$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
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
																						<th style="width:10%">%&nbsp;&nbsp;Achieved</th>
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

																								$query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND subtask_id=:subtask_id ");
																								$query_rsProgramOfWorks->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
																								$row_rsProgramOfWorks = $query_rsProgramOfWorks->fetch();

																								if ($row_rsProgramOfWorks > 0) {
																									$subtask_status = $row_rsProgramOfWorks['status'];
																									$complete = $row_rsProgramOfWorks['complete'];




																									$subtask_progress = '
																							<div class="progress" style="height:20px; font-size:10px; color:black">
																								<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																									' . $progress . '%
																								</div>
																							</div>';

																									if ($progress == 100 || $complete == 1) {
																										$subtask_progress = '
																							<div class="progress" style="height:20px; font-size:10px; color:black">
																								<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
																								' . $progress . '%
																								</div>
																							</div>';
																									}

																									$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
																									$query_Projstatus->execute(array(":projstatus" => $subtask_status));
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
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
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