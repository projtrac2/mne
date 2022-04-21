$(document).ready(function () {
  // disable_refresh();
  $(".collapse td").click(function (e) {
    e.preventDefault();
    $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
  });

  $(".outputs td").click(function (e) {
    e.preventDefault();
    $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
  });
});

function addMonitoring(monitor) {
	console.log(monitor);
  $.ajax({
    type: "post",
    url: "assets/processor/task-monitoring-checklist",
    data: {
      getMonitoringChecklist: "new",
      taskid: monitor,
    },
    dataType: "html",
    success: function (response) {
      $("#checklistForm").html(response);
    },
  });
}

function editMonitoring(monitor) {
  $.ajax({
    type: "post",
    url: "assets/processor/task-monitoring-checklist",
    data: {
      geteditMonitoringChecklist: "new",
      taskid: monitor,
    },
    dataType: "html",
    success: function (response) {
      $("#checklistForm").html(response);
    },
  });
}

function getMore(monitor) {
  $.ajax({
    type: "post",
    url: "assets/processor/task-monitoring-checklist",
    data: {
      getmore: "more",
      taskid: monitor,
    },
    dataType: "html",
    success: function (response) {
      $("#morechecklistForm").html(response);
    },
  });
}

function removeItem(itemId, option) {
  if (itemId) {
    var projid = $("#projid").val();
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        $.ajax({
          url: "assets/processor/task-monitoring-checklist",
          type: "post",
          data: {
            itemId: itemId,
            projid: projid,
            deleteMonitoring: "deleteItem",
          },
          dataType: "json",
          success: function (response) {
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
              location.reload(true);
            } else {
              alert(response.messages);
              location.reload(true);
            }
          },
        });
        return false;
      });
  }
}

// function disable refreshing functionality
function disable_refresh() {
  return (window.onbeforeunload = function (e) {
    return "you can not refresh the page";
  });
}

function exit() {
  var projid = $("#projid").val();
  if (projid) {
    $.ajax({
      type: "post",
      url: "assets/processor/task-monitoring-checklist",
      data: {
        exit: "exit",
        itemId: projid,
      },
      dataType: "json",
      success: function (response) {
        if (!response) {
			alarm();
        } else {
			alert("Successfully added monitoring checklist!");
			window.location.href = "view-mne-plan";
        }
      },
    });
  }
}

function alarm() {
  alert("You have not finished adding monitoring checklist");
  return false;
}
