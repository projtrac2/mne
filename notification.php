<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");
include_once('./Models/Email.php');
include_once('./Models/Connection.php');
include_once('./vendor/autoload.php');


function update_projects_status()
{
    global $db;
    $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = 10  and (p.projstatus=3  OR p.projstatus=4 OR p.projstatus=11) ORDER BY p.projid DESC");
    $query_rsProjects->execute();
    $totalRows_rsProjects = $query_rsProjects->rowCount();

    echo "<pre/>";
    if ($totalRows_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $projid = $row_rsProjects['projid'];
            $projstatus = $row_rsProjects['projstatus'];
            // $activity_monitoring_frequency = $row_rsProjects['activity_monitoring_frequency'];
            // $monitoring_frequency = $row_rsProjects['monitoring_frequency'];
            $next_monitoring_date = $row_rsProjects['next_monitoring_date'];

            $query_rsTask_Start_Dates = $db->prepare("SELECT start_date, end_date, id,subtask_id,site_id FROM tbl_program_of_works WHERE projid=:projid AND complete=0 AND start_date>:next_monitoring_date");
            $query_rsTask_Start_Dates->execute(array(":projid" => $projid, ":next_monitoring_date" => $next_monitoring_date));
            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

            var_dump($totalRows_rsTask_Start_Dates);

            // if ($totalRows_rsTask_Start_Dates > 0) {
            //     $status = array();
            //     while ($row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch()) {
            //         $start_date = $row_rsTask_Start_Dates['start_date'];
            //         $end_date = $row_rsTask_Start_Dates['end_date'];
            //         $id = $row_rsTask_Start_Dates['id'];
            //         $subtask_id = $row_rsTask_Start_Dates['subtask_id'];
            //         $site_id = $row_rsTask_Start_Dates['site_id'];
            //         $today = date('Y-m-d');
            //         $query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND site_id=:site_id ");
            //         $query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':site_id' => $site_id));
            //         $row_rsMilestone_cummulative = $query_rsMilestone_cummulative->fetch();
            //         $achieved =  $row_rsMilestone_cummulative['cummulative'] != null ? $row_rsMilestone_cummulative['cummulative'] : 0;

            //         $subtask_status = 3;
            //         if ($start_date > $today && $achieved > 0) {
            //             $subtask_status = 4;
            //         } else if ($today > $start_date) {
            //             $subtask_status = 4;
            //             if ($today < $end_date) {
            //                 $subtask_status = 4;
            //                 if ($achieved == 0) {
            //                     $subtask_status = 11;
            //                 }
            //             } else if ($today > $end_date) {
            //                 $subtask_status = 11;
            //             }
            //         } else if ($today == $start_date) {
            //             $subtask_status = 4;
            //         }

            //         $status[] = $subtask_status;
            //         $sql = $db->prepare("UPDATE tbl_program_of_works SET status=:status WHERE  id=:id");
            //         $sql->execute(array(":status" => $subtask_status, ":id" => $id));
            //     }

            //     $projstatus = 3;
            //     if (in_array(11, $status)) {
            //         $projstatus = 11;
            //     } else if (in_array(4, $status)) {
            //         $projstatus = 4;
            //     }
            // } else {
            //     $query_rsTask_Start_Dates = $db->prepare("SELECT start_date, end_date, id,subtask_id,site_id FROM tbl_program_of_works WHERE projid=:projid AND complete=0");
            //     $query_rsTask_Start_Dates->execute(array(":projid" => $projid));
            //     $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

            //     $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid AND complete =0");
            //     $query_Output->execute(array(":projid" => $projid));
            //     $total_Output = $query_Output->rowCount();

            //     $query_rsIssues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND status <> 7");
            //     $query_rsIssues->execute(array(":projid" => $projid));
            //     $total_rsIssues = $query_rsIssues->rowCount();
            //     $projstatus = ($totalRows_rsTask_Start_Dates == 0 && $total_rsIssues == 0 && $total_Output  == 0) ? 5 : $projstatus;
            // }

            // $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus WHERE  projid=:projid");
            // $result  = $sql->execute(array(":projstatus" => $projstatus, ":projid" => $projid));
        }
    }
}
update_projects_status();
