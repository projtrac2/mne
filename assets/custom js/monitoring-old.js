var x = document.getElementById("geoposx");
var y = document.getElementById("geoposy");
var z = document.getElementById("geoposz");

$(document).ready(function () {
  $(".account").click(function () {
    var X = $(this).attr('id');
    if (X == 1) {
      $(".submenus").hide();
      $(this).attr('id', '0');
    } else {
      $(".submenus").show();
      $(this).attr('id', '1');
    }
  });

  //Mouseup textarea false
  $(".submenus").mouseup(function () {
    return false
  });
  $(".account").mouseup(function () {
    return false
  });

  //Textarea without editing.
  $(document).mouseup(function () {
    $(".submenus").hide();
    $(".account").attr('id', '');
  });


  disable_refresh(function () {
    console.log("This is the call back function that is needed");
    remove();
  });

  $("#submit").prop("disabled", true);
  enableSubmit();

  $("#tag-form").on("submit", function (event) {
    event.preventDefault();
    var taskscore = $("#tskscid").val();
    var form_data = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "assets/processor/savechecklistscore.php",
      data: form_data,
      dataType: "json",
      success: function (data) {
        $("#" + taskscore).val(data);
        $("#btn" + taskscore).html("Edit Score");
        $("#tag-form")[0].reset();
        $("#myModal").modal("hide");
        enableSubmit();
      },
      error: function () {
        alert("Error");
      },
    });
    return false;
  });
});

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else {
    z.innerHTML =
      '<input name="geoerror" type="text" id="geoerror" value="Geolocation is not supported by this browser."/>';
  }
}

function showPosition(position) {
  x.innerHTML =
    '<input name="latitude" type="hidden" id="monlatitude" value="' +
    position.coords.latitude +
    '" />';
  y.innerHTML =
    '<input name="longitude" type="hidden" id="monlongitude" value="' +
    position.coords.longitude +
    '" />';
}

function showError(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      z.innerHTML =
        '<input name="geoerror" type="hidden" id="geoerror" value="User denied the request for Geolocation."/>';
      break;
    case error.POSITION_UNAVAILABLE:
      z.innerHTML =
        '<input name="geoerror" type="text" id="geoerror" value=""Location information is unavailable."/>';
      break;
    case error.TIMEOUT:
      z.innerHTML =
        '<input name="geoerror" type="text" id="geoerror" value=""The request to get user location timed out."/>';
      break;
    case error.UNKNOWN_ERROR:
      z.innerHTML =
        '<input name="geoerror" type="text" id="geoerror" value=""An unknown error occurred."/>';
      break;
  }
}

// function disable refreshing functionality
function disable_refresh() {
  return (window.onbeforeunload = function (e) {
    return "you can not refresh the page";
  });
}

function GetTaskChecklist(tkid = null, pmtid = null) {
  if (tkid && pmtid) {
    let lev3id = $("#lev3id").val();
    let lev4id = $("#lev4id").val();
    $.ajax({
      type: "post",
      url: "assets/processor/gettaskchecklist.php",
      data: {
        tskid: tkid,
        pmtid: pmtid,
        lev3id: lev3id,
        lev4id: lev4id,
      },
      success: function (data) {
        $("#formcontent").html(data);
        $("#myModal").modal({
          backdrop: "static"
        });
      },
    });
  }
}

function enableSubmit() {
  let handler = [];
  $(".tasks").each(function () {
    var val = $(this).val();
    if (val != "") {
      handler.push(true)
    } else {
      handler.push(false);
    }
  });
  if (handler.includes(false)) {
    $("#submit").prop("disabled", true);
  } else {
    $("#submit").prop("disabled", false);
  }
}

function remove() {
  let data = $("#mainformid").val();
  if (data) {
    $.ajax({
      type: "post",
      url: "assets/processor/gettaskchecklist.php",
      data: {
        delete: "delete",
        formid: data
      },
      dataType: "dataType",
      success: function (response) {
        console.log(response);
      }
    });
  }
}