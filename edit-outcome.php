<?php
try {
	if ($permission && (isset($_GET['ind']) && !empty($_GET["ind"]))) {
		$ind =  base64_decode($_GET['ind']);
		require('includes/head.php');
		// get the functions selecting data
		require('functions/indicator.php');
		require('functions/department.php');
		require('functions/strategicplan.php');
		require('functions/datasources.php');
		require('functions/measurement-unit.php');
		require('functions/calculationmethods.php');
		$strategic_plan = get_strategic_plan();
		$currentYear = get_current_year();
		$departments = get_departments();
		$data_sources = get_data_sources();
		$measurement_units = get_measurement_units();
		$indicator_calculation_methods = get_indicator_calculation_methods();
		$strategic_plan_objectives = get_strategic_objectives();
		$years = $strategic_plan['years'];
		$starting_year = $strategic_plan['starting_year'];
		$results = "";
		$indicator = get_indicator_by_indid($ind);

		if ($indicator) {
			// get all the variables here
			$indcode = $indicator['indicator_code'];
			$indname = $indicator['indicator_name'];
			$inddesc = $indicator['indicator_description'];
			$calculationmethod = $indicator['indicator_calculation_method'];
			$indunit = $indicator['indicator_unit'];
			$inddir = $indicator['indicator_direction'];
			$indicator_category = $indicator['indicator_category'];
			$indicator_type = $indicator['indicator_type'];
			$indicator_sector = $indicator['indicator_sector'];
			$indicator_dept = $indicator['indicator_dept'];
			$disaggragated = $indicator['indicator_disaggregation'];
			$data_source_id = $indicator['indicator_data_source'];


			if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editindfrm")) {
				$indcd = $_POST['indcode'];
				$indid = $ind;
				$indname = $_POST['indname'];
				$desc = $_POST['inddesc'];
				$indcat = "Outcome";
				$unit = $_POST['indunit'];
				$indsector = $_POST['indsector'];
				$inddept = $_POST['inddept'];
				$user = $_POST['user_name'];
				$current_date = date("Y-m-d");

				$inddirection = null;
				$calculationmethod = null;
				$source_data = null;

				$inddirection = (isset($_POST['inddirection']) && !empty($_POST['inddirection'])) ? $_POST['inddirection'] : 0;
				$calculationmethod = (isset($_POST['indcalculation']) && !empty($_POST['indcalculation'])) ? $_POST['indcalculation'] : 0;
				$disaggregated = (isset($_POST['inddirectBenfType']) && !empty($_POST['inddirectBenfType'])) ? $disaggregated = $_POST['inddirectBenfType'] : 0;

				$query_rsIndicator = $db->prepare("SELECT indid, indicator_code FROM tbl_indicator WHERE indicator_code = '$indcd'");
				$query_rsIndicator->execute();
				$row_rsIndicator = $query_rsIndicator->fetch();
				$indcount = $query_rsIndicator->rowCount();
				$ind = $indcount > 0 ? $row_rsIndicator["indid"] : '';

				if ($ind == $indid || $indcount == 0) {

					$updateSQL = $db->prepare("UPDATE tbl_indicator SET indicator_code=:indcode, indicator_name=:indname, indicator_description=:inddesc, indicator_category=:indcat, indicator_calculation_method=:indcalcmethod, indicator_unit=:indunit, indicator_direction=:inddirection, indicator_sector=:indsector, indicator_dept=:inddept,indicator_data_source=:source_data,indicator_aggregation=:disaggregated, updated_by=:user, date_updated=:date WHERE indid=:indid");
					$result = $updateSQL->execute(array(':indcode' => $indcd, ':indname' => $indname, ':inddesc' => $desc, ':indcat' => $indcat,  ':indcalcmethod' => $calculationmethod, ':indunit' => $unit, ':inddirection' => $inddirection, ':indsector' => $indsector, ':inddept' => $inddept, ":source_data" => $source_data, ":disaggregated" => $disaggregated, ':user' => $user, ':date' => $current_date, ':indid' => $indid));

					if ($result) {
						// delete from tbl_indicator_measurement_variables_disaggregation_type
						$deleteQuery = $db->prepare("DELETE FROM `tbl_indicator_measurement_variables_disaggregation_type` WHERE indicatorid=:indicatorid");
						$results = $deleteQuery->execute(array(':indicatorid' => $indid));

						// delete from tbl_indicator_measurement_variables
						$deleteQuery = $db->prepare("DELETE FROM `tbl_indicator_measurement_variables` WHERE indicatorid=:indicatorid");
						$results = $deleteQuery->execute(array(':indicatorid' => $indid));

						// delete from tbl_indicator_disaggregations
						$deleteQuery = $db->prepare("DELETE FROM `tbl_indicator_disaggregations` WHERE indicatorid=:indicatorid");
						$results = $deleteQuery->execute(array(':indicatorid' => $indid));



						if (isset($_POST['inddirectBenfType'])) {
							$result2 = false;
							if (isset($_POST['indcalculation'])) {
								$indcalculation = $_POST['indcalculation'];
								$category = 2;
								if ($indcalculation == 1) {
									if (isset($_POST['direct_outcomeaggragate'])) {
										$aggragate = $_POST['direct_outcomeaggragate'];
										$type = "n";
										$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
										$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":measurement_variable" => $aggragate, ":category" => $category,  ":type" => $type));
									}
								} else if ($indcalculation == 2) {
									if (isset($_POST['direct_outcomedenominator'])) {
										$denominator = $_POST['direct_outcomedenominator'];
										$numerator = $_POST['direct_outcomenumerator'];
										$type = "n";
										$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
										$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":measurement_variable" => $numerator, ":category" => $category,  ":type" => $type));
										$type = "d";
										$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
										$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":measurement_variable" => $denominator, ":category" => $category,  ":type" => $type));
									}
								} else if ($indcalculation == 3) {
									if (isset($_POST['direct_outcomenumerator'])) {
										$numerator = $_POST['direct_outcomenumerator'];
										$type = "n";
										$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables(indicatorid,measurement_variable,category,type) VALUES(:indicatorid,:measurement_variable,:category, :type)");
										$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":measurement_variable" => $numerator, ":category" => $category,  ":type" => $type));
									}
								}
							}


							$$result2 = false;
							if ($disaggregated == 1) {
								if (isset($_POST['direct_outcome_dissagragation_type']) && !empty($_POST['direct_outcome_dissagragation_type'])) {
									$disaggregated_type = $_POST['direct_outcome_dissagragation_type'];
									$impact_dissagragation_parent = $_POST['direct_outcome_dissagragation_parent'];
									$impact_disaggregations = $_POST['direct_outcome_disaggregations'];
									$Btype = 2;
									for ($i = 0; $i < count($disaggregated_type); $i++) {
										$disaggregated_category = $disaggregated_type[$i];
										$parent = $impact_dissagragation_parent[$i] != "" ? $impact_dissagragation_parent[$i] : 0;

										$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_measurement_variables_disaggregation_type(indicatorid,disaggregation_type,parent, type) VALUES(:indicatorid,:disaggregated_category,:parent, :type)");
										$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":disaggregated_category" => $disaggregated_category, ":parent" => $parent,  ":type" => $Btype));

										if ($result2) {
											$disaggregations = explode(",", $impact_disaggregations[$i]);
											for ($j = 0; $j < count($disaggregations); $j++) {
												$name = $disaggregations[$j];
												$insertBeneficiary = $db->prepare("INSERT INTO `tbl_indicator_disaggregations`(indicatorid, disaggregation_type, disaggregation) VALUES(:indicatorid, :disaggregation_type, :disaggregation)");
												$result2  = $insertBeneficiary->execute(array(":indicatorid" => $indid, ":disaggregation_type" => $disaggregated_category, ":disaggregation" => $name));
											}
										}
									}
								}
							}
						}
					}



					if ($result) {
						$url = "all-indicators.php";
						$msg = 'Indicator successfully updated.';
						$results = "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 3000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = \" $url\";
							}, 3000);
						</script>";
						echo $results;
					} else {
						$msg = 'Failed!! This Indicator was not updated!!';
						$results = "<script type=\"text/javascript\">
							swal({
								title: \"Error!\",
								text: \" $msg\",
								type: 'Danger',
								timer: 5000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'add-indicators';
							}, 5000);
						</script>";
						echo $results;
					}
				}
			}

?>

			<!-- add assets -->
			<script src="assets/ckeditor/ckeditor.js"></script>
			<div class="body">
				<div style="margin-top:5px">
					<form id="editindfrm" method="POST" name="editindfrm" action="" onsubmit="return form_validate()" enctype="multipart/form-data" autocomplete="off">
						<?= csrf_token_html(); ?>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Indicator</legend>

							<div class="col-md-3">
								<label>Indicator Code *:</label>
								<div class="form-line">
									<input type="text" class="form-control" name="indcode" id="indcode" value="<?= $indcode ?>" style="border:#CCC thin solid; border-radius: 5px" required onBlur="checkAvailability()">
								</div>
							</div>

							<div class="col-md-7" id="code-availability-status">
								<p><img src="assets/images/LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>
							</div>

							<div class="col-md-12">
								<label>Indicator Name *:</label>
								<div>
									<input name="indname" type="text" class="form-control" id="indname" value="<?= $indname ?>" style="border:#CCC thin solid; border-radius: 5px" required />
								</div>
							</div>

							<div class="col-md-6">
								<label>Measurement Unit
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

							<div class="col-md-6">
								<label>Indicator <?= $ministrylabel ?>*:</label>
								<div class="form-line">
									<select name="indsector" id="indsector" onchange="get_department()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
										<option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
										<?php
										foreach ($departments as $department) {
											$selected = ($indicator_sector == $department['stid']) ? "selected" : "";
										?>
											<option value="<?php echo $department['stid'] ?>" <?= $selected ?>><?php echo $department['sector'] ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<label>Indicator <?= $departmentlabel ?>*:</label>
								<div class="form-line" id="inddeparment">
									<select name="inddept" id="inddept" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
										<option value="" selected="selected" class="selection">....Select <?= $departmentlabel ?> ....</option>
										<?php
										$sectors = get_department_child($indicator_sector);
										foreach ($sectors as $sector) {
											$selected = ($indicator_dept == $sector['stid']) ? "selected" : "";
										?>
											<option value="<?php echo $sector['stid'] ?>" <?= $selected ?>><?php echo $sector['sector'] ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-6" id="direction">
								<label class="control-label">Indicator Direction *:</label>
								<div class="form-line">
									<select name="inddirection" id="inddirection" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
										<option value="">.... Select Direction ....</option>
										<option value="1" <?php echo ($inddir == 1) ? "selected" : ""; ?>>Upward</option>
										<option value="2" <?php echo ($inddir == 2) ? "selected" : ""; ?>>Downward</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<label class="control-label">Disaggregation *:</label>
								<div class="form-line">
									<select name="inddirectBenfType" id="inddirectBenfType" onchange="outcome_direct_dissagragation_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
										<option value="">.... Select from list ....</option>
										<option value="1" <?php echo $disaggragated == 1 ? "selected" : ""; ?>>Disaggregated </option>
										<option value="0" <?php echo $disaggragated == 0 ? "selected" : ""; ?>>Non-Disaggregated </option>
									</select>
									<span id="bfmsg" style="color: red"> </span>
								</div>
							</div>

							<div class="col-md-6" id="indcalculationdiv">
								<label class="control-label">Calculation Method*:</label>
								<div class="form-line">
									<select name="indcalculation" id="indcalculation" onchange="indcalculation_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
										<option value="">.... Select Method ....</option>
										<?php
										foreach ($indicator_calculation_methods as $indicator_calculation_method) {
											$selected = ($calculationmethod == $indicator_calculation_method['id']) ? "selected" : "";
										?>
											<option value="<?php echo $indicator_calculation_method['id'] ?>" <?= $selected ?>><?php echo $indicator_calculation_method['method'] ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-12" id="outcome_direct_disagregation">
								<label class="control-label">Disaggregations *:</label>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
										<thead>
											<tr>
												<th width="10%">#</th>
												<th width="30%">Disaggregation Type
													<a type="button" data-toggle="modal" data-target="#adddetailsModal" onclick='adddetails("output", 1)' id="outputItemModalBtnrow"> (Add new)</a>
												</th>
												<th width="30%">Parent</th>
												<th width="30%">Disaggregations</th>
												<th width="5%">
													<button type="button" name="addplus" id="addplus_direct_outcome" onclick="add_row_direct_outcome();" class="btn btn-success btn-sm">
														<span class="glyphicon glyphicon-plus">
														</span>
													</button>
												</th>
											</tr>
										</thead>
										<tbody id="direct_outcome_table_body">
											<tr></tr>
											<?php
											$query_rsIndType = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid= '$ind' and type= 2 ORDER BY id");
											$query_rsIndType->execute();
											$row_rsIndType = $query_rsIndType->fetch();
											$indIndTypecount = $query_rsIndType->rowCount();

											if ($indIndTypecount > 0) {
												$rowno = 0;
												do {
													$rowno++;

													$output_dissagragated_type = $row_rsIndType['disaggregation_type'];
													$output_dissagragated_parent = $row_rsIndType['parent'];

													$query_rsInd_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types");
													$query_rsInd_type->execute();
													$row_rsInd_type = $query_rsInd_type->fetch();

													$imp_options = '<option  value="">Select from list</option>';
													do {
														if ($row_rsInd_type['id'] == $output_dissagragated_type) {
															$imp_options .= '<option  value="' . $row_rsInd_type['id'] . '" selected>' . $row_rsInd_type['category'] . '</option>';
														} else {
															$imp_options .= '<option  value="' . $row_rsInd_type['id'] . '">' . $row_rsInd_type['category'] . '</option>';
														}
													} while ($row_rsInd_type = $query_rsInd_type->fetch());

													$query_rsInd_parent = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types");
													$query_rsInd_parent->execute();
													$row_rsInd_parent = $query_rsInd_parent->fetch();

													$imp_parent_options = '<option  value="">Select from list</option>';
													do {
														if ($output_dissagragated_parent == $row_rsInd_parent['id']) {
															$imp_parent_options .= '<option  value="' . $row_rsInd_parent['id'] . '" selected>' . $row_rsInd_parent['category'] . '</option>';
														} else {
															$imp_parent_options .= '<option  value="' . $row_rsInd_parent['id'] . '">' . $row_rsInd_parent['category'] . '</option>';
														}
													} while ($row_rsInd_parent = $query_rsInd_parent->fetch());

													$query_rsInd_type_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregations WHERE indicatorid=:indicatorid AND disaggregation_type=:disaggregation_type");
													$query_rsInd_type_diss->execute(array(":indicatorid" => $ind, ":disaggregation_type" => $output_dissagragated_type));
													$row_rsInd_type_diss = $query_rsInd_type_diss->fetch();
													$data_diss = [];

													do {
														$data_diss[] = $row_rsInd_type_diss['disaggregation'];
													} while ($row_rsInd_type_diss = $query_rsInd_type_diss->fetch());

													$disabled  = "readonly";
													$required = "";
													$query_rstype = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id=:output_dissagragated_type");
													$query_rstype->execute(array(":output_dissagragated_type" => $output_dissagragated_type));
													$row_rstype = $query_rstype->fetch();

													$type = $row_rstype['type'];
													if ($type == 1) {
														$required = 'required="required"';
														$disabled = '';
													}
											?>
													<tr id="direct<?= $rowno ?>">
														<td><?= $rowno ?></td>
														<td>
															<select name="direct_outcome_dissagragation_type[]" id="direct_outcome_dissagragation_type<?= $rowno ?>" onchange="direct_outcome_dissagragation_type_change(<?= $rowno ?>)" class="form-control show-tick direct_outcome" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
																<?= $imp_options ?>
															</select>
														</td>
														<td>
															<select name="direct_outcome_dissagragation_parent[]" id="direct_outcome_dissagragation_parent<?= $rowno ?>" onchange="direct_outcome_dissagragation_parent_change(<?= $rowno ?>)" class="form-control show-tick direct_outcome_parent" style="border:1px #CCC thin solid; border-radius:5px">
																<?= $imp_parent_options ?>
															</select>
														</td>
														<td>
															<input type="text" value="<?= implode(",", $data_diss); ?>" name="direct_outcome_disaggregations[]" id="direct_outcome_disaggregations<?= $rowno ?>" placeholder="Enter" class="form-control" <?= $required . " " . $disabled ?>>
														</td>
														<td>
															<button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_direct_outcome('direct<?= $rowno ?>', <?= $rowno ?>)">
																<span class="glyphicon glyphicon-minus"></span>
															</button>
														</td>
													</tr>
												<?php
												} while ($row_rsIndType = $query_rsIndType->fetch());
											} else {
												?>
												<tr id="removeindoutputTr" class="text-center">
													<td colspan="5">Add Disaggregations</td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>

							<div class="" id="direct_outcome_formulas">
								<?php
								$prefix = "direct_outcome";
								$measurement_variable = "Direct Measurement Variable";
								$data = '';
								$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$ind' AND  category='2'");
								$query_measurement_variables->execute();
								$row_measurement_variables = $query_measurement_variables->fetch();

								$data = "";
								if ($calculationmethod == 1) {
									$variable = $row_measurement_variables['measurement_variable'];
									$data .=
										'<div class="col-md-12" id="">
								<label for="sum" id="" class="control-label">Summation Measurement variables *:</label>
								<div class="form-input">
									<input type="text" value="' . $variable . '" name="' . $prefix . 'aggragate" id="' . $prefix . 'aggragate" placeholder="' . $measurement_variable . '" class="form-control">
								</div>
							</div>';
								} else if ($calculationmethod == 2) {
									do {
										$variable = $row_measurement_variables['measurement_variable'];
										$type = $row_measurement_variables['type'];
										if ($type == 'n') {
											$data .=
												'<div class="col-md-12" id="">
										<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
										<div class="form-input">
											<input type="text" value="' . $variable . '" name="' . $prefix . 'numerator" id="' . $prefix . 'numerator" placeholder="' . $measurement_variable . '" class="form-control">
										</div>
									</div>';
										} else if ($type == 'd') {
											$data .=
												'<div class="col-md-12" id="">
										<label for="sum" id="denominator" class="control-label">Denominator Measurement variables *:</label>
										<div class="form-input">
											<input type="text" value="' . $variable . '" name="' . $prefix . 'denominator" id="' . $prefix . 'denominator" placeholder="' . $measurement_variable . '" class="form-control">
										</div>
									</div>';
										}
									} while ($row_measurement_variables = $query_measurement_variables->fetch());
								} else if ($calculationmethod == 3) {
									$variable = $row_measurement_variables['measurement_variable'];
									$data .=
										'<div class="col-md-12" id="">
								<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
								<div class="form-input">
									<input type="text" value="' . $variable . '" name="' . $prefix . 'numerator" id="' . $prefix . 'numerator" placeholder="' . $measurement_variable . '" class="form-control">
								</div>
							</div>';
								}
								echo $data;
								?>
							</div>

							<div class="col-md-12">
								<label class="control-label">Indicator Description : <font align="left" style="background-color:#eff2f4"> </font></label>
								<p align="left">
									<textarea name="inddesc" cols="45" rows="4" class="txtboxes" id="inddesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
								<?php
								echo $inddesc;
								?>
						</textarea>
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
								</div>
							</div>

						</fieldset>
					</form>
				</div>
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
	include_once('indicators/partials/add-modal.php');
	include_once('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/indicators/indicators.js"></script>
<script src="assets/js/indicators/outcome.js"></script>

<script>
	$(document).ready(function() {
		<?php
		if ($disaggragated != 1) {
		?>
			outcome_direct_disaggregation_hide()
		<?php
		}
		?>
	});
</script>