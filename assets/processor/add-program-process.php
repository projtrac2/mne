<?php
include_once "controller.php";

if (isset($_POST['get_independent'])) {
    //get financial years 
    $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year");
    $query_rsYear->execute();
    $row_rsYear = $query_rsYear->fetch();
    $totalRows_rsYear = $query_rsYear->rowCount();
    $fact = '';
    $fact = '
    <div class="col-md-6">
    <label class="control-label">Program Start Year *:</label>
    <div class="form-line">
        <select name="syear" id="syear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
            <option value="">.... Select Year from list ....</option>';
    $currentYear = '';
    $month =  date('m');
    if ($month  < 7) {
        $currentYear =  date("Y") - 1;
    } else {
        $currentYear =  date("Y");
    }

    do {
        $currentYear =  date("Y") - 1;
        $yearVal = $row_rsYear['yr'];
        if ($yearVal >= $currentYear) {
            $fact .= '<option value="' . $yearVal . '">' . $row_rsYear['year'] . '</option>';
        }
    } while ($row_rsYear = $query_rsYear->fetch());

    $fact .= '</select>
        <span id="projfscyearmsg" style="color:red"></span>
    </div>
    </div>
    <div id="theadyears"></div>
    <div class="col-md-6">
        <label for="years">Program Duration In Years *:</label>
        <div class="form-line">
            <input type="text" name="years" id="years" placeholder="Program Duration" class="form-control" required>
            <span id="info1" style="color:red"></span>
        </div>
    </div>';

    $fact .= '<script>
    $(document).ready(function() {
        $("#projfscyearmsg").hide();
        $("#programworkplan").hide();
    });

    $("#syear").change(function(e) {
        var programDuration = $("#years").val(); //  program  duration in years 
        var syear = $(this).val(); // program start year
        var programStartYear = parseInt(syear); //program start year number 												

        if (syear != "" && programDuration != "") {
            programDuration = parseInt(programDuration);
            var programEndYear = ((programStartYear + programDuration) - 1);
            $("#info1").hide();
            $("#projfscyearmsg").hide();
            $("#program").show();
            $("#programworkplan").show();
            var dispyear = parseInt(syear);
            var finyear = parseInt(syear) + 1;
            var containerH = $(\'<tr class="source bg-grey">\' +
                \'<th rowspan="2">OutputOutput&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                \'<th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
            var content = $(\'<tr>\');
            var contain = $(\'<div>\');
            for (var i = 0; i < programDuration; i++) {
                containerH.append(\'<th colspan="2"> \' + dispyear + "/" + finyear + \'</th>\');
                content.append(\'<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                    \'<th>Budget (ksh)) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                contain.append(\'<input type="hidden" name="progyear[]" value="\' + dispyear + \' " />\');
                dispyear++;
                finyear++;
            }
            containerH.append(\'<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> \' +
                \'</tr>\');

            content.append(\'</tr>\');
            contain.append(\'</div>\');
            $("#theadyears").html(contain);
            Array.prototype.push.apply(containerH, content);
            $(".thead").html(containerH);

        } else if (syear != \'\' && programDuration == \'\') {
            $("#info1").show();
            $("#projfscyearmsg").hide();
            $("#program").hide();
            $("#info1").html("Enter Program Duration");
        } else {
            $("#projfscyearmsg").show();
            $("#projfscyearmsg").html("Select Financial Year first");
            $("#info1").html("Select Financial Year first");
            $("#program").hide();
            $("#years").val("");
        }
    });

    $("#years").keyup(function(e) {
        var syear = $("#syear").val(); // program start year
        var programDuration = $(this).val(); // program  duration in years 
        var programStartYear = parseInt(syear); //program start year number 												

        if (syear != "") {
            if (programDuration != "") {
                programDuration = parseInt(programDuration);
                if (programDuration > 0) {
                    var programEndYear = ((programStartYear + programDuration) - 1);
                    $("#info1").hide();
                    $("#programworkplan").show();
                    $("#program").show();
                    var dispyear = parseInt(syear);
                    var finyear = parseInt(syear) + 1;
                    var containerH = $(\'<tr class="source bg-grey">\' +
                        \'<th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                        \'<th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                    var content = $(\'<tr>\');
                    var contain = $(\'<div>\');
                    for (var i = 0; i < programDuration; i++) {
                        containerH.append(\'<th colspan="2"> \' + dispyear + "/" + finyear + \'</th>\');
                        content.append(\'<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                            \'<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                        contain.append(\'<input type="hidden" name="progyear[]" value="\' + dispyear + \' " />\');
                        dispyear++;
                        finyear++;
                    }
                    containerH.append(\'<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> \' +
                        \'</tr>\');
                    content.append(\'</tr>\');
                    contain.append(\'</div>\');
                    $("#theadyears").html(contain);
                    Array.prototype.push.apply(containerH, content);
                    $(".thead").html(containerH);
                } else {
                    $("#info1").show();
                    $("#program").hide();
                    $("#info1").html("Program Duration cannot be equal or less than 0");
                    $(this).val("");
                }
            } else {
                $("#info1").show();
                $("#program").hide();
                $("#info1").html("Program Duration canot be empty");
                $(this).val("");
            }
        } else {
            $("#info1").show();
            $("#program").hide();
            $("#info1").html("Select Financial Year first");
            $(this).val("");
        }
    });

    $rowno = $("#program_body tr").length;

    function add_rowprogram() {
        var years = $("#years").val();
        var projdept = $("#projdept").val();
        if (years != "") {
            if (projdept != "") {
                var containerB = $(\'<tr class="" id="row\' + $rowno + \'">\' +
                    \'<td>\' +
                    \'<div class="form-line">\' +
                    \'<input  type="text" name="output[]" id="output\' + $rowno + \'"  class="form-control"    required>\' +
                    \'</div> \' +
                    \'</td>\' +
                    \'<td>\' +
                    \'<div class="form-line">\' +
                    \'<select name="indicator[]" id="indicatorrow\' + $rowno + \'" onchange=outputChange("row\' + $rowno + \'")   class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>\' +
                    \'<option value="">... Select Division first ...</option>\' +
                    \'</select>\' +
                    \'</div> \' +
                    \'</td>\');
                for (var i = 0; i < years; i++) {
                    containerB.append(\'<td> \' +
                        \'<input name="targetrow\' + $rowno + \'[]" id="targetrow\' + $rowno + \'" class="form-control targetrow\' + $rowno + \'" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>\' +
                        \'</td><td><input name="budgetrow\' + $rowno + \'[]" id="budgetrow\' + $rowno + \'" class="form-control currency budgetrow\' + $rowno + \'" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>\' +
                        \'</td>\');
                }
                containerB.append(\'<td><button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
                var sourceid = \'source\' + $rowno;
                $rowno = $rowno + 1;
                $("#program tr:last").after(containerB);
                getIndicator($rowno);
            } else {
                alert("Select Division First");
            }
        } else {
            alert("Enter Years ");
        }
    }

    function outputChange(rowno) {
        var rw = rowno;
        var output = \'output\' + rw;
        var indicator = \'indicator\' + rw;
        var target = \'.target\' + rw;
        var budget = \'.budget\' + rw;
        var indicatorVal = $("#" + indicator).val();   

        if (indicatorVal != "") { 
            $.ajax({
            type: \'POST\',
            url: \'assets/processor/add-program-process\',
            data: \'getUnits=\' + indicatorVal,
            dataType: "json",
            success: function(html) { 
                $(target).attr(\'placeholder\', html);
                var targets = \'target\' + indicatorVal + \'[]\';
                var budgets = \'budget\' + indicatorVal + \'[]\';
                $(target).attr(\'name\', targets);
                $(budget).attr(\'name\', budgets);
            }
        });
        } 
    }

    function delete_program_row(rowno) {
        $("#" + rowno).remove();
    }

    //function to get output
    function getIndicator(rowno) {
        var projdeptid = $("#projdept").val();
        console.log(projdeptid)
        var indicator = \'#indicatorrow\' + (rowno - 1);
        if (projdeptid) { 
            $.ajax({
                type: \'POST\',
                url: "assets/processor/add-program-process",
                data:\'getprogindicator=\'+ projdeptid,
                success: function(html) {
                    $(indicator).html(html);
                }
            });
        } else {
            $(indicator).html(\'<option value="">... Select Division First ... </option>\');
        }
    }
    </script>
    <div class="col-md-12">
        <label class="control-label" id="programworkplan"> Program Workplan </label>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="program" style="width:100%">
                <thead class="thead">
                    <!-- //tale head -->
                </thead>
                <tbody id="program_body">
                    <!-- tale body -->
                </tbody>
            </table>
        </div>
    </div>
    ';

    echo $fact;
}

if (isset($_POST['get_dependent'])) {
    //get financial years 
    $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year");
    $query_rsYear->execute();
    $row_rsYear = $query_rsYear->fetch();
    $totalRows_rsYear = $query_rsYear->rowCount();

    $indid = $_POST['get_dependent'];

    $query_rsObjective = $db->prepare("SELECT p.starting_year, p.years, o.id FROM tbl_key_results_area  k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE o.id = '$indid' AND p.current_plan=1 ");
    $query_rsObjective->execute();

    $row_rsObjective = $query_rsObjective->fetch();
    $syear = $row_rsObjective['starting_year'];
    $objid = $row_rsObjective['id'];
    $years = $row_rsObjective['years'];
    $endyear = ($syear + $years) - 1;

    $dependent  =  '
    <div class="col-md-4">
        <label for="syear">Strategic Plan Start Year *:</label>
        <div class="form-line">
            <input type="text" name="stratplanstartYear" id="stratplanstartYear" value="' . $syear . '" class="form-control" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <label for="syear">Strategic Plan End Year *:</label>
        <div class="form-line">
            <input type="text" name="stratplanendyear" id="stratplanendyear" value="' . $endyear . '" class="form-control" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <label for="syear">Strategic Plan Years *:</label>
        <div class="form-line">
            <input type="text" name="stratplanyears" id="stratplanyears" value="' . $years . '" class="form-control" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <label>Strategy *:</label>
        <div class="form-line">
            <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                <option value="">.... Select Strategy from list ....</option>'; 
                
                    $query_strategy = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = '$objid'");
                    $query_strategy->execute();
                    $row_strategy = $query_strategy->fetch();
                    $rows_count = $query_strategy->rowCount();

                    do {
                        $dependent  .= '<option value="' . $row_strategy['id'] . '">' . $row_strategy['strategy'] . '</option>';
                    } while ($row_strategy = $query_strategy->fetch());

                    $dependent  .= '
            </select>
        </div>
    </div>
   <div class="col-md-4">
    <label class="control-label">Program Start Year *:</label>
    <div class="form-line">
        <select name="syear" id="syear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
            <option value="">.... Select Year from list ....</option>';

    $currentYear = '';
    $month =  date('m');
    if ($month  < 7) {
        $currentYear =  date("Y") - 1;
    } else {
        $currentYear =  date("Y");
    }

    do {
        $fscyear = $row_rsYear['yr'];
        if ($fscyear >= $currentYear) {
            $dependent .= '<option value="' . $row_rsYear['yr'] . '">' . $row_rsYear['year'] . '</option>';
        }
    } while ($row_rsYear = $query_rsYear->fetch());

    $dependent .= '</select>
        <span id="projfscyearmsg" style="color:red"></span>
    </div>
    </div>
    <div id="theadyears"></div>
    <div class="col-md-4">
        <label for="years">Program Duration In Years *:</label>
        <div class="form-line">
            <input type="text" name="years" id="years" placeholder="Program Duration" class="form-control" required>
            <span id="info1" style="color:red"></span>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#projfscyearmsg").hide();
            $("#programworkplan").hide();

    });


    $("#syear").change(function(e) { 
        var progstrategy = $("#progstrategy").val(); //program objective 
        var programDuration = $("#years").val(); //  program  duration in years 
        var syear = $(this).val(); // program start year
        var programStartYear = parseInt(syear); //program start year number 												
        var stratPlanStartYear = parseInt($("#stratplanstartYear").val()); //strategic plan start year
       
        var stratPlanEndyear = parseInt($("#stratplanendyear").val()); //strategic plan start year
        var stratPlanduration = parseInt($("#stratplanyears").val()); //strategic plan start year
        console.log(stratPlanStartYear);
        console.log(stratPlanEndyear); 
        if (syear != "" && programDuration != "") {
            programDuration = parseInt(programDuration);
            var programEndYear = ((programStartYear + programDuration) - 1);
            if (programStartYear >= stratPlanStartYear && programStartYear <= stratPlanEndyear &&
                programEndYear >= stratPlanStartYear && programEndYear <= stratPlanEndyear) {
                $("#info1").hide();
                $("#projfscyearmsg").hide();
                $("#program").show();
                $("#programworkplan").show();
                var dispyear = parseInt(syear);
                var finyear = parseInt(syear) + 1;
                var containerH = $(\'<tr class="source bg-grey">\' +
                    \'<th rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                    \'<th rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                var content = $(\'<tr>\');
                var contain = $(\'<div>\');
                for (var i = 0; i < programDuration; i++) {
                    containerH.append(\'<th colspan="2"> \' + dispyear + "/" + finyear + \'</th>\');
                    content.append(\'<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                        \'<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                    contain.append(\'<input type="hidden" name="progyear[]" value="\' + dispyear + \' " />\');
                    dispyear++;
                    finyear++;
                }
                containerH.append(\'<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> \' +
                    \'</tr>\');

                content.append(\'</tr>\');
                contain.append(\'</div>\');
                $("#theadyears").html(contain);
                Array.prototype.push.apply(containerH, content);
                $(".thead").html(containerH);
            } else {
                $("#info1").show();
                $("#program").hide();
                $("#info1").html("Program duration should be less than Strategic Plan duration")
                $("#years").val("");
            }
        } else if (syear != "" && programDuration == "") {
            $("#info1").show();
            $("#projfscyearmsg").hide();
            $("#program").hide();
            $("#info1").html("Enter Program Duration");
        } else {
            $("#projfscyearmsg").show();
            $("#projfscyearmsg").html("Select Financial Year first");
            $("#info1").html("Select Financial Year first");
            $("#program").hide();
            $("#years").val("");
        }
    });

    $("#years").keyup(function(e) {
        var progstrategy = $("#progstrategy").val(); //program objective 
        var syear = $("#syear").val(); // program start year
        var programDuration = $(this).val(); // program  duration in years 
        var programStartYear = parseInt(syear); //program start year number 												
        var stratPlanStartYear = parseInt($("#stratplanstartYear").val()); //strategic plan start year
        var stratPlanEndyear = parseInt($("#stratplanendyear").val()); //strategic plan start year
        var stratPlanduration = parseInt($("#stratplanyears").val()); //strategic plan start year
        if (progstrategy != "") {
            if (syear != "") {
                if (programDuration != "") {
                    programDuration = parseInt(programDuration);
                    if (programDuration > 0) {
                        var programEndYear = ((programStartYear + programDuration) - 1);
                        if (programStartYear >= stratPlanStartYear && programStartYear <= stratPlanEndyear &&
                            programEndYear >= stratPlanStartYear && programEndYear <= stratPlanEndyear) {
                            $("#info1").hide();
                            $("#programworkplan").show();
                            $("#program").show();
                            var dispyear = parseInt(syear);
                            var finyear = parseInt(syear) + 1;
                            var containerH = $(\'<tr class="source bg-grey">\' +
                                \'<th rowspan="2" style="width:400px">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                                \'<th rowspan="2" style="width:300px">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                            var content = $(\'<tr>\');
                            var contain = $(\'<div>\');
                            for (var i = 0; i < programDuration; i++) {
                                containerH.append(\'<th colspan="2" style="width:400px"> \' + dispyear + "/" + finyear + \'</th>\');
                                content.append(\'<th style="width:200px">Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\' +
                                    \'<th style="width:200px">Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\');
                                contain.append(\'<input type="hidden" name="progyear[]" value="\' + dispyear + \' " />\');
                                dispyear++;
                                finyear++;
                            }
                            containerH.append(\'<th rowspan="2" style="width:50px"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> \' +
                                \'</tr>\');
                            content.append(\'</tr>\');
                            contain.append(\'</div>\');
                            $("#theadyears").html(contain);
                            Array.prototype.push.apply(containerH, content);
                            $(".thead").html(containerH);
                        } else {
                            $("#info1").show();
                            $("#program").hide();
                            $("#info1").html("Program duration should be less than Strategic Plan duration")
                            $(this).val("");
                        }
                    } else {
                        $("#info1").show();
                        $("#program").hide();
                        $("#info1").html("Program Duration cannot be equal or less than 0");
                        $(this).val("");
                    }
                } else {
                    $("#info1").show();
                    $("#program").hide();
                    $("#info1").html("Program Duration canot be empty");
                    $(this).val("");
                }
            } else {
                $("#info1").show();
                $("#program").hide();
                $("#info1").html("Select Financial Year first");
                $(this).val("");
            }
        } else {
            $("#info1").show();
            $("#program").hide();
            $("#info1").html("Select Strategy first");
            $(this).val("");
        }
    }); 
    $rowno = $("#program_body tr").length;

    function add_rowprogram() {
        var years = $("#years").val();
        var projdept = $("#projdept").val();
        if(years != "" ){
            if (projdept != "" ) {
                var containerB = $(\'<tr class="" id="row\' + $rowno + \'">\' +
                    \'<td class="col-sm-4" style="padding-right:5px">\' +
                    \'<div class="form-line">\' +
                        \'<input  type="text" name="output[]" id="output\' + $rowno + \'"  class="form-control"    required>\' +
                    \'</div> \' +
                    \'</td>\' +
                    \'<td class="col-sm-4" style="padding-right:5px">\' +
                    \'<div class="form-line">\' +
                        \'<select name="indicator[]" id="indicatorrow\' + $rowno + \'" onchange=outputChange("row\' + $rowno + \'")   class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>\' +
                            \'<option value="">... Select ' . $departmentlabel . ' first ...</option>\' +
                        \'</select>\' +
                    \'</div> \' +
                    \'</td>\');    
                for (var i = 0; i < years; i++) {
                    containerB.append(\'<td style="padding-right:5px"> \' +  
                    \'<input name="targetrow\' + $rowno + \'[]" id="targetrow\' + $rowno + \'" class="form-control targetrow\' + $rowno + \'" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>\' +
                        \'</td><td style="padding-right:5px"><input name="budgetrow\' + $rowno + \'[]" id="budgetrow\' + $rowno + \'" class="form-control currency budgetrow\' + $rowno + \'" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>\' +
                        \'</td>\');
                }  
                containerB.append(\'<td style="width:50px"><button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
                var sourceid = \'source\' + $rowno;
                $rowno = $rowno + 1; 
                $("#program tr:last").after(containerB);
                getIndicator($rowno);
            }else{
                alert("Select ' . $departmentlabel . ' First"); 
            }  
        }else{
            alert("Enter Years ");
        } 
    }

  
    function outputChange(rowno) {
        var rw = rowno;
        var output = \'output\' + rw;
        var indicator = \'indicator\' + rw;
        var target = \'.target\' + rw;
        var budget = \'.budget\' + rw;
        var indicatorVal = $("#" + indicator).val();   

        if (indicatorVal != "") { 
            $.ajax({
            type: \'POST\',
            url: \'assets/processor/add-program-process\',
            data: \'getUnits=\' + indicatorVal,
            dataType: "json",
            success: function(html) { 
                $(target).attr(\'placeholder\', html);
                var targets = \'target\' + indicatorVal + \'[]\';
                var budgets = \'budget\' + indicatorVal + \'[]\';
                $(target).attr(\'name\', targets);
                $(budget).attr(\'name\', budgets);
            }
        });
        } 
    }

    function delete_program_row(rowno) {
        $("#" + rowno).remove();
    }

    //function to get output
    function getIndicator(rowno) {
        var projdeptid = $("#projdept").val();
        var indicator = "#indicatorrow" + (rowno - 1);
        if (projdeptid) {
            $.ajax({
                type: \'POST\',
                url: \'assets/processor/add-program-process\',
                data: \'getprogindicator=\' + projdeptid,
                success: function(html) {
                    $(indicator).html(html);
                }
            });
        } else {
            $(indicator).html(\'<option value="">... Select  ' . $departmentlabel . ' First ... </option>\');
        }
    }
    </script>
    <div class="table-responsive col-md-12 col-sm-12 col-xs-12">
        <label class="control-label" id="programworkplan"> Program Workplan </label>
        <table class="table table-bordered" id="program"  style="width:100%">
            <thead class="thead">
                <!-- //tale head -->
            </thead>
            <tbody id="program_body">
                <!-- tale body -->
            </tbody>
        </table>
    </div>
    ';
    echo $dependent;
}


//GET THE indicator 
if (isset($_POST['getprogindicator'])) {
    $getindicator = $_POST['getprogindicator'];
    $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_dept ='$getindicator' AND indicator_category='Output' AND active='1'");
    $query_Indicator->execute();
    echo '<option value="">Select Indicator</option>';
    while ($row = $query_Indicator->fetch()) {
        echo '<option value="' . $row['indid'] . '"> ' . $row['indicator_name'] . '</option>';
    }
}

if (isset($_POST['getUnits'])) {
    $getUnits = $_POST['getUnits'];
	
    $query_Indicator = $db->prepare("SELECT unit FROM tbl_indicator i INNER JOIN tbl_measurement_units u ON u.id = i.indicator_unit WHERE i.indid = :indid");
    $query_Indicator->execute(array(":indid" => $getUnits));
    $row = $query_Indicator->fetch();
    $unit = $row['unit'];
    echo json_encode($unit);
}

// get department 
if (isset($_POST['getdept'])) {
    $dept = $_POST['getdept'];
    #echo 'department selected';
    $query_dep = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$dept' AND deleted='0'");
    $query_dep->execute();
    echo '<select name="department" id="department" class=" form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%"  required><option value="">Select Division</option>';
    while ($row = $query_dep->fetch()) {
        echo '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
    }
    echo '</select>';
}

if (isset($_POST['get_financier'])) {
    $query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_funding_type");
    $query_rsFunding_type->execute();
    $row_rsFunding_type = $query_rsFunding_type->fetch();
    $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
    $input = '<option value="">Select Funds Source Category</option>';
    do {
        $input .= '<option value="' . $row_rsFunding_type['id'] . '"> ' . $row_rsFunding_type['type'] . '</option>';
    } while ($row_rsFunding_type = $query_rsFunding_type->fetch());
    echo $input;
}
