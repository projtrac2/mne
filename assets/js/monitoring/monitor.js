var ajax_url = "ajax/monitoring/checklist";

$(document).ready(function () {
    $("#add_items button").click(function (ev) {
        ev.preventDefault();
        var data = $("#add_items")[0];
        var form = new FormData(data);

        if ($(this).attr("value") == "button2") {
            swal({
                title: "Are you sure?",
                text: `You want to close subtask monitoring!`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.append("button", "1");
                        submitForm(form);
                    } else {
                        swal("You cancelled the action!");
                    }
                });
        } else if ($(this).attr("value") == "button1") {
            form.append("button", "0");
            submitForm(form);
        }
    });
    hide_divs(false);
});

const submitForm = (form) => {
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
}

function hide_divs(output_type) {
    $("#site_div").hide();
    $("#site").removeAttr("required");
    if (output_type) {
        $("#site_div").show();
        $("#site").attr("required", "required");
    }
}

function get_outputs() {
    $("#subtask_table_body").html("");
    var milestone_id = $("#milestone").val();
    if (milestone_id != "" || milestone_id != undefined) {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_milestone_outputs: "milestone",
                milestone_id: milestone_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#output").html(response.outputs);
                } else {
                    console.log("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        console.log("Please select a milestone");
    }
}

function get_sites() {
    var milestone_id = $("#milestone").val();
    var output_id = $("#output").val();
    hide_divs(false);
    $("#subtask_table_body").html("");
    if (milestone_id != "" && output_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_output_sites: "milestone",
                milestone_id: milestone_id,
                output_id: output_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var output_type = response.output_type;
                    $("#output_type").val(output_type);

                    if (output_type == 1) {
                        hide_divs(true);
                        $("#site").html(response.sites);
                    } else if (output_type == 2) {
                        $("#subtask_table_body").html(response.subtasks);
                    }
                } else {
                    console.log("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        console.log("Please select a milestone");
    }
}

function get_subtasks() {
    var milestone_id = $("#milestone").val();
    var output_id = $("#output").val();
    var site_id = $("#site").val();
    if (site != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_subtasks: "get_subtasks",
                milestone_id: milestone_id,
                output_id: output_id,
                site_id: site_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#subtask_table_body").html(response.subtasks);
                } else {
                    console.log("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        error_alert("Please select site");
    }
}

function add_checklist(task_id, subtask_id) {
    var milestone_id = $("#milestone").val();
    var output_id = $("#output").val();
    var output_type = $("#output_type").val();
    var site = $("#site").val();
    var site_id = output_type == 2 ? 0 : site;
    $("#milestone_id").val(milestone_id);
    $("#output_id").val(output_id);
    $("#site_id").val(site_id);
    $("#task_id").val(task_id);
    $("#subtask_id").val(subtask_id);

    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_subtask_details: "get_subtask_details",
            milestone_id: milestone_id,
            output_id: output_id,
            task_id: task_id,
            subtask_id: subtask_id,
            site_id: site_id,
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // outputs
                $("#output_target").val(response.output_target);
                $("#output_cummulative").val(response.output_cummulative);
                $("#output_previous").val(response.previous_output_record);

                // subtasks
                $("#target").val(response.target);
                $("#cummulative").val(response.project_cummulative);
                $("#previous").val(response.previous_record);
                $("#previous_remarks").html(response.previous_remarks);
                $("#task_name").html(response.subtask);

                var issue = response.issues;
                $("#tag-form-submit1").show();
                if (issue == "1") {
                    $("#tag-form-submit1").hide();
                }
            } else {
                console.log("Sorrry could not find milestone outputs");
            }
        },
    });

}

function add_attachment() {
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#attachments_table tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $("#attachments_table tr:last").after(`
        <tr id="rw${rowno}">
            <td>1</td>
            <td>
                <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
    number_table();
}

function delete_attach(rownm) {
    $("#" + rownm).remove();
    number_table();
}

function number_table() {
    $("#attachments_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}
