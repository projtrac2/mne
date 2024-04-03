var ajax_url1 = "ajax/inspection/general.php";

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



