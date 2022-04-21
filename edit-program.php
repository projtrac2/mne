<?php
$pageName = "Edit PROGRAM";
require('includes/head.php');
require('includes/header.php');
require('functions/strategicplan.php');
require('functions/department.php');
require('functions/funding.php');
require('functions/programs.php');

$spid = 0;
if (isset($_GET['progid'])) {
    // $progid = base64_decode($_GET['progid']);
    $progid = $_GET['progid'];
    $query_rsProgram =  $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
    $query_rsProgram->execute();
    $row_rsProgram = $query_rsProgram->fetch();
    $totalRows_rsProgram = $query_rsProgram->rowCount();

    if ($totalRows_rsProgram > 0) {
        $progname = $row_rsProgram['progname'];
        $description = $row_rsProgram['description'];
        $problem_statement = $row_rsProgram['problem_statement'];
        $strategic_plan = $row_rsProgram['strategic_plan'];
        $strategic_obj = $row_rsProgram['strategic_obj'];
        $progstrategy = $row_rsProgram['progstrategy'];
        $projsector = $row_rsProgram['projsector'];
        $projdept = $row_rsProgram['projdept'];
        $program_syear  = $row_rsProgram['syear'];
        $progduration = $row_rsProgram['years'];
        $program_type = $row_rsProgram['program_type'];
        $indipendent = true;

        $query_depts = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and parent='$projsector'");
        $query_depts->execute();
        $row_depts = $query_depts->fetch();

        if ($program_type ==  1) {
            $objid = $strategic_obj;
            $strategic_objective = get_strategic_plan_strategic_objective_details($objid);
            $syear = $strategic_objective['starting_year'];
            $objid = $strategic_objective['id'];
            $objective = $strategic_objective['objective'];
            $years = $strategic_objective['years'];
            $endyear = ($syear + $years) - 1;  // strategic plan end year
            $strategies = get_strategic_objectives_strategy($objid);
            $month =  date('m');

            if ($month >= 7 && $month <= 12) {
                $currentYear = date('Y') + 1;
            } else {
                $currentYear = date('Y');
            }

            $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$currentYear' AND yr <= '$endyear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $totalRows_rsYear = $query_rsYear->rowCount();

            $query_sp =  $db->prepare("SELECT spid FROM tbl_strategic_plan_objectives o inner join tbl_key_results_area k on k.id=o.kraid WHERE o.id = '$objid'");
            $query_sp->execute();
            $row_sp = $query_sp->fetch();
            $spid = $row_sp["spid"];
        } else {
            if ($strategic_plan) {
                $indipendent = true;        
                $objid = $strategic_obj;
                $strategic_objective = get_strategic_plan_strategic_objective_details($objid);
                $syear = $strategic_objective['starting_year'];
                $objid = $strategic_objective['id'];
                $objective = $strategic_objective['objective'];
                $years = $strategic_objective['years'];
                $endyear = ($syear + $years) - 1;  // strategic plan end year
                $strategies = get_strategic_objectives_strategy($objid);
                $month =  date('m');

                if ($month >= 7 && $month <= 12) {
                    $currentYear = date('Y') + 1;
                } else {
                    $currentYear = date('Y');
                }

                $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$currentYear' AND yr <= '$endyear'");
                $query_rsYear->execute();
                $row_rsYear = $query_rsYear->fetch();
                $totalRows_rsYear = $query_rsYear->rowCount();

                $query_sp =  $db->prepare("SELECT spid FROM tbl_strategic_plan_objectives o inner join tbl_key_results_area k on k.id=o.kraid WHERE o.id = '$objid'");
                $query_sp->execute();
                $row_sp = $query_sp->fetch();
                $spid = $row_sp["spid"];
            }
        }
    }
}

$departments = get_departments();
$funding_types = get_funding_type();
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editprogramfrm")) {
    $currentdate = date("Y-m-d");
    $progname = $_POST['progname'];
    $program_type = isset($_POST['program_type']) ? $_POST['program_type'] : 0;
    $progstrategyobjective = isset($_POST['progstrategyobjective']) ? $_POST['progstrategyobjective'] : 0;
    $splan = $objid = $progstrategy = 0;
    $url = "all-programs.php";

    if ($program_type == 1) {
        $objid = $_POST['objid'];
        $progstrategy = $_POST['progstrategy'];
        $splan = $_POST["splan"];
        $url = "view-strategic-plan-objectives.php?plan=" . base64_encode($splan);
    }else{
        if($progstrategyobjective == 1){
            $objid = $_POST['ind_strategic_objective'];
            $progstrategy = $_POST['progstrategy'];
            $splan = $_POST["splan"];
        }
    }

    $projsector = $_POST['projsector'];
    $projdept = $_POST['projdept'];
    $progdescription = $_POST['progdesc'];
    $program_statement = $_POST['progstatement'];
    $syear = $_POST['syear'];
    $years = $_POST['years'];

    $insertSQL = $db->prepare("UPDATE tbl_programs SET progname=:progname, description=:progdescription, problem_statement=:program_statement, progstrategy=:progstrategy, projsector=:projsector, projdept=:projdept, syear=:syear,years=:years, modifiedby=:proguser, datemodified=:progdate WHERE progid =:progid");
    $result  = $insertSQL->execute(array(':progname' => $progname, ':progdescription' => $progdescription, ':program_statement' => $program_statement, ":progstrategy" => $progstrategy, ':projsector' => $projsector, ':projdept' => $projdept, ':syear' => $syear, ':years' => $years, ':proguser' => $user_name, ':progdate' => $currentdate, ':progid' => $progid));
    if ($result) {
        $current_date = date("Y-m-d H:i:s");
        $query_myprogfundingM = $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid='$progid' ORDER BY id ASC");
        $query_myprogfundingM->execute();
        $row_myprogfundingM = $query_myprogfundingM->fetch();
        do {
            $sourcecat = $row_myprogfundingM['sourcecategory'];
            $amt = $row_myprogfundingM['amountfunding'];
            $datecreated = $row_myprogfundingM['date_created'];

            $insertSQL = $db->prepare("INSERT INTO tbl_myprogfunding_history (progid, sourcecategory, amountfunding, created_by, date_created) VALUES (:progid, :sourcecat,  :amt, :username, :cdate)");
            $insertSQL->execute(array(':progid' => $progid, ':sourcecat' => $sourcecat, ':amt' => $amt,  ':username' => $user_name, ':cdate' => $datecreated));
        } while ($row_myprogfundingM = $query_myprogfundingM->fetch());

        $deleteQuery = $db->prepare("DELETE  FROM `tbl_myprogfunding` WHERE progid=:progid");
        $Fresult = $deleteQuery->execute(array(':progid' => $progid));

        if ($Fresult) {
            for ($j = 0; $j < count($_POST["source_category"]); $j++) {
                $sourcecat = $_POST['source_category'][$j];
                $amt = $_POST['amountfunding'][$j];
                $insertSQL = $db->prepare("INSERT INTO tbl_myprogfunding (progid, sourcecategory, amountfunding, created_by, date_created) VALUES (:last_id, :sourcecat,  :amt, :username, :cdate)");
                $insertSQL->execute(array(':last_id' => $progid, ':sourcecat' => $sourcecat, ':amt' => $amt,  ':username' => $user_name, ':cdate' => $current_date));
            }
        } else {
            $msg = 'Sorry, could not update funding details.';
            $results = "<script type=\"text/javascript\">
               swal({
               title: \"Warning!\",
               text: \" $msg\",
               type: 'Warning',
               timer: 2000,
               showConfirmButton: false });
            </script>";
        }

        $query_Progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid='$progid' ORDER BY id ASC");
        $query_Progdetails->execute();
        $row_Progdetails = $query_Progdetails->fetch();
        $count_Progdetails = $query_Progdetails->rowCount();

        if ($count_Progdetails > 0) {
            do {
                $year =  $row_Progdetails['year'];
                $budget =  $row_Progdetails['budget'];
                $target = $row_Progdetails['target'];
                $output =  $row_Progdetails['output'];
                $indicator =  $row_Progdetails['indicator'];
                $insertSQL = $db->prepare("INSERT INTO tbl_progdetails_history (progid, year,  output, indicator, target, budget) VALUES (:progid, :year, :outputid,:indicator, :target, :budget)");
                $result = $insertSQL->execute(array(':progid' => $progid, ':year' => $year, ':outputid' => $output, ':indicator' => $indicator, ':target' => $target, ':budget' => $budget));
            } while ($row_Progdetails = $query_Progdetails->fetch());
        }

        $deleteQuery = $db->prepare("DELETE FROM `tbl_progdetails` WHERE progid=:progid");
        $Dresult = $deleteQuery->execute(array(':progid' => $progid));

        if ($Dresult) {
            for ($j = 0; $j < count($_POST['indicator']); $j++) {
                $output = $_POST['output'][$j];
                $indicator = $_POST['indicator'][$j];
                for ($p = 0; $p < count($_POST['progyear']); $p++) {
                    $year = $_POST['progyear'][$p];
                    $targets = "target" . $indicator;
                    $budgets = "budget" . $indicator;

                    if (isset($_POST[$budgets]) && isset($_POST[$targets])) {
                        $target = $_POST[$targets][$p];
                        $budget = $_POST[$budgets][$p];
                        $insertSQL = $db->prepare("INSERT INTO tbl_progdetails (progid, year, output, indicator, target, budget) VALUES (:last_id, :year, :outputid,:indicator, :target, :budget)");
                        $insertSQL->execute(array(':last_id' => $progid, ':year' => $year, ':outputid' => $output, ':indicator' => $indicator, ':target' => $target, ':budget' => $budget));
                    }
                }
            }
        } else {
            $msg = 'Sorry, could not update Output details.';
            $results = "<script type=\"text/javascript\">
               swal({
               title: \"Warning!\",
               text: \" $msg\",
               type: 'Warning',
               timer: 2000,
               showConfirmButton: false });
            </script>";
            echo $results;
        }
 
        $msg = 'The Program was successfully updated.';
        $results = "<script type=\"text/javascript\">
                    swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    showConfirmButton: false });
                    setTimeout(function(){
                            window.location.href = '$url';
                        }, 2000);
                </script>";
        echo $results;
    }
}
?>

<script src="assets/ckeditor/ckeditor.js"></script>
<div class="body">
    <div style="margin-top:5px">
        <form id="addprogform" method="POST" name="addprogform" action="" enctype="multipart/form-data" autocomplete="off">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Edit Program Details</legend>
                <div class="col-md-12">
                    <label for="">Program Name *:</label>
                    <div class="form-line">
                        <input type="text" name="progname" id="progname" placeholder="Name Your Program" class="form-control" value="<?= $progname ?>" required>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="control-label">Program Problem Statement *:</label>
                    <div class="form-line">
                        <input type="text" name="progstatement" id="progstatement" value="<?= $problem_statement ?>" placeholder="Program Problem Statement" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label> <?= $ministrylabel ?> *:</label>
                    <div class="form-line">
                        <select name="projsector" id="projsector" onchange="get_department()" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                            <option value="">.... Select <?= $ministrylabel ?> from list ....</option>
                            <?php
                            for ($i = 0; $i < count($departments); $i++) {
                                $selected = $projsector == $departments[$i]['stid'] ? "selected" : "";
                            ?>
                                <option value="<?php echo $departments[$i]['stid'] ?>" <?= $selected ?>><?php echo $departments[$i]['sector'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label> <?= $departmentlabel ?> *:</label>
                    <div class="form-line">
                        <select name="projdept" id="projdept" onchange="get_indicator(null)" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                            <option value="">... Select <?= $ministrylabel ?> first ...</option>
                            <?php
                                do{
                                $selected = $row_depts['stid'] == $projdept ? "selected" : "";
                                ?>
                                <option value="<?=$row_depts['stid']?>" <?=$selected?> ><?=$row_depts['sector']?></option>
                                <?php
                                }while($row_depts = $query_depts->fetch());
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                if ($program_type == 1) {
                ?>
                    <div class="col-md-12" id="strat_div">
                        <label>Strategic Objective *:</label>
                        <div class="form-line">
                            <input type="text" name="objectives" id="objectives" placeholder="Program Problem Statement" class="form-control" value="<?= $objective ?>" style="border:#CCC thin solid; border-radius: 5px" disabled>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>Strategy *:</label>
                        <div class="form-line">
                            <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                <option value="">.... Select Strategy from list ....</option>
                                <?php
                                if ($strategies) {
                                    foreach ($strategies as $strategy) {
                                        $selected = $progstrategy == $strategy['id'] ? "selected" : "";
                                        echo '<option value="' . $strategy['id'] . '" ' . $selected . '>' . $strategy['strategy'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="syear">Strategic Plan Start Year *:</label>
                        <div class="form-line">
                            <input type="text" name="stratplanstartYear" id="stratplanstartYear" value="<?= $syear ?>" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="syear">Strategic Plan End Year *:</label>
                        <div class="form-line">
                            <input type="text" name="stratplanendyear" id="stratplanendyear" value="<?= $endyear ?>" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="syear">Strategic Plan Years *:</label>
                        <div class="form-line">
                            <input type="text" name="stratplanyears" id="stratplanyears" value="<?= $years ?>" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Program Start Year *:</label>
                        <div class="form-line">
                            <select name="syear" id="starting_year" onchange="program_workplan_header()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Year from list ....</option>
                                <?php
                                if ($totalRows_rsYear > 0) {
                                    do {
                                        $selected = $row_rsYear['yr'] == $syear ? "selected" : "";
                                        echo '<option value="' . $row_rsYear['yr'] . '" ' . $selected . '>' . $row_rsYear['year'] . '</option>';
                                    } while ($row_rsYear = $query_rsYear->fetch());
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="years">Program Duration In Years *:</label>
                        <div class="form-line">
                            <input type="number" name="years" id="program_duration" onkeyup="program_workplan_header()" value="<?= $progduration ?>" onchange="program_workplan_header()" placeholder="Program Duration" class="form-control" required>
                            <span id="info1" style="color:red"></span>
                        </div>
                    </div>
                    <input type="hidden" name="program_type" id="program_type" value="1">
                <?php
                } else {
                ?>
                    <input type="hidden" name="program_type" id="program_type" value="0">
                    <div class="col-md-12">
                        <label for="" class="control-label">Link to Strategic Objective? *:</label>
                        <div class="form-line">
                            <input name="progstrategyobjective" type="radio" value="1" <?php echo (!$indipendent) ? "checked" : ""; ?> id="strat1" onchange="hide_strategicplan(1)" class="with-gap radio-col-green insp" required="required" />
                            <label for="strat1">YES</label>
                            <input name="progstrategyobjective" type="radio" value="0" <?php echo ($indipendent) ? "checked" : ""; ?> id="strat2" onchange="hide_strategicplan(0)" class="with-gap radio-col-red insp" required="required" />
                            <label for="strat2">NO</label>
                        </div>
                    </div>
                    <div id="strategicplan_div">
                        <?php
                        if ($strategic_plan != 0) {
                            $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
                            $query_rsStrategicPlan->execute();
                            $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
                            $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
                            $syear = $row_rsStrategicPlan['starting_year'];
                            $years = $row_rsStrategicPlan['years'];
                            $endyear = ($syear + $years) - 1; // strategic plan end year

                            $query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
                            $query_rsStrategicObjectives->execute();
                            $row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch();
                            $totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();

                            $strategic_objective_options = '';
                            if ($totalRows_rsStrategicObjectives > 0) {
                                do {
                                    $strategic_objective_options .= '<option value="' . $row_rsStrategicObjectives['id'] . '">' . $row_rsStrategicObjectives['objective'] . '</option>';
                                } while ($row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch());
                            }
                        ?>
                            <div class="col-md-12" id="strat_div">
                                <label>Strategic Objective *:</label>
                                <div class="form-line">
                                    <select name="ind_strategic_objective" id="ind_strategic_objective" class="form-control show-tick" onchange="get_strategy()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                        <option value="">.... Select Strategic Objective ....</option>
                                        <?php
                                            $query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area  k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
                                            $query_rsStrategicObjectives->execute();
                                            $row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch();
                                            $totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();

                                            $strategic_objective_options = '';
                                            if ($totalRows_rsStrategicObjectives > 0) {
                                                do {
                                                    $selected = $strategic_obj ==$row_rsStrategicObjectives['id'] ? "selected" : "";
                                                    $strategic_objective_options .= '<option value="' . $row_rsStrategicObjectives['id'] . '" '.$selected.'>' . $row_rsStrategicObjectives['objective'] . '</option>';
                                                } while ($row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch());
                                            }
                                            echo $strategic_objective_options;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Strategy *:</label>
                                <div class="form-line">
                                    <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                        <?php 
                                        	$strategies = get_strategic_objectives_strategy($objid);
                                            $strategy_options = '<option value="">.... Select Strategy from list ....</option>';
                                            if ($strategies) {
                                                foreach ($strategies as $strategy) {
                                                    $selected = $progstrategy == $strategy['id']  ? "selected" : "";
                                                    $strategy_options .= '<option value="' . $strategy['id'] . '" '.$selected.'>' . $strategy['strategy'] . '</option>';
                                                }
                                            }
                                            echo $strategy_options;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="syear">Strategic Plan Start Year *:</label>
                                <div class="form-line">
                                    <input type="text" name="stratplanstartYear" id="stratplanstartYear" value="<?=$syear?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="syear">Strategic Plan End Year *:</label>
                                <div class="form-line">
                                    <input type="text" name="stratplanendyear" id="stratplanendyear" value="<?=$endyear?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="syear">Strategic Plan Years *:</label>
                                <div class="form-line">
                                    <input type="text" name="stratplanyears" id="stratplanyears" value="<?=$years ?>" class="form-control" disabled>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div id="program_year_div">
                        <?php
                        $program_year_options = "";
                        $end = "";
                        if (!$indipendent) {
                            $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
                            $query_rsStrategicPlan->execute();
                            $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
                            $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
                            $syear = $row_rsStrategicPlan['starting_year'];
                            $years = $row_rsStrategicPlan['years'];
                            $endyear = ($syear + $years) - 1;  // strategic plan end year
                            $end = "AND yr <= '$endyear'";
                        }

                        $month =  date('m');
                        $currentYear = ($month < 7) ? date("Y") - 1 : date("Y");
                        $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$currentYear' $end");
                        $query_rsYear->execute();
                        $row_rsYear = $query_rsYear->fetch();
                        $totalRows_rsYear = $query_rsYear->rowCount();
                       
                        if ($totalRows_rsYear > 0) {
                            do {
                                $selected = $program_syear == $row_rsYear['yr'] ? "selected" : "";
                                $program_year_options .= '<option value="' . $row_rsYear['yr'] . '" '.$selected.'>' . $row_rsYear['year'] . '</option>';
                            } while ($row_rsYear = $query_rsYear->fetch());
                        }
                        ?>
                        <div class="col-md-6">
                            <label class="control-label">Program Start Year *:</label>
                            <div class="form-line">
                                <select name="syear" id="starting_year" onchange="program_workplan_header()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                    <option value="">.... Select Year from list ....</option>
                                    <?=$program_year_options?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="years">Program Duration In Years *:</label>
                            <div class="form-line">
                                <input type="number" name="years" id="program_duration" onkeyup="program_workplan_header()" onchange="program_workplan_header()" value="<?=$progduration?>" placeholder="Program Duration" class="form-control" required>
                                <span id="info1" style="color:red"></span>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div id="program_workplan_div">
                    <div class="col-md-12">
                        <label class="control-label" id="programworkplan"> Program Output Targets </label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="program" style="width:100%">
                                <thead class="thead" id="phead">
                                    <!-- //tale head -->
                                    <tr>
                                        <th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <?php
                                        $dispyear  = $program_syear;
                                        for ($j = 0; $j < $progduration; $j++) {
                                            $dispyear++;
                                            echo '<th colspan="2">' . $program_syear . '/' . $dispyear . '</th> <input type="hidden" name="progyear[]" value="' . $program_syear . '" />';
                                            $program_syear++;
                                        }
                                        ?>
                                        <th rowspan="2">
                                            <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan();" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </th>
                                    </tr>
                                    <tr>
                                        <?php
                                        for ($j = 0; $j < $progduration; $j++) {
                                        ?>
                                            <th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody id="program_workplan_body">
                                    <!-- tale body -->
                                    <tr></tr>
                                    <?php
                                    $query_outputIndicator = $db->prepare("SELECT tbl_progdetails.output,  tbl_indicator.indicator_name, tbl_indicator.indid FROM tbl_progdetails INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE tbl_progdetails.progid = :progid GROUP BY tbl_progdetails.indicator ORDER BY tbl_progdetails.id");
                                    $query_outputIndicator->execute(array(":progid" => $progid));
                                    $total_outputIndicator = $query_outputIndicator->rowCount();
                                    $rowno = 0;
                                    $counter = 1;
                                    if ($total_outputIndicator > 0) {
                                        while ($row_outputIndicator = $query_outputIndicator->fetch()) {
                                            $row = "row" . $rowno;
                                            $progsyear = $row_rsProgram['syear'];
                                            $output = $row_outputIndicator['output'];
                                            $indicator = $row_outputIndicator['indicator_name'];
                                            $indid = $row_outputIndicator['indid'];
                                    ?>
                                            <tr id="<?php echo $row ?>">
                                                <td>
                                                    <div class="form-line">
                                                        <input name="output[]" type="text" id="output<?php echo $row ?>" value="<?php echo $output ?>" class="form-control" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name="indicator[]" id="indicator<?php echo $row ?>" onchange='indicatorChange("<?php echo $row ?>")' class="form-control selectOutput show-tick indicator" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>' +
                                                        <option value="">... Select Indicator ...</option>
                                                        <?php
                                                        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE  indicator_dept ='$projdept'");
                                                        $query_rsIndicator->execute();
                                                        $row_rsIndicator = $query_rsIndicator->fetchAll();

                                                        foreach ($row_rsIndicator as $val) {
                                                            $indicatorvid = $val['indid'];
                                                            $indicatorVal = $val['indicator_name'];
                                                            if ($indicatorvid == $indid) {
                                                        ?>
                                                                <option value="<?php echo $indicatorvid ?>" selected><?php echo $indicatorVal ?></option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="<?php echo $indicatorvid ?>"><?php echo $indicatorVal ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <?php
                                                for ($i = 0; $i < $progduration; $i++) {
                                                    $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails  WHERE progid = :progid and year = '$progsyear' and indicator = '$indid'");
                                                    $query_progdetails->execute(array(":progid" => $progid));
                                                    $total_progdetails = $query_progdetails->rowCount();
                                                    while ($row_progdeatils = $query_progdetails->fetch()) {
                                                        $target =  $row_progdeatils['target'];
                                                        $budget =  $row_progdeatils['budget'];
                                                        $output =  $row_progdeatils['budget'];
                                                        $indicator =  $row_progdeatils['budget'];
                                                ?>
                                                        <td>
                                                            <input name="target<?php echo $indid ?>[]" id="targetrow<?php echo $counter ?>" class="form-control targetrow<?php echo $counter ?>" value="<?php echo $target ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                        </td>
                                                        <td>
                                                            <input name="budget<?php echo $indid ?>[]"  onchange="sum_budget()" onkeyup="sum_budget()" id="budgetrow<?php echo $counter ?>" class="form-control currency targetrow<?php echo $counter ?>" value="<?php echo  $budget ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                        </td>
                                                <?php
                                                    };
                                                    $progsyear++;
                                                }
                                                ?>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("<?php echo $row ?>")>
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php
                                            $counter++;
                                            $rowno++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="control-label">Program Description *: <font align="left" style="background-color:#eff2f4">(Briefly describe goals and objectives of the program, approaches and execution methods, and other relevant information that explains the need for program.) </font></label>
                    <p align="left">
                        <textarea name="progdesc" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?= $description ?></textarea>
                        <script>
                            CKEDITOR.replace('projdesc', {
                                height: 200,
                                on: {
                                    instanceReady: function(ev) {
                                        // Output paragraphs as <p>Text</p>.
                                        this.dataProcessor.writer.setRules('p', {
                                            indent: false,
                                            breakBeforeOpen: false,
                                            breakAfterOpen: false,
                                            breakBeforeClose: false,
                                            breakAfterClose: false
                                        });
                                        this.dataProcessor.writer.setRules('ol', {
                                            indent: false,
                                            breakBeforeOpen: false,
                                            breakAfterOpen: false,
                                            breakBeforeClose: false,
                                            breakAfterClose: false
                                        });
                                        this.dataProcessor.writer.setRules('ul', {
                                            indent: false,
                                            breakBeforeOpen: false,
                                            breakAfterOpen: false,
                                            breakBeforeClose: false,
                                            breakAfterClose: false
                                        });
                                        this.dataProcessor.writer.setRules('li', {
                                            indent: false,
                                            breakBeforeOpen: false,
                                            breakAfterOpen: false,
                                            breakBeforeClose: false,
                                            breakAfterClose: false
                                        });
                                    }
                                }
                            });
                        </script>
                    </p>
                </div>
                <fieldset class="scheduler-border">
                    <div class="col-md-6">
                        <?php
                        $query_myprogfunding = $db->prepare("SELECT SUM(amountfunding) as funds FROM tbl_myprogfunding WHERE progid=:progid ORDER BY id ASC");
                        $query_myprogfunding->execute(array(":progid" => $progid));
                        $row_myprogfunding = $query_myprogfunding->fetch();
                        $totalRows_rsMyprogfunding = $query_myprogfunding->rowCount();
                        $amount = 0;
                        if ($totalRows_rsMyprogfunding > 0) {
                            $amount = $row_myprogfunding['funds'];
                        }
                        ?>
                        <label for="years">Program Budget *:</label>
                        <div class="form-line">
                            <input type="text" name="program_bud" id="program_bud" value="0" placeholder="Program Budget" class="form-control" readonly>
                            <input type="hidden" name="program_budget" id="program_budget" value="<?= $amount ?>">
                            <span id="info1" style="color:red"></span>
                        </div>
                    </div>
                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Source of funds</legend>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                <thead>
                                    <tr class="bg-grey">
                                        <th width="5%">#</th>
                                        <th width="40%">Category</th>
                                        <th width="50%">Amount</th>
                                        <th width="5%">
                                            <button type="button" name="addplus" id="addplus" onclick="add_row_financier();" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="financier_table_body">
                                    <?php
                                    $rowno = 0;
                                    $query_myprogfunding = $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid=:progid ORDER BY id ASC");
                                    $query_myprogfunding->execute(array(":progid" => $progid));
                                    $row_myprogfunding = $query_myprogfunding->fetch();
                                    $totalRows_rsMyprogfunding = $query_myprogfunding->rowCount();
                                    do {
                                        $rowno++;
                                        $sourceCategory = $row_myprogfunding['sourcecategory'];
                                        $amountfunding = $row_myprogfunding['amountfunding'];
                                        $myprojfundingid = $row_myprogfunding['id'];
                                        $row = "financerow" . $rowno;
                                    ?>
                                        <tr id="financerow<?php echo $row ?>">
                                            <td><?= $rowno ?></td>
                                            <td>
                                                <select data-id="0" name="source_category[]" id="source_categoryrow<?php echo $rowno ?>" class="form-control validoutcome selected_category" required="required">
                                                    <?php
                                                    $input = '';
                                                    if ($funding_types) {
                                                        $input .= '<option value="">Select Funds Source Category</option>';
                                                        foreach ($funding_types as $funding_type) {
                                                            $selected = $sourceCategory == $funding_type['id'] ? "selected" : "";
                                                            $input .= '<option value="' . $funding_type['id'] . '" ' . $selected . '> ' . $funding_type['type'] . '</option>';
                                                        }
                                                    } else {
                                                        $input .= '<option value="">No Funding Category Found !!!</option>';
                                                    }
                                                    echo $input;
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="amountfunding[]" id="amountfundingrow<?php echo $rowno ?>" onchange="calculate_budget('<?php echo $rowno ?>')" onkeyup="calculate_budget('<?php echo $rowno ?>')" placeholder="Enter amount in local currency" value="<?= $amountfunding ?>" class="form-control financierTotal" required />
                                            </td>
                                            <td>
                                                <?php
                                                if ($rowno != 1) {
                                                ?>
                                                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_financier("<?php echo $row ?>")'>
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    } while ($row_myprogfunding = $query_myprogfunding->fetch());
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
                <div class="col-md-12" style="margin-top:15px" align="center">
                    <input type="hidden" name="objid" value="<?php echo $objid ?>">
                    <input type="hidden" name="MM_update" value="editprogramfrm">
                    <button class="btn btn-success" type="submit">Save</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        hide_workplan(1);
    });
</script>
<script src="assets/js/programs/add-edit-programs.js"></script>
<?php
require('includes/footer.php');
?>