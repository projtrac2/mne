$(document).ready(function () {
    $("#output_dissegragate").hide();

    $("#output_directBenfType").change(function (e) { 
        e.preventDefault();
        var output_directBenfType = $(this).val();
        if($(this).val() == 1){
            $("#output_dissegragate").show();
        }else{
            $("#output_dissegragate").hide();
        }
    });

    $("#addform").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var addnew = $("#addnew").val();
        var dtype = $("#dissegration_category").val();
        var type_diss = $("#type_diss").val();
        if (addnew == "addunit") {
            $.ajax({
                type: "post",
                url: 'assets/processor/indicator-details.php',
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
                url: 'assets/processor/indicator-details.php',
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
});

function measurement_unit() {
    $.ajax({
        type: "POST",
        url: 'assets/processor/indicator-details.php',
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
            url: 'assets/processor/indicator-details.php',
            data: 'sct_id=' + sctID,
            success: function (html) {
                $('#inddept').html(html);
            }
        });
    } else {
        $('#inddept').html('<option value="">Select Sector first</option>');
    }
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
        var indicatorid = `#output_disaggregations${rowno}`;
        var ind_diss = disaggregations; 
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

function get_disaggregations(rowno, type = null) {
    if (type) {
        $.ajax({
            type: "POST",
            url: 'assets/processor/indicator-details.php',
            data: "get_disaggregations",
            dataType: "html",
            success: function (response) {
                $(`#output_dissagragation_type${rowno}`).html(response);
                $(`#output_dissagragation_parent${rowno}`).html(response); 
            }
        });
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
        url: 'assets/processor/indicator-details.php',
        data: "get_diss_type=0",
        dataType: "html",
        success: function (response) {
            $("#output_directbeneficiarycat").html(response);
            $(".selectpicker").selectpicker("refresh");
        }
    });
}

function indicator_disaggregation(ind_diss, indicatorid) {
    if (ind_diss != "") {
      $.ajax({
        type: "post",
        url: "assets/processor/indicator-details.php",
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

function checkAvailability() {
    $("#loaderIcon").show();
    var indcode = $.trim($("#indcode").val());
    jQuery.ajax({
        url: 'assets/processor/indicator-details.php',
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