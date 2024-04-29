var ajax_url = "ajax/maps/index";
$(document).ready(function () {
    $("#submit_assign_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: "ajax/maps/index",
            data: formData = $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $(".modal").each(function () {
                    $(this).modal("hide");
                });
                if (response.success == true) {
                    success_alert("Successfully assigned mapping roles");
                } else {
                    error_alert("Sorry could not save data. Please try again later.");
                }
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            },
        });
    });
});

function assign(details) {
    $("#projid").val(details.projid);
    $("#project_name").html(details.project_name);
    $("#store_mapping_teams").val("new");
    if (details.edit == 1) {
        if (projid != "") {
            $("#store_mapping_teams").val("edit");
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_edit_details: "get_edit_details",
                    projid: details.projid,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#team").html(response.teams);
                        $(".selectpicker").selectpicker("refresh");
                        $("#mapping_date").val(response.mapping_date);
                        $("#responsible").html(response.responsible);
                    } else {
                        error_alert("Error !!! Please try again later");
                    }
                },
            });
        } else {
            error_alert("Error! please try again later");
        }
    }
}


// get responsible who is in charge
function get_responsible() {
    var members = $("#team").val();
    if (members.length > 0) {
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: {
                get_responsible: "responsible",
                members: members,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#responsible").html(response.responsible);
                } else {
                    error_alert("Error loading, please try again later");
                }
            }
        });
    } else {
        $("#responsible").html('<option value="" selected="selected" class="selection">....Select Team first....</option>');
    }
}

// validate dates
function validate_date() {
    var today = new Date().setHours(0, 0, 0, 0);
    var chosen = new Date($("#mapping_date").val()).setHours(0, 0, 0, 0);
    if (chosen < today) {
        $("#mapping_date").val("");
        error_alert("Date should be greater than today");
    }
}

function more(projid) {
    $("#moreinfo").html("");
    if (projid != "") {
        $.ajax({
            url: ajax_url,
            type: "get",
            data: {
                get_mapping_personnel: "get_mapping_personnel",
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#moreinfo").html(response.info);
                }
            },
        });
    } else {
        error_alert("Error please refresh the page");
    }
}