const ajax_url1 = "ajax/inspection/general.php";
$(document).ready(function () {
    $("#add_questions_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url1,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Record saved successfully");
                } else {
                    error_alert("Record could not be saved successfully");
                }
                $("#tag-form-submit-2").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 3000);
            }
        });
    });
});


const get_details = (details) => {
    $("#project_approve_div").hide();
    $("#question_id").val(details.question_id);
    $("#question").html(details.question);
    $('#site_id').val(details.site_id);
    $('#output_id').val(details.output_id);
    $("#comment").removeAttr('required');
    $("#comment").html("");
    $(`#question1`).prop("checked", false);
    $(`#question2`).prop("checked", false);
    if (details.answer == 2) {
        $(`#question2`).prop("checked", true);
        $("#project_approve_div").show();
        $("#comment").html(details.comment);
        $("#comment").attr('required', 'required');
    } else if (details.answer == 1) {
        $(`#question1`).prop("checked", true);
        $("#comment").val('');
    }
}



const check_box = (id) => {
    if (id == 1) {
        $("#project_approve_div").hide();
        $("#comment").removeAttr('required');
        $("#comment").val('');
    } else {
        $("#project_approve_div").show();
        $("#comment").attr('required', 'required');
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