$(document).ready(function () {
    impact_disaggregation_hide(); 
});


// Impact 
function impact_dissagragation_change() {
    var measurementdiss = $(`#measurementdiss`).val();
    if (measurementdiss != "" && measurementdiss == "1") {
        $("#impact_disagregation").show();
        var $rowno = 0;
        $("#impact_table_body").html(`
            <tr id="${$rowno}">
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
                </td>
            </tr>`);
            
        number_impact_table();
        get_disaggregations($rowno, type = 1);
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

 

function indcalculation_change() {
    var calculation_method = $("#indcalculation").val();
    var category = "Impact";
    var outcome_type = 3; 
    impact_category();
    if (calculation_method != null && category != null) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                get_method: "get_method",
                method: calculation_method,
                outcome_type: outcome_type
            },
            dataType: "html",
            success: function (response) { 
                $("#impact_formula").html(response); 
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