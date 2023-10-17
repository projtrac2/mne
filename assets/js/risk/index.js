// var ajax_url = "ajax/activities/index";

$(document).ready(function () {	
    $(".risk_checklist_div").hide();
    $("#frequency").hide();
    $("#responsible").hide();
    $("#add_items").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Record saved successfully");
                } else {
                    error_alert("Record could not be saved successfully");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
});


//filter the expected output  cannot be selected twice
function riskstrategy() {
	var strategy = $("#strategy").val();
	if ( strategy == 3 || strategy == 5 ){
		$("#frequency").show();
		$("#responsible").show();
		$(".risk_checklist_div").show();
		const inputs = document.querySelectorAll('.parameter');
		for (const input of inputs) {
		  input.setAttribute('required', '');
		}
	} else if ( strategy == 1 || strategy == 2 || strategy == 4 ) {
		$("#frequency").hide();
		$("#responsible").hide();
		$(".risk_checklist_div").hide();
		$("#tasks_table_body").html(`            
			<tr></tr>
			<tr id="hideinfo2" align="center">
				<td colspan="5">Add Checklist Parameters!!</td>
			</tr>`);
		const inputs = document.querySelectorAll('.parameter');
		for (const input of inputs) {
		  input.removeAttribute('required');
		}
	}
};

function riskseverity() {
	var probability = $("#likelihood").val();	
	var impact = $("#impact").val();
	var ccvalue = document.getElementById("severityname").className;
	if (probability != '' && impact != '') {
		$.ajax({
            type: "post",
            url: ajax_url,
            data: {
					get_severity: "get_severity",
					probability: probability,
					impact: impact
				},
            dataType: "json",
            success: function (response) {
				$('#severity').val(response.severityvalue);
				$('#severityname').removeClass(ccvalue);
				$('#severityname').addClass(response.severityclass);
				$('#severityname').html(response.severitydesc);
            }
        });
	} else {
		console.log(ajax_url);
	}
}

function add_details(options, table, edit = "") {
    var milestone_details = options.milestone_details;
    var task_details = options.task_details;
    var output_details = options.output_details;
    var output_name = output_details.output_name;
    var output_id = output_details.output_id;
    var mapping_type = output_details.mapping_type;

    $("#output_id").val(output_id);
    $("#mapping_type").val(mapping_type);
    $("#milestone_id").val(milestone_details.milestone_id);
    $("#task_id").val(task_details.task_id);

    set_common_parameters();
    set_milestone_parameters(mapping_type);

    if (table == 1 || table == 4) {
        $("#milestone_div").show();
        if (table == 1) {
            $("#store_data").val("add_milestones");
            if (edit == 1) {
                $("#store_data").val("edit_milestone");
                if (mapping_type == "1") {
                    $("#milestone_table_body").html(`
                    <tr id="m_row1">
                        <td>1</td>
                        <td>
                            <input type="text" name="milestone" value="${milestone_details.milestone_name}" id="milestonerow1" placeholder="Enter" class="form-control" required/>
                        </td>
                        <td></td>
                    </tr>`);
                } else {
                    $("#milestone_table_body").html(`
                    <tr id="m_row1">
                        <td>1</td>
                        <td>
                            <input type="text" name="milestone" value="${milestone_details.milestone_name}" id="milestonerow1" placeholder="Enter" class="form-control" required/>
                        </td>
                        <td></td>
                    </tr>`);
                }
                $(".addplus_output").attr("disabled", true);
            }
        }
    } else if (table == 2) {
        $("#sequence_id").hide();
        $("#tasks_div").show();
        $("#store_data").val("tasks");
        if (edit == 1) {
        $("#sequence_id").show();
            $(".addplus_output").attr("disabled", true);
            $("#store_data").val("edit_tasks");
            $("#tasks_table_body").html(`
            <tr id="m_row1">
                <td>1</td>
                <td>
                    <input type="text" name="task" value="${task_details.task_name}" id="taskrow1" placeholder="Enter" class="form-control" required/>
                </td>
                <td>
                    <select name="unit_of_measure" id="unit_of_measurerow1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" >
                        <option value="">..Select Unit of Measure..</option>
                    </select>
                </td>
                <td></td>
            </tr>`);
            get_unit_of_measurements(1, task_details.unit_of_measure); 
        }
    }
}

function add_row_items() {
    add_task();
}

function add_task() {
    $("#hideinfo2").remove();
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#tasks_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;

    $("#tasks_table_body tr:last").after(`
    <tr id="m_row${rowno}">
        <td></td>
        <td>
            <input type="text" name="checklist_parameter[]" id="taskrow${rowno}" placeholder="Describe checklist parameter" class="form-control parameter" required/>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items('m_row${rowno}', 2)">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    number_table_items(2);
}

function delete_row_Items(rowno, table) {
    $("#" + rowno).remove();
    number_table_items(table);
    if ($("#tasks_table_body tr").length == 1) {
		$("#tasks_table_body tr:last").after('<tr id="hideinfo2"><td colspan="5" align="center"> Add Checklist Parameters!!</td></tr>');
	}
}

function number_table_items(table) {
    $("#tasks_table_body tr").each(function (idx) {
		$(this)
			.children()
			.first()
			.html(idx - 1 + 1);
	});
}

function destroy_task(id, table) {
    if (id != "" && table != "") {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover the record!",
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
                            destroy_item: "destroy_item",
                            id: id,
                            table: table
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                success_alert("Record deleted successfully");
                            } else {
                                error_alert("Could not delete the record");
                            }
                            setTimeout(() => {
                                location.reload(true);
                            }, 1000);
                        }
                    });
                } else {
                    swal("You have cancelled action!");
                }
            });
    }
}