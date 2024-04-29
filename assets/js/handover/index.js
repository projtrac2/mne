var handover_ajax_url = "ajax/handover/index";
$(document).ready(function () {
    $("#add_handover_details").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit1").prop("disabled", false);

        var form = $('#add_handover_details')[0];
        var form_data = new FormData(form);
        $.ajax({
            type: "post",
            url: handover_ajax_url,
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Hadover successful");
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
                } else {
                    error_alert("Error !!! Could not handover project");
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
                }
            }
        });

    });
});

function add_project_handover(details) {
    $("#projid").val(details.projid);
    $("#project").html(details.project_name);
    $("#workflow_stage").val(details.workflow_stage);
}

function add_attachment() {
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#attachments_table1 tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $("#attachments_table1 tr:last").after(`
            <tr id="rw${rowno}">
                <td>1</td>
                <td>
                    <input type="file" name="handoverattachment[]"  id="handoverattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
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
    $("#attachments_table1 tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}