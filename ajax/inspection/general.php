<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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

   function get_role($role_id)
   {
      global $db;
      $query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE active=1");
      $query_projrole->execute();
      $total_rows = $query_projrole->rowCount();
      $role_input = '<option value="">Select Role</option>';
      if ($total_rows > 0) {
         while ($row_projrole = $query_projrole->fetch()) {
            $id = $row_projrole['id'];
            $role = $row_projrole['role'];
            $selected = $role_id == $id ? "selected" : '';
            $role_input .= '<option value="' . $id . '" ' . $selected . ' >' . $role . '</option>';
         }
      }
      return  $role_input;
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
            $role_id = $row['role'];
            $member = get_members($member_id);
            $roles = get_role($role_id);
            $members .= '
            <tr id="mtng' . $counter . '">
                  <td>' . $counter . '</td>
                  <td>
                     <select name="member[]" id="memberrow' . $counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                        ' . $member . '
                     </select>
                  </td>
                  <td>
                     <select name="role[]" id="rolerow' . $counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                        ' . $roles . '
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



   if (isset($_POST['add_questions'])) {

      $projid = $_POST['projid'];
      $datecreated = date("Y-m-d");
      $questions = $_POST['question'];
      $total_questions = count($questions);
      $output_id = $_POST['output_id'];
      // $site_id = $_POST['site_id'];

      $sql = $db->prepare("DELETE FROM `tbl_inspection_checklist_questions` WHERE projid=:projid AND output_id=:output_id");
      $result = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id));

      for ($i = 0; $i < $total_questions; $i++) {
         $question = $questions[$i];
         $sql = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (projid,output_id,question,created_by,created_at) VALUES (:projid,:output_id,:question,:created_by,:created_at)");
         $result = $sql->execute(array(':projid' => $projid, ':output_id' => $output_id, ':question' => $question, ':created_by' => $user_name, ':created_at' => $datecreated));
         // $checklist = $db->prepare('INSERT INTO tbl_inspection_checklist (projid, site_id, output_id, question_id,answer, created_by, created_at) VALUES (:projid, :site_id, :output_id, :question_id, :created_by, :created_at, :updated_at, :answer)');
         // $result_checklist = $checklist->execute([':projid' => $projid, ':site_id' => $site_id, ':output_id' => $output_id, 'question_id' => $question_id, ':answer' => 0, ':created_by' => $user_name, 'created_at' => $datecreated]);
      }
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

   if (isset($_POST['assign_responsible'])) {
      $projid = $_POST['projid'];
      $datecreated = date("Y-m-d");
      $members = $_POST['member'];
      $roles = $_POST['role'];
      $total_members = count($members);

      $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 5");
      $result = $sql->execute(array(':projid' => $projid, ":stage" => 9));
      for ($i = 0; $i < $total_members; $i++) {
         $ptid = $members[$i];
         $role = $roles[$i];
         $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,team_type,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:team_type,:responsible,:created_by,:created_at)");
         $result = $sql->execute(array(':projid' => $projid, ':role' => $role, ":stage" => 9, ':team_type' => 5, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $datecreated));
      }

      update_project($projid, 2);
      echo json_encode(array("success" => true));
   }
} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
   echo $ex->getMessage();
}
