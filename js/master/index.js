const master_ajax_url = "ajax/master/index";
$(document).ready(function () {
    $("#assign_responsible").submit(function (e) {
        e.preventDefault();
        // $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: master_ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Successfully created record");
                } else {
                    error_alert("Sorry record could not be saved");
                }
                $(".modal").each(function () {
                    $(this).modal("hide");
                });
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });

    $("#submit_assign_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-mapping").prop("disabled", true);
        $.ajax({
            type: "post",
            url: master_ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Successfully created record");
                } else {
                    error_alert("Sorry record could not be saved");
                }
                $(".modal").each(function () {
                    $(this).modal("hide");
                });
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
});

function get_responsible_options(details) {
    $("#projid").val(details.projid);
    $("#sub_stage").val(details.sub_stage);
    $("#assign_responsible_data").val(details.edit);
    if (details.projid != "") {
        $.ajax({
            type: "get",
            url: master_ajax_url,
            data: {
                get_edit_details: "edit_details",
                projid: details.projid,
                workflow_stage: details.workflow_stage,
                sub_stage: details.sub_stage,
                project_directorate: details.project_directorate
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#responsible").html(response.responsible);
                } else {
                    error_alert("Could not find responsible details");
                }
            }
        });
    }
}

function get_members_options(details) {
    $("#m_projid").val(details.projid);
    $("#m_sub_stage").val(details.sub_stage);
    if (details.projid != "") {
        $.ajax({
            type: "get",
            url: master_ajax_url,
            data: {
                get_edit_details: "edit_details",
                projid: details.projid,
                workflow_stage: details.workflow_stage,
                sub_stage: details.sub_stage,
                project_directorate: details.project_directorate
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#team").html(response.responsible);
                } else {
                    error_alert("Could not find responsible details");
                }
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
}

function get_responsible() {
    var team = $('#team').val();
    if (team.length > 0) {
        $.ajax({
            type: "get",
            url: master_ajax_url,
            data: {
                get_responsible: "get_responsible",
                team: team,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#m_responsible").html(response.responsible);
                } else {
                    error_alert("Could not find responsible details");
                }
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
}


function approve_checklist_project(details) {
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
                    url: master_ajax_url,
                    data: {
                        approve_stage: "approve_stage",
                        projid: details.projid,
                        workflow_stage: details.workflow_stage,
                        sub_stage: details.sub_stage,
                        checklist: details.checklist,
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
                    url: master_ajax_url,
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
                    url: master_ajax_url,
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
