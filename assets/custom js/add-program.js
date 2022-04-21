
function add_row_financier() {
    $("#removeTr").remove(); //new change
    $rowno = $("#financier_table_body tr").length;
    $rowno = $rowno + 1;
    $("#financier_table_body tr:last").after(
        '<tr id="financerow' +
        $rowno +
        '">' +
        "<td>" +
        $rowno +
        "</td>" +
        "<td>" +
        '<select  data-id="' +
        $rowno +
        '" name="source_category[]" id="source_categoryrow' +
        $rowno +
        '" class="form-control validoutcome selected_category" required="required">' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="number" name="amountfunding[]"   id="amountfundingrow' +
        $rowno +
        '"   placeholder="Enter amount in local currency"  class="form-control financierTotal" required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financerow' +
        $rowno +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering();
    get_finacier($rowno)
}

// function to delete row 
function delete_row_financier(rowno) {
    $("#" + rowno).remove();
    numbering();
    $number = $("#financier_table_body tr").length;
    if ($number == 1) {
        $("#financier_table_body tr:last").after(
            '<tr id="removeTr"><td colspan="4">Attach file </td></tr>'
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

function get_finacier($rowno) {
    $.ajax({
        type: "post",
        url: "assets/processor/add-program-process",
        data: "get_financier",
        dataType: "html",
        success: function (response) {
            $("#source_categoryrow" + $rowno).html(response);
        }
    });
}


// check if the input has already been selected 
$(document).on("change", ".selected_category", function (e) {
    var tralse = true;
    var select_funding_arr = [];
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".selected_category").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
            tralse = false;
            alert("You canot select Category " + selectedText + " more than once ");
            var rw = $(v).attr("data-id");
            var amountfundingrow = "#amountfundingrow" + rw;
            $(v).val("");
            $(amountfundingrow).val("");
            return false;
        } else {
            select_funding_arr.push($(v).val());
        }
    });
    if (!tralse) {
        return false;
    }
});

$('input:radio[name="progstrategyobjective"]').change(function () {
    if ($(this).val() == '1') {
        var get_inputs = "get_independent";
        $("#strat_div").hide();
        $("#strategic_objective").removeAttr("required");
        $("#strategic_objective").val("");
        $.ajax({
            type: "POST",
            url: "assets/processor/add-program-process",
            data: get_inputs,
            dataType: "html",
            success: function (response) {
                $("#form_slip").html(response);
                $("#programworkplan").hide();
            }
        });
    } else if ($(this).val() == '0') {
        $("#strat_div").show();
        $("#strategic_objective").attr("required", "required");
        $("#form_slip").html("");
    }
});


$("#strategic_objective").change(function (e) {
    e.preventDefault();
    var kraid = $("#strategic_objective").val();
    if (kraid) {
        $.ajax({
            type: "POST",
            url: "assets/processor/add-program-process",
            data: "get_dependent=" + kraid,
            dataType: "html",
            success: function (response) {
                $("#form_slip").html(response);
                $("#programworkplan").hide();
            }
        });
    }
});


$(document).ready(function () {
    // get department
    $('#projsector').on('change', function () {
        var projsectorid = $(this).val();
        if (projsectorid) {
            $.ajax({
                type: 'POST',
                url: 'assets/processor/add-program-process',
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
    });

    //get output
    $('#projdept').on('change', function () {
        var projdeptid = $("#projdept").val();
        if (projdeptid) {
            $.ajax({
                type: 'POST',
                url: 'assets/processor/add-program-process',
                data: 'getprogindicator=' + projdeptid,
                success: function (html) {
                    $(".indicator").html(html);
                }
            });
        } else {
            $(".indicator").html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
        }
    });
});