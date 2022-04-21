<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header" align="center">
                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="40" style="padding-left:5px; background-color:#000; color:#FFF; font-size:16px"><div align="left" ><strong><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit Procurement Details</strong></div></td>
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
				<!--<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
					<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
						<i class="fa fa-university" style="color:#F44336" aria-hidden="true"></i> Funding Details
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
								/* $rowno = 0;
								$totalAmount = 0;
								if ($totalRows_rsProjFinancier > 0) {
									do {
										$rowno++;
										$progfundid =  $row_rsProjFinancier['progfundid'];

										$query_rsProcurement = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND funder=:funder AND type=:type");
										$query_rsProcurement->execute(array(":projid" => $projid, ":funder" => $progfundid, ":type" => 1));
										$row_plan = $query_rsProcurement->fetch();
										$totalRows_Procurement = $query_rsProcurement->rowCount();
										$contribution_amount = $row_plan['funds'];
										$totalAmount = $contribution_amount + $totalAmount;

										$query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid =:progid");
										$query_rsFunding->execute(array(":progid" => $progid));
										$row_rsFunding = $query_rsFunding->fetch();
										$totalRows_rsFunding = $query_rsFunding->rowCount();

										$inputs = '';
										do {
											$source = $row_rsFunding['sourceid'];
											$progfundids = $row_rsFunding['id'];

											if ($row_rsFunding['sourcecategory']  == "donor") {
												$query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid=:source");
												$query_rsDonor->execute(array(":source" => $source));
												$row_rsDonor = $query_rsDonor->fetch();
												$totalRows_rsDonor = $query_rsDonor->rowCount();
												$donor = $row_rsDonor['donorname'];

												if ($row_rsFunding['id'] == $progfundid) {
													$inputs .= '<span>' . $donor . '</span>';
												}
											} else if ($row_rsFunding['sourcecategory']  == "others") {
												$query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id=:source");
												$query_rsFunder->execute(array(":source" => $source));
												$row_rsFunder = $query_rsFunder->fetch();
												$totalRows_rsFunder = $query_rsFunder->rowCount();
												$funder = $row_rsFunder['name'];

												if ($row_rsFunding['id'] == $progfundid) {
													$inputs .= '<span>' . $funder . '</span>';
												}
											}
										} while ($row_rsFunding = $query_rsFunding->fetch());
										if ($contribution_amount > 0) { */
											?>
											<tr id="row<?//= $rowno ?>">
												<td>
													<?//= $rowno ?>
												</td>
												<td>
													<?php //echo $inputs ?>
												</td>
												<td align="right">
													<?php // echo number_format($contribution_amount, 2); ?>
												</td>
											</tr>
											<?php
										/* }
									} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
								} else { */
									?>
									<tr>
										<td colspan="5">No Financier Found</td>
									</tr>
								<?php
								//}
								?>
							<tfoot>
								<tr>
									<td colspan="2"><strong>Total Amount</strong></td>
									<td align="right"><strong><?//= number_format($totalAmount, 2) ?></strong></td>
								</tr>
							</tfoot>
							</tbody>
						</table>
					</div>
                </fieldset>-->
                <?php
                $query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND type=:type");
                $query_rsTender->execute(array(":projid" => $projid, ":type" => 1));
                $row_plan = $query_rsTender->fetch();
                $totalRows_Tender = $query_rsTender->rowCount();
                $contribution_val = $row_plan['funds'];
                ?>
                <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
					<?php
					if($projcategory==2){

						$query_tenderdetails = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid AND td_id = :tdid");
						$query_tenderdetails->execute(array(":projid" => $projid, ":tdid" => $projtenderid));
						$row_tenderdetails = $query_tenderdetails->fetch();
						$totalRows_tenderdetails = $query_tenderdetails->rowCount();
						$tenderid = $row_tenderdetails["td_id"];
						$tendertypeid = $row_tenderdetails["tendertype"];
						$tendercat = $row_tenderdetails["tendercat"];
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
						<input type="hidden" name="tenderid" id="tenderid" value="<?=$tenderid?>">
						<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-cogs" style="color:#F44336" aria-hidden="true"></i> Contractor Details</legend>
							<div class="col-md-12">
								<div class="form-inline">
									<label for="">Contractor Name</label>
									<select name="projcontractor" id="projcontractor"  class="form-control show-tick require" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required="required">
										<option value="">.... Select Project Contractor from list ....</option>
										<?php
										$query_contractor = $db->prepare("SELECT * FROM tbl_contractor WHERE pinstatus='1'");
										$query_contractor->execute();
										$row_contractor = $query_contractor->fetch();
										
										do {  
											$contrid = $row_contractor['contrid'];
											$contractorname = $row_contractor['contractor_name'];
											if ($contractor == $contrid) {
												echo '<option value="' . $contrid . '" selected>' . $contractorname . '</option>';
											} else {
												echo '<option value="' . $contrid . '">' . $contractorname . '</option>';
											}
										} while ($row_contractor = $query_contractor->fetch());
										?>
									</select>
								</div>
							</div>
							<div id="contrinfo">
								<div class="col-md-4"> 
									<label for="">Pin Number</label>
									<input type="text" name="pinnumber" id="pinnumber" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?=$row_contractordetail["pinno"]?>" disabled="disabled">
								</div>
								<div class="col-md-4">
									<label for="">Business Reg No.</label>
									<input type="text" name="bizregno" id="bizregno" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?=$row_contractordetail["busregno"]?>" disabled="disabled">
								</div>
								<div class="col-md-4">
									<label for="">Business Type</label>
									<input type="text" name="biztype" id="biztype" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="<?=$biztype?>" disabled="disabled">
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
									<input type="text" name="tendertitle" id="tendertitle" class="form-control" value="<?=$row_tenderdetails["tendertitle"]?>" required>
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
											<select name="tendercat" id="tendercat" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
												<option value="">.... Select Category ....</option>
												<?php												
												do {  
													$catid = $row_rscategory['id'];
													$contractorcat = $row_rscategory['category'];
													if ($tendercat == $catid) {
														echo '<option value="' . $catid . '" selected>' . $contractorcat . '</option>';
													} else {
														echo '<option value="' . $catid . '">' . $contractorcat . '</option>';
													}
												} while ($row_rscategory = $query_rscategory->fetch());
												?>
											</select>
										</td>
										<td>
											<select name="tendertype" id="tendertype" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
												<option value="">.... Select Type ....</option>
												<?php												
												do {  
													$tndtypeid = $row_rstender['id'];
													$tndtype = $row_rstender['type'];
													if ($tendertypeid == $tndtypeid) {
														echo '<option value="' . $tndtypeid . '" selected>' . $tndtype . '</option>';
													} else {
														echo '<option value="' . $tndtypeid . '">' . $tndtype . '</option>';
													}
												} while ($row_rstender = $query_rstender->fetch());
												?>
											</select>
										</td>
										<td>
											<select name="procurementmethod" id="procurementmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
													<option value="">Select Procurement Method</option>
													<?php												
													do {  
														$methodid = $row_rsprocurementmethod['id'];
														$method = $row_rsprocurementmethod['method'];
														if ($procurementmethod == $methodid) {
															echo '<option value="' . $methodid . '" selected>' . $method . '</option>';
														} else {
															echo '<option value="' . $methodid . '">' . $method . '</option>';
														}
													} while ($row_rsprocurementmethod = $query_rsprocurementmethod->fetch());
													?>
											</select>
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
												<input name="contractrefno" type="text"  id="contractrefno" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Contract Ref Number" value="<?=$row_tenderdetails["contractrefno"]?>" required/>
											</div>
										</td>
										<td> 
											<div class="form-line">
												<input name="tenderno" type="text"  id="tenderno" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Tender Number" value="<?=$row_tenderdetails["tenderno"]?>" required/>
											</div>
										</td>
										<td>
											<div class="form-line">
												<input name="technicalscore" type="number" id="technicalscore" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Technical Score" value="<?=$row_tenderdetails["technicalscore"]?>" required/>
											</div>
										</td>
										<td>
											<div class="form-line">
												<input name="financialscore" type="number"  id="financialscore" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Funancial Score" value="<?=$row_tenderdetails["financialscore"]?>" required/>
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
												<input name="tenderevaluationdate" type="date" id="tenderevaluationdate" class="form-control" placeholder="Enter Tender Evaluation date" value="<?=$row_tenderdetails["evaluationdate"]?>" required/>
											</div>
										</td>
										<td>  
											<div class="form-line">
												<input name="tenderawarddate" type="date" id="tenderawarddate" class="form-control" placeholder="Enter Award date" value="<?=$row_tenderdetails["awarddate"]?>" required/>
											</div>
										</td>
										<td>
											<div class="form-line">
												<input name="tendernotificationdate" type="date" id="tendernotificationdate" class="form-control" placeholder="Enter Notification Date" value="<?=$row_tenderdetails["notificationdate"]?>" required/>
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
												<input name="tendersignaturedate" type="date" id="tendersignaturedate" class="form-control" placeholder="Click Signature Date" value="<?=$row_tenderdetails["signaturedate"]?>" required/>
											</div>
										</td>
										<td> 
											<div class="form-line">
												<input name="tenderstartdate" type="date" id="tenderstartdate" class="form-control" placeholder="Click Start Date" value="<?=$row_tenderdetails["startdate"]?>" required/>
											</div>
										</td>
										<td>
											<div class="form-line">
												<input name="tenderenddate" type="date" id="tenderenddate" class="form-control"  placeholder="Click End Date" value="<?=$row_tenderdetails["enddate"]?>" required/>
											</div>
										</td>
									</tr>
								</table>
							</div>

							<div class="col-md-12">
								<label class="control-label">Contract/Tender Comments *:</label>
								<p align="left">
								<textarea name="comments" cols="45" rows="5" class="form-control" required="required"><?=$row_tenderdetails["comments"]?></textarea>
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
						do {
							$Ocounter++;
							//get indicator
							$outputName = $row_rsOutputs['output'];
							$outputCost = $row_rsOutputs['budget'];
							$outputid = $row_rsOutputs['opid'];
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
									<div class="col-md-8">
									</div>
									<div class="col-md-4 bg-brown">
										<h5>
											<strong> Procurement Budget (Ksh):
												<span class="">
													<?= number_format($contribution_amount, 2) ?>
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
															<th style="width:15%">Unit</th>
															<th style="width:20%">Unit Cost (Ksh)</th>
															<th style="width:10%">No. of Units</th>
															<th style="width:20%">Total Cost (Ksh)</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid='$projid' and outputid ='$outputid' ORDER BY sdate ");
														$query_rsMilestones->execute();
														$row_rsMilestones = $query_rsMilestones->fetch();
														$totalRows_rsMilestones = $query_rsMilestones->rowCount();
														$mcounter = 0;
														$sum = 0;
														if ($totalRows_rsMilestones > 0) {
															do {
																$mcounter++;
																$milestone = $row_rsMilestones['msid'];
																$msid = $outputid . $milestone;
																$milestoneName = $row_rsMilestones['milestone'];
																$medate = $row_rsMilestones['edate'];
																$msdate = $row_rsMilestones['sdate'];
																
																$query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid='$projid' and msid='$milestone' ORDER BY sdate ");
																$query_rsTasks->execute();
																$row_rsTasks = $query_rsTasks->fetch();
																$totalRows_rsTasks = $query_rsTasks->rowCount();
																if ($totalRows_rsTasks > 0) {
																?>
																	<input type="hidden" name="mileid<?= $outputid ?>[]" id="mileid<?= $milestone ?>" value="<?= $milestone ?>">
																	<tr class="bg-blue-grey">
																		<td><?= $Ocounter . "." . 1 . "." . $mcounter   ?></td>
																		<td colspan="3"><strong>Milestone: <?= $milestoneName ?></strong> </td>
																		<td colspan="1">
																			<strong>Start Date</strong>
																			<input type="date" name="mpsdate<?= $msid ?>" readonly id="mpsdate<?= $msid ?>" value="<?= $msdate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																		</td>
																		<td colspan="1">
																			<strong>End Date</strong>
																			<input type="date" name="mpedate<?= $msid ?>" readonly id="mpedate<?= $msid ?>" value="<?= $medate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																		</td>
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
																		$datetime1 = new DateTime($sdate);
																		$datetime2 = new DateTime($edate);
																		$difference = $datetime1->diff($datetime2);
																		$duration = $difference->d;
																		?>
																		<tr class="bg-grey">
																			<td><?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?></td>
																			<td colspan="3"><strong> Task: <?= $task ?></strong> </td>
																			<td colspan="1">
																				<span><strong>Start Date:</span>
																				<span style="color: red"> <?php echo date_format(date_create($sdate), "d M Y"); ?></strong></span>
																				<input type="hidden" name="sdate<?= $taskid ?>" id="sdate<?= $taskid ?>" value="<?= $sdate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																				<input type="date" name="psdate<?= $taskid ?>" id="psdate<?= $taskid ?>" value="<?= $sdate ?>" onchange="start_date(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" value="" class="form-control mile_start<?= $msid ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																				<input type="hidden" name="edate<?= $taskid ?>" id="edate<?= $taskid ?>" value="<?= $edate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																			</td>
																			<td colspan="1">
																				<strong>Duration:</strong>
																				<input type="hidden" name="taskid<?= $outputid ?>[]" id="taskid<?= $tkid ?>" value="<?= $tkid ?>">
																				<span style="color: red" id="pdurationmsg<?= $taskid ?>"><strong> <?= $duration - $duration ?> (Days)</strong></span>
																				<input type="number" name="tduration<?= $taskid ?>" id="tduration<?= $taskid ?>" value="<?= $duration  ?>" onchange="duration(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" onkeyup="duration(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																				<input type="hidden" name="pduration<?= $taskid ?>" id="pduration<?= $taskid ?>" value="<?= $duration  ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																				<input type="hidden" name="pedate<?= $taskid ?>" id="pedate<?= $taskid ?>" value="<?= $edate ?>" class="form-control mile_end<?= $msid ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																			</td>
																		</tr>
																		<?php
																		$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
																		$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																		$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																		$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();

																		if ($totalRows_rsDirect_cost_plan > 0) {
																			$plan_counter = 0;
																			do {
																				$plan_counter++;
																				$new_id = $taskid . $plan_counter;
																				$unit = $row_rsDirect_cost_plan['unit'];
																				$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																				$units_no = $row_rsDirect_cost_plan['units_no'];
																				$costlineid = $row_rsDirect_cost_plan['id'];
																				$total_cost = $unit_cost * $units_no;
																				$output_remeinder = $output_remeinder + $total_cost;

																				$query_rsProcurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid =:projid AND outputid=:outputid AND tasks=:tkid AND costlineid=:costlineid");
																				$query_rsProcurement->execute(array(":projid" => $projid, ":outputid" => $outputid, ":tkid" => $tkid, ':costlineid' => $costlineid));
																				$row_rsProcurement = $query_rsProcurement->fetch();
																				$totalRows_rsProcurement = $query_rsProcurement->rowCount();

																				$punit = $row_rsProcurement['unit'];
																				$punit_cost = $row_rsProcurement['unit_cost'];
																				$punits_no = $row_rsProcurement['units_no'];
																				$prmkid = $row_rsProcurement['id'];
																				$description = $row_rsProcurement['description'];
																				$ptotal_cost = $punit_cost * $punits_no;
																				$poutput_remeinder = $poutput_remeinder + $ptotal_cost;
																				$total_amount = $total_amount + $ptotal_cost;
																				?>
																				
																				<input type="hidden" name="pid<?= $taskid ?>[]" id="pid<?= $taskid ?>" value="<?= $prmkid ?>">
																				<tr>
																					<td>
																						<?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?>
																					</td>
																					<td>
																						<?= $description ?>
																						<input type="hidden" name="dtask<?= $taskid ?>[]" value="<?= $taskid ?>">
																						<input type="hidden" name="descrition<?= $taskid ?>[]" value="<?= $description ?>" id="description<?= $new_id ?>" readonly class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																					</td>
																					<td>
																						<?= $unit ?>
																						<input type="hidden" name="hunit<?= $taskid ?>[]" id="hunit<?= $new_id ?>" value="<?= $unit ?>">
																						<input type="hidden" name="dunit<?= $taskid ?>[]" value="<?= $unit ?>" id="unit<?= $new_id ?>" readonly class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																					</td>
																					<td>
																						<input type="hidden" name="hunitcost<?= $taskid ?>[]" value="<?= $unit_cost ?>" id="hunitcost<?= $new_id ?>">
																						<input type="number" name="dunitcost<?= $taskid ?>[]" value="<?= $punit_cost ?>" id="unitcost<?= $new_id ?>" onchange="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" onkeyup="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" class="form-control" placeholder="<?= $unit_cost ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																					</td>
																					<td>
																						<input type="hidden" name="htotalunits<?= $taskid ?>[]" value="<?= $units_no ?>" id="htotalunits<?= $new_id ?>">
																						<input type="number" name="dtotalunits<?= $taskid ?>[]" value="<?= $punits_no ?>" id="totalunits<?= $new_id ?>" onkeyup="totalCost(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" onchange="totalCost(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" class="form-control" placeholder="<?= $units_no ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																					</td>
																					<td>
																						<input type="hidden" name="htotalcost<?= $taskid ?>[]" value="<?= $total_cost ?>" id="htotalcost<?= $new_id ?>">
																						<input type="text" name="dtotalcost<?= $taskid ?>[]" value="<?= $ptotal_cost ?>" id="totalcost<?= $new_id ?>" class="form-control totalCost summarytotal  output_cost<?= $outputid ?> direct_sub_total_amount1<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required disabled>
																					</td>
																				</tr>
																				<?php
																			} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
																		}
																	} while ($row_rsTasks = $query_rsTasks->fetch());
																}
															} while ($row_rsMilestones = $query_rsMilestones->fetch());
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
																<td colspan="2"> <strong>Planned amount Balance (Ksh)</strong></td>
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
						} while ($row_rsOutputs = $query_rsOutputs->fetch());
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
													<input type="hidden" name="totalcost" id="totalcost" value="<?= $total_amount ?>">
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
						<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-paperclip" style="color:#F44336" aria-hidden="true"></i> Procurement Documents/Files Attachment</legend>
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
						<!-- File Upload | Drag & Drop OR With Click & Choose -->
						<div class="header">
								<i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
						</div>
						<div class="body">
							<div class="body table-responsive">
								<table class="table table-bordered" style="width:100%">
									<thead>
										<tr>
											<th style="width:2%">#</th>
											<th style="width:68%">Attachment</th>
											<th style="width:28%">Purpose</th>
											<th style="width:2%">
												<button type="button" name="addplus1" onclick="add_row_files_edit();" title="Add another document" class="btn btn-success btn-sm">
													<span class="glyphicon glyphicon-plus">
													</span>
												</button>
											</th>
										</tr>
									</thead>
									<tbody id="meetings_table_edit">
										<tr></tr>
										<tr id="add_new_file">
											<td colspan="4"> Add file </td>
										</tr>
									</tbody>
								</table>
							</div>
							<script type="text/javascript">
														
							function add_row_files_edit() {
							  $("#add_new_file").remove();
							  $rowno = $("#meetings_table_edit tr").length;
							  $rowno = $rowno + 1;
							  $("#meetings_table_edit tr:last").after(
								'<tr id="mtng' +
								$rowno +
								'">' +
								"<td>" +
								"</td>" +
								"<td>" +
								'<input type="file" name="pfiles[]" id="pfiles" multiple class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>' +
								"</td>" +
								"<td>" +
								'<input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
								"</td>" +
								"<td>" +
								'<button type="button" class="btn btn-danger btn-sm"  onclick=delete_files_edit("mtng' +
								$rowno +
								'")>' +
								'<span class="glyphicon glyphicon-minus"></span>' +
								"</button>" +
								"</td>" +
								"</tr>"
							  );
							  numbering_files_edit();
							}

							function delete_files_edit(rowno) {
							  $("#" + rowno).remove();
							  numbering_files();
							  $number = $("#meetings_table_edit tr").length;
							  if ($number == 1) {
								$("#meetings_table_edit tr:last").after(
								  '<tr id="add_new_file"><td colspan="4">Attach file </td></tr>'
								);
							  }
							}
							// auto numbering table rows on delete and add new for financier table
							function numbering_files_edit() {
							  $("#meetings_table_edit tr").each(function (idx) {
								$(this)
								  .children()
								  .first()
								  .html(idx - 1 + 1);
							  });
							}
							</script>
						</div>
						<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
					</fieldset>

                    <div>
                        <input type="hidden" name="output_cost_val" id="output_cost_val" class="" value="<?= implode(",", $output_cost_val) ?>">
                    </div>
                    <input type="hidden" name="MM_update" value="edit_budget_line_frm">
                    <input type="hidden" name="user_name" value="<?= $user_name ?>">
                    <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                        <div class="col-md-6 text-center">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                        </div>
                        <div class="col-md-6 text-center">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-print" value="Save and Print" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="assets/custom js/add-procurement.js"></script>