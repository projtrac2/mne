<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
if ($permission) {
    $pageTitle ="Financial Requests Payment Dashboard";
		try {
			if (isset($_GET['contrid'])) {
				$contrid_rsInfo = $_GET['contrid'];
			}
			$query_rsPayRequest = $db->prepare("SELECT R.*,P.* FROM tbl_payments_request R INNER JOIN tbl_projects P ON R.projid=P.projid WHERE status = 1 OR status = 7 Order BY itemcategory ASC");
			$query_rsPayRequest->execute();
			$totalRows_rsPayRequest = $query_rsPayRequest->rowCount();

			$query_rsPayApproved = $db->prepare("SELECT R.*,P.* FROM tbl_payments_request R INNER JOIN tbl_projects P ON R.projid=P.projid WHERE status = 2 OR status = 6 Order BY itemcategory ASC");
			$query_rsPayApproved->execute();
			$totalRows_rsPayApproved = $query_rsPayApproved->rowCount();

			$query_rsPayRejected = $db->prepare("SELECT R.*,P.* FROM tbl_payments_request R INNER JOIN tbl_projects P ON R.projid=P.projid WHERE status = 3 Order BY itemcategory ASC");
			$query_rsPayRejected->execute();
			$totalRows_rsPayRejected = $query_rsPayRejected->rowCount();

			$query_rsPayReceived = $db->prepare("SELECT tbl_payments_request.*, tbl_payments_disbursed.*, tbl_payments_request.itemid AS ritem,tbl_payments_request.requestid AS req FROM tbl_payments_disbursed INNER JOIN tbl_payments_request ON tbl_payments_disbursed.reqid=tbl_payments_request.id Order BY itemcategory ASC");
			$query_rsPayReceived->execute();
			$totalRows_rsPayReceived = $query_rsPayReceived->rowCount();
		} catch (PDOException $ex) {
			$results = flashMessage("An error occurred: " . $ex->getMessage());
		}
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
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
												<ul class="nav nav-tabs" style="font-size:14px">
													<li class="active">
														<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Approved Requests &nbsp;<span class="badge bg-blue"><?php echo $totalRows_rsPayApproved; ?></span></a>
													</li>
													<li>
														<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-right bg-green" aria-hidden="true"></i> Requests Paid &nbsp;<span class="badge bg-light-green"><?php echo $totalRows_rsPayReceived; ?></span></a>
													</li>
												</ul>
											</div>
                        <div class="body">
														<div class="table-responsive">
															<div class="tab-content">
																<div id="home" class="tab-pane fade in active">
																	<div style="color:#333; background-color:#EEE; width:100%; height:30px">
																		<h4><i class="fa fa-list" style="font-size:25px;color:blue"></i> Project Payment Requests Approved</h4>
																	</div>

																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																		<thead>
																			<tr id="colrow">
																				<th style="width:3%">#</th>
																				<th style="width:8%">Request ID</th>
																				<th style="width:10%">Category</th>
																				<th style="width:29%">Item Name</th>
																				<th style="width:10%">Amount(Ksh)</th>
																				<th style="width:10%">Approver</th>
																				<th style="width:12%">Payment Due Date</th>
																				<th style="width:10%">Status</th>
																				<?php
																				if ($role_group) {
																				?>
																					<th style="width:8%">Action</th>
																				<?php
																				}
																				?>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			$nm = 0;
																			while ($rsPayApproved = $query_rsPayApproved->fetch()) {
																				$nm = $nm + 1;
																				$approvedid = $rsPayApproved['id'];
																				$reqidno = $rsPayApproved['requestid'];
																				$project = $rsPayApproved['projname'];
																				$projid = $rsPayApproved['projid'];
																				$category = $rsPayApproved['itemcategory'];
																				$itemid = $rsPayApproved['itemid'];
																				$approvedstatus = $rsPayApproved['status'];
																				$approveddate = $rsPayApproved['approvaldate'];
																				$approvedby = $rsPayApproved['approvalby'];
																				$approvedamnt = number_format($rsPayApproved['amountrequested'], 2);

																				$appduedate = strtotime($approveddate . "+ 30 days");
																				$paymentduedate = date("d M Y", $appduedate);

																				$payappdate = strtotime($approveddate);
																				$paymentappdate = date("d M Y", $payappdate);

																				if ($category == 1) {
																					$itemcat = "Task";
																					$query_item = $db->prepare("SELECT * FROM tbl_task WHERE tkid = '$itemid'");
																					$query_item->execute();
																					$row_item = $query_item->fetch();
																					$itemname = $row_item["task"];
																					$itemstatus = $row_item["status"];
																				} else {
																					$itemcat = "Milestone";
																					$query_item = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = '$itemid'");
																					$query_item->execute();
																					$row_item = $query_item->fetch();
																					$itemname = $row_item["milestone"];
																					$itemstatus = $row_item["status"];
																				}

																				$query_appstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$approvedstatus'");
																				$query_appstatus->execute();
																				$row_appstatus = $query_appstatus->fetch();
																				$approvalstatus = $row_appstatus["status"];

																				$query_approver = $db->prepare("SELECT fullname FROM admin WHERE username = '$approvedby'");
																				$query_approver->execute();
																				$row_approver = $query_approver->fetch();
																				$approver = $row_approver["fullname"];
																			?>
																				<tr>
																					<td align="center"><?php echo $nm; ?></td>
																					<td><?php echo $reqidno; ?></td>
																					<td><?php echo $itemcat; ?></td>
																					<td><?php echo $itemname; ?></td>
																					<td><?php echo $approvedamnt; ?></td>
																					<td><?php echo $approver; ?></td>
																					<td><?php echo $paymentduedate; ?></td>
																					<?php
																					if ($approvedstatus == 6) { ?>
																						<td style="color:#F44336"><b><?php echo $approvalstatus; ?></b></td>
																					<?php
																					} else {
																					?>
																						<td><?php echo $approvalstatus; ?></td>
																					<?php
																					}
																					if ($role_group) {
																					?>
																						<td>
																							<a type="button" class="btn bg-green waves-effect" onclick="javascript:CallPaymentDisburse(<?php echo $rsPayApproved['id']; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here to request payment" style="height:25px; padding-top:0px"><i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Pay</a>
																						</td>
																					<?php
																					}
																					?>
																				</tr>
																			<?php
																			}
																			?>
																		</tbody>
																	</table>
																</div>
																<div id="menu1" class="tab-pane fade">
																	<div style="color:#333; background-color:#EEE; width:100%; height:30px">
																		<h4><i class="fa fa-list" style="font-size:25px;color:green"></i> Project Payment Requests Paid</h4>
																	</div>
																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																		<thead>
																			<tr class="bg-green">
																				<th style="width:3%">#</th>
																				<th style="width:10%">Request ID</th>
																				<th style="width:10%">Reference ID</th>
																				<th style="width:10%">Category</th>
																				<th style="width:25%">Item Name</th>
																				<th style="width:10%">Amount paid (Ksh)</th>
																				<th style="width:15%">Paid By</th>
																				<th style="width:10%">Date Paid</th>
																				<th style="width:7%">Receipt</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			$nm = 0;
																			while ($rsPayReceived = $query_rsPayReceived->fetch()) {
																				$nm = $nm + 1;
																				$receivedid = $rsPayReceived['id'];
																				$reqidno = $rsPayReceived['req'];
																				$refno = $rsPayReceived['refid'];
																				$category = $rsPayReceived['itemcategory'];
																				$itemid = $rsPayReceived['ritem'];
																				$receivedstatus = $rsPayReceived['status'];
																				$pmntdate = $rsPayReceived['datepaid'];
																				$paidbyid = $rsPayReceived['paidby'];
																				$receiveddate = $rsPayReceived['daterecorded'];
																				$receivedby = $rsPayReceived['recordedby'];
																				$amountpaid = number_format($rsPayReceived['amountpaid'], 2);

																				$paymdate = strtotime($pmntdate);
																				$paymentdate = date("d M Y", $paymdate);

																				$payrecdate = strtotime($receiveddate);
																				$paymentrecdate = date("d M Y", $payrecdate);

																				if ($category == 1) {
																					$itemcat = "Task";
																					$query_item = $db->prepare("SELECT * FROM tbl_task WHERE tkid = '$itemid'");
																					$query_item->execute();
																					$row_item = $query_item->fetch();
																					$itemname = $row_item["task"];
																					$itemstatus = $row_item["status"];

																					$query_file = $db->prepare("SELECT floc FROM tbl_files WHERE tkid = '$itemid'");
																					$query_file->execute();
																					$row_file = $query_file->fetch();
																				} else {
																					$itemcat = "Milestone";
																					$query_item = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = '$itemid'");
																					$query_item->execute();
																					$row_item = $query_item->fetch();
																					$itemname = $row_item["milestone"];
																					$itemstatus = $row_item["status"];
																				}
																				$query_user = $db->prepare("SELECT fullname FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid WHERE userid = '$paidbyid'");
																				$query_user->execute();
																				$row_user = $query_user->fetch();
																				$paidby = $row_user["fullname"];
																			?>
																				<tr>
																					<td align="center"><?php echo $nm; ?></td>
																					<td><?php echo $reqidno; ?></td>
																					<td><?php echo $refno; ?></td>
																					<td><?php echo $itemcat; ?></td>
																					<td><?php echo $itemname; ?></td>
																					<td><?php echo $amountpaid; ?></td>
																					<td><?php echo $paidby; ?></td>
																					<td><?php echo $paymentdate; ?></td>
																					<td>
																						<a href="#<?php //echo $row_file['floc'];
																									?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Click Here to Download Payment Receipt" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
																					</td>
																				</tr>
																			<?php
																			}
																			?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
		<!-- Modal Receive Payment -->
		<div class="modal fade" id="recModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title" align="center">
							<font color="#FFF">PAYMENT DISBURSEMENT FORM</font>
						</h3>
					</div>
					<form class="tagForm" action="savepaymentreceive" method="post" id="payment-disburse-form" enctype="multipart/form-data" autocomplete="off">
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
														<div class="input-group">
															<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
															<input name="datepaid" type="date" title="d/m/Y" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" placeholder="Format: 2019-12-31" />
														</div>
													</div>
												</div>

												<div class="form-group">
													<div class="col-sm-12 inputGroupContainer">
														<div class="input-group">
															<div style="margin-bottom:5px">
																<font color="#174082"><strong>Comments: </strong></font>
															</div>
															<textarea name="receivecomment" id="receivecomment" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:99.5%"></textarea>
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
?>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$('#payment-disburse-form').on('submit', function(event) {
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
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});

	function CallPaymentDisburse(id) {
		$.ajax({
			type: 'post',
			url: 'callpaymentdisburse.php',
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
		$('#approvalstatus').on('change', function() {
			var actionID = $(this).val();
			var reqid = $("#requestid").val();

			$.ajax({
				type: 'post',
				url: 'callapprovalform.php',
				data: "actid=" + actionID + "&rqid=" + reqid,
				success: function(data) {
					$('#actionformcontent').html(data);
					$("#actModal").modal({
						backdrop: "static"
					});
				}
			});
		})
	})
</script>
