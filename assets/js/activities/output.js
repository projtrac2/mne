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
            },
        });
    });

    $("#store_output_data_mile").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $("#tag-form-submit").prop("disabled", true);
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
            },
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
                error_alert(
                    "You canot select Output " + selectedText + " more than once"
                );
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
    hide_milestone_type();
});

function hide_milestone_type() {
    var val = $("#milestone_type").val();
    $("#milestone_data_task_based").hide();
    $("#milestone_data_output_based").hide();
    $("#milestone_table_body").html(`<tr></tr><tr id="hideinfo1" align="center"><td colspan="5">Add Outputs!!</td></tr>`);
    $("#milestone_task_table_body").html(`<tr></tr><tr id="hideinfo2" align="center"><td colspan="3">Add Outputs!!</td></tr>`);

    if (val == 1) {
        $("#milestone_data_task_based").show();
        $("#milestone_data_output_based").hide();
    } else if (val == 2) {
        $("#milestone_data_task_based").hide();
        $("#milestone_data_output_based").show();
    }
}

function add_details(options, id) {
    $("#milestone_data").show();
    $("#store_output_data").val("new");
    $("#milestone_data_task_based").hide();
    $("#milestone_data_output_based").hide();
    $("#milestone_table_body").html(`<tr></tr><tr id="hideinfo1" align="center"><td colspan="5">Add Outputs!!</td></tr>`);
    $("#milestone_task_table_body").html(`<tr></tr><tr id="hideinfo2" align="center"><td colspan="3">Add Outputs!!</td></tr>`);

    if (id == 2) {
        $("#milestone_data").hide();
        $("#store_output_data").val("edit");
        $("#milestone_name").val(options.milestone);
        $("#milestone_id").val(options.milestone_id);
        $("#milestone_type").val(options.milestone_type);

        if (options.milestone_type == 1) {
            $("#milestone_data_task_based").show();
            $("#milestone_data_output_based").hide();
        } else if (options.milestone_type == 2) {
            $("#milestone_data_task_based").hide();
            $("#milestone_data_output_based").show();
        }
        edit_milestone(options.milestone_id);
    }
}

function add_output(options, id) {
    $("#milestone_data").show();
    $("#store_output_data_mile_d").val("new");
    $("#o_milestone_id").val(options.milestone_id);
    $("#outputrow9999").prop("readonly", false);
    if (id == 2) {
        $("#milestone_data").hide();
        $("#store_output_data_mile_d").val("edit");
        $("#targetrow9999").val(options.target);
        $("#outputrow9999").html(`<option value="">Select Output from list</option><option value="${options.output_id}" selected="selected">${options.output}</option>`);
        $("#outputrow9999").prop("readonly", true);
        get_targets('row9999', options);
    } else {
        if (options.milestone_type == '2') {
            get_outputs('row9999', options);
            $("#design").show();
            $("#milestone_data_task_based_1").hide();
            $("#milestone_task_table_body_1 tr:last").after('<tr></tr><tr id="hideinfo4"><td colspan="5" align="center"> Add Output</td></tr>');
            $("#outputrow9999").attr("required", "required");
            $("#targetrow9999").attr("required", "required");
        } else {
            $("#design").hide();
            $("#milestone_data_task_based_1").show();
            $("#outputrow9999").removeAttr("required");
            $("#targetrow9999").removeAttr("required");
            add_task_output_1();
        }
    }
}

// output based

function add_row_output() {
    $("#hideinfo1").remove();
    var rowno = $("#milestone_table_body tr").length + 1;
    $("#milestone_table_body tr:last").after(`
        <tr id="row${rowno}">
            <td></td>
            <td>
                <select name="output[]" data-id="outputrow${rowno}" id="outputrow${rowno}" onchange=get_targets("row${rowno}")  class="form-control validoutcome select_output" required="required">
                    <option value="">Select Output from list</option>
                </select>
            </td>
            <td>
                <input type="number" name="target[]" id="targetrow${rowno}" onchange="calculate_maximum('row${rowno}')" onkeyup="calculate_maximum('row${rowno}')" placeholder="Please enter target" class="form-control"/>
                <input type="hidden" value="" id="target_valrow${rowno}"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("row${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    $(".selectpicker").selectpicker("refresh");
    numbering_output();
    get_outputs(`row${rowno}`);
}

// function to delete output output
function delete_row_output(rowno) {
    $("#" + rowno).remove();
    numbering_output();
    $number = $("#milestone_table_body tr").length;
    if ($number == 1) {
        $("#milestone_table_body tr:last").after(
            '<tr></tr><tr id="hideinfo1"><td colspan="5" align="center"> Add Output</td></tr>'
        );
    }
}

// function to number output table
function numbering_output() {
    $("#milestone_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function get_outputs(rowno, options) {
    var projid = $("#projid").val();
    var milestone_id = (options != undefined) ? options.milestone_id : '';
    if (projid != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_outputs: "get_outputs", projid: projid, milestone_id: milestone_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#output${rowno}`).html(response.outputs);
                } else {
                    error_alert("Error could not get target");
                }
            },
        });
    }
}

function calculate_maximum(rowno) {
    var target = $(`#target${rowno}`).val();
    var maximum = $(`#target_val${rowno}`).val();
    if (target != "" && parseFloat(target) > 0) {
        if (maximum != "") {
            var balance = parseFloat(maximum) - parseFloat(target);
            if (balance >= 0) {
            } else {
                error_alert("sorry you cannot exceed the maximum ceiling");
                $(`#target${rowno}`).val("");
            }
        }
    } else {
        error_alert("sorry you cannot enter 0 or empty file");
    }
}

// task based
function add_task_output() {
    $("#hideinfo2").remove();
    var rowno = $("#milestone_task_table_body tr").length + 1;

    $("#milestone_task_table_body tr:last").after(`
        <tr id="rowrs${rowno}">
            <td></td>
            <td>
                <select name="outputx[]" data-id="outputxrow${rowno}" id="outputxrow${rowno}" class="form-control validoutcome select_output" required="required">
                    <option value="">Select Output from list</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_task_output("rowrs${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    numbering_task_output();
    get_task_outputs(rowno);
}

// function to delete output output
function delete_task_output(rowno) {
    $("#" + rowno).remove();
    numbering_task_output();
    $number = $("#milestone_task_table_body tr").length;
    if ($number == 1) {
        $("#milestone_task_table_body tr:last").after(
            '<tr></tr><tr id="hideinfo2"><td colspan="5" align="center"> Add Output</td></tr>'
        );
    }
}

// function to number output table
function numbering_task_output() {
    $("#milestone_task_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}


// single output
function add_task_output_1() {
    $("#hideinfo4").remove();
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#milestone_task_table_body_1 tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    $("#milestone_task_table_body_1 tr:last").after(`
        <tr id="rowrs${rowno}">
            <td></td>
            <td>
                <select name="outputp[]" data-id="outputprow${rowno}" id="outputxrow${rowno}" class="form-control validoutcome select_output" required="required">
                    <option value="">Select Output from list</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_task_output_1("rowrs${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    numbering_task_output_1();
    get_task_outputs(rowno);
}

// function to delete output output
function delete_task_output_1(rowno) {
    $("#" + rowno).remove();
    numbering_task_output();
    $number = $("#milestone_task_table_body_1 tr").length;
    if ($number == 1) {
        $("#milestone_task_table_body_1 tr:last").after(
            '<tr></tr><tr id="hideinfo4"><td colspan="5" align="center"> Add Output</td></tr>'
        );
    }
}

// function to number output table
function numbering_task_output_1() {
    $("#milestone_task_table_body_1 tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}

function get_task_outputs(rowno) {
    var milestone_id = $("#o_milestone_id").val();
    milestone_id = milestone_id != '' ? milestone_id : 0;
    var projid = $("#projid").val();
    if (projid != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: { get_task_outputs: "get_task_outputs", projid: projid, milestone_id: milestone_id },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#outputxrow${rowno}`).html(response.outputs);
                } else {
                    error_alert("Error could not get target");
                }
            },
        });
    }
}

function get_targets(rowno, options) {
    var projid = $(`#projid`).val();
    var output_id = $(`#output${rowno}`).val();
    var target = milestone_id = 0;

    if (options != undefined) {
        output_id = options.output_id;
        target = options.target;
        milestone_id = $("#o_milestone_id").val();
        if (milestone_id == "") {
            milestone_id = $("#milestone_id").val();
        }
    }

    if (output_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_target: "get_target",
                output_id: output_id,
                projid: projid,
                target: target,
                milestone_id: milestone_id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#target_val${rowno}`).val(response.target);
                    $(`#target${rowno}`).attr('placeholder', `Target ${response.target}`);
                } else {
                    error_alert("Error could not get target");
                }
            },
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
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: "post",
                url: ajax_url,
                data: {
                    delete_milestone: "delete_milestone",
                    milestone_id: details.milestone_id,
                    csrf_token: $("#csrf_token").val(),
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

function delete_output(details) {
    swal({
        title: "Are you sure?",
        text: `you want to delete ${details.output} under ${details.milestone}`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: "post",
                url: ajax_url,
                data: {
                    delete_milestone_output: "delete_milestone_output",
                    milestone_id: details.milestone_id,
                    output_id: details.output_id,
                    csrf_token: $("#csrf_token").val(),
                },
                dataType: "json",
                success: function (response) {
                    if (response.success == true) {
                        swal({
                            title: "Milestone !",
                            text: "Successfully deleted ouput under milestone",
                            icon: "success",
                        });
                    } else {
                        swal({
                            title: "Project !",
                            text: "Error deleting output under milestone",
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

function edit_milestone(milestone_id) {
    if (milestone_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_miletone_edit_details: "get_miletone_edit_details",
                milestone_id: milestone_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var milestone = response.milestone;
                    var milestone_type = milestone.milestone_type;
                    var outputs = response.outputs;
                    var project_outputs = response.project_outputs;
                    $("#milestone_type").val(milestone.milestone_type);
                    if (outputs.length > 0) {
                        var html_body = "<tr></tr>";
                        var rowno = 0;
                        $.each(outputs, function (counter, output) {
                            rowno++;
                            var options = set_outputs(project_outputs, output.output_id);
                            if (milestone_type == 1) {
                                html_body += `
                                <tr id="rowrs${rowno}">
                                    <td>${rowno}</td>
                                    <td>
                                        <select name="outputx[]" data-id="outputxrow${rowno}" id="outputxrow${rowno}" class="form-control validoutcome select_output" required="required">
                                            ${options}
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_task_output("rowrs${rowno}")>
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </td>
                                </tr>`;
                            } else {
                                html_body += `
                                <tr id="row${rowno}">
                                    <td>${rowno}</td>
                                    <td>
                                        <select name="output[]" data-id="outputrow${rowno}" id="outputrow${rowno}" onchange=get_targets("row${rowno}")  class="form-control validoutcome select_output" required="required">
                                            ${options}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="target[]" id="targetrow${rowno}" value='${output.target}'  onchange="calculate_maximum('row${rowno}')" onkeyup="calculate_maximum('row${rowno}')" placeholder="Please enter target"class="form-control">
                                        <input type="hidden" value="" id="target_valrow${rowno}"/>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("row${rowno}")>
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </td>
                                </tr>`;
                            }
                        });

                        if (milestone_type == 1) {
                            $("#milestone_task_table_body").html(html_body);
                        } else {
                            $("#milestone_table_body").html(html_body);
                        }
                    } else {
                        if (milestone_type == 1) {
                            $("#milestone_task_table_body").html(
                                '<tr></tr><tr id="hideinfo2"><td colspan="5" align="center"> Add Output</td></tr>'
                            );
                            numbering_task_output();
                        } else {
                            $("#milestone_table_body").html(
                                '<tr></tr><tr id="hideinfo1"><td colspan="5" align="center"> Add Output</td></tr>'
                            );
                            numbering_output();
                        }
                    }
                } else {
                    error_alert("Could not find edit details ");
                }
            },
        });
    }
}

function set_outputs(project_outputs, output_id) {
    var outputs = '<option value="">Select Output from list</option>';
    $.each(project_outputs, function (counter, output) {
        var selected = output.id == output_id ? "selected" : "";
        outputs += `<option value="${output.id}" ${selected}>${output.indicator_name}</option>`;
    });
    return outputs;
}
