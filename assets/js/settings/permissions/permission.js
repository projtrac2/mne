const ajax_url = "ajax/settings/permissions/permission.php";

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
                if(response.success){
                    success_alert("Successfully created record");
                }else{
                    error_alert("Sorry record could not be saved");
                }
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });

    $("#submitPermissionForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Successfully created record ");
                } else {
                    error_alert("Error could not create record");
                }
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
});

function get_edit_details(id) {
    $("#store").val("new");
    $("#id").val(id);
    if (id != "") {
        $("#store").val("edit");
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { permission: "permission", id: id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let perm = response.permission;
                    $("#status").val(perm.status);
                    $("#name").val(perm.name);
                    $("#phrase").val(perm.phrase);
                } else {
                    console.log("There is no record found");
                }
            }
        });
    }
}


function add_designations(id) {
    $("#store_designation").val("new");
    $("#permission_id").val(id);
    if (id != "") {
        $("#store_designation").val("edit");
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_designation_permission: "get_designation_permission", id: id },
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

// sweet alert notifications
function success_alert(msg) {
    return swal({
        title: "Success",
        text: msg,
        type: "Success",
        icon: 'success',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
}


// sweet alert notifications
function error_alert(msg) {
    return swal({
        title: "Error !!!",
        text: msg,
        type: "Error",
        icon: 'warning',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
}