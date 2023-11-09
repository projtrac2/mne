const url1 = "assets/processor/indicator-existing-baseline-data-processor";


$(document).ready(function () {
  $("#indicator_baseline_form").submit(function (e) {
    e.preventDefault();
    $("#tag-form-base-submit").prop("disabled", false);
    $.ajax({
      type: "POST",
      url: url1,
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          success_alert(response.messages);
          $(".modal").each(function () {
            $(this).modal("hide");
          });
        } else {
          error_alert(response.messages);
        }
        $(".base_value_type").val('1');
        get_filter();
        check_val();
      },
    });
  });
  check_val();
  $('#bank_table').DataTable();
});


function check_val() {
  var base_value_type = $('.base_value_type').val();
  $("#submit_div").hide();
  if (base_value_type == "1") {
    $("#submit_div").show();
  }
}
// Non disaggragated
function get_add_inddiss_inputs(lve3id, value) {
  var level3 = $(`#level2${lve3id}`).val();
  $("#level3").val(lve3id);
  $("#locationName").html(level3);
  $("#base_val_id").val(value);
}


$(document).ready(function () {
  $('#subcounty').on('change', function () {
    var scID = $(this).val();
    var indid = $("#indicator_id").val();
    get_wards(scID);
    filter_by_subcounty(scID, indid)
  });

  $('#ward').on('change', function () {
    var wardID = $(this).val();
    var scID = $("#subcounty").val();
    var indid = $("#indicator_id").val();
    filter_by_ward(scID, wardID, indid);
  });
});


function get_wards(scID) {
  if (scID.length > 0) {
    $.ajax({
      type: 'POST',
      url: 'indicator-existing-baseline-data-filter',
      data: 'getward=' + scID,
      success: function (html) {
        $('#ward').html(html);
        $('#loc').html('<option value="">Select Ward first</option>');
        $(".selectpicker").selectpicker("refresh");
      }
    });
  } else {
    $('#ward').html('<option value="">Select Sub-County first</option>');
    $('#loc').html('<option value="">Select Ward first</option>');
    $(".selectpicker").selectpicker("refresh");
  }
}


function get_filter() {
  var level1 = $("#subcounty").val();
  var level2 = $("#ward").val();
  var indid = $("#indicator_id").val();
  get_baseline(indid);
  if (level1.length > 0) {
    if (level2.length > 0) {
      filter_by_ward(level1, level2, indid);
    } else {
      filter_by_subcounty(level1, indid);
    }
  } else {
    filter_by_all(indid);
  }
}

function filter_by_all(indid) {
  if (indid) {
    $.ajax({
      type: 'POST',
      url: 'indicator-existing-baseline-data-filter',
      data: {
        get_all: "get_all",
        indid: indid
      },
      dataType: 'json',
      success: function (html) {
        $('#scbody').html(html.body);
        $('#base_val').html(html.total_amount);
      }
    });
  } else {
    console.log("There is an error");
  }
}

function filter_by_subcounty(scID, indid) {
  if (scID.length > 0) {
    $.ajax({
      type: 'POST',
      url: 'indicator-existing-baseline-data-filter',
      data: {
        subcountydata: scID,
        indid: indid
      },
      dataType: 'json',
      success: function (html) {
        $('#scbody').html(html.body);
        $('#base_val').html(html.total_amount);
      }
    });
  } else {
    get_filter();
  }
}

function filter_by_ward(scID, wardID, indid) {
  if (wardID.length > 0) {
    $.ajax({
      type: 'POST',
      url: 'indicator-existing-baseline-data-filter',
      data: {
        warddata: wardID,
        subcounty_id: scID,
        indid: indid
      },
      dataType: 'json',
      success: function (html) {
        $('#scbody').html(html.body);
        $('#base_val').html(html.total_amount);
      }
    });
  } else {
    get_filter();
  }
}

function get_baseline(indid) {
  $.ajax({
    type: 'POST',
    url: 'indicator-existing-baseline-data-filter',
    data: {
      get_baseline_ind: indid,
    },
    success: function (html) {
      $('#base_val').html(html);
    }
  });
}



function delete_baseline(ward_id) {
  var indicator_id = $("#indicator_id").val();
  var ward = $(`#level2${ward_id}`).val();
  swal({
    title: "Are you sure?",
    text: `You want to delete baseline for  ${ward} ward !`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          type: "post",
          url: url1,
          data: {
            delete_baseline: 'delete_baseline',
            indicator_id: indicator_id,
            ward_id: ward_id,
          },
          dataType: "json",
          success: function (response) {
            if (response.success == true) {
              swal({
                title: "Indicator baseline !",
                text: "Successfully deleted",
                icon: "success",
              });
              $('.base_value_type').val(response.messages)
            } else {
              swal({
                title: "Indicator baseline !",
                text: "Error deleting baseline",
                icon: "error",
              });
            }

            get_filter();
            check_val();
          },
        });
      } else {
        swal("You cancelled the action!");
      }
    });
}