var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-countries-items",
    order: [], 
    'columnDefs': [{
      'targets': [5],
      'orderable': false,
    }]
  });

  // submit countries  form
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();
    // form validation
    var country = $("#country").val(); 
    var isocode = $("#isocode").val(); 
    var code = $("#code").val(); 
    var newitem = $("#newitem").val();

    if (country == "") {
      $("#country").after(
        '<p class="text-danger">Country Name is required</p>'
      );
      $("#country")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#country")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#country")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (isocode == "") {
      $("#isocode").after(
        '<p class="text-danger">Country ISO Code required</p>'
      );
      $("#isocode")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#isocode")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#isocode")
        .closest(".form-input")
        .addClass("has-success");
    } 



    if (code == "") {
      $("#code").after(
        '<p class="text-danger">Country Code is required</p>'
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

    if (value == "") {
      $("#value").after(
        '<p class="text-danger">Country Value is required</p>'
      );
      $("#value")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#value")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#value")
        .closest(".form-input")
        .addClass("has-success");
    } 



    if (country && isocode && code && value) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-countries-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the countries s table 
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
  }); // /submit countries  form

  // add countries  modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // countries  form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add countries  modal btn clicked

  // remove countries 
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
      url: "general-settings/selected-items/fetch-selected-countries-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        //  id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // countries  name
        $("#editcountry").val(response.country);
        $("#editisocode").val(response.iso_code);
        $("#editvalue").val(response.value);
        $("#editcode").val(response.country_code); 
        // status
        $("#editStatus").val(response.status);

        // update the countries  data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();

            // form validation
            var country = $("#editcountry").val();
            var isocode = $("#editisocode").val();
            var code = $("#editcode").val();
            var value = $("#editvalue").val();
            var itemStatus = $("#editStatus").val();

            if (country == "") {
              $("#editcountry").after(
                '<p class="text-danger">Country Name field is required</p>'
              );
              $("#editcountry")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editcountry")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editcountry")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (isocode == "") {
              $("#editisocode").after(
                '<p class="text-danger">Country ISO Code field is required</p>'
              );
              $("#editisocode")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editisocode")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editisocode")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (value == "") {
              $("#editvalue").after(
                '<p class="text-danger">Country Value field is required</p>'
              );
              $("#editvalue")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editvalue")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editvalue")
                .closest(".form-input")
                .addClass("has-success");
            } 

            
            if (code == "") {
              $("#editeditcode").after(
                '<p class="text-danger">Country Code field is required</p>'
              );
              $("#editeditcode")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editeditcode")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editeditcode")
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

            if (country && isocode && code && value  && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-countries-action",
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
                    // reload the manage countries table
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
          }); // update the manage countries  data function
      } // /success function
    }); // /ajax to fetch manage countries  image
  } else {
    alert("error please refresh the page");
  } 
} // /edit manage countries  function
 
// remove manage countries 
function removeItem(itemId = null) {
  if (itemId) {
    // remove manage countries  button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-countries-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage countries table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the manage countries 
        return false;
      }); // /remove manage countries  btn clicked
  } // /if manage countries id
} // /remove manage countries  function

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
