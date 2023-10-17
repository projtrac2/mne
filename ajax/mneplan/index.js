var manageImpactTable;
var manageOutcomeTable;
// var ajax_processor= "ajax/mneplan/add-monitoring-evaluation-plan-processor";

$(document).ready(function () {
    var myprojid = $("#myprojid").val();

    // manage Project Impact data table
    var url1 = "ajax/mneplan/index?myprojid=" + myprojid + "&evaluation=1";
    var url2 = "ajax/mneplan/index?myprojid=" + myprojid + "&evaluation=2";

    manageImpactTable = $("#manageImpactTable").DataTable({
        ajax: url1,
        order: [],
        'columnDefs': [{
            'targets': [5],
            'orderable': false,
        }]
    });

    // manage Project Outcome data table
    manageOutcomeTable = $("#manageOutcomeTable").DataTable({
        ajax: url2,
        order: [],
        'columnDefs': [{
            'targets': [5],
            'orderable': false,
        }]
    });

    $("#addimpactform").on("submit", function (event) {
        event.preventDefault();
        var form_data = $(this).serialize();
        var form = $(this);
        var formData = new FormData(this);
        $.ajax({
            url: ajax_processor,
            type: form.attr("method"),
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#addimpactform")[0].reset();
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                    swal(response.msg);
                    setTimeout(3000);
                } // /if response.success
            } // /success function
        }); // /ajax function
        // /if validation is ok
        return false;
    }); // /submit Project Main Menu  form


    $("#addoutcomeform").on("submit", function (event) {
        event.preventDefault();
        var form_data = $(this).serialize();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: ajax_processor,
            type: form.attr("method"),
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#addoutcomeform")[0].reset();
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                    swal(response.msg);
                    setTimeout(3000);
                } // /if response.success
            } // /success function
        }); // /ajax function
        // /if validation is ok
        return false;
    }); // /submit Project Main Menu  form

    // add Project Main Menu  modal btn clicked
    $("#addImpactModalBtn")
        .unbind("click")
        .bind("click", function () {
            // // Project Main Menu  form reset
            $("#addimpactform")[0].reset();
        }); // /add Project Main Menu  modal btn clicked

    //filter the output cannot be selected twice
    $(document).on("change", ".selectedfinance", function (e) {
        var tralse = true;
        var selectImpact_arr = [];
        var attrb = $(this).attr("id");
        var rowno = $(this).attr("data-id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();
        var handler = true;

        $(".selectedfinance").each(function (k, v) {
            var getVal = $(v).val();
            if ($(v).val() != "") {
                if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
                    tralse = false;
                    sweet_alert("Error !!!", "You canot select Financier " + selectedText + " more than once ");
                    var rw = $(v).attr("data-id");
                    var amountfundingrow = "#amountfundingrow" + rw;
                    var ceilingvalrow = "#ceilingvalrow" + rw;
                    $(v).val("");
                    $(amountfundingrow).val("");
                    $(ceilingvalrow).val("");
                    return false;
                } else {
                    selectImpact_arr.push($(v).val());
                }
            } else {
                $("#amountfundingrow" + rowno).val("");
                $("#ceilingvalrow" + rowno).val("");
            }
        });

        if (!tralse) {
            return false;
        }
    });

    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var financier_contribution = validate_financiers();
        var budgetline_type = $("#plan_id").val();
        if (financier_contribution) {
            var form_data = $(this).serialize();
            $.ajax({
                type: "post",
                url: "ajax/mneplan/index",
                data: form_data,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        success_alert("Successfully created the record");
                        var plan_id = $("#plan_id").val();
                        var budget_line = "";

                        if (plan_id == "A") {
                            budget_line = "Monitoring";
                        } else if (plan_id == "B") {
                            budget_line = "Mapping";
                        } else if (plan_id == "C") {
                            budget_line = "Evaluation";
                        }

                        var sum_cost = 0;
                        $(`.subamount`).each(function () {
                            sum_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
                        });

                        var budget_line_details = `{
                            cost_type: 4,
                            output_id: 0,
                            budget_line_id: 0,
                            budget_line: '${budget_line}',
                            plan_id: '${plan_id}',
                            task_id: 0,
                            edit: 1,
                            sum_cost: ${sum_cost},
                        }`;

                        $(`#${plan_id}`).html(`<button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" name="addplus" title="Edit budgetlines" class="btn btn-success btn-sm" onclick="add_budgetline(${budget_line_details})"> <span class="glyphicon glyphicon-pencil"></span></button>`);
                        $(`#budget_lines_table${budgetline_type}`).html(response.body);
                        $(`#budget_line_foot${budgetline_type}`).html(response.footer);
                        $(".modal").each(function () {
                            $(this).modal("hide");
                            $(this)
                                .find("form")
                                .trigger("reset");
                        });


                    } else {
                        success_alert("Error !!", "Error creating the record");
                    }
                }
            });
        } else {
            sweet_alert("Error !!", "Ensure you have entered financier details")
        }
    });


    $("#mne_plan_form").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: "ajax/mneplan/index",
            data: form_data,
            dataType: "json",
            success: function (response) {
                 if(response.success){
                    sweet_alert(response.message);
                    setTimeout(() => {
                        window.location.href = "view-mne-plan.php";
                    }, 3000);
                 }else{
                    error_alert(response.message);
                 }
            }
        });
    });
});

function editItem(evalform = null, itemId = null) {
    if (itemId) {
        /* var heading = document.getElementById('impactmodaltitle');
        heading.textContent = "Edit Impact Details"

        var input = document.getElementById('tag-form-submit');
        input.setAttribute('name', 'Update'); */


        var url = "ajax/mneplan/index";
        var title, addform, editform, modalclass, editformname;
        if (evalform == "impact") {
            title = "Edit Impact Details";
            addform = "addimpactform";
            editform = "editImpactForm";
            editformname = "editimpactform";
            modalclass = "impactmodaltitle";
        } else if (evalform == "outcome") {
            title = "Edit Outcome Details";
            addform = "addoutcomeform";
            editform = "editOutcomeForm";
            editformname = "editoutcomeform";
            modalclass = "outcomemodaltitle";
        }

        $.ajax({
            type: "post",
            url: url,
            data: { edititemId: itemId, evalform: evalform },
            dataType: "html",
            success: function (response) {
                // modal div
                $("#" + addform).html(response);
                $("." + modalclass).html(title);
                $('.selectpicker').selectpicker();

                if (evalform == "impact") {
                    var source = $("#impactSourceid").val();
                    if (source == 1) {
                        const input = document.getElementById('impactmainquestion');
                        input.setAttribute('required', '');
                        $(".impactquestions").show();
                    } else {
                        $(".impactquestions").hide();
                        $('.impactquerry').removeAttr('required');
                        $('#impactmainquestion').removeAttr('required');
                    }
                    var editdata = $("#editimpact").val();
                } else if (evalform == "outcome") {
                    var source = $("#outcomeSourceid").val();
                    if (source == 1) {
                        const input = document.getElementById('outcomemainquestion');
                        input.setAttribute('required', '');
                        $(".outcomequestions").show();
                    } else {
                        $(".outcomequestions").hide();
                        $('.outcomequerry').removeAttr('required');
                        $('#outcomemainquestion').removeAttr('required');
                    }
                    var editdata = $("#editoutcome").val();
                }

                const formname = document.getElementById(addform);
                formname.setAttribute('id', editform);
                formname.setAttribute('name', editformname);

                /* ====== LOAD NEW FILE OR RELOAD A FILE ======= *
                var s = document.createElement('script');
                s.src = 'assets/js/mneplan/new-page-js.js';
                document.body.appendChild(s); */

                /* ====== LOAD A FUNCTION ======= *
                $('#impactdataSource').add_impact_questions();  */

                // update the Project Main Menu  data function
                $("#" + editform)
                    .unbind("submit")
                    .bind("submit", function (e) {
                        // form validation
                        e.preventDefault();

                        if (editdata) {
                            var form = $(this);
                            var formData = new FormData(this);

                            $.ajax({
                                url: ajax_processor,
                                type: form.attr("method"),
                                data: formData,
                                dataType: "json",
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                    if (response) {
                                        // submit loading button
                                        $("#editProductBtn").button("reset");

                                        $(".modal").each(function () {
                                            $(this).modal("hide");
                                        });
                                        swal('Record Successfully Updated!');
                                        setTimeout(3000);
                                    } // /success function
                                } // /success function
                            }); // /ajax function
                        } // /if validation is ok

                        return false;
                    }); // update the Project Main Menu  data function
            } // /success function
        }); // /ajax to fetch Project Main Menu  image
    } else {
        swal('Error creating record!!');
        setTimeout(() => {
            location.reload(true);
        }, 3000);
    }
} // /edit Project Main Menu  function

// sweet alert notifications
function success_alert(msg) {
    return swal({
        title: "Success",
        text: msg,
        type: "Success",
        icon: 'success',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
    setTimeout(function () { }, 15000);
}

// sweet alert notifications
function sweet_alert(err, msg) {
    return swal({
        title: err,
        text: msg,
        type: "Error",
        icon: 'warning',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
    setTimeout(function () { }, 15000);
}

//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

// function to add new rowfor financiers
function add_budget_costline(starting_year = null) {
    $rowno = $(`#budget_lines_values_table${starting_year} tr`).length;
    $rowno = $rowno + 1 + "" + starting_year;
    $(`#budget_lines_values_table${starting_year} tr:last`).after(`
    <tr id="budget_line_cost_line${$rowno}">
        <td>
            <input type="text" name="description${starting_year}[]" class="form-control" id="description${$rowno}">
        </td>
        <td>
            <input type="text" name="unit${starting_year}[]" class="form-control" id="unit${$rowno}">
        </td> 
        <td>
            <input type="number" name="unit_cost${starting_year}[]" min="0" class="form-control " onchange="calculate_total_cost(${$rowno},${starting_year})" onkeyup="calculate_total_cost(${$rowno},${starting_year})" id="unit_cost${$rowno}">
        </td>
        <td>
            <input type="number" name="no_units${starting_year}[]" min="0" class="form-control " onchange="calculate_total_cost(${$rowno},${starting_year})" onkeyup="calculate_total_cost(${$rowno}, ${starting_year})" id="no_units${$rowno}">
        </td>
        <td>
            <input type="hidden" name="subtotal_amount${starting_year}[]" id="subtotal_amount${$rowno}" class="subtotal_amount${starting_year} subamount" value="">
            <span id="subtotal_cost${$rowno}" style="color:red"></span>
        </td>
        <td style="width:2%">
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${$rowno}','${starting_year}')">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    get_designations($rowno);
}

function get_designations(rowno) {
    $.ajax({
        type: "get",
        url: "ajax/mneplan/index",
        data: "get_designations",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $(`#designation_id${rowno}`).html(response.designations);
            }
        }
    });
}

// function to delete financiers row
function delete_budget_costline(rowno, starting_year) {
    $("#" + rowno).remove();
    var project_implementation_balance = $("#h_project_implementation_balance").val();
    var mne_budget = (project_implementation_balance != "") ? parseFloat(project_implementation_balance) : 0;
    calculate_subtotal(rowno, starting_year, mne_budget);
}

function calculate_budget(costline_amount) {
    $("#financier_table_body").html(`<tr></tr><tr id="removeTr"><td colspan="7">Add Financiers</td></tr>`);
    $(`#budget_lines_values_table1`).html(`
        <tr>
            <td>
                <input type="text" name="description1[]" class="form-control" id="description1">
            </td>
            <td>
                <input type="text" name="unit1[]" class="form-control" id="unit1">
            </td>
            <td>
                <select name="designation_id1[]" id="designation_id1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false">
                    <option value="">..Select Designation..</option>
                </select>
            </td>
            <td>
                <input type="number" name="unit_cost1[]" min="0" class="form-control" onchange="calculate_total_cost(1, 1)" onkeyup="calculate_total_cost(1, 1)" id="unit_cost1">
            </td>
            <td>
                <input type="number" name="no_units1[]" min="0" class="form-control" onchange="calculate_total_cost(1, 1)" onkeyup="calculate_total_cost(1, 1)" id="no_units1">
            </td>
            <td>
                <input type="hidden" name="subtotal_amount[]" id="subtotal_amount1" class="subtotal_amount1 subamount" value="">
                <span id="subtotal_cost1" style="color:red"></span>
            </td>
            <td style="width:2%">
            </td>
        </tr>
    `);
    get_designations(1);
    var budget_contribution = 0;
    $(".sub_totals").each(function () {
        budget_contribution += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
    });

    var other_budget_lines = budget_contribution - parseFloat(costline_amount);
    $("#other_budget_lines_div").html(commaSeparateNumber(other_budget_lines));
    $("#other_budget_lines").val(other_budget_lines);
}

function add_budgetline(details) {
    $(".modal").each(function () {
        $(this)
            .find("form")
            .trigger("reset");
    });

    $(`.subamount`).each(function () {
        $(this).val("");
    });

    var cost_type = details.cost_type;
    var output_id = details.output_id;
    var budget_line_id = details.budget_line_id;
    var budget_line = details.budget_line;
    var task_id = details.task_id;
    var edit = details.edit;
    var plan_id = details.plan_id;
    var costline_amount = details.sum_cost;
    calculate_budget(costline_amount);

    $(".sub").val("");
    if (plan_id == "A") {
        $("#others_budget_line").hide();
        $("#monitoring_budget_line").show();
    } else {
        $("#others_budget_line").show();
        $("#monitoring_budget_line").hide();
    }

    $("#modal_info").html(`Add financial budgetlines for ${budget_line}`);
    $("#budget_line_id").val(budget_line_id);
    $("#task_id").val(task_id);
    $("#cost_type").val(cost_type);
    $("#output_id").val(output_id);
    $("#plan_id").val(plan_id);
    $("#costline_amount").val(costline_amount);
    $("#costline_amount_div").html(commaSeparateNumber(costline_amount));
    $("#current_plan_span").html(budget_line);

    if (edit) {
        get_edit_details(plan_id);
    }
}

function get_edit_details(budget_line) {
    var projid = $("#projid").val();
    if (budget_line == "A") {
        $.ajax({
            type: "get",
            url: "ajax/mneplan/index",
            data: {
                get_m_details: "get_m_details",
                projid: projid,
                other_plan_id: budget_line,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#monitoring_budget_line`).html(response.body);
                    $("#financier_table_body").html(response.financier_details);
                    $("#comment").val(response.remarks);
                }
            }
        });
    } else {
        $.ajax({
            type: "get",
            url: "ajax/mneplan/index",
            data: {
                get_m_e_details: "get_m_e_details",
                projid: projid,
                other_plan_id: budget_line,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#budget_lines_values_table1`).html(response.body);
                    $(`#budget_line_foot1`).html(response.footer);
                    $("#financier_table_body").html(response.financier_details);
                    $("#comment").val(response.remarks);
                }
            }
        });
    }
}

function calculate_total_cost(rowno, starting_year) {
    var unit_cost = $(`#unit_cost${rowno}`).val();
    var no_units = $(`#no_units${rowno}`).val();
    var other_budget_lines = $(`#other_budget_lines`).val();
    var implementation_budget = $(`#mne_budget`).val();

    if (implementation_budget != "") {
        implementation_budget = parseFloat(implementation_budget);
        other_budget_lines = parseFloat(other_budget_lines);
        unit_cost = unit_cost != "" ? parseFloat(unit_cost) : 0;
        no_units = no_units != "" ? parseFloat(no_units) : 0;
        var total_budget_line_cost = 0;

        $(`.subamount`).each(function () {
            total_budget_line_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
        });

        var total_contribution = total_budget_line_cost + other_budget_lines;
        var total_cost = 0;


        if (total_contribution <= implementation_budget) {
            total_cost = no_units * unit_cost;
        } else {
            $(`#no_units${rowno}`).val("");
            $(`#unit_cost${rowno}`).val("");
            sweet_alert("Total contribution should be less or equal to the project implementation cost");
        }

        $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(total_cost));
        $(`#subtotal_amount${rowno}`).val(total_cost);

    } else {
        sweet_alert("Ensure that the implementation budget is correct");
    }

    var total_cost = 0;
    $(`.subamount`).each(function () {
        total_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
    });

    $("#output_cost_fi_celing").val(total_cost);
    $("#total_financiers").html(commaSeparateNumber(total_cost));
    calculate_subtotal(rowno, starting_year, implementation_budget);
}

function calculate_subtotal(rowno, starting_year, mne_budget) {
    var subtotal_amount = 0;
    $(`.subtotal_amount${starting_year}`).each(function () {
        if ($(this).val() != "") {
            subtotal_amount = subtotal_amount + parseFloat($(this).val());
        }
    });

    var total_amount = 0;
    $(`.subamount`).each(function () {
        if ($(this).val() != "") {
            total_amount = total_amount + parseFloat($(this).val());
        }
    });

    var percentage = ((subtotal_amount / mne_budget) * 100);
    $(`#sub_total_amount3${starting_year}`).val(commaSeparateNumber(subtotal_amount.toFixed(2)));
    $(`#sub_total_percentage3${starting_year}`).val(percentage.toFixed(2));
    $(`#output_cost_ceiling`).html(commaSeparateNumber(total_amount.toFixed(2)));
    $(`#output_cost_fi_celing`).val(total_amount);
}

// function to add new rowfor financiers
$rowno = $("#financier_table_body tr").length;

function add_row_financier() {
    $("#removeTr").remove(); //new change
    $rowno = $rowno + 1;
    $("#financier_table_body tr:last").after(
        `<tr id="financierrow${$rowno}">
                <td></td>
                <td colspan="3">
                    <select onchange=get_financier_funds("row${$rowno}") name="finance[]" id="financerow${$rowno}" class="form-control validoutcome selectedfinance" required="required">' +
                        <option value="">Select Financier from list</option>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="ceilingval[]"  id="ceilingvalrow${$rowno}" /><span id="currrow${$rowno}"></span>
                    <span id="financierCeilingrow${$rowno}" style="color:red"></span>
                </td>
                <td>
                    <input type="number" name="amountfunding[]" onkeyup=financier_funding("row${$rowno}") onchange=financier_funding("row${$rowno}")  id="amountfundingrow${$rowno}"  placeholder="Enter amount"  class="form-control financierTotal" required/>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financierrow${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>
        `);
    get_financiers($rowno);
    numbering_financier();
}

// function to delete financiers row
function delete_row_financier(rowno) {
    var financierId = "#financierId" + rowno;
    if ($(financierId).length > 0) {
        var confirmation = confirm("Are you sure you want to delete the financier");
        if (confirmation) {
            $("#" + rowno).remove();
            numbering_financier();
            $check = $("#financier_table_body tr").length;
            if ($check == 1) {
                $("#financier_table_body").html(
                    `<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`
                );
            }
        }
    } else {
        $("#" + rowno).remove();
        numbering_financier();
        $check = $("#financier_table_body tr").length;
        if ($check == 1) {
            $("#financier_table_body").html(`<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`);
        }
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_financier() {
    $("#financier_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

//get financiers
function get_financiers(rowno) {
    var projid = $("#projid").val();
    var financier = "#financerow" + rowno;
    $.ajax({
        type: "post",
        url: "assets/processor/add-financial-plan-process",
        data: {
            getfinancier: projid,
        },
        dataType: "html",
        success: function (response) {
            $(financier).html(response);
        },
    });
}

function get_financier_funds(rowno) {
    var finance = $("#finance" + rowno).val();
    var financierCeiling = "#financierCeiling" + rowno;
    var projid = $("#projid").val();

    if (finance) {
        $.ajax({
            type: "post",
            url: "assets/processor/add-financial-plan-process",
            data: {
                cfinance: "finance",
                financeId: finance,
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                var finance = $("#finance" + rowno).val();
                if (finance) {
                    if (response.msg == "true") {
                        var responseval = response.remaining;
                        $(financierCeiling).html(commaSeparateNumber(parseFloat(responseval.toFixed(2))));
                        $("#ceilingval" + rowno).val(responseval);
                    } else if (response.msg == "false") {
                        $("#ceilingval" + rowno).val("No Balance");
                        $("#finance" + rowno).val("");
                        $(financierCeiling).html("");
                    }
                } else {
                    $("#ceilingval" + rowno).val("No Balance");
                    $("#finance" + rowno).val("");
                    $(financierCeiling).html("");
                }
            },
        });
    } else {
        var msg = "Select Financier";
        sweet_alert("Error !!!", response);
        $(financierCeiling).html("");
        $("#amountfunding" + rowno).val("");
    }
}

function financier_funding(rowno) {
    var output_cost_fi_celing = $("#output_cost_fi_celing").val();
    var ceilingval = $("#ceilingval" + rowno).val();
    var amountfunding = $("#amountfunding" + rowno).val();

    var financierCeilingId = "#financierCeiling" + rowno;
    var amountfundingId = "#amountfunding" + rowno;

    // according to the ceiling of the financier
    if (output_cost_fi_celing != "") {
        output_cost_fi_celing = parseFloat(output_cost_fi_celing);
        if (ceilingval != "") {
            if (amountfunding != "") {
                if (parseFloat(amountfunding) > 0) {
                    var ceiling_val = validate_against_output_cost();
                    if (ceiling_val) {
                        var remaining = parseFloat(ceilingval) - parseFloat(amountfunding);
                        if (remaining < 0) {
                            var msg = "The value entered cannot be less than 0 ";
                            sweet_alert(msg);
                            $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
                            $(amountfundingId).val("");
                        } else {
                            $(financierCeilingId).html(commaSeparateNumber(remaining));
                        }
                    } else {
                        sweet_alert("Ensure that the financier contribution should be less or equal to budgetline cost");
                        $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
                        $(amountfundingId).val("");
                        validate_against_output_cost();
                    }
                } else {
                    var msg = "The value should be greater than 0 ";
                    sweet_alert(msg);
                    $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
                    $(amountfundingId).val("");
                }
            } else {
                $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
                $(amountfundingId).val("");
            }
        } else {
            $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
            $(amountfundingId).val("");
        }
    } else {
        sweet_alert("Enter budgetline items first");
        $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
        $(amountfundingId).val("");
    }
}

function validate_against_output_cost() {
    var outputCost = parseFloat($("#output_cost_fi_celing").val());
    var msg = false;
    var financierTotal = 0;
    $(".financierTotal").each(function () {
        if ($(this).val() != "") {
            financierTotal = financierTotal + parseFloat($(this).val());
        }
    });

    var remaining = outputCost - financierTotal;
    if (remaining >= 0) {
        $("#output_cost_ceiling").html("Total Cost Ksh: " + commaSeparateNumber(remaining));
        msg = true;
    } else {
        $("#output_cost_ceiling").html("Total Cost Ksh: " + commaSeparateNumber(remaining));
        sweet_alert("Ensure that you don't cross the plan budget limit");
    }

    return msg;
}

function validate_financiers() {
    var msg = false;
    var financier_contribution = 0;
    $(".financierTotal").each(function () {
        if ($(this).val() != "") {
            financier_contribution = financier_contribution + parseFloat($(this).val());
        }
    });

    var total_amount = 0;
    $(`.subamount`).each(function () {
        if ($(this).val() != "") {
            total_amount = total_amount + parseFloat($(this).val());
        }
    });

    if (total_amount > 0 && financier_contribution > 0) {
        msg = total_amount == financier_contribution ? true : false;
    } else {
        msg = false;
    }
    return msg;
}