<?php
try {
	//code...

require('includes/head.php');
if ($permission) {
	$emailsetting = $db->query("select * from `tbl_email_settings`")->fetch();

	if (isset($_POST["submit"])) {
		$today = date('Y-m-d');
		$smtpAutoTLS = $_POST["autotls"];
		$SMTPAuth = $_POST["SMTPAuth"];
		$port = $_POST["Port"];
		$host = $_POST["host"];
		$security = $_POST["security"];
		$username = $_POST["username"];
		$password = $_POST["password"];
		$id = $_POST["id"];
		$createdby = 1;

		if (empty($id)) {
			$save = $db->prepare("INSERT INTO tbl_email_settings SET smtpAutoTLS = :smtpAutoTLS, SMTPAuth = :SMTPAuth, port = :port, host = :host, SMTPSecure = :security, username = :username, password = :password, createdby = :createdby, dateCreated = :today");
			$result  = $save->execute(array(":smtpAutoTLS" => $smtpAutoTLS, ":SMTPAuth" => $SMTPAuth, ':port' => $port, ':host' => $host, ':security' => $security, ':username' => $username, ":password" => $password, ":createdby" => $createdby, ":today" => $today));
		} else {
			$save = $db->prepare("UPDATE tbl_email_settings SET smtpAutoTLS = :smtpAutoTLS, SMTPAuth = :SMTPAuth, port = :port, host = :host, SMTPSecure = :security, username = :username, password = :password, createdby = :createdby, dateCreated = :today WHERE id = :id");
			$result  = $save->execute(array(":smtpAutoTLS" => $smtpAutoTLS, ":SMTPAuth" => $SMTPAuth, ':port' => $port, ':host' => $host, ':security' => $security, ':username' => $username, ":password" => $password, ":createdby" => $createdby, ":today" => $today, ":id" => $id));
		}

		if ($result) {
			$msg = 'Settings details successfully saved.';
			$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'email_configuration.php';
			  }, 5000);
		</script>";
		} else {
			$msg = 'Can not save the provided details!!';
			$results = "<script type=\"text/javascript\">
			swal({
				title: \"Error!\",
				text: \" $msg \",
				type: 'Danger',
				timer: 5000,
				showConfirmButton: false });
		</script>";
		}
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php include_once("settings-menu.php"); ?>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<form method="POST" name="addemailconffrm" id="email-conf" action="" enctype="multipart/form-data" autocomplete="off">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Email Configurations</legend>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">SMTPAutoTLS <span style="color:red;font-size:15px;">*</span> :</label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['smtpAutoTLS']; ?>" name="autotls" placeholder="Enter SMTPAutoTLS" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">SMTPAuth <span style="color:red;font-size:15px;">*</span> :</label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['SMTPAuth']; ?>" name="SMTPAuth" placeholder="Enter SMTPAuth" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">Port <span style="color:red;font-size:15px;">*</span> :</label>
										<div class="col-lg-6">
											<input type="number" class="form-control" value="<?php echo $emailsetting['port']; ?>" name="Port" placeholder="Enter email port" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">Security <span style="color:red;font-size:15px;">*</span></label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['SMTPSecure']; ?>" name="security" placeholder="Enter email security (SSL/TLS)" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">Host <span style="color:red;font-size:15px;">*</span></label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['host']; ?>" name="host" placeholder="Enter email host" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">Username <span style="color:red;font-size:15px;">*</span></label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['username']; ?>" name="username" placeholder="Enter username" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<div class="form-group row">
										<div class="col-lg-2"></div>
										<label class="col-lg-2" style="margin-top: 10px;">Password <span style="color:red;font-size:15px;">*</span></label>
										<div class="col-lg-6">
											<input type="text" class="form-control" value="<?php echo $emailsetting['password']; ?>" name="password" placeholder="Enter Password" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
										</div>
										<div class="col-lg-2"></div>
									</div>

									<input type="hidden" value="<?php echo $emailsetting['id']; ?>" name="id" />
									<div class="col-md-12" align="center">
										<div class="form-line" align="center" style="padding-top:15px">
											<div class="d-flex w-100 justify-content-center align-items-center">
												<button type="button" class="btn btn-warning waves-effect waves-light mx-2" data-dismiss="modal"> Cancel</button>
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
											</div>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>
<script src="assets/custom js/indicator-details.js"></script>