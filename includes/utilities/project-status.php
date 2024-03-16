<?php

function get_achieved($site_id, $subtask_id, $end_date)
{
    global $db;
    $stmt = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id AND created_at <=:end_date');
    $stmt->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $end_date));
    $result = $stmt->fetch();
    $achieved = !is_null($result['achieved'])  ? $result['achieved'] : 0;
    return $achieved;
}

function get_target($site_id, $subtask_id, $end_date)
{
    global $db;
    $stmt = $db->prepare('SELECT * FROM tbl_project_target_breakdown WHERE site_id=:site_id AND subtask_id=:subtask_id AND end_date <=:end_date');
    $stmt->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $end_date));
    $stmt_result = $stmt->rowCount();
    $result = $stmt->fetch();
    $target = $stmt_result > 0 ? $result['target'] : 0;
    return $target;
}


$today = date('Y-m-d');

function change_status()
{
    global $today, $db;
    $query_rsMyP = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND p.projstage >= 8  AND projstatus <> 5");
    $query_rsMyP->execute();
    $count_rsMyP = $query_rsMyP->rowCount();

    if ($count_rsMyP > 0) {
        while ($row_rsMyP = $query_rsMyP->fetch()) {
            $projid = $row_rsMyP['projid'];
            $status_id = $row_rsMyP['projstatus'];
            $stage_id = $row_rsMyP['projstage'];
            $substage_id = $row_rsMyP['proj_substage'];
            $implimentation_type =  $row_rsMyP['projcategory'];
            $updated_at =  $row_rsMyP['date_updated'];
            $start_date =  date('Y-m-d', strtotime($row_rsMyP['projstartdate']));
            $end_date =  date('Y-m-d', strtotime($row_rsMyP['projenddate']));

            $program_of_works_duration = 14;
            if ($implimentation_type == 2) {
                $query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
                $query_rsContractDates->execute(array(":projid" => $projid));
                $row_rsContractDates = $query_rsContractDates->fetch();
                $totalRows_rsContractDates = $query_rsContractDates->rowCount();
                if ($totalRows_rsContractDates > 0) {
                    $start_date = date('Y-m-d', strtotime($row_rsContractDates["startdate"]));
                    $end_date = date('Y-m-d', strtotime($row_rsContractDates["enddate"]));
                    $program_of_works_duration = $row_rsContractDates["program_of_works_duration"];
                }
            }

            if ($stage_id == 8) {
                if ($today > $start_date) {
                    if ($substage_id == 0) {
                        $status_id = 11;
                    } else {
                        $status_id = 4;
                        $grace_period_end_date = date('Y-m-d', strtotime($start_date . ' + ' . $program_of_works_duration . ' days'));
                        if ($today > $grace_period_end_date) {
                            $status_id = 11;
                        }
                    }
                }
            } else {
                if ($today > $end_date) {
                    $status_id = 11;
                } else {
                    if ($status_id == 6) {
                        $date1 = new DateTime($updated_at);
                        $date2 = new DateTime($today);
                        $interval = $date1->diff($date2);
                        $duration = $interval->format("%a");
                        if ($duration >= 365) {
                            $status_id = 14;
                        }
                    } else {
                        $status = [];

                        $query_rsWorkBreakdown = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid=:projid");
                        $query_rsWorkBreakdown->execute(array(':projid' => $projid));
                        $rows_rsWorkBreakdown = $query_rsWorkBreakdown->rowCount();
                        if ($rows_rsWorkBreakdown > 0) {
                            while ($row_rsWorkBreakdown = $query_rsWorkBreakdown->fetch()) {
                                $id = $row_rsWorkBreakdown['id'];
                                $site_id = $row_rsWorkBreakdown['site_id'];
                                $subtask_id = $row_rsWorkBreakdown['subtask_id'];
                                $subtask_status_id = $row_rsWorkBreakdown['status'];
                                $complete = $row_rsWorkBreakdown['complete'];
                                if (!$complete) {
                                    $stmt = $db->prepare('SELECT * FROM tbl_project_target_breakdown WHERE projid=:projid AND start_date <= :today AND end_date >= :today');
                                    $stmt->execute(array(':projid' => $projid, ":today" => $today));
                                    $stmt_result = $stmt->rowCount();
                                    $result = $stmt->fetch();
                                    if ($stmt_result > 0) {
                                        $subtask_start_date = $result['start_date'];
                                        $subtask_end_date = $result['end_date'];
                                        $target_end_date = date('Y-m-d', strtotime($start_date . ' - 1 days'));

                                        $sql = $db->prepare('SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id AND created_at >=:start_date  AND created_at <=:end_date ORDER BY id ASC LIMIT 1');
                                        $sql->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":start_date" => $subtask_start_date, ":end_date" => $subtask_end_date));
                                        $row = $sql->fetch();
                                        $achieved_end_date = $row["created_at"];

                                        $achieved = get_achieved($site_id, $subtask_id, $achieved_end_date);
                                        $sql_target = $db->prepare('SELECT * FROM tbl_project_target_breakdown WHERE site_id=:site_id AND subtask_id=:subtask_id AND end_date =:end_date');
                                        $sql_target->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $target_end_date));
                                        $sql_target_result = $sql_target->rowCount();
                                        if ($sql_target_result == 0) {
                                            $subtask_status_id = 4;
                                        } else {
                                            $target = get_target($site_id, $subtask_id, $target_end_date);
                                            $subtask_status_id = 4;
                                            if ($achieved < $target) {
                                                $subtask_status_id = 11;
                                            }
                                        }
                                    }
                                }

                                $status[] = $subtask_status_id;
                                $sql = $db->prepare("UPDATE tbl_program_of_works SET status=:status WHERE  id=:id");
                                $sql->execute(array(":status" => $subtask_status_id, ":id" => $id));
                            }
                        }

                        $status_id = 3;
                        if (in_array(11, $status)) {
                            $status_id = 11;
                        } else if (in_array(4, $status)) {
                            $status_id = 4;
                        }
                    }
                }
            }

            $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, proj_substage=:substage_id WHERE  projid=:projid");
            $result  = $sql->execute(array(":projstatus" => $status_id, ":substage_id" => $substage_id, ":projid" => $projid));
        }
    }
}
