<?php

include '../controller.php';
try {
    if (isset($_GET['get_sites'])) {
        $msg = false;
        $output_id = $_GET['output_id'];
        $mapping_type = $_GET['mapping_type'];
        $sites = isset($_GET['sites']) ? explode(",", $_GET['sites']) : [];
        $options = '<option value="">..Select State Type..</option>';
        if ($mapping_type == 1) {
            $options = '<option value="">..Select Site Type..</option>';
            $query_Output = $db->prepare("SELECT * FROM tbl_project_sites s INNER JOIN tbl_output_disaggregation d ON s.site_id = d.output_site WHERE d.outputid=:output_id ");
            $query_Output->execute(array(":output_id" => $output_id));
            $total_Output = $query_Output->rowCount();
            $msg = true;
            while ($row_rsOutput = $query_Output->fetch()) {
                $site = $row_rsOutput['site'];
                $id = $row_rsOutput['site_id'];
                $selected = in_array($id, $sites) ? 'selected' : '';
                $options .= '<option value="' . $id . '"  ' . $selected . '>' . $site . '</option>';
            }
        } else {
            $query_Output = $db->prepare("SELECT s.id, s.state FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id");
            $query_Output->execute(array(":output_id" => $output_id));
            $total_Output = $query_Output->rowCount();
            if ($total_Output > 0) {
                $msg = true;
                while ($row_rsOutput = $query_Output->fetch()) {
                    $id = $row_rsOutput['id'];
                    $state = $row_rsOutput['state'];
                    $selected = in_array($id, $sites) ? 'selected' : '';
                    $options .= '<option value="' . $id . '" ' . $selected . '>' . $state . '</option>';
                }
            }
        }
        echo json_encode(array('options' => $options, 'success' => $msg));
    }

    if (isset($_POST['store_data'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $current_date = date("Y-m-d");
            $projid = $_POST['projid'];
            $outputid = $_POST['output_id'];
            $mapping_type = $_POST['mapping_type'];
            $username = $_POST['user_name'];
            $store_data = $_POST['store_data'];

            if ($store_data == "add_milestones") {
                $milestones = $_POST['milestone'];
                $counters = isset($_POST['counter']) ? $_POST['counter'] : "";
                $total_milestones = count($milestones);
                for ($i = 0; $i < $total_milestones; $i++) {
                    $milestone = $milestones[$i];
                    $counter = $counters != "" ? $counters[$i] : 0;
                    $location = isset($_POST['sites' . $counter]) ? implode(",", $_POST['sites' . $counter])  : "";
                    $sql = $db->prepare("INSERT INTO tbl_milestone (projid,outputid,milestone,location,user_name,date_entered) VALUES (:projid,:outputid,:milestone,:location, :user_name,:date_entered)");
                    $results = $sql->execute(array(':projid' => $projid, ":outputid" => $outputid, ':milestone' => $milestone, ':location' => $location, ':user_name' => $username, ':date_entered' => $current_date));
                }
            } else if ($store_data == "tasks") {
                $tasks = $_POST['task'];
                $msid = $_POST['milestone_id'];
                $unit_of_measures = $_POST['unit_of_measure'];
                $total_task = count($tasks);
                $data_set = [];
                for ($i = 0; $i < $total_task; $i++) {
                    $task = $tasks[$i];
                    $unit_of_measure = $unit_of_measures[$i];
                    $sql = $db->prepare("INSERT INTO tbl_task (projid,outputid,msid,task,unit_of_measure,user_name,date_entered) VALUES (:projid,:outputid,:msid,:task,:unit_of_measure,:user_name,:date_entered)");
                    $results = $sql->execute(array(':projid' => $projid, ":outputid" => $outputid, ":msid" => $msid, ':task' => $task, ':unit_of_measure' => $unit_of_measure, ':user_name' => $username, ':date_entered' => $current_date));
                    $data_set[] =  $db->lastInsertId();
                }
            } else if ($store_data == "edit_milestone") {
                $msid = $_POST['milestone_id'];
                $milestone = $_POST['milestone'];
                $location = isset($_POST['m_sites']) ? implode(",", $_POST['m_sites'])  : "";
                $sql = $db->prepare("UPDATE tbl_milestone SET  milestone=:milestone,location=:location,changedby=:user_name,datechanged=:date_entered WHERE msid=:msid ");
                $results = $sql->execute(array(':milestone' => $milestone, ':location' => $location, ':user_name' => $username, ':date_entered' => $current_date, ":msid" => $msid));
            } else if ($store_data == "edit_tasks") {
                $task_id = $_POST['task_id'];
                $task = $_POST['task'];
                $unit_of_measure = $_POST['unit_of_measure'];
                $sql = $db->prepare("UPDATE tbl_task SET task=:task,unit_of_measure=:unit_of_measure WHERE tkid=:tkid ");
                $results = $sql->execute(array(":task" => $task, ':unit_of_measure' => $unit_of_measure, ":tkid" => $task_id));
            }
        }
        echo json_encode(array("success" => $success, "message" => "Created successfully"));
    }

    if (isset($_GET['get_unit_of_measure'])) {
        $unit_of_measure = $_GET['unit_of_measure'];
        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
        $query_rsIndUnit->execute(array(":unit_id" => $unit_of_measure));
        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
        $unit_options = '<option value="">..Select Unit of Measure..</option>';
        if ($totalRows_rsIndUnit > 0) {
            while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
                $unit_id = $row_rsIndUnit['id'];
                $unit = $row_rsIndUnit['unit'];
                $selected = $unit_of_measure == $unit_id ? "selected" : '';
                $unit_options .= '<option value="' . $unit_id . '" ' . $selected . '>' . $unit . '</option>';
            }
        }
        echo json_encode(array('success' => true, 'options' => $unit_options));
    }

    if (isset($_POST['destroy_item'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $id = $_POST['id'];
            $table = $_POST['table'];
            if ($table == 1) {
                $sql = $db->prepare("DELETE FROM `tbl_milestone` WHERE msid=:msid");
                $results = $sql->execute(array(':msid' => $id));
            } else if ($table == 2) {
                $sql = $db->prepare("DELETE FROM `tbl_task` WHERE tkid=:task_id");
                $results = $sql->execute(array(':task_id' => $id));
            }
        }

        echo json_encode(array("success" => $success, "task_details" => "Deleted successfully"));
    }

    function get_outputs($projid, $outputid)
    {
        global $db;
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        $outputs = '';
        if ($total_Output > 0) {
            $outputs = '';
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    $selected = $output_id == $outputid ? "selected" : '';
                    $outputs .= '<option value="' . $output_id . '" ' . $selected . '>' . $output . '</option>';
                }
            }
        }
        return $outputs;
    }

    if (isset($_GET['get_outputs'])) {
        $projid = $_GET['projid'];
        $milestone_id = isset($_GET['milestone_id']) ? $_GET['milestone_id'] : 0;
        $outputs = '<option value="">Select Output from list</option>';
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        if ($total_Output > 0) {
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    $output_target = $row_rsOutput['total_target'];

                    $query_rsMilestone =  $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE output_id =:output_id AND milestone_id=:milestone_id");
                    $query_rsMilestone->execute(array(":output_id" => $output_id, ':milestone_id' => $milestone_id));
                    $totalRows_rsMilestone = $query_rsMilestone->rowCount();

                    if ($totalRows_rsMilestone == 0) {
                        $query_rsMl =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_milestone_outputs WHERE output_id =:output_id");
                        $query_rsMl->execute(array(":output_id" => $output_id));
                        $Rows_rsMl = $query_rsMl->fetch();
                        $totalRows_rsMl = $query_rsMl->rowCount();
                        $target_used = !is_null($Rows_rsMl['target'])  ? $Rows_rsMl['target'] : 0;
                        $max_target = $output_target - $target_used;
                        if ($max_target > 0) {
                            $outputs .= '<option value="' . $output_id . '">' . $output . '</option>';
                        }
                    }
                }
            }
        }
        echo json_encode(["outputs" => $outputs, "success" => true]);
    }

    if (isset($_GET['get_task_outputs'])) {
        $projid = $_GET['projid'];
        $milestone_id = isset($_GET['milestone_id']) ? $_GET['milestone_id'] : '';
        $milestone_id = isset($_GET['milestone_id']) ? $_GET['milestone_id'] : '';
        $outputs = '<option value="">Select Output from list</option>';
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        if ($total_Output > 0) {
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    $query_rsMilestone =  $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE output_id =:output_id AND milestone_id=:milestone_id");
                    $query_rsMilestone->execute(array(":output_id" => $output_id, ':milestone_id' => $milestone_id));
                    $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                    if ($totalRows_rsMilestone == 0) {
                        $outputs .= '<option value="' . $output_id . '">' . $output . '</option>';
                    }
                }
            }
        }
        echo json_encode(["outputs" => $outputs, "success" => true]);
    }

    if (isset($_GET['get_target'])) {
        $max_target = 0;
        $output_id = $_GET['output_id'];
        $projid = $_GET['projid'];
        $target = $_GET['target'];
        $milestone_id = $_GET['milestone_id'];
        $query_Output =  $db->prepare("SELECT total_target FROM tbl_project_details WHERE id =:output_id");
        $query_Output->execute(array(":output_id" => $output_id));
        $Rows_Output = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        if ($total_Output > 0) {
            $output_target = $Rows_Output['total_target'];
            $query_rsMl =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_milestone_outputs WHERE output_id =:output_id ");
            $query_rsMl->execute(array(":output_id" => $output_id));
            $Rows_rsMl = $query_rsMl->fetch();
            $totalRows_rsMl = $query_rsMl->rowCount();
            $target_used = !is_null($Rows_rsMl['target'])  ? $Rows_rsMl['target'] : 0;
            $max_target = $output_target - $target_used;
        }
        echo json_encode(array('success' => true, 'target' => $max_target + $target));
    }

    if (isset($_GET['get_miletone_edit_details'])) {
        $milestone_id = $_GET['milestone_id'];
        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE id=:milestone_id");
        $query_rsMilestone->execute(array(":milestone_id" => $milestone_id));
        $row_rsMilestone = $query_rsMilestone->fetch();
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $Rows_rsOutput = [];
        if ($totalRows_rsMilestone) {
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id");
            $query_rsOutput->execute(array(":milestone_id" => $milestone_id));
            $totalRows_rsOutput = $query_rsOutput->rowCount();
            $Rows_rsOutput = $query_rsOutput->fetchAll();
        }

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $row_rsMilestone['projid']));
        $row_rsOutput = $query_Output->fetchAll();
        echo json_encode(['success' => true, 'milestone' => $row_rsMilestone, 'outputs' => $Rows_rsOutput, "project_outputs" => $row_rsOutput]);
    }

    if (isset($_POST['store_output_data'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;

            $store_data = $_POST['store_output_data'];
            $milestone = $_POST['milestone_name'];
            $projid =  $_POST['projid'];
            $milestone_type = $_POST['milestone_type'];
            $current_date = date("Y-m-d");

            $milestone_id = '';
            if ($store_data == "new") {
                $sql = $db->prepare("INSERT INTO tbl_project_milestone (projid,milestone,milestone_type,created_by,created_at) VALUES (:projid,:milestone,:milestone_type,:user_name,:date_entered)");
                $results = $sql->execute(array(':projid' => $projid, ':milestone' => $milestone, ':milestone_type' => $milestone_type, ':user_name' => $user_name, ':date_entered' => $current_date));
                $milestone_id = $db->lastInsertId();
            } else {
                $milestone_id = $_POST['milestone_id'];

                $sql = $db->prepare("UPDATE tbl_project_milestone SET  milestone=:milestone,milestone_type=:milestone_type,updated_by=:user_name,updated_at=:date_entered WHERE id=:milestone_id ");
                $results = $sql->execute(array(':milestone' => $milestone, ':milestone_type' => $milestone_type, ':user_name' => $user_name, ':date_entered' => $current_date, ":milestone_id" => $milestone_id));


                $sql = $db->prepare("DELETE FROM `tbl_project_milestone_outputs` WHERE milestone_id=:milestone_id");
                $results = $sql->execute(array(':milestone_id' => $milestone_id));

                $sql = $db->prepare("DELETE FROM `tbl_milestone_output_subtasks` WHERE milestone_id=:milestone_id ");
                $results = $sql->execute(array(':milestone_id' => $milestone_id));
            }

            if ($milestone_type == 1) {
                $outputx = $_POST['outputx'];
                $total_outputs = count($outputx);
                for ($i = 0; $i < $total_outputs; $i++) {
                    $output_id = $outputx[$i];
                    $sql = $db->prepare("INSERT INTO tbl_project_milestone_outputs (projid,milestone_id,output_id,created_by,created_at) VALUES (:projid,:milestone_id,:output_id,:user_name,:date_entered)");
                    $results = $sql->execute(array(':projid' => $projid, ':milestone_id' => $milestone_id, ":output_id" => $output_id, ':user_name' => $user_name, ':date_entered' => $current_date));
                }
            } else {
                $outputs = $_POST['output'];
                $total_outputs = count($outputs);
                for ($i = 0; $i < $total_outputs; $i++) {
                    $output_id = $outputs[$i];
                    $target = $_POST['target'][$i];
                    $sql = $db->prepare("INSERT INTO tbl_project_milestone_outputs (projid,milestone_id,output_id,target,created_by,created_at) VALUES (:projid,:milestone_id,:output_id,:target,:user_name,:date_entered)");
                    $results = $sql->execute(array(':projid' => $projid, ':milestone_id' => $milestone_id, ":output_id" => $output_id, ":target" => $target, ':user_name' => $user_name, ':date_entered' => $current_date));

                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                    $query_rsTasks->execute(array(":output_id" => $output_id));
                    $totalRows_rsTasks = $query_rsTasks->rowCount();
                    if ($totalRows_rsTasks > 0) {
                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                            $task_id = $row_rsTasks['msid'];
                            $subtask_id = $row_rsTasks['tkid'];
                            $sql = $db->prepare("INSERT INTO tbl_milestone_output_subtasks (projid,output_id,milestone_id,task_id,subtask_id) VALUES (:projid,:output_id,:milestone_id,:task_id,:subtask_id)");
                            $results = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id, ':milestone_id' => $milestone_id,  ":task_id" => $task_id, ':subtask_id' => $subtask_id));
                        }
                    }
                }
            }
        }
        echo json_encode(array('success' => $success));
    }

    if (isset($_POST['store_output_data_mile_d'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $store_output_data = $_POST['store_output_data_mile_d'];
            $output_id = $_POST['output'];
            $milestone_id = $_POST['milestone_id'];
            $projid =  $_POST['projid'];
            $current_date = date("Y-m-d");
            $target = $_POST['target'];
            if ($store_output_data == "new") {
                $sql = $db->prepare("INSERT INTO tbl_project_milestone_outputs (projid,milestone_id,output_id,target,created_by,created_at) VALUES (:projid,:milestone_id,:output_id,:target,:user_name,:date_entered)");
                $results = $sql->execute(array(':projid' => $projid, ':milestone_id' => $milestone_id, ":output_id" => $output_id, ":target" => $target, ':user_name' => $user_name, ':date_entered' => $current_date));
            } else {
                $sql = $db->prepare("UPDATE tbl_project_milestone_outputs SET target=:target WHERE projid=:projid AND milestone_id=:milestone_id AND output_id=:output_id");
                $results = $sql->execute(array(":target" => $target, ':projid' => $projid, ':milestone_id' => $milestone_id, ":output_id" => $output_id));
            }
        }
        echo json_encode(array('success' => $success));
    }

    if (isset($_POST['destroy_milestone'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $id = $_POST['id'];
            $sql = $db->prepare("DELETE FROM `tbl_milestone` WHERE msid=:msid");
            $results = $sql->execute(array(':msid' => $id));
        }
        echo json_encode(array("success" => $success, "milestone" => "Deleted successfully"));
    }

    if (isset($_POST['delete_milestone'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $milestone_id = $_POST['milestone_id'];
            $sql = $db->prepare("DELETE FROM `tbl_project_milestone` WHERE id=:milestone_id");
            $results = $sql->execute(array(':milestone_id' => $milestone_id));

            $sql = $db->prepare("DELETE FROM `tbl_project_milestone_outputs` WHERE milestone_id=:milestone_id");
            $results = $sql->execute(array(':milestone_id' => $milestone_id));

            $sql = $db->prepare("DELETE FROM `tbl_milestone_output_subtasks` WHERE milestone_id=:milestone_id ");
            $results = $sql->execute(array(':milestone_id' => $milestone_id));
        }
        echo json_encode(array("success" => $success, "milestone" => "Deleted successfully"));
    }

    if (isset($_POST['delete_milestone_output'])) {
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $milestone_id = $_POST['milestone_id'];
            $output_id = $_POST['output_id'];
            $sql = $db->prepare("DELETE FROM `tbl_project_milestone_outputs` WHERE milestone_id=:milestone_id AND output_id=:output_id");
            $results = $sql->execute(array(':milestone_id' => $milestone_id, ":output_id" => $output_id));

            $sql = $db->prepare("DELETE FROM `tbl_milestone_output_subtasks` WHERE milestone_id=:milestone_id AND output_id=:output_id ");
            $results = $sql->execute(array(':milestone_id' => $milestone_id, ":output_id" => $output_id));
        }
        echo json_encode(array("success" => $success, "milestone" => "Deleted successfully"));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
