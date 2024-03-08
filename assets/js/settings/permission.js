const ajax_url = "ajax/settings/permission.php";

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


// function destroy() {
//     $.ajax({
//         type: "delete",
//         url: ajax_url,
//         data: { destroy: "destroy", id: id },
//         dataType: "json",
//         success: function (response) {
//             if (response.success) {
//                 success_alert("Successfully created record ");
//             } else {
//                 error_alert("Error could not create record");
//             }
//         }
//     });
// }


function disable(id, name, action) {
    swal({
      title: "Are you sure?",
      text: `You want to ${action} ${name}!`,
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willUpdate) => {
      if (willUpdate) {
        $.ajax({
          type: "post",
          url: ajax_url,
          data: {
            destroy: "deleteItem",
            id: id,
          },
          dataType: "json",
          success: function (response) {
            console.log(response);
            if (response == true) {
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
          }
        });
      } else {
        swal("You cancelled the action!");
      }
    })
  }