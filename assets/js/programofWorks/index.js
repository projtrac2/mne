const ajax_url = "ajax/programsOfWorks/index";
$(document).ready(function () {
    $("#add_output").submit(function (e) {
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

    $("#edit_duration").submit(function (e) {
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
        url: ajax_url,
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
        url: ajax_url,
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

function validate_dates(task_id) {
    var start_date = $(`#start_date${task_id}`).val();
    var today = $("#today").val();
    if (start_date != "") {
        $(`#end_date${task_id}`).val("");
        var d1 = new Date(start_date);
        var d2 = new Date(today);
        if (d2 > d1) {
            $(`#start_date${task_id}`).val("");
            error_alert("Please ensure task start date is greater today")
        }
    } else {
        $(`#start_date${task_id}`).val("");
    }
}

function calculate_end_date(task_id) {
    var start_date = $(`#start_date${task_id}`).val();
    var duration = $(`#duration${task_id}`).val();
    if (start_date != "") {
        duration = parseInt(duration);
        var today = new Date(start_date);
        var end_date = new Date(today);
        end_date.setDate(today.getDate() + duration);
        var today = new Date(end_date).toISOString().split('T')[0];
        $(`#end_date${task_id}`).val(today);
    } else {
        $(`#duration${task_id}`).val("");
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
                    url: ajax_url,
                    data: {
                        save_data_entry: "save_data_entry",
                        projid: details.projid,
                        workflow_stage: details.workflow_stage,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Project proceeded to approval stage",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error",
                                icon: "error",
                            });
                        }
                        setTimeout(function () {
                            window.location.href = redirect_url;
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
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
                    url: ajax_url,
                    data: {
                        approve_stage: "approve_stage",
                        projid: details.projid,
                        workflow_stage: details.workflow_stage,
                        sub_stage: details.sub_stage,
                        // checklist:details.checklist,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Successfully approved project",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error approving project",
                                icon: "error",
                            });
                        }
                        setTimeout(function () {
                            window.location.href = redirect_url;
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}