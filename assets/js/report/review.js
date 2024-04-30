var ajax_url = "ajax/reports/index"
$(document).ready(function () {
    $("#add_items").submit(function (ev) {
        ev.preventDefault();
        var data = $("#add_items")[0];
        var form = new FormData(data);
        $("#tag-form-submit").prop("disabled", false);
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
            },
        });

    });
});


const remarks = (indicator_id, record_type, edit, remarks_id) => {
    $("#indicator").val(indicator_id);
    $("#record_type").val(record_type);
    $("#store").val(edit);
    $("#remarks_id").val(remarks_id);

    if (edit = "edit") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_edit_details: "get_edit_details",
                remarks_id: remarks_id,
                record_type: record_type,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#remarks").val(response.remarks);
                } else {
                    sweet_alert("Error!");
                }
            },
        });
    }
}