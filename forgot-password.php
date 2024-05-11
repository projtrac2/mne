<?php
try {
  include_once('includes/auth-head.php');
  if (isset($_POST['forgotpassword']) && $_POST['forgotpassword'] == "Forgot Password") {
    $email = $_POST['email'];
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
  <div class="container">
    <div class="row">
      <div class="col-md-4" style="padding-top: 8vh;">
        <div class="glass-morphism">
          <div style="margin-bottom: 6vh;" class="m-bg glass-bg-resp">
            <img src="images/logo-proj.png" alt="" srcset="" width="400">
          </div>
          <div style="margin-bottom: 4vh;" class="glass-bg-resp">
            <h4 style="color: #003366;">Forgot your password ?</h4>
            <p style="color: white;">Enter your email to reset it!</p>
          </div>
          <!-- inputs -->
          <div class="glass-bg-resp">
            <form method="POST" id="loginusers">
              <div style="margin-bottom: 4vh;">
                <input class="m-email" name="email" type="email" id="email" placeholder="Email" required>
                <p style="color: #dc2626;"></p>
              </div>
              <input type="hidden" name="forgotpassword" value="Forgot Password">
              <div class="btn-flex">
                <button id="forgot-btn" class="submit-btn">Forgot Password</button>
                <a href="index.php">
                  <button type="button" style="background-color: transparent; color: white; border: 1.5px solid #003366; padding-left: 2vw; padding-right: 2vw; padding-top: 0.5vw; padding-bottom: 0.5vw; font-size: 14px; font-weight: 600; letter-spacing: 0.5px; border-radius: 5px;">Go To Login
                  </button>
                </a>
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
  include_once('includes/auth-footer.php');
} catch (PDOException $ex) {
  customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>