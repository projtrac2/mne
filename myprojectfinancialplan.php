<?php
try {
	require('includes/head.php');
	if ($permission &&  (isset($_GET['projid']) && !empty($_GET["projid"]))) {
		$decode_projid = base64_decode($_GET['projid']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];
		$original_projid = $_GET['projid'];

		$query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projplanstatus='1' and projid=:projid ");
		$query_rsProjects->execute(array(":projid" => $projid));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();

		if ($totalRows_rsProjects > 0) {
			$implimentation_type = $row_rsProjects['projcategory'];
			$projname = $row_rsProjects['projname'];
			$projcode = $row_rsProjects['projcode'];
			$projcost = $row_rsProjects['projcost'];
			$projfscyear = $row_rsProjects['projfscyear'];
			$projduration = $row_rsProjects['projduration'];
			$mne_cost = $row_rsProjects['mne_budget'];
			$implementation_cost = $projcost - $mne_cost;
			$progid = $row_rsProjects['progid'];
			$projstartdate = $row_rsProjects['projstartdate'];
			$projenddate = $row_rsProjects['projenddate'];
			$projcategory = $row_rsProjects['projcategory'];
			$projmapping = $row_rsProjects['projmapping'];
			$projevaluation = $row_rsProjects['projevaluation'];
			$projtenderid = $row_rsProjects['projtender'];
			$percent2 = $row_rsProjects['progress'];


			$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
			$query_rsMlsProg->execute(array(":projid" => $projid));
			$row_rsMlsProg = $query_rsMlsProg->fetch();
			include_once('projects-functions.php');

			$query_rsProjBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid = :projid");
			$query_rsProjBudget->execute(array(":projid" => $projid));
			$row_rsProjBudget = $query_rsProjBudget->fetch();

			$total_direct_cost = $total_direct_cost_percentage = $total_administrative_cost  = $total_administrative_cost_percentage = 0;
			$summary = "";
?>
			<!-- start body  -->
			<!-- JQuery Nestable Css -->
			<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
			<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
			<section class="content">
				<div class="container-fluid">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<?= $icon ?>
							<?= $pageTitle ?>
							<div class="btn-group" style="float:right">
								<div class="btn-group" style="float:right">
								</div>
							</div>
						</h4>
					</div>
					<div class="row clearfix">
						<div class="block-header">
							<?= $results; ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="header" style="padding-bottom:0px">
									<div class="button-demo" style="margin-top:-15px">
										<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
										<a href="myprojectdash.php?projid=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Dashboard</a>
										<a href="myprojectmilestones.php?projid=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Performance</a>
										<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
										<a href="myproject-key-stakeholders.php?projid=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
										<a href="my-project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues</a>
										<a href="myprojectfiles.php?projid=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
									</div>
								</div>
								<h4>
									<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
										Project Name: <font color="white"><?php echo $projname; ?></font>
									</div>
									<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
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
								<div class="body">
									<ul class="nav nav-tabs" style="font-size:14px">
										<li class="active">
											<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> INDICATIVE FINANCIAL DETAILS &nbsp;<span class="badge bg-orange">|</span></a>
										</li>
										<?php
										if ($projcategory == 2) {
										?>
											<li>
												<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> ACTUAL FINANCIAL DETAILS &nbsp;<span class="badge bg-blue">|</span></a>
											</li>
										<?php } ?>
									</ul>
								</div>
								<div class="body">
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
											<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
													<i class="fa fa-university" aria-hidden="true"></i>Details
												</legend>
												<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project Code:</label>
													<div class="form-line">
														<input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
													</div>
												</div>
												<div class="col-md-9 clearfix" style="margin-top:5px; margin-bottom:5px">
													<input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>" readonly>
													<label class="control-label">Project Name:</label>
													<div class="form-line">
														<input type="hidden" class="form-control" value=" <?= $row_rsProjBudget['budget'] ?>" id="project_cost">
														<input type="text" class="form-control" value=" <?= $projname ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Total Project Cost:</label>
													<div class="form-line">
														<input type="text" class="form-control" value="Ksh. <?php echo number_format($row_rsProjBudget["budget"], 2); ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project Implementation Cost:</label>
													<div class="form-line">
														<input type="hidden" class="form-control" id="implementation_budget" value="<?= $implementation_cost ?>">
														<input type="text" class="form-control" value="<?= number_format($implementation_cost, 2) ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project M&E Cost :</label>
													<div class="form-line">
														<input type="text" class="form-control" value=" <?= number_format($mne_cost, 2) ?>" readonly>
													</div>
												</div>

												<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
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

												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
														<i class="fa fa-list-ol" aria-hidden="true"></i> 1.0 Direct Project Cost
													</legend>
													<?php

													$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
													$query_rsOutputs->execute(array(":projid" => $projid));
													$row_rsOutputs = $query_rsOutputs->fetch();
													$totalRows_rsOutputs = $query_rsOutputs->rowCount();
													$direct_cost = 0;
													if ($totalRows_rsOutputs > 0) {
														$Ocounter = 0;
														do {
															$Ocounter++;
															$outputName = $row_rsOutputs['output'];
															$outputCost = $row_rsOutputs['budget'];
															$outputid = $row_rsOutputs['opid'];
															$output_cost_val[] = $outputid;
															$output_remeinder = 0;

															$query_rs_output_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as budget FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:outputid ");
															$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":outputid" => $outputid));
															$row_rs_output_cost_plan = $query_rs_output_cost_plan->fetch();
															$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
															$output_cost = $row_rs_output_cost_plan ? $row_rs_output_cost_plan['budget'] : 0;
															$total_direct_cost += $output_cost;

													?>
															<div class="panel panel-primary">
																<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
																	<i class="fa fa-caret-down" aria-hidden="true"></i>
																	<strong> Output <?= $Ocounter ?>:<span class=""><?= $outputName ?> </span> </strong> <?= number_format($output_cost) ?>
																</div>
																<div class="collapse output<?php echo $outputid ?>" style="padding:5px">

																	<?php
																	$query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs WHERE output_id=:output_id ORDER BY id");
																	$query_rsDesigns->execute(array(":output_id" => $outputid));
																	$row_rsDesigns = $query_rsDesigns->fetch();
																	$totalRows_rsDesigns = $query_rsDesigns->rowCount();

																	if ($totalRows_rsDesigns > 0) {
																		$design_counter = 0;
																		do {
																			$design_counter++;
																			$design_id = $row_rsDesigns['id'];
																			$design_name = $row_rsDesigns['design'];
																			$design_sites = $row_rsDesigns['sites'];
																			$sites = explode(",", $design_sites);
																			$total_sites = count($sites) > 0 ? count($sites) : 1;
																			$site_type = count($sites) > 0 ? 2 : 1;
																			$site_counter = 0;
																			for ($i = 0; $i < $total_sites; $i++) {
																				$sub_total_percentage = $sub_total_amount = 0;
																				$site_counter++;
																				$site_name = "";
																				$site_id = $sites[$i];
																				if ($site_type == 2) {
																					$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id = :id ");
																					$query_Sites->execute(array(":id" => $site_id));
																					$row_rsSites = $query_Sites->fetch();
																					$total_Sites1 = $query_Sites->rowCount();
																					$site_name = $total_Sites1 > 0 ? $row_rsSites['site'] : "";
																				}
																	?>
																				<fieldset class="scheduler-border">
																					<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																						<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $site_type == 1 ? "Direct Cost" : "Site" ?> <?= $site_counter ?>. <?= $site_name ?>
																					</legend>
																					<div class="table-responsive">
																						<table class="table table-bordered" id="direct_table<?= $outputid ?>">
																							<thead>
																								<tr>
																									<th style="width:2%"># </th>
																									<th style="width:16%">Description</th>
																									<th style="width:8%">Unit</th>
																									<th style="width:8%">Unit Cost (Ksh)</th>
																									<th style="width:9%">No. of Units</th>
																									<th style="width:10%">Total Cost (Ksh)</th>
																									<th style="width:2%">Action </th>
																								</tr>
																							</thead>
																							<tbody>
																								<?php
																								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE design_id=:design_id ORDER BY parent ASC");
																								$query_rsMilestone->execute(array(":design_id" => $design_id));
																								$row_rsMilestone = $query_rsMilestone->fetch();
																								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																								if ($totalRows_rsMilestone > 0) {
																									$mcounter = 0;
																									do {
																										$milestone_name = $row_rsMilestone['milestone'];
																										$milestone_id = $row_rsMilestone['msid'];
																										$milestone_location = $row_rsMilestone['location'];
																										$milestone_sequence = $row_rsMilestone['parent'];

																										$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
																										$query_rsTasks->execute(array(":milestone" => $milestone_id));
																										$row_rsTasks = $query_rsTasks->fetch();
																										$totalRows_rsTasks = $query_rsTasks->rowCount();
																										if ($totalRows_rsTasks > 0) {
																											$mcounter++;
																								?>
																											<tr class="bg-blue-grey">
																												<td><?= $mcounter ?></td>
																												<td colspan="7"><strong> Milestone:</strong> <?= $milestone_name ?></td>
																											</tr>
																											<?php
																											$tcounter = 0;
																											do {
																												$task_name = $row_rsTasks['task'];
																												$task_id = $row_rsTasks['tkid'];
																												$task_duration = $row_rsTasks['duration'];
																												$task_sequence = $row_rsTasks['parenttask'];

																												$query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_task_parameters WHERE task_id=:task_id");
																												$query_rsTask_parameters->execute(array(":task_id" => $task_id));
																												$row_rsTask_parameters = $query_rsTask_parameters->fetch();
																												$totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();

																												if ($totalRows_rsTask_parameters > 0) {
																													$tcounter++;
																													$cost_type = 1;
																													$edit = 1;
																													$query_rsTask_direct = $db->prepare("SELECT SUM(units_no * unit_cost) as total_cost FROM tbl_project_direct_cost_plan WHERE projid=:projid AND design_id=:design_id AND site_id=:site_id AND tasks=:task_id ");
																													$query_rsTask_direct->execute(array(':projid' => $projid, ":design_id" => $design_id, ":site_id" => $site_id, ":task_id" => $task_id));
																													$row_rsTask_direct = $query_rsTask_direct->fetch();
																													$totalRows_rsTask_direct = $query_rsTask_direct->rowCount();
																													$sum_cost = $row_rsTask_direct['total_cost'] != null ? $row_rsTask_direct['total_cost'] : "0";
																													$sub_total_amount += $sum_cost;
																											?>
																													<tr class="bg-grey">
																														<td><?= $mcounter . "." . $tcounter  ?></td>
																														<td colspan="5"><strong> Task:</strong> <?= $task_name ?></td>
																														<td> </td>
																													</tr>
																													<?php
																													$budget_line_rowno = 0;
																													do {
																														$budget_line_rowno++;
																														$task_parameter_name = $row_rsTask_parameters['parameter'];
																														$unit_of_measure = $row_rsTask_parameters['unit_of_measure'];
																														$task_parameter_id = $row_rsTask_parameters['id'];

																														$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND design_id=:design_id AND site_id=:site_id AND cost_type=:cost_type AND tasks=:tkid AND task_parameter_id=:task_parameter_id");
																														$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":site_id" => $site_id, ":design_id" => $design_id, ":cost_type" => $cost_type, ":tkid" => $task_id, ":task_parameter_id" => $task_parameter_id));
																														$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																														$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();

																														$unit_cost = $totalRows_rsDirect_cost_plan > 0 ? $row_rsDirect_cost_plan['unit_cost'] : 0;
																														$units_no = $totalRows_rsDirect_cost_plan > 0 ? $row_rsDirect_cost_plan['units_no'] : 0;
																														$total_cost = $unit_cost * $units_no;

																													?>
																														<tr id="row<?= $budget_line_rowno ?>">
																															<td><?= $budget_line_rowno ?></td>
																															<td><?= $task_parameter_name ?></td>
																															<td><?= $unit_of_measure ?></td>
																															<td><?= number_format($unit_cost, 2) ?></td>
																															<td><?= number_format($units_no) ?></td>
																															<td><?= number_format($total_cost, 2) ?></td>
																															<td></td>
																														</tr>
																								<?php
																													} while ($row_rsTask_parameters = $query_rsTask_parameters->fetch());
																												}
																											} while ($row_rsTasks = $query_rsTasks->fetch());
																										}
																									} while ($row_rsMilestone = $query_rsMilestone->fetch());
																								}
																								$sub_total_percentage  = ($sub_total_amount / $projcost) * 100;
																								?>

																							</tbody>
																							<tfoot>
																								<tr>
																									<td colspan="2"><strong>Sub Total</strong></td>
																									<td colspan="2">
																										<input type="hidden" name="subtotal_amounts" value="<?= $sub_total_amount ?>" class="sub_totals" id="h_sub_total_amount3<?= $design_id ?>">
																										<input type="text" name="d_sub_total_amount" id="sub_total_amount3<?= $design_id ?>" value="<?= number_format($sub_total_amount, 2) ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																									<td colspan="1"> <strong>% Sub Total</strong></td>
																									<td colspan="2">
																										<input type="text" name="d_sub_total_percentage" id="sub_total_percentage3<?= $design_id ?>" value="<?= number_format($sub_total_percentage, 2) ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>
																				</fieldset>
																			<?php
																			}
																			?>

																	<?php
																		} while ($row_rsDesigns = $query_rsDesigns->fetch());
																	}
																	?>
																</div>
															</div>
													<?php
														} while ($row_rsOutputs = $query_rsOutputs->fetch());
													}
													?>
												</fieldset>

												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
														<i class="fa fa-list-ol" aria-hidden="true"></i> 2.0 Administrative/Operational Cost
													</legend>
													<div class="row clearfix">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="card">
																<div class="panel panel-info">
																	<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative">
																		<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
																		<strong> 2.1
																			<span class="">
																				Administrative/Operational Cost
																			</span>
																		</strong>
																	</div>
																	<div class="body collapse administrative">
																		<div class="table-responsive">
																			<table class="table table-bordered">
																				<thead>
																					<tr>
																						<th style="width:5%"># </th>
																						<th style="width:35%">Description </th>
																						<th style="width:20%">Unit</th>
																						<th style="width:10%">Unit Cost</th>
																						<th style="width:10%">No. of Units</th>
																						<th style="width:10%">Total Cost</th>
																						<th style="width:10%">Financial Year</th>
																					</tr>
																				</thead>
																				<tbody id="budget_lines_table<?= $cost_type ?>">
																					<tr></tr>
																					<?php
																					$cost_type = $budget_line_id = 2;
																					$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
																					$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
																					$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
																					$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
																					$edit = $totalRows_rsOther_cost_plan > 0 ? 1 : 0;
																					$plan_id = $totalRows_rsOther_cost_plan > 0 ? $row_rsOther_cost_plan['plan_id'] : 0;

																					$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
																					$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
																					$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
																					$totalRows_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->rowCount();
																					$sum_cost = $totalRows_rsOther_cost_plan > 0 ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
																					$outputid = 0;
																					$rowno = 1;
																					$other_sum = $other_percent = 0;
																					if ($totalRows_rsOther_cost_plan > 0) {
																						$table_counter = 0;
																						do {
																							$table_counter++;
																							$rowno++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$financial_year = $row_rsOther_cost_plan['financial_year'];
																							$total_cost = $unit_cost * $units_no;
																							$other_sum = $other_sum + $total_cost;
																							$budget_line_rowno = $rowno + $cost_type;
																							$end_year = $financial_year + 1;
																					?>
																							<tr id="row<?= $budget_line_rowno ?>">
																								<td><?= $table_counter ?></td>
																								<td><?= $description ?></td>
																								<td><?= $unit ?></td>
																								<td><?= number_format($unit_cost, 2) ?></td>
																								<td><?= number_format($units_no) ?></td>
																								<td><?= number_format($total_cost, 2) ?></td>
																								<td><?= $financial_year  . "/" . $end_year ?></td>
																							</tr>
																					<?php
																						} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																						$other_per  = ($other_sum / $projcost) * 100;
																						$other_percent = number_format($other_per, 2);
																						$total_administrative_cost += $other_sum;
																					}
																					?>
																				</tbody>
																				<tfoot id="budget_line_foot<?= $cost_type ?>">
																					<tr>
																						<td colspan="2"><strong>Sub Total</strong></td>
																						<td colspan="2">
																							<input type="hidden" name="subtotal_amounts" value="<?= $other_sum ?>" class="sub_totals" id="h_sub_total_amount3<?= $cost_type ?>">
																							<input type="text" name="subtotal_amount3<?= $cost_type ?>" value="<?= number_format($other_sum, 2) ?>" id="sub_total_amount3<?= $cost_type ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="2"> <strong>% Sub Total</strong></td>
																						<td colspan="2">
																							<input type="text" name="subtotal_percentage3<?= $cost_type ?>" value="<?= $other_percent ?> %" id="sub_total_percentage3<?= $cost_type  ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																					</tr>
																				</tfoot>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</fieldset>

												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
														<i class="fa fa-list-ol" aria-hidden="true"></i> 3.0 M&E Budget details
													</legend>
													<?php
													if ($projmapping == 1) {
														$other_plan_id = "B";
														$query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND other_plan_id=:other_plan_id ");
														$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
														$row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
														$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();

														$edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

														$query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND other_plan_id=:other_plan_id ");
														$query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
														$row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
														$totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
														$sum_cost = $totalRows_rs_output_cost_plan > 0 ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
														$budget_line = "Mapping";
													?>
														<div class="row clearfix">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="card">
																	<div class="panel panel-info">
																		<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".Mapping">
																			<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
																			<strong> 3.1
																				<span class="">
																					Mapping
																				</span>
																			</strong>
																		</div>
																		<div class="body collapse Mapping">
																			<table class="table table-bordered">
																				<thead>
																					<tr>
																						<th># </th>
																						<th>Description </th>
																						<th>Unit</th>
																						<th>Unit Cost</th>
																						<th>No. of Units</th>
																						<th>Total Cost</th>
																					</tr>
																				</thead>
																				<tbody id="budget_lines_tableB">
																					<?php
																					$body = "";
																					$sum_budget =  $other_percent = 0;
																					if ($totalRows_rs_output_cost_plan > 0) {
																						$table_counter = 0;
																						do {
																							$table_counter++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$financial_year = $row_rsOther_cost_plan['financial_year'];
																							$total_cost = $unit_cost * $units_no;
																							$sum_budget += $total_cost;
																							$end_year = $financial_year + 1;
																							$body .=
																								'<tr id="row">
                                                                                    <td>' . $table_counter . '</td>
                                                                                    <td>' . $description . '</td>
                                                                                    <td>' . $unit . '</td>
                                                                                    <td>' . number_format($unit_cost, 2) . '</td>
                                                                                    <td>' . number_format($units_no) . '</td>
                                                                                    <td>' . number_format($total_cost, 2) . '</td>
                                                                                </tr>';
																						} while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch());
																						echo $body;
																						$other_percent = ($sum_budget / $mne_budget) * 100;
																					}
																					?>
																				</tbody>
																				<tfoot id="budget_line_footB">
																					<tr>
																						<td colspan="2"><strong>Sub Total</strong></td>
																						<td colspan="2">
																							<input type="hidden" name="monitoring_subtotal" value="<?= $sum_budget ?>" class="sub_totals" id="monitoring_sum">
																							<input type="text" name="monitoring_subtotal" value="<?= number_format($sum_budget, 2) ?>" id="monitoring_sum" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="1"> <strong>% Sub Total</strong></td>
																						<td colspan="2">
																							<input type="text" name="" value="<?= number_format($other_percent, 2) ?> %" id="monitoring_sub_total_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																					</tr>
																				</tfoot>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													<?php
													}
													$other_plan_id = "A";
													$query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND other_plan_id=:other_plan_id ");
													$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
													$row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
													$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
													$body = "";
													$sum_budget =  $other_percent = 0;
													$edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

													$query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND other_plan_id=:other_plan_id ");
													$query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
													$row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
													$totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
													$sum_cost = $totalRows_rs_output_cost_plan > 0 ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
													$budget_line = "Monitoring";
													?>

													<div class="row clearfix">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="card">
																<div class="panel panel-info">
																	<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".Monitoring">
																		<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
																		<strong> 3.2
																			<span class="">
																				Monitoring
																			</span>
																		</strong>
																	</div>
																	<div class="body collapse Monitoring">
																		<table class="table table-bordered">
																			<thead>
																				<tr>
																					<th># </th>
																					<th>Description </th>
																					<th>Unit</th>
																					<th>Unit Cost</th>
																					<th>No. of Units</th>
																					<th>Total Cost</th>
																					<th>Year</th>
																				</tr>
																			</thead>
																			<tbody id="budget_lines_tableA">

																				<?php
																				if ($totalRows_rs_output_cost_plan > 0) {
																					$table_counter = 0;
																					do {
																						$table_counter++;
																						$unit = $row_rsOther_cost_plan['unit'];
																						$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																						$units_no = $row_rsOther_cost_plan['units_no'];
																						$rmkid = $row_rsOther_cost_plan['id'];
																						$description = $row_rsOther_cost_plan['description'];
																						$financial_year = $row_rsOther_cost_plan['financial_year'];
																						$end_year = $financial_year + 1;
																						$total_cost = $unit_cost * $units_no;
																						$sum_budget += $total_cost;
																						$body .=
																							'<tr id="row">
                                                                                    <td>' . $table_counter . '</td>
                                                                                    <td>' . $description . '</td>
                                                                                    <td>' . $unit . '</td>
                                                                                    <td>' . number_format($unit_cost, 2) . '</td>
                                                                                    <td>' . number_format($units_no) . '</td>
                                                                                    <td>' . number_format($total_cost, 2) . '</td>
                                                                                    <td>' . $financial_year . "/" . $end_year . '</td>
                                                                                </tr>';
																					} while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch());
																					echo $body;
																					$other_percent = ($sum_budget / $mne_budget) * 100;
																				}
																				?>
																			</tbody>
																			<tfoot id="budget_line_footA">
																				<tr>
																					<td colspan="2"><strong>Sub Total</strong></td>
																					<td colspan="2">
																						<input type="hidden" name="monitoring_subtotal" value="<?= $sum_budget ?>" class="sub_totals" id="monitoring_sum">
																						<input type="text" name="monitoring_subtotal" value="<?= number_format($sum_budget, 2) ?>" id="monitoring_sum" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																					<td colspan="2"> <strong>% Sub Total</strong></td>
																					<td colspan="2">
																						<input type="text" name="" value="<?= number_format($other_percent, 2) ?> %" id="monitoring_sub_total_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?php
													if ($projevaluation == 1) {
														$other_plan_id = "C";
														$query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND other_plan_id=:other_plan_id ");
														$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
														$row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
														$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
														$edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

														$query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND other_plan_id=:other_plan_id ");
														$query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
														$row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
														$totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
														$sum_cost = $totalRows_rs_output_cost_plan > 0 ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
														$budget_line = "Baseline Evaluation";
													?>
														<div class="row clearfix">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="card">
																	<div class="panel panel-info">
																		<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".evaluation">
																			<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
																			<strong> 3.1
																				<span class="">
																					Evaluation
																				</span>
																			</strong>
																		</div>
																		<div class="body collapse evaluation">
																			<table class="table table-bordered">
																				<thead>
																					<tr>
																						<th># </th>
																						<th>Description </th>
																						<th>Unit</th>
																						<th>Unit Cost</th>
																						<th>No. of Units</th>
																						<th>Total Cost</th>
																					</tr>
																				</thead>
																				<tbody id="budget_lines_tableC">
																					<?php
																					$body = "";
																					$sum_budget =  $other_percent = 0;
																					if ($totalRows_rs_output_cost_plan > 0) {
																						$table_counter = 0;
																						do {
																							$table_counter++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$total_cost = $unit_cost * $units_no;
																							$sum_budget += $total_cost;
																							$body .=
																								'<tr id="row">
                                                                                    <td>' . $table_counter . '</td>
                                                                                    <td>' . $description . '</td>
                                                                                    <td>' . $unit . '</td>
                                                                                    <td>' . number_format($unit_cost, 2) . '</td>
                                                                                    <td>' . number_format($units_no) . '</td>
                                                                                    <td>' . number_format($total_cost, 2) . '</td>
                                                                                </tr>';
																						} while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch());
																						echo $body;
																						$other_percent = ($sum_budget / $mne_budget) * 100;
																					}
																					?>
																				</tbody>
																				<tfoot id="budget_line_footC">
																					<tr>
																						<td colspan="1"><strong>Sub Total</strong></td>
																						<td colspan="2">
																							<input type="hidden" name="monitoring_subtotal" value="<?= $sum_budget ?>" class="sub_totals" id="monitoring_sum">
																							<input type="text" name="monitoring_subtotal" value="<?= number_format($sum_budget, 2) ?>" id="monitoring_sum" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="2"> <strong>% Sub Total</strong></td>
																						<td colspan="2">
																							<input type="text" name="" value="<?= number_format($other_percent, 2) ?> %" id="monitoring_sub_total_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																					</tr>
																				</tfoot>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													<?php
													}
													?>

												</fieldset>
											</fieldset>
										</div>
										<div id="menu1" class="tab-pane fade">
											<?php
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
											<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
													<i class="fa fa-university" aria-hidden="true"></i>Details
												</legend>
												<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project Code:</label>
													<div class="form-line">
														<input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
													</div>
												</div>
												<div class="col-md-9 clearfix" style="margin-top:5px; margin-bottom:5px">
													<input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>" readonly>
													<label class="control-label">Project Name:</label>
													<div class="form-line">
														<input type="hidden" class="form-control" value=" <?= $row_rsProjBudget['budget'] ?>" id="project_cost">
														<input type="text" class="form-control" value=" <?= $projname ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Total Project Cost:</label>
													<div class="form-line">
														<input type="text" class="form-control" value="Ksh. <?php echo number_format($row_rsProjBudget["budget"], 2); ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project Implementation Cost:</label>
													<div class="form-line">
														<input type="hidden" class="form-control" id="implementation_budget" value="<?= $implementation_cost ?>">
														<input type="text" class="form-control" value="<?= number_format($implementation_cost, 2) ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project M&E Cost :</label>
													<div class="form-line">
														<input type="text" class="form-control" value=" <?= number_format($mne_cost, 2) ?>" readonly>
													</div>
												</div>
												<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
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
												$query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND type = :type");
												$query_rsTender->execute(array(":projid" => $projid, ":type" => 1));
												$row_plan = $query_rsTender->fetch();
												$totalRows_Tender = $query_rsTender->rowCount();
												$contribution_val = $row_plan['funds'];
												?>
												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
														<i class="fa fa-list-ol" aria-hidden="true"></i> 1.0 Procurement Cost
													</legend>
													<?php

													$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
													$query_rsOutputs->execute(array(":projid" => $projid));
													$row_rsOutputs = $query_rsOutputs->fetch();
													$totalRows_rsOutputs = $query_rsOutputs->rowCount();
													$direct_cost = 0;
													if ($totalRows_rsOutputs > 0) {
														$Ocounter = 0;
														do {
															$Ocounter++;
															$outputName = $row_rsOutputs['output'];
															$outputCost = $row_rsOutputs['budget'];
															$outputid = $row_rsOutputs['opid'];
															$output_cost_val[] = $outputid;
															$output_remeinder = 0;

															$query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND outputid=:opid AND type=:type");
															$query_rsTender->execute(array(":projid" => $projid, ":opid" => $outputid, ":type" => 1));
															$row_plan = $query_rsTender->fetch();
															$totalRows_Tender = $query_rsTender->rowCount();
															$contribution_amount = $row_plan['funds'];

													?>
															<div class="panel panel-primary">
																<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
																	<i class="fa fa-caret-down" aria-hidden="true"></i>
																	<strong> Output <?= $Ocounter ?>:<span class=""><?= $outputName ?> </span> </strong> <?= number_format($output_cost) ?>
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
																	<?php
																	$query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs WHERE output_id=:output_id ORDER BY id");
																	$query_rsDesigns->execute(array(":output_id" => $outputid));
																	$row_rsDesigns = $query_rsDesigns->fetch();
																	$totalRows_rsDesigns = $query_rsDesigns->rowCount();

																	if ($totalRows_rsDesigns > 0) {
																		$design_counter = 0;
																		do {
																			$design_counter++;
																			$design_id = $row_rsDesigns['id'];
																			$design_name = $row_rsDesigns['design'];
																			$design_sites = $row_rsDesigns['sites'];
																			$sites = explode(",", $design_sites);
																			$total_sites = count($sites) > 0 ? count($sites) : 1;
																			$site_type = count($sites) > 0 ? 2 : 1;
																			$site_counter = 0;
																			for ($i = 0; $i < $total_sites; $i++) {
																				$sub_total_percentage = $sub_total_amount = 0;
																				$site_counter++;
																				$site_name = "";
																				$site_id = $sites[$i];
																				if ($site_type == 2) {
																					$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id = :id ");
																					$query_Sites->execute(array(":id" => $site_id));
																					$row_rsSites = $query_Sites->fetch();
																					$total_Sites1 = $query_Sites->rowCount();
																					$site_name = $total_Sites1 > 0 ? $row_rsSites['site'] : "";
																				}
																	?>
																				<fieldset class="scheduler-border">
																					<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																						<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $site_type == 1 ? "Direct Cost" : "Site" ?> <?= $site_counter ?>. <?= $site_name ?>
																					</legend>
																					<div class="table-responsive">
																						<table class="table table-bordered" id="direct_table<?= $outputid ?>">
																							<thead>
																								<tr>
																									<th style="width:2%"># </th>
																									<th style="width:16%">Description</th>
																									<th style="width:8%">Unit</th>
																									<th style="width:8%">Unit Cost (Ksh)</th>
																									<th style="width:9%">No. of Units</th>
																									<th style="width:10%">Total Cost (Ksh)</th>
																								</tr>
																							</thead>
																							<tbody>
																								<?php
																								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE design_id=:design_id ORDER BY parent ASC");
																								$query_rsMilestone->execute(array(":design_id" => $design_id));
																								$row_rsMilestone = $query_rsMilestone->fetch();
																								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
																								if ($totalRows_rsMilestone > 0) {
																									$mcounter = 0;
																									do {
																										$milestone_name = $row_rsMilestone['milestone'];
																										$milestone_id = $row_rsMilestone['msid'];
																										$milestone_location = $row_rsMilestone['location'];
																										$milestone_sequence = $row_rsMilestone['parent'];

																										$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
																										$query_rsTasks->execute(array(":milestone" => $milestone_id));
																										$row_rsTasks = $query_rsTasks->fetch();
																										$totalRows_rsTasks = $query_rsTasks->rowCount();
																										if ($totalRows_rsTasks > 0) {
																											$mcounter++;
																								?>
																											<tr class="bg-blue-grey">
																												<td><?= $mcounter ?></td>
																												<td colspan="7"><strong> Milestone:</strong> <?= $milestone_name ?></td>
																											</tr>
																											<?php
																											$tcounter = 0;
																											do {
																												$task_name = $row_rsTasks['task'];
																												$task_id = $row_rsTasks['tkid'];
																												$task_duration = $row_rsTasks['duration'];
																												$task_sequence = $row_rsTasks['parenttask'];

																												$query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_task_parameters WHERE task_id=:task_id");
																												$query_rsTask_parameters->execute(array(":task_id" => $task_id));
																												$row_rsTask_parameters = $query_rsTask_parameters->fetch();
																												$totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();

																												if ($totalRows_rsTask_parameters > 0) {
																													$tcounter++;
																													$cost_type = 1;
																													$edit = 1;
																													$query_rsTask_direct = $db->prepare("SELECT SUM(unit_cost * units_no) as total_cost FROM tbl_project_tender_details WHERE projid=:projid AND design_id=:design_id AND site_id=:site_id AND tasks=:task_id ");
																													$query_rsTask_direct->execute(array(':projid' => $projid, ":design_id" => $design_id, ":site_id" => $site_id, ":task_id" => $task_id));
																													$row_rsTask_direct = $query_rsTask_direct->fetch();
																													$totalRows_rsTask_direct = $query_rsTask_direct->rowCount();
																													$sub_total_amount = $row_rsTask_direct['total_cost'] != null ? $row_rsTask_direct['total_cost'] : "0";
																											?>
																													<tr class="bg-grey">
																														<td><?= $mcounter . "." . $tcounter  ?></td>
																														<td colspan="6"><strong> Task:</strong> <?= $task_name ?></td>
																													</tr>
																													<?php
																													$budget_line_rowno = 0;
																													do {
																														$budget_line_rowno++;
																														$task_parameter_name = $row_rsTask_parameters['parameter'];
																														$unit_of_measure = $row_rsTask_parameters['unit_of_measure'];
																														$task_parameter_id = $row_rsTask_parameters['id'];
																														$query_Procurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid =:projid AND design_id=:design_id AND site_id=:site_id AND tasks=:tkid AND task_parameter_id=:task_parameter_id");
																														$query_Procurement->execute(array(":projid" => $projid, ":site_id" => $site_id, ":design_id" => $design_id, ":tkid" => $task_id, ":task_parameter_id" => $task_parameter_id));
																														$row_Procurement = $query_Procurement->fetch();
																														$totalRows_Procurement = $query_Procurement->rowCount();

																														$punit_cost = $row_Procurement['unit_cost'];
																														$punits_no = $row_Procurement['units_no'];
																														$total_cost = $punit_cost * $punits_no;
																														$output_remeinder = $output_remeinder + $total_cost;
																													?>
																														<tr id="row<?= $budget_line_rowno ?>">
																															<td><?= $budget_line_rowno ?></td>
																															<td><?= $task_parameter_name ?></td>
																															<td><?= $unit_of_measure ?></td>
																															<td><?= number_format($unit_cost, 2) ?></td>
																															<td><?= number_format($units_no) ?></td>
																															<td><?= number_format($total_cost, 2) ?></td>
																														</tr>
																								<?php
																													} while ($row_rsTask_parameters = $query_rsTask_parameters->fetch());
																												}
																											} while ($row_rsTasks = $query_rsTasks->fetch());
																										}
																									} while ($row_rsMilestone = $query_rsMilestone->fetch());
																								}
																								$sub_total_percentage  = ($sub_total_amount / $projcost) * 100;
																								?>

																							</tbody>
																							<tfoot>
																								<tr>
																									<td colspan="2"><strong>Sub Total</strong></td>
																									<td colspan="2">
																										<input type="hidden" name="subtotal_amounts" value="<?= $sub_total_amount ?>" class="sub_totals" id="h_sub_total_amount3<?= $design_id ?>">
																										<input type="text" name="d_sub_total_amount" id="sub_total_amount3<?= $design_id ?>" value="<?= number_format($sub_total_amount, 2) ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																									<td colspan="1"> <strong>% Sub Total</strong></td>
																									<td colspan="2">
																										<input type="text" name="d_sub_total_percentage" id="sub_total_percentage3<?= $design_id ?>" value="<?= number_format($sub_total_percentage, 2) ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>
																				</fieldset>
																			<?php
																			}
																			?>

																	<?php
																		} while ($row_rsDesigns = $query_rsDesigns->fetch());
																	}
																	?>
																</div>
															</div>
													<?php
														} while ($row_rsOutputs = $query_rsOutputs->fetch());
													}

													$query_rsTask_direct = $db->prepare("SELECT SUM(units_no * unit_cost) as total_cost FROM tbl_project_tender_details WHERE projid=:projid  ");
													$query_rsTask_direct->execute(array(':projid' => $projid));
													$row_rsTask_direct = $query_rsTask_direct->fetch();
													$totalRows_rsTask_direct = $query_rsTask_direct->rowCount();
													$total_amount = $row_rsTask_direct['total_cost'];
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

											</fieldset>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
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