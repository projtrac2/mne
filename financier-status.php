<?php
include 'includes/head.php';
if ($permission) {
	try {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "financierstatusfrm")) {

			$fnid = $_POST['fnid'];
			$statusdate = date("Y-m-d", strtotime($_POST['statusdate']));
			$comments = $_POST['comments'];
			$current_date = date("Y-m-d");
			$status = $_POST['status'];

			if (!empty($fnid) && !empty($statusdate) && !empty($_POST['user_name'])) {
				$insertSQL = $db->prepare("Update tbl_financiers SET active=:status, statusdate=:statusdate, updated_by=:user, date_updated=:recorddate WHERE id=:fnid");
				//add the data into the database
				$update = $insertSQL->execute(array(':status' => $status, ':statusdate' => $statusdate, ':user' => $_POST['user_name'], ':recorddate' => $current_date, ':fnid' => $fnid));

				if ($update) {
					$filecategory = "Financiers";
					$reason = "Donor Status Change (" . $status . ")";
					$myUser = $_POST['user_name'];

					$count = count($_POST["attachmentpurpose"]);

					for ($cnt = 0; $cnt < $count; $cnt++) {
						//Check that we have a file
						if (!empty($_FILES['financierstatusattachment']['name'][$cnt])) {
							$purpose = $_POST["attachmentpurpose"][$cnt];
							//Check if the file is JPEG image and it's size is less than 350Kb
							$filename = basename($_FILES['financierstatusattachment']['name'][$cnt]);
							$ext = substr($filename, strrpos($filename, '.') + 1);

							if (($ext != "exe") && ($_FILES["financierstatusattachment"]["type"][$cnt] != "application/x-msdownload")) {
								$newname = $fnid . "-" . $filename;
								$filepath = "uploads/financiers/" . $newname;
								//Check if the file with the same name already exists in the server
								if (!file_exists($filepath)) {
									//Attempt to move the uploaded file to it's new place
									if (move_uploaded_file($_FILES['financierstatusattachment']['tmp_name'][$cnt], $filepath)) {
										//successful upload
										$qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projstage, :fname, :ext, :floc, :fcat, :purpose, :user, :date)");
										$qry2->execute(array(':projstage' => $fnid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':purpose' => $purpose, ':user' => $myUser, ':date' => $current_date));
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

					$fnstatus = ($status == 1) ? "Activated" : "Deactivated";

					$msg = 'Financier ' . $fnstatus . ' Successfully.';
					$results = "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 5000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'view-financiers';
							}, 5000);
						</script>";
				}

				$insertdonorcomments = $db->prepare(" INSERT INTO tbl_financier_status_comments(fnid, comments, statusdate, created_by, date_created)
					VALUES(:fnid, :comments, :statusdate, :user, :date)");
				$insertdonorcomments->execute(array(":fnid" => $fnid, ":comments" => $comments, ":statusdate" => $statusdate, ":user" => $myUser, ":date" => $current_date));
			}
		}

		if (isset($_GET['fn'])) {
			$hash = $_GET['fn'];
			$decode_fndid = base64_decode($hash);
			$fndid_array = explode("fn918273AxZID", $decode_fndid);
			$fnid = $fndid_array[1];
		}

		$query_financier =  $db->prepare("SELECT * FROM tbl_financiers WHERE id = :fnid");
		$query_financier->execute(array(":fnid" => $fnid));
		$row_financier = $query_financier->fetch();
		$statusid = $row_financier["active"];

		if ($statusid == 1) {
			$status = "Active";
		} else {
			$status =  "Inactive";
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<?= $icon ?>
							<?= $pageTitle ?>
							<div class="btn-group" style="float:right">
								<div class="btn-group" style="float:right">
									<?php
									if ($add_financier) {
									?>
										<a href="add-financier.php" class="btn btn-primary pull-right">Add Financier</a>
									<?php
									}
									?>
								</div>
							</div>
						</h4>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<div class="col-md-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px">
									<img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
								</span>
								<a href="view-financier-info.php?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Financier Details</a>
								<a href="view-financier-funds.php?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-5px">Funds Contributed</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-5px">Financier Status</a>
							</div>
						</div>
					</div>
					<?= $results; ?>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<!-- start body -->
							<div style="margin-top:5px">
								<form id="add_donation" method="POST" name="financierstatusfrm" action="" enctype="multipart/form-data" autocomplete="off">
									<?= csrf_token_html(); ?>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
										<div class="col-md-12">
											<label>Financier :</label>
											<div>
												<input type="text" class="form-control" value="<?php echo $row_financier['financier']; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
											</div>
										</div>
										<div class="col-md-4">
											<label>Financier Status *:</label>
											<div class="form-line">
												<select name="status" id="status" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
													<option value="" class="selection">...Select...</option>
													<?php
													if ($statusid == 1) {
														echo '
															<option value="1" selected="selected" class="selection">Activate</option>
															<option value="0" class="selection">Deactivate</option>';
													} else {
														echo '
															<option value="0" selected="selected" class="selection">Inactive</option>
															<option value="1" class="selection">Activate</option>';
													}
													?>
												</select>
											</div>
										</div>
										<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
										<script type="text/javascript">
											webshims.setOptions('forms-ext', {
												replaceUI: 'auto',
												types: 'number'
											});
											webshims.polyfill('forms forms-ext');
										</script>
										<div class="col-md-6">
											<label>Action Date (Activated/Deactived) *:</label>
											<div class="form-line">
												<input name="statusdate" type="date" class="form-control" placeholder="Please choose a date..." style="border:#CCC thin solid; border-radius: 5px; padding-left:10px" value="<?php echo $row_financier['statusdate']; ?>" required />
											</div>
										</div>
										<div class="col-md-12">
											<label>Comments *:</label>
											<div class="form-line">
												<textarea name="comments" cols="45" rows="7" class="form-control" id="comments" style="border:#CCC thin solid; border-radius: 5px" required><?php echo Strip_tags($row_financier['comments']); ?></textarea>
											</div>
										</div>
									</fieldset>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Status Attachments</legend>
										<!-- File Upload | Drag & Drop OR With Click & Choose -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="header">
														<i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
													</div>
													<div class="body">
														<table class="table table-bordered" id="donor_status">
															<tr>
																<th style="width:40%">Attachments</th>
																<th style="width:58%">Attachment Purpose</th>
																<th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
															<tr>
																<td>
																	<input type="file" name="financierstatusattachment[]" multiple id="financierstatusattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																</td>
																<td>
																	<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																</td>
																<td></td>
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
											<input name="fnid" type="hidden" id="fnid" value="<?php echo $fnid; ?>" />
											<input name="user_name" type="hidden" id="user_name" value="<?php echo 1; ?>" />
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
											</div>
											<input type="hidden" name="MM_insert" value="financierstatusfrm" />
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
									</div>
								</form>
							</div>
							<!-- end body -->
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- Bootstrap Datepicker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">
		function add_row() {
			$rowno = $("#donor_status tr").length;
			$rowno = $rowno + 1;
			$("#donor_status tr:last").after('<tr id="row' + $rowno + '"><td><input type="file" name="donorstatusattachment[]" multiple id="donorstatusattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
		}

		function delete_row(rowno) {
			$('#' + rowno).remove();
		}
	</script>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>