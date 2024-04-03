var ajax_url = "ajax/projects/index";

$(document).ready(function () {
    hide_project_site_table(0);
    show_impact(param);
    var navListItems = $("div.setup-panel div a");
    var allWells = $(".setup-content");
    var allNextBtn = $(".nextBtn");
    allPrevBtn = $(".prev-step");
    allWells.hide();
    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr("href")),
            $item = $(this);
        if (!$item.hasClass("disabled")) {
            navListItems.removeClass("btn-primary").addClass("btn-default");
            $item.addClass("btn-primary");
            allWells.hide();
            $target.show();
            $target.find("input:eq(0)").focus();
        }
    });

    allNextBtn.click(function () {
        var prevStep;
        var curStep = $(this).closest(".setup-content");
        var curStepBtn = curStep.attr("id");
        var nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a");
        var curInputs = curStep.find("input, select");
        var isValid = false;
        if (curStepBtn == "step-1") {
            var project_id = $("#project_id").val();
            if (project_id != "") {
                isValid = step_1_verification();
            } else {
                error_alert("Please add project ");
            }
        }

        if (curStepBtn == "step-3") {
            isValid = true;
        }

        //Loop through all inputs in this form group and validate them.
        for (var i = 0; i < curInputs.length; i++) {
            if (!$(curInputs[i]).valid()) {
                isValid = false;
            }
        }

        if (isValid) {
            nextStepWizard.addClass("verified");
            nextStepWizard.removeClass("disabled").trigger("click");
        }
    });

    allPrevBtn.click(function (e) {
        var curStep = $(this).closest(".setup-content");
        curStepBtn = curStep.attr("id");
        prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]')
            .parent()
            .prev()
            .children("a");
        prevStepWizard.removeClass("disabled").trigger("click");
    });

    $("div.setup-panel div a.btn-primary").trigger("click");

    $("#project_details").validate({
        ignore: [],
        rules: {
            firstname: {
                required: true
            },
            projdesc: {
                ckeditor_required: true
            }
        },
        errorPlacement: function (error, element) {
            var lastError = $(element).data("lastError");
            var newError = $(error).text();
            $(element).data("lastError", newError);
            if (newError !== "" && newError !== lastError) {
                $(element).after('<div class="red">The field is Required</div>');
            }
        },
        success: function (label, element) {
            $(element).next(".red").remove();
        }
    });

    ////////////////
    // Save data
    ////////////////
    $("#project_details").submit(function (e) {
        e.preventDefault();
        var isValid = step_1_verification();
        if (isValid) {
            $.ajax({
                type: "post",
                url: ajax_url,
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $(".project_id").val(response.projid);
                        var project_sites = $('input[name="project_sites"]:checked').val();
                        project_sites == 1 ? $("#sites_list").val('1') : $("#sites_list").val('0');
                        $("#project_details_id").html("Edit");
                        success_alert("Created project successfully")
                    } else {
                        error_alert("Project could not be created, please try again");
                    }
                }
            });
        }
    });

    $("#files_details").submit(function (e) {
        e.preventDefault();
        var form = $('#files_details')[0];
        var form_data = new FormData(form);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Files added successfully");
                } else {
                    console.log("Error !!! Could not add Files");
                }
            }
        });
    });

    //

    //filter the expected output  cannot be selected twice
    $(document).on("change", ".lvidstates", function (e) {
        var tralse = true;
        var selectOutcome_arr = []; // for contestant name
        var attrb = $(this).attr("id");
        var selectedid = "#" + attrb;
        var selectedText = $(selectedid + " option:selected").html();

        $(".lvidstates").each(function (k, v) {
            var getVal = $(v).val();
            if (getVal && $.trim(selectOutcome_arr.indexOf(getVal)) != -1) {
                tralse = false;
                error_alert("You canot select Ward " + selectedText + " more than once");
                $(v).val("");
                return false;
            } else {
                selectOutcome_arr.push($(v).val());
            }
        });
        if (!tralse) {
            return false;
        }
    });
});


function disable_refresh() {
    return (window.onbeforeunload = function (e) {
        return "You cannot refresh the page";
    });
}


//function to put commas to the data
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
    }
    return val;
}


////////////////
// Step Verification
////////////////
function step_1_verification() {
    var isValid = false;
    var project_sites = $('input[name="project_sites"]:checked').val();
    if (project_sites == 1) {
        isValid = ($("input[name='site[]']").length > 0) ? true : false;
        !isValid ? error_alert("Please add project sites") : "";
    } else {
        isValid = true;
    }
    return isValid;
}



////////////////////////////////
//////////// Location Information
////////////////////////////////
// Function to validate ecosystem on change

function delete_sites() {
    var project_id = $("#project_id").val();
    var sites_list = $("#sites_list").val();

    if (project_id != "" && sites_list == "1") {
        swal({
            title: "Are you sure?",
            text: "This change will delete sites plan defined. Would you like to amend the selection?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Poof! Your imaginary file has been deleted!", {
                        icon: "success",
                    });
                } else {
                    swal("You have canceled the action!");
                }
            });
    }
}


function conservancy() {
    $("#project_sites_table_body").html('<tr></tr> <tr id="removeSTr" class="text-center"><td colspan="4"> Add Sites</td></tr>');
    var project_id = $("#project_id").val();
    var sites_list = $("#sites_list").val();
    if (project_id != "" && sites_list != "") {
        swal({
            title: "Are you sure?",
            text: "This change will delete sites plan defined. Would you like to amend the selection?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    get_community();
                } else {
                    // get_conservancy();
                }
            });
    } else {
        get_conservancy();
        $("#level1label").val("");
        $("#level2label").val("");
        $("#level3label").val("");
    }
}

function get_community(comm) {
    if (comm) {
        $.ajax({
            type: "post",
            url: ajax_url,
            data: `get_comm=${comm}`,
            dataType: "html",
            success: function (response) {
                $("#projcommunity").html(response);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}

// function to get ecosystem
function get_conservancy() {
    var scID = $("#projcommunity").val();
    if (scID.length > 0) {
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: "getward=" + scID,
            dataType: "html",
            success: function (html) {
                $("#projlga").html(html);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}

// get history ward
function get_hlevel2(ward) {
    var conservancy = $("#projcommunity").val();
    if (ward) {
        $.ajax({
            type: "post",
            url: ajax_url,
            data: { get_ward: ward, conservancy: conservancy },
            dataType: "html",
            success: function (response) {
                $("#projlga").html(response);
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
}


function show_impact(param) {
    if (param == 1) {
        $("#impact_div").show();
        $(".impact").attr("required", "required");
    } else {
        $("#impact_div").hide();
        $(".impact").removeAttr("required");
        $(".impact").prop("checked", false);
    }
}

////////////////
// Project Details
////////////////
// project code function check if code is already used
function validate_projcode() {
    var projcode = $("#projcode").val();
    $("#projcodemsg").hide();
    $("#projcodemsg").html("");
    if (projcode != "") {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_project_code: "get_project_code",
                projcode: projcode
            },
            dataType: "json",
            success: function (response) {
                if (response) {
                    $("#projcodemsg").show();
                    $("#projcodemsg").html("This project exists!! Please confirm if this is the correct project code");
                    $("#projcode").val("");
                }
            }
        });
    }
}

// Project duration change
function project_duration_validate() {
    var program_start_year = $("#progstartyear").val();
    var program_end_year = $("#progendyear").val();
    var program_duration = $("#program_duration").val();
    var project_start_year = $("#projfscyear1").val();
    var project_duration = $("#projduration1").val();

    if (program_start_year != "" && program_end_year != "") {
        program_start_year = parseInt(program_start_year);
        program_end_year = parseInt(program_end_year);
        if (project_start_year != "") {
            project_start_year = parseInt(project_start_year);
            if (project_duration != "" && parseInt(project_duration) > 0) {
                project_duration = parseInt(project_duration);
                $.ajax({
                    type: "get",
                    url: ajax_url,
                    data: {
                        get_project_end_year: "get_project_end_year",
                        program_start_year: program_start_year,
                        program_end_year: program_end_year,
                        project_start_year: project_start_year,
                        project_duration: project_duration
                    },
                    dataType: "json",
                    success: function (response) {
                        var remaining_duration = response.remaining_duration;
                        $("#projdurationmsg").html(remaining_duration);
                        $("#projendyear").val(response.project_end_year);
                        $("#project_duration").html(project_duration);
                        if (!response.success) {
                            $("#projduration1").val("");
                            $("#projendyear").val("");
                            error_alert("Sorry, you have exceeded program end year");
                        }
                    }
                });
            } else {
                $("#projdurationmsg").html(program_duration);
                $("#projduration1").val("");
                $("#projendyear").val("");
            }
        } else {
            error_alert("Select financial year first");
        }
    }
}


////////////////
// Sites
////////////////
function hide_project_site_table(param = "") {
    var param = $('input[name="project_sites"]:checked').val();
    if (param == 1) {
        $("#project_site_table").show();
    } else {
        $("#project_site_table").hide();
        $("#project_sites_table_body").html('<tr></tr><tr id="removeSTr" class="text-center"><td colspan="4"> Add Sites</td></tr>');
    }
}

// function to add financiers
function add_site_row() {
    $("#removeSTr").remove(); //new change
    $row = $("#project_sites_table_body tr").length;
    $row = $row + 1;
    var randno = Math.floor((Math.random() * 1000) + 1);
    var $rowno = $row + "" + randno;
    var locations = $("#projlga").val();

    if (locations.length > 0) {
        $("#project_sites_table_body tr:last").after(`
        <tr id="siterow${$rowno}">
            <td></td>
            <td>
                <select name="lvid[]" id="lvidrow${$rowno}" class="form-control lvidstates" required="required">
                    <option value="">Select Ward from list</option>
                </select>
            </td>
            <td>
                <input type="text" name="site[]" id="siterow${$rowno}"   placeholder="Enter sites separated by comma"  class="form-control" required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_sites("siterow${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
        number_sites_table();
        get_site_locations($rowno, selected = "");
    } else {
        error_alert("Please select  a Ward");
        $("#project_sites_table_body").html('<tr></tr> <tr id="removeSTr" class="text-center"><td colspan="4"> Add Sites</td></tr>');
    }
}

// function to delete financiers
function delete_row_sites(rowno) {
    $("#" + rowno).remove();
    number_sites_table();
    var number = $("#project_sites_table_body tr").length;
    if (number == 1) {
        $("#project_sites_table_body tr:last").after('<tr id="removeSTr" class="text-center"><td colspan="4"> Add Sites</td></tr>');
    }
}

// auto numbering table rows on delete and add new for financier table
function number_sites_table() {
    $("#project_sites_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}


function get_site_locations(rowno, selected = "") {
    var locations = $("#projlga").val();
    if (locations.length > 0) {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_site_locations: "locations",
                locations: locations,
            },
            dataType: "html",
            success: function (response) {
                if (rowno != "") {
                    $(`#lvidrow${rowno}`).html(response);
                } else {
                    $(".lvidstates").html(response);
                }
            },
        });
    } else {
        error_alert("Please select Ward ");
        $("#project_sites_table_body").html('<tr></tr><tr id="removeSTr" class="text-center"><td colspan="4"> Add Sites</td></tr>');
    }
}


////////////////
// Documents
////////////////

function add_row_files() {
    $row = $("#meetings_table tr").length;
    $row = $row + 1;
    var randno = Math.floor((Math.random() * 1000) + 1);
    var $rowno = $row + "" + randno;
    $("#meetings_table tr:last").after(`
        <tr id="mtng${$rowno}">
            <td></td>
            <td>
                <input type="file" name="pfiles[]" id="pfiles" multiple class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_files("mtng${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>' +
                </button>
            </td>
        </tr>`);
    numbering_files();
}

function delete_files(rowno) {
    $("#" + rowno).remove();
    numbering_files();
}

function numbering_files() {
    $("#meetings_table tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx + 1);
    });
}

function add_row_files_edit() {
    $("#add_new_file").remove();
    $row = $("#meetings_table_edit tr").length;
    $row = $row + 1;
    var randno = Math.floor((Math.random() * 1000) + 1);
    var $rowno = $row + "" + randno;

    $("#meetings_table_edit tr:last").after(`
        <tr id="mtng${$rowno}">
            <td></td>
            <td>
                <input type="file" name="pfiles[]" id="pfiles" multiple class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_files_edit("mtng${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
    numbering_files_edit();
}

function delete_files_edit(rowno) {
    $("#" + rowno).remove();
    numbering_files();
    var number = $("#meetings_table_edit tr").length;
    if (number == 1) {
        $("#meetings_table_edit tr:last").after('<tr id="add_new_file"><td colspan="4">Attach file </td></tr>');
    }
}

function delete_attachment(rowno) {
    var handler = confirm("Are you sure you want to delete the file");
    if (handler) {
        $("#" + rowno).remove();
    }
}

// auto numbering table rows on delete and add new for financier table
function numbering_files_edit() {
    $("#meetings_table_edit tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx - 1 + 1);
    });
}




////////////////
// Finish page
////////////////


// function to display project details
function project_details() {
    var prog = $("#prog").val();
    var projcode = $("#projcode").val();
    var projname = $("#projname").val();
    var projcase = $("#projcase").val();
    var projfocus = $("#projfocus").val();
    var projimplmethod = $("#projimplmethod>option:selected").text();
    var bigfour = $("#bigfour>option:selected").text();
    var projfscyear = $("#projfscyear1>option:selected").text();
    var projduration = $("#projduration1").val();
    var impact = $('input[name="impact"]:checked').val();
    var outcome = $('input[name="projevaluation"]:checked').val();
    (impact == 1) ? $("#impact").text("Yes") : $("#impact").text("No");;
    (outcome == 1) ? $("#projeval").text("Yes") : $("#projeval").text("No");;


    $("#progs").text(prog);
    $("#projcodes").text(projcode);
    $("#projName").text(projname);
    $("#projcases").text(projcase);
    $("#focusarea").text(projfocus);
    $("#implementation").text(projimplmethod);
    $("#bigfourA").text(bigfour);
    $("#projfscyears").text(projfscyear);
    $("#projdurations").text(commaSeparateNumber(parseInt(projduration)) + " Days");

    var projcommunity = [];
    $("select[name='projcommunity[]'] option:selected").each(function () {
        projcommunity.push($(this).text());
    });

    var projlga = [];
    $("select[name='projlga[]'] option:selected").each(function () {
        projlga.push($(this).text());
    });

    $("#projcommunitys").text(projcommunity);
    $("#projlgas").text(projlga);
}


// function to create files display
function attachments() {
    var attachment_purpose = [];
    $(".eattachment_purpose").each(function () {
        attachment_purpose.push($(this).val());
    });

    var file_name = [];
    $(".eattachment_file").each(function () {
        file_name.push($(this).val());
    });

    var files = "";
    var counter = 0;
    for (var i = 0; i < attachment_purpose.length; i++) {
        var attach_p = attachment_purpose[i];
        var f_name = file_name[i];
        counter++;
        files += `
        <tr>
            <td>${counter}</td>
            <td>${attach_p}</td>
            <td>${f_name}</td>
        </tr>`;
    }

    var file_pp = [];
    $(".attachment_purpose").each(function () {
        file_pp.push($(this).val());
    });

    var file_attachment = [];
    $(".file_attachment").each(function () {
        file_attachment.push($(this).val().replace(/.*(\/|\\)/, ''));
    });

    var counter_p = 0;
    for (var j = 0; j < file_attachment.length; j++) {
        counter_p++;
        var attach = file_pp[j];
        var name = file_attachment[j];
        files += `
        <tr>
            <td>${counter_p}</td>
            <td>${attach}</td>
            <td>${name}</td>
        </tr>`;
        $("#files_attached").html(files);
    }
}

// function to listen for the event handlers
function display_finish() {
    project_details();
    attachments();
}

