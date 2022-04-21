<?php
session_start();
(!isset($_SESSION['MM_Username'])) ? "" : "";
$user_name = $_SESSION['MM_Username']; 


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");


require '../../vendor/autoload.php';
require '../../Models/Connection.php';
include "../../Models/Auth.php";
include "../../Models/Company.php";  
require '../../Models/Email.php';
require '../../Models/RolesPermissions.php';



$results ="";
$currentPage = $_SERVER["PHP_SELF"];
$currentdate = date("Y-m-d");

$permissions = new RolesPermissions();
$role_group = $permissions->index($user_name);
$user_details = $permissions->get_user($user_name);
$designation = $user_details->designation;
$sidebar_details = ($role_group)  ? $permissions->get_sidebar($role_group) : false;