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

    function get_body($user_id, $site_id, $output_id)
    {
        global $db;
        $task_div = '';
        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
        $query_rsMilestone->execute(array(":output_id" => $output_id));
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        if ($totalRows_rsMilestone > 0) {
            $counter = "";
            while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                $counter++;
                $milestone_name = $row_rsMilestone['milestone'];
                $task_id = $row_rsMilestone['msid'];
                $task_div .= '<tr><td>Task :' . $counter . '</td><td>' . $milestone_name . '</td></tr>';
                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task t INNER JOIN tbl_project_direct_cost_plan c ON t.tkid = c.subtask_id WHERE t.outputid=:output_id AND c.site_id=:site_id AND tasks=:task_id");
                $query_rsTasks->execute(array(":output_id" => $output_id, ":site_id" => $site_id, ":task_id" => $task_id));
                $totalRows_rsTasks = $query_rsTasks->rowCount();
                if ($totalRows_rsTasks > 0) {
                    $t_counter = 0;
                    while ($row_rsTasks = $query_rsTasks->fetch()) {
                        $t_counter++;
                        $task_name = $row_rsTasks['task'];
                        $task_id = $row_rsTasks['tkid'];
                        $query_rsSubTask = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id<>:member_id AND subtask_id=:subtask_id AND site_id=:site_id ");
                        $query_rsSubTask->execute(array(":member_id" => $user_id, ":subtask_id" => $task_id, ":site_id" => $site_id));
                        $totalRows_rsSubTask = $query_rsSubTask->rowCount();
                        if ($totalRows_rsSubTask == 0) {
                            $query_rsUser = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id=:member_id AND subtask_id=:subtask_id AND site_id=:site_id ");
                            $query_rsUser->execute(array(":member_id" => $user_id, ":subtask_id" => $task_id, ":site_id" => $site_id));
                            $totalRows_rsUser = $query_rsUser->rowCount();
                            $checked = $totalRows_rsUser > 0 ? 1 : 0;
                            $check =  $checked == 1 ? "checked" : "";
                            $task_div  .=
                                '<tr>
                                    <td>' . $t_counter . '</td>
                                    <td>
                                        <div class="form-line">
                                            <input name="subtask_id' . $site_id . $output_id . '[]" value="' . $task_id . '" ' . $checked . ' onchange="check_item(\'' . $site_id .  $output_id . '\',' . $task_id . ')" type="checkbox" ' . $check . ' id="subtask_id' . $site_id . $output_id . $task_id . '" class="with-gap radio-col-green subtasks_' . $site_id . $output_id . ' sub_task' . $task_id . '" />
                                            <label for="subtask_id' . $site_id  . $output_id . $task_id . '"> ' . $task_name . '</label>
                                        </div>
                                    </td>
                                </tr>';
                        }
                    }
                }
            }
        }

        return  $task_div;
    }

    function get_subtasks($projid, $user_id)
    {
        global $db;
        $task_div = '<div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
        $query_Sites->execute(array(":projid" => $projid));
        $rows_sites = $query_Sites->rowCount();
        if ($rows_sites > 0) {
            $counter = 0;
            while ($row_Sites = $query_Sites->fetch()) {
                $site_id = $row_Sites['site_id'];
                $site = $row_Sites['site'];
                $counter++;
                $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                $query_Site_Output->execute(array(":site_id" => $site_id));
                $rows_Site_Output = $query_Site_Output->rowCount();
                if ($rows_Site_Output > 0) {
                    $output_counter = 0;
                    $task_div .= '
                            <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Site ' . $counter . ': ' . strtoupper($site) . '</legend>
                                <input type="hidden" name="site_id[]" value="' . $site_id . '"/>';
                    while ($row_Site_Output = $query_Site_Output->fetch()) {
                        $output_counter++;
                        $output_id = $row_Site_Output['outputid'];
                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                        $query_Output->execute(array(":outputid" => $output_id));
                        $row_Output = $query_Output->fetch();
                        $total_Output = $query_Output->rowCount();
                        if ($total_Output) {
                            $output_id = $row_Output['id'];
                            $output = $row_Output['indicator_name'];
                            $query_rsUser = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE member_id=:member_id AND output_id=:output_id AND site_id=:site_id ");
                            $query_rsUser->execute(array(":member_id" => $user_id, ":output_id" => $output_id, ":site_id" => $site_id));
                            $totalRows_rsUser = $query_rsUser->rowCount();
                            $checked = $totalRows_rsUser > 0 ? 1 : 0;
                            $output_checked =  $checked == 1 ? "checked" : "";
                            $check =  $checked ? "Uncheck" : "Check";

                            $task_div .= '
                            <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output ' . $output_counter . ': ' . strtoupper($output) . '</legend>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-line">
                                        <input name="projevaluation" onchange="output_check_box(\'' . $site_id . $output_id . '\', 0, 0)" type="checkbox" ' . $output_checked . ' id="outputs' . $site_id . $output_id . '" class="with-gap radio-col-green sub_task" />
                                        <label for="outputs' . $site_id . $output_id . '"><span id="output_checked' . $site_id . $output_id . '"> ' . $check . ' All</span></label>
                                        <input type="hidden" name="output_id' . $site_id . '[]" value="' . $output_id . '">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example ">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%;">#</th>
                                                    <th style="width:90%;">Subtask</th>
                                                </tr>
                                            </thead>
                                            <tbody id="">
                                                ' . get_body($user_id, $site_id, $output_id) .  '
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>';
                        }
                    }
                    $task_div .=  '</fieldset>';
                }
            }
        }

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        if ($total_Output > 0) {
            $counter = 0;
            $site_id = 0;
            $task_div .= '<input type="hidden" name="site_id[]" value="' . $site_id . '"/>';
            while ($row_rsOutput = $query_Output->fetch()) {
                $output_id = $row_rsOutput['id'];
                $output = $row_rsOutput['indicator_name'];
                $counter++;
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
                            <input name="projevaluation" onchange="output_check_box(\'' . $site_id . $output_id . '\', 0, 0)" type="checkbox" ' . $output_checked . ' id="outputs' . $site_id . $output_id . '" class="with-gap radio-col-green sub_task" />
                            <label for="outputs' . $site_id . $output_id . '"><span id="output_checked' . $site_id . $output_id . '"> ' . $check . ' All</span></label>
                            <input type="hidden" name="output_id' . $site_id . '[]" value="'  . $output_id . '">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                    <tr>
                                        <th style="width:10%;"> #</th>
                                        <th style="width:90%;"> Subtask</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ' . get_body($user_id, $site_id, $output_id) .  '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>';
            }
        }

        $task_div .= '</div>
                                </div>';
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
        $ptid = $_POST['member'];
        $role = $_POST['role'];
        $implimentation_stage = 9;

        $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 4 AND responsible=:responsible");
        $result = $sql->execute(array(':projid' => $projid, ":stage" => $implimentation_stage, ':responsible' => $ptid));

        $sql = $db->prepare("DELETE FROM `tbl_member_subtasks` WHERE projid=:projid AND member_id=:responsible");
        $result = $sql->execute(array(':projid' => $projid, ':responsible' => $ptid));

        $sql = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,team_type,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:team_type,:responsible,:created_by,:created_at)");
        $result = $sql->execute(array(':projid' => $projid, ':role' => $role, ":stage" => $implimentation_stage, ':team_type' => 4, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $datecreated));


        if (isset($_POST['site_id'])) {
            $sites = $_POST['site_id'];
            $count_sites = count($sites);
            for ($s = 0; $s < $count_sites; $s++) {
                $site_id = $sites[$s];
                if (isset($_POST['output_id' . $site_id])) {
                    $output_length = count($_POST['output_id' . $site_id]);
                    for ($o = 0; $o < $output_length; $o++) {
                        $output_id = $_POST['output_id' . $site_id][$o];
                        if (isset($_POST['subtask_id' . $site_id . $output_id])) {
                            if (isset($_POST['subtask_id' . $site_id . $output_id])) {
                                $subtask_length = count($_POST['subtask_id' . $site_id . $output_id]);
                                for ($i = 0; $i < $subtask_length; $i++) {
                                    $subtask_id = $_POST['subtask_id' . $site_id . $output_id][$i];
                                    $sql = $db->prepare("INSERT INTO tbl_member_subtasks (projid,member_id,output_id,site_id,subtask_id) VALUES (:projid,:member_id,:output_id,:site_id,:subtask_id)");
                                    $result = $sql->execute(array(':projid' => $projid, ':member_id' => $ptid, ":output_id" => $output_id, ':site_id' => $site_id, ':subtask_id' => $subtask_id));
                                }
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
