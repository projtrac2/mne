var ajax_url = "ajax/monitoring/issues";
$(document).ready(function () {
    $("#add_items").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        var data = $(this)[0];
        var form = new FormData(data);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Success!");
                } else {
                    sweet_alert("Error!");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
    $("#adjust_quality").hide();
    $("#adjust_scope").hide();
    $("#adjust_schedule").hide();
    $("#adjust_cost").hide();


    $("#add_issue_closure").submit(function (e) {
        e.preventDefault();
        $("#add_issue_closure-form-submit").prop("disabled", true);
        var data = $(this)[0];
        var form = new FormData(data);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: "json",
            success: function (response) {
                $("#add_issue_closure")[0].reset();
                $("#add_issue_closure-form-submit").prop("disabled", false);
                $(".modal").each(function () {
                    $(this).modal("hide");
                });

                if (response.success) {
                    success_alert("Data successfully updated");
                } else {
                    sweet_alert("Error! Update failed");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
});

function add_project_issues(projid, project_name) {
    if (projid != '') {
        $("#issue_projid").val(projid);
        $("#project_name").html(project_name);
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_project_issues: 'project',
                projid: projid
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#previous_issues").html(response.issues);
                } else {
                    $("#previous_issues").html('<h4 class="text-danger">No records found!</h4>');
                }
            }
        });
    }
}


function add_issues() {
    $("#issues_table #removeTr").remove();
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#issues_table tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $("#issues_table tr:last").after(
        `<tr id="row${rowno}">
        <td>1</td>
        <td>
            <textarea name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Describe the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ></textarea>
        <td>
            <div class="form-line">
                <select name="issuearea[]" id="issuearea${rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px">
                    <option value="" selected="selected" class="selection">... Select ...</option>
                    <option value="1" class="selection">Quality</option>
                    <option value="2" class="selection">Scope</option>
                    <option value="3" class="selection">Schedule</option>
                    <option value="4" class="selection">Cost</option>
                </select>
            </div>
        </td>
        <td>
            <div class="form-line">
                <select name="issue[]" id="issue${rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px">
                    <option value="" selected="selected" class="selection">... Select ...</option>
                </select>
            </div>
        </td>
        <td>
            <div class="form-line">
                <select name="issuepriority[]" id="issuepriority${rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px">
                    <option value="" selected="selected" class="selection">... Select ...</option>
                    <option value="1" class="selection">High</option>
                    <option value="2" class="selection">Medium</option>
                    <option value="3" class="selection">Low</option>
                </select>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_issues("row${rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    get_risk_category(rowno);
    number_issues_table();
}

function delete_issues(rowno) {
    $("#" + rowno).remove();
    rowno = $("#issues_table tr").length;
    if (rowno == 1) {
        $("#issues_table").html(
            `<tr></tr><tr id="removeTr" class="text-center"><td colspan="4">Add Issues</td></tr>`
        );
    }
    number_issues_table();
}

function number_issues_table() {
    $("#issues_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function get_risk_category(rowno) {
    var projid = $("#projid").val();
    var output_id = $("#output_id").val();
    if (output_id != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_risk_category: "get_risk_category",
                projid: projid,
                output_id: output_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#issue${rowno}`).html(response.issues);
                }
            }
        });
    }
}

function add_attachment() {
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#attachments_table tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $("#attachments_table tr:last").after(`
    <tr id="rw${rowno}">
        <td>1</td>
        <td>
            <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
        </td>
        <td>
            <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    number_table();
}

function delete_attach(rownm) {
    $("#" + rownm).remove();
    number_table();
}

function number_table() {
    $("#attachments_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

//filter the expected output  cannot be selected twice issue_type
function adjustscope() {
    var issue_type = $("#issue_area").val();
    var projid = $("#issue_projid").val();
    if (projid != '') {
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: {
                issue_type: issue_type,
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#issue_type").html(response.issuefields);
                }
            }
        });
    }

    /* var issue_area = $("#issue_area").val();
    if ( issue_area == 1 ) {
        $("#adjust_quality").show();
        $("#adjust_scope").hide();
        $("#adjust_schedule").hide();
        $("#adjust_cost").hide();
    } else if ( issue_area == 2 ){
        $("#adjust_scope").show();
        $("#adjust_quality").hide();
        $("#adjust_schedule").hide();
        $("#adjust_cost").hide();
    } else if ( issue_area == 3 ){
        $("#adjust_schedule").show();
        $("#adjust_quality").hide();
        $("#adjust_scope").hide();
        $("#adjust_cost").hide();
    } else if ( issue_area == 4 ){
        $("#adjust_cost").show();
        $("#adjust_quality").hide();
        $("#adjust_schedule").hide();
        $("#adjust_scope").hide();
    } */
};

function adjustedscopes(issueid) {
    var clicked = $("#clicked").val();
    if (clicked == 0) {
        $("." + issueid).show();
        clicks = clicked + 1;
    } else {
        $("." + issueid).hide();
        clicks = clicked - 1;
    }

    $('#clicked').val(clicks);
};

function close_project_issue(issueid, projid) {
    if (issueid != '') {
        $("#projid").val(projid);
        $("#issueid").val(issueid);
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_project_issue_details: issueid,
                projid: projid
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#project_name").html(response.projname);
                    $("#issue_details").html(response.issue_details);
                } else {
                    $("#issue_details").html('<h4 class="text-danger">No records found!</h4>');
                }
            }
        });
    }
}

function closed_project_issue(issueid) {
    if (issueid != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_closed_project_issue_details: issueid
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#closed_issue_project_name").html(response.projname);
                    $("#closed_issue_details").html(response.closed_issue_details);
                } else {
                    $("#closed_issue_details").html('<h4 class="text-danger">No records found!</h4>');
                }
            }
        });
    }
}