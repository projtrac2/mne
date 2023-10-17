<?php 
$decode_stplanid = (isset($_GET['plan']) && !empty($_GET["plan"])) ? base64_decode($_GET['plan']) : header("Location: view-strategic-plans.php"); 
$stplanid_array = explode("strplan1", $decode_stplanid);
$stplan = $stplanid_array[1];

$stplane = $_GET['plan'];
require('includes/head.php');
if ($permission) { 
	require('functions/strategicplan.php');
    // delete edit add_strategy add_program
    try {
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
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
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
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
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
                                                $query_objPrograms->execute(array(":objid"=>$objid));   
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
                                                                    if (in_array("create",$page_actions)) {
                                                                    ?>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#editItemModal" id="moreItemModalBtn" onclick="addstrategy(<?php echo $objid ?>)">
                                                                                <i class="fa fa-plus-square"></i> Add Strategy
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    if (in_array("update",$page_actions)) {
                                                                    ?>
                                                                        <li>
                                                                            <a href="edit-objective.php?obj=<?php echo $objectiveid ?>">
                                                                                <i class="glyphicon glyphicon-edit"></i> Edit Objective
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    if (in_array("delete",$page_actions)) {
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
?>
<script src="assets/js/strategicplan/strategic-objectives.js"></script>