<?php
try {
    include '../controller.php';

    function get_roles($role)
    {
        global $db;
        $query_mbrrole = $db->prepare("SELECT * FROM tbl_project_team_roles WHERE id =:role");
        $query_mbrrole->execute(array(":role" => $role));
        $row_mbrrole = $query_mbrrole->fetch();
        $count_row_mbrrole = $query_mbrrole->rowCount();
        return $count_row_mbrrole > 0 ? $row_mbrrole['role'] : "";
    }

    function get_designation($moid)
    {
        global $db;
        $query_rsPMbrDesg = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid =:moid ORDER BY moid ASC");
        $query_rsPMbrDesg->execute(array(":moid" => $moid));
        $row_rsPMbrDesg = $query_rsPMbrDesg->fetch();
        return $row_rsPMbrDesg ? $row_rsPMbrDesg['designation'] : "";
    }

    function get_caretaker($projid, $user_name, $workflow_stage, $sub_stage, $activity)
    {
        global $db;
        $standin_responsible = "";
        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage  AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            $standin_responsible = $total_rsOutput > 0 ? true : false;
        }

        $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :standin_responsible ORDER BY ptid ASC");
        $query_rsPMbrs->execute(array(":standin_responsible" => $standin_responsible));
        $row_rsPMbrs = $query_rsPMbrs->fetch();
        $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
        return $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
    }

    function get_technical_team($projid)
    {
        global $db;
        $technical_team = '';
        $query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid AND team_type=4 GROUP BY responsible ORDER BY role ASC");
        $query_rsMembers->execute(array(":projid" => $projid));
        $total_rsMembers = $query_rsMembers->rowCount();
        if ($total_rsMembers > 0) {
            $rowno = 0;
            while ($row_rsMembers = $query_rsMembers->fetch()) {
                $rowno++;
                $role_id = $row_rsMembers['role'];
                $ptid = $row_rsMembers['responsible'];
                $technical_substage = 2;
                $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                $query_rsPMbrs->execute(array(":user_id" => $ptid));
                $row_rsPMbrs = $query_rsPMbrs->fetch();
                $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                if ($count_row_rsPMbrs > 0) {
                    $mbrdesg = $row_rsPMbrs['designation'];
                    $availability = $row_rsPMbrs['availability'];
                    $file_path = $row_rsPMbrs['floc'];
                    $email = $row_rsPMbrs['email'];
                    $phone = $row_rsPMbrs['phone'];
                    $fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
                    $user_role = get_roles($role_id);
                    $designation = get_designation($mbrdesg);

                    $p_availability = "<p>Yes</p>";
                    if ($availability == 0) {
                        $caretaker =    get_caretaker($projid, $ptid, 10, $technical_substage, 1);
                        $p_availability = '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">No</p>';
                    }

                    $technical_team .= '
                    <tr>
                        <td>
                            <img src="' . $file_path . '" style="width:30px; height:30px; margin-bottom:0px" />
                        </td>
                        <td>' . $fullname . '</td>
                        <td>' . $designation . '</td>
                        <td>' . $user_role . '</td>
                        <td>' . $p_availability . '</td>
                        <td>' . $email . '</td>
                        <td>' . $phone . '</td>
                    </tr>';
                }
            }
        }

        return $technical_team;
    }

    function get_mne_team($projid)
    {
        global $db;
        $query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid  AND (team_type=1 OR team_type=2 OR team_type=3) GROUP BY responsible");
        $query_rsMembers->execute(array(":projid" => $projid));
        $total_rsMembers = $query_rsMembers->rowCount();
        $mne_team = '';
        $technical_substage = 2;
        if ($total_rsMembers > 0) {
            $rowno = 0;
            while ($row_rsMembers = $query_rsMembers->fetch()) {
                $rowno++;
                $role_id = $row_rsMembers['team_type'];
                $workflow_stage = '';
                if ($role_id == 1) {
                    $user_role = "Output Monitor";
                    $workflow_stage = 10;
                } elseif ($role_id == 2) {
                    $user_role = "Outcome Evaluator";
                    $workflow_stage = 9;
                } elseif ($role_id == 3) {
                    $user_role = "Impact Evaluator";
                    $workflow_stage = 9;
                }

                $ptid = $row_rsMembers['responsible'];
                $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                $query_rsPMbrs->execute(array(":user_id" => $ptid));
                $row_rsPMbrs = $query_rsPMbrs->fetch();
                $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                if ($count_row_rsPMbrs > 0) {
                    $mbrdesg = $row_rsPMbrs['designation'];
                    $availability = $row_rsPMbrs['availability'];
                    $file_path = $row_rsPMbrs['floc'];
                    $email = $row_rsPMbrs['email'];
                    $phone = $row_rsPMbrs['phone'];
                    $fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
                    $designation = get_designation($mbrdesg);

                    $p_availability = "<p>Yes</p>";
                    if ($availability == 0) {
                        $caretaker =    get_caretaker($projid, $ptid, $workflow_stage, $technical_substage, 1);
                        $p_availability = '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">No</p>';
                    }

                    $mne_team .= '
                    <tr>
                        <td>
                            <img src="' . $file_path . '" style="width:30px; height:30px; margin-bottom:0px" />
                        </td>
                        <td>' . $fullname . '</td>
                        <td>' . $designation . '</td>
                        <td>' . $user_role . '</td>
                        <td>' . $p_availability . '</td>
                        <td>' . $email . '</td>
                        <td>' . $phone . '</td>
                    </tr>';
                }
            }
        }

        return $mne_team;
    }

    if (isset($_GET['get_team_members'])) {
        $projid = $_GET['projid'];
        $technical_team = get_technical_team($projid);
        $mne_team = get_mne_team($projid);
        echo json_encode(array('success' => true, 'technical_team' => $technical_team, 'mne_team' => $mne_team));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
