<?php
try {
    require('includes/head.php');
    if ($permission && isset($_GET['projid'])) {
        $encoded_projid = $_GET['projid'];
        $decode_projid = base64_decode($encoded_projid);
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];


        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid AND projstage=:workflow_stage");
        $query_rsProjects->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $implimentation_type = $row_rsProjects['projcategory'];
            $project_name = $row_rsProjects['projname'];
            $projcode = $row_rsProjects['projcode'];
            $team_type = 4;
            function validate_tasks($milestone_id)
            {
                global $db, $projid, $user_designation, $team_type, $workflow_stage, $user_name;
                $responsible = false;
                if ($user_designation == 1) {
                    $responsible = true;
                } else {
                    $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
                    $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $user_name));
                    $total_rsOutput = $query_rsOutput->rowCount();
                    if ($total_rsOutput > 0) {
                        $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.milestone_id=:milestone_id  AND t.member_id=:member_id ");
                        $query_rsChecked->execute(array(":milestone_id" => $milestone_id, ":member_id" => $user_name));
                        $totalRows_rsChecked = $query_rsChecked->rowCount();
                        $responsible = $totalRows_rsChecked > 0 ? true : false;
                    }
                }

                $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND team_type =:team_type AND status = 1");
                $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":team_type" => $team_type));
                $row_rsOutput_standin = $query_rsOutput_standin->fetch();
                $total_rsOutput_standin = $query_rsOutput_standin->rowCount();
                $stand_in_responsible = false;

                if ($total_rsOutput_standin > 0) {
                    $owner_id = $row_rsOutput_standin['owner'];
                    $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
                    $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $owner_id));
                    $total_rsOutput = $query_rsOutput->rowCount();

                    if ($total_rsOutput > 0) {
                        $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.milestone_id=:milestone_id  AND t.member_id=:member_id ");
                        $query_rsChecked->execute(array(":milestone_id" => $milestone_id, ":member_id" => $user_name));
                        $totalRows_rsChecked = $query_rsChecked->rowCount();
                        $stand_in_responsible = $totalRows_rsChecked > 0 ? true : false;
                    }
                }

                return $stand_in_responsible == true || $responsible == true  ? true : false;
            }
?>
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon . " " . $pageTitle  ?>
                            <div class="btn-group" style="float:right">
                                <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right" style="margin-right:10px; margin-top:-5px">
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
                                                <li class="list-group-item"><strong>Code: </strong> <?= $projcode ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label class="control-label">Output *:</label>
                                            <div class="form-line">
                                                <select name="output" id="output" class="form-control show-tick" onchange="get_milestones()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Output ....</option>
                                                    <?php
                                                    $query_rsOutput = $db->prepare("SELECT i.indicator_name, d.id FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid=:projid AND complete=0");
                                                    $query_rsOutput->execute(array(":projid" => $projid));
                                                    $totalRows_rsOutput = $query_rsOutput->rowCount();
                                                    $outputs = '<option value="">... Select Output ...</option>';
                                                    if ($totalRows_rsOutput > 0) {
                                                        while ($row_rsOutput = $query_rsOutput->fetch()) {
                                                            $output_id = $row_rsOutput['id'];
                                                            $output_name = $row_rsOutput['indicator_name'];
                                                            $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id = :output_id AND complete=0 ");
                                                            $query_rsPlan->execute(array(":output_id" => $output_id));
                                                            $totalRows_plan = $query_rsPlan->rowCount();
                                                            if ($totalRows_plan > 0) {
                                                    ?>
                                                                <option value="<?= $output_id ?>"> <?= $output_name  ?></option>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="milestone_div">
                                            <label class="control-label">Milestones *:</label>
                                            <div class="form-line">
                                                <select name="milestone" id="milestone" onchange="get_subtasks()" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Milestone ....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="site_div">
                                            <label class="control-label">Site *:</label>
                                            <div class="form-line">
                                                <input type="hidden" name="output_type" id="output_type">
                                                <select name="site" id="site" class="form-control show-tick" onchange="get_subtasks()" onchange="get_outputs()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                    <option value="">.... Select Site ....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Checklist</legend>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example ">
                                                            <thead>
                                                                <tr style="background-color:#0b548f; color:#FFF">
                                                                    <th style="width:5%" align="center">#</th>
                                                                    <th style="width:80%">Subtask</th>
                                                                    <th style="width:80%">Target</th>
                                                                    <th style="width:80%">Cummulative</th>
                                                                    <th style="width:10%">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="subtask_table_body">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </fieldset>
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
                            <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info"> Monitor</span></h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">SubTask : <span id="task_name"></span> </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <ul class="nav nav-tabs" style="font-size:14px">
                                    <li class="active">
                                        <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Monitor &nbsp;<span class="badge bg-orange">|</span></a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Remarks &nbsp;<span class="badge bg-blue">|</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <form class="form-horizontal" id="add_items" action="" method="POST">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-calculator" aria-hidden="true"></i> Activities Monitoring
                                            </legend>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                <label for="projduration">Target :</label>
                                                <div class="form-input">
                                                    <input type="text" name="target" id="target" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                <label for="projendyear">Cumulative Measurement :</label>
                                                <input type="text" name="cummulative" id="cummulative" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                <label for="projendyear">Previous Measurement :</label>
                                                <input type="text" name="previous" id="previous" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                <label for="projendyear">Current Measurement *:</label>
                                                <input type="number" name="current_measure" min="0" id="current_measure" class="form-control" required>
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
                                        <div class="modal-footer">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input type="hidden" name="store_checklists" id="store_checklists" value="store_checklists">
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                <input type="hidden" name="milestone_id" id="milestone_id">
                                                <input type="hidden" name="output_id" id="output_id" value="">
                                                <input type="hidden" name="task_id" id="task_id" value="">
                                                <input type="hidden" name="user_name" id="user_name" value="">
                                                <input type="hidden" name="project_type" id="project_type" value="">
                                                <input type="hidden" name="output_type" id="output_type" value="">
                                                <input type="hidden" name="site_id" id="site_id" value="">
                                                <input type="hidden" name="subtask_id" id="subtask_id" value="">
                                                <input type="hidden" name="subtask_issues" id="subtask_issues" value="">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light" value="button1" id="tag-form-submit"> Save</button>
                                                <button type="submit" class="btn btn-success waves-effect waves-light" value="button2" id="tag-form-submit2"> Save and Complete</button>
                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                            </div>
                                        </div> <!-- /modal-footer -->
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
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/monitoring/monitor.js"></script>