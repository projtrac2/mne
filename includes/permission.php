<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");

require 'vendor/autoload.php';
include "Models/Auth.php";
include "Models/Company.php";
include "Models/Permission.php";
require 'Models/Connection.php';
session_start();
(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";

$user_name = $_SESSION['MM_Username'];
$designation_id = $_SESSION['designation'];
$department_id = $_SESSION['ministry'];
$section_id = $_SESSION['sector'];
$directorate_id = $_SESSION['directorate'];
$avatar = $_SESSION['avatar'];
$fullname = $_SESSION['fullname'];
$designation = $designation_id;

$today = date('Y-m-d');


$user_department = $department_id;
$user_section = $section_id;
$user_directorate = $directorate_id;
$user_designation = $designation_id;

function get_current_url()
{
    $current_page_url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_components = parse_url($current_page_url);

    if (isset($url_components['query'])) { // getting the parameters
        parse_str($url_components['query'], $params);
    }

    $paths = explode("/", $url_components['path']); // getting the filename
    $path = isset($paths[2]) ? explode(".", $paths[2]) : explode(".", $paths[1]); // production server
    return $path[0];
}

function page_sector($page_id) // validating pages that are department specific
{
    global $db, $designation_id, $department_id, $section_id, $directorate_id;
    $sql = $db->prepare("SELECT * FROM tbl_page_sectors  WHERE page_id=:page_id ");
    $sql->execute(array(":page_id" => $page_id));
    $rows = $sql->fetch();
    $total_rows = $sql->rowCount();
    $result = true;

    if ($total_rows > 0) {
        $page_department = $rows['department_id'];
        $page_sector = $rows['sector_id'];
        $page_directorate = $rows['directorate_id'];
        $result = false;
        if ($designation_id == 1) {
            $result = true;
        } else if ($designation_id == 5) {
            if ($page_department == $department_id) {
                $result = true;
            }
        } else if ($designation_id == 6) {
            if ($page_department == $department_id) {
                if ($page_sector == $section_id) {
                    $result = true;
                }
            }
        } else if ($designation_id >= 7) {
            if ($page_department == $department_id) {
                if ($page_sector  == $section_id) {
                    if ($page_directorate == $directorate_id) {
                        $result = true;
                    }
                }
            }
        }
    }
    return $result;
}

function get_page_actions() // for getting the permission to access individual page
{
    global $db, $designation_id;
    $page_details = get_page_details();
    $permissions = [];
    if ($page_details) { // ensure that the user can access the page
        $page_id = $page_details['id'];
        $read_allowed = $page_details['allow_read'];
        $sector_validation = page_sector($page_id); // ensure that the user can access if it is department specific
        if ($sector_validation) {
            $sql = $db->prepare("SELECT s.phrase, s.id FROM tbl_page_permissions p INNER JOIN tbl_permissions s ON p.permission_id = s.id WHERE p.page_id=:page_id");
            $sql->execute(array(":page_id" => $page_id));
            $count = $sql->rowCount();
            if ($count > 0) {
                while ($row = $sql->fetch()) { // store the page actions for the page in an array
                    $permission_id = $row['id'];
                    $stmt = $db->prepare("SELECT * FROM tbl_designation_permissions  WHERE designation_id=:designation_id AND permission_id=:permission_id ");
                    $stmt->execute(array(":designation_id" => $designation_id, "permission_id" => $permission_id));
                    $total_rows = $stmt->rowCount();
                    $permissions[] = $total_rows > 0 ? $row['phrase'] : "";
                }
            }
        } else {
            if ($read_allowed) {
                $permissions = ['read'];
            }
        }
    }
    return $permissions;
}


function get_page_details()
{
    global $db, $designation_id;
    $path = get_current_url();
    $stmt = $db->prepare("SELECT p.id, p.name, p.icon, p.allow_read, p.url, p.parent, p.workflow_stage FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE url=:url and d.designation_id=:designation_id LIMIT 1");
    $stmt->execute(array(":designation_id" => $designation_id, ":url" => $path));
    $row_stmt = $stmt->fetch();
    $rows_stmt = $stmt->rowCount();
    return $rows_stmt > 0 ? $row_stmt : false;
}

function get_parent_id($page_detials)
{
    global $db;
    $parent = $page_detials['id'];
    $parent_id = $page_detials['parent'];
    if ($parent_id != 0) {
        $stmt = $db->prepare("SELECT * FROM tbl_pages  WHERE id=:id LIMIT 1");
        $stmt->execute(array(":id" => $parent_id));
        $row_stmt = $stmt->fetch();
        if ($row_stmt) {
            $parent = $row_stmt['parent'] == 0 ?  $parent_id : $row_stmt['parent'];
        }
    }
    return $parent;
}

function get_child_id($page_detials)
{
    global $db;
    $child = $page_detials['id'];
    $parent_id = $page_detials['parent'];
    if ($parent_id != 0) {
        $stmt = $db->prepare("SELECT * FROM tbl_pages  WHERE id=:id LIMIT 1");
        $stmt->execute(array(":id" => $parent_id));
        $row_stmt = $stmt->fetch();
        if ($row_stmt) {
            $child = $row_stmt['parent'] == 0 ?  $child : $row_stmt['id'];
        }
    }
    return $child;
}


$page_actions = get_page_actions();
$permission =  (in_array("read", $page_actions)) ? true : false;
$page_detials = get_page_details();

$pageTitle = $icon = $allow_read_records = $workflow_stage = $Id = $subId = '';
if ($page_detials) {
    $pageTitle = $page_detials['name'];
    $icon = $page_detials['icon'];
    $allow_read_records = $page_detials['allow_read'];
    $workflow_stage = $page_detials['workflow_stage'];
    $Id = get_parent_id($page_detials);
    $subId = get_child_id($page_detials);
}


function view_record($department, $section, $directorate)
{
    global $user_department, $user_section, $user_directorate, $allow_read_records, $user_designation;
    $msg = false;
    if ($allow_read_records) {
        if ($user_designation >= 5) {
            if ($user_designation >= 5) {
                if ($user_department == $department) {
                    if ($section == $user_section) {
                        if ($directorate == $user_directorate) {
                            $msg = true;
                        } else {
                            if ($user_designation == 5) {
                                $msg = true;
                            }
                        }
                    } else {
                        if ($user_designation == 5) {
                            $msg = true;
                        }
                    }
                }
            }
        } else {
            $msg = true;
        }
    } else {
        if ($user_designation <= 8) {
            $msg = true;
        } else {
            if ($user_designation >= 5) {
                if ($user_department == $department) {
                    if ($section == $user_section) {
                        if ($directorate == $user_directorate) {
                            $msg = true;
                        } else {
                            if ($user_designation == 5) {
                                $msg = true;
                            }
                        }
                    } else {
                        if ($user_designation == 5) {
                            $msg = true;
                        }
                    }
                }
            }
        }
    }
    return $msg;
}

function get_timeline_details($stage, $sub_stage, $start_date)
{
    global $db;
    $today = date('Y-m-d');
    // $sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage_timelines` WHERE stage=:stage AND sub_stage=:sub_stage ORDER BY `id` ASC");
    // $sql->execute(array(":stage" => $stage, ":sub_stage" => $sub_stage));
    // $rows_count = $sql->rowCount();
    // $row = $sql->fetch();
    // $state = false;
    $status = 'Pending';
    // $due_date = "";

    // if ($rows_count > 0) {
    //     $stage = $row["stage"];
    //     $time = $row["time"];
    //     $units = $row["units"];
    //     $due_date = date('Y-m-d', strtotime($start_date . ' + ' . $time . ' ' . $units));
    //     if ($due_date < $today) {
    //         $status = "Behind Schedule";
    //     }
    // }
    return array('status' => $status, 'due_date' => $today);
}

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
$results = "";
$results =  "";
$parentId = 3;
function restriction()
{
    return "
	<script type='text/javascript'>
		swal({
		title: 'Success!',
		text: 'Sorry you are not permitted to access this page',
		type: 'Error',
		timer: 3000,
		icon:'error',
		showConfirmButton: false });
		setTimeout(function(){
			window.history.back();
		}, 3000);
	</script>";
}




function calculate_project_progress($projid, $implimentation_type)
{
    global $db;
    $direct_cost = 0;
    if ($implimentation_type == 1) {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE tasks =:task_id AND cost_type=1 ");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $projid));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    } else {
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE tasks =:task_id ");
        $query_rsOther_cost_plan_budget->execute(array(":task_id" => $projid));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
    }

    $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE s.projid =:projid ");
    $query_rsPercentage->execute(array(":projid" => $projid));
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
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE output_id =:output_id ");
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
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE output_id =:output_id  AND site_id=:site_id");
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
