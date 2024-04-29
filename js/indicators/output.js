$(document).ready(function () {
    var urlpath = window.location.pathname;
    var filename = urlpath.substring(urlpath.lastIndexOf('/') + 1);
  
    if (urlpath == "add-output-indicator.php" || urlpath == "add-output-indicator.php") {
        $("#output_dissegragate").hide();
        $("#output_table_body").html(
            '<tr></tr><tr id="removeindoutputTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
        );
    } 

    $("#output_directBenfType").change(function (e) { 
        e.preventDefault();
        if($(this).val() == 1){
            $("#output_dissegragate").show();
            var $rowno =0;
            $("#output_table_body").html(
                `<tr></tr><tr id="${$rowno}">
                    <td></td> 
                    <td> 
                        <select name="output_dissagragation_type[]" id="output_dissagragation_type${$rowno}" onchange="output_dissagragation_type_change(${$rowno})" class="form-control show-tick output  output_input" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                        </select>
                    </td>
                    <td>
                        <select name="output_dissagragation_parent[]" id="output_dissagragation_parent${$rowno}" onchange="output_dissagragation_parent_change(${$rowno})" class="form-control show-tick output_parent" style="border:1px #CCC thin solid; border-radius:5px">
                        </select>
                    </td> 
                    <td>
                        <input type="text" name="output_disaggregations[]" id="output_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
                    </td>
                    <td>
                    </td>
                </tr>`
            );
            number_output_table();
            get_disaggregations($rowno, (type = 4));
        }else{
            $("#output_dissegragate").hide();
            $("#output_table_body").html(
                '<tr></tr><tr id="removeindoutputTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
            );
        }
    });
});

function hide_disaggregation(disaggregation= null){
    if(disaggregation != 1){
        $("#output_dissegragate").hide();
        $("#output_table_body").html(
            '<tr></tr><tr id="removeindoutputTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
        );
    }
}

// add output table row
// function to add output disaggregations
function add_row_output() {
    $row = $("#output_table_body tr").length;
    $row = $row + 1;
    let randno = Math.floor(Math.random() * 1000 + 1);
    let $rowno = $row + "" + randno;
    $("#removeindoutputTr").remove();
    $("#output_table_body tr:last").after(
      `
        <tr id="${$rowno}">
              <td></td> 
              <td> 
                  <select name="output_dissagragation_type[]" id="output_dissagragation_type${$rowno}" onchange="output_dissagragation_type_change(${$rowno})" class="form-control show-tick output" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
                  </select> 
              </td> 
              <td>
                  <select name="output_dissagragation_parent[]" id="output_dissagragation_parent${$rowno}" onchange="output_dissagragation_parent_change(${$rowno})" class="form-control show-tick output_parent" style="border:1px #CCC thin solid; border-radius:5px">
                  </select>
              </td> 
              <td>
                  <input type="text" name="output_disaggregations[]" id="output_disaggregations${$rowno}" placeholder="Enter" class="form-control" required="required">
              </td> 
                  <td>
                      <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("${$rowno}")>
                          <span class="glyphicon glyphicon-minus"></span>
                      </button>
                  </td>
          </tr>`
    );
    number_output_table();
    get_disaggregations($rowno, (type = 4));
}

// function to number output table
function number_output_table() {
    $("#output_table_body tr").each(function (idx) {
        $(this)
        .children()
        .first()
        .html(idx - 1 + 1);
    });
}

// function to delete ouput rows
function delete_row_output(rowno) {
    output_match(rowno);
    $("#" + rowno).remove();
    number_output_table();
    $number = $("#output_table_body tr").length;
    if ($number == 1) {
        $("#output_table_body tr:last").after(
            '<tr id="removeindoutputTr"><td colspan="5" align="center"> Add Disaggregations</td></tr>'
        );
    }
}

// function to remove selected value if parent has been removed 
function output_match(rowno) {
    var disaggregations = $(`#output_dissagragation_type${rowno}`).val(); 
    if (disaggregations != "") {
        $(".output_parent").each(function () {
            if ($(this).val() != "") {
                if ($(this).val() == disaggregations) {
                    $(this).val("");
                }
            }
        });
        return true;
    } else {
        return true;
    }
}

// 
function output_dissagragation_type_change(rowno) {
    var disaggregations = $(`#output_dissagragation_type${rowno}`).val();
    var parent = $(`#output_dissagragation_parent${rowno}`).val();

    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".output").each(function () {
                if ($(this).val() != "") {
                    data.push($(this).val());
                }
            });

            var handler = data.includes(parent);

            if (!handler) {
                $(`#output_dissagragation_parent${rowno}`).val(""); 
                var indicatorid = `#output_disaggregations${rowno}`;
                var ind_diss = disaggregations; 
                indicator_disaggregation(ind_diss, indicatorid);
            }
        } else {
            $(`#output_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#output_dissagragation_parent${rowno}`).val("");
    }
}

// function that ensures that you do n
function output_dissagragation_parent_change(rowno) {
    var disaggregations = $(`#output_dissagragation_type${rowno}`).val();
    var parent = $(`#output_dissagragation_parent${rowno}`).val();
    
    if (disaggregations != "") {
        if (disaggregations != parent) {
            var data = [];
            $(".output").each(function () {
                if ($(this).val() != "") {
                data.push($(this).val());
                }
            });
            var handler = data.includes(parent);
            if (!handler) {
                $(`#output_dissagragation_parent${rowno}`).val("");
            }
        } else {
            $(`#output_dissagragation_parent${rowno}`).val("");
        }
    } else {
        $(`#output_dissagragation_parent${rowno}`).val("");
    }
}

function output_cat() {
    $.ajax({
        type: "POST",
        url: url,
        data: "get_diss_type=0",
        dataType: "html",
        success: function (response) {
            $("#output_directbeneficiarycat").html(response); 
        }
    });
}