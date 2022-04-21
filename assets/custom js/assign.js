$(document).ready(function () {
  $(".projects td").click(function (e) {
    e.preventDefault();
    $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
  });

  // submit form
  $("#submitMilestoneForm").submit(function (e) {
    e.preventDefault();
  });

  $("#tag-form-submit").click(function (e) {
    e.preventDefault();
    var formData = $("#submitMilestoneForm").serialize(); 
    $.ajax({
      type: "POST",
      url: "assets/processor/add-project-map-location-assign-process",
      data: formData,
      dataType: "json",
      success: function (response) {
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
  });
});

// sweet alert notifications
function sweet_alert(err, msg) {
  return swal({
    title: err,
    text: msg,
    type: "Error",
    timer: 5000,
    showConfirmButton: false,
  });
  setTimeout(function () {}, 2000);
}

function more(projid, opid, dissagragated) {
  if (projid && opid) {
    $.ajax({
      url: "assets/processor/add-project-map-location-assign-process",
      type: "post",
      data: {
        projid: projid,
        dissagragated: dissagragated,
        opid: opid,
        get_more: "get_more",
      },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
      },
    });
  } else {
    alert("Error please refresh the page");
  }
}

function more_info(mapid) {
  if (mapid) {
    $.ajax({
      url: "assets/processor/add-project-map-location-assign-process",
      type: "get",
      data: {
        mapid: mapid,
        more: "more",
      },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
      },
    });
  } else {
    alert("Error please refresh the page");
  }
}

function add(projid, opid, dissagragated) {
  if (opid && projid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-map-location-assign-process",
      data: {
        dissagragated: dissagragated,
        opid: opid,
        projid: projid,
        get_locations: "get_locations",
      },
      dataType: "html",
      success: function (response) {
        $("#assign_table_body").html(response);
        $(".selectpicker").selectpicker("refresh");
        $("#newitem").val("newitem");
        $("#newitem").attr("name", "newitem");
      },
    });
  }
}

function edit(projid, opid, dissagragated) {
  if (opid && projid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-map-location-assign-process",
      data: {
        opid: opid,
        dissagragated: dissagragated,
        projid: projid,
        get_details: "get_details",
      },
      dataType: "html",
      success: function (response) {
        $("#assign_table_body").html(response);
        $(".selectpicker").selectpicker("refresh");
        $("#newitem").val("edititem");
        $("#newitem").attr("name", "edititem");
      },
    });
  }
}

// get responsible who is in charge
function get_responsible(rowno) {
  var members = $(`#team${rowno}`).val();
  if (projid) {
    $.ajax({
      type: "POST",
      url: "assets/processor/add-project-map-location-assign-process",
      data: {
        get_responsible: "responsible",
        members: members,
      },
      dataType: "html",
      success: function (html) {
        $("#responsible" + rowno).html(html);
        $(".selectpicker").selectpicker("refresh");
      },
    });
  }
}

// validate dates
function validate_date($rowno) {
  var today = new Date().setHours(0, 0, 0, 0);
  var chosen = new Date($("#mdate" + $rowno).val()).setHours(0, 0, 0, 0);
  if (today >= chosen) {
    $("#mdate" + $rowno).val("");
    var msg = "Date should be greater than today";
    // sweet_alert("Error", msg);
    alert(msg);
  }
}
