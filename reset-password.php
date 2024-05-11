<?php
try {
  include_once('includes/auth-head.php');

  if ((isset($_GET['token']) && !empty($_GET['token']))) {
    $token = $_GET['token'];
    if (isset($_POST['resetpassword']) && $_POST['resetpassword'] == "Reset Password") {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $confirm_password = $_POST['confirm_password'];
      $token = $_POST['token'];
      $user = $user_auth->get_user($email);
      if ($user && $confirm_password === $password) {
        $verify = $user_auth->verify_token($token);
        if ($verify) {
          $reset = $user_auth->reset_password($email, $token, $password);
          if ($reset) {
            $_SESSION["successMessage"] =  "Successfully reset password";
            header("location:index.php");
            return;
          } else {
            $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong email address.";
            // header("location:reset-password.php?token=$token");
            return;
          }
        } else {
          $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong email address.";
          // header("location:reset-password.php?token=$token");
          return;
        }
      } else {
        $_SESSION["errorMessage"] =  "Your login attempt failed. You may have entered a wrong email address.";
        // header("location:reset-password.php?token=$token");
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
            </div>
            <?php
            if ($verified) {
            ?>
              <!-- inputs -->
              <div style="margin-bottom: 4vh;" class="glass-bg-resp">
                <h4 style="color: #003366;">Reset password</h4>
              </div>
              <div class="glass-bg-resp">
                <form method="POST" id="loginusers">
                  <div style="margin-bottom: 4vh;">
                    <input name="email" type="email" id="email" autocomplete="off" placeholder="Email" class="m-email" required>
                    <p style="color: #dc2626;"></p>
                  </div>

                  <div style="margin-bottom: 4vh;">
                    <input name="password" type="password" id="password" placeholder="Enter new password" class="m-password" required>
                    <p style="color: #dc2626;"></p>
                  </div>

                  <div style="margin-bottom: 4vh;">
                    <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm new password" class="m-password" required>
                    <p style="color: #dc2626;"></p>
                  </div>
                  <input type="hidden" name="token" value="<?= $token ?>">
                  <input type="hidden" name="resetpassword" value="Reset Password">
                  <div class="btn-flex">
                    <button id="reset-btn" type="button" class="submit-btn">Reset Password</button>
                    <a href="forgot-password.php"><button type="button" id="forgot-password">Forgot Password</button></a>
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
          </div>
        </div>
        <div class="col-md-8">
        </div>
      </div>
    </div>
<?php
  } else {
  }
} catch (PDOException $ex) {
  customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>