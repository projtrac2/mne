<?php
include '../controller.php';
try {
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

    function get_sector($sector_id)
    {
        global $db;
        $query_rsMinistry = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :sector_id");
        $query_rsMinistry->execute(array(":sector_id" => $sector_id));
        $row_rsMinistry = $query_rsMinistry->fetch();
        $rows_rsMinistry = $query_rsMinistry->rowCount();
        return $rows_rsMinistry > 0 ? $row_rsMinistry['sector'] : "";
    }


    
    function get_caretaker($projid, $user_name)
    {
        global $db;
        $standin_responsible = "";
        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND team_type=4 AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            $standin_responsible = $total_rsOutput > 0 ? true : false;
        }

        $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :standin_responsible ORDER BY ptid ASC");
        $query_rsPMbrs->execute(array(":standin_responsible" => $standin_responsible));
        $row_rsPMbrs = $query_rsPMbrs->fetch();
        $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
        return $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
    }


    if (isset($_GET['get_team_members'])) {
        $projid = $_GET['projid'];
        $team_body ='';
        $query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid AND team_type=4");
        $query_rsMembers->execute(array(":projid" => $projid));
        $total_rsMembers = $query_rsMembers->rowCount();
        if ($total_rsMembers > 0) {
            $rowno = 0;
            while ($row_rsMembers = $query_rsMembers->fetch()) {
                $rowno++;
                $role_id = $row_rsMembers['role'];
                $ptid = $row_rsMembers['responsible'];
                $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                $query_rsPMbrs->execute(array(":user_id" => $ptid));
                $row_rsPMbrs = $query_rsPMbrs->fetch();
                $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                if ($count_row_rsPMbrs > 0) {
                    $mbrministry = $row_rsPMbrs['ministry'];
                    $mbrdept = $row_rsPMbrs['department'];
                    $mbrdesg = $row_rsPMbrs['designation'];
                    $userid = $row_rsPMbrs['userid'];
                    $availability = $row_rsPMbrs['availability'];
                    $file_path = $row_rsPMbrs['floc'];
                    $email = $row_rsPMbrs['email'];
                    $phone = $row_rsPMbrs['phone'];
                    $fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
                    $user_role = get_roles($role_id);
                    $designation = get_designation($mbrdesg);
                    $ministries = get_sector($mbrministry);
                    $sections = get_sector($mbrdept);
                    $directorates = get_sector($mbrministry);

                    $ministry = $ministries != '' ? $ministries : "All " . $ministrylabelplural;
                    $section = $sections != '' ? $sections : "All " . $departmentlabelplural;
                    $directorate = $directorates != '' ? $directorates : "All " . $ministrylabelplural;

                    $p_availability = "<p>Yes</p>";
                    if ($availability == 0) {
                        $caretaker =    get_caretaker($projid, $ptid);
                        $p_availability = '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">No</p>';
                    }
                    $team_body .= "
                    <tr>
                        <td>
                            <img src='$file_path' style='width:30px; height:30px; margin-bottom:0px' />
                        </td>
                        <td>$fullname</td>
                        <td>$designation</td>
                        <td>$user_role</td>
                        <td>$p_availability</td>
                        <td>$email</td>
                        <td>$phone</td>
                    </tr>";
                }
            }
        }

        echo json_encode(array("success"=>true, "team"=>$team_body));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
