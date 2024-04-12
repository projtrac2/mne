const url = "ajax/strategicplan/strategic-objectives";

$(document).ready(function () {			
	$(".objid").hide();
});

function objective_kpi(objid) {
	var clicked = $("#clicked").val();
	if ( clicked == 0 ){
		$("." + objid).show();
		clicks = clicked + 1;
	}else{
		$("." + objid).hide();
		clicks = clicked - 1;
	}
	
	$('#clicked').val(clicks);
};

// fetch more of  strategy
function more(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    $(".text-danger").remove();
    $(".form-input").removeClass("has-error").removeClass("has-success");
    $(".div-result").addClass("div-hide");
    $.ajax({
      url: url,
      type: "post",
      data: { more: "more", itemId: itemId },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
        $("#moreInfo").DataTable({});
      },
    });
  } else {
    swal({
      title: "Strategic Objective !",
      text: "Error!!!",
      icon: "error",
    }); 
  }
}

// /get info for objectives
function moreInfo(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    $(".text-danger").remove();
    $(".form-input").removeClass("has-error").removeClass("has-success");
    $(".div-result").addClass("div-hide");
    $.ajax({
      url: url,
      type: "post",
      data: { moreInfo: "more", itemId: itemId },
      dataType: "html",
      success: function (response) {
        $("#moreinformation").html(response);
        $("#moreInformations").DataTable({});
      },
    });
  } else {
    swal({
      title: "Strategic Objective !",
      text: "Error!!!",
      icon: "error",
    }); 
  }
}

// /get info for objectives
function addstrategy(itemId = null) {
  if (itemId) {
    var itmid = itemId;
    $(".strat").prepend(
      '<input type="hidden" name="objid" value="' + itmid + '">'
    );
  } else { 
    swal({
      title: "Strategic Objective !",
      text: "Error!!!",
      icon: "error",
    }); 
  }
}

function removeItem(itemId = null) {
  if (itemId) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: url,
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              swal({
                title: "Strategic Objective !",
                text: response.messages,
                icon: "success",
              }); 
              setTimeout(function () { window.location.reload(true);}, 3000); 
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              swal({
                title: "Strategic Objective !",
                text: response.messages,
                icon: "success",
              }); 
              setTimeout(function () { window.location.reload(true);}, 3000); 
            } // /error
          }, // /success function
        }); // /ajax fucntion to remove the Contractor Nationality
        return false;
      }); // /remove Contractor Nationality btn clicked
  } // /if Contractor Nationalityid
} // /remove Contractor Nationality function

function removeStrategy(itemId = null) {
  if (itemId) {
    $("#removeStrategyBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: url,
          type: "post",
          data: { strategyid: itemId, deleteStrategy: "deleteStrategy" },
          dataType: "json",
          success: function (response) {
            $("#removeStrategyBtn").button("reset");
            if (response.success == true) {
              $(".modal").each(function () {
                $(this).modal("hide");
              });
              swal({
                title: "Strategic Objective !",
                text: response.messages,
                icon: "success",
              });              
              setTimeout(function () { window.location.reload(true);}, 3000);              
            } else {
              swal({
                title: "Strategic Objective !",
                text: response.messages,
                icon: "success",
              }); 
              setTimeout(function () { window.location.reload(true);}, 3000); 
            }
          },
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
    $(".form-input").removeClass("has-error").removeClass("has-success");
    // modal div
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "fetch-selected-stratplan-objectives-item",
      type: "post",
      data: { edit: "edit", itemId: itemId },
      dataType: "html",
      success: function (response) {
        $("#result").html(response);
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function (e) {
            e.preventDefault();
            $("#tag-form-submit").prop("disabled", true);
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
              success: function (response) {
                if (response) { 
                  $("#edittitleBtn").button("reset");

                  $(".modal").each(function () {
                    $(this).modal("hide");
                  });

                  swal({
                    title: "Strategic Objective !",
                    text: response.messages,
                    icon: "success",
                  });
                  setTimeout(function () { window.location.reload(true);}, 3000);                
                }
              },
            });
          });
      },
    });
  } else {
    swal({
      title: "Strategic Objective !",
      text: "error please refresh the page",
      icon: "success",
    });
    setTimeout(function () { window.location.reload(true);}, 3000); 
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

$(document).ready(function () {
  $("#addstrategyForm").on("submit", function (event) {
    event.preventDefault();
    $("#tag-form-submit").prop("disabled", true);

    var form_info = $(this).serialize(); 
    $.ajax({
      type: "POST",
      url: url,
      data: form_info,
      dataType: "json",
      success: function (response) {  
        // loading remove button
        //$("#addStrategyBtn").button("reset");
        if (response.success == true) {
          swal({
            title: "Strategic Objective !",
            text: response.messages,
            icon: "success",
          });
          $(".modal").each(function () {
            $(this).modal("hide");
          });
          setTimeout(function () { window.location.reload(true);}, 3000);
        } else {
          swal({
            title: "Strategic Objective !",
            text: response.messages,
            icon: "error",
          });
          $(".modal").each(function () {
            $(this).modal("hide");
          });
          setTimeout(function () { window.location.reload(true);}, 3000);
        } // /error
      },
      error: function () {
        swal({
          title: "Strategic Objective !",
          text: "Error!!!",
          icon: "error",
        });
        setTimeout(function () { window.location.reload(true);}, 3000);
        $(".modal").each(function () {
          $(this).modal("hide");
        });
      },
    });
    return false;
  });
});


//edit register risk
function addkpi(objid,objective) {
	$('#objid').val(objid);
	$('#objective').html(objective);
	/* if (objid != '' || objid != null) {
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
	} */
};

function kpi_more_info(kpi_id) {
	$.ajax({
		type: 'get',
		url: url,
		data: {
			get_kpi_more_info: 1,
			kpi_id: kpi_id
		},
		dataType: "json",
		success: function(data) {
			$('#kpi_details').html(data.kpi_more_details_body);
		}
	});
}