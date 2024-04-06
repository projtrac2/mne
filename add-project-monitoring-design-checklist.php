<?php
    try {

require('includes/head.php');
if ($permission) {
        $decode_output_id = (isset($_GET['output_id']) && !empty($_GET["output_id"])) ? base64_decode($_GET['output_id']) : "";
        $output_id_array = explode("projid54321", $decode_output_id);
        $output_id = $output_id_array[1];

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :output_id ");
        $query_Output->execute(array(":output_id" => $output_id));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        $indicator_id = $total_Output > 0 ? $row_rsOutput['indicator'] : "";
        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indicator_id");
        $query_rsIndicator->execute(array(":indicator_id" => $indicator_id));
        $row_rsIndicator = $query_rsIndicator->fetch();
        $totalRows_rsIndicator = $query_rsIndicator->rowCount();
        $output_name = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_name'] : "";
        $mapping_type = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_mapping_type'] : "";
        $projid = $total_Output > 0 ?  $row_rsOutput['projid'] : "";


        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $progid = $project = $sectorid = "";
        $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
    
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                            Go Back
                        </a>
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
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project_name ?> </li>
                                        <li class="list-group-item"><strong>Output: </strong> <?= $output_name ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    if ($mapping_type == 2) {
                                    ?>
                                        <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Monitoring Checklist</legend>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example">
                                                        <thead>
                                                            <tr style="background-color:#0b548f; color:#FFF">
                                                                <th style="width:5%" align="center">#</th>
                                                                <th style="width:85%">Task</th>
                                                                <th style="width:10%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid ASC");
                                                            $query_rsTasks->execute(array(":output_id" => $output_id));
                                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                            if ($totalRows_rsTasks > 0) {
                                                                $mcounter = 0;
                                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                    $mcounter++;
                                                                    $task_name = $row_rsTasks['task'];
                                                                    $task_id = $row_rsTasks['tkid'];
                                                                    $query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE task_id=:task_id");
                                                                    $query_rsMonitoring->execute(array(":task_id" => $task_id));
                                                                    $row_rsMonitoring = $query_rsMonitoring->fetch();
                                                                    $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
                                                                    $edit = $totalRows_rsMonitoring > 0 ? 1 : 0;
                                                                    $details = "{
                                                                        site_id:'0',
                                                                        task_id:$task_id,
                                                                        task_name: '$task_name',
                                                                        edit_id:'$edit',
                                                                    }";
                                                            ?>
                                                                    <tr class="bg-grey">
                                                                        <td align="center"><?php echo  $mcounter ?></td>
                                                                        <td><?= $task_name ?></td>
                                                                        <td>
                                                                            <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" name="addplus" title="<?= $edit == 1 ? "Edit" : "Add" ?> Checklist" class="btn btn-success btn-sm" onclick="add_checklist_details(<?= $details ?>)">
                                                                                <span class="glyphicon glyphicon-<?= $edit == 1 ? "pencil" : "plus" ?>"></span>
                                                                            </button>
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
                                        </fieldset>
                                        <?php
                                    } else {
                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                                        $query_Output->execute(array(":output_id" => $output_id));
                                        $total_Output = $query_Output->rowCount();
                                        if ($total_Output > 0) {
                                            while ($row_rsOutput = $query_Output->fetch()) {
                                                $site_name = $row_rsOutput['site'];
                                                $site_id = $row_rsOutput['site_id'];
                                        ?>
                                                <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><?= $site_name ?></legend>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example">
                                                                <thead>
                                                                    <tr style="background-color:#0b548f; color:#FFF">
                                                                        <th style="width:5%" align="center">#</th>
                                                                        <th style="width:85%">Task</th>
                                                                        <th style="width:10%">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid ASC");
                                                                    $query_rsTasks->execute(array(":output_id" => $output_id));
                                                                    $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                    if ($totalRows_rsTasks > 0) {
                                                                        $mcounter = 0;
                                                                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                            $mcounter++;
                                                                            $task_name = $row_rsTasks['task'];
                                                                            $task_id = $row_rsTasks['tkid'];
                                                                            $query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE task_id=:task_id AND site_id=:site_id");
                                                                            $query_rsMonitoring->execute(array(":task_id" => $task_id, ":site_id"=>$site_id));
                                                                            $row_rsMonitoring = $query_rsMonitoring->fetch();
                                                                            $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
                                                                            $edit = $totalRows_rsMonitoring > 0 ? 1 : 0;
                                                                            $details = "{
                                                                                site_id:$site_id,
                                                                                task_id:$task_id,
                                                                                task_name: '$task_name',
                                                                                edit_id:'$edit',
                                                                            }";
                                                                    ?>
                                                                            <tr class="bg-grey">
                                                                                <td align="center"><?php echo  $mcounter ?></td>
                                                                                <td><?= $task_name ?></td>
                                                                                <td>
                                                                                    <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" name="addplus" title="<?= $edit == 1 ? "Edit" : "Add" ?> Checklist" class="btn btn-success btn-sm" onclick="add_checklist_details(<?= $details ?>)">
                                                                                        <span class="glyphicon glyphicon-<?= $edit == 1 ? "pencil" : "plus" ?>"></span>
                                                                                    </button>
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
                                                </fieldset>
                                        <?php
                                            }
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="outputItemModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Project Output Design Checklist</h4>
                </div>
                <form class="form-horizontal" id="add_items" action="" method="POST">
                    <div class="modal-body">
                        <fieldset class="scheduler-border" id="tasks_parameters_div">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Checklist </legend>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active"> Task Name: <span id="task_name"></span> </li>
                                </ul>
                            </div>
                            <div class="col-md-12" id="projoutputTable">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tasks_parameter_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="40%">Parameter</th>
                                                <th width="40%">Unit of Measure</th>
                                                <th width="10%">Target</th>
                                                <th width="5%">
                                                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_checklist();" class="btn btn-success btn-sm addplus_output">
                                                        <span class="glyphicon glyphicon-plus">
                                                        </span>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tasks_checklist_table_body">
                                            <tr></tr>
                                            <tr id="hideinfo3" align="center">
                                                <td colspan="3">Add Checklist!!</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <input type="hidden" name="store_checklists" id="store_checklists" value="store_checklists">
                            <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                            <input type="hidden" name="site_id" id="site_id" value="">
                            <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                            <input type="hidden" name="task_id" id="task_id" value="">
                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                            <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Item more -->
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

<script src="assets/js/mneplan/checklist.js"></script>