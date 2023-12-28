const ajax_url = "ajax/payments/contractor";

$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Approved payment successfully");
                } else {
                    error_alert("Approval error !!");
                }

                $(".modal").each(function () {
                    $(this).modal("hide");
                    $(this)
                        .find("form")
                        .trigger("reset");
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        });
    });

    $("#modal_submit_form").submit(function (e) {
        e.preventDefault();
        // $("#modal-form-submit").prop("disabled", true);]

        var form = $(this)[0];
        var form_details = new FormData(form);
        $.ajax({
            type: "post",
            url: ajax_url,
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
                    error_alert("Sorry record could not be created");
                }
            }
        });
    });
});

function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function set_details(details) {
    $("#project_name").val(details.project_name);
    $("#contractor_name").val(details.contractor_name);
    $("#contractor_number").val(details.contract_no);
    $("#request_id").val(details.request_id);
    $("#projid").val(details.projid);
    $("#payment_plan").val(details.payment_plan);
    $("#project_plan").val(details.project_plan);
    $("#stage").val(details.stage);
    $("#complete").val(details.complete);

    $("#milestones").hide();
    $("#tasks").hide();
    var payment_plan = details.payment_plan;

    if (payment_plan == "1") {
        $("#milestones").show();
    } else if (payment_plan == "2") {
        $("#tasks").show();
    } else {
        console.log("payment plan for work measured");
    }
}

function get_details(details, plan) {
    set_details(details);
    $("#project_approve_div").hide();
    $("#modal-form-submit").hide();
    if (plan == 2) {
        $("#project_approve_div").show();
        $("#modal-form-submit").show();
    }

    if (details.request_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_more_info: "get_more_info",
                request_id: details.request_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.details.success) {
                    $("#comments_div").html(response.comments);
                    $("#attachment_div").html(response.attachment);
                    if (details.payment_plan == '1') {
                        $("#milestones").show();
                        $("#milestone_table").html(response.details.milestones);
                        $("#request_amount").val(response.details.request_amount);
                        $("#request_percentage").val(response.details.request_percentage);
                        $("#requested_amount").val(response.details.request_amount);
                        $("#payment_phase").val(response.details.payment_phase);
                    } else {
                        $("#tasks").show();
                        $("#tasks_table").html(response.details.tasks);
                        $("#subtotal").html(response.details.task_amount);
                        $("#requested_amount").val(response.details.task_amount);
                    }
                } else {
                    error_alert("Could not find any record");
                }
            }
        });
    } else {
        error_alert("Ensure that the request is correct");
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
					<input type="number" name="amountfunding[]" onkeyup=financier_funding("row${$rowno}") onchange=financier_funding("row${$rowno}")  id="amountfundingrow${$rowno}"  placeholder="Enter amount" step="0.01"  class="form-control financierTotal" required/>
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
