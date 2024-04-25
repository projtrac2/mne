function addInspection(inspectionid, indid, msid) {
  if (inspectionid) {
    $.ajax({
      type: "post",
      url: "assets/processor/task-inspection-checklist",
      data: {
        getaddInspectionChecklist: "new",
        taskid: inspectionid,
        indid: indid,
        msid: msid,
      },
      dataType: "html",
      success: function (response) {
        $("#checklistForm").html(response);
      },
    });
  } else {
    console.log("Id does not exists");
  }
}

function editInspection(inspectionid, indid, msid) {
  if (inspectionid) {
    $.ajax({
      type: "post",
      url: "assets/processor/task-inspection-checklist",
      data: {
        geteditInspectionChecklist: "edit",
        taskid: inspectionid,
        indid: indid,
        msid: msid,
      },
      dataType: "html",
      success: function (response) {
        $("#checklistForm").html(response);
      },
    });
  } else {
    console.log("Id does not exists");
  }
}

function getInspection(inspectionid, indid, msid) {
  if (inspectionid) {
    $.ajax({
      type: "post",
      url: "assets/processor/task-inspection-checklist",
      data: {
        getmore: "more",
        taskid: inspectionid,
        indid: indid,
        msid: msid,
      },
      dataType: "html",
      success: function (response) {
        $("#morechecklistForm").html(response);
      },
    });
  } else {
    console.log("Id does not exists");
  }
}

function removeItem(itemId, option) {
  if (itemId) {
    var projid = $("#mprojid").val();
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        $.ajax({
          url: "assets/processor/task-inspection-checklist",
          type: "post",
          data: {
            itemId: itemId,
            projid: projid,
            deleteInspection: "deleteItem",
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

function exit(projid) {
  $.ajax({
    type: "post",
    url: "assets/processor/task-inspection-checklist",
    data: {
      exit: "exit",
      itemId: projid,
    },
    dataType: "json",
    success: function (response) {},
  });
}

$(".collapse td").click(function (e) {
  e.preventDefault();
  $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
});

$(".outputs td").click(function (e) {
  e.preventDefault();
  $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
});
