function add_row() {
  $rowno = $("#funding_table tr").length;
  $rowno = $rowno + 1;
  $("#funding_table tr:last").after(
    '<tr id="row' +
      $rowno +
      '"><td><input type="text" name="kra[]" id="kra" class="form-control"  placeholder="Enter the Key Results Area" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' +
      $rowno +
      '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>'
  );
}

function delete_row(rowno) { 
  $("#" + rowno).remove();
} 

function add_strow() {
	$rwno = $("#strategy_table tr").length;
	$rwno = $rwno + 1;
	$("#strategy_table tr:last").after(
		'<tr id="row' +
		$rwno +
		'"><td><input type="text" name="strategic[]" id="strategic" class="form-control"  placeholder="Strategic Objective" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_strow("row' +
		$rwno +
		'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>'
	);
}  
function delete_strow(rwno) { 
	$("#" + rwno).remove();
}

// tab functionality javascript files
$(document).ready(function() {
  //Initialize tooltips
  $(".nav-tabs > li a[title]").tooltip();

  //Wizard
  $('a[data-toggle="tab"]').on("show.bs.tab", function(e) {
    var $target = $(e.target);
    if ($target.parent().hasClass("disabled")) {
      return false;
    }
  });

  $("#strplan").click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "strategic-plan-process",
      data: $("#stratcplan").serialize(),
      success: function(response) {
        $("#objective_table").html(response);
        var $active = $(".wizard .nav-tabs li.active");
        $active.next().removeClass("disabled");
        nextTab($active);
      }
    });
  });
  
  $(".continue").click(function(e) {
    e.preventDefault();
    var $active = $(".wizard .nav-tabs li.active");
    $active.next().removeClass("disabled");
    nextTab($active);
  });

  //saving objectives to the database and getting the
  $("#objec").click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "strategic-plan-process",
      data: $("#objectiveplan").serialize(),
      success: function(response) {
        $("#strategy_table").html(response);
        var $active = $(".wizard .nav-tabs li.active");
        $active.next().removeClass("disabled");
        nextTab($active);
      }
    });
  });

  //saving strategies
  $("#str").click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "strategic-plan-process",
      data: $("#strategy").serialize(),
      success: function(response) {
        var $active = $(".wizard .nav-tabs li.active");
        $active.next().removeClass("disabled");
        nextTab($active);
      }
    });
  });

  $(".prev-step").click(function(e) {
    var $active = $(".wizard .nav-tabs li.active");
    prevTab($active);
  });
});

function nextTab(elem) {
  $(elem)
    .next()
    .find('a[data-toggle="tab"]')
    .click();
}

function prevTab(elem) {
  $(elem)
    .prev()
    .find('a[data-toggle="tab"]')
    .click();
}
