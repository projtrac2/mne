$(document).ready(function() {
	$('.tables').DataTable();


	// submit approved pbb details  
	$("#approveItemForm").submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "post",
			url: "assets/processor/padp-process",
			data: form_data,
			dataType: "json",
			success: function(response) {
				if (response) {
					alert(response.messages);
					$(".modal").each(function() {
						$(this).modal("hide");
					});
				}
				window.location.reload(true);
			}
		});
	});

	// submit editted approved pbb details  
	$("#editpbbItemForm").submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "post",
			url: "assets/processor/padp-process",
			data: form_data,
			dataType: "json",
			success: function(response) {
				if (response) {
					alert(response.messages);
					$(".modal").each(function() {
						$(this).modal("hide");
					});
				}
				window.location.reload(true);
			}
		});
	});

	// submit program quarterly targets
	$("#quarterlyTargetsForm").submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "post",
			url: "assets/processor/padp-process",
			data: form_data,
			dataType: "json",
			success: function(response) {
				if (response) {
					alert(response.messages);
					$(".modal").each(function() {
						$(this).modal("hide");
					});
				}
				window.location.reload(true);
			}
		});
	});

	// submit editted program quarterly targets
	$("#editquarterlyTargetsForm").submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "post",
			url: "assets/processor/padp-process",
			data: form_data,
			dataType: "json",
			success: function(response) {
				if (response) {
					alert(response.messages);
					$(".modal").each(function() {
						$(this).modal("hide");
					});
				}
				window.location.reload(true);
			}
		});
	});
});
// get the program budget/target div from db 
function approvePADP(progid = null) {
	if (progid) {
		$.ajax({
			type: "post",
			url: "general-settings/action/adp-edit-action",
			data: {
				create_padp_div: "create_padp_div",
				progid: progid
			},
			dataType: "html",
			success: function(response) {
				$("#aproveBody").html(response);
			}
		});
	}
}

// get the program budget/target div from db 
function editPADP(progid = null, adpyr = null) {
	if (progid) {
		$.ajax({
			type: "post",
			url: "general-settings/action/adp-edit-action",
			data: {
				edit_padp_div: "edit_padp_div",
				progid: progid,
				adpyr: adpyr
			},
			dataType: "html",
			success: function(response) {
				$("#editBody").html(response);
			}
		});
	}
}

// get the program budget/target div from db 
function addQuarterlytargets(progid = null, adpyr = null) {
	//console.log(progid);
	if (progid) {
		$.ajax({
			type: "post",
			url: "general-settings/action/adp-edit-action",
			data: {
				create_qtargets_div: "create_qtargets_div",
				progid: progid,
				adpyr: adpyr
			},
			dataType: "html",
			success: function(response) {
				$("#quarterlyTargetsBody").html(response);
			}
		});
	}
}

// get the program budget/target div from db 
function editQuarterlytargets(progid = null, adpyr = null) {
	//console.log(progid);
	if (progid) {
		$.ajax({
			type: "post",
			url: "general-settings/action/adp-edit-action",
			data: {
				edit_qtargets_div: "edit_qtargets_div",
				progid: progid,
				adpyr: adpyr
			},
			dataType: "html",
			success: function(response) {
				$("#editquarterlyTargetsBody").html(response);
			}
		});
	}
}