var ajax_url = "ajax/mneplan/checklist";
$(document).ready(function () {
    $("#add_items").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Record saved successfully");
                } else {
                    error_alert("Record could not be saved successfully");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
});

function add_checklist_details(details) {
    var task_name = details.task_name;
    var edit_id = details.edit_id;
    var task_id = details.task_id;
    var site_id = details.site_id;

    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    $("#task_name").html(task_name);
    $("#tasks_checklist_table_body").html(`<tr></tr><tr id="hideinfo3" align="center"><td colspan="3">Add Checklist!!</td></tr>`);
    if (edit_id == "1") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { task_id: task_id, site_id:site_id, get_checklist: "get_checklist" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#tasks_checklist_table_body").html(response.checklists);
                }
            }
        });
    }
}

function add_row_checklist() {
    $("#hideinfo3").remove();
    var rowno = $("#tasks_checklist_table_body tr").length + 1;
    $("#tasks_checklist_table_body tr:last").after(`
        <tr id="s_row${rowno}">
            <td></td>
            <td>
                <input type="text" name="checklist[]" id="checklistrow${rowno}" placeholder="Enter" class="form-control" required/>
            </td>
            <td>
                <select name="unit[]" id="unitrow${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                    <option value="">..Select Measurement Unit..</option>
                </select>
            </td>
            <td>
                <input type="number" name="target[]" id="targetrow${rowno}" placeholder="Enter" class="form-control" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_checklist('s_row${rowno}')">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    get_measurement_unit(rowno)
    number_table_checklist();
}

function get_measurement_unit(rowno) {
    $.ajax({
        type: "get",
        url: ajax_url,
        data: { get_measurement_unit: "get_measurement_unit" },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#unitrow" + rowno).html(response.measurement_units);
            }
        }
    });
}

function delete_row_checklist(rowno) {
    $("#" + rowno).remove();
    number_table_items(table);
    if ($("#tasks_checklist_table_body tr").length == 1) {
        $("#tasks_checklist_table_body tr:last").after(`
            <tr></tr>
            <tr id="hideinfo3" align="center">
                <td colspan="3">Add Checklist!!</td>
            </tr>`);
    }
}

function number_table_checklist() {
    $("#tasks_checklist_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

