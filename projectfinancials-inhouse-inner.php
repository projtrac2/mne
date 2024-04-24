    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
								<?php //if($totalRows_rsTender == 0){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Contractors</a>-->
									<?php //}else{?>
									<a href="projectfinancials-contractor" class="btn bg-light-blue waves-effect" style="margin-top:10px">Contractor</a>
								<?php //} ?>
								<?php //if($totalRows_rsTender == 0){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>
									<?php //}else{?>
									<!--<a href="myprojecttask?projid=<?php //echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>-->
								<?php //} ?>
								<?php //if($totalRows_rsTender == 0){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>-->
									<?php //}else{?>
									<a href="certificateofcompletion" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Completion Certificates</a>
								<?php// } ?>
								
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
										<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000"><div align="left" ><i class="fa fa-balance-scale" aria-hidden="true"></i> Project Financial Dashboard</strong></div></td>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered">								
                                <thead>
                                    <tr style="background-color:#eaf1fc">
                                        <th colspan="7">
											Tasks Ready For Payment
										</th>
                                    </tr>
                                </thead>
                                <tbody>
									<tr id="colrow">
										<th style="width:5%"><strong>#</strong></th>
										<th style="width:95%"><strong>Task Name</strong></th>
									</tr>
                                    <tr>
                                        <td colspan="7">
											<div class="clearfix m-b-20">
												<div class="dd" id="nestable">
													<ol class="dd-list">	
														<?php 
														try{
															if($Rows_rsTask > 0){
																$sn = 0;
																while($row_rsTask = $query_rsTask->fetch()){ 
																	$sn = $sn + 1;
																	$Progid = $row_rsTask['Progid'];
																	$Prjid = $row_rsTask['Prjid'];
																	$projname = $row_rsTask['projname'];
																	$subcounty = $row_rsTask['projcommunity'];
																	$ward = $row_rsTask['projlga'];
																	$location = $row_rsTask['projstate'];
																	$stdate = $row_rsTask['projstartdate'];
																	$projectStatus = $row_rsTask['projstatus'];
																	$projcategory = $row_rsTask['projcategory'];
																	$projcontrid = $row_rsTask['projcontractor'];
																	$projbudget = $row_rsTask['projcost'];
																	

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

																	$query_rsMyPrjFund =  $db->prepare("SELECT sourcecategory, amountfunding, financier FROM tbl_myprojfunding WHERE projid = :Projid");
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
																	?>
																	<li class="dd-item" data-id="4">
																		<div class="dd-handle"><?php echo $sn; ?>. &nbsp;&nbsp;|&nbsp;&nbsp; <font color="#4CAF50" width="20%"> <?php echo "<u>PROJECT NAME</u>: ".$projname."; </font><font color='#FF5722' > <u>TASK NAME</u>: ".$row_rsTask['task']; ?></font></div>
																		<ol class="dd-list">
																			<table class="table table-bordered">
																				<thead>
																					<tr id="colrow">
																						<th style="width:20%"><strong>Name of Contractor</strong></th>
																						<th style="width:17%"><strong>Project Budget (Ksh)</strong></th>
																						<th style="width:17%"><strong>Total Amount Paid (Ksh)</strong></th>
																						<th style="width:17%"><strong>Utilization Rate</strong></th>
																						<th style="width:12%">Project Status</strong></th>
																						<th style="width:17%"> <strong>Project Location</strong></th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td style="color:#"><strong>In House</strong></td>
																						<td><?php echo number_format($row_rsTask['projcost'], 2); ?></td>
																						<td><?php echo $totalprojamountpaid; ?></td>
																						<td align="center"><?php echo $utilrate."%"; ?></td>
																						<?php
																						if($projectStatus =="Cancelled" || $projectStatus =="On Hold")
																						{
																						?>
																							<td><?php echo "Project ".$row_rsTask['projstatus']; ?></td>
																						<?php
																						}
																						else{
																						?>
																							<td><?php echo $row_rsTask['projstatus']; ?></td>
																						<?php
																						}
																						
																						$prjsdate = strtotime($row_rsTask['sdate']);
																						$prjedate = strtotime($row_rsTask['edate']);
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
																						<th width="30%">Task Status</th>
																						<th width="20%">Start Date</th>
																						<th width="20%">End Date</th>
																						<th width="30%">Payment Status</th>
																					</tr>
																				</thead>
																				<tbody>
																				<?php
																					$taskID =  $row_rsTask['tkid'];
																					$tskStatus =  $row_rsTask['status'];
																					$tsksdate = strtotime($row_rsTask["sdate"]);
																					$tskedate = strtotime($row_rsTask["edate"]);
																					$tskstartdate = date("d M Y",$tsksdate);
																					$tskenddate = date("d M Y",$tskedate);
																					$taskbudget = $row_rsTask['taskbudget'];
																					if(empty($taskbudget) || $taskbudget==''){
																						$taskbudget = 0;
																					}else{
																						$taskbudget = $taskbudget;
																					}
																					
																					$query_rsTaskPayReq =  $db->prepare("SELECT * FROM tbl_payments_request WHERE itemcategory='1' AND projid = '$Prjid' AND itemid = '$taskID'");
																					$query_rsTaskPayReq->execute();		
																					$row_rsTaskPayReq = $query_rsTaskPayReq->fetch();
																					$Rows_rsTaskPayReq = $query_rsTaskPayReq->rowCount();
																					$paystatus = $row_rsTaskPayReq['status'];
																					$payrequestno = $row_rsTaskPayReq['requestid'];
																					
																					$query_rsTaskPayRecv =  $db->prepare("SELECT * FROM tbl_payments_disbursed WHERE requestid = '$payrequestno' ORDER BY id");
																					$query_rsTaskPayRecv->execute();		
																					$row_rsTaskPayRecv = $query_rsTaskPayRecv->fetch();
																					$Rows_rsTaskPayRecv = $query_rsTaskPayRecv->rowCount();
																					
																					$query_rsTaskPayRecvFiles =  $db->prepare("SELECT floc FROM tbl_files WHERE catid = '$payrequestno' AND fcategory='Payment' AND projid='$Prjid'");
																					$query_rsTaskPayRecvFiles->execute();		
																					$row_rsTaskPayRecvFiles = $query_rsTaskPayRecvFiles->fetch();
																					$Rows_rsTaskPayRecvFiles = $query_rsTaskPayRecvFiles->rowCount();
																					?>
																					<tr>
																						<td><?php echo number_format($taskbudget, 2); ?></td>
																						<td><?php echo $tskStatus; ?></td>
																						<td><?php echo $tskstartdate; ?></td>
																						<td><?php echo $tskenddate; ?></td>
																						<td><?php
																							if($Rows_rsTaskPayReq == 0 || ($Rows_rsTaskPayReq > 0 && $paystatus ==3)){ ?>
																								<a type="button" class="btn bg-purple waves-effect" onclick="javascript:CallTskPaymentRequest(<?php echo $row_rsTask['tkid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here to request payment" style="height:25px; padding-top:0px"><i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Request</a>
																							<?php }
																							elseif($Rows_rsTaskPayReq > 0 && $paystatus ==1){
																							?>
																								<i class="fa fa-refresh fa-spin fa-2x fa-fw text-success"></i><span class="sr-only">Loading...</span>
																							<?php
																							}
																							elseif($Rows_rsTaskPayReq > 0 && ($paystatus ==2 || $paystatus ==5 || $paystatus ==6)){ ?><div style="color:#2196F3">Pending</div>
																							<?php } 
																							elseif($Rows_rsTaskPayReq > 0 && $paystatus ==4){ ?>
																								<a href="<?php echo $row_rsTaskPayRecv['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Download Payment Receipt" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
																							<?php } ?>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<li class="dd-item" data-id="5">
																				<div class="dd-handle"><font color="#2196F3">Task Funding Records</font></div>
																				<ol class="dd-list">
																					<div style="font-size:14px"><span class="badge bg-purple"><strong>Funds Requested</strong></span></div>
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
																							if($Rows_rsTaskPayReq > 0){
																								do { 
																									$rqid = $row_rsTaskPayReq['id'];
																									$requestref = $row_rsTaskPayReq['requestid'];
																									$reqamnt = number_format($row_rsTaskPayReq['amountrequested'], 2);
																									$rqstatus = $row_rsTaskPayReq['status'];
																									$rqby = $row_rsTaskPayReq['requestedby'];
																									$appby = $row_rsTaskPayReq['approvalby'];
																									$rqdate = strtotime($row_rsTaskPayReq['daterequested']);
																									$daterequested = date("d M Y",$rqdate);
																									$appdate = strtotime($row_rsTaskPayReq['approvaldate']);
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
																								} while ($row_rsTaskPayReq = $query_rsTaskPayReq->fetch()); 
																							}
																							else{
																								?>
																							<tr> 
																								<td colspan="6" style="color:#F44336; font-size:16px; padding-left:10%"><i>No Funds Requested</i></td>
																							</tr>
																							<?php
																							}
																							?>
																						</tbody>
																					</table>
																					<div style="font-size:14px"><span class="badge bg-light-green"><strong>Funds Disbursed</strong></span></div>
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
																							if($Rows_rsTaskPayRecv > 0){
																							do { 
																								$rqid = $row_rsTaskPayRecv['id'];
																								$requestref = $row_rsTaskPayRecv['requestid'];
																								$recvamnt = $row_rsTaskPayRecv['amountpaid'];
																								$recvamnt =  number_format($recvamnt, 2);
																								$paymentmode = $row_rsTaskPayRecv['paymentmode'];
																								$fundsource = $row_rsTaskPayRecv['fundsource'];
																								$paidby = $row_rsTaskPayRecv['paidby'];
																								$paymdate = strtotime($row_rsTaskPayRecv['datepaid']);
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
																							} while ($row_rsTaskPayRecv = $query_rsTaskPayRecv->fetch()); 
																							}
																							else{
																								?>
																							<tr>
																								<td colspan="6" style="color:#F44336; font-size:16px; padding-left:10%"><i>No Funds Released</i></td>
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
																	<div class="dd-handle" style="color:#F44336">No Task(s) due for fund request</div>
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