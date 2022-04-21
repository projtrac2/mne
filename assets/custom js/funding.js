$(document).ready(function () {
    var urlpath = window.location.pathname;
    var filename = urlpath.substring(urlpath.lastIndexOf('/') + 1);
	var funderid = $('#funderid').val();
	
    if (filename == "add-development-funds.php" || filename == "add-development-funds" || filename == "add-development-funds?fnd=" + funderid || filename == "add-development-funds.php?fnd=" + funderid) {
		var fndtype = $('#fundtype').val();
		
		if (fndtype == 3) {
			$("#grants").show();
			$("#grantperiod").show();
			$("#grantinstallment").show();
			$("#grantinstallmentdate").show();
			$("#ppprogram").show();
			$("#ppprogram").attr("required", "required");
			$("#grants .form-control").each(function () {
				$(this).attr("required", "required");
			});
			$("#ppgeneral").hide();
			$("#ppgeneral .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});
		}
		else if (fndtype == 4) {
			$("#ppprogram").show();
			$("#ppprogram").attr("required", "required");
			$("#ppgeneral").hide();
			$("#ppgeneral .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});	
			grants_hide();			
		}
		else if (fndtype == 1 || fndtype == 2) {
			$("#ppgeneral").show();
			$("#ppgeneral").attr("required", "required");
			$("#ppprogram").hide();
			$("#ppprogram .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});	
			grants_hide();
		}
		else if (fndtype == "") {
			grants_hide();
			purpose_hide()
		}
    }

    $(".account").click(function () {
        var X = $(this).attr('id');
        if (X == 1) {
            $(".submenus").hide();
            $(this).attr('id', '0');
        } else {
            $(".submenus").show();
            $(this).attr('id', '1');
        }
    });

    //Mouseup textarea false
    $(".submenus").mouseup(function () {
        return false
    });
    $(".account").mouseup(function () {
        return false
    });

    //Textarea without editing.
    $(document).mouseup(function () {
        $(".submenus").hide();
        $(".account").attr('id', '');
    });
});

function get_financier() {
    var fundtype = $('#fundingtype').val();
    if (fundtype) {
		if (fundtype == 3) {
			$("#grants").show();
			$("#grantperiod").show();
			$("#grantinstallment").show();
			$("#grantinstallmentdate").show();
			$("#grants .form-control").each(function () {
				$(this).attr("required", "required");
				$(this).val("");
			});
			$("#ppgeneral").hide();
			$("#ppgeneral .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});
			$("#ppprogram").show();
			$("#ppprogram").attr("required", "required");
		} 
		else if (fundtype == 4) {
			$("#ppprogram").show();
			$("#ppprogram").attr("required", "required");
			$("#ppgeneral").hide();
			$("#ppgeneral .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});
			grants_hide();
		} 
		else if (fundtype == 1 || fundtype == 2) {
			$("#ppgeneral").show();
			$("#ppgeneral").attr("required", "required");
			$("#ppprogram").hide();
			$("#ppprogram .form-control").each(function () {
				$(this).removeAttr("required");
				$(this).val("");
			});
			grants_hide();
		}
		
        $.ajax({
            type: 'POST',
            url: 'assets/processor/funding-details-processor',
            data: 'fundtype=' + fundtype,
            success: function (html) {
                $('#financier').html(html);
            }
        });
    } else {
        $('#financier').html('<option value="">Select Funding Type First</option>');
    }
}

function grants_hide() {
	$("#grantperiod").hide();
	$("#grantinstallment").hide();
	$("#grantinstallmentdate").hide();
    $("#grants").hide();
    $("#grants .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
}

function purpose_hide() {
    $("#ppgeneral").hide();
    $("#ppgeneral .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
    $("#ppprogram").hide();
    $("#ppprogram .form-control").each(function () {
        $(this).removeAttr("required");
        $(this).val("");
    });
}

// remove Contractor Nationality
function removeItem(itemId = null) {
  if (itemId) {
	  console.log(itemId);
    // remove Contractor Nationality button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "assets/processor/funding-details-processor",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage Contractor Nationality table
              //manageItemTable.ajax.reload(null, true);

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