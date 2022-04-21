<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
			<div class="header" style="padding-bottom:15px">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader" style = "text-transform:uppercase"><i class="fa fa-money" aria-hidden="true"></i>
					<?php if($count_distr == 0){ echo $action; } ?> <?=$ministrylabel?> FUNDS
					</h4>
				</div>
			</div>
			<div class="block-header">
				<?php 
					echo $results;
				?>
				<input type="hidden" id="fndid" value=" <?= $hash ?>" readonly>
			</div>
			<div class="header" align="" style="color:#000000">
				<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
					<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
						<label class="control-label">Fund Code:</label>
						<div class="form-line">
							<input type="text" class="form-control" value=" <?= $fundcode ?>" readonly>
						</div>
					</div>
					<div class="col-md-9 clearfix" style="margin-top:5px; margin-bottom:5px">
						<label class="control-label">Financier:</label>
						<div class="form-line">
							<input type="text" class="form-control" value=" <?= $financier ?>" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="body">
				<?php if($fundtype==1 || $fundtype == 2){
				if($count_distr == 0){ ?>
                <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
				<?php } ?>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
							<i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> Fund Distribution
						</legend>
						<input type="hidden" name="funding_amount" id="funding_amount" value="<?= $totalfund ?>">
							<div class="fund" style="padding:5px">
								<div class="col-md-8">
								</div>
								<div class="col-md-4 bg-brown">
									<h5>
										<strong> Total Fund (Ksh):
											<span class="">
												<?= number_format($totalfund, 2) ?>
											</span>
										</strong>
									</h5>
								</div>
								<div class="row clearfix" style="margin-top:3px; margin-bottom:3px">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<!-- direct cost  -->
										<div class="table-responsive">
											<?php if($count_distr == 0){ ?>
												<table class="table table-bordered" id="funding_table">
													<thead>
														<tr>
															<th style="width:5%"># </th>
															<th style="width:70%"><?=$ministrylabel?></th>
															<th style="width:25%">Amount (Ksh)</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$query_department =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 AND deleted = '0' AND sector NOT LIKE 'All%'");
														$query_department->execute();
														$row_department = $query_department->fetch();
														$totalRows_department = $query_department->rowCount();
														
														if ($totalRows_department > 0) {
														$plan_counter = 0;
															do {
																$plan_counter++;
																$sectorid = $row_department['stid'];
																$department = $row_department['sector'];
																?>
																
																<tr>
																	<td>
																		<?= $plan_counter ?>
																	</td>
																	<td>
																		<?= $department ?>
																	</td>
																	<td>
																		<input type="hidden" name="sector[]" id="sectorid<?= $plan_counter ?>" value="<?= $sectorid ?>">
																		<input type="hidden" name="s" value="<?= $total_cost ?>" id="htotalcost">
																		<input type="text" name="allocation[]" value="" id="allocation<?= $plan_counter ?>" onkeyup="totalCost(<?= $plan_counter ?>)" onchange="totalCost(<?= $plan_counter ?>)" class="form-control totalCost summarytotal  allocated_amount direct_sub_total_amount" placeholder="Total Allocation" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																	</td>
																</tr>
																<?php
															} while ($row_department = $query_department->fetch());
														}
														?>
														<tfoot style="background-color:#ebedeb">
															<tr>
																<td></td>
																<td><strong>Total</strong></td>
																<td>
																	<input type="text" name="d_sub_total_amount" value="" id="sub_total_amount" class="form-control" placeholder="Total Sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																</td>
															</tr>
															<tr>
																<td></td>
																<td> <strong>% Total</strong></td>
																<td>
																	<input type="text" name="d_sub_total_percentage" value="" id="sub_total_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																</td>
															</tr>
															<tr>
																<td></td>
																<td> <strong>Fund Balance</strong></td>
																<td>
																	<input type="text" name="outputBal" id="" class="form-control output_cost_bal" value="<?= number_format(($totalfund), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																</td>
															</tr>
														</tfoot>
													</tbody>
												</table>
											<?php } else { ?>
												<table class="table table-bordered table-striped table-hover" id="manageItemTable">
													<thead>
														<tr>
															<th style="width:5%"># </th>
															<th style="width:75%"><?=$ministrylabel?></th>
															<th style="width:20%">Amount (Ksh)</th>
														</tr>
													</thead>
												</table>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php
						$summary  .= '<tr>
							<td>' . $Ocounter . '</td>
							<td>' . $outputName . '</td>
							<td style="text-align:left">' . number_format($output_remeinder, 2) . '</td>
							<td id="summaryOutput' . $outputid . '"  style="text-align:left"></td>
							<td id="percrate"  style="text-align:left"></td>
						</tr>';
						?>
                    </fieldset>
					<?php if($count_distr == 0){ ?>
					<div class="row clearfix">
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
							<input type="hidden" name="<?=$fundsfrmid?>" value="<?=$fundsfrm?>" />
							<input name="fundid" type="hidden" id="fundid" value="<?php echo $fndid; ?>" />
							<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
							<div class="btn-group">
								<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="tag-form-submit" value="<?=$action?>" />
							</div>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
						</div>
					</div>
                </form>
					<?php } ?>
				<?php } else { ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="control-label txt-warning">SORRY, THE AMOUNT CAN NOT BE DISTRIBUED TO SECTORS/MINISTRIES!!!!</label>
					</div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>


<script src="assets/custom js/ministry-distribution.js"></script>