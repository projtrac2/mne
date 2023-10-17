<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
require '../../vendor/autoload.php';
include "../../Models/Auth.php";
include "../../Models/Company.php";
include "../../Models/Permission.php";
require '../../Models/Connection.php';
require '../../Models/Email.php';
session_start();
(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";
$user_name = $_SESSION['MM_Username'];

$ministry = $_SESSION['ministry'];
$sector = $_SESSION['sector'];
$designation = $_SESSION['designation'];
$role_group = $_SESSION['role_group'];
$directorate = $_SESSION['directorate'];
$fullname = $_SESSION['fullname'];
$avatar = $_SESSION['avatar'];
$subId = $_SESSION['subId'];



$results = "";
$currentdate = date("Y-m-d");
$permissions = new Permission();


$add = $permissions->get_action_permissions($subId, "add");
$edit = $permissions->get_action_permissions($subId, "edit");
$delete = $permissions->get_action_permissions($subId, "delete");

$add_quarterly_targets = $permissions->get_action_permissions($subId, "add_quarterly_targets");
$edit_quarterly_targets = $permissions->get_action_permissions($subId, "edit_quarterly_targets");
$add_project = $permissions->get_action_permissions($subId, "add_project");
$approve_project = $permissions->get_action_permissions($subId, "approve_project");
$unapprove_project =  $permissions->get_action_permissions($subId, "approve_project");


$add_to_adp =  $permissions->get_action_permissions($subId, "add_to_adp");
$remove_adp =  $permissions->get_action_permissions($subId, "remove_adp");
