<?php
require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';

$company_details = new Company();
$company_settings = $company_details->get_company_details();
session_start();
if (isset($_POST['forgotpassword']) && $_POST['forgotpassword'] == "Forgot Password") {
  $email = $_POST['email'];
  $user_auth = new Auth();
  $user = $user_auth->get_user($email);
  if ($user) {
    $forgot = $user_auth->forgot_password($email);
    $_SESSION["successMessage"] =  "Reset link has been sent to your email please use it to reset you password.";
    header("location:forgot-password.php");
    return;
  } else {
    $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong email address.";
    header("location:forgot-password.php");
    return;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="no-cache">
  <meta http-equiv="Expires" content="-1">
  <meta http-equiv="Cache-Control" content="no-cache">
  <title>Result-Based Monitoring &amp; Evaluation System</title>
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
  <style>
        body {
            background-image: url('./images/back-14.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            min-height: 100vh;
        }

        .m-footer {
            text-align: center;
            background-color: black;
            color: white;
            position: absolute;
            bottom: 0px;
            width: 100%;
        }

        .m-alert {
            padding: 0.6vw;
            background-color: rgba(254, 242, 241, 0.7);
            border: 1px solid #ef4444;
            display: flex;
            gap: 1vw;
            align-content: center;
            align-items: center;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }


        .m-alert-danger {
            padding: 0.6vw;
            background-color: rgba(220, 252, 231, 0.7);
            border: 1px solid #16a34a;
            display: flex;
            gap: 1vw;
            align-content: center;
            align-items: center;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }
    </style>
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
  <script src="https://kit.fontawesome.com/6557f5a19c.js" crossorigin="anonymous"></script>

</head>

<body>
<div class="container">
        <div class="row">
            <div class="col-md-4" style="padding-top: 8vh;">
                <div style="margin-bottom: 6vh;">
                    <img src="./images/logo-proj.png" alt="" srcset="" width="400">
                </div>



                <div style="margin-bottom: 4vh;">
                    <h4 style="color: #003366;">Forgot your password ?</h4>
                    <p style="color: #808080;">Enter your email to reset it!</p>
                </div>
                <!-- inputs -->
                <form method="POST" id="loginusers">
                    <div style="margin-bottom: 4vh;">
                        <input name="email" type="email" id="email" placeholder="Email" style="color:black; padding: 0.6vw; border-radius: 5px; border: none; width: 40%; font-size: 16px;" required>
                        <p style="color: #dc2626;"></p>
                    </div>

                    <input type="hidden" name="forgotpassword" value="Forgot Password">

                    <div style="display: flex; gap: 2vw;">
                        <button id="submit-btn" type="button" style="background-color: #22c55e; color: white; border: none; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 0.5px; border-radius: 5px;">Forgot Password</button>
                        <a href="index.php"><button type="button" style="background-color: transparent; color: white; border: 1.5px solid #003366; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 0.5px; border-radius: 5px;">Go To Login</button></a>
                    </div>
                </form>
            </div>
            <div class="col-md-8">

            </div>
        </div>
    </div>
    <div class="m-footer">
        <p>ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System .</p>
        <p>Copyright @ 2017 - 2024. ProjTrac Systems Ltd .</p>
    </div>

    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div style="position:absolute; bottom: 12vh; right: 2vw; width: 35%;">
            <div class="m-alert">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 26px; color: #dc2626; padding-left: 1vw"></i>
                <div>
                    <p style="margin: 0px; font-size: 1rem; line-height: 1.5rem; font-weight: bold; letter-spacing: 1px; color: #7f1d1d;">Danger Alert</p>
                    <p style="margin: 0px; font-size: 0.875rem; line-height: 1.25rem; letter-spacing: 0.6px;"><?= $_SESSION["errorMessage"] ?></p>
                </div>
            </div>
        </div>
    <?php
    }
    unset($_SESSION["errorMessage"]);
    ?>


    <?php
    if (isset($_SESSION["successMessage"])) {
    ?>
    <div style="position:absolute; bottom: 12vh; right: 2vw; width: 35%;">
        <div class="m-alert-danger">
            <i class="fa-solid fa-circle-check" style="font-size: 26px; color: #16a34a; padding-left: 1vw"></i>
            <div>
                <p style="margin: 0px; font-size: 1rem; line-height: 1.5rem; font-weight: bold; letter-spacing: 1px; color: #052e16;">Success Alert</p>
                <p style="margin: 0px; font-size: 0.875rem; line-height: 1.25rem; letter-spacing: 0.6px;"><?= $_SESSION["successMessage"] ?></p>
            </div>
        </div>
    </div>
    <?php
    }
    unset($_SESSION["successMessage"]);
    ?>

<script>
        $(function() {
            console.log($('#email'));

            $('#submit-btn').on('click', (e) => {
                e.preventDefault();

                if (!$('#email').val()) {
                    $('#email').next().text('field required');
                    return;
                } else {
                    $('#email').next().text('');
                }

                $('#loginusers').submit();
            })
        })
    </script>
</body>

</html>

  <!-- <p>&nbsp;</p>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div align="center">
          <div class="container-fluid1" id="content_area_cell">
            <h3 align="center" class="contenttitles">ProjTrac Monitoring, Evaluation, And Reporting System</h3>
            <p>&nbsp;</p>
            <form action="" method="POST" class="form-signin" style="margin-bottom:10px" id="loginusers">
              <div style="width:100%; height:auto; background-color:#036">
                <p><img src="<?php // $company_settings->floc; ?>" style="height:100px; width:230px; margin-top:10px" class="imgdim" /></p>
              </div>
              <br />
              <?php
              // if (isset($_SESSION["errorMessage"])) {
              //   $type = $_SESSION['type'];
              //   if ($type == "error") {
              ?>
                  <div class='alert alert-error'>
                    <p class="errormsg">
                      <img src="images/error.png" alt="success_msg" />
                      <?= $_SESSION["errorMessage"] ?>
                    </p>
                  </div>
                <?php
                // } else {
                ?>
                  <div class='alert alert-success'>
                    <p class="success_msg">
                      <img src="assets/images/apply.gif" alt="success" />
                      <?= $_SESSION["errorMessage"] ?>
                    </p>
                  </div>
              <?php
              //   }
              // }
              // unset($_SESSION["errorMessage"]);
              ?>
              <p>
                <input name="email" type="email" class="input-block-level" id="username" placeholder="Enter your email address" required />
              </p>
              <p>
                <input name="forgotpassword" type="submit" class="loginbutton" id="submit" value="Forgot Password" />
              </p>
              <a href="index.php">Go to login</a>
            </form>
            <p>&nbsp;</p>
          </div>
        </div>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
  <?php
  //include_once "includes/login-footer.php";
  ?> -->