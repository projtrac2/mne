const ajax_url1 = "ajax/programsOfWorks/index";
$(document).ready(function () {
    $("#add_output").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: ajax_url1,
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

    $("#edit_duration").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: ajax_url1,
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
    //

    $("#add_project_frequency_data").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#tag-form-submit-frequency").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url1,
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

    $("#add_project_frequency").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        // $("#tag-form-submit-frequency").prop("disabled", true);
        $.ajax({
            type: "post",
            url: "ajax/programsOfWorks/wbs",
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

    $('.tasks_id_header').each((index, element) => {
        var projid = $("#projid").val();
        var site_id = $(element).next().val();
        if (projid != '') {
            $.ajax({
                type: "get",
                url: "ajax/programsOfWorks/get-wbs",
                data: {
                    projid: projid,
                    site_id: site_id,
                    output_id: $(element).next().next().val(),
                    task_id: $(element).val(),
                    get_wbs: 'get_wbs'
                },
                dataType: "json",
                cache: false,
                success: function (response) {
                    let tkid = $(element).val();
                    $(`.peter-${site_id + tkid}`).html(response.table);
                }
            });
        }
    });
});


function get_tasks(details) {
    var output_id = details.output_id;
    var task_id = details.task_id;
    var site_id = details.site_id;
    var edit = details.edit;
    $("#output_id").val(output_id);
    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    var projid = $("#projid").val();
    (edit == "1") ? $("#store_tasks").val(1) : $("#store_tasks").val(0);
    $.ajax({
        type: "get",
        url: ajax_url1,
        data: {
            get_tasks: "get_tasks",
            projid: projid,
            output_id: output_id,
            task_id: task_id,
            site_id: site_id,
        },
        dataType: "json",
        success: function (response) {
            $("#tasks_table_body").html(response.tasks);
        }
    });
}

function get_subtasks_adjust(details) {
    var output_id = details.output_id;
    var task_id = details.task_id;
    var site_id = details.site_id;
    var subtask_id = details.subtask_id;
    $("#output_id").val(output_id);
    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    var projid = $("#projid").val();
    $.ajax({
        type: "get",
        url: ajax_url1,
        data: {
            get_subtasks_edit: "get_subtasks_edit",
            projid: projid,
            output_id: output_id,
            task_id: task_id,
            site_id: site_id,
            subtask_id: subtask_id
        },
        dataType: "json",
        success: function (response) {
            $("#tasks_table_body").html(response.tasks);
        }
    });
}

function get_frequency_tasks(details) {
    var output_id = details.output_id;
    var task_id = details.task_id;
    var site_id = details.site_id;
    var edit = details.edit;
    $("#output_id").val(output_id);
    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    var projid = $("#projid").val();
    (edit == "1") ? $("#store_tasks").val(1) : $("#store_tasks").val(0);
    $.ajax({
        type: "get",
        url: ajax_url1,
        data: {
            get_frequency_tasks: "get_frequency_tasks",
            projid: projid,
            output_id: output_id,
            task_id: task_id,
            site_id: site_id,
        },
        dataType: "json",
        success: function (response) {
            $("#tasks_table_body").html(response.tasks);
        }
    });
}

function get_subtasks_wbs(output_id, site_id, task_id, subtask_id) {
    $("#t_output_id").val(output_id);
    $("#t_site_id").val(site_id);
    $("#t_task_id").val(task_id);
    $("#t_subtask_id").val(subtask_id);
    // if (subtask_id != '' && site_id != '') {
    $.ajax({
        type: "get",
        url: "ajax/programsOfWorks/wbs",
        data: {
            get_wbs: "get_wbs",
            projid: $("#projid").val(),
            output_id: output_id,
            site_id: site_id,
            task_id: task_id,
            subtask_id: subtask_id
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#tasks_wbs_table_body").html(response.structure);
                var subtask = response.task;
                $("#subtask_start_date").html(response.start_date);
                $("#subtask_duration").html(response.duration);
                $("#subtask_end_date").html(response.end_date);
                $("#subtask_target").html(subtask.units_no + ' ' + subtask.unit);
                $("#subtask_name").html(subtask.task);
                $("#total_target").val(subtask.units_no);

            } else {
                error_alert("Error please try again later");
            }
        }
    });
    // }
}

function validate_dates(task_id) {
    var project_start_date = $("#project_start_date").val();
    var project_end_date = $("#project_end_date").val();
    var today = $("#today").val();

    if (project_start_date != '' && project_end_date != '') {
        var start_date = $(`#start_date${task_id}`).val();
        if (start_date != "") {
            var d1 = new Date(start_date);
            var d2 = new Date(today);
            var d3 = new Date(project_start_date);
            var d4 = new Date(project_end_date);

            if (d3 <= d1 && d1 <= d4) {
                if (d2 > d1) {
                    $(`#start_date${task_id}`).val("");
                    error_alert("Please ensure task start date is greater today")
                }
                calculate_end_date(task_id)
            } else {
                $(`#start_date${task_id}`).val("");
                error_alert("Please ensure that start date is between contract dates");
            }
        } else {
            $(`#start_date${task_id}`).val("");
        }
    } else {
        $(`#start_date${task_id}`).val("");
    }
}

function calculate_end_date(task_id) {
    var project_end_date = $(`#project_end_date`).val();
    var start_date = $(`#start_date${task_id}`).val();
    var duration = $(`#duration${task_id}`).val();
    if (project_end_date != '') {
        if (start_date != "" && duration != "") {
            $.ajax({
                type: "get",
                url: ajax_url1,
                data: {
                    compare_dates: "compare_dates",
                    project_end_date: project_end_date,
                    start_date: start_date,
                    duration: duration
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $(`#end_date${task_id}`).val(response.subtask_end_date);
                    } else {
                        $(`#duration${task_id}`).val("");
                        $(`#end_date${task_id}`).val("");
                        error_alert("Please ensure that end date is between contract dates");
                    }
                }
            });
        } else {
            $(`#duration${task_id}`).val("");
            $(`#end_date${task_id}`).val("");
        }
    } else {
        $(`#duration${task_id}`).val("");
        $(`#end_date${task_id}`).val("");
        error_alert('Project start date please check');
    }

}

function save_data_entry_project(details) {
    swal({
        title: "Are you sure?",
        text: `You want to submit the entered data (${details.project_name}) for approval stage!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: ajax_url1,
                    data: {
                        save_data_entry: "save_data_entry",
                        projid: details.projid,
                        workflow_stage: details.workflow_stage,
                        sub_stage: details.sub_stage,
                        csrf_token: $("#csrf_token").val(),
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            success_alert("Project proceeded to approval stage");
                        } else {
                            error_alert("error occured while processing");
                        }
                        setTimeout(function () {
                            window.location.href = redirect_url;
                        }, 3000);
                    },
                });
            } else {
                success_alert("You cancelled the action!");
            }
        });
}

function approve_project(details) {
    swal({
        title: "Are you sure?",
        text: `You want to approve ${details.project_name} and move it to the next stage!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: ajax_url1,
                    data: {
                        approve_stage: "approve_stage",
                        projid: details.projid,
                        workflow_stage: details.workflow_stage,
                        sub_stage: details.sub_stage,
                        csrf_token: $("#csrf_token").val(),
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            success_alert("Successfully approved project")
                        } else {
                            error_alert("Error approving project")
                        }
                        setTimeout(function () {
                            window.location.href = redirect_url;
                        }, 3000);
                    },
                });
            } else {
                error_alert("You cancelled the action!");
            }
        });
}


const calculate_total = (direct_cost_id) => {
    var target = 0;
    $(".targets").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal != '') {
            target += parseFloat(getVal);
        }
    });

    var response = false;
    var total_target = $("#total_target").val();
    if (total_target != '') {
        total_target = parseFloat(total_target);
        if (total_target >= target) {
            response = true;
        } else {
            $(`#direct_cost_id${direct_cost_id}`).val("");
            error_alert('You should not exceed planned target')
        }
    }
    return response;
}

const calculate_total1 = () => {
    var target = 0;
    $(".targets").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal != '') {
            target += parseFloat(getVal);
        }
    });

    var response = false;
    var total_target = $("#total_target").val();
    if (total_target != '') {
        total_target = parseFloat(total_target);
        if (total_target == target) {
            response = true;
        } else {
            error_alert('Subtask Target should be equal to the planned target')
        }
    }
    return response;
}