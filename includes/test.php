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
$user_auth = new Auth();
$user_details = $user_auth->get_user_by_id($user_name);


$ministry = $user_details->ministry;
$sector = $user_details->department;
$designation = $user_details->designation;
$role_group = $user_details->role_group;
$directorate = $user_details->directorate;
$fullname = $user_details->fullname;
$avatar = $user_details->floc;

$results = "";
$currentdate = date("Y-m-d");
$results = $Id = $subId = "";
$permissions = new Permission();
$parent_sidebar = $permissions->get_parent_side_bar();
$current_url =  basename($_SERVER['PHP_SELF'], ".php");
$permission = $permissions->get_page_permissions($current_url);
$Id =  $permission ? $permission->parent : "";
$subId = $permission ?  $permission->id : "";
$pageTitle = $permission ? $permission->Name : "";
$icon = $permission ? $permission->icons : "";
$_SESSION['subId'] = $subId;
$parentId = 3; 
function restriction()
{
	$msg = "Sorry you are not permitted to access this page";
	return	$results = "
	<script type=\"text/javascript\">
		swal({
		title: \"Success!\",
		text: \" $msg\",
		type: 'Error',
		timer: 2000, 
		icon:'error',
		showConfirmButton: false });
		setTimeout(function(){ 
			window.history.back();
		}, 2000);
	</script>";
}