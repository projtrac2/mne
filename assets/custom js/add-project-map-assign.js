// sweet alert notifications
function sweet_alert(err, msg) {
    return swal({
        title: err,
        text: msg,
        type: "Error",
        timer: 5000,
        showConfirmButton: false
    });
    setTimeout(function () { }, 2000);
}

function more(itemId = null) {
    if (itemId) {
        $("#itemId").remove();
        $(".text-danger").remove();
        $(".form-input")
            .removeClass("has-error")
            .removeClass("has-success");
        $(".div-result").addClass("div-hide");

        $.ajax({
            url: "general-settings/selected-items/fetch-selected-projects-item",
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

function add(projid) {
    if (projid) {
        $("#projid").val(projid);
        $("#newitem").attr("name", "newitem");
        $("#newitem").val("new");
        $("#assign_table_body").html(
            "<tr></tr>" +
            '<tr id="removeTr">' +
            '<td colspan="6">Assign</td>' +
            "</tr>"
        );
    }
}

function edit(projid) {
    if (projid) {
        $.ajax({
            type: "post",
            url: "assets/processor/add-project-map-assign-process",
            data: {
                get_edit: "get_edit",
                projid: projid
            },
            dataType: "html",
            success: function (response) {
                $("#assign_table_body").html(response);
                $("#projid").val(projid);
                $("#newitem").attr("name", "edititem");
                $("#newitem").val("edit");
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}

// function to add new rowfor financiers
function add_row_assign() {
    $("#removeTr").remove(); //new change
    $rowno = $("#assign_table_body tr").length;
    console.log($rowno);
    $rowno = $rowno + 1;
    $("#assign_table_body tr:last").after(
        '<tr id="row' +
        $rowno +
        '">' +
        "<td></td>" +
        "<td>" +
        '<select  data-id="' +
        $rowno +
        '" name="forest[]" id="forestrow' +
        $rowno +
        '" class="form-control show-tick selectpicker validoutcome selectedfinance" onchange="state(' +
        $rowno +
        ')"  style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<select name="team[]" multiple id="teamrow' +
        $rowno +
        '" class="form-control selectpicker membersrow' + $rowno + '" onchange="get_responsible(' + $rowno + ')" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<select name="responsible[]" id="responsiblerow' +
        $rowno +
        '" class="form-control selectpicker" data-actions-box="true"  style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">' +
        '<option value="">Select from list</option>' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="hidden" name="hid[]" id="hidrow' + $rowno + '"  class="form-control" value="" style="width:85%; float:right" required />' +
        '<input type="date" name="mdate[]"    id="mdaterow' +
        $rowno +
        '"   placeholder="Enter" onchange="validate_date(' + $rowno + ')"  class="form-control" style="width:85%; float:right" required/>' +
        "</td>" +
        "<td>" +
        '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_assign("row' +
        $rowno +
        '")>' +
        '<span class="glyphicon glyphicon-minus"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
    numbering_financier();
    get_options($rowno);
}

// function to delete financiers row
function delete_row_assign(rowno) {
    var hid = $("#hid" + rowno).val();
    if (hid == "") {
        $("#" + rowno).remove();
        numbering_financier();
        $check = $("#assign_table_body tr").length;
        if ($check == 1) {
            $("#assign_table_body").html(
                "<tr></tr>" +
                '<tr id="removeTr">' +
                '<td colspan="6">Assign</td>' +
                "</tr>"
            );
        }
    } else {
        var handler = confirm("Are you sure you want to delete data")
        if (handler) {
            var projid = $("#projid").val();
            if (projid && hid) {
                $.ajax({
                    type: "post",
                    url: "assets/processor/add-project-map-assign-process",
                    data: {
                        deleteItem: "deleteItem",
                        projid: projid,
                        itemId: hid
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            $("#" + rowno).remove();
                            numbering_financier();
                            $check = $("#assign_table_body tr").length;
                            console.log($check);
                            if ($check == 1) {
                                $("#assign_table_body").html(
                                    "<tr></tr>" +
                                    '<tr id="removeTr">' +
                                    '<td colspan="6">Assign</td>' +
                                    "</tr>"
                                );
                            }
                        } else {
                            console.log("Error");
                        } // /error
                    }
                });
            }
        }
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_financier() {
    $("#assign_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx);
    });
}

//filter the output cannot be selected twice
$(document).on("change", ".selectedfinance", function (e) {
    var tralse = true;
    var selectImpact_arr = [];
    var attrb = $(this).attr("id");
    var rowno = $(this).attr("data-id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();
    var handler = true;

    $(".selectedfinance").each(function (k, v) {
        var getVal = $(v).val();
        if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
            tralse = false;
            alert("You canot select Output " + selectedText + " more than once ");
            var rw = $(v).attr("data-id");
            $(v).val("");
            $(".selectpicker").selectpicker("refresh");
            return false;
        } else {
            selectImpact_arr.push($(v).val());
        }
    });
    if (!tralse) {
        return false;
    }
});

function get_options(rowno) {
    var projid = $("#projid").val();
    if (projid) {
        $.ajax({
            type: "POST",
            url: "assets/processor/add-project-map-assign-process",
            data: {
                get_option: "get_option",
                projid: projid
            },
            dataType: 'json',
            success: function (html) {
                $("#forestrow" + rowno).html(html.forest);
                $("#teamrow" + rowno).html(html.team);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}

function state(rowno) {
    var state = $("#forestrow" + rowno).val();
    var name = "team" + state + "[]";
    $("#teamrow" + rowno).attr("name", name);
}

function validate_date($rowno) {
    var today = new Date().setHours(0, 0, 0, 0);

    var chosen = new Date($("#mdaterow" + $rowno).val()).setHours(0, 0, 0, 0);
    if (today >= chosen) {
        $("#mdaterow" + $rowno).val("");
        var msg = "Date should be equal or greater than today";
        sweet_alert("Error", msg);
    }
}

$("#submitMilestoneForm").submit(function (e) {
    e.preventDefault();
    var formData = $("#submitMilestoneForm").serialize();
    $.ajax({
        type: "POST",
        url: "assets/processor/add-project-map-assign-process",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
                alert(response.messages);
                $(".modal").each(function () {
                    $(this).modal("hide");
                });
                location.reload(true);
            } else {
                alert(response.messages);
                location.reload(true);
            }
        }
    });
});

function get_responsible(rowno) {
    var members = Array();
    $(".membersrow" + rowno).each(function () {
        members.push($(this).val());
    });

    var data = members.toString();
    if (projid) {
        $.ajax({
            type: "POST",
            url: "assets/processor/add-project-map-assign-process",
            data: {
                get_responsible: "responsible",
                members: data
            },
            dataType: 'html',
            success: function (html) {
                $("#responsiblerow" + rowno).html(html);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}