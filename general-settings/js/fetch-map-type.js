var manageItemTable;

$(document).ready(function() {
  // top nav bar
  $("#navProduct").addClass("active");
  // manage product data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-map-type-items.php",
    order: [], 
    'columnDefs': [{
      'targets': [5],
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
        '<p class="text-danger">Map Type field is required</p>'
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
        '<p class="text-danger">Map Type Description field is required</p>'
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
            // reload the manage student table
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
  }); // /submit product form

  // add product modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // product form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add product modal btn clicked

  // remove product
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
      url: "general-settings/selected-items/fetch-selected-map-type-item.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // product id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        // product name
        $("#editType").val(response.type);
        // quantity
        $("#editDescription").val(response.description);
        // status
        $("#editStatus").val(response.status);

        // update the product data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var type = $("#editType").val();
            var description = $("#editDescription").val();

            if (type == "") {
              $("#editType").after(
                '<p class="text-danger">Map Type field is required</p>'
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
                '<p class="text-danger">Map Type Description field is required</p>'
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
                url: "general-settings/action/project-map-type-action.php",
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

                    // reload the manage student table
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
          }); // update the product data function
      } // /success function
    }); // /ajax to fetch product image
  } else {
    alert("error please refresh the page");
  }
} // /edit product function

// remove product
function removeItem(itemId = null) {
  if (itemId) {
    // remove product button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-map-type-action.php",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage student table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the product
        return false;
      }); // /remove product btn clicked
  } // /if productid
} // /remove product function

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

function disable(id, name, action) {
  swal({
    title: "Are you sure?",
    text: `You want to  ${action} priority ${name}!`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willUpdate) => {
    if (willUpdate) {
      $.ajax({
        type: "post",
        url: 'general-settings/action/project-map-type-action.php',
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