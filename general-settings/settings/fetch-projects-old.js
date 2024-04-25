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
      url: "general-settings/selected-items/fetch-selected-projects-item.php",
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

$("#approveItems").click(function(e) {
  e.preventDefault();
  var itemId = [];
  $.each($("input[name='projects[]']:checked"), function() {
    itemId.push($(this).val());
  });
  if (itemId != "") {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action.php",
      data: {
        approveMultiple: "approveMultiple",
        itemId: itemId
      },
      dataType: "html",
      success: function(response) {
        $("#approveProject").html(response);
        $("#multipleProjects").DataTable();
      }
    });
  } else {
    alert("Select projects you want to approve ");
    $("#approveProject").modal(
      "Hey you need to select one or more projects to approve "
    );
  }
});

$("#approveMultipleProjects").click(function(e) {
  e.preventDefault();
  var approveItems = 1;
  //get projid
  var projid = [];
  $.each($("input[name='projid[]']"), function() {
    projid.push($(this).val());
  });

  if (projid.length > 0) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action.php",
      data: {
        approveItems: approveItems,
        projid: projid
      },
      dataType: "json",
      success: function(response) {
        $("#selectAll").prop("checked", false);
        $("#approveProject").html("");
        $("#hidden").hide();
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
  } else {
    $("#approveProject").html("Select Ones yo want to Approve ");
  }
});

$("#deleteMultiple").click(function(e) {
  e.preventDefault();
  var itemId = [];
  $.each($("input[name='projects[]']:checked"), function() {
    itemId.push($(this).val());
  });
  if (itemId != "") {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action.php",
      data: {
        fetchItems: "fetchItems",
        itemId: itemId
      },
      dataType: "html",
      success: function(response) {
        $("#deleteProject").html(response);
        $("#selectAll").prop("checked", false);
        $("#approveProject").html("");
        $("#hidden").hide();
      }
    });
  } else {
    $("#deleteProject").html("Select Ones yo want to delete ");
  }
});

$("#deleteMultipleProjects").click(function(e) {
  e.preventDefault();
  var itemId = [];
  $.each($("input[name='itemids[]']"), function() {
    itemId.push($(this).val());
  });
  if (itemId != "") {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action.php",
      data: {
        deleteItems: "deleteM",
        itemId: itemId
      },
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
  } else {
    $("#deleteProject").html("Select Ones yo want to delete ");
  }
});

// remove Project
function removeItem(itemId = null) {
  if (itemId) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action.php",
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

function approveItem(itemId = null) {
  if (itemId) {
    $.ajax({
      url: "general-settings/action/project-approval.php",
      type: "post",
      data: { itemId: itemId },
      dataType: "html",
      success: function(response) {
        $("#aproveBody").html(response);
      }
    });
  } else {
    alert("error please refresh the page");
  }
}

// approve item
$("#approveItemForm")
  .unbind("submit")
  .bind("submit", function(e) {
    e.preventDefault();
    var form = $(this);
    var formData = new FormData(this);

    var sumOutputBudget = 0;
    $("input[name='projcost[]']").each(function() {
      if ($(this).val()) {
        sumOutputBudget = parseFloat($(this).val()) + sumOutputBudget;
      }
    });

    var financierContribution = 0;
    $("input[name='amountfunding[]']").each(function() {
      if ($(this).val()) {
        financierContribution =
          parseFloat($(this).val()) + financierContribution;
      }
    });

    var difference = sumOutputBudget - financierContribution;

    if (difference == 0) {
      $.ajax({
        url: "general-settings/action/project-edit-action.php",
        type: "post",
        data: formData,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response) {
            $("#editProductBtn").button("reset");
            manageItemTable.ajax.reload(null, true);
            alert(response.messages);
            $(".modal").each(function() {
              $(this).modal("hide");
            });
          }
        }
      });
    } else {
      alert("Ensure that the financier funding is equal to Output Budget");
    }
  });

function Undo(itemId = null) {
  if (itemId) {
    $("#Unapprove")
      .unbind("click")
      .bind("click", function() {
        var unapproveitem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action.php",
          type: "post",
          data: { itemId: itemId, unapproveitem: unapproveitem },
          dataType: "json",
          success: function(response) {
            $("#unapproveItemBtn").button("reset");
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
