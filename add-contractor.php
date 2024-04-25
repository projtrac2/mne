<?php
require('functions/strategicplan.php');
require('includes/head.php');

if ($permission) {
	try {
		$query_rsPinStatus = $db->prepare("SELECT id,pin_status FROM tbl_contractorpinstatus");
		$query_rsPinStatus->execute();
		$row_rsPinStatus = $query_rsPinStatus->fetch();
		$totalRows_rsPinStatus = $query_rsPinStatus->rowCount();

		$query_rsContrVat = $db->prepare("SELECT id,vat FROM tbl_contractorvat");
		$query_rsContrVat->execute();
		$row_rsContrVat = $query_rsContrVat->fetch();
		$totalRows_rsContrVat = $query_rsContrVat->rowCount();

		$query_rsBiztype = $db->prepare("SELECT * FROM tbl_contractorbusinesstype");
		$query_rsBiztype->execute();
		$row_rsBiztype = $query_rsBiztype->fetch();
		$totalRows_rsBiztype = $query_rsBiztype->rowCount();

		$edit_form = false;
		if ((isset($_GET["edit"])) && ($_GET["edit"] == "1")) {
			$contrid = "-1";
			if (isset($_GET['contrid'])) {
				$contrid = $_GET['contrid'];
			}
			$edit_form = true;
			$query_rsContrEdit = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid='$contrid'");
			$query_rsContrEdit->execute();
			$row_rsContrEdit = $query_rsContrEdit->fetch();
			$totalRows_rsContrEdit = $query_rsContrEdit->rowCount();

			$action = "Edit";
			$submitAction = "MM_update";
			$formName = "editsectorfrm";
			$submitValue = "Update";
			$cntBizType = $row_rsContrEdit["businesstype"];
			$contrcounty = $row_rsContrEdit["county"];
			$cntPinStatus = $row_rsContrEdit["pinstatus"];
			$cntVat = $row_rsContrEdit["vatregistered"];

			$query_rsBztype = $db->prepare("SELECT * FROM tbl_contractorbusinesstype where id='$cntBizType'");
			$query_rsBztype->execute();
			$row_rsBztype = $query_rsBztype->fetch();
			$totalRows_rsBztype = $query_rsBztype->rowCount();

			$query_rsPnstatus = $db->prepare("SELECT * FROM tbl_contractorpinstatus where id='$cntPinStatus'");
			$query_rsPnstatus->execute();
			$row_rsPnstatus = $query_rsPnstatus->fetch();
			$totalRows_rsPnstatus = $query_rsPnstatus->rowCount();

			$query_rsVat = $db->prepare("SELECT * FROM tbl_contractorvat where id='$cntVat'");
			$query_rsVat->execute();
			$row_rsVat = $query_rsVat->fetch();
			$totalRows_rsVat = $query_rsVat->rowCount();

			$query_rsCnty = $db->prepare("SELECT * FROM counties where id='$contrcounty'");
			$query_rsCnty->execute();
			$row_rsCnty = $query_rsCnty->fetch();
			$totalRows_rsCnty = $query_rsCnty->rowCount();

			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addcontractorfrm")) {
				$changedon = date("Y-m-d H:i:s");
				$contractorname = $_POST['contractorname'];
				$biztype = $_POST['biztype'];
				$bizregno = $_POST['bizregno'];
				$pinnumber = $_POST['pinnumber'];
				$dateregistered = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dateregistered'])));
				$pinstatus = $_POST['pinstatus'];
				$vat = $_POST['vat'];
				$postaladdress = $_POST['postaladdress'];
				$city = $_POST['city'];
				$county = $_POST['county'];
				$phone = $_POST['phone'];
				$email = $_POST['email'];
				$physicaladdress = $_POST['physicaladdress'];
				$comments = $_POST['comments'];
				$user_name = $_POST['username'];
				$username = $_POST['username'];

				$sqlupdate = $db->prepare("UPDATE tbl_contractor SET contractor_name='$contractorname', businesstype='$biztype', pinno='$pinnumber', busregno='$bizregno', dateregistered='$dateregistered', pinstatus='$pinstatus', vatregistered='$vat', contact='$postaladdress', address='$physicaladdress', city='$city', county='$county', phone='$phone', email='$email', comments='$comments', updated_by='$user_name', date_updated='$changedon' WHERE contrid='$contrid'");
				$retval = $sqlupdate->execute();
				$last_id = $contrid;

				$deleteQuery = $db->prepare("DELETE FROM `tbl_contractordirectors` WHERE contrid=:last_id");
				$results = $deleteQuery->execute(array(':last_id' => $contrid));

				$current_date = date("Y-m-d");
				for ($cnt = 0; $cnt < count($_POST["passport"]); $cnt++) {
					$fullname = $_POST['fullname'][$cnt];
					$pinpassport = $_POST['passport'][$cnt];
					$nationality = $_POST['nationality'][$cnt];

					$insertSQL = $db->prepare("INSERT INTO tbl_contractordirectors (contrid, fullname, pinpassport, nationality, created_by, date_created) VALUES (:contrid, :fullname, :pinpassport, :nationality, :createdby, :datecreated)");
					$insertSQL->execute(array(':contrid' => $last_id, ':fullname' => $fullname, ':pinpassport' => $pinpassport, ':nationality' => $nationality, ':createdby' => $username, ':datecreated' => $current_date));
				}
				// Warning: move_uploaded_file(): Unable to move '/tmp/phpB0o6U1' to 'uploads/contractordirectors/4_6566_File_8-GWDTL-63-New Text Document.txt' in /home/biwottech/Documents/mne/add-contractor.php on line 125


				$deleteQuery = $db->prepare("DELETE FROM `tbl_contractordocuments` WHERE contrid=:contrid");
				$results = $deleteQuery->execute(array(':contrid' => $contrid));

				$current_date = date("Y-m-d");
				$rd2 = mt_rand(1000, 9999) . "_File";
				$count = count($_POST["attachmentpurpose"]);

				for ($cnt = 0; $cnt < $count; $cnt++) {
					$purpose = $_POST["attachmentpurpose"][$cnt];
					if (!empty($_FILES['attachment']['name'][$cnt])) {
						$filename = basename($_FILES['attachment']['name'][$cnt]);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						$newname = "uploads/contractordirectors/" . $last_id . "_" . $rd2 . "_" . $filename;
						if (!file_exists($newname)) {
							if (move_uploaded_file($_FILES['attachment']['tmp_name'][$cnt], $newname)) {
								$qry2 = $newname;
								$purpose = $_POST['attachmentpurpose'][$cnt];
								$insertSQL = $db->prepare("INSERT INTO tbl_contractordocuments (contrid, floc, file_format, attachment_purpose, created_by, date_created) VALUES (:contrid, :floc, :fileformat, :purpose, :createdby, :datecreated)");
								$insertSQL->execute(array(':contrid' => $last_id, ':floc' => $qry2, ':fileformat' => $ext, ':purpose' => $purpose, ':createdby' => $username, ':datecreated' => $current_date));
							} else {
								echo "Could not upload : " . $newname;
							}
						}
					}
				}

				if ($retval) {
					header("Location:view-contractors.php");
					$msg = 'Contractor Successfully Added';
					$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
							'icon':'success',
						showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'view-contractors.php';
						}, 2000);
					</script>";
				} else {
					header("Location:view-contractors.php");
					$msg = 'Failed to add the contractor!';
					$results = "<script type=\"text/javascript\">
						swal({
							title: \"Warning!\",
							text: \" $msg\",
							type: 'Warning',
							timer: 2000,
							'icon':'warning',
						showConfirmButton: false });
					</script>";
				}
			}
		} else {
			$action = "Add";
			$submitAction = "MM_insert";
			$formName = "addcontractorfrm";
			$submitValue = "Submit";

			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addcontractorfrm")) {
				$current_date = date("Y-m-d");

				$contractorname = $_POST['contractorname'];
				$businesstype = $_POST['biztype'];
				$pinno = $_POST['pinnumber'];
				$busregno = $_POST['bizregno'];
				$dateregistered = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dateregistered'])));
				$pinstatus = $_POST['pinstatus'];
				$vatregistered = $_POST['vat'];
				$contact = $_POST['postaladdress'];
				$address = $_POST['physicaladdress'];
				$city = $_POST['city'];
				$county = $_POST['county'];
				$phone = $_POST['phone'];
				$email = $_POST['email'];
				$comments = $_POST['comments'];
				$username = $_POST['username'];

				$qry2 = $db->prepare("INSERT INTO tbl_contractor (contractor_name, businesstype, pinno, busregno, dateregistered, pinstatus, vatregistered, contact, address, city, county, phone, email, comments, date_created, user_name) VALUES (:contractorname, :businesstype, :pinno, :busregno, :dateregistered, :pinstatus, :vatregistered, :contact, :address, :city, :county, :phone, :email, :comments, :datecreated, :username)");
				$Result1 = $qry2->execute(array(':contractorname' => $contractorname, ':businesstype' => $businesstype, ':pinno' => $pinno, ':busregno' => $busregno, ':dateregistered' => $dateregistered, ':pinstatus' => $pinstatus, ':vatregistered' => $vatregistered, ':contact' => $contact, ':address' => $address, ':city' => $city, ':county' => $county, ':phone' => $phone, ':email' => $email, ':comments' => $comments, ':datecreated' => $current_date, ':username' => $username));

				if ($Result1 && isset($_POST["passport"])) {
					$username = $_POST['username'];
					$last_id = $db->lastInsertId();
					$current_date = date("Y-m-d");
					for ($cnt = 0; $cnt < count($_POST["passport"]); $cnt++) {
						$fullname = $_POST['fullname'][$cnt];
						$pinpassport = $_POST['passport'][$cnt];
						$nationality = $_POST['nationality'][$cnt];

						$insertSQL = $db->prepare("INSERT INTO tbl_contractordirectors (contrid, fullname, pinpassport, nationality, created_by, date_created) VALUES (:contrid, :fullname, :pinpassport, :nationality, :createdby, :datecreated)");
						$insertSQL->execute(array(':contrid' => $last_id, ':fullname' => $fullname, ':pinpassport' => $pinpassport, ':nationality' => $nationality, ':createdby' => $username, ':datecreated' => $current_date));
					}
				}

				if ($Result1) {
					$username = $_POST['username'];
					// $last_id = $db->lastInsertId();
					$current_date = date("Y-m-d");
					$rd2 = mt_rand(1000, 9999) . "_File";
					$count = count($_POST["attachmentpurpose"]);
					for ($cnt = 0; $cnt < $count; $cnt++) {
						$purpose = $_POST["attachmentpurpose"][$cnt];
						if (!empty($_FILES['attachment']['name'][$cnt])) {
							$filename = basename($_FILES['attachment']['name'][$cnt]);
							$ext = substr($filename, strrpos($filename, '.') + 1);
							if (($ext != "exe") && ($_FILES["attachment"]["type"][$cnt] != "application/x-msdownload")) {
								$newname = "uploads/contractordirectors/" . $last_id . "_" . $rd2 . "_" . $filename;
								if (!file_exists($newname)) {
									if (move_uploaded_file($_FILES['attachment']['tmp_name'][$cnt], $newname)) {
										$qry2 = $newname;
										$purpose = $_POST['attachmentpurpose'][$cnt];
										$insertSQL = $db->prepare("INSERT INTO tbl_contractordocuments (contrid, floc, file_format, attachment_purpose, created_by, date_created) VALUES (:contrid, :floc, :fileformat, :purpose, :createdby, :datecreated)");
										$insertSQL->execute(array(':contrid' => $last_id, ':floc' => $qry2, ':fileformat' => $ext, ':purpose' => $purpose, ':createdby' => $username, ':datecreated' => $current_date));
									}
								}
							}
						}
					}
				}

				$msg = 'Contractor Successfully Added';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						'icon':'success',
					showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'add-contractor.php';
					}, 2000);
				</script>";
			}
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
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
								<?= csrf_token_html(); ?>
								<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Core Details</legend>
									<div class="col-md-12">
										<label class="control-label">Contractor Name*:</label>
										<div class="form-line">
											<input type="text" name="contractorname" id="contractorname" value="<?php echo ($edit_form) ? $row_rsContrEdit['contractor_name'] : ""; ?>" placeholder="Please enter contractor name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Business Type *:</label>
										<div class="form-line">
											<?php
											if (isset($_GET["contrid"])) {
											?>
												<select name="biztype" id="biztype" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="1">..Select Business Type..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsBiztype['id'] ?>" <?php echo (!(strcmp($row_rsBiztype['type'], $row_rsBztype['type']))) ? "selected=\"selected\"" : ""; ?>>
															<?php echo $row_rsBiztype['type']; ?>
														</option>
													<?php
													} while ($row_rsBiztype = $query_rsBiztype->fetch());
													?>
												</select>
											<?php
											} elseif (!isset($_GET["contrid"])) {
											?>
												<select name="biztype" id="biztype" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="1">..Select Business Type..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsBiztype['id'] ?>"><?php echo $row_rsBiztype['type']; ?></option>
													<?php
													} while ($row_rsBiztype = $query_rsBiztype->fetch());
													?>
												</select>
											<?php
											}
											?>
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Business Registration Number *:</label>
										<div class="form-line">
											<input name="bizregno" type="text" value="<?php echo ($edit_form) ? $row_rsContrEdit['busregno'] : ""; ?>" placeholder="Enter business registration no." class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Pin Number *:</label>
										<div class="form-line">
											<input type="text" name="pinnumber" id="pinnumber" value="<?php echo ($edit_form) ? $row_rsContrEdit['pinno'] : ""; ?>" placeholder="Enter Pin Number" class=" form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Date Registered *:</label>
										<span id="gt" style="display:none; color:#fff; background-color:#F44336; padding:5px"> Code Exists </span>
										<div class="form-line">
											<input type="date" name="dateregistered" id="dateregistered" class="IP_calendar form-control" title="d/m/Y" value="<?php echo $row_rsContrEdit['dateregistered']; ?>" style="border:#CCC thin solid; border-radius: 5px" required="required">
											<span id="projcodemsg" style="color:red"> </span>
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Pin Status *:</label>
										<div class="form-line">
											<?php
											if (isset($_GET["contrid"])) {
											?>
												<select name="pinstatus" id="pinstatus" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="1">..Select..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsPinStatus['id'] ?>" <?php echo (!(strcmp($row_rsPinStatus['pin_status'], $row_rsPnstatus['pin_status']))) ?  "selected=\"selected\"" : ""; ?>><?php echo $row_rsPinStatus['pin_status']; ?></option>
													<?php
													} while ($row_rsPinStatus = $query_rsPinStatus->fetch());
													?>
												</select>
											<?php
											} elseif (!isset($_GET["contrid"])) {
											?>
												<select name="pinstatus" id="pinstatus" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="1">..Select Pin Status..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsPinStatus['id'] ?>"><?php echo $row_rsPinStatus['pin_status']; ?></option>
													<?php
													} while ($row_rsPinStatus = $query_rsPinStatus->fetch());
													?>
												</select>
											<?php
											}
											?>
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Is VAT Registered? *:</label>
										<div class="form-line">
											<?php
											if (isset($_GET["contrid"])) {
											?>
												<select name="vat" id="vat" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="">..Select..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsContrVat['id'] ?>" <?php echo (!(strcmp($row_rsContrVat['vat'], $row_rsVat['vat']))) ? "selected=\"selected\"" : ""; ?>><?php echo $row_rsContrVat['vat']; ?></option>
													<?php
													} while ($row_rsContrVat = $query_rsContrVat->fetch());
													?>
												</select>
											<?php
											} elseif (!isset($_GET["contrid"])) {
											?>
												<select name="vat" id="vat" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
													<option value="">..Select..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsContrVat['id'] ?>"><?php echo $row_rsContrVat['vat']; ?></option>
													<?php
													} while ($row_rsContrVat = $query_rsContrVat->fetch());
													?>
												</select>
											<?php
											}
											?>
										</div>
									</div>
									<div class="col-md-4">
										<label for="projduration">Postal Address *:</label><span id="projdurationmsg" style="color:darkgoldenrod"></span>
										<div class="form-input">
											<input type="text" name="postaladdress" class="form-control" value="<?php echo ($edit_form) ? $row_rsContrEdit['contact'] : ""; ?>" placeholder="Enter contractor postal address" required>
										</div>
									</div>
									<div class="col-md-4">
										<label for="projendyear">City *:</label>
										<div class="form-input">
											<input type="text" name="city" class="form-control" value="<?php echo ($edit_form) ? $row_rsContrEdit['city'] : ""; ?>" placeholder="Enter contractor city/town" required="required">
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">County *:</label>
										<div class="form-line">
											<?php
											if (isset($_GET["contrid"])) {
											?>
												<select name="county" id="county" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="false" required>
													<option value="1">..Select County..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsCounty['id'] ?>" <?php echo (!(strcmp($row_rsCounty['name'], $row_rsCnty['name']))) ? "selected=\"selected\"" : ""; ?>><?php echo $row_rsCounty['name']; ?></option>
													<?php
													} while ($row_rsCounty = $query_rsCounty->fetch());
													?>
												</select>
											<?php
											} elseif (!isset($_GET["contrid"])) {
											?>
												<select name="county" id="county" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="false" required>
													<option value="1">..Select County..</option>
													<?php
													do {
													?>
														<option value="<?php echo $row_rsCounty['id'] ?>"><?php echo $row_rsCounty['name']; ?></option>
													<?php
													} while ($row_rsCounty = $query_rsCounty->fetch());
													?>
												</select>
											<?php
											}
											?>
										</div>
									</div>
									<div class="col-md-4">
										<label for="" class="control-label">Phone Number *:</label>
										<div class="form-line">
											<input name="phone" id="phone" class="form-control" value="<?php echo ($edit_form) ? $row_rsContrEdit['phone'] : ""; ?>" placeholder="Enter contractor phone number" required="required" />
										</div>
									</div>
									<div class="col-md-4">
										<label for="" class="control-label">Email Address *:</label>
										<div class="form-line">
											<input name="email" id="email" class="form-control" value="<?php echo ($edit_form) ? $row_rsContrEdit['email'] : ""; ?>" placeholder="Enter contractor email address" required="required" />
										</div>
									</div>
									<div class="col-md-12" id="valueget">
										<label class="control-label">Physical Address *:</label>
										<div class="form-line">
											<input type="text" name="physicaladdress" id="physicaladdress" class="form-control" value="<?php echo ($edit_form) ? $row_rsContrEdit['address'] : ""; ?>" placeholder="Enter contractor physical address" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
										</div>
									</div>
								</fieldset>
								<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Directors' Details</legend>
									<div class="container-fluid" style="margin-top:10px">
										<div class="row-fluid">
											<div class="table-repsonsive">
												<span id="error"></span>
												<table class="table table-bordered" id="item_table">
													<tr>
														<th style="width:40%">Director's Full Name</th>
														<th style="width:29%">Director's ID/Passport Number</th>
														<th style="width:29%">Director's Nationality</th>
														<th style="width:2%"><button type="button" name="add" title="Add another director" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
													</tr>
													<?php
													if ($edit_form) {
														$query_rsContractor = $db->prepare("SELECT * FROM tbl_contractordirectors where contrid='$contrid'");
														$query_rsContractor->execute();
														$row_rsContractor = $query_rsContractor->fetch();
														$totalRows_rsContractor = $query_rsContractor->rowCount();

														if ($totalRows_rsContractor > 0) {
															$p = 0;
															do {
																$p++;
													?>
																<tr>
																	<td><input type="text" name="fullname[]" value="<?= $row_rsContractor['fullname'] ?>" placeholder="Director's Full Name" class="form-control" required="required" /></td>
																	<td><input type="number" name="passport[]" value="<?= $row_rsContractor['pinpassport'] ?>" placeholder="Director's ID/Passport" class="form-control item_quantity" required="required" /></td>
																	<td>
																		<select name="nationality[]" id="nationality" class="form-control item_unit" required="required">
																			<option value="">... Select Nationality ...</option>
																			<option value="1" <?php echo $row_rsContractor['nationality'] == "1" ? "selected" : ""; ?>>Visitor</option>
																			<option value="2" <?php echo $row_rsContractor['nationality'] == "2" ? "selected" : ""; ?>>Citizen</option>
																			<option value="3" <?php echo $row_rsContractor['nationality'] == "3" ? "selected" : ""; ?>>Local Company</option>
																			<option value="4" <?php echo $row_rsContractor['nationality'] == "4" ? "selected" : ""; ?>>Foreign Company</option>
																		</select>
																	</td>
																	<td>
																		<?php
																		if ($p != 1) {
																		?>
																			<button type="button" name="remove" class="btn btn-danger btn-sm remove">
																				<span class="glyphicon glyphicon-minus"></span>
																			</button>
																		<?php
																		}
																		?>
																	</td>
																</tr>
															<?php
															} while ($row_rsContractor = $query_rsContractor->fetch());
														} else {
															?>
															<tr>
																<td><input type="text" name="fullname[]" placeholder="Director's Full Name" class="form-control" required="required" /></td>
																<td><input type="number" name="passport[]" placeholder="Director's ID/Passport" class="form-control item_quantity" required="required" /></td>
																<td>
																	<select name="nationality[]" id="nationality" class="form-control item_unit" required="required">
																		<option value="">... Select Nationality ...</option>
																		<option value="1">Visitor</option>
																		<option value="2">Citizen</option>
																		<option value="3">Local Company</option>
																		<option value="4">Foreign Company</option>
																	</select>
																</td>
																<td></td>
															</tr>
														<?php
														}
													} else {
														?>
														<tr>
															<td><input type="text" name="fullname[]" placeholder="Director's Full Name" class="form-control" required="required" /></td>
															<td><input type="number" name="passport[]" placeholder="Director's ID/Passport" class="form-control item_quantity" required="required" /></td>
															<td>
																<select name="nationality[]" id="nationality" class="form-control item_unit" required="required">
																	<option value="">... Select Nationality ...</option>
																	<option value="1">Visitor</option>
																	<option value="2">Citizen</option>
																	<option value="3">Local Company</option>
																	<option value="4">Foreign Company</option>
																</select>
															</td>
															<td></td>
														</tr>
													<?php
													}
													?>
												</table>
											</div>
										</div>
									</div>
									<script>
										$(document).ready(function() {

											$(document).on('click', '.add', function() {
												var html = '';
												html += '<tr>';
												html += '<td><input type="text" name="fullname[]" placeholder="Director\'s Full Name" class="form-control item_quantity" required="required" /></td>';
												html += '<td><input type="number" name="passport[]" placeholder="Director\'s ID/Passport" class="form-control item_quantity" required="required" /></td>';
												html += '<td><select name="nationality[]" id="nationality" class="form-control item_unit" required="required"><option value="">... Select Nationality ...</option><option value="1">Visitor</option><option value="2">Citizen</option><option value="3">Local Company</option><option value="4">Foreign Company</option></select></td>';
												html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
												$('#item_table').append(html);
											});

											$(document).on('click', '.remove', function() {
												$(this).closest('tr').remove();
											});

										});
									</script>
								</fieldset>
								<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Contractor Documents</legend>
									<div class="container-fluid">
										<div class="row-fluid">
											<div class="span12 table-repsonsive" style="float:right">
												<span id="error"></span>
												<table class="table table-bordered" id="funding_table">
													<tr>
														<th style="width:40%">Attachments</th>
														<th style="width:58%">Attachment Purpose</th>
														<th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
													</tr>
													<?php
													if ($edit_form) {
														$query_rsContractorDocuments = $db->prepare("SELECT * FROM tbl_contractordocuments where contrid='$contrid'");
														$query_rsContractorDocuments->execute();
														$row_rsContractorDocuments = $query_rsContractorDocuments->fetch();
														$totalRows_rsContractorDocuments = $query_rsContractorDocuments->rowCount();
														if ($totalRows_rsContractorDocuments > 0) {
															$d = 0;
															do {
																$d++;
													?>
																<tr>
																	<td>
																		<span><strong>Document:</strong><a style="color: #000;" href="<?= $row_rsContractorDocuments['floc'] ?>"><?= $row_rsContractorDocuments['floc'] ?></a></span>
																		<input type="file" name="attachment[]" multiple id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																	</td>
																	<td>
																		<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" value="<?= $row_rsContractorDocuments['attachment_purpose'] ?>" placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																	</td>
																	<td>
																		<?php
																		if ($d != 1) {
																		?>
																			<button type="button" class="btn btn-danger btn-sm" onclick='delete_row("row<?= $d ?>")'><span class="glyphicon glyphicon-minus"></span></button>
																		<?php
																		}
																		?>
																	</td>
																</tr>
															<?php

															} while ($row_rsContractorDocuments = $query_rsContractorDocuments->fetch());
														} else {
															?>
															<tr>
																<td>
																	<input type="file" name="attachment[]" multiple id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
																</td>
																<td>
																	<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																</td>
																<td></td>
															</tr>
														<?php
														}
													} else {
														?>
														<tr>
															<td>
																<input type="file" name="attachment[]" multiple id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
															</td>
															<td>
																<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
															</td>
															<td></td>
														</tr>
													<?php
													}
													?>
												</table>
											</div>

											<script type="text/javascript">
												function add_row() {
													$rowno = $("#funding_table tr").length;
													$rowno = $rowno + 1;
													$("#funding_table tr:last").after('<tr id="row' + $rowno + '"><td><input type="file" name="attachment[]" multiple id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" /></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
													// <input type='text' name='funding[]' placeholder='Enter Name'></td><td><input type='button' value='DELETE' onclick=delete_row('row"+$rowno+"')></td></tr>");
												}

												function delete_row(rowno) {
													$('#' + rowno).remove();
												}
											</script>
										</div>
									</div>
								</fieldset>
								<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
									<div class="col-md-12">
										<label class="control-label">Comments :</label>
										<div class="form-line">
											<textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?php echo ($edit_form) ? $row_rsContrEdit['comments'] : ""; ?></textarea>
											<script>
												CKEDITOR.replace('comments', {
													on: {
														instanceReady: function(ev) {
															// Output paragraphs as <p>Text</p>.
															this.dataProcessor.writer.setRules('p', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('ol', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('ul', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('li', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
														}
													}
												});
											</script>
										</div>
									</div>
									<div class="row clearfix " id="">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<input name="submit" type="submit" class="btn btn-success" value="Save &amp; Submit" />
											<input type="button" VALUE="Cancel" class="btn btn-warning" onclick="location.href='view-contractors.php';" id="btnback">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<input type="hidden" name="MM_insert" value="addcontractorfrm" />
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

require('includes/footer.php');
?>