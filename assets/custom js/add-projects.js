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

  var urlpath = window.location.pathname;
  var filename = urlpath.substring(urlpath.lastIndexOf('/') + 1);

	if (filename == "add-projects.php" || filename == "add-project") {
		hide_divs();
	}

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

  //on load calculate the program days
  var progduration = $("#progduration").val();
  var remainingDuration = parseInt(progduration) * 365;
  $("#projdurationmsg").html(remainingDuration);

  //validation file
  $("#form").validate({
    ignore: [],
    rules: {
      firstname: {
        required: true
      },
      projdesc: {
        ckeditor_required: true
      }
    },
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
  var navListItems = $("div.setup-panel div a"),
    allWells = $(".setup-content"),
    allNextBtn = $(".nextBtn");
  allPrevBtn = $(".prev-step");
  allWells.hide();
  navListItems.click(function (e) {
    e.preventDefault();
    var $target = $($(this).attr("href")),
      $item = $(this);

    if (!$item.hasClass("disabled")) {
      navListItems.removeClass("btn-primary").addClass("btn-default");
      $item.addClass("btn-primary");
      allWells.hide();
      $target.show();
      $target.find("input:eq(0)").focus();
    }
  });

  /* 
    Handles validating using jQuery validate.
   */
  allNextBtn.click(function () {
    var prevStep;
    var curStep = $(this).closest(".setup-content"),
      curStepBtn = curStep.attr("id"),
      nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]')
        .parent()
        .next()
        .children("a"),
      curInputs = curStep.find("input, select"),
      isValid = true,
      step4 = true,
      indirectBal = true,
      directBal = true;

    if (curStepBtn == "step-2") {
      var outputVale = validate_states();
      if (outputVale == false) {
        isValid = false;
      } else {
        isValid = true;
      }
    }


    if (curStepBtn == "step-3") {
      var financeValues = financeValue();
      if (financeValues) {
        isValid = true;
      } else {
        isValid = false;
      }
    }


    //Loop through all inputs in this form group and validate them.
    for (var i = 0; i < curInputs.length; i++) {
      if (!$(curInputs[i]).valid()) {
        isValid = false;
      }
    }
    if (isValid) {
      //Progress to the next page.
      nextStepWizard.addClass("verified");
      nextStepWizard.removeClass("disabled").trigger("click");
    }
  });

  allPrevBtn.click(function (e) {
    var curStep = $(this).closest(".setup-content");
    curStepBtn = curStep.attr("id");
    prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]')
      .parent()
      .prev()
      .children("a");
    prevStepWizard.removeClass("disabled").trigger("click");
  });

  $("div.setup-panel div a.btn-primary").trigger("click");
});


//function to put commas to the data
function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

//////////////////
//Project Details
//////////////////


// project code function check if code is already used  
function validate_projcode() {
  var projcode = $("projcode").val();
  if (projcode != "") {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: "projcode=" + projcode,
      dataType: "json",
      success: function (response) {
        if (response == "true") {
          $("#projcodemsg").show();
          $("#projcodemsg").html(
            "This project exists!! Please confirm if this is the correct project code"
          );
          $("#projcode").val("");
        } else {
          $("#projcodemsg").hide();
        }
      }
    });
  }
}

// event change on project financial year
function finacial_year_change() {
  var phandler = output_form_fields();
  if (phandler) {
    var chandler = confirm("This change will delete Output plan defined in the next tab. Would you like to proceed?");
    if (chandler) {
      delete_all_outputs();
      change_fsc_year();
    } else {
      var hfscyear = $("#hfscyear").val();
      $("#projfscyear1").val(hfscyear);
    }
  } else {
    change_fsc_year();
  }
}


// financial year change functionality 
function change_fsc_year() {
  let projfscyear1 = $("#projfscyear1").val();
  let projduration = $("#projduration1").val();
  let progendyear = $("#progendyear").val();
  if (projfscyear1 != "") {
    $("#projendyear").val("");
    $("#hprojendyear").val("");
    $("#projdurationmsg1").hide();
    $("#projfscyearmsg1").hide();
    get_financial_year(projfscyear1);
  } else {
    $("#projfscyearmsg1").show();
    $("#projdurationmsg1").show();
    $("#projduration1").val("");
    $("#projfscyear1").val("");
    $("#projectStartingYear").val("");
    $("#projendyearDate").val("");
    $("#projendyear").val("");
    $("#hprojendyear").val("");
    $("#projdurationmsg1").html("select Financial Year");
    $("#projfscyearmsg1").html("select Financial Year");
    var progduration = $("#progduration").val();
    var remainingDuration = parseInt(progduration) * 365;
    $("#projdurationmsg").html(remainingDuration);
  }
}

// event change and key up for project duration 
function project_duration_change() {
  var projfscyear1 = $("#projfscyear1").val();
  var projduration = $("#projduration1").val();
  var progendyear = $("#progendyear").val();
  if (projfscyear1 != "") {
    if (projduration != "") {
      projduration = parseInt(projduration);
      if (projduration > 0) {
        validate_project_duration();
      } else {
        $("#projdurationmsg1").show();
        $("#projendyear").val("");
        $("#hprojendyear").val("");
        $("#projdurationmsg1").html("Duration cannot be 0 or less");
        $("#projendyearDate").val("");
        $("#projduration1").val("");
      }
    } else {
      $("#projdurationmsg1").show();
      $("#projendyearDate").val("");
      $("#projduration1").val("");
      $("#projendyear").val("");
      $("#hprojendyear").val("");
      $("#projdurationmsg1").html("Project duration is required");
      var projectStartingYear = $("#projectStartingYear").val();
      var remainingDuration =
        (parseInt(progendyear) - parseInt(projectStartingYear)) * 365;
      $("#projdurationmsg").html(remainingDuration);
    }
  } else {
    $("#projdurationmsg1").show();
    $("#projduration1").val("");
    $("#projendyearDate").val("");
    $("#projendyear").val("");
    $("#hprojendyear").val("");
    $("#projdurationmsg1").html("select Financial Year");
  }
}

// validation checker for output duration

function project_duration_validate() {
  var handler = false;

  var phandler = output_form_fields();

  if (phandler) {
    if (handler == false) {
      var chandler = confirm("This change will delete Output plan defined in the next tab. Would you like to proceed?");
      if (chandler) {
        project_duration_change();
        delete_all_outputs();
        handler = true;
      } else {
        var hfscyear = $("#hduration").val();
        $("#projduration1").val(hfscyear);
        handler = false;
      }
    }
  } else {
    project_duration_change();
  }
}

// validate the project duration 
function validate_project_duration() {
  var projduration = parseInt($("#projduration1").val());
  if ($("#projduration1").val() != "") {
    var progendyear = $("#progendyear").val();
    var starting_year = $("#projectStartingYear").val();
    var progduration = parseInt(progendyear) - parseInt(starting_year);
    var remainingDuration = parseInt(progduration) * 365 - projduration;

    var years = projduration / 365;
    var startYear = starting_year;
    var startYearDate = starting_year + "-07-01";
    var today = new Date(startYearDate);
    var progendyearDate = new Date(progendyear + "-06-30");

    var endyearDate = "";
    var a = new Date();

    if (projduration % 365 != 0) {
      projduration = projduration - 1;
      for (var i = 0; i < years; i++) {
        startYear++;
        var leapYear = isLeap(startYear);
        if (leapYear == true) {
          projduration++;
        } else {
          projduration = projduration;
        }
      }

      Date.prototype.addDays = function (d) {
        today.setDate(today.getDate() + d);
        return today;
      };

      endyearDate = a.addDays(projduration);
    } else {
      Date.prototype.addDays = function (d) {
        today.setDate(today.getDate() + d);
        return today;
      };
      endyearDate = a.addDays(projduration);
    }

    //function to check if leap year
    function isLeap(year) {
      return new Date(year, 1, 29).getDate() === 29;
    }

    var projendyearDate = new Date(endyearDate)
      .toISOString()
      .slice(0, 10);

    var projendyear = endyearDate.getFullYear();

    if (endyearDate <= progendyearDate) {
      var Edate = projendyear + "/" + (parseInt(projendyear) + 1);
      $("#projendyear").val(Edate);
      $("#hprojendyear").val(projendyear);
      $("#projendyearDate").val(projendyearDate);
      $("#projdurationmsg1").hide();
      get_output_table();
      if (remainingDuration < 0) {
        $("#projdurationmsg").html(parseInt(progduration) * 365);
      } else {
        $("#projdurationmsg").html(remainingDuration);
      }
    } else {
      $("#projdurationmsg1").show();
      $("#projdurationmsg1").html(
        "Enter duration that is within program duration"
      );
      $("#projendyear").val("");
      $("#hprojendyear").val("");
      $("#projendyearDate").val("");
      $("#projduration1").val("");
      $("#projdurationmsg").html(parseInt(progduration) * 365);
    }
  }
}

// get the project start year 
function get_financial_year(projfscyear1) {
  var progendyear = $("#progendyear").val();
  if (progendyear != "" && projfscyear1 != "") {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: "getyear=" + projfscyear1,
      dataType: "json",
      success: function (response) {
        $("#projectStartingYear").val(response);
        var progduration = parseInt(progendyear) - parseInt(response);
        $("#projdurationmsg").html(parseInt(progduration) * 365);
        validate_project_duration();
      }
    });
  }
}

// Function to validate ecosystem on change 
function conservancy() {
  var scID = $("#projcommunity").val();

  var fields = output_form_fields();
  if (fields) {
    var handler = confirm("This change will delete Output plan defined in the next tab. Would you like to amend the selection?");
    if (handler) {
      delete_all_outputs();
      get_conservancy(scID);
    } else {
      var hconservancy = $("#level1label").val();
      get_community(hconservancy);
    }

  } else {
    get_conservancy(scID);
    $("#level1label").val("");
    $("#level2label").val("");
    $("#level3label").val("");
  }
}

function get_community(comm) {
  if (comm) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: `get_comm=${comm}`,
      dataType: "html",
      success: function (response) {
        $("#projcommunity").html(response);
        $(".selectpicker").selectpicker("refresh");
      }
    });
  }
}

// function to get ecosystem 
function get_conservancy(scID) {
  if (scID) {
    $.ajax({
      type: "POST",
      url: "assets/processor/add-project-process",
      data: "getward=" + scID,
      dataType: "html",
      success: function (html) {
        $("#projlga").html(html);
        $("#projstate").html('<option value="">Select Level II first</option>');
        $(".selectpicker").selectpicker("refresh");
      }
    });
  }
}

// Function to validate ecosystem on change 
function ecosystem() {
  var ward = $("#projlga").val();
  var fields = output_form_fields();

  if (fields) {
    var handler = confirm("This change will delete Output plan defined in the next tab. Would you like to amend the selection?");
    if (handler) {
      delete_all_outputs();
      get_ecosystem(ward);
    } else {
      var hecosystem = $("#level2label").val();
      get_hlevel2(hecosystem);
      $(".selectpicker").selectpicker("refresh");
    }
  } else {
    get_ecosystem(ward);
  }
}

// function to get forests 
function get_ecosystem(ward) {
  $.ajax({
    type: "POST",
    url: "assets/processor/add-project-process",
    data: "getlocation=" + ward,
    success: function (html) {
      $("#projstate").html(html);
      $(".selectpicker").selectpicker("refresh");
    }
  });
}


// get history ward 
function get_hlevel2(ward) {
  var conservancy = $("#projcommunity").val();
  if (ward) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: { get_ward: ward, conservancy: conservancy },
      dataType: "html",
      success: function (response) {
        $("#projlga").html(response);
        $(".selectpicker").selectpicker("refresh");
      }
    });
  }
}

// Function to validate forest on change  
function forest() {
  var forest = $("#projstate").val();
  var fields = output_form_fields();
  if (fields) {
    var handler = confirm("This change will delete Output plan defined in the next tab. Would you like to amend the selection?");
    if (handler) {
      delete_all_outputs();
    } else {
      var hforest = $("#level3label").val();
      get_hlevel3(hforest);
    }
  }
}

// get history state  
function get_hlevel3(state) {
  var level2 = $("#projlga").val();
  if (state) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: { get_level3: state, level2: level2 },
      dataType: "html",
      success: function (response) {
        $("#projstate").html(response);
        $(".selectpicker").selectpicker("refresh");
      }
    });
  }
}


////////////////
// Workplan 
////////////////

// --------output table ------------

// function to get output table 
function get_output_table() {
	$.ajax({
		type: "post",
		url: "assets/processor/add-project-process",
		data: "getOutputTable",
		dataType: "html",
		success: function (response) {
			$("#projoutputTable").html(response);
		}
	});
}

// function to add output 
function add_row_output() {
  var projfscyear1 = $("#projfscyear1").val();
  var projduration = $("#projduration1").val();

  var projstate = $("#projstate").val();
  var projcommunity = $("#projcommunity").val();
  var projlga = $("#projlga").val();


  if (projstate) {
    // set history values 
    $("#hduration").val(projduration);
    $("#hfscyear").val(projfscyear1);
    $("#level1label").val(projcommunity);
    $("#level2label").val(projlga);
    $("#level3label").val(projstate);


    $row = $("#output_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $rowno = $row + "" + randno;
    $("#hideinfo").remove(); //new change
    $("#output_table_body tr:last").after(
      '<tr id="row' +
      $rowno +
      '">' +
      "<td></td><td>" +
      '<select  data-id="' +
      $rowno +
      '" name="output[]" id="outputrow' +
      $rowno +
      '" onchange=getIndicator("row' +
      $rowno +
      '")  class="form-control validoutcome select_output" required="required">' +
      '<option value="">Select Output from list</option> </select>' +
      '<input type="hidden" name="outputIdsTrue[]" id="outputIdsTruerow' +
      $rowno +
      '" value="" />' +
      '<input type="hidden" name="ben_diss[]" id="ben_dissrow' +
      $rowno +
      '" value="" />' +
      "</td>" +
      "<td>" +
      '<input type="hidden" name="indicatorid[]" id="indicatoridrow' +
      $rowno +
      '" /> ' +
      '<input type="hidden" name="diss_type_op[]" id="diss_type_oprow' +
      $rowno +
      '" /> ' +
      '<span id="indicatorrow' + $rowno + '"></span>' +
      '<input type="hidden" name="indicator[]"    placeholder="Enter"  class="form-control" disabled  />' +
      "</td>" +
      "<td>" +
      '<a type="button" data-toggle="modal"  data-target="#outputItemModal" onclick=output_year("row' +
      $rowno +
      '") id="outputItemModalBtnrow' +
      $rowno +
      '"> Add Details</a>' +
      "</td>" +
      "<td>" +
      '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("row' +
      $rowno +
      '")>' +
      '<span class="glyphicon glyphicon-minus"></span>' +
      "</button>" +
      "</td>" +
      "</tr>"
    );
    numberingOutput();
    getoutput($rowno);
  } else {
    alert("Select Forest First");
  }

}

// function to number output table 
function numberingOutput() {
  $("#output_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function check_ben() {
  var ben_diss_val = [];
  $("input[name='ben_diss[]']").each(function () {
    if ($(this).val() != "") {
      ben_diss_val.push(parseInt($(this).val()));
    }
  });

  if (ben_diss_val.includes(1)) {
    $("#location_targets_div_fieldset").show();
  } else {
    $("#location_targets_div_fieldset").hide();
  }
}


// function to delete output output 
function delete_row_output(rowno) {
  var outputIdsTrue = $("#outputIdsTrue" + rowno).val();

  if (outputIdsTrue != "") {
    var handler = confirm("This change will delete the below plan. Would you like to proceed?");
    if (handler) {
      $.ajax({
        type: "post",
        url: "assets/processor/add-project-process",
        data: { deleteItem: "deleteItem", itemId: outputIdsTrue },
        dataType: "json",
        success: function (response) {
          if (response.success == true) {
            alert(response.messages)
            $("#target_div_" + outputIdsTrue).remove();
            $("#div_" + outputIdsTrue).remove();
            $("#" + rowno).remove();
            numberingOutput();
            $number = $("#output_table_body tr").length;
            if ($number == 1) {
              $("#output_table_body tr:last").after(
                '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
              );
              hide_divs();
            }

            getcost();
            check_ben();
          } else {
            alert(response.messages);
          }
        }
      });
    }
  } else {
    $("#" + rowno).remove();
    numberingOutput();
    $number = $("#output_table_body tr").length;
    if ($number == 1) {
      $("#output_table_body tr:last").after(
        '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
      );
      hide_divs();
    }
    getcost();
  }


}

// function to get output that still has target 
function getoutput(rowno) {
  var projoutput = "#outputrow" + rowno;
  var link_id = "#outputItemModalBtnrow" + rowno;
  var projstartYear = parseInt($("#projectStartingYear").val());
  var projendYear = parseInt($("#hprojendyear").val());
  var progid = $("#progid").val();
  var duration = "";

  if (projendYear == projstartYear) {
    duration = 1;
  } else {
    duration = projendYear - projstartYear;
  }

  if (projendYear) {
    $.ajax({
      type: "POST",
      url: "assets/processor/add-project-process",
      data: {
        getprojoutput: "getprojoutput",
        outprojstartYear: projstartYear,
        outduration: duration,
        outProgid: progid
      },
      success: function (html) {
        $(projoutput).html(html);
        $(link_id).hide();
      }
    });
  }
}

//filter the expected output  cannot be selected twice
$(document).on("change", ".select_output", function (e) {
  var tralse = true;
  var selectOutcome_arr = []; // for contestant name
  var attrb = $(this).attr("id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();

  $(".select_output").each(function (k, v) {
    var getVal = $(v).val();
    if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
      tralse = false;
      alert("You canot select Output " + selectedText + " more than once");
      var rw = $(v).attr("data-id");
      var outcomeindicator = "#outcomeindicatorrow" + rw; // outcome  indicator id
      $(v).val("");
      $(outcomeindicator).val("");
      return false;
    } else {
      selectOutcome_arr.push($(v).val());
    }
  });
  if (!tralse) {
    return false;
  }
});

// function too get indicator
function getIndicator(rowno) {
  var outputid = $("#output" + rowno).val();
  var link_id = "#outputItemModalBtn" + rowno;
  var progid = $("#progid").val();
	var mapp = $("input:radio[name=projmapping]:checked").val();
	console.log(mapp);

  if (outputid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getIndicator: outputid,
        getIndicatorProgid: progid
      },
      dataType: "json",
      success: function (response) {
        var outputid = $("#output" + rowno).val();
        if (outputid) {
          $("#indicator" + rowno).html(response.indicator_name);
          $("#indicatorid" + rowno).val(response.indid);
          $("#ben_diss" + rowno).val(response.ben_diss);
          $("#diss_type_op" + rowno).val(response.diss_type_op);
          $("#output" + rowno).attr("data-id", response.ben_diss);
          $(link_id).show();
        } else {
          $("#indicator" + rowno).html("");
          $("#diss_type_op" + rowno).val("");
          $("#indicatorid" + rowno).val("");
          $("#output" + rowno).removeAttr("data-id");
        }
      }
    });
  } else {
    alert("Select Indicator");
  }
}


///////////
// output workplan modal 
///////////

// function to get  output year
function output_year(rowno) {
	var mapp = $("input:radio[name=projmapping]:checked").val();
  var indicatorVal = $("#indicatorid" + rowno).val();
  var projstartYear = parseInt($("#projectStartingYear").val());
  var projendYear = parseInt($("#hprojendyear").val());
  var projstate = $("#projstate").val();
  var projid = $("#projid").val();
  var progid = $("#progid").val();
  var opid = $("#outputIdsTrue" + rowno).val();
  var ben_diss = $("#ben_diss" + rowno).val();
  var diss_type = $("#diss_type_op" + rowno).val();
  $(`#diss_type`).html(diss_type);

  var opduration = "";
  $("#outputStartYear").val("");
  $("#outputdurationmsg1").html("");
  console.log(mapp);

  if (projendYear == projstartYear) {
    opduration = 1;
  } else {
    opduration = projendYear - projstartYear;
  }

  if (projstate) {
    $.ajax({
      type: "POST",
      url: "assets/processor/add-project-process",
      data: {
        projoutputYear: "projoutputYear",
        opprojstartYear: projstartYear,
        opprojendYear: projendYear,
        opstartoutputId: indicatorVal,
        mapp: mapp,
        opprojid: projid,
        opprojid: projid,
        opduration: opduration
      },
      success: function (html) {
        // reset the form first 
        $(".modal").each(function () {
          $(this)
            .find("form")
            .trigger("reset");
          $("#addprojoutput .selectpicker").selectpicker("deselectAll");
        });

        $("#outputfscyear").html(html);
        var indicatorVal = $("#indicatorid" + rowno).val();
        var projoutputValue = $("#output" + rowno).val();
        $("#outputids").val(projoutputValue);
        $("#rowno").val(rowno);
        $("#indicatorids").val(indicatorVal);

        // check if dissegragation applies to the indicator
        if (ben_diss == "1") {
          // direct 
          $("#ben_dissegragation").show();
          // indirect 
          $("#indben_dissegragation").show();
          $("#inddiss_state_body").html('<tr></tr><tr id="remove_diss"><td colspan="5" align="center">Add Location </td></tr>');
          $("#indben_dissegragation").hide();
        } else {
          // direct 
          $("#ben_dissegragation").show();
          $("#diss_state_body").html('<tr></tr><tr id="remove_diss"><td colspan="5" align="center">Add Location </td></tr>');
          $("#ben_dissegragation").hide();
          // indirect 
          $("#indben_dissegragation").show();

        }
        $("#dben_diss").val(ben_diss);

        if (opid) {
          edit_output_details(projstate, opid, rowno);
        } else {
          $("#diss_state_body").html('<tr></tr><tr id="remove_diss"><td colspan="5" align="center">Add Location </td></tr>');
          $("#addoutput").val("addoutput");
          $("#addoutput").attr("name", "addoutput");
          $("#modal-title").html('<i class="fa fa-edit"></i> Add Output Details');
        }
      }
    });
  } else {
    $(".modal").each(function () {
      $(this)
        .find("form")
        .trigger("reset");
      $("#addprojoutput .selectpicker").selectpicker("deselectAll");
      $(this).modal("hide");
    });
    alert("Select Forest !!");
  }
}

// function  to get output data for edit 
function edit_output_details(projstate, opid, rowno) {
  if (projstate && opid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getprojectOutputData: "getprojectOutputData",
        outputids: opid,
        get_states: projstate
      },
      dataType: "json",
      success: function (response) {
        var year = response.year;
        var duration = response.duration;
        var outputBudget = response.outputBudget;
        var projwaypoints = response.mapping_type;
        var outputDuration = parseInt(response.outputDuration);
        var remainingBudget = response.remainingBudget;
        var actual_budget = response.actual_budget;
        var total_target = response.total_target;
        var distribution_type = response.distribution_type;
        var projduration = parseInt($("#projduration1").val());
        var ceil_target = response.ceil_target;
        var remaining_target = response.remaining_target;

        var projendYear = $("#projendyearDate").val();
        var sdate = `${response.opfscyear}-07-01`;

        var outputstartyear = new Date(sdate);
        var yearDate = new Date(projendYear);
        var one_day = 1000 * 60 * 60 * 24;
        var diff = yearDate.getTime() - outputstartyear.getTime();
        var opduration = diff / one_day;

        var opremainingdays = opduration - duration;
        var states = response.states;
        $("#outputStartYear").val(response.opfscyear);
        $("#outputfscyear").val(year);

        $("#outputTarget").val(total_target);
        $("#houtputTarget").val(total_target);
        $("#ceiling_output_target").val(ceil_target);
        $("#ceiling_output_target_msg").html(commaSeparateNumber(remaining_target));

        $("#outputMonitorigFreq").val(distribution_type);
        $("#projwaypoints").val(projwaypoints);
        $("#outputceiling").val(
          commaSeparateNumber(parseFloat(actual_budget))
        );


        var ben_diss = $(`#ben_diss${rowno}`).val();

        if (ben_diss == "1") {
          $("#diss_state_body").html(states);
        } else {
          $("#inddiss_state_body").html(states);
        }

        $("#outputceilingVal").val(remainingBudget);
        $("#outputbudget").val(outputBudget);
        $("#outputdurationmsg1").html(commaSeparateNumber(opremainingdays));
        $("#outputduration1").val(opduration);
        $("#outputduration").val(duration);
        $("#opid").val(opid);

        $("#addoutput").val("editoutput");
        $("#addoutput").attr("name", "editoutput");
        $("#modal-title").html('<i class="fa fa-edit"></i> Edit Output Details');
      }
    });
  }
}

// function to detect event change of the output year 
function output_year_change() {
  var progid = $("#progid").val();
  var indicatorid = $("#indicatorids").val();
  var outputYear = $("#outputfscyear").val();
  var projduration1 = $("#projduration1").val();
  var outputduration = $("#outputduration").val();
  var projendyear = new Date($("#projendyearDate").val());

  if (outputYear != "") {
    $("#outputfscyearmsg").hide();
    get_op_budget_ceil(indicatorid, progid, outputYear);
    get_op_year(outputYear, projendyear);
  } else {
    $("#outputfscyearmsg").show();
    $("#outputfscyearmsg").html("Select Output Financial Year");
    $("#outputdurationmsg1").html(projduration1);
    $("#outputduration").val("");
    $("#ceiling_output_target").val("");
    $("#ceiling_output_target_msg").html("");
  }
}

// function to get output year from the db 
function get_op_year(outputYear, projendyear) {
  $.ajax({
    type: "post",
    url: "assets/processor/add-project-process",
    data: {
      getYear: outputYear
    },
    dataType: "json",
    success: function (response) {
      var sdate = response + "-07-01";
      var outputstartyear = new Date(sdate);
      var yearDate = new Date(projendyear);
      var one_day = 1000 * 60 * 60 * 24;
      var diff = yearDate.getTime() - outputstartyear.getTime();
      var outputduration = diff / one_day;

      $("#outputduration1").val(outputduration);
      $("#outputStartYear").val(response);
      $("#outputdurationmsg1").html(outputduration);
      $("#outputbudget").val("");
      validate_output_days();
      empty_dissegragation();
    }
  });
}

// function to get output budget ceiling 
function get_op_budget_ceil(indicatorid, progid, outputYear) {
  $.ajax({
    type: "post",
    url: "assets/processor/add-project-process",
    data: {
      getoutputBudget: "budget",
      indicatorid: indicatorid,
      programid: progid,
      outputYear: outputYear
    },
    dataType: "json",
    success: function (response) {
      if (response.msg) {
        var remaining = response.remaining;
        $("#outputceilingVal").val(remaining);
        $("#outputceiling").val(commaSeparateNumber(parseFloat(remaining)));
      } else {
        $("#outputceilingVal").val("");
        $("#outputceiling").val("");
      }
    }
  });
}

// function to validate if leap year
function isLeap(year) {
  return new Date(year, 1, 29).getDate() === 29;
}

// function to detect event keyup on input 
function onKeyUpDays() {
  var projoutputDValue = $("#outputduration").val();
  var outputYear = $("#outputfscyear").val();
  var projendyearDate = $("#projendyearDate").val();
  var projendyear = $("#projendyear").val();
  var outputduration1 = $("#outputduration1").val();
  empty_dissegragation();

  if (outputYear) {
    if (projoutputDValue) {
      if (parseInt(projoutputDValue) > 0) {
        validate_output_days();
      } else {
        $("#outputdurationmsg").show();
        $("#outputduration").val("");
        $("#outputdurationmsg").html("This field cannot be 0 or less");
        $("#outputdurationmsg1").html(outputduration1);
      }
    } else {
      $("#outputdurationmsg").show();
      $("#outputduration").val("");
      $("#outputdurationmsg").html("This field cannot be empty ");
      $("#outputdurationmsg1").html(outputduration1);
    }
  } else {
    $("#outputfscyearmsg").show();
    $("#outputduration").val("");
    $("#outputdurationmsg").show();
    $("#outputfscyearmsg").html("Please enter the financial Year");
    $("#outputdurationmsg").html("Please enter the financial Year");
    $("#outputdurationmsg1").html(outputduration1);
  }
}

function empty_dissegragation() {
  $(".sub_total").each(function () {
    $(this).val("");
  });
  $("#outputTarget").val("");
  $("#ceiling_output_target").val("");
  $("#ceiling_output_target_msg").html("");
}

// function to validate dates 
function validate_output_days() {
  var projoutputDValue = $("#outputduration").val();
  var outputYear = $("#outputStartYear").val();
  var projendyearDate = $("#projendyearDate").val();
  var projendyear = $("#hprojendyear").val();
  var outputduration1 = $("#outputduration1").val();

  if (projoutputDValue) {

    projoutputDValue = parseInt(projoutputDValue);
    var startYear = parseInt(outputYear);
    var years = projoutputDValue / 365;

    var projOutputYear = parseInt(outputYear) + "-07-01";
    var projectEndDate = new Date(projendyearDate);
    var today = new Date(projOutputYear);
    var a = new Date();

    var r = parseInt(outputduration1) - projoutputDValue;

    if (r >= 0) {
      $("#outputdurationmsg1").html(r);
    } else {
      $("#outputdurationmsg1").html(outputduration1);
    }

    if (projoutputDValue % 365 != 0) {
      projoutputDValue = projoutputDValue - 1;
      for (var i = 0; i < years; i++) {
        startYear++;
        var leapYear = isLeap(startYear);
        if (leapYear == true) {
          projoutputDValue++;
        } else {
          projoutputDValue = projoutputDValue;
        }
      }

      Date.prototype.addDays = function (d) {
        today.setDate(today.getDate() + d);
        return today;
      };

      projOutputendyearDate = a.addDays(projoutputDValue);
    } else {
      Date.prototype.addDays = function (d) {
        today.setDate(today.getDate() + d);
        return today;
      };
      projOutputendyearDate = a.addDays(projoutputDValue);
    }

    if (projectEndDate >= projOutputendyearDate) {
      $("#outputdurationmsg").hide();
      get_total_target_ceiling();
    } else {
      $("#outputdurationmsg").show();
      $("#ceiling_output_target").val("");
      $("#ceiling_output_target_msg").html("");
      $("#outputduration").val("");
      $("#outputdurationmsg").html("Enter Duration within Project Duration ");
    }
  }
}

// function to calculate the budget
function budgetCalculate() {
  var outputbudget = $("#outputbudget").val();
  var outputceilingVal = $("#outputceilingVal").val();
  var outputfscyear = $("#outputfscyear").val();
  if (outputfscyear) {
    if (outputceilingVal) {
      if (outputbudget) {
        if (parseFloat(outputbudget) > 0) {
          var remaining =
            parseFloat(outputceilingVal) - parseFloat(outputbudget);
          if (remaining >= 0) {
            $("#outputbudgetmsg").hide();
            $("#outputceiling").val(commaSeparateNumber(remaining));
          } else {
            $("#outputbudgetmsg").show();
            $("#outputbudgetmsg").html(
              "The value enetered is greater than the ceiling "
            );
            $("#outputceiling").val(commaSeparateNumber(outputceilingVal));
            $("#outputbudget").val("");
          }
        } else {
          $("#outputbudgetmsg").show();
          $("#outputbudgetmsg").html("This field cannot be 0 or less");
          $("#outputceiling").val(commaSeparateNumber(outputceilingVal));
          $("#outputbudget").val("");
        }
      } else {
        $("#outputbudgetmsg").show();
        $("#outputbudgetmsg").html("This field cannot be null");
        $("#outputceiling").val(commaSeparateNumber(outputceilingVal));
        $("#outputbudget").val("");
      }
    } else {
      $("#outputbudgetmsg").show();
      $("#outputbudgetmsg").html("No enough Balance for this output");
      $("#outputceiling").val(commaSeparateNumber(outputceilingVal));
      $("#outputbudget").val("");
    }
  } else {
    $("#outputfscyearmsg").show();
    $("#outputbudgetmsg").show();
    $("#outputfscyearmsg").html("Please enter the financial Year");
    $("#outputbudgetmsg").html("Please enter the financial Year");
    $("#outputceiling").val(commaSeparateNumber(outputceilingVal));
    $("#outputbudget").val("");
  }
}

// functions to add the dissegragation values
function add_row_diss() {
  var projstate = $("#projstate").val();
  if (projstate) {
    $("#remove_diss").remove();
    $improwno = $("#diss_state_body tr").length;
    $improwno = $improwno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $improwno + "" + randno;
    $("#diss_state_body tr:last").after(
      `<tr id="row${$row}" >
        <td>${$row}</td>
        <td>
          <select data-id="${$row}" name="diss_states[]" id="diss_statesrow${$row}" class="form-control  selected_diss" required="required">
            <option value="">Select from list</option>
          </select>
        </td>

        <td>
          <input type="hidden" name="diss" value="diss">
            <input type="number" name="diss_states_target[]" id="diss_states_targetrow${$row}" placeholder="Enter" onkeyup="opTargetBal(${$row})" onchange="opTargetBal(${$row})" class="form-control sub_total" style="width:85%; float:right" required />
      </td>
          <td>
            <input type="text" name="diss_states_locations[]" id="diss_states_locationsrow${$row}" placeholder="Enter" class="form-control" style="width:85%; float:right" required />
          </td>
          <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_diss('row${$row}')">
          <span class="glyphicon glyphicon-minus"></span>
        </button>
        </td>
      </tr > `
    );

    numbering_diss();
    get_state($row, 1);
  } else {
    alert("Error Select projstate");
  }
}

// function to delete row for dissegragation  
function delete_row_diss(rowno) {
  $("#" + rowno).remove();
  numbering_diss();
  $number = $("#diss_state_body tr").length;
  if ($number == 1) {
    $("#diss_state_body tr:last").after(
      '<tr id="remove_diss"><td colspan="5" align="center">Add Location </td></tr>'
    );
  }
}

// auto numbering table rows on delete and add new for dissegragation table
function numbering_diss() {
  $("#diss_state_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}


// functions to add the dissegragation values
function add_row_inddiss() {
  var projstate = $("#projstate").val();

  if (projstate) {
    $("#remove_inddiss").remove();
    $improwno = $("#inddiss_state_body tr").length;
    $improwno = $improwno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $improwno + "" + randno;

    $("#inddiss_state_body tr:last").after(
      `<tr id="indrow${$row}" >
          <td>${$row}</td>
          <td>
            <select data-id="${$row}" name="diss_states[]" id="diss_statesindrow${$row}" class="form-control  selected_diss" required="required">
              <option value="">Select from list</option>
            </select>
          </td>
          <td>
            <input type="hidden" name="inddiss" value="inddiss">
              <input type="number" name="diss_states_target[]" id="diss_states_targetindrow${$row}" placeholder="Enter" onkeyup="indopTargetBal(${$row})" onchange="indopTargetBal(${$row})" class="form-control sub_total" style="width:85%; float:right" required />
      </td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_inddiss("indrow${$row}")'>
                <span class="glyphicon glyphicon-minus"></span>
              </button>
          </td>
      </tr> `
    );

    numbering_inddiss();
    get_state($row, 0);
  } else {
    alert("Error Select projstate");
  }
}

// function to delete row for dissegragation  
function delete_row_inddiss(rowno) {
  $("#" + rowno).remove();
  numbering_inddiss();
  $number = $("#inddiss_state_body tr").length;
  if ($number == 1) {
    $("#inddiss_state_body tr:last").after(
      '<tr id="remove_inddiss"><td colspan="5" align="center">Add Forest </td></tr>'
    );
  }
}

// auto numbering table rows on delete and add new for dissegragation table
function numbering_inddiss() {
  $("#inddiss_state_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}


// function to get the project states to be dissegragated 
function get_state(rowno, type) {
  var projstate = $("#projstate").val();
  $.ajax({
    type: "post",
    url: "assets/processor/add-project-process",
    data: "get_location=" + projstate,
    dataType: "html",
    success: function (response) {
      if (type == 1) {
        $("#diss_statesrow" + rowno).html(response);
        $("#diss_states_locationsrow" + rowno).val("");
      } else {
        $("#diss_statesindrow" + rowno).html(response);
        $("#diss_states_locationsindrow" + rowno).val("");
      }
    }
  });
}

// check if the project state has already been selected 
$(document).on("change", ".selected_diss", function (e) {
  var tralse = true;
  var select_funding_arr = [];
  var attrb = $(this).attr("id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();

  $(".selected_diss").each(function (k, v) {
    var getVal = $(v).val();
    if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
      tralse = false;
      alert("You canot select " + selectedText + " more than once ");
      var rw = $(v).attr("data-id");
      var impact_assumptions = "#diss_states_locationsrow" + rw;
      $(v).val("");
      $(impact_assumptions).val("");
      return false;
    } else {
      select_funding_arr.push($(v).val());
    }
  });
  if (!tralse) {
    return false;
  }
});

// indirect 
$(document).on("change", ".selected_inddiss", function (e) {
  var tralse = true;
  var select_funding_arr = [];
  var attrb = $(this).attr("id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();

  $(".selected_inddiss").each(function (k, v) {
    var getVal = $(v).val();
    if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
      tralse = false;
      alert("You canot select " + selectedText + " more than once ");
      var rw = $(v).attr("data-id");
      var impact_assumptions = "#diss_states_locationsindrow" + rw;
      $(v).val("");
      $(impact_assumptions).val("");
      return false;
    } else {
      select_funding_arr.push($(v).val());
    }
  });
  if (!tralse) {
    return false;
  }
});

// validate target of individual states to that of the overal total Target
function opTargetBal(rowno) {
  let total_sum = 0;
  let outputTarget = $("#outputTarget").val();
  let data = parseInt($("#diss_states_targetrow" + rowno).val());

  if (outputTarget != "") {
    if (data > 0) {
      $("#houtputTarget").val(outputTarget);
      $(".sub_total").each(function () {
        if ($(this).val() != "") {
          total_sum = total_sum + parseInt($(this).val());
        }
      });
      let remainder = parseInt(outputTarget) - total_sum;
      if (remainder < 0) {
        alert("You can not exceed the above defined total target");
        $("#diss_states_targetrow" + rowno).val("");
      }
    } else {
      alert("Disaggregation target can not be equal or less than 0");
      $("#diss_states_targetrow" + rowno).val("");
    }
  } else {
    alert("First define total target above");
    $("#diss_states_targetrow" + rowno).val("");
  }

}

function optarget() {
  var outputTarget = parseInt($("#outputTarget").val());
  var ceil = $("#ceiling_output_target").val();

  if (outputTarget > 0) {
    let total_sum = 0;
    $(".sub_total").each(function () {
      if ($(this).val() != "") {
        total_sum = total_sum + parseInt($(this).val());
      }
    });

    if (total_sum != "") {
      let houtputTarget = $("#houtputTarget").val();
      let handler = confirm("Do you wish to adjust the value of target");
      if (handler) {
        $("#houtputTarget").val(outputTarget);
        $(".sub_total").each(function () {
          $(this).val("");
        });

        var remaining_ceil = ceil - outputTarget;
        if (remaining_ceil >= 0) {
          $("#ceiling_output_target_msg").html(commaSeparateNumber(remaining_ceil));
        } else {
          $("#ceiling_output_target_msg").html(commaSeparateNumber(ceil));
          $("#outputTarget").val("");
          alert("Project output target should be greater than indicated Program target balance");
        }

      } else {
        $("#outputTarget").val(houtputTarget);
        var remaining_ceil = ceil - houtputTarget;
        if (remaining_ceil >= 0) {
          $("#ceiling_output_target_msg").val(commaSeparateNumber(remaining_ceil));
        } else {
          $("#ceiling_output_target_msg").val(commaSeparateNumber(ceil));
          $("#outputTarget").val("");
          alert("Project output target should be greater than indicated Program target balance");
        }
      }
    } else {
      var remaining_ceil = ceil - outputTarget;
      if (remaining_ceil >= 0) {
        $("#ceiling_output_target_msg").html(commaSeparateNumber(remaining_ceil));
      } else {
        $("#ceiling_output_target_msg").html(commaSeparateNumber(ceil));
        $("#outputTarget").val("");
        alert("Project output target should be greater than indicated Program target balance");
      }
    }
  } else {
    alert("Value of target should be greater than 0");
    $("#ceiling_output_target_msg").html(commaSeparateNumber(ceil));
    $("#outputTarget").val("");
  }
}

// validate target of individual states to that of the overal total Target 
// indirect 
function indopTargetBal(rowno) {
  let total_sum = 0;
  let outputTarget = $("#outputTarget").val();
  let data = parseInt($("#diss_states_targetindrow" + rowno).val());

  if (outputTarget != "") {
    if (data > 0) {
      $("#houtputTarget").val(outputTarget);
      $(".sub_total").each(function () {
        if ($(this).val() != "") {
          total_sum = total_sum + parseInt($(this).val());
        }
      });
      let remainder = parseInt(outputTarget) - total_sum;
      if (remainder < 0) {
        alert("You can not exceed the above defined total target");
        $("#diss_states_targetindrow" + rowno).val("");
      }
    } else {
      alert("Disaggregation target can not be equal or less than 0");
      $("#diss_states_targetindrow" + rowno).val("");
    }
  } else {
    alert("First define total target above");
    $("#diss_states_targetindrow" + rowno).val("");
  }
}


// ceiling value for total targets 
function get_total_target_ceiling() {
  var progid = $("#progid").val();
  var indicatorid = $("#indicatorids").val();
  var opduration = $("#outputduration").val();
  var outputStartYear = $("#outputStartYear").val();
  var StartYear = $("#outputStartYear").val();
  var years = Math.floor(opduration / 365);

  if (opduration % 365 > 0) {
    years += 1;
  }

  for (var i = 0; i < years; i++) {
    var leapYear = isLeap(outputStartYear);
    if (leapYear == true) {
      opduration--;
    } else {
      opduration = opduration;
    }
    outputStartYear++;
  }

  var years = Math.floor(opduration / 365);

  if (opduration % 365 > 0) {
    years += 1;
  }

  if (opduration != "") {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        get_target_bal: "new_bal",
        progid: progid,
        indicatorid: indicatorid,
        opduration: years,
        projfscyear: StartYear
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#ceiling_output_target").val(response.remaining);
          $("#ceiling_output_target_msg").html(commaSeparateNumber(response.remaining));
        } else {
          alert("Something went wrong");
        }
      }
    });
  } else {
    alert("Enter project duration");
    $("#ceiling_output_target").val("");
    $("#ceiling_output_target_msg").html("");
  }
}

// hidding target fieldsets 
function hide_divs() {
  $("#op_targets_div_fieldset").hide();
  $("#location_targets_div_fieldset").hide();
}

// output form submision  
$("#addprojoutput").submit(function (e) {
  e.preventDefault();
  var form_data = $(this).serialize();
  var rowno = $("#rowno").val();
  var addoutput = $("#addoutput").val();
  var ben_diss = $("#dben_diss").val();

  var message;
  if (addoutput == "addoutput") {
    message = "Record Successfully Saved";
  } else if (addoutput == "editoutput") {
    message = "Record Successfully Edited";
  }

  var diss_target_handler = validate_diss_value(ben_diss);
  var add = $("#addoutput").val();
  var indicator = $("#indicatorid" + rowno).val();
  if (diss_target_handler) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: form_data,
      dataType: "json",
      success: function (response) {
        $("#op_targets_div_fieldset").show();
        check_ben();

        if (add == "addoutput") {
          add_op_targets_div(response);
          if (ben_diss == "1") {
            add_targets_div(response);
          }
        } else if (add == "editoutput") {
          del__op_targets_div(response);
          add_op_targets_div(response);
          if (ben_diss == "1") {
            del_targets_div(response);
            add_targets_div(response);
          }
        }

        alert(message);
        $(".modal").each(function () {
          $(this).modal("hide");
          $(this)
            .find("form")
            .trigger("reset");
          $("#addprojoutput .selectpicker").selectpicker("deselectAll");
        });
        $("#outputIdsTrue" + rowno).val(response);
        $("#output" + rowno).attr("disabled", "disabled");
        $("#outputItemModalBtn" + rowno).html("Edit Details");
        getcost();
        states_auto_val();
      }
    });
  }
});


// function check if you have distriuted all the states
function states_auto_val() {
  var projstate = $("#projstate").val();
  var outputIds = [];

  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      outputIds.push($(this).val());
    }
  });

  var ben_diss_val = [];
  $("input[name='ben_diss[]']").each(function () {
    if ($(this).val() != "") {
      ben_diss_val.push(parseInt($(this).val()));
    }
  });

  if (ben_diss_val.includes(1)) {
    if (outputIds.length > 0) {
      $.ajax({
        type: "post",
        url: "assets/processor/add-project-process",
        data: { projstate: projstate, outputIds: outputIds, validate_state: "val_state" },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#stateVal").val(1);
          } else {
            $("#stateVal").val(0);
          }
        }
      });
    } else {
      Alert("Enter output details");
      return false;
    }
  } else {
    $("#stateVal").val(1);
  }
}

// function to validate if indicator should be disegragated or not 
// function also ensures that the sum total equals the total target 
function validate_diss_value(ben_diss) {
  var label3 = $("#label3level").val();

  if ($('input[name="diss_states_target[]"]').length > 0) {
    var total_val = 0;
    var diss_target = parseInt($("#outputTarget").val());
    if (diss_target) {
      $(".sub_total").each(function () {
        if ($(this).val() != "") {
          total_val = total_val + parseInt($(this).val());
        }
      });

      var diss_remainder = diss_target - total_val;
      if (diss_remainder != 0) {
        alert("Ensure you utilize the above defined total target ");
        return false;
      } else {
        return true;
      }
    } else {
      alert("Enter Target");
      return false;
    }
  } else {
    alert(`Define ${label3} disaggregations`);
    return false;
  }

}

// function to get the cost 
function getcost() {
  var outputIds = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    outputIds.push($(this).val());
  });

  if (outputIds.length > 0) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getprojcost: "getprojcost",
        outputDispId: outputIds
      },
      dataType: "json",
      success: function (response) {
        $("#projcosts").html(commaSeparateNumber(parseFloat(response)));
        $("#outputcost").val(commaSeparateNumber(response));
        $("#financierceiling").val(response);
        var amountfunding = [];
        $("input[name='amountfunding[]']").each(function () {
          if ($(this).val() != "") {
            amountfunding.push(parseFloat($(this).val()));
          }
        });

        var sum = 0;
        for (var i = 0; i < amountfunding.length; i++) {
          sum = sum + amountfunding[i];
        }

        var outputceiling = parseFloat($("#financierceiling").val());
        var remaining = outputceiling - sum;

        if (remaining >= 0) {
          $("#outputcost").val(commaSeparateNumber(remaining));
        } else {
          $("input[name='amountfunding[]']").each(function () {
            $(this).val("");
          });
          $("#outputcost").val(commaSeparateNumber(response));
        }
      }
    });
  } else {
    alert("You must have output before selecting any files here");
  }
}


/////////////////
// Target and Location Workplan 
////////////////////////////////

// ---------location target------------


// get the location target div from db 
function getMeasurements(id) {
  if (id) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: "opdiss=" + id,
      dataType: "html",
      success: function (response) {
        $("#div_" + id).html(response);
      }
    });
  }
}

// get sum of the dissegragated divs 
function get_sum(state, id) {
  var location_target = parseInt($("#locate_numb" + id).val());
  var opid = $(`#locate_numb${id}`).attr("data-id");

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

  if (location_target > 0) {
    if (remainder < 0) {
      alert(`The sum total of disaggregation values cannot exceed  ${level3label} ceiling(${ceilinglocation_target} ${unit})`);
      $("#locate_numb" + id).val("");
      let total_sum = 0;
      $(".locate_total" + state + "" + opid).each(function () {
        if ($(this).val() != "") {
          total_sum = total_sum + parseInt($(this).val());
        }
      });
      let remainder = ceilinglocation_target - total_sum;
      $("#state_ceil" + state + "" + opid).html(`(${remainder} ${unit})`);
    } else {
      $("#state_ceil" + state + "" + opid).html(`(${remainder} ${unit})`);
    }
  } else {
    alert("Value entered should be greater than 0");
    $("#locate_numb" + id).val("");
    $("#state_ceil" + state + "" + opid).html(`(${remainder} ${unit})`);
  }
}

// add location target divs 
function add_targets_div(opid) {
  $(".element:last").after("<div class='element' id='div_" + opid + "'></div>");
  getMeasurements(opid);
}

// check if input field exists 
function validate_target_div() {
  if ($('input[name="outputlocationtarget[]"]').length > 0) {
    return true;
  } else {
    return false;
  }
}

// delete the target divs 
function del_targets_div(opid) {
  $("#div_" + opid).remove();
}

// remove all the location target divs and delete from db 
function del_targets_divs() {
  $("#location_targets_div").empty();
  $("#location_targets_div").html('<div class="element"></div>');
}

//----------------------workplan Target ----------------------

// get workplan target div from the db 
function get_op_target_div(id) {
  var prograid = $("#progid").val();
  if (id) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        get_target_div: "get_target_div",
        outputid: id,
        prograid: prograid
      },
      dataType: "html",
      success: function (response) {
        $("#target_div_" + id).html(response);
      }
    });
  }
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

function get_sum_opyear(opid, year) {
  let ceiling_year_target = parseInt($(`#coptarget_target${opid} `).val());
  let cyear_target = parseInt($(`#cyear_target${opid} ${year} `).val());

  let opid_name = $(`#opid_name${opid} `).val();
  let total_sum = 0;

  $(`.workplanTarget${opid} `).each(function () {
    if ($(this).val() != "") {
      total_sum = total_sum + parseInt($(this).val());
    }
  });

  let remainder = ceiling_year_target - total_sum;

  if (remainder < 0) {
    alert(`The sum total of year values cannot exceed  ${opid_name} ceiling(${ceiling_year_target})`);
    $(`#target_year${opid} ${year} `).val("");
    let total_sum = 0;
    $(`.workplanTarget${opid} `).each(function () {
      if ($(this).val() != "") {
        total_sum = total_sum + parseInt($(this).val());
      }
    });

    let remainder = ceiling_year_target - total_sum;
    $(`#op_target${opid} `).html(remainder);
    $("#year_target" + opid + "" + year).html(cyear_target);
  } else {
    $(`#op_target${opid} `).html(remainder);
  }
}

// function to add workplan target divs
function add_op_targets_div(opid) {
  $(".elementT:last").after("<div class='element' id='target_div_" + opid + "'></div>");
  get_op_target_div(opid);
}


// delete certain target div 
function del__op_targets_div(opid) {
  $("#target_div_" + opid).remove();
}

// function to empty all target tables 
function del__op_targets_divs() {
  $("#op_targets_div").empty();
  $("#op_targets_div").html('<div class="elementT"></div>');
}


// function to delete one the output details already set 
// removes the output table, the location and workplan target divs
function delete_one_output(opid) {
  if (opid) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: { deleteItem: "deleteItem", itemid: opid },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          alert(response.messages)
          del__op_targets_div(opid);
          del_targets_div(opid);

        } else {
          alert(response.messages);
        }
      }
    });
  }
}

// function to delete all the output details already set 
// removes the output table, all the location and workplan target divs
function delete_all_outputs() {
  var outputIds = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    outputIds.push($(this).val());
  });

  if (outputIds.length > 0) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: { deleteItems: "deleteItems", itemIds: outputIds },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          alert(response.messages)
          del__op_targets_divs();
          del_targets_divs();
          hide_divs();
          $("#output_table_body").html(
            '<tr></tr><tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
          );
        } else {
          alert(response.messages);
        }
      }
    });
  } else {
    del__op_targets_divs();
    del_targets_divs();
    hide_divs();
    $("#output_table_body tr:last").after(
      '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
    );
  }
}

// function to check if ouput table has fields
function output_form_fields() {
  if ($("input[name='indicatorid[]']").length > 0) {
    return true;
  } else {
    return false;
  }
}


////////////////
// Implementors
////////////////

// lead implementing partner
const project_lead_implementor = () => {
  var projleadimplementor = $("#projleadimplementor").val();
  if (projleadimplementor) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getImplementingPartner: "implementingPartner",
        leadImplementor: projleadimplementor
      },
      dataType: "html",
      success: function (response) {
        $("#projimplementingpartner").html(response);
        $("#projcollaborativepratner").html(
          '<option value="">....Select Implementing Partner....</option>'
        );
        $(".selectpicker").selectpicker("refresh");
      }
    });
  } else {
    $("#projimplementingpartner").html(
      '<option value="">....Select Lead Implementor....</option>'
    );
    $("#projcollaborativepratner").html(
      '<option value="">....Select Lead Implementor....</option>'
    );
  }
}

// implementing partner function 
const project_implementing_partner = () => {
  var projleadimplementor = $("#projleadimplementor").val();

  var impl = [];
  $("select[name='projimplementingpartner[]'] option:selected").each(
    function () {
      impl.push($(this).val());
    }
  );

  const unselect = "0";
  var data = [];
  if (impl.indexOf(unselect) != -1) {
    data = $.grep(impl, function (n, i) {
      return i == unselect;
    });

    $("select[name='projimplementingpartner[]'] option:selected").each(
      function () {
        if ($(this).val() != unselect) {
          $(this)
            .prop("selected", false)
            .trigger("change");
          $(".selectpicker").selectpicker("refresh");
        }
      }
    );
  } else {
    data = impl;
  }

  if (data) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getcollaborativepratner: "collaborative",
        leadImpl: projleadimplementor,
        implPa: data
      },
      dataType: "html",
      success: function (response) {
        $("#projcollaborativepratner").html(response);
        $(".selectpicker").selectpicker("refresh");
      }
    });
  } else {
    $("#projcollaborativepratner").html(
      '<option value="">....Select Implementing Partner....</option>'
    );
    $(".selectpicker").selectpicker("refresh");
  }
}

// collaborative partner function 
const project_collaborative_partner = () => {
  var collab = [];
  $("select[name='projcollaborativepratner[]'] option:selected").each(
    function () {
      collab.push($(this).val());
    }
  );

  const unselect = "0";
  if (collab.indexOf(unselect) != -1) {
    $("select[name='projcollaborativepratner[]'] option:selected").each(
      function () {
        if ($(this).val() != unselect) {
          $(this)
            .prop("selected", false)
            .trigger("change");
          $(".selectpicker").selectpicker("refresh");
        }
      }
    );
  }
}

// function to add financiers 
function add_row_financier() {
  $("#removeTr").remove(); //new change
  $row = $("#financier_table_body tr").length;
  $row = $row + 1;
  let randno = Math.floor((Math.random() * 1000) + 1);
  let $rowno = $row + "" + randno;
  $("#financier_table_body tr:last").after(
    '<tr id="finrow' +
    $rowno +
    '">' +
    "<td>" +
    $rowno +
    "</td>" +
    "<td>" +
    '<select onchange=financeir_celing("row' +
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
    '" />' +
    '<span id="financierCeilingrow' +
    $rowno +
    '" style="color:red"></span>' +
    "</td>" +
    "<td>" +
    '<input type="hidden" name="amountfundingcurrency[]" id="amountfundingcurrencyrow' +
    $rowno +
    '"><input type="number" name="amountfunding[]" onkeyup=amountfunding("row' +
    $rowno +
    '") onchange=amountfunding("row' +
    $rowno +
    '")   id="amountfundingrow' +
    $rowno +
    '"   placeholder="Enter"  class="form-control financierTotal" style="width:85%; float:right" required/>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("finrow' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  numbering();
  getFinanciers($rowno);
}

// function to delete financiers 
function delete_row_financier(rowno) {
  $("#" + rowno).remove();
  numbering();
  $number = $("#financier_table_body tr").length;
  if ($number == 1) {
    $("#financier_table_body tr:last").after(
      '<tr id="removeTr"><td colspan="5"> Add financiers</td></tr>'
    );
  }
}

// auto numbering table rows on delete and add new for financier table
function numbering() {
  $("#financier_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

// function to get financiers from db whose funds have not been exhausted
function getFinanciers(rowno) {
  var progid = $("#progid").val();
  var financier = "#financerow" + rowno;
  $.ajax({
    type: "post",
    url: "assets/processor/add-project-process",
    data: {
      getfinancier: progid
    },
    dataType: "html",
    success: function (response) {
      $(financier).html(response);
    }
  });
}

//filter the financier cannot be selected twice
$(document).on("change", ".selectedfinance", function (e) {
  var tralse = true;
  var selectImpact_arr = [];
  var attrb = $(this).attr("id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();

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
  if (!tralse) {
    return false;
  }
});

// get the ceiling of the financier selecetd
function financeir_celing(rowno) {
  var finance = $("#finance" + rowno).val();
  var financierCeiling = "#financierCeiling" + rowno;
  var progid = $("#progid").val();
  if (finance) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        finance: "finance",
        sourcecategory: finance,
        progid: progid
      },
      dataType: "json",
      success: function (response) {
        var finance = $("#finance" + rowno).val();
        if (finance) {
          if (response.msg == "true") {
            var responseval = response.remaining;
            $(financierCeiling).html(commaSeparateNumber(parseInt(responseval)));
            $("#ceilingval" + rowno).val(responseval);
          } else if (response.msg == "false") {
            $("#ceilingval" + rowno).val("No Balance");
            $("#finance" + rowno).val("");
            $(financierCeiling).html("");
          }
        } else {
          $("#ceilingval" + rowno).val("No Balance");
          $("#finance" + rowno).val("");
          $(financierCeiling).html("");
        }
      }
    });
  } else {
    alert("Select Input");
    $(financierCeiling).html("");
    $("#amountfunding" + rowno).val("");
  }
}

// function to ensure the ceiling is not exceeded
function amountfunding(rowno) {
  var ceilingval = $("#ceilingval" + rowno).val();
  var amountfunding = $("#amountfunding" + rowno).val();
  // according to the ceiling of the financier
  if (ceilingval) {
    if (amountfunding) {
      if (parseInt(amountfunding) > 0) {
        var remaining = parseInt(ceilingval) - parseInt(amountfunding);
        if (remaining < 0) {
          alert(
            "The value requesting from financiers can not be more than outputs budget!!"
          );
          $("#financierCeiling" + rowno).html(commaSeparateNumber(parseInt(ceilingval)));
          $("#amountfunding" + rowno).val("");
          amount_val_ceil(rowno);
        } else {
          $("#financierCeiling" + rowno).html(commaSeparateNumber(remaining));
          amount_val_ceil(rowno);
        }
      } else {
        alert("The value should be greater than 0");
        $("#financierCeiling" + rowno).html(
          ceilingval
        );
        $("#amountfunding" + rowno).val("");
        amount_val_ceil(rowno);
      }
    } else {
      alert("This field cannot be empty");
      $("#financierCeiling" + rowno).html(
        commaSeparateNumber(parseInt(ceilingval))
      );
      $("#amountfunding" + rowno).val("");
      amount_val_ceil(rowno);
    }
  } else {
    alert("Please select a financier");
    $("#financierCeiling" + rowno).html(
      commaSeparateNumber(parseInt(ceilingval))
    );
    $("#amountfunding" + rowno).val("");
    amount_val_ceil(rowno);
  }
}

// function to ensure that the output cost ceiling is not surpassed 
function amount_val_ceil(rowno) {
  var sum = 0;
  $("input[name='amountfunding[]']").each(function () {
    if ($(this).val() != "") {
      sum = sum + parseFloat($(this).val());
    }
  });

  var financierceiling = parseFloat($("#financierceiling").val());
  var remaining = financierceiling - sum;

  if (remaining >= 0) {
    $("#outputcost").val(commaSeparateNumber(remaining));
  } else {
    $("#amountfunding" + rowno).val("");
    alert("You can not exceed defined project budget");
    var total = 0;
    $("input[name='amountfunding[]']").each(function () {
      if ($(this).val() != "") {
        total = + parseFloat($(this).val());
      }
    });
    var financierceiling = parseFloat($("#financierceiling").val());
    var remeinder = financierceiling - total;
    $("#outputcost").val(commaSeparateNumber(remeinder));
  }
}


////////////////
// Documents
////////////////

function add_row_files() {
  $row = $("#meetings_table tr").length;
  $row = $row + 1;
  let randno = Math.floor((Math.random() * 1000) + 1);
  let $rowno = $row + "" + randno;
  $("#meetings_table tr:last").after(
    '<tr id="mtng' +
    $rowno +
    '">' +
    "<td>" +
    "</td>" +
    "<td>" +
    '<input type="file" name="pfiles[]" id="pfiles" multiple class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>' +
    "</td>" +
    "<td>" +
    '<input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm"  onclick=delete_files("mtng' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  numbering_files();
}

function delete_files(rowno) {
  $("#" + rowno).remove();
  numbering_files();
}

function numbering_files() {
  $("#meetings_table tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx + 1);
  });
}

function add_row_files_edit() {
  $("#add_new_file").remove();
  $row = $("#meetings_table_edit tr").length;
  $row = $row + 1;
  let randno = Math.floor((Math.random() * 1000) + 1);
  let $rowno = $row + "" + randno;

  $("#meetings_table_edit tr:last").after(
    '<tr id="mtng' +
    $rowno +
    '">' +
    "<td>" +
    "</td>" +
    "<td>" +
    '<input type="file" name="pfiles[]" id="pfiles" multiple class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>' +
    "</td>" +
    "<td>" +
    '<input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm"  onclick=delete_files_edit("mtng' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  numbering_files_edit();
}

function delete_files_edit(rowno) {
  $("#" + rowno).remove();
  numbering_files();
  $number = $("#meetings_table_edit tr").length;
  if ($number == 1) {
    $("#meetings_table_edit tr:last").after(
      '<tr id="add_new_file"><td colspan="4">Attach file </td></tr>'
    );
  }
}

function delete_attachment(rowno) {
  var handler = confirm("Are you sure you want to delete the file");
  if (handler) {
    $("#" + rowno).remove();
  }
}

// auto numbering table rows on delete and add new for financier table
function numbering_files_edit() {
  $("#meetings_table_edit tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}


////////////////
// Finish
////////////////


// function to display project details
function project_details() {
  var prog = $("#prog").val();
  var projcode = $("#projcode").val();
  var projname = $("#projname").val();
  var projcase = $("#projcase").val();
  var projfocus = $("#projfocus").val();
  var projimplmethod = $("#projimplmethod>option:selected").text();
  var bigfour = $("#bigfour>option:selected").text();
  var projfscyear = $("#projfscyear1>option:selected").text();
  var projendyear = $("#hprojendyear").val();
  var projduration = $("#projduration1").val();
  var projinspection = $('input[name="projinspection"]:checked').val();
  if (projinspection == 1) {
    $("#projinsps").text("Yes");
  } else {
    $("#projinsps").text("No");
  }

  $("#progs").text(prog);
  $("#projcodes").text(projcode);
  $("#projName").text(projname);
  $("#projcases").text(projcase);
  $("#focusarea").text(projfocus);
  $("#implementation").text(projimplmethod);
  $("#bigfourA").text(bigfour);
  $("#projfscyears").text(projfscyear);
  $("#projdurations").text(
    commaSeparateNumber(parseInt(projduration)) + " Days"
  );
  project_location();
}


function get_total_ouptu_cost(outputIds) {
  if (outputIds) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getprojcost: "getprojcost",
        outputDispId: outputIds
      },
      dataType: "json",
      success: function (response) {
        $("#projcosts").html(commaSeparateNumber(parseFloat(response)));
      }
    });
  }
}

function output_details_display() {
  var outputIds = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      outputIds.push($(this).val());
    }
  });

  if (outputIds.length > 0) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: {
        getoutputDetailsDisp: "getdetails",
        outputDispId: outputIds
      },
      dataType: "html",
      success: function (response) {
        $("#outputDataDisp").html(response);
        get_total_ouptu_cost(outputIds);
      }
    });
  }
}


function location_target_display() {
  var ouputid = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      ouputid.push($(this).val());
    }
  });


  for ($i = 0; $i < ouputid.length; $i++) {
    var opid = ouputid[$i];

    var outputstateclass = ".outputstate" + opid;
    var outputName = $("#locate_opid" + opid).val();
    var indicatorName = $("#indicatorName" + opid).val();
    var unitNameL = $("#unitNameL" + opid).val();
    var ben_diss = $("#ben_diss_value" + opid).val();

    var containerH = '';
    var containerTH = '';
    var containerTB = '';

    if (ben_diss == 1) {
      $(outputstateclass).each(function () {
        if ($(this).val() != "") {
          var state = $(this).val();
          var stateName = $(this).attr("data-id");
          var opname = state + "" + opid;
          var unitName = $(`#unitName${state} ${opid} `).val();
          var locations = $(`input[name = 'outputlocation${opname}[]']`).length;
          containerH = containerH + `<th colspan = "${locations}" > ${stateName}</th> `;

          let total_sum = 0;
          $(".locate_total" + state + "" + opid).each(function () {
            if ($(this).val() != "") {
              total_sum = $(this).val();
              var data_loc = $(this).attr("data-loc");
              containerTH = containerTH + `<th> ${data_loc}</th> `;
              containerTB = containerTB + `<td> ${total_sum} ${unitNameL}</td> `;
            }
          });
        }
      });

      var locate_target_plan = `
          <div class="row clearfix elemetT" id="Targetrowcontainer" >
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                <div class="header">
                  <div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
                    <h5 style="color:#2B982B"><strong> Output: ${outputName}</strong></h5>
                  </div>
                  <div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
                    <h5 style="color:#2B982B"><strong> Indicator: ${indicatorName}</strong></h5>
                  </div>
                </div>
                <div class="body">
                  <div class="row clearfix ">
                    <div class="col-md-12 ">
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                          <thead>
                            <tr>
                              ${containerH}
                            </tr>
                            <tr>${containerTH}</tr>
                          </thead>
                          <tbody>
                            <tr>
                              ${containerTB}
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
      </div> `;
      add_diss_targets_div(opid, locate_target_plan);
    } else {
      add_ind_targets_div(opid);
    }
  }
}


// create div and add element div for diss 
function add_diss_targets_div(opid, data) {
  $(".elementDiv:last").after("<div class='elementDiv' id='div_ind" + opid + "'></div>");

  $(`#div_ind${opid}`).html(data);
}
// create div and add element div for nondiss
function add_ind_targets_div(opid) {
  $(".elementDiv:last").after("<div class='elementDiv' id='div_ind" + opid + "'></div>");
  get_ind_state_val(opid);
}


// get non disaggregated div 
function get_ind_state_val(id) {
  if (id) {
    $.ajax({
      type: "post",
      url: "assets/processor/add-project-process",
      data: "get_ind_state_val=" + id,
      dataType: "html",
      success: function (response) {
        $("#div_ind" + id).html(response);
      }
    });
  }
}



function workplan_target_display() {
  var opid = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      opid.push($(this).val());
    }
  });


  var workplanDetails = '<div>';
  for ($i = 0; $i < opid.length; $i++) {
    var cyear_targetYearclass = ".output_years" + opid[$i];
    var outputName = $(`#workplan_opName${opid[$i]} `).val();
    var unit = $(`#unit${opid[$i]} `).val();
    var indicatorName = $(`#indicatorName${opid[$i]} `).val();

    var containerH = '';
    var containerTB = '';

    $(cyear_targetYearclass).each(function () {
      if ($(this).val() != "") {
        var opyear = $(this).val();
        var newopyear = parseInt(opyear) + 1;
        var newyear = `${opyear} /${newopyear}`;
        var opname = opid[$i] + "" + opyear + "";
        var target_year_val = $("#target_year" + opname).val();
        containerH = containerH + `<th> ${newyear}</th> `;
        containerTB = containerTB + `<td> ${target_year_val}  ${unit}</td> `;
      }
    });


    var workplanDetails = workplanDetails + `
          <div class="row clearfix " id="Targetrowcontainer">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
                                <h5 style="color:#2B982B"><strong> Output: ${outputName}</strong></h5> 
                                </div>
                            <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
                                <h5 style="color:#2B982B"><strong> Indicator: ${indicatorName}</strong></h5> 
                              </div> 
                        </div>
                        <div class="body">
                            <div class="row clearfix "> 
                                <div class="col-md-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                            <thead> 
                                              ${containerH}
                                            </thead>
                                            <tbody>
                                              ${containerTB} 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
  }
  workplanDetails = workplanDetails + `</div > `;
  $("#workplanDetails").html(workplanDetails);
}

function workplan() {
  output_details_display();
  workplan_target_display();

  var ben_diss_val = [];
  $("input[name='ben_diss[]']").each(function () {
    if ($(this).val() != "") {
      ben_diss_val.push(parseInt($(this).val()));
    }
  });

  $("#location_sec_div").show();
  location_target_display();

}

// function to display finance module 
function display_finace() {
  //finance
  var finance = [];
  $("select[name='finance[]'] option:selected").each(function () {
    finance.push($(this).text());
  });

  var amountfunding = [];
  $("input[name='amountfunding[]']").each(function () {
    amountfunding.push($(this).val());
  });

  var financierFunds = "<ul>";
  for (var f = 0; f < finance.length; f++) {
    var financeVal = finance[f];
    var amountfundingVal = amountfunding[f];
    financierFunds =
      financierFunds +
      "<li>" +
      financeVal +
      " : Ksh." +
      commaSeparateNumber(parseInt(amountfundingVal)) +
      "</li>";
  }
  financierFunds = financierFunds + "<ul>";
  $("#financiers").html(financierFunds);
}
// function to get implementor details 
function implementors() {
  // stakeholders
  var projleadimplementor = $("#projleadimplementor>option:selected").text();
  var projimplementingpartner = $(
    "#projimplementingpartner>option:selected"
  ).text();
  var projcollaborativepratner = $(
    "#projcollaborativepratner>option:selected"
  ).text();
  $("#leadImple").text(projleadimplementor);

  $("#ImplPart").text(projimplementingpartner);
  $("#collabPartner").text(projcollaborativepratner);
  display_finace();
}

// gets the location details 
function project_location() {
  var projcommunity = [];
  $("select[name='projcommunity[]'] option:selected").each(function () {
    projcommunity.push($(this).text());
  });

  var projlga = [];
  $("select[name='projlga[]'] option:selected").each(function () {
    projlga.push($(this).text());
  });

  var projstate = [];
  $("select[name='projstate[]'] option:selected").each(function () {
    projstate.push($(this).text());
  });

  $("#projcommunitys").text(projcommunity);
  $("#projlgas").text(projlga);
  $("#projstates").text(projstate);
}

// function to create files display
function attachments() {
  var attachment_purpose = [];
  $(".eattachment_purpose").each(function () {
    attachment_purpose.push($(this).val());
  });

  var file_name = [];
  $(".eattachment_file").each(function () {
    file_name.push($(this).val());
  });

  var files = "";
  for (var i = 0; i < attachment_purpose.length; i++) {
    var attach_p = attachment_purpose[i];
    var f_name = file_name[i];
    var counter = i + 1;
    files =
      files +
      "<tr><td>" +
      counter +
      "</td><td>" +
      attach_p +
      "</td><td>" +
      f_name +
      "</td></tr>";
  }

  var file_pp = [];
  $(".attachment_purpose").each(function () {
    file_pp.push($(this).val());
  });

  var file_attachment = [];
  $(".file_attachment").each(function () {
    file_attachment.push($(this).val().replace(/.*(\/|\\)/, ''));
  });

  for (var j = 0; j < file_attachment.length; j++) {
    var attach_p = file_pp[j];
    var f_name = file_attachment[j];
    var counter = j + 1;
    files =
      files +
      "<tr><td>" +
      counter +
      "</td><td>" +
      attach_p +
      "</td><td>" +
      f_name +
      "</td></tr>";
  }
  $("#files_attached").html(files);
}

// function to listen for the event handlers 
function display_finish() {
  project_details();
  workplan();
  implementors();
  attachments();
}



////////////////////////
// Next step validation 
////////////////////////

//----step 4--- 
// function to ensure that the user adds financier  
function financeValue() {
  if ($("select[name='finance[]'] option:selected").length > 0) {
    var handler = financierBal();
    if (handler) {
      return true;
    } else {
      return false;
    }
  } else {
    alert("Please define the financier/s");
    return false;
  }
}

//function checks if the balance is equal to 0 
function financierBal() {
  var amountfunding = [];
  $("input[name='amountfunding[]']").each(function () {
    amountfunding.push(parseFloat($(this).val()));
  });

  var sum = 0;
  for (var i = 0; i < amountfunding.length; i++) {
    sum = sum + amountfunding[i];
  }

  var outputceiling = parseFloat($("#financierceiling").val());
  var remaining = outputceiling - sum;

  if (remaining == 0) {
    return true;
  } else {
    alert("Ensure that financier/s amount is equal to defined project budget");
    return false;
  }
}



// step  number 2  

// validating location targets 
function validate_states() {
  var outputIds = [];

  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      outputIds.push($(this).val());
    }
  });



  if (outputIds.length > 0) {
    var stateVal = $("#stateVal").val();
    if (stateVal != "" && stateVal == "1") {
      var handler = validate_location_state();
      if (handler) {
        var phandler = workplan_val();
        if (phandler) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      alert("Ensure you have added targets for: ");
      return false;
    }
  } else {
    alert("Ensure you have created outputs");
    return false;
  }


}

//validate the summation of all location targets  the outputs 
function validate_location_state() {
  var ophandler = [];
  var stateFields = '';

  var opid = [];
  $("input[name='outputIdsTrue[]']").each(function () {
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

// validate the summation of all the workplan  outputs 
function workplan_val() {
  var ophandler = [];
  var stateFields = '';

  var opid = [];
  $("input[name='outputIdsTrue[]']").each(function () {
    if ($(this).val() != "") {
      opid.push($(this).val());
    }
  });

  for ($i = 0; $i < opid.length; $i++) {
    var outputid = opid[$i];
    var outputName = $(`#workplan_opName${outputid}`).val();
    var coptarget_target = $(`#coptarget_target${outputid}`).val();
    var workplanTargetClass = $(`.workplanTarget${outputid}`);
    var total_sum = 0;
    $(workplanTargetClass).each(function () {
      if ($(this).val() != "") {
        total_sum = total_sum + parseInt($(this).val());
      }
    });

    var remainder = coptarget_target - total_sum;
    if (remainder != 0) {
      ophandler.push(false);
      stateFields = stateFields + `The sum of targets under ${outputName} do not add up to output ceiling target (${coptarget_target}).  Only ${total_sum} has been entered.`;
    } else {
      ophandler.push(true);
    }
  }

  if (ophandler.includes(false)) {
    alert(stateFields);
    return false;
  } else {
    return true;
  }
}