<?php
try {
    include '../controller.php';
    if (isset($_POST['answer_question'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $site_id = $_POST['site_id'];
        $datecreated = date("Y-m-d");
        $question_id = $_POST['question_id'];
        $answer = $_POST['question'];
        $issue_id = $_POST['issue_id'];

        $comment = $_POST['comments'] != '' ? $_POST['comments'] : '';
        $sql = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND parent_issue_id=:parent_issue_id");
        $sql->execute(array(":site_id" => $site_id, "output_id" => $output_id, ':question_id' => $question_id, ":parent_issue_id" => $issue_id));
        $total_rows = $sql->rowCount();
        $rows = $sql->fetch();

        if ($total_rows > 0) {
            $checklist_id = $rows['id'];
            $sql = $db->prepare("UPDATE tbl_inspection_checklist SET answer=:answer,updated_by=:updated_by, updated_at=:updated_at WHERE question_id=:question_id AND parent_issue_id=:parent_issue_id ");
            $result = $sql->execute(array(':answer' => $answer, ':updated_by' => $user_name, ':updated_at' => $datecreated, ":question_id" => $question_id, ":parent_issue_id" => $issue_id));

            $checklist_delete = $db->prepare('DELETE FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id  AND question_id=:question_id  AND checklist_id=:checklist_id');
            $result_checklist = $checklist_delete->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':checklist_id' => $checklist_id]);

            if ($comment != '') {
                $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist_comments (projid, site_id, output_id, question_id, checklist_id, comment, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :checklist_id, :comment, :created_by, :created_at)');
                $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, 'question_id' => $question_id, ':checklist_id' => $checklist_id, ':comment' => $comment, ":created_by" => $user_name, ":created_at" => $datecreated]);
            }
        } else {
            $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist (projid, site_id, output_id, question_id,parent_issue_id,answer, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id,:parent_issue_id, :answer, :created_by, :created_at )');
            $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':parent_issue_id' => $issue_id, ':answer' => $answer, ':created_by' => $user_name, 'created_at' => $datecreated]);
            $checklist_id = $db->lastInsertId();

            if ($comment != '') {
                $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist_comments (projid, site_id, output_id, question_id, checklist_id, comment, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :checklist_id, :comment, :created_by, :created_at)');
                $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':checklist_id' => $checklist_id, ':comment' => $comment, ":created_by" => $user_name, ":created_at" => $datecreated]);
            }
        }

        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
