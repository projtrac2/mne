var manageItemTable;
$(document).ready(function() { 
  manageItemTable = $("#manageItemTable").DataTable({
    order: [],
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
    }]
   }); 
}); 
 
// fetch more of  strategy
function more(itemId = null) { 
  if (itemId) {
    $("#itemId").remove(); 
    $(".text-danger").remove(); 
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success"); 
    $(".div-result").addClass("div-hide"); 
    $.ajax({
      url: "assets/processor/fetch-selected-stratplan-objectives-item",
      type: "post",
      data: { more: "more", itemId: itemId },
      dataType: "html",
      success: function(response) { 
        $("#moreinfo").html(response);
        $("#moreInfo").DataTable({});
      } 
    });
  } else {
    alert("error please refresh the page");
  }
} 
 
// /get info for objectives 
function moreInfo(itemId = null) { 
  if (itemId) {
    $("#itemId").remove(); 
    $(".text-danger").remove(); 
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success"); 
    $(".div-result").addClass("div-hide"); 
    $.ajax({
      url: "assets/processor/fetch-selected-stratplan-objectives-item",
      type: "post",
      data: { moreInfo: "more", itemId: itemId },
      dataType: "html",
      success: function(response) { 
        $("#moreinformation").html(response);
        $("#moreInformations").DataTable({}); 
      } 
    }); 
  } else {
    alert("error please refresh the page");
  }
} 
 
// /get info for objectives 
function addstrategy(itemId = null) { 
	if (itemId) {
		var itmid = itemId; 
		$(".strat").prepend('<input type="hidden" name="objid" value="' + itmid + '">');  
	} else {
		alert("error please refresh the page");
	}
}

 
function removeItem(itemId = null) {
  if (itemId) { 
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "assets/processor/fetch-selected-stratplan-objectives-item",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {   
              window.location.reload(true);
              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Contractor Nationality
        return false;
      }); // /remove Contractor Nationality btn clicked
  } // /if Contractor Nationalityid
} // /remove Contractor Nationality function


function removeStrategy(itemId = null) {
  if (itemId) { 
    $("#removeStrategyBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "assets/processor/fetch-selected-stratplan-objectives-item",
          type: "post",
          data: { strategyid: itemId, deleteStrategy: "deleteStrategy" },
          dataType: "json",
          success: function(response) { 
            $("#removeStrategyBtn").button("reset");
            if (response.success == true) {   
              window.location.reload(true);
              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } 
          }  
        });  
        return false;
      });  
  }   
}   



function editItem(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    // remove text-error
    $(".text-danger").remove();
    // remove from-group error
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success");
    // modal div 
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "fetch-selected-stratplan-objectives-item",
      type: "post",
      data: { edit:"edit", itemId: itemId },
      dataType: "html",
      success: function(response) {  
         $("#result").html(response);  
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();  
              var form = $(this);
              var formData = new FormData(this);
              $.ajax({
                url: "fetch-selected-stratplan-objectives-item",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response) { 
                    window.location.reload(true);
                    $("#edittitleBtn").button("reset");  
                    alert(response.messages);
                    $(".modal").each(function() {
                      $(this).modal("hide");
                    });
                  }  
                }  
              });  
               
          }); 
      }  
    });  
  } else {
    alert("error please refresh the page");
  } 
} // /edit manage countries  function
 

function add_row() {
  $rowno = $("#funding_table tr").length;
  $rowno = $rowno + 1;
  $("#funding_table tr:last").after(
    '<tr id="row' +
      $rowno +
      '"><td><input type="text" name="strategic[]" id="strategic" class="form-control"  placeholder="Enter Strategic Objective " style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' +
      $rowno +
      '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>'
  );
}

function delete_row(rowno) { 
  $("#" + rowno).remove();
} 


$(document).ready(function(){		
	$('#addstrategyForm').on('submit', function(event){
		event.preventDefault();
		var form_info = $(this).serialize();
		console.log("YEEEEES");
		$.ajax({
			type: "POST",
			url: "assets/processor/add-new-strategy",
			data:form_info,
			dataType:"json",
			success:function(response)
			{   
				/* if(response){
					alert('Record successfully saved');
					window.location.reload();
				} */
				
				// loading remove button
				//$("#addStrategyBtn").button("reset");
				if (response.success == true) {
				  alert(response.messages);
				  window.location.reload();
				} else {
				  alert(response.messages);
				} // /error
			},
			error: function() {
				alert('Error');
			}
			
		});
		return false;
	});
});