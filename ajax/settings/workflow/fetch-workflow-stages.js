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
            alert("Record successfully saved");
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
        $("#editStatus").val(response.active);

        // update the Project Main Menu  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function (e) {
            // form validation
            e.preventDefault();
            var editunit = $("#editunit").val();
            var editdescription = $("#editdescription").val();
            var itemStatus = $("#editStatus").val();

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

            if (itemStatus == "") {
              $("#editStatus").after(
                '<p class="text-danger">Status field is required</p>'
              );
              $("#editStatus")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editStatus")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editStatus")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (editunit && editdescription && editStatus) {
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
                  if (response) {
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

// remove Project Main Menu
function removeItem(itemId = null) {
  if (itemId) {
    // remove Project Main Menu  button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/measurement-units-action.php",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              swal('Record successfully deleted');
              setTimeout(() => {
              }, 3000);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              swal('Error Updating Record');
              setTimeout(() => {
                location.reload(true);
              }, 3000);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Project Main Menu
        return false;
      }); // /remove Project Main Menu  btn clicked
  } // /if Project Main Menu id
} // /remove Project Main Menu  function

function clearForm(oForm) {
  // var frm_elements = oForm.elements;
  // console.log(frm_elements);
  // for(i=0;i<frm_elements.length;i++) {
  // field_type = frm_elements[i].type.toLowerCase();
  // switch (field_type) {
  //    case "text":
  //    case "password":
  //    case "textarea":
  //    case "hidden":
  //    case "select-one":
  //      frm_elements[i].value = "";
  //      break;
  //    case "radio":
  //    case "checkbox":
  //      if (frm_elements[i].checked)
  //      {
  //          frm_elements[i].checked = false;
  //      }
  //      break;
  //    case "file":
  //     if(frm_elements[i].options) {
  //     frm_elements[i].options= false;
  //     }
  //    default:
  //        break;
  //     } // /switch
  // } // for
}
