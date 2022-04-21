var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-fiscal-year-items",
    order: [], 
    'columnDefs': [{
      'targets': [6],
      'orderable': false,
    }]
  });

  // submit title form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var fscyear = $("#fscyear").val();
    var year = $("#year").val();
    var sdate = $("#sdate").val();
    var edate = $("#edate").val();
    var newitem = $("#newitem").val();

    if (fscyear == "") {
      $("#fscyear").after(
        '<p class="text-danger"> Financil Year is required</p>'
      );
      $("#fscyear")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#fscyear")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#fscyear")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (year == "") {
      $("#year").after(
        '<p class="text-danger"> Start Year is required</p>'
      );
      $("#year")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#year")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#year")
        .closest(".form-input")
        .addClass("has-success");
    } 
    if (sdate == "") {
      $("#sdate").after(
        '<p class="text-danger">End Date field is required</p>'
      );
      $("#sdate")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#sdate")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#sdate")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (edate == "") {
      $("#edate").after(
        '<p class="text-danger">End Date field is required</p>'
      );
      $("#edate")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#edate")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#edate")
        .closest(".form-input")
        .addClass("has-success");
    } 



    if (year && fscyear && sdate && edate) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-fiscal-year-action",
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
      url: "general-settings/selected-items/fetch-selected-fiscal-year-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // title id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // title name
        $("#editfscyear").val(response.year);
        $("#edityear").val(response.yr);
        $("#editsdate").val(response.sdate);
        $("#editedate").val(response.edate);
        // status
        $("#editStatus").val(response.status);

        // update the title data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation\
            var itemStatus = $("#editStatus").val();
    // form validation
    var fscyear = $("#editfscyear").val();
    var year = $("#edityear").val();
    var sdate = $("#editsdate").val();
    var edate = $("#editedate").val(); 
    if (fscyear == "") {
      $("#editfscyear").after(
        '<p class="text-danger">Financial Year field  is required</p>'
      );
      $("#editfscyear")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#editfscyear")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#editfscyear")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (year == "") {
      $("#edityear").after(
        '<p class="text-danger"> Start Year field  is required</p>'
      );
      $("#edityear")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#edityear")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#edityear")
        .closest(".form-input")
        .addClass("has-success");
    } 
    if (sdate == "") {
      $("#editsdate").after(
        '<p class="text-danger">Start Date field is required</p>'
      );
      $("#editsdate")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#editsdate")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#editsdate")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (edate == "") {
      $("#editedit").after(
        '<p class="text-danger">End Date field  is required</p>'
      );
      $("#editedate")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#editedate")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#editedate")
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

            if (year && fscyear && sdate && edate && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-fiscal-year-action",
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
          url: "general-settings/action/project-fiscal-year-action",
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
