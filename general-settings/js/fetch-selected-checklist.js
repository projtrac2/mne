// function o get add inspection checklist
function addInspection(tkid) {
  if (tkid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/task-inspection-checklist",
      data: {
        getaddInspectionChecklist: "new",
        tkid: tkid
      },
      dataType: "html",
      success: function(response) {
        $("#checklistForm").html(response);
        $("#title").html('<i class="fa fa-plus-square"></i> Add Inspection Checklist');
      }
    });
  } else {
    console.log("Id does not exists");
  }
}
// get edit inspection checklist
function editInspection(tkid) {
  if (tkid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/task-inspection-checklist",
      data: {
        geteditInspectionChecklist: "edit",
        tkid: tkid
      },
      dataType: "html",
      success: function(response) {
        $("#checklistForm").html(response);
        $("#title").html('<i class="fa fa-pencil-square"></i> Edit Inspection Checklist');
      }
    });
  } else {
    console.log("Id does not exists");
  }
}

// get minitoring checklist table
function addMonitoring(monitor) {
  $.ajax({
    type: "post",
    url: "general-settings/action/task-monitoring-checklist",
    data: {
      getMonitoringChecklist: "new",
      taskid: monitor
    },
    dataType: "html",
    success: function(response) {
      $("#checklistForm").html(response);
        $("#title").html('<i class="fa fa-plus-square"></i> Add Monitoring Checklist');
    }
  });
}

// get editing monitoring cheklist
function editMonitoring(monitor) {
  $.ajax({
    type: "post",
    url: "general-settings/action/task-monitoring-checklist",
    data: {
      geteditMonitoringChecklist: "new",
      taskid: monitor
    },
    dataType: "html",
    success: function(response) {
      $("#checklistForm").html(response);
        $("#title").html('<i class="fa fa-pencil-square"></i> Edit Monitoring Checklist');
    }
  });
}

// remove function
function removeItem(itemId, option) {
  if (itemId) {
    if (option == "2") {
      $("#removeItemBtn")
        .unbind("click")
        .bind("click", function() {
          $.ajax({
            url: "general-settings/action/task-inspection-checklist",
            type: "post",
            data: { itemId: itemId, deleteInspection: "deleteItem" },
            dataType: "json",
            success: function(response) {
              $("#removeItemBtn").button("reset");
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
    } else if (option == "3") {
      $("#removeItemBtn")
        .unbind("click")
        .bind("click", function() {
          $.ajax({
            url: "general-settings/action/task-monitoring-checklist",
            type: "post",
            data: { itemId: itemId, deleteMonitoring: "deleteItem" },
            dataType: "json",
            success: function(response) {
              $("#removeItemBtn").button("reset");
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
}

// functionality to collapse
$(".collapse td").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa-plus-square fa-minus-square");
});

// functionality to collapse

$(".outputs td").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa-plus-square fa-minus-square");
});
