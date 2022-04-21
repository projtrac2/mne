function more(itemId, option) {
  if (itemId) {
    if (option == "1") {
      $.ajax({
        url: "general-settings/selected-items/fetch-selected-projects-item",
        type: "post",
        data: { itemId: itemId },
        dataType: "html",
        success: function(response) {
          $("#moreinfo").html(response);
        }
      });
    } else if (option == "2") {
      $.ajax({
        url:
          "general-settings/selected-items/fetch-selected-milestone-task",
        type: "post",
        data: { itemId: itemId, milestones: "milestones" },
        dataType: "html",
        success: function(response) {
          $("#moreinfo").html(response);
        }
      });
    } else if (option == "3") {
      $.ajax({
        url:
          "general-settings/selected-items/fetch-selected-milestone-task",
        type: "post",
        data: { itemId: itemId, tasks: "tasks" },
        dataType: "html",
        success: function(response) {
          $("#moreinfo").html(response);
        }
      });
    }
  } else {
    alert("Error please refresh the page");
  }
}

// remove Project
function removeItem(itemId, option) {
  if (itemId) {
    var deleteItem;
    if (option == "2") {
      deleteItem = 2;
    } else if (option == "3") {
      deleteItem = 3;
    }
    if (deleteItem) {
      $("#removeItemBtn")
        .unbind("click")
        .bind("click", function() {
          $.ajax({
            url:
              "general-settings/selected-items/fetch-selected-milestone-task",
            type: "post",
            data: { itemId: itemId, deleteItem: deleteItem },
            dataType: "json",
            success: function(response) {
              $("#removeItemBtn").button("reset");
              if (response.success == true) {
                //   manageItemTable.ajax.reload(null, true);
                alert(response.messages);
                $(".modal").each(function() {
                  $(this).modal("hide");
                });
                location.reload(true);
              } else {
                alert(response.messages);
                location.reload(true);
              }
            }
          });
          return false;
        });
    }
  }
}

function getMilestoneForm(projid, outputid) {
  $.ajax({
    type: "POST",
    url: "general-settings/selected-items/fetch-selected-milestone-task",
    data: {
      getMilestoneForm: "getMilestoneForm",
      projid: projid,
      outputid: outputid
    },
    dataType: "html",
    success: function(response) {
      $("#milestoneForm").html(response);
      $("#newitem").val("newItem");
      $("#tag-form-submit").val("Add New Milestone");
    }
  });
}

function getMilestoneEditForm(mileid) {
  if (mileid) {
    $.ajax({
      type: "POST",
      url: "general-settings/selected-items/fetch-selected-milestone-task",
      data: {
        getMilestoneEditForm: "getMilestoneEditForm",
        mileid: mileid
      },
      dataType: "html",
      success: function(response) {
        $("#milestoneForm").html(response);
        $("#newitem").val("editItem");
        $("#tag-form-submit").val("Edit Milestone");
        $("#addModal").html('<i class="fa fa-plus"></i> Edit Milestone');
      }
    });
  }
}

function getAddTaskForm(mileid) {
  $.ajax({
    type: "POST",
    url: "general-settings/selected-items/fetch-selected-milestone-task",
    data: {
      getAddTaskForm: "getAddTaskForm",
      mileid: mileid
    },
    dataType: "html",
    success: function(response) {
      $("#milestoneForm").html(response);
      $("#newitem").val("newItem");
      $("#tag-form-submit").val("Add New Task");
      $("#addModal").html('<i class="fa fa-plus"></i> Add Task');
    }
  });
}

function getTaskEditForm(taskid) {
  if (taskid) {
    $.ajax({
      type: "POST",
      url: "general-settings/selected-items/fetch-selected-milestone-task",
      data: {
        getTaskEditForm: "getTaskEditForm",
        taskid: taskid
      },
      dataType: "html",
      success: function(response) {
        $("#milestoneForm").html(response);
        $("#newitem").val("editItem");
        $("#tag-form-submit").val("Edit Task");
        $("#addModal").html('<i class="fa fa-plus"></i> Edit Task');
      }
    });
  }
}

$(".collapse td").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa-plus-square fa-minus-square");
});

$(".projects td").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa-plus-square fa-minus-square");
});


// remove Project
function endaddingactivities(projid) {
  if (projid) {
    $("#finishAddItemBtn")
      .unbind("click")
      .bind("click", function() {
        $.ajax({
			url: "general-settings/selected-items/fetch-selected-milestone-task",
			type: "post",
			data: { projid: projid, finishAddItem: "finishAddItem" },
			dataType: "json",
			success: function(response) {
				$("#finishAddItemBtn").button("reset");
				if (response.success == true) {
				  alert(response.messages);
				  $(".modal").each(function() {
					$(this).modal("hide");
				  });
				  location.reload(true);
				} else {
				  alert(response.messages);
				  location.reload(true);
				}
			}
        });
        return false;
      });
  }
}
