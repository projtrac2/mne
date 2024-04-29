const ajax_url = "ajax/activities/index";
$(document).ready(function () {
    $("#output_form").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#m-tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form_data,
            dataType: "json",
            success: function (response) {
                $(".modal").each(function () {
                    $(this).modal("hide");
                });
                if (response.success) {
                    success_alert("Successfully created record");
                } else {
                    error_alert("Error creating record");
                }
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        });
    });


    //filter the expected output  cannot be selected twice
    $(document).on("change", ".select_output", function (e) {
        var tralse = true;
        var selectOutcome_arr = []; // for contestant name
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();

        $(".select_output").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
                tralse = false;
                error_alert("You canot select Output " + selectedText + " more than once");
                $(v).val("");
                return false;
            } else {
                selectOutcome_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
    });
});

function add_row_output() {
    $("#hideinfo").remove();
    var rowno = $("#mile_table_body tr").length + 1;
    $("#mile_table_body tr:last").after(`
    <tr id="row${rowno}">
        <td></td>
        <td>
            <select name="output[]" data-id="outputrow${rowno}" id="outputrow${rowno}" onchange=get_target("row${rowno}")  class="form-control validoutcome select_output" required="required">
                <option value="">Select Output from list</option>
                ${details.outputs}
            </select>
        </td>
        <td>
            <input type="number" name="target[]" id="targetrow${rowno}" placeholder="Enter" class="form-control">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("row${rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    numbering_output();
}

// function to delete output output
function delete_row_output(rowno) {
    $("#" + rowno).remove();
    numbering_output();
    $number = $("#mile_table_body tr").length;
    if ($number == 1) {
        $("#mile_table_body tr:last").after(
            '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
        );
    }
}

// function to number output table
function numbering_output() {
    $("#mile_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function get_target(rowno) {
    var output = $(`#output${rowno}`).val();
    if (output != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_target: "get_target", output_id: output },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#target${rowno}`).attr("max", response.target);
                } else {
                    error_alert("Error could not get target");
                }
            }
        });
    }
}

function get_milestone_edit_details(details) {
    $("#store_output_data").val('new');
    if (details.milestone_id != "") {
        $("#milestone_name").val(details.milestone);
        $("#id").val(details.milestone_id);
        $("#store_output_data").val('edit');
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_milestone_details: "get_milestone_details", milestone_id: details.milestone_id },
            dataType: "json",
            success: function (response) {
                console.log(response);

                if (response.success) {
                    $(`#mile_table_body`).html(response.outputs);
                } else {
                    error_alert("Error could not get outputs");
                }
            }
        });
    }
}

function delete_milestone(details) {
    swal({
        title: "Are you sure?",
        text: `you want to delete ${details.milestone}`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    data: {
                        delete_milestone: 'delete_milestone',
                        milestone_id: details.milestone_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            swal({
                                title: "Milestone !",
                                text: "Successfully deleted milestone",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Project !",
                                text: "Error deleting milestone",
                                icon: "error",
                            });
                        }

                        setTimeout(function () {
                            window.location.reload(true);
                        }, 3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}