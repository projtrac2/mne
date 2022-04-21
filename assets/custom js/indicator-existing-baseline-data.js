// function calldisaggregateloc(lv3id, indid) {
//   var lvid = $("#lvid" + indid + lv3id).val();
//   var lv3lb = $("#lv3lb" + indid + lv3id).val();

//   $.ajax({
//     type: "post",
//     url: "assets/processor/indicator-disaggregations-processor",
//     data: { indid: indid, lv3id: lv3id, level3: lvid, lv3lb: lv3lb },
//     success: function (data) {
//       $("#level3locdisaggregation").html(data);
//       $("#disModal").modal({ backdrop: "static" });
//     },
//   });
// }

// $(document).ready(function () {
//   $("#indicator-disaggregation-form").on("submit", function (event) {
//     event.preventDefault();
//     var level3id = $("#level3id").val();
//     var form_data = $(this).serialize();
//     $.ajax({
//       type: "POST",
//       url: url1,
//       data: form_data,
//       dataType: "html",
//       success: function (response) {
//         if (response) {
//           alert("Disaggregations successfully defined!!");
//           $("#level3" + level3id).html(response);
//           $("#button" + level3id).html(
//             '<button type="button" class="btn bg-blue-grey btn-block btn-xs waves-effect"  disabled="disabled" >Disaggregated</button>'
//           );
//           $("#disModal").modal("hide");
//         }
//       },
//       error: function () {
//         alert("Error");
//       },
//     });
//     return false;
//   });
// });



const url1 = "assets/processor/indicator-existing-baseline-data-processor"; 
jQuery(document).ready(function () {
  jQuery(".tabs .tab-links a").on("click", function (e) {
    var currentAttrValue = jQuery(this).attr("href");

    // Show/Hide Tabs
    jQuery(".tabs " + currentAttrValue)
      .show()
      .siblings()
      .hide();

    // Change/remove current tab to active
    jQuery(this) 
      .parent("li")
      .addClass("active")
      .siblings()
      .removeClass("active");

    e.preventDefault();
  });
});
$(document).ready(function () {
  $(".account").click(function () {
    var X = $(this).attr("id");

    if (X == 1) {
      $(".submenus").hide();
      $(this).attr("id", "0");
    } else {
      $(".submenus").show();
      $(this).attr("id", "1");
    }
  });

  //Mouseup textarea false
  $(".submenus").mouseup(function () {
    return false;
  });
  $(".account").mouseup(function () {
    return false;
  });

  //Textarea without editing.
  $(document).mouseup(function () {
    $(".submenus").hide();
    $(".account").attr("id", "");
  });
});

// disaggretions
function disaggregate(level3 = null) {
  if (indid && level3) {
    $("#level3dis").val(level3);
  }
}

$("#tag-form-submit").click(function (e) {
  e.preventDefault();

  let formdata = $("#indicator-disaggregation-form").serialize();
  console.log(formdata);
  $.ajax({
    type: "POST",
    url: url1,
    data: formdata,
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

// disaggregated
function get_add_diss_inputs(
  lve3id = null,
  location = null,
  indid = null,
  level3
) {
  if (lve3id && indid) {
    $("#level3").val(lve3id);
    $("#location").val(location);

    if (location != null) {
      let level3 = $(`#level3${location}`).val();
      console.log(level3);
      $("#locationName").html(level3);
    } else {
      let level3 = $(`#level3${lve3id}`).val();
      $("#locationName").html(level3);
    }

    $.ajax({
      type: "GET",
      url: url1,
      data: {
        getdiss: "getdiss",
        indicatorid: indid,
      },
      dataType: "html",
      success: function (response) {
        $("#baselineform").html(response);
      },
    });
  }
}

// Non disaggragated
function get_add_inddiss_inputs(lve3id = null, level3) {
  if (lve3id) {
    $("#level3").val(lve3id);
    let level3 = $(`#level3${lve3id}`).val();
    $("#locationName").html(level3);
    $.ajax({
      type: "GET",
      url: url1,
      data: "getinddiss",
      dataType: "html",
      success: function (response) {
        $("#baselineform").html(response);
      },
    });
  }
}

$("#tag-form-base-submit").click(function (e) {
  e.preventDefault();
  let formdata = $("#indicator-baseline-form").serialize();
  $.ajax({
    type: "POST",
    url: url1,
    data: formdata,
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

// disaggregated
function get_add_dissedit_inputs(
  lve3id = null,
  location = null,
  indid = null,
  level3
) {
  if (lve3id && indid) {
    $("#level3").val(lve3id);
    $("#location").val(location);

    if (location != null) {
      let level3 = $(`#level3${location}`).val();
      console.log(level3);
      $("#locationName").html(level3);
    } else {
      let level3 = $(`#level3${lve3id}`).val();
      $("#locationName").html(level3);
    }

    $.ajax({
      type: "GET",
      url: url1,
      data: {
        getdissedit: "getdissedit",
        indicatorid: indid,
        level3: lve3id,
        level4: location,
      },
      dataType: "html",
      success: function (response) {
        $("#baselineform").html(response);
      },
    });
  }
}

// Non disaggragated
function get_add_inddissedit_inputs(lve3id = null) {
  if (lve3id) {
    let indid = $("#indicator").val();
    $("#level3").val(lve3id);

    let level3 = $(`#level3${lve3id}`).val();
    $("#locationName").html(level3);

    $.ajax({
      type: "GET",
      url: url1,
      data: {
        getinddissedit: "getinddissedit",
        level3: lve3id,
        indid: indid,
      },
      dataType: "html",
      success: function (response) {
        $("#baselineform").html(response);
      },
    });
  }
}

function disableSubmit() {
  $("#submit").prop("disabled", true);
}

function enableSubmit() {
  $("#submit").prop("disabled", false);
}

function validateLocation() {
  let disaggregated = $("#disaggregated").val();
  let indid = $("#indicator").val();
  $.ajax({
    type: "POST",
    url: url1,
    data: {
      validate: "validate",
      indid: indid,
      disaggregated: disaggregated,
    },
    dataType: "json",
    success: function (response) {
      if (response.msg) {
        enableSubmit();
      } else {
        disableSubmit();
      }
    },
  });
}

$(document).ready(function () {
  disableSubmit();
  validateLocation();
});
