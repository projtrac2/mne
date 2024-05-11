<?php
try {
  include_once('includes/auth-head.php');
  if ((isset($_SESSION['MM_Username_First_Login']) && !empty($_SESSION['MM_Username_First_Login']))) {
    if (isset($_POST['setpass']) && $_POST['setpass'] == "setpassword") {
      $confirm_password = $_POST['confirm_password'];
      $password = $_POST['password'];
      if ($confirm_password === $password) {
        $user = $user_auth->change_password($user_name, $password);
        if ($user) {
          $_SESSION['MM_Username_First_Login'] = null;
          $_SESSION['MM_Username'] = $user->userid;
          $_SESSION['ministry'] = $user->ministry;
          $_SESSION['sector'] = $user->department;
          $_SESSION['designation'] = $user->designation;
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
    <div class="container">
      <div class="row">
        <div class="col-md-4 m-padding">
          <div class="glass-morphism">
            <div class="m-bg glass-bg-resp">
              <img src="images/logo-proj.png" alt="" srcset="" width="400">
              <img src="images/logo-proj.png" alt="" srcset="" width="400">
            </div>
            <!-- inputs -->
            <div style="margin-bottom: 4vh;" class="glass-bg-resp">
              <h4 style="color: #003366;">Reset password</h4>
            </div>
            <div class="glass-bg-resp">
              <form method="POST" id="loginusers">
                <div style="margin-bottom: 4vh;">
                  <input name="password" type="password" id="password" placeholder="Enter new password" class="m-password" required>
                  <p style="color: #dc2626;"></p>
                </div>
                <div style="margin-bottom: 4vh;">
                  <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm new password" class="m-password" required>
                  <p style="color: #dc2626;"></p>
                </div>
                <input type="hidden" name="setpass" value="setpassword">
                <div class="btn-flex">
                  <button id="setpass-btn" type="button" class="submit-btn">Set New Password</button>
                  <a href="index.php"><button type="button" id="forgot-password">Forgot Password</button></a>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        </div>
      </div>
    </div>
<?php
  } else {
    // var_dump("Things are not working return to dashboard");
  }
  include_once('includes/auth-footer.php');
} catch (PDOException $ex) {
  // customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>