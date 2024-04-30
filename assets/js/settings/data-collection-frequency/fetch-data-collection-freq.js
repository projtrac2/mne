var manageItemTable;

$(document).ready(function () {
  $("#navtitle").addClass("active");
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: "ajax/settings/data-collection-frequency/fetch-selected-data-collection-freq-items.php",
    order: [],
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
  });

  // submit title form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function (event) {
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
      url: "ajax/settings/data-collection-frequency/fetch-selected-data-collection-freq-item.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function (response) {
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

        // update the title data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function (e) {
            e.preventDefault();
            // form validation
            var frequency = $("#editfrequency").val();
            var days = $("#editdays").val();

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


            if (frequency && days) {
              var form = $(this);
              var formData = new FormData(this);

              $.ajax({
                url: "ajax/settings/data-collection-frequency/project-data-collection-frequency-action.php",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                  if (response.success) {
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
        url: 'ajax/settings/data-collection-frequency/project-data-collection-frequency-action.php',
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
