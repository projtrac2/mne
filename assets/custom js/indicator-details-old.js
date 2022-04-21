$(document).ready(function () {
    var urlpath = window.location.pathname;
    var filename = urlpath.substring(urlpath.lastIndexOf('/') + 1);

    if (filename == "add-indicators.php") {
        $("#aggreg").hide();
        $("#levels").hide();
        $("#output_details").hide();
        $("#other_details").hide();
    }


    $(".account").click(function () {
        var X = $(this).attr('id');

        if (X == 1) {
            $(".submenus").hide();
            $(this).attr('id', '0');
        } else {

            $(".submenus").show();
            $(this).attr('id', '1');
        }

    });

    //Mouseup textarea false
    $(".submenus").mouseup(function () {
        return false
    });
    $(".account").mouseup(function () {
        return false
    });

    //Textarea without editing.
    $(document).mouseup(function () {
        $(".submenus").hide();
        $(".account").attr('id', '');
    });

    $('#projcommunity').on('change', function () {
        var scID = $(this).val();
        if (scID) {
            $.ajax({
                type: 'POST',
                url: 'addProjectLocation.php',
                data: 'sc_id=' + scID,
                success: function (html) {
                    $('#projlga').html(html);
                    $('#projstate').html('<option value="">Select Ward first</option>');
                }
            });
        } else {
            $('#projlga').html('<option value="">Select Sub-County first</option>');
            $('#projstate').html('<option value="">Select Ward first</option>');
        }
    });

    $('#projlga').on('change', function () {
        var wardID = $(this).val();
        if (wardID) {
            $.ajax({
                type: 'POST',
                url: 'addProjectLocation.php',
                data: 'ward_id=' + wardID,
                success: function (html) {
                    $('#projstate').html(html);
                }
            });
        } else {
            $('#projstate').html('<option value="">Select Ward first</option>');
        }
    });

    $('#indsector').on('change', function () {
        var sctID = $(this).val();
        if (sctID) {
            $.ajax({
                type: 'POST',
                url: 'indicator-details.php',
                data: 'sct_id=' + sctID,
                success: function (html) {
                    $('#inddept').html(html);
                }
            });
        } else {
            $('#inddept').html('<option value="">Select Sector first</option>');
        }
    });


    // get department
    $("#indtype").on("change", function () {
        var indtype = $(this).val();
        if (indtype) {
            jQuery.ajax({
                url: "indicator-details.php",
                data: 'indtype=' + indtype,
                type: "POST",
                success: function (data) {
                    if (data) {
                        $("#indcategory").html(data);
                    }
                },
                error: function () { }
            });
        }
    });

    $("#indtype").change(function (e) {
        e.preventDefault();
        var itype = $(this).val();
        var indcategory = $("#indcategory").val();
        $("#inddept").html('<option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>');

        if (itype == 1) {
            // show kpi details 
            $("#aggreg").show();
            $("#aggreg .form-control").each(function () {
                $(this).attr("required", "required");
                $(this).val("");
            });

            // hide others details 
            $("#levels").hide();
            $("#levels .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });

        } else if (itype == 2) {
            // hide  kpi details 
            $("#aggreg").hide();
            $("#aggreg .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });

            // show others details 
            $("#levels").show();
            $("#levels .form-control").each(function () {
                $(this).attr("required", "required");
                $(this).val("");
            });
        } else {
            // hide  kpi details 
            $("#aggreg").hide();
            $("#aggreg .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });

            // hide others details 
            $("#levels").hide();
            $("#levels .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });
        }
    });

    $("#indcategory").change(function (e) {
        e.preventDefault();
        var indcategory = $("#indcategory").val();
        var indtype = $("#indtype").val();

        if (indtype == 2 && indcategory != "") {
            if (indcategory == "Output") {
                $("#output_details").show();
                $("#output_directBenfType").attr("required", "required");

                $("#output_dissegragate").hide();
                $("#output_dissegragate").removeAttr("required");
                // hide other details from other categories  and remove reqired and empty fields 
                $("#other_details").hide();
                $("#other_details .form-control").each(function () {
                    $(this).removeAttr("required");
                    $(this).val("");
                });
            } else {
                // show other details from other categories 
                $("#other_details").show();
                // hide output details and remove reqired and empty fields 
                $("#output_details").hide();
                $("#output_details .form-control").each(function () {
                    $(this).removeAttr("required");
                    $(this).val("");
                });

                // hide direct dissegragation type and  dissagragate names and empty fields and remove required
                $("#directbeneficiary").hide();
                $("#directbeneficiary .form-control").each(function () {
                    $(this).removeAttr("required")
                    $(this).val("");
                });

                // hide indirect dissegragation type and  dissagragate names and empty fields and remove required
                $("#indirectbeneficiary").hide();
                $("#indirectbeneficiary .form-control").each(function () {
                    $(this).removeAttr("required");
                    $(this).val("");
                });

            }
        } else {
            // hide output details and remove reqired and empty fields 
            $("#output_details").hide();
            $("#output_details .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });

            // hide otherdetails and empty and remove required
            $("#other_details").hide();
            $("#other_details .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });
        }
    });

    $("#output_directBenfType").change(function (e) {
        e.preventDefault();
        var output_directBenfType = $(this).val();
        if (output_directBenfType != "") {
            if (output_directBenfType == 1) {
                $("#output_dissegragate").show();
                $("#output_directbeneficiarycat").attr("required", "required");
                $.ajax({
                    type: "POST",
                    url: "indicator-details.php",
                    data: "get_diss_type=0",
                    dataType: "html",
                    success: function (response) {
                        $("#output_directbeneficiarycat").html(response);
                    }
                });

            } else {
                $("#output_dissegragate").hide();
                $("#output_dissegragate").removeAttr("required");
            }
        } else {
            $("#output_dissegragate").hide();
            $("#output_dissegragate").removeAttr("required");
        }
    });

    $("#indindirectBenfType").change(function (e) {
        e.preventDefault();
        var indbentype = $(this).val();
        var indirectbeneficiaryName = $("#indirectbeneficiaryName").val();
        if (indirectbeneficiaryName != "") {
            if (indbentype != "") {
                if (indbentype == 0) {
                    $("#indirectbeneficiary .form-control").each(function () {
                        $(this).val("");
                        $(this).removeAttr("required");
                    });
                    $("#indirectbeneficiary").hide();
                    $("#indbfmsg").html("");
                } else if (indbentype == 1) {
                    $("#indirectbeneficiary .form-control").each(function () {
                        $(this).val("");
                        $(this).attr("required", "required");
                    });
                    $("#indirectbeneficiary").show();
                    $("#indbfmsg").html("");
                    $.ajax({
                        type: "POST",
                        url: "indicator-details.php",
                        data: "get_diss_type=1",
                        dataType: "html",
                        success: function (response) {
                            $("#indirectbeneficiarycat").html(response);
                        }
                    });
                }
            } else {
                $("#indirectbeneficiary .form-control").each(function () {
                    $(this).val("");
                    $(this).removeAttr("required");
                });
                $("#indirectbeneficiary").hide();
                $("#indbfmsg").html("");
            }
        } else {
            $("#indirectbeneficiary .form-control").each(function () {
                $(this).val("");
                $(this).removeAttr("required");
            });
            $("#indirectbeneficiary").hide();
            $(this).val("");
            $("#indbfmsg").html("Enter indirect beneficiary name first ");
        }
    });

    $("#indirectbeneficiaryName").keyup(function (e) {
        e.preventDefault();
        if ($(this).val() == "") {
            // hide indirect dissegragation type and  dissagragate names and empty fields and remove required
            $("#indirectbeneficiary").hide();
            $("#indirectbeneficiary .form-control").each(function () {
                $(this).removeAttr("required");
                $(this).val("");
            });
            $("#indindirectBenfType").val("");
        }
    });

    $("#inddirectBenfType").change(function (e) {
        e.preventDefault();
        var dbentype = $(this).val();
        if (dbentype != "") {
            if (dbentype == 0) {
                $("#directbeneficiary .form-control").each(function () {
                    $(this).val("");
                    $(this).removeAttr("required");
                });
                $("#directbeneficiary").hide();
            } else if (dbentype == 1) {
                $("#directbeneficiary .form-control").each(function () {
                    $(this).val("");
                    $(this).attr("required", "required");
                });
                $("#directbeneficiary").show();
                $.ajax({
                    type: "POST",
                    url: "indicator-details.php",
                    data: "get_diss_type=1",
                    dataType: "html",
                    success: function (response) {
                        $("#directbeneficiarycat").html(response);
                    }
                });
            }
        } else {
            $("#directbeneficiary .form-control").each(function () {
                $(this).val("");
                $(this).removeAttr("required");
            });
            $("#directbeneficiary").hide();
        }
    });
});

jQuery(document).ready(function () {
    jQuery('.tabs .tab-links a').on('click', function (e) {
        var currentAttrValue = jQuery(this).attr('href');

        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

        e.preventDefault();
    });
});

function checkAvailability() {
    $("#loaderIcon").show();
    var indcode = $.trim($("#indcode").val());
    jQuery.ajax({
        url: "indicator-details.php",
        data: 'indcode=' + indcode,
        type: "POST",
        success: function (data) {
            if (data) {
                $("#addindfrm")[0].reset();
                $("#code-availability-status").html(data);
                $("#loaderIcon").hide();
            } else {
                if (indcode != '') {
                    $("#code-availability-status").html("<label>&nbsp;</label><div class='alert bg-green alert-dismissible' role='alert' style='height:35px; padding-top:5px'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>This Indicator Code (" + indcode + ") is valid and can only be used once</div>");
                    $("#loaderIcon").hide();
                } else if (indcode == '') {
                    $("#code-availability-status").html("");
                    $("#loaderIcon").hide();
                }
            }
        },
        error: function () { }
    });
}

const adddetails = (data, dissegration_category = 1, type_diss = 0) => {
    if (data == "unit") {
        // show unit details 
        $("#unitsof_measure").show();
        $("#unit").attr("required", "required");
        $("#unitdescription").attr("required", "required");

        // rename the input name and give it a value 
        $("#addnew").attr("name", "addunit");
        $("#addnew").val("addunit");

        // hide dissagragatio details fields and remove required field 
        $("#diss_type").hide();
        $("#diss_type_name").removeAttr("required");
        $("#type_diss").val("");
        $("#dissegration_category").val("");
    } else {
        // show unit details fields and remove required field 
        $("#unitsof_measure").hide();
        $("#unit").removeAttr("required");
        $("#unitdescription").removeAttr("required");
        $("#unit").val("");
        $("#unitdescription").val("");


        // show dissagragatio details fields and remove required field 
        $("#diss_type").show();
        $("#diss_type_name").attr("required", "required");
        $("#type_diss").val(type_diss);
        $("#dissegration_category").val(dissegration_category);

        // rename the input name and give it a value 
        $("#addnew").attr("name", "add_type_diss");
        $("#addnew").val("add_type_diss");
    }
}

$("#addform").submit(function (e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    var addnew = $("#addnew").val();
    var dtype = $("#dissegration_category").val();
    var type_diss = $("#type_diss").val();
    if (addnew == "addunit") {
        $.ajax({
            type: "post",
            url: "indicator-details.php",
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.msg == true) {
                    measurement_unit();
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                } else {
                    alert("Error while saving data ");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                }
            }
        });
    } else if (addnew == "add_type_diss") {
        $.ajax({
            type: "post",
            url: "indicator-details.php",
            data: form_data,
            dataType: "json",
            success: function (response) {
                if (response.msg == true) {
                    if (dtype == 0) {
                        output_cat();
                    } else if (dtype == 1) {
                        others_cat(type_diss);
                    } else {
                        console.log("error");
                    }

                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                } else {
                    alert("Error while saving data ");
                    $(".modal").each(function () {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });
                }

            }
        });
    } else {
        alert("Error while trying to save data");
        $(".modal").each(function () {
            $(this).modal("hide");
            $(this)
                .find("form")
                .trigger("reset");
        });
    }
});

const output_cat = () => {
    $.ajax({
        type: "POST",
        url: "indicator-details.php",
        data: "get_diss_type=0",
        dataType: "html",
        success: function (response) {
            $("#output_directbeneficiarycat").html(response);
        }
    });
}

const others_cat = (data) => {
    var id = '';
    if (data == "direct") {
        id = "#directbeneficiarycat";
    } else {
        id = "#indirectbeneficiarycat";
    }

    $.ajax({
        type: "POST",
        url: "indicator-details.php",
        data: "get_diss_type=1",
        dataType: "html",
        success: function (response) {
            $(id).html(response);
        }
    });
}

const measurement_unit = () => {
    $.ajax({
        type: "POST",
        url: "indicator-details.php",
        data: "get_unit",
        dataType: "html",
        success: function (response) {
            $("#indunit").html(response);
        }
    });
} 