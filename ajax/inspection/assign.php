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
                        <select name="member[]" id="memberrow' . $counter . '" class="form-control show-tick members" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
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

        $sql = $db->prepare("UPDATE tbl_projects SET proj_substage=:proj_substage WHERE projid=:projid");
        $result  = $sql->execute(array(":proj_substage" => 2, ":projid" => $projid));
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
