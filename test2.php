<?php
require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';

session_start();

if (isset($_SESSION['MM_Username'])) header("location:dashboard.php");

//check if can login again
if (isset($_SESSION['attempt_again'])) {
    $now = time();
    if ($now >= $_SESSION['attempt_again']) {
        unset($_SESSION['attempt']);
        unset($_SESSION['attempt_again']);
    }
}

$user_auth = new Auth();
$company_details = new Company();
$company_settings = $company_details->get_company_details();

if (isset($_POST['sign-in'])) {
    //set login attempt if not set
    if (!isset($_SESSION['attempt'])) {
        $_SESSION['attempt'] = 0;
    }
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = $user_auth->get_user($email);

    //check if there are 3 attempts already
    if ($_SESSION['attempt'] == 3) {
        $_SESSION['errorMessage'] = 'Attempt limit reached';
        $user_auth->suspicious_activity($email);
        header("location:index.php");
        return;
    } else {
        if ($user) {
            //unset our attempt
            unset($_SESSION['attempt']);
            if ($user->first_login) {
                header("location: set-new-password.php");
            } else {
                if (isset($_GET['action'])) {
                    $page_url = $_GET['action'];
                    header("location: $page_url");
                    return;
                } else {
                    $mail_otp_code = $user_auth->otp($email);
                    $mail_otp_code = true;
                    if ($mail_otp_code) {
                        header("location: otp.php?email=$email");
                        return;
                    } else {
                        $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong username or wrong password.";
                        header("location:index.php");
                        return;
                    }
                }
            }
        } else {
            //this is where we put our 3 attempt limit
            $_SESSION['attempt'] += 1;
            //set the time to allow login if third attempt is reach
            if ($_SESSION['attempt'] == 3) {
                $_SESSION['attempt_again'] = time() + (5 * 60);
                //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
            }

            $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong username or wrong password.";
            header("location:index.php");
            return;
        }
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

        @media only screen and (max-height: 600px) {

            /* CSS rules for extra small devices */
            .m-padding {
                padding-top: 2vh;
            }
        }


        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-height: 601px) and (max-height: 900px) {

            /* CSS rules for small devices */
            .m-padding {
                padding-top: 10vh;
            }
        }

        /* Medium devices (landscape tablets, 900px and up) */
        @media only screen and (min-height: 901px) and (max-height: 1200px) {
            .m-padding {
                padding-top: 10vh;
            }
        }

        /* Large devices (laptops/desktops, 1200px and up) */
        @media only screen and (min-height: 1201px) {

            /* CSS rules for large devices */
            .m-padding {
                padding-top: 10vh;
            }
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
            <div class="col-md-4 m-padding">
                <div style="margin-bottom: 6vh;">
                    <img src="./images/logo-proj.png" alt="" srcset="" width="500">
                </div>

                <div style="margin-bottom: 4vh;">
                    <h4 style="color: #003366;">Login to mne</h4>
                    <!-- <p style="color: black;">Check email for otp code!</p> -->
                </div>
                <form method="POST" id="loginusers">
                    <div style="margin-bottom: 4vh;">
                        <input name="email" type="email" id="email" placeholder="Email" style="color:black; padding: 0.6vw; border-radius: 5px; border: none; width: 40%; font-size: 16px;" required>
                        <p style="color: #dc2626;"></p>
                    </div>

                    <div style="margin-bottom: 4vh;">
                        <input name="password" type="password" id="password" placeholder="Password" style="color:black; padding: 0.6vw; border-radius: 5px; border: none; width: 40%; font-size: 16px;" required>
                        <p style="color: #dc2626;"></p>
                    </div>

                    <input type="hidden" name="sign-in" value="sign-in">

                    <div style="display: flex; gap: 2vw;">
                        <button id="submit-btn" type="button" style="background-color: #22c55e; color: white; border: none; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Sign In</button>
                        <a href="forgot-password.php"><button type="button" style="background-color: transparent; color: white; border: 1.5px solid #003366; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Forgot Password</button></a>
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

                if (!$('#password').val()) {
                    $('#password').next().text('field required');
                    return;
                } else {
                    $('#password').next().text('');
                }
                console.log($('#loginusers').submit());
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
                <p><img src="<?php //$company_settings->floc;
                                ?>" style="height:100px; width:230px; margin-top:10px" class="imgdim" /></p>
              </div>
              <br />
              <?php
                // if (isset($_SESSION["errorMessage"])) {
                ?>
                <div class='alert alert-danger'>
                  <p class="errormsg">
                    <img src="images/error.png" alt="errormsg" />
                    <?php // $_SESSION["errorMessage"]
                    ?>

                  </p>
                </div>
              <?php
                // }
                // unset($_SESSION["errorMessage"]);
                ?>
              <p>
                <input name="email" type="email" class="input-block-level" id="username" placeholder="Enter your email address" required />
                <label for="password"></label>
                <input name="password" type="password" class="input-block-level" id="password" placeholder="Enter your password" required />
              </p>
              <p>
                <input name="submit" type="submit" class="loginbutton" id="submit" value="Sign In" />
              </p>
              <a href="forgot-password.php">Forgot your password?</a>
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
                <p><img src="<?php // $company_settings->floc;
                                ?>" style="height:100px; width:230px; margin-top:10px" class="imgdim" /></p>
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

<?php
require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';

$user_auth = new Auth();
$verified = ($token != "" && !empty($token)) ? $verified = $user_auth->verify_token($token) : false;

$company_details = new Company();
$company_settings = $company_details->get_company_details();
session_start();

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

        @media only screen and (max-height: 600px) {

            /* CSS rules for extra small devices */
            .m-padding {
                padding-top: 2vh;
            }
        }


        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-height: 601px) and (max-height: 900px) {

            /* CSS rules for small devices */
            .m-padding {
                padding-top: 10vh;
            }
        }

        /* Medium devices (landscape tablets, 900px and up) */
        @media only screen and (min-height: 901px) and (max-height: 1200px) {
            .m-padding {
                padding-top: 10vh;
            }
        }

        /* Large devices (laptops/desktops, 1200px and up) */
        @media only screen and (min-height: 1201px) {

            /* CSS rules for large devices */
            .m-padding {
                padding-top: 10vh;
            }
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
            <div class="col-md-4 m-padding" style="">
                <div style="margin-bottom: 4vh;">
                    <img src="./images/logo-proj.png" alt="" srcset="" width="400">
                </div>
                <?php
                if ($verified) {
                ?>
                    <!-- inputs -->
                    <div style="margin-bottom: 4vh;">
                        <h4 style="color: #003366;">Reset password</h4>
                    </div>
                    <form method="POST" id="loginusers">
                        <div style="margin-bottom: 4vh;">
                            <input name="email" type="email" id="email" placeholder="Email" style="color:black; padding: 0.4vw; border-radius: 5px; border: none; width: 40%; font-size: 14px;" required>
                            <p style="color: #dc2626;"></p>
                        </div>

                        <div style="margin-bottom: 4vh;">
                            <input name="password" type="password" id="password" placeholder="Enter new password" style="color:black; padding: 0.4vw; border-radius: 5px; border: none; width: 40%; font-size: 14px;" required>
                            <p style="color: #dc2626;"></p>
                        </div>

                        <div style="margin-bottom: 4vh;">
                            <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm new password" style="color:black; padding: 0.4vw; border-radius: 5px; border: none; width: 40%; font-size: 14px;" required>
                            <p style="color: #dc2626;"></p>
                        </div>

                        <input type="hidden" name="token" value="<?= $token ?>">
                        <input type="hidden" name="resetpassword" value="Reset Password">

                        <div style="display: flex; gap: 2vw;">
                            <button id="submit-btn" type="button" style="background-color: #22c55e; color: white; border: none; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Reset Password</button>
                            <a href="forgot-password.php"><button type="button" style="background-color: transparent; color: white; border: 1px solid #22c55e; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Forgot Password</button></a>
                        </div>
                    </form>
                <?php
                } else {
                ?>
                    <h1>Sorry Your Token Has expired please !!!!</h1>
                <?php
                }
                ?>

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

                if (!$('#password').val()) {
                    $('#password').next().text('field required');
                    return;
                } else {
                    $('#password').next().text('');
                }

                if (!$('#confirm_password').val()) {
                    $('#confirm_password').next().text('field required');
                    return;
                } else {
                    $('#confirm_password').next().text('');
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
            <?php
            // if ($verified) {
            ?>
              <form action="" method="POST" class="form-signin" style="margin-bottom:10px" id="loginusers">
                <div style="width:100%; height:auto; background-color:#036">
                  <p><img src="<?php // $company_settings->floc;
                                ?>" style="height:100px; width:230px; margin-top:10px" class="imgdim" /></p>
                </div>
                <br />
                <?php
                // if (isset($_SESSION["errorMessage"])) {
                ?>
                  <div class='alert alert-danger'>
                    <p class="errormsg">
                      <img src="images/error.png" alt="errormsg" />
                      <?= $_SESSION["errorMessage"] ?>
                    </p>
                  </div>
                <?php
                // }
                // unset($_SESSION["errorMessage"]);
                ?>
                <p>
                  <input name="email" type="email" class="input-block-level" id="username" placeholder="Enter your email address" required />
                  <label for="password"></label>
                  <input name="password" type="password" class="input-block-level" id="password" placeholder="Enter new password" required />
                  <label for="password"></label>
                  <input name="confirm_password" type="password" class="input-block-level" id="password" placeholder="Confirm new password" required />
                </p>
                <p>
                  <input type="hidden" name="token" value="<?= $token ?>">
                  <input name="resetpassword" type="submit" class="loginbutton" id="submit" value="Reset Password" />
                </p>
                <a href="forgot-password.php">Forgot your password?</a>
              </form>
              <p>&nbsp;</p>
            <?php
            // } else {
            ?>
              <h1>Sorry Your Token Has expired please !!!!</h1>
            <?php
            // }
            ?>
          </div>
        </div>
        <p>&nbsp;</p>
      </div>
    </div>
  </div> -->
<?php
// include_once "includes/login-footer.php";
?>

<?php
session_start();
(!isset($_SESSION['MM_Username'])) ? $_SESSION['MM_Username'] : "index.php";

require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';

$user_auth = new Auth();
$company_details = new Company();
$company_settings = $company_details->get_company_details();
if (isset($_POST['setpass']) && $_POST['setpass'] == "setpassword") {
    $confirm_password = $_POST['confirm_password'];
    $password = $_POST['password'];
    if ($confirm_password === $password) {
        $userid = $_SESSION['MM_Username'];
        $user = $user_auth->change_password($userid, $password);
        if ($user) {
            $_SESSION['ministry'] = $user->ministry;
            $_SESSION['sector'] = $user->department;
            $_SESSION['designation'] = $user->designation;
            $_SESSION['role_group'] = $user->role_group;
            $_SESSION['directorate'] = $user->directorate;
            $_SESSION['avatar'] = $user->floc;
            $_SESSION['fullname'] = $user->fullname;
            $_SESSION["success"] =  "Successfully changed  password";
            header("location: dashboard.php");
        } else {
            $_SESSION["errorMessage"] =  "Error changing your password";
            header("location:set-new-password.php");
            return;
        }
    } else {
        $_SESSION["errorMessage"] =  "Check the passwords they do not match";
        header("location:set-new-password.php");
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
    <style media="screen">
        body {
            background-color: #036;
        }

        #loginuser {
            margin-left: 10px;
            margin-right: 10px;
            border: 1px solid #999;
            border-radius: 5px;
            box-shadow: 5px 5px 5px #888888;
            max-width: 100%;
            min-width: 50%;
        }

        .form-signin {
            max-width: 350px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #E8E8E8;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 5px 5px 5px #CCCCCC;
            margin-top: 20px;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .form-signin input[type="text"] {
            font-size: 14px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
            background-image: url(images/user.png);
            background-repeat: no-repeat;
            background-position: 98%;
            font-family: Verdana, Geneva, sans-serif;
            color: #000;
        }

        .form-signin input[type="password"] {
            font-size: 14px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
            background-image: url(images/pwd.png);
            background-repeat: no-repeat;
            background-position: 98%;
            font-family: Verdana, Geneva, sans-serif;
            color: #000;
        }

        .loginbutton {
            width: 130px;
            height: 40px;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 14px;
            color: #FFF;
            background-color: #06C;
            border: 5px solid #06C;
            border-radius: 3px;
            cursor: pointer;
            -webkit-appearance: button;
            font-weight: bold;
        }

        .errormsg {
            width: 80%;
            height: 30%;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            border: #930 1px solid;
            padding-left: 10px;
            padding-right: 10px;
            color: #000;
            padding-top: 10px;
            background-color: #F9B6AC;
            text-align: center;
            margin-left: 10px;
            padding-bottom: 10px;
        }


        .loginbutton {
            width: 200px;
            height: 40px;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 14px;
            color: #FFF;
            background-color: #06C;
            border: 5px solid #06C;
            border-radius: 3px;
            cursor: pointer;
            -webkit-appearance: button;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        #contentleft {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            float: left;
            width: 800px;
            padding-left: 5px;
            padding-right: 5px;
        }

        #footer {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            color: #999;
            height: 20px;
        }

        .row {
            margin-left: 0px;
            margin-top: 10px;
        }

        #content_area_cell {
            max-width: 100%;
            min-width: 70%;
            margin-left: 0px;
            background-color: #fff;
            padding-left: 5px;
        }

        .container-fluid1 {
            outline: 1px dashed #999;
        }

        .contenttitles {
            font-family: Candara;
            font-size: 28px;
            font-weight: bold;
            color: #036;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
            border-bottom-width: thin;
            border-bottom-style: dashed;
            border-bottom-color: #999;
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head>

<body>
    <p>&nbsp;</p>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div align="center">
                    <div class="container-fluid1" id="content_area_cell">
                        <h3 align="center" class="contenttitles">ProjTrac Monitoring, Evaluation, And Reporting System</h3>
                        <p>&nbsp;</p>
                        <form action="" method="POST" class="form-signin" style="margin-bottom:10px" id="loginusers">
                            <div style="width:100%; height:auto; background-color:#036">
                                <p><img src="<?= $company_settings->floc; ?>" style="height:100px; width:230px; margin-top:10px" class="imgdim" /></p>
                            </div>
                            <br />
                            <?php
                            if (isset($_SESSION["errorMessage"])) {
                            ?>
                                <div class='alert alert-danger'>
                                    <p class="errormsg">
                                        <img src="images/error.png" alt="errormsg" />
                                        Your login attempt failed. You may have entered a wrong username or wrong password.
                                    </p>
                                </div>
                            <?php
                            }
                            unset($_SESSION["errorMessage"]);
                            ?>
                            <h4 class="card-text">Welcome this is your first time to login, change password</h4>
                            <p>
                                <label for="password"></label>
                                <input name="password" type="password" class="input-block-level" id="password" placeholder="New Password" required />
                                <label for="password"></label>
                                <input name="confirm_password" type="password" class="input-block-level" id="password" placeholder="Confirm password" required />
                            </p>
                            <p>
                                <input type="hidden" name="setpass" value="setpassword">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input name="resetpassword" type="submit" class="loginbutton" id="submit" value="Reset Password" />
                            </p>
                            <a href="forgot-password.php">Forgot your password?</a>
                        </form>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
    <?php
    include_once "includes/login-footer.php";
    ?>
</body>

</html>