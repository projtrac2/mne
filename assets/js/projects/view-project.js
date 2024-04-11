const ajax_url = "ajax/projects/more";
const more_url = "ajax/projects/info";
const approve_url = "ajax/projects/approve";

$(document).ready(function () {
    get_total_projects();
    $("#adjustFyForm").submit(function (e) {
        e.preventDefault();
        var projid = $("#projid").val();
        var financialyear = $("#financialyear").val();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: { "projid": projid, "financialyear": financialyear },
            dataType: "json",
            success: function (response) {
                if (response.msg) {
                    // manageItemTable.ajax.reload(null, true);
                    success_alert(response.msg);
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);

                } else {
                    error_alert(response.msg);
                }
            }
        });
    });

    $("#approveItemForm").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        $.ajax({
            url: "ajax/projects/approve",
            type: "post",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response) {
                    $("#editProductBtn").button("reset");
                    success_alert(response.messages);
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                }
                setTimeout(() => {
                    window.location.reload();
                    // manageItemTable.ajax.reload(null, true);
                }, 3000);
            },
        });
    });
});

function project_info(projid = null) {
    if (projid != null) {
        $.ajax({
            type: "get",
            url: more_url,
            data: { projid: projid, project_info: "project_info" },
            dataType: "html",
            success: function (response) {
                $("#moreinfo").html(response);
            }
        });
    } else {
        alert("error please refresh the page");
    }
}

function add_to_adp(details) {
    swal({
        title: "Are you sure?",
        text: `This project will be added to ${details.currentfy} ADP!`,
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
                        add_to_adp: 'add_to_adp',
                        projid: details.projid,
                        plan: details.plan,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: response.messages,
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: response.messages,
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


function remove_from_adp(projid) {
    swal({
        title: "Are you sure?",
        text: "You want to remove project from ADP!",
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
                        remove_from_adp: 'remove_from_adp',
                        projid: projid,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Project successfully removed from ADP",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text:  "Error removing Project from ADP",
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

function removeItem(projid) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this project data!",
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
                        deleteItem: 'deleteItem',
                        itemId: projid,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Successfully deleted project",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error deleting project",
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

function adjustFy(details) {
    if (details.projid != "") {
        var projname = $(`#projname${details.projid}`).val();
        console.log(projname);
        $("#projname").html(projname);
        $("#projid").val(details.projid);
    } else {
        error_alert("error please refresh the page");
    }
}

function get_total_projects() {
    $.ajax({
        type: "get",
        url: "ajax/projects/index",
        data: {
            get_total_number_of_projects: "get_total_number_of_projects",
            type: 1,
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                $("#total-projects").html(response.projects);
            }
        }
    });
}
