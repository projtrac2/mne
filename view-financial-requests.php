<?php
try {
	//code...

require('includes/head.php'); 
if ($permission) {
	function project_permission($project_department, $project_section, $payment_stage)
	{
		global $role_group, $designation, $ministry, $sector;
		$msg = false;
		if ($role_group == 4 && $designation == 1) {
			$msg = true;
		} else {
			if ($role_group == 1 && $payment_stage > 1) {
				$msg = true;
			} else if ($role_group == 2) {
				if ($ministry == $project_department) {
					if ($sector == $project_section) {
						$msg = true;
					}
				}
			}
		}
		return $msg;
	}
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
								<li class="active">
									<a data-toggle="tab" href="#home">
										<i class="fa fa-caret-square-o-down bg-purple" aria-hidden="true"></i>
										New Requests&nbsp;
										<span class="badge bg-purple"></span>
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1">
										<i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
										Paid Requests &nbsp;
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
										<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i> New</h4>
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
													<th style="width:10%">Stage</th>
													<th style="width:10%">Status</th>
													<th style="width:10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_payments_request WHERE status != 3");
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

														$status = $stage = "";
														if ($payment_stage == 1) {
															$status  = $payment_status == 1 ? "Pending" : "Rejected";
															$stage = "CO Department";
														} else if ($payment_stage == 2) {
															$status  = $payment_status == 1 ? "Pending" : "Rejected";
															$stage = "CO Finance";
														} else if ($payment_stage == 3) {
															$status  = "Pending";
															$stage = "Director Finance";
														}

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
														$filter_department = project_permission($project_department, $project_section, $payment_stage);

														if ($filter_department) {
															$counter++;
												?>
															<tr class="">
																<td><?= $counter ?></td>
																<td><?= $project_name ?></td>
																<td><?= number_format($amount_paid, 2) ?></td>
																<td><?= date("Y-m-d", strtotime($payment_requested_date)) ?></td>
																<td><?= date("Y-m-d", strtotime($payment_due_date)) ?></td>
																<td><?= $stage ?></td>
																<td><?= $status ?></td>
																<td>
																	<!-- Single button -->
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			<?php
																			if ($payment_status == 1 && $payment_stage < 3) {
																			?>
																				<li>
																					<a type="button" data-toggle="modal" id="approve_dataBtn" data-target="#approve_data" onclick="get_approval_details('<?= $request_id ?>', '<?= $payment_stage ?>')">
																						<i class="fa fa-info"></i> Approve
																					</a>
																				</li>
																			<?php
																			}
																			?>
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
								<div id="menu1" class="tab-pane">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i> Paid</h4>
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
												$query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid  FROM tbl_payments_request r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.request_id WHERE status = 3");
												$query_rsPayement_reuests->execute();
												$rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
												$total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
												if ($total_rsPayement_reuests > 0) {
													$counter = 0;
													do {
														$costline_id = $rows_rsPayement_reuests['id'];
														$request_id = $rows_rsPayement_reuests['request_id'];
														$projid = $rows_rsPayement_reuests['projid'];
														$date_requested = $rows_rsPayement_reuests['date_requested'];
														$payment_stage = $rows_rsPayement_reuests['stage'];
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
														$filter_department = project_permission($project_department, $project_section, $payment_stage);
														if ($filter_department) {
															$counter++;
												?>
															<tr class="">
																<td style="width:5%"><?= $counter ?></td>
																<td style="width:45%"><?= $project_name ?></td>
																<td style="width:10%"><?= number_format($amount_paid, 2) ?></td>
																<td style="width:10%"><?= date("Y-m-d", strtotime($date_requested)) ?></td>
																<td style="width:10%"><?= $officer ?></td>
																<td style="width:10%"><?= date("Y-m-d", strtotime($date_paid)) ?></td>
																<td style="width:10%">
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


	<!-- add item -->
	<div class="modal fade" id="approve_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Payment Request Details</span></h4>
				</div>
				<form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="col-md-12">
							<div id="approve_budgetline_data">

							</div>
							<br>
							<br>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									<i class="fa fa-comment" aria-hidden="true"></i> Approval Details
								</legend>
								<div class="col-md-6" id="purpose_div">
									<label for="outcomeIndicator" class="control-label">Required Action *:</label>
									<div class="form-line">
										<select name="status" id="purpose" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
											<option value="">.... Select from list ....</option>
											<option value="1">Approve</option>
											<option value="2">Reject</option>
										</select>
									</div>
								</div>
								<div class="col-md-6" id="amount">
									<label for="amount" class="control-label">Requested Amount *:</label>
									<div class="form-line">
										<input type="hidden" name="h_requested_amount" class="form-control" id="h_requested_amount" readonly>
										<input type="text" name="requested_amount" class="form-control" id="requested_amount" readonly>
									</div>
								</div>
								<br>
								<br>
								<br>
								<br>
								<div class="col-md-12" id="projfinancier">
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
											<tbody id="financier_table_body">
												<tr></tr>
												<tr id="removeTr">
													<td colspan="7">Add Financiers</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
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
						<!-- /modal-body -->
					</div> <!-- /modal-content -->
					<div class="modal-footer">
						<div class="col-md-12 text-center">
							<input type="hidden" name="stage" id="stage" value="">
							<input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
							<input type="hidden" name="request_id" id="request_id" value="">
							<input type="hidden" name="approve" id="approve" value="new">
							<button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">Save</button>
							<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						</div>
					</div> <!-- /modal-footer -->
				</form>
			</div> <!-- /modal-dailog -->
		</div>
	</div>
	<!-- End add item -->
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
<script src="assets/custom js/fetch-monitoring-evaluation.js"></script>
<script>
	$(document).ready(function() {
		//filter the output cannot be selected twice
		$(document).on("change", ".selectedfinance", function(e) {
			var tralse = true;
			var selectImpact_arr = [];
			var attrb = $(this).attr("id");
			var rowno = $(this).attr("data-id");
			var selectedid = "#" + attrb;
			var selectedText = $(selectedid + " option:selected").html();
			var handler = true;

			var finance_input = $("input[name='financierId[]']").length;

			if (finance_input > 0) {
				handler = confirm("Are you sure you want to change");
				if (handler) {
					$(".selectedfinance").each(function(k, v) {
						var getVal = $(v).val();
						if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
							tralse = false;
							alert("You canot select Financier " + selectedText + " more than once ");
							var rw = $(v).attr("data-id");
							var amountfundingrow = "#amountfundingrow" + rw;
							var ceilingvalrow = "#ceilingvalrow" + rw;
							$(v).val("");
							$(amountfundingrow).val("");
							$(ceilingvalrow).val("");
							return false;
						} else {
							selectImpact_arr.push($(v).val());
						}
					});
				} else {
					var financier = $("#financierIdfinancierrow" + rowno).val();
					var ceilingval = $("#hceilingvalrow" + rowno).val();
					$("#financerow" + rowno).val(financier);
					$("#ceilingvalrow" + rowno).val(ceilingval);
					$("#financierCeilingrow" + rowno).html(commaSeparateNumber(ceilingval));
				}
			} else {
				$(".selectedfinance").each(function(k, v) {
					var getVal = $(v).val();
					if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
						tralse = false;
						alert("You canot select Financier " + selectedText + " more than once ");
						var rw = $(v).attr("data-id");
						var amountfundingrow = "#amountfundingrow" + rw;
						var ceilingvalrow = "#ceilingvalrow" + rw;
						$(v).val("");
						$(amountfundingrow).val("");
						$(ceilingvalrow).val("");
						return false;
					} else {
						selectImpact_arr.push($(v).val());
					}
				});
			}

			if (!tralse) {
				return false;
			}
		});

		$("#purpose").change(function(e) {
			e.preventDefault();
			var purpose = $(this).val();
			var stage = $("#stage").val();
			if (purpose == "" || purpose == 2) {
				$("#projfinancier").hide();
			} else {
				if (stage == "1") {
					$("#projfinancier").show();
				} else {
					$("#projfinancier").hide();
				}
			}
			$(`#financier_table_body`).html(`<tr></tr><tr id="removeTr"><td colspan="7">Add Financiers</td></tr>`);
		});

		$("#modal_form_submit").submit(function(e) {
			e.preventDefault();
			var confirmation = validate_amounts();
			var purpose = $("#purpose").val();
			var msg = (purpose == "1") ? "Approve" : "Rejected";
			if (confirmation) {
				var form_details = $(this).serialize();
				$.ajax({
					type: "post",
					url: "ajax/payments/index",
					data: form_details,
					dataType: "json",
					success: function(response) {
						if (response.success) {
							sweet_alert_success(`Successfully ${msg}`);
							setTimeout(() => {
								window.location.reload(true)
							}, 100);
						} else {
							sweet_alert("Error occured kindly repeat the process")
						}
					}
				});
			} else {
				sweet_alert("Kindly make sure that the financier is selected and the contribution equals to requested amount")
			}
		});
	});

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

	function sweet_alert_success(msg) {
		return swal({
			title: "Success",
			text: msg,
			type: "Success",
			icon: 'success',
			dangerMode: true,
			timer: 15000,
			showConfirmButton: false
		});
		setTimeout(function() {}, 15000);
	}

	function validate_amounts() {
		var msg = false;
		var purpose = $("#purpose").val();
		var stage = $("#stage").val();
		if (purpose == "1") {
			if (stage == "1") {
				var request_amount = $("#h_requested_amount").val();
				request_amount = parseFloat(request_amount);
				var financier_contribution = calculate_financiers_contributions();
				if (request_amount == financier_contribution) {
					msg = true;
				}
			} else {
				msg = true;
			}
		} else {
			msg = true;
		}
		return msg;
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
						sweet_alert("No data found !!!")
					}
				}
			});
		}
	}

	//function to put commas to the data
	function commaSeparateNumber(val) {
		while (/(\d+)(\d{3})/.test(val.toString())) {
			val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
		}
		return val;
	}

	function get_approval_details(request_id, stage) {
		$("#projfinancier").hide();
		$("#stage").val(stage);
		$("#request_id").val(request_id);

		if (stage == "2") {
			$("#purpose").removeAttr("required");
			$("#purpose").val(1);
			$("#purpose").attr("disabled", "disabled");
		} else {
			$("#purpose").attr("required", "required")
			$("#readonly").removeAttr("readonly")
			$("#purpose").val("");
		}

		if (request_id != "" && stage != "") {
			$.ajax({
				type: "get",
				url: "ajax/payments/index",
				data: {
					approval_details: "approval_details",
					request_id: request_id,
					stage: stage
				},
				dataType: "json",
				success: function(response) { 
					if (response.success) {
						$("#approve_budgetline_data").html(response.data);
						$("#h_requested_amount").val(response.budget);
						$("#requested_amount").val(commaSeparateNumber(response.budget));
					} else {
						sweet_alert("No data found!");
					}
				}
			});
		} else {
			sweet_alert("Error !!! Data could not be found");
		}
	}

	function financier_funding(rowno) {
		var request_amount = $("#h_requested_amount").val();
		var financier_ceiling_amount = $(`#ceilingval${rowno}`).val();
		var amount_funding = $(`#amountfunding${rowno}`).val();

		if (request_amount != "" && financier_ceiling_amount != "") {
			request_amount = parseFloat(request_amount);
			financier_ceiling_amount = parseFloat(financier_ceiling_amount);
			var financier_contribution = calculate_financiers_contributions();

			if (request_amount < financier_contribution) {
				sweet_alert("Make sure the indicated financier contribution is equal to requested amount");
				setTimeout(() => {
					$(`#amountfunding${rowno}`).val("");
				}, 3000);
			}

			var balanace = financier_ceiling_amount - financier_contribution;
			if (balanace < 0) {
				setTimeout(() => {
					$(`#amountfunding${rowno}`).val("");
				}, 3000);
				sweet_alert("Please note that the financiers balance is less than the requested amount");
			}

			var financier_contribution = calculate_financiers_contributions();
			var balance = financier_ceiling_amount - financier_contribution;
			var financier_ceiling_amount = $(`#financierCeiling${rowno}`).html(commaSeparateNumber(balance));
		} else {
			sweet_alert("Sorry an error occurred")
		}
	}

	function calculate_financiers_contributions() {
		var financierTotal = 0;
		$(".financierTotal").each(function() {
			if ($(this).val() != "") {
				financierTotal = financierTotal + parseFloat($(this).val());
			}
		});

		return financierTotal;
	}


	// function to add new rowfor financiers
	$rowno = $("#financier_table_body tr").length;

	function add_row_financier() {
		$("#removeTr").remove(); //new change
		$rowno = $rowno + 1;
		$("#financier_table_body tr:last").after(
			`<tr id="financierrow${$rowno}">
				<td></td>
				<td colspan="3">
					<select onchange=get_financier_funds("row${$rowno}") name="financiers[]" id="financerow${$rowno}" class="form-control validoutcome selectedfinance" required="required">
						<option value="">Select Financier from list</option>
					</select>
				</td>
				<td>
					<input type="hidden" name="ceilingval[]"  id="ceilingvalrow${$rowno}" /><span id="currrow${$rowno}"></span>
					<span id="financierCeilingrow${$rowno}" style="color:red"></span> 
				</td>
				<td>
					<input type="number" name="amountfunding[]" onkeyup=financier_funding("row${$rowno}") onchange=financier_funding("row${$rowno}")  id="amountfundingrow${$rowno}"  placeholder="Enter amount"  class="form-control financierTotal" required/>
				</td>
				<td>
					<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financierrow${$rowno}")>
						<span class="glyphicon glyphicon-minus"></span>
					</button>
				</td>
		</tr>`);
		get_financiers($rowno);
		numbering_financier();
	}

	// function to delete financiers row
	function delete_row_financier(rowno) {
		$("#" + rowno).remove();
		numbering_financier();
		$check = $("#financier_table_body tr").length;
		if ($check == 1) {
			$("#financier_table_body").html(
				`<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`
			);
		}
	}

	// auto numbering table rows on delete and add new for financier table
	function numbering_financier() {
		$("#financier_table_body tr").each(function(idx) {
			$(this)
				.children()
				.first()
				.html(idx - 1 + 1);
		});
	}

	//get financiers
	function get_financiers(rowno) {
		var projid = $("#projid").val();
		var financier = "#financerow" + rowno;
		var request_id = $("#request_id").val();
		$.ajax({
			type: "get",
			url: "ajax/payments/index",
			data: {
				get_financier: "get_financier",
				request_id: request_id,
			},
			dataType: "json",
			success: function(response) {
				if (response.success) {
					$(financier).html(response.financiers);
				}
			},
		});
	}

	function get_financier_funds(rowno) {
		var financier = $(`#finance${rowno}`).val();
		var request_id = $("#request_id").val();
		if (request_id != "") {
			$.ajax({
				type: "get",
				url: "ajax/payments/index",
				data: {
					financier_balance: "financier_balance",
					request_id: request_id,
					financier: financier,
				},
				dataType: "json",
				success: function(response) {
					if ($(`#finance${rowno}`).val() != "") {
						if (response.success) {
							$(`#ceilingval${rowno}`).val(response.balance);
							$(`#financierCeiling${rowno}`).html(commaSeparateNumber(response.balance));
						}
					}
				},
			});
		}
	}
</script>