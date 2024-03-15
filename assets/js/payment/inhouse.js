const ajax_url = 'ajax/payments/index';


$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var cost_type = $("#purpose1").val();
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

    $("#modal_form_submit1").submit(function (e) {
        e.preventDefault();
        var cost_type = $("#purpose1").val();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {

                console.log(response);
                if (response.success) {
                    success_alert("Request created successfully");
                } else {
                    error_alert("Request error !!");
                }
                // $(".modal").each(function () {
                //     $(this).modal("hide");
                //     $(this)
                //         .find("form")
                //         .trigger("reset");
                // });

                // setTimeout(() => {
                //     if (cost_type == '1') {
                //         window.location.reload();
                //     } else {
                //         window.location.href = response.items_url;
                //     }
                // }, 3000);
            }
        });
    });

    // check if the input has already been selected
    $(document).on("change", ".description", function (e) {
        var tralse = true;
        var select_funding_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();

        $(".description").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
                tralse = false;
                swal("Warning", "You canot select budgetline " + selectedText + " more than once ", "warning");
                var rw = $(v).attr("data-id");
                var target = rw;
                $(v).val("");
                return false;
            } else {
                select_funding_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
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



function add_request_details(projid, implementation_type) {
    $("#project_id").val(projid);
    $("#_budget_lines_values_table").html(`<tr></tr><tr id="e_removeTr" class="text-center"><td colspan="5">Add Budgetline Costlines</td></tr>`);
    $("#sub_total_amount").val(0);
    $("#subtotal_percentage").val(0);
    $("#due_date").val("");
    $("#purpose1").val("");
    $("#implementation_type").val(implementation_type);
    $("#comments").val("");
    check_cost_type();
    $("#purpose1").removeAttr('readonly');

    if (implementation_type == '2') {
        $("#purpose1").val(2);
        $("#purpose1").attr('readonly', 'readonly');
    }
}

function check_cost_type() {
    var purpose = $("#purpose1").val();
    var implementation_type = $("#implementation_type").val();
    $("#project_commets_div").hide();
    $("#budgetline_div").hide();
    $("#project_commets_div").hide();
    if (implementation_type == '1') {
        $("#purpose1").removeAttr('readonly');
    } else {
        $("#project_commets_div").show();
        $("#budgetline_div").show();
        $("#project_commets_div").show();
    }

    if (purpose == '2') {
        $("#project_commets_div").show();
        $("#budgetline_div").show();
        $("#project_commets_div").show();
    }
}

// function to add new rowfor financiers
function add_budget_costline() {
    var stage = $("#stage").val();
    var purpose = $("#purpose1").val();
    var result = false;
    var task = '';
    result = false;
    var msg = "Please select a purpose first";
    if (purpose != '') {
        result = true;
        if (stage == '10') {
            if (purpose == 1) {
                task = $("#tasks").val();
                result = task != '' ? true : false;
                msg = task == '' ? "Please select a task first" : '';
            }
        }
    }

    if (result) {
        $(`#e_removeTr`).remove(); //new change
        $rowno = $(`#_budget_lines_values_table tr`).length;
        $rowno += 1;
        $rowno = $rowno;
        $(`#_budget_lines_values_table tr:last`).after(`
        <tr id="budget_line_cost_line${$rowno}">
            <td>
                <select name="direct_cost_id[]" id="description${$rowno}" data-id="${$rowno}" onchange="get_costline_details(${$rowno})" class="form-control show-tick description" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                    <option value="">.... Select from list ....</option>
                </select>
            </td>
            <td id="unit${$rowno}"> </td>
            <td>
                <input type="number" name="unit_cost[]" min="0" class="form-control " id="unit_cost${$rowno}" onchange="calculate_total_cost(${$rowno})" onkeyup="calculate_total_cost(${$rowno})">
            </td>
            <td>
                <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost(${$rowno})" onkeyup="calculate_total_cost(${$rowno})" id="no_units${$rowno}">
            </td>
                <td>
                <input type="hidden" name="h_no_units[]" id="h_no_units${$rowno}">
                <input type="text" name="p_no_units[]" min="0" class="form-control " id="p_no_units${$rowno}" readonly>
            </td>
            <td>
                    <input type="hidden" name="subtotal_amount[]" id="subtotal_amount${$rowno}" class="subamount sub" value="">
                    <span id="subtotal_cost${$rowno}" style="color:red"></span>
            </td>
            <td style="width:2%">
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${$rowno}', '')">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
        get_budgetline_details($rowno, purpose);
    } else {
        error_alert(msg);
    }
}

// function to delete financiers row
function delete_budget_costline(rowno) {
    $("#" + rowno).remove();
    var check = $(`#_budget_lines_values_table tr`).length;
    if (check == 1) {
        $(`#_budget_lines_values_table`).html(`
        <tr></tr>
            <tr id="e_removeTr" class="text-center">
            <td colspan="7">Add Budgetline Costlines</td>
        </tr>`);
    }
    calculate_subtotal();
}

function get_budgetline_details(rowno, task_id, cost_type) {
    var projid = $("#project_id").val();
    var output_id = $("#output").val();
    var site_id = $("#site_id").val();
    var task_id = $("#tasks").val();
    var stage = $("#stage").val();
    var cost_type = $("#implementation_type").val();
    var purpose = $("#purpose1").val();

    if (projid != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_budgetline_info: "get_budgetline_info",
                projid: projid,
                output_id: output_id,
                site_id: site_id,
                task_id: task_id,
                stage: stage,
                purpose: purpose,
                cost_type: cost_type,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#description${rowno}`).html(response.description);
                } else {
                    console.log("Error please ensure that you use the correct project")
                }
            }
        });
    } else {
        console.log("Ensure you have the correct project");
    }
}

function calculate_subtotal() {
    var ceiling_amount = $("#ceiling_amount").val();
    var total_amount = 0;
    $(`.sub`).each(function () {
        if ($(this).val() != "") {
            total_amount = total_amount + parseFloat($(this).val());
        }
    });
    var percentage = ((total_amount / ceiling_amount) * 100);
    $(`#sub_total_amount`).val(commaSeparateNumber(total_amount));
    $(`#subtotal_percentage`).val(percentage);
}

function calculate_total_cost(rowno) {
    var unit_cost = $(`#unit_cost${rowno}`).val();
    var no_units = $(`#no_units${rowno}`).val();
    var h_no_units = $(`#h_no_units${rowno}`).val();
    var ceiling_amount = $("#ceiling_amount").val();

    unit_cost = (unit_cost != "") ? parseFloat(unit_cost) : 0;
    no_units = (no_units != "") ? parseFloat(no_units) : 0;
    h_no_units = (h_no_units != "") ? parseFloat(h_no_units) : 0;
    var total = 0;
    if (h_no_units >= no_units && no_units > 0) {
        if (ceiling_amount != "") {
            ceiling_amount = parseFloat(ceiling_amount);
            total = no_units * unit_cost;

            $(`#subtotal_amount${rowno}`).val(total);
            var total_amount = 0;
            $(`.subamount`).each(function () {
                if ($(this).val() != "") {
                    total_amount = total_amount + parseFloat($(this).val());
                }
            });
            var remainder = ceiling_amount - total_amount;
            if (remainder < 0) {
                $(`#no_units${rowno}`).val("");
                total = 0;
            }
        } else {
            error_alert("Error check the data");
            $(`#no_units${rowno}`).val("");
        }
    } else {
        error_alert("Sorry ensure that the number of units is less or equal to the number of units specified");
        $(`#no_units${rowno}`).val("");
    }
    $(`#subtotal_amount${rowno}`).val(total);
    $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(total));
    calculate_subtotal(rowno);
}

function get_costline_details(rowno) {
    var description = $(`#description${rowno}`).val();
    var projid = $("#project_id").val();
    if (description != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_costline_details: "get_costline_details",
                projid: projid,
                direct_cost_id: description,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var description = $(`#description${rowno}`).val();
                    if (description != "") {
                        var budgetline_details = response.budgetline_details;
                        var unit_cost = budgetline_details.unit_cost;
                        var unit = budgetline_details.unit_of_measure;
                        $(`#p_no_units${rowno}`).val(response.remaining_units);
                        $(`#h_no_units${rowno}`).val(response.remaining_units);
                        $(`#unit_cost${rowno}`).val(unit_cost);
                        $(`#h_unit_cost${rowno}`).val(unit_cost);
                        $(`#unit${rowno}`).html(unit);
                        $(`#subtotal_amount${rowno}`).val("");
                        $(`#subtotal_cost${rowno}`).html("");
                    } else {
                        console.log("data could not be found")
                        $(`#unit_cost${rowno}`).val("");
                        $(`#no_units${rowno}`).val("");
                        $(`#subtotal_amount${rowno}`).val("");
                        $(`#subtotal_cost${rowno}`).val("");
                    }
                } else {
                    console.log("data could not be found");
                    $(`#unit_cost${rowno}`).val("");
                    $(`#no_units${rowno}`).val("");
                    $(`#subtotal_amount${rowno}`).val("");
                    $(`#subtotal_cost${rowno}`).val("");
                }
                calculate_subtotal();
            }
        });
    } else {
        console.log("Please select the description please");
    }
}