<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['plan']) && !empty($_GET["plan"]))) {
        $decode_stplanid =   base64_decode($_GET['plan']);
        $stplanid_array = explode("strplan1", $decode_stplanid);
        $spid = $stplanid_array[1];
        $stplan = $stplanid_array[1];
        $stplane = $_GET['plan'];

        $sql = $db->prepare("SELECT * FROM `tbl_programs`  ORDER BY `progid` ASC");
        $sql->execute();
        $rows_count = $sql->rowCount();
?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon   . " " . $pageTitle  ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                    Go Back
                                </button>
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                        <input type="hidden" name="objid" id="spid" value="<?= $spid ?>">
                        <input type="hidden" name="spid" id="planid" value="<?= $stplan ?>">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="header" style="padding-bottom:0px">
                                    <div class="button-demo" style="margin-top:-15px">
                                        <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                        <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                        <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                        <!-- <a href="portfolios.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a> -->
                                        <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                        <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                        <a href="strategic-plan-implementation-matrix.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Implementation Matrix</a>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <div class="card-header">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="btn-group" style="float:right">
                                            <div class="btn-group" style="float:right">
                                                <a href="add-program.php?plan=<?= $stplane ?>" class="btn btn-success">New Program </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">#</th>
                                                        <th width="36%">Program</th>
                                                        <th width="13%">Budget (ksh)</th>
                                                        <th width="15%">Budget Bal (ksh)</th>
                                                        <th style="width:8%">Project(s)</th>
                                                        <th width="7%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($rows_count > 0) {
                                                        $sn = 0;
                                                        while ($row_rsProgram = $sql->fetch()) {
                                                            $progid = $row_rsProgram['progid'];
                                                            $project_department = $row_rsProgram['projsector'];
                                                            $project_section = $row_rsProgram['projdept'];
                                                            $project_directorate = $row_rsProgram['directorate'];
                                                            $created_by = $row_rsProgram['createdby'];
                                                            $progname =  $row_rsProgram['progname'];

                                                            //fetch budget
                                                            $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid=:progid AND strategic_plan_id=:strategic_plan_id");
                                                            $query_rsBudget->execute(array(":progid" => $progid, ":strategic_plan_id" => $stplan));
                                                            $row_rsBudget = $query_rsBudget->fetch();
                                                            $progbudget = !is_null($row_rsBudget['budget']) ? number_format($row_rsBudget['budget'], 2) : number_format(0, 2);

                                                            $query_rsStrategicPlanProgram =  $db->prepare("SELECT * FROM tbl_strategic_plan_programs WHERE progid =:progid AND strategic_plan_id=:strategic_plan_id");
                                                            $query_rsStrategicPlanProgram->execute(array(":progid" => $progid, ":strategic_plan_id" => $stplan));
                                                            $row_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->fetch();
                                                            $totalRows_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->rowCount();

                                                            $projectscount = '<a href="#"><span class="badge bg-purple">0</span></a>';
                                                            $progbudgetbal = number_format(0, 2);

                                                            if ($totalRows_rsStrategicPlanProgram > 0) {
                                                                $strategic_plan_program_id = $row_rsStrategicPlanProgram['id'];
                                                                //get total projects
                                                                $query_projsbudget = $db->prepare("SELECT COUNT(*) as projectscount, SUM(projcost) as budget FROM tbl_projects WHERE progid = :progid AND strategic_plan_program_id=:strategic_plan_program_id");
                                                                $query_projsbudget->execute(array(":progid" => $progid, ":strategic_plan_program_id" => $strategic_plan_program_id));
                                                                $row_projsbudget = $query_projsbudget->fetch();
                                                                $count_projsbudget = $query_projsbudget->rowCount();

                                                                $count_projects = !is_null($row_projsbudget['projectscount']) ? $row_projsbudget['projectscount'] : 0;
                                                                if ($count_projects > 0) {
                                                                    $projsbudget = !is_null($row_projsbudget['budget']) ? $row_projsbudget['budget'] : number_format(0, 2);
                                                                    $progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);
                                                                    $progid_hashed = base64_encode("progid54321{$strategic_plan_program_id}");
                                                                    $projectscount = '<a href="view-project.php?prg=' . $progid_hashed . '"><span class="badge bg-purple">' . $count_projects . '</span></a>';
                                                                }
                                                            }

                                                            $program_hashed = base64_encode("progid54321{$progid}");
                                                            $filter_department = view_record($project_department, $project_section, $project_directorate);

                                                            if ($filter_department) {
                                                                $sn++;
                                                    ?>
                                                                <tr>
                                                                    <td><?= $sn ?></td>
                                                                    <td><?= $progname ?></td>
                                                                    <td><?= $progbudget ?></td>
                                                                    <td><?= $progbudgetbal ?></td>
                                                                    <td><?= $projectscount  ?></td>
                                                                    <td>
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#moreInfoModal" id="moreInfoModalBtn" onclick="program_info(<?= $progid ?>, <?= $stplan ?>)">
                                                                                        <i class="glyphicon glyphicon-file"></i> More Info</a>
                                                                                </li>

                                                                                <?php
                                                                                if ($totalRows_rsStrategicPlanProgram > 0) {
                                                                                    $strategic_plan_program_id = $row_rsStrategicPlanProgram['id'];
                                                                                    $strategic_plan_program_hashed = base64_encode("progid54321{$strategic_plan_program_id}");
                                                                                    if ($count_projects == 0) {
                                                                                ?>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" id="editprogram" href="add-program-details.php?progid=<?= $program_hashed ?>&plan=<?= $stplane ?>">
                                                                                                <i class="glyphicon glyphicon-edit"></i> Edit Output Targets
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php
                                                                                    }

                                                                                    if (!is_null($row_rsBudget['budget'])) {
                                                                                    ?>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" id="editprogram" href="add-project.php?progid=<?= $strategic_plan_program_hashed ?>">
                                                                                                <i class="glyphicon glyphicon-edit"></i> Add Project
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php
                                                                                    }
                                                                                } else {
                                                                                    ?>
                                                                                    <li>
                                                                                        <a type="button" data-toggle="modal" id="editprogram" href="add-program-details.php?progid=<?= $program_hashed ?>&plan=<?= $stplane ?>">
                                                                                            <i class="glyphicon glyphicon-edit"></i>Add Output Targets
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a type="button" data-toggle="modal" id="editprogram" href="edit-program?progid=<?= $program_hashed ?>&plan=<?= $stplane ?>">
                                                                                            <i class="glyphicon glyphicon-edit"></i> Edit
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="destroy_program(<?= $progid ?>)"> <i class="glyphicon glyphicon-trash"></i> Delete
                                                                                        </a>
                                                                                    </li>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            } // /while
                                                        } // if num_rows
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
                </div>
            </div>
        </section>
        <!-- end body  -->
        <!-- Start Item more -->
        <div class="modal fade" tabindex="-1" role="dialog" id="moreInfoModal">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                    </div>
                    <div class="modal-body" id="progmoreinfo">
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- End Item more -->
<?php

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