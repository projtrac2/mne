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
});

function add_project_issues(projid, project_name) {
    if (projid != '') {
        $("#projid").val(projid);
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
                    $("#previous_remarks").html(response.issues);
                } else {
                    $("#previous_remarks").html("<h1>No records Found</h1>");
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
            <div class="form-line">
                <select name="issue[]" id="issue${rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px">
                    <option value="" selected="selected" class="selection">... Select ...</option>
                </select>
            </div>
        </td>
        <td>
            <input type="text" name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Describe the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ></td>
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