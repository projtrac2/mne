<?php


function calculate_duration($date_one, $date_two)
{
    $date1 = new DateTime($date_one);
    $date2 = new DateTime($date_two);
    $interval = $date1->diff($date2);
    return $interval->days;
}


function get_activity_projects()
{
    global $db;
    $team_type = 4;
    $workflow_stage = 9;
    $user_name = 1;
    
    $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = 10  and (p.projstatus=3  OR p.projstatus=4 OR p.projstatus=11) ORDER BY p.projid DESC");
    $query_rsProjects->execute();
    $totalRows_rsProjects = $query_rsProjects->rowCount();

    if ($totalRows_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $projid = $row_rsProjects['projid'];
            $query_rsTeam = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
            $query_rsTeam->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $user_name));
            $total_rsTeam = $query_rsTeam->rowCount();
            if ($total_rsTeam > 0) {
                $query_rsTasks = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks m INNER JOIN tbl_member_subtasks t ON t.subtask_id = m.subtask_id WHERE t.member_id=:member_id");
                $query_rsTasks->execute(array(":member_id" => $user_name));
                $totalRows_rsTasks = $query_rsTasks->rowCount();
                if ($totalRows_rsTasks > 0) {
                    while ($Rows_rsTasks = $query_rsTasks->fetch()) {
                        $subtask_id = $Rows_rsTasks['subtask_id'];
                        $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id = :subtask_id AND complete=0 ");
                        $query_rsPlan->execute(array(":subtask_id" => $subtask_id));
                        $totalRows_plan = $query_rsPlan->rowCount();
                        if ($totalRows_plan > 0) {
                            while ($Rows_plan = $query_rsPlan->fetch()) {
                                $start_date = $Rows_plan['start_date'];
                                $end_date = $Rows_plan['end_date'];
                                $today = date('Y-m-d');

                                if ($start_date > $today) {
                                    $timeline = 10;
                                    if (calculate_duration($today, $start_date) == $timeline) {
                                        $subtask_reminder[] = $subtask_id;
                                    }
                                } else {
                                    if ($start_date == $today) {
                                        $subtask_alerts[] = $subtask_id;
                                    } else {
                                        if ($today > $start_date) {// escalation of the subtask
                                            if ($today > $end_date) {
                                                $subtask_escalations[] = $subtask_id;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
