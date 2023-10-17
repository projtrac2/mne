const ajax_url = "ajax/settings/designation";
$(document).ready(function () {
    $("#submitPermissionForm").submit(function (e) {
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
});

function get_edit_details(id, edit) {
    $("#store").val("new");
    $("#id").val(id);
    if (id != "" && edit == 1) {
        $("#store").val("edit");
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { designation_permission: "designation_permission", designation_id: id },
            dataType: "json",
            success: function (response) {
                if (response.success) { 
                    var designation_permissions = response.designation_permissions; 
                    if (designation_permissions.length > 0) {
                        for (p = 0; p < designation_permissions.length; p++) {
                            var permission = designation_permissions[p].permission_id;
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