$(document).ready(function () {
    var urlpath = window.location.pathname;
    var filename = urlpath.substring(urlpath.lastIndexOf('/') + 1);
	
    if (filename == "add-indicators" || filename == "add-indicators") {
        $("#aggreg").hide(); 
        empty_category(); 
        direction_calculation_hide();
    }

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
});

jQuery(document).ready(function () {
    jQuery('.tabs .tab-links a').on('click', function (e) {
        var currentAttrValue = jQuery(this).attr('href');
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue)
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

function checkAvailability() {
    $("#loaderIcon").show();
    var indcode = $.trim($("#indcode").val());
    jQuery.ajax({
        url: 'assets/processor/indicator-details',
        data: 'indcode=' + indcode,
        type: "POST",
        success: function (data) {
            if (data) {
                $("#addindfrm")[0].reset();
                $("#code-availability-status").html(data);
                $("#loaderIcon").hide();
            } else {
                if (indcode != '') {
                    $("#code-availability-status").html("<label>&nbsp;</label><div class='alert bg-green alert-dismissible' role='alert' style='height:35px; padding-top:5px'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>This Indicator Code (" + indcode + ") is valid and can only be used once</div>");
                    $("#loaderIcon").hide();
                } else if (indcode == '') {
                    $("#code-availability-status").html("");
                    $("#loaderIcon").hide();
                }
            }
        },
        error: function () { }
    });
}

function adddetails(data, dissegration_category = 1, type_diss = 0) {
    if (data == "unit") {
        // show unit details 
        $("#unitsof_measure").show();
        $("#unit").attr("required", "required");
        $("#unitdescription").attr("required", "required");

        $("#modal_title").html("Add Measurement Unit");
        // rename the input name and give it a value 
        $("#addnew").attr("name", "addunit");
        $("#addnew").val("addunit");

        // hide dissagragatio details fields and remove required field 
        $("#diss_type").hide();
        $("#diss_type_name").removeAttr("required");
        $("#disaggregation_cat").removeAttr("required");
        $("#type_diss").val("");
        $("#dissegration_category").val("");
    } else {
        // show unit details fields and remove required field 		
        $("#disaggregation_cat").attr("required","required");
        $("#unitsof_measure").hide();
        $("#unit").removeAttr("required");
        $("#unitdescription").removeAttr("required");
        $("#unit").val("");
        $("#unitdescription").val("");
        $("#modal_title").html("Add Disaggregation Type ");

        // show dissagragatio details fields and remove required field 
        $("#diss_type").show();
        $("#diss_type_name").attr("required", "required");
        $("#type_diss").val(type_diss);
        $("#dissegration_category").val(dissegration_category);

        // rename the input name and give it a value 
        $("#addnew").attr("name", "add_type_diss");
        $("#addnew").val("add_type_diss");
    }
}

function output_cat() {
    $.ajax({
        type: "POST",
        url: 'assets/processor/indicator-details',
        data: "get_diss_type=0",
        dataType: "html",
        success: function (response) {
            $("#output_directbeneficiarycat").html(response);
            $(".selectpicker").selectpicker("refresh");
        }
    });
}

function others_cat(data) {
    var id = '';
    if (data == "direct") {
        id = "#directbeneficiarycat";
    } else {
        id = "#indirectbeneficiarycat";
    }

    $.ajax({
        type: "POST",
        url: 'assets/processor/indicator-details',
        data: "get_diss_type=1",
        dataType: "html",
        success: function (response) {
            $(id).html(response);
            $(".selectpicker").selectpicker("refresh");
        }
    });
}

function measurement_unit() {
    $.ajax({
        type: "POST",
        url: 'assets/processor/indicator-details',
        data: "get_unit",
        dataType: "html",
        success: function (response) {
            $("#indunit").html(response);
        }
    });
}

function get_department() {
    var sctID = $('#indsector').val();
    if (sctID) {
        $.ajax({
            type: 'POST',
            url: 'assets/processor/indicator-details',
            data: 'sct_id=' + sctID,
            success: function (html) {
                $('#inddept').html(html);
            }
        });
    } else {
        $('#inddept').html('<option value="">Select Sector first</option>');
    }
}

  

// function get_indicator_category(indtype) {
//     $("#indcategorydiv").show();
//     if (indtype) {
//         jQuery.ajax({
//             url: 'assets/processor/indicator-details',
//             data: '=' + indtype,
//             type: "POST",
//             success: function (data) {
//                 if (data) {
//                     $("#indcategory").html(data);
//                 }
//             },
//             error: function () { }
//         });
//     }
// }

// category changes 
function indicator_category() {
    var indcategory = $("#indcategory").val(); 
    if (indcategory != "") {

        if (indcategory == "Output") {
            output_category();

            direction_calculation_hide();
        } else {
            direction_calculation_show();
            empty_category();
        }
    } else {
        empty_category();
        direction_calculation_hide();
    }
}

// formula 
function indcalculation_change() {
    var indcalculation = $("#indcalculation").val();
    var indcategory = $("#indcategory").val();
    if (indcalculation != "" && indcategory !== "" && indcategory != "Output") {
        if (indcategory == "Outcome") {
            outcome_category();
            get_formula(indcalculation, indcategory, outcome_type = 1);
        } else {
            impact_category();
            get_formula(indcalculation, indcategory, outcome_type = 3);
        }
    } else {
        empty_category();
    }
}

function get_formula(calculation_method = null, category = null, outcome_type = null) {
    if (calculation_method != null && category != null) {
        $.ajax({
            type: "POST",
            url: 'assets/processor/indicator-details',
            data: {
                get_method: "get_method",
                method: calculation_method,
                outcome_type: outcome_type
            },
            dataType: "html",
            success: function (response) {
                if (category == "Impact") {
                    $("#impact_formula").html(response);
                } else if (category == "Outcome") {
                    if (outcome_type == "1") {
                        $("#direct_outcome_formula").html(response);
                    } else {
                        $("#inddirect_outcome_formula").html(response);
                    }
                }
            }
        });
    }
}

function direction_calculation_show() {
    // calculation method
    $("#indcalculationdiv").show();
    $("#indcalculation").val("");
    $("#indcalculation").attr("required", "required");

    // impact direction 
    $("#direction").show();
    $("#inddirection").val("");
    $("#inddirection").attr("required", "required");
}

function direction_calculation_hide() {
    // Output has no direction 
    $("#indcalculationdiv").hide();
    $("#indcalculation").val("");
    $("#indcalculation").removeAttr("required");

    // Output has no direction 
    $("#direction").hide();
    $("#inddirection").val("");
    $("#inddirection").removeAttr("required");
}

function impact_category() {
    // show impact details from other categories 
    $("#impact_details").show();
    $("#impact_details .form-control").each(function () {
        $(this).attr("required", "required");
        $(this).val("");
    });
    impact_disaggregation_hide();
    output_hide();
    outcome_hide();
}

function impact_hide() {
    // hide Impact details from other categories  and remove reqired and empty fields 
    $("#impact_details").hide();
    $("#impact_details .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
    $("#impact_formula").html("");

    impact_disaggregation_hide();
}

function output_category() {
    $("#output_details").show();
    $("#output_directBenfType").attr("required", "required");
    $("#output_dissegragate").hide();
    $("#output_dissegragate").removeAttr("required");
    impact_hide();
    outcome_hide();
}

function output_hide() {
    // hide output details and remove reqired and empty fields 
    $("#output_details").hide();
    $("#output_details .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
}

function outcome_category() {
    // show outcome details from other categories 
    $("#outcome_details").show();
    $("#outcome_details .form-control").each(function () {
        $(this).attr("required", "required");
        $(this).val("");
    });
    $(".insp").each(function () {
        $(this).attr("required", "required");
    });
    $("#inddirect_outcome_formula").html("");

    outcome_inddirect_disaggregation_hide();
    outcome_direct_disaggregation_hide();
    output_hide();
    impact_hide();
}

function outcome_hide() {
    // hide outcome details from other categories  and remove reqired and empty fields 
    $("#outcome_details").hide();
    $("#direct_outcome_formula").html("");
    $("#inddirect_outcome_formula").html("");

    $("#outcome_details .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });

    $(".insp").each(function () {
        $(this).prop("checked", false);
        $(this).removeAttr("required");
    });

    outcome_inddirect_disaggregation_hide();
    outcome_direct_disaggregation_hide();
}

function empty_category() {
    output_hide();
    outcome_hide();
    impact_hide();
}

// Impact 
function impact_dissagragation_change() {
    var measurementdiss = $(`#measurementdiss`).val();
    if (measurementdiss != "" && measurementdiss == "1") {
        $("#impact_disagregation").show();
    } else {
        impact_disaggregation_hide();
    }
}

function impact_disaggregation_hide() {
    $("#impact_disagregation").hide("");
    $("#impact_table_body").html(`
            <tr></tr>
            <tr id="removeTr">
                <td colspan="5">Add Disaggregations</td>
            </tr>  
        `);
    $("#impact_disagregation").hide();
}

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

// add output table row
// function to add output disaggregations
function add_row_output() {
  $row = $("#output_table_body tr").length;
  $row = $row + 1;
  let randno = Math.floor(Math.random() * 1000 + 1);
  let $rowno = $row + "" + randno;
  $("#removeindoutputTr").remove();
  $("#output_table_body tr:last").after(
    `<tr id="${$rowno}">
            <td></td> 
            <td> 
                <select name="output_dissagragation_type[]" id="output_dissagragation_type${$rowno}" onchange="output_dissagragation_type_change(${$rowno})" class="form-control show-tick output" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                </select> 
            </td> 
            <td>
                <select name="output_dissagragation_parent[]" id="output_dissagragation_parent${$rowno}" onchange="output_dissagragation_parent_change(${$rowno})" class="form-control show-tick output_parent" style="border:1px #CCC thin solid; border-radius:5px">
                </select>
            </td> 
            <td>
                <input type="text" name="output_disaggregations[]" id="output_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
            </td> 
                <td>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
        </tr>`
  );

  number_output_table();
  get_disaggregations($rowno, (type = 4));
}

// function to number output table
function number_output_table() {
  $("#output_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

// function to delete ouput rows
function delete_row_output(rowno) {
  output_match(rowno);
  $("#" + rowno).remove();
  number_output_table();
  $number = $("#output_table_body tr").length;
  if ($number == 1) {
    $("#output_table_body tr:last").after(
      '<tr id="removeindoutputTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
    );
  }
}
function output_match(rowno) {
  var disaggregations = $(`#output_dissagragation_type${rowno}`).val();
  var parent = $(`#output_dissagragation_parent${rowno}`).val();

  if (disaggregations != "") {
    $(".output_parent").each(function () {
      if ($(this).val() != "") {
        if ($(this).val() == disaggregations) {
          $(this).val("");
        }
      }
    });
    return true;
  } else {
    return true;
  }
}

function output_dissagragation_type_change(rowno) {
  var disaggregations = $(`#output_dissagragation_type${rowno}`).val();
  var parent = $(`#output_dissagragation_parent${rowno}`).val();
  if (disaggregations != "") {
    if (disaggregations != parent) {
      var data = [];
      $(".output").each(function () {
        if ($(this).val() != "") {
          data.push($(this).val());
        }
      });
      var handler = data.includes(parent);
      if (!handler) {
        $(`#output_dissagragation_parent${rowno}`).val("");
        console.log(disaggregations);
        var indicatorid = `#output_disaggregations${rowno}`;
        var ind_diss = disaggregations;
        console.log("Here we re");
        indicator_disaggregation(ind_diss, indicatorid);
      }
    } else {
      $(`#output_dissagragation_parent${rowno}`).val("");
    }
  } else {
    $(`#output_dissagragation_parent${rowno}`).val("");
  }
}

function output_dissagragation_parent_change(rowno) {
  var disaggregations = $(`#output_dissagragation_type${rowno}`).val();
  var parent = $(`#output_dissagragation_parent${rowno}`).val();

  if (disaggregations != "") {
    if (disaggregations != parent) {
      var data = [];
      $(".output").each(function () {
        if ($(this).val() != "") {
          data.push($(this).val());
        }
      });
      var handler = data.includes(parent);
      if (!handler) {
        $(`#output_dissagragation_parent${rowno}`).val("");
      }
    } else {
      $(`#output_dissagragation_parent${rowno}`).val("");
    }
  } else {
    $(`#output_dissagragation_parent${rowno}`).val("");
  }
}

// add impact table row
// function to add impact disaggregations  
function add_row_impact() {
    $row = $("#impact_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $rowno = $row + "" + randno;
    $("#removeTr").remove();
    $("#impact_table_body tr:last").after(
        `<tr id="${$rowno}">
            <td></td> 
            <td> 
                <select name="impact_dissagragation_type[]" id="impact_dissagragation_type${$rowno}" onchange="impact_dissagragation_type_change(${$rowno})" class="form-control show-tick impact" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                </select> 
            </td> 
            <td>
                <select name="impact_dissagragation_parent[]" id="impact_dissagragation_parent${$rowno}" onchange="impact_dissagragation_parent_change(${$rowno})" class="form-control show-tick impact_parent" style="border:1px #CCC thin solid; border-radius:5px">
                </select>
            </td> 
            <td>
                <input type="text" name="impact_disaggregations[]" id="impact_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
            </td> 
                <td>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_impact("${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
        </tr>`
    );

    number_impact_table();
    get_disaggregations($rowno, type = 1);
}

// function to number impact table 
function number_impact_table() {
    $("#impact_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

// function to delete impact rows  
function delete_row_impact(rowno) {
    impact_match(rowno);
    $("#" + rowno).remove();
    number_impact_table();
    $number = $("#impact_table_body tr").length;
    if ($number == 1) {
        $("#impact_table_body tr:last").after(
            '<tr id="removeTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
        );
    }
}

function impact_match(rowno) {
    var disaggregations = $(`#impact_dissagragation_type${rowno}`).val();
    var parent = $(`#impact_dissagragation_parent${rowno}`).val();

    if (disaggregations != "") {
        $(".impact_parent").each(function () {
            if ($(this).val() != "") {
                if ($(this).val() == disaggregations) {
                    $(this).val("");
                }
            }
        });
        return true;
    } else {
        return true;
    }
}

function get_disaggregations(rowno, type = null) {
    if (type) {
        $.ajax({
            type: "POST",
            url: 'assets/processor/indicator-details',
            data: "get_disaggregations",
            dataType: "html",
            success: function (response) {
                if (type == 1) {
                    $(`#impact_dissagragation_type${rowno}`).html(response);
                    $(`#impact_dissagragation_parent${rowno}`).html(response);
                } else if (type == 2) {
                    $(`#direct_outcome_dissagragation_type${rowno}`).html(response);
                    $(`#direct_outcome_dissagragation_parent${rowno}`).html(response);
                } else if (type == 3) {
                    $(`#indirect_outcome_dissagragation_type${rowno}`).html(response);
                    $(`#indirect_outcome_dissagragation_parent${rowno}`).html(response);
                } else if (type == 4) {
					$(`#output_dissagragation_type${rowno}`).html(response);
					$(`#output_dissagragation_parent${rowno}`).html(response);
				}
            }
        });
    }
}

function impact_dissagragation_type_change(rowno) {
    var disaggregations = $(`#impact_dissagragation_type${rowno}`).val();
    var parent = $(`#impact_dissagragation_parent${rowno}`).val();
    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".impact").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#impact_dissagragation_parent${rowno}`).val("");
				var indicatorid = `#impact_disaggregations${rowno}`;
				var ind_diss = disaggregations;
				indicator_disaggregation(ind_diss, indicatorid);
            }
        } else {
            $(`#impact_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#impact_dissagragation_parent${rowno}`).val("");
    }
}

function impact_dissagragation_parent_change(rowno) {
    var disaggregations = $(`#impact_dissagragation_type${rowno}`).val();
    var parent = $(`#impact_dissagragation_parent${rowno}`).val();

    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".impact").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#impact_dissagragation_parent${rowno}`).val("");
            }
        } else {
            $(`#impact_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#impact_dissagragation_parent${rowno}`).val("");
    }
}

// outcome 
// direct 
function outcome_direct_dissagragation_change() {
    var inddirectBenfType = $(`#inddirectBenfType`).val();
    if (inddirectBenfType != "" && inddirectBenfType == "1") {
        $("#outcome_direct_disagregation").show();
        var indirect_ben = $("input[name='beneficiary']:checked").val();
        if (indirect_ben == "1") {
            $("#outcome_inddirect_disagregation").show();
        } else {
            outcome_inddirect_disaggregation_hide();
        }
    } else {
        outcome_direct_disaggregation_hide();
        outcome_inddirect_disaggregation_hide();
    }
}

function outcome_direct_disaggregation_hide() {
    $("#direct_outcome_table_body").html(`
            <tr></tr>
            <tr id="removedirectTr">
                <td colspan="5">Add Direct Disaggregations</td>
            </tr>  
        `);
    $("#outcome_direct_disagregation").hide();
}

// function to add direct outcome disaggregations  
function add_row_direct_outcome() {
    $row = $("#direct_outcome_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $rowno = $row + "" + randno;
    $("#removedirectTr").remove();
    $("#direct_outcome_table_body tr:last").after(
        `<tr id="direct${$rowno}">
            <td></td> 
            <td> 
                <select name="direct_outcome_dissagragation_type[]" id="direct_outcome_dissagragation_type${$rowno}" onchange="direct_outcome_dissagragation_type_change(${$rowno})" class="form-control show-tick direct_outcome" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                </select> 
            </td> 
            <td> 
                <select name="direct_outcome_dissagragation_parent[]" id="direct_outcome_dissagragation_parent${$rowno}" onchange="direct_outcome_dissagragation_parent_change(${$rowno})" class="form-control show-tick direct_outcome_parent" style="border:1px #CCC thin solid; border-radius:5px">
                </select>
            </td> 
            <td>
                <input type="text" name="direct_outcome_disaggregations[]" id="direct_outcome_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
            </td> 
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_direct_outcome("direct${$rowno}","${$rowno}")'>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`
    );
    number_direct_outcome();
    get_disaggregations($rowno, type = 2);
}

// function to number direct impact table 
function number_direct_outcome() {
    $("#direct_outcome_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

// function to delete direct outcome rows  
function delete_row_direct_outcome(rowno, rowid) {
    direct_outcome_match(rowid);
    $("#" + rowno).remove();
    number_direct_outcome();
    $number = $("#direct_outcome_table_body tr").length;
    if ($number == 1) {
        $("#direct_outcome_table_body tr:last").after(
            '<tr id="removedirectTr"><td colspan="5" align="center"> Add Direct Disaggregations</td></tr>'
        );
    }
}

function direct_outcome_match(rowno) {
    var disaggregations = $(`#direct_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#direct_outcome_dissagragation_parent${rowno}`).val();
    if (disaggregations != "") {
        $(".direct_outcome_parent").each(function () {
            if ($(this).val() != "") {
                if ($(this).val() == disaggregations) {
                    $(this).val("");
                }
            }
        });
        return true;
    } else {
        return true;
    }
}

function direct_outcome_dissagragation_type_change(rowno) {
    var disaggregations = $(`#direct_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#direct_outcome_dissagragation_parent${rowno}`).val();
    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".impact").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
				indicator_disaggregation(
				  disaggregations,
				  `#direct_outcome_disaggregations${rowno}`
				);
            }
        } else {
            $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
    }
}

function direct_outcome_dissagragation_parent_change(rowno) {
    var disaggregations = $(`#direct_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#direct_outcome_dissagragation_parent${rowno}`).val();

    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".direct_outcome").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
            }
        } else {
            $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#direct_outcome_dissagragation_parent${rowno}`).val("");
    }
}

// indirect 
function outcome_inddirect_dissagragation_change() {
    var indindirectBenfType = $(`#indindirectBenfType`).val();
    if (indindirectBenfType != "" && indindirectBenfType == "1") {
        $("#outcome_inddirect_disagregation").show();
    } else {
        outcome_inddirect_disaggregation_hide();
    }
}

function outcome_inddirect_disaggregation_hide() {
    $("#inddirect_outcome_table_body").html(`
            <tr></tr>
            <tr id="removeinddirectTr">
                <td colspan="5">Add Disaggregations</td>
            </tr>  
        `);
    $("#outcome_inddirect_disagregation").hide();
}

$("input[name=beneficiary]").on("change", function () {
    var indirect_ben = $("input[name='beneficiary']:checked").val();
    console.log(indirect_ben);

    var inddirectBenfType = $(`#inddirectBenfType`).val();
    if (indirect_ben) {
        if (indirect_ben == "1") {
            var indcalculation = $("#indcalculation").val();
            var indcategory = $("#indcategory").val();
            if (indcalculation != "" && indcategory !== "") {
                if (indcategory == "Outcome") {
                    get_formula(indcalculation, indcategory, outcome_type = 2);
                    if (inddirectBenfType != "" && inddirectBenfType == "1") {
                        $("#outcome_inddirect_disagregation").show();
                    } else {
                        outcome_inddirect_disaggregation_hide();
                    }
                }
            }
        } else {
            outcome_inddirect_disaggregation_hide();
            $("#inddirect_outcome_formula").html("");
        }
    } else {
        outcome_inddirect_disaggregation_hide();
        $("#inddirect_outcome_formula").html("");
    }
});

function ben() {
    var indirect_ben = $("input[name='beneficiary']:checked").val();
    var inddirectBenfType = $(`#inddirectBenfType`).val();
    if (indirect_ben) {
        if (indirect_ben == "1") {
            var indcalculation = $("#indcalculation").val();
            var indcategory = $("#indcategory").val();
            if (indcalculation != "" && indcategory !== "") {
                if (indcategory == "Outcome") {
                    get_formula(indcalculation, indcategory, outcome_type = 2);
                    if (inddirectBenfType != "" && inddirectBenfType == "1") {
                        $("#outcome_inddirect_disagregation").show();
                    } else {
                        outcome_inddirect_disaggregation_hide();
                    }
                }
            }
        } else {
            outcome_inddirect_disaggregation_hide();
            $("#inddirect_outcome_formula").html("");
        }
    } else {
        outcome_inddirect_disaggregation_hide();
        $("#inddirect_outcome_formula").html("");
    }
}

// function to add direct outcome disaggregations  
function add_row_inddirect_outcome() {
    $row = $("#inddirect_outcome_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $rowno = $row + "" + randno;
    $("#removeinddirectTr").remove();
    $("#inddirect_outcome_table_body tr:last").after(
        `<tr id="inddirect${$rowno}">
            <td></td> 
            <td> 
                <select name="indirect_outcome_dissagragation_type[]" id="indirect_outcome_dissagragation_type${$rowno}" onchange="indirect_outcome_dissagragation_type_change(${$rowno})" class="form-control show-tick indirect_outcome" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                </select> 
            </td> 
            <td>
                <select name="indirect_outcome_dissagragation_parent[]" id="indirect_outcome_dissagragation_parent${$rowno}" onchange="indirect_outcome_dissagragation_parent_change(${$rowno})" class="form-control show-tick indirect_outcome_parent" style="border:1px #CCC thin solid; border-radius:5px">
                </select>
            </td> 
            <td>
                <input type="text" name="indirect_outcome_disaggregations[]" id="indirect_outcome_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
            </td>  
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_inddirect_outcome('inddirect${$rowno}','${$rowno}')">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`
    );
    number_inddirect_outcome_table();
    get_disaggregations($rowno, type = 3);
}

// function to number direct impact table 
function number_inddirect_outcome_table() {
    $("#inddirect_outcome_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

// function to delete direct outcome rows  
function delete_row_inddirect_outcome(rowno, rowid) {
    indirect_outcome_match(rowid);
    $("#" + rowno).remove();
    number_inddirect_outcome_table();
    $number = $("#inddirect_outcome_table_body tr").length;
    if ($number == 1) {
        $("#inddirect_outcome_table_body tr:last").after(
            '<tr id="removeinddirectTr"><td colspan="5" align="center"> Add indirect Disaggregations</td></tr>'
        );
    }
}

function indirect_outcome_match(rowno) {
    var disaggregations = $(`#indirect_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#indirect_outcome_dissagragation_parent${rowno}`).val();
    if (disaggregations != "") {
        $(".indirect_outcome_parent").each(function () {
            if ($(this).val() != "") {
                if ($(this).val() == disaggregations) {
                    $(this).val("");
                }
            }
        });
        return true;
    } else {
        return true;
    }
}

function indirect_outcome_dissagragation_type_change(rowno) {
    var disaggregations = $(`#indirect_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#indirect_outcome_dissagragation_parent${rowno}`).val();
    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".impact").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");			
				indicator_disaggregation(
				  disaggregations,
				  `#indirect_outcome_disaggregations${rowno}`
				);
            }
        } else {
            $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");
    }
}

function indirect_outcome_dissagragation_parent_change(rowno) {
    var disaggregations = $(`#indirect_outcome_dissagragation_type${rowno}`).val();
    var parent = $(`#indirect_outcome_dissagragation_parent${rowno}`).val();

    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".indirect_outcome").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");
            }
        } else {
            $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#indirect_outcome_dissagragation_parent${rowno}`).val("");
    }
}

// output
function output_disaggregation() {
    var output_directBenfType = $("#output_directBenfType").val();
    if (output_directBenfType != "" && output_directBenfType == 1) {
        $("#output_dissegragate").show();
        $("#output_directbeneficiarycat").attr("required", "required");
        get_output_disaggregation_type();
    } else {
        $("#output_dissegragate").hide();
        $("#output_directbeneficiarycat").removeAttr("required");
        $("#output_directbeneficiarycat").val("");
    }
}

function get_output_disaggregation_type() {
    $.ajax({
        type: "POST",
        url: 'assets/processor/indicator-details',
        data: "get_diss_type=0",
        dataType: "html",
        success: function (response) {
            $("#output_directbeneficiarycat").html(response);
            $(".selectpicker").selectpicker("refresh");
        }
    });
}

$("#addform").submit(function (e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    var addnew = $("#addnew").val();
    var dtype = $("#dissegration_category").val();
    var type_diss = $("#type_diss").val();
    if (addnew == "addunit") {
        $.ajax({
            type: "post",
            url: 'assets/processor/indicator-details',
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.msg == true) {
                    measurement_unit();
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                } else {
                    alert("Error while saving data ");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                }
            }
        });
    } else if (addnew == "add_type_diss") {
        $.ajax({
            type: "post",
            url: 'assets/processor/indicator-details',
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.msg == true) {
                    if (dtype == 0) {
                        output_cat();
                    } else if (dtype == 1) {
                        others_cat(type_diss);
                    } else {
                        console.log("error");
                    }

                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                } else {
                    alert("Error while saving data ");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this).find("form").trigger("reset");
                    });
                }
            }
        });
    } else {
        alert("Error while trying to save data");
        $(".modal").each(function () {
            $(this).modal("hide");
            $(this).find("form").trigger("reset");
        });
    }
});

function impact_validate() {
    var measurementdiss = $("#measurementdiss").val();
    if (measurementdiss != "" && measurementdiss == "1") {
        var fields = $("select[name='impact_dissagragation_type[]'] option:selected").length;
        if (fields > 0) {
            return true;
        } else {
            alert("Ensure you have entered impact  disaggregations");
            return false;
        }
    } else {
        return true;
    }
}

function outcome_validate() {
    var inddirectBenfType = $("#inddirectBenfType").val();
    if (inddirectBenfType != "" && inddirectBenfType == "1") {
        var direct_fields = $("select[name='direct_outcome_dissagragation_type[]'] option:selected").length;
        var indirect_ben = $("input[name='beneficiary']:checked").val();
        var indirect_fields = 1;
        if (indirect_ben == "1") {
            indirect_fields = $("select[name='indirect_outcome_dissagragation_type[]'] option:selected").length;
        }

        if (direct_fields > 0) {
            if (indirect_fields > 0) {
                return true;
            } else {
                alert("Ensure you have given the indirect disaggregations");
                return false;
            }
        } else {
            alert("Ensure you have given the direct disaggregations");
            return false;
        }
    } else {
        return true;
    }
}

function form_validate() {
    var indcategory = $("#indcategory").val();
    if (indcategory == "Impact") {
        var impacthandler = impact_validate();
        if (impacthandler) {
            return true;
        } else {
            return false;
        }
    } else if (indcategory == "Outcome") {
        var outcomehandler = outcome_validate();
        if (outcomehandler) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function indicator_disaggregation(ind_diss, indicatorid) {
  if (ind_diss != "") {
    $.ajax({
      type: "post",
      url: "assets/processor/indicator-details",
      data: {
        getdisstype: "new",
        ind_diss: ind_diss,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $(indicatorid).val("");
          $(indicatorid).attr("readonly", "readonly");
          $(indicatorid).removeAttr("required");
        } else {
          $(indicatorid).attr("required", "required");
          $(indicatorid).val("");
          $(indicatorid).removeAttr("readonly");
        }
      },
    });
  } else {
    $(indicatorid).attr("required", "required");
    $(indicatorid).val("");
  }
}

/* $("#ministry").on("change", function () {
    var ministry = $(this).val();
    //console.log(ministry);

    if (ministry) {       
        $.ajax({
            type: "post",
            url: 'assets/processor/indicator-details',
            data: {
					get_enumerator: ministry,
				},
            dataType: "html",
			success: function (response) {
				$("#inhouse").html(response);
				$(".selectpicker").selectpicker("refresh");
			}
        });
    }
}); */