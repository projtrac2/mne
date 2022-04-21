<?php
$stplan = (isset($_GET['plan'])) ? base64_decode($_GET['plan']) : header("Location: view-strategic-plans.php");
$stplane = base64_encode($stplan);
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    require('functions/strategicplan.php');
    try {
        $strategicPlan = get_strategic_plan($stplan);
        if (!$strategicPlan) {
            // redirect back to strategic plan  
            header("Location: view-strategic-plans.php");
        }

        $strategicplan = $strategicPlan["plan"];
        $vision = $strategicPlan["vision"];
        $mission = $strategicPlan["mission"];
        $datecreated = $strategicPlan["date_created"];
        $spstatus  = $strategicPlan['current_plan'];

        // get the key results areas under this strategic plan 
        $kras = get_strategic_plan_kras($stplan);
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?> KRAs (<?php echo $strategicplan ?>)
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <?php
                            if ($file_rights->add) {
                            ?>
                                <div style="float:right; margin-top:0px; margin-right:5px">
                                    <a type="button" class="btn btn-success" data-toggle="modal" data-target="#addItemModal" id="addItemModalBtn" onclick="addKRA()" style="margin-top:0px">ADD NEW KRA</a>
                                </div>
                            <?php
                            }
                            ?>
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
                                    <span class="label bg-black" style="font-size:18px">
                                        <img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
                                    </span>
                                    <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                    <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                    <a href="view-strategic-workplan-budget.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Targets Distribution</a>
                                    <a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                    <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                    <a href="view-objective-performance.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="70%">Key Result Area </th>
                                            <th width="10%">Strategic Objectives</th>
                                            <?php
                                            if ($file_rights->edit && $file_rights->delete_permission) {
                                            ?>
                                                <th width="15%" data-orderable="false">Action</th>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 0;
                                        if ($kras) {
                                            foreach ($kras as $kra) {
                                                $counter++;
                                                $kraName = $kra['kra'];
                                                $kraid = $kra['id'];
                                                $encode_kraid = base64_encode($kra['id']);
                                                $kra_objectives = get_kra_strategic_objectives($kraid);
                                                $total_kra_strategic_objectives  = ($kra_objectives) ? count($kra_objectives) : 0;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $counter ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $kraName; ?>
                                                    </td>
                                                    <td align="center">
                                                        <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(<?php echo $kraid ?>)">
                                                            <span class="badge bg-purple"><?php echo $total_kra_strategic_objectives ?> </span> </a>
                                                    </td>
                                                    <?php
                                                    if ($file_rights->add && $file_rights->delete_permission) {
                                                    ?>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <?php
                                                                if ($spstatus == 1) {
                                                                ?>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" id="addobjective" href="add-objective.php?kra=<?= $encode_kraid ?>">
                                                                                <i class="fa fa-plus-square"></i> Add Objective</a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#editItemModal" id="editItemModalBtn" onclick="editItem(<?php echo $kraid ?>)">
                                                                                <i class="glyphicon glyphicon-edit"></i> Edit
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(<?php echo $kraid ?>)">
                                                                                <i class="glyphicon glyphicon-trash"></i> Remove
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
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
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Add Key Result Areas </h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="addItemForm" action="assets/processor/fetch-selected-kra-item" method="POST">
                                            <br />
                                            <div class="col-md-12 id=" edit-kra-messages"></div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Key Result Areas: </font>
                                                </label>
                                                <input type="text" class="form-control" id="addkra" placeholder="Key Result Areas" name="addkra" required autocomplete="off">
                                            </div>
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="username" id="username" value="<?= $user_name ?>">
                                                    <input type="hidden" name="spid" id="spid" value="<?= $stplan ?>">
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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

    <!-- Start Modal Item Edit -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Key Result Areas </h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="fetch-selected-kra-item" method="POST">
                                            <br />
                                            <div class="col-md-12 id=" edit-kra-messages"></div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Key Result Areas: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editname" placeholder="Key Result Areas" name="editname" required autocomplete="off">
                                            </div>
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="edititem" id="edititem" value="edit">
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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
    <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Strategic Objectives</h4>
                </div>
                <div class="modal-body" id="moreinfo">
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
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
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/strategicplan/view-kra.js"></script>