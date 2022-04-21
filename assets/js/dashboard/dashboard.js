// get level 2
function conservancy() {
    var level1 = $("#projcommunity").val();
    if (level1 != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor.php",
            data: { get_level2: "get_level2", level1: level1 },
            dataType: "html",
            success: function (response) {
                $("#projlga").html(response);
            },
        });
    }
}

// get level 3
function ecosystem() {
    var level2 = $("#projlga").val();
    if (level2 != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor.php",
            data: { get_level3: "get_level3", level2: level2 },
            dataType: "html",
            success: function (response) {
                $("#projloc").html(response);
            },
        });
    }
}

// get financial year to 
function finyearfrom() {
    var fyfrom = $("#fyfrom").val();
    if (fyfrom != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor.php",
            data: { get_fyto: fyfrom },
            dataType: "html",
            success: function (response) {
                $("#fyto").html(response);
            },
        });
    }
}

// get projects for  particular department 
function get_projects() {
    var deptid = $("#department").val();
    var start_year = $("#fyfrom").val();
    var end_year = $("#fyto").val();
    if (deptid != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor.php",
            data: {
                get_dept_projects: deptid,
                start_year: start_year,
                end_year: end_year,
            },
            dataType: "html",
            success: function (response) {
                $("#projid").html(response);
            },
        });
    }
}

// get outputs for a particular project 
function get_outputs() {
    var projid = $("#projid").val();
    if (projid) {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor.php",
            data: {
                get_outputs: "get_outputs",
                projid: projid,
            },
            dataType: "json",
            success: function (response) {
                $("#outputs").html(response);
            }
        });
    }
}