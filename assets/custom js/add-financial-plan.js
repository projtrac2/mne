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
  setTimeout(function () { }, 15000);
}

// delete financial data that are incomplete
function delete_finacial_plan(projid) {
  if (projid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-financial-plan-process",
      data: {
        emptyTables: "emptyTables",
        projid: projid
      },
      dataType: "json",
      success: function (response) {
        sweet_alert("Success", response);
      }
    });
  }
}

// function disable refreshing functionality
function disable_refresh() {
  return (window.onbeforeunload = function (e) {
    return "you can not refresh the page";
  });
}

// function to calculate remaining budget
function ouput_used_cost(opid) {
  var output_budget = parseFloat($("#outputcost" + opid).val());
  var projbudget = parseFloat($("#projcost").val());
  if (output_budget) {
    var used_budget = 0;
    $(".output_cost" + opid).each(function () {
      if ($(this).val() != "") {
        used_budget = used_budget + parseFloat($(this).val());
      }
    });

    var summarytotal = 0;
    $(".summarytotal").each(function () {
      if ($(this).val() != "") {
        summarytotal = summarytotal + parseFloat($(this).val());
      }
    });

    var remaining = output_budget - used_budget; //calculate remaining output budget
    var summary_percentage = (summarytotal / projbudget) * 100;
    var sub_total_percentage =(used_budget/output_budget) * 100;
    if (remaining >= 0) {
      $(".output_cost_bal" + opid).val(commaSeparateNumber(remaining));
      $("#summaryOutput" + opid).html(commaSeparateNumber(used_budget));
      $("#perc" + opid).html(sub_total_percentage.toFixed(2) + "%");
      $("#summary_total").html(commaSeparateNumber(summarytotal));
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return true;
    } else {
      $(".output_cost_bal" + opid).val(commaSeparateNumber(remaining));
      $("#summaryOutput" + opid).html(commaSeparateNumber(used_budget));
      $("#perc" + opid).html(sub_total_percentage.toFixed(2) + "%");
      $("#summary_total").html(commaSeparateNumber(summarytotal));
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return false;
    }
  }
}

function cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid) {
  var output_budget = parseFloat($("#outputcost" + opid).val());
  var sub_total_val = 0;
  $(sub_total_class).each(function () {
    if ($(this).val() != "") {
      sub_total_val = sub_total_val + parseFloat($(this).val());
    }
  });

  var sub_total_percentage = (sub_total_val / output_budget) * 100; //calculate percentage of the given div
  $(sub_total_aid).val(commaSeparateNumber(sub_total_val));
  $(sub_total_pid).val(sub_total_percentage.toFixed(2) + "%");
  return true;
}

// function  to calculate the total cost of a task
function totalCost(tkid, opid, number, rowno) {
  var sub_total_pid = "";
  var sub_total_aid = "";
  var sub_total_class = "";
  var total_cost_id = "";
  var total_unit_id = "";
  var unit_cost_id = "";

  var htotal_cost_id = "";
  var htotal_unit_id = "";
  var hunit_cost_id = "";

  var remark_id = "";
  var financier_id = "";
  var timeline_id = "";
  if (number == "1") {
    sub_total_pid = "#sub_total_percentage" + number + opid;
    sub_total_aid = "#sub_total_amount" + number + opid;
    sub_total_class = ".direct_sub_total_amount" + number + opid;

    total_cost_id = "#dtotalcost" + opid + tkid + rowno;
    total_unit_id = "#dtotalunits" + opid + tkid + rowno;
    unit_cost_id = "#dunitcost" + opid + tkid + rowno;

    remark_id = "#drmkid" + number + opid + tkid + rowno;
    financier_id = "#dfinid" + number + opid + tkid + rowno;
    timeline_id = "#dtimeline" + number + opid + tkid + rowno;

    // history
    htotal_cost_id = "#dhtotalcost" + opid + tkid + rowno;
    htotal_unit_id = "#dhtotalunits" + opid + tkid + rowno;
    hunit_cost_id = "#dhunitcost" + opid + tkid + rowno;

  } else if (number == "2") {
    sub_total_pid = "#sub_total_percentage" + number + opid;
    sub_total_aid = "#sub_total_amount" + number + opid;
    sub_total_class = ".direct_sub_total_amount" + number + opid;

    total_cost_id = "#totalcost" + tkid;
    total_unit_id = "#totalunits" + tkid;
    unit_cost_id = "#unitcost" + tkid;

    // history
    htotal_cost_id = "#htotalcost" + tkid;
    htotal_unit_id = "#htotalunits" + tkid;
    hunit_cost_id = "#hunitcost" + tkid;

    remark_id = "#rmkid" + number + tkid;
    financier_id = "#finid" + number + tkid;
    timeline_id = "#rmkid" + number + tkid;
  } else if (number == "3") {
    total_cost_id = "#totalcost" + tkid;
    total_unit_id = "#totalunits" + tkid;
    unit_cost_id = "#unitcost" + tkid;

    // history
    htotal_cost_id = "#htotalcost" + tkid;
    htotal_unit_id = "#htotalunits" + tkid;
    hunit_cost_id = "#hunitcost" + tkid;

    remark_id = "#rmkid3" + tkid;
    financier_id = "#finid3" + tkid;
    timeline_id = "#rmkid3" + tkid;

    var budget_line_id = $(total_unit_id).attr("data-id");
    var id = budget_line_id;
    sub_total_aid = "#sub_total_amount3" + $.trim(id);
    sub_total_pid = "#sub_total_percentage3" + $.trim(id);
    sub_total_class =
      ".direct_sub_total_amount" + number + $.trim(budget_line_id);
  }

  var totalUnits = parseInt($(total_unit_id).val());
  var unitCost = parseFloat($(unit_cost_id).val());
  var remarks = $(remark_id).val();
  var financiers = $(financier_id).val();
  var distr_timeline = $(financier_id).val();
  var projid = $("#projid").val();

  if (financiers == "" && remarks == "") {
    if (unitCost > 0) {
      if (totalUnits > 0) {
        var taskCost = unitCost * totalUnits;
        $(total_cost_id).val(taskCost);
        var total_output_Cost = ouput_used_cost(opid);
        if (total_output_Cost) {
          var total_output_Cost = ouput_used_cost(opid);
          cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
        } else {
          var msg = "Ensure the amount should not exceed Output Budget";
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
  } else {
    var handler = delete_other_info(opid, tkid, number, rowno);
    if (handler) {
      if (unitCost > 0) {
        if (totalUnits > 0) {
          var taskCost = unitCost * totalUnits;
          $(total_cost_id).val(taskCost);
          var total_output_Cost = ouput_used_cost(opid);
          if (total_output_Cost) {
            var total_output_Cost = ouput_used_cost(opid);
            cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
          } else {
            var msg = "Ensure the amount should not exceed Output Budget";
            sweet_alert("Error!!!", msg);

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
        $(total_cost_id).val("");
        $(total_unit_id).val("");
        var total_output_Cost = ouput_used_cost(opid);
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
      }
    } else {
      var htotal_cost = $(htotal_cost_id).val();
      var htotal_unit = $(htotal_unit_id).val();
      var hunit_cost = $(hunit_cost_id).val();

      $(total_cost_id).val(hunit_cost);
      $(total_unit_id).val(htotal_unit);
      $(total_cost_id).val(htotal_cost);
    }
  }
}

//function to listen for changes in units
// and also change in no of units
function number_of_units_change(tkid, opid, number, rowno) {
  var sub_total_pid = "";
  var sub_total_aid = "";
  var sub_total_class = "";
  var total_cost_id = "";
  var total_unit_id = "";
  var unit_cost_id = "";
  var remark_id = "";
  var financier_id = "";
  var timeline_id = "";
  var htotal_cost_id = "";
  var htotal_unit_id = "";
  var hunit_cost_id = "";

  if (number == "1") {
    sub_total_pid = "#sub_total_percentage" + number + opid;
    sub_total_aid = "#sub_total_amount" + number + opid;
    sub_total_class = ".direct_sub_total_amount" + number + opid;

    total_cost_id = "#dtotalcost" + opid + tkid + rowno;
    total_unit_id = "#dtotalunits" + opid + tkid + rowno;
    unit_cost_id = "#dunitcost" + opid + tkid + rowno;

    // history
    htotal_cost_id = "#dhtotalcost" + opid + tkid + rowno;
    htotal_unit_id = "#dhtotalunits" + opid + tkid + rowno;
    hunit_cost_id = "#dhunitcost" + opid + tkid + rowno;

    remark_id = "#drmkid" + number + opid + tkid + rowno;
    financier_id = "#dfinid" + number + opid + tkid + rowno;
    timeline_id = "#drmkid" + number + opid + tkid + rowno;
  } else if (number == "2") {
    sub_total_pid = "#sub_total_percentage" + number + opid;
    sub_total_aid = "#sub_total_amount" + number + opid;
    sub_total_class = ".direct_sub_total_amount" + number + opid;

    total_cost_id = "#totalcost" + tkid;
    total_unit_id = "#totalunits" + tkid;
    unit_cost_id = "#unitcost" + tkid;

    // history
    htotal_cost_id = "#htotalcost" + tkid;
    htotal_unit_id = "#htotalunits" + tkid;
    hunit_cost_id = "#hunitcost" + tkid;

    remark_id = "#rmkid" + number + tkid;
    financier_id = "#finid" + number + tkid;
    timeline_id = "#rmkid" + number + tkid;
  } else if (number == "3") {
    total_cost_id = "#totalcost" + tkid;
    total_unit_id = "#totalunits" + tkid;
    unit_cost_id = "#unitcost" + tkid;

    // history
    htotal_cost_id = "#htotalcost" + tkid;
    htotal_unit_id = "#htotalunits" + tkid;
    hunit_cost_id = "#hunitcost" + tkid;

    remark_id = "#rmkid3" + tkid;
    financier_id = "#finid3" + tkid;
    timeline_id = "#rmkid3" + tkid;

    var budget_line_id = $(total_unit_id).attr("data-id");
    var id = budget_line_id;
    sub_total_aid = "#sub_total_amount3" + $.trim(id);
    sub_total_pid = "#sub_total_percentage3" + $.trim(id);
    sub_total_class =
      ".direct_sub_total_amount" + number + $.trim(budget_line_id);
  }

  var totalUnits = parseInt($(total_unit_id).val());
  var unitCost = parseFloat($(unit_cost_id).val());
  var remarks = $(remark_id).val();
  var financiers = $(financier_id).val();
  var distr_timeline = $(timeline_id).val();
  var projid = $("#projid").val();

  if (financiers == "" && remarks == "") {
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
            var msg = "Ensure that amount is equal to output cost";
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
  } else {
    var handler = delete_other_info(opid, tkid, number, rowno);
    if (handler) {
      if (unitCost > 0) {
        if (totalUnits > 0) {
          var taskCost = unitCost * totalUnits;
          $(total_cost_id).val(taskCost);
          var total_output_Cost = ouput_used_cost(opid);
          if (total_output_Cost) {
            var total_output_Cost = ouput_used_cost(opid);
            cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
          } else {
            var msg = "Ensure the amount should not exceed Output Budget";
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
        $(total_cost_id).val("");
        $(total_unit_id).val("");
        var total_output_Cost = ouput_used_cost(opid);
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
      }
    } else {
      var htotal_cost = $(htotal_cost_id).val();
      var htotal_unit = $(htotal_unit_id).val();
      var hunit_cost = $(hunit_cost_id).val();
      $(unit_cost_id).val(hunit_cost);
      $(total_unit_id).val(htotal_unit);
      $(total_cost_id).val(htotal_cost);
    }
  }
}

// function to add new rowfor financiers
$rowno = $("#financier_table_body tr").length;
function add_row_financier() {
  $("#removeTr").remove(); //new change
  $rowno = $rowno + 1;
  $("#financier_table_body tr:last").after(
    '<tr id="financierrow' +
    $rowno +
    '">' +
    "<td></td>" +
    "<td>" +
    '<select onchange=financeirChange("row' +
    $rowno +
    '") data-id="' +
    $rowno +
    '" name="finance[]" id="financerow' +
    $rowno +
    '" class="form-control validoutcome selectedfinance" required="required">' +
    '<option value="">Select Financier from list</option>' +
    "</select>" +
    "</td>" +
    "<td>" +
    '<input type="hidden" name="ceilingval[]"  id="ceilingvalrow' +
    $rowno +
    '" /><span id="currrow' +
    $rowno +
    '"></span> ' +
    '<span id="financierCeilingrow' +
    $rowno +
    '" style="color:red"></span>' +
    "</td>" +
    "<td>" +
    '<input type="number" name="amountfunding[]" onkeyup=amountfunding("row' +
    $rowno +
    '") onchange=amountfunding("row' +
    $rowno +
    '")  id="amountfundingrow' +
    $rowno +
    '"   placeholder="Enter amount"  class="form-control financierTotal" required/>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financierrow' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  getFinanciers($rowno);
  numbering_financier();
}

// function to delete financiers row
function delete_row_financier(rowno) {
  var financierId = "#financierId" + rowno;
  if ($(financierId).length > 0) {
    var confirmation = confirm("Are you sure you want to delete the financier");
    if (confirmation) {
      $("#" + rowno).remove();
      numbering_financier();
      validate_against_output_cost();
      delete_financier(financierId);
      $check = $("#financier_table_body tr").length;
      if ($check == 1) {
        $("#financier_table_body").html(
          "<tr></tr>" +
          '<tr id="removeTr">' +
          '<td colspan="5">Add Financiers</td>' +
          "</tr>"
        );
      }
    }
  } else {
    $("#" + rowno).remove();
    numbering_financier();
    validate_against_output_cost();

    $check = $("#financier_table_body tr").length;
    if ($check == 1) {
      $("#financier_table_body").html(
        "<tr></tr>" +
        '<tr id="removeTr">' +
        '<td colspan="5">Add Financiers</td>' +
        "</tr>"
      );
    }
  }
}

// auto numbering table rows on delete and add new for financier table
function numbering_financier() {
  $("#financier_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function delete_financier(financierId) {
  var financier = $(financierId).val();
  if (financierId) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-financial-plan-process",
      data: {
        deleteFinancier: "delete",
        deletefinid: financier
      },
      dataType: "json",
      success: function (response) {
        sweet_alert("Error !!!", response);
        $(financierId).remove();
      }
    });
  }
}

//get financiers
function getFinanciers(rowno) {
  var projid = $("#projid").val();
  var financier = "#financerow" + rowno;
  $.ajax({
    type: "post",
    url: "assets/processor/add-financial-plan-process",
    data: {
      getfinancier: projid
    },
    dataType: "html",
    success: function (response) {
      $(financier).html(response);
    }
  });
}

//filter the output cannot be selected twice
$(document).on("change", ".selectedfinance", function (e) {
  var tralse = true;
  var selectImpact_arr = [];
  var attrb = $(this).attr("id");
  var rowno = $(this).attr("data-id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();
  var handler = true;

  var finance_input = $("input[name='financierId[]']").length;

  if (finance_input > 0) {
    handler = confirm("Are you sure you want to change");
    if (handler) {
      $(".selectedfinance").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
          tralse = false;
          alert("You canot select Output " + selectedText + " more than once ");
          var rw = $(v).attr("data-id");
          var amountfundingrow = "#amountfundingrow" + rw;
          var ceilingvalrow = "#ceilingvalrow" + rw;
          $(v).val("");
          $(amountfundingrow).val("");
          $(ceilingvalrow).val("");
          return false;
        } else {
          selectImpact_arr.push($(v).val());
        }
      });
    } else {
      var financier = $("#financierIdfinancierrow" + rowno).val();
      var ceilingval = $("#hceilingvalrow" + rowno).val();
      $("#financerow" + rowno).val(financier);
      $("#ceilingvalrow" + rowno).val(ceilingval);
      $("#financierCeilingrow" + rowno).html(commaSeparateNumber(ceilingval));
    }
  } else {
    $(".selectedfinance").each(function (k, v) {
      var getVal = $(v).val();
      if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
        tralse = false;
        alert("You canot select Output " + selectedText + " more than once ");
        var rw = $(v).attr("data-id");
        var amountfundingrow = "#amountfundingrow" + rw;
        var ceilingvalrow = "#ceilingvalrow" + rw;
        $(v).val("");
        $(amountfundingrow).val("");
        $(ceilingvalrow).val("");
        return false;
      } else {
        selectImpact_arr.push($(v).val());
      }
    });
  }

  if (!tralse) {
    return false;
  }
});

function financeirChange(rowno) {
  var finance = $("#finance" + rowno).val();
  var financierCeiling = "#financierCeiling" + rowno;
  var projid = $("#projid").val();

  if (finance) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-financial-plan-process",
      data: {
        cfinance: "finance",
        financeId: finance,
        projid: projid
      },
      dataType: "json",
      success: function (response) {
        var finance = $("#finance" + rowno).val();
        if (finance) {
          if (response.msg == "true") {
            var responseval = response.remaining;
            $(financierCeiling).html(
              commaSeparateNumber(parseFloat(responseval))
            );
            $("#ceilingval" + rowno).val(responseval);
            validate_against_output_cost();
          } else if (response.msg == "false") {
            $("#ceilingval" + rowno).val("No Balance");
            $("#finance" + rowno).val("");
            $(financierCeiling).html("");
            validate_against_output_cost();
          }
        } else {
          $("#ceilingval" + rowno).val("No Balance");
          $("#finance" + rowno).val("");
          $(financierCeiling).html("");
          validate_against_output_cost();
        }
      }
    });
  } else {
    var msg = "Select Financier";
    sweet_alert("Error !!!", response);
    $(financierCeiling).html("");
    $("#amountfunding" + rowno).val("");
  }
}

function validate_against_output_cost() {
  var outputCost = parseFloat($("#output_cost_fi_celing").val());
  var financierTotal = 0;
  $(".financierTotal").each(function () {
    if ($(this).val() != "") {
      financierTotal = financierTotal + parseFloat($(this).val());
    }
  });

  var remaining = outputCost - financierTotal;

  if (remaining >= 0) {
    $("#output_cost_ceiling").html(
      "Total Cost Ksh:" + commaSeparateNumber(remaining)
    );
    return true;
  } else {
    $("#output_cost_ceiling").html(
      "Total Cost Ksh:" + commaSeparateNumber(remaining)
    );
    msg = "Ensure that you don't cross the plan budget limit";
    sweet_alert("Error !!!", msg);
    return false;
  }
}

function amountfunding(rowno) {
  var ceilingval = $("#ceilingval" + rowno).val();
  var amountfunding = $("#amountfunding" + rowno).val();

  var financierCeilingId = "#financierCeiling" + rowno;
  var amountfundingId = "#amountfunding" + rowno;

  // according to the ceiling of the financier
  if (ceilingval) {
    if (amountfunding) {
      if (parseFloat(amountfunding) > 0) {
        var handler = validate_against_output_cost();
        if (handler) {
          var remaining = parseFloat(ceilingval) - parseFloat(amountfunding);
          if (remaining < 0) {
            var msg = "The value entered cannot be less than 0 ";
            sweet_alert("Error !!!", msg);
            $(financierCeilingId).html(
              commaSeparateNumber(parseFloat(ceilingval))
            );
            $(amountfundingId).val("");
          } else {
            $(financierCeilingId).html(commaSeparateNumber(remaining));
            validate_against_output_cost();
          }
        } else {
          $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
          $(amountfundingId).val("");
          validate_against_output_cost();
        }
      } else {
        var msg = "The value should be greater than 0 ";
        sweet_alert("Error !!!", msg);

        $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
        $(amountfundingId).val("");
        validate_against_output_cost();
      }
    } else {
      var msg = "This field cannot be empty ";
      $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
      $(amountfundingId).val("");
      validate_against_output_cost();
    }
  } else {
    var msg = "Select a financier ";
    $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
    $(amountfundingId).val("");
    validate_against_output_cost();
  }
}

function add_direct_cost(outputid, planid, budgetline_type, rowno){
  $(".modal").each(function () {
    $(this).modal("hide");
    $(this)
      .find("form")
      .trigger("reset");
  });
  var cut_id = outputid + ""+ planid + rowno;
  var projid = $("#projid").val();
  var output_description= $(`#dddescription${cut_id}`).val();
  var output_unit= $(`#dunit${cut_id}`).val();
  var output_unit_cost= $(`#dunitcost${cut_id}`).val();
  var output_units_no= $(`#dtotalunits${cut_id}`).val();

  $("#projid").val(projid);
  $("#outputid").val(outputid);
  $("#output_description").val(output_description);
  $("#output_unit").val(output_unit);
  $("#output_unit_cost").val(output_unit_cost);
  $("#output_units_no").val(output_units_no);
  budget_line_type_hide_show(budgetline_type);
  var plan_cost_validation = validate_plan_cost(outputid, planid, budgetline_type, rowno);
  if(plan_cost_validation){
    check_edit_plan(outputid, planid, budgetline_type, rowno);
  }else{
    console.log("cannot work please wait i wook on it s")
  }
}

function budget_line_type_hide_show(budgetline_type){
  if(budgetline_type== 1){
    $("#timeline_div").hide();
    $("#responsible").removeAttr("required");
    $("#timelinedate").removeAttr("required");
  }else if(budgetline_type== 2){
    $("#timeline_div").show();
    $("#responsible_div").hide();
    $("#responsible").removeAttr("required");
    $("#timelinedate").attr("required", "required");
  }else if(budgetline_type== 3){
    $("#responsible_div").show();
    $("#timeline_div").show();
    $("#responsible").attr("required", "required");
    $("#timelinedate").attr("required", "required");
  }
}

function validate_plan_cost(outputid, planid, budgetline_type, rowno){
  var plan_cost = (budgetline_type == 1) ? $("#dtotalcost" + outputid + planid + rowno).val() : $("#totalcost" + planid).val();
  var message = false;
  if(plan_cost !=""){
    $("#output_cost_fi_celing").val(plan_cost);
    $("#output_cost_ceiling").html(
      "Total Cost Ksh:" + commaSeparateNumber(plan_cost)
    );
    message= true; 
  }else{
    $(".modal").modal("toggle"); 
    sweet_alert("Error !!!", "Add unit cost and total no of units");
  }
  return message;
}

function check_edit_plan(outputid, planid, budgetline_type, rowno){ 
  var finid =  (budgetline_type == 1) ?  $("#dfinid" + budgetline_type + outputid + planid + rowno).val() : $("#finid" + budgetline_type + planid).val();
  var rmkid =  (budgetline_type == 1) ?  $("#drmkid" + budgetline_type + outputid + planid + rowno).val() : $("#rmkid" + budgetline_type + planid).val();
  var cdsmttimeline =  (budgetline_type == 1) ?  "" : $("#dsmttimeline" + budgetline_type + planid).val();

  console.log(finid);
  console.log(rmkid);

  
  if (finid == "" && rmkid == "") {
    $("#financier_table_body").html(`<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`);
    $("#newitem").val("newitem");
    $("#newitem").attr("name", "newitem");
    $("#edit-item").html("");
    console.log("checking if we can add file");

  }else{ 
    $("#newitem").val("edititem");
    $("#newitem").attr("name", "edititem");
    $("#edit-item").html(`
      <input type="hidden" name="remarkid" id="remarkid" value="${rmkid}">
      <input type="hidden" name="dfinid" id="dfinid" value="${finid}">
      <input type="hidden" name="timelineid" id="timelineid" value="${cdsmttimeline}">
    `);
    get_edit_plan_details(outputid,crmkid,cfinid, cdsmttimeline);
    get_edit_financier_details(projid,outputid, cfinid);
    console.log("checking if we can edit file");
  }
}

function get_edit_plan_details(outputid,crmkid,cfinid, cdsmttimeline){
  $.ajax({
    type: "POST",
    url: "assets/processor/add-financial-plan-process",
    data: {
      editdetails: "editdetails",
      outputid: outputid,
      crmkid: crmkid,
      cfinid: cfinid,
      cdsmttimeline: cdsmttimeline
    },
    dataType: "json",
    success: function (response) {
      var disbursement_date = response.disbursement_date;
      var responsible = response.responsible;
      var comment = response.comment;
      $("#comment").val(comment);
      $("#timelinedate").val(disbursement_date).trigger("change");
      $("#responsible").val(responsible).trigger("change");
    }
  });
}

function get_edit_financier_details(projid,outputid, cfinid){
  $.ajax({
    type: "POST",
    url: "assets/processor/add-financial-plan-process",
    data: {
      getfinancieredit: "getfinancieredit",
      cfinid: cfinid,
      projid: projid,
      outputid: outputid
    },
    dataType: "html",
    success: function (response) {
      $("#financier_table_body").html(response);
      validate_against_output_cost();
    }
  });
}


//function to validate timeline date against project dates
function validate_timeline_date() {
  var projstart = new Date($("#projstartdate").val());
  var projend = new Date($("#projenddate").val());
  var timeline_date = new Date($("#timelinedate").val());

  if (timeline_date < projstart) {
    $("#timeline_date").html(
      "Timeline Date cannot less that project start date "
    );
    $("#timelinedate").val("");
  } else if (timeline_date > projend) {
    $("#timeline_date").html(
      "Timeline Date cannot be greater than project end date"
    );
    $("#timelinedate").val("");
  } else {
    $("#timeline_date").html("");
  }
}

function financier_balance_check() {
  var outputCost = parseFloat($("#output_cost_fi_celing").val());
  var financierTotal = 0;
  $(".financierTotal").each(function () {
    if ($(this).val() != "") {
      financierTotal = financierTotal + parseFloat($(this).val());
    }
  });

  var remaining = outputCost - financierTotal;

  if (remaining == 0) {
    return true;
  } else {
    return false;
  }
}

// submitting modal form
$("#modal_form_submit").submit(function (e) {
  e.preventDefault();
  var planid = $("#fplanid").val();
  var outputid = $("#foutputid").val();
  var ftype = $("#ftype").val();
  var rowno = $("#rowno").val();
  var total_financiers = $("#total_financiers").val();

  var total_cost_id = "";
  var total_unit_id = "";
  var unit_cost_id = "";

  var htotal_cost_id = "";
  var htotal_unit_id = "";
  var hunit_cost_id = "";

  var modal_link = "";
  var dsmttimeline = "";
  var finid = "";
  var rmkid = "";

  if (ftype == "1") {
    modal_link = "#daddFormModalBtn" + ftype + outputid + planid + rowno;
    dsmttimeline = "#ddsmttimeline" + ftype + outputid + planid + rowno;
    finid = "#dfinid" + ftype + outputid + planid + rowno;
    rmkid = "#drmkid" + ftype + outputid + planid + rowno;

    total_cost_id = "#dtotalcost" + outputid + planid + rowno;
    total_unit_id = "#dtotalunits" + outputid + planid + rowno;
    unit_cost_id = "#dunitcost" + outputid + planid + rowno;
    // history
    htotal_cost_id = "#dhtotalcost" + outputid + planid + rowno;
    htotal_unit_id = "#dhtotalunits" + outputid + planid + rowno;
    hunit_cost_id = "#dhunitcost" + outputid + planid + rowno;

  } else if (ftype == "2") {
    modal_link = "#addFormModalBtn" + ftype + planid;
    dsmttimeline = "#dsmttimeline" + ftype + planid;
    finid = "#finid" + ftype + planid;
    rmkid = "#rmkid" + ftype + planid;

    total_cost_id = "#totalcost" + planid;
    total_unit_id = "#totalunits" + planid;
    unit_cost_id = "#unitcost" + planid;

    // history
    htotal_cost_id = "#htotalcost" + planid;
    htotal_unit_id = "#htotalunits" + planid;
    hunit_cost_id = "#hunitcost" + planid;
  } else if (ftype == "3") {
    modal_link = "#addFormModalBtn3" + planid;
    dsmttimeline = "#dsmttimeline" + ftype + planid;
    finid = "#finid" + ftype + planid;
    rmkid = "#rmkid" + ftype + planid;

    total_cost_id = "#totalcost" + planid;
    total_unit_id = "#totalunits" + planid;
    unit_cost_id = "#unitcost" + planid;

    // history
    htotal_cost_id = "#htotalcost" + planid;
    htotal_unit_id = "#htotalunits" + planid;
    hunit_cost_id = "#hunitcost" + planid;
  }

  var total_cost = $(total_cost_id).val();
  var total_unit = $(total_unit_id).val();
  var unit_cost = $(unit_cost_id).val();

  // var internet_handler = checkNetConnection();
  var internet_handler = true;

  if (internet_handler) {
    var total_financiers = $("#total_financiers").val();
    var financet = financier_balance_check();

    var fin = false;
    if (total_financiers == "1") {
      fin = true;
    } else {
      if ($("select[name='finance[]'] option:selected").length > 0) {
        fin = true;
      } else {
        fin = false;
      }
    }

    if (fin) {
      if (financet) {
        var formData = $(this).serialize();
        $.ajax({
          type: "post",
          url: "assets/processor/add-financial-plan-process",
          data: formData,
          dataType: "json",
          success: function (response) {
            alert(response.message);
            $(modal_link).html("Edit Details");
            $("#financier_table_body").html(
              "<tr></tr>" +
              '<tr id="removeTr">' +
              '<td colspan="5">Add Financiers</td>' +
              "</tr>"
            );

            $(".modal").each(function () {
              $(this).modal("hide");
              $(this)
                .find("form")
                .trigger("reset");
            });

            $(hunit_cost_id).val(unit_cost);
            $(htotal_unit_id).val(total_unit);
            $(htotal_cost_id).val(total_cost);

            $(dsmttimeline).val(response.timeline);
            $(finid).val(response.finance);
            $(rmkid).val(response.remarks);
          }
        });
      } else {
        var msg =
          "Financier contribution should be equal to budget of the plan ";
        sweet_alert("Error !!!", msg);
      }
    } else {
      var msg = "Please Add Financier";
      sweet_alert("Error !!!", msg);
    }
  }
});

// deleting the file information
function delete_other_info(opid, planid, typeno, rowno) {
  var projid = $("#projid").val();
  var dsmttimeline = "";
  var finid = "";
  var rmkid = "";
  var outputid = opid;
  var modal_link = "";
  var sub_total_pid = "";
  var sub_total_aid = "";
  var sub_total_class = "";

  if (typeno == "1") {
    sub_total_pid = "#sub_total_percentage" + typeno + opid;
    sub_total_aid = "#sub_total_amount" + typeno + opid;
    sub_total_class = ".direct_sub_total_amount" + typeno + opid;
    modal_link = "#daddFormModalBtn" + typeno + outputid + planid + rowno;
    dsmttimeline = "#ddsmttimeline" + typeno + outputid + planid + rowno;
    finid = "#dfinid" + 1 + opid + planid + rowno;
    rmkid = "#drmkid" + typeno + outputid + planid + rowno;
  } else if (typeno == "2") {
    sub_total_pid = "#sub_total_percentage" + typeno + opid;
    sub_total_aid = "#sub_total_amount" + typeno + opid;
    sub_total_class = ".direct_sub_total_amount" + typeno + opid;
    modal_link = "#addFormModalBtn" + typeno + planid;
    dsmttimeline = "#dsmttimeline" + typeno + planid;
    finid = "#finid" + typeno + planid;
    rmkid = "#rmkid" + typeno + planid;
  } else if (typeno == "3") {
    total_unit_id = "#totalunits" + planid;
    var budget_line_id = $(total_unit_id).attr("data-id");
    var id = budget_line_id;
    sub_total_aid = "#sub_total_amount3" + $.trim(id);
    sub_total_pid = "#sub_total_percentage3" + $.trim(id);
    sub_total_class =
      ".direct_sub_total_amount" + typeno + $.trim(budget_line_id);
    modal_link = "#addFormModalBtn3" + planid;
    dsmttimeline = "#dsmttimeline" + typeno + planid;
    finid = "#finid" + typeno + planid;
    rmkid = "#rmkid" + typeno + planid;
  }

  var dexpid = $(dsmttimeline).val();
  var dfinid = $(finid).val();
  var drmid = $(rmkid).val();


  if (dfinid != "" && drmid != "") {
    var confirmation = confirm("Are you sure you want to change this item");
    if (confirmation) {
      $.ajax({
        type: "post",
        url: "assets/processor/add-financial-plan-process",
        data: {
          deleteItem: "deleteItem",
          projid: projid,
          outputid: opid,
          dfinid: dfinid,
          drmid: drmid,
          dexpid: dexpid
        },
        dataType: "json",
        success: function (response) {
          $(dsmttimeline).val("");
          $(finid).val("");
          $(rmkid).val("");
          $(modal_link).html("Add details");
          ouput_used_cost(opid);
          cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid, opid);
        }
      });
      return true;
    } else {
      return false;
    }
  } else {
    return true;
  }
}

// function to ensure that all output costs have been depleted
function output_cost_val() {
  var outputid = $("#output_cost_val").val();
  var opids = outputid.split(",");
  var handler = [];
  mesg = "";
  var count = 0;
  for (var i = 0; i < opids.length; i++) {
    var opid = opids[i];
    count++;
    var output_budget = parseFloat($("#outputcost" + opid).val());
    var used_budget = 0;

    $(".output_cost" + opid).each(function () {
      if ($(this).val() != "") {
        used_budget = used_budget + parseFloat($(this).val());
      }
    });

    var balance = output_budget - used_budget;

    if (balance == 0) {
      handler.push("true");
    } else {
      var output_name = $("#output_name" + opid).val();
      mesg =
        mesg +
        count +
        " Output" +
        output_name +
        "still has balance of " +
        commaSeparateNumber(balance) +
        "\n";
      handler.push("false");
    }
  }

  if (handler.indexOf("false") != -1) {
    sweet_alert("Error !!!", mesg);
    // alert(mesg);
    return false;
  } else {
    return true;
  }
}

// validating modal input
function modal_validate() {
  var handler = [];
  var msg = [];
  $(".modal_val").each(function () {
    if ($(this).val() == "") {
      handler.push("false");
      var err = $(this).attr("title");
      msg.push(err);
    }
  });

  var err_msg = "";
  if (handler.indexOf("false") != -1) {
    var counter = 0;
    for (var i = 0; i < msg.length; i++) {
      counter++;
      err_msg = err_msg + counter + ". ) " + msg[i] + "\n";
    }
    sweet_alert("Error !!!", err_msg);
    return false;
  } else {
    return true;
  }
}

$(".careted").click(function (e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa fa-caret-down fa fa-caret-up");
});

var counter = 0;

// function to add new rowfor financiers
function add_direct_row(id, opid, tkid) {
  var classid = ".task" + id;
  $rowno = $("#direct_table" + opid + " tr" + classid).length;
  $rowno = $rowno + 1;
  var afterid = "#direct_table" + opid + " tr" + classid + ":last";
  var numb_id = "#direct_table" + opid + " tr" + classid;
  var tid = id + $rowno;
  var new_id = opid + tkid;

  $(afterid).after(
    '<tr id="task' + tid + '" class="task' + id + '"> ' +
    '<td style="color:#FF5722">' +
    '</td>' +
    '<td>' +
    '<input type="text" name="ddescription' + new_id + '[]" id="dddescription' + tid + '" class="form-control" placeholder="Item Description" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    '</td>' +
    '<td>' +
    '<input type="text" name="dunit' + new_id + '[]" id="dunit' + tid + '" class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    '</td>' +
    '<td>' +
    '<input type="hidden" name="hunitcost' + new_id + '[]" id="dhunitcost' + tid + '">' +
    '<input type="number" name="dunitcost' + new_id + '[]" id="dunitcost' + tid + '" class="form-control" onkeyup = "number_of_units_change(' + tkid + ',' + opid + ',1,' + $rowno + ')" onchange = "number_of_units_change(' + tkid + ',' + opid + ',1,' + $rowno + ')" placeholder="Unit Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    '</td>' +
    '<td>' +
    '<input type="hidden" name="htotalunits' + new_id + '[]" id="dhtotalunits' + tid + '">' +
    '<input type="number" name="dtotalunits' + new_id + '[]" id="dtotalunits' + tid + '" class="form-control" onchange="totalCost(' + tkid + ',' + opid + ',1,' + $rowno + ')" onkeyup="totalCost(' + tkid + ',' + opid + ',1,' + $rowno + ')" placeholder="No. of units" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    '</td>' +
    '<td>' +
    '<input type="hidden" name="htotalcost' + new_id + '[]" id="dhtotalcost' + tid + '">' +
    '<input type="text" name="dtotalcost' + new_id + '[]" id="dtotalcost' + tid + '" class="form-control totalCost summarytotal  output_cost' + opid + ' direct_sub_total_amount1' + opid + '" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>' +
    '</td>' +
    '<td>' +
    '<input type="hidden" name="dsmttimeline1' + new_id + '[]" id="ddsmttimeline1' + tid + '">' +
    '<input type="hidden"  name="finid1' + new_id + '[]" id="dfinid1' + tid + '">' +
    '<input type="hidden" class="modal_val" name="rmkid1' + new_id + '[]" id="drmkid1' + tid + '" title="The output  has a other details not captured at Budget line <?= $budget_line ?> look at row number <?= $rowno ?>">' +
    '<a type="button" data-toggle="modal" data-id="" data-target="#addFormModal" onclick="addFinancier(' + opid + ',' + tkid + ',1,' + $rowno + ')" id="">' +
    '<i class="glyphicon glyphicon-file"></i>' +
    '<span id = "daddFormModalBtn1' + tid + '" >' +
    'Add Details' +
    '</span>' +
    '</a>' +
    '</td>' +
    '<td>' +
    '<button type="button" class="btn btn-danger btn-sm"  onclick=delete_row_direct("' + $rowno + '","' + tkid + '","' + opid + '") >' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    '</button>' +
    '</td></tr >');
  numbering_direct(numb_id);
}

// function to delete financiers row
function delete_row_direct(rowno, tkid, opid) {
  var tid = opid + tkid;
  var $rowno = tid + rowno;
  var finid = $("#dfinid" + 1 + opid + tkid + rowno).val();

  if (finid == "") {
    $("#task" + $rowno).remove();
    var classid = ".task" + tid;
    var numb_id = "#direct_table" + opid + " tr" + classid;
    numbering_direct(numb_id);
  } else {
    var handler = delete_other_info(opid, tkid, 1, rowno);
    if (handler) {
      $("#task" + $rowno).remove();
      var classid = ".task" + tid;
      var numb_id = "#direct_table" + opid + " tr" + classid;
      numbering_direct(numb_id);
    }
  }
}

function numbering_direct(id) {
  $(id).each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx + 1);
  });
}

$("#tag-form-submit").click(function (e) {
  e.preventDefault();
  swal({
    title: "Confirmation",
    text: "Please confirm whether you have entered correct data!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      var internet_handler = true;
      if (internet_handler) {
        $("#form").validate({
          ignore: [],
          errorPlacement: function (error, element) {
            var lastError = $(element).data("lastError"),
              newError = $(error).text();
    
            $(element).data("lastError", newError);
    
            if (newError !== "" && newError !== lastError) {
              $(element).after('<div class="red">The field is Required</div>');
            }
          },
          success: function (label, element) {
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
          var bal = output_cost_val();
          if (bal) {
            var modal_val = modal_validate();
            if (modal_val) {
              var formData = $("#form").serialize();
              $.ajax({
                type: "post",
                url: "assets/processor/add-financial-plan-process",
                data: formData,
                dataType: "json",
                success: function (response) {
                  if(response.msg){
                    sweet_alert("Success !!!", "Successfully Added Financial Plan");
                    var get_url = "add-project-financial-plan";
                    window.location.href = get_url;
                  }else{
                    sweet_alert("Error !!!", "Eror Inserting data");
                  } 
                }
              });
            }
          }
        }
      }
    }
  });
  //var internet_handler = checkNetConnection();
});