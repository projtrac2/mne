const ajax_url = 'ajax/programs/index';

$(document).ready(function () {
    hide_strategicplan(details.linked_to_objective == 1 || details.program_type == 1 ? 1 : 0);
  
    if(details.edit == 1){
        $("#program_workplan_div").show();
    }
    hide_workplan(details.edit);

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

function hide_strategicplan(param) {
    $("#strategic_plan_div").hide();
    $("#strategic_objective").removeAttr("required");
    $("#progstrategy").removeAttr("required");
    $("#starting_year").html(details.ind_financial_years);
    if (param == '1') {
        $("#strategic_plan_div").show();
        $("#strategic_objective").attr("required", "required");
        $("#progstrategy").attr("required", "required");
        $("#starting_year").html(details.financial_years);
    }
    return;
}

function get_sections() {
    hide_workplan(0);
    $("#program_duration").val("");
    $("#sector_id").html(`<option value="" selected="selected" class="selection">....Select ${details.ministry} first....</option>`);
    $("#directorate_id").html(`<option value="" selected="selected" class="selection">....Select ${details.ministry} first....</option>`);
    var department_id = $("#department_id").val();
    if (department_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_sections: "get_sections", department_id: department_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#sector_id").html(response.sections);
                    $("#directorate_id").html(`<option value="" selected="selected" class="selection">....Select ${details.section} first....</option>`);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    }
}

function get_directorate() {
    hide_workplan(0);
    $("#program_duration").val("");
    $("#directorate_id").html(`<option value="" selected="selected" class="selection">....Select ${details.section} first....</option>`);
    var sector_id = $("#sector_id").val();
    if (sector_id != "") {
        get_indicator();
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_directorate: "get_directorate", sector_id: sector_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#directorate_id").html(response.directorates);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    }
}

function get_strategy() {
    var objid = $("#strategic_objective").val();
    $.ajax({
        type: "get",
        url: ajax_url,
        data: { objid: objid, get_strategy: "get_strategy" },
        dataType: "html",
        success: function (response) {
            $("#progstrategy").html(response);
        }
    });
}

function get_workplan_header() {
    var program_duration = $("#program_duration").val();
    var program_starting_year = $("#starting_year").val();
    var strategic_plan_end_year = $("#stratplanendyear").val();
    var program_type = details.program_type;
    let link_objective = program_type == '0' ? $('input[name="progstrategyobjective"]:checked').val() : '';
    hide_workplan(0);

    if (program_duration != "" || parseInt(program_duration) != 0) {
        program_duration = parseInt(program_duration);
        if (link_objective == 1 || program_type == 1) {
            program_starting_year = parseInt(program_starting_year);
            var program_end_year = program_starting_year + program_duration - 1;
            if (program_end_year <= strategic_plan_end_year) {
                var containerH = `<tr> 
                <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
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
                        <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan()" class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </th> 
                    </tr>`;
                content += `<tr>`;
                containerH += content;
                hide_workplan(1);
                $("#phead").append(containerH);
            } else {
                hide_workplan(0);
                $("#program_duration").val("");
            }
        } else {
            if (link_objective == 0) {
                var containerH = `<tr> 
                <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
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

$rowno = $("#program_workplan_body tr").length;
function add_program_workplan() {
    var years = $("#program_duration").val();
    var directorate_id = $("#directorate_id").val();
    if (directorate_id != "") {
        var containerB =
            `<tr class="" id="row${$rowno}">
            <td>
                <select name="indicator[]" id="indicatorrow${$rowno}" onchange="get_indicator_details('row${$rowno}')" class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">... Select Indicator first ...</option>
                </select>
            </td> 
            <td id="outputrow${$rowno}">  
            </td>
            `;
        for (var i = 0; i < years; i++) {
            containerB +=
                `<td>
                <input name="targetrow${$rowno}[]" id="targetrow${$rowno}" class="form-control targetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>
            <td>
                <input name="budgetrow${$rowno}[]" id="budgetrow${$rowno}" class="form-control currency budgetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>`;
        }

        containerB += `
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`;
        $rowno = $rowno + 1;
        $("#program tr:last").after(containerB);
        get_indicator($rowno);
    } else {
        swal("Warning", `Select ${details.directorate} First!`, "warning");
    }
}

function delete_program_row(rowno) {
    $("#" + rowno).remove();
}

function get_indicator(rowno = null) {
    var sector_id = $("#sector_id").val();
    var indicator = "#indicatorrow" + (rowno - 1);
    if (sector_id) {
        $.ajax({
            type: 'get',
            url: ajax_url,
            data: { getprogindicator: sector_id },
            success: function (html) {
                if (rowno != null) {
                    $(indicator).html(html);
                } else {
                    $(".indicator").html(html);
                }
            }
        });
    } else {
        $(".indicator").html(`<option value="">... Select ${details.directorate}  First ... </option>`);
    }

}

function get_indicator_details(rowno) {
    var rw = rowno;
    var output = 'output' + rw;
    var indicator = 'indicator' + rw;
    var target = '.target' + rw;
    var budget = '.budget' + rw;
    var years = $("#program_duration").val();
    var program_starting_year = $("#starting_year").val();
    var indicatorVal = $("#" + indicator).val();

    if (indicatorVal != "") {
        $.ajax({
            type: 'get',
            url: ajax_url,
            data: {
                getUnits: indicatorVal,
                program_type: details.program_type,
                years: years,
                program_starting_year: program_starting_year,
            },
            dataType: "json",
            success: function (html) {
                var indicatorVal = $("#" + indicator).val();
                if (indicatorVal != "") {
                    $(`#output${rowno}`).html(html.indicator_name);
                    $(target).attr('placeholder', html.unit);
                    var targets = 'target' + indicatorVal + '[]';
                    var budgets = 'budget' + indicatorVal + '[]';
                    $(target).attr('name', targets);
                    $(budget).attr('name', budgets);
                    if (details.program_type == 1) {
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

