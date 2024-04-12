<?php
include '../controller.php';

$projid = $_POST['projid'];
$output_id = $_POST['output_id'];
$site_id = $_POST['site_id'];
$task_id = $_POST['task_id'];
$subtask_id = $_POST['subtask_id'];
// $frequency = $_POST['frequency'];
$frequency = 2;
$target = $_POST['yearlyTargets'];
$achieved = $_POST['yearlyAchieved'];
$year = $_POST['year'];
$date = date('Y-m-d');
$recordExists = false;

try {

    for ($i = 0; $i < count($target); $i++) {
        $stmt = $db->prepare('SELECT * FROM tbl_project_target_breakdown WHERE task_id = :task_id AND subtask_id = :subtask_id AND site_id = :site_id AND subtask_year = :subtask_year');
        $stmt->bindParam(':task_id', $task_id);
        $stmt->bindParam(':subtask_id', $subtask_id);
        $stmt->bindParam(':site_id', $site_id);
        $stmt->bindParam(':subtask_year', $year[$i]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $recordExists = true;
        if ($result) {
            if ($target[$i]) {
                $update_stmt = $db->prepare("UPDATE tbl_project_target_breakdown SET subtask_target = :subtask_target, subtask_achieved = :subtask_achieved WHERE task_id = :task_id AND subtask_id = :subtask_id AND site_id = :site_id AND subtask_year = :subtask_year");
                $update_stmt->bindParam(':task_id', $task_id);
                $update_stmt->bindParam(':subtask_id', $subtask_id);
                $update_stmt->bindParam(':site_id', $site_id);
                $update_stmt->bindParam(':subtask_year', $year[$i]);
                $update_stmt->bindParam(':subtask_target', $target[$i]);
                $update_stmt->bindParam(':subtask_achieved', $achieved[$i]);
                $update_stmt->execute();
            }
        } else {
            
            if ($target[$i]) {
                // add transaction
                $m_null = NULL;
                $save_query = $db->prepare("INSERT INTO tbl_project_target_breakdown (projid, output_id, site_id, task_id, subtask_id, frequency, subtask_target, subtask_year, created_by, created_at) VALUES (:projid, :output_id, :site_id, :task_id, :subtask_id, :frequency, :subtask_target, :subtask_year, :created_by, :created_at)");
                $save_query->bindParam(':projid', $projid);
                $save_query->bindParam(':output_id', $output_id);
                $save_query->bindParam(':site_id', $site_id);
                $save_query->bindParam(':task_id', $task_id);
                $save_query->bindParam(':subtask_id', $subtask_id);
                $save_query->bindParam(':frequency', $frequency);
                $save_query->bindParam(':subtask_target', $target[$i]);
               
                $save_query->bindParam(':subtask_year', $year[$i], PDO::PARAM_STR);
                $save_query->bindParam(':created_by', $frequency);
                $save_query->bindParam(':created_at', $date);
                $save_query->execute();
            }
        }
    }

    echo json_encode(1);
} catch (\PDOException $th) {
    echo $th->getMessage();
}
