<?php
include '../controller.php';
try {
    function validate_output($output_id)
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
                $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.output_id=:output_id  AND t.member_id=:member_id ");
                $query_rsChecked->execute(array(":output_id" => $output_id, ":member_id" => $user_name));
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
                $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.output_id=:output_id  AND t.member_id=:member_id ");
                $query_rsChecked->execute(array(":output_id" => $output_id, ":member_id" => $user_name));
                $totalRows_rsChecked = $query_rsChecked->rowCount();
                $stand_in_responsible = $totalRows_rsChecked > 0 ? true : false;
            }
        }

        return $stand_in_responsible == true || $responsible == true  ? true : false;
    }

    function validate_subtasks($subtask_id)
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
                $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.subtask_id=:subtask_id  AND t.member_id=:member_id ");
                $query_rsChecked->execute(array(":subtask_id" => $subtask_id, ":member_id" => $user_name));
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
                $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE m.subtask_id=:subtask_id  AND t.member_id=:member_id ");
                $query_rsChecked->execute(array(":subtask_id" => $subtask_id, ":member_id" => $user_name));
                $totalRows_rsChecked = $query_rsChecked->rowCount();
                $stand_in_responsible = $totalRows_rsChecked > 0 ? true : false;
            }
        }

        return $stand_in_responsible == true || $responsible == true  ? true : false;
    }

    function get_measurement($unit)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
        $sql->execute(array(":unit_id" => $unit));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        return ($rows_count > 0) ?   $row['unit'] : "";
    }

    function previous_remarks($milestone_id, $site_id, $subtask_id, $task_id)
    {
        global $db;
        $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE milestone_id=:milestone_id AND site_id=:site_id and subtask_id=:subtask_id AND task_id = :task_id");
        $query_allObservation->execute(array(":milestone_id" => $milestone_id, ":site_id" => $site_id, ":subtask_id" => $subtask_id, ":task_id" => $task_id));
        $totalrows_allObservation = $query_allObservation->rowCount();
        $comments_body = "";
        if ($totalrows_allObservation > 0) {
            $count =  0;
            while ($rows_allObservation = $query_allObservation->fetch()) {
                $count++;
                $comments = $rows_allObservation["observation"];
                $created_at = $rows_allObservation["created_at"];
                $comments_body .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td>' . $comments . '</td>
                        <td>' . date("d-m-Y", strtotime($created_at)) . '</td>
                    </tr>';
            }
        }

        $previous_records = $comments_body != "" ?  get_body($comments_body) : "<h4>No Records</h4>";
        return $previous_records;
    }

    function get_sites($output_id)
    {
        global $db;
        $sites = '<option value="">... Select Site ...</option>';
        $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
        $query_Output->execute(array(":output_id" => $output_id));
        $total_Output = $query_Output->rowCount();
        if ($total_Output > 0) {
            while ($row_rsOutput = $query_Output->fetch()) {
                $site_id = $row_rsOutput['site_id'];
                $site_name = $row_rsOutput['site'];
                $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id = :site_id AND complete=0 ");
                $query_rsPlan->execute(array(":site_id" => $site_id));
                $totalRows_plan = $query_rsPlan->rowCount();
                if ($totalRows_plan > 0) {
                    $sites .= '<option value="' . $site_id . '">' . $site_name . '</option>';
                }
            }
        }
        return $sites;
    }

    function get_output_milestones($projid, $output_id)
    {
        global $db;
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid AND milestone_type=1");
        $query_rsOutput->execute(array(":projid" => $projid));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $milestones = '<option value="">... Select Milestone ...</option>';
        $project_type = 1; // output based
        if ($totalRows_rsOutput > 0) {
            $project_type = 2;
            while ($row_rsOutput = $query_rsOutput->fetch()) {
                $milestone_id = $row_rsOutput['id'];
                $milestone = $row_rsOutput['milestone'];

                $query_rsMilestone_Outputs = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE output_id=:output_id AND milestone_id=:milestone_id");
                $query_rsMilestone_Outputs->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id));
                $totalRows_rsMilestone_Outputs = $query_rsMilestone_Outputs->rowCount();

                $query_rsSubtasks = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE milestone_id=:milestone_id  AND complete=0");
                $query_rsSubtasks->execute(array(":milestone_id" => $milestone_id));
                $totalRows_rsSubtasks = $query_rsSubtasks->rowCount();

                if ($totalRows_rsSubtasks > 0 && $totalRows_rsMilestone_Outputs > 0) {
                    $milestones .= '<option value="' . $milestone_id . '">' . $milestone . '</option>';
                }
            }
        }

        $milestone_array = array("project_type" => $project_type, "milestones" => $milestones);
        return $milestone_array;
    }

    function get_output_subtasks($output_id)
    {
        global $db;

        $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id = :output_id AND complete=0 ");
        $query_rsPlan->execute(array(":output_id" => $output_id));
        $totalRows_plan = $query_rsPlan->rowCount();
        $subtasks = '';
        if ($totalRows_plan > 0) {
            $counter = 0;
            while ($Rows_plan = $query_rsPlan->fetch()) {
                $task_id = $Rows_plan['task_id'];
                $subtask_id = $Rows_plan['subtask_id'];
                $site_id = $Rows_plan['site_id'];
                $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE site_id=:site_id  AND subtask_id=:subtask_id");
                $query_rsOther_cost_plan->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id));
                $row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
                $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

                if ($totalRows_rsOther_cost_plan > 0) {
                    $task = $row_rsOther_cost_plan['description'];
                    $target = $row_rsOther_cost_plan['units_no'];
                    $site_id = $row_rsOther_cost_plan['site_id'];
                    $unit = $row_rsOther_cost_plan['unit'];
                    $unit_of_measure =  get_measurement($unit);

                    $query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND site_id=:site_id ");
                    $query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':site_id' => $site_id));
                    $row_rsMilestone_cummulative = $query_rsMilestone_cummulative->fetch();
                    $cummulative = $row_rsMilestone_cummulative['cummulative'] != null ? $row_rsMilestone_cummulative['cummulative'] : 0;

                    $counter++;
                    $subtasks .= '
                        <tr id="s_row">
                            <td>' . $counter . '</td>
                            <td>' . $task . '</td>
                            <td>' . $target  . " " . $unit_of_measure . ' </td>
                            <td>' . $cummulative . " " . $unit_of_measure . ' </td>
                            <td>
                                <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false"
                                onclick="add_checklist(' . $site_id . ',' . 0 . ',' . $task_id . ', ' . $subtask_id . ')" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </td>
                        </tr>';
                }
            }
        }
        return $subtasks;
    }

    if (isset($_GET['get_milestones'])) {
        $output_id = $_GET['output_id'];
        $projid = $_GET['projid'];
        $query_rsOutputs = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutputs->execute(array(":output_id" => $output_id));
        $Rows_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();
        $mapping_type =  ($totalRows_rsOutputs > 0) ? $Rows_rsOutputs['indicator_mapping_type'] : '';

        $sites =  $milestones = $subtasks = '';
        if ($totalRows_rsOutputs > 0) {
            $milestones = get_output_milestones($projid, $output_id);
            if ($mapping_type == 1) {
                $sites = get_sites($output_id);
            } else {
                $subtasks = $milestones['project_type'] == 1  ? get_output_subtasks($output_id) : '';
            }
        }
        echo json_encode(array("success" => true, "milestone_data" => $milestones, "output_details" => $Rows_rsOutputs, "sites" => $sites, "subtasks" => $subtasks));
    }

    function get_inspection_status($status_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE id = :status_id");
        $sql->execute(array(":status_id" => $status_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        return ($rows_count > 0) ? $row['status'] : "";
    }

    function get_body($comments_body)
    {
        $data =  '';
        $c = $i = "";
        if ($comments_body != "") {
            $c = '
            <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    <i class="fa fa-comment" aria-hidden="true"></i> Remark(s)
                </legend>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:85%">Comment</th>
                                    <th style="width:10%">Date Posted</th>
                                </tr>
                            </thead>
                            <tbody id="previous_comments">
                            ' . $comments_body . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>';
        }
        $dat = "";
        if ($comments_body == "") {
            $dat = "<h3>No Record</h3>";
        }

        $data .= '
            <div class="row clearfix">
                ' . $i . $c . $dat .  '
            </div> ';
        return $data;
    }


    function get_project_subtask_progress($subtask_id, $site_id)
    {
        global $db;
        $query_rsAchieved =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND site_id=:site_id");
        $query_rsAchieved->execute(array(":subtask_id" => $subtask_id, ":site_id" => $site_id));
        $row_rsAchieved = $query_rsAchieved->fetch();
        return $row_rsAchieved['cummulative'] != null ? $row_rsAchieved['cummulative'] : 0;
    }

    function get_milestone_subtask_progress($milestone_id, $subtask_id, $site_id)
    {
        global $db;
        $query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND milestone_id=:milestone_id AND site_id=:site_id ");
        $query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':milestone_id' => $milestone_id, ':site_id' => $site_id));
        $row_rsMilestone_cummulative = $query_rsMilestone_cummulative->fetch();
        return $row_rsMilestone_cummulative['cummulative'] != null ? $row_rsMilestone_cummulative['cummulative'] : 0;
    }

    function get_milestone_subtask_previous_record($milestone_id, $subtask_id, $site_id)
    {
        global $db;
        $query_rsMilestone_previous =  $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND milestone_id=:milestone_id AND site_id=:site_id ORDER BY id DESC");
        $query_rsMilestone_previous->execute(array(":subtask_id" => $subtask_id, ':milestone_id' => $milestone_id, ':site_id' => $site_id));
        $row_rsMilestone_previous = $query_rsMilestone_previous->fetch();
        $total_rsMilestone_previous = $query_rsMilestone_previous->rowCount();
        return $total_rsMilestone_previous > 0 ? $row_rsMilestone_previous['achieved'] : 0;
    }

    if (isset($_GET['get_subtasks'])) {
        $output_id = $_GET['output_id'];
        $milestone_id = $_GET['milestone_id'];
        $site_id = $_GET['site_id'];
        $success = true;

        $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id = :output_id AND site_id=:site_id AND complete=0 ");
        $query_rsPlan->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
        $totalRows_plan = $query_rsPlan->rowCount();
        $subtasks = '';

        if ($totalRows_plan > 0) {
            $count = 0;
            while ($Rows_plan = $query_rsPlan->fetch()) {
                $task_id = $Rows_plan['task_id'];
                $subtask_id = $Rows_plan['subtask_id'];
                $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE site_id=:site_id  AND subtask_id=:subtask_id");
                $query_rsOther_cost_plan->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id));
                $row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
                $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

                if ($totalRows_rsOther_cost_plan > 0) {
                    $task = $row_rsOther_cost_plan['description'];
                    $target = $row_rsOther_cost_plan['units_no'];
                    $unit = $row_rsOther_cost_plan['unit'];
                    $unit_of_measure =  get_measurement($unit);

                    $milestone_validation = true;
                    $query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND site_id=:site_id ");
                    $query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':site_id' => $site_id));

                    if ($milestone_id  != 0) {
                        $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE output_id=:output_id AND  milestone_id=:milestone_id AND subtask_id=:subtask_id AND complete=0");
                        $query_rsChecked->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id, ":subtask_id" => $subtask_id));
                        $totalRows_rsChecked = $query_rsChecked->rowCount();
                        $milestone_validation = $totalRows_rsChecked > 0 ? true : false;

                        $query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND milestone_id=:milestone_id AND site_id=:site_id ");
                        $query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':milestone_id' => $milestone_id, ':site_id' => $site_id));
                    }

                    $row_rsMilestone_cummulative = $query_rsMilestone_cummulative->fetch();
                    $cummulative = $row_rsMilestone_cummulative['cummulative'] != null ? $row_rsMilestone_cummulative['cummulative'] : 0;

                    if ($milestone_validation) {
                        $count++;
                        $subtasks .= '
                        <tr id="s_row">
                            <td>' . $count . '</td>
                            <td>' . $task . '</td>
                            <td>' . $target  . " " . $unit_of_measure . ' </td>
                            <td>' . $cummulative . " " . $unit_of_measure . ' </td>
                            <td>
                                <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false"
                                onclick="add_checklist(' . $site_id . ',' . $milestone_id . ',' . $task_id . ', ' . $subtask_id . ')" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </td>
                        </tr>';
                    }
                }
            }
        }

        echo json_encode(array("success" => $success, "subtasks" => $subtasks));
    }


    if (isset($_GET['get_subtask_details'])) {
        $output_id = $_GET['output_id'];
        $milestone_id = $_GET['milestone_id'];
        $subtask_id = $_GET['subtask_id'];
        $site_id = $_GET['site_id'];
        $task_id = $_GET['task_id'];
        $target = 0;
        $success = true;
        $subtask = '';

        $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE site_id=:site_id  AND tasks=:task_id  AND subtask_id=:subtask_id");
        $query_rsOther_cost_plan->execute(array(':site_id' => $site_id, ':task_id' => $task_id, ":subtask_id" => $subtask_id));
        $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
        $row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
        if ($totalRows_rsOther_cost_plan > 0) {
            $subtask = $row_rsOther_cost_plan['description'];
            $unit = $row_rsOther_cost_plan['unit'];
            $target = $row_rsOther_cost_plan['units_no'];
            $unit_of_measure = get_measurement($unit);
        }

        $bq =   previous_remarks($milestone_id, $site_id, $subtask_id, $task_id);

        $query_rsIssues =  $db->prepare("SELECT * FROM tbl_project_adjustments a INNER JOIN tbl_projissues i  ON i.id = a.issueid WHERE i.status <> 7  AND a.sub_task_id=:subtask_id");
        $query_rsIssues->execute(array(":subtask_id" => $subtask_id));
        $totalRows_rsIssues = $query_rsIssues->rowCount();
        $row_rsIssues = $query_rsIssues->rowCount();

        echo json_encode(
            array(
                "success" => $success,
                "subtask" => $subtask,
                'target' => $target  . " " . $unit_of_measure,
                'issues' => $row_rsIssues > 0 ? '1' : '0',
                "project_cummulative" => get_project_subtask_progress($subtask_id, $site_id) . " " . $unit_of_measure,
                "milestone_cummulative" => get_milestone_subtask_progress($milestone_id, $subtask_id, $site_id) . " " . $unit_of_measure,
                "previous_record" => get_milestone_subtask_previous_record($milestone_id, $subtask_id, $site_id) . " " . $unit_of_measure,
                "previous_remarks" => $bq,
            )
        );
    }

    if (isset($_POST['store_checklists'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $milestone_id = $_POST['milestone_id'];
        $task_id = $_POST['task_id'];
        $site_id = $_POST['site_id'];
        $subtask_id = $_POST['subtask_id'];
        $achieved = $_POST['current_measure'];
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");
        $origin = 3;
        $complete = $_POST['button'];

        if ($complete == 1) {
            $sql = $db->prepare("UPDATE tbl_program_of_works SET complete=:complete, status=5 WHERE  site_id=:site_id AND projid=:projid AND subtask_id=:subtask_id");
            $result  = $sql->execute(array(":complete" => $complete, ":site_id" => $site_id, ":projid" => $projid, ":subtask_id" => $subtask_id));

            $sql = $db->prepare("UPDATE tbl_milestone_output_subtasks SET complete=:complete WHERE  milestone_id=:milestone_id AND projid=:projid AND subtask_id=:subtask_id");
            $result  = $sql->execute(array(":complete" => $complete, ":milestone_id" => $milestone_id, ":projid" => $projid, ":subtask_id" => $subtask_id));
        }

        $sql = $db->prepare("INSERT INTO tbl_project_monitoring_checklist_score (projid,output_id,milestone_id,site_id,task_id,subtask_id,formid,achieved,created_by,created_at) VALUES(:projid,:output_id,:milestone_id,:site_id,:task_id,:subtask_id,:formid,:achieved,:created_by,:created_at)");
        $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => $task_id, ":subtask_id" => $subtask_id, ":formid" => $formid, ":achieved" => $achieved, ":created_by" => $user_name, ":created_at" => $created_at));

        if ($results) {
            if (isset($_POST['comments'])) {
                $observ = $_POST['comments'];
                $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid,output_id,milestone_id,site_id,task_id,subtask_id,formid,observation,created_at,created_by) VALUES (:projid,:output_id,:milestone_id,:site_id,:task_id,:subtask_id,:formid,:observation,:created_at,:created_by)");
                $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => $task_id, ":subtask_id" => $subtask_id, ':formid' => $formid, ':observation' => $observ, ':created_at' => $currentdate, ':created_by' => $user_name));
            }

            if (isset($_POST["attachmentpurpose"])) {
                $filecategory = "monitoring checklist";
                $stage = 1;
                $count = count($_POST["attachmentpurpose"]);
                for ($cnt = 0; $cnt < $count; $cnt++) {
                    if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
                        if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                            $purpose = $_POST["attachmentpurpose"][$cnt];
                            $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                            $ext = substr($filename, strrpos($filename, '.') + 1);
                            if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                                $newname = date("d-m-Y") . "-" . $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                                $filepath = "../../uploads/monitoring/other-files/" . $newname;
                                $path = "uploads/monitoring/other-files/" . $newname;
                                if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                                    $filepath = "../../uploads/monitoring/photos/" . $newname;
                                    $path = "uploads/monitoring/photos/" . $newname;
                                }

                                if (!file_exists($filepath)) {
                                    if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                        $qry2 = $db->prepare("INSERT INTO tbl_files (projid,opid,milestone_id,site_id,task_id,subtask_id,projstage,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:output_id, :milestone_id,:site_id,:task_id,:subtask_id,:projstage,:formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
                                        $result =  $qry2->execute(array(':projid' => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => $task_id, ':subtask_id' => $subtask_id, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                                    } else {
                                        echo "could not move the file ";
                                    }
                                } else {
                                    $msg = 'File you are uploading already exists, try another file!!';
                                }
                            } else {
                                $msg = 'This file type is not allowed, try another file!!';
                            }
                        } else {
                            $msg = 'You have not attached any file!!';
                        }
                    }
                }
            }
        }

        echo json_encode(array("success" => true, "message" => "Created successfully"));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
