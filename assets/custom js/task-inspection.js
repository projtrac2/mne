$(document).ready(function(){
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});

	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
	
	$('#assign-inspection-form').on('submit', function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "assets/processor/inspectorassignment.php",
			data: form_data,
			dataType: "json",
			success:function(response)
			{   
				if(response){
					alert('Record successfully saved');
					window.location.reload();
				}
			},
			error: function() {
				alert('Error');
			}
		});
		return false;
	});
});

function CallChecklistAssignment(msid)
{
	$.ajax({
		type: 'post',
		url: 'assets/processor/callchecklistassignment.php',
		data: {msid:msid},
		success: function (data) {
			$('#checklistassignment').html(data);
			 $("#assignModal").modal({backdrop: "static"});
		}
	});
}