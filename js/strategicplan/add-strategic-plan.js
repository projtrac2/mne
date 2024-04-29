function add_row() {
  $rowno = $("#funding_table tr").length;
  $rowno = $rowno + 1;
  $("#funding_table tr:last").after(`
    <tr id="row${$rowno}">
        <td>
          <input type="text" name="kra[]" id="kra" class="form-control"  placeholder="Enter the Key Results Area" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row${$rowno}")>
            <span class="glyphicon glyphicon-minus"></span>
          </button>
        </td>
    </tr>`);
}

function delete_row(rowno) {
  $("#" + rowno).remove();
}

function add_strow() {
  $rwno = $("#strategy_table tr").length;
  $rwno = $rwno + 1;
  $("#strategy_table tr:last").after(
    `<tr id="row${$rwno}">
      <td>
        <input type="text" name="strategic[]" id="strategic" class="form-control"  placeholder="Strategic Objective" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
      </td>
      <td>
        <button type="button" class="btn btn-danger btn-sm"  onclick=delete_strow("row${$rwno}")>
          <span class="glyphicon glyphicon-minus"></span>
        </button>
      </td>
    </tr>`);
}

function delete_strow(rwno) {
  $("#" + rwno).remove();
}

$(document).ready(function () {
  $("#stryear").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    startView: 'decade',
    viewSelect: 'decade',
    autoclose: true,
  });
});

function validate() {
  const end_year = $("#end_year").val();;
  var year = $("#stryear").val();
  if (year != "") {
    if (year <= end_year) {
      $("#stryear").val("");
      swal("Starting year should be greater than the final year of the previous <?= $planlabel ?>");
    }
  }
}