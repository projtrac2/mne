const ajax_url = "ajax/adjustment/cost";
$(document).ready(function () {
    $("#edit_cost").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Record successfully created");
                } else {
                    error_alert("Record could not be created");
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
});

function get_subtasks_adjust(details) {
    var output_id = details.output_id;
    var task_id = details.task_id;
    var site_id = details.site_id;
    var subtask_id = details.subtask_id;
    $("#output_id").val(output_id);
    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    var projid = $("#projid").val();
    var adjustment_id = $("#adjustment_id").val();
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_subtasks_edit: "get_subtasks_edit",
            projid: projid,
            output_id: output_id,
            task_id: task_id,
            site_id: site_id,
            subtask_id: subtask_id,
            adjustment_id: adjustment_id,
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#tasks_table_body").html(response.tasks);
                $("#subtask_name").html(response.task);
                $("#adjusted_amount").html(response.remaining_amount);
            } else {
                console.log("Sorry no data found")
            }
        }
    });
}

//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function change_calculate_adjust_cost(task_id, remaining_units, remaining_amount) {
    var unit_cost = $(`#unit_cost${task_id}`).val();
    if (unit_cost != '') {
        unit_cost = parseFloat(unit_cost);
        remaining_units = parseFloat(remaining_units);
        var sub_total_cost = unit_cost * remaining_units;
        $("#subtotal_amount").html(commaSeparateNumber(sub_total_cost));
        if (sub_total_cost > remaining_amount) {
            $(`#unit_cost${task_id}`).val("");
            error_alert("Ensure you do not exceed the amount the project was adjusted");
            $("#subtotal_amount").html(0);
        }
    }
}