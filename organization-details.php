<?php
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
	$pageTitle = "Organization Details";

	try {
		$setting = $db->query("select * from `tbl_company_settings`")->fetch();

		$query_title =  $db->prepare("SELECT id,title FROM tbl_mbrtitle");
		$query_title->execute();

		$query_financiertype =  $db->prepare("SELECT * FROM tbl_funding_type where status=1 ORDER BY id ASC");
		$query_financiertype->execute();

		$query_country =  $db->prepare("SELECT id,country FROM countries");
		$query_country->execute();

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfinancierfrm")) {
			$current_date = date("Y-m-d");
			$results = "";

			//if(!empty($_POST['financier']) && !empty($_POST['type']) && !empty($_POST['contactperson']) && !empty($_POST['title']) && !empty($_POST['designation']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['country']) && !empty($_POST['phone']) && !empty($_POST['email']) && !empty($_POST['comments'])){

			$insertSQL = $db->prepare("INSERT INTO tbl_company_settings (financier, type, contact, title, designation, address, city, state, country, phone, email, comments, created_by, date_created) VALUES (:financier, :type, :contact, :title, :designation, :address, :city, :state, :country, :phone, :email, :comments, :user, :date)");
			$insertSQL->execute(array(':financier' => $_POST['financier'], ':type' => $_POST['type'], ':contact' => $_POST['contactperson'], ':title' => $_POST['title'], ':designation' => $_POST['designation'], ':address' => $_POST['address'], ':city' => $_POST['city'],  ':state' => $_POST['state'], ':country' => $_POST['country'], ':phone' => $_POST['phone'], ':email' => $_POST['email'], ':comments' => $_POST['comments'], ':user' => $user_name, ':date' => $current_date));

			$last_id = $db->lastInsertId();
			if ($insertSQL->rowCount() == 1) {
				$filecategory = "Financiers";
				$catid = $last_id;
				$myUser = $_POST['user_name'];

				$count = count($_POST["attachmentpurpose"]);

				for ($cnt = 0; $cnt < $count; $cnt++) {
					if (!empty($_FILES['financierattachment']['name'][$cnt])) {
						$reason = $_POST["attachmentpurpose"][$cnt];
						$filename = basename($_FILES['financierattachment']['name'][$cnt]);
						$ext = substr($filename, strrpos($filename, '.') + 1);

						if (($ext != "exe") && ($_FILES["financierattachment"]["type"][$cnt] != "application/x-msdownload")) {
							$newname = $catid . "-" . $filename;
							$filepath = "uploads/financiers/" . $newname;

							if (!file_exists($filepath)) {
								if (move_uploaded_file($_FILES['financierattachment']['tmp_name'][$cnt], $filepath)) {
									$qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:stage, :fname, :ext, :floc, :fcat, :reason, :user, :date)");
									$qry2->execute(array(':stage' => $catid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $reason, ':user' => $myUser, ':date' => $current_date));
								}
							} else {
								$msg = 'File you are uploading already exists, try another file!!';
								$results = "<script type=\"text/javascript\">
										swal({
											title: \"Error!\",
											text: \" $msg \",
											type: 'Danger',
											timer: 3000,
											showConfirmButton: false });
									</script>";
							}
						} else {
							$msg = 'This file type is not allowed, try another file!!';
							$results = "<script type=\"text/javascript\">
									swal({
										title: \"Error!\",
										text: \" $msg \",
										type: 'Danger',
										timer: 3000,
										showConfirmButton: false });
									</script>";
						}
					} else {
						$msg = 'You have not attached any file!!';
						$results = "<script type=\"text/javascript\">
								swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 3000,
									showConfirmButton: false });
							</script>";
					}
				}

				$msg = 'Financier successfully added.';
				$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'view-financiers';
						}, 2000);
					</script>";
			}
			//}

			echo $results;
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
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
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
						<div class="header" style="padding-bottom:5px; margin-left:-10px; margin-right:-12px">
							<?php include_once("settings-menu.php"); ?>
						</div>
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
										<label>Address *:</label>
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
												while ($row_country = $query_country->fetch()) {
												?>
													<option value="<?php echo $row_country['id'] ?>"><?php echo $row_country['country'] ?></option>
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
																<input type="file" name="financierattachment" multiple id="financierattachment" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
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
										<input type="hidden" name="MM_insert" value="addfinancierfrm" />
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