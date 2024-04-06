<?php
    try {

require('includes/head.php');
if ($permission) {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $progid = $project = $sectorid = $project_sub_stage = $project_directorate =  $workflow_stage = "";
        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProjects['progid'];
            $project = $row_rsProjects['projname'];
            $sectorid = $row_rsProjects['projsector'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $workflow_stage = $row_rsProjects['projstage'];
            $project_directorate = $row_rsProjects['directorate'];
        }
        $approval_stage = ($project_sub_stage  >= 2) ? true : false;
        $approve_details = "{
            get_edit_details: 'details',
            projid:$projid,
            workflow_stage:$workflow_stage,
            project_directorate:$project_directorate,
            project_name:'$project',
            sub_stage:'$project_sub_stage',
        }";

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();

        function get_sites($output_id, $mapping_type)
        {
            global $db;
            $site_name = [];
            if ($mapping_type == 1) {
                $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                $query_Output->execute(array(":output_id" => $output_id));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $site_name[] = $row_rsOutput['site'];
                    }
                }
            } else {
                $query_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                $query_Output->execute(array(":output_id" => $output_id));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $site_name[] = $row_rsOutput['state'];
                    }
                }
            }
            return implode(",", $site_name);
        }

        function validate_specifications()
        {
            global $db, $projid;
            $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ");
            $query_Output->execute(array(":projid" => $projid));
            $total_Output = $query_Output->rowCount();
            $results = [];
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY msid ASC");
                    $query_rsMilestone->execute(array(":output_id" => $output_id));
                    $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                    if ($totalRows_rsMilestone > 0) {
                        while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                            $milestone_id = $row_rsMilestone['msid'];
                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
                            $query_rsTasks->execute(array(":milestone" => $milestone_id));
                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                            if ($totalRows_rsTasks > 0) {
                                $results[] = true;
                            } else {
                                $results[] = false;
                            }
                        }
                    } else {
                        $results[] = false;
                    }
                }
            }
            return !in_array(false, $results) ? true : false;
        }
    
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon  . " " . $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right" style="margin-right:10px;">
                                Go Back
                            </a>
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body">
                        <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr style="background-color:#0b548f; color:#FFF">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:45%">Output</th>
                                                <th style="width:40%"><?= $level2label ?>/Site/s</th>
                                                <th style="width:10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($total_Output > 0) {
                                                $counter = 0;
                                                while ($row_rsOutput = $query_Output->fetch()) {
                                                    $counter++;
                                                    $output_id = $row_rsOutput['id'];
                                                    $indicator_id = $row_rsOutput['indicator'];

                                                    // //get indicator
                                                    $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indicator_id");
                                                    $query_rsIndicator->execute(array(":indicator_id" => $indicator_id));
                                                    $row_rsIndicator = $query_rsIndicator->fetch();
                                                    $totalRows_rsIndicator = $query_rsIndicator->rowCount();
                                                    $indicator_name = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_name'] : "";
                                                    $mapping_type = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_mapping_type'] : "";
                                                    $output_hashed = base64_encode("projid54321{$output_id}");

                                            ?>
                                                    <tr>
                                                        <td> <?= $counter ?></td>
                                                        <td><?= $indicator_name  ?></td>
                                                        <td><?= get_sites($output_id, $mapping_type) ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a href="add-activities.php?output_id=<?= $output_hashed ?>">
                                                                            <i class="fa fa-pencil-square"></i> Activities
                                                                        </a>
                                                                    </li>
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
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <?php
                                $cost_val = validate_specifications();
                                if ($cost_val) {
                                    $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                    $stage = $workflow_stage;
                                    $approve_details = "{
                                                get_edit_details: 'details',
                                                projid:$projid,
                                                workflow_stage:$stage,
                                                project_directorate:$project_directorate,
                                                project_name:'$project',
                                                sub_stage:'$project_sub_stage',
                                            }";
                                    if ($assigned_responsible) {
                                        if ($approval_stage) {
                                ?>
                                            <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                        <?php
                                        } else {
                                            $data_entry_details = "{
                                                        get_edit_details: 'details',
                                                        projid:$projid,
                                                        workflow_stage:$workflow_stage,
                                                        project_directorate:$project_directorate,
                                                        project_name:'$project',
                                                        sub_stage:'$project_sub_stage',
                                                    }";
                                        ?>
                                            <button type="button" onclick="save_data_entry_project(<?= $data_entry_details ?>)" class="btn btn-success">Proceed</button>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php
} else {
    $results =  restriction();
    echo $results;
}
} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>
<script>
  const redirect_url = "add-project-activities.php";
</script>
<script src="assets/js/master/index.js"></script>