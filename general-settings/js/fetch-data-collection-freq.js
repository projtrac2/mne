var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-data-collection-freq-items",
    order: [], 
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });
 
  // submit title form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var frequency = $("#frequency").val();
    var days = $("#days").val();
    var newitem = $("#newitem").val();

    if (frequency == "") {
      $("#frequency").after(
        '<p class="text-danger">Frequency field is required</p>'
      );
      $("#frequency")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#frequency")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#frequency")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (days == "") {
      $("#days").after(
        '<p class="text-danger">Days Field is required</p>'
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
    } 

    if (frequency && days) {
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
            // reload the titles table 
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
  }); // /submit title form

  // add title modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // title form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add title modal btn clicked

  // remove title
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
      url: "general-settings/selected-items/fetch-selected-data-collection-freq-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // title id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.fqid +
            '" />'
        );

        // title name
        $("#editfrequency").val(response.frequency);
        $("#editdays").val(response.days);
        // status
        $("#editStatus").val(response.status);

        // update the title data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation
            var frequency = $("#editfrequency").val();
            var days = $("#editdays").val();
            var itemStatus = $("#editStatus").val();

            if (frequency == "") {
              $("#editfrequency").after(
                '<p class="text-danger">Frequency field is required</p>'
              );
              $("#editfrequency")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editfrequency")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editfrequency")
                .closest(".form-input")
                .addClass("has-success");
            } 

            if (days == "") {
              $("#editdays").after(
                '<p class="text-danger">Days field is required</p>'
              );
              $("#editdays")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editdays")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editdays")
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

            if (frequency && days  && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-data-collection-frequency-action",
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
          }); // update the title data function
      } // /success function
    }); // /ajax to fetch title image
  } else {
    alert("error please refresh the page");
  }
} // /edit title function

// remove title
function removeItem(itemId = null) {
  if (itemId) {
    // remove title button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-data-collection-frequency-action",
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
        }); // /ajax fucntion to remove the title
        return false;
      }); // /remove title btn clicked
  } // /if titleid
} // /remove title function

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
