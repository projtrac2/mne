<?php
$decode_objid = (isset($_GET['obj']) && !empty($_GET["obj"])) ? base64_decode($_GET['obj']) : header("Location: view-strategic-plan-objectives.php?obj=" . $_GET['obj']);
$objid_array = explode("obj321", $decode_objid);
$objid = $objid_array[1];
$obj = $_GET['obj'];
require('includes/head.php');
if ($permission) {
    require('functions/programs.php');
    try {
        $strategic_plan_programs = get_programs(3, $objid);
        $total_strategic_plan_programs = ($strategic_plan_programs) ? count($strategic_plan_programs) : 0;
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <style>
        .modal-lg {
            max-width: 100% !important;
            width: 90%;
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?= $pageTitle ?>
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
                <div class="block-header">
                    <?= $objid ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr id="colrow">
                                            <th width="3%">#</th>
                                            <th width="24%">Program</th>
                                            <th width="12%">Program Type</th>
                                            <th width="13%">Budget (ksh)</th>
                                            <th width="15%">Budget Bal (ksh)</th>
                                            <th style="width:8%">Project(s)</th>
                                            <th width="10%">Start Year </th>
                                            <th width="8%">Duration </th>
                                            <th width="7%" data-orderable="false">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($total_strategic_plan_programs > 0) {
                                            $nm = 0;
                                            foreach ($strategic_plan_programs as $strategic_plan_program) { 
                                                $progname =  $strategic_plan_program['progname'];
                                                $projduration = $strategic_plan_program['years'] . " Years";
                                                $projsyear = $strategic_plan_program['syear'];
                                                $projendsfc = $projsyear + 1;
                                                $progid = $strategic_plan_program['progid'];
                                                $program_budget = get_program_budget($progid);
                                                $program_amount_spent = get_program_amount_spent($progid);
                                                $budget_balance = ($program_budget > 0) ? $program_budget - $program_amount_spent : 0;
                                                $program_projects =  get_program_projects($progid);
                                                
                                                $total_projects = ($program_projects) ? count($program_projects) : 0;
                                                $program_id = base64_encode("progid54321{$progid}");
                                                //get program and department 
                                                $prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
                                                $prog->execute(array(":progid" => $progid));
                                                $rowprog = $prog->fetch();
                                                $projdept = $rowprog["projdept"];

                                                $project_department = $rowprog['projsector'];
                                                $project_section = $rowprog['projdept'];
                                                $project_directorate = $rowprog['directorate'];
                                                $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                if ($filter_department) {
                                                    $nm++;
                                        ?>
                                                    <tr>
                                                        <td><?= $nm ?></td>
                                                        <td><?= $progname ?></td>
                                                        <td><?= $progname ?></td>
                                                        <td> <?= number_format($program_budget, 2) ?> </td>
                                                        <td> <?= number_format($budget_balance, 2) ?> </td>
                                                        <td>
                                                            <a href="view-project.php?prg=<?= $program_id ?>">
                                                                <span class="badge bg-purple"><?= $total_projects ?></span>
                                                            </a>
                                                        </td>
                                                        <td> <?= $projsyear . "/" . $projendsfc ?> </td>
                                                        <td><?= $projduration ?> </td>
                                                        <td>
                                                            <!-- Single button -->
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#removeItemModal" id="moreInfoModalBtn" onclick="program_info(<?= $progid ?>)">
                                                                            <i class="glyphicon glyphicon-file"></i> More Info
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                        <?php
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
    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-plus"></i>Program </h4>
                </div>
                <div class="modal-body">
                    <div id="progmoreinfo"></div>
                </div>
                <div class="modal-footer removeContractor NationalityFooter">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
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
<script src="assets/js/programs/view-programs.js"></script> 