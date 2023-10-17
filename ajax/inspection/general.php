<?php

include '../controller.php';
try {

   function get_members($responsible)
   {
      global $db;
      $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title ORDER BY ptid ASC");
      $query_rsPMbrs->execute();
      $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
      $input = '<option value="">Select Member from List</option>';
      if ($count_row_rsPMbrs > 0) {
         while ($row_rsPMbrs = $query_rsPMbrs->fetch()) {
            $fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
            $user_id = $row_rsPMbrs['userid'];
            $selected = $user_id == $responsible ? 'selected' : '';
            $input .= '<option value="' . $user_id . '" ' . $selected . ' >' . $fullname . '</option>';
         }
      }

      return $input;
   }

   if (isset($_GET['get_edit_members'])) {
      $projid = $_GET['projid'];
      $query_rsTeamMembers = $db->prepare("SELECT * FROM tbl_projmembers WHERE team_type=5 AND projid=:projid");
      $query_rsTeamMembers->execute(array(":projid" => $projid));
      $totalRows_rsTeamMembers = $query_rsTeamMembers->rowCount();

      $members = '';
      if ($totalRows_rsTeamMembers > 0) {
         $counter = 0;
         while ($row = $query_rsTeamMembers->fetch()) {
            $counter++;
            $member_id = $row['responsible'];
            $member = get_members($member_id);
            $members .= '
            <tr id="mtng' . $counter . '">
                  <td>' . $counter . '</td>
                  <td>
                     <select name="member[]" id="memberrow' . $counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                        ' . $member . '
                     </select>
                  </td>
                  <td>';

            if ($counter != 1) {
               $members .= '
               <button type="button" class="btn btn-danger btn-sm"  onclick=delete_member("mtng' . $counter . '")>
                  <span class="glyphicon glyphicon-minus"></span>
               </button>';
            }
            $members .= '
                  </td>
            </tr>';
         }
      }
      echo json_encode(array("success" => true, "members" => $members));
   }


   if (isset($_GET['get_edit_questions'])) {
      $projid = $_GET['projid'];
      $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid");
      $query_rsQuestions->execute(array(":projid" => $projid));
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

      $sql = $db->prepare("DELETE FROM `tbl_inspection_checklist_questions` WHERE projid=:projid");
      $result = $sql->execute(array(':projid' => $projid));
      for ($i = 0; $i < $total_questions; $i++) {
         $question = $questions[$i];
         $sql = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (projid,question,created_by,created_at) VALUES (:projid,:question,:created_by,:created_at)");
         $result = $sql->execute(array(':projid' => $projid, ':question' => $question, ':created_by' => $user_name, ':created_at' => $datecreated));
      }
      echo json_encode(array("success" => true));
   }

   // answer_question
   if (isset($_POST['answer_question'])) {
      $projid = $_POST['projid'];
      $datecreated = date("Y-m-d");
      $question_id = $_POST['question_id'];
      $answer = $_POST['question'];
      $comment = $_POST['comments'] !='' ? $_POST['comments'] : null;
      $sql = $db->prepare("UPDATE tbl_inspection_checklist_questions SET answer=:answer, comment=:comment,updated_by=:created_by, updated_at=:updated_at WHERE id=:question_id ");
      $result = $sql->execute(array(':answer' => $answer, ':comment' => $comment, ':created_by' => $user_name, ':updated_at' => $datecreated, ":question_id" => $question_id));
      echo json_encode(array("success" => $result));
   }

   if (isset($_POST['assign_responsible'])) {
      $projid = $_POST['projid'];
      $datecreated = date("Y-m-d");
      $members = $_POST['member'];
      $total_members = count($members);

      $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 5");
      $result = $sql->execute(array(':projid' => $projid, ":stage" => 10));
      for ($i = 0; $i < $total_members; $i++) {
         $ptid = $members[$i];
         $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,team_type,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:team_type,:responsible,:created_by,:created_at)");
         $result = $sql->execute(array(':projid' => $projid, ':role' => 0, ":stage" => 10, ':team_type' => 5, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $datecreated));
      }
      echo json_encode(array("success" => true));
   }
} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
   echo $ex->getMessage();
}
