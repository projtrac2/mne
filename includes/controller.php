<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";
$user_name = $_SESSION['MM_Username'];

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");

require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';
require 'Models/RolesPermissions.php';

$results = "";
$currentPage = $_SERVER["PHP_SELF"];
$currentdate = date("Y-m-d");
$conn = new Connection();
$conn_db = $conn->openConnection();
$results = $Id = $subId = $sidebar_details = "";
$add_path = $edit_path = $delete_path = "";


$currentPage =  'http://34.74.197.215' . $currentPage;
$path_array = parse_url($currentPage);

$path = $path_array['path'];
$url_link = ltrim($path, "/");
$url_link = ltrim($url_link, "county");
$url_link = ltrim($url_link, "/");

$url_link = rtrim($url_link, "p");
$url_link = rtrim($url_link, "h");
$url_link = rtrim($url_link, "p");
$url_link = rtrim($url_link, ".");

$permission = $action_permission = false;

function get_user()
{
    global $conn_db, $user_name;
    $query_user =  $conn_db->prepare("SELECT p.*, u.username FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
    $query_user->execute(array(":user_id" => $user_name));
    $row_rsUser = $query_user->fetch();
    $count = $query_user->rowCount();
    if ($count > 0) {
        return $row_rsUser;
    } else {
        return false;
    }
}

function get_department($department)
{
    global $conn_db;
    $query_rsDepartment =  $conn_db->prepare("SELECT * FROM `tbl_sectors` WHERE stid=:department");
    $query_rsDepartment->execute(array(":department" => $department));
    $row_rsDepartment = $query_rsDepartment->fetch();
    $count = $query_rsDepartment->rowCount();
    if ($count > 0) {
        return $row_rsDepartment;
    } else {
        return false;
    }
}

function get_designation($designation)
{
    global $conn_db;
    $query_rsDesignation =  $conn_db->prepare("SELECT * FROM `tbl_pmdesignation` WHERE moid=:designation");
    $query_rsDesignation->execute(array(":designation" => $designation));
    $row_rsDesignation = $query_rsDesignation->fetch();
    $count = $query_rsDesignation->rowCount();

    if ($count > 0) {
        return $row_rsDesignation;
    } else {
        return false;
    }
}

// display error message and redirect
function restriction()
{
    $result =
        '<script type="text/javascript">
        swal({
            title: "Permission Denied!",
            text: "You have no rights to access this page.",
            type: "Error",
            timer: 5000,
            icon:"error",
            showConfirmButton: true
        });
        setTimeout(function(){
            window.history.back();
        }, 5000);
    </script>';
    return $result;
}

// get parent sidebar links 
function get_sidebar()
{
    global $conn_db;
    $query_Sidebar =  $conn_db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = 0 AND status =1 ");
    $query_Sidebar->execute();
    $row_rsSidebar = $query_Sidebar->fetchAll();
    $count = $query_Sidebar->rowCount();
    if ($count > 0) {
        return $row_rsSidebar;
    } else {
        return false;
    }
}

// get sidebar children 
function get_sidebar_children($parent_id)
{
    global $conn_db;
    $query_Sidebar =  $conn_db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = :parent_id AND status = 1");
    $query_Sidebar->execute(array(":parent_id" => $parent_id));
    $row_rsSidebar = $query_Sidebar->fetchAll();
    $count = $query_Sidebar->rowCount();
    if ($count > 0) {
        return $row_rsSidebar;
    } else {
        return false;
    }
}

// replace variable string options 
function string_replace($string, $replacement_array)
{
    $string_processed = preg_replace_callback(
        '~\{\$(.*?)\}~si',
        function ($match) use ($replacement_array) {
            return str_replace($match[0], isset($replacement_array[$match[1]]) ? $replacement_array[$match[1]] : $match[0], $match[0]);
        },
        $string
    );
    return $string_processed;
}

function get_role_group($user)
{
    $role_group = $designation = "";
    $permission = false;
    if ($user) {
        $department = $user->ministry;
        $designation = $user->designation;
        $designation_details = get_designation($designation);

        if ($designation_details) {
            $position = $designation_details->position;
            if ($position == 1) {
                $role_group = 4;
                $permission = true;
            } else if ($position > 1 &&  $position <= 4) {
                $role_group = 3;
                $permission = true;
            } else {
                $department_details = get_department($department);
                if ($department_details) {
                    $role_group = $department_details->role_id;
                    $permission = true;
                }
            }
        }
    }
    return array("permission" => $permission, "role_group" => $role_group, "designation" => $designation);
}

//  check each page the user is in and authenticate
function crud_permissions($page, $replacement_array)
{
    global $conn_db, $url_link, $role_group, $designation;
    $where = "";
    if ($page == 'view') {
        $where = "url='" . $url_link . "'";
    } else if ($page == "add") {
        $where = "add_path='" . $url_link . "'";
    } else if ($page == "edit") {
        $where = "edit_path='" . $url_link . "'";
    }

    $query_pages =  $conn_db->prepare("SELECT * FROM tbl_sidebar_menu WHERE  $where AND  status = 1");
    $query_pages->execute();
    $row_pages = $query_pages->fetch();
    $count = $query_pages->rowCount();

    if ($count > 0) {
        $parent = $row_pages->parent;
        $query_parent_pages =  $conn_db->prepare("SELECT * FROM tbl_sidebar_menu WHERE  id='$parent' AND  status = 1");
        $query_parent_pages->execute();
        $row_parent_pages = $query_parent_pages->fetch();
        $count_parent_pages = $query_parent_pages->rowCount();


        if ($count_parent_pages > 0) {
            $grand_parent = $row_parent_pages->parent;

            if ($grand_parent ==  0) {
                // get rolegroup users who can access the page 
                $view = explode(",", $row_pages->view_group);
                $add = explode(",", $row_pages->add_group);
                $edit = explode(",", $row_pages->edit_group);
                $delete = explode(",", $row_pages->delete_group);

                // get designations of users who can access the page 
                $view_designation = explode(",", $row_pages->view_designation);
                $add_designation = explode(",", $row_pages->add_designation);
                $edit_designation = explode(",", $row_pages->edit_designation);
                $delete_designation = explode(",", $row_pages->delete_designation);


                // check if a user can access the page 
                $view_permission = (in_array($role_group, $view) && in_array($designation, $view_designation)) ? true :  false;
                $add_permission = (in_array($role_group, $add) && in_array($designation, $add_designation)) ? true :  false;
                $edit_permission = (in_array($role_group, $edit) && in_array($designation, $edit_designation)) ? true :  false;
                $delete_permission = (in_array($role_group, $delete) && in_array($designation, $delete_designation)) ? true :  false;

                $parent = $row_pages->parent;
                $menu_id = $row_pages->id;

                $file_rights = array("add" => $add_permission, "edit" => $edit_permission, "delete_permission" => $delete_permission, "view" => $view_permission);
                

                if ($page == "view") {
                    $add_path = $add_permission ? string_replace($row_pages->add_path, $replacement_array) : "";
                    $edit_path = $edit_permission ? string_replace($row_pages->edit_path, $replacement_array) : "";
                    $delete_path = $delete_permission ? string_replace($row_pages->delete_path, $replacement_array) : "";
                    $view_path = $view_permission ? string_replace($row_pages->url, $replacement_array) : "";

                    return array("view_path" => $view_path, "add_path" => $add_path, "edit_path" => $edit_path, "delete_path" => $delete_path, "file_rights" => $file_rights, "parent" => $parent, "menu_id" => $menu_id);
                } else if ($page == "add") {
                    return  array("permission" => $add_permission, "parent" => $parent, "menu_id" => $menu_id);
                } else if ($page == "edit") {
                    return  array("permission" => $edit_permission, "parent" => $parent, "menu_id" => $menu_id);
                } else {
                    return false;
                }
            } else {
                // get rolegroup users who can access the page 
                $view = explode(",", $row_parent_pages->view_group);
                $add = explode(",", $row_parent_pages->add_group);
                $edit = explode(",", $row_parent_pages->edit_group);
                $delete = explode(",", $row_parent_pages->delete_group);

                // get designations of users who can access the page 
                $view_designation = explode(",", $row_parent_pages->view_designation);
                $add_designation = explode(",", $row_parent_pages->add_designation);
                $edit_designation = explode(",", $row_parent_pages->edit_designation);
                $delete_designation = explode(",", $row_parent_pages->delete_designation);

                // check if a user can access the page 
                $view_permission = (in_array($role_group, $view) && in_array($designation, $view_designation)) ? true :  false;
                $add_permission = (in_array($role_group, $add) && in_array($designation, $add_designation)) ? true :  false;
                $edit_permission = (in_array($role_group, $edit) && in_array($designation, $edit_designation)) ? true :  false;
                $delete_permission = (in_array($role_group, $delete) && in_array($designation, $delete_designation)) ? true :  false;

                $parent = $row_parent_pages->parent;
                $menu_id = $row_parent_pages->id;

                $file_rights = array("add" => $add_permission, "edit" => $edit_permission, "delete_permission" => $delete_permission, "view" => $view_permission);
                if ($page == "view") {
                    $add_path = $add_permission ? string_replace($row_parent_pages->add_path, $replacement_array) : "";
                    $edit_path = $edit_permission ? string_replace($row_parent_pages->edit_path, $replacement_array) : "";
                    $delete_path = $delete_permission ? string_replace($row_parent_pages->delete_path, $replacement_array) : "";
                    $view_path = $view_permission ? string_replace($row_parent_pages->url, $replacement_array) : "";
                    return array("view_path" => $view_path, "add_path" => $add_path, "edit_path" => $edit_path, "delete_path" => $delete_path, "file_rights" => $file_rights, "parent" => $parent, "menu_id" => $menu_id);
                } else if ($page == "add") {
                    return  array("permission" => $add_permission, "parent" => $parent, "menu_id" => $menu_id);
                } else if ($page == "edit") {
                    return  array("permission" => $edit_permission, "parent" => $parent, "menu_id" => $menu_id);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

$user_details = get_user();
$role_group_details =  get_role_group($user_details);

$permission = $role_group_details['permission'];
$role_group = $role_group_details['role_group'];
$designation = $role_group_details['designation'];

// check the type of page the user is at
if ($permission) {
    $response = crud_permissions($page, $replacement_array); 
    if ($response) {
        $add_path = $response['add_path'];
        $edit_path = $response['edit_path'];
        $delete_path = $response['delete_path'];
        $Id = $response['parent'];
        $subId = $response['menu_id'];

        if ($delete_path || $edit_path) {
            $action_permission = true;
        }

        $file_rights = (object)$response['file_rights']; 
        $permission = $file_rights->view;
    } else {
        $permission = false;
    }
    $sidebar_details = get_sidebar();
}
