var ajax_url = "ajax/notifications/index";
$(document).ready(function () {

    $("#submit_notification_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-notification").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
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

    $("#submit_template_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-template").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
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

    $("#submit_timeline_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-timeline").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
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

// notification group
const change_notification_group_status = (notification_group_id, status_id, status, area) => {
    swal({
        title: "Are you sure?",
        text: `You want to ${status} ${area}!`,
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
                        update_notification_group_status: "update_notification_group_status",
                        notification_group_id: notification_group_id,
                        status: status_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Notification Group !",
                                text: `Successfully ${status}`,
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Notification Group !",
                                text: `Error approving ${status}`,
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

// notifications
const change_notification_status = (notification_id, status_id, status, notification) => {
    swal({
        title: "Are you sure?",
        text: `You want to ${status} ${notification}!`,
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
                        update_notification_status: "update_notification_status",
                        notification_id: notification_id,
                        status: status_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Notification !",
                                text: `Successfully ${status}`,
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Notification !",
                                text: `Error ${status}`,
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

const get_notification_details = (edit, notification_id, notification, data_entry, approval) => {
    $("#store_notification").val(edit);
    $("#notification_id").val(notification_id);
    $("#notification_name").val(notification);
    $("#data_entry").val(data_entry);
    $("#approval").val(approval);
}

// Notification Template
const change_notification_template_status = (template_id, status_id, status, title) => {
    swal({
        title: "Are you sure?",
        text: `You want to ${status} ${title}!`,
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
                        update_notification_template_status: "update_notification_template_status",
                        template_id: template_id,
                        status: status_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Notification Template!",
                                text: `Successfully ${status}`,
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Notification Template!",
                                text: `Error ${status}`,
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

const get_notification_template_details = (template_id, notification_type) => {
    $("#template_id").val(template_id);
    $("#notification_type").val(notification_type);
    if (template_id != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_notification_template_details: "get_notification_template_details",
                template_id: template_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var template = response.template;
                    $("#title").val(template.title);
                    $("#content").val(template.content);
                } else {
                    error_alert("Error try again later");
                }
            }
        });
    }
}


// Notification timelines
const get_notification_timeline_details = (edit, timeline_id, notification_type) => {
    $("#timeline_id").val(timeline_id);
    $("#store_notifications_timelines").val(edit);
    $("#notification_type").val(notification_type);
    if (timeline_id != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_notification_timeline_details: "get_notification_timeline_details",
                timeline_id: timeline_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var timeline = response.timeline;
                    $("#timeline").val(timeline.timeline);
                } else {
                    error_alert("Error try again later");
                }
            }
        });
    }
}

const change_notification_timeline_status = (timeline_id, status_id, status, timeline) => {
    swal({
        title: "Are you sure?",
        text: `You want to ${status} ${timeline}!`,
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
                        update_notification_timeline_status: "update_notification_timeline_status",
                        timeline_id: timeline_id,
                        status: status_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Notification !",
                                text: `Successfully ${status}`,
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Notification !",
                                text: `Error ${status}`,
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