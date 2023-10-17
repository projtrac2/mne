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
      type: "Error",
      icon: "warning",
      dangerMode: true,
      timer: 15000,
      showConfirmButton: false,
   });
   setTimeout(function () { }, 15000);
}

function add_direct_cost(output_id,budget_line_id,cost_type_id,task_id=0) {
   var projid= $("#projid").val();
   $("#output_id").val(output_id);
   $("#budget_line_id").val(cost_type_id);
   $("#other_plan_id").val(budget_line_id);
   $("#task_id").val(task_id);
   var output_budget_line_id = budget_line_id + "" + output_id;
   get_ouput_balance(output_id, output_budget_line_id); 
   if(cost_type_id != 1){
      $("#Budgetrowcontainer").show();
      get_financial_years(projid, output_id);
   }else{
      $("#Budgetrowcontainer").hide();
   }
}

function edit_direct_cost(output_id, budget_line_id, cost_type_id, task_id=0, cost_plan_id) {
   var projid= $("#projid").val();
   $("#output_id").val(output_id);
   $("#budget_line_id").val(cost_type_id);
   $("#other_plan_id").val(budget_line_id);
   $("#task_id").val(task_id);
   $("#cost_plan_id").val(cost_plan_id);
   var output_budget_line_id = budget_line_id + "" + output_id;
   get_ouput_balance(output_id, output_budget_line_id);
   if(cost_type_id != 1){
      $("#Budgetrowcontainer").show();
      // get_financial_years(projid, output_id);
   }else{
      $("#Budgetrowcontainer").hide();
   }

   get_costplan_details(cost_plan_id,cost_type_id);
}

function get_costplan_details(cost_plan_id, cost_type_id){
   if(cost_plan_id){
      $.ajax({
         type: "get",
         url: "ajax/financialplan/index",
         data: {
            get_budget_line_cost_plan:"get_budget_line_cost_plan", 
            cost_plan_id:cost_plan_id,
            cost_type_id:cost_type_id,
         },
         dataType: "json",
         success: function (response) { 
            set_cost_plan_details(response.cost_plan_details);
            $("#output_financial_years_table").html(response.yearly_plan_details);
            console.log(response.financier_plan_details);
            $("#financier_table_body").html(response.financier_plan_details);
            $("#store").val("edit");
            numbering_financier();
         }
      });
   }else{
      console.log("Error could not find")
   }
}

function set_cost_plan_details(cost_plan_details){
   if(cost_plan_details){
      var unit_cost =parseFloat(cost_plan_details.unit_cost);
      var no_units = parseFloat(cost_plan_details.units_no);
      var total_cost = unit_cost * no_units;

      $("#description").val(cost_plan_details.description);
      $("#unit").val(cost_plan_details.unit);
      $("#unit_cost").val(unit_cost);
      $("#no_units").val(no_units);
      $("#subtotal_cost").html(commaSeparateNumber(total_cost));
      $("#comment").val(cost_plan_details.comments);
   }
}

function get_financial_years(projid,output_id){
   if(projid && output_id){
      $.ajax({
         type: "get",
         url: "ajax/financialplan/index",
         data: {
            output_financial_years:"output_financial_years", 
            projid:projid,
            output_id:output_id,
         },
         dataType: "html",
         success: function (response) {
            $("#output_financial_years_table").html(response);
         }
      });
   }else{
      console.log("Error could not find")
   }
}


function get_ouput_balance(output_id, output_budget_line_id) {
   var output_budget = parseFloat($(`#output_budget_cost${output_id}`).val());
   var budget_line_subtotal = parseFloat(
      $(`#h_sub_total_amount3${output_budget_line_id}`).val()
   );
   var project_cost = parseFloat($("#project_cost").val());
   var output_sub_total = 0;
   $(`.h_sub_total_amount3${output_budget_line_id}`).each(function () {
      output_sub_total += parseFloat($(this).val());
   });

   var output_budget_balance = output_budget - output_sub_total;
   var sub_total_percentage3 = (budget_line_subtotal / output_budget) * 100;

   $(`#sub_total_amount3`).val(commaSeparateNumber(budget_line_subtotal));
   $(`#sub_total_percentage3`).val(commaSeparateNumber(sub_total_percentage3.toFixed(2)));
   $(`#output_balance`).val(commaSeparateNumber(output_budget_balance));
   $(`#h_output_balance`).val(output_budget_balance);

   var project_sub_total = 0;
   $(`.project_output_cost`).each(function () {
      project_sub_total += parseFloat($(this).val());
   });

   var project_balance = project_cost - project_sub_total;
   var project_used_percentage = (budget_line_subtotal / output_budget) * 100;

   $(`#project_used`).val(commaSeparateNumber(project_sub_total));
   $(`#project_used_percentage`).val(
      commaSeparateNumber(project_used_percentage.toFixed(2))
   );
   $(`#project_balance_cost`).val(commaSeparateNumber(project_balance));
}

function calculate_total_cost() {
   var unit_cost = $("#unit_cost").val();
   var no_units = $("#no_units").val();

   var output_balance = $("#h_output_balance").val();
   var total_cost = 0;
   if (unit_cost != "" && no_units != "" && output_balance != "") {
      unit_cost = parseFloat(unit_cost);
      no_units = parseFloat(no_units);
      output_balance = parseFloat(output_balance);
      cost = unit_cost * no_units;

      if (output_balance >= cost) {
         total_cost = cost;
      }else{
         $("#no_units").val("");
      }
   }

   $("#subtotal_cost").html(commaSeparateNumber(total_cost));
   $("#output_cost_fi_celing").val(total_cost);
   $("#total_financiers").html(commaSeparateNumber(total_cost));
}

function delete_cost_plan(cost_plan_id){
   swal({
      title: "Are you sure you want to remove?",
      text: "Once removed, you will not be able to recover this record!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
   })
    .then((willDelete) => {
      if (willDelete) {
         $.ajax({
            type: "post",
            url: "ajax/financialplan/index",
            data: {
               delete_cost_plan:"delete_cost_plan",
               cost_plan_id:cost_plan_id
            },
            dataType: "json",
            success: function (response) {
               swal("Your record has been deleted!", {
                  icon: "success",
               }); 
               window.location.reload(true);
            }
         });
      } else {
        swal("Your record is safe!");
      }
   });
}

function get_sum_budget(year){
   var input_field= $(`#budget_year${year}`).val();
   var output_cost_fi_celing = $("#output_cost_fi_celing").val();
   var budget_line_cost = 0;
   $(".budget_line_cost").each(function () {
      if ($(this).val() != "") {
         budget_line_cost = budget_line_cost + parseFloat($(this).val());
      }
   });

   if(output_cost_fi_celing != ""){
      if(output_cost_fi_celing > 0){
         input_field = parseFloat(input_field);
         output_cost_fi_celing = parseFloat(output_cost_fi_celing); 
         if(output_cost_fi_celing >= budget_line_cost){
            console.log("Everything is working")
         }else{
            $(`#budget_year${year}`).val("");
            console.log("Ensure that the cost is all working bana")
         }
      }else{
         $(`#budget_year${year}`).val("");
         console.log("Ensure that you have defined the output balance should be greater than 0");
      }
   }else{
      $(`#budget_year${year}`).val("");

      console.log("Ensure you have entered data on the output first");
   }

}

function get_budget_line_sub_totals(output_budget_line_id) {
   var subtotal_amount = $(`#sub_total_amount3${output_budget_line_id}`).val();
   var subtotal_percentage = $(`#sub_total_percentage3${output_budget_line_id}`).val();
   $("#h_output_balance").val(output_remaining_budget);
   $("#output_balance").val(output_remaining_budget);
   $("#subtotal_percentage").val(subtotal_percentage);
   $("#subtotal_amount").val(subtotal_amount);
}

// function to add new rowfor financiers
$rowno = $("#financier_table_body tr").length;
function add_row_financier() {
   $("#removeTr").remove(); //new change
   $rowno = $rowno + 1;
   $("#financier_table_body tr:last").after(
      `<tr id="financierrow${$rowno}">
		<td></td>
		<td colspan="3">
			<select onchange=get_financier_funds("row${$rowno}") name="finance[]" id="financerow${$rowno}" class="form-control validoutcome selectedfinance" required="required">' +
				<option value="">Select Financier from list</option>
			</select>
		</td>
		<td>
			<input type="hidden" name="ceilingval[]"  id="ceilingvalrow${$rowno}" /><span id="currrow${$rowno}"></span>
			<span id="financierCeilingrow${$rowno}" style="color:red"></span>
		</td>
		<td>
			<input type="number" name="amountfunding[]" onkeyup=financier_funding("row${$rowno}") onchange=financier_funding("row${$rowno}")  id="amountfundingrow${$rowno}"  placeholder="Enter amount"  class="form-control financierTotal" required/>
		</td>
		<td>
			<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financierrow${$rowno}")>
				<span class="glyphicon glyphicon-minus"></span>
			</button>
		</td>
    </tr>`
   );
   get_financiers($rowno);
   numbering_financier();
}

// function to delete financiers row
function delete_row_financier(rowno) {
   var financierId = "#financierId" + rowno;
   if ($(financierId).length > 0) {
      var confirmation = confirm("Are you sure you want to delete the financier");
      if (confirmation) {
         $("#" + rowno).remove();
         numbering_financier();
         $check = $("#financier_table_body tr").length;
         if ($check == 1) {
            $("#financier_table_body").html(
               `<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`
            );
         }
      }
   } else {
      $("#" + rowno).remove();
      numbering_financier();
      $check = $("#financier_table_body tr").length;
      if ($check == 1) {
         $("#financier_table_body").html(
            `<tr></tr><tr id="removeTr"><td colspan="5">Add Financiers</td></tr>`
         );
      }
   }
}



// auto numbering table rows on delete and add new for financier table
function numbering_financier() {
   $("#financier_table_body tr").each(function (idx) {
      $(this)
         .children()
         .first()
         .html(idx - 1 + 1);
   });
}

//get financiers
function get_financiers(rowno) {
   var projid = $("#projid").val();
   var financier = "#financerow" + rowno;
   $.ajax({
      type: "post",
      url: "assets/processor/add-financial-plan-process",
      data: {
         getfinancier: projid,
      },
      dataType: "html",
      success: function (response) {
         $(financier).html(response);
      },
   });
}

function get_financier_funds(rowno) {
   var finance = $("#finance" + rowno).val();
   var financierCeiling = "#financierCeiling" + rowno;
   var projid = $("#projid").val();

   if (finance) {
      $.ajax({
         type: "post",
         url: "assets/processor/add-financial-plan-process",
         data: {
            cfinance: "finance",
            financeId: finance,
            projid: projid,
         },
         dataType: "json",
         success: function (response) {
            var finance = $("#finance" + rowno).val();
            if (finance) {
               if (response.msg == "true") {
                  var responseval = response.remaining;
                  $(financierCeiling).html(
                     commaSeparateNumber(parseFloat(responseval))
                  );
                  $("#ceilingval" + rowno).val(responseval);
                  validate_against_output_cost();
               } else if (response.msg == "false") {
                  $("#ceilingval" + rowno).val("No Balance");
                  $("#finance" + rowno).val("");
                  $(financierCeiling).html("");
                  validate_against_output_cost();
               }
            } else {
               $("#ceilingval" + rowno).val("No Balance");
               $("#finance" + rowno).val("");
               $(financierCeiling).html("");
               validate_against_output_cost();
            }
         },
      });
   } else {
      var msg = "Select Financier";
      sweet_alert("Error !!!", response);
      $(financierCeiling).html("");
      $("#amountfunding" + rowno).val("");
   }
}

//filter the output cannot be selected twice
$(document).on("change", ".selectedfinance", function (e) {
   var tralse = true;
   var selectImpact_arr = [];
   var attrb = $(this).attr("id");
   var rowno = $(this).attr("data-id");
   var selectedid = "#" + attrb;
   var selectedText = $(selectedid + " option:selected").html();
   var handler = true;

   var finance_input = $("input[name='financierId[]']").length;

   if (finance_input > 0) {
      handler = confirm("Are you sure you want to change");
      if (handler) {
         $(".selectedfinance").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
               tralse = false;
               sweet_alert(
                  "Error !!!",
                  "You canot select Output " + selectedText + " more than once "
               );
               var rw = $(v).attr("data-id");
               var amountfundingrow = "#amountfundingrow" + rw;
               var ceilingvalrow = "#ceilingvalrow" + rw;
               $(v).val("");
               $(amountfundingrow).val("");
               $(ceilingvalrow).val("");
               return false;
            } else {
               selectImpact_arr.push($(v).val());
            }
         });
      } else {
         var financier = $("#financierIdfinancierrow" + rowno).val();
         var ceilingval = $("#hceilingvalrow" + rowno).val();
         $("#financerow" + rowno).val(financier);
         $("#ceilingvalrow" + rowno).val(ceilingval);
         $("#financierCeilingrow" + rowno).html(commaSeparateNumber(ceilingval));
      }
   } else {
      $(".selectedfinance").each(function (k, v) {
         var getVal = $(v).val();
         if (getVal && $.trim(selectImpact_arr.indexOf(getVal)) != -1) {
            tralse = false;
            sweet_alert(
               "Error !!!",
               "You canot select Output " + selectedText + " more than once "
            );
            var rw = $(v).attr("data-id");
            var amountfundingrow = "#amountfundingrow" + rw;
            var ceilingvalrow = "#ceilingvalrow" + rw;
            $(v).val("");
            $(amountfundingrow).val("");
            $(ceilingvalrow).val("");
            return false;
         } else {
            selectImpact_arr.push($(v).val());
         }
      });
   }

   if (!tralse) {
      return false;
   }
});

function financier_funding(rowno) {
   var ceilingval = $("#ceilingval" + rowno).val();
   var amountfunding = $("#amountfunding" + rowno).val();

   var financierCeilingId = "#financierCeiling" + rowno;
   var amountfundingId = "#amountfunding" + rowno;

   // according to the ceiling of the financier
   if (ceilingval) {
      if (amountfunding) {
         if (parseFloat(amountfunding) > 0) {
            var handler = validate_against_output_cost();
            if (handler) {
               var remaining = parseFloat(ceilingval) - parseFloat(amountfunding);
               if (remaining < 0) {
                  var msg = "The value entered cannot be less than 0 ";
                  sweet_alert("Error !!!", msg);
                  $(financierCeilingId).html(
                     commaSeparateNumber(parseFloat(ceilingval))
                  );
                  $(amountfundingId).val("");
               } else {
                  $(financierCeilingId).html(commaSeparateNumber(remaining));
                  validate_against_output_cost();
               }
            } else {
               $(financierCeilingId).html(
                  commaSeparateNumber(parseFloat(ceilingval))
               );
               $(amountfundingId).val("");
               validate_against_output_cost();
            }
         } else {
            var msg = "The value should be greater than 0 ";
            sweet_alert("Error !!!", msg);

            $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
            $(amountfundingId).val("");
            validate_against_output_cost();
         }
      } else {
         var msg = "This field cannot be empty ";
         $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
         $(amountfundingId).val("");
         validate_against_output_cost();
      }
   } else {
      var msg = "Select a financier ";
      $(financierCeilingId).html(commaSeparateNumber(parseFloat(ceilingval)));
      $(amountfundingId).val("");
      validate_against_output_cost();
   }
}

function validate_against_output_cost() {
   var outputCost = parseFloat($("#output_cost_fi_celing").val());
   var financierTotal = 0;
   $(".financierTotal").each(function () {
      if ($(this).val() != "") {
         financierTotal = financierTotal + parseFloat($(this).val());
      }
   });
   
   var remaining = outputCost - financierTotal;
   if (remaining >= 0) {
      $("#output_cost_ceiling").html(
         "Total Cost Ksh:" + commaSeparateNumber(remaining)
      );
      return true;
   } else {
      $("#output_cost_ceiling").html(
         "Total Cost Ksh:" + commaSeparateNumber(remaining)
      );
      msg = "Ensure that you don't cross the plan budget limit";
      sweet_alert("Error !!!", msg);
      return false;
   }
}

function validate_financiers(){
   var msg = false;
   var output_cost_fi_celing = $("#output_cost_fi_celing").val();
   var financier_contribution = 0;
   $(".financierTotal").each(function () {
      if ($(this).val() != "") {
         financier_contribution = financier_contribution + parseFloat($(this).val());
      }
   });

   if(output_cost_fi_celing != ""){
      output_cost_fi_celing = parseFloat(output_cost_fi_celing);
      msg = output_cost_fi_celing == financier_contribution && financier_contribution !=0 ? true : false;
   }else{
      msg = false;
   } 
   return msg;
}

function validate_yearly_budget(){
   var msg = false;
   var cost_type_id = $("#budget_line_id").val();
   if(cost_type_id != "1"){
      var output_cost_fi_celing = $("#output_cost_fi_celing").val();
      var budget_line_cost = 0;
      $(".budget_line_cost").each(function () {
         if ($(this).val() != "") {
            budget_line_cost = budget_line_cost + parseFloat($(this).val());
         }
      });

      if(output_cost_fi_celing != ""){
         output_cost_fi_celing = parseFloat(output_cost_fi_celing);
         msg = output_cost_fi_celing == budget_line_cost && budget_line_cost !=0 ? true : false;
      }else{
         msg = false;
      }
   }else{
      msg = true;
   }
   return msg;
}


$(document).ready(function () {
   $("#modal_form_submit").submit(function (e) { 
      e.preventDefault();
      var budget= validate_yearly_budget();
      var financier_funds= validate_financiers();
      if(budget){
         if(financier_funds){
            var form_data = $(this).serialize();
            $.ajax({
               type: "post",
               url: "ajax/financialplan/index",
               data: form_data,
               dataType: "json",
               success: function (response) {
                  if(response.success){
                     sweet_alert("Success !!!", "Successfully added new record");
                  }else{
                     sweet_alert("Error !!!", "Successfully added new record");
                  }
                  window.location.reload(true);
               }
            });
         }else{
            sweet_alert("Error !!!", "Ensure the financier contribution equals the defined amount for the cost plan")
         }
      }else{
         sweet_alert("Error !!!", "Ensure the yearly budget equals the defined amount for the cost plan")
      }
   });


   $("#form").submit(function (e) { 
      e.preventDefault(); 
      var form_data = $(this).serialize();
      $.ajax({
         type: "post",
         url: "ajax/financialplan/index",
         data: form_data,
         dataType: "json",
         success: function (response) {
            if(response.success){
               sweet_alert("Success !!!", response.message);
            }else{
               sweet_alert("Error !!!", response.message);
            }
            setTimeout(function () { window.location.reload(true); }, 15000);
         }
      });
   });
});