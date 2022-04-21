// function fetches more about the financial plan
function more(indid) {
  if (indid) {
    $.ajax({
      type: "post",
      url:
        "general-settings/selected-items/fetch-selected-kpi.php",
      data: {
        indid: indid,
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
function removeItem(indid) {
  if (indid) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        $.ajax({
          url:
            "general-settings/selected-items/fetch-selected-kpi.php",
          type: "post",
          data: { indid: indid, deleteItem: "deleteItem" },
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