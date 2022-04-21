
var process_url = "ajax/inspection/assign";
$(document).ready(function () {
   $(".projects td").click(function (e) {
      e.preventDefault();
      $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
   });

   $("#tag-form-submit").click(function (e) {
      e.preventDefault();
      var formData = $("#submitMilestoneForm").serialize();
      $("#tag-form-submit").prop("disabled", true);
      $.ajax({
         type: "POST",
         url: process_url,
         data: formData,
         dataType: "json",
         success: function (response) {
            $("#tag-form-submit").prop("disabled", false);
            if (response.success == true) {
               alert(response.messages);
               $(".modal").each(function () {
                  $(this).modal("hide");
               });
               location.reload(true);
            } else {
               alert(response.messages);
               location.reload(true);
            }
         },
      });
   });

});

function add(projid, opid) {
   if (opid && projid) {
      $.ajax({
         type: "post",
         url: process_url,
         data: {
            opid: opid,
            projid: projid,
            add: "add",
         },
         dataType: "html",
         success: function (response) {
            $("#assign_table_body").html(response);
            $(".selectpicker").selectpicker("refresh");
            $("#newitem").val("newitem");
            $("#newitem").attr("name", "newitem");
         },
      });
   }
}

function edit(projid, opid, dissagragated) {
   if (opid && projid) {
      $.ajax({
         type: "post",
         url: process_url,
         data: {
            opid: opid,
            projid: projid,
            edit: "edit",
         },
         dataType: "html",
         success: function (response) {
            $("#assign_table_body").html(response);
            $(".selectpicker").selectpicker("refresh");
            $("#newitem").val("edititem");
            $("#newitem").attr("name", "edititem");
         },
      });
   }
} 

// sweet alert notifications
function sweet_alert(err, msg) {
   return swal({
      title: err,
      text: msg,
      type: "Error",
      timer: 5000,
      showConfirmButton: false,
   });
   setTimeout(function () { }, 2000);
}

function more(projid, opid) {
   if (projid && opid) {
      $.ajax({
         url: process_url,
         type: "post",
         data: {
            projid: projid,
            opid: opid,
            get_more: "get_more",
         },
         dataType: "html",
         success: function (response) {
            $("#moreinfo").html(response);
         },
      });
   } else {
      alert("Error please refresh the page");
   }
}

function more_info(mapid) {
   if (mapid) {
      $.ajax({
         url: process_url,
         type: "get",
         data: {
            mapid: mapid,
            more: "more",
         },
         dataType: "html",
         success: function (response) {
            $("#moreinfo").html(response);
         },
      });
   } else {
      alert("Error please refresh the page");
   }
}

