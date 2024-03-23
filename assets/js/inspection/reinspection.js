const ajax_url1 = "ajax/inspection/reinspection";
$(document).ready(function () {
    $("#add_questions_form").submit(function (e) {
        e.preventDefault();
        // $("#tag-form-submit").prop("disabled", true);
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
