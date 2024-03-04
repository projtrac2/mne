<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function get_target($site_id, $task_id, $subtask_id, $end_date)
{
    global $db;
    $stmt = $db->prepare('SELECT SUM(target) as target FROM tbl_project_target_breakdown WHERE site_id=:site_id AND task_id=:task_id AND subtask_id=:subtask_id AND end_date =:end_date');
    $stmt->execute(array(':site_id' => $site_id, ':task_id' => $task_id, ":subtask_id" => $subtask_id, ":end_date" => $end_date));
    $result = $stmt->fetch();
    $target = !is_null($result['target'])  ? $result['target'] : 0;
    return $target;
}

function get_achieved($site_id, $task_id, $subtask_id, $end_date)
{
    global $db;
    $stmt = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND task_id=:task_id AND subtask_id=:subtask_id AND created_at <=:end_date');
    $stmt->execute(array(':site_id' => $site_id, ':task_id' => $task_id, ":subtask_id" => $subtask_id, ":end_date" => $end_date));
    $result = $stmt->fetch();
    $target = !is_null($result['achieved'])  ? $result['achieved'] : 0;
    return $target;
}

function set_project_status()
{
    global $db;
    $today = date('Y-m-d');
    $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = 9  and (p.projstatus=3  OR p.projstatus=4 OR p.projstatus=11) AND proj_substage = 0  ORDER BY p.projid DESC");
    $query_rsProjects->execute();
    $totalRows_rsProjects = $query_rsProjects->rowCount();


    echo "<pre/>";
    echo $totalRows_rsProjects . "<br/>";
    if ($totalRows_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $projid = $row_rsProjects['projid'];
            $project_status = $row_rsProjects['projstatus'];
            $status = array();
            $query_rsTask_Start_Dates = $db->prepare("SELECT start_date, end_date, id,subtask_id,site_id, task_id FROM tbl_program_of_works WHERE projid=:projid AND complete=0");
            $query_rsTask_Start_Dates->execute(array(":projid" => $projid));
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

            if ($totalRows_rsTask_Start_Dates > 0) {
                while ($row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch()) {
                    $start_date = $row_rsTask_Start_Dates['start_date'];
                    $end_date = $row_rsTask_Start_Dates['end_date'];
                    $subtask_id = $row_rsTask_Start_Dates['subtask_id'];
                    $task_id = $row_rsTask_Start_Dates['task_id'];
                    $site_id = $row_rsTask_Start_Dates['site_id'];
                    $id = $row_rsTask_Start_Dates['id'];

                    $achieved = get_achieved($site_id, $task_id, $subtask_id, $today);
                    $subtask_status = $achieved ? 4 : 3;
                    if ($today >= $start_date) {
                        $subtask_status = 4;
                        if ($today > $end_date) {
                            $subtask_status = 11;
                        } else {
                            $frequency_end_date = date('Y-m-d', strtotime($today . '- 1 days'));
                            $target = get_target($site_id, $task_id, $subtask_id, $frequency_end_date);
                            if ($achieved <  $target) {
                                $subtask_status = 11;
                            }
                        }
                    }

                    $status[] = $projid;
                    var_dump($projid);
                    $sql = $db->prepare("UPDATE tbl_program_of_works SET status=:status WHERE  id=:id");
                    $sql->execute(array(":status" => $subtask_status, ":id" => $id));
                }
            }

            $query_rsActivity_Monitoring = $db->prepare("SELECT start_date, end_date, id,subtask_id,site_id FROM tbl_program_of_works WHERE projid=:projid AND complete=0");
            $query_rsActivity_Monitoring->execute(array(":projid" => $projid));
            $totalRows_rsActivity_Monitoring = $query_rsActivity_Monitoring->rowCount();
            $activity_monitoring = $totalRows_rsActivity_Monitoring > 0 ? false : true;

            $query_Output_Monitoring = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid AND complete =0");
            $query_Output_Monitoring->execute(array(":projid" => $projid));
            $total_Output_Monitoring = $query_Output_Monitoring->rowCount();
            $output_monitoring = $total_Output_Monitoring > 0 ? false : true;

            $query_rsIssues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND status <> 7");
            $query_rsIssues->execute(array(":projid" => $projid));
            $total_rsIssues = $query_rsIssues->rowCount();
            $issues = $total_rsIssues > 0 ? false : true;

            $complete = $activity_monitoring && $output_monitoring && $issues ? true : false;

            $substage_id = 0;
            if (!$complete) {
                if (in_array(11, $status)) {
                    $project_status = 11;
                } else if (in_array(4, $status)) {
                    $project_status = 4;
                }
            } else {
                $project_status = 5;
                $substage_id = 1;
            }

            $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, proj_substage=:substage_id WHERE  projid=:projid");
            $result  = $sql->execute(array(":projstatus" => $project_status, ":substage_id" => $substage_id, ":projid" => $projid));
        }
    }
}

set_project_status();
