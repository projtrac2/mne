var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-currency-items",
    order: [], 
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });

  // submit currency form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();
 
    // form validation
    var currency = $("#currency").val();
    var code = $("#code").val();
    var sympol = $("#sympol").val();
    var newitem = $("#newitem").val();

    if (currency == "") {
      $("#currency").after(
        '<p class="text-danger">Currency is required</p>'
      );
      $("#currency")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#currency")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#currency")
        .closest(".form-input")
        .addClass("has-success");
    } 



    if (code == "") {
      $("#code").after(
        '<p class="text-danger">Currency Code is required</p>'
      );
      $("#code")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#code")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#code")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (sympol == "") {
      $("#sympol").after(
        '<p class="text-danger">Currency Sympol is required</p>'
      );
      $("#sympol")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#sympol")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#sympol")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (currency && code && sympol ) {
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
            // reload the currency table 
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
  }); // /submit currency form

  // add currency modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // currency form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add currency modal btn clicked

  // remove currency
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
      url: "general-settings/selected-items/fetch-selected-currency-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // currency id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // currency name
        $("#editcode").val(response.code);
        $("#editcurrency").val(response.currency);
        $("#editsympol").val(response.sympol);
        // status
        $("#editStatus").val(response.active);

        // update the currency data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation
            var code = $("#editcode").val();
            var currency = $("#editcurrency").val();
            var sympol = $("#editsympol").val();
            var itemStatus = $("#editStatus").val();

            if (code == "") {
              $("#editcode").after(
                '<p class="text-danger">Currency Code field is required</p>'
              );
              $("#editcode")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editcode")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editcode")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (sympol == "") {
              $("#editsympol").after(
                '<p class="text-danger">Currency sympol field is required</p>'
              );
              $("#editsympol")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editsympol")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editsympol")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (currency == "") {
              $("#editcurrency").after(
                '<p class="text-danger">Currency field is required</p>'
              );
              $("#editcurrency")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editcurrency")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editcurrency")
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

            if (code && currency && sympol && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-currency-action",
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
          }); // update the currency data function
      } // /success function
    }); // /ajax to fetch currency image
  } else {
    alert("error please refresh the page");
  }
} // /edit currency function

// remove currency
function removeItem(itemId = null) {
  if (itemId) {
    // remove currency button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-currency-action",
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
        }); // /ajax fucntion to remove the currency
        return false;
      }); // /remove currency btn clicked
  } // /if currencyid
} // /remove currency function

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
