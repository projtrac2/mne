var manageItemTable;

$(document).ready(function() {
  // top nav bar
  $("#navProduct").addClass("active");
  // manage product data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-tender-category-items",
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
    var category = $("#category").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();
    if (category == "") {
      $("#category").after(
        '<p class="text-danger">Tender category field is required</p>'
      );
      $("#category")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#category")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#category")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Tender Category Description field is required</p>'
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
    
    if (category && description) {
      var form = $(this);
      var formData = new FormData(this);
      $.ajax({
        url: "general-settings/action/project-tender-category-action",
        type: "post",
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
      url: "general-settings/selected-items/fetch-selected-tender-category-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        console.log(response);
        // modal div
        $(".div-result").removeClass("div-hide");

        // product id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        // product name
        $("#editCategory").val(response.category);
        // quantity
        $("#editDescription").val(response.description);
        // status
        $("#editStatus").val(response.status);

        // update the product data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var category = $("#editCategory").val();
            var description = $("#editDescription").val();
            var itemStatus = $("#editStatus").val();

            if (category == "") {
              $("#editCategory").after(
                '<p class="text-danger">Tender  Category field is required</p>'
              );
              $("#editCategory")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editCategory")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editCategory")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (description == "") {
              $("#editDescription").after(
                '<p class="text-danger">Evaluation category Description field is required</p>'
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

            if (category && description && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-tender-category-action",
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
          url: "general-settings/action/project-tender-category-action",
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
