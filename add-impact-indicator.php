<?php
require('functions/strategicplan.php');
require('includes/head.php');

if ($permission) {
	require('functions/indicator.php');
	require('functions/datasources.php');
	require('functions/measurement-unit.php');
	require('functions/calculationmethods.php');

	try {
		//$currentYear = get_current_year();
		$data_sources = get_data_sources();
		$measurement_units = get_measurement_units();
		$indicator_calculation_methods = get_indicator_calculation_methods();
		$editFormAction = $_SERVER['PHP_SELF'];

		$results = "";
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addindfrm")) {
			$results = "";
			$indcd = $_POST['indcode'];
			$indname = $_POST['indname'];
			$desc = $_POST['inddesc'];
			$indcat = "Impact";
			$unit = $_POST['indunit'];
			$user = $_POST['user_name'];
			$current_date = date("Y-m-d");
			$source_data = 0;
			$inddirection = (isset($_POST['inddirection']) && !empty($_POST['inddirection'])) ? $_POST['inddirection'] : 0;
			$indcalculation = (isset($_POST['indcalculation']) && !empty($_POST['indcalculation'])) ? $_POST['indcalculation'] : '';

			$disaggregated = (isset($_POST['inddirectBenfType']) && !empty($_POST['inddirectBenfType'])) ?  $_POST['inddirectBenfType'] : 0;

			$query_rsIndicator = $db->prepare("SELECT indicator_code FROM tbl_indicator WHERE indicator_code = '$indcd'");
			$query_rsIndicator->execute();
			$indcount = $query_rsIndicator->rowCount();

			if ($indcount == 0) {
				$insertSQL = $db->prepare("INSERT INTO tbl_indicator (indicator_code, indicator_name, indicator_description, indicator_category, indicator_disaggregation, indicator_calculation_method, indicator_unit, indicator_direction, indicator_data_source, user_name, date_entered) VALUES (:indcode, :indname, :inddesc, :indcat, :indidis, :indcalcmethod, :indunit, :inddirection, :source_data, :user, :date)");
				$result = $insertSQL->execute(array(':indcode' => $indcd, ':indname' => $indname, ':inddesc' => $desc, ':indcat' => $indcat, ':indidis' => $disaggregated, ':indcalcmethod' => $indcalculation, ':indunit' => $unit, ':inddirection' => $inddirection, ':source_data' => $source_data, ':user' => $user, ':date' => $current_date));

				if ($result) {
					$last_id = $db->lastInsertId();

					if (isset($_POST['inddirectBenfType'])) {
						/* if (isset($_POST['indcalculation'])) {
							$category = 2;
							if ($indcalculation == 1) {
								if (isset($_POST['direct_impact_aggragate'])) {
									$aggragate = $_POST['direct_impact_aggragate'];
									$type = "n";
									$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
									$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":measurement_variable" => $aggragate, ":category" => $category,   ":type" => $type));
								}
							} else if ($indcalculation == 2) {
								if (isset($_POST['direct_impact_denominator'])) {
									$denominator = $_POST['direct_impact_denominator'];
									$numerator = $_POST['direct_impact_numerator'];
									$type = "n";
									$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
									$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":measurement_variable" => $numerator,  ":category" => $category,  ":type" => $type));
									$type = "d";
									$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
									$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":measurement_variable" => $denominator, ":category" => $category,   ":type" => $type));
								}
							} else if ($indcalculation == 3) {
								if (isset($_POST['direct_impact_numerator'])) {
									$numerator = $_POST['direct_impact_numerator'];
									$type = "n";
									$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
									$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":measurement_variable" => $numerator, ":category" => $category,   ":type" => $type));
								}
							}
						} */

						if (isset($_POST['direct_impact_dissagragation_type']) && !empty($_POST['direct_impact_dissagragation_type'])) {
							$disaggregated_type = $_POST['direct_impact_dissagragation_type'];
							$impact_dissagragation_parent = $_POST['direct_impact_dissagragation_parent'];
							$Btype = 2;

							for ($i = 0; $i < count($disaggregated_type); $i++) {
								$disaggregated_category = $disaggregated_type[$i];
								$parent = $impact_dissagragation_parent[$i] != "" ? $impact_dissagragation_parent[$i] : 0;

								$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables_disaggregation_type(indicatorid,disaggregation_type,parent, type) VALUES(:indicatorid,:disaggregated_category,:parent, :type)");
								$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":disaggregated_category" => $disaggregated_category, ":parent" => $parent,  ":type" => $Btype));

								if ($result2) {
									if (isset($_POST['direct_impact_disaggregations']) && !empty($_POST['direct_impact_disaggregations'])) {
										$impact_disaggregations = $_POST['direct_impact_disaggregations'];
										$disaggregations = explode(",", $impact_disaggregations[$i]);
										for ($j = 0; $j < count($disaggregations); $j++) {
											$name = $disaggregations[$j];
											$insertBeneficiary = $db->prepare("INSERT INTO `tbl_indicator_disaggregations`(indicatorid, disaggregation_type, disaggregation) VALUES(:indicatorid, :disaggregation_type, :disaggregation)");
											$result2  = $insertBeneficiary->execute(array(":indicatorid" => $last_id, ":disaggregation_type" => $disaggregated_category, ":disaggregation" => $name));
										}
									}
								}
							}
						}
					}

					$indid = $last_id;
					$url = 'view-indicators.php';
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
						window.location.href = all-indicators';
					}, 5000);
				</script>";
				}
			}
		}


		if (isset($_SERVER['QUERY_STRING'])) {
			$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
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
							<form id="addindfrm" method="POST" name="addindfrm" action="" onsubmit="return form_validate()" enctype="multipart/form-data" autocomplete="off">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Indicator</legend>

									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<label>Indicator Code *:</label>
										<div class="form-line">
											<input type="text" class="form-control" name="indcode" id="indcode" style="border:#CCC thin solid; border-radius: 5px" required onBlur="checkAvailability()" placeholder="Enter impact indicator code">
										</div>
									</div>

									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="code-availability-status">
										<p id="loaderIcon" style="display:none" />
										</p>
									</div>

									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Impact Indicator *:</label>
										<div>
											<input name="indname" type="text" class="form-control" id="indname" style="border:#CCC thin solid; border-radius: 5px" required placeholder="Enter impact indicator" />
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<label>Unit of Measure
											<a type="button" data-toggle="modal" data-target="#adddetailsModal" onclick='adddetails("unit")' id="outputItemModalBtnrow"> (Add new)</a>
											*:
										</label>
										<div class="form-line">
											<select name="indunit" id="indunit" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
												<option value="" selected="selected" class="selection">....Select Unit....</option>
												<?php
												for ($i = 0; $i < count($measurement_units); $i++) {
												?>
													<option value="<?php echo $measurement_units[$i]['id'] ?>"><?php echo $measurement_units[$i]['unit'] ?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="direction">
										<label class="control-label">Desired progress direction *:</label>
										<div class="form-line">
											<select name="inddirection" id="inddirection" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
												<option value="">.... Select Direction ....</option>
												<option value="1">Upward</option>
												<option value="2">Downward</option>
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<label class="control-label">Further Disaggregations ? *:</label>
										<div class="form-line">
											<select name="inddirectBenfType" id="inddirectBenfType" onchange="impact_direct_dissagragation_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
												<option value="">.... Select from list ....</option>
												<option value="1">Yes</option>
												<option value="0">No</option>
											</select>
											<span id="bfmsg" style="color: red"> </span>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="indcalculationdiv">
										<label class="control-label">Calculation Method*:</label>
										<div class="form-line">
											<select name="indcalculation" id="indcalculation" onchange="indcalculation_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
												<option value="">.... Select Method ....</option>
												<?php
												for ($i = 0; $i < count($indicator_calculation_methods); $i++) {
												?>
													<option value="<?php echo $indicator_calculation_methods[$i]['id'] ?>"><?php echo $indicator_calculation_methods[$i]['method'] ?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>

									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="impact_direct_disagregation">
										<label class="control-label">Disaggregations *:</label>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="direct_impact_table" style="width:100%">
												<thead>
													<tr>
														<th width="10%">#</th>
														<th width="30%">Disaggregation Type
															<a type="button" data-toggle="modal" data-target="#adddetailsModal" onclick='adddetails("output", 1)' id="outputItemModalBtnrow"> (Add new)</a>
														</th>
														<th width="30%">Parent</th>
														<th width="30%">Disaggregations</th>
														<th width="5%">
															<button type="button" name="addplus" id="addplus_direct_impact" onclick="add_row_direct_impact();" class="btn btn-success btn-sm">
																<span class="glyphicon glyphicon-plus">
																</span>
															</button>
														</th>
													</tr>
												</thead>
												<tbody id="direct_impact_table_body">
													<tr></tr>
													<tr id="removedirectTr">
														<td colspan="5" class="text-center">Add Disaggregations</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>

									<!--<div class="impact_details">
										<div class="" id="direct_impact_formula">
										</div>
									</div>-->

									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
										<div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
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
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Measurement Unit *:</label>
													<div class="form-input">
														<input type="text" name="unit" id="unit" placeholder="Enter" class="form-control">
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Measurement Unit Description *:</label>
													<div class="form-input">
														<textarea name="unitdescription" id="unitdescription" cols="" rows="" class="form-control">
													</textarea>
													</div>
												</div>
											</div>
											<div id="diss_type">
												<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Add Disaggregation Type*:</label>
													<div class="form-input">
														<input type="text" name="diss_type_name" id="diss_type_name" placeholder="Enter" class="form-control">
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
													<label class="control-label" title="">Disaggregation Category*:</label>
													<div class="form-line">
														<select name="disaggregation_cat" id="disaggregation_cat" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
															<option value="">.... Select from list ....</option>
															<option value="0">Location</option>
															<option value="1">Others</option>
														</select>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="indirectbeneficiary">
													<label for="diss_type_name" class="control-label">Disaggregation Type Description *:</label>
													<div class="form-input">
														<textarea name="disdescription" id="disdescription" cols="" rows="" class="form-control">
													</textarea>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="">
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
<script src="assets/js/indicators/impact.js"></script>

<script>
	$(document).ready(function() {
		impact_direct_disaggregation_hide();
	});
</script>