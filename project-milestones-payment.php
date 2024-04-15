<?php 
try {

require('includes/head.php');
//$pageTitle = $planlabelplural;

if ($permission) { 
		$query_rsMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE paymentstatus = 0 AND status = 5 AND paymentrequired = 1 GROUP BY msid");
		$query_rsMilestone->execute();
		$rows_rsMilestone = $query_rsMilestone->rowCount();
	
?> 
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">


	<script src="assets/ckeditor/ckeditor.js"></script>

	<!-- start body  -->
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
					<div class="col-md-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Contractor</a>
								<a href="view-inhouse-payment-requests.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>
								<!-- <a href="certificateofcompletion.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Completion Certificates</a> -->
								<!--<a href="paymentsreport.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Payments Report</a>-->
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<div class="header">
								<div style="color:#333; background-color:#EEE; width:100%; height:30px">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
										<tr>
											<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
												<div align="left"><i class="fa fa-balance-scale" aria-hidden="true"></i> Project Payments Dashboard</strong></div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="body">
							<div class="table-responsive">
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
															if ($rows_rsMilestone > 0) {
																$sn = 0;
																while ($row_rsMilestone = $query_rsMilestone->fetch()) {
																	$sn = $sn + 1;
																	$projid = $row_rsMilestone['projid'];
																	$msid = $row_rsMilestone['msid'];
																	$outputid = $row_rsMilestone['outputid'];
																	$milestone = $row_rsMilestone['milestone'];
																	$mlsdate = $row_rsMilestone['sdate'];
																	$mledate = $row_rsMilestone['edate'];

																	$query_milestonePrj =  $db->prepare("SELECT p.*, s.statusname FROM tbl_projects p INNER JOIN tbl_status s ON s.statusid=p.projstatus WHERE p.projid = '$projid'");
																	$query_milestonePrj->execute();
																	$row_milestonePrj = $query_milestonePrj->fetch();

																	if($row_milestonePrj){
																		$progid = $row_milestonePrj['progid'];
																		$projname = $row_milestonePrj['projname'];
																		$subcounty = $row_milestonePrj['projcommunity'];
																		$ward = $row_milestonePrj['projlga'];
																		$location = $row_milestonePrj['projstate'];
																		$stdate = $row_milestonePrj['projstartdate'];
																		$projectStatus = $row_milestonePrj['statusname'];
																		$projcategory = $row_milestonePrj['projcategory'];
																		$projcontrid = $row_milestonePrj['projcontractor'];
																		
																		$query_rsProjContractor =  $db->prepare("SELECT * FROM tbl_contractor WHERE contrid = '$projcontrid'");
																		$query_rsProjContractor->execute();
																		$row_rsProjContractor = $query_rsProjContractor->fetch();
																	}
																	
																	$query_milestoneoutput =  $db->prepare("SELECT g.output FROM tbl_progdetails g inner join tbl_projdetails p on p.outputid = g.id WHERE p.id = '$outputid'");
																	$query_milestoneoutput->execute();
																	$row_milestoneoutput = $query_milestoneoutput->fetch();
																	$count_row_milestoneoutput = $query_milestoneoutput->rowCount();
																	$milestoneoutput = $count_row_milestoneoutput > 0 ?  $row_milestoneoutput['output'] : 0;

																	$query_tenderamount =  $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = '$projid'");
																	$query_tenderamount->execute();
																	$row_tenderamount = $query_tenderamount->fetch();
																	

																	$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier inner join tbl_financier_type t on t.id= f.type WHERE projid = :projid ORDER BY amountfunding desc");
																	$query_rsProjFinancier->execute(array(":projid" => $projid));
																	$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																	$query_amntPaid =  $db->prepare("SELECT SUM(tbl_payments_disbursed.amountpaid) AS totalamount FROM tbl_projects LEFT JOIN tbl_payments_request ON tbl_projects.projid = tbl_payments_request.projid LEFT JOIN tbl_payments_disbursed ON tbl_payments_request.id = tbl_payments_disbursed.reqid WHERE  tbl_projects.projid = '$projid'");
																	$query_amntPaid->execute();
																	$row_amntPaid = $query_amntPaid->fetch();
																	$totalRows_amntPaid = $query_amntPaid->rowCount();
																	if (empty($row_amntPaid['totalamount']) || $row_amntPaid['totalamount'] == '') {
																		$amountpaid = 0;
																		$utilrate = 0;
																	} else {
																		$amountpaid = $row_amntPaid['totalamount'];
																	}
																	
																	if($row_tenderamount){
																		$tendercost = $row_tenderamount["tenderamount"];
																	}

																	$totalprojamountpaid = number_format($amountpaid, 2);
																	if($amountpaid > 0){
																		$utilrate = ($amountpaid / $tendercost) * 100;
																	}

																	$query_rsProjBudget =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM tbl_project_direct_cost_plan WHERE tasks IS NULL and projid = '$projid'");
																	$query_rsProjBudget->execute();
																	$row_sProjBudget = $query_rsProjBudget->fetch();
																	$itemprice = $row_sProjBudget['cost'];
																	$units = $row_sProjBudget['units'];
																	$sProjBudget = $itemprice * $units;
																	//$tenderamount = $row_tenderamount["tenderamount"];
																	$projectcost = $tendercost + $sProjBudget;
																	?>
																	<li class="dd-item" data-id="4">
																		<div class="dd-handle"><?php echo $sn; ?>. &nbsp;&nbsp;|&nbsp;&nbsp; <font color="#4CAF50" width="20%"> <?php echo "<u>PROJECT NAME</u>: " . $projname . "; </font><font color='#FF5722' > <u>MILESTONE NAME</u>: " . $milestone; ?></font>
																		</div>
																		<ol class="dd-list">
																			<table class="table table-bordered">
																				<thead>
																					<tr id="colrow">
																						<th style="width:25%"><strong>Name of Contractor</strong></th>
																						<th style="width:20%"><strong>Tender Amount
																								(Ksh)</strong></th>
																						<th style="width:20%"><strong>Total Amount Paid (Ksh)</strong></th>
																						<th style="width:20%"><strong>Utilization Rate</strong></th>
																						<th style="width:15%">Project Status</strong></th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td style="color:#"><strong><?= $row_rsProjContractor["contractor_name"] ?></strong></td>
																						<td><?php
																							echo number_format($tendercost, 2); ?></td>
																						<td><?php echo number_format($amountpaid, 2); ?></td>
																						<td align="center"><?php echo $utilrate . "%"; ?></td>
																						<td><?php echo $projectStatus; ?></td>
																						<?php
																						$prjsdate = strtotime($row_rsMilestone['sdate']);
																						$prjedate = strtotime($row_rsMilestone['edate']);
																						$prjstartdate = date("d M Y", $prjsdate);
																						$prjenddate = date("d M Y", $prjedate);
																						?>
																					</tr>
																				</tbody>
																			</table>
																			<table class="table table-bordered">
																				<div style="margin:2px">
																					<thead>
																						<tr style="font-size:12px; font-family:Verdana, Geneva, sans-serif; background-color:#83a4db; color:#FFF">
																							<th width="3%">SN</th>
																							<th width="37%" colspan="2">Milestones</th>
																							<th width="15%">Start Date</th>
																							<th width="15%">End Date</th>
																							<th width="15%">Cost</th>
																							<th width="15%">Payment Status</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$query_last_payment_milestone = $db->prepare("SELECT msid FROM tbl_milestone WHERE projid='$projid' AND paymentrequired = 1 AND msid < '$msid' ORDER BY msid ASC LIMIT 1");
																						$query_last_payment_milestone->execute();
																						$row_last_payment_milestones = $query_last_payment_milestone->fetch();
																						$count_last_milestones = $query_last_payment_milestone->rowCount();
																						$lastmsid = 0;
																						if ($count_last_milestones > 0) {
																							$lastmsid = $row_last_payment_milestones["msid"];
																						}

																						$query_milestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid='$projid' AND status = '5' AND (msid > '$lastmsid' AND msid <= '$msid') ORDER BY msid ASC");
																						$query_milestones->execute();
																						$count_milestones = $query_milestones->rowCount();

																						if ($count_milestones > 0) {

																							$num = 0;
																							$total_amount = 0;
																							while ($milestone = $query_milestones->fetch()) {
																								$num++;
																								$mstnid = $milestone["msid"];

																								$query_rsMilestoneReq =  $db->prepare("SELECT * FROM tbl_payments_request WHERE itemcategory='2' AND projid = '$projid' AND itemid = '$mstnid' ORDER BY id DESC LIMIT 1");
																								$query_rsMilestoneReq->execute();
																								$row_rsMilestonePayReq = $query_rsMilestoneReq->fetch();
																								$Rows_rsMilestoneReq = $query_rsMilestoneReq->rowCount();

																								$paystatus = $Rows_rsMilestoneReq > 0 ?  $row_rsMilestonePayReq['status'] : 0;
																								$payrequestno = $Rows_rsMilestoneReq > 0 ?  $row_rsMilestonePayReq['requestid'] : 0;

																								$query_rsMilestoneRecv =  $db->prepare("SELECT * FROM tbl_payments_disbursed WHERE requestid = '$payrequestno' ORDER BY id");
																								$query_rsMilestoneRecv->execute();
																								$row_rsMilestonePayRecv = $query_rsMilestoneRecv->fetch();
																								$Rows_rsMilestoneRecv = $query_rsMilestoneRecv->rowCount();

																								$query_rsMilestoneRecvFiles =  $db->prepare("SELECT floc FROM tbl_files WHERE fcategory='Payment' AND projid='$projid'");
																								$query_rsMilestoneRecvFiles->execute();
																								$row_rsMilestonePayRecvFiles = $query_rsMilestoneRecvFiles->fetch();
																								$Rows_rsMilestoneRecvFiles = $query_rsMilestoneRecvFiles->rowCount();
																								$query_rsCost = $db->prepare("SELECT SUM(d.unit_cost * d.units_no) as cost FROM tbl_project_tender_details d INNER JOIN tbl_task t ON t.tkid = d.tasks WHERE t.msid=:msid");
																								$query_rsCost->execute(array(":msid" => $mstnid));
																								$row_rsCost = $query_rsCost->fetch();

																								$query_dates = $db->prepare("SELECT MIN(sdate) as sdate, MAX(edate) as edate FROM tbl_task WHERE msid=:msid");
																								$query_dates->execute(array(":msid" => $mstnid));
																								$row_dates = $query_dates->fetch();

																								$milestone = $milestone["milestone"];
																								$milestone_amount = $row_rsCost["cost"];
																								$total_amount += $milestone_amount;
																								$milestone_start_date = $row_dates["sdate"];
																								$milestone_end_date = $row_dates["edate"];

																								echo
																								'<tr>
																									<td>' . $num . '</td>
																									<td scope="row" colspan="2">' . $milestone . '</td>
																									<td>' . $milestone_start_date . '</td>
																									<td>' . $milestone_end_date . '</td>
																									<td>' . number_format($milestone_amount, 2) . '</td> 
																									<td></td>
																								</tr>';
																							}
																						?>
																							<tr>
																								<td></td>
																								<td colspan="4" align="right">Total amount: </td>
																								<td><?php echo number_format($total_amount, 2); ?></td>
																								<td>
																									<?php
																									if ($Rows_rsMilestoneReq == 0 ||  $paystatus == 3 && $add) {
																									?>
																										<a type="button" class="btn bg-purple waves-effect" onclick="javascript:CallMlstPaymentRequest(<?php echo $msid . ',' . $total_amount ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here to request payment" style="height:25px; padding-top:0px"><i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Request</a>
																									<?php
																									} elseif ($paystatus == 1) {
																									?>
																										<i class="fa fa-refresh fa-spin fa-2x fa-fw text-success" data-toggle="tooltip" data-placement="right" title="Awaiting payment"></i><span class="sr-only">Loading...</span>
																									<?php
																									} elseif ($paystatus == 2 || $paystatus == 5 || $paystatus == 6) {
																									?>
																										<div style="color:#2196F3">Pending</div>
																									<?php
																									} elseif ($paystatus == 4) {
																									?>
																										<a href="<?php echo $row_rsMilestonePayRecv['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Download Payment Receipt" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
																									<?php
																									}
																									?>
																								</td>
																							</tr>
																						<?php
																						}
																						?>
																					</tbody>
																				</div>
																			</table>
																			<table class="table table-bordered">
																				<?php
																				if ($totalRows_rsProjFinancier > 0) {
																				?><div style="margin:2px">
																						<thead>
																							<tr style="font-size:12px; font-family:Verdana, Geneva, sans-serif; background-color:#83a4db; color:#FFF">
																								<th width="5%">SN</th>
																								<th width="40%">Financier</th>
																								<th width="35%">Financier Type</th>
																								<th width="20%">Amount (Ksh)</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$num = 0;
																							while ($row_rsProjFinancier = $query_rsProjFinancier->fetch()) {
																								$num++;
																								//$financierid = $row_rsProjFinancier['financier'];
																								$localamnt = $row_rsProjFinancier['amountfunding'];
																								//$sourcecat = $row_rsProjFinancier['sourcecategory'];
																								$localamnt = number_format($localamnt, 2);
																							?>
																								<tr>
																									<td><?php echo $num; ?></td>
																									<td><?php echo $row_rsProjFinancier['financier']; ?></td>
																									<td><?php echo $row_rsProjFinancier['description']; ?></td>
																									<td><?php echo $localamnt; ?></td>
																								</tr>
																							<?php } ?>
																						</tbody>
																					</div>
																				<?php
																				}
																				?>
																			</table>
																			<li class="dd-item" data-id="5">
																				<div class="dd-handle">
																					<font color="#2196F3">Milestone Payment Records</font>
																				</div>
																				<ol class="dd-list">
																					<div style="font-size:14px"><span class="badge bg-purple"><strong>Payment Requested</strong></span></div>
																					<table class="table table-bordered">
																						<thead>
																							<tr style="background-color:#eaf1fc">
																								<th width="10%">Request ID</th>
																								<th width="20%">Amount Requested (ksh)</th>
																								<?php if ($paystatus == 1 || $paystatus == 4 || $paystatus == 5) { ?>
																									<th width="30%">Requested By</th>
																									<th width="15%">Date Requested</th>
																								<?php } elseif ($paystatus == 2) { ?>
																									<th width="30%">Approved By</th>
																									<th width="15%">Date Approved</th>
																								<?php } elseif ($paystatus == 3) { ?>
																									<th width="30%">Rejected By</th>
																									<th width="15%">Date Rejected</th>
																								<?php } ?>
																								<th width="15%">Request Status</th>
																								<th width="10%">Comments</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							if ($Rows_rsMilestoneReq > 0) {
																								do {
																									$rqid = $row_rsMilestonePayReq['id'];
																									$requestref = $row_rsMilestonePayReq['requestid'];
																									$reqamnt = number_format($row_rsMilestonePayReq['amountrequested'], 2);
																									$rqstatus = $row_rsMilestonePayReq['status'];
																									$rqby = $row_rsMilestonePayReq['requestedby'];
																									$appby = $row_rsMilestonePayReq['approvalby'];
																									$rqdate = strtotime($row_rsMilestonePayReq['daterequested']);
																									$daterequested = date("d M Y", $rqdate);
																									$appdate = strtotime($row_rsMilestonePayReq['approvaldate']);
																									$dateapproved = date("d M Y", $appdate);

																									$query_rqPayStatus =  $db->prepare("SELECT status FROM tbl_payment_status where id='$rqstatus'");
																									$query_rqPayStatus->execute();
																									$row_rqPayStatus = $query_rqPayStatus->fetch();

																									$query_rqPayRequester =  $db->prepare("SELECT fullname FROM admin where username='$rqby'");
																									$query_rqPayRequester->execute();
																									$row_rqPayRequester = $query_rqPayRequester->fetch();
																									$count_row_rqPayRequester = $query_rqPayRequester->rowCount();
																									$requester = $count_row_rqPayRequester > 0 ? $row_rqPayRequester['fullname'] : 0;

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
																										<?php if ($rqstatus == 6) { ?>
																											<!-- # -->
																											<?php if ($paystatus == 1) { ?>
																												<!-- # Pending approval -->
																												<td><?php echo $requester; ?></td>
																												<td><?php echo $daterequested; ?></td>
																											<?php } elseif ($paystatus == 2 || $paystatus == 3) { ?>
																												<td><?php echo $approver; ?></td>
																												<td><?php echo $dateapproved; ?></td>
																											<?php } ?>
																											<td style="color:#F44336"><strong><?php echo $row_rqPayStatus['status']; ?></strong></td>
																											<td>
																												<a href="#" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" onclick="javascript:CallRequestComments(<?php echo $rqid; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click Here to View Comments"><i class="fa fa-comments fa-2x" aria-hidden="true"></i></a>
																											</td>
																										<?php } else {  ?>

																											<?php if ($paystatus == 1 || $paystatus == 4 || $paystatus == 5) { ?>
																												<td><?php echo $requester; ?></td>
																												<td><?php echo $daterequested; ?></td>
																											<?php } elseif ($paystatus == 2 || $paystatus == 3) { ?>
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
																							} else {
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
																								<th width="20%">Amount Funded (ksh)</th> <!-- Tafashi -->
																								<th width="30%">Payment Finance Officer</th>
																								<th width="15%">Payment Date</th>
																								<th width="20%">Mode of Payment</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							if ($Rows_rsMilestoneRecv > 0) {
																								do {
																									$rqid = $row_rsMilestonePayRecv['id'];
																									$requestref = $row_rsMilestonePayRecv['requestid'];
																									$recvamnt = $row_rsMilestonePayRecv['amountpaid'];
																									$recvamnt =  number_format($recvamnt, 2);
																									$paymentmode = $row_rsMilestonePayRecv['paymentmode'];
																									$fundsource = $row_rsMilestonePayRecv['fundsource'];
																									$paidby = $row_rsMilestonePayRecv['paidby'];
																									$paymdate = strtotime($row_rsMilestonePayRecv['datepaid']);
																									$paymentdate = date("d M Y", $paymdate);

																									//$start_date = date_format($projstartdate, "Y-m-d");
																									$current_date = date("Y-m-d");
																									if ($paymentmode == 1) {
																										$paymode = "Cash";
																									} elseif ($paymentmode == 2) {
																										$paymode = "M-Pesa";
																									} elseif ($paymentmode == 3) {
																										$paymode = "Airtel Money";
																									} elseif ($paymentmode == 4) {
																										$paymode = "Cheque";
																									} elseif ($paymentmode == 5) {
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
																							} else {
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
															} else {
																?>
																<li class="dd-item" data-id="9">
																	<div class="dd-handle" style="color:#F44336">No Milestone(s) due for payment</div>
																</li>
															<?php
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
			</div>
	</section>
	<!-- end body  -->
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg span5">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<font color="#000000">PROJECT STATUS CHANGE REASON(S)</font>
					</h4>
				</div>
				<div class="modal-body" id="formcontent">

				</div>
			</div>
		</div>
	</div>
	<!-- Modal Request Payment -->
	<div class="modal fade" id="payModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">PAYMENT REQUEST FORM</font>
					</h3>
				</div>
				<form id="payment-request-form" class="tagForm" action="savepaymentrequest" method="post" enctype="multipart/form-data">
					<div class="modal-body" id="requestformcontent">

					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Request Payment -->
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="recModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">PAYMENT RECEIPT FORM</font>
					</h3>
				</div>
				<form class="tagForm" action="savepaymentreceive" method="post" id="payment-receipt-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="receiveformcontent">
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">
													<font color="#174082">Payment Release Date</font>
												</label>
												<div class="col-sm-6 inputGroupContainer">
													<div class="input-group date" id="bs_datepicker_component_container">
														<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
														<input name="datepaid" type="text" title="d/m/Y" id="datepaid" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" placeholder="Format: 2019-12-31" />
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-sm-12 inputGroupContainer">
													<div class="input-group">
														<div style="margin-bottom:5px">
															<font color="#174082"><strong>Comments: </strong></font>
														</div>
														<textarea name="receivecomment" id="receivecomment" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%"></textarea>
														<script>
															CKEDITOR.replace("receivecomment", {
																height: "150px",
																toolbar: [{
																		name: "clipboard",
																		items: ["Cut", "Copy", "Paste", "PasteText", "PasteFromWord", "-", "Undo", "Redo"]
																	},
																	{
																		name: "editing",
																		items: ["Find", "Replace", "-", "SelectAll", "-", "Scayt"]
																	},
																	{
																		name: "insert",
																		items: ["Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "PageBreak", "Iframe"]
																	},
																	"/",
																	{
																		name: "styles",
																		items: ["Styles", "Format"]
																	},
																	{
																		name: "basicstyles",
																		items: ["Bold", "Italic", "Strike", "-", "RemoveFormat"]
																	},
																	{
																		name: "paragraph",
																		items: ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote"]
																	},
																	{
																		name: "links",
																		items: ["Link", "Unlink", "Anchor"]
																	},
																	{
																		name: "tools",
																		items: ["Maximize", "-", "About"]
																	}
																]

															});
														</script>
													</div>
												</div>
											</div>
											<div class="body">
												<table class="table table-bordered" id="funding_table">
													<tr>
														<th style="width:50%">Attachments</th>
													</tr>
													<tr>
														<td>
															<input type="file" name="file" id="file" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Receive Payment-->
	<!-- Modal -->
	<div class="modal fade" id="commModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Payment Request Comments</font>
					</h3>
				</div>
				<div class="modal-body" id="commentcontent">

				</div>
				<div class="modal-footer">
					<div class="col-md-4">
					</div>
					<div class="col-md-4" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
					<div class="col-md-4">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>

<!-- Jquery Nestable -->
<script src="projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
<script src="projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$('#payment-request-form').on('submit', function(event) {
			event.preventDefault();
			//var taskscore = $("#tskscid").val();
			//console.log(taskscore);
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "savepaymentrequest",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert('Request successfully sent');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	$(document).ready(function() {
		$('#payment-receipt-form').on('submit', function(event) {
			event.preventDefault();
			var form_info = new FormData(this);
			form_info.append('file', $('#file')[0].files[0]);
			$.ajax({
				type: "POST",
				url: "savepaymentreceive",
				data: form_info,
				dataType: "json",
				mimeType: 'multipart/form-data',
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					if (response) {
						alert('Record successfully saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});

	function CallMlstPaymentRequest(msid, amount) {
		$.ajax({
			type: 'post',
			url: 'callpaymentrequest',
			data: {
				mstid: msid,
				amount: amount
			},
			success: function(data) {
				$('#requestformcontent').html(data);
				$("#payModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallPaymentReceive(id) {
		$.ajax({
			type: 'post',
			url: 'callpaymentreceive',
			data: {
				rqid: id
			},
			success: function(data) {
				$('#receiveformcontent').html(data);
				$("#recModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRequestComments(id) {
		$.ajax({
			type: 'post',
			url: 'getreqcomments',
			data: {
				reqid: id
			},
			success: function(data) {
				$('#commentcontent').html(data);
				$("#commModal").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>