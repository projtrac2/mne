const url = "/ajax/programs/programs.php";

function moreInfo(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    // remove text-error
    $(".text-danger").remove();
    // remove from-group error
    $(".form-input").removeClass("has-error").removeClass("has-success");
    // modal div
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: url,
      type: "post",
      data: { itemId: itemId, moreinfo: "moreinfo" },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
      }, // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else { 
    swal({
        title: "Programs !",
        text: "error please refresh the page",
        icon: "error",
      });
  }
} // /edit Project Main Menu  function

// remove Contractor Nationality
function removeItem(itemId = null) {
  if (itemId) {
    // remove Contractor Nationality button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: url,
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {  
              swal({
                title: "Programs !",
                text: response.messages,
                icon: "success",
              });
              $(".modal").each(function () {
                $(this).modal("hide");
              });
              setTimeout(function () { window.location.reload(true);}, 3000); 
            } else { 
              swal({
                title: "Programs !",
                text: response.messages,
                icon: "error",
              }); 
              setTimeout(function () { window.location.reload(true);}, 3000); 
            } // /error
          }, // /success function
        }); // /ajax fucntion to remove the Contractor Nationality
        return false;
      }); // /remove Contractor Nationality btn clicked
  } // /if Contractor Nationalityid
} // /remove Contractor Nationality function