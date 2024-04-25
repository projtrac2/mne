$(document).ready(function () {
  projectsTable = $("#projectsTable").DataTable({
    ajax: "general-settings/selected-items/fetch-approved-projects",
    order: [],
    columnDefs: [
      {
        targets: [6],
        orderable: false
      }
    ]
  });
  $("#hidden").hide();
});


//function to put commas to the data
function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

function change_duration(opid = null) {
  if (opid) {
    var hopduration = parseInt($(`#hopduration${opid}`).val());
    var opduration = $(`#opduration${opid}`).val();

    if (opduration != "") {
      opduration = parseInt(opduration);
      if (opduration > 0) {
        if (opduration > hopduration) {
          alert(`You cannot go higher than te initial defined duration of ${hopduration}`);
          $(`#opduration${opid}`).val(hopduration);
          // get_op_target_div(opid, target_plan = 1, hopduration);
        } else {
          get_op_target_div(opid, target_plan = 1, opduration);
        }
      } else {
        alert("Output duration should be greater than 0");
        // get_op_target_div(opid, hopduration);
        $(`#opduration${opid}`).val(hopduration);
      }
    } else {
      alert("Output duration should not be empty");
      $(`#opduration${opid}`).val(hopduration);
    }
  }
}

// get workplan target div from the db 
function get_op_target_div(opid, target_plan, duration = null, ) {
  var prograid = $("#progid").val();
  var new_op_bal = $(`#optotaltarget${opid}`).val();

  if (opid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: {
        get_target_div: "get_target_div",
        outputid: opid,
        duration: duration,
        prograid: prograid,
        target_plan: target_plan,
        opbal: new_op_bal
      },
      dataType: "html",
      success: function (response) {
        $("#target_div_" + opid).html(response);
      }
    });
  }
}

// function to add workplan target divs
function add_op_targets_div(opid) {
  $(".elementT:last").after("<div class='element' id='target_div_" + opid + "'></div>");
  get_op_target_div(opid);
}


// function to get the remaining for each year 
function get_op_sum_target(opid, year) {
  let ceiling_year_target = parseInt($("#cyear_target" + opid + "" + year).val());
  let total_sum1 = $("#target_year" + opid + "" + year).val();

  if (total_sum1 != "") {
    let total_sum = parseInt(total_sum1);
    if (total_sum > 0) {
      let remainder = ceiling_year_target - total_sum;
      if (remainder < 0) {
        alert("The target can not exceed the ceiling");
        $("#target_year" + opid + "" + year).val("");
        $("#year_target" + opid + "" + year).html(ceiling_year_target);
      } else {
        $("#year_target" + opid + "" + year).html(remainder);
        get_sum_opyear(opid, year);
      }
    } else {
      $("#target_year" + opid + "" + year).val("");
      alert("Enter a value greater than 0");
      $("#year_target" + opid + "" + year).html(ceiling_year_target);
    }
  } else {
    $("#target_year" + opid + "" + year).val("");
    alert("This field cannot be empty");
    $("#year_target" + opid + "" + year).html(ceiling_year_target);
  }
}

// check the tota sum 
function get_sum_opyear(opid, year) {
  let ceiling_year_target = parseInt($(`#coptarget_target${opid}`).val());
  let cyear_target = parseInt($(`#cyear_target${opid}${year}`).val());

  let opid_name = $(`#opid_name${opid}`).val();
  let total_sum = 0;

  $(`.workplanTarget${opid}`).each(function () {
    if ($(this).val() != "") {
      total_sum = total_sum + parseInt($(this).val());
    }
  });

  let remainder = ceiling_year_target - total_sum;

  if (remainder < 0) {
    alert(`The sum total of year values cannot exceed  ${opid_name} ceiling(${ceiling_year_target})`);
    $(`#target_year${opid}${year}`).val("");
    let total_sum = 0;
    $(`.workplanTarget${opid}`).each(function () {
      if ($(this).val() != "") {
        total_sum = total_sum + parseInt($(this).val());
      }
    });

    let remainder = ceiling_year_target - total_sum;
    $(`#op_target${opid}`).html(remainder);
    $("#year_target" + opid + "" + year).html(cyear_target);
  } else {
    $(`#op_target${opid}`).html(remainder);
  }
}

// function to calculate budget
function onKeyUpBudget(rowno) {

  var projoutputceiling = `#projoutputceiling${rowno}`
  var outputbudgetceil = parseInt($(`#outputbudgetceil${rowno}`).val());
  var projcost = $(`#projcost${rowno}`).val();

  if (projcost != "") {
    projcost = parseInt(projcost);
    if (projcost > 0) {
      var remainder = outputbudgetceil - projcost;
      if (remainder < 0) {
        $(`#projcost${rowno}`).val(outputbudgetceil);
        $(projoutputceiling).html(commaSeparateNumber(0));
        alert("Cost cannot be less than 0");
        calculateDifference();
      } else {
        $(projoutputceiling).html(commaSeparateNumber(remainder));
        calculateDifference();
      }
    } else {
      $(`#projcost${rowno}`).val(outputbudgetceil);
      $(projoutputceiling).html(commaSeparateNumber(0));
      alert("Cost cannot be less than 0");
      calculateDifference();
    }
  }
}



// calculate Financier Contribution
function amountfunding(rowno) {
  var ceilingval = $("#source_val" + rowno).val();
  var amountfunding = $("#amountfunding" + rowno).val();
  var source_categoryrow = $("#source_category" + rowno).val();
  var source_category_val = parseFloat($("#source_category_val" + rowno).val());
  var amount_contribute_id = $(".amount_funding_valrow" + source_categoryrow);

  var amount_contribute_val = 0;
  $(amount_contribute_id).each(function () {
    if ($(this).val()) {
      amount_contribute_val = parseFloat($(this).val()) + amount_contribute_val;
    }
  });

  if (ceilingval) {
    if (amountfunding) {
      if (parseInt(amountfunding) > 0) {
        var diff = source_category_val - amount_contribute_val;
        if (diff >= 0) {
          var remaining = parseInt(ceilingval) - parseInt(amountfunding);
          $(".source_category_span_row" + source_categoryrow).html(commaSeparateNumber(diff));
          if (remaining < 0) {
            var source_text = $(`#source_val${rowno}  option:selected`).html();
            alert(`You have crossed the ${source_text} Fund limit`);
            $("#source_span_" + rowno).html(
              commaSeparateNumber(parseInt(ceilingval))
            );
            $("#amountfunding" + rowno).val("");
            financierDifference(rowno);
            var amount_contribute_val = 0;
            $(amount_contribute_id).each(function () {
              if ($(this).val()) {
                amount_contribute_val = parseFloat($(this).val()) + amount_contribute_val;
              }
            });
            var diff = source_category_val - amount_contribute_val;
            $(".source_category_span_row" + source_categoryrow).html(commaSeparateNumber(diff));
          } else {
            $("#source_span_" + rowno).html(commaSeparateNumber(remaining));
            financierDifference(rowno);
          }
        } else {
          var cat_text = $(`#source_category${rowno} option:selected`).html();
          alert(`You have crossed the ${cat_text} Fund limit`);
          $("#source_span_" + rowno).html(commaSeparateNumber(ceilingval));
          $("#amountfunding" + rowno).val("");
          financierDifference(rowno);
          var amount_contribute_val = 0;
          $(amount_contribute_id).each(function () {
            if ($(this).val()) {
              amount_contribute_val = parseFloat($(this).val()) + amount_contribute_val;
            }
          });
          var diff = source_category_val - amount_contribute_val;
          $(".source_category_span_row" + source_categoryrow).html(commaSeparateNumber(diff));
        }
      } else {
        alert("Hey the value should be greater than 0 ");
        $("#source_span_" + rowno).html(
          commaSeparateNumber(parseInt(ceilingval))
        );

        $("#amountfunding" + rowno).val("");
        financierDifference(rowno);
      }
    } else {
      alert("This field cannot be empty ");
      $("#source_span_" + rowno).html(
        commaSeparateNumber(parseInt(ceilingval))
      );
      $("#amountfunding" + rowno).val("");
      financierDifference(rowno);
    }
  } else {
    alert("Select Source First");
    $("#source_span_" + rowno).html(
      commaSeparateNumber(parseInt(ceilingval))
    );
    $("#amountfunding" + rowno).val("");
    financierDifference(rowno);
  }
}

// calculate The financier output budget difference onKeyUpBudget()
function calculateDifference() {
  var sumOutputBudget = 0;
  $("input[name='projcost[]']").each(function () {
    if ($(this).val()) {
      sumOutputBudget = parseFloat($(this).val()) + sumOutputBudget;
    }
  });

  var financierContribution = 0;
  $("input[name='amountfunding[]']").each(function () {
    if ($(this).val()) {
      financierContribution = parseFloat($(this).val()) + financierContribution;
    }
  });

  var difference = sumOutputBudget - financierContribution;

  if (difference >= 0) {
    $("#financierceiling").val(commaSeparateNumber(difference));
    $("#outputcost").val(difference);
  } else {
    $("input[name='amountfunding[]']").each(function () {
      $(this).val("");
    });
    $("#financierceiling").val(commaSeparateNumber(sumOutputBudget));
    $("#outputcost").val(sumOutputBudget);
  }
}

// calculate The financier output budget difference amountfunding()
function financierDifference(rowno) {
  var sumOutputBudget = 0;
  $("input[name='projcost[]']").each(function () {
    if ($(this).val()) {
      sumOutputBudget = parseFloat($(this).val()) + sumOutputBudget;
    }
  });

  var financierContribution = 0;
  $("input[name='amountfunding[]']").each(function () {
    if ($(this).val()) {
      financierContribution = parseFloat($(this).val()) + financierContribution;
    }
  });

  var difference = sumOutputBudget - financierContribution;
  if (difference >= 0) {
    $("#financierceiling").val(commaSeparateNumber(difference));
    $("#outputcost").val(difference);
  } else {
    $("#amountfunding" + rowno).val("");
    alert("You cannot enter more funds than output Budget");
    var recalculate = 0;
    $("input[name='projcost[]']").each(function () {
      if ($(this).val()) {
        recalculate = parseFloat($(this).val()) + recalculate;
      }
    });

    var financier = 0;
    $("input[name='amountfunding[]']").each(function () {
      if ($(this).val() == "") {
        financier = false;
      }
    });

    var diff = recalculate - financier;

    $("#financierceiling").val(commaSeparateNumber(diff));
    $("#outputcost").val(diff);
  }
}

function category_fund_validate(rowno) {
  var source_categoryrow = $("#source_categoryrow" + rowno).val();
  var source_category_val = parseFloat($("#source_category_valrow" + rowno).val());
  var amount_contribute_id = $(".amount_funding_valrow" + source_categoryrow);
  var amount_contribute_val = 0;

  if (source_category_val) {
    $(amount_contribute_id).each(function () {
      if ($(this).val()) {
        amount_contribute_val = parseFloat($(this).val()) + amount_contribute_val;
      }
    });

    var diff = source_category_val - amount_contribute_val;
    if (diff >= 0) {
      return diff;
    } else {
      return false;
    }
  } else {
    return false;
  }
}


// adjusting total Targets 
function target_change(opid = null) {
  if (opid) {
    var target_ceil = parseInt($(`#projoutputtargetceilingValue${opid}`).val());
    var optotaltarget = $(`#optotaltarget${opid}`).val();
    if (optotaltarget != "") {
      optotaltarget = parseInt(optotaltarget);
      if (optotaltarget > target_ceil) {
        alert(`Hey you cannot exceed the defined ceiling ${target_ceil}`);
        $(`#optotaltarget${opid}`).val(target_ceil);
      } else {
        get_op_target_div(opid, target_plan = 2);
        add_targets_div(opid, target_plan = 1);
      }
    } else {
      $(`#optotaltarget${opid}`).val(target_ceil);
    }
  }
}

// get the location target div from db 
function get_loc_target_div(opid, target_plan) {
  if (opid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: { opid: opid, target_plan: target_plan, get_location_diss: "new" },
      dataType: "html",
      success: function (response) {
        $("#div_" + opid).html(response);
      }
    });
  }
}

// add location target divs 
function add_targets_div(opid) {
  $(".element:last").after("<div class='element' id='div_" + opid + "'></div>");
  get_loc_target_div(opid, target_plan = 2);
}

// delete the target divs 
function del_targets_div(opid) {
  $("#div_" + opid).remove();
}

function target_state_distribution(opid, stateid) {
  // var loc_hand = locations_handler();
  // if (loc_hand) {
  //   var con_handler = confirm("Do you wish to alter the value of state");
  //   if (con_handler) {

  //   } else {

  //   }
  // }

  var state_ceil = $(`#ceilinglocation_target${stateid}${opid}`).val();
  var optotaltarget = $(`#optotaltarget${opid}`).val();
  if (state_ceil != "") {
    if (parseInt(state_ceil) >= 0) {
      if (optotaltarget != "") {
        optotaltarget = parseInt(optotaltarget);
        var sum_target = 0;
        $(`.state_diss${opid}`).each(function () {
          if ($(this).val() != "") {
            sum_target = sum_target + parseInt($(this).val());
          }
        });

        var remainder = optotaltarget - sum_target;
        if (remainder < 0) {
          alert(`Ensure your target adds up to the defined target above of ${optotaltarget}`);
          $(`#ceilinglocation_target${stateid}${opid}`).val("");
          $(`#state_ceil${stateid}${opid}`).html("");
          validate_fields(opid, stateid);
        } else {
          var state_ceil = $(`#ceilinglocation_target${stateid}${opid}`).val();
          $(`#state_ceil${stateid}${opid}`).html(commaSeparateNumber(state_ceil));
          validate_fields(opid, stateid);
        }
      } else {
        alert("You have to enter totatal target first ");
      }
    } else {
      $(`#ceilinglocation_target${stateid}${opid}`).val("");
      $(`#state_ceil${stateid}${opid}`).html("");
    }
  } else {
    $(`#ceilinglocation_target${stateid}${opid}`).val("");
    $(`#state_ceil${stateid}${opid}`).html("");
  }
}

function validate_fields(opid, stateid) {
  var optotaltarget = parseInt($(`#optotaltarget${opid}`).val());
  var sum_target = 0;
  var handler = [];

  $(`.state_diss${opid}`).each(function () {
    if ($(this).val() != "") {
      sum_target = sum_target + parseInt($(this).val());
      handler.push(true);
    } else {
      handler.push(false);
    }
  });

  var remainder = optotaltarget - sum_target;
  var data = handler.includes(false);

  if (remainder == 0 && !data) {
    $(`.loc_op${opid}`).each(function () {
      $(this).val("");
      $(this).removeAttr("disabled");
    });
  } else {
    $(`.loc_op${opid}`).each(function () {
      $(this).val("");
      $(this).attr("disabled", "disabled");
    });
  }
}

function locations_handler() {
  var sum_target = 0;
  var handler = [];
  $(`.state_diss${opid}${stateid}`).each(function () {
    if ($(this).val() != "") {
      sum_target = sum_target + parseInt($(this).val());
      handler.push(true);
    } else {
      handler.push(false);
    }
  });

  if (includes(true)) {
    return true;
  } else {
    return false;
  }
}

// get sum of the dissegragated divs 
function get_sum(state, id) {
  var location_target = $("#locate_numb" + id).val();


  if (location_target != "") {
    location_target = parseInt(location_target);
    if (location_target >= 0) {
      var opid = $("#locate_numb" + id).attr("data-id");
      var level3label = $(`#level3label${state}${opid}`).val();
      var unit = $(`#unitName${state}${opid}`).val();

      let ceilinglocation_target = parseInt($("#ceilinglocation_target" + state + "" + opid).val());

      let total_sum = 0;
      $(".locate_total" + state + "" + opid).each(function () {
        if ($(this).val() != "") {
          total_sum = total_sum + parseInt($(this).val());
        }
      });

      let remainder = ceilinglocation_target - total_sum;

      if (remainder < 0) {
        alert(`The sum total of disaggregation values cannot exceed  ${level3label} ceiling ${ceilinglocation_target} ${unit}`);
        $("#locate_numb" + id).val("");
        let total_sum = 0;
        $(".locate_total" + state + "" + opid).each(function () {
          if ($(this).val() != "") {
            total_sum = total_sum + parseInt($(this).val());
          }
        });
        let remainder = ceilinglocation_target - total_sum;
        $("#state_ceil" + state + "" + opid).html(`${remainder} ${unit}`);
      } else {
        $("#state_ceil" + state + "" + opid).html(`${remainder} ${unit}`);
      }
    } else {
      $("#locate_numb" + id).val("");
    }
  } else {
    $("#locate_numb" + id).val();
  }
}

//validate the summation of all location targets  the outputs 
function validate_location_state() {
  var ophandler = [];
  var stateFields = '';

  var opid = [];
  $("input[name='projoutputid[]']").each(function () {
    if ($(this).val() != "") {
      opid.push($(this).val());
    }
  });

  for ($i = 0; $i < opid.length; $i++) {
    var outputstateclass = ".outputstate" + opid[$i];
    var output = $(`#locate_opid${opid[$i]}`).val();
    stateFields = stateFields + `Under ${output}: `;

    $(outputstateclass).each(function () {
      if ($(this).val() != "") {
        var state = $(this).val();
        var stateName = $(this).attr("data-id");
        var state_ceiling = parseInt($("#ceilinglocation_target" + state + "" + opid[$i]).val());
        let total_sum = 0;

        $(".locate_total" + state + "" + opid[$i]).each(function () {
          if ($(this).val() != "") {
            total_sum = total_sum + parseInt($(this).val());
          }
        });

        var remaining = state_ceiling - total_sum;
        var outputName = $("#locate_opid" + opid[$i]).val();
        var unit = $(`#unitName${state}${opid[$i]}`).val();

        if (remaining != 0) {
          stateFields = stateFields + `${stateName} ${unit} should be ${state_ceiling} and you have only used  ${total_sum}.`;
          ophandler.push(false);
        } else {
          ophandler.push(true);
        }
      }
    });
  }

  if (ophandler.includes(false)) {
    alert(stateFields);
    return false;
  } else {
    return true;
  }
}


