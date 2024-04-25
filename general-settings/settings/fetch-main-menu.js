var manageItemTable;

$(document).ready(function() {
  // manage Project Main Menu  data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-main-menu-items",
    order: [], 
    'columnDefs': [{
      'targets': [6],
      'orderable': false, 
    }]
  });

  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var name = $("#name").val();
    var icons = $("#icons").val();
    var url = $("#url").val();
    var parent = $("#parent").val();
    var newitem = $("#newitem").val();

    if (name == "") {
        $("#name").after(
        '<p class="text-danger">Project Menu Name field is required</p>'
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

    if (parent == "") {
        $("#parent").after(
        '<p class="text-danger">Project Menu Parent field is required</p>'
        );
        $("#parent")
        .closest(".form-input")
        .addClass("has-error");
    } else {
        // remov error text field
        $("#parent")
        .find(".text-danger")
        .remove();
        // success out for form
        $("#parent")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (icons == "") {
        $("#icons").after(
            '<p class="text-danger">Project Menu Icons field is required</p>'
        );
        $("#icons")
            .closest(".form-input")
            .addClass("has-error");
        } else {
        // remov error text field
        $("#icons")
            .find(".text-danger")
            .remove();
        // success out for form
        $("#icons")
            .closest(".form-input")
            .addClass("has-success");
        } // /else

        if (url == "") {
        $("#url").after(
            '<p class="text-danger">Project URL field is required</p>'
        );
        $("#url")
            .closest(".form-input")
            .addClass("has-error");
        } else {
        // remov error text field
        $("#url")
            .find(".text-danger")
            .remove();
        // success out for form
        $("#url")
            .closest(".form-input")
            .addClass("has-success");
        } // /else
        
    if (parent && name && icons && url) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-main-menu-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset(); 
            $(".modal").each(function() {
              $(this).modal("hide");
            });
            swal('Record Successfully deleted');
            setTimeout(() => { 
              location.reload(true);
            }, 3000); 
          } // /if response.success
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok
    return false;
  }); // /submit Project Main Menu  form

  // add Project Main Menu  modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
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
      url: "general-settings/selected-items/fetch-selected-main-menu-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // Project Main Menu  id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        $("#editparent").val(response.parent);
        $("#editname").val(response.Name);
        $("#editicons").val(response.icons);
        $("#editurl").val(response.url);
        $("#editStatus").val(response.status);

        // update the Project Main Menu  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            // form validation
            e.preventDefault();
        var editname = $("#editname").val();
        var editicons = $("#editicons").val();
        var editurl = $("#editurl").val();
        var editparent = $("#editparent").val();
        var itemStatus = $("#editStatus").val();
    if (editname == "") {
        $("#editname").after(
        '<p class="text-danger">Project Menu Name field is required</p>'
        );
        $("#editname")
        .closest(".form-input")
        .addClass("has-error");
    } else {
        // remov error text field
        $("#editname")
        .find(".text-danger")
        .remove();
        // success out for form
        $("#editname")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (editparent == "") {
        $("#editparent").after(
        '<p class="text-danger">Project Menu Parent field is required</p>'
        );
        $("#editparent")
        .closest(".form-input")
        .addClass("has-error");
    } else {
        // remov error text field
        $("#editparent")
        .find(".text-danger")
        .remove();
        // success out for form
        $("#editparent")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (editicons == "") {
        $("#editicons").after(
            '<p class="text-danger">Project Menu Icons field is required</p>'
        );
        $("#editicons")
            .closest(".form-input")
            .addClass("has-error");
    } else {
    // remov error text field
    $("#editicons")
        .find(".text-danger")
        .remove();
    // success out for form
    $("#editicons")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

        if (editurl == "") {
        $("#editurl").after(
            '<p class="text-danger">Project URL field is required</p>'
        );
        $("#editurl")
            .closest(".form-input")
            .addClass("has-error");
        } else {
        // remov error text field
        $("#editurl")
            .find(".text-danger")
            .remove();
        // success out for form
        $("#editurl")
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

        if (editparent && editname && editicons && editStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-main-menu-action",
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
 
                    $(".modal").each(function() {
                      $(this).modal("hide");
                    });
                    swal('Record Successfully Saved');
                    setTimeout(() => { 
                      location.reload(true);
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
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-main-menu-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) { 
              swal('Record Successfully updated');
              setTimeout(() => { 
                location.reload(true);
              }, 3000); 
              $(".modal").each(function() {
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
