var manageItemTable;

$(document).ready(function () {
  // manage Project Main Menu  data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-measurement-units.php",
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
    var unit = $("#unit").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (unit == "") {
      $("#unit").after(
        '<p class="text-danger">Measurement unit field is required</p>'
      );
      $("#unit")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#unit")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#unit")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Measurement unit description field is required</p>'
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
    }

    if (unit && description) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function (response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the titles table
            manageItemTable.ajax.reload(null, true);
            swal("Record successfully saved");
            $(".modal").each(function () {
              $(this).modal("hide");
            });
          } // /if response.success
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok
    // /if validation is ok
    return false;
  }); // /submit Project Main Menu  form

  // add Project Main Menu  modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function () {
      // // Project Main Menu  form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Project Main Menu  modal btn clicked

  // remove Project Main Menu
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
      url: "general-settings/selected-items/fetch-selected-measurement-unit.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function (response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // Project Main Menu  id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
          response.id +
          '" />'
        );

        $("#editunit").val(response.unit);
        $("#editdescription").val(response.description);;

        // update the Project Main Menu  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function (e) {
            // form validation
            e.preventDefault();
            var editunit = $("#editunit").val();
            var editdescription = $("#editdescription").val();

            if (editunit == "") {
              $("#editunit").after(
                '<p class="text-danger">Measurement unit field is required</p>'
              );
              $("#editunit")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editunit")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editunit")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (editdescription == "") {
              $("#editdescription").after(
                '<p class="text-danger">Measurement unit description field is required</p>'
              );
              $("#editdescription")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editdescription")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editdescription")
                .closest(".form-input")
                .addClass("has-success");
            } // /else


            if (editunit && editdescription) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/measurement-units-action.php",
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
                    $(".modal").each(function () {
                      $(this).modal("hide");
                    });
                    manageItemTable.ajax.reload(null, true);
                    swal('Record successfully updated');
                    setTimeout(() => {
                    }, 3000);
                  } // /success function
                } // /success function
              }); // /ajax function
            } // /if validation is ok

            return false;
          }); // update the Project Main Menu  data function
      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    swal('Error creating record!!');
    setTimeout(() => {
      location.reload(true);
    }, 3000);
  }
} // /edit Project Main Menu  function

// disable Project Main Menu
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
        url: 'general-settings/action/measurement-units-action.php',
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

          manageItemTable.ajax.reload(null, true);
          // setTimeout(function () {
          //   window.location.reload(true);
          // }, 3000);
        }
      });
    } else {
      swal("You cancelled the action!");
    }
  })
}
