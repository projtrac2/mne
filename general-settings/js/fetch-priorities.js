var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-priorities-items",
    order: [], 
    'columnDefs': [{
      'targets': [5],
      'orderable': false,
    }]
  });

  // submit priority form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var newitem = $("#newitem").val();
    var weight = $("#weight").val();
    var description = $("#description").val();
    var priority = $("#priority").val();

    if (priority == "") {
      $("#priority").after(
        '<p class="text-danger">The priority field is required</p>'
      );
      $("#priority")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#priority")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#priority")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Priority Description field is required</p>'
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

    if (weight == "") {
      $("#weight").after(
        '<p class="text-danger">Priority weight field is required</p>'
      );
      $("#weight")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#weight")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#weight")
        .closest(".form-input")
        .addClass("has-success");
    } 
    if (weight && priority && description ) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-priorities-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the prioritys table 
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
  }); // /submit priority form

  // add priority modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // priority form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add priority modal btn clicked

  // remove priority
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
      url: "general-settings/selected-items/fetch-selected-priorities-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // priority id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // priority name
        $("#editweight").val(response.weight);
        $("#editdescription").val(response.description);
        $("#editpriority").val(response.priority);
        // status
        $("#editStatus").val(response.status);

        // update the priority data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation
            var weight = $("#editweight").val();
            var description = $("#editdescription").val();
            var priority = $("#editpriority").val();
            var itemStatus = $("#editStatus").val();

            if (priority == "") {
              $("#editpriority").after(
                '<p class="text-danger">Priority Name field is required</p>'
              );
              $("#editpriority")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editpriority")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editpriority")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (description == "") {
              $("#editdescription").after(
                '<p class="text-danger">Priority Description field is required</p>'
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
            } 

            if (weight == "") {
              $("#editTitle").after(
                '<p class="text-danger">Priority weight field is required</p>'
              );
              $("#editweight")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editweight")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editweight")
                .closest(".form-input")
                .addClass("has-success");
            } 
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

            if (weight && priority && description  && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);
              $.ajax({
                url: "general-settings/action/project-priorities-action",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response) {
                    // submit loading button
                    $("#edittitleBtn").button("reset");
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
          }); // update the priority data function
      } // /success function
    }); // /ajax to fetch priority image
  } else {
    alert("error please refresh the page");
  }
} // /edit priority function

// remove priority
function removeItem(itemId = null) {
  if (itemId) {
    // remove priority button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-priorities-action",
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
        }); // /ajax fucntion to remove the priority
        return false;
      }); // /remove priority btn clicked
  } // /if priorityid
} // /remove priority function

function clearForm(oForm) {
  // var frm_elements = oForm.elements;
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
