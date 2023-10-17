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
										<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
													<i class="fa fa-money" aria-hidden="true"></i> Financial Plan
												</legend>
												
													<?php
													if($totalRows_rsOutputs > 0){
														$Ocounter = 0;
														$summary = '';
														$output_cost_val = [];
														do {
															$Ocounter++; 
															$outputName = $row_rsOutputs['output'];
															$outputCost = $row_rsOutputs['budget'];
															$outputid = $row_rsOutputs['opid'];
															$output_cost_val[] = $outputid;
															$output_remeinder = 0;
																?>
																<div class="panel panel-primary">
																	<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
																		<i class="fa fa-caret-down" aria-hidden="true"></i>
																		<strong> Output <?= $Ocounter ?>:<span class=""><?= $outputName ?></span></strong>
																	</div>
																	<div class="collapse output<?php echo $outputid ?>" style="padding:5px"> 
																		<input type="hidden" name="opid[]" id="opid<?= $outputid ?>" value="<?= $outputid ?>">
																		<input type="hidden" name="outputcost" id="outputcost<?= $outputid ?>" class="outputcost" value="<?= $outputCost ?>">
																		<input type="hidden" name="output_name" id="output_name<?= $outputid ?>" class="output_name" value="<?= $outputName ?>">
																		<div class="col-md-8">
																		</div>
																		<div class="col-md-4 bg-brown">
																			<h5><strong> Output Budget (Ksh):<span class=""><?= number_format($outputCost, 2) ?></span></strong></h5>
																		</div>
																		<!-- start direct cost  -->
																		<div class="row clearfix" style="margin-top:3px; margin-bottom:3px">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
																												<td><?= $Ocounter . "." . 1 . "." . $mcounter   ?></td>
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

																												$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
																												$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																												$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																												$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
																												$dddescription = $unit = $unit_cost = $units_no = $rmkid = $total_cost = "";
																												$fnid = "";
																												$edit_direct_cost = false;
																												if($totalRows_rsDirect_cost_plan > 0){
																													$edit_direct_cost = true;
																													$unit = $row_rsDirect_cost_plan['unit'];
																													$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																													$units_no = $row_rsDirect_cost_plan['units_no'];
																													$rmkid = $row_rsDirect_cost_plan['id'];
																													$dddescription = $row_rsDirect_cost_plan['description'];
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
																												}
																												?>
																													<input type="hidden" name="taskid<?= $outputid ?>[]" id="taskid<?= $tkid ?>" value="<?= $tkid ?>">
																													<tr class="bg-grey">
																														<td><?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?></td>
																														<td colspan="3"><strong> Task:</strong> <?= $task ?></td>
																														<td colspan="2"><strong> Start Date:</strong> <?php echo date("d M Y", strtotime($sdate)); ?></td>
																														<td colspan="2"><strong> End Date:</strong> <?php echo date("d M Y", strtotime($edate)); ?></td>
																													</tr>

																													<tr class="task<?= $taskid ?>">
																														<td style="color:#FF5722">
																															1
																														</td>
																														<td>
																															<input type="text" name="ddescription<?= $taskid ?>[]" id="dddescription<?= $taskid ?>1" value="<?=$dddescription?>" class="form-control" placeholder="Item Descritpion" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																															<input type="text" name="dunit<?= $taskid ?>[]" id="dunit<?= $taskid ?>1" value="<?=$unit?>" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																															<input type="hidden" name="hunitcost<?= $taskid ?>[]" id="dhunitcost<?= $taskid ?>1" value="<?=$unit_cost?>">
																															<input type="number" name="dunitcost<?= $taskid ?>[]" id="dunitcost<?= $taskid ?>1" value="<?=$unit_cost?>" onchange="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1,1)" onkeyup="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1,1)" class="form-control" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																															<input type="hidden" name="htotalunits<?= $taskid ?>[]" id="dhtotalunits<?= $taskid ?>1" value="<?=$units_no?>">
																															<input type="number" name="dtotalunits<?= $taskid ?>[]" id="dtotalunits<?= $taskid ?>1" value="<?=$units_no?>" onkeyup="totalCost(<?= $tkid ?>, <?= $outputid ?>,1, 1)" onchange="totalCost(<?= $tkid ?>, <?= $outputid ?>,1,1)" class="form-control" placeholder="No of Units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																															<input type="hidden" name="htotalcost<?= $taskid ?>[]" id="dhtotalcost<?= $taskid ?>1"  value="<?=$total_cost?>">
																															<input type="text" name="dtotalcost<?= $taskid ?>[]" id="dtotalcost<?= $taskid ?>1"  value="<?=$total_cost?>" class="form-control totalCost summarytotal  output_cost<?= $outputid ?> direct_sub_total_amount1<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required disabled>
																														</td>
																														<td>
																															<input type="hidden" name="sdate<?= $taskid ?>[]" id="dsdate<?= $taskid ?>1" value="<?= $sdate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																															<input type="hidden" name="edate<?= $taskid ?>[]" id="dedate<?= $taskid ?>1" value="<?= $edate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																															<input type="hidden" name="dsmttimeline1<?= $taskid ?>[]" id="ddsmttimeline1<?= $taskid ?>1">
																															<input type="hidden" name="finid1<?= $taskid ?>[]" id="dfinid1<?= $taskid ?>1">
																															<input type="hidden" class="modal_val" name="rmkid1<?= $taskid ?>[]" id="drmkid1<?= $taskid ?>1" title="The Task <?= $task ?> under Milestone <?= $milestoneName ?> and Output <?= $outputName ?>">
																															<a type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="addFinancier(<?= $outputid ?>,<?= $tkid ?>, 1, 1)" id="">
																																<i class="glyphicon glyphicon-plus"></i>
																																<span id="daddFormModalBtn1<?= $taskid ?>1">
																																	<?= $edit_direct_cost ? "Edit Details" : "Add Details" ?>;
																																</span>
																															</a>
																														</td>
																														<td>
																															<button type="button" name="addplus" onclick="add_direct_row('<?= $taskid ?>', '<?= $outputid ?>', '<?= $tkid ?>')" title="Add another field" class="btn btn-success btn-sm">
																																<span class="glyphicon glyphicon-plus"></span>
																															</button>
																														</td>
																													</tr>
																												<?php
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
																			</div>
																		</div>
																		<!-- end  direct cost -->

																		<!-- start personnel -->
																		<div class="row clearfix" style="margin-top:3px; margin-bottom:3px">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<div class="pull-right">
																					<h6 style="color:#FF5722">
																						<strong> Output Budget (Ksh):
																							<span class=""> <?= number_format($outputCost, 2) ?>
																							</span>
																						</strong>
																					</h6>
																				</div>
																			</div>
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<fieldset class="scheduler-border">
																					<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																						<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter ?>.2 Personnel Project Cost
																					</legend>
																					<div class="table-responsive">
																						<table class="table table-bordered">
																							<thead>
																								<tr>
																									<th style="width: 3%">#</th>
																									<th style="width: 24%">Personnel </th>
																									<th style="width: 10%">Unit</th>
																									<th style="width: 15%">Unit Cost</th>
																									<th style="width: 10%">No. of Units</th>
																									<th style="width: 15%">Total Cost</th>
																									<th style="width: 12%">Other Details</th>
																									<th style="width: 5%">
																										<button type="button" name="addplus" onclick="add_personel_row<?= $outputid ?>();" title="Add another field" class="btn btn-success btn-sm">
																											<span class="glyphicon glyphicon-plus"></span>
																										</button>
																									</th>
																								</tr>
																							</thead>
																							<tbody id="personel_table<?= $outputid ?>"> 
																								<tr></tr>
																									<?php
																									$cost_type = 2;
																									$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid ");
																									$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid));
																									$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																									$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
																									$prowno = "";
																									$pcounter = 1;
																									$personnel_sum = 0;
																									$personnel_percent =0; 
																									if ($totalRows_rsDirect_cost_plan > 0) {
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


																												?>
																												<tr id="row<?= $prowno ?>">
																													<td></td>
																													<td>
																														<select name="personel<?= $outputid ?>[]" id="personelrow<?= $prowno ?>" class="form-control show-tick selectPersonel<?= $outputid ?>" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
																																<option value="">.... Select from list ....</option>
																																<?php 
																																$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projmembers m inner join users u on u.userid =m.ptid inner join tbl_projteam2 t on u.pt_id = t.ptid where m.projid = :projid");
																																$query_rsPersonel->execute(array(":projid"=>$projid));
																																$row_rsPersonel = $query_rsPersonel->fetch();
																																$totalRows_rsPersonel = $query_rsPersonel->rowCount();
																																do {
																																	$ptnid = $row_rsPersonel['userid'];
																																	$ptnname = $row_rsPersonel['fullname'];
																																	if ($personnel == $ptnid) {
																																		echo '<option value="' . $ptnid . '" selected>' . $ptnname . '</option>';
																																	} else {
																																		echo '<option value="' . $ptnid . '">' . $ptnname . '</option>';
																																	}
																																} while ($row_rsPersonel = $query_rsPersonel->fetch());
																																?>
																														</select>
																													</td>
																													<td>
																														<input type="text" name="punit<?= $outputid ?>[]" value="<?= $unit ?>" id="unit<?= $prowno ?>" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																													</td>
																													<td>
																														<input type="hidden" name="hunitcost<?= $outputid ?>" value="<?= $unit_cost ?>" id="hunitcost<?= $prowno ?>">
																														<input type="number" name="punitcost<?= $outputid ?>[]" value="<?= $unit_cost ?>" id="unitcost<?= $prowno ?>" class="form-control" onkeyup="number_of_units_change(<?= $prowno ?>, <?= $outputid ?>,2)" onchange="number_of_units_change(<?= $prowno ?>, <?= $outputid ?>,2)" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																													</td>
																													<td>
																														<input type="hidden" name="htotalunits<?= $outputid ?>" value="<?= $units_no ?>" id="htotalunits<?= $prowno ?>">
																														<input type="number" name="pnoofunits<?= $outputid ?>[]" value="<?= $units_no ?>" id="totalunits<?= $prowno ?>" class="form-control" onkeyup="totalCost(<?= $prowno ?>, <?= $outputid ?>,2)" onchange="totalCost(<?= $prowno ?>, <?= $outputid ?>,2)" placeholder="No of Units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																													</td>
																													<td>
																														<input type="hidden" name="htotalcost<?= $outputid ?>" value="<?= $total_cost ?>" id="htotalcost<?= $prowno ?>">
																														<input type="text" name="ptotalcost<?= $outputid ?>[]" value="<?= $total_cost ?>" id="totalcost<?= $prowno ?>" class="form-control summarytotal output_cost<?= $outputid ?> direct_sub_total_amount2<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																													</td>
																													<td>
																														<input type="hidden" name="dsmttimeline2<?= $outputid ?>[]" value="<?= $timeline_id ?>" id="dsmttimeline2<?= $prowno ?>">
																														<input type="hidden" name="finid2<?= $outputid ?>[]" value="<?= $fnid ?>" id="finid2<?= $prowno ?>">
																														<input type="hidden" class="modal_val" title="The output <?= $outputName ?> has a otherdetails not captured look at row number <?= $pcounter ?>" name="rmkid2<?= $outputid ?>[]" value="<?= $rmkid ?>" id="rmkid2<?= $prowno ?>">
																														<a type="button" data-toggle="modal" data-target="#addFormModal" onclick="addFinancier(<?= $outputid ?>,<?= $prowno ?>, 2)" id="">
																															<i class="glyphicon glyphicon-file"></i>
																															<span id="addFormModalBtn2<?= $prowno ?>">Edit Details</span>
																														</a>
																													</td>
																													<td>
																														<button type="button" class="btn btn-danger btn-sm" onclick=delete_personel_row<?= $outputid ?>(<?= $prowno ?>,<?= $outputid ?>,2)>
																																<span class="glyphicon glyphicon-minus"></span>
																														</button>
																													</td>
																												</tr>
																												<?php
																											} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
																										$personnel_per  = ($personnel_sum / $outputCost) * 100;
																										$personnel_percent = number_format($personnel_per, 2);
																									}else{ 
																										$prowno = 1 . $outputid;
																										?>
																										<tr id="row<?= $prowno ?>">
																											<td>1</td>
																											<td>
																												<select name="personel<?= $outputid ?>[]" id="personelrow<?= $prowno ?>" class="form-control show-tick selectPersonel<?= $outputid ?>" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
																													<option value="">.... Select from list ....</option>
																													<?php
																													$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projmembers m inner join users u on u.userid =m.ptid inner join tbl_projteam2 t on u.pt_id = t.ptid where m.projid = :projid");
																													$query_rsPersonel->execute(array(":projid" => $projid));
																													$row_rsPersonel = $query_rsPersonel->fetch();
																													$totalRows_rsPersonel = $query_rsPersonel->rowCount();
																													if($totalRows_rsPersonel > 0){
																														do {
																															$ptnid = $row_rsPersonel['userid'];
																															$ptntitle = $row_rsPersonel['title'];
																															$ptnname = $row_rsPersonel['fullname'];
																															$membername = $ptntitle . "." . $ptnname;
																															echo '<option value="' . $ptnid . '">' . $membername . '</option>';
																														} while ($row_rsPersonel = $query_rsPersonel->fetch());
																													}
																													?>
																												</select>
																											</td>
																											<td>
																												<input type="text" name="punit<?= $outputid ?>[]" value="" id="unit<?= $prowno ?>" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																											</td>
																											<td>
																												<input type="hidden" name="hunitcost<?= $outputid ?>" value="" id="hunitcost<?= $prowno ?>">
																												<input type="number" name="punitcost<?= $outputid ?>[]" value="" id="unitcost<?= $prowno ?>" class="form-control" onkeyup="number_of_units_change(<?= $prowno ?>, <?= $outputid ?>,2)" onchange="number_of_units_change(<?= $prowno ?>, <?= $outputid ?>,2)" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																											</td>
																											<td>
																												<input type="hidden" name="htotalunits<?= $outputid ?>" value="" id="htotalunits<?= $prowno ?>">
																												<input type="number" name="pnoofunits<?= $outputid ?>[]" value="" id="totalunits<?= $prowno ?>" class="form-control" onkeyup="totalCost(<?= $prowno ?>, <?= $outputid ?>,2)" onchange="totalCost(<?= $prowno ?>, <?= $outputid ?>,2)" placeholder="No of Units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																											</td>
																											<td>
																												<input type="hidden" name="htotalcost<?= $outputid ?>" value="" id="htotalcost<?= $prowno ?>">
																												<input type="text" name="ptotalcost<?= $outputid ?>[]" value="" id="totalcost<?= $prowno ?>" class="form-control summarytotal output_cost<?= $outputid ?> direct_sub_total_amount2<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																											</td>
																											<td>
																												<input type="hidden" name="dsmttimeline2<?= $outputid ?>[]" value="" id="dsmttimeline2<?= $prowno ?>">
																												<input type="hidden" name="finid2<?= $outputid ?>[]" value="" id="finid2<?= $prowno ?>">
																												<input type="hidden" class="modal_val" title="Personnel Other details for output: <?= $outputName ?>, has not been captured. Please look at row number <?= $pcounter ?>" name="rmkid2<?= $outputid ?>[]" value="<?= $rmkid ?>" id="rmkid2<?= $prowno ?>">
																												<a type="button" data-toggle="modal" data-target="#addFormModal" onclick="addFinancier(<?= $outputid ?>,<?= $prowno ?>, 2)" id="">
																													<i class="glyphicon glyphicon-file"></i>
																													<span id="addFormModalBtn2<?= $prowno ?>">
																														Add Details
																													</span>
																												</a>
																											</td>
																											<td>
																											</td>
																										</tr>
																									<?php 
																									}
																									?>
																							</tbody>
																							<tfoot id="pfoot<?= $outputid ?>">
																								<tr>
																									<td colspan="5" align="right"><strong>Sub-Total</strong></td>
																									<td colspan="1">
																										<input type="text" name="d_sub_total_amount" id="sub_total_amount2<?= $outputid ?>" value="<?=number_format($personnel_sum,2)?>" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																									<td colspan="2">
																										<strong style="height:35px; width:50%; float:left">% Sub-Total</strong>
																										<input type="text" name="d_sub_total_percentage" id="sub_total_percentage2<?= $outputid ?>"  value="<?=$personnel_percent?>" class="form-control" placeholder="% sub-total" style="height:35px; width:50%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif; float:left" disabled>
																									</td>
																								</tr>
																								<tr>
																									<td colspan="6"> <strong>Output Budget Balance</strong></td>
																									<td colspan="2">
																										<input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format($outputCost - $output_remeinder, 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																									</td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>
																					<script>
																						//  personel
																						// function to add personell table row
																						function add_personel_row<?= $outputid ?>() {
																							$prsrowno = $("#personel_table<?= $outputid ?> tr").length;
																							$prsrowno = $prsrowno + 1;
																							var outputid = <?= $outputid ?>;
																							$prowno = $prsrowno + outputid.toString();
																							getpersonel<?= $outputid ?>($prowno);
																							$("#personel_table<?= $outputid ?> tr:last").after(
																								'<tr id="row' +
																								$prowno +
																								'"> ' +
																								"<td></td>" +
																								"<td>" +
																								'<select name="personel<?= $outputid ?>[]" id="personelrow' +
																								$prowno +
																								'" class="form-control show-tick selectPersonel<?= $outputid ?>" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">' +
																								"</select></td>" +
																								"<td>" +
																								'<input type="text" name="punit<?= $outputid ?>[]" id="unit' + $prowno + '" class="form-control"   placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																								"</td>" +
																								"<td>" +
																								'<input type="hidden" name="hunitcost<?= $outputid ?>" id="hunitcost' + $prowno + '">' +
																								'<input type="number" name="punitcost<?= $outputid ?>[]" id="unitcost' + $prowno + '" class="form-control" onkeyup="number_of_units_change(' + $prowno + ', <?= $outputid ?>,2)" onchange="number_of_units_change(' + $prowno + ', <?= $outputid ?>,2)" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																								"</td>" +
																								"<td>" +
																								'<input type="hidden" name="htotalunits<?= $taskid ?>" id="htotalunits' + $prowno + '">' +
																								'<input type="number" name="pnoofunits<?= $outputid ?>[]" id="totalunits' + $prowno + '" class="form-control" onkeyup="totalCost(' + $prowno + ', <?= $outputid ?>,2)" onchange="totalCost(' + $prowno + ', <?= $outputid ?>,2)" placeholder="No of Units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																								"</td>" +
																								"<td>" +
																								'<input type="hidden" name="htotalcost<?= $taskid ?>" id="htotalcost' + $prowno + '">' +
																								'<input type="text" name="ptotalcost<?= $outputid ?>[]" id="totalcost' + $prowno + '" class="form-control summarytotal output_cost<?= $outputid ?> direct_sub_total_amount2<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>' +
																								"</td><td>" +
																								'<input type="hidden" name="dsmttimeline2<?= $outputid ?>[]" id="dsmttimeline2' + $prowno + '">' +
																								'<input type="hidden" name="finid2<?= $outputid ?>[]" id="finid2' + $prowno + '">' +
																								'<input type="hidden" class="modal_val" title="Personnel Other details for output: <?= $outputName ?>, has not been captured. Please look at row number <?= $rowno ?>" name="rmkid2<?= $outputid ?>[]" id="rmkid2' + $prowno + '">' +
																								'<a type="button" data-toggle="modal" data-target="#addFormModal" onclick="addFinancier(<?= $outputid ?>,' + $prowno + ', 2)" id="">' +
																								'<i class="glyphicon glyphicon-file"></i>' +
																								'<span id="addFormModalBtn2' + $prowno + '">' +
																								'Add Details' +
																								'</span>' +
																								'</a>' +
																								"</td>" +
																								"<td>" +
																								'<button type="button" class="btn btn-danger btn-sm"  onclick=delete_personel_row<?= $outputid ?>(' + $prowno + ',<?= $outputid ?>,2)>' +
																								'<span class="glyphicon glyphicon-minus"></span>' +
																								"</button>" +
																								"</td></tr >"
																							);
																							numbering<?= $outputid ?>();
																						}

																						// function to delete personel table rows
																						function delete_personel_row<?= $outputid ?>(tkid, opid, number) {
																							var handler = delete_other_info(opid, tkid, number);
																							if (handler) {
																								$("#row" + tkid).remove();
																								numbering<?= $outputid ?>();
																							}
																						}

																						// auto numbering table rows on delete and add new for personel table
																						function numbering<?= $outputid ?>() {
																							$("#personel_table<?= $outputid ?> tr").each(function(idx) {
																								$(this)
																									.children()
																									.first()
																									.html(idx + 1);
																							});
																						}

																						// gets personel upon add new row
																						function getpersonel<?= $outputid ?>($prowno) {
																							var projid = <?= $projid ?>;
																							$.ajax({
																								type: "post",
																								url: "general-settings/action/add-financial-plan-process",
																								data: {
																									getpersonel: "getpersonel",
																									projid: projid
																								},
																								dataType: "html",
																								success: function(response) {
																									//console.log("#personelrow" + $prowno);
																									$("#personelrow" + $prowno).html(response);
																								}
																							});
																						}

																						//filter the personell cannot be selected more than once
																						$(document).on("change", ".selectPersonel<?= $outputid ?>", function(e) {
																							var tralse = true;
																							var selectOutput_arr = []; // for contestant name
																							var attrb = $(this).attr("id");
																							var selectedid = "#" + attrb;
																							var selectedText = $(selectedid + " option:selected").html();
																							$(".selectPersonel<?= $outputid ?>").each(function(k, v) {
																								var getVal = $(v).val();
																								if (getVal && $.trim(selectOutput_arr.indexOf(getVal)) != -1) {
																									tralse = false;
																									alert("You canot select Personel " + selectedText + " more than once ");
																									$(v).val("");
																									return false;
																								} else {
																									selectOutput_arr.push($(v).val());
																								}
																							});
																							if (!tralse) {
																								return false;
																							}
																						});
																					</script>
																				</fieldset>
																			</div>
																		</div>
																		<!-- end personnel -->

																		<!-- start query budget lines -->
																		<?php
																		$query_rsBilling = $db->prepare("SELECT * FROM tbl_budget_lines WHERE status=1");
																		$query_rsBilling->execute();
																		$row_rsBilling = $query_rsBilling->fetch();
																		$totalRows_rsBilling = $query_rsBilling->rowCount();
																		$counter = 2;
																		if ($totalRows_rsBilling > 0) {
																			do {
																				$counter++;
																				$budget_line = $row_rsBilling['name'];
																				$budget_line_id = $row_rsBilling['id'];
																				$budget_lineid = $budget_line_id . $outputid;
																				?>
																				<div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
																					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																						<div class="pull-right">
																							<h6 style="color:#FF5722">
																								<strong> Output Budget (Ksh):
																									<span class="output_cost_bal"> <?= number_format($outputCost, 2) ?>
																									</span>
																								</strong>
																							</h6>
																						</div>
																					</div>
																					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																						<fieldset class="scheduler-border">
																							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																								<i class="fa fa-list-ol" aria-hidden="true"></i> <?= $Ocounter . "." . $counter . " " . $budget_line ?>
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
																												<button type="button" name="addplus" onclick="add_budget_lines_row<?= $budget_lineid ?>();" title="Add another field" class="btn btn-success btn-sm">
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
																														<td>
																														</td>
																														<td>
																																<input type="text" name="budget_line_description<?= $budget_lineid ?>[]" value="<?= $description ?>" id="description<?= $budget_line_rowno ?>" class="form-control" placeholder="Description" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																																<input type="text" name="budget_lineunit<?= $budget_lineid ?>[]" value="<?= $unit ?>" id="unit<?= $budget_line_rowno ?>" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																																<input type="hidden" name="hbudget_lineunitcost<?= $outputid ?>" value="<?= $unit_cost ?>" id="hunitcost<?= $budget_line_rowno ?>">
																																<input type="number" name="budget_lineunitcost<?= $budget_lineid ?>[]" value="<?= $unit_cost ?>" id="unitcost<?= $budget_line_rowno ?>" class="form-control" onkeyup="number_of_units_change(<?= $budget_line_rowno ?>, <?= $outputid ?>, 3)" onchange="number_of_units_change(<?= $budget_line_rowno ?>, <?= $outputid ?>, 3)" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																																<input type="hidden" name="hbudget_linenoofunits<?= $outputid ?>" value="<?= $units_no ?>" id="htotalunits<?= $budget_line_rowno ?>">
																																<input type="number" name="budget_linenoofunits<?= $budget_lineid ?>[]" value="<?= $units_no ?>" id="totalunits<?= $budget_line_rowno ?>" class="form-control" data-id="<?= trim($budget_lineid) ?>" onchange="totalCost(<?= $budget_line_rowno ?>, <?= $outputid ?>, 3)" onkeyup="totalCost(<?= $budget_line_rowno ?>, <?= $outputid ?>, 3)" placeholder="No. of units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																														</td>
																														<td>
																																<input type="hidden" name="hbudget_linetotalcost<?= $outputid ?>" value="<?= $total_cost ?>" id="htotalcost<?= $budget_line_rowno ?>">
																																<input type="text" name="budget_linetotalcost<?= $budget_lineid ?>[]" value="<?= $total_cost ?>" id="totalcost<?= $budget_line_rowno ?>" class="form-control summarytotal output_cost<?= $outputid ?> direct_sub_total_amount3<?= $budget_lineid  ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																														</td>
																														<td>
																																<input type="hidden" name="dsmttimeline3<?= $budget_lineid ?>[]" value="<?= $timeline_id ?>" id="dsmttimeline3<?= $budget_line_rowno ?>">
																																<input type="hidden" name="finid3<?= $budget_lineid ?>[]" value="<?= $fnid ?>" id="finid3<?= $budget_line_rowno ?>">
																																<input type="hidden" class="modal_val" name="rmkid3<?= $budget_lineid ?>[]" value="<?= $rmkid ?>" title="The output <?= $outputName ?> has a other details not captured at Budget line <?= $budget_line ?> look at row number <?= $rowno ?>" id="rmkid3<?= $budget_line_rowno ?>">
																																<a type="button" data-toggle="modal" data-id="<?= trim($budget_lineid) ?>" data-target="#addFormModal" onclick="addFinancier(<?= $outputid ?>, <?= $budget_line_rowno ?>, 3)" id="">
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
																				<script>
																					$(document).ready(function() {
																						pfoot<?= $budget_lineid ?>();
																					});
																					// show personel footer if rows exists
																					function pfoot<?= $budget_lineid ?>() {
																						$rowno = $("#budget_lines_table<?= $budget_lineid ?> tr").length;
																						if ($rowno == 1) {
																							$("#budget_line_foot<?= $budget_lineid ?>").hide();
																						} else {
																							$("#budget_line_foot<?= $budget_lineid ?>").show();
																						}
																					}

																					// auto numbering table rows on delete and add new for personel table
																					function numbering<?= $budget_lineid ?>() {
																						$("#budget_lines_table<?= $budget_lineid ?> tr").each(function(idx) {
																							$(this)
																								.children()
																								.first()
																								.html(idx - 1 + 1);
																						});
																					}

																					function add_budget_lines_row<?= $budget_lineid ?>() {
																						$rowno = $("#budget_lines_table<?= $budget_lineid ?> tr").length;
																						$rowno = $rowno + 1;
																						var budgetid = <?= $budget_lineid ?>;
																						var budget_line_id = <?= $outputid ?> + budgetid;
																						$budget_line_rowno = $rowno + budgetid.toString();
																						var budget_line_rowno_id = $budget_line_rowno;

																						$("#budget_lines_table<?= $budget_lineid ?> tr:last").after(
																							'<tr id="row' + $budget_line_rowno + '"> ' +
																							'<td>' +
																							'</td>' +
																							'<td>' +
																							'<input type="text" name="budget_line_description<?= $budget_lineid ?>[]" id="description' + $budget_line_rowno + '" class="form-control" placeholder="Description" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																							'</td>' +
																							'<td>' +
																							'<input type="text" name="budget_lineunit<?= $budget_lineid ?>[]" id="unit' + $budget_line_rowno + '" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																							'</td>' +
																							'<td>' +
																							'<input type="hidden" name="hbudget_lineunitcost<?= $outputid ?>" id="hunitcost' + $budget_line_rowno + '">' +
																							'<input type="number" name="budget_lineunitcost<?= $budget_lineid ?>[]" id="unitcost' + $budget_line_rowno + '" class="form-control" onkeyup = "number_of_units_change(' + $budget_line_rowno + ', <?= $outputid ?>, 3)" onchange = "number_of_units_change(' + $budget_line_rowno + ', <?= $outputid ?>, 3)" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																							'</td>' +
																							'<td>' +
																							'<input type="hidden" name="hbudget_linenoofunits<?= $outputid ?>" id="htotalunits' + $budget_line_rowno + '">' +
																							'<input type="number" name="budget_linenoofunits<?= $budget_lineid ?>[]" id="totalunits' + $budget_line_rowno + '" class="form-control" data-id="<?= trim($budget_lineid) ?>" onchange="totalCost(' + $budget_line_rowno + ', <?= $outputid ?>, 3)" onkeyup="totalCost(' + $budget_line_rowno + ', <?= $outputid ?>, 3)" placeholder="No. of units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
																							'</td>' +
																							'<td>' +
																							'<input type="hidden" name="hbudget_linetotalcost<?= $outputid ?>" id="htotalcost' + $budget_line_rowno + '">' +
																							'<input type="text" name="budget_linetotalcost<?= $budget_lineid ?>[]" id="totalcost' + $budget_line_rowno + '" class="form-control summarytotal output_cost<?= $outputid ?> direct_sub_total_amount3<?= $budget_lineid  ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>' +
																							'</td>' +
																							'<td>' +
																							'<input type="hidden" name="dsmttimeline3<?= $outputid ?>[]" id="dsmttimeline3' + $budget_line_rowno + '">' +
																							'<input type="hidden"  name="finid3<?= $outputid ?>[]" id="finid3' + $budget_line_rowno + '">' +
																							'<input type="hidden" class="modal_val" name="rmkid3<?= $budget_lineid ?>[]" title="<?= $budget_line ?> Other details for output: <?= $outputName ?>, has not been captured. Please look at row number <?= $rowno ?>" id="rmkid3' + $budget_line_rowno + '">' +
																							'<a type="button" data-toggle="modal" data-id="<?= trim($budget_lineid) ?>" data-target="#addFormModal" onclick="addFinancier(<?= $outputid ?>, ' + $budget_line_rowno + ', 3)" id="">' +
																							'<i class="glyphicon glyphicon-file"></i>' +
																							'<span id = "addFormModalBtn3' + $budget_line_rowno + '" >' +
																							'Add Details' +
																							'</span>' +
																							'</a>' +
																							'</td>' +
																							'<td>' +
																							'<button type="button" class="btn btn-danger btn-sm"  onclick="delete_budget_lines_row<?= $budget_lineid ?>(' + budget_line_rowno_id + ', <?= $outputid ?>, ' + $budget_line_rowno + ', 3 )" >' +
																							'<span class="glyphicon glyphicon-minus"></span>' +
																							'</button>' +
																							'</td></tr >');
																						numbering<?= $budget_lineid ?>();
																						pfoot<?= $budget_lineid ?>();
																					}

																					function delete_budget_lines_row<?= $budget_lineid ?>(rowno, opid, tkid, number) {
																						var handler = delete_other_info(opid, tkid, number);
																						if (handler) {
																							$("#row" + rowno).remove();
																							numbering<?= $budget_lineid ?>();
																							pfoot<?= $budget_lineid ?>();
																						}
																					}
																				</script>
																				<?php
																			} while ($row_rsBilling = $query_rsBilling->fetch());
																		}
																		?>
																		<!-- end query budget lines -->
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


											<input type="hidden" name="output_cost_val" id="output_cost_val" class="" value="<?= implode(",", $output_cost_val) ?>">
											<div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
												<div class="col-md-6 text-center">
													<input type="hidden" name="projcost" id="projcost" value="<?= $projcost ?>">
													<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
													<input type="hidden" name="MM_insert" value="add_budget_line_frm">
													<input type="hidden" name="username" value="<?= $user_name ?>">
													<input type="hidden" name="implimentation_type" value="<?= $implimentation_type ?>">
													<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
												</div>
											</div>
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
														<fieldset class="scheduler-border" id="timeline_div">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
																<i class="fa fa-calendar" aria-hidden="true"></i> Add Disbusement Timeline
															</legend>
															<div class="timeline">


																<div class="col-md-6">
																	<label class="control-label">Project Start Date *:</label>
																	<div class="form-line">
																		<input type="date" name="projstartdate" id="projstartdate" value="<?= $projstartdate ?>" placeholder="Project End Date" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>
																<div class="col-md-6">
																	<label class="control-label">Project End Date *:</label>
																	<div class="form-line">
																		<input type="date" name="projenddate" id="projenddate" value="<?= $projenddate ?>" placeholder="Project End Date" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>

																<div class="col-md-6">
																	<label class="control-label">Description *:</label>
																	<div class="form-line">
																		<input type="text" name="output_description" id="output_description" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>
																<div class="col-md-6">
																	<label class="control-label">Unit *:</label>
																	<div class="form-line">
																		<input type="text" name="output_unit" id="output_unit"  class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>
																<div class="col-md-4">
																	<label class="control-label">Unit Cost *:</label>
																	<div class="form-line">
																		<input type="text" name="output_unit_cost" id="output_unit_cost" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>
																<div class="col-md-4">
																	<label class="control-label">No Units *:</label>
																	<div class="form-line">
																		<input type="text" name="output_units_no" id="output_units_no" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
																	</div>
																</div>
																<div class="col-md-4">
																	<label class="control-label">Disbursement Date *:</label>
																	<div class="form-line">
																		<input type="date" name="timelinedate" id="timelinedate" onchange="validate_timeline_date()" placeholder="Add Timeline Date" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
																		<span style="color: red" id="timeline_date"></span>
																	</div>
																</div>
																<div class="col-md-6" id="responsible_div">
																	<label class="control-labelb">Responsible *:</label>
																	<div class="form-line">
																		<select name="responsible" id="responsible" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
																			<option value="">.... Select from list ....</option>
																			<?php

																			$query_reportUser =  $db->prepare("SELECT * FROM tbl_projmembers m inner join users u on u.userid =m.ptid inner join tbl_projteam2 t on u.pt_id = t.ptid where m.projid = :projid");
																			$query_reportUser->execute(array(":projid" => $projid));
																			$row_reportUser = $query_reportUser->fetch();
																			if($row_reportUser ){
																				do {
																				?>
																					<option value="<?php echo $row_reportUser['ptid'] ?>"><?php echo $row_reportUser['fullname'] ?></option>
																				<?php
																				} while ($row_reportUser = $query_reportUser->fetch());
																			}
																			?>
																		</select>
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
																				<span id="output_cost_ceiling" style="color: red">

																				</span>
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
																						<th width="30%">Financier</th>
																						<th width="30%">Ceiling</th>
																						<th width="30%">Amount</th>
																						<th width="5%">
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
																						<td colspan="5">Add Financiers</td>
																					</tr>
																				</tbody>
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
												<input type="hidden" name="newitem" id="newitem" value="new">
												<input type="hidden" name="foutputid" id="foutputid" value="">
												<input type="hidden" name="ftype" id="ftype" value="">
												<input type="hidden" name="fplanid" id="fplanid" value="">
												<input type="hidden" name="rowno" id="rowno" value="">
												<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
												<input type="hidden" name="classType" id="classType" value="">
												<div id="edit-item"> </div>
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

<script src="assets/custom js/add-financial-plan.js"></script>
