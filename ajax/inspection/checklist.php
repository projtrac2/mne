<?php
try {
    include '../controller.php';
    if (isset($_GET['get_edit_questions'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
        $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
        $totalRows_rsQuestions = $query_rsQuestions->rowCount();
        $questions = '';
        if ($totalRows_rsQuestions > 0) {
            $counter = 0;
            while ($row = $query_rsQuestions->fetch()) {
                $counter++;
                $question = $row['question'];
                $questions .= '
                <tr id="cht' . $counter . '">
                    <td>' . $counter . '</td>
                    <td>
                        <input type="text" name="question[]" id="questionrow' . $counter . '" value="' . $question . '" placeholder="Enter Question" class="form-control" required />
                    </td>
                    <td>';
                if ($counter != 1) {
                    $questions .= '
                    <button type="button" class="btn btn-danger btn-sm"  onclick=delete_checklist("cht' . $counter . '")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>';
                }
                $questions .= '
                    </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => true, "questions" => $questions));
    }


    if (isset($_POST['add_questions'])) {
        $projid = $_POST['projid'];
        $datecreated = date("Y-m-d");
        $questions = $_POST['question'];
        $total_questions = count($questions);
        $output_id = $_POST['output_id'];
        $sql = $db->prepare("DELETE FROM `tbl_inspection_checklist_questions` WHERE projid=:projid AND output_id=:output_id");
        $result = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id));
        for ($i = 0; $i < $total_questions; $i++) {
            $question = $questions[$i];
            $sql = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (projid,output_id,question,created_by,created_at) VALUES (:projid,:output_id,:question,:created_by,:created_at)");
            $result = $sql->execute(array(':projid' => $projid, ':output_id' => $output_id, ':question' => $question, ':created_by' => $user_name, ':created_at' => $datecreated));
        }

        echo json_encode(array("success" => true));
    }


    if (isset($_POST['update_substage'])) {
        $projid = $_POST['projid'];
        $substage_id = $_POST['substage_id'];
        $sql = $db->prepare("UPDATE tbl_projects SET proj_substage=:proj_substage WHERE projid=:projid");
        $result  = $sql->execute(array(":proj_substage" => $substage_id, ":projid" => $projid));
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
