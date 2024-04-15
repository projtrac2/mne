<?php
try {

$decode_stplanid = (isset($_GET['plan']) && !empty($_GET["plan"])) ? base64_decode($_GET['plan']) : header("Location: view-strategic-plans.php");
$stplanid_array = explode("strplan1", $decode_stplanid);
$stplan = $stplanid_array[1];
$strategicplanid = $_GET['plan'];

$stplane = $_GET['plan'];
require('includes/head.php');
if ($permission) {
    require('functions/strategicplan.php');
    // delete edit add_strategy add_program
        $strategicPlan = get_splan($stplan);
        if (!$strategicPlan) {
            header("Location: view-strategic-plans.php");
        }

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
            $objid = $_POST['objid'];
            $indid = $_POST['indicator'];
            $weighting = $_POST['weighting'];
            $data_source = $_POST['data_source'];
            $frequency = $_POST['data_frequency'];
            $initial_basevalue = $_POST['initial_basevalue'];
            $responsible = $_POST['responsible'];
            $current_date = date("Y-m-d");
            $record_name = $data_source == 2 ? $_POST['record_name'] : "";

            if ($kpi == "addkpi") {
                $query_insert_kpi = $db->prepare("INSERT INTO tbl_kpi(strategic_objective_id, outcome_indicator_id, weighting, data_source, record_name, data_frequency, initial_baseline, responsible, created_by, date_created) VALUES (:objid, :indid, :weighting, :data_source, :record_name, :frequency, :initial_basevalue, :responsible, :user, :dates)");
                $result = $query_insert_kpi->execute(array(":objid" => $objid, ":indid" => $indid, ":weighting" => $weighting, ":data_source" => $data_source, ":record_name" => $record_name, ":frequency" => $frequency, ":initial_basevalue" => $initial_basevalue, ":responsible" => $responsible, ":user" => $user_name, ":dates" => $current_date));
                $kpi_id = $db->lastInsertId();

                for ($i = 0; $i < $duration; $i++) {
                    $year = $start_year + $i;
                    $target = $_POST[$year . 'target'];

                    $query_insert_target = $db->prepare("INSERT INTO tbl_kpi_targets(kpi_id, year, target) VALUES (:kpi_id, :year, :target)");
                    $query_insert_target->execute(array(":kpi_id" => $kpi_id, ":year" => $year, ":target" => $target));
                    $target_id = $db->lastInsertId();

                    $threshold_1 = $_POST[$year . '_threshold_1'];
                    $threshold_2 = $_POST[$year . '_threshold_2'];
                    $threshold_3 = $_POST[$year . '_threshold_3'];
                    $threshold_4 = $_POST[$year . '_threshold_4'];

                    $query_insert_target = $db->prepare("INSERT INTO tbl_kpi_target_thresholds(kpi_id, kpi_target_id, threshold_1, threshold_2, threshold_3, threshold_4) VALUES (:kpi_id, :target_id, :threshold_1, :threshold_2, :threshold_3, :threshold_4)");
                    $query_insert_target->execute(array(":kpi_id" => $kpi_id, ":target_id" => $target_id, ":threshold_1" => $threshold_1, ":threshold_2" => $threshold_2, ":threshold_3" => $threshold_3, ":threshold_4" => $threshold_4));
                }
                if ($result) {
                    $redirect_url = "view-strategic-plan-objectives?plan=" . $strategicplanid;
                    $msg = 'KPI Successfully added';
                    $results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
							'icon':'success',
						showConfirmButton: false });
						setTimeout(function(){
							window.location.href = '$redirect_url';
						}, 2000);
					</script>";
                } else {
                    $msg = 'Error saving KPI details, please try again later!!';
                    $results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
                }
            } else {
                /* $ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, outcome=:outcome, indicator=:indicator, baseline=:kpibaseline, target=:target, created_by=:user, date_created=:dates WHERE id='$objid'");
				$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":outcome" => $outcome, ":indicator" => $indicator, ":kpibaseline" => $kpibaseline, ":target" => $outcometarget, ":user" => $user, ":dates" => $current_date)); */
			}

		}

	?>
<style>
.container{

     margin-top:100px;
 }
.modal.fade .modal-bottom,
.modal.fade .modal-left,
.modal.fade .modal-right,
.modal.fade .modal-top {
    position: fixed;
    z-index: 1055;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
    max-width: 100%
}

            margin-top: 100px;
        }

        .modal.fade .modal-bottom,
        .modal.fade .modal-left,
        .modal.fade .modal-right,
        .modal.fade .modal-top {
            position: fixed;
            z-index: 1055;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            max-width: 100%
        }

        .modal.fade .modal-right {
            left: auto !important;
            transform: translate3d(100%, 0, 0);
            transition: transform .3s cubic-bezier(.25, .8, .25, 1)
        }

        .modal.fade.show .modal-bottom,
        .modal.fade.show .modal-left,
        .modal.fade.show .modal-right,
        .modal.fade.show .modal-top {
            transform: translate3d(0, 0, 0)
        }

        .w-xl {
            width: 50%
        }

        .modal-content,
        .modal-footer,
        .modal-header {
            border: none
        }

        .h-100 {
            height: 100% !important
        }

        .list-group.no-radius .list-group-item {
            border-radius: 0 !important
        }

        .btn-light {
            color: #212529;
            background-color: #f5f5f6;
            border-color: #f5f5f6
        }

        .btn-light:hover {
            color: #212529;
            background-color: #e1e1e4;
            border-color: #dadade
        }

        .modal-footer {
            align-items: center
        }

        /* Important part */
        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            height: 80vh;
            overflow-y: auto;
        }
    </style>
    <style src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"></style>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
                                    <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                    <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                    <!-- <a href="portfolios.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a> -->
                                    <a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                    <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                    <a href="strategic-plan-implementation-matrix.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Implementation Matrix</a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <input type="hidden" value="0" id="clicked">
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

                                                $sql = $db->prepare("SELECT * FROM `tbl_programs` g INNER JOIN tbl_strategic_plan_programs s ON  s.progid=g.progid WHERE s.strategic_plan_id=:strategic_plan_id AND s.strategic_objective_id=:strategic_objective_id");
                                                $sql->execute(array(":strategic_plan_id" => $stplan, ":strategic_objective_id" => $objid));
                                                $rows_count = $sql->rowCount();

                                                $query_kpis = $db->prepare("SELECT id, indicator_name, weighting FROM tbl_kpi k left join tbl_indicator i on i.indid=k.outcome_indicator_id WHERE strategic_objective_id=:objid");
                                                $query_kpis->execute(array(":objid" => $objid));
                                                $totalRows_kpis = $query_kpis->rowCount();
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $counter ?>
                                                    </td>
                                                    <td>
                                                        <div onclick="objective_kpi(<?= $objid ?>)"><?php echo $objective; ?></div>
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
                                                            <span class="badge bg-deep-purple"><?php echo $rows_count ?> </span>
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
                                                                            <a type="button" data-toggle="modal" data-target="#addKPIModal" id="addKPIModalBtn" onclick="addkpi(<?php echo $objid ?>, '<?= $objective ?>')">
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
                                                <?php if ($totalRows_kpis > 0) { ?>
                                                    <tr class="objid <?= $objid ?>" style="background-color:#cccccc">
                                                        <th></th>
                                                        <th colspan="2">KPI</th>
                                                        <th>Weighting</th>
                                                        <th colspan="2">Performance</th>
                                                    </tr>
                                                    <?php
                                                    $count_kpi = 0;
                                                    while ($rows = $query_kpis->fetch()) {
                                                        $count_kpi++;
                                                        $kpi_id = $rows['id'];
                                                        $kpi_description = $rows['indicator_name'];
                                                        $kpi_weighting = $rows['weighting'];
                                                        $kpi_performance = 10 . "%";
                                                    ?>
                                                        <tr class="objid <?= $objid ?>">
                                                            <td><?= $counter . "." . $count_kpi ?></td>
                                                            <td colspan="2">
                                                                <a data-toggle="modal" data-target="#kpi-modal-right" data-toggle-class="modal-open-aside" onclick="kpi_more_info(<?= $kpi_id ?>)">
                                                                    <?= $kpi_description ?>
                                                                </a>
                                                            </td>
                                                            <td><?= $kpi_weighting ?></td>
                                                            <td colspan="2"><?= $kpi_performance ?></td>
                                                        </tr>
                                        <?php
                                                    }
                                                }
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

    <!-- Start Add Strategy Modal -->
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
    <!-- End Add Strategy Modal -->

    <!-- Start View KPI Details Modal -->
    <div id="kpi-modal-right" class="modal fade" data-backdrop="true">
        <div class="modal-dialog modal-right modal-lg w-xl">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-bar-chart" aria-hidden="true" style="color:yellow"></i> <span id="modal_info"> KPI Performance Details</span></h3>
                </div>
                <div class="modal-body">
                    <div class="p-4" id="kpi_details">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End View KPI Details Modal -->

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
                                    <br>
                                    <div id="result">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Strategic Objective:</label>
                                            <div id="objective" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <div class="form-inline">
                                                <label for="">Link Outcome Indicator *:</label>
                                                <select name="indicator" id="indicator" class="form-control require" onchange="get_measurement_unit()" style="width:100%;" required>
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
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label>Unit of Measure *:</label>
                                            <div id="unit" class="form-control">....</div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label>Weighting *:</label>
                                            <input type="number" name="weighting" id="weighting" class="form-control" required="required" placeholder="Enter KPI weight">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label>Base Year Value (<span id="basevalue_id"></span>)*:</label>
                                            <input type="number" name="initial_basevalue" id="initial_basevalue" class="form-control" required="required" placeholder="Enter the base year value">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label>Data Collection Frequency *:</label>
                                            <select name="data_frequency" id="data_frequency" class="form-control require" required>
                                                <option value="">.... Select Frequency ....</option>
                                                <?php
                                                $query_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE level > 2 AND status=1");
                                                $query_frequency->execute();

                                                while ($row_frequency = $query_frequency->fetch()) {
                                                ?>
                                                    <font color="black">
                                                        <option value="<?php echo $row_frequency['fqid'] ?>"><?php echo $row_frequency['frequency'] ?></option>
                                                    </font>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label for="">Responsible *:</label>
                                            <select name="responsible" id="responsible" class="form-control require" required>
                                                <option value="">.... Select Responsible Role ....</option>
                                                <?php
                                                $query_designation = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE active=1 ");
                                                $query_designation->execute();
                                                while ($row_designation = $query_designation->fetch()) {
                                                ?>
                                                    <font color="black">
                                                        <option value="<?php echo $row_designation['moid'] ?>"><?php echo $row_designation['designation'] ?></option>
                                                    </font>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                            <label>Source of data *:</label>
                                            <select name="data_source" id="data_source" class="form-control require" onchange="get_record_type()" required>
                                                <option value="">.... Select Data Source ....</option>
                                                <font color="black">
                                                    <option value="1">Survey</option>
                                                    <option value="2">Records</option>
                                                </font>
                                            </select>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="record_name" style="margin-bottom:10px">

                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
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
                                                                        <input type="number" name="<?= $year ?>target" id="strategy" class="form-control" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter <?= $fy ?> Target Change" />
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
                                                                        <input type="number" name="<?= $year ?>_threshold_1" id="<?= $year ?>threshold1" class="form-control thresholds" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="<?= $year ?>_threshold_2" id="<?= $year ?>threshold2" class="form-control thresholds" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="<?= $year ?>_threshold_3" id="<?= $year ?>threshold3" class="form-control thresholds" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="<?= $year ?>_threshold_4" id="<?= $year ?>threshold4" class="form-control thresholds" style="height:35px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="" />
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

require('includes/footer.php');

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
<script src="assets/js/strategicplan/strategic-objectives.js"></script>