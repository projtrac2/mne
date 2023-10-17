var crud_url = "ajax/indicators/measurement_unit.php";
var manageItemTable;

$(document).ready(function () {
    manageItemTable = $("#manageItemTable").DataTable({
        ajax: crud_url + "?get_mesurement_units=get_mesurement_units",
    });

    $("#submitItemForm").on("submit", function (event) {
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response) {
                    $("#submitItemForm")[0].reset();
                    manageItemTable.ajax.reload(null, true);
                    alert("Record successfully saved");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                } else {

                }
            }
        });
    });


});

function get_item() {

}


function remove_item(itemId = null) {
    if (itemId) {
        var deleteItem = 1;
        $.ajax({
            url: "general-settings/action/measurement-units-action",
            type: "post",
            data: { itemId: itemId, deleteItem: deleteItem },
            dataType: "json",
            success: function (response) { 
                $("#removeItemBtn").button("reset");
                if (response.success == true) {
                    manageItemTable.ajax.reload(null, true);
                    swal('Record successfully deleted');
                    setTimeout(() => {
                    }, 3000);
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                } else {
                    swal('Error Updating Record');
                    setTimeout(() => {
                        location.reload(true);
                    }, 3000);
                }
            }
        });
    }
}