<?php
require('includes/head.php');

if ($permission) {
	try {
		$query_setting = $db->prepare("select * from `tbl_company_settings`");
		$query_setting->execute();
		$setting = $query_setting->fetch();
		$rows_count = $query_setting->rowCount();

		$query_country =  $db->prepare("SELECT id,country FROM countries where status=1");
		$query_country->execute();

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addcompanydetailsfrm")) {
			$current_date = date("Y-m-d");
			// $latitude = "-1.292066";
			// $longitude = "36.821946";

			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$company_name = $_POST['company_name'];
			$postal_address = $_POST['postal_address'];
			$country = $_POST['country'];
			$phone = $_POST['phone'];
			$mobile = $_POST['mobile'];
			$email = $_POST['email'];
			$ip_address =$_POST['ip_address'];
			$domain_address = $_POST['domain_address'];
			$plot_no =  $_POST['plot_no'];
			$city = $_POST['city'];
			if (!empty($_FILES['logo']['name'])) {
				$filename = basename($_FILES['logo']['name']);
				$ext = substr($filename, strrpos($filename, '.') + 1);

				if (($ext != "exe") && ($_FILES["logo"]["type"] != "application/x-msdownload")) {
					$filepath = "uploads/company/" . $filename;

					if (!file_exists($filepath)) {
						if (move_uploaded_file($_FILES['logo']['tmp_name'], $filepath)) {
							if($rows_count == 0){
								$insertSQL = $db->prepare("INSERT INTO tbl_company_settings (company_name, postal_address, country, telephone_no, mobile_no, email_address, city, plot_no, domain_address, ip_address, logo, latitude, longitude, created_by, date_created) VALUES (:companyname, :postal, :country, :phone, :mobile, :email, :city, :plotno, :domain, :ipaddress, :logo, :latitude, :longitude, :user, :date)");
								$insertSQL->execute(array(':companyname' => $company_name, ':postal' => $postal_address, ':country' => $country, ':phone' => $phone, ':mobile' => $mobile, ':email' => $email, ':city' => $city, ':plotno' =>$plot_no,  ':domain' => $domain_address, ':ipaddress' => $ip_address, ':logo' => $filepath, ':latitude' => $latitude, ':longitude' => $longitude, ':user' => $user_name, ':date' => $current_date));
								
								$msg = "Company details successfully added";
							}else{
								$detailsid = $setting["id"];
								$updateQuery = $db->prepare("UPDATE tbl_company_settings SET company_name=:companyname, postal_address=:postal, country=:country, telephone_no=:phone, mobile_no=:mobile, email_address=:email, city=:city, plot_no=:plotno, domain_address=:domain, ip_address=:ipaddress, logo=:logo, latitude=:latitude, longitude=:longitude, created_by=:user, date_created=:date  WHERE id=:id");
								$updatest = $updateQuery->execute(array(':companyname' => $_POST['company_name'], ':postal' => $_POST['postal_address'], ':country' => $_POST['country'], ':phone' => $_POST['phone'], ':mobile' => $_POST['mobile'], ':email' => $_POST['email'], ':city' => $_POST['city'], ':plotno' => $_POST['plot_no'],  ':domain' => $_POST['domain_address'], ':ipaddress' => $_POST['ip_address'], ':logo' => $filepath, ':latitude' => $latitude, ':longitude' => $longitude, ':user' => $user_name, ':date' => $current_date, ':id' => $detailsid));
								$msg = "Company details successfully updated";
							} 
						}
					} else {
						$msg = 'The logo file you are uploading already exists, try another file!!';
						$results =
							"<script type=\"text/javascript\">
								swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 3000,
									showConfirmButton: false 
								});
							</script>";
					}
				} else {
					$msg = 'This logo file type is not allowed, try another file!!';
					$results =
						"<script type=\"text/javascript\">
							swal({
								title: \"Error!\",
								text: \" $msg \",
								type: 'Danger',
								timer: 3000,
								showConfirmButton: false });
							</script>";
				}
			} else {
				$msg = 'You have not attached any logo file!!';
				$results = "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg \",
							type: 'Danger',
							timer: 3000,
							showConfirmButton: false 
						});
					</script>";
			}
			//$msg = 'company details successfully added.';
			$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'organization-details.php';
					}, 2000);
				</script>";
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<script src="assets/ckeditor/ckeditor.js"></script>
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
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->

							<form id="add_financier" method="POST" name="addfinancierfrm" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
									<div class="col-md-12">
										<label>Organization Name *:</label>
										<div>
											<input name="company_name" type="text" class="form-control" placeholder="Enter organization name" value="<?= $setting["company_name"] ?>" style="border:#CCC thin solid; border-radius: 5px" required />
										</div>
									</div>
									<div class="col-md-12">
										<label>postal_address *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="postal_address" placeholder="Enter the organization address" value="<?= $setting["postal_address"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>City *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="city" placeholder="Enter city" value="<?= $setting["city"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>Country *:</label>
										<div class="form-line">
											<select name="country" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
												<option value="" selected="selected" class="selection">...Select organization Country...</option>
												<?php
												$countrycode = $setting["country"];
												while ($row_country = $query_country->fetch()) {
													$selected = "";
													$code = $row_country['id'];
													if($countrycode == $code){
														$selected = "selected";
													}
													?>
													<option value="<?php echo $row_country['id'] ?>" <?php echo $selected ?> ><?php echo $row_country['country'] ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<label>Telephone Number *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="phone" placeholder="Enter Telephone number" value="<?= $setting["telephone_no"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>Mobile Number *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="mobile" placeholder="Enter mobile number" value="<?= $setting["mobile_no"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>Plot Number :</label>
										<div class="form-line">
											<input type="text" class="form-control" name="plot_no" placeholder="Enter organization location plot number" value="<?= $setting["plot_no"] ?>" style="border:#CCC thin solid; border-radius: 5px">
										</div>
									</div>
									<div class="col-md-3">
										<label>Email *:</label>
										<div class="form-line">
											<input type="email" class="form-control" name="email" placeholder="Enter financier email" value="<?= $setting["email_address"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>Domain name *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="domain_address" placeholder="Enter system domain name" value="<?= $setting["domain_address"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
									<div class="col-md-3">
										<label>System host ip address *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="ip_address" placeholder="Enter system host ip address" value="<?= $setting["ip_address"] ?>" style="border:#CCC thin solid; border-radius: 5px" required>
										</div>
									</div>
								</fieldset>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Organization Logo</legend>
									<!-- File Upload | Drag & Drop OR With Click & Choose -->
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card" style="margin-bottom:-20px">
												<div class="body">
													<table class="table table-bordered" id="donation_table">
														<tr>
															<th style="width:100%">Attachments</th>
														</tr>
														<tr>
															<td>
																<input type="file" name="logo" multiple id="logo" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
									<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
								</fieldset>
								<div class="row clearfix">
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
										<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
										<div class="btn-group">
											<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
										</div>
										<input type="hidden" name="MM_insert" value="addcompanydetailsfrm" />
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
								</div>
							</form>
							<!-- ============================================================== -->
							<!-- End PAge Content -->
							<!-- ============================================================== -->
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
require('includes/footer.php');
?>