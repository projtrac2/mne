<?php
//Include database configuration file



include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

// get outputs
if (isset($_POST['get_output'])) {
    $projid = $_POST['projid'];
    $query_rsOutputs = $db->prepare("SELECT g.output, d.id FROM `tbl_projects` p INNER JOIN tbl_project_details d ON d.projid = p.projid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE p.projid = $projid");
    $query_rsOutputs->execute();
    $row_rsOutputs = $query_rsOutputs->fetch();
    $count_rsOutputs = $query_rsOutputs->rowCount();
    if ($count_rsOutputs > 0) {
        $outputs = '<option value="">.... Select Output ....</option>';
        do {
            $outputs .= '<option value="' . $row_rsOutputs['id'] . '">' . $row_rsOutputs['output'] . '</option>';
        } while ($row_rsOutputs = $query_rsOutputs->fetch());
        echo json_encode(array('success' => true, 'outputs' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'outputs' => ""));
    }
}

// get level 3
if (isset($_POST['get_level3'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $query_rsLevel3 = $db->prepare("SELECT s.id, s.state FROM `tbl_output_disaggregation` o INNER JOIN tbl_state s ON o.outputstate = s.id WHERE o.projid = $projid AND o.outputid = $outputid");
    $query_rsLevel3->execute();
    $row_rsLevel3 = $query_rsLevel3->fetch();
    $count_rsLevel3 = $query_rsLevel3->rowCount();

    if ($count_rsLevel3 > 0) {
        $level3 = '<option value="">.... Select Level 3 ....</option>';
        do {
            $level3 .= '<option value="' . $row_rsLevel3['id'] . '">' . $row_rsLevel3['state'] . '</option>';
        } while ($row_rsLevel3 = $query_rsLevel3->fetch());
        echo json_encode(array('success' => true, 'level3' => $level3));
    } else {
        echo json_encode(array('success' => false, 'level3' => ""));
    }
}

// get level 4
if (isset($_POST['get_level4'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $level3 = $_POST['level3'];
    $query_rsLevel4 = $db->prepare("SELECT d.id,d.disaggregations FROM `tbl_project_results_level_disaggregation` p INNER JOIN tbl_indicator_level3_disaggregations d ON d.id = p.name WHERE p.projid = $projid AND p.projoutputid = $outputid AND p.opstate = $level3");
    $query_rsLevel4->execute();
    $row_rsLevel4 = $query_rsLevel4->fetch();
    $count_rsLevel4 = $query_rsLevel4->rowCount();
    if ($count_rsLevel4 > 0) {
        $outputs = '<option value="">.... Select Output ....</option>';
        do {
            $outputs .= '<option value="' . $row_rsLevel4['id'] . '">' . $row_rsLevel4['disaggregations'] . '</option>';
        } while ($row_rsLevel4 = $query_rsLevel4->fetch());
        echo json_encode(array('success' => true, 'outputs' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'outputs' => ""));
    }
}

if (isset($_POST['getTasks'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $level3 = $_POST['level3'];
    $level4 = $_POST['level4'];
    $level4id = '';
    if ($level4 != 0) {
        $level4id = "AND level4 = $level4";
    }

    $query_rsTasks = $db->prepare("SELECT tkid, task FROM tbl_task where projid = $projid AND outputid =$outputid ");
    $query_rsTasks->execute();
    $row_rsTasks = $query_rsTasks->fetch();
    $count_rsTasks = $query_rsTasks->rowCount();


    if ($count_rsTasks) {
        $tasks = '';
        $nm = 0;
        do {
            $taskid = $row_rsTasks['tkid'];
            $task = $row_rsTasks['task'];
            $query_rsmonitoringTasks = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where score = 10 AND taskid = $taskid AND level3= $level3 $level4id");
            $query_rsmonitoringTasks->execute();
            $row_rsmonitoringTasks = $query_rsmonitoringTasks->fetch();
            $count_rsmonitoringTasks = $query_rsmonitoringTasks->rowCount();

            if ($count_rsmonitoringTasks > 0) {
                $query_rsInspectionTasks = $db->prepare("SELECT * FROM tbl_task_inspection_status where taskid = $taskid AND level3 = $level3 $level4id");
                $query_rsInspectionTasks->execute();
                $row_rsInspectionTasks = $query_rsInspectionTasks->fetch();
                $count_rsInspectionTasks = $query_rsInspectionTasks->rowCount();
                $nm++;
                if ($count_rsInspectionTasks == 0) {
                    $tasks .=
                        '<tr>
                            <td>' . $nm . '</td>
                            <td>' . $task . '</td>
                            <td>N/A</td>
                        </tr>';
                } else if ($row_rsInspectionTasks['status'] == 0) {
                    $tasks .=
                        '<tr>
                            <td>' . $nm . '</td>
                            <td>' . $task . '</td>
                            <td>Failed</td>
                        </tr>';
                }
            }
        } while ($row_rsTasks = $query_rsTasks->fetch());
        echo json_encode(array("success" => true, "tasks" => $tasks));
    }
}

if (isset($_POST['insepectform'])) {
    $uploadDir = '../../uploads/inspection/';
    $assignment_id = $_POST['formid'];
    $task_id = $_POST['task_id'];
    $question_id = $_POST['question_id'];
    $comments = $_POST['comments'];
    $attachment_path = "Test";
    $created_by = 3;
    $created_at = date('Y-m-d');
    $uploadStatus = 1;
    $uploadedFile = '';
    $attachment_path = '';
    if (!empty($_FILES["file"]["name"])) {

        // File path config 
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats 
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server 
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $uploadedFile = $fileName;
                $attachment_path = $targetFilePath;
            } else {
                $uploadStatus = 0;
                $response['message'] = 'Sorry, there was an error uploading your file.';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
        }
    }

    if ($uploadStatus == 1) {
        $insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_noncompliance_comments (assignment_id, task_id, question_id, comments,attachment_path,created_by,created_at) VALUES (:assignment_id, :task_id, :question_id, :comments,:attachment_path,:created_by,:created_at)");
        $result = $insertSQL->execute(array(":assignment_id" => $assignment_id, ":task_id" => $task_id, ":question_id" => $question_id, ":comments" => $comments, ":attachment_path" => $attachment_path, ":created_by" => $created_by, ":created_at" => $created_at));
        if ($result) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }
}

if (isset($_POST['dept'])) {
    $dept = $_POST['dept'];
    $query_rsIndicator = $db->prepare("SELECT indid, indicator_name FROM tbl_indicator WHERE indicator_sector = '$dept'");
    $query_rsIndicator->execute();
    $row_rsIndicator = $query_rsIndicator->fetch();
    $indcount = $query_rsIndicator->rowCount();
    if ($indcount > 0) {
        $outputs = '<option value="">.... Select Output Indicator....</option>';
        do {
            $outputs .= '<option value="' . $row_rsIndicator['indid'] . '">' . $row_rsIndicator['indicator_name'] . '</option>';
        } while ($row_rsIndicator = $query_rsIndicator->fetch());
        echo json_encode(array('success' => true, 'indicators' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'indicators' => ""));
    }
}
