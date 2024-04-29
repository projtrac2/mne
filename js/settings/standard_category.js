const ajax_url = "ajax/settings/standardCategory";
const standard_url = "ajax/settings/standards";

$(document).ready(function () {
    $("#submitItemForm").submit(function (e) {
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

    $("#submit_standard_form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: standard_url,
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

function get_category_edit_details(category_id) {
    $("#store_category").val("new");
    $("#category_id").val(category_id);
    if (category_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { category_id: category_id, get_category: "get_category" },
            dataType: "json",
            success: function (response) {
                if (response.success) { 
                    $("#category").val(response.standard.category);
                    $("#category_status").val(response.standard.status);
                    $("#store_category").val("edit"); 
                }
            }
        });
    }
} 

function get_edit_details(standard_id) {
    $("#store").val("new");
    $("#standard_id").val(standard_id);
    if (standard_id != "") {
        $.ajax({
            type: "get",
            url: standard_url,
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
            url: standard_url,
            data: { standard_id: standard_id, view_standard: "view_standard" },
            dataType: "html",
            success: function (response) {
                if (response) { 
                    $("#standard-content").html(response);
                }
            }
        });
    } 
}


