const ajax_url = "ajax/settings/standards";

$(document).ready(function () {
    $("#submit_standard_form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Successfully created record ");
                } else {
                    error_alert("Error could not create record");
                }
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
});

function get_edit_details(standard_id) {
    $("#store").val("new");
    $("#standard_id").val(standard_id);
    if (standard_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { standard_id: standard_id, get_standard: "get_standard" },
            dataType: "json",
            success: function (response) {
                if (response.success) { 
                    $("#category").val(response.standard.category_id);
                    $("#code").val(response.standard.code);
                    $("#title").val(response.standard.standard);
                    $("#standard_status").val(response.standard.status);
                    $("#store").val("edit");
                    CKEDITOR.instances['standard'].setData(response.standard.description);
                }
            }
        });
    } 
}

function get_view_details(standard_id) {
    if (standard_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { standard_id: standard_id, view_standard: "view_standard" },
            dataType: "html",
            success: function (response) {
                if (response.success) { 
                    $("#standard-content").html(response);
                }
            }
        });
    } 
}

