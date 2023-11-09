<?php

include '../controller.php';
try {

    function get_department($department_id)
    {
        global $db;
        $query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent =0 AND deleted='0'");
        $query_department->execute();
        $row_department = $query_department->fetch();
        $input = '<option value="">Select Department</option>';
        if ($row_department) {
            do {
                $id = $row_department['stid'];
                $sector = $row_department['sector'];
                $selected = $department_id == $id ? ' selected="selected"' : '';
                $input .= '<option value="' . $id . '" ' . $selected . '>' . $sector . '</option>';
            } while ($row_department = $query_department->fetch());
        }
        return $input;
    }

    function get_section($department_id, $section_id)
    {
        global $db;
        $query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent=:stid AND deleted='0'");
        $query_department->execute(array(":stid" => $department_id));
        $row_department = $query_department->fetch();

        $input = '<option value="">Select Section From List</option>';
        if ($row_department) {
            do {
                $id = $row_department['stid'];
                $sector = $row_department['sector'];
                $selected = $section_id == $id ? ' selected="selected"' : '';
                $input .= '<option value="' . $id . '" ' . $selected . '>' . $sector . '</option>';
            } while ($row_department = $query_department->fetch());
        }
        return $input;
    }

    function get_directorate($section_id, $directorate_id)
    {
        global $db;
        $query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent=:stid AND deleted='0'");
        $query_department->execute(array(":stid" => $section_id));
        $row_department = $query_department->fetch();
        $input = '<option value="">Select Directorate From List</option>';
        if ($row_department) {
            do {
                $id = $row_department['stid'];
                $sector = $row_department['sector'];
                $selected = $directorate_id == $id ? ' selected="selected"' : '';
                $input .= '<option value="' . $id . '" ' . $selected . '>' . $sector . '</option>';
            } while ($row_department = $query_department->fetch());
        }
        return $input;
    }

    function get_members($projid, $department_id, $section_id, $directorate_id, $member_id, $edit)
    {
        global $db;
        $input = '<option value="">Select Member</option>';
        if ($department_id != 0) {
            $query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE ministry=:ministry");
            $query_team->execute(array(":ministry" => $department_id));
            if ($section_id != 0) {
                $query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE department=:section");
                $query_team->execute(array(":section" => $section_id));
                if ($directorate_id != 0) {
                    $query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate =:directorate_id");
                    $query_team->execute(array(":directorate_id" => $directorate_id));
                }
            }

            $rows_team = $query_team->rowCount();
            if ($rows_team) {
                while ($row_team = $query_team->fetch()) {
                    $user_id = $row_team['userid'];
                    $query_rsMembers = $db->prepare("SELECT responsible, role FROM `tbl_projmembers` WHERE projid=:projid  AND stage=:workflow_stage and team_type=4 AND responsible=:user_id");
                    $query_rsMembers->execute(array(":projid" => $projid, ":workflow_stage" => 10, ":user_id" => $user_id));
                    $total_rsMembers = $query_rsMembers->rowCount();
                    if ($total_rsMembers == 0 || $edit == 1) {
                        $title_id = $row_team['title'];
                        $firstname = $row_team['firstname'];
                        $middlename = $row_team['middlename'];
                        $lastname = $row_team['lastname'];

                        $query_rsTitle = $db->prepare("SELECT * FROM `tbl_titles` WHERE id=:title_id ");
                        $query_rsTitle->execute(array(":title_id" => $title_id));
                        $row_rsTitle = $query_rsTitle->fetch();
                        $title = $row_rsTitle ? $row_rsTitle['title'] : '';
                        $membername = $title . ". " . $firstname . " " . $middlename . " " . $lastname;
                        $selected = $member_id == $user_id ? ' selected="selected"' : '';
                        $input .= '<option value="' . $user_id . '" ' . $selected . '>' . $membername . '</option>';
                    }
                }
            }
        }
        return $input;
    }

    function get_subtasks($projid, $user_id)
    {
        global $db;
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();

        $task_div = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">';
        if ($total_Output > 0) {
            $counter = 0;
            while ($row_rsOutput = $query_Output->fetch()) {
                $counter++;
                $output_id = $row_rsOutput['id'];
                $output = $row_rsOutput['indicator_name'];
                $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id");
                $query_rsMilestone->execute(array(":output_id" => $output_id));
                $totalRows_rsMilestone = $query_rsMilestone->rowCount();

                if ($totalRows_rsMilestone > 0) {
                    $query_rsUser = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id=:member_id AND output_id=:output_id ");
                    $query_rsUser->execute(array(":member_id" => $user_id, ":output_id" => $output_id));
                    $totalRows_rsUser = $query_rsUser->rowCount();
                    $checked = $totalRows_rsUser > 0 ? 1 : 0;
                    $output_checked =  $checked == 1 ? "checked" : "";
                    $check =  $checked ? "Uncheck" : "Check";
                    $task_div .= '
                        <fieldset class="scheduler-border row setup-content" style="padding:10px">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output ' . $counter . ': ' . strtoupper($output) . '</legend>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-line">
                                            <input name="projevaluation" onchange="output_check_box(' . $output_id . ', 0, 0)" type="checkbox" ' . $output_checked . ' id="outputs' . $output_id . '" class="with-gap radio-col-green sub_task" />
                                            <label for="outputs' . $output_id . '"><span id="output_checked' . $output_id . '"> ' . $check . ' All</span></label>
                                            <input type="hidden" name="output[]" value="' . $output_id . '">
                                        </div>
                                    </div>';
                    $tcounter = 0;
                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                        $tcounter++;
                        $milestone_name = $row_rsMilestone['milestone'];
                        $milestone_id = $row_rsMilestone['msid'];

                        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
                        $query_rsTasks->execute(array(":milestone" => $milestone_id));
                        $totalRows_rsTasks = $query_rsTasks->rowCount();

                        $query_rsUser = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id=:member_id AND task_id=:task_id ");
                        $query_rsUser->execute(array(":member_id" => $user_id, ":task_id" => $milestone_id));
                        $totalRows_rsUser = $query_rsUser->rowCount();
                        $checked = $totalRows_rsUser > 0 ? 1 : 0;

                        $milestone_checked = $checked == 1 ? "checked" : "";
                        $check =  $milestone_checked ? "Uncheck" : "Check";
                        $task_div  .= '
                            <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Task ' . $tcounter . ': ' . strtoupper($milestone_name) . '</legend>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-line">
                                        <input name="projevaluation" onchange="check_box(' . $output_id . ',' . $milestone_id . ')" type="checkbox" ' . $milestone_checked . ' id="all' . $milestone_id . '" class="with-gap radio-col-green sub_task task_check_' . $output_id . '" />
                                        <label for="all' . $milestone_id . '"><span id="checked' . $milestone_id . '" class="task_checked_' . $output_id . '"> ' . $check . ' All</span></label>
                                        <input type="hidden" name="task' . $output_id . '[]" value="' . $milestone_id . '">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr style="background-color:#0b548f; color:#FFF">
                                                    <td style="width:5%"></td>
                                                    <th style="width:5%" align="center">#</th>
                                                    <th style="width:40%">Sub-Task</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                        if ($totalRows_rsTasks > 0) {
                            $mcounter = 0;
                            while ($row_rsTasks = $query_rsTasks->fetch()) {
                                $mcounter++;
                                $task_name = $row_rsTasks['task'];
                                $task_id = $row_rsTasks['tkid'];
                                $query_rsUser = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id=:member_id AND subtask_id=:subtask_id ");
                                $query_rsUser->execute(array(":member_id" => $user_id, ":subtask_id" => $task_id));
                                $totalRows_rsUser = $query_rsUser->rowCount();
                                $checked = $totalRows_rsUser > 0 ? 1 : 0;
                                $check =  $checked == 1 ? "checked" : "";
                                $count = $milestone_id . $mcounter . $counter;
                                $task_div  .=
                                    '<tr style="background-color:#FFFFFF">
                                        <td align="center">
                                            <div class="form-line">
                                                <input name="sub_task' . $milestone_id . '[]" value="' . $task_id . '" ' . $checked . ' onchange="check_item(' . $output_id . ',' . $milestone_id . ')" type="checkbox" ' . $check . ' id="evaluation' . $count . '" class="with-gap radio-col-green subtasks_' . $output_id . ' sub_task' . $milestone_id . '" />
                                                <label for="evaluation' . $count . '"></label>
                                            </div>
                                        </td>
                                        <td align="center">' . $counter . "." . $mcounter . '</td>
                                        <td>' . $task_name . '</td>
                                    </tr>';
                            }
                        }
                        $task_div  .= '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>';
                    }
                    $task_div  .= '</fieldset>';
                }
            }
        }
        $task_div  .= '</div>';


        return $task_div;
    }

    // get department
    if (isset($_GET['get_sections'])) {
        $department_id = $_GET['department_id'];
        $projid = $_GET['projid'];
        $members = get_members($projid, $department_id, 0, 0, 0, 0);
        $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent AND deleted='0'");
        $result = $sql->execute(array(":parent" => $department_id));
        $sections = '<option value="">Select Section</option>';
        while ($row = $sql->fetch()) {
            $sections .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
        }
        echo json_encode(array("success" => $result, "sections" => $sections, 'members' => $members));
    }

    if (isset($_GET['get_directorate'])) {
        $department_id = $_GET['department_id'];
        $sector_id = $_GET['sector_id'];
        $projid = $_GET['projid'];

        $members = get_members($projid, $department_id, $sector_id, 0, 0, 0);
        $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent AND deleted='0'");
        $result = $sql->execute(array(":parent" => $sector_id));
        $directorates = '<option value="">Select Directorate</option>';
        while ($row = $sql->fetch()) {
            $directorates .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
        }
        echo json_encode(array("success" => $result, "directorates" => $directorates, 'members' => $members));
    }

    if (isset($_GET['get_members'])) {
        $projid = $_GET['projid'];
        $department_id = $_GET['department_id'];
        $section_id = $_GET['sector_id'];
        $directorate_id = $_GET['directorate_id'];

        $members = get_members($projid, $department_id, $section_id, $directorate_id, 0, 0);
        echo json_encode(array("success" => true, "members" => $members));
    }

    if (isset($_GET['get_edit_details'])) {
        $projid = $_GET['projid'];
        $user_id = $_GET['user_id'];

        $query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE userid=:userid");
        $query_team->execute(array(":userid" => $user_id));
        $row_team = $query_team->fetch();
        $rows_team = $query_team->rowCount();
        $department_id = $section_id = $directorate_id = 0;
        if ($rows_team) {
            $department_id = $row_team['ministry'];
            $section_id = $row_team['department'];
            $directorate_id =  $row_team['directorate'];
        }

        $members = get_members($projid, $department_id, $section_id, $directorate_id, $user_id, 1);
        $departments = get_department($department_id);
        $sections =  get_section($department_id, $section_id);
        $directorates = get_directorate($section_id, $directorate_id);
        $task_body  = get_subtasks($projid, $user_id);

        echo json_encode(array("success" => true, "departments" => $departments,  "sections" => $sections, "directorates" => $directorates, 'members' => $members, "tasks" => $task_body));
    }

    if (isset($_GET['delete_team_member'])) {
        $projid = $_GET['projid'];
        $implimentation_stage = 10;
        $ptid = $_GET['member'];

        $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 4 AND responsible=:responsible");
        $result = $sql->execute(array(':projid' => $projid, ":stage" => $implimentation_stage, ':responsible' => $ptid));
        echo json_encode(array("success" => $result));
    }

    if (isset($_GET['get_task_details'])) {
        $projid = $_GET['projid'];
        $task_body  = get_subtasks($projid, 0);
        echo json_encode(array("success" => true, "tasks" => $task_body));
    }

    if (isset($_POST['store_technical_team'])) {
        $projid = $_POST['projid'];
        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        $mnecode = "AB123" . $projid;
        $ptid = $_POST['member'];
        $role = $_POST['role'];
        $implimentation_stage = 10;

        $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 4 AND responsible=:responsible");
        $result = $sql->execute(array(':projid' => $projid, ":stage" => $implimentation_stage, ':responsible' => $ptid));

        $sql = $db->prepare("DELETE FROM `tbl_member_subtasks` WHERE projid=:projid AND member_id=:responsible");
        $result = $sql->execute(array(':projid' => $projid, ':responsible' => $ptid));

        $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,team_type,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:team_type,:responsible,:created_by,:created_at)");
        $result = $sql->execute(array(':projid' => $projid, ':role' => $role, ":stage" => $implimentation_stage, ':team_type' => 4, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $datecreated));

        if (isset($_POST['output'])) {
            $output_length = count($_POST['output']);
            for ($o = 0; $o < $output_length; $o++) {
                $output_id = $_POST['output'][$o];
                if (isset($_POST['task' . $output_id])) {
                    $task_length = count($_POST['task' . $output_id]);
                    for ($t = 0; $t < $task_length; $t++) {
                        $task_id = $_POST['task' . $output_id][$t];
                        if (isset($_POST['sub_task' . $task_id])) {
                            $subtask_length = count($_POST['sub_task' . $task_id]);
                            for ($i = 0; $i < $subtask_length; $i++) {
                                $subtask_id = $_POST['sub_task' . $task_id][$i];
                                $sql = $db->prepare("INSERT INTO tbl_member_subtasks (projid,member_id,output_id,task_id,subtask_id) VALUES (:projid,:member_id,:output_id,:task_id,:subtask_id)");
                                $result = $sql->execute(array(':projid' => $projid, ':member_id' => $ptid, ":output_id" => $output_id, ':task_id' => $task_id, ':subtask_id' => $subtask_id));
                            }
                        }
                    }
                }
            }
        }
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
