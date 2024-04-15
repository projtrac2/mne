<?php
require('includes/head.php');


if ($permission && (isset($_GET['progid']) && !empty($_GET["progid"]))) {
    $decode_progid =  base64_decode($_GET['progid']);
    $progid_array = explode("progid54321", $decode_progid);
    $progid = $progid_array[1];

    $decode_stplanid =   base64_decode($_GET['plan']);
    $stplanid_array = explode("strplan1", $decode_stplanid);
    $strategic_plan_id = $stplanid_array[1];
    $stplane = $_GET['plan'];

    try {
        $query_rsProgram =  $db->prepare("SELECT * FROM tbl_programs WHERE progid =:progid");
        $query_rsProgram->execute(array(":progid" => $progid));
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();

        $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE id=:strategic_plan_id LIMIT 1");
        $query_rsStrategicPlan->execute(array(":strategic_plan_id" => $strategic_plan_id));
        $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
        $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();

        if ($totalRows_rsProgram > 0 && $totalRows_rsStrategicPlan > 0) {
            $program_name = $row_rsProgram['progname'];
            $program_description = $row_rsProgram['description'];
            $program_statement = $row_rsProgram['problem_statement'];
            $program_department = $row_rsProgram['projsector'];
            $program_section = $row_rsProgram['projdept'];
            $program_directorate = $row_rsProgram['directorate'];

            $strategic_plan = $row_rsStrategicPlan['plan'];
            $strategic_plan_start_year = $row_rsStrategicPlan['starting_year'];
            $strategic_plan_duration = $row_rsStrategicPlan['years'];

            $strategy_id = $strategic_objective_id = '';

            $query_rsStrategicPlanProgram =  $db->prepare("SELECT * FROM tbl_strategic_plan_programs WHERE progid =:progid AND strategic_plan_id=:strategic_plan_id");
            $query_rsStrategicPlanProgram->execute(array(":progid" => $progid, ":strategic_plan_id" => $strategic_plan_id));
            $row_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->fetch();
            $totalRows_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->rowCount();

            if ($totalRows_rsStrategicPlanProgram > 0) {
                $strategic_objective_id = $row_rsStrategicPlanProgram['strategic_objective_id'];
                $strategy_id = $row_rsStrategicPlanProgram['strategy_id'];
            }

            function get_indicator($indicator)
            {
                global $db;
                $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = '$indicator'");
                $query_rsIndicator->execute();
                $row_rsIndicator = $query_rsIndicator->fetch();
                $indcount = $query_rsIndicator->rowCount();
                return ($indcount > 0)  ? $row_rsIndicator['indicator_name'] : 0;
            }

            if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprogramfrm")) {
                $created_at = date("Y-m-d");
                $progid = $_POST['progid'];
                $strategic_plan_id = $_POST['strategic_plan_id'];
                $strategic_objective_id = $_POST['strategic_objective'];
                $strategy_id = $_POST['progstrategy'];
                $result = false;
                $query_rsStrategicPlanProgram =  $db->prepare("SELECT * FROM tbl_strategic_plan_programs WHERE progid =:progid AND strategic_plan_id=:strategic_plan_id");
                $query_rsStrategicPlanProgram->execute(array(":progid" => $progid, ":strategic_plan_id" => $strategic_plan_id));
                $row_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->fetch();
                $totalRows_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->rowCount();
                if ($totalRows_rsStrategicPlanProgram == 0) {
                    $sql = $db->prepare("INSERT INTO tbl_strategic_plan_programs (strategic_plan_id,strategic_objective_id,strategy_id,progid,created_by,created_at ) VALUES (:strategic_plan_id,:strategic_objective_id,:strategy_id,:progid,:created_by,:created_at)");
                    $result  = $sql->execute(array(":strategic_plan_id" => $strategic_plan_id, ":strategic_objective_id" => $strategic_objective_id, ":strategy_id" => $strategy_id, ":progid" => $progid, ":created_by" => $user_name, ":created_at" => $created_at));
                    $last_id = $db->lastInsertId();
                } else {
                    $id = $row_rsStrategicPlanProgram['id'];
                    $sql = $db->prepare("UPDATE tbl_strategic_plan_programs SET strategic_objective_id=:strategic_objective_id,strategy_id=:strategy_id,updated_by=:updated_by,updated_at=:updated_at WHERE id=:id ");
                    $result  = $sql->execute(array(":strategic_objective_id" => $strategic_objective_id, ":strategy_id" => $strategy_id, ":updated_by" => $user_name, ":updated_at" => $created_at, ":id" => $id));
                }

                if ($result) {
                    $sql = $db->prepare("DELETE FROM tbl_progdetails WHERE strategic_plan_id =:strategic_plan_id AND progid=:progid ");
                    $result = $sql->execute(array(":strategic_plan_id" => $strategic_plan_id, ":progid" => $progid));
                    for ($j = 0; $j < count($_POST['indicator']); $j++) {
                        $indicator = $_POST['indicator'][$j];
                        $output = get_indicator($indicator);
                        for ($p = 0; $p < count($_POST['progyear']); $p++) {
                            $year = $_POST['progyear'][$p];
                            $targets = "target" . $indicator;
                            $budgets = "budget" . $indicator;
                            $target = $_POST[$targets];
                            $budget = $_POST[$budgets];
                            if (isset($_POST[$budgets]) && isset($_POST[$targets])) {
                                $target = $_POST[$targets][$p];
                                $budget = $_POST[$budgets][$p];
                                $sql = $db->prepare("INSERT INTO tbl_progdetails (strategic_plan_id,progid,year,output,indicator,target,budget) VALUES (:strategic_plan_id,:progid,:year,:outputid,:indicator,:target,:budget)");
                                $sql->execute(array(":strategic_plan_id" => $strategic_plan_id, ':progid' => $progid,  ':year' => $year, ':outputid' => $output, ':indicator' => $indicator, ':target' => $target, ':budget' => $budget));
                            }
                        }
                    }

                    $url = "view-program.php?plan=" . $stplane;
                    $msg = 'The Program was successfully created.';
                    $results =
                        "<script type=\"text/javascript\">
                            swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                            icon:'success',
                            showConfirmButton: false });
                            setTimeout(function(){
                                    window.location.href = '$url';
                                }, 2000);
                        </script>";
                }
            }

            function get_strategic_objectives($strategic_objective_id)
            {
                global $db;
                $query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
                $query_rsStrategicObjectives->execute();
                $totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();

                $strategic_objective_options = '';
                if ($totalRows_rsStrategicObjectives > 0) {
                    while ($row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch()) {
                        $selected = $row_rsStrategicObjectives['id'] == $strategic_objective_id ? 'selected' : '';
                        $strategic_objective_options .= '<option value="' . $row_rsStrategicObjectives['id'] . '" ' . $selected . '>' . $row_rsStrategicObjectives['objective'] . '</option>';
                    }
                }
                return $strategic_objective_options;
            }

            function get_strategies($strategic_objective_id, $strategy_id)
            {
                global $db;

                if ($strategy_id != '') {
                    $query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where objid=:objid");
                    $query_strategy->execute(array(":objid" => $strategic_objective_id));
                    $totalRows_strategy = $query_strategy->rowCount();
                    $strategy_options = '<option value="">.... Select Strategy from list ....</option>';
                    if ($totalRows_strategy > 0) {
                        while ($row_strategy = $query_strategy->fetch()) {
                            $selected =  $row_strategy['id'] == $strategy_id ? 'selected' : '';
                            echo '<option value="' . $row_strategy['id'] . '" ' . $selected . '>' . $row_strategy['strategy'] . '</option>';
                        }
                    }
                } else {
                    $strategy_options = '<option value="">.... Select Strategic Objective First ....</option>';
                }
                return $strategy_options;
            }

            function get_indicators($indicator_id)
            {
                global $db;
                $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator i INNER JOIN tbl_measurement_units m ON m.id = i.indicator_unit WHERE baseline=1");
                $query_rsIndicator->execute();
                $row_rsIndicator = $query_rsIndicator->fetchAll();
                $indicators = '<option value="">... Select Indicator ...</option>';
                foreach ($row_rsIndicator as $val) {
                    $indicatorvid = $val['indid'];
                    $indicatorVal = $val['indicator_name'];
                    $unit = $val['unit'];
                    $selected = ($indicatorvid == $indicator_id) ? 'selected' : '';
                    $indicators .= '<option value="' . $indicatorvid . '" ' . $selected . '>' . $unit . " of " . $indicatorVal . '</option>';
                }
                return $indicators;
            }


            // tbl_strategic_plan_programs

            function get_sector($sector_id)
            {
                global $db;
                $query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid=:sector_id ");
                $query_rsDepart->execute(array(":sector_id" => $sector_id));
                $row_rsDepart = $query_rsDepart->fetch();
                return $row_rsDepart ? $row_rsDepart['sector'] : '';
            }

            function get_outputs_header()
            {
                global $strategic_plan_duration, $strategic_plan_start_year;
                $thead = $colspan = '';
                $start_year = $strategic_plan_start_year;
                for ($i = 0; $i < $strategic_plan_duration; $i++) {
                    $end_year = $start_year + 1;
                    $thead .=
                        '<th colspan="2">' . $start_year . '/' . $end_year . '</th><input type="hidden" name="progyear[]" value="' . $strategic_plan_start_year . '" />';
                    $colspan .= '<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                    $start_year++;
                }

                return array($thead, $colspan);
            }

            function get_table_body($indicator_id, $counter)
            {
                global $db, $strategic_plan_id, $progid, $strategic_plan_duration, $strategic_plan_start_year;
                $body = '';
                $start_year = $strategic_plan_start_year;
                for ($i = 0; $i < $strategic_plan_duration; $i++) {
                    $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails  WHERE progid = :progid AND year =:start_year AND indicator = :indicator_id AND strategic_plan_id = :strategic_plan_id");
                    $query_progdetails->execute(array(":progid" => $progid, ":start_year" => $start_year, ":indicator_id" => $indicator_id, ":strategic_plan_id" => $strategic_plan_id));
                    $total_progdetails = $query_progdetails->rowCount();
                    if ($total_progdetails > 0) {
                        while ($row_progdeatils = $query_progdetails->fetch()) {
                            $target =  $row_progdeatils['target'];
                            $budget =  $row_progdeatils['budget'];
                            $body .=
                                '<td>
                                <input name="target' . $indicator_id . '[]" id="target' . $counter . '" class="form-control target' . $counter . '" value="' . $target . '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                            </td>
                            <td>
                                <input name="budget' . $indicator_id . '[]" id="budget' . $counter . '" class="form-control currency target' . $counter . '" value="' . $budget . '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                            </td>';
                        }
                    }
                    $start_year++;
                }

                return $body;
            }
?>

            <script src="assets/ckeditor/ckeditor.js"></script>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon . " " . $pageTitle ?>
                            <?= $results; ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <button type="button" id="outputItemModalBtnrow" class="btn btn-warning" style="margin-left: 10px;" onclick="window.history.back()">
                                        Go Back
                                    </button>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Program: <?= $program_name ?> </li>
                                                <?php
                                                if ($user_designation == 1) {
                                                ?>
                                                    <li class="list-group-item"><strong><?= $ministrylabel ?> : </strong> <?= get_sector($program_department) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?= $departmentlabel ?> : </strong> <?= get_sector($program_section) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?= $directoratelabel ?> : </strong> <?= get_sector($program_directorate) ?> </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <form id="addprogform" method="POST" name="addprogform" action="" enctype="multipart/form-data" autocomplete="off">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Program Details</legend>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="strat_div">
                                                <label>Strategic Objective *:</label>
                                                <div class="form-line">
                                                    <select name="strategic_objective" id="strategic_objective" class="form-control show-tick" onchange="get_strategy()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <option value="">.... Select Strategic Objective ....</option>';
                                                        <?= get_strategic_objectives($strategic_objective_id) ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label>Strategy *:</label>
                                                <div class="form-line">
                                                    <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <?= get_strategies($strategic_objective_id, $strategy_id) ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label" id="programworkplan"> Program Output Targets </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="program" style="width:100%">
                                                        <thead class="thead" id="phead">
                                                            <!-- //tale head -->
                                                            <tr>
                                                                <th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                <th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                <?= get_outputs_header()[0] ?>
                                                                <th rowspan="2">
                                                                    <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_program_workplan();" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <?= get_outputs_header()[1] ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="program_workplan_body">
                                                            <!-- tale body -->
                                                            <?php
                                                            $query_outputIndicator = $db->prepare("SELECT indicator FROM tbl_progdetails WHERE progid = :progid GROUP BY indicator ORDER BY id");
                                                            $query_outputIndicator->execute(array(":progid" => $progid));
                                                            $total_outputIndicator = $query_outputIndicator->rowCount();
                                                            if ($total_outputIndicator > 0) {
                                                                $counter = 0;
                                                                while ($row_outputIndicator = $query_outputIndicator->fetch()) {
                                                                    $indicator_id = $row_outputIndicator['indicator'];
                                                                    $output = get_indicator($indicator_id);
                                                                    $counter++;
                                                            ?>
                                                                    <tr id="<?= $counter ?>">
                                                                        <td>
                                                                            <select name="indicator[]" id="indicator<?= $counter ?>" onchange='get_indicator_details("<?= $counter ?>")' class="form-control selectOutput show-tick indicator" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                                                <option value="">... Select Indicator ...</option>
                                                                                <?= get_indicators($indicator_id); ?>
                                                                            </select>
                                                                        </td>
                                                                        <td id="output<?= $counter ?>"> <?= $output ?></td>
                                                                        <?= get_table_body($indicator_id, $counter) ?>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_program_row("<?= $counter ?>")'>
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:15px" align="center">
                                                <input type="hidden" name="MM_insert" value="addprogramfrm">
                                                <input type="hidden" name="progid" value="<?= $progid ?>">
                                                <input type="hidden" name="strategic_plan_id" value="<?= $strategic_plan_id ?>">
                                                <input type="hidden" name="strategic_plan_duration" id="strategic_plan_duration" value="<?= $strategic_plan_duration ?>">
                                                <button class="btn btn-success" type="submit">Save</button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- end body  -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } catch (PDOException $ex) {
        var_dump($ex);
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/programs/index.js"></script>