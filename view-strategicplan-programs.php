<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['obj']) && !empty($_GET["obj"]))) {
        $decode_objid =   base64_decode($_GET['obj']);
        $objid_array = explode("obj321", $decode_objid);
        $objid = $objid_array[1];
        $obj = $_GET['obj'];

        $query_obj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id = :strategic_objective_id LIMIT 1");
        $query_obj->execute(array(":strategic_objective_id" => $objid));
        $row_obj = $query_obj->fetch();
        $totalRows_obj = $query_obj->rowCount();

        if ($totalRows_obj > 0) {
            $strategic_objective =  $row_obj["objective"];
            $query_rsprograms = $db->prepare("SELECT * FROM tbl_programs p INNER JOIN tbl_strategic_plan_programs s ON p.progid=s.progid   WHERE strategic_objective_id =:strategic_objective_id ");
            $query_rsprograms->execute(array(":strategic_objective_id" => $objid));
            $totalRows_rsprograms = $query_rsprograms->rowCount();

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
                                <div class="card-header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Strategic Objective: <?= $strategic_objective ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr id="colrow">
                                                    <th width="3%">#</th>
                                                    <th width="24%">Program</th>
                                                    <th width="13%">Budget (ksh)</th>
                                                    <th width="15%">Budget Bal (ksh)</th>
                                                    <th style="width:8%">Project(s)</th>
                                                    <th width="7%" data-orderable="false">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($totalRows_rsprograms > 0) {
                                                    $nm = 0;
                                                    while ($row_rsprograms = $query_rsprograms->fetch()) {
                                                        $progname =  $row_rsprograms['progname'];
                                                        $progid = $row_rsprograms['progid'];
                                                        $strategic_plan_id = $row_rsprograms['strategic_plan_id'];
                                                        $strategic_plan_program_id = $row_rsprograms['id'];

                                                        //fetch budget
                                                        $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid=:progid AND strategic_plan_id=:strategic_plan_id");
                                                        $query_rsBudget->execute(array(":progid" => $progid, ":strategic_plan_id" => $strategic_plan_id));
                                                        $row_rsBudget = $query_rsBudget->fetch();
                                                        $program_budget = !is_null($$row_rsBudget['budget']) ? number_format($row_rsBudget['budget'], 2) : number_format(0, 2);

                                                        //get total projects
                                                        $query_projsbudget = $db->prepare("SELECT COUNT(*) as projectscount, SUM(projcost) as budget FROM tbl_projects WHERE progid = :progid AND strategic_plan_program_id=:strategic_plan_program_id");
                                                        $query_projsbudget->execute(array(":progid" => $progid, ":strategic_plan_program_id" => $strategic_plan_program_id));
                                                        $row_projsbudget = $query_projsbudget->fetch();
                                                        $count_projsbudget = $query_projsbudget->rowCount();

                                                        $projsbudget = !is_null($row_projsbudget['budget']) ? $row_projsbudget['budget'] : number_format(0, 2);
                                                        $projectscount = !is_null($row_projsbudget['projectscount']) ? $row_projsbudget['projectscount'] : number_format(0, 2);
                                                        $progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);

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
                                                                <td> <?= $program_budget ?> </td>
                                                                <td> <?= $progbudgetbal ?> </td>
                                                                <td>
                                                                    <a href="view-project.php?prg=<?= $program_id ?>">
                                                                        <span class="badge bg-purple"><?= $projectscount ?></span>
                                                                    </a>
                                                                </td>
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
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/programs/view-programs.js"></script>