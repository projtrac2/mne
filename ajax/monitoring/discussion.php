<?php
include '../controller.php';
try {
    if (isset($_POST['store_topic'])) {
        $results = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $projid = $_POST['projid'];
            $topic = $_POST['subject'];
            $sql = $db->prepare("INSERT INTO tbl_project_discussion_topics (projid,topic,created_by) VALUES(:projid,:topic,:created_by)");
            $results = $sql->execute(array(":projid" => $projid, ":topic" => $topic, ":created_by" => $user_name));
        }
        echo json_encode(array("success" => $results));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
