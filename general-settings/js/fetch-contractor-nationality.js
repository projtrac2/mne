var manageItemTable;

$(document).ready(function() {
  // manage Contractor Nationality data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-contractor-nationality-items",
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
    var nationality = $("#nationality").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (nationality == "") {
      $("#nationality").after(
        '<p class="text-danger">Contractor Nationality field is required</p>'
      );
      $("#nationality")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#nationality")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#nationality")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Contractor Nationality Description field is required</p>'
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

    if (nationality && description) {
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
            // reload the manage Contractor Nationality table
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
  }); // /submit Contractor Nationality form

  // add Contractor Nationality modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // Contractor Nationality form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Contractor Nationality modal btn clicked

  // remove Contractor Nationality
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
      url: "general-settings/selected-items/fetch-selected-contractor-nationality-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        //console.log(response);
        // modal div
        $(".div-result").removeClass("div-hide");

        // Contractor Nationality id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        // Contractor Business nationality name
        $("#editNationality").val(response.nationality);
        // quantity
        $("#editDescription").val(response.description);
        // status
        $("#editStatus").val(response.active);

        // update the Contractor Nationality data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var nationality = $("#editNationality").val();
            var description = $("#editDescription").val();
            var itemStatus = $("#editStatus").val();

            if (nationality == "") {
              $("#editNationality").after(
                '<p class="text-danger">Contractor Nationality field is required</p>'
              );
              $("#editNationality")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editNationality")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editNationality")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (description == "") {
              $("#editDescription").after(
                '<p class="text-danger">Contractor Nationality Description field is required</p>'
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

            if (nationality && description && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response) {
                    // submit loading button
                    $("#editProductBtn").button("reset");

                    // reload the manage Contractor Nationality table
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
          }); // update the Contractor Nationality data function
      } // /success function
    }); // /ajax to fetch Contractor Nationality image
  } else {
    alert("error please refresh the page");
  }
} // /edit Contractor Nationality function

// remove Contractor Nationality
function removeItem(itemId = null) {
  if (itemId) {
    // remove Contractor Nationality button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-contractor-nationality-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage Contractor Nationality table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Contractor Nationality
        return false;
      }); // /remove Contractor Nationality btn clicked
  } // /if Contractor Nationalityid
} // /remove Contractor Nationality function

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
