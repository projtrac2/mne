var ajax_url = "ajax/monitoring/index";

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

function monitor_site(details) {
    var site_id = details.site_id;
    var state_id = details.state_id;
    var projid = details.projid;
    var output_id = details.output_id;
    var design_id = details.design_id;
    var target = details.target;
    var cummulative = details.cummulative;
    var previous = details.previous;
    var remaining = details.remaining; 
    $("#projid").val(projid);
    $("#output_id").val(output_id);
    $("#design_id").val(design_id);
    $("#state_id").val(state_id);
    $("#site_id").val(site_id);
    $("#output_ceiling").val(target);
    $("#cumulative_measurement").val(cummulative);
    $("#previous").val(previous);
    $("#remaining").attr("max", remaining);
    if (state_id != "" || site_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_previous_remarks: "get_previous_remarks",
                projid: projid,
                output_id: output_id,
                design_id: design_id,
                site_id: site_id,
                state_id: state_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#previous_remarks").html(response.previous_remarks);
                }
            }
        });
    }
}

function add_issues() {
    $("#issues_table #removeTr").remove();
    $rowno = $("#issues_table tr").length;
    $rowno = $rowno + 1;
    $listno = $rowno - 1;
    $("#issues_table tr:last").after(`
    <tr id="row${$rowno}">
        <td>${$listno} </td>
        <td>
            <div class="form-line">
                <select name="issue[]" id="issue${$rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px">
                    <option value="" selected="selected" class="selection">... Select ...</option> 
                </select>
            </div>
        </td>
        <td>
            <input type="text" name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_issues("row${$rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>
    `);

    get_risk_category($rowno);
}

function delete_issues(rowno) {
    $('#' + rowno).remove();
    $rowno = $("#issues_table tr").length;
    if ($rowno == 1) {
        $("#issues_table").html(`<tr></tr><tr id="removeTr" class="text-center"><td colspan="4">Add Issues</td></tr>`);
    }
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
    $rownm = $("#attachments_table tr").length;
    $rownm = $rownm + 1;
    $attno = $rownm;
    $("#attachments_table tr:last").after(`
        <tr id="rw${$rownm}">
            <td>${$attno}</td>
            <td>
                <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${$rownm}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
}

function delete_attach(rownm) {
    $('#' + rownm).remove();
}

function hide_issues_div() {
    $("#issues_div").show();
    var target = $("#output_ceiling").val();
    var cummulative = $("#$cumulative_measurement").val();
    var outputprogress = $("#$output_progress").val();

    if (outputprogress != "" && cummulative != "") {
        var total = parseFloat(cummulative) + parseFloat(outputprogress);
        if (parseFloat(target) == total || total > target) {
            $("#issues_div").hide();
            $("#issues_table").html(`<tr></tr><tr id="removeTr" class="text-center"><td colspan="4">Add Issues</td></tr>`);
        }
    }
    return;
}