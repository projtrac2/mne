var manageItemTable;
$(document).ready(function () {
    manageItemTable = $("#manageItemTable").DataTable({
        ajax: "assets/processor/fetch-selected-inspection-checklist",
        order: [],
        columnDefs: [
            {
                targets: [0, 6],
                orderable: false
            }
        ]
    });
});

function more(itemId = null) {
    if (itemId) {
        $.ajax({
            url: "assets/processor/fetch-selected-projects-item",
            type: "post",
            data: { itemId: itemId },
            dataType: "html",
            success: function (response) {
                $("#moreinfo").html(response);
            } // /success function
        }); // /ajax to fetch Project Main Menu  image
    } else {
        alert("error please refresh the page");
    }
}

function moreme(itemId = null) {
    if (itemId) {
        $.ajax({
            url: "assets/processor/fetch-selected-workplan-item",
            type: "post",
            data: { itemId: itemId },
            dataType: "html",
            success: function (response) {
                $("#moreinfo").html(response);
            } // /success function
        }); // /ajax to fetch Project Main Menu  image
    } else {
        alert("error please refresh the page");
    }
}

// remove Project
function removeItem(itemId = null) {
    if (itemId) {
        $("#removeItemBtn")
            .unbind("click")
            .bind("click", function () {
                var deleteItem = 1;
                $.ajax({
                    url: "assets/processor/add-project-workplan-process",
                    type: "post",
                    data: { itemId: itemId, deleteItem: deleteItem },
                    dataType: "json",
                    success: function (response) {
                        $("#removeItemBtn").button("reset");
                        if (response.success == true) {
                            manageItemTable.ajax.reload(null, true);
                            alert(response.messages);
                            $(".modal").each(function () {
                                $(this).modal("hide");
                            });
                        } else {
                            alert(response.messages);
                        }
                    }
                });
                return false;
            });
    }
}