<?php
try {
    include_once('includes/auth-head.php');
    if (isset($_SESSION['MM_Username_Email'])) {
        $user_email = $_SESSION['MM_Username_Email'];
        if (isset($_POST['sign-in'])) {
            $otp_code = $_POST['otp_code'];
            $checkIfOtpExpired =  $user_auth->checkIfOptExpired($user_email, $otp_code);
            if ($checkIfOtpExpired) {
                $user = $user_auth->get_user($user_email);
                if ($user) {
                    $_SESSION['MM_Username_Email'] = null;
                    $_SESSION['MM_Username'] = $user->userid;
                    $_SESSION['ministry'] = $user->ministry;
                    $_SESSION['sector'] = $user->department;
                    $_SESSION['designation'] = $user->designation;
                    $_SESSION['directorate'] = $user->directorate;
                    $_SESSION['avatar'] = $user->floc;
                    $_SESSION['fullname'] = $user->fullname;
                    $_SESSION["success"] =  "Successfully changed password";
                    header("location: dashboard.php");
                } else {
                    $_SESSION["successMessage"] = "Sorry your details are incorrect!";
                    header("location: otp.php");
                    return;
                }
            } else {
                $mail_otp_code = $user_auth->otp($user_email);
                $_SESSION["successMessage"] = "Sorry Otp code has been expired a new code has been sent to your email!";
                header("location: otp.php");
                return;
            }
        }

        if (isset($_POST['resend']) && $_POST['resend'] == "resend otp") {
            $mail_otp_code = $user_auth->otp($user_email);
            $_SESSION['MM_Username_Email'] = $user_email;
            logActivity("resend otp code", "false");
            if ($mail_otp_code) {
                $_SESSION["successMessage"] = "Otp code has been resent to your email!";
                header("location: otp.php");
                return;
            } else {
                $_SESSION["successMessage"] = "Sorry OTP could not be sent please try again later!";
                header("location: otp.php");
                return;
            }
        }
?>
        <div class="container">
            <div class="row">
                <div class="col-md-4 m-padding">
                    <div class="glass-morphism">
                        <div class="m-bg glass-bg-resp">
                            <img src="./images/logo-proj.png" alt="" srcset="" width="500">
                        </div>
                        <div style="margin-bottom: 4vh;" class="glass-bg-resp">
                            <h4 style="color: #003366;">OTP Verification</h4>
                            <p style="color: black;">Check email for otp code!</p>
                        </div>
                        <!-- inputs -->
                        <div class="glass-bg-resp">
                            <form method="POST" id="loginusers">
                                <div style="margin-bottom: 4vh;">
                                    <input class="m-email" name="otp_code" type="text" id="otp_code" placeholder="OTP Code" required>
                                    <p style="color: #dc2626;"></p>
                                </div>
                                <input type="hidden" name="sign-in" value="sign-in">
                                <div class="btn-flex">
                                    <button id="otp-btn" type="button" class="submit-btn">Sign In</button>
                                </div>
                            </form>
                            <form method="post" id="resend-form">
                                <input type="hidden" name="resend" value="resend otp">
                                <p style="color: black;">Didn't receive otp? <a type="button" id="resend-btn" style="color:#003366;">Resend</a></p>
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
    } else {
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>