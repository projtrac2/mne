var manageItemTable;

$(document).ready(function() {
  // top nav bar 
  $("#navProduct").addClass("active");
  // manage Leave Categories data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-employee-leave-categories-items",
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
    var name = $("#name").val();
    var days = $("#days").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (name == "") {
      $("#name").after(
        '<p class="text-danger">Leave Name field is required</p>'
      );
      $("#name")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#name")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#name")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Leave  Description field is required</p>'
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

    if (days == "") {
      $("#days").after(
        '<p class="text-danger">Leave days field is required</p>'
      );
      $("#days")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#days")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#days")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (name && description && days) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-employee-leave-categories-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();

            // reload the Leave Categories table
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
  }); // /submit Leave Categories form

  // add Leave Categories modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // Leave Categories form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Leave Categories modal btn clicked

  // remove Leave Categories
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
      url: "general-settings/selected-items/fetch-selected-employee-leave-categories-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // Leave Categories id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        // Leave Categories name
        $("#editName").val(response.leavename);
        $("#editDescription").val(response.description);
        $("#editdays").val(response.days);
        // status
        $("#editStatus").val(response.status);

        // update the Leave Categories data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var name = $("#editName").val();
            var days = $("#editdays").val();
            var description = $("#editDescription").val();
            var itemStatus = $("#editStatus").val();

            if (name == "") {
              $("#editName").after(
                '<p class="text-danger">Leave Category Name field is required</p>'
              );
              $("#editName")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editName")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editName")
                .closest(".form-input")
                .addClass("has-success");
            } // /else


            if (days == "") {
              $("#days").after(
                '<p class="text-danger">Leave days field is required</p>'
              );
              $("#days")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#days")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#days")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            if (description == "") {
              $("#editDescription").after(
                '<p class="text-danger">Leave Category Description field is required</p>'
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

            if (name && description && days && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-employee-leave-categories-action",
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

                    // reload the Leave Categories table
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
          }); // update the Leave Categories data function
      } // /success function
    }); // /ajax to fetch Leave Categories image
  } else {
    alert("error please refresh the page");
  }
} // /edit Leave Categories function

// remove Leave Categories
function removeItem(itemId = null) {
  if (itemId) {
    // remove Leave Categories button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-employee-leave-categories-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the Leave Categories table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Leave Categories
        return false;
      }); // /remove Leave Categories btn clicked
  } // /if Leave Categoriesid
} // /remove Leave Categories function

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
