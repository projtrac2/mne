<?php
require 'vendor/autoload.php';
require 'Models/Connection.php';
include "Models/Auth.php";
include "Models/Company.php";
require 'Models/Email.php';
include_once "includes/alerts.php";

$user_auth = new Auth();
$verified = ($token != "" && !empty($token)) ? $verified = $user_auth->verify_token($token) : false;

$company_details = new Company();
$company_settings = $company_details->get_company_details();

$response = "";
if (isset($_POST['login']) && $_POST['login'] == "slogin") {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$user = $user_auth->login($email, $password);
	if ($user) {
		session_start();
		$_SESSION['MM_Username'] = $user->userid;
		if ($user->first_login) {
			header("location: set-new-password.php");
		} else {
			header("location: dashboard.php");
		}
	} else {
		$msg = "Sorry!! you have entered wrong details";
		$response = message_alerts($msg, 2, "");
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Result-Based Monitoring &amp; Evaluation System: Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<link rel="stylesheet" href="assets/css/login.css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body>
	<div class="container h-100">
		<div class="d-flex justify-content-center">
			<h3>ProjTrac Monitoring, Evaluation, And Reporting System</h3>
		</div>
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<?= $response ?>
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="<?= $company_settings->floc; ?>" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="email" class="form-control input_user" value="" placeholder="example@email.com">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="Password">
						</div>
						<div class="d-flex justify-content-center mt-3 login_container">
							<input type="hidden" name="login" value="slogin">
							<button type="submit" name="button" class="btn login_btn">Login</button>
						</div>
					</form>
				</div>

				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						<a href="forgot-password.php">Forgot your password?</a>
					</div>
				</div>
			</div>
		</div>
		<?php
		// include_once "includes/login-footer.php";
		?>
	</div>

</body>

</html>