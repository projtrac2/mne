<?php

function check_monitoring_responsible($projid, $workflow_stage, $team_type)
{
    global $db, $user_name, $user_designation;
    $output_responsible = $standin_responsible = false;
    if ($user_designation == 1) {
        $output_responsible = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
        $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $user_name));
        $total_rsOutput = $query_rsOutput->rowCount();
        $output_responsible = $total_rsOutput > 0 ? true : false;

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND team_type =:team_type");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":team_type" => $team_type));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            $standin_responsible = $total_rsOutput > 0 ? true : false;
        }
    }

    $responsible = $output_responsible || $standin_responsible  ? true : false;
    return $responsible;
}

function check_if_assigned($projid, $workflow_stage, $sub_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $output_responsible = $standin_responsible = false;
    if ($user_designation <= 8) {
        $output_responsible = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
        $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $user_name));
        $total_rsOutput = $query_rsOutput->rowCount();
        $output_responsible = $total_rsOutput > 0 ? true : false;

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            $standin_responsible = $total_rsOutput > 0 ? true : false;
        }
    }

    $responsible = $output_responsible || $standin_responsible ? true : false;
    return $responsible;
}

function check_if_map_responsible($projid, $workflow_stage, $sub_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $member = $responsible = $standin_responsible = $standin_member = false;
    if ($user_designation <= 8) {
        $responsible =   $member = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage");
        $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage));
        $row_rsOutput = $query_rsOutput->fetch();
        $total_rsOutput = $query_rsOutput->rowCount();
        if ($total_rsOutput > 0) {
            $members = explode(",", $row_rsOutput['members']);
            $member = in_array($user_name, $members);
            $responsible = $row_rsOutput['responsible'] == $user_name ? true : false;
        }

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            if ($total_rsOutput > 0) {
                $members = explode(",", $row_rsOutput['members']);
                $member = in_array($owner_id, $members);
                $responsible = $row_rsOutput['responsible'] == $owner_id ? true : false;
            }
        }
    }
    $member = $member || $standin_member ? true : false;

    return  array("member" => $member, "responsible" => $responsible);
}

function  check_if_member($projid, $workflow_stage, $general_stage, $specialized_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $output_responsible = $standin_responsible = false;
    if ($user_designation <= 8) {
        $output_responsible = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND (sub_stage =:sub_stage OR sub_stage=:specialized_substage) AND responsible=:responsible");
        $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $general_stage, ":specialized_substage" => $specialized_stage, ":responsible" => $user_name));
        $total_rsOutput = $query_rsOutput->rowCount();
        $output_responsible = $total_rsOutput > 0 ? true : false;

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND (sub_stage =:sub_stage OR sub_stage=:specialized_substage) AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $general_stage,  ":specialized_substage" => $specialized_stage, ":responsible" => $owner_id));
            $total_rsOutput = $query_rsOutput->rowCount();
            $standin_responsible = $total_rsOutput > 0 ? true : false;
        }
    }

    $responsible = $output_responsible || $standin_responsible ? true : false;
    return $responsible;
}

function check_if_general_member($projid, $workflow_stage, $sub_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $member = $standin_member = $output_responsible = $standin_responsible = false;
    if ($user_designation <= 8) {
        $output_responsible = $member = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
        $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $user_name));
        $row_rsOutput = $query_rsOutput->fetch();
        $total_rsOutput = $query_rsOutput->rowCount();
        if ($total_rsOutput > 0) {
            $role = $row_rsOutput['role'];
            $member = true;
            $output_responsible = $role == 1 > 0 ? true : false;
        }

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
            $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
            $row_rsOutput = $query_rsOutput->fetch();
            $total_rsOutput = $query_rsOutput->rowCount();
            if ($total_rsOutput > 0) {
                $role = $row_rsOutput['role'];
                $standin_member = true;
                $standin_responsible = $role == 1 > 0 ? true : false;
            }
        }
    }

    $responsible = $output_responsible || $standin_responsible ? true : false;
    $member = $member || $standin_member ? true : false;
    return array("responsible" => $responsible, "member" => $member);
}

function get_output_responsible($projid, $output_id, $workflow_stage, $sub_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $member = $responsible = $standin_member = $standin_responsible = false;
    if ($user_designation <= 8) {
        $responsible = $member = true;
    } else {
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND outputid=:output_id AND stage=:workflow_stage AND sub_stage =:sub_stage");
        $query_rsOutput->execute(array(":projid" => $projid, ":output_id" => $output_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage));
        $row = $query_rsOutput->fetch();
        $total_rsOutput = $query_rsOutput->rowCount();

        if ($total_rsOutput > 0) {
            $members = explode(",", $row['member']);
            $member = in_array($user_name, $members);
            $responsible = $row['responsible'] == $user_name ? true : false;
        }

        $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
        $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $row_rsOutput_standin = $query_rsOutput_standin->fetch();
        $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

        if ($total_rsOutput_standin > 0) {
            $owner_id = $row_rsOutput_standin['owner'];

            $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND outputid=:output_id AND stage=:workflow_stage AND sub_stage =:sub_stage");
            $query_rsOutput->execute(array(":projid" => $projid, ":output_id" => $output_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage));
            $row = $query_rsOutput->fetch();
            $total_rsOutput = $query_rsOutput->rowCount();

            if ($total_rsOutput > 0) {
                $standin_members = explode(",", $row['member']);
                $standin_member = in_array($owner_id, $standin_members);
                $standin_responsible = $row['responsible'] == $owner_id ? true : false;
            }
        }
    }

    $member = $member || $standin_member ? true : false;
    $responsible = $responsible || $standin_responsible ? true : false;
    return array("member" => $member, ":responsible" => $responsible);
}

function get_specialist_responsible($projid, $output_id, $workflow_stage, $sub_stage, $activity)
{
    global $db, $user_name, $user_designation;
    $specialist_responsible = false;
    if ($user_designation <= 8) {
        $specialist_responsible = true;
    } else {
        $query_rsOutput_Checklist = $db->prepare("SELECT * FROM tbl_projmembers m INNER JOIN tbl_program_of_works p ON p.task_id = m.task_id WHERE p.progress != 100 AND role_type = 2  AND (p.status=11 OR p.status=4) AND p.projid =:projid AND output_id=:output_id AND stage=:workflow_stage AND sub_stage =:sub_stage AND m.ptid =:user_name");
        $query_rsOutput_Checklist->execute(array(":projid" => $projid, ":output_id" => $output_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":user_name" => $user_name));
        $total_rsOutput_Checklist = $query_rsOutput_Checklist->rowCount();
        $specialist_responsible = $total_rsOutput_Checklist > 0 ? true : false;

        $query_rsOutput_Checklist_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity = :activity ");
        $query_rsOutput_Checklist_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
        $total_rsOutput_Checklist_standin = $query_rsOutput_Checklist_standin->rowCount();

        if ($total_rsOutput_Checklist_standin > 0) {
            $responsible = [];
            while ($row_rsOutput_Checklist_standin = $query_rsOutput_Checklist_standin->fetch()) {
                $owner_id = $row_rsOutput_Checklist_standin['owner'];
                $sql = $db->prepare("SELECT * FROM tbl_projmembers m INNER JOIN tbl_program_of_works p ON p.task_id = m.task_id WHERE p.progress != 100 AND role_type = 2  AND (p.status=11 OR p.status=4) AND p.projid =:projid AND output_id=:output_id AND stage=:workflow_stage AND sub_stage =:sub_stage AND m.ptid =:user_name");
                $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":user_name" => $owner_id));
                $rows = $sql->rowCount();
                $responsible[] = $rows > 0 ? true : false;
            }
            $specialist_responsible = in_array(true, $responsible) ? true : false;
        }
    }
    return $specialist_responsible;
}

function check_if_task_specialist($projid, $output_id, $task_id)
{
    global $db, $user_name, $user_designation;
    $specialist_responsible = false;
    if ($user_designation <= 8) {
        $specialist_responsible = true;
    } else {
        $query_rsOutput_Checklist = $db->prepare("SELECT * FROM tbl_projmembers m INNER JOIN tbl_program_of_works p ON p.task_id = m.task_id WHERE p.progress != 100 AND role_type = 2  AND (p.status=11 OR p.status=4) AND p.projid =:projid AND output_id=:output_id AND task_id=:task_id AND m.ptid =:user_name");
        $query_rsOutput_Checklist->execute(array(":projid" => $projid, ":output_id" => $output_id, ":task_id" => $task_id, ":user_name" => $user_name));
        $total_rsOutput_Checklist = $query_rsOutput_Checklist->rowCount();
        $specialist_responsible = $total_rsOutput_Checklist > 0 ? true : false;

        $query_rsOutput_Checklist_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity = 1");
        $query_rsOutput_Checklist_standin->execute(array(":projid" => $projid, ":user_name" => $user_name));
        $row_rsOutput_Checklist_standin = $query_rsOutput_Checklist_standin->fetch();
        $total_rsOutput_Checklist_standin = $query_rsOutput_Checklist_standin->rowCount();

        if ($total_rsOutput_Checklist_standin > 0) {
            $owner_id = $row_rsOutput_Checklist_standin['owner'];
            $sql = $db->prepare("SELECT * FROM tbl_projmembers m INNER JOIN tbl_program_of_works p ON p.task_id = m.task_id WHERE p.progress != 100 AND role_type = 2  AND (p.status=11 OR p.status=4) AND p.projid =:projid AND output_id=:output_id AND m.ptid =:user_name");
            $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":task_id" => $task_id, ":user_name" => $owner_id));
            $rows = $sql->rowCount();
            $specialist_responsible = $rows > 0 ? true : false;
        }
    }
    return $specialist_responsible;
}
