var manageItemTable;

$(document).ready(function() {
  // manage Project Funding Types  data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-issue-severity-items",
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
    var name = $("#name").val();
    var score = $("#score").val();
    var newitem = $("#newitem").val();

    if (name == "") {
      $("#name").after(
        '<p class="text-danger">Project Severity Name field is required</p>'
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

    if (score == "") {
      $("#score").after(
        '<p class="text-danger">Project Issue Severity Score field is required</p>'
      );
      $("#score")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#score")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#score")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (name && score) {
      var form = $(this);
      var formData = new FormData(this);
      console.log(formData);
      $.ajax({
        url: "general-settings/action/project-issue-severity-action",
        type: "POST",
        data: form_data,
        dataType: "json",
        success: function(response) {
          console.log(response);
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the manage Project Funding Types  table
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
  }); // /submit Project Funding Types  form

  // add Project Funding Types  modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // Project Funding Types  form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Project Funding Types  modal btn clicked

  // remove Project Funding Types 
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
      url: "general-settings/selected-items/fetch-selected-issue-severity-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        console.log(response);
        // modal div
        $(".div-result").removeClass("div-hide");

        // Project Funding Types  id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        $("#editname").val(response.name);
        $("#editscore").val(response.score);
        $("#editStatus").val(response.status);

        // update the Project Funding Types  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var name = $("#editname").val();
            var score = $("#editscore").val();
            var itemStatus = $("#editStatus").val();

            if (type == "") {
              $("#editname").after(
                '<p class="text-danger">Project Severity Name field is required</p>'
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

            if (score == "") {
              $("#editscore").after(
                '<p class="text-danger">Project Severity score field is required</p>'
              );
              $("#editscore")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editscore")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editscore")
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

            if (name && score && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-issue-severity-action",
                type: form.attr("name"),
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response) {
                    // submit loading button
                    $("#editProductBtn").button("reset");

                    // reload the manage Project Funding Types  table
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
          }); // update the Project Funding Types  data function
      } // /success function
    }); // /ajax to fetch Project Funding Types  image
  } else {
    alert("error please refresh the page");
  }
} // /edit Project Funding Types  function

// remove Project Funding Types 
function removeItem(itemId = null) {
  if (itemId) {
    // remove Project Funding Types  button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-issue-severity-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage Project Funding Types  table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Project Funding Types 
        return false;
      }); // /remove Project Funding Types  btn clicked
  } // /if Project Funding Types id
} // /remove Project Funding Types  function

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
