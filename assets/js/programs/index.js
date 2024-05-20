const ajax_url = 'ajax/programs/index';

$(document).ready(function () {
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

function get_sections() {
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

$rowno = $("#program_workplan_body tr").length;
function add_program_workplan() {
    var years = $("#strategic_plan_duration").val();
    var containerB =
        `<tr class="" id="row${$rowno}">
            <td>
                <select name="indicator[]" id="indicatorrow${$rowno}" onchange="get_indicator_details('row${$rowno}')" class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">... Select Indicator first ...</option>
                </select>
            </td>
            <td id="outputrow${$rowno}">
            </td>`;
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

}

function delete_program_row(rowno) {
    $("#" + rowno).remove();
}

function get_indicator(rowno = null) {
    var indicator = "#indicatorrow" + (rowno - 1);
    $.ajax({
        type: 'get',
        url: ajax_url,
        data: { getprogindicator: "" },
        success: function (html) {
            if (rowno != null) {
                $(indicator).html(html);
            } else {
                $(".indicator").html(html);
            }
        }
    });
}

function get_indicator_details(rowno) {
    var rw = rowno;
    var indicator = 'indicator' + rw;
    var target = '.target' + rw;
    var budget = '.budget' + rw;
    var indicatorVal = $("#" + indicator).val();

    if (indicatorVal != "") {
        $.ajax({
            type: 'get',
            url: ajax_url,
            data: {
                getUnits: indicatorVal,
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
                }
            }
        });
    }
}
