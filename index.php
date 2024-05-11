<?php
try {
  include_once('includes/auth-head.php');
  if (isset($_SESSION['attempt_again'])) {
    $now = time();
    if ($now >= $_SESSION['attempt_again']) {
      unset($_SESSION['attempt']);
      unset($_SESSION['attempt_again']);
    }
  }

  if (isset($_POST['sign-in'])) {
    if (!isset($_SESSION['attempt'])) {
      $_SESSION['attempt'] = 0;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = $user_auth->login($email, $password);
    // $company_settings->login_attempts

    if ($_SESSION['attempt'] == 3) {
      $_SESSION['errorMessage'] = 'Attempt limit reached';
      $user_auth->suspicious_activity($email);
      header("location:index.php");
      return;
    } else {
      if ($user) {
        unset($_SESSION['attempt']);
        if ($user->first_login) {
          $_SESSION['MM_Username_First_Login'] = $user->userid;
          header("location: set-new-password.php");
        } else {
          if (isset($_GET['action'])) {
            $page_url = $_GET['action'];
            header("location: $page_url");
          } else {
            $mail_otp_code = $user_auth->otp($email);
            if ($mail_otp_code) {
              $_SESSION['MM_Username_Email'] = $user->email;
              header("location: otp.php");
            }
          }
        }
      } else {
        $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong username or wrong password.";
        //this is where we put our 3 attempt limit
        $_SESSION['attempt'] += 1;
        //set the time to allow login if third attempt is reach
        if ($_SESSION['attempt'] == 3) {
          $_SESSION['attempt_again'] = time() + (5 * 60);
          //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
        }
        header("location:index.php");
        return;
      }
    }
  }

?>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12 m-padding">
        <div class="glass-morphism">
          <div style="margin-bottom: 6vh;" class="m-bg glass-bg-resp">
            <img src="images/logo-proj.png" alt="" srcset="" width="500">
          </div>
          <div style="margin-bottom: 4vh;" class="glass-bg-resp">
            <h4 style="color: #003366;">Login to M&E</h4>
          </div>
          <div class="glass-bg-resp">
            <form method="POST" id="loginusers">
              <div style="margin-bottom: 1vh;">
                <input class="m-email" name="email" type="email" id="email" placeholder="Email" required>
                <p style="color: #dc2626;"></p>
              </div>
              <div style="margin-bottom: 4vh;">
                <input class="m-password" name="password" type="password" id="password" placeholder="Password" required>
                <p style="color: #dc2626;"></p>
              </div>
              <input type="hidden" name="sign-in" value="sign-in">
              <div class="btn-flex">
                <button id="login-btn" type="button" class="submit-btn">Sign In</button>
                <a href="forgot-password.php"><button type="button" id="forgot-password">Forgot Password</button></a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-12 col-sm-12">
      </div>
    </div>
  </div>
<?php
  include_once('includes/auth-footer.php');
} catch (PDOException $ex) {
  customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>