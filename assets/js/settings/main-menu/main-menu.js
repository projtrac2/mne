const ajax_url = "ajax/settings/main-menu/main-menu.php";
$(document).ready(function () {
    $("#submitItemForm").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
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
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
    $("#read_access").hide();
    $("#child_div").hide();
});

function get_child() {
    var parent_id = $("#parent").val();
    if (parent_id != "") {
        $("#child_div").show();
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_children: "get_children", parent_id: parent_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#child").html(response.children);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    } else {
        $("#child").html(`<option value="">.... Select Parent First ....</option>`);
        $("#child_div").hide();
    }
}

function get_sections() {
    var department_id = $("#department_id").val();
    allow_read(department_id);
    if (department_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_sections: "get_sections", department_id: department_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#sector_id").html(response.sections);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    } else {
        $("#sector_id").html("");
        $("#directorate_id").html("");
    }
}

function get_directorate() {
    var sector_id = $("#sector_id").val();
    if (sector_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_directorate: "get_directorate", sector_id: sector_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#directorate_id").html(response.directorates);
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    } else {
        $("#directorate_id").html("");
    }
}

function get_edit_details(id) {
    $("#submitItemForm").trigger("reset"); //reset form
    $("#store").val("new");
    $("#id").val(id);
    if (id != "") {
        $("#store").val("edit");
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { page: "page", id: id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let page = response.page;
                    $("#name").val(page.name);

                    var parent_id = page.parent;
                    var id = parent_id != 0 ? page.parent : page.id;

                    parent_id != 0 ? $("#child_div").show() : $("#child_div").hide();

                    $("#parent").val(id);
                    $("#icon").val(page.icon);
                    $("#url").val(page.url);
                    $("#priority").val(page.priority);
                    $("#status").val(page.status);
                    $("#workflow_stage").val(page.workflow_stage);
                    var allow_read = page.allow_read;

                    var page_designations = response.page_designations;
                    var page_permissions = response.page_permissions;
                    var page_sectors = response.page_sectors;
                    $("#child").html(response.children);


                    let checked = page_sectors['checked'];
                    $("#read_access").hide();
                    if (checked) {
                        $("#read_access").show();
                        allow_read == 1 ? $("#allow_read").attr("checked", true) : "";
                    }

                    $("#department_id").html(page_sectors['departments']);
                    $("#sector_id").html(page_sectors['sections']);
                    $("#directorate_id").html(page_sectors['directorates']);

                    if (page_designations.length > 0) {
                        for (d = 0; d < page_designations.length; d++) {
                            var designation = page_designations[d].designation_id;
                            $(`#designation${designation}`).attr("checked", true);
                        }
                    }

                    if (page_permissions.length > 0) {
                        for (p = 0; p < page_permissions.length; p++) {
                            var permission = page_permissions[p].permission_id;
                            $(`#permission${permission}`).attr("checked", true);
                        }
                    }
                } else {
                    error_alert("There is no record found");
                }
            }
        });
    }
}



function destroy() {
    $.ajax({
        type: "delete",
        url: ajax_url,
        data: { destroy: "destroy", id: id },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                success_alert("Successfully created record ");
            } else {
                error_alert("Error could not create record");
            }
        }
    });
}

function allow_read(department_id) {
    var department_id = $("#department_id").val();
    $("#read_access").hide();
    $("#allow_read").attr("checked", false);
    if (department_id != "") {
        $("#read_access").show();
    }
}