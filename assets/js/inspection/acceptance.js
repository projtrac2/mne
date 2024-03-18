var ajax_url1 = "ajax/inspection/general";

$(document).ready(function () {
    $("#assign_responsible").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-2").prop("disabled", true);
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


    $("#add_questions_form").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit-1").prop("disabled", true);
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


function add_checklists(details) {
    $("#checklist_projid").val(details.projid);
    $("#projcode").html(details.projcode);
    $("#projname").html(details.project_name);
    $("#outputname").html(details.output_name);
    $('#output_id').val(details.output_id);
    if (details.edit > 0) {
        $.ajax({
            type: "GET",
            url: ajax_url1,
            data: {
                get_edit_questions: 'get_edit_questions',
                projid: details.projid,
                output_id: details.output_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#checklist_table_body").html(response.questions);
                } else {
                    error_alert("Record could not be saved successfully");
                }
            }
        });
    }
}
const assign_committee = (details) => {
    $("#member_projid").val(details.projid);
    $("#project_code").html(details.projcode);
    $("#project_name").html(details.project_name);
    if (details.edit > 0) {
        $.ajax({
            type: "GET",
            url: ajax_url1,
            data: {
                get_edit_members: 'get_edit_members',
                projid: details.projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#member_table_body").html(response.members);
                } else {
                    error_alert("Record could not be saved successfully");
                }
            }
        });
    }
}

function add_row_member() {
    $row = $("#member_table_body tr").length;
    $row = $row + 1;
    var randno = Math.floor((Math.random() * 1000) + 1);
    var $rowno = $row + "" + randno;
    $("#member_table_body tr:last").after(`
        <tr id="mtng${$rowno}">
            <td></td>
            <td>
                <select name="member[]" id="memberrow${$rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">..Select Member..</option>
                    ${members}
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_member("mtng${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    numbering_member();
}

function delete_member(rowno) {
    $("#" + rowno).remove();
    numbering_member();
}

function numbering_member() {
    $("#member_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

function add_row_checklist() {
    $row = $("#checklist_table_body tr").length;
    $row = $row + 1;
    var randno = Math.floor((Math.random() * 1000) + 1);
    var $rowno = $row + "" + randno;

    $("#checklist_table_body tr:last").after(`
            <tr id="cht${$rowno}">
                <td></td>
                <td>
                    <input type="text" name="question[]" id="questionrow0" placeholder="Enter Question" class="form-control" required />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"  onclick=delete_checklist("cht${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>`);
    numbering_checklist();
}

function delete_checklist(rowno) {
    $("#" + rowno).remove();
    numbering_checklist();
}

function numbering_checklist() {
    $("#checklist_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}


function proceed(projid, substage_id) {
    swal({
        title: "Are you sure?",
        text: `You want to submit data!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: ajax_url1,
                    data: {
                        update_substage: "update_substage",
                        projid: projid,
                        substage_id: substage_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Project !",
                                text: "Record was successful",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error",
                                icon: "error",
                            });
                        }
                        setTimeout(function () {
                            window.location.href = "general-project-progress.php";
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}