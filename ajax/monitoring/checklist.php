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

    if (isset($_GET['get_milestone_outputs'])) {
        $milestone_id = $_GET['milestone_id'];
        $query_rsOutput = $db->prepare("SELECT i.indicator_name, d.id,m.target FROM tbl_project_milestone_outputs m INNER JOIN tbl_project_details d ON m.output_id = d.id INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE milestone_id=:milestone_id");
        $query_rsOutput->execute(array(":milestone_id" => $milestone_id));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $outputs = '<option value="">... Select Output ...</option>';
        $success = true;
        if ($totalRows_rsOutput > 0) {
            $mcounter = 0;
            while ($row_rsOutput = $query_rsOutput->fetch()) {
                $output_id = $row_rsOutput['id'];
                $output_name = $row_rsOutput['indicator_name'];
                if (validate_output($output_id)) {
                    $outputs .= '<option value="' . $output_id . '">' . $output_name . '</option>';
                }
            }
        }
        echo json_encode(array("success" => $success, "outputs" => $outputs));
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


    function get_project_subtask_progress($subtask_id)
    {
        global $db;
        $query_rsAchieved =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id ");
        $query_rsAchieved->execute(array(":subtask_id" => $subtask_id));
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


    function get_subtasks($milestone_id, $output_id, $site_id)
    {
        global $db;
        $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks s INNER JOIN tbl_task t ON t.tkid = s.subtask_id WHERE output_id=:output_id AND  milestone_id=:milestone_id AND complete=0");
        $query_rsChecked->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id));
        $totalRows_rsChecked = $query_rsChecked->rowCount();
        $subtasks = '';
        if ($totalRows_rsChecked > 0) {
            $count = 0;
            while ($Rows_rsChecked = $query_rsChecked->fetch()) {
                $count++;
                $task = $Rows_rsChecked['task'];
                $tkid = $Rows_rsChecked['tkid'];
                $msid = $Rows_rsChecked['msid'];
                $unit = $Rows_rsChecked['unit_of_measure'];

                $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id = :output_id AND site_id=:site_id  AND subtask_id=:subtask_id AND complete=0 ");
                $query_rsPlan->execute(array(":output_id" => $output_id, ':site_id' => $site_id, ":subtask_id" => $tkid));
                $totalRows_plan = $query_rsPlan->rowCount();

                if (validate_subtasks($tkid) && $totalRows_plan > 0) {
                    $unit_of_measure =  get_measurement($unit);
                    $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE site_id=:site_id  AND subtask_id=:subtask_id");
                    $query_rsOther_cost_plan->execute(array(':site_id' => $site_id, ":subtask_id" => $tkid));
                    $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
                    $row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
                    $target = ($totalRows_rsOther_cost_plan > 0) ? $row_rsOther_cost_plan['units_no'] : 0;
                    $cummulative = get_milestone_subtask_progress($milestone_id, $tkid, $site_id);
                    $subtasks .= '
                    <tr id="s_row">
                        <td>' . $count . '</td>
                        <td>' . $task . '</td>
                        <td>' . $target  . " " . $unit_of_measure . ' </td>
                        <td>' . $cummulative . " " . $unit_of_measure . ' </td>
                        <td>
                            <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_checklist(' . $msid . ', ' . $tkid . ')" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </td>
                    </tr>';
                }
            }
        }
        return $subtasks;
    }

    if (isset($_GET['get_output_sites'])) {
        $output_id = $_GET['output_id'];
        $milestone_id = $_GET['milestone_id'];
        $success = true;
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs m INNER JOIN tbl_project_details d ON m.output_id = d.id INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $Rows_rsOutput = $query_rsOutput->fetch();
        $sites = '<option value="">... Select Site ...</option>';
        $mapping_type = '';
        if ($totalRows_rsOutput > 0) {
            $mapping_type = $Rows_rsOutput['indicator_mapping_type'];
            $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
            $query_Output->execute(array(":output_id" => $output_id));
            $total_Output = $query_Output->rowCount();
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $site_id = $row_rsOutput['site_id'];
                    $site_name = $row_rsOutput['site'];
                    $sites .= '<option value="' . $site_id . '">' . $site_name . '</option>';
                }
            }
        }

        $output_type = $mapping_type == 2 || $mapping_type == 0 ? 2 : 1;
        $subtasks = $output_type == 2 ? get_subtasks($milestone_id, $output_id, 0) : '';
        echo json_encode(array("success" => $success, "sites" => $sites, 'output_type' => $output_type, "subtasks" => $subtasks));
    }

    if (isset($_GET['get_subtasks'])) {
        $output_id = $_GET['output_id'];
        $milestone_id = $_GET['milestone_id'];
        $site_id = $_GET['site_id'];
        $success = true;
        $subtasks = get_subtasks($milestone_id, $output_id, $site_id);
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
        echo json_encode(
            array(
                "success" => $success,
                "subtask" => $subtask,
                'target' => $target  . " " . $unit_of_measure,
                "project_cummulative" => get_project_subtask_progress($subtask_id) . " " . $unit_of_measure,
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
            $sql = $db->prepare("UPDATE tbl_program_of_works SET complete=:complete WHERE  site_id=:site_id AND projid=:projid AND subtask_id=:subtask_id");
            $result  = $sql->execute(array(":complete" => $complete, ":site_id" => $site_id, ":projid" => $projid, ":subtask_id" => $subtask_id,));

            $sql = $db->prepare("UPDATE tbl_milestone_output_subtasks SET complete=:complete WHERE  milestone_id=:milestone_id AND projid=:projid AND subtask_id=:subtask_id");
            $result  = $sql->execute(array(":complete" => $complete, ":milestone_id" => $milestone_id, ":projid" => $projid, ":subtask_id" => $subtask_id,));
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
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
