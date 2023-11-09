<?php
require('includes/head.php');
if ($permission) {
    try {
        if (isset($_GET['projid'])) {
            $encoded_projid = $_GET['projid'];
            $decode_projid = base64_decode($encoded_projid);
            $projid_array = explode("projid54321", $decode_projid);
            $projid = $projid_array[1];
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            $approve_details = "";
            // if ($totalRows_rsProjects > 0) {
            $implimentation_type = $row_rsProjects['projcategory'];
            $projname = $row_rsProjects['projname'];
            $projcode = $row_rsProjects['projcode'];
            $projcost = $row_rsProjects['projcost'];
            $projfscyear = $row_rsProjects['projfscyear'];
            $projduration = $row_rsProjects['projduration'];
            $mne_cost = $row_rsProjects['mne_budget'];
            $direct_cost = $row_rsProjects['direct_cost'];
            $administrative_cost = $row_rsProjects['administrative_cost'];
            $implementation_cost = $projcost - $mne_cost;
            $progid = $row_rsProjects['progid'];
            $projstartdate = $row_rsProjects['projstartdate'];
            $projenddate = $row_rsProjects['projenddate'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $workflow_stage = $row_rsProjects['projstage'];
            $project_directorate = $row_rsProjects['directorate'];

            $projstartyear = date('Y', strtotime($projstartdate));
            $end_year = date('Y', strtotime($projenddate));
            $years = ($end_year - $projstartyear) + 1;

            $query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where id ='$projfscyear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();

            $starting_year = $row_rsYear ? $row_rsYear['yr'] : false;
            $start_date = $starting_year . "-07-01";
            $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $projduration . ' days'));
            if ($implimentation_type == 2) {
                $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
                $query_rsTender->execute(array(":projid" => $projid));
                $row_rsTender = $query_rsTender->fetch();
                $totalRows_rsTender = $query_rsTender->rowCount();
                if ($totalRows_rsTender > 0) {
                    $start_date = $row_rsTender['startdate'];
                    $end_date = $row_rsTender['enddate'];
                }
            }

            function validate_program_of_works()
            {
                global $db, $projid;

                $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                $query_Sites->execute(array(":projid" => $projid));
                $rows_sites = $query_Sites->rowCount();
                $outputs = array();
                if ($rows_sites > 0) {
                    while ($row_Sites = $query_Sites->fetch()) {
                        $site_id = $row_Sites['site_id'];
                        $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                        $query_Site_Output->execute(array(":site_id" => $site_id));
                        $rows_Site_Output = $query_Site_Output->rowCount();
                        if ($rows_Site_Output > 0) {
                            while ($row_Site_Output = $query_Site_Output->fetch()) {
                                $output_id = $row_Site_Output['outputid'];
                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                $query_Output->execute(array(":outputid" => $output_id));
                                $row_Output = $query_Output->fetch();
                                $total_Output = $query_Output->rowCount();
                                if ($total_Output) {
                                    $output_id = $row_Output['id'];
                                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                                    $query_rsTasks->execute(array(":output_id" => $output_id));
                                    $totalRows_rsTasks = $query_rsTasks->rowCount();
                                    if ($totalRows_rsTasks > 0) {
                                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                                            $subtask_id = $row_rsTasks['tkid'];
                                            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id AND site_id=:site_id ");
                                            $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
                                            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                            $outputs[] = $totalRows_rsTask_Start_Dates > 0 ? true : false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type=2 AND projid = :projid");
                $query_Output->execute(array(":projid" => $projid));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    if ($total_Output > 0) {
                        while ($row_rsOutput = $query_Output->fetch()) {
                            $output_id = $row_rsOutput['id'];
                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                            $query_rsTasks->execute(array(":output_id" => $output_id));
                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                            if ($totalRows_rsTasks > 0) {
                                $tcounter = 0;
                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                    $subtask_id = $row_rsTasks['tkid'];
                                    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id AND site_id=:site_id");
                                    $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => 0));
                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                    $outputs[] = $totalRows_rsTask_Start_Dates > 0 ? true : false;
                                }
                            }
                        }
                    }
                }
                return !in_array(false, $outputs) ? true : false;
            }
            $approval_stage = ($project_sub_stage  >= 2) ? true : false;

            $proceed = validate_program_of_works();

            if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "approve")) {
                $sub_stage = 1;
				$current_date = date("Y-m-d");
                $projid = $_POST['projid'];
                $comments = $_POST['comments'];

                if ($_POST['submit'] == "Approve") {
                    $sub_stage = 3;
                } else if ($_POST['submit'] == "Amend") {
                    $sub_stage = 2;
                }

                $sql = $db->prepare("UPDATE tbl_projects SET proj_substage=:proj_substage WHERE  projid=:projid");
                $result  = $sql->execute(array(":proj_substage" => $sub_stage, ":projid" => $projid));


                $insertSQL = $db->prepare("INSERT INTO tbl_program_of_work_comments (projid, comments, created_by, created_at) VALUES (:projid, :comments, :createdby, :datecreated)");
                $insertSQL->execute(array(':projid' => $projid, ':comments' => $comments, ':createdby' => $user_name, ':datecreated' => $current_date));

                $msg = 'Record Successfully Added';
                $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000,
                        'icon':'success',
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = 'add-program-of-works.php';
                    }, 2000);
                </script>";
            }
?>

            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon ?>
                            <?php echo $pageTitle   ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                                        Go Back
                                    </a>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card">
                        <div class="row clearfix">
                            <div class="block-header">
                                <?= $results; ?>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header" style="padding-left:20px;padding-right:20px">
                                    <div class="row clearfix" style="border:1px solid #f0f0f0; border-radius:3px">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:15px; margin-bottom:15px">
                                            <strong>Project Name:</strong> <?= $projname ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:15px">
                                            <strong>Code: </strong> <?= $projcode ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:15px">
                                            <strong>Start Date: </strong> <?= date('d M Y', strtotime($start_date)); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:15px">
                                            <strong>End Date: </strong> <?= date('d M Y', strtotime($end_date)); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <?php
                                    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                    $query_Sites->execute(array(":projid" => $projid));
                                    $rows_sites = $query_Sites->rowCount();
                                    if ($rows_sites > 0) {
                                        $counter = 0;
                                        while ($row_Sites = $query_Sites->fetch()) {
                                            $site_id = $row_Sites['site_id'];
                                            $site = $row_Sites['site'];
                                            $counter++;
                                    ?>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    SITE <?= $counter ?> : <?= $site ?>
                                                </legend>
                                                <?php
                                                $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                                                $query_Site_Output->execute(array(":site_id" => $site_id));
                                                $rows_Site_Output = $query_Site_Output->rowCount();
                                                if ($rows_Site_Output > 0) {
                                                    $output_counter = 0;
                                                    while ($row_Site_Output = $query_Site_Output->fetch()) {
                                                        $output_counter++;
                                                        $output_id = $row_Site_Output['outputid'];
                                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                                        $query_Output->execute(array(":outputid" => $output_id));
                                                        $row_Output = $query_Output->fetch();
                                                        $total_Output = $query_Output->rowCount();
                                                        if ($total_Output) {
                                                            $output_id = $row_Output['id'];
                                                            $output = $row_Output['indicator_name'];
                                                ?>
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#f0f0f0; border-radius:3px">
                                                                    OUTPUT <?= $output_counter ?> : <?= $output ?>
                                                                </legend>
                                                                <?php
                                                                $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
                                                                $query_rsMilestone->execute(array(":output_id" => $output_id));
                                                                $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                                                if ($totalRows_rsMilestone > 0) {
                                                                    $task_counter = 0;
                                                                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                                        $milestone = $row_rsMilestone['milestone'];
                                                                        $msid = $row_rsMilestone['msid'];
                                                                        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id ");
                                                                        $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id));
                                                                        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                        $edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
                                                                        $details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
                                                                        $task_counter++;
                                                                ?>
                                                                        <div class="row clearfix">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="card-header">
                                                                                    <div class="row clearfix">
                                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                            <h5><u>
                                                                                                    TASK <?= $task_counter ?>: <?= $milestone ?>
                                                                                                    <?php
                                                                                                    if (!$approval_stage && $implimentation_type == 1 ) {
                                                                                                    ?>
                                                                                                        <div class="btn-group" style="float:right">
                                                                                                            <div class="btn-group" style="float:right">
                                                                                                                <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="get_tasks(<?= htmlspecialchars(json_encode($details)) ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px"> 
                                                                                                                    <?php echo $totalRows_rsTask_Start_Dates > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </u>
                                                                                            </h5>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style="width:5%">#</th>
                                                                                                <th style="width:40%">Subtask</th>
                                                                                                <th style="width:15%">Unit of Measure</th>
                                                                                                <th style="width:10%">Duration</th>
                                                                                                <th style="width:15%">Start Date</th>
                                                                                                <th style="width:15%">End Date</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                                                                            $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                                                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                                            if ($totalRows_rsTasks > 0) {
                                                                                                $tcounter = 0;
                                                                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                                    $tcounter++;
                                                                                                    $task_name = $row_rsTasks['task'];
                                                                                                    $task_id = $row_rsTasks['tkid'];
                                                                                                    $unit =  $row_rsTasks['unit_of_measure'];
                                                                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
                                                                                                    $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id, ":subtask_id" => $task_id));
                                                                                                    $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                                                    $start_date = $end_date = $duration =  "";
                                                                                                    if ($totalRows_rsTask_Start_Dates > 0) {
                                                                                                        $start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
                                                                                                        $end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
                                                                                                        $duration = number_format($row_rsTask_Start_Dates['duration']);
                                                                                                    }
                                                                                            ?>
                                                                                                    <tr id="row">
                                                                                                        <td style="width:5%"><?= $tcounter ?></td>
                                                                                                        <td style="width:40%"><?= $task_name ?></td>
                                                                                                        <td style="width:15%"><?= $unit_of_measure ?></td>
                                                                                                        <td style="width:10%"><?= $duration ?> Days</td>
                                                                                                        <td style="width:15%"><?= $start_date ?></td>
                                                                                                        <td style="width:15%"><?= $end_date ?></td>
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
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </fieldset>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </fieldset>
                                            <?php
                                        }
                                    }

                                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type=2 AND projid = :projid");
                                    $query_Output->execute(array(":projid" => $projid));
                                    $total_Output = $query_Output->rowCount();
                                    $outputs = '';
                                    if ($total_Output > 0) {
                                        $outputs = '';
                                        if ($total_Output > 0) {
                                            $counter = 0;

                                            while ($row_rsOutput = $query_Output->fetch()) {
                                                $output_id = $row_rsOutput['id'];
                                                $output = $row_rsOutput['indicator_name'];
                                                $counter++;
                                                $site_id = 0;
                                            ?>
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                        AWAY POINT OUTPUT <?= $counter ?>: <?= $output ?>
                                                    </legend>
                                                    <?php
                                                    $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
                                                    $query_rsMilestone->execute(array(":output_id" => $output_id));
                                                    $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                                    if ($totalRows_rsMilestone > 0) {
                                                        $task_counter = 0;
                                                        while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                            $milestone = $row_rsMilestone['milestone'];
                                                            $msid = $row_rsMilestone['msid'];
                                                            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id ");
                                                            $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0));
                                                            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                            $edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
                                                            $details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
                                                            $task_counter++;
                                                    ?>
                                                            <div class="row clearfix">
                                                                <input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="card-header">
                                                                        <div class="row clearfix">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <h5>
                                                                                    <u>
                                                                                        TASK <?= $task_counter ?>: <?= $milestone ?>
                                                                                        <?php
                                                                                        if (!$approval_stage) {
                                                                                        ?>
                                                                                            <div class="btn-group" style="float:right">
                                                                                                <div class="btn-group" style="float:right">
                                                                                                    <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="get_tasks(<?= htmlspecialchars(json_encode($details)) ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                                        <?php echo $totalRows_rsTask_Start_Dates > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </u>
                                                                                </h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width:5%">#</th>
                                                                                    <th style="width:40%">Item</th>
                                                                                    <th style="width:15%">Unit of Measure</th>
                                                                                    <th style="width:10%">Duration</th>
                                                                                    <th style="width:15%">Start Date</th>
                                                                                    <th style="width:15%">End Date</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                                                                $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                                                                $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                                if ($totalRows_rsTasks > 0) {
                                                                                    $tcounter = 0;
                                                                                    while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                        $tcounter++;
                                                                                        $task_name = $row_rsTasks['task'];
                                                                                        $task_id = $row_rsTasks['tkid'];
                                                                                        $unit =  $row_rsTasks['unit_of_measure'];
                                                                                        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                        $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                        $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                        $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                                        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
                                                                                        $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0, ":subtask_id" => $task_id));
                                                                                        $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                                        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                                        $start_date = $end_date = $duration =  "";
                                                                                        if ($totalRows_rsTask_Start_Dates > 0) {
                                                                                            $start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
                                                                                            $end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
                                                                                            $duration = number_format($row_rsTask_Start_Dates['duration']);
                                                                                        }
                                                                                ?>
                                                                                        <tr id="row<?= $tcounter ?>">
                                                                                            <td style="width:5%"><?= $tcounter ?></td>
                                                                                            <td style="width:40%"><?= $task_name ?></td>
                                                                                            <td style="width:15%"><?= $unit_of_measure ?></td>
                                                                                            <td style="width:10%"><?= $duration ?> Days</td>
                                                                                            <td style="width:15%"><?= $start_date ?> </td>
                                                                                            <td style="width:15%"><?= $end_date ?></td>
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
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </fieldset>
                                        <?php
                                            }
                                        }
                                    }
                                    if ($implimentation_type == 1) {

                                        ?>
                                        <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-md-12 text-center">
                                                    <?php
                                                    if ($proceed) {
                                                        $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                                        $stage =  $implimentation_type == 1 ? $workflow_stage + 1 : $workflow_stage;
                                                        $approve_details =
                                                            "{
                                                            get_edit_details: 'details',
                                                            projid:$projid,
                                                            workflow_stage:$stage,
                                                            project_directorate:$project_directorate,
                                                            project_name:'$projname',
                                                            sub_stage:'$project_sub_stage',
                                                        }";
                                                        if ($assigned_responsible) {
                                                            if ($approval_stage) {
                                                    ?>
                                                                <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                                            <?php
                                                            } else {
                                                                $data_entry_details =
                                                                    "{
                                                                    get_edit_details: 'details',
                                                                    projid:$projid,
                                                                    workflow_stage:$workflow_stage,
                                                                    project_directorate:$project_directorate,
                                                                    project_name:'$projname',
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
                                        </form>
                                    <?php
                                    } else {
                                    ?>
                                        <form role="form" id="form_contractor" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <fieldset class="scheduler-border" id="project_approve_div">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                            <i class="fa fa-comment" aria-hidden="true"></i> Remarks
                                                        </legend>
                                                        <div id="comment_section">
                                                            <div class="col-md-12">
                                                                <label class="control-label">Remarks *:</label>
                                                                <br />
                                                                <div class="form-line">
                                                                    <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="MM_insert" value="approve">
                                                    <input type="hidden" name="projid" value="<?=$projid?>">
                                                    <input name="submit" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit1" value="Approve" />
                                                    <input name="submit" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit2" value="Amend" />
                                                </div>
                                            </div>
                                        </form>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Start Modal Item Edit -->
            <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="modal-title">Add Program of Works</h4>
                        </div>
                        <div class="modal-body" style="max-height:450px; overflow:auto;">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <form class="form-horizontal" id="add_output" action="" method="POST">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="files_table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th style="width:55%">Subtask *</th>
                                                                    <th style="width:10%">Start Date *</th>
                                                                    <th style="width:20%">Duration (Days) *</th>
                                                                    <th style="width:10%">End Date *</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tasks_table_body">
                                                                <tr></tr>
                                                                <tr id="removeTr" align="center">
                                                                    <td colspan="4">Add Program of Works</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                        <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                        <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                        <input type="hidden" name="store_tasks" id="store_tasks" value="">
                                                        <input type="hidden" name="output_id" id="output_id" value="">
                                                        <input type="hidden" name="task_id" id="task_id" value="">
                                                        <input type="hidden" name="site_id" id="site_id" value="">
                                                        <input type="hidden" name="today" id="today" value="<?= date('Y-m-d') ?>">
                                                        <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
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


<?php
            // } else {
            //     $results =  restriction();
            //     echo $results;
            // }
        } else {
            $results =  restriction();
            echo $results;
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script>
    const redirect_url = "add-program-of-works.php";
</script>
<script src="assets/js/programofWorks/index.js"></script>
<script src="assets/js/master/index.js"></script>