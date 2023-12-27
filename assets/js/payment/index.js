var ajax_url = "ajax/payments/index";
$(document).ready(function () {
    $(document).on("change", ".description", function (e) {
        var tralse = true;
        var selectImpact_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();

        $(".description").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
                tralse = false;
                error_alert("You canot select budgetline" + selectedText + " more than once ");
                var rowno = $(v).attr("data-id");
                empty_budget_line_values(rowno);
                $(v).val("");
                return false;
            } else {
                selectImpact_arr.push($(v).val());
            }
        });

        if (!tralse) {
            return false;
        }
    });

    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var total_amount = 0;
        $(`.subamount`).each(function () {
            if ($(this).val() != "") {
                total_amount = total_amount + parseFloat($(this).val());
            }
        });
        if (total_amount > 0) {
            var form_details = $(this).serialize();
            $.ajax({
                type: "post",
                url: ajax_url,
                data: form_details,
                dataType: "json",
                success: function (response) {
                    success_alert("Congratulations your requested has been submitted!");
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
                }
            });
        } else {
            console.log("This is not how we deal with things yawa")
        }
    });
    hide_div(false);
});

//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function hide_div(option) {
    if (option) {
        $("#purpose_div").show();
        $("#purpose").attr('required', 'required');
    } else {
        $("#purpose_div").hide();
        $("#tasks_div").hide();
        $("#purpose").removeAttr('required');
    }
}

function get_details(details) {
    var projid = details.projid;
    var stage = details.projstage;
    var request_id = details.request_id;
    var purpose = details.purpose;
    $("#projid").val(projid);
    $("#stage").val(stage);
    $("#request_id").val(request_id);
    $("#purpose").val(purpose);
    $("#store").val("new");

    // 1) Direct Cost
    // 2) Administrative cost
    // 3) Mapping
    // 4) Monitoring
    // 5) Evaluation

    var cost_type = '';
    if (stage == '8') {
        $("#project_purpose").val(3);
        cost_type = 3;
    } else if (stage == '9') {
        $("#project_purpose").val(4);
        cost_type = 4;
    } else if (stage == '10') {
        hide_div(true);
    }

    $("#cost_type").val(cost_type);
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

function get_personnel() {
    var projid = $("#projid").val();
    var stage = $("#stage").val();
    var purpose = $("#purpose").val();
    if (projid != "" && stage != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_personnel: "get_personnel",
                projid: projid,
                stage: stage,
                purpose: purpose,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#personnel").html(response.personnel);
                } else {
                    $("#personnel").html("");
                    console.log("Error please ensure that you use the correct project");
                }
            }
        });
    } else {
        console.log("Ensure you have the correct project");
    }
}

function get_tasks() {
    var projid = $("#projid").val();
    if (projid != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_tasks: "get_tasks",
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#tasks_div").show();
                    $("#tasks").attr('required', 'required');
                    $("#tasks").html(response.tasks);
                } else {
                    $("#tasks").html("");
                    $("#tasks_div").hide();
                    $("#tasks").removeAttr('required');
                    console.log("Error please ensure that you use the correct project");
                }
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}

// function to add new rowfor financiers
function add_budget_costline(starting_year) {
    var stage = $("#stage").val();
    var purpose = '';
    var result = false;
    var task = '';
    if (stage == '10') {
        purpose = $("#purpose").val();
        result = purpose != '' ? true : false;
        if (purpose == 1) {
            task = $("#task").val();
            result = task != '' ? true : false;
        }
    } else {
        purpose = $("project_purpose").val();
        result = purpose != '' ? true : false;
    }

    if (result) {
        $("#cost_type").val(purpose);
        $(`#${starting_year}_removeTr`).remove(); //new change
        $rowno = $(`#${starting_year}_budget_lines_values_table tr`).length;
        $rowno += 1;
        $rowno = $rowno + "" + starting_year;
        $(`#${starting_year}_budget_lines_values_table tr:last`).after(`
    <tr id="budget_line_cost_line${$rowno}">
        <td>
            <select name="description${starting_year}[]" id="description${$rowno}" data-id="${$rowno}" onchange="get_costline_details(${$rowno})" class="form-control show-tick description" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                <option value="">.... Select from list ....</option>
            </select>
        </td>
        <td id="unit${$rowno}"> </td>
        <td>
            <input type="number" name="unit_cost${starting_year}[]" min="0" class="form-control " id="unit_cost${$rowno}" onchange="calculate_total_cost(${$rowno}, ${starting_year})" onkeyup="calculate_total_cost(${$rowno}, ${starting_year})">
        </td>
        <td>
            <input type="number" name="no_units${starting_year}[]" min="0" class="form-control " onchange="calculate_total_cost(${$rowno}, ${starting_year})" onkeyup="calculate_total_cost(${$rowno}, ${starting_year})" id="no_units${$rowno}">
        </td>
            <td>
            <input type="hidden" name="h_no_units${starting_year}[]" id="h_no_units${$rowno}">
            <input type="text" name="p_no_units${starting_year}[]" min="0" class="form-control " id="p_no_units${$rowno}" readonly>
        </td>
        <td>
                <input type="hidden" name="subtotal_amount${starting_year}[]" id="subtotal_amount${$rowno}" class="subamount sub${starting_year}" value="">
                <span id="subtotal_cost${$rowno}" style="color:red"></span>
        </td>
        <td style="width:2%">
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${$rowno}', '${starting_year}')">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
        get_budgetline_details($rowno, starting_year, purpose);
    } else {
        error_alert('Please select a purpose first');
    }

}

// function to delete financiers row
function delete_budget_costline(rowno, starting_year) {
    $("#" + rowno).remove();
    var check = $(`#${starting_year}_budget_lines_values_table tr`).length;
    if (check == 1) {
        $(`#${starting_year}_budget_lines_values_table`).html(`
        <tr></tr>
            <tr id="${starting_year}_removeTr" class="text-center">
            <td colspan="7">Add Budgetline Costlines</td>
        </tr>`);
    }
    calculate_subtotal(starting_year);
}

function get_budgetline_details(rowno, task_id, cost_type) {
    var projid = $("#projid").val();
    var stage = $("#stage").val();
    if (projid != "") {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_budgetline_info: "get_budgetline_info",
                projid: projid,
                stage: stage,
                purpose: cost_type,
                cost_type: cost_type,
                task_id: task_id,
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
                description: description,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var description = $(`#description${rowno}`).val();
                    if (description != "") {
                        var budgetline_details = response.budgetline_details;
                        var unit_cost = budgetline_details.unit_cost;
                        var unit = budgetline_details.unit;

                        $(`#p_no_units${rowno}`).val(response.remaining_units);
                        $(`#h_no_units${rowno}`).val(response.remaining_units);
                        $(`#unit_cost${rowno}`).val(unit_cost);
                        $(`#h_unit_cost${rowno}`).val(unit_cost);
                        $(`#unit${rowno}`).html(unit);
                        $(`#subtotal_amount${rowno}`).val("");
                        $(`#subtotal_cost${rowno}`).html("");
                    } else {
                        console.log("data could not be found")
                    }
                } else {
                    console.log("data could not be found")
                }
            }
        });
    } else {
        console.log("Please select the description please");
    }
}

function calculate_total_cost(rowno, task_id) {
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
    calculate_subtotal(task_id);
}

function calculate_subtotal(task_id) {
    var ceiling_amount = $("#ceiling_amount").val();
    var total_amount = 0;
    $(`.sub${task_id}`).each(function () {
        if ($(this).val() != "") {
            total_amount = total_amount + parseFloat($(this).val());
        }
    });

    var percentage = ((total_amount / ceiling_amount) * 100);
    $(`#${task_id}_sub_total_amount`).val(commaSeparateNumber(total_amount));
    $(`#${task_id}_subtotal_percentage`).val(percentage);
}


