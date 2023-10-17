const ajax_processor = "ajax/mneplan/add-monitoring-evaluation-plan-processor";
const ajax_url = "ajax/mneplan/index.php";
const monitoring_ajax = "ajax/monitoring/index";
$(document).ready(function () {
    $(".impactquestions").hide();
    $(".outcomequestions").hide();
    $("#editoutcomedataSource").on("change", function () {
        var source = $(this).val("");
        if (source == 1) {
            $(".editquestions").show();
        } else {
            $(".editquestions").hide();
            $('.querry').removeAttr('required');
        }
    });

    $("input[name=projimpact]").on("change", function () {
        var impactVal = $("input[name='projimpact']:checked").val();
        if (impactVal) {
            if (impactVal == "1") {
                get_impact_table();
                get_limits(type = 1);
            } else if (impactVal == 0) {
                $("#impact_details").empty();
            }
        }
    });

    get_limits(type = 2);
    get_limits(type = 1);
});

function add_impact_questions() {
    var source = $("#impactdataSource").val();
    if (source == 1) {
        const input = document.getElementById('impactmainquestion');
        input.setAttribute('required', '');
        $(".impactquestions").show();
    } else {
        $(".impactquestions").hide();
        $('.impactquerry').removeAttr('required');
        $('#impactmainquestion').removeAttr('required');
    }
}

function add_outcome_questions() {
    var source = $("#outcomedataSource").val();
    if (source == 1) {
        const input = document.getElementById('outcomemainquestion');
        input.setAttribute('required', '');
        $(".outcomequestions").show();
    } else {
        $(".outcomequestions").hide();
        $('.querry').removeAttr('required');
        $('#outcomemainquestion').removeAttr('required');
    }
}

function get_outcome_details() {
    var outcome_id = $("#outcomeIndicator").val();
    if (outcome_id != "") {
        $.ajax({
            type: "post",
            url: monitoring_ajax,
            data: {
                outcome_details: "get_outcome_details",
                outcome_id: outcome_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#outcom_calc_method").val(response.outcom_calc_method);
                    $("#outcomeunitofmeasure").val(response.outcomeunitofmeasure);
                } else {
                    swal("Error!", "Could not find outcome details!", "error");
                }
            }
        });
    }
}

function get_impact_details() {
    var impact_id = $("#impactIndicator").val();
    if (impact_id != "") {
        $.ajax({
            type: "post",
            url: monitoring_ajax,
            data: {
                impact_details: "get_impact_details",
                impact_id: impact_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#impact_calc_method").val(response.impact_calc_method);
                    $("#impactunitofmeasure").val(response.impactunitofmeasure);
                } else {
                    swal("Error!", "Could not find impact details!", "error");
                }
            }
        });
    }
}

function get_limits(type = null) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: { type: type, get_limits: "get_limits" },
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
                if (type == 2) {
                    $("#outcomeEvaluation").val(response.assessment);
                } else if (type == 1) {
                    $("#impactEvaluation").val(response.assessment);
                }
            } else {
                alert("Error");
            }
        }
    });
}

function get_reporting_timeline() {
    var outputMonitorigFreq = $("#outputMonitorigFreq").val();
    if (outputMonitorigFreq != "") {
        $.ajax({
            type: "post",
            url: ajax_processor,
            data: {
                get_reportingtimeline: outputMonitorigFreq,
            },
            dataType: "html",
            success: function (response) {
                $("#outputReportingTimeline").html(response);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    } else {
        $("#outputReportingTimeline").html('<option value="">Select Monitoring Frequency First</option>');
        $(".selectpicker").selectpicker("refresh");
    }
}

function impact_Evaluation() {
    var impactEvaluationFreq = $("#impactEvaluationFreq").val();
    var impact_assessment = parseInt($("#impactEvaluation").val());
    if (impactEvaluationFreq) {
        impactEvaluationFreq = parseInt(impactEvaluationFreq);
        if (impactEvaluationFreq > impact_assessment) {
            $("#impactEvaluationFreq").val("");
            alert(`Error!!! cannot exceed ${impact_assessment}  years`);
        } else if (impactEvaluationFreq <= 0) {
            $("#impactEvaluationFreq").val("");
            alert("Error!!! cannot be less or equal to 0 ");
        }
    }
}

function outcome_Evaluation() {
    var outcomeEvaluationFrequency = $("#outcomeEvaluationFreq").val();
    var outcome_assessment = parseInt($("#outcomeEvaluation").val());
    if (outcomeEvaluationFrequency) {
        outcomeEvaluationFrequency = parseInt(outcomeEvaluationFrequency);
        if (outcomeEvaluationFrequency > outcome_assessment) {
            $("#outcomeEvaluationFreq").val("");
            alert(`Error!!! cannot exceed ${outcome_assessment} years`);
        } else if (outcomeEvaluationFrequency <= 0) {
            $("#outcomeEvaluationFreq").val("");
            alert("Error!!! cannot be less or equal to 0 ");
        }
    }
}

function get_impact_table() {
    var progid = $("#progid").val();
    if (progid) {
        $.ajax({
            type: "post",
            url: ajax_processor,
            data: {
                progid: progid,
                get_impact_table: "get_table"
            },
            dataType: "html",
            success: function (response) {
                $("#impact_details").html(response);
                $(".selectpicker").selectpicker("refresh");

            }
        });
    }
}


function add_row_impact() {
    $improwno = $("#impact_table_body tr").length;
    $improwno = $improwno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $improwno + "" + randno;
    $("#impact_table_body tr:last").after(
        '<tr id="row' +
        $row +
        '">' +
        "<td>" +
        $row +
        "</td>" +
        "<td>" +
        '<select  data-id="' +
        $row +
        '" name="impactrisk[]" id="impactriskrow' +
        $row +
        '" class="form-control  selected_impact" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="text" name="impact_assumptions[]"  id="impact_assumptionsrow' +
        $row +
        '"   placeholder="Enter impact risk assumption"  class="form-control"  required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_impact("row' +
        $row +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_impact();
    get_impact_risks($row);
}

// function to delete row
function delete_row_impact(rowno) {
    $("#" + rowno).remove();
    numbering_impact();
}

// auto numbering table rows on delete and add new for financier table
function numbering_impact() {
    $("#impact_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

// check if the input has already been selected
$(document).on("change", ".selected_impact", function (e) {
    var tralse = true;
    var select_funding_arr = [];
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".selected_impact").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
            tralse = false;
            alert("You cannot select Risk " + selectedText + " more than once ");
            var rw = $(v).attr("data-id");
            var impact_assumptions = "#impact_assumptionsrow" + rw;
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
/*
function get_impact_risks(rowno) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: "get_risks=1",
        dataType: "html",
        success: function (response) {
            $("#impactriskrow" + rowno).html(response);
            $("#impact_assumptionsrow" + rowno).val("");
        }
    });
} */

function get_impact_ind_details() {
    var indicator = $("#impactIndicator").val();
    if (indicator) {
        $.ajax({
            type: "post",
            url: ajax_processor,
            data: { impact_indicator: indicator, ind_category: "impact" },
            dataType: "json",
            success: function (response) {
                $("#impact_calc_method").val(response.impact_calc_method);
                $("#unitofmeasure").val(response.unitofmeasure);
            }
        });
    } else {
        $("#impact_calc_method").val("");
        $("#unitofmeasure").val("");
    }
}

function impact_target_change() {
    let total_target = $("#himpactTarget").val();
    let total_sum = 0;
    $(".sub_total_imptarget").each(function () {
        if ($(this).val() != "") {
            total_sum = total_sum + parseInt($(this).val());
        }
    });
    if (total_sum > 0) {
        var confirm_handler = confirm("Would you like to amend impact indicator target");
        if (confirm_handler) {
            $(".sub_total_imptarget").each(function () {
                $(this).val("");
            });
        } else {
            $("#impactTarget").val(total_target);
        }
    }
}

function impact_sum_target(rowno) {
    let total_target = $("#impactTarget").val();
    himpactTarget
    if (total_target != "") {
        $("#himpactTarget").val(total_target);
        var outcome_dissvalue = parseInt($(`#impact_dissvalue${rowno}`).val());
        if ($(`#impact_dissvalue${rowno}`).val() != "") {
            if (outcome_dissvalue > 0) {
                let total_sum = 0;
                $(".sub_total_imptarget").each(function () {
                    if ($(this).val() != "") {
                        total_sum = total_sum + parseInt($(this).val());
                    }
                });
                let remaining_outcome_target = parseInt(total_target) - total_sum;
                if (remaining_outcome_target < 0) {
                    $(`#impact_dissvalue${rowno}`).val("");
                    alert("Total sum of direct beneficiary disaggregation target values cannot exceed above defined target")
                }
            } else {
                $(`#impact_dissvalue${rowno}`).val("");
                alert("Direct beneficiary disaggregation target value cannot be equal or less than 0");
            }
        }
    } else {
        $(`#impact_dissvalue${rowno}`).val("");
        alert("Define impact direct beneficiary total target above first");
    }
}

function impact_indsum_target(rowno) {
    let total_target = $("#indimpactTarget").val();
    himpactTarget
    if (total_target != "") {
        $("#hindimpactTarget").val(total_target);
        var outcome_dissvalue = parseInt($(`#indimpact_dissvalue${rowno}`).val());
        if ($(`#indimpact_dissvalue${rowno}`).val() != "") {
            if (outcome_dissvalue > 0) {
                let total_sum = 0;
                $(".sub_indtotal_imptarget").each(function () {
                    if ($(this).val() != "") {
                        total_sum = total_sum + parseInt($(this).val());
                    }
                });
                let remaining_outcome_target = parseInt(total_target) - total_sum;
                if (remaining_outcome_target < 0) {
                    $(`#indimpact_dissvalue${rowno}`).val("");
                    alert("Total sum of indirect beneficiary disaggregation target values cannot exceed above defined target")
                }
            } else {
                $(`#indimpact_dissvalue${rowno}`).val("");
                alert("Indirect beneficiary disaggregation target value cannot be equal or less than 0");
            }
        }
    } else {
        $(`#indimpact_dissvalue${rowno}`).val("");
        alert("Define impact indirect beneficiary total target above first");
    }
}


// ===================================== Add multiple outcome evaluation risks =============================================

function add_row_outcome() {
    $outcomerowno = $("#outcome_table_body tr").length;
    $outcomerowno = $outcomerowno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $outcomerowno + "" + randno;
    $("#outcome_table_body tr:last").after(
        '<tr id="outcomerow' +
        $row +
        '">' +
        "<td>" +
        $row +
        "</td>" +
        "<td>" +
        '<select  data-id="' +
        $row +
        '" name="outcomerisk[]" id="outcomeriskrow' +
        $row +
        '" class="form-control  selected_outcome" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="text" name="outcome_assumptions[]"  id="outcome_assumptionsrow' +
        $row +
        '"   placeholder="Enter"  class="form-control"  required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_outcome("outcomerow' +
        $row +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_outcome();
    get_outcome_risks($row);
}

// function to delete row
function delete_row_outcome(rowno) {
    $("#" + rowno).remove();
    numbering_outcome();
}

// auto numbering table rows on delete and add new for financier table
function numbering_outcome() {
    $("#outcome_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

// =============================================== Add multiple impact evaluation questions =================================================

function add_row_impact_question() {
    $questionrowno = $("#impact_questions_table_body tr").length;
    $questionrowno = $questionrowno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $questionrowno + "" + randno;
    $("#impact_questions_table_body tr:last").after(
        '<tr id="impactquestionrow' +
        $row +
        '">' +
        "<td>" +
        $row +
        "</td>" +
        "<td>" +
        '<input type="text" name="impactquestions[]" id="impactquestions' +
        $row +
        '" placeholder="Enter any other impact evaluation question" class="form-control impactquerry" required/>' +
        "</td>" +
        "<td>" +
        '<select data-id="0" name="impactanswertype[]" id="impactanswertype' +
        $row +
        '" class="form-control" required="required">' +
        '<option value="">... Select ...</option>' +
        '<option value="1">Number</option>' +
        '<option value="2">Mutiple Choices</option>' +
        '<option value="3">Checkboxes</option>' +
        '<option value="4">Dropdown</option>' +
        '<option value="6">Text</option>' +
        '<option value="6">File Upload</option>' +
        '</select>' +
        "</td>" +
        "<td>" +
        '<input type="text" name="impact_other_answer_label[]" id="impact_other_answer_label' +
        $row +
        '" placeholder="Enter comma seperated labels" class="form-control impactquerry" required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_impact_question("impactquestionrow' +
        $row +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_impact_questions();
}

// function to delete row
function delete_row_impact_question(rowno) {
    $("#" + rowno).remove();
    numbering_impact_questions();
}

// auto numbering table rows on delete and add new for financier table
function numbering_impact_questions() {
    $("#impact_questions_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}


// ===================================== Add multiple outcome evaluation questions =============================================

function add_row_question() {
    $questionrowno = $("#questions_table_body tr").length;
    $questionrowno = $questionrowno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $questionrowno + "" + randno;
    $("#questions_table_body tr:last").after(
        '<tr id="questionrow' +
        $row +
        '">' +
        "<td>" +
        $row +
        "</td>" +
        "<td>" +
        '<input type="text" name="outcomeotherquestions[]" id="questions' +
        $row +
        '" placeholder="Enter any other outcome evaluation question" class="form-control querry" required/>' +
        "</td>" +
        "<td>" +
        '<select data-id="0" name="outcomeotheranswertype[]" id="answertype' +
        $row +
        '" class="form-control" required="required">' +
        '<option value="">... Select ...</option>' +
        '<option value="1">Number</option>' +
        '<option value="2">Mutiple Choices</option>' +
        '<option value="3">Checkboxes</option>' +
        '<option value="4">Dropdown</option>' +
        '<option value="6">Text</option>' +
        '<option value="6">File Upload</option>' +
        '</select>' +
        "</td>" +
        "<td>" +
        '<input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label' +
        $row +
        '" placeholder="Enter comma seperated labels" class="form-control impactquerry" required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_question("questionrow' +
        $row +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_questions();
}

// function to delete row
function delete_row_question(rowno) {
    $("#" + rowno).remove();
    numbering_questions();
}

// auto numbering table rows on delete and add new for financier table
function numbering_questions() {
    $("#questions_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

// check if the input has already been selected
$(document).on("change", ".selected_outcome", function (e) {
    var tralse = true;
    var select_outcome_arr = [];
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".selected_outcome").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(select_outcome_arr.indexOf(getVal)) != -1) {
            tralse = false;
            alert("You cannot select Risk " + selectedText + " more than once ");
            var rw = $(v).attr("data-id");
            var outcome_assumptions = "#outcome_assumptionsrow" + rw;
            $(v).val("");
            $(outcome_assumptions).val("");
            return false;
        } else {
            select_outcome_arr.push($(v).val());
        }
    });
    if (!tralse) {
        return false;
    }
});

function get_impact_risks(rowno) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: "get_risks=1",
        dataType: "html",
        success: function (response) {
            $("#impactriskrow" + rowno).html(response);
            $("#impact_assumptionsrow" + rowno).val("");
        }
    });
}

function get_outcome_risks(rowno) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: "get_risks=2",
        dataType: "html",
        success: function (response) {
            $("#outcomeriskrow" + rowno).html(response);
            $("#outcome_assumptionsrow" + rowno).val("");
        }
    });
}

function outcome_target_change() {
    let total_target = $("#houtcomeTarget").val();
    let total_sum = 0;
    $(".sub_total_octarget").each(function () {
        if ($(this).val() != "") {
            total_sum = total_sum + parseInt($(this).val());
        }
    });
    if (total_sum > 0) {
        var confirm_handler = confirm("Would you like to amend outcome indicator target");
        if (confirm_handler) {
            $(".sub_total_octarget").each(function () {
                $(this).val("");
            });
        } else {
            $("#outcomeTarget").val(total_target);
        }
    }
}


function outcome_sum_target(rowno) {
    let total_target = $("#outcomeTarget").val();
    if (total_target != "") {
        $("#houtcomeTarget").val(total_target);
        var outcome_dissvalue = parseInt($(`#outcome_dissvalue${rowno}`).val());
        if ($(`#outcome_dissvalue${rowno}`).val() != "") {
            if (outcome_dissvalue > 0) {
                let total_sum = 0;
                $(".sub_total_octarget").each(function () {
                    if ($(this).val() != "") {
                        total_sum = total_sum + parseInt($(this).val());
                    }
                });
                let remaining_outcome_target = parseInt(total_target) - total_sum;
                if (remaining_outcome_target < 0) {
                    $(`#outcome_dissvalue${rowno}`).val("");
                    alert("Total sum of direct beneficiary disaggregation values cannot exceed above defined target")
                }
            } else {
                $(`#outcome_dissvalue${rowno}`).val("");
                alert("Direct beneficiary disaggregation target value cannot be equal or less than 0");
            }
        }
    } else {
        $(`#outcome_dissvalue${rowno}`).val("");
        alert("Define outcome direct beneficiary total target above first");
    }
}

function outcome_indsum_target(rowno) {
    let total_target = $("#indoutcomeTarget").val();

    if (total_target != "") {
        $("#hindoutcomeTarget").val(total_target);
        var outcome_dissvalue = parseInt($(`#indoutcome_dissvalue${rowno}`).val());
        if ($(`#indoutcome_dissvalue${rowno}`).val() != "") {
            if (outcome_dissvalue > 0) {
                let total_sum = 0;
                $(".sub_total_ocindtarget").each(function () {
                    if ($(this).val() != "") {
                        total_sum = total_sum + parseInt($(this).val());
                    }
                });
                let remaining_outcome_target = parseInt(total_target) - total_sum;
                if (remaining_outcome_target < 0) {
                    $(`#indoutcome_dissvalue${rowno}`).val("");
                    alert("Total sum of indirect beneficiary disaggregation target values cannot exceed above defined target")
                }
            } else {
                $(`#indoutcome_dissvalue${rowno}`).val("");
                alert("Indirect beneficiary disaggregation target value cannot be equal or less than 0");
            }
        }
    } else {
        $(`#indoutcome_dissvalue${rowno}`).val("");
        alert("Define outcome indirect beneficiary total target above first");
    }
}


function add_row_output() {
    $outputrowno = $("#output_risk_table_body tr").length;
    $outputrowno = $outputrowno + 1;
    let randno = Math.floor((Math.random() * 1000) + 1);
    let $row = $outputrowno + "" + randno;

    $("#output_risk_table_body tr:last").after(
        '<tr id="outputrow' +
        $row +
        '">' +
        "<td>" +
        $row +
        "</td>" +
        "<td>" +
        '<select  data-id="' +
        $row +
        '" name="outputrisk[]" id="outputriskrow' +
        $row +
        '" class="form-control  selected_output" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="text" name="output_assumptions[]"  id="output_assumptionsrow' +
        $row +
        '"   placeholder="Enter"  class="form-control"  required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("outputrow' +
        $row +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_output();
    get_output_risks($row);
}

// function to delete row
function delete_row_output(rowno) {
    $("#" + rowno).remove();
    numbering_output();
}

// auto numbering table rows on delete and add new for financier table
function numbering_output() {
    $("#output_risk_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

// check if the input has already been selected
$(document).on("change", ".selected_output", function (e) {
    var tralse = true;
    var select_output_arr = [];
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".selected_output").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(select_output_arr.indexOf(getVal)) != -1) {
            tralse = false;
            alert("You cannot select Risk " + selectedText + " more than once ");
            var rw = $(v).attr("data-id");
            var output_assumptions = "#output_assumptionsrow" + rw;
            $(v).val("");
            $(output_assumptions).val("");
            return false;
        } else {
            select_output_arr.push($(v).val());
        }
    });
    if (!tralse) {
        return false;
    }
});

function get_output_risks(rowno) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: "get_risks=3",
        dataType: "html",
        success: function (response) {
            $("#outputriskrow" + rowno).html(response);
            $("#output_assumptionsrow" + rowno).val("");
        }
    });
}

function get_output_ind_details() {
    var indicator = $("#outputIndicator").val();
    if (indicator) {
        $.ajax({
            type: "post",
            url: ajax_processor,
            data: "impact_indicator=" + indicator,
            dataType: "json",
            success: function (response) {
                $("#output_calc_method").val(response.impact_calc_method);
                $("#output_unitofmeasure").val(response.unitofmeasure);
            }
        });
    }
}

function get_output_risks(rowno) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: "get_risks=3",
        dataType: "html",
        success: function (response) {
            $("#outputriskrow" + rowno).html(response);
            $("#output_assumptionsrow" + rowno).val("");
        }
    });
}

function getopdetails(opid) {
    var outputid = $("#outputid" + opid).val();
    var indicatorid = $("#indicatorid" + opid).val();
    var outputName = $("#outputName" + opid).val();
    var indicatorName = $("#indicatorName" + opid).val();
    var output_details_unitof_measure = $("#output_details_unitof_measure" + opid).val();
    var output_calc_method = $("#output_calculation_method" + opid).val();
    var output_details_id = $("#output_details_id" + opid).val();
    var projid = $("#projidid").val();
    var ben_diss = $("#ben_diss" + opid).val();


    $("#output_risk_table_body").html(
        '<tr id="row0">' +
        '<td> 1 </td>' +
        '<td>' +
        '<select data-id="0" name="outputrisk[]" id="outputriskrow0" class="form-control  selected_output" required="required">' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input type="text" name="output_assumptions[]" id="output_assumptions0" placeholder="Enter" class="form-control"  required />' +
        '</td>' +
        '<td>' +
        '</td>' +
        '</tr>');
    get_output_risks(0);

    $(".modal").each(function () {
        $(this).modal("hide");
        $(this).find("form").trigger("reset");
        $("#outputform .selectpicker").selectpicker("deselectAll");
    });

    $("#outputsName").val(outputName);
    $("#outputIndicator").val(indicatorName);
    $("#output_calc_method").val(output_calc_method);
    $("#outputunitofmeasure").val(output_details_unitof_measure);
    $("#opid").val(outputid);
    $("#output_indicator").val(indicatorid);

    if (output_details_id == "") {
        console.log("Its empty!!");
        $("#addoutput").val("addoutput");
        $("#addoutput").attr("name", "addoutput");
        $("#modal-title").html('<i class="fa fa-edit"></i> Add Output Details');
        get_op_diss(opid);
        get_frequency(opid);
    }

    $.ajax({
        type: "post",
        url: ajax_processor,
        data: {
            get_output_details: "get_output_details",
            projid: projid,
            opid: outputid
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#outputMonitorigFreq").val(response.monitoringfreq);
            $("#responsible_m").val(response.responsible);
            if (response.detailscount > 0) {
                $("#output_risk_table_body").html(response.risks);
                $("#addoutput").val("editoutput");
                $("#addoutput").attr("name", "editoutput");
                $("#modal-title").html('<i class="fa fa-edit"></i> Edit Output Details');
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}



function get_frequency(opid) {
    $.ajax({
        type: "post",
        url: ajax_processor,
        data: {
            get_frequency: opid,
        },
        dataType: "html",
        success: function (response) {
            $("#outputMonitorigFreq").html(response);
        }
    });
}


function impact_form_fields() {
    if ($("input[name='impact_dissvalue[]']").length > 0) {
        let total_sum = 0;
        let total_target = parseInt($("#impactTarget").val());

        $(".sub_total_imptarget").each(function () {
            if ($(this).val() != "") {
                total_sum = total_sum + parseInt($(this).val());
            }
        });

        let remaining_impact_target = parseInt(total_target) - total_sum;
        if (remaining_impact_target != 0) {
            alert("Total sum of impact direct beneficiary disaggregation values should be equal to defined outcome direct beneficiary total target");
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function impact_indform_fields() {
    if ($("input[name='indimpact_dissvalue[]']").length > 0) {
        let total_sum = 0;
        let total_target = parseInt($("#indimpactTarget").val());

        $(".sub_indtotal_imptarget").each(function () {
            if ($(this).val() != "") {
                total_sum = total_sum + parseInt($(this).val());
            }
        });

        let remaining_impact_target = parseInt(total_target) - total_sum;
        if (remaining_impact_target != 0) {
            alert("Total sum of impact indirect beneficiary disaggregation values should be equal to defined impact indirect beneficiary total target");
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function outcome_form_fields() {
    if ($("input[name='outcome_dissvalue[]']").length > 0) {
        let total_target = parseInt($("#outcomeTarget").val());
        let total_sum = 0;
        $(".sub_total_octarget").each(function () {
            if ($(this).val() != "") {
                total_sum = total_sum + parseInt($(this).val());
            }
        });
        let remaining_outcome_target = parseInt(total_target) - total_sum;
        if (remaining_outcome_target != 0) {
            alert("Total sum of outcome direct beneficiary disaggregation values should be equal to defined outcome direct beneficiary total target");
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function outcome_indform_fields() {
    if ($("input[name='indoutcome_dissvalue[]']").length > 0) {
        let total_target = parseInt($("#indoutcomeTarget").val());
        let total_sum = 0;

        $(".sub_total_ocindtarget").each(function () {
            if ($(this).val() != "") {
                total_sum = total_sum + parseInt($(this).val());
            }
        });

        let remaining_outcome_target = parseInt(total_target) - total_sum;
        if (remaining_outcome_target != 0) {
            alert("Total sum of outcome indirect beneficiary disaggregation values should be equal to defined outcome indirect beneficiary total target");
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function output_val() {
    let data = [];
    let number = [];
    $("input[name='output_details_id[]']").each(function () {
        if ($(this).val() != "") {
            data.push(true);
        } else {
            let attr = $(this).attr("data-id");
            number.push(attr);
            data.push(false);
        }
    });

    if (data.includes(false)) {
        alert("Insert output Data for: " + number);
        return false;
    } else {
        return true;
    }
}

function mne_budget_distribution() {
    var mne_budget = $("#mne_budget").val();
    var msg = false;
    if (mne_budget != "") {
        var budget_contribution = 0;
        $(".sub_totals").each(function () {
            if ($(this).val() != "") {
                budget_contribution += parseFloat($(this).val());
            }
        });
        mne_budget = parseFloat(mne_budget);
        if (mne_budget == budget_contribution) {
            msg = true;
        } else {
            msg = false;
            alert("Ensure you have distributed the m&e funds ");
        }
    }
    return msg;
}


function formVal() {
    var msg = false;
    let outcome_handler = outcome_form_fields();
    if (outcome_handler) {
        let output_handler = output_val();
        if (output_handler) {
            outcome_indf_handler = outcome_indform_fields();
            if (outcome_indf_handler) {
                var mne_budget = mne_budget_distribution();
                msg = (mne_budget) ? true : false;
            }
        }
    }
    return msg;
}

function get_op_diss(opid) {
    var projid = $("#projid").val();
    if (opid) {
        $.ajax({
            type: "post",
            url: ajax_processor,
            data: { opid: opid, projid: projid, get_locations: "get_locations" },
            dataType: "html",
            success: function (response) {
                $("#op_table_body").html(response);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}


function question_data_type() {
    var source = $("#impactdataSource").val();
    if (source == 1) {
        const input = document.getElementById('impactmainquestion');
        input.setAttribute('required', '');
        $(".impactquestions").show();
    } else {
        $(".impactquestions").hide();
        $('.impactquerry').removeAttr('required');
        $('#impactmainquestion').removeAttr('required');
    }
}