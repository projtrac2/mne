// var ajax_url = "ajax/activities/index";

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

function set_milestone_parameters(mapping_type) {
    if (mapping_type == "1") {
        $("#milestone_table_head").html(`
            <tr>
                <th width="5%">#</th>
                <th width="90%">Task</th>
                <th width="5%">
                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_items(1);" class="btn btn-success btn-sm addplus_output">
                        <span class="glyphicon glyphicon-plus">
                        </span>
                    </button>
                </th>
            </tr>`);
        $("#sites_div").show();
        $("#sites").attr("required", "required");
        get_sites(0);
    } else {
        $("#milestone_table_head").html(`
            <tr>
                <th width="5%">#</th>
                <th width="55%">Task</th>
                <th width="5%">
                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_items(1);" class="btn btn-success btn-sm addplus_output">
                        <span class="glyphicon glyphicon-plus">
                        </span>
                    </button>
                </th>
            </tr>`);
    }
}

function set_common_parameters() {
    $(".addplus_output").attr("disabled", false);
    $("#sites_div").hide();
    $("#milestone_div").hide();
    $("#tasks_div").hide();
    $("#sites").removeAttr("required");
    $("#sites").html('<option value="">..Select Site Type..</option>');
    $("#milestone_table_body").html('<tr></tr><tr id="hideinfo1"><td colspan="5" align="center"> Add Tasks</td></tr>');
    $("#tasks_table_body").html('<tr><tr id="hideinfo2"><td colspan="5" align="center"> Add Tasks</td></tr>');
}

function add_details(options, table, edit = "") {
    var milestone_details = options.milestone_details;
    var task_details = options.task_details;
    var output_details = options.output_details;
    var output_name = output_details.output_name;
    var output_id = output_details.output_id;
    var mapping_type = output_details.mapping_type;

    $("#output_id").val(output_id);
    $("#mapping_type").val(mapping_type);
    $("#milestone_id").val(milestone_details.milestone_id);
    $("#task_id").val(task_details.task_id);

    set_common_parameters();
    set_milestone_parameters(mapping_type);

    if (table == 1 || table == 4) {
        $("#milestone_div").show();
        if (table == 1) {
            $("#store_data").val("add_milestones");
            if (edit == 1) {
                $("#store_data").val("edit_milestone");
                if (mapping_type == "1") {
                    $("#milestone_table_body").html(`
                    <tr id="m_row1">
                        <td>1</td>
                        <td>
                            <input type="text" name="milestone" value="${milestone_details.milestone_name}" id="milestonerow1" placeholder="Enter" class="form-control" required/>
                        </td>
                        <td></td>
                    </tr>`);
                } else {
                    $("#milestone_table_body").html(`
                    <tr id="m_row1">
                        <td>1</td>
                        <td>
                            <input type="text" name="milestone" value="${milestone_details.milestone_name}" id="milestonerow1" placeholder="Enter" class="form-control" required/>
                        </td>
                        <td></td>
                    </tr>`);
                }
                $(".addplus_output").attr("disabled", true);
            }
        }
    } else if (table == 2) {
        $("#sequence_id").hide();
        $("#tasks_div").show();
        $("#store_data").val("tasks");
        if (edit == 1) {
            $("#sequence_id").show();
            $(".addplus_output").attr("disabled", true);
            $("#store_data").val("edit_tasks");
            $("#tasks_table_body").html(`
            <tr id="m_row1">
                <td>1</td>
                <td>
                    <input type="text" name="task" value="${task_details.task_name}" id="taskrow1" placeholder="Enter" class="form-control" required/>
                </td>
                <td>
                    <select name="unit_of_measure" id="unit_of_measurerow1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" >
                        <option value="">..Select Unit of Measure..</option>
                    </select>
                </td>
                <td></td>
            </tr>`);
            get_unit_of_measurements(1, task_details.unit_of_measure);
        }
    }
}

function add_row_items(table) {
    if (table == 1) {
        add_milestone();
    } else if (table == 2) {
        add_task();
        $("#sequence_id").hide();
    }
}

function add_milestone() {
    var mapping_type = $("#mapping_type").val();
    $("#hideinfo1").remove(); //new change
    $("#tasks_table_body tr:last").after('<tr id="hideinfo"><td colspan="5" align="center"> Add Tasks</td></tr>');
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#milestone_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;

    if (mapping_type == "1") {
        $("#milestone_table_body tr:last").after(`
        <tr id="m_row${rowno}">
            <td></td>
            <td>
                <input type="text" name="milestone[]" id="milestonerow${rowno}" placeholder="Enter" class="form-control" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items('m_row${rowno}', 1)">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    } else {
        $("#milestone_table_body tr:last").after(`
        <tr id="m_row${rowno}">
            <td></td>
            <td>
                <input type="text" name="milestone[]" id="milestonerow${rowno}" placeholder="Enter" class="form-control" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items('m_row${rowno}', 1)">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
        $(".selectpicker").selectpicker("refresh");
        get_sites(rowno);
    }
    number_table_items(1);
}

function add_task() {
    $("#hideinfo2").remove();
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#tasks_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;

    $("#tasks_table_body tr:last").after(`
    <tr id="m_row${rowno}">
        <td></td>
        <td>
            <input type="text" name="task[]" id="taskrow${rowno}" placeholder="Describe your sub-task" class="form-control" required/>
        </td>
        <td>
            <select name="unit_of_measure[]" id="unit_of_measurerow${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" >
                <option value="">..Select Unit of Measure..</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items('m_row${rowno}', 2)">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    get_unit_of_measurements(rowno, 0);
    number_table_items(2);
}

function delete_row_Items(rowno, table) {
    $("#" + rowno).remove();
    number_table_items(table);
    if (table == 1) {
        if ($("#milestone_table_body tr").length == 1) {
            $("#milestone_table_body tr:last").after('<tr id="hideinfo1"><td colspan="5" align="center"> Add Milestones</td></tr>');
        }
    } else if (table == 2) {
        if ($("#tasks_table_body tr").length == 1) {
            $("#tasks_table_body tr:last").after('<tr id="hideinfo2"><td colspan="5" align="center"> Add Tasks</td></tr>');
        }
    }
}

function number_table_items(table) {
    if (table == 1) {
        $("#milestone_table_body tr").each(function (idx) {
            $(this)
                .children()
                .first()
                .html(idx - 1 + 1);
        });
    } else if (table == 2) {
        $("#tasks_table_body tr").each(function (idx) {
            $(this)
                .children()
                .first()
                .html(idx - 1 + 1);
        });
    }
}

function get_unit_of_measurements(rowno, unit_of_measure) {
    if (rowno != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_unit_of_measure: "get_unit_of_measure",
                unit_of_measure: unit_of_measure,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#unit_of_measurerow${rowno}`).html(response.options);
                }
                $(".selectpicker").selectpicker("refresh");
            }
        });
    } else {
        error_alert("please ensure you have a valid output");
    }
}

function get_sites(rowno, sites) {
    var output_id = $("#output_id").val();
    var mapping_type = $("#mapping_type").val();
    if (mapping_type != "" || output_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_sites: "get_sites",
                output_id: output_id,
                mapping_type: mapping_type,
                sites: sites
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    if (mapping_type == "1") {
                        $("#sites").html(response.options);
                    } else {
                        $(`#sitesrow${rowno}`).html(response.options);
                    }
                }
                $(".selectpicker").selectpicker("refresh");
            }
        });
    } else {
        error_alert("please ensure you have a valid output");
    }
}

function destroy_task(id, table) {
    if (id != "" && table != "") {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover the record!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: ajax_url,
                        data: {
                            destroy_item: "destroy_item",
                            id: id,
                            table: table,
                            csrf_token: $("#csrf_token").val(),
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                success_alert("Record deleted successfully");
                            } else {
                                error_alert("Could not delete the record");
                            }
                            setTimeout(() => {
                                location.reload(true);
                            }, 1000);
                        }
                    });
                } else {
                    swal("You have cancelled action!");
                }
            });
    }
}

function check_box(task_id) {
    var checked = $(`.sub_task${task_id}`).is(':checked');
    if (checked) {
        $(`#checked${task_id}`).html("Check");
        $(`.sub_task${task_id}`).prop("checked", false);
    } else {
        $(`.sub_task${task_id}`).prop("checked", true);
        $(`#checked${task_id}`).html("Uncheck");
        console.log("Checking")
    }
}

function check_item(task_id) {
    var checked = $(`.sub_task${task_id}`).is(':checked');
    if (!checked) {
        $(`#checked${task_id}`).html("Check");
        $(`#all${task_id}`).prop("checked", false);
    } else {
        $(`#all${task_id}`).prop("checked", true);
        $(`#checked${task_id}`).html("Uncheck");
    }
}

function validateForm() {
    var checked = $(`.tasks`).is(':checked');

    if (!checked) {
        error_alert("Ensure you have attached atleast one sub-task");
        return false;
    }
    return checked;
}