<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require('includes/head.php');
if ($permission) {
	try {
		if (isset($_GET['proj'])) {
			$encoded_projid = $_GET['proj'];
			$decode_projid = base64_decode($encoded_projid);
			$projid_array = explode("encodefnprj", $decode_projid);
			$projid = $projid_array[1];

			$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projstage=4 and projid = :projid");
			$query_rsProjects->execute(array(":projid" => $projid));
			$row_rsProjects = $query_rsProjects->fetch();
			$totalRows_rsProjects = $query_rsProjects->rowCount();

			if($totalRows_rsProjects > 0){
				$implimentation_type = $row_rsProjects['projcategory'];
				$projname = $row_rsProjects['projname'];
				$projcode = $row_rsProjects['projcode'];
				$projcost = $row_rsProjects['projcost'];
				$progid = $row_rsProjects['progid'];
				$projstartdate = $row_rsProjects['projstartdate'];
				$projenddate = $row_rsProjects['projenddate'];
				$projstartyear= date('Y', strtotime($projstartdate));
				$end_year= date('Y', strtotime($projenddate));

				$years = ($end_year- $projstartyear) + 1;

				$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
				$query_rsOutputs->execute(array(":projid" => $projid));
				$row_rsOutputs = $query_rsOutputs->fetch();
				$totalRows_rsOutputs = $query_rsOutputs->rowCount();

				$query_rsProjBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid = :projid");
				$query_rsProjBudget->execute(array(":projid" => $projid));
				$row_rsProjBudget = $query_rsProjBudget->fetch();

				// query the
				$query_rsProjFinancier =  $db->prepare("SELECT *, f.financier FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier WHERE projid = :projid ORDER BY amountfunding desc");
				$query_rsProjFinancier->execute(array(":projid" => $projid));
				$row_rsProjFinancier = $query_rsProjFinancier->fetch();
				$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

				$summary = '';
				$output_cost_val = [];



				function get_budget_lines($grp){
					global $db;
					$query_rsBilling = $db->prepare("SELECT * FROM tbl_budget_lines WHERE grp=:grp AND status=1");
					$query_rsBilling->execute(array(":grp"=>$grp));
					$row_rsBilling = $query_rsBilling->fetchAll();
					$totalRows_rsBilling = $query_rsBilling->rowCount();
					return $totalRows_rsBilling > 0 ?  $row_rsBilling : false;
				}
				?>
				<link rel="stylesheet" href="css/addprojects.css">
				<style>
					@media (min-width: 1200px) {
						.modal-lg {
							width: 90%;
							height: 100%;
						}
					}
				</style>
				<link href="style.css" rel="stylesheet">
				<!-- start body  -->

				<!-- start body  -->
				<section class="content">
					<div class="container-fluid">
						<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
							<h4 class="contentheader">
								<?= $icon ?>
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
							</div> 
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="card-header">
										<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
											<input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>" readonly>
											<label class="control-label">Project Name:</label>
											<div class="form-line">
												<input type="hidden" class="form-control" value=" <?= $row_rsProjBudget['budget'] ?>" id="project_cost">
												<input type="text" class="form-control" value=" <?= $projname ?>" readonly>
											</div>
										</div>
										<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
											<label class="control-label">Project Code:</label>
											<div class="form-line">
												<input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
											</div>
										</div>
										<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
											<label class="control-label">Project Approved Budget:</label>
											<div class="form-line">
												<input type="text" class="form-control" value="Ksh. <?php echo number_format($row_rsProjBudget["budget"], 2); ?>" readonly>
											</div>
										</div>
									</div>
									<div class="body">
										<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
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
													<i class="fa fa-money" aria-hidden="true"></i> Financial Plan
												</legend>
												<?php
													if($totalRows_rsOutputs > 0){
														$Ocounter = 0;
														do {
															$Ocounter++; 
															$outputName = $row_rsOutputs['output'];
															$outputCost = $row_rsOutputs['budget'];
															$outputid = $row_rsOutputs['opid'];
															$output_cost_val[] = $outputid;
															$output_remeinder = 0;
															$administrative_budget_lines= get_budget_lines(1);
															$non_expandable_budget_lines= get_budget_lines(2);
															$other_cost_budget_lines= get_budget_lines(3);
 
															$query_rs_output_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as budget FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:outputid ");
															$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":outputid" => $outputid));
															$row_rs_output_cost_plan = $query_rs_output_cost_plan->fetch();
															$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
															$output_cost = $row_rs_output_cost_plan ? $row_rs_output_cost_plan['budget'] : 0 ; 
																?>
																<div class="panel panel-primary">
																	<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
																		<i class="fa fa-caret-down" aria-hidden="true"></i>
																		<strong> Output <?= $Ocounter ?>:<span class=""><?= $outputName ?></span></strong>
																	</div>
																	<div class="collapse output<?php echo $outputid ?>" style="padding:5px">
																		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																			<div class="pull-right">
																				<h6 style="color:#FF5722">
																					<strong> Output Budget (Ksh):
																						<span class="output_cost_bal"> <?= number_format($outputCost, 2) ?>
																						</span>
																						<input type="hidden" name="output_budget_cost" id="output_budget_cost<?=$outputid?>" value="<?=$outputCost?>">
																						<input type="hidden" name="project_output_cost" value="<?= $output_cost ?>" class="project_output_cost" >
																					</strong>
																				</h6>
																			</div>
																		</div>
																		<fieldset class="scheduler-border">
																			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																				<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter ?>.1. Direct Project Cost
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
																							<th style="width:9%"> Other Details </th>
																							<th style="width:2%">Action </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						
																						$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid and outputid = :outputid ORDER BY sdate");
																						$query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
																						$row_rsMilestones = $query_rsMilestones->fetch();
																						$totalRows_rsMilestones = $query_rsMilestones->rowCount();
																						$mcounter = 0;
																						$sum = 0;

																						if ($totalRows_rsMilestones > 0) {
																							do {
																								$mcounter++;
																								$milestone = $row_rsMilestones['msid'];
																								$milestoneName = $row_rsMilestones['milestone'];
																								$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate");
																								$query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
																								$row_rsTasks = $query_rsTasks->fetch();
																								$totalRows_rsTasks = $query_rsTasks->rowCount();
																								if ($totalRows_rsTasks > 0) {
																						?>
																									<input type="hidden" name="mileid[]" id="mileid<?= $milestone ?>" value="<?= $milestone ?>">
																									<tr class="bg-blue-grey">
																										<td><?= $mcounter ?></td>
																										<td colspan="8"><strong> Milestone:</strong> <?= $milestoneName ?></td>
																									</tr>
																									<?php
																									$tcounter = 0;
																									do {
																										$tcounter++;
																										$task =  $row_rsTasks['task'];
																										$tkid =  $row_rsTasks['tkid'];
																										$edate =  $row_rsTasks['edate'];
																										$sdate =  $row_rsTasks['sdate'];
																										$taskid = $outputid . $tkid; // to distinguish between different outputs
																										$cost_type = 1;

																										?>
																											<input type="hidden" name="taskid<?= $outputid ?>[]" id="taskid<?= $tkid ?>" value="<?= $tkid ?>">
																											<tr class="bg-grey">
																												<td><?= $mcounter . "." . $tcounter  ?></td>
																												<td colspan="2"><strong> Task:</strong> <?= $task ?></td>
																												<td colspan="2"><strong> Start Date:</strong> <?php echo date("d M Y", strtotime($sdate)); ?></td>
																												<td colspan="2"><strong> End Date:</strong> <?php echo date("d M Y", strtotime($edate)); ?></td>
																												<td>
																													<button type="button" name="addplus" onclick="add_direct_row('<?= $taskid ?>', '<?= $outputid ?>', '<?= $tkid ?>')" title="Add another field" class="btn btn-success btn-sm">
																														<span class="glyphicon glyphicon-plus"></span>
																													</button>
																												</td>
																											</tr>
																											<?php

																										$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
																										$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																										$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																										$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
																										$dddescription = $unit = $unit_cost = $units_no = $rmkid = $total_cost = "";
																										if($totalRows_rsDirect_cost_plan > 0){
																											do{
																												$unit = $row_rsDirect_cost_plan['unit'];
																												$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																												$units_no = $row_rsDirect_cost_plan['units_no'];
																												$rmkid = $row_rsDirect_cost_plan['id'];
																												$dddescription = $row_rsDirect_cost_plan['description'];
																												$total_cost = $unit_cost * $units_no;
																												$sum = $sum + $total_cost;
																												$output_remeinder = $output_remeinder + $total_cost;
																												?>
																												<tr class="task<?= $taskid ?>">
																													<td style="color:#FF5722">
																														<?= $mcounter . "." . $tcounter  ?>
																													</td>
																													<td><?=$dddescription?></td>
																													<td><?=$unit?></td>
																													<td><?=$unit_cost?></td>
																													<td><?=$units_no?></td>
																													<td><?=$total_cost?></td> 
																													<td>
																														<a type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_direct_cost(<?= $outputid ?>,<?= $tkid ?>, 1, 1)" id="">
																															<i class="glyphicon glyphicon-plus"></i>
																															<span id="daddFormModalBtn1<?= $taskid ?>1">
																																Edit Details
																															</span>
																														</a>
																													</td> 
																												</tr>
																											<?php
																											}while($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
																										}
																									} while ($row_rsTasks = $query_rsTasks->fetch());
																								}
																							} while ($row_rsMilestones = $query_rsMilestones->fetch());
																							$per  = ($sum && $outputCost) ? ($sum / $outputCost) * 100 :0;
																							$percent = number_format($per, 2);
																						}
																						?>
																					</tbody>
																					<tfoot>
																						<tr>
																							<td colspan="2"><strong>Sub Total</strong></td>
																							<td colspan="1">
																								<input type="hidden" name="subtotal_amounts" value="<?= $other_sum ?>" class="h_sub_total_amount3<?= $budget_lineid ?>" id="h_sub_total_amount3<?= $budget_lineid ?>">
																								<input type="text" name="d_sub_total_amount" id="sub_total_amount1<?= $outputid ?>" value="<?=$sum?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																							</td>
																							<td colspan="1"> <strong>% Sub Total</strong></td>
																							<td colspan="1">
																								<input type="text" name="d_sub_total_percentage" id="sub_total_percentage1<?= $outputid ?>" value="<?=$percent?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																							</td>
																							<td colspan="1"> <strong>Output Budget Bal</strong></td>
																							<td colspan="2">
																								<input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format($outputCost - $output_remeinder, 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																							</td>
																						</tr>
																					</tfoot>
																				</table>
																			</div>
																		</fieldset>
																		<?php 
																		if($administrative_budget_lines){
																			?>
																			<fieldset class="scheduler-border">
																				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																					<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter ?>.2. Administrative/Operational Cost
																				</legend>
																				<?php
																				$counter = 2;
																				$a_counter =0;
																				$pcounter=0;
																				foreach($administrative_budget_lines as $administrative_budget_line){ 
																					$a_counter++;
																					$budget_line = $administrative_budget_line['name'];
																					$budget_line_id = $administrative_budget_line['id'];
																					$budget_lineid = $budget_line_id . $outputid;
																					?>
																					<div class="panel  panel-info">
																						<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative<?php echo $budget_lineid ?>">
																							<i class="fa fa-caret-down" aria-hidden="true"></i>
																							<strong> <?= $Ocounter . "." . $counter . "." . $a_counter  . ") " . $budget_line?>
																						</div>
																						<div class="collapse administrative<?php echo $budget_lineid ?>" style="padding:5px">
																							<div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
																								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																									<div class="pull-right">
																										<h6 style="color:#FF5722">
																											<strong> Output Budget (Ksh):
																												<span class="output_cost_bal"> <?= number_format($outputCost, 2) ?></span>
																											</strong>
																										</h6>
																									</div>
																								</div>
																								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																									<input type="hidden" name="budgetlineid<?= $outputid ?>[]" id="budgetlineid<?= $outputid ?>" value="<?= $budget_line_id ?>">
																									<div class="table-responsive">
																										<table class="table table-bordered">
																											<thead>
																												<tr>
																													<th> # </th>
																													<th> Description </th>
																													<th>Unit</th>
																													<th>Unit Cost</th>
																													<th>No. of Units</th>
																													<th>Total Cost</th>
																													<th>Add Other Details</th>
																													<th style="width:2%">
																														<button type="button" data-toggle="modal" data-target="#addFormModal"  data-backdrop="static" data-keyboard="false" name="addplus" title="Add another field" class="btn btn-success btn-sm" onclick="add_direct_cost(<?= $outputid ?>,<?= $budget_line_id ?>)"> 
																															<span class="glyphicon glyphicon-plus"></span>
																														</button>
																													</th>
																												</tr>
																											</thead>
																											<tbody id="budget_lines_table<?= $budget_lineid ?>">
																												<tr></tr>
																												<?php
																												$cost_type = 3;
																												$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
																												$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
																												$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
																												$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

																												$rowno = 1;
																												$other_sum = 0;
																												if ($totalRows_rsOther_cost_plan > 0) {
																														$table_counter=0;
																														do {
																															$table_counter++;
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
																															$budget_line_rowno = $rowno + $budget_lineid;
																														?>
																															<tr id="row<?= $budget_line_rowno ?>">
																																<td><?=$table_counter?></td>
																																<td><?= $description ?></td>
																																<td> <?= $unit ?></td>
																																<td><?= $unit_cost ?></td>
																																<td><?= $units_no ?></td>
																																<td><?= $total_cost ?><td>
																																	<a type="button" data-toggle="modal" data-id="<?= trim($budget_lineid) ?>" data-target="#addFormModal" onclick="add_direct_cost(<?= $outputid ?>, <?= $budget_line_rowno ?>, 3)" id="">
																																		<i class="glyphicon glyphicon-file"></i>
																																		<span id="addFormModalBtn3<?= $budget_line_rowno ?>">
																																			Edit Details
																																		</span>
																																	</a>
																																</td>
																																<td>
																																	<button type="button" class="btn btn-danger btn-sm" onclick="delete_budget_lines_row<?= $budget_lineid ?>(' + budget_line_rowno_id + ', <?= $outputid ?>, <?= $budget_line_rowno ?>, 3 )">
																																		<span class="glyphicon glyphicon-minus"></span>
																																	</button>
																																</td>
																															</tr>
																														<?php
																													} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																														$other_per  = ($other_sum / $outputCost) * 100;
																														$other_percent = number_format($other_per, 2);
																												} ?>
																											</tbody>
																											<tfoot id="budget_line_foot<?= $budget_lineid ?>">
																												<tr>
																													<td colspan="1"><strong>Sub Total</strong></td>
																													<td colspan="2">
																														<input type="hidden" name="subtotal_amounts" value="<?= $other_sum ?>" class="h_sub_total_amount3<?= $budget_lineid ?>" id="h_sub_total_amount3<?= $budget_lineid ?>">
																														<input type="text" name="subtotal_amount3<?= $budget_lineid ?>" value="<?= number_format($other_sum, 2) ?>" id="sub_total_amount3<?= $budget_lineid ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																													<td colspan="1"> <strong>% Sub Total</strong></td>
																													<td colspan="1">
																														<input type="text" name="subtotal_percentage3<?= $budget_lineid ?>" value="<?= $other_percent ?> %" id="sub_total_percentage3<?= $budget_lineid  ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																													<td colspan="1"> <strong>Output Budget Bal</strong></td>
																													<td colspan="2">
																														<input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format(($outputCost - $output_remeinder), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
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
																			<?php
																		}

																		if($non_expandable_budget_lines){
																			?>
																			
																			<fieldset class="scheduler-border">
																				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																					<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter ?>.3. Non Expendable Equipment Cost
																				</legend>
																				<?php
																				$counter = 2;
																				$n_counter =0;
																				foreach($non_expandable_budget_lines as $non_expandable_budget_line){
																					$counter++;
																					$n_counter++;
																					$budget_line = $non_expandable_budget_line['name'];
																					$budget_line_id = $non_expandable_budget_line['id'];
																					$budget_lineid = $budget_line_id . $outputid;
																					?>
																					<div class="panel panel-primary">
																						<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative<?php echo $budget_lineid ?>">
																							<i class="fa fa-caret-down" aria-hidden="true"></i>
																							<strong> <?= $Ocounter . "." . $counter . "." . $n_counter . ") " . $budget_line ?></span></strong>
																						</div>
																						<div class="collapse administrative<?php echo $budget_lineid ?>" style="padding:5px">
																							<div class="row clearfix" style="margin-top:5px; margin-bottom:5px"> 
																								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																										<input type="hidden" name="budgetlineid<?= $outputid ?>[]" id="budgetlineid<?= $outputid ?>" value="<?= $budget_line_id ?>">
																										<div class="table-responsive">
																											<table class="table table-bordered">
																												<thead>
																													<tr>
																														<th> # </th>
																														<th> Description </th>
																														<th>Unit</th>
																														<th>Unit Cost</th>
																														<th>No. of Units</th>
																														<th>Total Cost</th>
																														<th>Add Other Details</th>
																														<th style="width:2%">
																															<button type="button" data-toggle="modal" data-target="#addFormModal"  data-backdrop="static" data-keyboard="false" name="addplus" title="Add another field" class="btn btn-success btn-sm" onclick="add_direct_cost(<?= $outputid ?>,<?= $budget_line_id ?>)"> 
																																<span class="glyphicon glyphicon-plus"></span>
																															</button>
																														</th>
																													</tr>
																												</thead>
																												<tbody id="budget_lines_table<?= $budget_lineid ?>">
																													<tr></tr>
																													<?php
																													$cost_type = 3;
																													$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
																													$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
																													$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
																													$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

																													$rowno = 1;
																													$other_sum = 0;
																													if ($totalRows_rsOther_cost_plan > 0) {
																														$pcounter=0;
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
																																$budget_line_rowno = $rowno + $budget_lineid;
																															?>
																																<tr id="row<?= $budget_line_rowno ?>">
																																	<td> <?= $pcounter?></td>
																																	<td><?= $description ?></td>
																																	<td> <?= $unit ?></td>
																																	<td><?= $unit_cost ?></td>
																																	<td><?= $units_no ?></td>
																																	<td><?= $total_cost ?><td>
																																			<input type="hidden" name="dsmttimeline3<?= $budget_lineid ?>[]" value="<?= $timeline_id ?>" id="dsmttimeline3<?= $budget_line_rowno ?>">
																																			<input type="hidden" name="finid3<?= $budget_lineid ?>[]" value="<?= $fnid ?>" id="finid3<?= $budget_line_rowno ?>">
																																			<input type="hidden" class="modal_val" name="rmkid3<?= $budget_lineid ?>[]" value="<?= $rmkid ?>" title="The output <?= $outputName ?> has a other details not captured at Budget line <?= $budget_line ?> look at row number <?= $rowno ?>" id="rmkid3<?= $budget_line_rowno ?>">
																																			<a type="button" data-toggle="modal" data-id="<?= trim($budget_lineid) ?>" data-target="#addFormModal" onclick="add_direct_cost(<?= $outputid ?>, <?= $budget_line_rowno ?>, 3)" id="">
																																				<i class="glyphicon glyphicon-file"></i>
																																				<span id="addFormModalBtn3<?= $budget_line_rowno ?>">
																																					Edit Details
																																				</span>
																																			</a>
																																	</td>
																																	<td>
																																			<button type="button" class="btn btn-danger btn-sm" onclick="delete_budget_lines_row<?= $budget_lineid ?>(' + budget_line_rowno_id + ', <?= $outputid ?>, <?= $budget_line_rowno ?>, 3 )">
																																				<span class="glyphicon glyphicon-minus"></span>
																																			</button>
																																	</td>
																																</tr>
																															<?php
																														} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																															$other_per  = ($other_sum / $outputCost) * 100;
																															$other_percent = number_format($other_per, 2);
																													} ?>
																												</tbody>
																												<tfoot id="budget_line_foot<?= $budget_lineid ?>">
																													<tr>
																														<td colspan="1"><strong>Sub Total</strong></td>
																														<td colspan="2">
																															<input type="hidden" name="subtotal_amounts" value="<?= $other_sum ?>" class="h_sub_total_amount3<?= $budget_lineid ?>" id="h_sub_total_amount3<?= $budget_lineid ?>">
																															<input type="text" name="subtotal_amount3<?= $budget_lineid ?>" value="<?= number_format($other_sum, 2) ?>" id="sub_total_amount3<?= $budget_lineid ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td colspan="1"> <strong>% Sub Total</strong></td>
																														<td colspan="1">
																															<input type="text" name="subtotal_percentage3<?= $budget_lineid ?>" value="<?= $other_percent ?> %" id="sub_total_percentage3<?= $budget_lineid  ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td colspan="1"> <strong>Output Budget Bal</strong></td>
																														<td colspan="2">
																															<input type="text" name="outputBal" id="output_remaining_budget3<?= $budget_lineid ?>" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format(($outputCost - $output_remeinder), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
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
																			<?php
																		} 
																		if($other_cost_budget_lines){
																			?>
																			<fieldset class="scheduler-border">
																				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																					<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter ?>.4. Other Cost Lines
																				</legend>
																				<?php
																				$counter = 2;
																				$o_counter=0; 
																				foreach($other_cost_budget_lines as $other_cost_budget_line){
																					$counter++;
																					$o_counter++;
																					$budget_line = $other_cost_budget_line['name'];
																					$budget_line_id = $other_cost_budget_line['id'];
																					$budget_lineid = $budget_line_id . $outputid;
																					?>
																					<div class="row clearfix" style="margin-top:5px; margin-bottom:5px"> 
																						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																							<fieldset class="scheduler-border">
																								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																									<i class="fa fa-list-ol" aria-hidden="true"></i> <?=$Ocounter . "." . $counter . " " . $o_counter . " " . $budget_line ?>
																								</legend>
																								<input type="hidden" name="budgetlineid<?= $outputid ?>[]" id="budgetlineid<?= $outputid ?>" value="<?= $budget_line_id ?>">
																								<div class="table-responsive">
																									<table class="table table-bordered">
																										<thead>
																											<tr>
																												<th> # </th>
																												<th> Description </th>
																												<th>Unit</th>
																												<th>Unit Cost</th>
																												<th>No. of Units</th>
																												<th>Total Cost</th>
																												<th>Add Other Details</th>
																												<th style="width:2%">
																													<button type="button" data-toggle="modal" data-target="#addFormModal"  data-backdrop="static" data-keyboard="false" name="addplus" title="Add another field" class="btn btn-success btn-sm" onclick="add_direct_cost(<?= $outputid ?>,<?= $budget_line_id ?>)"> 
																														<span class="glyphicon glyphicon-plus"></span>
																													</button>
																												</th>
																											</tr>
																										</thead>
																										<tbody id="budget_lines_table<?= $budget_lineid ?>">
																											<tr></tr>
																											<?php
																											$cost_type = 3;
																											$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
																											$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
																											$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
																											$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

																											$rowno = 1;
																											$other_sum = 0;
																											$pcounter=0;
																											if ($totalRows_rsOther_cost_plan > 0) {
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
																														$budget_line_rowno = $rowno + $budget_lineid;
																													?>
																														<tr id="row<?= $budget_line_rowno ?>">
																															<td> <?= $Ocounter . "." . $counter . " " . $rowno ?></td>
																															<td><?= $description ?></td>
																															<td> <?= $unit ?></td>
																															<td><?= $unit_cost ?></td>
																															<td><?= $units_no ?></td>
																															<td><?= $total_cost ?><td>
																																	<input type="hidden" name="dsmttimeline3<?= $budget_lineid ?>[]" value="<?= $timeline_id ?>" id="dsmttimeline3<?= $budget_line_rowno ?>">
																																	<input type="hidden" name="finid3<?= $budget_lineid ?>[]" value="<?= $fnid ?>" id="finid3<?= $budget_line_rowno ?>">
																																	<input type="hidden" class="modal_val" name="rmkid3<?= $budget_lineid ?>[]" value="<?= $rmkid ?>" title="The output <?= $outputName ?> has a other details not captured at Budget line <?= $budget_line ?> look at row number <?= $rowno ?>" id="rmkid3<?= $budget_line_rowno ?>">
																																	<a type="button" data-toggle="modal" data-id="<?= trim($budget_lineid) ?>" data-target="#addFormModal" onclick="add_direct_cost(<?= $outputid ?>, <?= $budget_line_rowno ?>, 3)" id="">
																																		<i class="glyphicon glyphicon-file"></i>
																																		<span id="addFormModalBtn3<?= $budget_line_rowno ?>">
																																			Edit Details
																																		</span>
																																	</a>
																															</td>
																															<td>
																																<button type="button" class="btn btn-danger btn-sm" onclick="delete_budget_lines_row<?= $budget_lineid ?>(' + budget_line_rowno_id + ', <?= $outputid ?>, <?= $budget_line_rowno ?>, 3 )">
																																	<span class="glyphicon glyphicon-minus"></span>
																																</button>
																															</td>
																														</tr>
																													<?php
																												} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																													$other_per  = ($other_sum / $outputCost) * 100;
																													$other_percent = number_format($other_per, 2);
																											} ?>
																										</tbody>
																										<tfoot id="budget_line_foot<?= $budget_lineid ?>">
																											<tr>
																													<td colspan="1"><strong>Sub Total</strong></td>
																													<td colspan="2">
																														<input type="hidden" name="subtotal_amounts" value="<?= $other_sum ?>" class="h_sub_total_amount3<?= $budget_lineid ?>" id="h_sub_total_amount3<?= $budget_lineid ?>">
																														<input type="text" name="subtotal_amount3<?= $budget_lineid ?>" value="<?= number_format($other_sum, 2) ?>" id="sub_total_amount3<?= $budget_lineid ?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																													<td colspan="1"> <strong>% Sub Total</strong></td>
																													<td colspan="1">
																														<input type="text" name="subtotal_percentage3<?= $budget_lineid ?>" value="<?= $other_percent ?> %" id="sub_total_percentage3<?= $budget_lineid  ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																													<td colspan="1"> <strong>Output Budget Bal</strong></td>
																													<td colspan="2">
																														<input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format(($outputCost - $output_remeinder), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																											</tr>
																										</tfoot>
																									</table>
																								</div>
																							</fieldset>
																						</div>
																					</div> 
																					<?php 
																				}
																				?>
																			</fieldset>
																			<?php
																		}
																		?>
																	</div>
																</div>
															<?php
															$summary  .= 
															'<tr>
																<td>' . $Ocounter . '</td>
																<td>' . $outputName . '</td>
																<td>' . number_format($outputCost, 2) . '</td>
																<td id="summaryOutput' . $outputid . '" >' . number_format($output_remeinder, 2) . '</td>
																<td id="perc' . $outputid .  '" >' . number_format((($output_remeinder / $projcost) * 100), 2) . ' %</td>
															</tr>';
														} while ($row_rsOutputs = $query_rsOutputs->fetch());
													}
												?>
											</fieldset>
											<fieldset class="scheduler-border" style="background-color:#ebedeb; border-radius:3px">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
													<i class="fa fa-file-text" aria-hidden="true"></i> Plan Summary
												</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
														<thead>
															<tr>
																<th width="2%">#</th>
																<th width="54%">Output</th>
																<th width="17%">Output Budget (Ksh)</th>
																<th width="17%">Amount Planned (Ksh)</th>
																<th width="10%">% Planned </th>
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
																<td>
																	<strong>
																		<?= number_format($projcost, 2) ?>
																	</strong>
																</td>
																<td>
																	<strong id="summary_total">
																	</strong>
																</td>
																<td>
																	<strong id="summary_percentage">
																	</strong>
																</td>
															</tr>
														</tfoot>
														</tbody>
													</table>
												</div>
											</fieldset>
										</form>
									</div>
								</div>
							</div>
						</div>
				</section>
				<!-- end body  -->
				<!-- add item -->
				<div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
									<div class="modal-header" style="background-color:#03A9F4">
										<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Add Other Details</span></h4>
									</div>
									<div class="modal-body">
										<div class="card">
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="body" id="add_modal_form">
														<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																	<i class="fa fa-calendar" aria-hidden="true"></i> Budgetline Details
																</legend>
																<div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
																	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
																		<div class="table-responsive">
																			<table class="table table-bordered">
																				<thead>
																					<tr>
																						<th colspan="3"> Description </th>
																						<th>Unit</th>
																						<th>Unit Cost</th>
																						<th>No. of Units</th>
																						<th>Total Cost</th> 
																					</tr>
																				</thead>
																				<tbody id="budget_lines_values_table"> 
																					<tr> 
																						<td colspan="3">
																							<input type="text" name="description" class="form-control" id="">
																						</td>
																						<td>
																							<input type="text" name="unit" class="form-control" id="">
																						</td>
																						<td>
																							<input type="number" name="unit_cost" min="0" class="form-control" onchange="calculate_total_cost()" onkeyup="calculate_total_cost()" id="unit_cost">
																						</td>
																						<td>
																							<input type="number" name="no_units" min="0" class="form-control" onchange="calculate_total_cost()" onkeyup="calculate_total_cost()" id="no_units">
																						</td>
																						<td>
																							<span id="subtotal_cost" style="color:red"></span>
																						</td>
																					</tr>
																				</tbody>
																				<tfoot id="budget_line_foot">
																					<tr>
																						<td colspan="1"><strong>Sub Total</strong></td>
																						<td colspan="2">
																							<input type="text" name="subtotal_amount" value="" id="sub_total_amount3" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="1"> <strong>% Sub Total</strong></td>
																						<td colspan="1">
																							<input type="text" name="subtotal_percentage" value="%" id="sub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="1"> <strong>Output Budget Bal</strong></td> 
																						<td colspan="2">
																							<input type="text" name="output_balance" id="output_balance" class="form-control output_cost_bal" value="" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																					</tr>
																				</tfoot>
																			</table>
																		</div>
																	</div>
																</div>
														</fieldset>
														<fieldset class="scheduler-border row setup-content" style="padding:10px">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Budget per Financial Year</legend>
															<div class="row clearfix " id="Targetrowcontainer">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																		<div class="card"> 
																			<div class="body">
																				<div class="row clearfix ">
																						<div class="col-md-12 " id="output_financial_years_table">
																							
																						</div>
																				</div>
																			</div> 
																		</div>
																</div>
															</div>
														</fieldset>
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-university" aria-hidden="true"></i> Add Financiers
															</legend>
															<?php
															if ($totalRows_rsProjFinancier > 0) {
															?>
																<div id="financier_detail">
																	<div class="col-md-8"></div>
																	<div class="col-md-4">
																		<h5>
																			<strong>
																				<span id="output_cost_ceiling" style="color: red"></span>
																			</strong>
																		</h5>
																		<input type="hidden" name="output_cost_fi_celing" id="output_cost_fi_celing">
																		<input type="hidden" name="total_financiers" id="total_financiers" class="form-control" value="<?= $totalRows_rsProjFinancier ?>">
																	</div>
																	<div class="col-md-12" id="projfinancier">
																		<div class="table-responsive">
																			<table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
																				<thead>
																					<tr>
																						<th width="10%">#</th>
																						<th width="30%" colspan="3">Financier</th>
																						<th width="30%">Ceiling</th>
																						<th width="30%">Amount</th>
																						<th width="5%" >
																							<button type="button" name="addplus" id="addplus_financier" onclick="add_row_financier();" class="btn btn-success btn-sm">
																								<span class="glyphicon glyphicon-plus">
																								</span>
																							</button>
																						</th>
																					</tr>
																				</thead>
																				<tbody id="financier_table_body">
																					<tr></tr>
																					<tr id="removeTr">
																						<td colspan="7">Add Financiers</td>
																					</tr>
																				</tbody>
																				<tfoot id="budget_line_foot">
																					<tr>
																						<td><strong>Sub Total</strong></td>
																						<td colspan="2">
																							<input type="text" name="project_used" value="" id="project_used" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="1"> <strong>% Sub Total</strong></td>
																						<td colspan="1">
																							<input type="text" name="project_used_percentage" value="%" id="sub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																						<td colspan="1"> <strong>Project Budget Bal</strong></td>
																						<td colspan="2">
																							<input type="text" name="project_balance_cost" id="project_balance_cost" class="form-control" value="" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																						</td>
																					</tr>
																				</tfoot>
																			</table>
																		</div>
																	</div>
																</div>
															<?php
															} else {
															?>
																<input type="hidden" name="total_financiers" id="total_financiers" class="form-control" value="<?= $totalRows_rsProjFinancier ?>">
															<?php
															}
															?>
														</fieldset>
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-comment" aria-hidden="true"></i> Add Remarks
															</legend>
															<div id="comment_section">
																<div class="col-md-12">
																	<label class="control-label">Remarks *:</label>
																	<br>
																	<div class="form-line">
																		<textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
																	</div>
																</div>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div> <!-- /modal-body -->
			
										<div class="modal-footer">
											<div class="col-md-12 text-center"> 
												<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
												<input type="hidden" name="output_id" id="output_id">
												<input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
												<button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">
													Save
												</button>
												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
											</div>
										</div> <!-- /modal-footer -->


								</form> <!-- /.form -->
							</div> <!-- /modal-content -->
						</div> <!-- /modal-dailog -->
					</div>
					<!-- End add item -->
				<?php
			}else{
				$results =  restriction();
				echo $results;
			}
		}else{
			$results =  restriction();
			echo $results;
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script src="assets/js/financialplan/index.js"></script>

<script>

	function get_financial_years(projid,output_id){
		if(projid && output_id){
			$.ajax({
				type: "get",
				url: "ajax/financialplan/index.php",
				data: {
					output_financial_years:"output_financial_years", 
					projid:projid,
					output_id:output_id,
				},
				dataType: "html",
				success: function (response) {
					$("#output_financial_years_table").html(response);
				}
			});
		}else{
			console.log("Error could not find")
		}
	}
	get_financial_years();
</script>