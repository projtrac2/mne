var ajax_url = "ajax/inspection/inspect";

$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        var data = $(this)[0];
        var form = new FormData(data);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Success!");
                } else {
                    sweet_alert("Error!");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });

    $("#specification_issues").hide();
    $("#issues_table").html(`
        <tr></tr>
        <tr id="removeTr" class="text-center">
            <td colspan="4">Add Issues</td>
        </tr>`);

    $("#compliance").change(function (e) {
        e.preventDefault();
        var complaince = $(this).val();
        if (complaince == "2") {
            $("#specification_issues").show();
            add_issues();
        } else {
            $("#specification_issues").hide();
            $("#issues_table").html(`
            <tr></tr>
            <tr id="removeTr" class="text-center">
                <td colspan="4">Add Issues</td>
            </tr>`);
        }
    });
});

function inspect(details) {
    var specification_id = details.specification_id;
    var non_compliant = details.non_compliant;
    var specification = details.specification;
    $("#specification_id").val(specification_id);
    $("#specification_names").html(specification);

    if (non_compliant == "2") {
        $("#non_compliant_form").show();
        $(".compliance_form").hide();
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_issues: "get_issues",
                specification_id: specification_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#non_compliance_table").html(response.issues);
                }
            }
        });
    } else {
        $(".compliance_form").show();
        $("#non_compliant_form").hide();
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_previous_remarks: "get_previous_remarks",
                specification_id: specification_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#previous_remarks").html(response.issues);
                }
            }
        });
    }
}

function inspect_site(details){
    var projid= details.projid; 
    var output_id= details.output_id; 
    var design_id= details.design_id; 
    var site_id= details.site_id;
    var state_id= details.state_id;
    $("#projid").val(projid);
    $("#output_id").val(output_id);
    $("#design_id").val(design_id);
    $("#site_id").val(site_id);
    $("#state_id").val(state_id);
    $("#specification_issues").show();
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_previous_records: "get_previous_records",  
            design_id: design_id,
            site_id: site_id,
            state_id: state_id
        },
        dataType: "json",
        success: function (response) { 
            if (response.success) {
                $("#previous_records").html(response.previous_records);
            }
        }
    });
}

function general_inspect_site(details){
    var projid= details.projid; 
    var output_id= details.output_id; 
    var design_id= details.design_id; 
    var site_id= details.site_id;
    var state_id= details.state_id;  
    $("#projid").val(projid);
    $("#output_id").val(output_id);
    $("#design_id").val(design_id);
    $("#site_id").val(site_id);
    $("#state_id").val(state_id);
    $("#specification_issues").show();

    var project_name= details.project_name;
    var measurement_unit= details.measurement_unit;
    var output_name= details.output_name;
    var state_name= details.state_name;
    var site_name= details.site_name;
    
    $("#project_name").html(project_name);
    $("#output_name").html(output_name);
    $("#measurement_unit").html(measurement_unit);
    $("#state_name").html(state_name);
    $("#site_name").html(site_name);

    $.ajax({
        type: "get",
        url: ajax_url,
        data: {
            get_previous_records: "get_previous_records",  
            design_id: design_id,
            site_id: site_id,
            state_id: state_id
        },
        dataType: "json",
        success: function (response) { 
            if (response.success) {
                $("#previous_records").html(response.previous_records);
            }
        }
    });
}
 

function add_issues() {
    $("#issues_table #removeTr").remove();
    $rowno = $("#issues_table tr").length;
    $rowno = $rowno + 1;
    $listno = $rowno - 1;
    $("#issues_table tr:last").after(`
    <tr id="row${$rowno}">
        <td>${$listno} </td>
        <td>
            <div class="form-line">
                <select name="issue[]" id="issue${$rowno}" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
                    <option value="" selected="selected" class="selection">... Select ...</option> 
                </select>
            </div>
        </td>
        <td>
            <input type="text" name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Describe the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"  onclick=delete_issues("row${$rowno}")>
                <span class="glyphicon glyphicon-minus"></span>
            </button>
        </td>
    </tr>
    `);
    get_risk_category($rowno);
}

function delete_issues(rowno) {
    $('#' + rowno).remove();
    $rowno = $("#issues_table tr").length;
    if ($rowno == 1) {
        $("#issues_table").html(`<tr></tr><tr id="removeTr" class="text-center"><td colspan="4">Add Issues</td></tr>`);
    }
}

function get_risk_category(rowno) {
    var projid = $("#projid").val();
    var output_id = $("#output_id").val();
    if (output_id != '') {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_risk_category: "get_risk_category",
                projid: projid,
                output_id: output_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(`#issue${rowno}`).html(response.issues);
                }
            }
        });
    }
}

function add_attachment() {
    $rownm = $("#attachments_table tr").length;
    $rownm = $rownm + 1;
    $attno = $rownm;
    $("#attachments_table tr:last").after(`
        <tr id="rw${$rownm}">
            <td>${$attno}</td>
            <td>
                <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${$rownm}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
}

function delete_attach(rownm) {
    $('#' + rownm).remove();
}

function get_standard(standard_id){
if(standard_id!= ""){
    $.ajax({
        type: "get",
        url: ajax_url,
        data: {get_standard:"get_standard", standard_id:standard_id},
        dataType: "json",
        success: function (response) {
            if(response.success){
                $("#standrad_topic").html(response.standard);
                $("#modal_body_spec").html(response.description);
            }
        }
    });
}
}