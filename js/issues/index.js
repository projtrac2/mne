var issues_url = "ajax/issuesandrisks/index";

$(document).ready(function(){		
	/* $('#par-change-form').on('submit', function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "parameterschange",
			data: form_data,
			dataType: "json",
			success:function(response)
			{   
				if(response){
					alert('Record Successfully Saved');
					$('.modal').each(function(){
						$(this).modal('hide');
					});
				}
			},
			error: function() {
				alert('Error');
			}
		});
		return false;
	});
			
	$('#escalation-response-form').on('submit', function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "escalationresponse",
			data: form_data,
			dataType: "json",
			success:function(response)
			{   
				if(response){
					alert('Record Successfully Saved');
					window.location.reload();
				}
			},
			error: function() {
				alert('Error');
			}
		});
		return false;
	}); */
		
	$(".adjustments").hide();
});

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

function adjustedscopes(issueid) {
	var clicked = $("#clicked").val();
	if ( clicked == 0 ){
		$("." + issueid).show();
		clicks = clicked + 1;
	}else{
		$("." + issueid).hide();
		clicks = clicked - 1;
	}
	
	$('#clicked').val(clicks);
};