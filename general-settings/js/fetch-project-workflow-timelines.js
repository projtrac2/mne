var manageItemTable;

$(document).ready(function() {
  $("#navtitle").addClass("active");  
    manageItemTable = $("#manageItemTable").DataTable({
    ajax: "general-settings/selected-items/fetch-selected-project-workflow-timelines-items",
    order: [], 
    'columnDefs': [{
      'targets': [8],
      'orderable': false,
    }]
  });

  // submit title form
  //$("#submitItemForm").unbind('submit').bind('submit', function() {
  $("#submitItemForm").on("submit", function(event) {
    event.preventDefault();
    var form_data = $(this).serialize();

    // form validation
    var category = $("#category").val();
    var stage = $("#stage").val();
    var statusname = $("#statusname").val();
    var description = $("#description").val();
    var time = $("#time").val();
    var units = $("#units").val();
    var newitem = $("#newitem").val();

    if (category == "") {
      $("#category").after(
        '<p class="text-danger">Project workflow timeline category is required</p>'
      );
      $("#category")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#category")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#category")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (stage == "") {
      $("#stage").after(
        '<p class="text-danger">Project workflow timeline stage  is required</p>'
      );
      $("#stage")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#stage")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#stage")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (statusname == "") {
      $("#statusname").after(
        '<p class="text-danger">Project workflow timeline status name is required</p>'
      );
      $("#statusname")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#statusname")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#statusname")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (description == "") {
      $("#description").after(
        '<p class="text-danger">Project workflow timeline Description field is required</p>'
      );
      $("#description")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#description")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#description")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (time == "") {
      $("#time").after(
        '<p class="text-danger">Project workflow timeline time is required</p>'
      );
      $("#time")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#time")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#time")
        .closest(".form-input")
        .addClass("has-success");
    } 


    if (units == "") {
      $("#units").after(
        '<p class="text-danger">Project workflow timeline units is required</p>'
      );
      $("#units")
        .closest(".form-input")
        .addClass("has-error");
    } else {
      // remov error text field
      $("#units")
        .find(".text-danger")
        .remove();
      // success out for form
      $("#units")
        .closest(".form-input")
        .addClass("has-success");
    } 

    if (category && stage && statusname && description && time && units) {
      var form = $(this);
      var formData = new FormData(this);

      $.ajax({
        url: "general-settings/action/project-workflow-stage-timelines-action",
        type: form.attr("method"),
        data: form_data,
        dataType: "json",
        success: function(response) {
          if (response) {
            $("#submitItemForm")[0].reset();
            // reload the titles table 
            manageItemTable.ajax.reload(null, true);
            alert("Record Successfully Saved");
            $(".modal").each(function() {
              $(this).modal("hide");
            });
          } // /if response.success
        } // /success function
      }); // /ajax function
    } // /if validation is ok
    // /if validation is ok

    return false;
  }); // /submit title form

  // add title modal btn clicked
  $("#addItemModalBtn")
    .unbind("click")
    .bind("click", function() {
      // // title form reset
      $("#submitItemForm")[0].reset();

      // remove text-error
      $(".text-danger").remove();
      // remove from-group error
      $(".form-input")
        .removeClass("has-error")
        .removeClass("has-success");
    }); // /add title modal btn clicked

  // remove title
}); // document.ready fucntion

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
      url: "general-settings/selected-items/fetch-selected-project-workflow-timelines-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "json",
      success: function(response) {
        $(".div-result").removeClass("div-hide");

        // title id
        $(".editItemFooter").append(
          '<input type="hidden" name="itemId" id="itemId" value="' +
            response.id +
            '" />'
        );

        // title name
        $("#editworkflow").val(response.workflow);
        $("#editcategory").val(response.category);
        $("#editstage").val(response.stage);
        $("#editstatusname").val(response.status);
        $("#editdescription").val(response.description);
        $("#edittime").val(response.time);
        $("#editescalateto").val(response.escalate_to);
        $("#editunits").val(response.units);
        $("#editescalateafter").val(response.escalate_after);
        $("#editStatus").val(response.active);

        // update the title data function
        $("#editItemForm")
          .unbind("submit")
          .bind("submit", function(e) {
            e.preventDefault();
        // form validation
        // form validation
        var category = $("#editcategory").val();
        var stage = $("#editstage").val();
        var statusname = $("#editstatusname").val();
        var description = $("#editdescription").val();
        var time = $("#edittime").val();
        var units = $("#editunits").val();
        var itemStatus = $("#editStatus").val();

		if (category == "") {
		  $("#editcategory").after(
			'<p class="text-danger">Project workflow timeline category is required</p>'
		  );
		  $("#editcategory")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editcategory")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editcategory")
			.closest(".form-input")
			.addClass("has-success");
		} 


		if (stage == "") {
		  $("#editstage").after(
			'<p class="text-danger">Project workflow timeline stage is required</p>'
		  );
		  $("#editstage")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editstage")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editstage")
			.closest(".form-input")
			.addClass("has-success");
		} 


		if (statusname == "") {
		  $("#editstatusname").after(
			'<p class="text-danger">Project workflow timeline status name is required</p>'
		  );
		  $("#editstatusname")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editstatusname")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editstatusname")
			.closest(".form-input")
			.addClass("has-success");
		} 


		if (description == "") {
		  $("#editdescription").after(
			'<p class="text-danger">Project workflow timeline Description is required</p>'
		  );
		  $("#editdescription")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editdescription")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editdescription")
			.closest(".form-input")
			.addClass("has-success");
		} 


		if (time == "") {
		  $("#edittime").after(
			'<p class="text-danger">Project workflow timeline time is required</p>'
		  );
		  $("#edittime")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#edittime")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#edittime")
			.closest(".form-input")
			.addClass("has-success");
		} 


		if (units == "") {
		  $("#editunits").after(
			'<p class="text-danger">Project workflow timeline units is required</p>'
		  );
		  $("#editunits")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editunits")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editunits")
			.closest(".form-input")
			.addClass("has-success");
		} 

		if (itemStatus == "") {
		  $("#editStatus").after(
			'<p class="text-danger">Status field is required</p>'
		  );
		  $("#editStatus")
			.closest(".form-input")
			.addClass("has-error");
		} else {
		  // remov error text field
		  $("#editStatus")
			.find(".text-danger")
			.remove();
		  // success out for form
		  $("#editStatus")
			.closest(".form-input")
			.addClass("has-success");
		} // /else

		if (category && stage && statusname && description && time && units && itemStatus) {
              var form = $(this);
              var formData = new FormData(this);
              $.ajax({
                url: "general-settings/action/project-workflow-stage-timelines-action",
                type: "post",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response) {
                    // submit loading button
                    $("#edittitleBtn").button("reset");
                    // reload the manage student table
                    manageItemTable.ajax.reload(null, true);
                    alert(response.messages);
                    $(".modal").each(function() {
                      $(this).modal("hide");
                    });
                  } // /success function
                } // /success function
              }); // /ajax function
            } // /if validation is ok

            return false;
          }); // update the title data function
      } // /success function
    }); // /ajax to fetch title image
  } else {
    alert("error please refresh the page");
  }
} // /edit title function

// remove title
function removeItem(itemId = null) {
  if (itemId) {
    // remove title button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-workflow-stage-timelines-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage student table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the title
        return false;
      }); // /remove title btn clicked
  } // /if titleid
} // /remove title function

function clearForm(oForm) {
  // var frm_elements = oForm.elements;
  // for(i=0;i<frm_elements.length;i++) {
  // field_type = frm_elements[i].type.toLowerCase();
  // switch (field_type) {
  //    case "text":
  //    case "password":
  //    case "textarea":
  //    case "hidden":
  //    case "select-one":
  //      frm_elements[i].value = "";
  //      break;
  //    case "radio":
  //    case "checkbox":
  //      if (frm_elements[i].checked)
  //      {
  //          frm_elements[i].checked = false;
  //      }
  //      break;
  //    case "file":
  //     if(frm_elements[i].options) {
  //     frm_elements[i].options= false;
  //     }
  //    default:
  //        break;
  //     } // /switch
  // } // for
}
