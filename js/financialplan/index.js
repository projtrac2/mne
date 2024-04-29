var ajax_url = "ajax/financialplan/index";
$(document).ready(function () {
   $("#modal_form_submit").submit(function (e) {
      e.preventDefault();
      var cost_type = $("#cost_type").val();
      var submit = true;
      if (cost_type == '2') {
         var administrative_cost = $("#administrative_cost").val();
         if (administrative_cost != '') {
            administrative_cost = parseFloat(administrative_cost);
            var total_budget_line_cost = 0;
            $(`.subamount`).each(function () {
               total_budget_line_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
            });
            if (total_budget_line_cost != administrative_cost) {
               submit = false;
               error_alert("Ensure that the administrative budgetline cost is equal to the allocated administrative cost");
            }
         }
      }

      if (submit) {
         var form_data = $(this).serialize();
         $("#modal-form-submit").attr("disabled", "disabled");
         $.ajax({
            type: "post",
            url: ajax_url,
            data: form_data,
            dataType: "json",
            success: function (response) {
               if (response.success) {
                  success_alert("Successfully created the record");
               } else {
                  error_alert("Error !!", "Error creating the record");
               }

               $(".modal").each(function () {
                  $(this).modal("hide");
                  $(this)
                     .find("form")
                     .trigger("reset");
               });

               setTimeout(() => {
                  window.location.reload(true);
               }, 3000);
            }
         });
      }
   });
});

//function to put commas to the data
function commaSeparateNumber(val) {
   while (/(\d+)(\d{3})/.test(val.toString())) {
      val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
   }
   return val;
}

function get_task_parameters(task_details) {
   $.ajax({
      type: "get",
      url: ajax_url,
      data: task_details,
      dataType: "json",
      success: function (response) {
         if (response.success) {
            $(`#budget_lines_values_table1`).html(response.body);
            $(`#budget_line_foot1`).html(response.footer);
            $("#comment").val(response.remarks);
         }
      }
   });
}

function add_budgetline(details) {
   $("#budget_lines_values_table").html(
      `<tr>
      <td>
         <input type="text" name="order[]"  class="form-control sequence" id="order1">
      </td>
      <td>
         <input type="text" name="description[]" class="form-control" id="description">
      </td>
      <td>
         <select name="unit_of_measure[]" id="unit_of_measure1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">

         </select>
      </td>
      <td>
         <input type="number" name="unit_cost[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="unit_cost1">
      </td>
      <td>
         <input type="number" name="no_units[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="no_units1">
      </td>
      <td>
         <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id1" value="0"/>
         <input type="hidden" name="task_type[]" class="form-control" id="task_type1" value="1" />
         <input type="hidden" name="subtotal_amount[]" id="subtotal_amount1" class="subtotal_amount subamount" value="">
         <span id="subtotal_cost1" style="color:red"></span>
      </td>
      <td style="width:2%"></td>
   </tr>`);
   get_unit_of_measure(1);

   $(".modal").each(function () {
      $(this).modal("hide");
      $(this)
         .find("form")
         .trigger("reset");
   });

   var projid = $("#myprojid").val();
   var administrative_cost = $("#administrative_cost").val();
   var direct_cost = $("#direct_cost").val();
   var cost_type = details.cost_type;
   var output_id = details.output_id;
   var budget_line_id = details.budget_line_id;
   var budget_line = details.budget_line;
   var task_id = details.task_id;
   var edit = details.edit;
   var plan_id = details.plan_id;
   var site_id = details.site_id;

   if (cost_type == 1 || cost_type == 6) {
      var task_costs = 0;
      $(`.task_costs`).each(function () {
         task_costs += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
      });
      cost = parseFloat(direct_cost) - task_costs;
      $('#remaining_balance').val(cost);
      $('#remaining_balance1').val(commaSeparateNumber(cost));
   } else {
      $('#remaining_balance').val(administrative_cost);
      $('#remaining_balance1').val(commaSeparateNumber(administrative_cost));
   }

   $("#site_id").val(site_id);
   $("#modal_info").html(`Add financial budgetlines for ${budget_line}`);
   $("#budget_line_id").val(budget_line_id);
   $("#task_id").val(task_id);
   $("#cost_type").val(cost_type);
   $("#output_id").val(output_id);
   $("#plan_id").val(plan_id);
   var projid = $("#myprojid").val();
   if (edit || (!edit && cost_type == 1)) {
      $.ajax({
         type: "get",
         url: ajax_url,
         data: {
            get_budgetline_details: "get_budgetline_details",
            projid: projid,
            other_plan_id: cost_type,
            output_id: output_id,
            site_id: site_id,
            task_id: task_id,
            plan_id: plan_id,
            cost_type: cost_type,
            edit: edit,
            administrative_cost: administrative_cost,
            direct_cost: direct_cost,
         },
         dataType: "json",
         success: function (response) {
            if (response.success) {
               $(`#budget_lines_values_table`).html(response.body);
               $(`#budget_line_foot`).html(response.footer);
               $("#output_cost_ceiling").html("Total Cost Ksh: " + commaSeparateNumber(0));
            }
         }
      });
   }
}

function calculate_total_cost(rowno) {
   var cost_type = $("#cost_type").val();
   var direct_cost = parseFloat($("#direct_cost").val());
   var remaining_balance = $("#remaining_balance").val();

   remaining_balance = remaining_balance != '' ? parseFloat(remaining_balance) : 0;

   var unit_cost = $(`#unit_cost${rowno}`).val();
   var no_units = $(`#no_units${rowno}`).val();
   unit_cost = unit_cost != '' ? parseFloat(unit_cost) : 0;
   no_units = no_units != '' ? parseFloat(no_units) : 0;

   total_cost = no_units * unit_cost;
   $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(total_cost));
   $(`#subtotal_amount${rowno}`).val(total_cost);

   var total_budget_line_cost = 0;
   $(`.subamount`).each(function () {
      total_budget_line_cost += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
   });

   if (total_budget_line_cost > remaining_balance) {
      $(`#no_units${rowno}`).val("");
      $(`#unit_cost${rowno}`).val("");
      total_cost = 0;
      success_alert("Total contribution should be less or equal to the project implementation cost");
   }

   $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(parseInt(total_cost)));
   $(`#subtotal_amount${rowno}`).val(total_cost);

   if (cost_type == '1' || cost_type == '6') {
      calculate_subtotal(parseFloat(direct_cost));
   } else if (cost_type == '2') {
      var administrative_cost = $("#administrative_cost").val();
      if (administrative_cost != '') {
         administrative_cost = parseFloat(administrative_cost);
         calculate_subtotal(administrative_cost);
      }
   }
}

function calculate_subtotal(cost) {
   var total_amount = 0;
   $(`.subamount`).each(function () {
      if ($(this).val() != "") {
         total_amount = total_amount + parseFloat($(this).val());
      }
   });

   var percentage = (total_amount / cost) * 100;
   $(`#psub_total_amount3`).val(commaSeparateNumber(total_amount));
   $(`#psub_total_percentage3`).val(percentage.toFixed(2));
}


// function to add new rowfor financiers
function add_budget_costline() {
   var rand = Math.floor(Math.random() * 6) + 1;
   var rowno = $("#milestone_table_body tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
   $(`#budget_lines_values_table tr:last`).after(`
   <tr id="budget_line_cost_line${rowno}">
      <td>
         <input type="text" name="order[]"  class="form-control sequence" id="order${rowno}" required>
      </td>
      <td>
         <input type="text" name="description[]" class="form-control" id="description${rowno}">
      </td>
      <td>
         <select name="unit_of_measure[]" id="unit_of_measure${rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
            <option value="">..Select Unit of Measure..</option>
         </select>
      </td>
      <td>
         <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="no_units${rowno}">
      </td>
      <td>
         <input type="number" name="unit_cost[]" min="0" class="form-control " onchange="calculate_total_cost('${rowno}')" onkeyup="calculate_total_cost('${rowno}')" id="unit_cost${rowno}">
      </td>
      <td>
         <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id${rowno}" value="0"/>
         <input type="hidden" name="task_type[]"  class="form-control" id="task_type${rowno}" value="1"/>
         <input type="hidden" name="subtotal_amount[]" id="subtotal_amount${rowno}" class="subtotal_amount${rowno} subamount" value="">
         <span id="subtotal_cost${rowno}" style="color:red"></span>
      </td>
      <td style="width:2%">
         <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${rowno}')">
            <span class="glyphicon glyphicon-minus"></span>
         </button>
      </td>
   </tr>`);
   get_unit_of_measure(rowno);
}

// function to delete financiers row
function delete_budget_costline(rowno) {
   $("#" + rowno).remove();
   var cost_type = $("#cost_type").val();
   var direct_cost = parseFloat($("#direct_cost").val());
   if (cost_type == '1' || cost_type == '6') {
      calculate_subtotal(parseFloat(direct_cost));
   } else if (cost_type == '2') {
      var administrative_cost = $("#administrative_cost").val();
      if (administrative_cost != '') {
         administrative_cost = parseFloat(administrative_cost);
         calculate_subtotal(administrative_cost);
      }
   } 
}

function get_unit_of_measure(rowno) {
   $.ajax({
      type: "get",
      url: ajax_url,
      data: {
         get_unit_of_measure: 'get_unit_of_measure',
      },
      dataType: "json",
      success: function (response) {
         if (response.success) {
            $(`#unit_of_measure${rowno}`).html(response.measurements);
         } else {
            error_alert('Error could not find unit of measure ');
         }
      }
   });
}