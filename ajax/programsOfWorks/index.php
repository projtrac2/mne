<?php
include '../controller.php';
try {
    if (isset($_GET['get_tasks'])) {
        $site_id = $_GET['site_id'];
        $projid = $_GET['projid'];
        $task_id = $_GET['task_id'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $min_date = $row_rsProjects['projstartdate'];
            $max_date = $row_rsProjects['projenddate'];
            $implimentation_type = $row_rsProjects['projcategory'];
            if ($implimentation_type == 2) {
                $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
                $query_rsTender->execute(array(":projid" => $projid));
                $row_rsTender = $query_rsTender->fetch();
                $totalRows_rsTender = $query_rsTender->rowCount();
                if ($totalRows_rsTender > 0) {
                    $tenderstartdate = $row_rsTender['startdate'];
                    $tenderenddate = $row_rsTender['enddate'];
                }
            }
        }

        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_task WHERE msid=:task_id");
        $query_rsMilestone->execute(array(":task_id" => $task_id));
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $input = "";
        if ($totalRows_rsMilestone > 0) {
            $counter = "";
            while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                $task = $row_rsMilestone['task'];
                $tkid = $row_rsMilestone['tkid'];
                $counter++;

                $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
                $query_rsTask_Start_Dates->execute(array(':task_id' => $task_id, ':site_id' => $site_id, ":subtask_id" => $tkid));
                $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                $start_date = $end_date = $duration =  "";

                if ($totalRows_rsTask_Start_Dates > 0) {
                    $start_date = $row_rsTask_Start_Dates['start_date'];
                    $end_date = $row_rsTask_Start_Dates['end_date'];
                    $duration = $row_rsTask_Start_Dates['duration'];
                }

                $today = date('Y-m-d');
                $input .= '
                <tr>
                    <td>' . $counter . '</td>
                    <td>
                        <input name="tasks[]" type="hidden" value="' . $tkid . '" />
                        ' . $task . '
                    </td>
                    <td>
                        <input name="start_date[]" type="date" min="' . $min_date . '" min="' . $max_date . '" value="' . $start_date . '" id="start_date' . $tkid . '" class="form-control" required="required" onkeyup="validate_dates(' . $tkid . ')" onchange="validate_dates(' . $tkid . ')" required/>
                    </td>
                    <td>
                        <input name="duration[]" type="number" min="1"  step="1" placeholder="Enter Duration" value="' . $duration . '" id="duration' . $tkid . '" class="form-control" required="required" onkeyup="calculate_end_date(' . $tkid . ')" onchange="calculate_end_date(' . $tkid . ')" required/>
                    </td>
                    <td>
                        <input name="end_date[]" type="date" min="' . $min_date . '" min="' . $max_date . '"  value="' . $end_date . '" id="end_date' . $tkid . '" class="form-control" required="required" readonly/>
                    </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => true, "tasks" => $input));
    }

    if (isset($_GET['get_subtasks_edit'])) {
        $site_id = $_GET['site_id'];
        $projid = $_GET['projid'];
        $task_id = $_GET['task_id'];
        $subtask_id = $_GET['subtask_id'];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $min_date = $row_rsProjects['projstartdate'];
            $max_date = $row_rsProjects['projenddate'];
            $implimentation_type = $row_rsProjects['projcategory'];
            if ($implimentation_type == 2) {
                $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
                $query_rsTender->execute(array(":projid" => $projid));
                $row_rsTender = $query_rsTender->fetch();
                $totalRows_rsTender = $query_rsTender->rowCount();
                if ($totalRows_rsTender > 0) {
                    $tenderstartdate = $row_rsTender['startdate'];
                    $tenderenddate = $row_rsTender['enddate'];
                }
            }
        }


        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_task WHERE msid=:task_id AND tkid=:subtask_id");
        $query_rsMilestone->execute(array(":task_id" => $task_id, ":subtask_id" => $subtask_id));
        $row_rsMilestone = $query_rsMilestone->fetch();
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $input = "";
        if ($totalRows_rsMilestone > 0) {
            $counter = "";
            $task = $row_rsMilestone['task'];
            $tkid = $row_rsMilestone['tkid'];
            $counter++;
            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
            $query_rsTask_Start_Dates->execute(array(':task_id' => $task_id, ':site_id' => $site_id, ":subtask_id" => $tkid));
            $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
            $start_date = $end_date = $duration =  "";

            if ($totalRows_rsTask_Start_Dates > 0) {
                $start_date = $row_rsTask_Start_Dates['start_date'];
                $end_date = $row_rsTask_Start_Dates['end_date'];
                $duration = $row_rsTask_Start_Dates['duration'];
                $today = date('Y-m-d');
                $input .= '
                    <tr>
                        <td>' . $counter . '</td>
                        <td>
                            ' . $task . '
                        </td>
                        <td>
                            <input name="duration" type="number" min="1"  step="1" placeholder="Enter Duration  (' . $duration . ')" value="" id="duration' . $tkid . '" class="form-control" required="required" onkeyup="change_calculate_adjust_end_date(' . $tkid . ')" onchange="change_calculate_adjust_end_date(' . $tkid . ')" required/>
                        </td>
                        <input name="tasks" type="hidden" value="' . $tkid . '" />
                        <input name="old_start_date" type="hidden" value="' . $start_date . '" id="old_start_date' . $tkid . '"/>
                        <input name="old_end_date" type="hidden" value="' . $end_date . '" id="old_end_date' . $tkid . '"/>
                        <input name="old_duration" type="hidden" value="' . $duration . '"id="old_duration' . $tkid . '" />
                    </tr>';
            }
        }
        echo json_encode(array("success" => true, "tasks" => $input));
    }


    if (isset($_POST['store_tasks'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $site_id = $_POST['site_id'];
        $task_id = $_POST['task_id'];
        $tasks = $_POST['tasks'];
        $edit = $_POST['store_tasks'];
        $user_name = $_POST['user_name'];
        $start_dates = $_POST['start_date'];
        $durations = $_POST['duration'];
        $end_dates = $_POST['end_date'];
        $current_date = date('Y-m-d');

        for ($i = 0; $i < count($tasks); $i++) {
            $tkid = $tasks[$i];
            $start_date = $start_dates[$i];
            $end_date = $end_dates[$i];
            $duration = $durations[$i];
            if ($edit == 1) {
                $sql = $db->prepare("UPDATE `tbl_program_of_works` SET start_date=:start_date,duration=:duration, end_date=:end_date,updated_by=:updated_by,updated_at=:updated_at WHERE subtask_id=:subtask_id AND site_id=:site_id AND task_id=:task_id");
                $sql->execute(array(':start_date' => $start_date, ':duration' => $duration, ':end_date' => $end_date, ':updated_by' => $user_name, ":updated_at" => $current_date, ":task_id" => $task_id, ':site_id' => $site_id, ":subtask_id" => $tkid));
            } else {
                $sql = $db->prepare("INSERT INTO tbl_program_of_works (projid,output_id,task_id,site_id,subtask_id,start_date,duration,end_date,created_by,created_at) VALUES (:projid,:output_id,:task_id,:site_id,:subtask_id,:start_date,:duration,:end_date,:created_by,:created_at)");
                $results = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id, ":task_id" => $task_id, ":site_id" => $site_id, ":subtask_id" => $tkid, ':start_date' => $start_date, ':duration' => $duration, ':end_date' => $end_date, ":created_by" => $user_name, ':created_at' => $current_date));
            }
        }
        echo json_encode(array("success" => true));
    }

    if (isset($_POST['store_duration'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $site_id = $_POST['site_id'];
        $task_id = $_POST['task_id'];
        $subtask_id = $_POST['tasks'];
        $user_name = $_POST['user_name'];
        $duration = $_POST['duration'];
        $old_duration = $_POST['old_duration'];
        $start_date = $_POST['old_start_date'];
        $old_end_date = $_POST['old_end_date'];
        $current_date = date('Y-m-d');
        $hduration = $duration + $old_duration;
        $end_date =  date('Y-m-d', strtotime($start_date . ' + ' . $hduration . ' days'));

        $sql = $db->prepare("UPDATE `tbl_program_of_works` SET start_date=:start_date,duration=:duration, end_date=:end_date,updated_by=:updated_by,updated_at=:updated_at WHERE subtask_id=:subtask_id AND site_id=:site_id AND task_id=:task_id");
        $sql->execute(array(':start_date' => $start_date, ':duration' => $duration, ':end_date' => $end_date, ':updated_by' => $user_name, ":updated_at" => $current_date, ":task_id" => $task_id, ':site_id' => $site_id, ":subtask_id" => $subtask_id));

        $sql = $db->prepare("INSERT INTO tbl_program_of_work_histories (projid,output_id,task_id,site_id,subtask_id,start_date,duration,end_date,created_by,created_at) VALUES (:projid,:output_id,:task_id,:site_id,:subtask_id,:start_date,:duration,:end_date,:created_by,:created_at)");
        $results = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id, ":task_id" => $task_id, ":site_id" => $site_id, ":subtask_id" => $subtask_id, ':start_date' => $start_date, ':duration' => $old_duration, ':end_date' => $old_end_date, ":created_by" => $user_name, ':created_at' => $current_date));
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
