var manageImpactTable;
var manageOutcomeTable;
// var ajax_processor= "ajax/mneplan/add-monitoring-evaluation-plan-processor";
var ajax_finance = "ajax/mneplan/add-financial-plan-process";


$(document).ready(function () {
    var myprojid = $("#myprojid").val();
    var url1 = "ajax/mneplan/index?myprojid=" + myprojid + "&evaluation=1";
    var url2 = "ajax/mneplan/index?myprojid=" + myprojid + "&evaluation=2";
    // manage Project Impact data table
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
      $("#impact-tag-form-submit").prop("disabled", true);
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
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
                } // /if response.success
            } // /success function
        }); // /ajax function
        // /if validation is ok
        return false;
    }); // /submit Project Main Menu  form


    $("#addoutcomeform").on("submit", function (event) {
        event.preventDefault();
      $("#outcome-tag-form-submit").prop("disabled", true);

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
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
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
        var form_data = $(this).serialize();
        $("#modal-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: "ajax/mneplan/index",
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Successfully created the record");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                } else {
                    success_alert("Error !!", "Error creating the record");
                }
            }
        });
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
                if (response.success) {
                    sweet_alert(response.message);
                    setTimeout(() => {
                        window.location.href = "view-mne-plan.php";
                    }, 3000);
                } else {
                    error_alert(response.message);
                }
            }
        });
    });
});

/* function survey_questions_table(url3){
    // manage Project Outcome data table
    surveyQuestionsTable = $("#survey_questions_table").DataTable({
        ajax: url3,
        order: [],
        'columnDefs': [{
            'targets': [7],
            'orderable': false,
        }]
    });
} */

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

function calculate_budget(costline_amount) {

    var budget_contribution = 0;
    $(".sub_totals").each(function () {
        budget_contribution += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
    });

    var other_budget_lines = budget_contribution - parseFloat(costline_amount);
    $("#other_budget_lines_div").html(commaSeparateNumber(other_budget_lines));
    $("#other_budget_lines").val(other_budget_lines);
}

function roles(){
    var projevaluation = $("#projevaluation").val();
    var project_impact = $("#project_impact").val();
    var role_input = `
    <option value="">Select Result Level</option>`;
    if (projevaluation != '0') {
        role_input += `<option value="2" >Outcome</option>`;
        if (project_impact != '0') {
            role_input += `<option value="3" >Impact</option>`;
        }
    }
    return role_input
}

function add_member() {
    $row = $("#members_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor(Math.random() * 1000 + 1);
    let $rowno = $row + "" + randno;
    $("#members_table_body tr:last").after(`
        <tr id="memrow${$rowno}">
            <td>${$rowno}</td>

            <td>
                <select name="role[]" id="rolesrow${$rowno}" class="form-control" required="required">
                    ${roles()}
                </select>
            </td>
            <td>
            <select name="member[]" data-id="row${$rowno}" id="membersrow${$rowno}" class="form-control members" required="required">
                ${details.members}
            </select>
        </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_member("memrow${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    numbering();
}

// function to delete financiers
function delete_row_member(rowno) {
    $("#" + rowno).remove();
    numbering();
}

// auto numbering table rows on delete and add new for financier table
function numbering() {
    $("#members_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}



function add_budgetline(details) {
    $(".modal").each(function () {
        $(this)
            .find("form")
            .trigger("reset");
    });
    var rowno = 11111;
    $(`#budget_lines_values_table`).html(`
        <tr id="budget_line_cost_line${rowno}">
            <td>
                <input type="number" name="order[]" min="0" class="form-control sequence" id="order${rowno}">
            </td>
            <td>
                <input type="text" name="description[]" class="form-control" id="description${rowno}">
            </td>
            <td>
                <select name="unit_of_measure[]" id="unit_of_measure${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                    <option value="">..Select Unit of Measure..</option>
                </select>
            </td>
            <td>
                <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="no_units${rowno}">
            </td>
            <td>
                <input type="number" name="unit_cost[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="unit_cost${rowno}">
            </td>
            <td>
                <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id${rowno}" value="0"/>
                <input type="hidden" name="task_type[]"  class="form-control" id="task_type${rowno}" value="1"/>
                <input type="hidden" name="subtotal_amount[]" id="subtotal_amount${rowno}" class="subtotal_amount${rowno} subamount" value="">
                <span id="subtotal_cost${rowno}" style="color:red"></span>
            </td>
            <td style="width:2%">
            </td>
        </tr>`);
    get_unit_of_measure(rowno);
    $(`.subamount`).each(function () {
        $(this).val("");
    });
    var mne_budget = parseFloat($("#mne_budget").val());

    var task_costs = 0;
    $(`.task_costs`).each(function () {
        task_costs += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
    });
    cost = mne_budget - task_costs;
    $('#remaining_balance').val(cost);
    $('#remaining_balance1').val(commaSeparateNumber(cost));

    var cost_type = details.cost_type;
    var output_id = details.output_id;
    var budget_line_id = details.budget_line_id;
    var budget_line = details.budget_line;
    var task_id = details.task_id;
    var edit = details.edit;
    var plan_id = details.plan_id;
    var costline_amount = details.sum_cost;
    calculate_budget(costline_amount);

    $("#modal_info").html(`Add financial budgetlines for ${budget_line}`);
    $("#budget_line_id").val(budget_line_id);
    $("#task_id").val(task_id);
    $("#cost_type").val(cost_type);
    $("#output_id").val(output_id);
    $("#plan_id").val(plan_id);
    var mne_budget = parseFloat($("#mne_budget").val());
    if (edit) {
        var projid = $("#projid").val();
        $.ajax({
            type: "get",
            url: "ajax/mneplan/index",
            data: {
                get_budgetline_details: "get_budgetline_details",
                projid: projid,
                cost_type: cost_type,
                mne_cost: mne_budget
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#budget_lines_values_table`).html(response.body);
                    $(`#budget_line_foot`).html(response.footer);
                    $("#comment").val(response.remarks);
                }
            }
        });
    }
}


// function to add new rowfor financiers
function add_budget_costline() {
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#milestone_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $(`#budget_lines_values_table tr:last`).after(`
        <tr id="budget_line_cost_line${rowno}">
            <td>
                <input type="number" name="order[]" min="0" class="form-control sequence" id="order${rowno}">
            </td>
            <td>
                <input type="text" name="description[]" class="form-control" id="description${rowno}">
            </td>
            <td>
                <select name="unit_of_measure[]" id="unit_of_measure${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                    <option value="">..Select Unit of Measure..</option>
                </select>
            </td>
            <td>
                <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="no_units${rowno}">
            </td>
            <td>
                <input type="number" name="unit_cost[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="unit_cost${rowno}">
            </td>
            <td>
                <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id${rowno}" value="0"/>
                <input type="hidden" name="task_type[]"  class="form-control" id="task_type${rowno}" value="1"/>
                <input type="hidden" name="subtotal_amount[]" id="subtotal_amount${rowno}" class="subtotal_amount${rowno} subamount" value="">
                <span id="subtotal_cost${rowno}" style="color:red"></span>
            </td>
            <td style="width:2%">
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${rowno}')">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    get_unit_of_measure(rowno);
}

// function to delete financiers row
function delete_budget_costline(rowno) {
    $("#" + rowno).remove();
    var mne_budget = parseFloat($("#mne_budget").val());
    calculate_subtotal(mne_budget);
}

function get_unit_of_measure(rowno) {
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_unit_of_measure: 'get_unit_of_measure',
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $(`#unit_of_measure${rowno}`).html(response.measurements);
            } else {
                error_alert('Error could not find unit of measure ');
            }
        }
    });
}

function calculate_total_cost(rowno) {
    var mne_budget = parseFloat($("#mne_budget").val());
    var remaining_balance = $("#remaining_balance").val();

    remaining_balance = remaining_balance != '' ? parseFloat(remaining_balance) : 0;

    var unit_cost = $(`#unit_cost${rowno}`).val();
    var no_units = $(`#no_units${rowno}`).val();
    unit_cost = unit_cost != '' ? parseFloat(unit_cost) : 0;
    no_units = no_units != '' ? parseFloat(no_units) : 0;

    total_cost = no_units * unit_cost;
    $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(total_cost));
    $(`#subtotal_amount${rowno}`).val(total_cost);

    var total_budget_line_cost = 0;
    $(`.subamount`).each(function () {
        total_budget_line_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
    });

    if (total_budget_line_cost > remaining_balance) {
        $(`#no_units${rowno}`).val("");
        $(`#unit_cost${rowno}`).val("");
        total_cost = 0;
        success_alert("Total contribution should be less or equal to the M&E cost");
    }

    $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(parseInt(total_cost)));
    $(`#subtotal_amount${rowno}`).val(total_cost);
    calculate_subtotal(parseFloat(mne_budget));
}

function calculate_subtotal(cost) {
    var total_amount = 0;
    $(`.subamount`).each(function () {
        if ($(this).val() != "") {
            total_amount = total_amount + parseFloat($(this).val());
        }
    });
    var percentage = (total_amount / cost) * 100;
    $(`#psub_total_amount3`).val(commaSeparateNumber(total_amount));
    $(`#psub_total_percentage3`).val(percentage.toFixed(2));
}

//

function deleteItem(item_type, itemId) {
    swal({
        title: "Are you sure?",
        text: `you want to delete ${item_type}!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: 'ajax/mneplan/index',
                    data: {
                        deleteItem: 'deleteItem',
                        itemId: itemId,
                        item_type:item_type
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: `${item_type} `,
                                text: "Successfully deleted",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: `${item_type} `,
                                text: "Error deleting",
                                icon: "error",
                            });
                        }
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}