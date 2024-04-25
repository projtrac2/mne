var manageItemTable;

$(document).ready(function() {
  // manage Contractor Business Type data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-contractor-business-types-items.php",
    order: [], 
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });

  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var type = $("#type").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (type == "") {
      $("#type").after(
        '<p class="text-danger">Contractor Business Types field is required</p>'
      );
      $("#type")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#type")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#type")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Contractor Business Types Description field is required</p>'
      );
      $("#description")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#description")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#description")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (type && description) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the manage Contractor Business Type table
            manageItemTable.ajax.reload(null, true);

            alert("Record Successfully Saved");
            $(".modal").each(function() {
              $(this).modal("hide");
            });
          } // /if response.success
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok

    return false;
  }); // /submit Contractor Business Type form

  // add Contractor Business Type modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // Contractor Business Type form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Contractor Business Type modal btn clicked

  // remove Contractor Business Type
}); // document.ready fucntion

function editItem(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    // remove text-error
    $(".text-danger").remove();
    // remove from-group error
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success");
    // modal div
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "general-settings/selected-items/fetch-selected-contractor-business-types-item.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        //console.log(response);
        // modal div
        $(".div-result").removeClass("div-hide");

        // Contractor Business Type id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        // Contractor Business Type name
        $("#editType").val(response.type);
        // quantity
        $("#editDescription").val(response.description);

        // update the Contractor Business Type data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var type = $("#editType").val();
            var description = $("#editDescription").val();

            if (type == "") {
              $("#editType").after(
                '<p class="text-danger">Contractor Business Types field is required</p>'
              );
              $("#editType")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editType")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editType")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (description == "") {
              $("#editDescription").after(
                '<p class="text-danger">Contractor Business Types Description field is required</p>'
              );
              $("#editDescription")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editDescription")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editDescription")
                .closest(".form-input")
                .addClass("has-success");
            } // /else


            if (type && description) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  response = JSON.parse(response);
                  if (response.success) {
                    // submit loading button
                    $("#editProductBtn").button("reset");

                    // reload the manage Contractor Business Type table
                    manageItemTable.ajax.reload(null, true);

                    alert(response.messages);
                    $(".modal").each(function() {
                      $(this).modal("hide");
                    });
                  } // /success function
                } // /success function
              }); // /ajax function
            } // /if validation is ok

            return false;
          }); // update the Contractor Business Type data function
      } // /success function
    }); // /ajax to fetch Contractor Business Type image
  } else {
    alert("error please refresh the page");
  }
} // /edit Contractor Business Type function

// disable Contractor Business Type
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
        url: 'general-settings/action/project-contractor-business-types-action.php',
        data: {
          deleteItem: "deleteItem",
          itemId: id,
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
