const ajax_url = 'ajax/payments/index';


$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        var purpose = $("#purpose").val();
        var msg = purpose != '' && purpose == '1' ? "Approved" : "Rejected";
        $.ajax({
            type: "post",
            url: ajax_url,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert(`Successfully ${msg}`);
                } else {
                    success_alert("Error occured kindly repeat the process")
                }

                setTimeout(() => {
                    window.location.reload(true)
                }, 3000);
            }
        });
    });

    $("#modal_form_submit_disburse").submit(function (e) {
        e.preventDefault();
        var form = $(this)[0];
        var form_details = new FormData(form);
        $.ajax({
            type: "post",
            url: "ajax/payments/index",
            data: form_details,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Congratulations you have disbursed the budgetline");
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 100);
                } else {
                    alert("Sorry record could not be created");
                }
            }
        });
    });
});

//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}

function get_approval_details(request_id, stage, amount_requested, requested_amount) {
    $("#stage_id").val(stage);
    $("#request_id").val(request_id);
    $("#h_requested_amount").val(amount_requested);
    $("#a_requested_amount").val(requested_amount);
    $("#status_id").attr("required", "required")
    $("#status_id").val("");
    if (stage == "2") {
        $("#purpose_div").hide("required");
        $("#status_id").removeAttr("required");
        $("#status_id").val(1);
        $("#status_id").attr("disabled", "disabled");
    }
}


const get_more_info = (request_id) => {
    if (request_id != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_more_info: "get_more_info",
                request_id: request_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var stage = response.details.stage;
                    var status = response.details.status;
                    var purpose = response.details.purpose;
                    var request_amount = response.request_amount;

                    var payment_stage = '';
                    if (stage == 1) {
                        payment_stage = 'CO Department';
                    } else if (stage == 2) {
                        payment_stage = 'CO Finance';
                    } else if (stage == 3) {
                        payment_stage = 'Director Finance';
                    }

                    var payment_status = '';
                    if (status == 1) {
                        payment_status = 'Pending';
                    } else if (status == 2) {
                        payment_status = 'Canceled';
                    } else if (status == 3) {
                        payment_status = 'Disbursed';
                        $("#payment_mode").html(response.disbursement.payment_mode);
                        $("#receipt_no").html(response.disbursement.receipt_no);
                        $("#receipt").attr("href", response.disbursement.receipt);
                        $("#date_paid").html(response.disbursement.date_paid);
                    }

                    var payment_purpose = '';
                    if (purpose == 1) {
                        payment_purpose = 'Direct Cost';
                    } else if (purpose == 2) {
                        payment_purpose = 'Administrative Cost';
                    } else if (purpose == 3) {
                        payment_purpose = 'Mapping Cost';
                    } else if (purpose == 4 || purpose == 5) {
                        payment_purpose = 'Evaluation Cost';
                    }
                    var fullname = response.details.ttitle + ' ' + response.details.fullname;

                    //

                    $("#comments_div").html(response.comments);
                    $("#project_name").html(response.project_details.projname);
                    $("#project_code").html(response.project_details.projcode);
                    $("#requested_amount").html(response.details.amount_requested);
                    $("#requested_by").html(fullname);
                    $("#date_requested").html(response.details.date_requested);
                    $("#due_date").html(response.details.due_date);
                    $("#stage").html(payment_stage);
                    $("#status").html(payment_status);
                    $("#purpose").html(payment_purpose);

                    if (purpose == '1') {
                        $("#milestone_table").html(response.details.milestones);
                        $("#request_amount").val(response.details.request_amount);
                        $("#request_percentage").val(response.details.request_percentage);
                        $("#requested_amount").val(response.details.request_amount);
                        $("#payment_phase").html(response.details.payment_phases);
                    } else {
                        $("#tasks_table").html(response.data);
                        $("#requested_amount").val(response.details.amount_requested);
                        $("#subtotal").html(request_amount);
                    }
                } else {
                    success_alert("No data found !!!")
                }
            }
        });
    } else {
        success_alert("Ensure that the request is correct");
    }
}