<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("includes/system-labels.php");
include_once("includes/app-sessions.php");
require 'vendor/autoload.php';
include "Models/Auth.php";
include "Models/Company.php";
include "Models/Permission.php";
require 'Models/Connection.php';
require 'Models/Email.php';

$company_details = new Company();
$user_auth = new Auth();

$company_settings = $company_details->get_company_details();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <title>Result-Based Monitoring &amp; Evaluation System</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
    <script src="https://kit.fontawesome.com/6557f5a19c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/auth/index.css">
</head>

<body>