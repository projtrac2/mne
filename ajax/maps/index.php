<?php
try {
    include '../controller.php';

    function get_responsible($members, $responsible)
    {
        global $db;
        $team = '<option value="" selected="selected" class="selection">....Select Team first....</option>';
        $total_members = count($members);
        for ($i = 0; $i < $total_members; $i++) {
            $ptid = $members[$i];
            if (!empty($ptid)) {
                $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id AND directorate=42");
                $query_rsTeam->execute(array(":user_id" => $ptid));
                $row_rsTeam = $query_rsTeam->fetch();
                $totalRows_rsTeam = $query_rsTeam->rowCount();
                if ($totalRows_rsTeam > 0) {
                    $selected =  $row_rsTeam['userid'] == $responsible ? 'selected' : '';
                    $team .= '<option value="' . $row_rsTeam['userid'] . '" ' . $selected . '>' . $row_rsTeam['fullname'] . '</option>';
                }
            }
        }
        return $team;
    }

    function get_team($members)
    {
        global $db;
        $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate=42");
        $query_rsTeam->execute();
        $row_rsTeam = $query_rsTeam->fetch();
        $totalRows_rsTeam = $query_rsTeam->rowCount();
        $team = "";
        if ($totalRows_rsTeam > 0) {
            do {
                $user_id = $row_rsTeam['userid'];
                $selected = in_array($user_id, $members)  ? "selected" : "";
                $team .= '<option value="' . $user_id . '" ' . $selected . '>' . $row_rsTeam['fullname'] . '</option>';
            } while ($row_rsTeam = $query_rsTeam->fetch());
        }
        return $team;
    }

    if (isset($_POST['get_responsible'])) {
        $members =  $_POST['members'];
        $responsible =   get_responsible($members, "");
        echo json_encode(array("success" => true, "responsible" => $responsible));
    }

    if (isset($_GET['get_edit_details'])) {
        $projid = $_GET['projid'];
        $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping WHERE projid=:projid ");
        $query_rs_locations->execute(array(":projid" => $projid));
        $row_rs_locations = $query_rs_locations->fetch();
        $totalrow_rs_locations = $query_rs_locations->rowCount();
        $team = $responsible = $mapping_date = "";
        if ($totalrow_rs_locations > 0) {
            $members = explode(",", $row_rs_locations['ptid']);
            $team =  get_team($members);
            $responsible_id = $row_rs_locations['responsible'];
            $responsible = get_responsible($members, $responsible_id);
            $mapping_date = $row_rs_locations['mapping_date'];
        }
        echo json_encode(array("success" => true, "teams" => $team, "responsible" => $responsible, "mapping_date" => $mapping_date));
    }

    if (isset($_POST['store_mapping_teams'])) {
        $success = false;
        $message = "Error ! try again later";
        if (validate_csrf_token($_POST['csrf_token'])) {
            $projid = $_POST['projid'];
            $store_mapping_teams = $_POST['store_mapping_teams'];
            $mapping_date = $_POST['mapping_date'];
            $team = implode(",", $_POST['team']);
            $responsible = $_POST['responsible'];
            $message = "Error ! inserting records";
            if ($store_mapping_teams == "new") {
                $message = "Successfully created record";
                $sql = $db->prepare("INSERT INTO tbl_project_mapping (projid,ptid,responsible,mapping_date) VALUES(:projid,:ptid,:responsible,:mapping_date)");
                $success = $sql->execute(array(":projid" => $projid, ":ptid" => $team, ":responsible" => $responsible, ":mapping_date" => $mapping_date));
            } else {
                $message = "Successfully updated record";
                $sql = $db->prepare("UPDATE tbl_project_mapping SET ptid=:ptid,responsible=:responsible,mapping_date=:mapping_date) VALUES(:projid,:ptid,:responsible,:mapping_date)");
                $success = $sql->execute(array(":ptid" => $team, ":responsible" => $responsible, ":mapping_date" => $mapping_date, ":projid" => $projid));
            }
        }
        echo json_encode(array("success" => $success, "message" => $message));
    }

    if (isset($_POST['pin_location'])) {
        $message = "Error occured please try again later";
        $success = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $success = true;
            $message = "Message Successfully";
            $current_date = date("Y-m-d");
            $projid = $_POST['projid'];
            $outputid = $_POST['output_id'];
            $state_id = $_POST['state_id'];
            $site_id = $_POST['site_id'];
            $user_name = $_POST['user_name'];
            $mapping_type = $_POST['mapping_type'];
            $distance = $_POST['distance'];
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];
            $sql = $db->prepare("INSERT INTO tbl_markers (projid,opid,state,site_id,lat,lng,distance_mapped,mapped_date,mapped_by)  VALUES(:projid,:opid,:state,:site_id,:lat,:lng,:distance,:mapped_date,:mapped_by)");
            $result = $sql->execute(array(':projid' => $projid, ":opid" => $outputid, ":state" => $state_id, ':site_id' => $site_id, ':lat' => $lat, ':lng' => $lng, ":distance" => $distance, ":mapped_date" => $current_date, ":mapped_by" => $user_name));
        }
        echo json_encode(array("success" => $success, "message" => $message));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
