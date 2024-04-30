const ajax_url = "ajax/monitoring/checklist-history.php";

function add_checklist(subtask_id, site_id) {
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_info: 'get_info',
            subtask_id: subtask_id,
            site_id: site_id,
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#attachments_table1").html(response.attachments);
                $("#remarks").html(response.remarks);
            } else {
                $("#attachments_table1").html(`<tr><td colspan="3" class="text-center">No Files Found</td></tr>`);
            }
        }
    });
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
        </tr>
    `);
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