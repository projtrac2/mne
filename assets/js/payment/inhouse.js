const ajax_url = 'ajax/payments/index';


$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var purpose = $("#purpose").val();
        var msg = purpose != '' && purpose == '1' ? "Approved" : "Rejected";
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(`Successfully ${msg}`);
                } else {
                    success_alert("Error occured kindly repeat the process")
                }

                setTimeout(() => {
                    window.location.reload(true)
                }, 3000);
            }
        });
    });

    $("#modal_form_submit_disburse").submit(function (e) {
        e.preventDefault();
        var form = $(this)[0];
        var form_details = new FormData(form);
        var financier_contribution = calculate_financiers_contributions();
        var disburse_amount = $("#disburse_amount").val();
        if (financier_contribution == disburse_amount) {
            $.ajax({
                type: "post",
                url: "ajax/payments/index",
                data: form_details,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        success_alert("Congratulations you have disbursed the budgetline");
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 100);
                    } else {
                        alert("Sorry record could not be created");
                    }
                }
            });
        } else {
            error_alert("Please ensure the contribution by financiers is equal to request amount")
        }
    });
});

//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function get_approval_details(request_id, stage, amount_requested, requested_amount) {
    $("#stage_id").val(stage);
    $("#request_id").val(request_id);
    $("#h_requested_amount").val(amount_requested);
    $("#a_requested_amount").val(requested_amount);
    $("#status_id").attr("required", "required")
    $("#status_id").val("");
    if (stage == "2") {
        $("#purpose_div").hide("required");
        $("#status_id").removeAttr("required");
        $("#status_id").val(1);
        $("#status_id").attr("disabled", "disabled");
    }
}


const get_more_info = (request_id) => {
    $(".disbursed_div").hide();
    if (request_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_more_info: "get_more_info",
                request_id: request_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var stage = response.details.stage;
                    var status = response.details.status;
                    var purpose = response.details.purpose;
                    var request_amount = response.request_amount;

                    var payment_stage = '';
                    if (stage == 1) {
                        payment_stage = 'CO Department';
                    } else if (stage == 2) {
                        payment_stage = 'CO Finance';
                    } else if (stage == 3) {
                        payment_stage = 'Director Finance';
                    }

                    var payment_status = '';
                    if (status == 1) {
                        payment_status = 'Pending';
                    } else if (status == 2) {
                        payment_status = 'Canceled';
                    } else if (status == 3) {
                        $(".disbursed_div").show();
                        payment_status = 'Disbursed';
                        $("#payment_mode").html(response.disbursement.payment_mode);
                        $("#receipt_no").html(response.disbursement.receipt_no);
                        $("#receipt").attr("href", response.disbursement.receipt);
                        $("#date_paid").html(response.disbursement.date_paid);
                        $("#financier_table_body").html(response.financiers);
                    }

                    var payment_purpose = '';
                    if (purpose == 1) {
                        payment_purpose = 'Direct Cost';
                    } else if (purpose == 2) {
                        payment_purpose = 'Administrative Cost';
                    } else if (purpose == 3) {
                        payment_purpose = 'Mapping Cost';
                    } else if (purpose == 4 || purpose == 5) {
                        payment_purpose = 'Evaluation Cost';
                    }
                    var fullname = response.details.ttitle + ' ' + response.details.fullname;

                    //

                    $("#comments_div").html(response.comments);
                    $("#project_name").html(response.project_details.projname);
                    $("#project_code").html(response.project_details.projcode);
                    $("#requested_amount").html(response.request_amount);
                    $("#requested_by").html(fullname);
                    $("#date_requested").html(response.details.date_requested);
                    $("#due_date").html(response.details.due_date);
                    $("#stage").html(payment_stage);
                    $("#status").html(payment_status);
                    $("#purpose").html(payment_purpose);

                    if (purpose == '1') {
                        $("#milestone_table").html(response.details.milestones);
                        $("#request_amount").val(response.details.request_amount);
                        $("#request_percentage").val(response.details.request_percentage);
                        $("#requested_amount").val(response.details.request_amount);
                        $("#payment_phase").html(response.details.payment_phases);
                    } else {
                        $("#tasks_table").html(response.data);
                        $("#requested_amount").val(response.details.amount_requested);
                        $("#subtotal").html(request_amount);
                    }
                } else {
                    success_alert("No data found !!!")
                }
            }
        });
    } else {
        success_alert("Ensure that the request is correct");
    }
}

const disburse_funds = (projid, request_id, amount_requested, amount_disbursed) => {
    $("#request_id").val(request_id);
    $("#amount_requested").val(amount_disbursed);
    $("#disburse_amount").val(amount_requested);
    $("#projid").val(projid);

    add_row_financier();
}

function financier_funding(rowno) {
    var request_amount = $("#disburse_amount").val();
    var financier_ceiling_amount = $(`#ceilingval${rowno}`).val();

    if (request_amount != "" && financier_ceiling_amount != "") {
        request_amount = parseFloat(request_amount);
        financier_ceiling_amount = parseFloat(financier_ceiling_amount);
        var financier_contribution = calculate_financiers_contributions();

        if (request_amount < financier_contribution) {
            error_alert("Make sure the indicated financier contribution is equal to requested amount");
            setTimeout(() => {
                $(`#amountfunding${rowno}`).val("");
                $(`#financierCeiling${rowno}`).html(commaSeparateNumber(financier_ceiling_amount));
            }, 1000);
            return;
        }

        var balanace = financier_ceiling_amount - financier_contribution;
        if (balanace < 0) {
            setTimeout(() => {
                $(`#amountfunding${rowno}`).val("");
                $(`#financierCeiling${rowno}`).html(commaSeparateNumber(financier_ceiling_amount));
            }, 1000);
            error_alert("Please note that the financiers balance is less than the requested amount");
            return;
        }

        var financier_contribution = calculate_financiers_contributions();
        var balance = financier_ceiling_amount - financier_contribution;
        var financier_ceiling_amount = $(`#financierCeiling${rowno}`).html(commaSeparateNumber(balance));
    } else {
        error_alert("Sorry an error occurred");
        $(`#financierCeiling${rowno}`).html(commaSeparateNumber(financier_ceiling_amount));
    }
}

function calculate_financiers_contributions() {
    var financierTotal = 0;
    $(".financierTotal").each(function () {
        if ($(this).val() != "") {
            financierTotal = financierTotal + parseFloat($(this).val());
        }
    });

    return financierTotal;
}


// function to add new rowfor financiers
function add_row_financier() {
    $("#removeTr1").remove(); //new change
    var $rowno = $("#financier_table_body_d tr").length;
    $rowno = $rowno + 1;
    var row = Math.floor(Math.random());
    $rowno = $rowno + row;

    $("#financier_table_body_d tr:last").after(
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
    $check = $("#financier_table_body_d tr").length;
    if ($check == 1) {
        $("#financier_table_body_d").html(
            `<tr></tr><tr id="removeTr1"><td colspan="5">Add Financiers</td></tr>`
        );
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_financier() {
    $("#financier_table_body_d tr").each(function (idx) {
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
    if (request_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_financier: "get_financier",
                request_id: request_id,
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(financier).html(response.financiers);
                } else {
                    error_alert('Error!! no record found');
                }
            },
        });
    } else {
        error_alert('Error!! ensure that you have the correct request parameters');
    }
}

function get_financier_funds(rowno) {
    var financier = $(`#finance${rowno}`).val();
    var request_id = $("#request_id").val();
    var projid = $("#projid").val();
    if (request_id != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                financier_balance: "financier_balance",
                request_id: request_id,
                financier: financier,
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if ($(`#finance${rowno}`).val() != "") {
                    if (response.success) {
                        $(`#ceilingval${rowno}`).val(response.balance);
                        $(`#financierCeiling${rowno}`).html(commaSeparateNumber(response.balance));
                    } else {
                        error_alert('Error!! no record found');
                    }
                }
            },
        });
    } else {
        error_alert('Error!! ensure that you have the correct request parameters');
    }
}