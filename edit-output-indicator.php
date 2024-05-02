<?php
try {
	require('includes/head.php');
	if ($permission && (isset($_GET['ind']) && !empty($_GET["ind"]))) {
		$decode_indid =   base64_decode($_GET['ind']);
		$indid_array = explode("opid", $decode_indid);
		$ind = $indid_array[1];

		require('functions/indicator.php');
		require('functions/measurement-unit.php');

		$indicator = get_indicator_by_indid($ind);
		$measurement_units = get_measurement_units();
		if ($indicator) {
			$indcode = $indicator['indicator_code'];
			$indname = $indicator['indicator_name'];
			$inddesc = $indicator['indicator_description'];
			$calculationmethod = $indicator['indicator_calculation_method'];
			$indunit = $indicator['indicator_unit'];
			$indicator_category = $indicator['indicator_category'];
			$indicator_type = $indicator['indicator_type'];
			$indicator_sector = $indicator['indicator_sector'];
			$indicator_dept = $indicator['indicator_dept'];
			$indicator_baseline_level = $indicator['indicator_baseline_level'];
			$mapping_type = $indicator['indicator_mapping_type'];
			// update data
			if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editindfrm")) {
				$indcd = $_POST['indcode'];
				$indname = $_POST['indname'];
				$desc = $_POST['inddesc'];
				$unit = $_POST['indunit'];
				$user = $_POST['user_name'];
				$indid = $_POST['indid'];
				$baselinelevel = $_POST['baselinelevel'];
				$mapping_type = $_POST['mapping_type'];
				$indcat = "Output";
				$current_date = date("Y-m-d");
				$url = 'view-indicators.php';
				$results = "";
				$query_rsIndicator = $db->prepare("SELECT indid, indicator_code FROM tbl_indicator WHERE indicator_code = '$indcd'");
				$query_rsIndicator->execute();
				$row_rsIndicator = $query_rsIndicator->fetch();
				$indcount = $query_rsIndicator->rowCount();
				$ind = $indcount > 0 ? $row_rsIndicator["indid"] : '';

				if ($ind == $indid || $indcount == 0) {
					$updateSQL = $db->prepare("UPDATE tbl_indicator SET indicator_code=:indcode, indicator_name=:indname, indicator_description=:inddesc, indicator_category=:indcat, indicator_unit=:indunit, indicator_baseline_level=:baselinelevel, indicator_mapping_type=:mapping_type, updated_by=:user, date_updated=:date WHERE indid=:indid");
					$result = $updateSQL->execute(array(':indcode' => $indcd, ':indname' => $indname, ':inddesc' => $desc, ':indcat' => $indcat, ':indunit' => $unit, ":baselinelevel" => $baselinelevel, ":mapping_type" => $mapping_type, ':user' => $user_name, ':date' => $current_date, ':indid' => $indid));

					if ($result) {
						$msg = 'Indicator successfully updated.';
						$results =
							"<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 3000,
							icon:'success',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = \" $url\";
						}, 3000);
					</script>";
					} else {
						$msg = 'Failed!! This Indicator was not updated!!';
						$results =
							"<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'Danger',
							timer: 5000,
							icon:'error',
							showConfirmButton: false
						});
						setTimeout(function(){
							window.location.href = '$url';
						}, 5000);
					</script>";
					}

					echo $results;
				} else {
					$msg = 'Indicator successfully updated.';
					$results = "<script type=\"text/javascript\">
					swal({
						title: \"Error!\",
						text: \" $msg\",
						type: 'Error',
						timer: 3000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = \" $url\";
					}, 3000);
				</script>";
					echo $results;
				}
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
								<button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
									Go Back
								</button>
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
									<form id="addindfrm" method="POST" name="addindfrm" action="" onsubmit="" enctype="multipart/form-data" autocomplete="off">
										<?= csrf_token_html(); ?>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Indicator</legend>
											<div class="col-md-3">
												<label>Indicator Code *:</label>
												<div class="form-line">
													<input type="text" class="form-control" name="indcode" placeholder="Enter indicator code" value="<?= $indcode ?>" id="indcode" style="border:#CCC thin solid; border-radius: 5px" required onBlur="checkAvailability()">
												</div>
											</div>
											<div class="col-md-7" id="code-availability-status">
												<p><img src="assets/images/LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>
											</div>
											<div class="col-md-12 row">
												<div class="col-md-4">
													<label>Unit of Measure
														<a type="button" data-toggle="modal" data-target="#adddetailsModal" onclick='adddetails("unit")' id="outputItemModalBtnrow"> (Add new)</a>
														*:
													</label>
													<div class="form-line">
														<select name="indunit" id="indunit" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
															<option value="" selected="selected" class="selection">....Select Unit....</option>
															<?php
															foreach ($measurement_units as $measurement_unit) {
																$selected = ($indunit == $measurement_unit['id']) ? "selected" : "";
															?>
																<option value="<?php echo $measurement_unit['id'] ?>" <?= $selected ?>><?php echo $measurement_unit['unit'] ?></option>
															<?php
															}
															?>
														</select>
													</div>
												</div>
												<div class="col-md-8">
													<label>Result to be measured *:</label>
													<div>
														<input name="indname" type="text" value="<?= $indname ?>" class="form-control" placeholder="Enter result to be measured" id="indname" style="border:#CCC thin solid; border-radius: 5px" required />
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<label>Indicator Data Level *:</label>
												<div class="form-line">
													<select name="baselinelevel" id="baselinelevel" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px" required>
														<option value="" selected="selected" class="selection">....Select Values Level....</option>
														<option value="1" <?= ($indicator_baseline_level == 1) ? "selected" : ""; ?>>Top Level</option>
														<option value="2" <?= ($indicator_baseline_level == 2) ? "selected" : ""; ?>>Lowest Level</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<label>Mapping Type *:</label>
												<div class="form-line">
													<select name="mapping_type" id="mapping_type" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px" required>
														<option value="" selected="selected" class="selection">....Select Mapping Type....</option>

														<?php
														$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type where status=1");
														$query_rsMapType->execute();
														$selected = $mapping_type == 0 ? "selected" : "";
														echo '<option value="0" ' . $selected . '>Not Applicable</option>';
														while ($row_rsMapType = $query_rsMapType->fetch()) {
															$selected = $row_rsMapType['id'] == $mapping_type ? "selected" : "";
														?>
															<option value="<?= $row_rsMapType['id'] ?>" <?= $selected ?>><?= $row_rsMapType['type'] ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</div>

											<div class="col-md-12">
												<label class="control-label">Indicator Description/Definition : <font align="left" style="background-color:#eff2f4"> </font></label>
												<p align="left">
													<textarea name="inddesc" cols="45" rows="4" class="txtboxes" id="inddesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required><?php echo $inddesc; ?></textarea>
													<script>
														CKEDITOR.replace('inddesc', {
															height: 200,
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
												</p>
											</div>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
													<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
													<div class="btn-group">
														<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
													</div>
													<input type="hidden" name="MM_update" value="editindfrm" />
													<input type="hidden" name="indid" value="<?= $ind ?>" />
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
			<!-- Start Modal Item Edit -->
			<div class="modal fade" id="adddetailsModal" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> <span id="modal_title">Add details</span> </h4>
						</div>
						<div class="modal-body" style="max-height:450px; overflow:auto;">
							<div class="card">
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="body">
											<div class="div-result">
												<form class="form-horizontal" id="addform" action="" method="POST">
													<?= csrf_token_html(); ?>
													<br />
													<div id="unitsof_measure">
														<div class="col-md-12" id="indirectbeneficiary">
															<label for="diss_type_name" class="control-label">Measurement Unit *:</label>
															<div class="form-input">
																<input type="text" name="unit" id="unit" placeholder="Enter" class="form-control">
															</div>
														</div>
														<div class="col-md-12" id="indirectbeneficiary">
															<label for="diss_type_name" class="control-label">Measurement Unit Description *:</label>
															<div class="form-input">
																<textarea name="unitdescription" id="unitdescription" cols="" rows="" class="form-control"></textarea>
															</div>
														</div>
													</div>
													<div id="diss_type">
													</div>
													<div class="modal-footer">
														<div class="col-md-12 text-center" id="">
															<input type="hidden" name="addnew" id="addnew" value="addnew">
															<input type="hidden" name="type_diss" id="type_diss" value="">
															<input type="hidden" name="dissegration_category" id="dissegration_category" value="">
															<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
															<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
															<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- /modal-body -->
					</div>
					<!-- /modal-content -->
				</div>
				<!-- /modal-dailog -->
			</div>
<?php
		} else {
			$results =  restriction();
			echo $results;
		}
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/indicators/indicators.js"></script>
<script src="assets/js/indicators/output.js"></script>