// function fetches more about the financial plan
function more(projid) {
  if (projid) {
    $.ajax({
      type: "post",
      url:
        "general-settings/selected-items/fetch-selected-project-financial-plan",
      data: {
        projid: projid,
        more_info: "more"
      },
      dataType: "html",
      success: function(response) {
        $("#moreinfo").html(response);
      }
    });
  }
}

// remove Project
function removeItem(projid) {
  if (projid) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        $.ajax({
          url:
            "general-settings/selected-items/fetch-selected-project-financial-plan",
          type: "post",
          data: { projid: projid, deleteItem: "deleteItem" },
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

// function fetches more about the  procurement
function more_procurement(projid) {
  if (projid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/add-procurement-details-process",
      data: {
        projid: projid,
        getprocurementdetails: "getprocurementdetails"
      },
      dataType: "html",
      success: function(response) {
        $("#moreinfo").html(response);
      }
    });
  }
}

// remove Project procurement details
function remove_procurement(projid) {
  if (projid) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        $.ajax({
          url: "general-settings/action/add-procurement-details-process",
          type: "post",
          data: { projid: projid, deleteItem: "deleteItem" },
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