<?php
function calculate_project_progress($projid, $implimentation_type)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 ");
        $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE projid =:projid ");
        $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid=:projid");
    $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

    $progress = 0;
    $cost_used = 0;
    $project_complete = [];
    if ($totalRows_rsTask_Start_Dates > 0) {
        while ($Rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch()) {
            $subtask_id = $Rows_rsTask_Start_Dates['subtask_id'];
            $site_id = $Rows_rsTask_Start_Dates['site_id'];
            $complete = $Rows_rsTask_Start_Dates['complete'];

            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT unit_cost , units_no FROM tbl_project_direct_cost_plan WHERE projid =:projid AND site_id=:site_id AND subtask_id=:subtask_id AND cost_type=1 ");
            $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();

            if ($implimentation_type == 2) {
                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT unit_cost , units_no FROM tbl_project_tender_details WHERE projid =:projid AND site_id=:site_id AND subtask_id=:subtask_id");
                $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            }

            $units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
            $unit_cost = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['unit_cost'] : 0;
            $cost = $units * $unit_cost;
            $cost_used += $units * $unit_cost;
            $percentage = 100;

            if ($complete == 0) {
                $project_complete[] = false;
                $query_rsPercentage =  $db->prepare("SELECT SUM(achieved)  as achieved FROM tbl_project_monitoring_checklist_score WHERE projid =:projid  AND site_id=:site_id AND subtask_id=:subtask_id");
                $query_rsPercentage->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
                $row_rsPercentage = $query_rsPercentage->fetch();
                $sub_percentage = $row_rsPercentage['achieved'] != null ?  ($row_rsPercentage['achieved'] / $units) * 100 : 0;
                $percentage = $sub_percentage >= 100 ? 99 : $sub_percentage;
            }

            $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
        }
        $progress =  !in_array(false, $project_complete) ? 100 : $progress;
    }

    return $progress;
}

function calculate_output_progress($output_id, $implimentation_type)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE outputid =:output_id AND cost_type=1 ");
        $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE outputid =:output_id ");
        $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.outputid =:output_id ");
    $query_rsPercentage->execute(array(":output_id" => $output_id));
    $progress = 0;
    while ($row_rsPercentage = $query_rsPercentage->fetch()) {
        $subtask_id = $row_rsPercentage['subtask_id'];
        $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
        $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;

        if ($percentage >= 100) {
            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1");
            $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id));
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
            $percentage = $totalRows_rsTask_Start_Dates > 1 ? 100 : 99;
        }
        $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
    }
    return $progress;
}

function calculate_task_progress($task_id, $implimentation_type)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE tasks =:task_id AND cost_type=1 ");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $task_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE tasks =:task_id ");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $task_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE task_id =:task_id ");
    $query_rsPercentage->execute(array(":task_id" => $task_id));
    $task_progress = 0;
    while ($row_rsPercentage = $query_rsPercentage->fetch()) {
        $subtask_id = $row_rsPercentage['subtask_id'];
        $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
        $progress =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1");
        $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id));
        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
        $complete = $totalRows_rsTask_Start_Dates > 1 ? true : false;
        $progress = $progress >= 100 && !$complete ? 99 : $progress;
        $task_progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $progress : 0;
    }
    return $task_progress;
}

function calculate_subtask_progress($subtask_id)
{
    global $db;
    $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE subtask_id =:subtask_id ");
    $query_rsPercentage->execute(array(":subtask_id" => $subtask_id));
    $row_rsPercentage = $query_rsPercentage->fetch();
    $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;

    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id AND site_id=:site_id  AND complete=1");
    $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id));
    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
    $complete = $totalRows_rsTask_Start_Dates > 1 ? true : false;
    return $progress >= 100 && !$complete ? 99 : $progress;
}

function calculate_output_site_progress($output_id, $implimentation_type, $site_id)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE outputid =:output_id AND cost_type=1  AND site_id=:site_id");
        $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE outputid =:output_id  AND site_id=:site_id");
        $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.outputid =:output_id AND d.site_id=:site_id");
    $query_rsPercentage->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
    $progress = 0;
    while ($row_rsPercentage = $query_rsPercentage->fetch()) {
        $subtask_id = $row_rsPercentage['subtask_id'];
        $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
        $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;

        if ($percentage >= 100) {
            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1 AND site_id=:site_id");
            $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
            $percentage = $totalRows_rsTask_Start_Dates > 1 ? 100 : 99;
        }
        $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
    }

    return $progress;
}

function calculate_task_site_progress($task_id, $implimentation_type, $site_id)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE tasks =:task_id AND cost_type=1  AND site_id = :site_id");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $task_id, ':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE tasks =:task_id  AND site_id=:site_id");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $task_id, ':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE task_id =:task_id  AND s.site_id=:site_id");
    $query_rsPercentage->execute(array(":task_id" => $task_id, ':site_id' => $site_id));
    $task_progress = 0;
    while ($row_rsPercentage = $query_rsPercentage->fetch()) {
        $subtask_id = $row_rsPercentage['subtask_id'];
        $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
        $progress =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1 AND site_id=:site_id");
        $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
        $complete = $totalRows_rsTask_Start_Dates > 1 ? true : false;
        $progress = $progress >= 100 && !$complete ? 99 : $progress;
        $task_progress += $cost / $direct_cost * $progress;
    }
    return $task_progress;
}

function calculate_subtask_site_progress($subtask_id, $site_id)
{
    global $db;
    $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.subtask_id =:subtask_id AND s.site_id=:site_id");
    $query_rsPercentage->execute(array(":subtask_id" => $subtask_id, ':site_id' => $site_id));
    $row_rsPercentage = $query_rsPercentage->fetch();
    $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;

    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id AND site_id=:site_id  AND complete=1 AND site_id=:site_id");
    $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
    $complete = $totalRows_rsTask_Start_Dates > 1 ? true : false;
    return $progress >= 100 && !$complete ? 99 : $progress;
}

function calculate_site_progress($implimentation_type, $site_id)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE cost_type=1  AND site_id=:site_id");
        $query_rsOther_cost_plan_budget->execute(array(':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE site_id=:site_id");
        $query_rsOther_cost_plan_budget->execute(array(':site_id' => $site_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.site_id=:site_id");
    $query_rsPercentage->execute(array(':site_id' => $site_id));
    $progress = 0;
    while ($row_rsPercentage = $query_rsPercentage->fetch()) {
        $subtask_id = $row_rsPercentage['subtask_id'];
        $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
        $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;

        if ($percentage >= 100) {
            $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1 AND site_id=:site_id");
            $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
            $percentage = $totalRows_rsTask_Start_Dates > 1 ? 100 : 99;
        }
        $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
    }

    return $progress;
}


function get_project_progress($progress)
{
    $project_progress = '
    <div class="progress" style="height:20px; font-size:10px; color:black">
        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
            ' . number_format($progress, 2) . '%
        </div>
    </div>';
    if ($progress == 100) {
        $project_progress = '
        <div class="progress" style="height:20px; font-size:10px; color:black">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
            ' . number_format($progress, 2) . '%
            </div>
        </div>';
    }

    return $project_progress;
}



