const ajax_url = "ajax/inspection/specification";
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

function add_specification_details(details) {
    var site_id = details.site_id;
    var edit_id = details.edit_id;
    var task_id = details.task_id;
    $("#task_id").val(task_id);
    $("#site_id").val(site_id);
    $("#tasks_specifications_table_body").html(`<tr></tr><tr id="hideinfo3" align="center"><td colspan="3">Add Specifications!!</td></tr>`);
    if (edit_id == "1") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { site_id: site_id, task_id:task_id, get_specification: "get_specification" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#tasks_specifications_table_body").html(response.specifications);
                }
            }
        });
    }
}

function add_row_specifications() {
    $("#hideinfo3").remove();
    var rowno = $("#tasks_specifications_table_body tr").length + 1;
    $("#tasks_specifications_table_body tr:last").after(`
        <tr id="s_row${rowno}">
            <td></td>
            <td>
                <input type="text" name="specifications[]" id="specificationsrow${rowno}" placeholder="Enter" class="form-control" required/>
            </td>
            <td>
                <select name="standard[]" id="standard${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false">
                    <option value="">..Select Standard..</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_specifications('s_row${rowno}')">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    get_standards(rowno);
    number_table_specifications();
}

function get_standards(rowno) {
    $.ajax({
        type: "get",
        url: ajax_url,
        data: { get_standards: "get_standards" },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#standard" + rowno).html(response.standard);
            }
        }
    });
}

function delete_row_specifications(rowno) {
    $("#" + rowno).remove();
    number_table_items(table);
    if ($("#tasks_specifications_table_body tr").length == 1) {
        $("#tasks_specifications_table_body tr:last").after(`
            <tr></tr>
            <tr id="hideinfo3" align="center">
                <td colspan="3">Add Specifications!!</td>
            </tr>`);
    }
}

function number_table_specifications() {
    $("#tasks_specifications_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}


