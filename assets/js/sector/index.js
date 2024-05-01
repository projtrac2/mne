var ajax_url = "ajax/sectors/index";
$(document).ready(function () {
    $(".collapse td").click(function (e) {
        e.preventDefault();
        $(this)
            .find("i")
            .toggleClass("fa-plus-square fa-minus-square");
    });

    $(".projects td").click(function (e) {
        e.preventDefault();
        $(this)
            .find("i")
            .toggleClass("fa-plus-square fa-minus-square");
    });

    $(".output td").click(function (e) {
        e.preventDefault();
        $(this)
            .find("i")
            .toggleClass("fa-plus-square fa-minus-square");
    });

    $("#submitItemForm").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: "ajax/sectors/index",
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response) {
                    swal("Success!", "Record created successfully!", "success");
                } else {
                    swal("Error!", "Could not create record!", "error");
                }
                window.location.reload(true)
            }
        });
    });

    $("#editItemForm").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response) {
                    swal("Success!", "Record updated successfully!", "success");
                } else {
                    swal("Error!", "Could not update record!", "error");
                }
                window.location.reload(true)
            }
        });
    });
});


function add(parent, value_type) {
    $("#parent").val(parent);
    $("#value_type").html(value_type);
}

function edit(parent, value_type, sector, role_group, stid) {
    $("#parent1").val(parent);
    $("#value_type1").html(value_type);
    $("#sector1").val(sector);
    $("#parent1").val(parent);
    $("#role_group1").val(role_group);
    $("#stid1").val(stid);
}

function destroy(stid, status, statusid) {
    swal({
        title: "Are you sure?",
        text: `${status}`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: "ajax/sectors/index",
                    data: {
                        deleteItem: "deleteItem",
                        stid: stid,
                        status: statusid,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response) {
                            swal("Success!", "Status updated successfully!", "success");
                        } else {
                            swal("Error!", "Could not update status!", "error");
                        }
                        window.location.reload(true)
                    }
                });
            } else {
                swal("You have canceled the action!");
            }
        });
}