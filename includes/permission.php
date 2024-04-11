<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function get_current_url_tests()
{
    $path = $_SERVER['REQUEST_URI'];
    $paths = explode("/", $path);
    $url_path = isset($paths[2]) ? explode(".", $paths[2]) : explode(".", $paths[1]);
    return $url_path[0];
}

session_start();

// Set the inactivity time of 60 minutes (3600 seconds)
$inactivity_time = 15 * 60;

if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
    session_unset();
    session_destroy();
    $current_page_url = get_current_url_tests();
    //Redirect user to login page
    header("Location: index.php?action=$current_page_url");
    exit();
} else {
    // Regenerate new session id and delete old one to prevent session fixation attack
    session_regenerate_id(true);
    // Update the last timestamp
    $_SESSION['last_timestamp'] = time();
}

(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";
$_SESSION['last_accessed_url'] = $_SERVER['REQUEST_URI'];

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");

require 'vendor/autoload.php';
include "Models/Auth.php";
include "Models/Company.php";
include "Models/Permission.php";
require 'Models/Connection.php';

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

$query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
$query_company->execute();
$row_company = $query_company->fetch();
$total_company = $query_company->rowCount();

$company_latitude = $company_longitude = $company_main_url = $company_logo = $company_email_address = $company_name = '';
if ($total_company > 0) {
    $company_latitude = $row_company['latitude'];
    $company_longitude = $row_company['longitude'];
    $company_main_url = $row_company['main_url'];
    $company_logo = $row_company['logo'];
    $company_email_address = $row_company['email_address'];
    $company_name = $row_company["company_name"];
}

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





function validate_program_url($progid, $program_type)
{
    global $db;

    if ($program_type == "" && $progid != '') {
        $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid=:progid");
        $query_rsProgram->execute(array(":progid" => $progid));
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();
        $program_type = $totalRows_rsProgram > 0 ?  $row_rsProgram['program_type'] : 0;
    }
    return $program_type == 1 ? "view-strategic-plans" : "all-programs";
}

function validate_project_url($progid)
{
    global $db;
    $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid=:progid");
    $query_rsProgram->execute(array(":progid" => $progid));
    $row_rsProgram = $query_rsProgram->fetch();
    $totalRows_rsProgram = $query_rsProgram->rowCount();
    $program_type = $totalRows_rsProgram > 0 ?  $row_rsProgram['program_type'] : 0;
    return $program_type == 1 ? "view-strategic-plans" : "all-programs";
}

function validate_output_url($projid)
{
    global $db;
    $sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid WHERE p.projid=:projid LIMIT 1");
    $sql->execute(array(":projid" => $projid));
    $rows_count = $sql->rowCount();
    $row = $sql->fetch();
    $program_type =  ($rows_count > 0) ? $row['program_type'] : 0;
    return $program_type == 1 ? "view-strategic-plans" : "all-programs";
}

function child_ids($page_url)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM tbl_pages  WHERE url=:page_url LIMIT 1");
    $stmt->execute(array(":page_url" => $page_url));
    $row_stmt = $stmt->fetch();
    return $row_stmt;
}



$page_actions = get_page_actions();
$permission =  (in_array("read", $page_actions)) ? true : false;
$page_detials = get_page_details();

$pageTitle = $icon = $allow_read_records = $workflow_stage = $Id = $subId = '';

if ($page_detials) {
    $_SESSION['page_id'] = $page_detials['id'];
    $pageTitle = $page_detials['name'];
    $icon = $page_detials['icon'];
    $allow_read_records = $page_detials['allow_read'];
    $workflow_stage = $page_detials['workflow_stage'];

    if (isset($_GET['progid']) ||  isset($_GET['program_type'])) {
        $url_pages = '';
        if (get_current_url() == "add-project") {
            $decode_progid = (isset($_GET['progid']) && !empty($_GET["progid"])) ? base64_decode($_GET['progid']) : "";
            $progid_array = explode("progid54321", $decode_progid);
            $progid = $progid_array[1];
            $url_pages = validate_project_url($progid);
        } else  if (get_current_url() == 'edit-program') {
            $decode_progid = (isset($_GET['progid']) && !empty($_GET["progid"])) ? base64_decode($_GET['progid']) : "";
            $progid_array = explode("progid54321", $decode_progid);
            $progid = $progid_array[1];
            $url_pages = validate_program_url($progid, '');
        } else if (get_current_url() ==  'add-program') {
            $program_type = $_GET['program_type'];
            $url_pages = validate_program_url('', $program_type);
        }
        $page_detials = child_ids($url_pages);
    } else if (get_current_url() == 'add-project-outputs' && isset($_GET['projid'])) {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];
        $url_pages = validate_output_url($projid);
        $page_detials = child_ids($url_pages);
    } else if (isset($_GET['projid']) && get_current_url() == "add-project") {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];
        $url_pages = validate_output_url($projid);
        $page_detials = child_ids($url_pages);
    }

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
            showConfirmButton: false
        });
		setTimeout(function(){
			window.history.back();
		}, 3000);
	</script>";
}




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
    if ($totalRows_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $projid = $row_rsProjects['projid'];
            $project_status = $row_rsProjects['projstatus'];
            $implementation_type = $row_rsProjects['projcategory'];
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

                    $status[] = $subtask_status;
                    $sql = $db->prepare("UPDATE tbl_program_of_works SET status=:status WHERE  id=:id");
                    $sql->execute(array(":status" => $subtask_status, ":id" => $id));
                }
            }

            // $substage_id = 0;
            // if (!$complete) {
            //     if (in_array(11, $status)) {
            //         $project_status = 11;
            //     } else if (in_array(4, $status)) {
            //         $project_status = 4;
            //     }
            // } else {
            //     $project_status = 5;
            //     $substage_id = $implementation_type == 2 ? 1 : 2;
            // }

            // $sql = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, proj_substage=:substage_id WHERE  projid=:projid");
            // $result  = $sql->execute(array(":projstatus" => $project_status, ":substage_id" => $substage_id, ":projid" => $projid));
        }
    }
}

// set_project_status();


function get_master_data_due_date($projid, $stage_id, $substage_id)
{
    global $db;
    $sql = $db->prepare("SELECT * FROM tbl_project_stage_actions WHERE projid=:projid AND stage=:stage_id AND sub_stage=0");
    $sql->execute(array(":projid" => $projid, ":stage_id" => $stage_id));
    $totalRows = $sql->rowCount();
    $Rows = $sql->fetch();
    $due_date = $status = '';
    if ($totalRows > 0) {
        $date = $Rows['created_at'];
        $stmt =  $db->prepare("SELECT * FROM tbl_notifications WHERE stage_id=:stage_id");
        $stmt->execute(array(":stage_id" => $stage_id));
        $notification = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $duration = ($substage_id == 0 || $substage_id == 1) ? $notification['data_entry'] : $notification['approval'];
            $due_date = date('Y-m-d', strtotime($date . ' + ' . $duration . ' days'));
        }
    }
    return $due_date;
}
