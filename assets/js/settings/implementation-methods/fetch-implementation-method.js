var manageItemTable;

$(document).ready(function () {
  // manage Project Implementation  data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "ajax/settings/implementation-method/fetch-selected-implementation-method.php",
    order: [],
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });

  $("#submitItemForm").on("submit", function (event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var method = $("#method").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (method == "") {
      $("#method").after(
        '<p class="text-danger">Project Implementation  field is required</p>'
      );
      $("#method")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#method")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#method")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Project Implementation  Description field is required</p>'
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

    if (method && description) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "ajax/settings/implementation-method/project-implementation-method-action.php",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function (response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the manage Project Implementation  table
            manageItemTable.ajax.reload(null, true);

            alert("Record Successfully Saved");
            $(".modal").each(function () {
              $(this).modal("hide");
            });
          } // /if response.success
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok
    return false;
  }); // /submit Project Implementation  form

  // add Project Implementation  modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function () {
      // // Project Implementation  form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Project Implementation  modal btn clicked

  // remove Project Implementation 
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

      url: "ajax/settings/implementation-method/fetch-selected-implementation-method-item.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function (response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // Project Implementation  id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
          response.id +
          '" />'
        );
        $("#editmethod").val(response.method);
        $("#editDescription").val(response.description);

        // update the Project Implementation  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function () {
            // form validation
            var method = $("#editmethod").val();
            var description = $("#editDescription").val();

            if (method == "") {
              $("#editmethod").after(
                '<p class="text-danger">Project Implementation  field is required</p>'
              );
              $("#editmethod")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editmethod")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editmethod")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (description == "") {
              $("#editDescription").after(
                '<p class="text-danger">Project Implementation  Description field is required</p>'
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


            if (method && description) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "ajax/settings/implementation-method/project-implementation-method-action.php",
                type: form.attr("method"),
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                  if (response.success) {
                    // submit loading button
                    $("#editProductBtn").button("reset");

                    // reload the manage Project Implementation  table
                    manageItemTable.ajax.reload(null, true);

                    alert(response.messages);
                    $(".modal").each(function () {
                      $(this).modal("hide");
                    });
                  } // /success function
                } // /success function
              }); // /ajax function
            } // /if validation is ok

            return false;
          }); // update the Project Implementation  data function
      } // /success function
    }); // /ajax to fetch Project Implementation  image
  } else {
    alert("error please refresh the page");
  }
} // /edit Project Implementation  function

// remove Project Implementation 
function removeItem(itemId = null) {
  if (itemId) {
    // remove Project Implementation  button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: "ajax/settings/implementation-method/project-implementation-method-action.php",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage Project Implementation  table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Project Implementation 
        return false;
      }); // /remove Project Implementation  btn clicked
  } // /if Project Implementation id
} // /remove Project Implementation  function

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
        url: 'ajax/settings/implementation-method/project-implementation-method-action.php',
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
