const url = "/ajax/programs/programs";
$(document).ready(function () {
    program_workplan_hide();
    
    //filter the output cannot be selected twice
    $(document).on('change', '.selectOutput', function (e) {
        var tralse = true;
        var selectOutput_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        $('.selectOutput').each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectOutput_arr.indexOf(getVal)) != -1) {
                tralse = false;
                swal("Warning", 'You cannot select Output ' + selectedText + ' more than once', "warning");
                $(v).val("");
                return false;
            } else {
                selectOutput_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
    });

    //filter the output cannot be selected twice
    $(document).on('change', '.selectOutput', function (e) {
        var tralse = true;
        var selectOutput_arr = [];
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        $('.selectOutput').each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectOutput_arr.indexOf(getVal)) != -1) {
                tralse = false;
                swal("Warning", 'You cannot select Output ' + selectedText + ' more than once', "warning");
                $(v).val("");
                return false;
            } else {
                selectOutput_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
    });

});

function get_department(){
    var projsectorid = $("#projsector").val();
    if (projsectorid !="") {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'getdept=' + projsectorid,
            success: function (html) {
                $('#projdept').html(html);
                $(".indicator").html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
            }
        });
    } else {
        $('#projdept').html('<option value="">... Select <?= $ministrylabel ?> First ... </option>');
        $(".indicator").html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
    }
}

function program_workplan_hide(){
    $("#phead").html("");
    $("#programhide").hide();
    $("#program_workplan_body").hide(); 
}

$rowno = $("#program_workplan_body tr").length;
function program_workplan(){
    var program_duration = $("#program_duration").val();
    var program_starting_year = $("#starting_year").val();
    var strategic_plan_end_year = $("#stratplanendyear").val();
    program_workplan_hide();
    if(program_starting_year != "" && program_duration !="" && strategic_plan_end_year !=""){ 
        program_starting_year = parseInt(program_starting_year); 
        program_duration = parseInt(program_duration);
        var program_end_year =  program_starting_year + program_duration - 1;

        if(program_duration > 0 ){ 
            if(program_end_year <= strategic_plan_end_year){
                $("#programhide").show();
                var containerH =`<tr>
                    <th rowspan="2">OutputOutput&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`; 
                var content =`<tr>`;

                for (var i = 0; i < program_duration; i++) {
                    var finyear = program_starting_year + 1; 
                    containerH += `<th colspan="2">${program_starting_year}/${finyear}</th><input type="hidden" name="progyear[]" value="${program_starting_year}" />`;
                    content += `<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Budget (ksh)) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>`;
                    program_starting_year++;
                }

                containerH +=
                    `<th rowspan="2">
                        <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan();" class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </th> </tr>`;

                content += `<tr>`;
                containerH += content;
                $("#phead").append(containerH);
            }else{
                program_workplan_hide(); 
                $("#program_duration").val("");
            }
        }else{
            $("#program_duration").val("");
            program_workplan_hide();
        }
    }else{
       program_workplan_hide();
    }
}

$rowno = $("#program_workplan_body tr").length;
function add_program_workplan() {
    var years = $("#program_duration").val();
    var projdept = $("#projdept").val();
    // var start_year = $("#starting_year").val();
    if (projdept != "") {
        var containerB =
        `<tr class="" id="row${$rowno}">
            <td> 
                <input  type="text" name="output[]" id="output${$rowno}"  class="form-control" required> 
            </td>
            <td>
                <select name="indicator[]" id="indicatorrow${$rowno}" onchange="indicatorChange('row${$rowno}')" class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">... Select Division first ...</option>
                </select>
            </td> `;

        for (var i = 0; i < years; i++) {
            containerB +=
            `<td>
                <input name="targetrow${$rowno}'[]" id="targetrow${$rowno}" class="form-control targetrow${$rowno}" onchange="calculate_target('row${$rowno}')" onkeyup="calculate_target('row${$rowno}')" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
                <span class="spantargetplanrow${$rowno}">2000</span>
                <input name="targetplanrow${$rowno}" id="targetplanrow${$rowno}" type="hidden" value="2000"/>
                <input name="target_yearrow${$rowno}" id="target_yearrow${$rowno}" type="hidden" value=""/>
            </td>

            <td>
                <input name="budgetrow${$rowno}[]" id="budgetrow${$rowno}" class="form-control currency budgetrow${$rowno}" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>
            </td>`;
        }
        containerB +=`
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`;
        var sourceid = 'source' + $rowno;
        $rowno = $rowno + 1;
        $("#program tr:last").after(containerB);
        get_indicator($rowno);
    } else { 
        swal("Warning", "Select Division First!", "warning");
    } 
}

function delete_program_row(rowno) {
    $("#" + rowno).remove();
}

function get_indicator(rowno){
    var projdeptid = $("#projdept").val();
    var indicator = "#indicatorrow" + (rowno - 1);
    if (projdeptid) {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'getprogindicator=' + projdeptid,
            success: function(html) {
                $(indicator).html(html);
            }
        });
    } else {
        $(indicator).html('<option value="">... Select  Division First ... </option>');
    }
}

function indicatorChange(rowno) {
    var rw = rowno;
    var output = 'output' + rw;
    var indicator = 'indicator' + rw;
    var target = '.target' + rw;
    var budget = '.budget' + rw;
    var indicatorVal = $("#" + indicator).val();

    if (indicatorVal != "") {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'getUnits=' + indicatorVal,
            dataType: "json",
            success: function(html) {
                console.log(html.unit);
                $(target).attr('placeholder', html.unit);
                var targets = 'target' + indicatorVal + '[]';
                var budgets = 'budget' + indicatorVal + '[]';
                $(target).attr('name', targets);
                $(budget).attr('name', budgets);
            }
        });
    }
}

function calculate_target(rowno){ 
    var target = $("#targetplan"+rowno).val();
        target = target != "" ? parseInt(target) : "";
    var total = 0;
    $(`.target${rowno}`).each(function (k, v) { 
        var getVal = $(v).val();
        if(getVal !=""){
            total += parseInt(getVal);
        }
    });

    if(total > target){
        $(`#target${rowno}`).val("");
        swal("Warning", 'Sorry you cannot exceed the planned target', "warning");
        var total = 0;
        $(`.target${rowno}`).each(function (k, v) { 
            var getVal = $(v).val();
            if(getVal !=""){
                total += parseInt(getVal);
            }
        });
        var value = target - total;
        $(".spantargetplan"+rowno).html(value);
    }else{
        $(".spantargetplan"+rowno).html(target - total);
    }
}


$rowno = $("#financier_table_body tr").length;
function add_row_financier() {
    $("#removeTr").remove(); //new change
    $rowno = $rowno + 1;
    $("#financier_table_body tr:last").after(`
        <tr id="financerow${$rowno}">
            <td> ${$rowno} </td>
            <td>
                <select  data-id="${$rowno}" name="source_category[]" id="source_categoryrow${$rowno}" class="form-control validoutcome selected_category" required="required">
                </select>
            </td>
            <td>
                <input type="number" name="amountfunding[]"   id="amountfundingrow${$rowno}" placeholder="Enter amount in local currency"  class="form-control financierTotal" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financerow${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
    numbering();
    get_finacier($rowno)
}

// function to delete row 
function delete_row_financier(rowno) {
    $("#" + rowno).remove();
    numbering();
}

// auto numbering table rows on delete and add new for financier table
function numbering() {
    $("#financier_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

function get_finacier($rowno) {
    $.ajax({
        type: "post",
        url: url,
        data: "get_financier",
        dataType: "html",
        success: function (response) {
            $("#source_categoryrow" + $rowno).html(response);
        }
    });
}