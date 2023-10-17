////////////////
// Finish page
////////////////
var ajax_url = "ajax/projects/index";

$(document).ready(function () {
  show_dissaggregation(0);
  //filter the expected output  cannot be selected twice
  $(document).on("change", ".select_output", function (e) {
    var tralse = true;
    var selectOutcome_arr = []; // for contestant name
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".select_output").each(function (k, v) {
      var getVal = $(v).val();
      if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
        tralse = false;
        error_alert(
          "You canot select Output " + selectedText + " more than once"
        );
        var rw = $(v).attr("data-id");
        var outcomeindicator = "#outcomeindicatorrow" + rw; // outcome  indicator id
        $(v).val("");
        $(outcomeindicator).val("");
        return false;
      } else {
        selectOutcome_arr.push($(v).val());
      }
    });
    if (!tralse) {
      return false;
    }
  });

  $(document).on("change", ".output_location_select", function (e) {
    var tralse = true;
    var selectOutcome_arr = []; // for contestant name
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".output_location_select").each(function (k, v) {
      var getVal = $(v).val();
      if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
        tralse = false;
        $(v).val("");
        error_alert("You canot select location " + selectedText + " more than once");
        return false;
      } else {
        selectOutcome_arr.push($(v).val());
      }
    });
    if (!tralse) {
      return false;
    }
  });

  $(document).on("change", ".output_site_select", function (e) {
    var tralse = true;
    var selectOutcome_arr = []; // for contestant name
    var attrb = $(this).attr("id");
    var selectedid = "#" + attrb;
    var selectedText = $(selectedid + " option:selected").html();

    $(".output_site_select").each(function (k, v) {
      var getVal = $(v).val();
      if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
        tralse = false;
        error_alert("You canot select site " + selectedText + " more than once");
        $(v).val("");
        return false;
      } else {
        selectOutcome_arr.push($(v).val());
      }
    });
    if (!tralse) {
      return false;
    }
  });


  $("#add_output").submit(function (e) {
    e.preventDefault();
    var submit_handler = validate_submit();
    if (submit_handler) {
      $.ajax({
        type: "post",
        url: ajax_url,
        data: $(this).serialize(),
        dataType: "json",
        success: function (response) {
          var rowno = $("#rowno").val();
          if (response.success) {
            success_alert("Record saved successfully");
            $("#outputIdsTruerow1").val(response.output_id);
            $(`#output_result_id_${rowno}`).val(response.output_id);
            $(`#output${rowno}`).attr("disabled", "disabled");
          } else {
            error_alert("Record could not be saved successfully");
          }
          $(`#outputItemModalBtn${rowno}`).html("Edit Details");
          reset_output_modal();
        },
      });
    } else {
      error_alert("Ensure you have distributed the target defined");
    }
  });
});

function get_option_one() {
  var target_distribution = false;
  var output_target = parseFloat($("#project_target").val());
  if (output_target > 0) {
    var units = 0;
    $(".units").each(function () {
      var val = $(this).val();
      units += (val != "") ? parseFloat(val) : 0;
    });
    target_distribution = (output_target == units) ? true : false;
  }
  return target_distribution ? true : false;
}

function validate_submit() {
  var target_value = false;
  var mapping_type = $("#mapping_type").val();
  if (mapping_type == "1") {
    var unit_type = $("#unit_type").val();
    if (unit_type == "1") {
      target_value = get_option_one();
    } else {
      target_value = unit_type != "" ? true : false;
    }
  } else {
    target_value = get_option_one();
  }
  return target_value;
}

function add_row_output() {
  $("#hideinfo").remove();
  var rowno = $("#output_table_body tr").length + 1;

  $("#output_table_body tr:last").after(`
    <tr id="row${rowno}">
        <td></td>
        <td>
        <select  data-id="${rowno}" name="output[]" id="outputrow${rowno}" onchange=getIndicator("row${rowno}")  class="form-control validoutcome select_output" required="required">
            <option value="">Select Output from list</option>
        </select>
        </td>
        <td>
            <span id="indicatorrow${rowno}"></span>
        </td>
        <td>
            <a type="button" data-toggle="modal"  data-target="#outputItemModal" onclick=get_output_details("row${rowno}") id="outputItemModalBtnrow${rowno}"> Add Details</a>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("row${rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
        <input type="hidden" name="outputIdsTrue[]" id="output_result_id_row${rowno}" value="" />
        <input type="hidden" name="output_id[]" id="output_id_row${rowno}" value="" />
        <input type="hidden" name="indicator[]" id="indicator_id_row${rowno}" placeholder="Enter" class="form-control" value="" />
    </tr>`);
  get_output(rowno);
  numbering_output();
}

function get_output(rowno) {
  var progid = $("#progid").val();
  if (progid != "" && projid != "") {
    $.ajax({
      type: "POST",
      url: ajax_url,
      data: {
        getprojoutput: "getprojoutput",
        progid: progid,
      },
      dataType: "html",
      success: function (html) {
        $("#outputrow" + rowno).html(html);
      },
    });
  }
}

// function to delete output output
function delete_row_output(rowno) {
  var outputIdsTrue = $("#output_result_id_" + rowno).val();
  if (outputIdsTrue != "") {
    var handler = confirm(
      "This change will delete the below plan. Would you like to proceed?"
    );
    if (handler) {
      $.ajax({
        type: "post",
        url: ajax_url,
        data: { deleteItem: "deleteItem", itemId: outputIdsTrue },
        dataType: "json",
        success: function (response) {
          if (response.success == true) {
            success_alert(response.messages);
            $("#" + rowno).remove();
            numbering_output();
            var number = $("#output_table_body tr").length;
            if (number == 1) {
              $("#output_table_body tr:last").after(
                '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
              );
            }
          } else {
            error_alert(response.messages);
          }
        },
      });
    }
  } else {
    $("#" + rowno).remove();
    numbering_output();
    $number = $("#output_table_body tr").length;
    if ($number == 1) {
      $("#output_table_body tr:last").after(
        '<tr id="hideinfo"><td colspan="5" align="center"> Add Output</td></tr>'
      );
    }
  }
}

// function too get indicator
function getIndicator(rowno) {
  var outputid = $("#output" + rowno).val();
  var progid = $("#progid").val();
  if (outputid) {
    $.ajax({
      type: "post",
      url: ajax_url,
      data: {
        getIndicator: outputid,
        indicator: outputid,
        progid: progid,
      },
      dataType: "json",
      success: function (response) {
        var outputid = $("#output" + rowno).val();
        $("#indicator" + rowno).html("");
        $("#indicator_id_" + rowno).val("");
        if (outputid != "") {
          $("#indicator" + rowno).html(response.indicator_name);
          $("#indicator_id_" + rowno).val(response.indid);
        }
      },
    });
  } else {
    alert("Select Indicator");
  }
}

// function to number output table
function numbering_output() {
  $("#output_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function reset_output_modal() {
  $("#one_point").hide();
  $("#distributed").hide();
  $("#waypoint").hide();
  $("#unit_type_div").hide();
  $("#ben_dissegragation").hide();
  $("#output_target_distribution_div").html("");

  $(".modal").each(function () {
    $(this).modal("hide");
    $(this).find("form").trigger("reset");
  });
}

function get_output_details(rowno) {
  reset_output_modal();
  var indicator_id = $(`#indicator_id_${rowno}`).val();
  var output_id = $(`#output${rowno}`).val();
  var progid = $("#progid").val();
  var projid = $("#projid").val();
  var key_unique = $("#key_unique").val();
  $("#output_id").val(output_id);
  $("#indicator_id").val(indicator_id);
  $("#rowno").val(rowno);
  if (indicator_id != "") {
    $.ajax({
      type: "get",
      url: ajax_url,
      data: {
        get_output_details: "get_output_details",
        indicator_id: indicator_id,
        progid: progid,
        unique_key: key_unique,
        projid: projid
      },
      dataType: "json",
      success: function (response) {
        var mapping_type = response.mapping_type;
        $("#mapping_type").val(mapping_type);

        if (mapping_type == 1) {
          $("#unit_type_div").show();
        }

        $("#program_target").val(response.program_target);

        var output_data = response.output_data;
        var output_details = response.output_details;
        $("#unit_type").val(output_data.unit_type);
        $("#project_target").val(output_data.total_target);
        if (response.dissaggregation_details.length > 0) {
          $("#dissaggregation1").prop("checked", true);
          show_dissaggregation(1, response.dissaggregation_details);
        } else {
          $("#dissaggregation2").prop("checked", true);
          show_dissaggregation(0, []);
        }
        output_target_div(output_details);
      },
    });
  } else {
    error_alert("Error!! Please select output");
  }
}

function output_target_div(output_details = []) {
  var unit_type = $("#unit_type").val();
  var mapping_type = $("#mapping_type").val();
  $("#output_target_distribution_div").html("");
  if (mapping_type == "1") {
    if (unit_type == "1") {
      combined_target_distribution(output_details);
    } else if (unit_type == "2") {
      distributed_target_distribution(output_details);
    }
  } else {
    waypoints_target_distribution(output_details, mapping_type);
  }
}

function get_locations(rowno, outputstate) {
  var projid = $("#projid").val();
  $.ajax({
    type: "get",
    url: ajax_url,
    data: {
      get_project_locations: "locations",
      projid: projid,
    },
    dataType: "html",
    success: function (response) {
      $(`#output_location${rowno}`).html(response);
      $(`#output_location${rowno}`).val(outputstate);
    },
  });
}

function get_project_sites(rowno, site_id) {
  var projid = $("#projid").val();
  $.ajax({
    type: "get",
    url: ajax_url,
    data: {
      get_project_sites: "sites",
      projid: projid,
    },
    dataType: "html",
    success: function (response) {
      $(`#site${rowno}`).html(response);
      $(`#site${rowno}`).val(site_id);
    },
  });
}

function waypoints_target_distribution(output_details = [], mapping_type) {
  var table_body = "";
  var val = $("#project_target").val();
  var output_target = val != "" ? parseFloat(val) : 0;
  if (output_target > 0) {
    var total_details = output_details.length;
    if (total_details > 0) {
      var table_body_length = 0;
      for (var i = 0; i < total_details; i++) {
        table_body_length++;
        var details = output_details[i];
        var outputstate = details["outputstate"];
        var target = details["total_target"];
        var sequence = details["sequence"];

        var sequence_input = '';
        if (mapping_type == '2') {
          sequence_input = `
            <td>
                <input type="number" name="sequence[]" step="any" min="0" id="sequence${table_body_length}" onkeyup="validate_sequence()" onchange="validate_sequence()" value="${sequence}" placeholder="Enter" class="form-control sequence" required />
            </td>`
        } else {
          sequence_input = `<input type="hidden" name="sequence[]" step="any" min="0" id="sequence${table_body_length}"  value="0" />`;
        }

        table_body += `
          <tr id="${table_body_length}" >
              <td>${table_body_length}</td>
              <td>
                  <select name="output_location[]" id="output_location${table_body_length}" class="form-control output_location_select" required="required">
                      <option value="">Select from list</option>
                  </select>
              </td>
              <td>
                  <input type="number" name="units[]" step="any" min="0" id="units${table_body_length}"  value="${target}" placeholder="Enter" onkeyup="validate_units_share(${table_body_length})" onchange="validate_units_share(${table_body_length})" class="form-control units" required />
              </td>
              ${sequence_input}
              <td>
                  <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_output_distribution('${table_body_length}')">
                      <span class="glyphicon glyphicon-minus"></span>
                  </button>
              </td>
          </tr>`;
        get_locations(table_body_length, outputstate);
        $(`#output_location${table_body_length}`).val(outputstate);
      }
    } else {
      table_body = `
                <tr></tr>
                <tr id = "remove_diss" >
                    <td align = "center" colspan = "5" > Add Ward/s </td>
                </tr>`;
    }

    var sequence_input_head = '';
    if (mapping_type == '2') {
      sequence_input_head = `<th width="10%">Output Sequence</th>`
    } else {
      sequence_input_head = ``;
    }

    var output_target_distribution = `
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">Output Location Distribution</label>
                <div class="table-responsive" id="output_details">
                    <table class="table table-bordered table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="55%">Location</th>
                                <th width="20%">Output Share</th>
                                ${sequence_input_head}
                                <th width="10%">
                                    <button type="button" name="addplus" id="addplus" onclick="add_output_distribution();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="output_share_table_body">
                            ${table_body}
                        </tbody>
                    </table>
                </div>
            </div>`;
    $("#output_target_distribution_div").html(output_target_distribution);

  } else {
    $("#output_target_distribution_div").html("");
  }
}

function combined_target_distribution(output_details = []) {
  var table_body = "";
  var val = $("#project_target").val();
  var output_target = val != "" ? parseFloat(val) : 0;
  if (output_target > 0) {
    var total_details = output_details.length;
    if (total_details > 0) {
      var table_body_length = 0;
      for (i = 0; i < total_details; i++) {
        table_body_length++;
        var details = output_details[i];
        var output_site = details["output_site"];
        var outputstate = details["outputstate"];
        var total_target = details["total_target"];
        table_body += `
          <tr id="row${table_body_length}" >
              <td>${table_body_length}</td>
              <td>
                  <select  name="site[]" id="site${table_body_length}" class="form-control output_site_select" required="required">
                      <option value="">Select from list</option>
                      ${output_site}
                  </select>
              </td>
              <td>
                  <input type="number" name="units[]" step="any" min="0" id="units${table_body_length}" placeholder="Enter" value="${total_target}" onkeyup="validate_units_share(${table_body_length})" onchange="validate_units_share(${table_body_length})" class="form-control units" required />
              </td>
              <td>
                  <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_output_distribution('row${table_body_length}')">
                      <span class="glyphicon glyphicon-minus"></span>
                  </button>
              </td>
          </tr>`;
        get_project_sites(table_body_length, output_site);
      }
    } else {
      table_body = `
      <tr></tr>
      <tr id = "remove_diss" >
          <td align = "center" colspan = "5" > Add Sites </td>
      </tr>`;
    }

    var output_target_distribution = `
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">Output Site Distribution</label>
          <div class="table-responsive" id="output_details">
              <table class="table table-bordered table-striped table-hover" style="width:100%">
                  <thead>
                      <tr>
                          <th width="5%">#</th>
                          <th width="30%">Site</th>
                          <th width="20%">Units</th>
                          <th width="5%">
                              <button type="button" name="addplus" id="addplus" onclick="add_output_distribution();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
                          </th>
                      </tr>
                  </thead>
                  <tbody id="output_share_table_body">
                      ${table_body}
                  </tbody>
              </table>
          </div>
      </div>`;
    $("#output_target_distribution_div").html(output_target_distribution);
  } else {
    $("#output_target_distribution_div").html("");
  }
}

function distributed_target_distribution(output_details = []) {
  var table_body = "";
  var val = $("#project_target").val();
  var output_target = val != "" ? parseFloat(val) : 0;

  console.log(output_target)
  if (output_target > 0) {
    var total_details = output_details.length;
    if (total_details > 0) {
      var counter = 0;
      for (var i = 0; i < total_details; i++) {
        counter++;
        var details = output_details[i];
        var output_site = details["output_site"];
        table_body += `
                      <tr>
                          <td>${counter}</td>
                          <td>
                              <select name="site[]" id="site${counter}"  class="form-control output_site_select" required>
                                  <option value="">Select Site from list</option>
                              </select>
                          </td>
                      </tr>`;
        get_project_sites(counter, output_site);
      }

    } else {
      var counter = 0;
      for (var i = 0; i < output_target; i++) {
        counter++;
        table_body += `
            <tr>
                <td>${counter}</td>
                <td>
                    <select name="site[]" id="site${counter}"  class="form-control output_site_select" required>
                        <option value="">Select Sites from list</option>
                    </select>
                </td>
            </tr>`;
          get_project_sites(counter, "");
      }
    }



    var output_target_distribution = `
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">Output Site Distribution</label>
                <div class="table-responsive" id="output_details">
                    <table class="table table-bordered table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="95%">Site</th>
                            </tr>
                        </thead>
                        <tbody id="output_share_table_body">
                            ${table_body}
                        </tbody>
                    </table>
                </div>
            </div>`;
    $("#output_target_distribution_div").html(output_target_distribution);
  } else {
    $("#output_target_distribution_div").html("");
  }
}

function add_output_distribution() {
  $("#remove_diss").remove();
  var rand = Math.floor(Math.random() * 6) + 1;
  var table_body_length = $("#output_share_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;

  var mapping_type = $("#mapping_type").val();
  if (mapping_type == "1" || mapping_type == '3') {
    $("#output_share_table_body tr:last").after(`
      <tr id="row${table_body_length}" >
          <td>${table_body_length}</td>
          <td>
              <select  name="site[]" id="site${table_body_length}" class="form-control output_site_select" required="required">
                  <option value="">Select from list</option>
              </select>
          </td>
          <td>
              <input type="number" name="units[]" step="any" min="0" id="units${table_body_length}" placeholder="Enter" onkeyup="validate_units_share(${table_body_length})" onchange="validate_units_share(${table_body_length})" class="form-control units" required />
          </td>
          <td>
              <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_output_distribution('row${table_body_length}')">
                  <span class="glyphicon glyphicon-minus"></span>
              </button>
          </td>
      </tr>`);
    get_project_sites(table_body_length, "");
  } else {
    if (mapping_type == '2') {
      $("#output_share_table_body tr:last").after(`
      <tr id="${table_body_length}" >
          <td>${table_body_length}</td>
          <td>
              <select name="output_location[]" id="output_location${table_body_length}" class="form-control output_location_select" required="required">
                  <option value="">Select from list</option>
              </select>
          </td>
          <td>
              <input type="number" name="units[]" step="any" min="0" id="units${table_body_length}" placeholder="Enter" onkeyup="validate_units_share(${table_body_length})" onchange="validate_units_share(${table_body_length})" class="form-control units" required />
          </td>
          <td>
            <input type="number" name="sequence[]" step="any" min="0" id="sequence${table_body_length}"  value="" onkeyup="validate_sequence()" onchange="validate_sequence()"  placeholder="Enter" class="form-control sequence" required />
          </td>
          <td>
              <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_output_distribution('${table_body_length}')">
                  <span class="glyphicon glyphicon-minus"></span>
              </button>
          </td>
      </tr>`);
    } else {
      $("#output_share_table_body tr:last").after(`
      <tr id="${table_body_length}" >
          <td>${table_body_length}</td>
          <td>
              <select name="output_location[]" id="output_location${table_body_length}" class="form-control output_location_select" required="required">
                  <option value="">Select from list</option>
              </select>
          </td>
          <td>
              <input type="number" name="units[]" step="any" min="0" id="units${table_body_length}" placeholder="Enter" onkeyup="validate_units_share(${table_body_length})" onchange="validate_units_share(${table_body_length})" class="form-control units" required />
          </td>
            <input type="hidden" name="sequence[]" step="any" min="0" id="sequence${table_body_length}"  value="0" />
          <td>
              <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_output_distribution('${table_body_length}')">
                  <span class="glyphicon glyphicon-minus"></span>
              </button>
          </td>
      </tr>`);
    }
    get_locations(table_body_length, "");
  }
  number_output_disttribution();
}

function validate_sequence() {
  var selectOutcome_arr = [];
  var tralse = true;

  $(".sequence").each(function (k, v) {
    var getVal = $(v).val();
    if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
      tralse = false;
      $(v).val("");
      error_alert("You cannot have similar location sequence more than once");
      return false;
    } else {
      selectOutcome_arr.push($(v).val());
    }
  });

  if (!tralse) {
    return false;
  }
}

function delete_output_distribution(rowno) {
  $("#" + rowno).remove();
  number_output_disttribution();
  var table_length = $("#output_share_table_body tr").length;
  if (table_length == 1) {
    $("#output_share_table_body tr:last").after(
      '<tr id="remove_diss"><td colspan="5" align="center">Add Ward/s </td></tr>'
    );
  }
}

function number_output_disttribution() {
  $("#output_share_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function validate_units_share(rowno) {
  var output_target = parseFloat($("#project_target").val());
  if (output_target > 0) {
    var units = 0;
    $(".units").each(function () {
      var val = $(this).val();
      units += val != "" ? parseFloat(val) : 0;
    });
    if (output_target < units) {
      $(`#units${rowno}`).val("");
      error_alert("Sorry you cannot exceed the output targets limit");
    }
  } else {
    error_alert("Ensure you have output target first");
  }
}


function validate_output_target() {
  var program_target = $("#program_target").val();
  program_target = program_target != "" ? parseFloat(program_target) : 0;
  if (program_target > 0) {
    var val = $("#project_target").val();
    var output_target = val != "" ? parseFloat(val) : 0;
    if (program_target < output_target) {
      $(`#project_target`).val("");
      error_alert("Target Surpassed");
    } else {
      output_target_div([]);
    }
  }
}



function show_dissaggregation(outpuval, dissaggregation_details = []) {
  $("#output_dissaggregation_div").html("");
  console.log(outpuval)
  if (outpuval == 1  ) {
    var body = ' <tr></tr>';
    if (dissaggregation_details.length > 0) {
      var diss_length = dissaggregation_details.length;
      var counter = 0;
      for (var i = 0; i < diss_length; i++) {
        counter++;
        var diss = dissaggregation_details[i];
        var dissaggregation = diss.dissaggregation;
        var dissaggregation_target = diss.dissaggregation_target;

        body += `
        <tr>
            <td></td>
            <td>
                <input type="text" name="dissaggregation_val[]" id="diss1row${counter}"  value="${dissaggregation}"  placeholder="Enter"  class="form-control" required/>
            </td>
            <td>
                <input type="number" name="dissaggregation_target[]" id="dissrow${counter}" value="${dissaggregation_target}" min='0'  onchange="validate_units_diss('row${counter}')" onkeyup="validate_units_diss('row${counter}')"  placeholder="Enter"  class="form-control diss" required/>
            </td>
            <td>
            </td>
        </tr>`;
      }
    } else {
      body += `
        <tr>
            <td></td>
            <td>
                <input type="text" name="dissaggregation_val[]" id="diss1row0"   placeholder="Enter"  class="form-control" required/>
            </td>
            <td>
                <input type="number" name="dissaggregation_target[]" id="dissrow0"  min="0" onchange="validate_units_diss('row0')" onkeyup="validate_units_diss('row0')"  placeholder="Enter"  class="form-control diss" required/>
            </td>
            <td>
            </td>
        </tr>`;
    }

    var html_ = `
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">Output Yearly Distribution</label>
          <div class="table-responsive" id="output_details">
              <table class="table table-bordered table-striped table-hover" style="width:100%">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th style="width:70">Dissaggregation</th>
                          <th style="width:20">Targets</th>
                          <th width="10%">
                              <button type="button" name="addplus" id="addplus" onclick="add_output_dissagggregation();" class="btn btn-success btn-sm">
                                  <span class="glyphicon glyphicon-plus"></span>
                              </button>
                          </th>
                      </tr>
                  </thead>
                  <tbody id="output_dissaggregations">
                    ${body}
                  </tbody>
              </table>
          </div>
      </div>`;
    $("#output_dissaggregation_div").html(html_);
    number_diss_table();
  }else{
    console.log("Sorry Unable to connect to");
  }
}

// function to add financiers
function add_output_dissagggregation() {
  $row = $("#output_dissaggregations tr").length;
  $row = $row + 1;
  var randno = Math.floor((Math.random() * 1000) + 1);
  var $rowno = $row + "" + randno;
  var locations = $(".output_target").val();
  $("#output_dissaggregations tr:last").after(`
      <tr id="testrow${$rowno}">
          <td></td>
          <td>
              <input type="text" name="dissaggregation_val[]" id="diss1row${$rowno}"   placeholder="Enter"  class="form-control" required/>
          </td>
          <td>
              <input type="number" name="dissaggregation_target[]" id="dissrow${$rowno}"  onchange="validate_units_diss('row${$rowno}')" onkeyup="validate_units_diss('row${$rowno}')"  placeholder="Enter"  class="form-control diss" required/>
          </td>
          <td>
              <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_dissaggregation("testrow${$rowno}")>
                  <span class="glyphicon glyphicon-minus"></span>
              </button>
          </td>
      </tr>`);
  number_diss_table();
}

// function to delete financiers
function delete_row_dissaggregation(rowno) {
  $("#" + rowno).remove();
  number_diss_table();
  var number = $("#output_dissaggregations tr").length;
  if (number == 1) {
    $("#output_dissaggregations tr:last").after('<tr id="remove_Diss" class="text-center"><td colspan="4"> Add Dissaggregations</td></tr>');
  }
}

// auto numbering table rows on delete and add new for financier table
function number_diss_table() {
  $("#output_dissaggregations tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function validate_units_diss(rowno) {
  var output_target = parseFloat($("#project_target").val());
  if (output_target > 0) {
    var units = 0;
    $(".diss").each(function () {
      var val = $(this).val();
      units += val != "" ? parseFloat(val) : 0;
    });

    if (output_target < units) {
      $(`#diss${rowno}`).val("");
      error_alert("Sorry you cannot exceed the output targets limit");
    }
  } else {
    error_alert("Ensure you have output target first");
  }
}