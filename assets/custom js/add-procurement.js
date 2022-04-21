//function to put commas to the data
function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

// sweet alert notifications
function sweet_alert(err, msg) {
  return swal({
    title: err,
    text: msg,
    type: "Error",
	icon: 'warning',
	dangerMode: true,
    timer: 15000,
    showConfirmButton: false
  });
  setTimeout(function() {}, 15000);
}

// function to calculate remaining budget
function ouput_used_cost(opid) {
  var output_budget = parseInt($("#contribution_amount" + opid).val());
  var projbudget = parseInt($("#contributed_amount").val());

  if (output_budget) {
    var used_budget = 0;
    $(".output_cost" + opid).each(function () {
      if ($(this).val() != "") {
        used_budget = used_budget + parseInt($(this).val());
      }
    });

    var summarytotal = 0;
    $(".summarytotal").each(function () {
      if ($(this).val() != "") {
        summarytotal = summarytotal + parseInt($(this).val());
      }
    });

    var remaining = output_budget - used_budget; //calculate remaining output budget
    var summary_percentage = (summarytotal / projbudget) * 100;

    if (remaining >= 0) {
      $(".output_cost_bal" + opid).val(commaSeparateNumber(remaining));
      $("#summaryOutput" + opid).html(commaSeparateNumber(used_budget));
      $("#summary_total").html(commaSeparateNumber(summarytotal) + '<input type="hidden" name="totalcost" id="totalcost" value="' + summarytotal + '">');
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return true;
    } else {
      $(".output_cost_bal" + opid).val(commaSeparateNumber(remaining));
      $("#summaryOutput" + opid).html(commaSeparateNumber(used_budget));
      $("#summary_total").html(commaSeparateNumber(summarytotal) + '<input type="hidden" name="totalcost" id="totalcost" value="' + summarytotal + '">');
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return false;
    }
  }
}

function cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid) {
  var output_budget = parseFloat($("#contribution_amount" + opid).val());
  var sub_total_val = 0;
  $(sub_total_class).each(function () {
    if ($(this).val() != "") {
      sub_total_val = sub_total_val + parseFloat($(this).val());
    }
  });
  var sub_total_percentage = (sub_total_val / output_budget) * 100; //calculate percentage of the given div
  $(sub_total_aid).val(commaSeparateNumber(sub_total_val));
  $(sub_total_pid).val(sub_total_percentage.toFixed(2) + "%");
  $("#perc" + opid).html(sub_total_percentage.toFixed(2) + "%");
}

// function  to calculate the total cost of a task
function totalCost(tkid, opid, number, rowno) {
  var sub_total_pid = "#sub_total_percentage" + number + opid;
  var sub_total_aid = "#sub_total_amount" + number + opid;
  var sub_total_class = ".direct_sub_total_amount" + number + opid;
  var total_cost_id = "#totalcost" + opid + tkid + rowno;
  var total_unit_id = "#totalunits" + opid + tkid + rowno;
  var unit_cost_id = "#unitcost" + opid + tkid + rowno;

  var totalUnits = parseInt($(total_unit_id).val());
  var unitCost = parseInt($(unit_cost_id).val());

  if (unitCost > 0) {
    if (totalUnits > 0) {
      var taskCost = unitCost * totalUnits;
      $(total_cost_id).val(taskCost);
      var total_output_Cost = ouput_used_cost(opid);
      if (total_output_Cost) {
        var total_output_Cost = ouput_used_cost(opid);
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
      } else {
        var msg =
          "Ensure the amount does not exceed financial cost plan";
        sweet_alert("Error !!!", msg);
        $(total_cost_id).val("");
        $(total_unit_id).val("");
        var total_output_Cost = ouput_used_cost(opid);
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
      }
    } else {
      $(total_cost_id).val("");
      $(total_unit_id).val("");
      var total_output_Cost = ouput_used_cost(opid);
      cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
    }
  } else {
    var msg = "Enter Unit Cost  First";
    sweet_alert("Error !!!", msg);
    $(total_cost_id).val("");
    $(total_unit_id).val("");
    var total_output_Cost = ouput_used_cost(opid);
    cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
  }
}

//function to listen for changes in units
// and also change in no of units
function number_of_units_change(tkid, opid, number, rowno) {
  var sub_total_pid = "#sub_total_percentage" + number + opid;
  var sub_total_aid = "#sub_total_amount" + number + opid;
  var sub_total_class = ".direct_sub_total_amount" + number + opid;
  var total_cost_id = "#totalcost" + opid + tkid + rowno;
  var total_unit_id = "#totalunits" + opid + tkid + rowno;
  var unit_cost_id = "#unitcost" + opid + tkid + rowno;

  var totalUnits = parseInt($(total_unit_id).val());
  var unitCost = parseInt($(unit_cost_id).val());

  if (unitCost > 0) {
    var sub_total_pid = "";
    var sub_total_aid = "";
    var sub_total_class = "";
    if (number == "3") {
      var budget_line_id = $(total_unit_id).attr("data-id");
      var id = budget_line_id + opid.toString();
      sub_total_aid = "#sub_total_amount3" + $.trim(id);
      sub_total_pid = "#sub_total_percentage3" + $.trim(id);
      sub_total_class =
        ".direct_sub_total_amount" + number + $.trim(budget_line_id);
    } else {
      sub_total_pid = "#sub_total_percentage" + number + opid;
      sub_total_aid = "#sub_total_amount" + number + opid;
      sub_total_class = ".direct_sub_total_amount" + number + opid;
    }

    if (totalUnits > 0) {
      if (unitCost > 0) {
        var taskCost = unitCost * totalUnits;
        $(total_cost_id).val(taskCost);

        var total_output_Cost = ouput_used_cost(opid);
        if (total_output_Cost) {
          var total_output_Cost = ouput_used_cost(opid);
          cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
        } else {
          var msg =
            "Ensure that amount is less or equal to the procurement cost plan";
          sweet_alert("Error !!!", msg);

          $(total_cost_id).val("");
          $(total_unit_id).val("");
          $(unit_cost_id).val("");
          var total_output_Cost = ouput_used_cost(opid);
          cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
        }
      } else {
        $(total_cost_id).val("");
        $(total_unit_id).val("");
        $(unit_cost_id).val("");
        var total_output_Cost = ouput_used_cost(opid);
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
      }
    }
  } else {
    $(unit_cost_id).val("");
    $(total_unit_id).val("");
    $(total_cost_id).val("");
  }
}

// validationg start date with the stipulated dates
function start_date(tkid, opid, msid) {
  var tstart_id = "#psdate" + opid + tkid; //task start date  id
  var hstart_id = "#sdate" + opid + tkid; //stipulated start date  id
  var hend_id = "#edate" + opid + tkid; //stipulated end date id
  var tstart = new Date($(tstart_id).val()).setHours(0, 0, 0, 0);
  var hstart = new Date($(hstart_id).val()).setHours(0, 0, 0, 0);
  var hend = new Date($(hend_id).val()).setHours(0, 0, 0, 0);
  var duration_id = "#tduration" + opid + tkid; // duration of the id
  var pdurationmsg_id = "#pdurationmsg" + opid + tkid; // duration of the   
  var pduration_id = "#pduration" + opid + tkid; // duration of the id
  var pduration = parseInt($(pduration_id).val());

  $(duration_id).val("");
  if (tstart < hstart) {
    var msg =
      "Task start date should be greater or equal to defined task start date";
    sweet_alert("Error !!!", msg);
    $(tstart_id).val("");
    $(pdurationmsg_id).html(pduration + " Days");
    max_min_date(opid, msid);
  } else {
    $(pdurationmsg_id).html(pduration + " Days");
    max_min_date(opid, msid);
  }
}

function duration(tkid, opid, msid) {
  var tstart_id = "#psdate" + opid + tkid; //task start date  id  
  var tend_id = "#pedate" + opid + tkid; //task end date  id 
  var duration_id = "#tduration" + opid + tkid;
  var pduration_id = "#pduration" + opid + tkid; // duration of the id
  var pdurationmsg_id = "#pdurationmsg" + opid + tkid; // duration of the id
  var tstart = new Date($(tstart_id).val());
  var startDate = $(tstart_id).val();
  var duration = parseInt($(duration_id).val());
  var pduration = parseInt($(pduration_id).val());

  if (startDate) {
    if (duration > 0) {
      if (duration <= pduration) {
        var remaining = pduration - duration;
        if (remaining >= 0) {
          $(pdurationmsg_id).html(remaining + " Days");
          var a = new Date();
          Date.prototype.addDays = function (d) {
            tstart.setDate(tstart.getDate() + d);
            return tstart;
          };
          var tenddate = a.addDays(duration);
          var tdate = convert_date(tenddate)
          $(tend_id).val(tdate);
          max_min_date(opid, msid);
        } else {
          $(pdurationmsg_id).html(pduration + " Days");
          $(tend_id).val("");
          max_min_date(opid, msid)
        }
      } else {
        sweet_alert("Error", "Duration cannot exceed defined task duration");
        $(duration_id).val("");
        $(tend_id).val("");
        $(pdurationmsg_id).html(pduration + " Days");
        max_min_date(opid, msid)
      }
    } else {
      sweet_alert("Error", "Field should not be empty");
      $(duration_id).val("");
      $(tend_id).val("");
      $(pdurationmsg_id).html(pduration + " Days");
      max_min_date(opid, msid)
    }
  } else {
    $(duration_id).val("");
    $(tend_id).val("");
    sweet_alert("Error", "Enter start date first");
    $(pdurationmsg_id).html(pduration + " Days");
    max_min_date(opid, msid);
  }
}

function convert_date(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}

function max_min_date(opid, msid) {
  var mile_mstart_id = "#mpsdate" + opid + msid
  var mile_mend_id = "#mpedate" + opid + msid
  var mile_tstart_id = ".mile_start" + opid + msid
  var mile_tend_id = ".mile_end" + opid + msid

  mile_tstart = [];
  $(mile_tstart_id).each(function () {
    if ($(this).val() != "") {
      var sdate = new Date($(this).val()).setHours(0, 0, 0, 0);
      mile_tstart.push(sdate);
    }
  });

  if (mile_tstart.length > 0) {
    var mileStart = Math.min(...mile_tstart);
    var newsDate = convert_date(new Date(mileStart));
    console.log(newsDate);
    $(mile_mstart_id).val(newsDate);
  } else {
    $(mile_mstart_id).val("");
  }

  mile_tend = [];
  $(mile_tend_id).each(function () {
    if ($(this).val() != "") {
      var edate = new Date($(this).val()).setHours(0, 0, 0, 0);
      mile_tend.push(edate);
    }
  });

  if (mile_tend.length > 0) {
    var tend = Math.max(...mile_tend);
    var neweDate = convert_date(new Date(tend));
    console.log(neweDate);
    $(mile_mend_id).val(neweDate);
  } else {
    $(mile_mend_id).val("");
  }
}

// to check internet connection
function checkNetConnection() {
  jQuery.ajaxSetup({ async: false });
  re = "";
  r = Math.round(Math.random() * 10000);
  $.get(
    "https://1.bp.blogspot.com/-LtDtdVE1roA/UmAavs_T_iI/AAAAAAAADNY/g0L-HAPlkTY/s1600/0060.png", //logo
    { subins: r },
    function(d) {
      re = true; 
    }
  ).error(function() {
    sweet_alert("You have lost Internet Connection");
    re = false;
  });
  return re;
}

setInterval(function() {
  var conn = checkNetConnection();
  if (conn) {
    $("#tag-form-submit").removeClass("btn-danger");
    $("#tag-form-submit").addClass("btn-primary");
  } else {
    $("#tag-form-submit").removeClass("btn-primary");
    $("#tag-form-submit").addClass("btn-danger");
  }
}, 3000);

$("#tag-form-submit").click(function(e) {
  e.preventDefault();
  var internet_handler = checkNetConnection();

  if (internet_handler) {
    $("#form").validate({
      ignore: [],
      errorPlacement: function(error, element) {
        var lastError = $(element).data("lastError"),
          newError = $(error).text();

        $(element).data("lastError", newError);

        if (newError !== "" && newError !== lastError) {
          $(element).after('<div class="red">The field is Required</div>');
        }
      },
      success: function(label, element) {
        $(element)
          .next(".red")
          .remove();
      }
    });
    var isValid = true;
    var curInputs = $("#form").find("input, select");
    for (var i = 0; i < curInputs.length; i++) {
      if (!$(curInputs[i]).valid()) {
        isValid = false;
      }
    }
    if (isValid) {
      var formData = $("#form").serialize();
      $.ajax({
        type: "post",
        url: "assets/processor/add-procurement-details-process",
        data: formData,
        dataType: "json",
        success: function(response) {
          sweet_alert("Success", response);
          window.location.href = "add-project-procurement-details";
        }
      });
    }
  }
});

$(".careted").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa fa-caret-down fa fa-caret-up");
});

// function disable refreshing functionality
function disable_refresh() {
  //
  return (window.onbeforeunload = function(e) {
    return "you can not refresh the page";
  });
}