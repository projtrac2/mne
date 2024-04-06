<?php
require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';

try {
  //code...

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

    .m-email {
          color:black; padding: 1.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
        }

        .m-password {
          color:black; padding: 1.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
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

    /* Extra small devices (phones, 600px and down) */
    @media only screen and (max-width: 600px) {
      body {
        background-image: url('./images/main-4.jpg');
        background-repeat: no-repeat;
        background-size: 100% 100%;
        min-height: 100vh;
      }

      .m-padding {
        padding-top: 10vh;
        background-color: red;

      }
      .m-email {
          color:black; padding: 2.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
        }

        .m-password {
          color:black; padding: 1.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
        }
     

    }

  /* Small devices (portrait tablets and large phones, 600px and up) */
  @media only screen and (min-width: 600px) {
    body {
        background-image: url('./images/main-4.jpg');
        background-repeat: no-repeat;
        background-size: 100% 100%;
        min-height: 100vh;
      }

      .m-padding {
        padding-top: 20vh;
        text-align: center;
      }

      /* .m-email {
          color:black; padding: 1.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
        }

        .m-password {
          color:black; padding: 1.6vw !important; border-radius: 5px !important; border: none; width: 70%; font-size: 30px !important;
        } */
  }

  /* Medium devices (landscape tablets, 768px and up) */
  @media only screen and (min-width: 768px) {
    body {
      background-image: url('./images/main-4.jpg');
      background-repeat: no-repeat;
      background-size: 100% 100%;
      min-height: 100vh;
    }

    .m-padding {
      padding-top: 20vh;
    }
  }

  /* Large devices (laptops/desktops, 992px and up) */
  @media only screen and (min-width: 992px) {
    body {
        background-image: url('./images/back-14.jpg');
        background-repeat: no-repeat;
        background-size: 100% 100%;
        min-height: 100vh;
      }

      .m-padding {
        padding-top: 2vh;
      }

      .m-email {
          color:black; padding: 0.6vw; border-radius: 5px; border: none; width: 40%; font-size: 16px;
        }

        .m-password {
          color:black; padding: 0.6vw; border-radius: 10px; border: none; width: 40%; font-size: 16px;
        }
  }

  /* Extra large devices (large laptops and desktops, 1200px and up) */
  @media only screen and (min-width: 1200px) {
    body {
          background-image: url('./images/back-14.jpg');
          background-repeat: no-repeat;
          background-size: 100% 100%;
          min-height: 100vh;
        }

        .m-padding {
          padding-top: 2vh;
          text-align: left;
        }

        .btn-flex {
          display: flex; gap: 2vw;
        }

        .m-email {
          color:black; padding: 0.6vw !important; border-radius: 5px !important; border: none; width: 40%; font-size: 18px !important;
        }

        .m-password {
          color:black; padding: 0.6vw !important; border-radius: 5px !important; border: none; width: 40%; font-size: 18px !important;
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
      <div class="col-md-4 col-sm-12 m-padding">
        <div style="margin-bottom: 6vh;" class="m-bg">
          <img src="./images/logo-proj.png" alt="" srcset="" width="500">
        </div>

       
        <div style="margin-bottom: 4vh;">
          <h4 style="color: #003366;">Login to mne</h4>
          <!-- <p style="color: black;">Check email for otp code!</p> -->
        </div>

        <div class="glass-bg-resp">
        <form method="POST" id="loginusers">
          <div style="margin-bottom: 4vh;">
            <input class="m-email" name="email" type="email" id="email" placeholder="Email"  required>
            <p style="color: #dc2626;"></p>
          </div>

          <div style="margin-bottom: 4vh;">
            <input class="m-password" name="password" type="password" id="password" placeholder="Password"  required>
            <p style="color: #dc2626;"></p>
          </div>

          <input type="hidden" name="sign-in" value="sign-in">

          <div class="btn-flex" >
            <button id="submit-btn" type="button" style="background-color: #22c55e; color: white; border: none; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Sign In</button>
            <a href="forgot-password.php"><button type="button" style="background-color: transparent; color: white; border: 1.5px solid #003366; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 1px; border-radius: 5px;">Forgot Password</button></a>
          </div>
        </form>
        </div>
        
      </div>
      <div class="col-lg-8 col-md-8 col-sm-12">

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

<?php 
} catch (\PDOException $th) {
  customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>