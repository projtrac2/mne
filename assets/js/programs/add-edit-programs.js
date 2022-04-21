const url = "ajax/programs/programs";

$(document).ready(function () {
    // check if the input has already been selected 
    $(document).on("change", ".selected_category", function (e) {
        var tralse = true;
        var select_funding_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        var program_type =
            $(".selected_category").each(function (k, v) {
                var getVal = $(v).val();
                if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
                    tralse = false;
                    swal("Warning", "You canot select category " + selectedText + " more than once ", "warning");
                    var rw = $(v).attr("data-id");
                    var amountfundingrow = "#amountfundingrow" + rw;
                    $(v).val("");
                    $(amountfundingrow).val("");
                    return false;
                } else {
                    select_funding_arr.push($(v).val());
                }
            });
        if (!tralse) {
            return false;
        }
    });

    // check if the input has already been selected 
    $(document).on("change", ".indicator", function (e) {
        var tralse = true;
        var select_funding_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        $(".indicator").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
                tralse = false;
                swal("Warning", "You canot select Indicator " + selectedText + " more than once ", "warning");
                var rw = $(v).attr("data-id");
                var target = rw;
                $(v).val("");
                $(target).attr('placeholder', "");
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


function get_department() {
    var projsectorid = $("#projsector").val();
    if (projsectorid) {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'getdept=' + projsectorid,
            success: function (html) {
                $('#projdept').html(html);
                $(".indicator").html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
            }
        });
    } else {
        $('#projdept').html('<option value="">... Select <?= $ministrylabel ?> First ... </option>');
        $(".indicator").html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
    }
}

function hide_workplan(param) {
    if (param) {
        $("#program_workplan_div").show();
    } else {
        $("#program_workplan_div").hide();
        $("#phead").html("");
        $("#program_workplan_body").html("");
    }
}

function hide_strategicplan(param) {
    if (param) {
        $("#strategicplan_div").show();
        get_strategicplan();
        get_program_years(1);
    } else {
        $("#strategicplan_div").hide();
        $("#strategicplan_div").html("");
        get_program_years(0);
    }
}

function get_strategicplan() {
    $.ajax({
        type: "POST",
        url: url,
        data: "get_strategicplan",
        dataType: "html",
        success: function (response) {
            $("#strategicplan_div").html(response);
        }
    });
}

function get_program_years(param) {
    $.ajax({
        type: "POST",
        url: url,
        data: { indipendent: param, get_program_years: "get_program_years" },
        dataType: "html",
        success: function (response) {
            $("#program_year_div").html(response);
        }
    });
}

function get_strategy() {
    var objid = $("#ind_strategic_objective").val();
    $.ajax({
        type: "POST",
        url: url,
        data: { objid: objid, get_strategy: "get_strategy" },
        dataType: "html",
        success: function (response) {
            $("#progstrategy").html(response);
        }
    });
}

function program_workplan_header() {
    var program_duration = $("#program_duration").val();
    var program_starting_year = $("#starting_year").val();
    var strategic_plan_end_year = $("#stratplanendyear").val();
    var program_type = $("#program_type").val();

    if (program_starting_year != "" && program_duration != "" && strategic_plan_end_year != "") {
        program_starting_year = parseInt(program_starting_year);
        program_duration = parseInt(program_duration);
        var program_end_year = program_starting_year + program_duration - 1;
        var link_objective = $('input[name="progstrategyobjective"]:checked').val();
        // program type
        // type 1 = Strategic plan programs
        // type 0 = Indipendent programs
        hide_workplan(0);
        if (program_duration > 0) {
            if (program_type == 1) {
                if (program_end_year <= strategic_plan_end_year) {
                    var containerH = `<tr> 
                        <th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                    var content = `<tr>`;
                    for (var i = 0; i < program_duration; i++) {
                        var finyear = program_starting_year + 1;
                        containerH += `<th colspan="3">${program_starting_year}/${finyear}</th><input type="hidden" id="output_year${i}" name="progyear[]" value="${program_starting_year}" />`;
                        content += `<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Target Ceiling&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                        program_starting_year++;
                    }
                    containerH +=
                        `<th rowspan="2">
                            <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_strategic_workplan();" class="btn btn-success btn-sm">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </th> </tr>`;
                    content += `<tr>`;
                    containerH += content;
                    hide_workplan(1);
                    $("#phead").append(containerH);
                } else {
                    hide_workplan(0);
                    $("#program_duration").val("");
                }
            } else {
                if (link_objective == 1) {
                    if (program_end_year <= strategic_plan_end_year) {
                        var containerH = `<tr>
                        <th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                        var content = `<tr>`;
                        for (var i = 0; i < program_duration; i++) {
                            var finyear = program_starting_year + 1;
                            containerH += `<th colspan="2">${program_starting_year}/${finyear}</th><input type="hidden" id="output_year${i}" name="progyear[]" value="${program_starting_year}" />`;
                            content += `<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                            program_starting_year++;
                        }
                        containerH +=
                            `<th rowspan="2">
                            <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan();" class="btn btn-success btn-sm">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </th> </tr>`;
                        content += `<tr>`;
                        containerH += content;
                        hide_workplan(1);
                        $("#phead").append(containerH);
                    } else {
                        hide_workplan(0);
                        $("#program_duration").val("");
                    }
                } else {
                    var containerH = `<tr> 
                    <th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                    var content = `<tr>`;
                    for (var i = 0; i < program_duration; i++) {
                        var finyear = program_starting_year + 1;
                        containerH += `<th colspan="2">${program_starting_year}/${finyear}</th><input type="hidden" id="output_year${i}" name="progyear[]" value="${program_starting_year}" />`;
                        content += `<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                        program_starting_year++;
                    }
                    containerH +=
                        `<th rowspan="2">
                        <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan();" class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </th> </tr>`;
                    content += `<tr>`;
                    containerH += content;
                    hide_workplan(1);
                    $("#phead").append(containerH);
                }
            }
        } else {
            hide_workplan(0);
            $("#program_duration").val("");
        }
    } else {
        hide_workplan(0);
    }
}

$rowno = $("#program_workplan_body tr").length;
function add_program_strategic_workplan() {
    var years = $("#program_duration").val();
    var projdept = $("#projdept").val();
    if (projdept != "") {
        var containerB =
            `<tr class="" id="row${$rowno}">
            <td> 
                <input  type="text" name="output[]" id="output${$rowno}"  class="form-control" required> 
            </td>
            <td>
                <select name="indicator[]" id="indicatorrow${$rowno}" onchange="get_indicator_details('row${$rowno}')" class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">... Select Division first ...</option>
                </select>
            </td> `;
        for (var i = 0; i < years; i++) {
            containerB +=
                `<td>
                <input name="targetrow${$rowno}[]" data-id="indicator_targetsrow${$rowno}${i}"  id="targetrow${$rowno}" class="form-control  target targetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>
            <td>
                <span id="indicator_targetsrow${$rowno}${i}" class="text-danger s_target"></span>
                <input type="hidden" name="hidden_indicator_targetsrow${$rowno}${i}" class="s_target_val" id="hidden_indicator_targetsrow${$rowno}${i}" value="">
            </td>
            <td>
                <input name="budgetrow${$rowno}[]" id="budgetrow${$rowno}" onchange="sum_budget()" onkeyup="sum_budget()" class="form-control currency budgetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>`;
        }
        containerB += `
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`;
        var sourceid = 'source' + $rowno;
        $rowno = $rowno + 1;
        $("#program tr:last").after(containerB);
        get_indicator($rowno);
    } else {
        swal("Warning", "Select Division First!", "warning");
    }
}


$rowno = $("#program_workplan_body tr").length;
function add_program_workplan() {
    var years = $("#program_duration").val();
    var projdept = $("#projdept").val();
    if (projdept != "") {
        var containerB =
            `<tr class="" id="row${$rowno}">
            <td> 
                <input  type="text" name="output[]" id="output${$rowno}"  class="form-control" required> 
            </td>
            <td>
                <select name="indicator[]" id="indicatorrow${$rowno}" onchange="get_indicator_details('row${$rowno}')" class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">... Select Division first ...</option>
                </select>
            </td> `;
        for (var i = 0; i < years; i++) {
            containerB +=
                `<td>
                <input name="targetrow${$rowno}[]" id="targetrow${$rowno}" class="form-control targetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>
            <td>
                <input name="budgetrow${$rowno}[]" id="budgetrow${$rowno}" onchange="sum_budget()" onkeyup="sum_budget()" class="form-control currency budgetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>`;
        }

        containerB += `
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`;
        var sourceid = 'source' + $rowno;
        $rowno = $rowno + 1;
        $("#program tr:last").after(containerB);
        get_indicator($rowno);
    } else {
        swal("Warning", "Select Division First!", "warning");
    }
}

function delete_program_row(rowno) {
    $("#" + rowno).remove();
}

function get_indicator(rowno = null) {
    var projdeptid = $("#projdept").val();
    var indicator = "#indicatorrow" + (rowno - 1);
    if (projdeptid) {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'getprogindicator=' + projdeptid,
            success: function (html) {
                if (rowno != null) {
                    $(indicator).html(html);
                } else {
                    $(".indicator").html(html);
                }
            }
        });
    } else {
        $(".indicator").html('<option value="">... Select  Division First ... </option>');
    }
}

function get_indicator_details(rowno) {
    var rw = rowno;
    var output = 'output' + rw;
    var indicator = 'indicator' + rw;
    var target = '.target' + rw;
    var budget = '.budget' + rw;
    var program_type = $("#program_type").val();
    var years = $("#program_duration").val();
    var program_starting_year = $("#starting_year").val();
    var indicatorVal = $("#" + indicator).val();
    if (indicatorVal != "") {
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                getUnits: indicatorVal,
                program_type: program_type,
                years: years,
                program_starting_year: program_starting_year,
            },
            dataType: "json",
            success: function (html) {
                var indicatorVal = $("#" + indicator).val();
                if (indicatorVal != "") {
                    $(target).attr('placeholder', html.unit);
                    var targets = 'target' + indicatorVal + '[]';
                    var budgets = 'budget' + indicatorVal + '[]';
                    $(target).attr('name', targets);
                    $(budget).attr('name', budgets);
                    if (program_type == 1) {
                        for (i = 0; i < years; i++) {
                            $("#indicator_targets" + rowno + i).html(html.targets[i]);
                            $("#hidden_indicator_targets" + rowno + i).val(html.targets[i]);
                        }
                    }
                }
            }
        });
    }
}

function validate_targets(rowno, year_rowno) {
    var target = parseFloat($("#target" + rowno).val());
    var s_target = parseFloat($("#hidden_indicator_targets" + year_rowno).val());
    if (s_target > 0) {
        var remaining = s_target - target;
        if (remaining >= 0) {
            $("#indicator_targets" + year_rowno).html(remaining);
        } else {
            $("#target" + rowno).val("");
            $("#indicator_targets" + year_rowno).html(s_target);
        }
    }
}


$rowno = $("#financier_table_body tr").length;
function add_row_financier() {
    $("#removeTr").remove(); //new change
    $rowno = $rowno + 1;
    $("#financier_table_body tr:last").after(`
        <tr id="financerow${$rowno}">
            <td> ${$rowno} </td>
            <td>
                <select  data-id="${$rowno}" name="source_category[]" id="source_categoryrow${$rowno}" class="form-control validoutcome selected_category" required="required">
                </select>
            </td>
            <td>
                <input type="number" name="amountfunding[]" id="amountfundingrow${$rowno}" onchange="calculate_budget(${$rowno})" onkeyup="calculate_budget(${$rowno})" placeholder="Enter amount in local currency"  class="form-control financierTotal" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financerow${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
    numbering();
    get_finacier($rowno)
}

// function to delete row 
function delete_row_financier(rowno) {
    $("#" + rowno).remove();
    numbering();
}

// auto numbering table rows on delete and add new for financier table
function numbering() {
    $("#financier_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

function get_finacier($rowno) {
    $.ajax({
        type: "post",
        url: url,
        data: "get_financier",
        dataType: "html",
        success: function (response) {
            $("#source_categoryrow" + $rowno).html(response);
        }
    });
}

function sum_budget() {
    var budget = 0;
    $(".currency").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal != "") {
            getVal = parseFloat(getVal);
            budget += getVal;
        }
    });
    $("#program_budget").val(budget);
    $("#program_bud").val(budget);
    $(".financierTotal").val("");
}
 
function program_balance(program_budget) {
    var financier_funds = 0;
    $(".financierTotal").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal != "") {
            getVal = parseFloat(getVal);
            financier_funds += getVal;
        }
    });

    var balance = program_budget - financier_funds;
    return balance;
}


function calculate_budget(param) {
    var program_budget = $("#program_budget").val();
    if (program_budget != "") {
        var balance = program_balance(program_budget);
        if (balance >= 0) {
            $("#program_bud").val(balance);
        } else {
            console.log("#amountfundingrow" + param);
            $("#amountfundingrow" + param).val("");
            var balance = program_balance(program_budget);
            $("#program_bud").val(balance);
        }
    } else {
        swal("Warning", "Add program output budgets first", "warning");
        $("#amountfundingrow" + param).val("");
    }
}

function validate_budget() {
    if (balance > 0) {
        swal("Warning", "You canot select category " + selectedText + " more than once ", "warning");
        return false;
    } else {
        return true;
    }
}