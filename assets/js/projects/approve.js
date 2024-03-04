const project_approve_url = 'ajax/projects/approve';

$(document).ready(function () {
    $("#approveItemForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var validate = validate_approval();
        $("#tag-form-submit").prop("disabled", true);
        if (validate) {
            $.ajax({
                url: "ajax/projects/approve",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        success_alert(response.messages);
                        $(".modal").each(function () {
                            $(this).modal("hide");
                        });
                        $("#tag-form-submit").prop("disabled", false);
                    } else {
                        error_alert("Sorry couldn't approve project");
                    }

                    setTimeout(() => {
                        window.location.reload();
                        // manageItemTable.ajax.reload(null, true);
                    }, 3000);
                },
            });
        }
    });


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
                $(v).val("");
                swal("Warning", "You cannot select " + selectedText + " more than once ", "warning");
                return false;
            } else {
                select_funding_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
    });
});

function validate_approval() {
    var distribution = distribute_project_cost(4);
    var project_cost = $("#project_cost").val();
    var financiers = false;
    if (project_cost != '') {
        project_cost = parseFloat(project_cost);
        var financierTotal = 0;
        $(".financierTotal").each(function (k, v) {
            financierTotal += $(v).val() != '' ? parseFloat($(v).val()) : 0;
        });
        if (financierTotal == project_cost) {
            financiers = true;
        }
    }
    return distribution && financiers ? true : false;
}


function approve_project(projid) {
    if (projid != '') {
        $.ajax({
            url: approve_url,
            type: "post",
            data: {
                approveProj: "approveProj",
                projid: projid,
            },
            dataType: "html",
            success: function (response) {
                $("#aproveBody").html(response);
                add_row_financier();
            },
        });
    } else {
        alert("error please refresh the page");
    }
}

function add_row_financier() {
    $("#removeTr").remove();
    $rowno = $("#financier_table_body tr").length;
    $rowno = $rowno + 1;
    $("#financier_table_body tr:last").after(`
    <tr id="financerow${$rowno}">
        <td> ${$rowno} </td>
        <td>
            <select name="source_category[]" id="source_categoryrow${$rowno}" onchange="get_financier('row${$rowno}')" class="form-control validoutcome selected_category" required="required">
                <option value="">Select Source Category from list</option>
                ${details.source_categories}
            </select>
        </td>
        <td>
            <select name="financier[]" id="financierrow${$rowno}" class="form-control validoutcome" required="required">
            <option value="">Select Financier list</option>
            </select>
        </td>
        <td>
            <input type="number" name="amountfunding[]" id="amountfundingrow${$rowno}" onchange="calculate_budget(${$rowno})" onkeyup="calculate_budget(${$rowno})" placeholder="Enter amount in local currency"  class="form-control financierTotal" required/>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financerow${$rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    number_financiers();
}

// function to delete row
function delete_row_financier(rowno) {
    $("#" + rowno).remove();
    number_financiers();
    $number = $("#financier_table_body tr").length;
    if ($number == 1) {
        $("#financier_table_body tr:last").after('<tr id="removeTr"><td colspan="4">Add Financier </td></tr>');
    }
}

// auto numbering table rows on delete and add new for financier table
function number_financiers() {
    $("#financier_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function get_financier(rowno) {
    let source_category = $(`#source_category${rowno}`).val();
    $.ajax({
        type: "get",
        url: project_approve_url,
        data: { get_finacier: "get_finacier", source_category: source_category },
        dataType: "json",
        success: function (response) {
            if ($(`#source_category${rowno}`).val() != "") {
                if (response.success) {
                    $(`#financier${rowno}`).html(response.financiers);
                }
                $(`#financier${rowno}`).html(response.financiers);
            }
        }
    });
}

function add_row_partners() {
    $("#add_new_partner").remove();
    $rowno = $("#partners_table tr").length;
    $rowno = $rowno + 1;
    $("#partners_table tr:last").after(`
    <tr id="ptns${$rowno}">
        <td></td>
        <td>
            <select name="partner_id[]" id="partner_id${$rowno}" class="form-control partner_role" required="required">
                <option value="">Select Parner Role from list</option>
                ${details.partners}
            </select>
        </td>
        <td>
            <select name="partner_role[]" id="partner_role${$rowno}" class="form-control partner_role" required="required">
                <option value="">Select Parner Role from list</option>
                ${details.partner_roles}
            </select>
        </td>
        <td>
            <textarea name="description[]"  style="width:100%" required ></textarea>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_partners("ptns${$rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    numbering_partners();
}

function delete_partners(rowno) {
    $("#" + rowno).remove();
    numbering_partners();
    $number = $("#partners_table tr").length;
    if ($number == 1) {
        $("#partners_table tr:last").after('<tr id="add_new_partner"><td colspan="4">Add Partners </td></tr>');
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_partners() {
    $("#partners_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}


function add_row_files() {
    $("#add_new_file").remove();
    $rowno = $("#ç tr").length;
    $rowno = $rowno + 1;
    $("#documents_table tr:last").after(`
    <tr id="mtng${$rowno}">
        <td></td>
        <td>
            <input type="file" name="pfiles[]" id="pfiles" multiple class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
        </td>
        <td>
            <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
        </td>
      <td>
        <button type="button" class="btn btn-danger btn-sm"  onclick=delete_files("mtng${$rowno}")>
            <span class="glyphicon glyphicon-minus"></span>
        </button>
      </td>
    </tr>`);
    numbering_files();
}

function delete_files(rowno) {
    $("#" + rowno).remove();
    numbering_files();
    $number = $("#documents_table tr").length;
    if ($number == 1) {
        $("#documents_table tr:last").after('<tr id="add_new_file"><td colspan="4">Attach file </td></tr>');
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_files() {
    $("#documents_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}


function unapprove_project(projid) {
    swal({
        title: "Are you sure?",
        text: "Once unapprove project!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: project_approve_url,
                    data: {
                        unapproveitem: 'unapproveitem',
                        projid: projid,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Successfully unapproved project",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error unapproving project",
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

function distribute_project_cost(id) {
    var project_cost = $("#project_cost").val();
    var direct_budget = $("#direct_budget").val(); 
    var administrative_budget = $("#administrative_budget").val();
    var msg = false;
    project_cost = project_cost != "" ? parseFloat(project_cost) : 0;
    direct_budget = direct_budget != "" ? parseFloat(direct_budget) : 0;
    administrative_budget = administrative_budget != "" ? parseFloat(administrative_budget) : 0;

    if (project_cost > 0) {
        var distributed_budget = direct_budget + administrative_budget;
        if (distributed_budget > project_cost) {
            if (id == 1) {
                $("#direct_budget").val('');
            } else if (id == 3) {
                $("#administrative_budget").val('');
            }
        } else {
            if (id == 4) {
                if (distributed_budget != project_cost) {
                    msg = true;
                    error_alert("Project cost should match financiers contribution")
                }
            } else {
                msg = true;
            }
        }
    } else {
        error_alert("Project cost should be greater than 0");
    }
    return msg;
}

function calculate_budget(rowno) {
    var project_cost = $("#project_cost").val();
    if (project_cost != '') {
        project_cost = parseFloat(project_cost);
        var financierTotal = 0;
        $(".financierTotal").each(function (k, v) {
            financierTotal += $(v).val() != '' ? parseFloat($(v).val()) : 0;
        });
        if (financierTotal > project_cost) {
            $(`#amountfundingrow${rowno}`).val("");
            error_alert(`Financiers contribution contribution should be less than ${project_cost}`);
        }
    } else {
        error_alert("Project cost should be greater than 0");
    }
}


//Add ADP budget for all projects
function addADPBudget(projid = null, year = null) {
    if (projid) {
        $.ajax({
            url: "ajax/projects/approve",
            type: "post",
            data: {
                addADPBudget: 1,
                projid: projid,
                year: year,
            },
            dataType: "html",
            success: function (response) {
                $("#addADPBudgetBody").html(response);
            },
        });
    } else {
        alert("error please refresh the page");
    }
}

// submit approved budget
$("#addADPBudgetForm")
    .unbind("submit")
    .bind("submit", function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        var budget = $("#addProjADPBudget").val();
        console.log("bhandler = " + budget);
        if (budget == 1) {
            $.ajax({
                url: "ajax/projects/approve",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        success_alert(response.messages);
                        $(".modal").each(function () {
                            $(this).modal("hide");
                        });
                        $("#tag-form-submit").prop("disabled", false);
                    } else {
                        error_alert("Sorry couldn't approve project");
                    }

                    setTimeout(() => {
                        window.location.reload();
                        // manageItemTable.ajax.reload(null, true);
                    }, 3000);
                },
            });
        }
    });


//Add Approved budget for on  going projects
function approvedBudget(itemId = null) {
    if (itemId) {
        $.ajax({
            url: "ajax/projects/approve",
            type: "post",
            data: {
                approveBudget: "approveBudget",
                itemId: itemId,
            },
            dataType: "html",
            success: function (response) {
                $("#aprovedBudgetBody").html(response);
            },
        });
    } else {
        alert("error please refresh the page");
    }
}

// submit approved budget
$("#approvedBudgetForm")
    .unbind("submit")
    .bind("submit", function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        var bhandler = validate_location_state();
        if (bhandler) {
            $.ajax({
                url: "ajax/projects/approve",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response) {
                        $("#editProductBtn").button("reset");
                        manageItemTable.ajax.reload(null, true);
                        alert(response.messages);
                        $(".modal").each(function () {
                            $(this).modal("hide");
                        });
                    }
                    window.location.reload(true);
                },
            });
        }
    });