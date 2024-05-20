<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

include_once '../../Models/Email.php';
include_once '../../Models/Connection.php';
require '../../vendor/autoload.php';


$currentdate = date("Y-m-d");
session_start();
(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";

$user_name = $_SESSION['MM_Username'];
$designation_id = $_SESSION['designation'];
$user_designation = $_SESSION['designation'];
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


// function update_project_stage($projid, $workflow_stage, $sub_stage)
// {

//     global $db, $today, $user_name;
//     $sql = $db->prepare("UPDATE tbl_projects SET projstage=:projstage, proj_substage=:proj_substage WHERE  projid=:projid");
//     $result  = $sql->execute(array(":projstage" => $workflow_stage, ":proj_substage" => $sub_stage, ":projid" => $projid));

//     if ($result) {
//         $sql = $db->prepare("INSERT INTO tbl_project_stage_actions (projid,stage,sub_stage,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:created_by,:created_at)");
//         $result = $sql->execute(array(":projid" => $projid, ':stage' => $workflow_stage, ':sub_stage' => $sub_stage, ':created_by' => $user_name, ':created_at' => $today));
//     }
//     return $result;
// }

// function update_stage($projid, $workflow_stage)
// {
//     global $db;
//     $sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority > :priority ORDER BY priority LIMIT 1");
//     $sql->execute(array(":priority" => $workflow_stage));
//     $row = $sql->fetch();
//     $rows = $sql->rowCount();

//     if ($rows > 0) {
//         $workflow_stage = $rows > 0 ? $row['priority'] : $workflow_stage;
//     }
//     update_project_stage($projid, $workflow_stage, 0);
// }


function get_page_details($designation_id, $path)
{
    global $db;
    $stmt = $db->prepare("SELECT p.id, p.name, p.icon, p.allow_read, p.url, p.parent, p.workflow_stage FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE url=:url and d.designation_id=:designation_id LIMIT 1");
    $stmt->execute(array(":designation_id" => $designation_id, ":url" => $path));
    $row_stmt = $stmt->fetch();
    $rows_stmt = $stmt->rowCount();
    return $rows_stmt > 0 ? $row_stmt : false;
}

function view_record($department, $section, $directorate, $allow_read_records)
{
    global $user_department, $user_section, $user_directorate, $user_designation;
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
