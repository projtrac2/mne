var manageItemTable;

$(document).ready(function () {
  // top nav bar 
  $("#navProduct").addClass("active");
  // manage Donation data table
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-designation-items",
    order: [],
    'columnDefs': [{
      'targets': [3],
      'orderable': false,
    }]
  });

  $("#submitItemForm").on("submit", function (event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var designation = $("#designation").val();
    var reporting = $("#reporting").val();
    var level = $("#level").val();
    var newitem = $("#newitem").val();

    if (designation == "") {
      $("#designation").after(
        '<p class="text-danger">Designation field is required</p>'
      );
      $("#designation")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#designation")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#designation")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (reporting == "") {
      $("#reporting").after(
        '<p class="text-danger">Reporting field is required</p>'
      );
      $("#reporting")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#reporting")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#reporting")
        .closest(".form-input")
        .addClass("has-success");
    } // /else

    if (level == "") {
      $("#level").after(
        '<p class="text-danger">Access Level field is required</p>'
      );
      $("#level")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#level")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#level")
        .closest(".form-input")
        .addClass("has-success");
    } // /else


    if (designation && reporting && level) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/designation-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function (response) {
          if (response) {
            $("#submitItemForm")[0].reset();

            swal("Record Successfully Saved");

            $(".modal").each(function () {
              $(this).modal("hide");
            });

            // reload the manage student table
            setTimeout(() => { 
              location.reload(true);
            }, 3000);
          } else{
            $("#submitItemForm")[0].reset();

            swal("Error !!!");

            $(".modal").each(function () {
              $(this).modal("hide");
            });

            // reload the manage student table
            setTimeout(() => { 
              location.reload(true);
            }, 3000);
          }
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok

    return false;
  }); // /submit Donation form

  // add Donation modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function () {
      // // Donation form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add Donation modal btn clicked

  // remove Donation
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
      url: "general-settings/selected-items/fetch-selected-designation-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function (response) {
        // modal div
        $(".div-result").removeClass("div-hide");

        // Donation id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
          response.moid +
          '" />'
        );
        // Donation name
        $("#editDesignation").val(response.designation);
        // quantity
        $("#editReporting").val(response.Reporting);
        $("#editLevel").val(response.level);

        // update the Donation data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function () {
            // form validation
            var designation = $("#editDesignation").val();
            var reporting = $("#editReporting").val();
            var level = $("#editLevel").val();

            if (designation == "") {
              $("#editDesignation").after(
                '<p class="text-danger">Designation field is required</p>'
              );
              $("#editDesignation")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editDesignation")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editDesignation")
                .closest(".form-input")
                .addClass("has-success");
            } // /else


            if (reporting == "") {
              $("#editReporting").after(
                '<p class="text-danger">Reporting field is required</p>'
              );
              $("#editReporting")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editReporting")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editReporting")
                .closest(".form-input")
                .addClass("has-success");
            } // /
            
            if (level == "") {
              $("#editLevel").after(
                '<p class="text-danger">Reporting field is required</p>'
              );
              $("#editLevel")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editLevel")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editLevel")
                .closest(".form-input")
                .addClass("has-success");
            } // /else

            console.log(designation);

            if (designation && reporting && level ) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/designation-action",
                type: form.attr("method"),
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                  if (response) {
                    // submit loading button
                    $("#editProductBtn").button("reset"); 

        
                    $(".modal").each(function () {
                      $(this).modal("hide");
                    });
        
                    swal("Successfully edit the designation !!!");
                    // reload the manage student table
                    setTimeout(() => { 
                      location.reload(true);
                    }, 3000);

                    $(".modal").each(function () {
                      $(this).modal("hide");
                    });
                  }else{
                    swal("Error editing designation!!!");
                    // reload the manage student table
                    setTimeout(() => { 
                      location.reload(true);
                    }, 3000);
                  }
                } // /success function
              }); // /ajax function
            } // /if validation is ok

            return false;
          }); // update the Donation data function
      } // /success function
    }); // /ajax to fetch Donation image
  } else { 
    swal("Error editing designation!!!");
    // reload the manage student table
    setTimeout(() => { 
      location.reload(true);
    }, 3000);
  }
} // /edit Donation function

// remove Donation
function removeItem(itemId = null) {
  if (itemId) {
    // remove Donation button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/designation-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage student table  
              swal(response.messages);
              // reload the manage student table
              setTimeout(() => { 
                location.reload(true);
              }, 3000);
              $(".modal").each(function () {
                $(this).modal("hide");
              });              
            } else { 
              swal(response.messages);
              // reload the manage student table
              setTimeout(() => { 
                location.reload(true);
              }, 3000);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Donation
        return false;
      }); // /remove Donation btn clicked
  } // /if Donationid
} // /remove Donation function