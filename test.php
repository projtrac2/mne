<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
$today = date('Y-m-d');
function get_due_date($projid, $stage_id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM tbl_project_stage_actions WHERE projid=:projid AND stage=:stage_id AND sub_stage=0");
    $stmt->execute(array(":projid" => $projid, ":stage_id" => $stage_id));
    $rows_stmt = $stmt->fetch();
    $total_stmt = $stmt->rowCount();

    $sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE priority=:stage_id ");
    $sql->execute(array(":stage_id" => $stage_id));
    $rows_count = $sql->rowCount();
    $row = $sql->fetch();
    $due_date = '';
    if ($rows_count > 0 && $total_stmt > 0) {
        $start_date = $rows_stmt['created_at'];
        $timeline = $row["timeline"];
        $due_date = date('Y-m-d', strtotime($start_date . ' + ' . $timeline . ' days'));
    }
    return $due_date;
}

function get_contract_dates($projid)
{
    global $db;
    $query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
    $query_rsContractDates->execute(array(":projid" => $projid));
    $row_rsContractDates = $query_rsContractDates->fetch();
    $totalRows_rsContractDates = $query_rsContractDates->rowCount();

    return $totalRows_rsContractDates > 0 ? $row_rsContractDates : false;
}

function get_cummulative_target($site_id, $subtask_id, $target_end_date)
{
    global $db;
    $sql_target = $db->prepare('SELECT SUM(target) as target FROM tbl_project_target_breakdown WHERE site_id=:site_id AND subtask_id=:subtask_id AND end_date <=:end_date');
    $sql_target->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $target_end_date));
    $rows_target_result = $sql_target->fetch();
    return !is_null($rows_target_result['target']) ? $rows_target_result['target'] : 0;
}

function get_subtask_achieved($site_id, $subtask_id, $breakdown_end_date, $target, $subtask_status_id, $monitoring_frequency)
{
    global $db;
    $stmt = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id AND created_at <=:end_date');
    $stmt->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $breakdown_end_date));
    $result = $stmt->fetch();

    if (is_null($result['achieved'])) {
        $achieved = $result['achieved'];
        $subtask_status_id = ($achieved >= $target) ? 4 : 11;
    }


    if ($monitoring_frequency != 1) {
        $sql = $db->prepare('SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id AND created_at > :end_date ORDER BY id ASC LIMIT 1');
        $sql->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $breakdown_end_date));
        $row = $sql->fetch();
        $achieved = 0;
        if ($row) {
            $achieved_end_date = $row["created_at"];
            $stmt = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id AND created_at <=:end_date');
            $stmt->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id, ":end_date" => $achieved_end_date));
            $result = $stmt->fetch();
            if (is_null($result['achieved'])) {
                $achieved = $result['achieved'];
                $subtask_status_id = ($achieved >= $target) ? 4 : 11;
            }
        }
    }

    return $subtask_status_id;
}

function get_sub_achieved($site_id, $subtask_id)
{
    global $db;
    $sql = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id');
    $sql->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id));
    $row = $sql->fetch();
    return !is_null($row) ? $row['achieved'] : 0;
}

function get_schedule_dates($projid)
{
    global $db;
    $query_rsSchedule = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid");
    $query_rsSchedule->execute(array(":projid" => $projid));
    $row_rsSchedule = $query_rsSchedule->fetch();
    return !is_null($row_rsSchedule['start_date']) ? $row_rsSchedule : false;
}

function get_subtask_status($projid, $monitoring_frequency)
{
    global $db, $today;
    $project_status = [];
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
            $subtask_end_date = $row_rsWorkBreakdown['end_date'];
            $subtask_start_date = $row_rsWorkBreakdown['start_date'];
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Contract Start date" . $subtask_start_date . "  Subtask End Date=>" . $subtask_end_date . ")<br/>";
            if ($complete == 0) {
                if ($today < $subtask_start_date) {
                    $sql = $db->prepare('SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND subtask_id=:subtask_id');
                    $sql->execute(array(':site_id' => $site_id, ":subtask_id" => $subtask_id));
                    $row = $sql->fetch();
                    $subtask_status_id = $row ? 4 : 3;
                } else if ($today >= $subtask_start_date && $today <= $subtask_end_date) {
                    $stmt = $db->prepare('SELECT * FROM tbl_project_target_breakdown WHERE projid=:projid AND start_date <= :today AND end_date >= :today');
                    $stmt->execute(array(':projid' => $projid, ":today" => $today));
                    $stmt_result = $stmt->rowCount();
                    $result = $stmt->fetch();
                    $subtask_status_id = 4;
                    if ($stmt_result > 0) {
                        $breakdown_start_date = $result['start_date'];
                        $target_end_date = date('Y-m-d', strtotime($breakdown_start_date . ' - 1 days'));
                        $target = get_cummulative_target($site_id, $subtask_id, $target_end_date);
                        $subtask_status_id = get_subtask_achieved($site_id, $subtask_id, $target_end_date, $target, $subtask_status_id, $monitoring_frequency);
                    }
                }
            }

            $project_status[] = $subtask_status_id;
            $sql = $db->prepare("UPDATE tbl_program_of_works SET status=:status WHERE  id=:id");
            $sql->execute(array(":status" => $subtask_status_id, ":id" => $id));
        }
    }

    $status_id = 3;
    if (!empty($project_status)) {
        if (in_array(11, $project_status)) {
            $status_id = 11;
        } else if (in_array(4, $project_status)) {
            $status_id = 4;
        } else if (in_array(5, $project_status)) {
            $status_id = 5;
        }
    }

    return $status_id;
}

function get_implementation_status($projid)
{
    global $db;
    // activty
    $query_rsActivity_Monitoring = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid=:projid AND complete=0");
    $query_rsActivity_Monitoring->execute(array(":projid" => $projid));
    $totalRows_rsActivity_Monitoring = $query_rsActivity_Monitoring->rowCount();
    $activity_monitoring = $totalRows_rsActivity_Monitoring > 0 ? false : true;

    // out
    $query_Output_Monitoring = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid AND complete =0");
    $query_Output_Monitoring->execute(array(":projid" => $projid));
    $total_Output_Monitoring = $query_Output_Monitoring->rowCount();
    $output_monitoring = $total_Output_Monitoring > 0 ? false : true;

    //
    $query_rsIssues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND status <> 7");
    $query_rsIssues->execute(array(":projid" => $projid));
    $total_rsIssues = $query_rsIssues->rowCount();
    $issues = $total_rsIssues > 0 ? false : true;

    return $activity_monitoring && $output_monitoring && $issues ? true : false;
}

function update_project_status()
{
    global $db, $today;
    $query_workflow = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE parent=0 AND  priority=2 ORDER BY `priority` ASC");
    $query_workflow->execute();
    $rows_count = $query_workflow->rowCount();
    if ($rows_count > 0) {
        while ($row = $query_workflow->fetch()) {
            $stage_id = $row['priority'];
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE stage_id =:stage_id");
            $query_rsProjects->execute(array(":stage_id" => $stage_id));
            $total_rsProjects = $query_rsProjects->rowCount();
            if ($total_rsProjects > 0) {
                while ($row_rsProjects = $query_rsProjects->fetch()) {
                    $projid = $row_rsProjects['projid'];
                    $projname = $row_rsProjects['projname'];
                    $child_stage_id = $row_rsProjects['projstage'];
                    $grand_stage_id = $row_rsProjects['proj_substage'];
                    $implimentation_type =  $row_rsProjects['projcategory'];
                    $status_id =  $row_rsProjects['projstatus'];
                    $monitoring_frequency =  $row_rsProjects['monitoring_frequency'];

                    if ($stage_id == 1) {
                        $due_date = get_due_date($projid, $child_stage_id);
                        $status_id = $today < $due_date ? 3 : 11;
                    } else if ($stage_id == 2) {
                        $project_schedule = get_schedule_dates($projid);
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; =>" . $projname . " (" . $status_id . ") <br/>";
                        if ($child_stage_id == 1) {
                            $change_substage = get_implementation_status($projid);
                            if ($change_substage) {
                                $grand_stage_id = $change_substage ? 2 : 1;
                            } else {
                                if ($project_schedule) {
                                    $end_date = $project_schedule['end_date'];
                                    // echo " ****&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => End date" . $end_date . " (" . $status_id . ") <br/>";
                                    if ($implimentation_type == 2) {
                                        $contract_details = get_contract_dates($projid);
                                        $contract_end_date = ($contract_details) ? date('Y-m-d', strtotime($contract_details["enddate"])) : "";

                                        if ($today > $contract_end_date) {
                                            $status_id = 11;
                                            get_subtask_status($projid, $monitoring_frequency);
                                        } else {
                                            $status_id = get_subtask_status($projid, $monitoring_frequency);
                                        }

                                        // echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Contract Start date" . $contract_end_date . " (" . $status_id . ") <br/>";
                                    } else {
                                    echo " ****&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => End date" . $end_date . " (" . $status_id . ") <br/>";

                                        if ($today > $end_date) {
                                            $status_id = 11;
                                        } else {
                                            $status_id = get_subtask_status($projid, $monitoring_frequency);
                                        }
                                    }
                                }
                            }
                        } else {
                            $contract_details = get_contract_dates($projid);
                            $contract_end_date = ($contract_details) ? date('Y-m-d', strtotime($contract_details["enddate"])) : "";

                            if ($today > $contract_end_date) {
                                $status_id = 11;
                                get_subtask_status($projid, $monitoring_frequency);
                            }
                        }
                    }
                    $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, proj_substage=:substage_id WHERE  projid=:projid");
                    $sql->execute(array(":projstatus" => $status_id, ":substage_id" => $grand_stage_id, ":projid" => $projid));
                }
            }
        }
    }
}

function get_all_projects_status($status_id)
{
    global $db;
    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
    $query_Projstatus->execute(array(":projstatus" => $status_id));
    $row_Projstatus = $query_Projstatus->fetch();
    $total_Projstatus = $query_Projstatus->rowCount();
    $status = "";
    if ($total_Projstatus > 0) {
        $status_name = $row_Projstatus['statusname'];
        $status_class = $row_Projstatus['class_text_color'];
        $status = '<span class="' . $status_class . '">' . $status_name . '</span>';
    }
    return $status;
}

function get_project_status($status_id)
{
    global $db;
    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
    $query_Projstatus->execute(array(":projstatus" => $status_id));
    $row_Projstatus = $query_Projstatus->fetch();
    $total_Projstatus = $query_Projstatus->rowCount();
    $status = "";
    if ($total_Projstatus > 0) {
        $status_name = $row_Projstatus['statusname'];
        $status_class = $row_Projstatus['class_name'];
        $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
    }
    return $status;
}

function get_status($status_id)
{
    global $db;
    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :status_id");
    $query_Projstatus->execute(array(":status_id" => $status_id));
    $row_Projstatus = $query_Projstatus->fetch();
    $total_Projstatus = $query_Projstatus->rowCount();
    $status = "";
    if ($total_Projstatus > 0) {
        $status_name = $row_Projstatus['statusname'];
        $status_class = $row_Projstatus['class_name'];
        $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
    }
    return $status;
}

function get_project_stage($stage_id)
{
    global $db;
    $query_Workflow_Stage =  $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority = :stage_id");
    $query_Workflow_Stage->execute(array(":stage_id" => $stage_id));
    $row_Workflow_Stage = $query_Workflow_Stage->fetch();
    $total_Workflow_Stage = $query_Workflow_Stage->rowCount();
    return ($total_Workflow_Stage > 0) ? $row_Workflow_Stage['stage']  : '';
}

function get_stage_status($projid, $workflow_stage, $sub_stage)
{
    global $db, $user_designation, $user_name, $today, $due_date;
    $activity_status = "";
    if ($user_designation == 1 || $user_designation <= 7) {
        if ($today > $due_date) {
            $activity_status = "<label class='label label-danger'>Behind Schedule</label>";
        } else {
            if ($sub_stage > 1) {
                $activity_status = "<label class='label label-primary'>Pending Approval</label>";
                if ($sub_stage == 3) {
                    $activity_status = "<label class='label label-success'>Assigned Approval</label>";
                }
            } else {
                $activity_status = "<label class='label label-primary'>Pending Data Entry</label>";
                if ($sub_stage == 1) {
                    $activity_status = "<label class='label label-success'>Assigned Data Entry</label>";
                }
            }
        }
    } else {
        $query_rsResponsible = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
        $query_rsResponsible->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $user_name));
        $total_rsResponsible = $query_rsResponsible->rowCount();
        if ($total_rsResponsible > 0) {
            if ($today > $due_date) {
                $activity_status = "<label class='label label-danger'>Behind Schedule</label>";
            } else {
                $activity_status = "<label class='label label-primary'>Pending Data Entry</label>";
                if ($sub_stage > 1) {
                    $activity_status = "<label class='label label-primary'>Pending Approval</label>";
                }
            }
        }
    }
    return $activity_status;
}


update_project_status();
