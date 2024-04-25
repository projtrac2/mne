

//set time iterval
function get_all(userid) {
  $.ajax({
    type: "GET",
    url:
      "general-settings/selected-items/fetch-selected-baseline-tasks-items",
    data: {
      userid: userid,
    },
    dataType: "json",
    success: function (response) { 
      let tdata = response.data;  
      let output_data = "";
      let output_counter = 0;

      //outcome
      let outcome_baseline_data = "";
      let outcome_evaluation_data = "";
      let outcome_baseline_counter = 0;
      let outcome_evaluation_counter = 0;

      //impact
      let impact_baseline_data = "";
      let impact_evaluation_data = "";
      let impact_baseline_counter = 0;
      let impact_evaluation_counter = 0;

      tdata.forEach((element) => 
	   
        let formid = element.formid;
        let indicator_name = element.indicator_name;
        let location = element.location;
        let surveyed = element.surveyed;
        let projname = element.projname;
        let form_name = element.form_name;
        let ftype = element.type; 
        let form_type = element.form_type;
        let action = "";
        if (surveyed == 1) {
          action = `
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Options <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a type="button" data-toggle="modal" id="editbaseline"  href="edit-baseline-survey-data.php?formid=${formid}"> 
                    <i class="glyphicon glyphicon-edit"></i> Edit
                  </a>
                </li>
              </ul>
            </div>`;
        } else {
          action = `
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Options <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a type="button" id="addbaseline"  href="add-baseline-survey-data.php?formid=${formid}" >
                    <i class="fa fa-plus-square"></i> Add
                  </a>
                </li>
              </ul>
            </div>`;
        }

        if (ftype == 3) {
          output_counter++;
          output_data =
            output_data +
            `<tr>
              <td style="width:5%">${output_counter}</td>
              <td style="width:5%">${indicator_name}</td>
              <td style="width:5%">${location}</td>
              <td style="width:5%">${action}</td>
            </tr>`;
        } else if (ftype == 2) {
          if (form_type == 1) {
            outcome_baseline_counter++;
            outcome_baseline_data =
              outcome_baseline_data +
              `<tr>
              <td style="width:5%">${outcome_baseline_counter}</td>
              <td style="width:60%">${indicator_name}</td>
              <td style="width:25%">${location}</td>
              <td style="width:10%">${action}</td>
            </tr>`;
          } else if (form_type == 2) {
            outcome_evaluation_counter++;
            outcome_evaluation_data =
              outcome_evaluation_data +
              `<tr>
              <td style="width:5%">${outcome_evaluation_counter}</td>
              <td style="width:60%">${indicator_name}</td>
              <td style="width:25%">${location}</td>
              <td style="width:10%">${action}</td>
            </tr>`;
          }
        } else if (ftype == 1) {
          if (form_type == 1) {
            impact_baseline_counter++;
            impact_baseline_data =
              impact_baseline_data +
              `<tr>
              <td style="width:5%">${impact_baseline_counter}</td>
              <td style="width:60%">${indicator_name}</td>
              <td style="width:25%">${location}</td>
              <td style="width:10%">${action}</td>
            </tr>`;
          } else if (form_type == 2) {
            impact_evaluation_counter++;
            impact_evaluation_data =
              impact_evaluation_data +
              `<tr>
              <td style="width:5%">${impact_evaluation_counter}</td>
              <td style="width:60%">${indicator_name}</td>
              <td style="width:25%">${location}</td>
              <td style="width:10%">${action}</td>
            </tr>`;
          }
        }
      });

      let outcome_counter =
        outcome_baseline_counter + outcome_evaluation_counter;
      let impact_counter = impact_baseline_counter + impact_evaluation_counter;

      if (output_counter > 0 && outcome_counter > 0 && impact_counter > 0) {
        $("#output").addClass("in active");
        $("#output_tab").addClass("in active");
        $("#output").show();
        $("#output_tab").show();

        $("#outcome_tab").removeClass("in active");
        $("#outcome").removeClass("in active");
        $("#outcome").show();
        $("#outcome_tab").show();

        $("#impact_tab").removeClass("in active");
        $("#impact").removeClass("in active");
        $("#impact").show();
        $("#impact_tab").show();
        outcome_tab(outcome_baseline_counter, outcome_evaluation_counter);
        impact_tab(impact_baseline_counter, impact_evaluation_counter);
      } else if (
        output_counter == 0 &&
        outcome_counter > 0 &&
        impact_counter > 0
      ) {
        $("#outcome_tab").addClass("in active");
        $("#outcome").addClass("in active");
        $("#outcome").show();
        $("#outcome_tab").show();

        $("#output_tab").removeClass("in active");
        $("#output").removeClass("in active");
        $("#output").hide();
        $("#output_tab").hide();

        $("#impact_tab").removeClass("in active");
        $("#impact").removeClass("in active");
        $("#impact").show();
        $("#impact_tab").show();

        outcome_tab(outcome_baseline_counter, outcome_evaluation_counter);
        impact_tab(impact_baseline_counter, impact_evaluation_counter);
      } else if (
        output_counter == 0 &&
        outcome_counter == 0 &&
        impact_counter > 0
      ) {
        $("#impact_tab").addClass("in active");
        $("#impact").addClass("in active");

        $("#impact").show();
        $("#impact_tab").show();

        $("#output_tab").removeClass("in active");
        $("#output").removeClass("in active");
        $("#output").hide();
        $("#output_tab").hide();

        $("#outcome_tab").removeClass("in active");
        $("#outcome").removeClass("in active");
        $("#outcome").hide();
        $("#outcome_tab").hide();

        impact_tab(impact_baseline_counter, impact_evaluation_counter);
      }

      // outcome
      // outcome_active(outcome_baseline_counter, outcome_evaluation_counter);
      $("#outcome_counter").html(outcome_counter);
      $("#tbody_outcome_baseline").html(outcome_baseline_data);
      $("#tbody_outcome_evaluation").html(outcome_evaluation_data);
      $("#outcome_baseline_counter").html(outcome_baseline_counter);
      $("#outcome_evaluation_counter").html(outcome_evaluation_counter);

      // impact
      // impact_active(impact_baseline_counter, impact_evaluation_counter);
      $("#impact_counter").html(impact_counter);
	  

      $("#tbody_impact_baseline").html(impact_baseline_data);
      $("#tbody_impact_evaluation").html(impact_evaluation_data);
      $("#impact_baseline_counter").html(impact_baseline_counter);
      $("#impact_evaluation_counter").html(impact_evaluation_counter);
    },
  });
}

function outcome_tab(outcome_baseline_counter, outcome_evaluation_counter) {
  if (outcome_baseline_counter > 0 && outcome_evaluation_counter > 0) {
    $("#outcome_base_tab").addClass("in active");
    $("#outcome_baseline").addClass("in active");
    $("#outcome_base_tab").show();
    $("#outcome_baseline").show();

    $("#outcome_eval_tab").removeClass("in active");
    $("#outcome_evaluation").removeClass("in active");
    $("#outcome_eval_tab").show();
    $("#outcome_evaluation").show();
  } else if (outcome_baseline_counter > 0 && outcome_evaluation_counter == 0) {
    $("#outcome_base_tab").addClass("in active");
    $("#outcome_baseline").addClass("in active");
    $("#outcome_base_tab").show();
    $("#outcome_baseline").show();

    $("#outcome_eval_tab").removeClass("in active");
    $("#outcome_evaluation").removeClass("in active");
    $("#outcome_eval_tab").hide();
    $("#outcome_evaluation").hide();
  } else if (outcome_baseline_counter == 0 && outcome_evaluation_counter > 0) {
    $("#outcome_eval_tab").addClass("in active");
    $("#outcome_evaluation").addClass("in active");
    $("#outcome_eval_tab").show();
    $("#outcome_evaluation").show();

    $("#outcome_base_tab").removeClass("in active");
    $("#outcome_baseline").removeClass("in active");
    $("#outcome_base_tab").hide();
    $("#outcome_baseline").hide();
  } else {
    $("#outcome_eval_tab").removeClass("in active");
    $("#outcome_base_tab").removeClass("in active");
    $("#outcome_baseline").removeClass("in active");
    $("#outcome_evaluation").removeClass("in active");
  }
}

function impact_tab(impact_baseline_counter, impact_evaluation_counter) {
  if (impact_baseline_counter > 0 && impact_evaluation_counter > 0) {
    $("#impact_base_tab").addClass("in active");
    $("#impact_baseline").addClass("in active");
    $("#impact_base_tab").show();
    $("#impact_baseline").show();

    $("#impact_eval_tab").removeClass("in active");
    $("#impact_evaluation").removeClass("in active");
    $("#impact_eval_tab").show();
    $("#impact_evaluation").show();
  } else if (impact_baseline_counter > 0 && impact_evaluation_counter == 0) {
    $("#impact_base_tab").addClass("in active");
    $("#impact_baseline").addClass("in active");
    $("#impact_base_tab").show();
    $("#impact_baseline").show();

    $("#impact_eval_tab").removeClass("in active");
    $("#impact_evaluation").removeClass("in active");
    $("#impact_eval_tab").hide();
    $("#impact_evaluation").hide();
  } else if (impact_baseline_counter == 0 && impact_evaluation_counter > 0) {
    $("#impact_eval_tab").addClass("in active");
    $("#impact_evaluation").addClass("in active");
    $("#impact_eval_tab").hide();
    $("#impact_evaluation").hide();

    $("#impact_base_tab").removeClass("in active");
    $("#impact_baseline").removeClass("in active");
    $("#impact_base_tab").show();
    $("#impact_baseline").show();
  } else {
    $("#impact_eval_tab").removeClass("in active");
    $("#impact_base_tab").removeClass("in active");
    $("#impact_baseline").removeClass("in active");
    $("#impact_evaluation").removeClass("in active");
  }
}
