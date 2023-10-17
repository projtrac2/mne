<?php
require('includes/head.php');
if ($permission) {
    try {
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
            checklist:'1',
        }";

        function get_sites($output_id, $mapping_type)
        {
            global $db;
            $site_name = [];
            if ($mapping_type == 1 || $mapping_type == 3) {
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

        $query_rsOutputs = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.projid=:projid ORDER BY d.id");
        $query_rsOutputs->execute(array(":projid" => $projid));
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();

        function validate_specifications()
        {
            global $db, $projid;
            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.projid = :projid ");
            $query_Output->execute(array(":projid" => $projid));
            $total_Output = $query_Output->rowCount();
            $val = [];
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $mapping_type = $row_rsOutput['indicator_mapping_type'];
                    $output_id = $row_rsOutput['id'];
                    if($mapping_type == 2){
                        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid ASC");
                        $query_rsTasks->execute(array(":output_id" => $output_id));
                        $totalRows_rsTasks = $query_rsTasks->rowCount();
                        if ($totalRows_rsTasks > 0) {
                            while ($row_rsTasks = $query_rsTasks->fetch()) {
                                $task_id = $row_rsTasks['tkid'];
                                $query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE task_id=:task_id");
                                $query_rsSpecifions->execute(array(":task_id" => $task_id));
                                $totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
                                $val[] = $totalRows_rsSpecifions > 0 ? true : false;
                            }
                        }else{
                            $val[] = false;
                        }
                    }else{
                        $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                        $query_Output->execute(array(":output_id" => $output_id));
                        $total_Output = $query_Output->rowCount();
                        if ($total_Output > 0) {
                            while ($row_rsOutput = $query_Output->fetch()) {
                                $site_id = $row_rsOutput['site_id'];
                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid ASC");
                                $query_rsTasks->execute(array(":output_id" => $output_id));
                                $totalRows_rsTasks = $query_rsTasks->rowCount();
                                if ($totalRows_rsTasks > 0) {
                                    while ($row_rsTasks = $query_rsTasks->fetch()) {
                                        $task_id = $row_rsTasks['tkid'];
                                        $query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE task_id=:task_id AND site_id=:site_id");
                                        $query_rsSpecifions->execute(array(":task_id" => $task_id, ':site_id'=>$site_id));
                                        $totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
                                        $val[] =  $totalRows_rsSpecifions > 0 ? true : false;
                                    }
                                }else{
                                    $val[] = false;
                                }
                            }
                        }else{
                            $val[] = false;
                        }
                    }
                }
            }else{
                $val[] = false;
            }
            return !in_array(false, $val) ? true : false;
        }
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
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
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
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix " id="">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example " id="direct_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%"># </th>
                                                    <th style="width:55%">Output</th>
                                                    <th style="width:35%">Location(s)/Site(s)</th>
                                                    <th style="width:5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($totalRows_rsOutputs > 0) {
                                                    $counter = 0;
                                                    while ($row_rsOutput = $query_rsOutputs->fetch()) {
                                                        $counter++;
                                                        $design_id = $row_rsOutput['id'];
                                                        $output_id = $row_rsOutput['id'];
                                                        $mapping_type = $row_rsOutput['indicator_mapping_type'];
                                                        $indicator_name = $row_rsOutput['indicator_name'];
                                                        $site_name = get_sites($output_id, $mapping_type);

                                                        $query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE output_id=:output_id");
                                                        $query_rsMonitoring->execute(array(":output_id" => $output_id));
                                                        $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
                                                        $edit = $totalRows_rsMonitoring > 0 ? 1 : 0;
                                                        $output_hashed = base64_encode("projid54321{$output_id}");
                                                ?>
                                                        <tr>
                                                            <td style="width:5%"><?= $counter ?></td>
                                                            <td style="width:40%"><?= $indicator_name ?></td>
                                                            <td style="width:40%"><?= $site_name ?></td>
                                                            <td style="width:5%">
                                                                <a href="add-specifications.php?output_id=<?= $output_hashed ?>" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-<?= $edit ? 'pencil' : 'plus' ?>"></span>
                                                                </a>
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
            </div>
    </section>
    <!-- end body  -->
<?php
} else {;
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/master/index.js"></script>