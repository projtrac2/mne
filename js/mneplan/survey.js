var surveyQuestionsTable;
	
var projid = $("#projid").val();
var resultstype = $("#resultstype").val();
var resultstypeid = $("#resultstypeid").val();
var url3 = "ajax/mneplan/index?fetchsurveyquestions=1&projid=" + projid + "&resultstype=" + resultstype + "&resultstypeid=" + resultstypeid;

$(document).ready(function () {
	$("#mainquestion").hide();
    $("#answer_label").hide();
    $("#calculation_method").hide();
    $("#questions").hide();

    surveyQuestionsTable = $("#survey_questions_table").DataTable({
        ajax: url3,
        order: [],
        'columnDefs': [{
            'targets': [6],
            'orderable': false,
        }]
    });
	
	check_evaluation_questions(projid, resultstype, resultstypeid);
	
	// /submit survey questions form
    $("#add_evaluation_questions_form").on("submit", function (event) {
        event.preventDefault();
		$("#question-tag-form-submit").prop("disabled", true);
        var form_data = $(this).serialize();
        var form = $(this);
        var formData = new FormData(this);
        $.ajax({
            url:  "ajax/mneplan/add-monitoring-evaluation-plan-processor",
            type: form.attr("method"),
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#add_evaluation_questions_form")[0].reset();
					$("#question-tag-form-submit").prop("disabled", false);
                    $(".modal").each(function () {
                        $(this).modal("hide");
                    });
                    swal(response.msg);
					surveyQuestionsTable.ajax.reload(null, true);
                    setTimeout(3000);
                } // /if response.success
            } // /success function
        }); // /ajax function
        // /if validation is ok
		$("#submit_evaluation_form").prop("disabled", false);
	
		// add projstatus modal btn clicked
		//$("#addQuestionsModal")
		//.unbind("click")
		//.bind("click", function() {
		  // // projstatus form reset
		  //$("#add_evaluation_questions_form")[0].reset();

		  // remove text-error
		  //$(".text-danger").remove();
		  // remove from-group error
		  //$(".form-input")
		  //  .removeClass("has-error")
		  //  .removeClass("has-success");
        return false;
    }); // /submit survey questions form
});

function get_survey_questions(url3) {
    surveyQuestionsTable = $("#survey_questions_table").DataTable({
        ajax: url3,
        order: [],
        'columnDefs': [{
            'targets': [6],
            'orderable': false,
        }]
    });
}

function resetModalContent() {
  // Clear or reset the content of the modal here
  //document.getElementById('modalInput').value = '';
}

function count_questions(projid = null, resultstype = null, resultstypeid = null) {
	$.ajax({
		url:  "ajax/mneplan/index",
		type: "get",
		data: { check_evaluation_questions: 1, projid: projid, resultstype: resultstype, resultstypeid: resultstypeid },
        dataType: "json",
		success: function (response) {
			if (response.success) {
				$("#questions").show();
				var fields = document.querySelectorAll('.main_question_count');
				// Loop through each field and set the 'required' attribute
				fields.forEach(function(field) {
					field.required = true;
				});
			} else {
				$("#questions").hide();
				$('.main_question_count').removeAttr('required');
			}
		} // /success function
	}); // /ajax function
}

function add_question_type() {
    var questiontype = $("#question_type").val();
    if (questiontype == 1) {
        $('#main_question').removeAttr('required');
        $("#mainquestion").hide();
    } else {
        $("#mainquestion").show();
        const input = document.getElementById('main_question');
        input.setAttribute('required', '');
    }
}

function check_evaluation_questions(projid = null, resultstype = null, resultstypeid = null) {
	$.ajax({
		url:  "ajax/mneplan/index",
		type: "get",
		data: { check_evaluation_questions: 1, projid: projid, resultstype: resultstype, resultstypeid: resultstypeid },
        dataType: "json",
		success: function (response) {
			if (response.success) {
				$("#submit_evaluation_form").prop("disabled", false);
			} else {
				$("#submit_evaluation_form").prop("disabled", true);
			}
		} // /success function
	}); // /ajax function
}

function add_answer_type() {
    var answertype = $("#answertype").val();
	//console.log(answertype);
    if (answertype == 1) {
        const answer_label_input = document.getElementById('answerlabel');
        answer_label_input.setAttribute('required', '');
        const calculation_method_input = document.getElementById('calc_method');
        calculation_method_input.setAttribute('required', '');
        $("#answer_label").hide();
        $('#answerlabel').removeAttr('required');
        $("#calculation_method").show();
        $('#calc_method').removeAttr('readonly');
		/* $("#calc_method").html('<option value="">... Select ...</option><option value="1">Summation [Addition]</option><option value="2">Median [Middle Value]</option><option value="3">Mode [Most Common Value]</option><option value="4">Mean [Average]</option><option value="5">Counting [Counting the Occurrences]</option>'); */
    /* } else if (answertype == 2 || answertype == 4) {
        const answer_label_input = document.getElementById('answerlabel');
        answer_label_input.setAttribute('required', '');
        const calculation_method_input = document.getElementById('calc_method');
        calculation_method_input.setAttribute('required', '');
        calculation_method_input.setAttribute('readonly', '');
		$("#calc_method").html('<option value="2" selected>Percentage</option>');
        $("#answer_label").show();
        $("#calculation_method").show(); */
    } else if (answertype == 2 || answertype == 3 || answertype == 4) {
        const answer_label_input = document.getElementById('answerlabel');
        answer_label_input.setAttribute('required', '');
        $("#answer_label").show();
        $("#calculation_method").hide();
        $('#calc_method').removeAttr('required');
    } else {
        $("#answer_label").hide();
        $("#calculation_method").hide();
        $('#answerlabel').removeAttr('required');
        $('#calc_method').removeAttr('required');
    }
}

function edit_row_question(questionId = null,formtype = null) {
    if (questionId) {
        /* var heading = document.getElementById('impactmodaltitle');
        heading.textContent = "Edit Impact Details"

        var input = document.getElementById('tag-form-submit');
        input.setAttribute('name', 'Update'); */

        var url = "ajax/mneplan/index";
        var title, addform, editform, modalclass, editformname;
		var question_status = $("#question_status").val();
		title = "Edit " + formtype + " Survey Question";
		if(question_status == 0) {
		addform = "add_evaluation_questions_form";
		} else {
			addform = "edit_evaluation_questions_form";
		}
		editform = "edit_evaluation_questions_form";
		editformname = "edit_evaluation_questions_form";
		modalclass = "questionmodaltitle";

        $.ajax({
            type: "post",
            url: url,
            data: { questionId: questionId, evalquestionform: 1 },
            dataType: "html",
            success: function (response) {
                // modal div
                $("#" + addform).html(response);
                $("." + modalclass).html(title);
                $('.selectpicker').selectpicker();
				
				var question_type = $("#question_type").val();
				if ( question_type == 1 ) {
					$("#mainquestion").hide();
					$('#main_question').removeAttr('required');
				} else {
					$("#mainquestion").show();
				}
				add_answer_type();
				
				var editdata = $("#edit_evaluation_questions").val();

                const formname = document.getElementById(addform);
                formname.setAttribute('id', editform);
                formname.setAttribute('name', editformname);

                /* ====== LOAD NEW FILE OR RELOAD A FILE ======= *
                var s = document.createElement('script');
                s.src = 'assets/js/mneplan/new-page-js.js';
                document.body.appendChild(s); */

                /* ====== LOAD A FUNCTION ======= *
                $('#impactdataSource').add_impact_questions();  */
                $("#" + editform)
                    .unbind("submit")
                    .bind("submit", function (e) {
                        // form validation
                        e.preventDefault();
						$("#question-tag-form-submit").prop("disabled", true);
                        if (editdata) {
                            var form = $(this);
                            var formData = new FormData(this);

                            $.ajax({
								url: "ajax/mneplan/add-monitoring-evaluation-plan-processor",
                                type: form.attr("method"),
                                data: formData,
                                dataType: "json",
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                    if (response) {
										$("#edit_evaluation_questions_form")[0].reset();
										$("#question-tag-form-submit").prop("disabled", false);

                                        $(".modal").each(function () {
                                            $(this).modal("hide");
                                        });
                                        swal(response.msg);
                                        setTimeout(3000);
										surveyQuestionsTable.ajax.reload(null, true);
                                    } // /success function
                                } // /success function
                            }); // /ajax function
                        } // /if validation is ok
						$("#submit_evaluation_form").prop("disabled", false);

                        return false;
                    }); // update the Project Main Menu  data function
            } // /success function
        }); // /ajax to fetch Project Main Menu  image
    } else {
        swal('Error creating record!!');
        setTimeout(3000);
    }
	
	// add projstatus modal btn clicked
	$("#addQuestionsModal")
    .unbind("click")
    .bind("click", function() {
      // // projstatus form reset
      $("#edit_evaluation_questions_form")[0].reset();

      // remove text-error
      //$(".text-danger").remove();
      // remove from-group error
      //$(".form-input")
      //  .removeClass("has-error")
      //  .removeClass("has-success");
    }); // /add projstatus modal btn clicked
} // /edit Project Main Menu  function


// Delete survey question
function delete_row_question(questionid = null, projid = null, resultstype = null, resultstypeid = null) {
    swal({
        title: "Are you sure you want to delete the question?",
        text: `Once deleted the record can not be recovered!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: 'ajax/mneplan/add-monitoring-evaluation-plan-processor',
                    data: {
                        deleteQuestion: 1,
                        questionid: questionid
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
							surveyQuestionsTable.ajax.reload(null, true);
							check_evaluation_questions(projid, resultstype, resultstypeid);
                            swal({
                                title: "Question ",
                                text: "Successfully deleted",
                                icon: "success",
                            });
                        } else {
                            swal({
                                title: "Question ",
                                text: "Error deleting",
                                icon: "error",
                            });
                        }
                        setTimeout(3000);
                    },
                });
            } else {
                swal("You cancelled the action!");
            }
        });
}



// sweet alert notifications
function success_alert(msg) {
    return swal({
        title: "Success",
        text: msg,
        type: "Success",
        icon: 'success',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
    setTimeout(function () { }, 15000);
}

// sweet alert notifications
function sweet_alert(err, msg) {
    return swal({
        title: err,
        text: msg,
        type: "Error",
        icon: 'warning',
        dangerMode: true,
        timer: 15000,
        showConfirmButton: false
    });
    setTimeout(function () { }, 15000);
}