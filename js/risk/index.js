// var ajax_url = "ajax/activities/index";

$(document).ready(function () {	
    $(".risk_checklist_div").hide();
    $("#frequency").hide();
    $("#responsible").hide();
    $("#add_risk").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(response.message);
                } else {
                    error_alert(response.message);
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
    $("#add_responsible").submit(function (e) {
        e.preventDefault();
        $("#responsible-form-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(response.message);
                } else {
                    error_alert(response.message);
                }
                $("#responsible-form-submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
	
	//saving risk monitoring
    $("#add_risk_monitoring").submit(function (e) {
        e.preventDefault();
        $("#tag-monitoring-submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(response.message);
                } else {
                    error_alert(response.message);
                }
                $("#tag-monitoring-submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
	
	//Add Risk to Risk Register
    $("#add_risk_register").submit(function (e) {
        e.preventDefault();
        $("#add_risk_form_submit").prop("disabled", true);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(response.message);
                } else {
                    error_alert(response.message);
                }
                $("#add_risk_form_submit").prop("disabled", false);
                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            }
        });
    });
});


//edit register risk
function edit_register_risk(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					edit_register_risk: 1,
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				$('#edit-modal-title').html('<i class="fa fa-info-circle" style="color:orange"></i> Edit Risk');
				$('#risk_details').html(response.register_risk_detail);
				$('#riskid').val(response.riskid);
				$('#save_risk').val("edit_risk");
            }
        });
	}
};

// delete register risk
function destroy_register_risk(riskid) {
    if (riskid != "") {
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
                            destroy_register_risk: 1,
                            riskid: riskid
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
				<td colspan="5">Add Strategic Measures!!</td>
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
	}
}

function risk_monitor_severity() {
	var probability = $("#risk_likelihood").val();	
	var impact = $("#risk_impact").val();
	var ccvalue = document.getElementById("risk_severityname").className;
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
				$('#risk_severity').val(response.severityvalue);
				$('#risk_severityname').removeClass(ccvalue);
				$('#risk_severityname').addClass(response.severityclass);
				$('#risk_severityname').html(response.severitydesc);
            }
        });
	} else {
		console.log("Likelihood: " + probability + "; Impact: " + impact);
	}
}

function risk_register_info(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					risk_register_more_info: "risk_register_more_info",
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				$('#risk_more_info').html(response.risk_more_info_body);
				$('#risk_measures').html(response.risk_measures);
            }
        });
	}
}

function risk_info(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					risk_more_info: "risk_more_info",
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				$('#risk_more_info').html(response.risk_more_info_body);
				$('#risk_measures').html(response.risk_measures);
            }
        });
	}
}

function risk_performance(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					risk_performance: "risk_performance",
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				$('#risk_more_info').html(response.risk_more_info_body);
				$('#risk_level_performance_table').html(response.risk_level_performance_table);
				$('#measures_performance_table').html(response.risk_measures);
            }
        });
	} else {
		console.log(ajax_url);
	}
}

function add_row_items() {
    add_task();
}

function add_task() {
    $("#hideinfo2").remove();
    var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#tasks_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
    /* var rand = Math.floor(Math.random() * 6) + 1;
    var rowno = $("#tasks_table_body tr").length + 1; */

    $("#tasks_table_body tr:last").after(`
    <tr id="m_row${rowno}">
        <td></td>
        <td>
            <input type="text" name="strategic_measure[]" id="taskrow${rowno}" placeholder="Describe the Strategic Measure" class="form-control parameter" required/>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items('m_row${rowno}')">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>`);
    number_table_items();
}

//filter the expected output  cannot be selected twice
function editrisk(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					edit_risk: "edit_risk",
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				$('#risk_details').html(response.risk_more_info_body);
				$('#tasks_table_body').html(response.risk_measures);
				$('#riskid').val(response.riskid);
				$('#store_risk').val("editrisk");
            }
        });
	}
};

function delete_row_Items(rowno) {
	/* const rowid = document.getElementById(rowno);
	console.log("rowid:" + rowid);
	rowid.remove(); */
	
    $("#" + rowno).remove();
    number_table_items();
    if ($("#tasks_table_body tr").length == 1) {
		$("#tasks_table_body tr:last").after('<tr id="hideinfo2"><td colspan="5" align="center"> Add Strategic Measures!!</td></tr>');
	}
}

function number_table_items() {
    $("#tasks_table_body tr").each(function (idx) {
		$(this)
			.children()
			.first()
			.html(idx - 1 + 1);
	});
}

function destroy_task(id) {
    if (id != "") {
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
                            id: id
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

//filter the expected output  cannot be selected twice
function riskmonitor(riskid) {
	if (riskid != '' || riskid != null) {
		$.ajax({
            type: "get",
            url: ajax_url,
            data: {
					risk_monitoring: "risk_monitoring",
					riskid: riskid
				},
            dataType: "json",
            success: function (response) {
				console.log(response.monitored_risk_id);
				$('#risk_monitoring').html(response.risk_more_info_body);
				$('#risk_monitoring_measures').html(response.risk_measures);
				$('#risk_level').html(response.risk_level);
				$('#monitored_risk_id').val(response.monitored_risk_id);
            }
        });
	}
};

function risks_more_info(projid, likelihood, impact, level) {
	$.ajax({
		type: 'get',
		url: 'ajax/risk/index',
		data: {
			get_risk_more_info: 1,
			projid: projid,
			likelihood: likelihood,
			impact: impact, 
			level: level
		},
		dataType: "json",
		success: function(data) {
			$('#risks_details').html(data.risk_more_details_body);
		}
	});
}

function issue_more_info(projid, issueid) {
	$.ajax({
		type: 'get',
		url: 'ajax/issuesandrisks/index',
		data: {
			get_issue_more_details: 1,
			projid: projid,
			issueid: issueid
		},
		dataType: "json",
		success: function(data) {
			$('#issue_details').html(data.issue_more_info_body);
		}
	});
}


function monitoredrisks(riskid) {
	var clicked = $("#clicked").val();
	if ( clicked == 0 ){
		$("." + riskid).show();
		clicks = clicked + 1;
	}else{
		$("." + riskid).hide();
		clicks = clicked - 1;
	}
	
	$('#clicked').val(clicks);
};

function category_risks() {
	var cat_id = $("#risk_category").val();	
	$.ajax({
		type: 'get',
		url: 'ajax/risk/index',
		data: {
			get_category_risks: 1,
			cat_id: cat_id
		},
		dataType: "json",
		success: function(data) {
			$('#risk_id').html(data.category_risks);
		}
	});
}