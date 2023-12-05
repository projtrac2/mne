var issues_url = "ajax/issuesandrisks/index";

function issuemoreinfo(id) {
	$.ajax({
		type: 'get',
		url: issues_url,
		data: {
			get_issue_more_info: 1,
			issueid: id
		},
		dataType: "json",
		success: function(data) {
			$('#moreinfo').html(data.issue_more_info);
			$("#issuemoreinfo").modal({
				backdrop: "static"
			});
		}
	});
}

function issue_details(id) {
	$.ajax({
		type: 'get',
		url: issues_url,
		data: {
			get_issue_more_info: 1,
			issueid: id
		},
		dataType: "json",
		success: function(data) {
			$('#issue_details').html(data.issue_more_info);
			$("#issueDetailsModal").modal({
				backdrop: "static"
			});
		}
	});
}

function committee_action(projid,issueid){
	var statusid = $('#issueaction').val();
	var projid = $("#projid").val();
	var issueid = $("#issueid").val();
	$.ajax({
		type: 'get',
		url: issues_url,
		data: {
			issue_status: 1,
			statusid: statusid,
			projid: projid,
			issueid: issueid
		},
		dataType: "html",
		success: function (data) {
		  $('#content').html(data);
		}
	});
}

function project_adjustments(projid,issueid){
	var change_area = $('#projadjustment').val();
	var projid = $("#projid").val();
	var issueid = $("#issueid").val();
	$.ajax({
		type: 'get',
		url: issues_url,
		data: {
			project_adjustments: 1,
			change_area: change_area,
			/* projid: projid,
			issueid: issueid */
		},
		dataType: "html",
		success: function (data) {
		  $('#adjustments').html(data);
		}
	});
}