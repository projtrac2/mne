<?php
try {
	require('includes/head.php');
	$pageTitle = $planlabelplural;

	if ($permission) {
		if (isset($_GET['plan'])) {
			$stplan = $_GET['plan'];
		}

		if ((isset($_GET["action"])) && $_GET["action"] == "edit") {
			if (isset($_GET['tmgy'])) {
				$hash = $_GET['tmgy'];
				$decode_tmid = base64_decode($hash);
				$tmid_array = explode("encodetmid9876", $decode_tmid);
				$tmid = $tmid_array[1];
			}
			$result = "";

			$query_terminologies =  $db->prepare("SELECT * FROM tbl_terminologies WHERE id='$tmid'");
			$query_terminologies->execute();
			$row_terminology = $query_terminologies->fetch();
			$cat = $row_terminology['category'];
			$name = $row_terminology['name'];
			$label = $row_terminology['label'];
			$labelplural = $row_terminology['label_plural'];

			$action = "Edit";
			$submitAction = "MM_update";
			$formName = "editterminology";
			$submitValue = "Update";
			if (isset($_POST["MM_update"]) && $_POST["MM_update"] == "editterminology") {
				$category = $_POST['category'];
				$tmgyname = $_POST['name'];
				$tmgylabel = $_POST['label'];
				$tmgylabelplural = $_POST['label-plural'];

				//if($milestonestatus == "Pending" || $milestonestatus == "Approved") {
				if (!empty($category) && !empty($tmgyname) && !empty($tmgylabel) && !empty($tmgylabelplural)) {

					$updateQuery = $db->prepare("Update tbl_terminologies SET category=:category, name=:name, label=:label, label_plural=:labelp WHERE id=:tmgyid");
					//add the data into the database
					$updresult = $updateQuery->execute(array(':category' => $category, ':name' => $tmgyname, ':label' => $tmgylabel, ':labelp' => $tmgylabelplural, ':tmgyid' => $tmid));

					if (!$updresult) {
						$msg = 'Terminology was not updated.';
						$result = "<script type=\"text/javascript\">
									swal({
									title: \"Error!\",
									text: \" $msg \",
									icon: 'warning',
									dangerMode: true,
									timer: 6000,
									showConfirmButton: false });
									setTimeout(function(){
											window.location.href = 'system-terminologies?action=edit&tmgy=$tmgyid';
										}, 6000);
								</script>";
					} else {
						$msg = 'Terminology successfully updated.';
						$result = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 3000,
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'system-terminologies';
							}, 3000);
					 </script>";
					}
				} else {
					$msg = "Please fill all required fields";
					$result = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						dangerMode: true,
						timer: 3000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'system-terminologies?action=edit&tmgy=$tmgyid';
						}, 3000);
				</script>";
				}
			}
		} elseif ((isset($_GET["action"])) && $_GET["action"] == "del") {
			$tmid = $_GET['tmgy'];
			$result = "<script type=\"text/javascript\">
			swal({
			  title: \"Are you sure??\",
			  text: \"Once deleted, you will not be able to recover this record!\",
			  icon: 'warning',
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  if (willDelete) {
				swal({
				 url: window.location.href = 'delterminology?id=$tmid',
				});
			  } else {
				swal(\"Your terminology is safe!\");
			  }
			});
		</script>";
		} else {
			$action = "Add";
			$submitAction = "MM_insert";
			$formName = "addterminology";
			$submitValue = "Submit";
			$label = $labelplural = $name = $result = "";

			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addterminology")) {
				//if ($projstatus == "Pending" || $projstatus == "Unapproved") {
				if (!empty($_POST['category']) && !empty($_POST['name']) && !empty($_POST['label']) && !empty($_POST['label-plural'])) {

					$cat = $_POST['category'];
					$name = $_POST['name'];
					$label = $_POST['label'];
					$labelplural = $_POST['label-plural'];

					$insertSQL = $db->prepare("INSERT INTO tbl_terminologies (category, name, label, label_plural) VALUES (:cat, :name, :label, :labelp)");

					$Result1 = $insertSQL->execute(array(':cat' => $cat, ':name' => $name, ':label' => $label, ':labelp' => $labelplural));
					if ($Result1) {
						$msg = 'Terminology successfully added.';
						$result = "<script type=\"text/javascript\">
									swal({
									title: \"Success!\",
									text: \" $msg\",
									type: 'Success',
									timer: 3000,
									showConfirmButton: false });
									setTimeout(function(){
											window.location.href = 'system-terminologies';
										}, 3000);
								  </script>";
					} else {
						$msg = "Sorry the terminology  was not added!!";
						$result = "<script type=\"text/javascript\">
							swal({
							title: \"Failed!!!\",
							text: \" $msg\",
							icon: 'warning',
							dangerMode: true,
							timer: 3000,
							showConfirmButton: false });
						</script>";
					}
				} else {
					$msg = 'Please fill all required fields!!';
					$result = "<script type=\"text/javascript\">
						swal({
						title: \"Error!!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 6000,
						showConfirmButton: false });
					</script>";
				}
			}
		}

		$query_terminologies =  $db->prepare("SELECT * FROM tbl_terminologies order by category asc");
		$query_terminologies->execute();
		$rows_terminologies = $query_terminologies->rowCount();
?>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Taxonomy</h4>
				</div>
				<div class="row clearfix" style="margin-top:10px">
					<div class="col-md-12" style="margin-top:10px">
						<div class="card">
							<div class="button-demo" style="margin-top:-15px; margin-left:0px; margin-right:-2px">
								<?php include_once("settings-menu.php"); ?>
							</div>
						</div>
					</div>
					<div class="block-header">
						<?php
						echo $result;
						?>
					</div>
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="header">
										<div style="color:#333; background-color:#EEE; width:100%; height:30px">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
												<tr>
													<td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
														<div align="left"><img src="images/projbrief.png" alt="img" /> <strong>System Terminologies</strong></div>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="body">
										<form id="addopfrm" method="POST" name="addopfrm" action="" autocomplete="off">

											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add System Global Terminologies</legend>
												<div class="col-md-2">
													<label>Category*:</label>
													<div class="form-line">
														<select name="category" id="category" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
															<option value="">....Select....</option>
															<?php
															if ($cat == 1) {
																echo '<option value="1" selected="selected">Sector</option>
															<option value="2">Administrative</option>
															<option value="3">Plan</option>';
															} elseif ($cat == 2) {
																echo '<option value="1">Sector</option>
															<option value="2" selected="selected">Administrative</option>
															<option value="3">Plan</option>';
															} elseif ($cat == 3) {
																echo '<option value="1">Sector</option>
															<option value="2">Administrative</option>
															<option value="3" selected="selected">Plan</option>';
															} else {
																echo '<option value="1">Sector</option>
															<option value="2">Administrative</option>
															<option value="3">Plan</option>';
															}
															?>
														</select>
													</div>
												</div>
												<div class="col-md-3">
													<label>Terminology Name *:</label>
													<div class="form-line">
														<input type="text" class="form-control" name="name" value="<?= $name ?>" placeholder="Enter terminology name/tag" style="border:#CCC thin solid; border-radius: 5px" required>
													</div>
												</div>
												<div class="col-md-3">
													<label>Terminology Label *:</label>
													<div class="form-line">
														<input type="text" class="form-control" name="label" value="<?= $label ?>" placeholder="Enter terminology label" style="border:#CCC thin solid; border-radius: 5px" required>
													</div>
												</div>
												<div class="col-md-4">
													<label>Terminology Label Plural *:</label>
													<div class="form-line">
														<input type="text" class="form-control" name="label-plural" value="<?= $labelplural ?>" placeholder="Enter terminology label in rural" style="border:#CCC thin solid; border-radius: 5px" required>
													</div>
												</div>
												<div class="row clearfix">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
														<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
														<input name="deptid" type="hidden" id="deptid" value="<?php echo $opid; ?>" />
														<div class="btn-group">
															<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?php echo $submitValue ?>" />
														</div>
														<input type="hidden" name="<?php echo $submitAction; ?>" value="<?php echo $formName; ?>" />
														<?php if (isset($_GET["tmgy"])) { ?>
															<input type="hidden" name="tmgy" value="<?php echo $_GET["tmgy"]; ?>" />
															<a type="button" class="btn btn-warning" href="system-terminologies">Cancel</a>
														<?php } ?>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
													</div>
												</div>
											</fieldset>
										</form>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><img src="images/indicator.png" alt="task" /> All Terminologies</legend>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr id="colrow">
															<td width="3%"><strong id="colhead">SN</strong></td>
															<td width="10%"><strong id="colhead">Category</strong></td>
															<td width="25%"><strong id="colhead">Name</strong></td>
															<td width="25%"><strong id="colhead">Label</strong></td>
															<td width="30%"><strong id="colhead">Label Plural</strong></td>
															<td width="7%"><strong id="colhead">Action</strong></td>
														</tr>
													</thead>
													<tbody><?php
															if ($rows_terminologies == 0) {
															?>
															<tr>
																<td colspan="9">
																	<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
																</td>
															</tr>
															<?php } else {
																$sn = 0;
																while ($rows = $query_terminologies->fetch()) {
																	$sn = $sn + 1;
																	$tmgyid = base64_encode("encodetmid9876{$rows['id']}");
																	$cat = $rows['category'];
																	if ($cat == 1) {
																		$category = "Sector";
																	} elseif ($cat == 2) {
																		$category = "Administrative";
																	} elseif ($cat == 3) {
																		$category = "Plan";
																	}

																	$name = $rows['name'];
																	$label = $rows['label'];
																	$labelplural = $rows['label_plural'];
															?>
																<tr id="rowlines">
																	<td><?php echo $sn; ?></td>
																	<td><?php echo $category; ?></td>
																	<td><?php echo $name; ?></td>
																	<td><?php echo $label; ?></td>
																	<td><?php echo $labelplural; ?></td>
																	<td><a href="system-terminologies?action=edit&tmgy=<?php echo $tmgyid; ?>"><img src="images/edit.png" width="16" height="16" alt="Edit" title="Edit Output" /></a>&nbsp;&nbsp;<a href="system-terminologies?action=del&tmgy=<?php echo $tmgyid; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" width="16" height="16" alt="Delete" title="Delete Output" /></a></td>
																</tr>
														<?php }
															}
														?>
													</tbody>
												</table>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- #END# Advanced Form Example With Validation -->
			</div>
		</section>

<?php
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?> 