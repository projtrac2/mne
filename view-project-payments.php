<?php
try {
	require('includes/head.php');
	if ($permission) {
?>
		<style>
			.modal-lg {
				max-width: 100% !important;
				width: 90%;
			}
		</style>

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
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="card-header">
								<ul class="nav nav-tabs" style="font-size:14px">
									<li>
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
														<th style="width:10%">Due Date</th>
														<th style="width:10%">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_payments_request WHERE stage = 3 AND status != 3");
													$query_rsPayement_reuests->execute();
													$rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
													$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
													if ($total_rsPayement_reuests > 0) {
														$counter = 0;
														do {
															$request_id = $rows_rsPayement_reuests['request_id'];
															$costline_id = $rows_rsPayement_reuests['id'];
															$projid = $rows_rsPayement_reuests['projid'];
															$payment_requested_date = $rows_rsPayement_reuests['date_requested'];
															$payment_due_date = $rows_rsPayement_reuests['due_date'];
															$payment_status = $rows_rsPayement_reuests['status'];
															$payment_stage = $rows_rsPayement_reuests['stage'];

															$query_rsPayment_request_details =  $db->prepare("SELECT SUM(unit_cost * no_of_units) as amount_paid FROM tbl_payments_request_details WHERE request_id=:request_id");
															$query_rsPayment_request_details->execute(array(":request_id" => $request_id));
															$rows_rsPayment_request_details = $query_rsPayment_request_details->fetch();
															$amount_paid = $rows_rsPayment_request_details['amount_paid'];

															$query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
															$query_rsprojects->execute(array(":projid" => $projid));
															$rows_rsprojects = $query_rsprojects->fetch();
															$total_rsprojects = $query_rsprojects->rowCount();

															$progid = $rows_rsprojects['progid'];
															$project_name = $rows_rsprojects['projname'];
															$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
															$query_rsPrograms->execute(array(":progid" => $progid));
															$row_rsPrograms = $query_rsPrograms->fetch();
															$totalRows_rsPrograms = $query_rsPrograms->rowCount();

															$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
															$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
															$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";
															$filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);

															if ($filter_department) {
																$counter++;
													?>
																<tr class="">
																	<td><?= $counter ?></td>
																	<td><?= $project_name ?></td>
																	<td><?= number_format($amount_paid, 2) ?></td>
																	<td><?= date("Y-m-d", strtotime($payment_requested_date)) ?></td>
																	<td><?= date("Y-m-d", strtotime($payment_due_date)) ?></td>
																	<td>
																		<!-- Single button -->
																		<div class="btn-group">
																			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				Options <span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu">
																				<li>
																					<a type="button" id="addmoneval" data-toggle="modal" id="disburseItemModalBtn" data-target="#disburseItemModal" onclick="get_details('<?= $request_id ?>', <?= $total_amount ?>)">
																						<i class="fa fa-plus-square"></i> Disburse
																					</a>
																				</li>
																				<li>
																					<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="get_more_info(<?= $costline_id ?>)">
																						<i class="fa fa-info"></i>More Info
																					</a>
																				</li>
																			</ul>
																		</div>
																	</td>
																</tr>
													<?php
															}
														} while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch());
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
													$query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid FROM tbl_payments_request r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.request_id WHERE status = 3");
													$query_rsPayement_reuests->execute();
													$rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
													$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
													if ($total_rsPayement_reuests > 0) {
														$counter = 0;
														do {
															$costline_id = $rows_rsPayement_reuests['id'];
															$projid = $rows_rsPayement_reuests['projid'];
															$date_requested = $rows_rsPayement_reuests['date_requested'];
															$request_id = $rows_rsPayement_reuests['request_id'];
															$date_paid = $rows_rsPayement_reuests['date_paid'];
															$created_by = $rows_rsPayement_reuests['created_by'];
															$created_at = $rows_rsPayement_reuests['created_at'];

															$query_rsPayment_request_details =  $db->prepare("SELECT SUM(unit_cost * no_of_units) as amount_paid FROM tbl_payments_request_details WHERE request_id=:request_id");
															$query_rsPayment_request_details->execute(array(":request_id" => $request_id));
															$rows_rsPayment_request_details = $query_rsPayment_request_details->fetch();
															$amount_paid = $rows_rsPayment_request_details['amount_paid'];


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
															$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
															$query_rsPrograms->execute(array(":progid" => $progid));
															$row_rsPrograms = $query_rsPrograms->fetch();
															$totalRows_rsPrograms = $query_rsPrograms->rowCount();

															$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
															$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
															$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";
															$filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);
															if ($filter_department) {
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
																					<a type="button" href="#">
																						<i class="fa fa-info"></i>Receipt
																					</a>
																				</li>
																				<li>
																					<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="get_more_info(<?= $costline_id ?>)">
																						<i class="fa fa-info"></i> More Info
																					</a>
																				</li>
																			</ul>
																		</div>
																	</td>
																</tr>
													<?php
															}
														} while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch());
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
			</div>
		</section>
		<!-- end body  -->


		<!-- add item -->
		<div class="modal fade" id="moreItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Payment Request Details</span></h4>
					</div>
					<div class="modal-body" id="budget_line_more_info">
						<!-- /modal-body -->
					</div> <!-- /modal-content -->
					<div class="modal-footer">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						</div>
					</div> <!-- /modal-footer -->
				</div> <!-- /modal-dailog -->
			</div>
		</div>
		<!-- End add item -->


		<!-- Disburse item -->
		<div class="modal fade" id="disburseItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Disburse Funds</span></h4>
					</div>
					<form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
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
						</div> <!-- /modal-content -->
						<div class="modal-footer">
							<div class="col-md-12 text-center">
								<input type="hidden" name="request_id" id="request_id" value="">
								<input type="hidden" name="paid_to" id="paid_to" value="">
								<input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
								<input type="hidden" name="disburse" id="disburse" value="new">
								<button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">Save</button>
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/custom js/fetch-monitoring-evaluation.js"></script>

<script>
	//function to put commas to the data
	function commaSeparateNumber(val) {
		while (/(\d+)(\d{3})/.test(val.toString())) {
			val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
		}
		return val;
	}

	function get_more_info(costline_id) {
		if (costline_id != "") {
			$.ajax({
				type: "get",
				url: "ajax/payments/index",
				data: {
					get_more_info: "get_more_info",
					payment_request_id: costline_id,
				},
				dataType: "json",
				success: function(response) {
					if (response.success) {
						$("#budget_line_more_info").html(response.details);
					} else {
						console.log("data could not be found")
					}
				}
			});
		}
	}

	// sweet alert notifications
	function sweet_alert(msg) {
		return swal({
			title: "Error",
			text: msg,
			type: "Error",
			icon: 'warning',
			dangerMode: true,
			timer: 15000,
			showConfirmButton: false
		});
		setTimeout(function() {}, 15000);
	}

	function get_details(request_id, amount_requested) {
		$("#request_id").val(request_id);
		$("#amount_requested").val(commaSeparateNumber(amount_requested));

		if (request_id) {
			$.ajax({
				type: "get",
				url: "ajax/payments/index",
				data: {
					approval_details: "approval_details",
					request_id: request_id,
				},
				dataType: "json",
				success: function(response) {
					if (response.success) {
						$("#approve_budgetline_data").html(response.data);
					} else {
						sweet_alert("No data found!");
					}
				}
			});
		} else {
			sweet_alert("Error !!! Data could not be found");
		}
	}

	$(document).ready(function() {
		$("#modal_form_submit").submit(function(e) {
			e.preventDefault();
			var form = $(this)[0];
			var form_details = new FormData(form);
			$.ajax({
				type: "post",
				url: "ajax/payments/index",
				data: form_details,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(response) {
					if (response.success) {
						alert("Congratulations you have disbursed the budgetline");
						setTimeout(() => {
							window.location.reload(true);
						}, 100);
					} else {
						alert("Sorry record could not be created");
					}
				}
			});
		});
	});
</script>