// outcome 
// direct  
function outcome_direct_dissagragation_change() {
    var inddirectBenfType = $(`#inddirectBenfType`).val();
    
    if (inddirectBenfType != "" && inddirectBenfType == "1") {
        $("#outcome_direct_disagregation").show();
        var $rowno = 0;

        $("#direct_outcome_table_body").html(`
            <tr></tr>
            <tr id="direct${$rowno}">
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
            </tr>
        `);
        number_direct_outcome();
        get_disaggregations($rowno, type = 2);
    } else {
        outcome_direct_disaggregation_hide(); 
    }
}

function outcome_direct_disaggregation_hide() {
    $("#direct_outcome_table_body").html(`
            <tr></tr>
            <tr id="removedirectTr">
                <td colspan="5" class="text-center">Add Disaggregations</td>
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
            '<tr id="removedirectTr"><td colspan="5" class="text-center"> Add Disaggregations</td></tr>'
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

function indcalculation_change() {
    var calculation_method = $("#indcalculation").val();
    var category = "Outcome";
    var outcome_type = 1;
    outcome_category();
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
                $("#direct_outcome_formula").html(response);                                 
            }
        });
    }
}

function outcome_category() {
    $("#outcome_details").show();
    $("#outcome_details .form-control").each(function () {
        $(this).attr("required", "required");
        $(this).val("");
    });
    $(".insp").each(function () {
        $(this).attr("required", "required");
    });
    $("#inddirect_outcome_formula").html(""); 
}

function output_hide() {
    // hide output details and remove reqired and empty fields 
    $("#output_details").hide();
    $("#output_details .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
}