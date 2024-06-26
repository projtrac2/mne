var ajax_url = "ajax/monitoring/output";

function submitForm(form) {
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
            setTimeout(() => {
                window.location.reload(true);
            }, 3000);
        }
    });
}


$(document).ready(function () {
    $("#add_items button").click(function (ev) {
        ev.preventDefault();
        var data = $("#add_items")[0];
        var form = new FormData(data);
        $("#tag-form-submit").prop("disabled", true);
        $("#tag-form-submit1").prop("disabled", true);

        if ($(this).attr("value") == "button2") {
            swal({
                title: "Are you sure?",
                text: `You want to complete output monitoring!`,
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
    hide_milestone_divs(false);
    $("#tag-form-submit1").hide();
});


function get_project_outputs(projid, milestone_type, project_name) {
    $(".modal").each(function () {
        $(this).modal("hide");
        $(this)
            .find("form")
            .trigger("reset");
    });
    $("#tag-form-submit1").hide();
    hide_milestone_divs(false);
    $("#current_measure").val("");
    $("#output_project_type").val(milestone_type);
    if (projid != "") {
        $("#projid").val(projid);
        $("#project_name").html(project_name);
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_project_outputs: "get_project_outputs",
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#output").html(response.outputs);
                } else {
                    error_alert("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        error_alert("Please select a milestone");
    }
}
function get_sites() {
    var output_id = $("#output").val();
    $("#subtask_table_body").html("");
    $("#current_measure").val("");
    hide_milestone_divs(false);
    if (output_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_output_sites: "milestone",
                output_id: output_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var output_type = response.output_type;
                    $("#output_type").val(output_type);
                    $("#site").html(response.sites);
                    var output_details = response.output_details;
                    $("#target").val(output_details.output_target);
                    $("#output_target").val(output_details.output_target);
                    $("#cummulative").val(output_details.output_cummulative_record);
                    $("#previous").val(output_details.previous);
                    $("#completed").val(output_details.output_completed);

                    if (output_details.output_completed == 2) {
                        $("#tag-form-submit1").show();
                    }

                    $("#previous_comments").html(response.comments);
                    $("#attachments_table1").html(response.files);
                } else {
                    error_alert("Sorrry could not find output sites");
                    $("#site").html('<option value="">.... Select Site ....</option>');
                    $("#milestone").html('<option value="">.... Select Milestone ....</option>');
                }
            },
        });
    } else {
        error_alert("Please select output");
        $("#site").html('<option value="">.... Select Site ....</option>');
        $("#milestone").html('<option value="">.... Select Milestone ....</option>');
    }
}

function get_milestones() {
    $("#current_measure").val("");
    var output_id = $("#output").val();
    var projid = $("#projid").val();
    var site_id = $("#site").val();
    var output_project_type = $("#output_project_type").val();
    if (output_id != "") {
        if (output_project_type == "2") {
            hide_milestone_divs(true);
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_milestones: "milestone",
                    projid: projid,
                    output_id: output_id,
                    site_id: site_id,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#milestone").html(response.milestones);
                        var output_details = response.output_details;
                        var site_details = response.site_details;
                        $("#target").val(site_details.site_target);
                        $("#site_target").val(site_details.site_target);
                        $("#site_achieved").val(site_details.site_cummulative_record);
                        $("#cummulative").val(site_details.site_cummulative_record);
                        $("#previous").val(site_details.site_previous_record);
                        $("#completed").val(site_details.site_completed);
                        $("#previous_comments").html(response.comments);
                        $("#attachments_table1").html(response.files);
                    } else {
                        error_alert("Sorrry could not find milestone outputs");
                        $("#milestone").html('<option value="">.... Select Milestone ....</option>');
                    }
                },
            });
        } else {
            get_output_details();
        }
    } else {
        error_alert("Please select a milestone");
        $("#milestone").html('<option value="">.... Select Milestone ....</option>');
    }
}
function get_output_details() {
    $("#current_measure").val("");
    var output_id = $("#output").val();
    var site_id = $("#site").val();
    var projid = $("#projid").val();
    var milestone_id = $("#milestone").val() != "" ? $("#milestone").val() : 0;
    if (output_id != "" || site_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_output_details: "get_output_details",
                projid: projid,
                output_id: output_id,
                site_id: site_id,
                milestone_id: milestone_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var output_details = response.output_details;
                    var site_details = response.site_details;
                    var milestone_details = response.milestone_details;
                    var output_project_type = $("#output_project_type").val();
                    if (output_project_type == "1") {
                        $("#target").val(site_details.site_target);
                        $("#cummulative").val(site_details.site_cummulative_record);
                        $("#previous").val(site_details.site_previous_record);
                        $("#completed").val(site_details.site_completed);
                    } else {
                        $("#milestone_target").val(milestone_details.milestone_target);
                        $("#milestone_achieved").val(milestone_details.milestone_achieved);
                        $("#target").val(milestone_details.milestone_target);
                        $("#cummulative").val(milestone_details.milestone_cummulative_record);
                        $("#previous").val(milestone_details.milestone_previous_record);

                        $("#completed").val(milestone_details.milestone_completed);
                    }

                    $("#site_target").val(site_details.site_target);
                    $("#site_achieved").val(site_details.site_achieved);

                    $("#previous_comments").html(response.comments);
                    $("#attachments_table1").html(response.files);
                } else {
                    error_alert("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        error_alert("Please select a milestone");
    }
}

function hide_milestone_divs(output_type) {
    $(".milestone_div").hide();
    $("#milestone").removeAttr("required");
    $("#milestone").html("");
    if (output_type) {
        $(".milestone_div").show();
        $("#milestone").attr("required", "required");
    }
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

const validateCeiling = () => {
    let measure = $("#current_measure").val();
    let completed = $("#completed").val();
    let target = $("#target").val();
    let cummulative = $("#cummulative").val();
    var output_project_type = $("#output_project_type").val();
    var site_target = $("#site_target").val();
    var site_achieved = $("#site_achieved").val();
    var site = $("#site").val();
    var milestone = $("#milestone").val();
    var output_target = parseFloat($("#output_target").val());

    site_achieved = site_achieved != "" ? parseFloat(site_achieved) : 0;
    measure = measure != "" ? parseFloat(measure) : 0;
    cummulative = cummulative != "" ? parseFloat(cummulative) : 0;

    if (site == "") {
        error_alert("Please select Ward/Site");
        $("#current_measure").val("");
        return;
    } else {
        if (milestone == "" && output_project_type == '2') {
            $("#current_measure").val("");
            error_alert("Please select milestone");
            return;
        }

        var total_site = site_achieved + measure;
        var total_milestone = cummulative + measure;

        if (total_site > site_target) {
            $("#current_measure").val("");
            error_alert(`The cummulative units should not exceed ${site_target} ward/site target`);
            return;
        } else {
            if (total_milestone > target) {
                $("#current_measure").val("");
                error_alert(`The cummulative units should not exceed ${target} ward/site target`);
                return;
            } else {
                if (completed == '1') {
                    if (output_project_type == "2" && output_target == total_milestone) {
                        $("#current_measure").val("");
                        error_alert("Please select ensure you have completed activity monitoring");
                        return;
                    } else {
                        if (total_milestone == target) {
                            $("#current_measure").val("");
                            error_alert("Please select ensure you have completed activity monitoring");
                            return;
                        }
                    }
                }
            }
        }
    }
}
