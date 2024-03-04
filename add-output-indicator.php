<?php
require('includes/head.php');
if ($permission) {
	require('functions/indicator.php');
	require('functions/department.php');
	require('functions/measurement-unit.php');
	$pageTitle = "Add Output Indicator";

	$measurement_units = get_measurement_units();


	try {
		$editFormAction = $_SERVER['PHP_SELF'];

		if (isset($_SERVER['QUERY_STRING'])) {
			$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addindfrm")) {
			$indcd = $_POST['indcode'];
			$indname = $_POST['indname'];
			$desc = $_POST['inddesc'];
			$unit = $_POST['indunit'];
			$indsector = $_POST['indsector'];
			$inddept = $_POST['inddept'];
			$baselinelevel = $_POST['baselinelevel'];
			$user = $_POST['user_name'];
			$mapping_type = $_POST['mapping_type'];

			$indcat = "Output";
			$current_date = date("Y-m-d");
			$indicator_type = 2;
			$indicators = get_indicator_by_indcode($indcd);
			$url = 'view-indicators.php';
			if (!$indicators) {
				$insertSQL = $db->prepare("INSERT INTO tbl_indicator (indicator_code, indicator_name, indicator_description, indicator_type, indicator_category, indicator_unit, indicator_sector, indicator_dept, indicator_baseline_level, indicator_mapping_type, user_name, date_entered) VALUES (:indcode, :indname, :inddesc, :indicator_type, :indcat, :indunit, :indsector, :inddept, :baselinelevel,:mapping_type, :user, :date)");
				$result = $insertSQL->execute(array(':indcode' => $indcd, ':indname' => $indname, ':inddesc' => $desc, ':indicator_type' => $indicator_type, ':indcat' => $indcat, ':indunit' => $unit, ':indsector' => $indsector, ':inddept' => $inddept, ':baselinelevel' => $baselinelevel, ":mapping_type" => $mapping_type, ':user' => $user, ':date' => $current_date));
				if ($result) {
					$msg = 'Indicator successfully added.';
					$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 3000,
							icon:'success',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = \"$url\";
						}, 3000);
						</script>";
				} else {
					$msg = 'Failed!! This Indicator was not added!!';
					$results = "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'Danger',
							timer: 5000,
							icon:'error',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = $url;
						}, 5000);
						</script>";
				}
			} else {
				$msg = 'Failed!! This Indicator was not added!!';
				$results = "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'Danger',
							timer: 5000,
							icon:'error',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = $url;
						}, 5000);
						</script>";
			}
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
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
							<form id="addindfrm" method="POST" name="addindfrm" action="" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Indicator</legend>

									<div class="col-lg-3 col-md-6 col-sm12 col-xs-12">
										<label>Indicator Code *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="indcode" placeholder="Enter indicator code" id="indcode" style="border:#CCC thin solid; border-radius: 5px" required onBlur="checkAvailability()">
										</div>
									</div>

									<div class="col-lg-7 col-md-12 col-sm12 col-xs-12" id="code-availability-status">
										<p><img src="assets/images/LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>
									</div>
									<div class="col-lg-12 col-md-12 col-sm12 col-xs-12 row">
										<div class="col-lg-3 col-md-6 col-sm12 col-xs-12">
											<label>Unit of Measure
												<a type="button" data-toggle="modal" data-target="#adddetailsModal" onclick='adddetails("unit")' id="outputItemModalBtnrow"> (Add new)</a>
												*:
											</label>
											<div class="form-line">
												<select name="indunit" id="indunit" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
													<option value="" selected="selected" class="selection">....Select Unit....</option>
													<?php
													if ($measurement_units) {
														foreach ($measurement_units as $measurement_unit) {
													?>
															<option value="<?php echo $measurement_unit['id'] ?>"><?php echo $measurement_unit['unit'] ?></option>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>

										<div class="col-lg-9 col-md-12 col-sm12 col-xs-12">
											<label>Result to be measured *:</label>
											<div>
												<input name="indname" type="text" class="form-control" placeholder="Enter result to be measured" id="indname" style="border:#CCC thin solid; border-radius: 5px" required />
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm12 col-xs-12">
										<label>Indicator <?= $ministrylabel ?>*:</label>
										<div class="form-line">
											<select name="indsector" id="indsector" onchange="get_department()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px" required>
												<option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
												<?php
												$departments = get_departments();
												if ($departments) {
													foreach ($departments as $department) {
												?>
														<option value="<?php echo $department['stid'] ?>"><?php echo $department['sector'] ?></option>
												<?php
													}
												}

												?>
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-12 col-sm12 col-xs-12">
										<label>Indicator <?= $departmentlabel ?>*:</label>
										<div class="form-line" id="inddeparment">
											<select name="inddept" id="inddept" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px" required>
												<option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-12 col-sm12 col-xs-12">
										<label>Indicator Data Level *:</label>
										<div class="form-line">
											<select name="baselinelevel" id="baselinelevel" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px" required>
												<option value="" selected="selected" class="selection">....Select Values Level....</option>
												<option value="1">Top Level</option>
												<option value="2">Lowest Level</option>
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-12 col-sm12 col-xs-12">
										<label>Mapping Type *:</label>
										<div class="form-line">
											<select name="mapping_type" id="mapping_type" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px" required>
												<option value="" selected="selected" class="selection">....Select Mapping Type....</option>
												<option value="0">Not Applicable</option>
												<?php
												$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
												$query_rsMapType->execute();
												$row_rsMapType = $query_rsMapType->fetch();
												$totalRows_rsMapType = $query_rsMapType->rowCount();
												do {
												?>
													<option value="<?= $row_rsMapType['id'] ?>"><?= $row_rsMapType['type'] ?></option>
												<?php
												} while ($row_rsMapType = $query_rsMapType->fetch());
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm12 col-xs-12">
										<label class="control-label">Indicator Description/Definition : <font align="left" style="background-color:#eff2f4"> </font></label>
										<p align="left">
											<textarea name="inddesc" cols="45" rows="4" class="txtboxes" id="inddesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
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
										<div class="col-lg-12 col-md-12 col-sm12 col-xs-12" align="center">
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
											</div>
											<input type="hidden" name="MM_insert" value="addindfrm" />
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
											<br />
											<div id="unitsof_measure">
												<div class="col-lg-12 col-md-12 col-sm12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Measurement Unit *:</label>
													<div class="form-input">
														<input type="text" name="unit" id="unit" placeholder="Enter" class="form-control">
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Measurement Unit Description *:</label>
													<div class="form-input">
														<textarea name="unitdescription" id="unitdescription" cols="" rows="" class="form-control"></textarea>
													</div>
												</div>
											</div>
											<div id="diss_type"> 
											</div>
											<div class="modal-footer">
												<div class="col-lg-12 col-md-12 col-sm12 col-xs-12 text-center" id="">
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

require('includes/footer.php');
?>

<script src="assets/js/indicators/indicators.js"></script>
<script src="assets/js/indicators/output.js"></script>