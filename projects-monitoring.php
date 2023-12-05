<?php
require('includes/head.php');
if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND (p.projstatus=3 OR p.projstatus=4 OR p.projstatus=11) AND proj_substage=3  ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        function scheduled_team($projid, $workflow_stage)
        {
            global $db,  $user_name, $workflow_stage, $user_designation;
            $output_responsible = $standin_responsible = false;
            if ($user_designation == 1) {
                $output_responsible = true;
            } else {
                $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
                $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 1, ":responsible" => $user_name));
                $total_rsOutput = $query_rsOutput->rowCount();
                $output_responsible = $total_rsOutput > 0 ? true : false;

                $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND team_type =:team_type");
                $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":team_type" => 1));
                $row_rsOutput_standin = $query_rsOutput_standin->fetch();
                $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

                if ($total_rsOutput_standin > 0) {
                    $owner_id = $row_rsOutput_standin['owner'];
                    $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
                    $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => 1, ":responsible" => $owner_id));
                    $total_rsOutput = $query_rsOutput->rowCount();
                    $standin_responsible = $total_rsOutput > 0 ? true : false;
                }
            }
            return $output_responsible || $standin_responsible ? true : false;
        }

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

        function check_output_completion($projid, $record_type)
        {
            global $db;
            $query_rsTarget = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.projid=:projid");
            $query_rsTarget->execute(array(":projid" => $projid));
            $totalRows_rsTarget = $query_rsTarget->rowCount();
            $complete = [];
            if ($totalRows_rsTarget > 0) {
                while ($Rows_rsOutput = $query_rsTarget->fetch()) {
                    $target =  $Rows_rsOutput['total_target'];
                    $output_id = $Rows_rsOutput['id'];

                    $query_rsCummulative = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id AND record_type=:record_type");
                    $query_rsCummulative->execute(array(":output_id" => $output_id, ":record_type" => $record_type));
                    $Rows_rsCummulative = $query_rsCummulative->fetch();
                    $output_cummulative_record = $Rows_rsCummulative['achieved'] != null ? $Rows_rsCummulative['achieved'] : 0;
                    $complete[] = $output_cummulative_record == $target ? true : false;
                }
            }

            return !in_array(false, $complete) ? true : false;
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
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
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width:5%" align="center">#</th>
                                            <th style="width:10%">Code</th>
                                            <th style="width:45%">Project </th>
                                            <th style="width:10%">Progress</th>
                                            <th style="width:10%">Status</th>
                                            <th style="width:10%">Due Date</th>
                                            <th style="width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                $projid = $row_rsProjects['projid'];
                                                $projid_hashed = base64_encode("projid54321{$projid}");
                                                $implementation = $row_rsProjects['projcategory'];
                                                $sub_stage = $row_rsProjects['proj_substage'];
                                                $sub_stage = $row_rsProjects['proj_substage'];
                                                $project_department = $row_rsProjects['projsector'];
                                                $project_section = $row_rsProjects['projdept'];
                                                $project_directorate = $row_rsProjects['directorate'];
                                                $projname = $row_rsProjects['projname'];
                                                $projcode = $row_rsProjects['projcode'];
                                                $projstatus = $row_rsProjects['projstatus'];
                                                $projstartdate = $row_rsProjects['projstartdate'];
                                                $monitoring_frequency =  $row_rsProjects['monitoring_frequency'];
                                                $schedule_team  = scheduled_team($projid, $workflow_stage);
                                                $daily_team = daily_team($projid, $workflow_stage, 2);

                                                $record_type = 0;
                                                $Date = date("Y-m-d");
                                                $due_date = date('Y-m-d', strtotime($Date . ' + 1 days'));
                                                $project_complete = false;
                                                if ($schedule_team) {
                                                    $record_type = 2;
                                                    $query_rsFrequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid = :fqid");
                                                    $query_rsFrequency->execute(array(":fqid" => $monitoring_frequency));
                                                    $row_rsFrequency = $query_rsFrequency->fetch();
                                                    $total_rsFrequency = $query_rsFrequency->rowCount();
                                                    if ($total_rsFrequency > 0) {
                                                        $days = $row_rsFrequency['days'];
                                                        $due_date = date("Y-m-d");
                                                        $Date = date("Y-m-d");

                                                        $query_rsCummulative = $db->prepare("SELECT  * FROM tbl_monitoringoutput WHERE projid=:projid AND record_type=:record_type ORDER BY moid DESC  LIMIT 1");
                                                        $query_rsCummulative->execute(array(":projid" => $projid, ":record_type" => $record_type));
                                                        $Rows_rsCummulative = $query_rsCummulative->fetch();
                                                        $Row_rsCummulative = $query_rsCummulative->rowCount();
                                                        if ($Row_rsCummulative > 0) {
                                                            $Date = $Rows_rsCummulative['date_created'];
                                                        } else {
                                                            $Date = $projstartdate;
                                                            if ($implementation == 2) {
                                                                $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
                                                                $query_rsTender->execute(array(":projid" => $projid));
                                                                $row_rsTender = $query_rsTender->fetch();
                                                                $totalRows_rsTender = $query_rsTender->rowCount();
                                                                if ($totalRows_rsTender > 0) {
                                                                    $Date = $row_rsTender['startdate'];
                                                                }
                                                            }
                                                        }

                                                        $due_date = date('Y-m-d', strtotime($Date . ' + ' . $days));
                                                    }
                                                    $project_complete = check_output_completion($projid, $record_type);
                                                } else if ($daily_team) {
                                                    $record_type = 1;
                                                    $Date = date("Y-m-d");
                                                    $due_date = date('Y-m-d', strtotime($Date . ' + 1 days'));
                                                    $project_complete = check_output_completion($projid, $record_type);
                                                }

                                                if ($schedule_team || $daily_team) {
                                                    $monitored = false;
                                                    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                    $query_Projstatus->execute(array(":projstatus" => $projstatus));
                                                    $row_Projstatus = $query_Projstatus->fetch();
                                                    $total_Projstatus = $query_Projstatus->rowCount();
                                                    $status = "";
                                                    if ($total_Projstatus > 0) {
                                                        $status_name = $row_Projstatus['statusname'];
                                                        $status_class = $row_Projstatus['class_name'];
                                                        $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                    }
                                                    $projectid = base64_encode("projid54321{$projid}");
                                                    $progress = number_format(calculate_project_progress($projid, $implementation), 2);

                                                    $project_progress = '
                                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                            ' . $progress . '%
                                                        </div>
                                                    </div>';

                                                    if ($progress == 100) {
                                                        $project_progress = '
                                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                            ' . $progress . '%
                                                            </div>
                                                        </div>';
                                                    }

                                                    $counter++;
                                        ?>
                                                    <tr>
                                                        <td style="width:5%" align="center"><?= $counter ?></td>
                                                        <td style="width:10%"><?= $projcode ?></td>
                                                        <td style="width:45%"><?= $projname ?></td>
                                                        <td style="width:10%"><?= $project_progress ?></td>
                                                        <td style="width:10%"><?= $status ?></td>
                                                        <td style="width:10%"><?= $due_date ?></td>
                                                        <td style="width:10%">
                                                            <?php
                                                            if (!$project_complete) {
                                                            ?>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="get_project_outputs(<?= $projid ?>,'<?= $record_type ?>', '<?= htmlspecialchars($projname) ?>')">
                                                                                <i class="fa fa-check"></i> Monitor
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
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
    <!-- End add item -->
    <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Output Monitoring</span></h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="project_name"></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-header">
                        <ul class="nav nav-tabs" style="font-size:14px">
                            <li class="active">
                                <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Monitor &nbsp;<span class="badge bg-orange">|</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Remarks &nbsp;<span class="badge bg-blue">|</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Attachments &nbsp;<span class="badge bg-blue">|</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <form class="form-horizontal" id="add_items" action="" method="POST" enctype="multipart/form-data">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-calculator" aria-hidden="true"></i> Measurement
                                    </legend>
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label class="control-label">Output *:</label>
                                            <div class="form-line">
                                                <select name="output" id="output" class="form-control show-tick" onchange="get_sites()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Output ....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label class="control-label"><?= $level2label ?>/Site *:</label>
                                            <div class="form-line">
                                                <input type="hidden" name="output_type" id="output_type">
                                                <select name="site" id="site" class="form-control show-tick" onchange="get_milestones()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                    <option value="">.... Select Site ....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 milestone_div">
                                            <label class="control-label">Milestones *:</label>
                                            <div class="form-line">
                                                <select name="milestone" id="milestone" class="form-control show-tick" onchange="get_output_details()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Milestone ....</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix" style="margin-top: 30px;">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <label for="projendyear">Target *:</label>
                                            <input type="text" name="target" id="target" placeholder="" class="form-control" readonly />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <label for="projendyear">Cummulative Measurement *:</label>
                                            <input type="text" name="cummulative" id="cummulative" placeholder="" class="form-control" readonly />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <label for="projendyear">Previous Measurement *:</label>
                                            <input type="text" name="previous" id="previous" placeholder="" class="form-control" readonly />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <label for="projendyear">Current Measurement *:</label>
                                            <input type="number" name="current_measure" id="current_measure" placeholder="Enter the current output measurement" min="0" onchange="validateCeiling()" onkeyup="validateCeiling()" class="form-control" required>
                                            <input type="hidden" name="milestone_target" id="milestone_target" class="form-control">
                                            <input type="hidden" name="milestone_achieved" id="milestone_achieved" class="form-control">
                                            <input type="hidden" name="site_target" id="site_target" class="form-control">
                                            <input type="hidden" name="site_achieved" id="site_achieved" class="form-control">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-comment" aria-hidden="true"></i> Monitoring Remark(s)
                                    </legend>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Remarks *:</label>
                                        <div class="form-line">
                                            <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i> Means of Verification (Files/Documents)
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="projid" id="projid" value="">
                                    <input type="hidden" name="formid" id="formid" value="<?= date("Y-m-d") ?>">
                                    <input type="hidden" name="store" id="store" value="new">
                                    <input type="hidden" name="output_project_type" id="output_project_type" value="new">
                                    <input type="hidden" name="store_outputs" id="store_outputs" value="new">
                                    <input type="hidden" name="monitoring_type" id="monitoring_type" value="1">
                                    <input type="hidden" name="record_type" id="record_type" value="">
                                    <input type="hidden" name="completed" id="completed" value="">
                                    <!-- <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="">Save</button> -->

                                    <button type="submit" class="btn btn-primary waves-effect waves-light" value="button1" id="tag-form-submit"> Save</button>
                                    <button type="submit" class="btn btn-success waves-effect waves-light" value="button2" id="tag-form-submit1"> Save and Complete</button>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div id="previous_remarks">
                                <h1>No records Found</h1>
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div id="previous_images">
                                <h1>No records Found</h1>
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- /modal-footer -->
                <div class="modal-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <!-- <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button> -->
                    </div>
                </div> <!-- /modal-footer -->
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


<script src="assets/js/monitoring/output.js"></script>