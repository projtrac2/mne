<?php
include '../controller.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {

    function get_stage_details($workflow_stage)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority = :priority ");
        $sql->execute(array(":priority" => $workflow_stage));
        $row = $sql->fetch();
        $rows = $sql->rowCount();
        return $rows > 0 ? $row['stage'] : false;
    }

    function get_user_details($user_id)
    {
        global $db;
        $query_rsUser = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
        $query_rsUser->execute(array(":user_id" => $user_id));
        $row_rsUser = $query_rsUser->fetch();
        $count_rsUser = $query_rsUser->rowCount();
        return  $count_rsUser > 0 ? $row_rsUser : false;
    }

    function update_project_stage($projid, $workflow_stage, $sub_stage)
    {
        global $db, $today, $user_name;
        $sql = $db->prepare("UPDATE tbl_projects SET projstage=:projstage, proj_substage=:proj_substage WHERE  projid=:projid");
        $result  = $sql->execute(array(":projstage" => $workflow_stage, ":proj_substage" => $sub_stage, ":projid" => $projid));

        if ($workflow_stage == 5) {
            $projstatus = 3;
            $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus WHERE  projid=:projid");
            $result  = $sql->execute(array(":projstatus" => $projstatus, ":projid" => $projid));
        }

        if ($result) {
            $sql = $db->prepare("INSERT INTO tbl_project_stage_actions (projid,stage,sub_stage,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:created_by,:created_at)");
            $result = $sql->execute(array(":projid" => $projid, ':stage' => $workflow_stage, ':sub_stage' => $sub_stage, ':created_by' => $user_name, ':created_at' => $today));
        }
        return $result;
    }

    function update_stage($projid, $workflow_stage)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority > :priority ORDER BY priority LIMIT 1");
        $sql->execute(array(":priority" => $workflow_stage));
        $row = $sql->fetch();
        $rows = $sql->rowCount();

        if ($rows > 0) {
            $workflow_stage = $rows > 0 ? $row['priority'] : $workflow_stage;
        }

        return update_project_stage($projid, $workflow_stage, 0);
    }

    // assigning who shall be responsible for creating records/ approving a project
    if (isset($_POST['assign_responsible'])) {
        $projid = $_POST['projid'];
        $workflow_stage = $_POST['workflow_stage'];
        $sub_stage = $_POST['sub_stage'];
        $responsible = $_POST['responsible'];
        $store = $_POST['assign_responsible'];
        $projsubstage = $sub_stage + 1;
        $results = false;

        if ($store == 'new') {
            $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,stage,sub_stage,responsible,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:responsible,:created_by,:created_at)");
            $results = $sql->execute(array(":projid" => $projid, ':stage' => $workflow_stage, ':sub_stage' => $projsubstage, ':responsible' => $responsible, ':created_by' => $user_name, ':created_at' => $currentdate));
            $sub_stage += 1;
            update_project_stage($projid, $workflow_stage, $sub_stage);
        } else {
            $substage = $projsubstage - 1;
            $query_rsResponsible = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage ORDER BY pmid DESC LIMIT 1");
            $query_rsResponsible->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage));
            $total_rsResponsible = $query_rsResponsible->rowCount();
            $row_rsResponsible = $query_rsResponsible->fetch();
            if ($total_rsResponsible > 0) {
                $assigned = $row_rsResponsible['responsible'];
                if ($assigned != $responsible) {
                    $sql = $db->prepare("UPDATE tbl_projmembers SET responsible=:responsible, updated_by=:updated_by, updated_at=:updated_at WHERE projid=:projid");
                    $results = $sql->execute(array(':responsible' => $responsible, ':updated_by' => $user_name, ':updated_at' => $currentdate, ":projid" => $projid));
                    $results =  $mail->send_master_data_email($projid, 2, $assigned);
                }
            }
        }
        $results =  $mail->send_master_data_email($projid, 1, $responsible);
        echo json_encode(array('success' => $results));
    }

    if (isset($_POST['assign_mapping_responsible'])) {
        $projid = $_POST['projid'];
        $workflow_stage = $_POST['workflow_stage'];
        $sub_stage = $_POST['sub_stage'];
        $members = implode(",", $_POST['team']);
        $responsible = $_POST['responsible'];
        $store = $_POST['assign_mapping_responsible'];
        $projsubstage = $sub_stage + 1;
        $results = false;
        if ($store == 'new') {
            $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,stage,sub_stage,members,responsible,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:members,:responsible,:created_by,:created_at)");
            $results = $sql->execute(array(":projid" => $projid, ':stage' => $workflow_stage, ':sub_stage' => $projsubstage, ':members' => $members, ':responsible' => $responsible, ':created_by' => $user_name, ':created_at' => $currentdate));
            $sub_stage += 1;
            update_project_stage($projid, $workflow_stage, $sub_stage);
        } else {
            $substage = $projsubstage - 1;
            $query_rsResponsible = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage ORDER BY pmid DESC LIMIT 1");
            $query_rsResponsible->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage));
            $total_rsResponsible = $query_rsResponsible->rowCount();
            $row_rsResponsible = $query_rsResponsible->fetch();
            if ($total_rsResponsible > 0) {
                $assigned = $row_rsResponsible['responsible'];
                if ($assigned != $responsible) {
                    $sql = $db->prepare("UPDATE tbl_projmembers SET members=:members, responsible=:responsible, updated_by=:updated_by, updated_at=:updated_at WHERE projid=:projid");
                    $results = $sql->execute(array(':members' => $members, ':responsible' => $responsible, ':updated_by' => $user_name, ':updated_at' => $currentdate, ":projid" => $projid));
                    $results =  $mail->send_master_data_email($projid, 2, $assigned);
                }
            }
        }
        $results =  $mail->send_master_data_email($projid, 1, $responsible);
        echo json_encode(array('success' => $results));
    }

    if (isset($_GET['get_responsible'])) {
        $team = $_GET['team'];
        $count_users = count($team);
        $users = '<option value="">Select Responsible</option>';
        if ($count_users > 0) {
            for ($i = 0; $i < $count_users; $i++) {
                $user_id = $team[$i];
                $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id ");
                $get_user->execute(array(":user_id" => $user_id));
                $count_user = $get_user->rowCount();
                if ($count_user > 0) {
                    while ($user = $get_user->fetch()) {
                        $user_name = $user['fullname'];
                        $user_id = $user['userid'];
                        $users .= '<option value="' . $user_id . '">' . $user_name . '</option>';
                    }
                }
            }
        }
        echo json_encode(array("responsible" => $users, "success" => true));
    }

    if (isset($_POST['save_data_entry'])) {
        $projid = $_POST['projid'];
        $workflow_stage = $_POST['workflow_stage'];
        $result = update_project_stage($projid, $workflow_stage, 2);
        $results =  $mail->send_master_data_email($projid, 6, '');
        echo json_encode(array('success' => $result));
    }

    if (isset($_POST['approve_stage'])) {
        $projid = $_POST['projid'];
        $workflow_stage = $_POST['workflow_stage'];
        $result = update_stage($projid, $workflow_stage);
        $results =  $mail->send_master_data_email($projid, 6, '');
        echo json_encode(array('success' => $result));
    }

    if (isset($_GET['get_edit_details'])) {
        $projid = $_GET['projid'];
        $p_directorate = $_GET['project_directorate'];
        $workflow_stage = $_GET['workflow_stage'];
        $sub_stage = $_GET['sub_stage'];

        $sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority=:id AND directorate_id <> 0");
        $result = $sql->execute(array(":id" => $workflow_stage));
        $row = $sql->fetch();
        $rows = $sql->rowCount();
        $project_directorate = $rows > 0 ? $row['directorate_id'] : $p_directorate;

        $sql =  $db->prepare("SELECT * FROM tbl_projmembers WHERE projid=:projid AND stage=:stage AND sub_stage=:sub_stage ");
        $sql->execute(array(":projid" => $projid, ":stage" => $workflow_stage, ":sub_stage" => $sub_stage));
        $row = $sql->fetch();
        $total = $sql->rowCount();
        $ptid = $total > 0 ? $row['responsible'] : '';

        if ($sub_stage == 2) {
            $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE p.directorate=:directorate AND (p.designation >= 7 AND p.designation <= 8)");
        } else {
            $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE p.directorate=:directorate  AND p.designation > 6");
        }

        $get_user->execute(array(":directorate" => $project_directorate));
        $count_user = $get_user->rowCount();
        $users = '<option value="">Select Responsible</option>';

        if ($count_user > 0) {
            while ($user = $get_user->fetch()) {
                $user_name = $user['fullname'];
                $user_id = $user['userid'];
                $selected = $ptid == $user_id ? 'selected' : '';
                $users .= '<option value="' . $user_id . '" ' . $selected . '>' . $user_name . '</option>';
            }
        }
        echo json_encode(array("responsible" => $users, "success" => true));
    }
} catch (PDOException $ex) {
    $results = flashMessage("An error occurred: " . $ex->getMessage());
    echo json_encode(array("success" => false, "message" => $results));
}
