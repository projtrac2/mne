var ajax_url = "ajax/monitoring/output";

$(document).ready(function () {
    $("#add_items").submit(function (e) {
        e.preventDefault();
        // $("#tag-form-submit").prop("disabled", true);
        var data = $(this)[0];
        var form = new FormData(data);
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
            }
        });
    });
    hide_milestone_divs(false);
});


const set_output_details = (details) => {
    $("#target").val(details.target);
    $("#cummulative").val(details.achieved);
    $("#previous").val(details.previous);
}

function get_project_outputs(projid, record_type) {
    $(".modal").each(function () {
        $(this).modal("hide");
        $(this)
            .find("form")
            .trigger("reset");
    });
    hide_milestone_divs(false);
    $("#current_measure").val("");
    $("#record_type").val(record_type);
    if (projid != "") {
        $("#projid").val(projid);
        $("#project_name").val(project_name);
        $.ajax({
            type: "get",
            url: ajax_url,
            record_type: record_type,
            data: {
                get_project_outputs: "get_project_outputs",
                projid: projid,
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
    var output_id = $("#output").val();
    $("#subtask_table_body").html("");
    $("#current_measure").val("");
    hide_milestone_divs(false);
    var record_type = $("#record_type").val();
    if (output_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_output_sites: "milestone",
                output_id: output_id,
                record_type: record_type,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var output_type = response.output_type;
                    var output_project_type = response.output_project_type;
                    $("#output_type").val(output_type);
                    $("#output_project_type").val(output_project_type);
                    $("#site").html(response.sites);

                    var output_details = response.output_details;
                    set_output_details(output_details);
                } else {
                    console.log("Sorrry could not find output sites");
                }
            },
        });
    } else {
        console.log("Please select output");
    }
}

function get_milestones() {
    $("#current_measure").val("");
    var output_id = $("#output").val();
    var projid = $("#projid").val();
    var site_id = $("#site").val();
    var output_project_type = $("#output_project_type").val();
    var record_type = $("#record_type").val();

    if (output_id != "") {
        if (output_project_type == 2) {
            hide_milestone_divs(true);
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_milestones: "milestone",
                    projid: projid,
                    output_id: output_id,
                    site_id: site_id,
                    record_type: record_type
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#milestone").html(response.milestones);
                        var output_details = response.output_details;
                        set_output_details(output_details);
                    } else {
                        console.log("Sorrry could not find milestone outputs");
                    }
                },
            });
        } else {
            get_output_details();
        }
    } else {
        console.log("Please select a milestone");
    }
}

function get_output_details() {
    $("#current_measure").val("");
    var output_id = $("#output").val();
    var site_id = $("#site").val();
    var projid = $("#projid").val();
    var record_type = $("#record_type").val();
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
                record_type: record_type
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var output_details = response.output_details;
                    $("#site_target").val(output_details.site_target);
                    $("#site_achieved").val(output_details.site_achieved);
                    $("#milestone_target").val(output_details.milestone_target);
                    $("#milestone_achieved").val(output_details.milestone_achieved);
                    var details = response.details;
                    set_output_details(details);
                } else {
                    console.log("Sorrry could not find milestone outputs");
                }
            },
        });
    } else {
        console.log("Please select a milestone");
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
    let milestone_target = $("#milestone_target").val();
    let milestone_achieved = $("#milestone_achieved").val();
    let site_target = $("#site_target").val();
    let site_achieved = $("#site_achieved").val();
    let output_project_type = $("#output_project_type").val();
    console.log(site_target);

    if (site_target != '') {
        console.log(site_target);
        milestone_target = parseFloat(milestone_target);
        milestone_achieved = parseFloat(milestone_achieved);
        site_target = parseFloat(site_target);
        site_achieved = parseFloat(site_achieved);
        if (measure != '' && parseFloat(measure) >= 0) {
            measure = parseFloat(measure);
            total = site_achieved + measure;
            if (total > site_target) {
                error_alert("Please ensure you do not exceed site target");
                $("#current_measure").val("");
            } else {
                console.log(output_project_type);
                if (output_project_type == '2') {
                    milestone_total = milestone_achieved + measure;
                    if (milestone_total > milestone_target) {
                        error_alert("Please ensure you do not exceed milestone target")
                        $("#current_measure").val("");
                    }
                }
            }
        } else {
            console.log(site_target);
            $("#current_measure").val("");
            error_alert('Please ensure that the current measurement is greater than zero.');
        }
    }
}