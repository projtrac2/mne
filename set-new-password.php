<?php
session_start();
(!isset($_SESSION['MM_Username'])) ? $_SESSION['MM_Username'] : "index.php";

require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';
require 'includes/alerts.php';

$user_auth = new Auth();
$company_details = new Company();
$company_settings = $company_details->get_company_details();

$response ="";
if (isset($_POST['setpass']) && $_POST['setpass'] == "setpassword") {
    $confirm_password = $_POST['confirm_password'];
    $password = $_POST['password'];
    if ($confirm_password === $password) {
        $userid = $_SESSION['MM_Username'];
        $user = $user_auth->change_password($userid, $password);
        if ($user) {
            header("location: dashboard.php");
        } else { 
            $msg = "Sorry!! Passwords do not match";
            $response = message_alerts($msg, 2, "");
        }
    } else {
        $msg = "Sorry!! ensure ou have entered correct details";
        $response = message_alerts($msg, 2, "");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>ProjTrac Monitoring && Evaluation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="container h-100">
        <div class="row">
            <div class="col-md-12">
                <h3>ProjTrac Monitoring, Evaluation, And Reporting System</h3>
            </div>
            <div class="col-col-md-12">
                <h4 class="card-text">Welcome this is your first time to login, change password</h4>
            </div>
        </div>
        <?=$response?>
        <div class="d-flex justify-content-center h-100">
            <div class="user_card" style="height:300px !important;">
                <div class="d-flex justify-content-center ">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" value="" placeholder="New password">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="confirm_password" class="form-control input_pass" value="" placeholder="Confirm password">
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="hidden" name="setpass" value="setpassword">
                            <button type="submit" name="button" class="btn login_btn">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        // include_once "includes/login-footer.php";
        ?>
    </div>

</body>

</html>