var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-project-status-items",
    order: [], 
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });

  // submit projstatus form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var projstatus = $("#projstatus").val();
    var newitem = $("#newitem").val();

    if (projstatus == "") {
      $("#projstatus").after(
        '<p class="text-danger">Project Status is required</p>'
      );
      $("#projstatus")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#projstatus")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#projstatus")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (projstatus) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-status-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the projstatuss table 
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
  }); // /submit projstatus form

  // add projstatus modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // projstatus form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add projstatus modal btn clicked

  // remove projstatus
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
      url: "general-settings/selected-items/fetch-selected-project-status-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // projstatus id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.statusid +
            '" />'
        );

        // projstatus name
        $("#editprojstatus").val(response.statusname);
        // projstatus level
        $("#editstatuslevel").val(response.level);
        // status
        $("#editStatus").val(response.active);

        // update the projstatus data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation
            var projstatus = $("#editprojstatus").val();
            var statuslevel = $("#editstatuslevel").val();
            var itemStatus = $("#editStatus").val();

            if (projstatus == "") {
              $("#editprojstatus").after(
                '<p class="text-danger">Project Status Name field is required</p>'
              );
              $("#editprojstatus")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editprojstatus")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editprojstatus")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (statuslevel == "") {
              $("#editstatuslevel").after(
                '<p class="text-danger">Project Status Level field is required</p>'
              );
              $("#editstatuslevel")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editstatuslevel")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editstatuslevel")
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

            if (projstatus && statuslevel && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-status-action",
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
          }); // update the projstatus data function
      } // /success function
    }); // /ajax to fetch projstatus image
  } else {
    alert("error please refresh the page");
  }
} // /edit projstatus function

// remove projstatus
function removeItem(itemId = null) {
  if (itemId) {
    // remove projstatus button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-status-action",
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
        }); // /ajax fucntion to remove the projstatus
        return false;
      }); // /remove projstatus btn clicked
  } // /if projstatusid
} // /remove projstatus function

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
