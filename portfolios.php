<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['plan']) && !empty($_GET["plan"]))) {
        $decode_stplanid =  base64_decode($_GET['plan']);
        $stplanid_array = explode("strplan1", $decode_stplanid);
        $stplan = $stplanid_array[1];

        $stplane = $_GET['plan'];
        require('functions/strategicplan.php');
        // delete edit add_strategy add_program

        $strategicPlan = get_splan($stplan);
        if ($strategicPlan) {

            $strategicplan = $strategicPlan["plan"];
            $vision = $strategicPlan["vision"];
            $mission = $strategicPlan["mission"];
            $datecreated = $strategicPlan["date_created"];
            $spstatus  = $strategicPlan['current_plan'];
            $strategic_plan_objectives = get_strategic_plan_objectives($stplan);

            $query_active_strategic_plan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
            $query_active_strategic_plan->execute();
            $rows_active_strategic_plan = $query_active_strategic_plan->fetch();
            $spid = $rows_active_strategic_plan["id"];
            $duration = $rows_active_strategic_plan["years"];
            $start_year = $rows_active_strategic_plan["starting_year"];

            $query_outcome_indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Outcome' AND active='1'");
            $query_outcome_indicator->execute();


            if (isset($_POST["kpi"])) {
                $kpi = $_POST["kpi"];
                $kpi_description = $_POST["kpi_description"];
                $objid = $_POST['objid'];
                $indid = $_POST['indicator'];
                $current_date = date("Y-m-d");

                if ($kpi == "addkpi") {
                    $query_insert_kpi = $db->prepare("INSERT INTO tbl_kpi(kpi_description, strategic_objective_id, outcome_indicator_id, created_by, date_created) VALUES (:kpi, :objid, :indid, :user, :dates)");
                    $query_insert_kpi->execute(array(":kpi" => $kpi_description, ":objid" => $objid, ":indid" => $indid, ":user" => $user_name, ":dates" => $current_date));
                    $kpi_id = $db->lastInsertId();

                    for ($i = 0; $i < $duration; $i++) {
                        $year = $start_year + $i;
                        $target = $_POST[$year . 'target'];

                        $query_insert_target = $db->prepare("INSERT INTO tbl_kpi_targets(kpi_id, year, target) VALUES (:kpi_id, :year, :target)");
                        $query_insert_target->execute(array(":kpi_id" => $kpi_id, ":year" => $year, ":target" => $target));
                        $target_id = $db->lastInsertId();

                        for ($j = 0; $j < 4; $j++) {
                            $threshold = $_POST[$year . 'threshold'][$j];

                            $query_insert_target = $db->prepare("INSERT INTO tbl_kpi_target_thresholds(kpi_id, tbl_kpi_target_id, threshold) VALUES (:kpi_id, :target_id, :threshold)");
                            $query_insert_target->execute(array(":kpi_id" => $kpi_id, ":target_id" => $target_id, ":threshold" => $threshold));
                        }
                    }
                } else {
                    /* $ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, outcome=:outcome, indicator=:indicator, baseline=:kpibaseline, target=:target, created_by=:user, date_created=:dates WHERE id='$objid'");
				$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":outcome" => $outcome, ":indicator" => $indicator, ":kpibaseline" => $kpibaseline, ":target" => $outcometarget, ":user" => $user, ":dates" => $current_date)); */
                }
            }

?>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon ?>
                            <?= $pageTitle ?>
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
                                <div class="card-header">
                                    <div class="header" style="padding-bottom:0px">
                                        <div class="button-demo" style="margin-top:-15px">
                                            <span class="label bg-black" style="font-size:18px"><img src="assets/images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu </span>
                                            <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                            <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                            <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Strategic Objectives</a>
                                            <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a>
                                            <a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                            <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                            <a href="strategic-plan-implementation-matrix.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Implementation Matrix</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                            <thead>
                                                <tr>
                                                    <th width="3%">#</th>
                                                    <th width="40%">Strategic Objective</th>
                                                    <th width="27%">Key Result Area</th>
                                                    <th width="10%">Strategies</th>
                                                    <th width="10%">Programs</th>
                                                    <th width="10%" data-orderable="false">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $counter = 0;
                                                if ($strategic_plan_objectives > 0) {
                                                    foreach ($strategic_plan_objectives as $strategic_plan_objective) {
                                                        $counter++;
                                                        $objective = $strategic_plan_objective['objective'];
                                                        $kra = $strategic_plan_objective['kra'];
                                                        $objid = $strategic_plan_objective['id'];
                                                        $kpi = $strategic_plan_objective['kpi'];

                                                        $objective_strategy = get_strategic_objectives_strategy($objid);
                                                        $objective_programs = strategic_objective_programs($objid);

                                                        $total_strategies = ($objective_strategy) ? count($objective_strategy) : 0;
                                                        $objectiveid = base64_encode("obj321{$objid}");

                                                        $query_objPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE program_type=1 AND strategic_obj=:objid");
                                                        $query_objPrograms->execute(array(":objid" => $objid));
                                                        $totalRows_objPrograms = $query_objPrograms->rowCount();
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $counter ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $objective; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $kra; ?>
                                                            </td>
                                                            <td>
                                                                <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(<?php echo $objid ?>)">
                                                                    <span class="badge bg-orange"><?php echo $total_strategies ?> </span> </a>
                                                            </td>
                                                            <td>
                                                                <a href="view-strategicplan-programs.php?obj=<?= $objectiveid ?>">
                                                                    <span class="badge bg-deep-purple"><?php echo $totalRows_objPrograms ?> </span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#moreInfoItemModal" id="moreInfoItemModalBtn" onclick="moreInfo(<?php echo $objid ?>)">
                                                                                <i class="glyphicon glyphicon-file"></i> More Details
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                        if ($spstatus  != 2) {
                                                                            if (in_array("create", $page_actions)) {
                                                                        ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#editItemModal" id="moreItemModalBtn" onclick="addstrategy(<?php echo $objid ?>)">
                                                                                        <i class="fa fa-plus-square"></i> Add Strategy
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#addKPIModal" id="addKPIModalBtn" onclick="addkpi(<?php echo $objid ?>)">
                                                                                        <i class="fa fa-key"></i> Add KPI
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("update", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a href="edit-objective.php?obj=<?php echo $objectiveid ?>">
                                                                                        <i class="glyphicon glyphicon-edit"></i> Edit Objective
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("delete", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(<?php echo $objid ?>)">
                                                                                        <i class="glyphicon glyphicon-trash"></i> Remove Objective
                                                                                    </a>
                                                                                </li>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- end body  -->
            <!-- Start Modal Item Edit -->
            <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Add New Strategy</h4>
                        </div>
                        <div class="modal-body" style="max-height:450px; overflow:auto;">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="div-result">
                                                <form class="form-horizontal" id="addstrategyForm" action="assets/processor/add-new-strategy.php" method="POST" autocomplete="off">
                                                    <?= csrf_token_html(); ?>
                                                    <br>
                                                    <div id="result">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Strategy:</label>
                                                            <div class="form-line">
                                                                <input type="text" name="strategy" id="strategy" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter a new strategy" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer editItemFooter">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strat">
                                                            <input type="hidden" name="username" id="username" value="<?= $user_name ?>">
                                                            <input type="hidden" name="addstrategy" value="addstrategy">
                                                            <input name="save" type="submit" id="addStrategyBtn" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                        </div>
                                                    </div> <!-- /modal-footer -->
                                                </form> <!-- /.form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                    </div>
                    <!-- /modal-content -->
                </div>
                <!-- /modal-dailog -->
            </div>

            <!-- Start Add KPI Modal -->
            <div class="modal fade" id="addKPIModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Add Key Performance Indicator (KPI)</h4>
                        </div>
                        <div class="modal-body" style="max-height:450px; overflow:auto;">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="addKPIForm" action="" method="POST" autocomplete="off">
                                            <?= csrf_token_html(); ?>
                                            <br>
                                            <div id="result">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>KPI Description:</label>
                                                    <div class="form-line">
                                                        <input type="text" name="kpi_description" id="kpi_description" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter a new KPI description" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                    <div class="form-inline">
                                                        <label for="">Link Outcome Indicator</label>
                                                        <select name="indicator" id="indicator" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:100%" required>
                                                            <option value="">.... Select Impact ....</option>
                                                            <?php
                                                            while ($row_outcome_indicator = $query_outcome_indicator->fetch()) {
                                                            ?>
                                                                <font color="black">
                                                                    <option value="<?php echo $row_outcome_indicator['indid'] ?>"><?php echo $row_outcome_indicator['indicator_name'] ?></option>
                                                                </font>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-alt" style="color:green" aria-hidden="true"></i> KPI Targets & Thresholds</legend>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover" style="width:100%">
                                                                <thead class="thead" id="phead">
                                                                    <tr>
                                                                        <th class="text-center"></th>
                                                                        <?php
                                                                        for ($i = 0; $i < $duration; $i++) {
                                                                            $year = $start_year + $i;
                                                                            $endyear = $year + 1;
                                                                            $fy = $year . "/" . $endyear;
                                                                        ?>
                                                                            <th colspan="4" class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $fy ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <strong>Target:</strong>
                                                                        </td>
                                                                        <?php
                                                                        for ($i = 0; $i < $duration; $i++) {
                                                                            $year = $start_year + $i;
                                                                            $endyear = $year + 1;
                                                                            $fy = $year . "/" . $endyear;
                                                                        ?>
                                                                            <td colspan="4">
                                                                                <input type="number" name="<?= $year ?>target" id="strategy" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter <?= $fy ?> Target Change in %ntage" />
                                                                            </td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <strong>Threshold:</strong>
                                                                        </td>
                                                                        <?php
                                                                        for ($i = 0; $i < $duration; $i++) {
                                                                            $year = $start_year + $i;
                                                                            $endyear = $year + 1;
                                                                            $fy = $year . "/" . $endyear;
                                                                        ?>
                                                                            <td>
                                                                                <input type="number" name="<?= $year ?>threshold[]" id="<?= $year ?>threshold1" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="%" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="<?= $year ?>threshold[]" id="<?= $year ?>threshold2" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="%" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="<?= $year ?>threshold[]" id="<?= $year ?>threshold3" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="%" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="<?= $year ?>threshold[]" id="<?= $year ?>threshold4" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="%" />
                                                                            </td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strat">
                                                    <input type="hidden" name="kpi" id="kpi" value="addkpi">
                                                    <input type="hidden" name="objid" id="objid">
                                                    <input name="save" type="submit" id="addKPIBtn" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                </div>
                                            </div> <!-- /modal-footer -->
                                        </form> <!-- /.form -->
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                    </div>
                    <!-- /modal-content -->
                </div>
                <!-- /modal-dailog -->
            </div>
            <!-- End Add KPI Modal -->

            <!-- Start Item more -->
            <div class="modal fade" tabindex="-1" role="dialog" id="moreInfoItemModal">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> More Information</h4>
                        </div>
                        <div class="modal-body" id="moreinformation">
                        </div>
                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Item more -->


            <!-- Start Item more -->
            <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Strategies</h4>
                        </div>
                        <div class="modal-body" id="moreinfo">
                        </div>
                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Item more -->

            <!-- Start Item Delete -->
            <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
                        </div>
                        <div class="modal-body">
                            <div class="removeItemMessages"></div>
                            <p align="center">Are you sure you want to delete this record?</p>
                        </div>
                        <div class="modal-footer removeContractor NationalityFooter">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                                <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Item Delete -->


            <!-- Start Item Delete -->
            <div class="modal fade" tabindex="-1" role="dialog" id="removeStrategyModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
                        </div>
                        <div class="modal-body">
                            <div class="removeItemMessages"></div>
                            <p align="center">Are you sure you want to delete this record?</p>
                        </div>
                        <div class="modal-footer removeContractor NationalityFooter">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                                <button type="button" class="btn btn-success" id="removeStrategyBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Item Delete -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }

    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/strategicplan/strategic-objectives.js"></script>