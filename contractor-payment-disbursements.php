<?php
require('includes/head.php');
if ($permission) {
?>
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
					<div class="header" style="padding-bottom:0px">
						<div class="button-demo" style="margin-top:-15px">
							<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
							<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Contractor</a>
							<a href="inhouse-payment-disbursements.php" class="btn bg-light-blue waves-effect" style="margin-top:10px">In House</a>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home">
										<i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
										New Requests&nbsp;
										<span class="badge bg-deep-purple"></span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1">
										<i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
										Paid Requests&nbsp;
										<span class="badge bg-deep-purple"></span>
									</a>
								</li>
							</ul>
						</div>
						<div class="body">
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i>New Requests</h4>
									</div>
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-deep-purple">
													<th style="width:5%">#</th>
													<th style="width:35%">Project Name</th>
													<th style="width:10%">Requested Amount</th>
													<th style="width:10%">Date Requested</th>
													<th style="width:10%">Payment Plan</th>
													<th style="width:10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_contractor_payment_requests WHERE stage = 5 AND status<>3");
												$query_rsPayement_reuests->execute();
												$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
												if ($total_rsPayement_reuests > 0) {
													$counter = 0;
													while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch()) {
														$request_id = $rows_rsPayement_reuests['request_id'];
														$costline_id = $rows_rsPayement_reuests['id'];
														$projid = $rows_rsPayement_reuests['projid'];
														$payment_requested_date = $rows_rsPayement_reuests['created_at'];
														$amount_paid = $rows_rsPayement_reuests['requested_amount'];
														$payment_status = $rows_rsPayement_reuests['status'];
														$payment_stage = $rows_rsPayement_reuests['stage'];

														$request_details = "{
                                                            request_id: '$costline_id',
															total_amount:$amount_paid,
                                                        }";

														$query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
														$query_rsprojects->execute(array(":projid" => $projid));
														$rows_rsprojects = $query_rsprojects->fetch();
														$total_rsprojects = $query_rsprojects->rowCount();

														$progid = $rows_rsprojects['progid'];
														$project_name = $rows_rsprojects['projname'];
														$payment_plan = $rows_rsprojects['payment_plan'];
														$contrid = $rows_rsprojects['projcontractor'];

														$payment_plan_name = "";
														if ($payment_plan == 1) {
															$payment_plan_name = "Milestone";
														} else if ($payment_plan == 2) {
															$payment_plan_name = "Task";
														} else if ($payment_plan == 3) {
															$payment_plan_name = "Work Measured";
														}

														// contractor_name contract_no
														$query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid");
														$query_rsTender->execute(array(":projid" => $projid));
														$row_rsTender = $query_rsTender->fetch();
														$totalRows_rsTender = $query_rsTender->rowCount();
														$contract_no = $totalRows_rsTender > 0 ? $row_rsTender['contractrefno'] : '';

														$query_rsContractor = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid = :contrid");
														$query_rsContractor->execute(array(":contrid" => $contrid));
														$row_rsContractor = $query_rsContractor->fetch();
														$totalRows_rsContractor = $query_rsContractor->rowCount();
														$contractor_name = $totalRows_rsContractor > 0 ? $row_rsContractor['contractor_name'] : '';

														$approval_details = "{
															project_name:'$project_name',
															projid: $projid,
															payment_plan: $payment_plan,
															request_id: '$costline_id',
															contractor_name:'$contractor_name',
															contract_no:'$contract_no',
															stage:$payment_stage
														}";

														$counter++;
												?>
														<tr class="">
															<td><?= $counter ?></td>
															<td><?= $project_name ?></td>
															<td><?= number_format($amount_paid, 2) ?></td>
															<td><?= date("Y-m-d", strtotime($payment_requested_date)) ?></td>
															<td><?= $payment_plan_name ?></td>
															<td>
																<!-- Single button -->
																<div class="btn-group">
																	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		Options <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																		<li>
																			<a type="button" id="addmoneval" data-toggle="modal" id="disburseItemModalBtn" data-target="#disburseItemModal" onclick="disburse_funds('<?= $projid ?>', '<?= $costline_id ?>', '<?= $amount_paid ?>', '<?= number_format($amount_paid,2) ?>')">
																				<i class="fa fa-plus-square"></i> Disburse
																			</a>
																		</li>
																		<li>
																			<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#approve_data" onclick="get_details(<?= $approval_details ?>)">
																				<i class="fa fa-info"></i>More Info
																			</a>
																		</li>
																	</ul>
																</div>
															</td>
														</tr>
												<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<div id="menu1" class="tab-pane">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i> Paid Requests</h4>
									</div>
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-deep-purple">
													<th style="width:5%">#</th>
													<th style="width:45%">Project Name</th>
													<th style="width:10%">Amount Paid</th>
													<th style="width:10%">Date Requested</th>
													<th style="width:10%">Paid By</th>
													<th style="width:10%">Date Paid</th>
													<th style="width:10%">Other Details</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid, d.receipt FROM tbl_contractor_payment_requests r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.id WHERE status = 3 AND request_type=2");
												$query_rsPayement_reuests->execute();
												$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
												if ($total_rsPayement_reuests > 0) {
													$counter = 0;
													while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch()) {
														$costline_id = $rows_rsPayement_reuests['id'];
														$projid = $rows_rsPayement_reuests['projid'];
														$date_requested = $rows_rsPayement_reuests['created_at'];
														$request_id = $rows_rsPayement_reuests['request_id'];
														$date_paid = $rows_rsPayement_reuests['date_paid'];
														$amount_paid = $rows_rsPayement_reuests['requested_amount'];
														$created_by = $rows_rsPayement_reuests['created_by'];
														$created_at = $rows_rsPayement_reuests['created_at'];
														$payment_stage = $rows_rsPayement_reuests['stage'];
														$receipt = $rows_rsPayement_reuests['receipt'];

														$get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
														$get_user->execute(array(":user_id" => $created_by));
														$count_user = $get_user->rowCount();
														$user = $get_user->fetch();
														$officer = $user['fullname'];

														$query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
														$query_rsprojects->execute(array('projid' => $projid));
														$rows_rsprojects = $query_rsprojects->fetch();
														$total_rsprojects = $query_rsprojects->rowCount();

														$progid = $rows_rsprojects['progid'];
														$project_name = $rows_rsprojects['projname'];
														$payment_plan = $rows_rsprojects['payment_plan'];
														$contrid = $rows_rsprojects['projcontractor'];

														// contractor_name contract_no
														$query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid");
														$query_rsTender->execute(array(":projid" => $projid));
														$row_rsTender = $query_rsTender->fetch();
														$totalRows_rsTender = $query_rsTender->rowCount();
														$contract_no = $totalRows_rsTender > 0 ? $row_rsTender['contractrefno'] : '';

														$query_rsContractor = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid = :contrid");
														$query_rsContractor->execute(array(":contrid" => $contrid));
														$row_rsContractor = $query_rsContractor->fetch();
														$totalRows_rsContractor = $query_rsContractor->rowCount();
														$contractor_name = $totalRows_rsContractor > 0 ? $row_rsContractor['contractor_name'] : '';

														$approval_details = "{
															project_name:'$project_name',
															projid: $projid,
															payment_plan: $payment_plan,
															request_id: '$costline_id',
															contractor_name:'$contractor_name',
															contract_no:'$contract_no',
															stage:$payment_stage
														}";
														$counter++;
												?>
														<tr class="">
															<td><?= $counter ?></td>
															<td><?= $project_name ?></td>
															<td><?= number_format($amount_paid, 2) ?></td>
															<td><?= date("Y-m-d", strtotime($date_requested)) ?></td>
															<td><?= $officer ?></td>
															<td><?= date("Y-m-d", strtotime($date_paid)) ?></td>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		Options <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																		<li>
																			<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#approve_data" onclick="get_details(<?= $approval_details ?>)">
																				<i class="fa fa-info"></i> More Info
																			</a>
																		</li>
																		<li>
																			<a type="button" href="<?= $receipt ?>">
																				<i class="fa fa-info"></i>Receipt
																			</a>
																		</li>
																	</ul>
																</div>
															</td>
														</tr>
												<?php

													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- ============================================================== -->
							<!-- End PAge Content -->
							<!-- ============================================================== -->
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<div class="modal fade" id="approve_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Payment Request Details</span></h4>
				</div>
				<form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									<i class="fa fa-comment" aria-hidden="true"></i> Request Details
								</legend>
								<div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="outcomeIndicator" class="control-label">Project Name:</label>
										<div class="form-line">
											<input type="text" name="project_name" value="" id="project_name" class="form-control" readonly>
										</div>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
										<label for="tasks" class="control-label">Contractor:</label>
										<div class="form-line">
											<input type="text" name="contractor_name" value="" id="contractor_name" class="form-control" readonly>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<label for="location" class="control-label">Contract Number:</label>
										<div class="form-line">
											<input type="text" name="contractor_number" value="" id="contractor_number" class="form-control" readonly>
										</div>
									</div>

									<div id="milestones" style="margin-top:15px; margin-bottom:15px">
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="payment_phase" class="control-label">Payment Phase:</label>
											<div class="form-line">
												<input type="text" name="payment_phase" value="" id="payment_phase" class="form-control" readonly>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="request_percentage" class="control-label">Percentage:</label>
											<div class="form-line">
												<input type="text" name="request_percentage" value="" id="request_percentage" class="form-control" readonly>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="request_amount" class="control-label">Request Amount:</label>
											<div class="form-line">
												<input type="text" name="request_amount" value="" id="request_amount" class="form-control" readonly>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th style="width:5%"># </th>
															<th style="width:95%">Milestone</th>
														</tr>
													</thead>
													<tbody id="milestone_table">

													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div id="tasks" style="margin-top:15px; margin-bottom:15px">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive" style="margin-top:15px; margin-bottom:15px">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th style="width:5%"># </th>
															<th style="width:20%">Output</th>
															<th style="width:20%">Site</th>
															<th style="width:25%">Subtask</th>
															<th style="width:10%">Units No.</th>
															<th style="width:10%">Unit Cost</th>
															<th style="width:10%">Cost</th>
														</tr>
													</thead>
													<tbody id="tasks_table">
														<tr></tr>
														<tr id="removeTr" class="text-center">
															<td colspan="5">Add Tasks</td>
														</tr>
													</tbody>
													<tfoot id="tasks_foot">
														<tr>
															<td colspan="6"><strong>Total</strong></td>
															<td id="subtotal"></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div id="comments_div"></div>
							</fieldset>
							<fieldset class="scheduler-border disbursed_div">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									<i class="fa fa-comment" aria-hidden="true"></i> Financiers
								</legend>
								<div id="comment_section">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
												<thead>
													<tr>
														<th width="10%">#</th>
														<th width="60%">Financier</th>
														<th width="35%">Amount</th>
													</tr>
												</thead>
												<tbody id="financier_table_body">
													<tr></tr>
													<tr id="removeTr">
														<td colspan="7">Add Financiers</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<!-- /modal-body -->
					</div> <!-- /modal-content -->
					<div class="modal-footer">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						</div>
					</div> <!-- /modal-footer -->
				</form>
			</div> <!-- /modal-dailog -->
		</div>
	</div>

	<!-- Disburse item -->
	<div class="modal fade" id="disburseItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Disburse Funds</span></h4>
				</div>
				<form class="form-horizontal" id="modal_submit_form" action="" method="POST" enctype="multipart/form-data">
					<div class="modal-body" id="">
						<!-- /modal-body -->
						<div id="approve_budgetline_data">

						</div>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								<i class="fa fa-comment" aria-hidden="true"></i> Disbursements Details
							</legend>
							<div class="col-md-3">
								<label class="control-label">Amount Requested<span id="disbursed_date"></span>:</label>
								<div class="form-input">
									<input type="amount" name="amount_requested" value="" id="amount_requested" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<label for="payment_mode" class="control-label">Payment Mode *:</label>
								<div class="form-line">
									<select name="payment_mode" id="payment_mode" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
										<option value="">.... Select from list ....</option>
										<option value="1">Option A</option>
										<option value="2">Option B</option>
										<option value="3">Option C</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<label class="control-label">Receipt <span id="impunit"></span>*:</label>
								<div class="form-input">
									<input type="file" name="receipt" value="" id="receipt" class="form-control" required="required">
								</div>
							</div>
							<div class="col-md-3">
								<label class="control-label">Date Paid<span id="disbursed_date"></span>*:</label>
								<div class="form-input">
									<input type="date" name="date_paid" value="" id="date_paid" class="form-control" required="required">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="">
								<label for="invoice" class="control-label">Invoice Attachment:</label>
								<div class="form-line">
									<div id="attachment_div"></div>
								</div>
							</div>
							<div id="comments_div"></div>
						</fieldset>
						<fieldset class="scheduler-border disbursed_div">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								<i class="fa fa-comment" aria-hidden="true"></i> Financiers
							</legend>
							<div id="comment_section">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
											<thead>
												<tr>
													<th width="10%">#</th>
													<th width="30%" colspan="3">Financier</th>
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
											<tbody id="financier_table_body_d">
												<tr></tr>
												<tr id="removeTr1">
													<td colspan="7">Add Financiers</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								<i class="fa fa-comment" aria-hidden="true"></i> Add Remarks
							</legend>
							<div id="comment_section">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label class="control-label">Remarks *:</label>
									<br>
									<div class="form-line">
										<textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
									</div>
								</div>
							</div>
						</fieldset>
					</div> <!-- /modal-content -->
					<div class="modal-footer">
						<div class="col-md-12 text-center">
							<input type="hidden" name="request_id" id="request_id" value="">
							<input type="hidden" name="paid_to" id="paid_to" value="">
							<input type="hidden" name="disburse_amount" id="disburse_amount" value="">
							<input type="hidden" name="projid" id="projid" value="">
							<input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
							<input type="hidden" name="disburse_contractor_payment" id="disburse_contractor_payment" value="new">
							<button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">Disburse</button>
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						</div>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div> <!-- /modal-dailog -->
		</div>
	</div>
	<!-- End Disburse item -->
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/payment/contractor.js"></script>