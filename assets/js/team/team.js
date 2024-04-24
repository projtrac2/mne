const ajax_url = "ajax/team/team";

$(document).ready(function () {
    $("#add_output").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#tag-form-submit").prop("disabled", true);
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


    $("#add_output_check_list").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#tag-form-submit-check-list").prop("disabled", true);
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

    $("#add_output_role").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#tag-form-submit-role").prop("disabled", true);
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



function save_replace() {
    let data = new FormData();
    data.append('projid', $('#projid').val());
    data.append('user_name', $('#projid').val());
    data.append('member', $('#ptid').val());
    data.append('role', $('#role_id').val());
    data.append('owner', $('#replace-input').val());
    data.append('store_technical_team', $('#store_technical_team_replace').val());
    $.ajax({
        type: "post",
        url: ajax_url,
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function (response) {
            response = JSON.parse(response);
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
}


function get_sections() {
    $("#sector_id").html(`<option value="" selected="selected" class="selection">....Select ${details.ministry} first....</option>`);
    $("#directorate_id").html(`<option value="" selected="selected" class="selection">....Select ${details.ministry} first....</option>`);
    $(`#member`).html(`<option value="" selected="selected" class="selection">....Select ${details.ministry} first....</option>`);
    var department_id = $("#department_id").val();
    var projid = $("#projid").val();
    if (department_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_sections: "get_sections", projid: projid, department_id: department_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#sector_id").html(response.sections);
                    $("#directorate_id").html(`<option value="" selected="selected" class="selection">....Select ${details.section} first....</option>`);
                    $(`#member`).html(response.members);
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
    var department_id = $("#department_id").val();
    var projid = $("#projid").val();
    if (sector_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_directorate: "get_directorate", projid: projid, department_id: department_id, sector_id: sector_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#directorate_id").html(response.directorates);
                    $(`#member`).html(response.members);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    }
}

function get_members() {
    var department_id = $("#department_id").val();
    var sector_id = $("#sector_id").val();
    var directorate_id = $("#directorate_id").val();
    var projid = $("#projid").val();
    if (department_id != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_members: "get_members",
                projid: projid,
                department_id: department_id,
                sector_id: sector_id,
                directorate_id: directorate_id,
            },
            dataType: "json",
            success: function (response) {
                $(`#member`).html(response.members);
            }
        });
    }
}

function get_members_replace(department_id, sector_id, directorate_id, ptid, name, role_id, created_by) {
    var projid = $("#projid").val();
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_members_replace: "get_members_replace",
            projid: projid,
            department_id: department_id,
            sector_id: sector_id,
            directorate_id: directorate_id,
            user_to_replace: ptid,
        },
        error: function (error) {
        },
        success: function (response) {
            response = JSON.parse(response);
            $('#replace_user').text(name);
            $('#role_id').val(role_id);
            $('#ptid').val(ptid);
            $('#created_by').val(created_by);
            $(`#replace-input`).html(response.members_replace);
        }
    });
}

function change_role_modal_data(user_id, role_id, projid, name) {
    $('#userid_change_role').val(user_id);
    $('#change_role_user_name').text(name);
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_edit_role_input: "get_edit_role_input",
            projid: projid,
            user_id: user_id,
            role_id: role_id
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#role_edit_select").html(response.inputs);
            } else {
                error_alert("Could not find the data");
            }
        }
    });
}

const add_team_members = (user_id, role_id) => {
    var projid = $("#projid").val();
    if (projid != '' && user_id != '') {
        $("#role").val(role_id);
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_edit_details: "get_edit_details",
                projid: projid,
                user_id: user_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // $(`#member`).html(response.members);
                    // $("#sector_id").html(response.sections);
                    // $("#directorate_id").html(response.directorates);
                    // $("#department_id").html(response.departments);
                    $('#role_id').val(role_id);
                    $('#userid_edit_checklist').val(user_id);
                    $("#task_div_edit").html(response.tasks);
                } else {
                    error_alert("Could not find the data");
                }
            }
        });
    } else {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_task_details: "get_task_details",
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#task_div").html(response.tasks);
                } else {
                    error_alert("Could not find the data");
                }
            }
        });
    }
}

function output_check_box(output_id) {
    var checked = $(`.subtasks_${output_id}`).is(':checked');
    if (checked) {
        $(`#output_checked${output_id}`).html("Check");
        $(`.subtasks_${output_id}`).prop("checked", false);
        $(`.task_check_${output_id}`).prop("checked", false);
        $(`.task_checked_${output_id}`).html("Check");
    } else {
        $(`.subtasks_${output_id}`).prop("checked", true);
        $(`.task_check_${output_id}`).prop("checked", true);
        $(`#output_checked${output_id}`).html("Uncheck");
        $(`.task_checked_${output_id}`).html("Uncheck");
    }
}

const check_uncheck_output = (output_id) => {
    var checked = $(`.subtasks_${output_id}`).is(':checked');
    if (checked) {
        $(`#outputs${output_id}`).prop("checked", true);
        $(`#output_checked${output_id}`).html("Uncheck");
    } else {
        $(`#output_checked${output_id}`).html("Check");
        $(`#outputs${output_id}`).prop("checked", false);
    }
}

function check_item(output_id, task_id) {
    var checked = $(`.sub_task${task_id}`).is(':checked');
    console.log(output_id);
    console.log(checked);
    if (!checked) {
        $(`#checked${task_id}`).html("Check");
        $(`#all${task_id}`).prop("checked", false);
        check_uncheck_output(output_id);
    } else {
        $(`#all${task_id}`).prop("checked", true);
        $(`#checked${task_id}`).html("Uncheck");
        check_uncheck_output(output_id);
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


const delete_team_member = (details) => {
    swal({
        title: "Are you sure?",
        text: `You want to delete ${details.full_name} from project_team!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "get",
                    url: ajax_url,
                    data: {
                        delete_team_member: "delete_team_member",
                        projid: details.projid,
                        member: details.user_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "User !",
                                text: "Successfully removed from team project",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Team !",
                                text: "Error removing team member",
                                icon: "error",
                            });
                        }
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}


