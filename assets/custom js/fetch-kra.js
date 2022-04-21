var manageItemTable;
$(document).ready(function() { 
  manageItemTable = $("#manageItemTable").DataTable({ }); 
}); 

 
function more(itemId = null) { 
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
      url: "assets/processor/fetch-selected-kra-item",
      type: "post",
      data: { more: "more", itemId: itemId },
      dataType: "html",
      success: function(response) { 
        $("#moreinfo").html(response);
        $("#moreInfo").DataTable({});

      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    alert("error please refresh the page");
  }
} // /edit Project Main Menu  function


// remove Contractor Nationality
function removeItem(itemId = null) {
  if (itemId) {
    // remove Contractor Nationality button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "assets/processor/fetch-selected-kra-item",
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

function addKRA() {
  	$('#addItemForm').on('submit', function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
        var kra = $("#addkra").val();
		if (kra == "") {
		  $("#addkra").after(
			'<p class="text-danger">Key Result Area field is required</p>'
		  );
		  $("#addkra")
			.closest(".form-input")
			.addClass("has-error");
		} else {  
		  $("#addkra")
			.find(".text-danger")
			.remove(); 
		  $("#addkra")
			.closest(".form-input")
			.addClass("has-success");
		}  
		if (kra) { 	  
			$.ajax({
				url: "assets/processor/fetch-selected-kra-item",
				type: "post",
				data: form_data,
				dataType: "json",
				success: function(response) {
				  if (response) { 
					window.location.reload(true); 
					$("#addItemForm").trigger("reset"); //reset form
					alert(response.messages);
					$(".modal").each(function() {
					  $(this).modal("hide");
					});
				  }  
				}  
			});  
		}   
		return false;
	});
} // /edit manage countries  function

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
      url: "assets/processor/fetch-selected-kra-item",
      type: "post",
      data: { edit:"edit", itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");
 
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        ); 
        $("#editname").val(response.kra);  

        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault(); 
            var kra = $("#editname").val();

            if (kra == "") {
              $("#editname").after(
                '<p class="text-danger">Key Result field is required</p>'
              );
              $("#editname")
                .closest(".form-input")
                .addClass("has-error");
            } else {  
              $("#editname")
                .find(".text-danger")
                .remove(); 
              $("#editeditname")
                .closest(".form-input")
                .addClass("has-success");
            }  
            if (kra) { 
              var form = $(this);
              var formData = new FormData(this);  
              $.ajax({
                url: "assets/processor/fetch-selected-kra-item",
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
            }   
            return false;
          }); 
      }  
    });  
  } else {
    alert("error please refresh the page");
  } 
} // /edit manage countries  function
 