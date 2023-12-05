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
        $progid = $project = $sectorid = "";
        $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
        $projcode = ($totalRows_rsProjects > 0) ? $row_rsProjects['projcode'] : "";
        $implimentation_type = $row_rsProjects["projcategory"];



        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
            $projid = $_POST['projid'];
            $datecreated = date('Y-m-d');
            if (isset($_POST['comments'])) {
                $observ = $_POST['comments'];
                $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid,output_id,milestone_id,site_id,task_id,subtask_id,formid,observation,observation_type,created_at,created_by) VALUES (:projid,:output_id,:milestone_id,:site_id,:task_id,:subtask_id,:formid,:observation,:observation_type,:created_at,:created_by)");
                $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => 0, ":milestone_id" => 0, ":site_id" => 0, ":task_id" => 0, ":subtask_id" => 0, ':formid' => $datecreated, ':observation' => $observ, ":observation_type" => 5, ':created_at' => $datecreated, ':created_by' => $user_name));
            }

            if (isset($_POST['attachmentpurpose'])) {
                $countP = count($_POST["attachmentpurpose"]);
                $stage = 1;
                for ($cnt = 0; $cnt < $countP; $cnt++) {
                    if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = time() . '_' . $projid . "_" . $stage . "_" . $filename;
                            $filepath = "uploads/payments/" . $newname;
                            if (!file_exists($filepath)) {

                                if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $filecategory = "Project Observations";
                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :stage, :filename, :ftype, :floc,:fcategory,:reason,:uploaded_by, :date_uploaded)");
                                    $results =  $qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $user_name, ":date_uploaded" => $datecreated));
                                    if ($results) {
                                        $type = true;
                                        $msg =  "Successfully uploaded files";
                                    } else {
                                        $msg =  "Error uploading files";
                                    }
                                } else {
                                    $msg =  "file culd not be  allowed";
                                }
                            } else {
                                $msg = 'File you are uploading already exists, try another file!!';
                            }
                        } else {
                            $msg = 'This file type is not allowed, try another file!!';
                        }
                    }
                }
            }

            $results = "<script type=\"text/javascript\">
                swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    'icon':'success',
                showConfirmButton: false });
                setTimeout(function(){
                    window.location.href = 'project-output-monitoring-checklist';
                }, 2000);
            </script>";
        }

        $month = date('m');
        $year = date('Y');
        $start_date = 01 . ' ' . date('M') . ' ' . date('Y');
        $end_date = date('t') . ' ' . date('M') . ' ' . date('Y');
        $month_date = '(' . $start_date . ' - ' . $end_date  . ')';
        $month = 9;
        $year = 2023;
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <?= $results; ?>
                    <div class="btn-group" style="float:right">
                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                            Go Back
                        </a>
                    </div>
                </h4>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row clearfix">
                    <div class="card">
                        <div class="card-header">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project_name ?> </li>
                                        <li class="list-group-item"><strong>Code: </strong> <?= $projcode ?> </li>
                                    </ul>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="nav nav-tabs" style="font-size:14px">
                                        <li class="active">
                                            <a data-toggle="tab" href="#current">
                                                <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                                Current &nbsp;&nbsp;<span class="badge bg-orange" id="total-programs">|</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#previous">
                                                <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                                Previous &nbsp;<span class="badge bg-indigo">|</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="current" class="tab-pane fade in active">
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php
                                            $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                            $query_Sites->execute(array(":projid" => $projid));
                                            $rows_sites = $query_Sites->rowCount();
                                            $created_at = date('Y-m-d');
                                            if ($rows_sites > 0) {
                                                $counter = 0;
                                                while ($row_Sites = $query_Sites->fetch()) {
                                                    $site_id = $row_Sites['site_id'];
                                                    $site = $row_Sites['site'];
                                                    $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at=:created_at AND site_id=:site_id");
                                                    $query_Site_score->execute(array(":created_at" => $created_at, ":site_id" => $site_id));
                                                    $rows_site_score = $query_Site_score->rowCount();
                                                    if ($rows_site_score > 0) {
                                                        $counter++;
                                            ?>
                                                        <fieldset class="scheduler-border">
                                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> : <?= $site ?>
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
                                                                    $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at=:created_at AND site_id=:site_id AND output_id=:output_id");
                                                                    $query_Site_score->execute(array(":created_at" => $created_at, ":site_id" => $site_id, ":output_id" => $output_id));
                                                                    $rows_site_score = $query_Site_score->rowCount();
                                                                    if ($rows_site_score > 0) {
                                                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                                                        $query_Output->execute(array(":outputid" => $output_id));
                                                                        $row_Output = $query_Output->fetch();
                                                                        $total_Output = $query_Output->rowCount();
                                                                        if ($total_Output > 0) {
                                                                            $output_id = $row_Output['id'];
                                                                            $output = $row_Output['indicator_name'];
                                                            ?>
                                                                            <fieldset class="scheduler-border">
                                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                                                </legend>
                                                                                <div class="row clearfix">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th style="width:5%">#</th>
                                                                                                        <th style="width:65%">Subtasks</th>
                                                                                                        <th style="width:12.5%">Target</th>
                                                                                                        <th style="width:12.5%">Acheived</th>
                                                                                                        <th style="width:5%">Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <?php
                                                                                                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
                                                                                                    $query_rsTasks->execute(array(":output_id" => $output_id));
                                                                                                    $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                                                    if ($totalRows_rsTasks > 0) {
                                                                                                        $tcounter = 0;
                                                                                                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                                            $task_name = $row_rsTasks['task'];
                                                                                                            $task_id = $row_rsTasks['tkid'];
                                                                                                            $unit =  $row_rsTasks['unit_of_measure'];

                                                                                                            $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at=:created_at AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                                                                                            $query_Site_score->execute(array(":created_at" => $created_at, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                                                                                            $rows_site_score = $query_Site_score->rowCount();
                                                                                                            $row_site_score = $query_Site_score->fetch();
                                                                                                            if ($row_site_score['achieved'] != null) {
                                                                                                                $tcounter++;
                                                                                                                $achieved = $row_site_score['achieved'];
                                                                                                                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                                                $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                                                $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                                                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                                                $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                                                                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT units_no FROM tbl_project_direct_cost_plan WHERE site_id=:site_id AND subtask_id=:subtask_id");
                                                                                                                $query_rsOther_cost_plan_budget->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
                                                                                                                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                                                                                $target_units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
                                                                                                    ?>
                                                                                                                <tr id="row<?= $tcounter ?>">
                                                                                                                    <td style="width:5%"><?= $tcounter ?></td>
                                                                                                                    <td style="width:65%"><?= $task_name ?></td>
                                                                                                                    <td style="width:2512.5%"><?= number_format($target_units, 2) . "  " . $unit_of_measure ?></td>
                                                                                                                    <td style="width:12.5%"><?= number_format($achieved, 2) . "  " . $unit_of_measure ?></td>
                                                                                                                    <td style="width:5%">
                                                                                                                        <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_checklist(<?= $task_id ?>, <?= $site_id ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                                                            <fa class="fa fa-eye"></fa>
                                                                                                                        </button>
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
                                                                            </fieldset>
                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </fieldset>
                                                        <?php
                                                    }
                                                }
                                            }


                                            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 OR indicator_mapping_type=0)  AND projid = :projid");
                                            $query_Output->execute(array(":projid" => $projid));
                                            $total_Output = $query_Output->rowCount();
                                            $outputs = '';
                                            if ($total_Output > 0) {
                                                $outputs = '';
                                                if ($total_Output > 0) {
                                                    $counter = 0;
                                                    $site_id = 0;
                                                    while ($row_rsOutput = $query_Output->fetch()) {
                                                        $output_id = $row_rsOutput['id'];
                                                        $output = $row_rsOutput['indicator_name'];

                                                        $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at=:created_at AND site_id=:site_id AND output_id=:output_id");
                                                        $query_Site_score->execute(array(":created_at" => $created_at, ":site_id" => $site_id, ":output_id" => $output_id));
                                                        $rows_site_score = $query_Site_score->rowCount();
                                                        if ($rows_site_score) {
                                                            $counter++;
                                                        ?>
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                                </legend>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width:5%">#</th>
                                                                                <th style="width:65%">SubTask</th>
                                                                                <th style="width:12.5%">Target</th>
                                                                                <th style="width:12.5%">Achieved</th>
                                                                                <th style="width:10%">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
                                                                            $query_rsTasks->execute(array(":output_id" => $output_id));
                                                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                            if ($totalRows_rsTasks > 0) {
                                                                                $tcounter = 0;
                                                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                    $task_name = $row_rsTasks['task'];
                                                                                    $task_id = $row_rsTasks['tkid'];
                                                                                    $unit =  $row_rsTasks['unit_of_measure'];

                                                                                    $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at=:created_at AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                                                                    $query_Site_score->execute(array(":created_at" => $created_at, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                                                                    $rows_site_score = $query_Site_score->rowCount();
                                                                                    $row_site_score = $query_Site_score->fetch();
                                                                                    if ($row_site_score['achieved'] != null) {
                                                                                        $units_no =  $row_site_score['achieved'];
                                                                                        $tcounter++;
                                                                                        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                        $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                        $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                        $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';


                                                                                        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                                                        $query_Projstatus->execute(array(":projstatus" => 11));
                                                                                        $row_Projstatus = $query_Projstatus->fetch();
                                                                                        $total_Projstatus = $query_Projstatus->rowCount();
                                                                                        $status = "";
                                                                                        if ($total_Projstatus > 0) {
                                                                                            $status_name = $row_Projstatus['statusname'];
                                                                                            $status_class = $row_Projstatus['class_name'];
                                                                                            $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                                                        }


                                                                                        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT units_no FROM tbl_project_direct_cost_plan WHERE site_id=:site_id AND subtask_id=:subtask_id");
                                                                                        $query_rsOther_cost_plan_budget->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
                                                                                        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                                                        $target_units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
                                                                            ?>
                                                                                        <tr id="row<?= $tcounter ?>">
                                                                                            <td style="width:5%"><?= $tcounter ?></td>
                                                                                            <td style="width:65%"><?= $task_name ?></td>
                                                                                            <td style="width:12.5%"><?= number_format($target_units, 2) . "  " . $unit_of_measure ?></td>
                                                                                            <td style="width:12.5%"><?= number_format($units_no, 2) . "  " . $unit_of_measure ?></td>
                                                                                            <td style="width:5%">
                                                                                                <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_checklist(<?= $task_id ?>, <?= $site_id ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                                    <fa class="fa fa-eye"></fa>
                                                                                                </button>
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
                                                            </fieldset>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                        <i class="fa fa-comment" aria-hidden="true"></i> Remarks
                                                    </legend>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="control-label">Remarks *:</label>
                                                        <div class="form-line">
                                                            <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Observations" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i> Attachment (Files/Documents)
                                                    </legend>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:2%">#</th>
                                                                            <th style="width:40%">Attachments</th>
                                                                            <th style="width:58%">Attachment Purpose</th>
                                                                            <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="attachments_table">
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>
                                                                                <input type="file" name="monitorattachment[]" id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-md-12 text-center">
                                                        <input type="hidden" name="MM_insert" value="addprojectfrm">
                                                        <input type="hidden" name="projid" value="<?= $projid ?>">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="previous" class="tab-pane">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="nav nav-tabs" style="font-size:14px">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#menu1">
                                                        <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                                        Observations &nbsp;&nbsp;<span class="badge bg-orange" id="total-programs">|</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#menu2">
                                                        <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                                        Media &nbsp;<span class="badge bg-indigo">|</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="body">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Observations</legend>
                                                    <!-- ============================================================== -->
                                                    <!-- Start Page Content -->
                                                    <!-- ============================================================== -->
                                                    <div class="tab-content">
                                                        <div id="menu1" class="tab-pane fade in active">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:5%" align="center">#</th>
                                                                            <th style="width:85%">Remarks</th>
                                                                            <th style="width:10%">Created On</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $query_rsObservations_pending = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE projid=:projid AND observation_type=5");
                                                                        $query_rsObservations_pending->execute(array(":projid" => $projid));
                                                                        $totalRows_rsObservations_pending = $query_rsObservations_pending->rowCount();
                                                                        if ($totalRows_rsObservations_pending > 0) {
                                                                            $counter = 0;
                                                                            while ($row = $query_rsObservations_pending->fetch()) {
                                                                                $counter++;
                                                                                $observation = $row['observation'];
                                                                                $created_at = $row['created_at'];
                                                                        ?>
                                                                                <tr>
                                                                                    <td style="width:5%"><?= $counter ?></td>
                                                                                    <td style="width:85%"><?= $observation ?></td>
                                                                                    <td style="width:10%"><?= date('d M Y', strtotime($created_at)) ?></td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div id="menu2" class="tab-pane">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <ul class="nav nav-tabs" style="font-size:14px">
                                                                        <li class="active">
                                                                            <a data-toggle="tab" href="#menu6"><i class="fa fa-file-text-o bg-green" aria-hidden="true"></i> Documents &nbsp;<span class="badge bg-green">|</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#menu7"><i class="fa fa-file-image-o bg-blue" aria-hidden="true"></i> Photos &nbsp;<span class="badge bg-blue">|</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#menu8"><i class="fa fa-file-video-o bg-orange" aria-hidden="true"></i> Videos &nbsp;<span class="badge bg-orange">|</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="body">
                                                                    <div class="tab-content">
                                                                        <div id="menu6" class="tab-pane fade in active">
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                            <thead>
                                                                                                <tr class="bg-grey">
                                                                                                    <th width="5%"><strong>#</strong></th>
                                                                                                    <th width="30%"><strong>Name</strong></th>
                                                                                                    <th width="30%"><strong>Purpose</strong></th>
                                                                                                    <th width="10%"><strong>Stage</strong></th>
                                                                                                    <th width="10%"><strong>Created On</strong></th>
                                                                                                    <th width="15%"><strong>Action</strong></th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                $query_project_docs = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND (ftype<>'jpg' and ftype<>'jpeg' and ftype<>'png' and ftype<>'mp4')");
                                                                                                $query_project_docs->execute(array(":projid" => $projid));
                                                                                                $count_project_docs = $query_project_docs->rowCount();
                                                                                                if ($count_project_docs > 0) {
                                                                                                    $rowno = 0;
                                                                                                    while ($rows_project_docs = $query_project_docs->fetch()) {
                                                                                                        $rowno++;
                                                                                                        $projstageid = $rows_project_docs['projstage'];
                                                                                                        $filename = $rows_project_docs['filename'];
                                                                                                        $filepath = $rows_project_docs['floc'];
                                                                                                        $purpose = $rows_project_docs['reason'];
                                                                                                        $created_at = $rows_project_docs['date_uploaded'];

                                                                                                        $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                        $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                        $rows_project_stage = $query_project_stage->fetch();
                                                                                                        $projstage = $rows_project_stage['stage'];
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <td width="5%"><?= $rowno; ?></td>
                                                                                                            <td width="30%"><?= $filename; ?></td>
                                                                                                            <td width="30%"><?= $purpose; ?></td>
                                                                                                            <td width="10%"><?= $projstage; ?></td>
                                                                                                            <td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
                                                                                                            <td width="15%">
                                                                                                                <a href="<?= $filepath; ?>" download>Download</a>
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
                                                                        <div id="menu7" class="tab-pane fade">
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                            <thead>
                                                                                                <tr class="bg-grey">
                                                                                                    <th width="5%"><strong>#</strong></th>
                                                                                                    <th width="30%"><strong>Name</strong></th>
                                                                                                    <th width="30%"><strong>Purpose</strong></th>
                                                                                                    <th width="10%"><strong>Stage</strong></th>
                                                                                                    <th width="10%"><strong>Created On</strong></th>
                                                                                                    <th width="15%"><strong>Action</strong></th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                $query_project_photos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND (ftype='jpg' or ftype='jpeg' or ftype='png')");
                                                                                                $query_project_photos->execute(array(":projid" => $projid));
                                                                                                $count_project_photos = $query_project_photos->rowCount();
                                                                                                if ($count_project_photos > 0) {
                                                                                                    $rowno = 0;
                                                                                                    while ($rows_project_photos = $query_project_photos->fetch()) {
                                                                                                        $rowno++;
                                                                                                        $fileid = $rows_project_photos['fid'];
                                                                                                        $projstageid = $rows_project_photos['projstage'];
                                                                                                        $filename = $rows_project_photos['filename'];
                                                                                                        $filepath = $rows_project_photos['floc'];
                                                                                                        $purpose = $rows_project_photos['reason'];
                                                                                                        $created_at = $rows_project_photos['date_uploaded'];
                                                                                                        $fileid = base64_encode("projid54321{$fileid}");

                                                                                                        $photo = '<a href="project-gallery.php?photo=' . $fileid . '" class="gallery-item">
                                                                                                            <img class="img-fluid" src="' . $filepath . '" alt="Click to view the photo" style="width:30px; height:30px; margin-bottom:0px"/>
                                                                                                        </a>';

                                                                                                        $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                        $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                        $rows_project_stage = $query_project_stage->fetch();
                                                                                                        $projstage = $rows_project_stage['stage'];
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <td width="5%"><?= $rowno; ?></td>
                                                                                                            <td width="5%"><?= $photo; ?></td>
                                                                                                            <td width="30%"><?= $filename; ?></td>
                                                                                                            <td width="30%"><?= $purpose; ?></td>
                                                                                                            <td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
                                                                                                            <td width="10%"><?= $projstage; ?></td>
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
                                                                        <div id="menu8" class="tab-pane fade">
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                            <thead>
                                                                                                <tr class="bg-grey">
                                                                                                    <th width="5%"><strong>#</strong></th>
                                                                                                    <th width="30%"><strong>Name</strong></th>
                                                                                                    <th width="30%"><strong>Purpose</strong></th>
                                                                                                    <th width="10%"><strong>Stage</strong></th>
                                                                                                    <th width="10%"><strong>Created On</strong></th>
                                                                                                    <th width="15%"><strong>Action</strong></th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND ftype='mp4'");
                                                                                                $query_project_videos->execute(array(":projid" => $projid));
                                                                                                $count_project_videos = $query_project_videos->rowCount();
                                                                                                if ($count_project_videos > 0) {
                                                                                                    $rowno = 0;
                                                                                                    while ($rows_project_videos = $query_project_videos->fetch()) {
                                                                                                        $rowno++;
                                                                                                        $projstageid = $rows_project_videos['projstage'];
                                                                                                        $filename = $rows_project_videos['filename'];
                                                                                                        $filepath = $rows_project_videos['floc'];
                                                                                                        $purpose = $rows_project_videos['reason'];
                                                                                                        $created_at = $rows_project_videos['date_uploaded'];

                                                                                                        $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                        $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                        $rows_project_stage = $query_project_stage->fetch();
                                                                                                        $projstage = $rows_project_stage['stage'];
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <td width="5%"><?= $rowno; ?></td>
                                                                                                            <td width="35%"><?= $filename; ?></td>
                                                                                                            <td width="35%"><?= $purpose; ?></td>
                                                                                                            <td width="10%"><?= $projstage; ?></td>
                                                                                                            <td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
                                                                                                            <td width="15%">
                                                                                                                <a href="<?= $filepath; ?>" watch>Watch</a>
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ============================================================== -->
                                                    <!-- End PAge Content -->
                                                    <!-- ============================================================== -->
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info"> <span id="task_name"></span> Details</span></h4>
                </div>
                <div class="modal-body">
                    <div class="card-header">
                        <ul class="nav nav-tabs" style="font-size:14px">
                            <li class="active">
                                <a data-toggle="tab" href="#home1"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Remarks &nbsp;<span class="badge bg-orange">|</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#menu10"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Attachments &nbsp;<span class="badge bg-blue">|</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="home1" class="tab-pane fade in active">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-comment" aria-hidden="true"></i> Monitoring Remark(s)
                                </legend>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Remarks *:</label>
                                    <div class="form-line">
                                        <p id="remarks"></p>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="menu10" class="tab-pane fade">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i>Attachments (Files/Documents)
                                </legend>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:2%">#</th>
                                                        <th style="width:40%">Attachments</th>
                                                        <th style="width:50%">Attachment Purpose</th>
                                                        <th style="width:10%">Attachment Purpose</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attachments_table">
                                                    <tr>
                                                        <td colspan="3" class="text-center">No Files Found</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </div> <!-- /modal-footer -->
                <div class="modal-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                    </div>
                </div>
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script>
    const ajax_url = "ajax/monitoring/checklist-history.php";

    function add_checklist(subtask_id, site_id) {
        $.ajax({
            type: "get",
            url: ajax_url,
            data: {
                get_info: 'get_info',
                subtask_id: subtask_id,
                site_id: site_id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#attachments_table").html(response.attachments);
                    $("#remarks").html(response.remarks);
                } else {
                    $("#attachments_table").html(`<tr><td colspan="3" class="text-center">No Files Found</td></tr>`);
                }
            }
        });
    }

    function add_attachment() {
        var rand = Math.floor(Math.random() * 6) + 1;
        var rowno = $("#attachments_table tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
        $("#attachments_table tr:last").after(`
        <tr id="rw${rowno}">
            <td>1</td>
            <td>
                <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
        number_table();
    }

    function delete_attach(rownm) {
        $("#" + rownm).remove();
        number_table();
    }

    function number_table() {
        $("#attachments_table tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx + 1);
        });
    }
</script>