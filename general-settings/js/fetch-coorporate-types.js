var manageItemTable;

$(document).ready(function() {
  // manageCooperates Typesdata table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-coorporate-types-items",
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
    var type = $("#type").val();
    var description = $("#description").val();
    var newitem = $("#newitem").val();

    if (type == "") {
      $("#type").after(
        '<p class="text-danger">Evaluation Type field is required</p>'
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
        '<p class="text-danger">Evaluation Type Description field is required</p>'
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
        url: "general-settings/action/project-coorporate-types-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the manageCooperates Typestable
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
  }); // /submitCooperates Typesform

  // addCooperates Typesmodal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // //Cooperates Typesform reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /addCooperates Typesmodal btn clicked

  // remove Contractor Business Type
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
      url: "general-settings/selected-items/fetch-selected-coorporate-types-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        console.log(response);
        // modal div
        $(".div-result").removeClass("div-hide");

        //Cooperates Typesid
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );
        //Cooperates Typesname
        $("#editType").val(response.type);
        // quantity
        $("#editDescription").val(response.description);
        // status
        $("#editStatus").val(response.active);

        // update theCooperates Typesdata function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function() {
            // form validation
            var type = $("#editType").val();
            var description = $("#editDescription").val();
            var itemStatus = $("#editStatus").val();

            if (type == "") {
              $("#editType").after(
                '<p class="text-danger">Evaluation Type field is required</p>'
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
                '<p class="text-danger">Evaluation Type Description field is required</p>'
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

            if (type && description && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-coorporate-types-action",
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

                    // reload the manageCooperates Typestable
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
          }); // update theCooperates Typesdata function
      } // /success function
    }); // /ajax to fetchCooperates Typesimage
  } else {
    alert("error please refresh the page");
  }
} // /editCooperates Typesfunction

// remove Contractor Business Type
function removeItem(itemId = null) {
  if (itemId) {
    // removeCooperates Typesbutton clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-coorporate-types-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manageCooperates Typestable
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Contractor Business Type
        return false;
      }); // /removeCooperates Typesbtn clicked
  } // /if Contractor Business Typeid
} // /removeCooperates Typesfunction

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
