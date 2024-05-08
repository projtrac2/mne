const program_ajax_url = 'ajax/programs/more';
const program_info_url = 'ajax/programs/info';
const program_count_url = 'ajax/programs/index';
const program_targets_url = "ajax/programs/targets";
$(document).ready(function () {
  get_total_programs();// submit program quarterly targets
  $("#quarterlyTargetsForm").submit(function (e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      type: "post",
      url: program_targets_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          success_alert(response.messages);
          $(".modal").each(function () {
            $(this).modal("hide");
          });
        }

        setTimeout(() => {
          window.location.reload();
          // manageItemTable.ajax.reload(null, true);
        }, 3000);
      }
    });
  });
});

function program_info(progid, strategic_plan_id) {
  if (progid != '' && strategic_plan_id != '') {
    $.ajax({
      type: "get",
      url: program_info_url,
      data: {
        progid: progid,
        strategic_plan_id: strategic_plan_id,
        program_info: "program_info"
      },
      dataType: "html",
      success: function (response) {
        $("#progmoreinfo").html(response);
      },
    });
  } else {
    error_alert("error please refresh the page");
  }
}

function get_total_programs() {
  $.ajax({
    type: "get",
    url: program_count_url,
    data: {
      get_total_number_of_programs: "indipendent",
      type: 2
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#total-programs").html(response.programs);
      }
    }
  });
}

function destroy_program(progid) {
  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this program data!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          type: "post",
          url: program_ajax_url,
          data: {
            progid: progid,
            deleteItem: 'deleteItem'
          },
          dataType: "json",
          success: function (response) {
            if (response.success == true) {
              swal({
                title: "Programs !",
                text: response.messages,
                icon: "success",
              });
            } else {
              swal({
                title: "Programs !",
                text: response.messages,
                icon: "error",
              });
            }
            setTimeout(function () {
              window.location.reload(true);
            }, 3000);
          },
        });
      } else {
        swal("You canceled the action!");
      }
    });
}

// get the program budget/target div from db
function add_independent_quarterly_targets(details) {
  if (details.progid) {
    $.ajax({
      type: "post",
      url: program_targets_url,
      data: {
        create_independent_qtargets_div: "create_independent_qtargets_div",
        progid: details.progid,
        edit: details.edit,
      },
      dataType: "html",
      success: function (response) {
        $("#quarterlyTargetsBody").html(response);
      }
    });
  }
}

// view the program budget/target
function viewPBB(progid = null, adpyr = null) {
  if (progid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/adp-edit-action",
      data: {
        view_independent_qtargets_div: "view_independent_qtargets_div",
        progid: progid,
        adpyr: adpyr
      },
      dataType: "html",
      success: function (response) {
        $("#viewQTargetsBody").html(response);
      }
    });
  }
}