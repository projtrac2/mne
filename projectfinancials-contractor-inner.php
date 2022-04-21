    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Contractor</a>
								<a href="projectfinancials-inhouse" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>
								<a href="certificateofcompletion" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Completion Certificates</a>
								<a href="paymentsreport" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Payments Report</a>
							</div>
						</div>
					</div>
				</div>
				<div class="block-header">
					<?php 
						echo $results;
					?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<div style="color:#333; background-color:#EEE; width:100%; height:30px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
									<tr>
										<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000"><div align="left" ><i class="fa fa-balance-scale" aria-hidden="true"></i> Project Payments Dashboard</strong></div></td>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered">								
                                <thead>
                                    <tr style="background-color:#eaf1fc">
                                        <th colspan="7">
											Milestones Ready For Payment
										</th>
                                    </tr>
                                </thead>
                                <tbody>
									<tr id="colrow">
										<th style="width:5%"><strong>#</strong></th>
										<th style="width:95%"><strong>Milestone Name</strong></th>
									</tr>
                                    <tr>
                                        <td colspan="7">
											<div class="clearfix m-b-20">
												<div class="dd" id="nestable">
													<ol class="dd-list">	
														<?php 
														try{
															if($Rows_rsMilestone > 0){
																$sn = 0;
																while($row_rsMilestone = $query_rsMilestone->fetch()){ 
																	$sn = $sn + 1;
																	$Progid = $row_rsMilestone['progid'];
																	$Prjid = $row_rsMilestone['projid'];
																	$projname = $row_rsMilestone['projname'];
																	$subcounty = $row_rsMilestone['projcommunity'];
																	$ward = $row_rsMilestone['projlga'];
																	$location = $row_rsMilestone['projstate'];
																	$stdate = $row_rsMilestone['projstartdate'];
																	$projectStatus = $row_rsMilestone['projstatus'];
																	$projcategory = $row_rsMilestone['projcategory'];
																	$projcontrid = $row_rsMilestone['projcontractor'];
																	$projbudget = $row_rsMilestone['projcost'];
																	

																	$query_tenderamount =  $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = '$Prjid'");
																	$query_tenderamount->execute();		
																	$row_tenderamount = $query_tenderamount->fetch();

																	$query_rsSubCounty =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$subcounty'");
																	$query_rsSubCounty->execute();		
																	$row_rsSubCounty = $query_rsSubCounty->fetch();
																	$totalRows_rsSubCounty = $query_rsSubCounty->rowCount();
																	
																	$query_rsWard =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$ward'");
																	$query_rsWard->execute();		
																	$row_rsWard = $query_rsWard->fetch();
																	$totalRows_rsWard = $query_rsWard->rowCount();

																	$query_rsLocation =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$location'");
																	$query_rsLocation->execute();		
																	$row_rsLocation = $query_rsLocation->fetch();
																	$totalRows_rsLocation = $query_rsLocation->rowCount();

																	$projlocation = $row_rsSubCounty['state'].' Sub-County; '.$row_rsWard['state'].' Ward; '.$row_rsLocation['state'].' Location';
																	
																	$query_rsMyPrjFund =  $db->prepare("SELECT sourcecategory, amountfunding, financier FROM tbl_myprojfunding WHERE projid = :Projid ORDER BY financier");
																	$query_rsMyPrjFund->execute(array(":Projid" => $Prjid));		
																	$row_rsMyPrjFund = $query_rsMyPrjFund->fetch();
																	$totalRows_rsMyPrjFund = $query_rsMyPrjFund->rowCount();

																	$query_rsProjContractor =  $db->prepare("SELECT * FROM tbl_contractor WHERE contrid = '$projcontrid'");
																	$query_rsProjContractor->execute();		
																	$row_rsProjContractor = $query_rsProjContractor->fetch();
																	
																	$query_amntPaid =  $db->prepare("SELECT SUM(tbl_payments_disbursed.amountpaid) AS totalamount FROM tbl_projects LEFT JOIN tbl_payments_request ON tbl_projects.projid = tbl_payments_request.projid LEFT JOIN tbl_payments_disbursed ON tbl_payments_request.id = tbl_payments_disbursed.reqid WHERE  tbl_projects.projid = '$Prjid'");
																	$query_amntPaid->execute();		
																	$row_amntPaid = $query_amntPaid->fetch();
																	$totalRows_amntPaid = $query_amntPaid->rowCount();
																	if(empty($row_amntPaid['totalamount']) || $row_amntPaid['totalamount']==''){
																		$amountpaid = 0;
																	}else{
																		$amountpaid = $row_amntPaid['totalamount'];
																	}
																	
																	$totalprojamountpaid = number_format($amountpaid, 2);
																	$utilrate = ($amountpaid / $projbudget) * 100;
																					
																	$query_rsProjBudget =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM tbl_project_direct_cost_plan WHERE tasks IS NULL and projid = '$Prjid'");
																	$query_rsProjBudget->execute();		
																	$row_sProjBudget = $query_rsProjBudget->fetch();
																	$itemprice = $row_sProjBudget['cost'];
																	$units = $row_sProjBudget['units'];
																	$sProjBudget = $itemprice * $units;														
																	$tenderamount = $row_tenderamount["tenderamount"];
																	$projectcost = $tenderamount + $sProjBudget;
																	?>
																	<li class="dd-item" data-id="4">
																		<div class="dd-handle"><?php echo $sn; ?>. &nbsp;&nbsp;|&nbsp;&nbsp; <font color="#4CAF50" width="20%"> <?php echo "<u>PROJECT NAME</u>: ".$projname."; </font><font color='#FF5722' > <u>MILESTONE NAME</u>: ".$row_rsMilestone['milestone']; ?></font></div>
																		<ol class="dd-list">
																			<table class="table table-bordered">
																				<thead>
																					<tr id="colrow">
																						<th style="width:20%"><strong>Name of Contractor</strong></th>
																						<th style="width:17%"><strong>Project Cost (Ksh)</strong></th>
																						<th style="width:17%"><strong>Total Amount Paid (Ksh)</strong></th>
																						<th style="width:17%"><strong>Utilization Rate</strong></th>
																						<th style="width:12%">Project Status</strong></th>
																						<th style="width:17%"> <strong>Project Location</strong></th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td style="color:#"><strong><?=$row_rsProjContractor["contractor_name"] ?></strong></td>
																						<td><?php 
																						echo number_format($projectcost, 2); ?></td>
																						<td><?php echo number_format($tenderamount, 2); ?></td>
																						<td align="center"><?php echo $utilrate."%"; ?></td>
																						<td><?php echo $row_rsMilestone['statusname']; ?></td>
																						<?php																
																						$prjsdate = strtotime($row_rsMilestone['sdate']);
																						$prjedate = strtotime($row_rsMilestone['edate']);
																						$prjstartdate = date("d M Y",$prjsdate);
																						$prjenddate = date("d M Y",$prjedate);
																						?>
																						<td><?php echo $projlocation; ?></td>
																					</tr>
																				</tbody>
																			</table>
																			<table class="table table-bordered">
																				<?php
																				if($totalRows_rsMyPrjFund > 0){
																				?><div style="margin:2px">
																					<thead>
																						<tr style="font-size:12px; font-family:Verdana, Geneva, sans-serif; background-color:#83a4db; color:#FFF">
																							<th width="5%">SN</th>
																							<th width="40%">Financier</th>
																							<th width="35%">Financier Type</th>
																							<th width="20%">Local Amount (Ksh)</th>
																						</tr>
																					</thead>
																					<tbody>
																					<?php 
																					$num = 0;
																					do{
																						$num = $num + 1;
																						$financierid = $row_rsMyPrjFund['financier'];
																						$localamnt = $row_rsMyPrjFund['amountfunding'];
																						$sourcecat = $row_rsMyPrjFund['sourcecategory'];
																						$localamnt = number_format($localamnt, 2);
																						
																						$query_financiercat =  $db->prepare("SELECT type, category FROM tbl_funding_type WHERE id = '$sourcecat'");
																						$query_financiercat->execute();		
																						$row_financiercat = $query_financiercat->fetch();
																						$financiercat= $row_financiercat["category"];
																						
																						if($financiercat == 1){
																							$query_financier =  $db->prepare("SELECT name as financier FROM tbl_funder WHERE id = '$financierid'");
																						} else {
																							$query_financier =  $db->prepare("SELECT donorname as financier FROM tbl_donors WHERE dnid = '$financierid'");
																						}
																						$query_financier->execute();		
																						$row_financier = $query_financier->fetch();
																						?>
																						<tr>
																							<td><?php echo $num; ?></td>
																							<td><?php echo $row_financier['financier']; ?></td>
																							<td><?php echo $row_financiercat['type']; ?></td>
																							<td><?php echo $localamnt; ?></td>
																						</tr>
																					<?php }while($row_rsMyPrjFund = $query_rsMyPrjFund->fetch()); ?>
																					</tbody>
																				</div>
																				<?php
																				}
																				?>
																			</table>
																			<table class="table table-bordered">
																				<thead>
																					<tr style="background-color:#eaf1fc">
																						<th width="30%">Budget (Ksh)</th>
																						<th width="30%">Milestone Status</th>
																						<th width="20%">Start Date</th>
																						<th width="20%">End Date</th>
																						<th width="30%">Payment Status</th>
																					</tr>
																				</thead>
																				<tbody>
																				<?php
																					$mstnID =  $row_rsMilestone['msid'];
																					$mstnStatus =  $row_rsMilestone['status'];
																					$mstnsdate = strtotime($row_rsMilestone["sdate"]);
																					$mstnedate = strtotime($row_rsMilestone["edate"]);
																					$mstnstartdate = date("d M Y",$mstnsdate);
																					$mstnenddate = date("d M Y",$mstnedate);
																					$mstnbudget = $row_rsMilestone['mstbudget'];
																					if(empty($mstnbudget) || $mstnbudget==''){
																						$mstnbudget = 0;
																					}else{
																						$mstnbudget = $mstnbudget;
																					}
																					
																					$query_msstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$mstnStatus'");
																					$query_msstatus->execute();		
																					$row_msstatus = $query_msstatus->fetch();
																					$msstatus= $row_msstatus["statusname"];
																					
																					$query_rsMilestoneBudget =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM tbl_project_tender_details c inner join tbl_task t ON t.tkid=c.tasks WHERE t.projid = '$Prjid' and t.msid='$mstnID'");
																					$query_rsMilestoneBudget->execute();		
																					$row_rsMilestoneBudget = $query_rsMilestoneBudget->fetch();
																					$msitemscost = $row_rsMilestoneBudget['cost'];
																					$msitems = $row_rsMilestoneBudget['units'];
																					$mscost = $msitems * $msitemscost;
																					
																					$query_rsMilestoneReq =  $db->prepare("SELECT * FROM tbl_payments_request WHERE itemcategory='2' AND projid = '$Prjid' AND itemid = '$mstnID'");
																					$query_rsMilestoneReq->execute();		
																					$row_rsMilestonePayReq = $query_rsMilestoneReq->fetch();
																					$Rows_rsMilestoneReq = $query_rsMilestoneReq->rowCount();
																					$paystatus = $row_rsMilestonePayReq['status'];
																					$payrequestno = $row_rsMilestonePayReq['requestid'];
																					
																					$query_rsMilestoneRecv =  $db->prepare("SELECT * FROM tbl_payments_disbursed WHERE requestid = '$payrequestno' ORDER BY id");
																					$query_rsMilestoneRecv->execute();		
																					$row_rsMilestonePayRecv = $query_rsMilestoneRecv->fetch();
																					$Rows_rsMilestoneRecv = $query_rsMilestoneRecv->rowCount();
																					
																					$query_rsMilestoneRecvFiles =  $db->prepare("SELECT floc FROM tbl_files WHERE fcategory='Payment' AND projid='$Prjid'");
																					$query_rsMilestoneRecvFiles->execute();		
																					$row_rsMilestonePayRecvFiles = $query_rsMilestoneRecvFiles->fetch();
																					$Rows_rsMilestoneRecvFiles = $query_rsMilestoneRecvFiles->rowCount();
																					?>
																					<tr>
																						<td><?php echo number_format($mscost, 2); ?></td>
																						<td><?php echo $msstatus; ?></td>
																						<td><?php echo $mstnstartdate; ?></td>
																						<td><?php echo $mstnenddate; ?></td>
																						<td><?php
																							if($Rows_rsMilestoneReq == 0 || ($Rows_rsMilestoneReq > 0 && $paystatus ==3)){ ?>
																								<a type="button" class="btn bg-purple waves-effect" onclick="javascript:CallMlstPaymentRequest(<?php echo $row_rsMilestone['msid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here to request payment" style="height:25px; padding-top:0px"><i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Request</a>
																							<?php }
																							elseif($Rows_rsMilestoneReq > 0 && $paystatus ==1){
																							?>
																								<i class="fa fa-refresh fa-spin fa-2x fa-fw text-success"></i><span class="sr-only">Loading...</span>
																							<?php
																							}
																							elseif($Rows_rsMilestoneReq > 0 && ($paystatus ==2 || $paystatus ==5 || $paystatus ==6)){ ?><div style="color:#2196F3">Pending</div>
																							<?php } 
																							elseif($Rows_rsMilestoneReq > 0 && $paystatus ==4){ ?>
																								<a href="<?php echo $row_rsMilestonePayRecv['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Download Payment Receipt" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
																							<?php } ?>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<li class="dd-item" data-id="5">
																				<div class="dd-handle"><font color="#2196F3">Milestone Payment Records</font></div>
																				<ol class="dd-list">
																					<div style="font-size:14px"><span class="badge bg-purple"><strong>Payment Requested</strong></span></div>
																					<table class="table table-bordered">
																						<thead>
																							<tr style="background-color:#eaf1fc">
																								<th width="10%">Request ID</th>
																								<th width="20%">Amount Requested (ksh)</th>
																								<?php if($paystatus == 1 || $paystatus == 4 || $paystatus == 5){ ?>
																								<th width="30%">Requested By</th>
																								<th width="15%">Request Date</th>
																								<?php }elseif($paystatus == 2){ ?>
																								<th width="30%">Approved By</th>
																								<th width="15%">Date Approved</th>
																								<?php }elseif($paystatus == 3){ ?>
																								<th width="30%">Rejected By</th>
																								<th width="15%">Date Rejected</th>
																								<?php } ?>
																								<th width="15%">Request Status</th>
																								<th width="10%">Comments</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php 
																							if($Rows_rsMilestoneReq > 0){
																								do { 
																									$rqid = $row_rsMilestonePayReq['id'];
																									$requestref = $row_rsMilestonePayReq['requestid'];
																									$reqamnt = number_format($row_rsMilestonePayReq['amountrequested'], 2);
																									$rqstatus = $row_rsMilestonePayReq['status'];
																									$rqby = $row_rsMilestonePayReq['requestedby'];
																									$appby = $row_rsMilestonePayReq['approvalby'];
																									$rqdate = strtotime($row_rsMilestonePayReq['daterequested']);
																									$daterequested = date("d M Y",$rqdate);
																									$appdate = strtotime($row_rsMilestonePayReq['approvaldate']);
																									$dateapproved = date("d M Y",$appdate);
																									
																									$query_rqPayStatus =  $db->prepare("SELECT status FROM tbl_payment_status where id='$rqstatus'");
																									$query_rqPayStatus->execute();		
																									$row_rqPayStatus = $query_rqPayStatus->fetch();
																									
																									$query_rqPayRequester =  $db->prepare("SELECT fullname FROM admin where username='$rqby'");
																									$query_rqPayRequester->execute();		
																									$row_rqPayRequester = $query_rqPayRequester->fetch();
																									$requester = $row_rqPayRequester['fullname'];
																									
																									$query_rqPayApprover =  $db->prepare("SELECT fullname FROM admin where username='$appby'");
																									$query_rqPayApprover->execute();		
																									$row_rqPayApprover = $query_rqPayApprover->fetch();
																									$approver = $row_rqPayApprover['fullname'];
																									
																									//$start_date = date_format($projstartdate, "Y-m-d");
																									$current_date = date("Y-m-d");
																									?>
																									<tr>
																										<td><?php echo $requestref; ?></td>
																										<td><?php echo $reqamnt; ?></td>
																										<?php if($rqstatus == 6){ ?>
																											<?php if($paystatus == 1){ ?>
																												<td><?php echo $requester; ?></td>
																												<td><?php echo $daterequested; ?></td>
																											<?php }elseif($paystatus == 2 || $paystatus == 3){ ?>
																												<td><?php echo $approver; ?></td>
																												<td><?php echo $dateapproved; ?></td>
																											<?php } ?>
																											<td style="color:#F44336"><strong><?php echo $row_rqPayStatus['status']; ?></strong></td>
																											<td>
																											<a href="#" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" onclick="javascript:CallRequestComments(<?php echo $rqid; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click Here to View Comments"><i class="fa fa-comments fa-2x" aria-hidden="true"></i></a>
																											</td>
																										<?php }else{  ?>
																										
																											<?php if($paystatus == 1 || $paystatus == 4 || $paystatus == 5){ ?>
																												<td><?php echo $requester; ?></td>
																												<td><?php echo $daterequested; ?></td>
																											<?php }elseif($paystatus == 2 || $paystatus == 3){ ?>
																												<td><?php echo $approver; ?></td>
																												<td><?php echo $dateapproved; ?></td>
																											<?php } ?>
																											<td><?php echo $row_rqPayStatus['status']; ?></td>
																											<td>
																											<a href="#" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" onclick="javascript:CallRequestComments(<?php echo $rqid; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click Here to View Comments"><i class="fa fa-comments fa-2x" aria-hidden="true"></i></a>
																											</td>
																										<?php } ?>
																									</tr>
																								<?php
																								} while ($row_rsMilestonePayReq = $query_rsMilestoneReq->fetch()); 
																							}
																							else{
																								?>
																							<tr> 
																								<td colspan="6" style="color:#F44336; font-size:16px; padding-left:10%"><i>No Payments Requested</i></td>
																							</tr>
																							<?php
																							}
																							?>
																						</tbody>
																					</table>
																					<div style="font-size:14px"><span class="badge bg-light-green"><strong>Payment Made</strong></span></div>
																					<table class="table table-bordered">
																						<thead>
																							<tr style="background-color:#eaf1fc">
																								<th width="15%">Reference No</th>
																								<th width="20%">Amount Funded (ksh)</th>
																								<th width="30%">Payment Finance Officer</th>
																								<th width="15%">Payment Date</th>
																								<th width="20%">Mode of Payment</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php 
																							if($Rows_rsMilestoneRecv > 0){
																							do { 
																								$rqid = $row_rsMilestonePayRecv['id'];
																								$requestref = $row_rsMilestonePayRecv['requestid'];
																								$recvamnt = $row_rsMilestonePayRecv['amountpaid'];
																								$recvamnt =  number_format($recvamnt, 2);
																								$paymentmode = $row_rsMilestonePayRecv['paymentmode'];
																								$fundsource = $row_rsMilestonePayRecv['fundsource'];
																								$paidby = $row_rsMilestonePayRecv['paidby'];
																								$paymdate = strtotime($row_rsMilestonePayRecv['datepaid']);
																								$paymentdate = date("d M Y",$paymdate);
																								
																								//$start_date = date_format($projstartdate, "Y-m-d");
																								$current_date = date("Y-m-d");
																								if($paymentmode == 1){
																									$paymode = "Cash";
																								}
																								elseif($paymentmode == 2){
																									$paymode = "M-Pesa";
																								}
																								elseif($paymentmode == 3){
																									$paymode = "Airtel Money";
																								}
																								elseif($paymentmode == 4){
																									$paymode = "Cheque";
																								}
																								elseif($paymentmode == 5){
																									$paymode = "Others";
																								}
																								?>
																								<tr>
																									<td><?php echo $requestref; ?></td>
																									<td><?php echo $recvamnt; ?></td>
																									<td><?php echo $paidby; ?></td>
																									<td><?php echo $paymentdate; ?></td>
																									<td><?php echo $paymode; ?></td>
																								</tr>
																							<?php
																							} while ($row_rsMilestonePayRecv = $query_rsMilestoneRecv->fetch()); 
																							}
																							else{
																								?>
																							<tr>
																								<td colspan="6" style="color:#F44336; font-size:16px; padding-left:10%"><i>No Payment Made</i></td>
																							</tr>
																							<?php
																							}
																							?>
																						</tbody>
																					</table>
																				</ol>
																			</li>
																		</ol>
																	</li>
																<?php
																}
															}else{
															?>
																<li class="dd-item" data-id="9">
																	<div class="dd-handle" style="color:#F44336">No Milestone(s) due for payment</div>
																</li>
															<?php
															}
														}catch (PDOException $ex){
															$result = flashMessage("An error occurred: " .$ex->getMessage());
															echo $result;
														}
														?>
													</ol>
												</div>
											</div>
										</td>
                                    </tr>
								</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>