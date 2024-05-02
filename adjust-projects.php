<?php
try {
    require('includes/head.php');
    if ($permission) {


        function daily_team($projid, $workflow_stage, $role)
        {
            global $db,  $user_name, $workflow_stage, $user_designation;
            $output_responsible = $standin_responsible = false;
            if ($user_designation == 1) {
                $output_responsible = true;
            } else {
                $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
                $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 4, ":responsible" => $user_name, ":role" => $role));
                $total_rsOutput = $query_rsOutput->rowCount();
                $output_responsible = $total_rsOutput > 0 ? true : false;

                $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND team_type =:team_type");
                $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":team_type" => 4));
                $row_rsOutput_standin = $query_rsOutput_standin->fetch();
                $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

                if ($total_rsOutput_standin > 0) {
                    $owner_id = $row_rsOutput_standin['owner'];
                    $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
                    $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 4, ":responsible" => $owner_id, ":role" => $role));
                    $total_rsOutput = $query_rsOutput->rowCount();
                    $standin_responsible = $total_rsOutput > 0 ? true : false;
                }
            }
            return $output_responsible || $standin_responsible ? true : false;
        }



        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();

?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . ' ' . $pageTitle ?>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                        <thead>
                                            <tr id="colrow">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:10%">Project Code</th>
                                                <th style="width:65%">Project Name </th>
                                                <th style="width:15">Status</th>
                                                <th style="width:5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($totalRows_rsProjects > 0) {
                                                $counter = 0;
                                                while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                    $projid = $row_rsProjects['projid'];
                                                    $projid_hashed = base64_encode("projrisk047{$projid}");
                                                    $implementation = $row_rsProjects['projcategory'];
                                                    $sub_stage = $row_rsProjects['proj_substage'];
                                                    $project_department = $row_rsProjects['projsector'];
                                                    $project_section = $row_rsProjects['projdept'];
                                                    $project_directorate = $row_rsProjects['directorate'];
                                                    $projname = $row_rsProjects['projname'];
                                                    $projcode = $row_rsProjects['projcode'];
                                                    $projstatusid = $row_rsProjects['projstatus'];
                                                    $start_date = date('Y-m-d');
                                                    $responsible = daily_team($projid, $workflow_stage, 2);

                                                    $query_rsAdjustments = $db->prepare("SELECT * FROM tbl_project_adjustments WHERE projid = :projid AND timeline_status=0");
                                                    $query_rsAdjustments->execute(array(":projid" => $projid));
                                                    $totalRows_Adjustments = $query_rsAdjustments->rowCount();


                                                    if ($responsible && $totalRows_Adjustments) {
                                                        $counter++;
                                            ?>
                                                        <tr>
                                                            <td align="center"><?= $counter ?></td>
                                                            <td><?= $projcode ?></td>
                                                            <td><?= $projname ?></td>
                                                            <td><label class='label label-primary'><?= "Pending"; ?></label></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                                <i class="fa fa-file-text"></i> View More
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" href="adjust-work-program.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
                                                                                <i class="fa fa-plus-square-o"></i> Adjust Program of Works
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" href="adjust-costs.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
                                                                                <i class="fa fa-plus-square-o"></i> Adjust Costs
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" href="adjust-units.php?projid=<?= $projid_hashed ?>" id="addFormModalBtn">
                                                                                <i class="fa fa-plus-square-o"></i> Adjust Units
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

        <!-- Start Item more -->
        <div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
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
        </div>
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
<script src="assets/js/projects/view-project.js"></script>