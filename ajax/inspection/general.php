<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include '../controller.php';
try {





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

   function update_project($projid, $substage_id)
   {
      global $db;
      $sql = $db->prepare("UPDATE tbl_projects SET proj_substage=:proj_substage WHERE projid=:projid");
      $result  = $sql->execute(array(":proj_substage" => $substage_id, ":projid" => $projid));
      return $result;
   }
   if (isset($_POST['update_substage'])) {
      $projid = $_POST['projid'];
      $substage_id = $_POST['substage_id'];
      update_project($projid, $substage_id);
      echo json_encode(array("success" => true));
   }




   // answer_question
   if (isset($_POST['answer_question'])) {
      $projid = $_POST['projid'];
      $output_id = $_POST['output_id'];
      $site_id = $_POST['site_id'];
      $datecreated = date("Y-m-d");
      $question_id = $_POST['question_id'];
      $answer = $_POST['question'];

      $comment = $_POST['comments'] != '' ? $_POST['comments'] : '';
      $sql = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE site_id=:site_id AND `output_id`=:output_id AND question_id=:question_id");
      $sql->execute(array(":site_id" => $site_id, "output_id" => $output_id, ':question_id' => $question_id));
      $total_rows = $sql->rowCount();
      $rows = $sql->fetch();

      if ($total_rows > 0) {
         $checklist_id = $rows['id'];
         $sql = $db->prepare("UPDATE tbl_inspection_checklist SET answer=:answer,updated_by=:updated_by, updated_at=:updated_at WHERE question_id=:question_id ");
         $result = $sql->execute(array(':answer' => $answer, ':updated_by' => $user_name, ':updated_at' => $datecreated, ":question_id" => $question_id));

         $checklist_delete = $db->prepare('DELETE FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id  AND question_id=:question_id  AND checklist_id=:checklist_id');
         $result_checklist = $checklist_delete->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':checklist_id' => $checklist_id]);

         if ($comment != '') {
            $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist_comments (projid, site_id, output_id, question_id, checklist_id, comment, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :checklist_id, :comment, :created_by, :created_at)');
            $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, 'question_id' => $question_id, ':checklist_id' => $checklist_id, ':comment' => $comment, ":created_by" => $user_name, ":created_at" => $datecreated]);
         }

      } else {
         $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist (projid, site_id, output_id, question_id,answer, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :answer, :created_by, :created_at )');
         $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':answer' => $answer, ':created_by' => $user_name, 'created_at' => $datecreated]);
         $checklist_id = $db->lastInsertId();

         if ($comment != '') {
            $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist_comments (projid, site_id, output_id, question_id, checklist_id, comment, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :checklist_id, :comment, :created_by, :created_at)');
            $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, ':question_id' => $question_id, ':checklist_id' => $checklist_id, ':comment' => $comment, ":created_by" => $user_name, ":created_at" => $datecreated]);
         }
      }

      echo json_encode(array("success" => true));
   }


} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
   echo $ex->getMessage();
}
