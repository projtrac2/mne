<?php
try {
    include '../controller.php';

    function get_output_details($output_id)
    {
        global $db;
        $previous_record = $cummulative_record = $target = 0;
        $query_rsTarget = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsTarget->execute(array(":output_id" => $output_id));
        $totalRows_rsTarget = $query_rsTarget->rowCount();
        $Rows_rsOutput = $query_rsTarget->fetch();
        $target = $totalRows_rsTarget > 0 ? $Rows_rsOutput['total_target'] : 0;

        $query_rsCummulative = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id ");
        $query_rsCummulative->execute(array(":output_id" => $output_id));
        $Rows_rsCummulative = $query_rsCummulative->fetch();
        $cummulative_record = $Rows_rsCummulative['achieved'] != null ? $Rows_rsCummulative['achieved'] : 0;

        $query_rsPrevious = $db->prepare("SELECT achieved FROM tbl_monitoringoutput WHERE output_id=:output_id  ORDER BY moid DESC LIMIT 1");
        $query_rsPrevious->execute(array(":output_id" => $output_id));
        $Row_rsPrevious = $query_rsPrevious->fetch();
        $Rows_rsPrevious = $query_rsPrevious->rowCount();
        $previous_record = $Rows_rsPrevious > 0 ? $Row_rsPrevious['achieved'] : 0;

        $query_rsCompleted = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id=:output_id AND complete=0");
        $query_rsCompleted->execute(array(":output_id" => $output_id));
        $totalRows_rsCompleted = $query_rsCompleted->rowCount();
        $completed = $totalRows_rsCompleted > 0 ? 1 : 2;
        return array("output_target" => $target, "output_cummulative_record" => $cummulative_record, "output_previous_record" => $previous_record, "output_completed" => $completed);
    }

    function get_site_ward_details($site_id, $state_id, $output_id, $mapping_type)
    {
        global $db;
        $site_target = $site_cummulative_record = $site_previous_record =  $completed = 0;
        if ($mapping_type == 1) {
            $query_rsSite = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE output_site=:site_id AND outputid=:output_id");
            $query_rsSite->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
            $Rows_rsSite = $query_rsSite->fetch();
            $totalRows_rsSite = $query_rsSite->rowCount();
            $site_target =  ($totalRows_rsSite > 0) ? $Rows_rsSite['total_target'] : 0;

            $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE site_id=:site_id AND output_id=:output_id");
            $query_rsTargetUsed->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
            $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
            $site_cummulative_record = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;

            $query_rsPrevious = $db->prepare("SELECT achieved FROM tbl_monitoringoutput WHERE site_id=:site_id AND output_id=:output_id ORDER BY moid DESC LIMIT 1");
            $query_rsPrevious->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
            $Rows_rsPrevious = $query_rsPrevious->fetch();
            $site_previous_record = $Rows_rsPrevious ? $Rows_rsPrevious['achieved'] : 0;


            $query_rsCompleted = $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND output_id=:output_id AND complete=0");
            $query_rsCompleted->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
            $totalRows_rsCompleted = $query_rsCompleted->rowCount();
            $completed = $totalRows_rsCompleted > 0 ? 1 : 2;
        } else {
            $query_rsSite = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputstate=:state_id AND outputid=:output_id");
            $query_rsSite->execute(array(":state_id" => $state_id, ":output_id" => $output_id));
            $Rows_rsSite = $query_rsSite->fetch();
            $totalRows_rsSite = $query_rsSite->rowCount();
            $site_target =  ($totalRows_rsSite > 0) ? $Rows_rsSite['total_target'] : 0;

            $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE state_id=:state_id AND output_id=:output_id ");
            $query_rsTargetUsed->execute(array(":state_id" => $state_id, ":output_id" => $output_id));
            $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
            $site_cummulative_record = !is_null($Rows_rsTargetUsed['achieved'])  ? $Rows_rsTargetUsed['achieved'] : 0;

            $query_rsPrevious = $db->prepare("SELECT achieved FROM tbl_monitoringoutput WHERE state_id=:state_id AND output_id=:output_id  ORDER BY moid DESC LIMIT 1");
            $query_rsPrevious->execute(array(":state_id" => $state_id, ":output_id" => $output_id));
            $Rows_rsPrevious = $query_rsPrevious->fetch();
            $Row_rsPrevious = $query_rsPrevious->rowCount();
            $site_previous_record = $Row_rsPrevious > 0 ? $Rows_rsPrevious['achieved'] : 0;

            $query_rsCompleted = $db->prepare("SELECT * FROM tbl_program_of_works WHERE output_id=:output_id AND complete=0");
            $query_rsCompleted->execute(array(":output_id" => $output_id));
            $totalRows_rsCompleted = $query_rsCompleted->rowCount();
            $completed = $totalRows_rsCompleted > 0 ? 1 : 2;
        }

        return array("site_target" => $site_target, "site_cummulative_record" => $site_cummulative_record, "site_previous_record" => $site_previous_record, "site_completed" => $completed);
    }

    function get_milestone_details($output_id, $milestone_id)
    {
        global $db;
        $milestone_target = $milestone_cummulative_record = $milestone_previous_record = 0;
        $query_rsTarget = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id AND output_id=:output_id");
        $query_rsTarget->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
        $Rows_rsTarget = $query_rsTarget->fetch();
        $totalRows_rsTarget = $query_rsTarget->rowCount();
        $milestone_target = $totalRows_rsTarget > 0 ? $Rows_rsTarget['target'] : 0;

        $query_rsCummulative = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE  milestone_id=:milestone_id AND output_id=:output_id");
        $query_rsCummulative->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
        $Rows_rsCummulative = $query_rsCummulative->fetch();
        $milestone_cummulative_record = $Rows_rsCummulative['achieved'] != null ? $Rows_rsCummulative['achieved'] : 0;

        $query_rsPrevious = $db->prepare("SELECT achieved FROM tbl_monitoringoutput WHERE milestone_id=:milestone_id AND output_id=:output_id ORDER BY moid DESC LIMIT 1");
        $query_rsPrevious->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
        $Rows_rsPrevious = $query_rsPrevious->fetch();
        $milestone_previous_record = $Rows_rsPrevious ? $Rows_rsPrevious['achieved'] : 0;

        $query_rsCompleted = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE  milestone_id=:milestone_id AND complete=0");
        $query_rsCompleted->execute(array(":milestone_id" => $milestone_id));
        $totalRows_rsCompleted = $query_rsCompleted->rowCount();
        $completed = $totalRows_rsCompleted > 0 ? 1 : 2;
        return array("milestone_target" => $milestone_target, "milestone_cummulative_record" => $milestone_cummulative_record, "milestone_previous_record" => $milestone_previous_record, "milestone_completed" => $completed);
    }

    function previous_attachment($output_id, $milestone_id, $site_id, $state_id)
    {
        global $db;
        $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE opid=:output_id ");
        $query_project_videos->execute(array(":output_id" => $output_id));
        if ($site_id != '' && $state_id != '') {
            if ($site_id != 0) {
                $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE opid=:output_id");
                $query_project_videos->execute(array(":output_id" => $output_id));
                if ($milestone_id != '') {
                    $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE  opid=:output_id AND milestone_id=:milestone_id AND state_id=:state_id");
                    $query_project_videos->execute(array(":milestone_id" => $milestone_id, ":state_id" => $state_id));
                }
            } else {
                $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE opid=:output_id AND site_id=:site_id");
                $query_project_videos->execute(array(":output_id" => $output_id, ":site_id" => $site_id));
                if ($milestone_id != '') {
                    $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE  opid=:output_id AND milestone_id=:milestone_id AND site_id=:site_id ");
                    $query_project_videos->execute(array(":milestone_id" => $milestone_id, ":site_id" => $site_id));
                }
            }
        }

        $count_project_videos = $query_project_videos->rowCount();
        $data = '';
        if ($count_project_videos > 0) {
            $counter = 0;
            while ($rows_project_videos = $query_project_videos->fetch()) {
                $counter++;
                $projstageid = $rows_project_videos['projstage'];
                $filename = $rows_project_videos['filename'];
                $filepath = $rows_project_videos['floc'];
                $purpose = $rows_project_videos['reason'];

                $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                $query_project_stage->execute(array(":projstageid" => $projstageid));
                $rows_project_stage = $query_project_stage->fetch();
                $projstage = $rows_project_stage['stage'];

                $data .= '
                <tr>
                    <td width="5%">' . $counter . '</td>
                    <td width="35%">' . $filename . '</td>
                    <td width="35%">' . $purpose . '</td>
                    <td width="10%">' . $projstage . '</td>
                    <td width="15%">
                        <a href="' . $filepath . '" watch target="_balnk">Download</a>
                    </td>
                </tr>';
            }
        }

        return $data;
    }

    function previous_remarks($output_id, $milestone_id, $site_id, $state_id)
    {
        global $db;
        $observation_type = 2;
        $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE output_id=:output_id AND observation_type=:observation_type");
        $query_allObservation->execute(array(":output_id" => $output_id, ':observation_type' => $observation_type));

        if ($site_id != "" && $state_id != "") {
            if ($site_id != 0) {
                $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE output_id=:output_id AND observation_type=:observation_type AND state_id=:state_id");
                $query_allObservation->execute(array(":output_id" => $output_id, ':observation_type' => $observation_type, ":state_id" => $state_id));
                if ($milestone_id != '') {
                    $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE  output_id=:output_id AND milestone_id=:milestone_id AND state_id=:state_id AND observation_type=:observation_type");
                    $query_allObservation->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id, ":state_id" => $state_id, ':observation_type' => $observation_type));
                }
            } else {
                $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE output_id=:output_id AND site_id=:site_id AND observation_type=:observation_type");
                $query_allObservation->execute(array(":output_id" => $output_id, ":site_id" => $site_id, ':observation_type' => $observation_type));
                if ($milestone_id != '') {
                    $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE  output_id=:output_id AND milestone_id=:milestone_id AND site_id=:site_id AND observation_type=:observation_type");
                    $query_allObservation->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ':observation_type' => $observation_type));
                }
            }
        }

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
        return $comments_body;
    }

    if (isset($_GET['get_project_outputs'])) {
        $projid = $_GET['projid'];
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid AND complete <>1");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        $success = false;
        $outputs = '<option value="">.... Select Output ....</option>';
        if ($total_Output > 0) {
            $success = true;
            while ($row_rsOutput = $query_Output->fetch()) {
                $output_id = $row_rsOutput['id'];
                $target = $row_rsOutput['total_target'];
                $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
                $query_rsTargetUsed->execute(array(":output_id" => $output_id));
                $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                $output_achieved = !is_null($Rows_rsTargetUsed['achieved']) ? $Rows_rsTargetUsed['achieved'] : 0;
                if ($target != $output_achieved) {
                    $output = $row_rsOutput['indicator_name'];
                    $outputs .= '<option value="' . $output_id . '">' . $output . '</option>';
                }
            }
        }
        echo json_encode(array("success" => $success, "outputs" => $outputs));
    }

    if (isset($_GET['get_output_sites'])) {
        $output_id = $_GET['output_id'];
        $success = true;
        $sites = '';
        $mapping_type = 0;
        $output_target = 0;

        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $Rows_rsOutput = $query_rsOutput->fetch();
        if ($totalRows_rsOutput > 0) {
            $mapping_type = $Rows_rsOutput['indicator_mapping_type'];
            if ($mapping_type == 1) {
                $sites = '<option value="">... Select Site ...</option>';
                $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                $query_Output->execute(array(":output_id" => $output_id));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $site_id = $row_rsOutput['site_id'];
                        $site_name = $row_rsOutput['site'];
                        $target = $row_rsOutput['total_target'];

                        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) achieved FROM tbl_monitoringoutput WHERE site_id=:site_id AND output_id=:output_id");
                        $query_rsTargetUsed->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                        $site_achieved = !is_null($Rows_rsTargetUsed['achieved']) ? $Rows_rsTargetUsed['achieved'] : 0;
                        if ($target != $site_achieved) {
                            $sites .= '<option value="' . $site_id . '">' . $site_name . '</option>';
                        }
                    }
                }
            } else {
                $query_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                $query_Output->execute(array(":output_id" => $output_id));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    $sites = '<option value="">... Select ' . $level2label . ' ...</option>';
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $state = $row_rsOutput['state'];
                        $state_id = $row_rsOutput['outputstate'];
                        $target = $row_rsOutput['total_target'];

                        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) achieved FROM tbl_monitoringoutput WHERE state_id=:state_id AND output_id=:output_id");
                        $query_rsTargetUsed->execute(array(":state_id" => $state_id, ":output_id" => $output_id));
                        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                        $site_achieved = !is_null($Rows_rsTargetUsed['achieved']) ? $Rows_rsTargetUsed['achieved'] : 0;
                        if ($target != $site_achieved) {
                            $sites .= '<option value="' . $state_id . '">' . $state . '</option>';
                        }
                    }
                }
            }
        }


        $output_type = $mapping_type == 2 || $mapping_type == 0 ? 2 : 1;
        $files = previous_attachment($output_id, "", "", "");
        $comments = previous_remarks($output_id, "", "", "");
        echo json_encode(array("success" => $success, "sites" => $sites, 'output_type' => $output_type,  "output_details" => get_output_details($output_id), "comments" => $comments, "files" => $files));
    }

    if (isset($_GET['get_milestones'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $site_id = $_GET['site_id'];

        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid ");
        $query_rsMilestone->execute(array(":projid" => $projid));
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $milestones = ' <option value="">.... Select Milestone ....</option>';

        if ($totalRows_rsMilestone > 0) {
            while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                $milestone_name = $row_rsMilestone['milestone'];
                $milestone_id = $row_rsMilestone['id'];
                $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id AND output_id=:output_id");
                $query_rsOutput->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
                $totalRows_rsOutput = $query_rsOutput->rowCount();
                $Rows_rsOutput = $query_rsOutput->fetch();
                if ($totalRows_rsOutput > 0) {
                    $target = $Rows_rsOutput['target'];
                    $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE milestone_id=:milestone_id AND output_id=:output_id");
                    $query_rsTargetUsed->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
                    $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                    $milestone_achieved = !is_null($Rows_rsTargetUsed['achieved']) ? $Rows_rsTargetUsed['achieved'] : 0;
                    if ($target != $milestone_achieved) {
                        $milestones .= '<option value="' . $milestone_id . '">' . $milestone_name . '</option>';
                    }
                }
            }
        }

        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $Rows_rsOutput = $query_rsOutput->fetch();
        $mapping_type = ($totalRows_rsOutput > 0) ? $Rows_rsOutput['indicator_mapping_type'] : 0;
        $site_id =  $mapping_type == 1 ? $site_id : 0;
        $state_id =  $mapping_type == 1  ? 0 : $_GET['site_id'];

        $files = previous_attachment($output_id, $milestone_id, $site_id, $state_id);
        $comments = previous_remarks($output_id, $milestone_id, $site_id, $state_id);
        $output_details = get_output_details($output_id);
        $site_details = get_site_ward_details($site_id, $state_id, $output_id, $mapping_type);
        echo json_encode(array("success" => true, "milestones" => $milestones, 'output_details' => $output_details, "site_details" => $site_details, "comments" => $comments, "files" => $files));
    }

    if (isset($_GET['get_output_details'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $site_id = $_GET['site_id'];
        $milestone_id = $_GET['milestone_id'];

        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $mapping_type =  ($totalRows_rsOutput > 0) ? $Rows_rsOutput['indicator_mapping_type'] : 0;

        $site_id =  $mapping_type == 1  ? $site_id : 0;
        $state_id =  $mapping_type == 1  ? 0 : $_GET['site_id'];

        $files = previous_attachment($output_id, $milestone_id, $site_id, $state_id);
        $comments = previous_remarks($output_id, $milestone_id, $site_id, $state_id);

        echo json_encode(array("success" => true, 'output_details' => get_output_details($output_id), "site_details" => get_site_ward_details($site_id, $state_id, $output_id, $mapping_type), "milestone_details" => get_milestone_details($output_id, $milestone_id), "comments" => $comments, "files" => $files));
    }

    if (isset($_POST['store_outputs'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output'];
        $milestone_id = isset($_POST['milestone']) && $_POST['milestone'] != '' ? $_POST['milestone'] : 0;
        $site_id = $_POST['site'];
        $achieved = $_POST['current_measure'];
        $complete = $_POST['button'];
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");

        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();


        $total_target = 0;
        if ($totalRows_rsOutput > 0) {
            $mapping_type = $Rows_rsOutput['indicator_mapping_type'];
            $total_target = $Rows_rsOutput['total_target'];
            $state_id = $mapping_type == 1 ? 0 : $site_id;
            $site_id = $mapping_type == 1  ? $site_id : 0;
        }

        $sql = $db->prepare("INSERT INTO tbl_monitoringoutput (projid,output_id,milestone_id,site_id,state_id,form_id,achieved,created_by,date_created) VALUES(:projid,:output_id,:milestone_id,:site_id,:state_id,:formid,:achieved,:created_by,:created_at)");
        $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":state_id" => $state_id, ":formid" => $formid, ":achieved" => $achieved, ":created_by" => $user_name, ":created_at" => $created_at));

        if ($results) {
            if (isset($_POST['comments'])) {
                $observ = $_POST['comments'];
                $observation_type = 2;
                $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid,output_id,milestone_id,state_id,site_id,task_id,subtask_id,formid,observation,observation_type,created_at,created_by) VALUES (:projid,:output_id,:milestone_id,:state_id,:site_id,:task_id,:subtask_id,:formid,:observation,:observation_type,:created_at,:created_by)");
                $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":state_id" => $state_id, ":site_id" => $site_id, ":task_id" => 0, ":subtask_id" => 0, ':formid' => $formid, ':observation' => $observ, ':observation_type' => $observation_type, ':created_at' => $currentdate, ':created_by' => $user_name));
            }

            if (isset($_POST["attachmentpurpose"])) {
                $filecategory = "monitoring checklist";
                $stage = 10;
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
                                        $result =  $qry2->execute(array(':projid' => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => 0, ':subtask_id' => 0, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $query_rsCummulative = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
        $query_rsCummulative->execute(array(":output_id" => $output_id));
        $Rows_rsCummulative = $query_rsCummulative->fetch();
        $cummulative_record = $Rows_rsCummulative['achieved'] != null ? $Rows_rsCummulative['achieved'] : 0;

        if ($complete == 1 || $cummulative_record == $total_target) {
            $sql = $db->prepare("UPDATE tbl_project_details SET complete=1, status=5 WHERE  projid=:projid AND id=:output_id");
            $result  = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id));
        }
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
