const url = "ajax/indicators/index";

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
                url: url,
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
                url: url,
                data: form_data,
                dataType: "json",
                success: function (response) {
                    if (response.msg == true) {
                        if (dtype == 0) {
                            output_cat();
                        } else if (dtype == 1) {
                            // others_cat(type_diss);
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

function checkAvailability() {
    $("#loaderIcon").show();
    var indcode = $.trim($("#indcode").val());
    var indid ="";
    jQuery.ajax({
        url: url,
        data: {
            indcode : indcode, 
            indid : indid
        },
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

function get_department() {
    var sctID = $('#indsector').val();
    if (sctID) {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'sct_id=' + sctID,
            success: function (html) {
                $('#inddept').html(html);
            }
        });
    } else {
        $('#inddept').html('<option value="">Select Sector first</option>');
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

function get_disaggregations(rowno, type = null) {
    if (type) {
        $.ajax({
            type: "POST",
            url: url,
            data: "get_disaggregations",
            dataType: "html",
            success: function (response) {
                if (type == 1) {
                    $(`#direct_impact_dissagragation_type${rowno}`).html(response);
                    $(`#direct_impact_dissagragation_parent${rowno}`).html(response);
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

function indicator_disaggregation(ind_diss, indicatorid) {
    if (ind_diss != "") {
      $.ajax({
        type: "post",
        url: url,
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