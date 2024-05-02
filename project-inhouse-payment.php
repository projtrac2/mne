<?php
try {
	$pageName = "Strategic Plans";
	$replacement_array = array(
		'planlabel' => "CIDP",
		'plan_id' => base64_encode(6),
	);

	$page = "view";
	require('includes/head.php');
	if ($permission) {
		$query_ipProjects =  $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projcategory = '1' AND (projstatus = 4  OR projstatus = 11)");
		$query_ipProjects->execute();
		$Rows_ipProjects = $query_ipProjects->rowCount();

		$query_rsUsers =  $db->prepare("SELECT * FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$user_name'");
		$query_rsUsers->execute();
		$row_rsUsers = $query_rsUsers->fetch();
		$totalRows_rsUsers = $query_rsUsers->rowCount();
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
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="header" style="padding-bottom:0px">
								<div class="button-demo" style="margin-top:-15px">
									<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
									<a href="project-milestones-payment.php" class="btn bg-light-blue waves-effect" style="margin-top:10px">Contractor</a>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>
									<a href="certificateofcompletion.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Completion Certificates</a>
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
													<div align="left"><i class="fa fa-balance-scale" aria-hidden="true"></i> In House Projects Payment Dashboard</strong></div>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="body">
								<div class="body table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">
													Tasks Ready For Payment
												</th>
											</tr>
											<tr id="colrow">
												<th style="width:5%"><strong>#</strong></th>
												<th style="width:10%"><strong>Project Code</strong></th>
												<th style="width:65%"><strong>Project Name</strong></th>
												<th style="width:20%"><strong>Project Status</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="7">
													<div class="clearfix m-b-20">
														<div class="dd" id="nestable">
															<ol class="dd-list">
																<?php
																try {
																	if ($Rows_ipProjects > 0) {
																		$sn = 0;
																		while ($row_ipProjects = $query_ipProjects->fetch()) {
																			$sn = $sn + 1;
																			$progid = $row_ipProjects['progid'];
																			$projid = $row_ipProjects['projid'];
																			$projcode = $row_ipProjects['projcode'];
																			$projname = $row_ipProjects['projname'];
																			$projstatusid = $row_ipProjects['projstatus'];
																			$projbudget = $row_ipProjects['projcost'];

																			$subcounty = $row_ipProjects['projcommunity'];
																			$projectStatus = $row_ipProjects['projstatus'];
																			$projbudget = $row_ipProjects['projcost'];

																			$query_projstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$projstatusid'");
																			$query_projstatus->execute();
																			$row_projstatus = $query_projstatus->fetch();
																			$projstatus = $row_projstatus['statusname'];

																			$query_rsSubCounty =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$subcounty'");
																			$query_rsSubCounty->execute();
																			$row_rsSubCounty = $query_rsSubCounty->fetch();
																			$totalRows_rsSubCounty = $query_rsSubCounty->rowCount();

																			$projlocation = 'Location';

																			$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier inner join tbl_financier_type t on t.id= f.type WHERE projid = :projid ORDER BY amountfunding desc");
																			$query_rsProjFinancier->execute(array(":projid" => $projid));
																			$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																			$query_amntPaid =  $db->prepare("SELECT SUM(d.amountpaid) AS totalamount FROM tbl_projects p LEFT JOIN tbl_payments_request r ON p.projid = r.projid LEFT JOIN tbl_payments_disbursed d ON r.id = d.reqid WHERE p.projid = '$projid'");
																			$query_amntPaid->execute();
																			$row_amntPaid = $query_amntPaid->fetch();
																			$totalRows_amntPaid = $query_amntPaid->rowCount();
																			if (empty($row_amntPaid['totalamount']) || $row_amntPaid['totalamount'] == '') {
																				$amountpaid = 0;
																			} else {
																				$amountpaid = $row_amntPaid['totalamount'];
																			}

																			$totalprojamountpaid = number_format($amountpaid, 2);
																			$utilrate = $amountpaid > 0 && $projbudget > 0 ? ($amountpaid / $projbudget) * 100 : 0;
																?>
																			<li class="dd-item" data-id="4">
																				<div class="dd-handle"><?php echo $sn; ?>. &nbsp;&nbsp;|&nbsp;&nbsp; <font color="#4CAF50" width="20%"> <?php echo $projcode . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $projname . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $projstatus; ?></font>
																				</div>
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
																								<td><strong>In House</strong></td>
																								<td><?php echo number_format($row_ipProjects['projcost'], 2); ?></td>
																								<td><?php echo $totalprojamountpaid; ?></td>
																								<td align="center"><?php echo $utilrate . "%"; ?></td>
																								<?php
																								if ($projectStatus == 2 || $projectStatus == 6) {
																								?>
																									<td><?php echo "Project " . $row_projstatus['statusname']; ?></td>
																								<?php
																								} else {
																								?>
																									<td><?php echo $row_projstatus['statusname']; ?></td>
																								<?php
																								}

																								$prjsdate = strtotime($row_ipProjects['projstartdate']);
																								$prjedate = strtotime($row_ipProjects['projenddate']);
																								$prjstartdate = date("d M Y", $prjsdate);
																								$prjenddate = date("d M Y", $prjedate);
																								?>
																								<td><?php echo $projlocation; ?></td>
																							</tr>
																						</tbody>
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
																										<th width="20%">Local Amount (Ksh)</th>
																									</tr>
																								</thead>
																								<tbody>
																									<?php
																									$num = 0;
																									while ($row_rsProjFinancier = $query_rsProjFinancier->fetch()) {
																										$num++;
																										$financierid = $row_rsProjFinancier['financier'];
																										$localamnt = $row_rsProjFinancier['amountfunding'];
																										$sourcecat = $row_rsProjFinancier['sourcecategory'];
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
																							<font color="#2196F3">Task Funding Records</font>
																						</div>
																						<ol class="dd-list">
																							<div style="font-size:14px"><span class="badge bg-purple"><strong>Funds Requested</strong></span></div>
																							<table class="table table-bordered">
																								<thead>
																									<tr style="background-color:#eaf1fc">
																										<th width="10%">Request ID</th>
																										<th width="20%">Amount Requested (ksh)</th>
																										<?php if ($paystatus == 1 || $paystatus == 4 || $paystatus == 5) { ?>
																											<th width="30%">Requested By</th>
																											<th width="15%">Request Date</th>
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
																									if ($Rows_rsTaskPayReq > 0) {
																										do {
																											$rqid = $row_rsTaskPayReq['id'];
																											$requestref = $row_rsTaskPayReq['requestid'];
																											$reqamnt = number_format($row_rsTaskPayReq['amountrequested'], 2);
																											$rqstatus = $row_rsTaskPayReq['status'];
																											$rqby = $row_rsTaskPayReq['requestedby'];
																											$appby = $row_rsTaskPayReq['approvalby'];
																											$rqdate = strtotime($row_rsTaskPayReq['daterequested']);
																											$daterequested = date("d M Y", $rqdate);
																											$appdate = strtotime($row_rsTaskPayReq['approvaldate']);
																											$dateapproved = date("d M Y", $appdate);

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
																												<?php if ($rqstatus == 6) { ?>
																													<?php if ($paystatus == 1) { ?>
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
																										} while ($row_rsTaskPayReq = $query_rsTaskPayReq->fetch());
																									} else {
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
																									if ($Rows_rsTaskPayRecv > 0) {
																										do {
																											$rqid = $row_rsTaskPayRecv['id'];
																											$requestref = $row_rsTaskPayRecv['requestid'];
																											$recvamnt = $row_rsTaskPayRecv['amountpaid'];
																											$recvamnt =  number_format($recvamnt, 2);
																											$paymentmode = $row_rsTaskPayRecv['paymentmode'];
																											$fundsource = $row_rsTaskPayRecv['fundsource'];
																											$paidby = $row_rsTaskPayRecv['paidby'];
																											$paymdate = strtotime($row_rsTaskPayRecv['datepaid']);
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
																										} while ($row_rsTaskPayRecv = $query_rsTaskPayRecv->fetch());
																									} else {
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
																	} else {
																		?>
																		<li class="dd-item" data-id="9">
																			<div class="dd-handle" style="color:#F44336">No Task(s) due for fund request</div>
																		</li>
																<?php
																	}
																} catch (PDOException $ex) {
																	$result = flashMessage("An error occurred: " . $ex->getMessage());
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
					<form class="tagForm" action="callpaymentrequest" method="post" id="payment-request-form" enctype="multipart/form-data">
						<?= csrf_token_html(); ?>
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
						<?= csrf_token_html(); ?>
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
							<font color="#FFF">Funds/Payment Request Comments</font>
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>


<!-- Jquery Nestable -->
<script src="assets/projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
<script src="assets/projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>

<script src="assets/ckeditor/ckeditor.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');

			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {

				$(".submenus").show();
				$(this).attr('id', '1');
			}

		});

		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});


		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});

	function CallTskPaymentRequest(tkid) {
		$.ajax({
			type: 'post',
			url: 'callpaymentrequest.php',
			data: {
				tskid: tkid
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
			url: 'callpaymentreceive.php',
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
			url: 'getreqcomments.php',
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
	$(document).ready(function() {
		$('#payment-request-form').on('submit', function(event) {
			event.preventDefault();
			//var taskscore = $("#tskscid").val();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "savepaymentrequest.php",
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
				url: "savepaymentreceive.php",
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
					} else {
						alert('Error saving record');
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
</script>