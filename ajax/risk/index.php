<?php
include '../controller.php';
try {
    if (isset($_POST['get_severity'])) {
        $impact = $_POST['impact'];
        $probability = $_POST['probability'];
		$severity = $impact * $probability;
        $severityclass = '';
		
        $query_severity = $db->prepare("SELECT * FROM tbl_risk_severity WHERE digit = :severity");
        $query_severity->execute(array(":severity" => $severity));
		$row_severity = $query_severity->fetch();
        $total_severity = $query_severity->rowCount();
		
        if ($total_severity > 0) {
            $severityclass = $row_severity['color']; 
			$severitydesc = $row_severity['description'];
        }
        echo json_encode(["severityclass" => $severityclass, "severityvalue" => $severity, "severitydesc" => $severitydesc]);
    }

    if (isset($_POST['store_data'])) {
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
        echo json_encode(array("success" => true, "message" => "Created successfully"));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
