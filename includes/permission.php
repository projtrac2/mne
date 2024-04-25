<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");
include_once("includes/page-details.php");
include_once("includes/app-security.php");
include_once("includes/app-sessions.php");
include_once "includes/project-status.php";
include_once "includes/project-progress.php";

require 'vendor/autoload.php';
include "Models/Auth.php";
include "Models/Company.php";
include "Models/Permission.php";
require 'Models/Connection.php';

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

$results =  "";
function restriction()
{
    return "
	<script type='text/javascript'>
		swal({
            title: 'Error!',
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

function error_message($message, $type, $url)
{
    $page_path = "";
    if ($type == 1) {
        $page_path =  $url = "window.location.reload();";
    } else if ($type == 2) {
        $page_path = "window.location.href = $url;";
    } else if ($type == 3) {
        $page_path = "window.history.back();";
    }

    return
        "<script type='text/javascript'>
		swal({
            title: 'Error!',
            text: '$message',
            type: 'Error',
            timer: 3000,
            icon:'error',
            showConfirmButton: false
        });
		setTimeout(function(){
			$page_path
		}, 3000);
	</script>";
}

function success_message($message, $type, $url)
{
    $page_path = "";
    if ($type == 1) {
        $page_path =  $url = "window.location.reload();";
    } else if ($type == 2) {
        $page_path = "window.location.href = $url;";
    }

    return
        "<script type='text/javascript'>
		swal({
            title: 'Success!',
            text: '$message',
            type: 'Error',
            timer: 3000,
            icon:'error',
            showConfirmButton: false
        });
		setTimeout(function(){
			$page_path
		}, 3000);
	</script>";
}
