var manageItemTable;

$(document).ready(function () {
  $("#navtitle").addClass("active");
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-counties-items",
    order: [],
    'columnDefs': [{
      'targets': [5],
      'orderable': false,
    }]
  });

  // submit title form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function (event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var code = $("#code").val();
    var name = $("#name").val();
    var size = $("#size").val();
    var newitem = $("#newitem").val();

    if (code == "") {
      $("#code").after(
        '<p class="text-danger">Project title is required</p>'
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

    if (name == "") {
      $("#name").after(
        '<p class="text-danger">County Name is required</p>'
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
    }

    if (size == "") {
      $("#size").after(
        '<p class="text-danger">County Size is required</p>'
      );
      $("#size")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#size")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#size")
        .closest(".form-input")
        .addClass("has-success");
    }

    if (code && name && size) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url:"general-settings/action/project-counties-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function (response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the titles table 
            manageItemTable.ajax.reload(null, true);
            alert("Record Successfully Saved");
            $(".modal").each(function () {
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
    .bind("click", function () {
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
      url: "general-settings/selected-items/fetch-selected-counties-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function (response) {
        $(".div-result").removeClass("div-hide");
        // title id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
          response.id +
          '" />'
        );

        // title name
        $("#editcode").val(response.code);
        $("#editsize").val(response.size);
        $("#editname").val(response.name);
        // status
        $("#editStatus").val(response.status);

        // update the title data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function (e) {
            e.preventDefault();
            // form validation
            var code = $("#editcode").val();
            var size = $("#editsize").val();
            var name = $("#editname").val();
            var itemStatus = $("#editStatus").val();

            if (code == "") {
              $("#editcode").after(
                '<p class="text-danger">County Code field is required</p>'
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

            if (size == "") {
              $("#editsize").after(
                '<p class="text-danger">County Size field is required</p>'
              );
              $("#editsize")
                .closest(".form-input")
                .addClass("has-error");
            } else {
              // remov error text field
              $("#editsize")
                .find(".text-danger")
                .remove();
              // success out for form
              $("#editsize")
                .closest(".form-input")
                .addClass("has-success");
            }

            if (name == "") {
              $("#editname").after(
                '<p class="text-danger">County Name field is required</p>'
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

            if (code && name && size && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "general-settings/action/project-counties-action",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                  if (response) {
                    // submit loading button
                    $("#edittitleBtn").button("reset");
                    // reload the manage student table
                    manageItemTable.ajax.reload(null, true);
                    alert(response.messages);
                    $(".modal").each(function () {
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
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-counties-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage student table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function () {
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