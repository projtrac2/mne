const ajax_url = "ajax/procurement/index";
$(document).ready(function () {
    hide_show();
    $(document).on("change", ".output_location_select", function (e) {
        var tralse = true;
        var selectOutcome_arr = []; // for contestant name
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        $(".output_location_select").each(function (k, v) {
            var getVal = $(v).val();
            var valid = findCommonElements3(selectOutcome_arr, getVal);
            if (valid) {
                var value = $(v).val("");
                error_alert(
                    "You canot select location " + selectedText + " more than once"
                );
                return false;
            } else {
                selectOutcome_arr.push(...getVal);
            }
        });
        $(".selectpicker").selectpicker("refresh");

        if (!tralse) {
            return false;
        }
    });


    $("#projcontractor").on("change", function () {
        var contrID = $(this).val();
        if (contrID != "") {
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: { getcont: contrID },
                success: function (html) {
                    $("#contrinfo").html(html);
                },
            });
        } else {
            $("#contrinfo").html(
                '<div class="col-md-12">Select Contractor First</div>'
            );
        }
    });
});

function add_row() {
    $rowno = $("#files_table tr").length;
    $rowno = $rowno + 1;
    $("#files_table tr:last").after(
        '<tr id="row' +
        $rowno +
        '"><td><input type="file" name="tenderfile[]" id="tenderfile[]" multiple class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' +
        $rowno +
        '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>'
    );
}

function delete_row(rowno) {
    $("#" + rowno).remove();
}

function add_guarantee_row() {
    $rowno = $("#guarantees_table tr").length;
    $rowno = $rowno + 1;
    $("#guarantees_table tr:last").after(
        '<tr id="row' +
        $rowno +
        '"><td><input type="text" name="guarantee[]" id="guarantee[]" class="form-control" placeholder="Describe the guarantee" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></td><td><input type="date" name="guarantee_start_date[]" id="guarantee_start_date[]" class="form-control" placeholder="Enter the guarantee start date" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="number" name="guarantee_duration[]" id="guarantee_duration[]" class="form-control" placeholder="Enter the duration" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="number" name="guarantee_notification[]" id="guarantee_notification[]" class="form-control" placeholder="Enter notification days" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' +
        $rowno +
        '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>'
    );
}

function delete_guarantee_row(rowno) {
    $("#" + rowno).remove();
}

function findCommonElements3(arr1, arr2) {
    return arr1.some((item) => arr2.includes(item));
}

function add_milestone() {
    $("#removeTR").remove(); //new change
    $rowno = $("#milestone_payment tr").length;
    $rowno = $rowno + 1;
    $("#milestone_payment tr:last").after(`
    <tr id="row${$rowno}">
        <td></td>
        <td>
            <input type="text" name="payment_phase[]" id="payment_phaserow${$rowno}" class="form-control percent"  placeholder="Enter the payment phase" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
        </td>
        <td>
            <select name="milestone_name${$rowno}[]" multiple id="milestone_namerow${$rowno}" data-id="${$rowno}" class="form-control require output_location_select show-tick selectpicker"  data-live-search="true" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
            </select>
        </td>
        <td>
            <input type="number" name="percentage[]" id="percentagerow${$rowno}" onchange="calculate_percentage(${$rowno})" onkeyup="calculate_percentage(${$rowno})" class="form-control percent"  placeholder="Enter the percentage" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
            <input type="hidden" name="row[]" value="${$rowno}"/>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_milestones("row${$rowno}")>
            <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    $(".selectpicker").selectpicker("refresh");
    number_table_items();
    get_milestones($rowno);
}

function calculate_percentage(rowno) {
    var milestone = $(`#percentagerow${rowno}`).val();
    if (milestone != "") {
        var percentage = 0;
        $(".percent").each(function (k, v) {
            percentage += $(v).val() != "" ? parseFloat($(v).val()) : 0;
        });
        if (percentage > 100) {
            error_alert("Sorry you can't have more than 100%");
            $(`#percentagerow${rowno}`).val("");
        }
    } else {
        error_alert("Sorry! Ensure you have to enter a value ");
    }
}

function number_table_items() {
    $("#milestone_payment tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function delete_milestones(rowno, table) {
    $("#" + rowno).remove();
    if ($("#milestone_payment tr").length == 1) {
        $("#milestone_payment").html(
            '<tr></tr><tr id="removeTR"><td  colspan="5">Add Milestones</td></tr>'
        );
    }
    number_table_items();
}

function get_milestones(rowno) {
    var projid = $("#m_projid").val();
    $.ajax({
        type: "get",
        url: "ajax/procurement/index",
        data: {
            get_milestones: "get_milestones",
            projid: projid,
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                var options = `<options value="">Select Milestones for this milestone</options>`;
                $.each(response.milestones, function (val, milestone) {
                    options += `<option value="${milestone.id}">${milestone.milestone}</option>`;
                });
                $(`#milestone_namerow${rowno}`).html(options);
                $(".selectpicker").selectpicker("refresh");
            } else {
                console.log("No milestones found ");
            }
        },
    });
}

function hide_show() {
    var payment_method = $("#payment_method").val();
    var edit_payment_plan = $("#edit_payment_plan").val();
    if (payment_method == "" || payment_method == "2" || payment_method == "3") {
        $("#milestone").hide();
        $("#milestone_payment").html(`<tr></tr><tr id="removeTR"><td colspan="5">Add Milestones</td></tr>`);
    } else if (payment_method == "1") {
        $("#milestone").show();
        if (edit_payment_plan == 1) {
            add_milestone();
        }
    }
    $(".selectpicker").selectpicker("refresh");
}


function validate_payment_form() {
    var payment_method = $("#payment_method").val();
    var result = true;
    if (payment_method == "1") {
        var total_milestones = parseInt($("#total_milestones").val());
        var selectOutcome_arr = []; // for contestant name
        $(".output_location_select").each(function (k, v) {
            var getVal = $(v).val();
            selectOutcome_arr.push(...getVal);
        });
        if (selectOutcome_arr.length > 0) {
            result = (selectOutcome_arr.length == total_milestones) ? true : false;
        }
    }
    return result;
}
