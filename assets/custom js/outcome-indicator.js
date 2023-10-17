$(document).ready(function () { 
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
    $("#outcome_direct_disagregation").hide();
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

// formula 
function indcalculation_change() {
    var indcalculation = $("#indcalculation").val();  
    var indcategory = "Outcome";
    get_formula(indcalculation, indcategory, outcome_type = 1);
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
                if (outcome_type == "1") {
                    $("#direct_outcome_formula").html(response);
                } else {
                    $("#inddirect_outcome_formula").html(response);
                } 
            }
        });
    }
}
 
//  
function outcome_direct_dissagragation_change() {
    var inddirectBenfType = $(`#inddirectBenfType`).val();
    if (inddirectBenfType != "" && inddirectBenfType == "1") {
        $("#outcome_direct_disagregation").show();
    } else {
        $("#outcome_direct_disagregation").hide();
        $("#direct_outcome_table_body").html(`<tr></tr><tr id="removedirectTr"><td colspan="5">Add Disaggregations</td></tr>`);
    }
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