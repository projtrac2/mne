var manageItemTable;

$(document).ready(function() {
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: url,
    order: [],
    columnDefs: [
      {
        targets: [0, 7],
        orderable: false
      }
    ]
  });
});

function more(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    $(".text-danger").remove();
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success");
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "general-settings/selected-items/fetch-selected-projects-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "html",
      success: function(response) {
        $("#moreinfo").html(response);
      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    alert("error please refresh the page");
  }
}


function Undo(itemId = null) {
  if (itemId) {
    $("#removeadpBtn")
      .unbind("click")
      .bind("click", function () {
        var unapproveitem = 1;
        $.ajax({
		  url: "assets/processor/fetch-sp-projects",
		  type: "post",
		  data: { itemId: itemId, removeadp: unapproveitem },
          dataType: "json",
          success: function (response) {
			console.log(response);
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            }
          }
        });
        return false;
      });
  }
}

// remove Project
function removeItem(itemId = null) {
  if (itemId) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            }
          }
        });
        return false;
      });
  }
}

