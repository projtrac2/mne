<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "ADD NEW PROGRAM";

if ($permission) {
    require('functions/strategicplan.php');
    require('functions/department.php');
    require('functions/funding.php');
    require('functions/programs.php');

    try {
        $spid = 0;
        if (isset($_GET['objid'])) {
            $objid = base64_decode($_GET['objid']);
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

            $query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$syear' AND yr <= '$endyear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $totalRows_rsYear = $query_rsYear->rowCount();

            $query_sp =  $db->prepare("SELECT spid FROM tbl_strategic_plan_objectives o inner join tbl_key_results_area k on k.id=o.kraid WHERE o.id = '$objid'");
            $query_sp->execute();
            $row_sp = $query_sp->fetch();
            $spid = $row_sp["spid"];
        }

        $departments = get_departments();
        $funding_types = get_funding_type();

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprogramfrm")) {
            $user_name = 3;
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
            } else {
                if ($progstrategyobjective == 1) {
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

            $insertSQL = $db->prepare("INSERT INTO tbl_programs (progname, description, problem_statement, strategic_plan, strategic_obj, progstrategy, projsector, projdept, syear, years,program_type, createdby, datecreated) VALUES (:progname, :progdescription, :program_statement, :splan, :objid, :progstrategy, :projsector, :projdept, :syear, :years, :program_type, :proguser, :progdate)");
            $result  = $insertSQL->execute(array(':progname' => $progname, ':progdescription' => $progdescription, ':program_statement' => $program_statement, ':splan' => $splan, ':objid' => $objid, ':progstrategy' => $progstrategy, ':projsector' => $projsector, ':projdept' => $projdept, ':syear' => $syear, ':years' => $years, ':program_type' => $program_type, ':proguser' => $user_name, ':progdate' => $currentdate));

            if ($result) {
                $last_id = $db->lastInsertId();
                $current_date = date("Y-m-d H:i:s");
                for ($j = 0; $j < count($_POST["source_category"]); $j++) {
                    $sourcecat = $_POST['source_category'][$j];
                    $amt = $_POST['amountfunding'][$j];
                    $insertSQL = $db->prepare("INSERT INTO tbl_myprogfunding (progid, sourcecategory, amountfunding, created_by, date_created) VALUES (:last_id, :sourcecat,  :amt, :username, :cdate)");
                    $insertSQL->execute(array(':last_id' => $last_id, ':sourcecat' => $sourcecat, ':amt' => $amt,  ':username' => $user_name, ':cdate' => $current_date));
                }

                for ($j = 0; $j < count($_POST['indicator']); $j++) {
                    $output = $_POST['output'][$j];
                    $indicator = $_POST['indicator'][$j];
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
                    showConfirmButton: false });
                    setTimeout(function(){
                            window.location.href = '$url';
                        }, 2000);
                </script>";
            }
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <script src="assets/ckeditor/ckeditor.js"></script>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                </div>
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

                                    <div class="col-md-6">
                                        <label> <?= $ministrylabel ?> *:</label>
                                        <div class="form-line">
                                            <select name="projsector" id="projsector" onchange="get_department()" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">.... Select <?= $ministrylabel ?> from list ....</option>
                                                <?php
                                                for ($i = 0; $i < count($departments); $i++) {
                                                ?>
                                                    <option value="<?php echo $departments[$i]['stid'] ?>"><?php echo $departments[$i]['sector'] ?></option>
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
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($_GET['objid'])) {
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
                                                            echo '<option value="' . $strategy['id'] . '">' . $strategy['strategy'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="syear">Strategic Plan Start Year *:</label>
                                            <div class="form-line">
                                                <input type="hidden" name="splan" value="<?= $spid ?>">
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
                                                            $month =  date('m');

                                                            if ($month >= 7 && $month <= 12) {
                                                                $currentYear = date('Y') + 1;
                                                            } else {
                                                                $currentYear = date('Y');
                                                            }
                                                            if ($row_rsYear['yr'] >= $currentYear) {
                                                                echo '<option value="' . $row_rsYear['yr'] . '">' . $row_rsYear['year'] . '</option>';
                                                            }
                                                        } while ($row_rsYear = $query_rsYear->fetch());
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="years">Program Duration In Years *:</label>
                                            <div class="form-line">
                                                <input type="number" name="years" id="program_duration" onkeyup="program_workplan_header()" onchange="program_workplan_header()" placeholder="Program Duration" class="form-control" required>
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
                                                <input name="progstrategyobjective" type="radio" value="1" id="strat1" onchange="hide_strategicplan(1)" class="with-gap radio-col-green insp" required="required" />
                                                <label for="strat1">YES</label>
                                                <input name="progstrategyobjective" type="radio" value="0" id="strat2" onchange="hide_strategicplan(0)" class="with-gap radio-col-red insp" required="required" />
                                                <label for="strat2">NO</label>
                                            </div>
                                        </div>
                                        <div id="strategicplan_div">

                                        </div>
                                        <div id="program_year_div">

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
                                        <input type="hidden" name="program_budget" id="program_budget">
                                        <div class="col-md-6">
                                            <label for="years">Program Budget *:</label>
                                            <div class="form-line">
                                                <input type="text" name="program_bud" id="program_bud" placeholder="Program Budget" class="form-control" readonly>
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
                                                        <tr id="financerow0">
                                                            <td> 1 </td>
                                                            <td>
                                                                <select data-id="0" name="source_category[]" id="source_categoryrow0" class="form-control validoutcome selected_category" required="required">
                                                                    <?php
                                                                    $input = '';
                                                                    if ($funding_types) {
                                                                        $input .= '<option value="">Select Funds Source Category</option>';
                                                                        foreach ($funding_types as $funding_type) {
                                                                            $input .= '<option value="' . $funding_type['id'] . '"> ' . $funding_type['type'] . '</option>';
                                                                        }
                                                                    } else {
                                                                        $input .= '<option value="">No Funding Category Found !!!</option>';
                                                                    }
                                                                    echo $input;
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="amountfunding[]" onchange="calculate_budget(0)" onkeyup="calculate_budget(0)" id="amountfundingrow0" placeholder="Enter amount in local currency" class="form-control financierTotal" required />
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="objid" value="<?php echo (isset($_GET['objid'])) ? $objid : ''; ?>">
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
    $(document).ready(function() {
        hide_workplan(0);
    });
</script>
<script src="assets/js/programs/add-edit-programs.js"></script>