var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-financial-lines.php",
    order: [], 
    'columnDefs': [{
      'targets': [4],
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
    var description = $("#description").val();
    var name = $("#name").val();

    if (name == "") {
      $("#name").after(
        '<p class="text-danger">Line name field is required</p>'
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

    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Line Description field is required</p>'
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
 
    if (name && description ) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-financial-lines-action.php",
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
      url: "general-settings/selected-items/fetch-selected-financial-line.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // line id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // line name
        $("#editdescription").val(response.description);
        $("#editname").val(response.name);
        // status
        $("#editGroup").val(response.grp);

        // update the line data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
            // form validation
            var description = $("#editdescription").val();
            var name = $("#editname").val();

            if (name == "") {
              $("#editname").after(
                '<p class="text-danger">Line Name field is required</p>'
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

            if (description == "") {
              $("#editdescription").after(
                '<p class="text-danger">Line Description field is required</p>'
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
 

            if (name && description) {
              var form = $(this);
              var formData = new FormData(this);
              $.ajax({
                url: "general-settings/action/project-financial-lines-action.php",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response.success) {
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
          url: "general-settings/action/project-financial-lines-action.php",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage financial line table
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

function disable(id, name, action) {
  swal({
    title: "Are you sure?",
    text: `You want to ${action} ${name}!`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willUpdate) => {
    if (willUpdate) {
      $.ajax({
        type: "post",
        url: 'general-settings/action/project-financial-lines-action.php',
        data: {
          deleteItem: "deleteItem",
          itemId: id,
        },
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response == true) {
            swal({
              title: "Notification !",
              text: `Successfully ${status}`,
              icon: "success",
            });
          } else {
            swal({
              title: "Notification !",
              text: `Error ${status}`,
              icon: "error",
            });
          }
          setTimeout(function () {
            window.location.reload(true);
          }, 3000);
        }
      });
    } else {
      swal("You cancelled the action!");
    }
  })
}
