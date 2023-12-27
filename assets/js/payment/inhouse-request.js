var ajax_url = "ajax/payments/index";
$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var cost_type = $("#purpose").val();
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
                    if (cost_type == 1) {
                        window.location.reload();
                    } else {
                        window.location.href = "inhouse-payment-requests.php";
                    }
                }, 3000);
            }
        });
    });
    $("#direct_cost").hide();

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
});


//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function get_tasks_budgetlines() {
    var tasks = $("#tasks").val();
    $("#budgetline_div").hide();

    if (tasks.length > 0) {
        $("#budgetline_div").show();
    }
}

const add_request_details = () => {
    var purpose = $("#purpose").val();
    if (purpose != '') {
        get_details();
        $("#output_div").hide();
        $("#site_div").hide();
        $("#tasks_div").hide();
        $("#output").removeAttr("required");
        $("#site_id").removeAttr("required");
        $("#tasks").removeAttr("required");
        $("#tasks").html("");
        $(".selectpicker").selectpicker("refresh");
        $("#store").val("new");
        $("#budgetline_div").show();
        $("#project_commets_div").show();
        $("#comment").attr("required", "required");
        $("#cost_type").val(purpose);
        if (purpose == '1') {
            $("#project_commets_div").hide();
            $("#budgetline_div").hide();
            $("#output_div").show();
            $("#output").attr('required', 'required');
            $("#comment").removeAttr('required');
        }

    } else {
        error_alert("Please select a purpose");
        setTimeout(() => {
            $(".modal").each(function () {
                $(this).modal("hide");
                $(this)
                    .find("form")
                    .trigger("reset");
            });
        }, 3000);
        $("#cost_type").val('');
    }
}

//
function get_sites() {
    var projid = $("#projid").val();
    var output_id = $("#output").val();
    $("#site_div").hide();
    $("#tasks_div").hide();
    $("#tasks").html("");
    $(".selectpicker").selectpicker("refresh");
    if (output_id != '') {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_sites: "get_sites",
                projid: projid,
                output_id: output_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var mapping_type = response.mapping_type;
                    if (mapping_type == 1 || mapping_type == 3) {
                        $("#site_div").show();
                        $("#site_id").html(response.sites);
                    } else {
                        $("#tasks_div").show();
                        $("#tasks").html(response.tasks);
                        $(".selectpicker").selectpicker("refresh");
                    }
                } else {
                    console.log("Error");
                }
            }
        });
    } else {
        console.log("Error, please select an output");
    }
}

function get_tasks() {
    var projid = $("#projid").val();
    var output_id = $("#output").val();
    var site_id = $("#site_id").val();
    $("#tasks_div").hide();
    if (site_id != '') {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_tasks: "get_tasks",
                projid: projid,
                output_id: output_id,
                site_id: site_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#tasks_div").show();
                    $("#tasks").html(response.tasks);
                    $(".selectpicker").selectpicker("refresh");
                } else {
                    console.log("Error");
                }
            }
        });
    } else {
        console.log("Error, please select an output");
    }
}

function get_details() {
    var projid = $("#projid").val();
    var stage = $("#stage").val();
    var request_id = $("#request_id").val();
    var purpose = $("#purpose").val();
    if (projid != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_details: "get_details",
                projid: projid,
                stage: stage,
                purpose: purpose,
                request_id: request_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#site_id").html(response.sites);
                    $("#personnel").html(response.personnel);
                    // edit_payment(response);
                } else {
                    error_alert("Error please ensure that you use the correct project");
                }
            }
        });
    }
}


// function to add new rowfor financiers
function add_budget_costline() {
    var stage = $("#stage").val();
    var purpose = $("#purpose").val();
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
        // $("#cost_type").val(purpose);
        $(`#_removeTr`).remove(); //new change
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
            <tr id="_removeTr" class="text-center">
            <td colspan="7">Add Budgetline Costlines</td>
        </tr>`);
    }
    calculate_subtotal();
}

function get_budgetline_details(rowno, task_id, cost_type) {
    var projid = $("#projid").val();
    var output_id = $("#output").val();
    var site_id = $("#site_id").val();
    var task_id = $("#tasks").val();
    var stage = $("#stage").val();
    var cost_type = $("#purpose").val();

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
                purpose: cost_type,
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


function get_costline_details(rowno) {
    var description = $(`#description${rowno}`).val();
    var projid = $("#projid").val();
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