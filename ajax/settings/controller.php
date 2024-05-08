<?php
date_default_timezone_set("Africa/Nairobi");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once '../../../projtrac-dashboard/resource/Database.php';
include_once '../../../projtrac-dashboard/resource/utilities.php';
include_once("../../../includes/system-labels.php");
include_once("../../../includes/app-security.php");

require '../../../vendor/autoload.php';
require '../../../Models/Connection.php';
require '../../../Models/Email.php';

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

$page_id = $_SESSION['page_id'];
$mail = new Email();

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

// Function to generate HTML with CSRF token input
function csrf_token_html()
{
    return   '<input type="hidden" id="csrf_token" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Validate CSRF token
function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
}
