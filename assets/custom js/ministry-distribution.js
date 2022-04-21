var manageItemTable;

$(document).ready(function() {
	// top nav bar 
	var fndid = $("#fndid").val();
	$('#navProduct').addClass('active');
	// manage product data table
	manageItemTable = $('#manageItemTable').DataTable({
		"paging": false,
		"bFilter": false, //hide Search bar
		'ajax': 'assets/processor/fetch-ministry-distribution-items.php?fnd=' + fndid,
		'order': []
	});	
});

function editItem(itemId = null) {

	if(itemId) {
		console.log(itemId);
		$("#itemId").remove();		
		// remove text-error 
		$(".text-danger").remove();
		// remove from-group error
		$(".form-input").removeClass('has-error').removeClass('has-success');
		// modal div
		$('.div-result').addClass('div-hide');

		$.ajax({
			url: 'general-settings/fetch-selected-item.php',
			type: 'post',
			data: {itemId: itemId},
			dataType: 'json',
			success:function(response) {		
			// alert(response.product_image);
				// modal div
				$('.div-result').removeClass('div-hide');				

				// product id 
				$(".editItemFooter").append('<input type="hidden" name="itemId" id="itemId" value="'+response.id+'" />');							
				
				// product name
				$("#editType").val(response.type);
				// quantity
				$("#editDescription").val(response.description);
				// status
				$("#editStatus").val(response.active);

				// update the product data function
				$("#editItemForm").unbind('submit').bind('submit', function() {

					// form validation
					var type = $("#editType").val();
					var description = $("#editDescription").val();
					var itemStatus = $("#editStatus").val();
								

					if(type == "") {
						$("#editType").after('<p class="text-danger">Evaluation Type field is required</p>');
						$('#editType').closest('.form-input').addClass('has-error');
					}	else {
						// remov error text field
						$("#editType").find('.text-danger').remove();
						// success out for form 
						$("#editType").closest('.form-input').addClass('has-success');	  	
					}	// /else

					if(description == "") {
						$("#editDescription").after('<p class="text-danger">Evaluation Type Description field is required</p>');
						$('#editDescription').closest('.form-input').addClass('has-error');
					}	else {
						// remov error text field
						$("#editDescription").find('.text-danger').remove();
						// success out for form 
						$("#editDescription").closest('.form-input').addClass('has-success');	  	
					}	// /else
						
					if(itemStatus == "") {
						$("#editStatus").after('<p class="text-danger">Status field is required</p>');
						$('#editStatus').closest('.form-input').addClass('has-error');
					}	else {
						// remov error text field
						$("#editStatus").find('.text-danger').remove();
						// success out for form 
						$("#editStatus").closest('.form-input').addClass('has-success');	  	
					}	// /else					

					if(type && description && itemStatus) {

						var form = $(this);
						var formData = new FormData(this);

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: formData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							success:function(response) {
								console.log(response.messages);

								if(response) {
									// submit loading button
									$("#editProductBtn").button('reset');

									// reload the manage student table
									manageItemTable.ajax.reload(null, true);

									alert(response.messages);
									$('.modal').each(function(){
										$(this).modal('hide');
									});
									
								} // /success function
							} // /success function
						}); // /ajax function
					}	 // /if validation is ok 					

					return false;
				}); // update the product data function

			} // /success function
		}); // /ajax to fetch product image

				
	} else {
		alert('error please refresh the page');
	}
} // /edit product function

//function to put commas to the data
function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

// sweet alert notifications
function sweet_alert(err, msg) {
  return swal({
    title: err,
    text: msg,
    type: "error",
	icon: 'warning',
	dangerMode: true,
    timer: 15000,
    showConfirmButton: false
  });
  setTimeout(function() {}, 15000);
}
// sweet alert notifications
function sweet_alert_success(succ, msg) {
  return swal({
    title: succ,
    text: msg,
    type: "success",
	icon: 'success',
	dangerMode: false,
    timer: 15000,
    showConfirmButton: false
  });
  setTimeout(function() {}, 15000);
}

// function to calculate remaining budget
function ouput_used_cost() {
  var funding_amount = parseInt($("#funding_amount").val());
  var projbudget = parseInt($("#contributed_amount").val());

  if (funding_amount) {
    var used_budget = 0;
    $(".allocated_amount").each(function () {
      if ($(this).val() != "") {
        used_budget = used_budget + parseInt($(this).val());
      }
    });

    var summarytotal = 0;
    $(".summarytotal").each(function () {
      if ($(this).val() != "") {
        summarytotal = summarytotal + parseInt($(this).val());
      }
    });

    var remaining = funding_amount - used_budget; //calculate remaining output budget
    var summary_percentage = (summarytotal / projbudget) * 100;

    if (remaining >= 0) {
      $(".output_cost_bal").val(commaSeparateNumber(remaining));
      $("#summaryOutput").html(commaSeparateNumber(used_budget));
      $("#summary_total").html(commaSeparateNumber(summarytotal) + '<input type="hidden" name="totalcost" id="totalcost" value="' + summarytotal + '">');
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return true;
    } else {
      $(".output_cost_bal").val(commaSeparateNumber(remaining));
      $("#summaryOutput").html(commaSeparateNumber(used_budget));
      $("#summary_total").html(commaSeparateNumber(summarytotal) + '<input type="hidden" name="totalcost" id="totalcost" value="' + summarytotal + '">');
      $("#summary_percentage").html(summary_percentage.toFixed(2) + "%");
      return false;
    }
  }
}

function cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid) {
  var output_budget = parseFloat($("#funding_amount").val());
  var sub_total_val = 0;
  $(sub_total_class).each(function () {
    if ($(this).val() != "") {
      sub_total_val = sub_total_val + parseFloat($(this).val());
    }
  });
  var sub_total_percentage = (sub_total_val / output_budget) * 100; //calculate percentage of the given div
  $(sub_total_aid).val(commaSeparateNumber(sub_total_val));
  $(sub_total_pid).val(sub_total_percentage.toFixed(2) + "%");
  $("#percrate").html(sub_total_percentage.toFixed(2) + "%");
}

// function  to calculate the total cost of a task
function totalCost(rowno) {
  var sub_total_pid = "#sub_total_percentage";
  var sub_total_aid = "#sub_total_amount";
  var sub_total_class = ".direct_sub_total_amount";
  var total_cost_id = "#allocation" + rowno;

  var unitCost = parseInt($(total_cost_id).val());

    if (unitCost > 0) {
      var taskCost = unitCost;
      $(total_cost_id).val(taskCost);
      var total_output_Cost = ouput_used_cost();
      if (total_output_Cost) {
        var total_output_Cost = ouput_used_cost();
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid);
      } else {
        var msg =
          "Ensure the amount does not exceed total fund";
        sweet_alert("Error !!!", msg);
        $(total_cost_id).val("");
        var total_output_Cost = ouput_used_cost();
        cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid);
      }
    } else {
      $(total_cost_id).val("");
      var total_output_Cost = ouput_used_cost();
      cal_sub_total(sub_total_class, sub_total_aid, sub_total_pid);
    }
}

// to check internet connection
function checkNetConnection() {
  jQuery.ajaxSetup({ async: false });
  re = "";
  r = Math.round(Math.random() * 10000);
  $.get(
    "https://1.bp.blogspot.com/-LtDtdVE1roA/UmAavs_T_iI/AAAAAAAADNY/g0L-HAPlkTY/s1600/0060.png", //logo
    { subins: r },
    function(d) {
      re = true; 
    }
  ).error(function() {
    sweet_alert("You have lost Internet Connection");
    re = false;
  });
  return re;
}

setInterval(function() {
  var conn = checkNetConnection();
  if (conn) {
    $("#tag-form-submit").removeClass("btn-danger");
    $("#tag-form-submit").addClass("btn-primary");
  } else {
    $("#tag-form-submit").removeClass("btn-primary");
    $("#tag-form-submit").addClass("btn-danger");
  }
}, 3000);

$("#tag-form-submit").click(function(e) {
  e.preventDefault();
  var internet_handler = checkNetConnection();

  if (internet_handler) {
    $("#form").validate({
      ignore: [],
      errorPlacement: function(error, element) {
        var lastError = $(element).data("lastError"),
          newError = $(error).text();

        $(element).data("lastError", newError);

        if (newError !== "" && newError !== lastError) {
          $(element).after('<div class="red">The field is Required</div>');
        }
      },
      success: function(label, element) {
        $(element)
          .next(".red")
          .remove();
      }
    });
    var isValid = true;
    var curInputs = $("#form").find("input, select");
    for (var i = 0; i < curInputs.length; i++) {
      if (!$(curInputs[i]).valid()) {
        isValid = false;
      }
    }
    if (isValid) {
      var formData = $("#form").serialize();
      $.ajax({
        type: "post",
        url: "assets/processor/departmental-funds-distribution-processor.php",
        data: formData,
        dataType: "json",
        success: function(response) {
          sweet_alert_success("Success", response);
          window.location.href = "funding";
        }
      });
    }
  }
});

$(".careted").click(function(e) {
  e.preventDefault();
  $(this)
    .find("i")
    .toggleClass("fa fa-caret-down fa fa-caret-up");
});

// function disable refreshing functionality
function disable_refresh() {
  //
  return (window.onbeforeunload = function(e) {
    return "you can not refresh the page";
  });
}