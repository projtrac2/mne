<?php
require('includes/head.php');
if ($permission) {
    $program_type = isset($_GET['program_type']) ? $_GET['program_type'] : '';

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
        $user_name = 3;
        $currentdate = date("Y-m-d");
        $progname = $_POST['progname'];
        $program_type = isset($_POST['program_type']) ? $_POST['program_type'] : 0;
        $progstrategyobjective = isset($_POST['progstrategyobjective']) ? $_POST['progstrategyobjective'] : 0;


        $splan = $objid = $progstrategy = $strategicplanid = 0;

        if ($progstrategyobjective == 1 || $program_type == 1) {
            $splan = $_POST["splan"];
            $objid = $_POST['strategic_objective'];
            $progstrategy = $_POST['progstrategy'];
            $strategicplanid = base64_encode("strplan1{$splan}");
        }

        $url = ($program_type == 1) ? "view-program.php?plan=" . $strategicplanid : "all-programs.php";
        $projsector = $_POST['department_id'];
        $projdept = $_POST['sector_id'];
        $directorate = $_POST['directorate_id'];
        $progdescription = $_POST['progdesc'];
        $program_statement = $_POST['progstatement'];
        $syear = $_POST['syear'];
        $years = $_POST['years'];

        $insertSQL = $db->prepare("INSERT INTO tbl_programs (progname, description, problem_statement, strategic_plan, strategic_obj, progstrategy, projsector, projdept, directorate, syear, years,program_type, createdby, datecreated) VALUES (:progname, :progdescription, :program_statement, :splan, :objid, :progstrategy, :projsector, :projdept,:directorate, :syear, :years, :program_type, :proguser, :progdate)");
        $result  = $insertSQL->execute(array(':progname' => $progname, ':progdescription' => $progdescription, ':program_statement' => $program_statement, ':splan' => $splan, ':objid' => $objid, ':progstrategy' => $progstrategy, ':projsector' => $projsector, ':projdept' => $projdept, ":directorate" => $directorate, ':syear' => $syear, ':years' => $years, ':program_type' => $program_type, ':proguser' => $user_name, ':progdate' => $currentdate));

        if ($result) {
            $last_id = $db->lastInsertId();
            $current_date = date("Y-m-d H:i:s");
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
                        $target = $_POST[$targets];
                        $budget = $_POST[$budgets];
                        if ($program_type != 1 && $progstrategyobjective == 1) {
                            $query_strategicplan = $db->prepare("SELECT year_target, id FROM tbl_strategic_plan_op_indicator_targets WHERE op_indicator_id ='$indicator' AND year='$year' ");
                            $query_strategicplan->execute();
                            $row_strategicplan = $query_strategicplan->fetch();
                            $count_strategicplan = $query_strategicplan->rowCount();
                            if ($count_strategicplan > 0) {
                                $s_target =   $row_strategicplan['year_target'];
                                $id = $row_strategicplan['id'];
                                $ns_target = $s_target + $target[$p];
                                $updateSQL = $db->prepare("UPDATE tbl_strategic_plan_op_indicator_targets SET year_target=:year_target WHERE id=:id");
                                $result = $updateSQL->execute(array(':year_target' => $ns_target, ":id" => $id));
                            }
                        }
                        $insertSQL = $db->prepare("INSERT INTO tbl_progdetails (progid, year, output, indicator, target, budget) VALUES (:last_id, :year, :outputid,:indicator, :target, :budget)");
                        $insertSQL->execute(array(':last_id' => $last_id, ':year' => $year, ':outputid' => $output, ':indicator' => $indicator, ':target' => $target[$p], ':budget' => $budget[$p]));
                    }
                }
            }

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

    function program_financial_years($syear, $endyear)
    {
        global $db;
        $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$syear' AND yr <= '$endyear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $totalRows_rsYear = $query_rsYear->rowCount();
        $options = '<option value="">.... Select Year from list ....</option>';
        if ($totalRows_rsYear > 0) {
            while ($row_rsYear = $query_rsYear->fetch()) {
                $month =  date('m');
                if ($month >= 7 && $month <= 12) {
                    $currentYear = date('Y') + 1;
                } else {
                    $currentYear = date('Y');
                }
                //if ($row_rsYear['yr'] >= $currentYear) {
                $options .= '<option value="' . $row_rsYear['yr'] . '">' . $row_rsYear['year'] . '</option>';
                //}
            }
        }
        return $options;
    }

    function get_independent_financial_years()
    {
        global $db;
        $program_year_options = '<option value="">.... Select Year from list ....</option>';
        $month =  date('m');
        $currentYear = ($month < 7) ? date("Y") - 1 : date("Y");
        $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$currentYear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $totalRows_rsYear = $query_rsYear->rowCount();
        if ($totalRows_rsYear > 0) {
            do {
                $program_year_options .= '<option value="' . $row_rsYear['yr'] . '">' . $row_rsYear['year'] . '</option>';
            } while ($row_rsYear = $query_rsYear->fetch());
        }
        return $program_year_options;
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
                        <div class="body">
                            <form id="addprogform" method="POST" name="addprogform" action="" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Program Details</legend>
                                    <div class="col-md-12">
                                        <label for="">Program Name *:</label>
                                        <div class="form-line">
                                            <input type="text" name="progname" id="progname" placeholder="Name Your Program" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">Program Problem Statement *:</label>
                                        <div class="form-line">
                                            <input type="text" name="progstatement" id="progstatement" placeholder="Program Problem Statement" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <?php
                                    if ($designation_id == 1) {
                                    ?>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label><?= $ministrylabel ?>*:</label>
                                            <div class="form-line">
                                                <select name="department_id" id="department_id" onchange="get_sections()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                    <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
                                                    <?php
                                                    $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =0 AND deleted=1 ORDER BY stid ASC");
                                                    $query_rsDepartments->execute();
                                                    $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                                    if ($totalRows_rsDepartments > 0) {
                                                        while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                            $sector = $row_rsDepartment['sector'];
                                                            $sector_id = $row_rsDepartment['stid'];
                                                    ?>
                                                            <option value="<?= $sector_id ?>"><?= $sector ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label><?= $departmentlabel ?>*:</label>
                                            <div class="form-line" id="">
                                                <select name="sector_id" id="sector_id" onchange="get_directorate()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                    <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label><?= $directoratelabel ?>*:</label>
                                            <div class="form-line" id="">
                                                <select name="directorate_id" id="directorate_id" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                    <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <input type="hidden" name="department_id" id="department_id" value="<?= $department_id ?>">
                                        <input type="hidden" name="sector_id" id="sector_id" value="<?= $section_id ?>">
                                        <input type="hidden" name="directorate_id" id="directorate_id" value="<?= $directorate_id ?>">
                                    <?php
                                    }


                                    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
                                    $query_rsStrategicPlan->execute();
                                    $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
                                    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();

                                    $plan = $row_rsStrategicPlan['plan'];
                                    $syear = $row_rsStrategicPlan['starting_year'];
                                    $spid = $row_rsStrategicPlan['id'];
                                    $years = $row_rsStrategicPlan['years'];
                                    $endyear = ($syear + $years) - 1; // strategic plan end year

                                    $query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
                                    $query_rsStrategicObjectives->execute();
                                    $totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();

                                    $strategic_objective_options = '';
                                    if ($totalRows_rsStrategicObjectives > 0) {
                                        while ($row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch()) {
                                            $strategic_objective_options .= '<option value="' . $row_rsStrategicObjectives['id'] . '">' . $row_rsStrategicObjectives['objective'] . '</option>';
                                        }
                                    }
                                    $financial_years = program_financial_years($syear, $endyear);
                                    $ind_financial_years = get_independent_financial_years();
                                    ?>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="strat_div">
                                        <label>Strategic Objective *:</label>
                                        <div class="form-line">
                                            <select name="strategic_objective" id="strategic_objective" class="form-control show-tick" onchange="get_strategy()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">.... Select Strategic Objective ....</option>';
                                                <?= $strategic_objective_options ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label>Strategy *:</label>
                                        <div class="form-line">
                                            <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">.... Select Strategy from list ....</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label">Program Start Year *:</label>
                                        <div class="form-line">
                                            <select name="syear" id="starting_year" onchange="get_workplan_header()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                                <?= $program_type == 1 ? $financial_years : $ind_financial_years ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="years">Program Duration In Years *:</label>
                                        <div class="form-line">
                                            <input type="number" name="years" id="program_duration" min="1" onkeyup="get_workplan_header()" onchange="get_workplan_header()" placeholder="Program Duration" class="form-control" required>
                                            <span id="info1" style="color:red"></span>
                                        </div>
                                    </div>
                                    <div id="program_workplan_div">
                                        <div class="col-md-12">
                                            <label class="control-label" id="programworkplan"> Program Output Targets </label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="program" style="width:100%">
                                                    <thead class="thead" id="phead">
                                                        <!-- //tale head -->
                                                    </thead>
                                                    <tbody id="program_workplan_body">
                                                        <!-- tale body -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">Program Description *: <font align="left" style="background-color:#eff2f4">(Briefly describe goals and objectives of the program, approaches and execution methods, and other relevant information that explains the need for program.) </font></label>
                                        <p align="left">
                                            <textarea name="progdesc" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."></textarea>
                                            <script>
                                                CKEDITOR.replace('projdesc', {
                                                    height: 200,
                                                    on: {
                                                        instanceReady: function(ev) {
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
                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="program_type" value="<?= $program_type ?>">
                                        <input type="hidden" name="MM_insert" value="addprogramfrm">
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
require('includes/footer.php');
?>

<script>
    const details = {
        ministry: '<?= $ministrylabel ?>',
        section: '<?= $departmentlabel ?>',
        directorate: '<?= $directoratelabel ?>',
        program_type: '<?= $program_type ?>',
        financial_years: '<?= $financial_years ?>',
        ind_financial_years: '<?= $ind_financial_years ?>',
        edit: 0,
    }
</script>
<script src="assets/js/programs/index.js"></script>