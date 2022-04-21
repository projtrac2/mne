<?php


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
require('../../functions/indicator.php');
require('../../functions/department.php');
require('../../functions/strategicplan.php');
require('../../functions/datasources.php');
require('../../functions/measurement-unit.php');
require('../../functions/calculationmethods.php');
require('../../system-labels.php');

$strategic_plan = get_strategic_plan();
$currentYear = get_current_year();
$data_sources = get_data_sources();
$measurement_units = get_measurement_units();
$indicator_calculation_methods = get_indicator_calculation_methods();
$strategic_plan_objectives = get_strategic_objectives();

// check if indicator codehas been used 
if (isset($_POST["indcode"]) && !empty($_POST["indcode"])) {
	// Get all state data
	$code = $_POST["indcode"];
	$indid = $_POST["indid"];
	// $indicators = get_indicator_by_indcode($indcd);
	$query_rsIndicator = $db->prepare("SELECT indid, indicator_code FROM tbl_indicator WHERE indicator_code = '$code'");
	$query_rsIndicator->execute();
	$row_rsIndicator = $query_rsIndicator->fetch();
	$indcount = $query_rsIndicator->rowCount();

	if (($indcount > 0 && empty($indid)) || ($indcount > 0 && !empty($indid) && $row_rsIndicator["indid"] != $indid)) {
		echo "<label>&nbsp;</label>
		<div class='alert bg-red alert-dismissible' role='alert' style='height:35px; padding-top:5px'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			This Indicator Code (" . $code . ") already exist and can not use it again!!
		</div>";
	}
}

// get department
if (isset($_POST['sct_id'])) {
	$dept = $_POST['sct_id'];
	$departments = get_department_child($dept);
	if ($departments) {
		echo '<option value="">Select ' . $departmentlabel . '</option>';
		for ($i = 0; $i < count($departments); $i++) {
			echo '<option value="' . $departments[$i]['stid'] . '"> ' . $departments[$i]['sector'] . '</option>';
		}
	} else {
		echo '<option value="">No Record Found!!!</option>';
	}
}

// get indicator disaggregations 
if (isset($_POST['get_disaggregations'])) {
	$disaggregations = indicator_disaggregation_types();
	if ($disaggregations) {
		$options = '<option value="">Select from list</option>';
		for ($i = 0; $i < count($disaggregations); $i++) {
			$options .=  '<option value="' . $disaggregations[$i]['id'] . '"> ' . $disaggregations[$i]['category'] . '</option>';
		}
		echo $options;
	} else {
		echo '<option value="">No Record Found!!!</option>';
	}
}

if (isset($_POST['add_type_diss']) && !empty($_POST['add_type_diss'])) {
	$diss_type_name = $_POST['diss_type_name'];
	$dissegration_category = $_POST['disaggregation_cat'];
	$description = $_POST['disdescription'];
	$valid = [];
	$insert = $db->prepare("INSERT INTO `tbl_indicator_disaggregation_types`(category, Description, type)  VALUES(:category,:description, :type)");
	$results  = $insert->execute(array(":category" => $diss_type_name, ":description" => $description, ":type" => $dissegration_category));

	if ($results === TRUE) {
		$valid['msg'] = true;
	} else {
		$valid['msg'] = false;
	}
	echo json_encode($valid);
}

if (isset($_POST['addunit']) && !empty($_POST['addunit'])) {
	$unit  = $_POST['unit'];
	$unitdescription = $_POST['unitdescription'];
	$valid = [];
	$insert = $db->prepare("INSERT INTO `tbl_measurement_units`(unit, description)  VALUES(:unit, :description)");
	$results  = $insert->execute(array(':unit' => $unit, ':description' => $unitdescription));

	if ($results === TRUE) {
		$valid['msg'] = true;
	} else {
		$valid['msg'] = false;
	}
	echo json_encode($valid);
}

if (isset($_POST['get_diss_type'])) {
	$type = $_POST['get_diss_type'];
	$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types");
	$query_diss_type->execute();
	$options = '<option value="">Select from list</option>';
	while ($row = $query_diss_type->fetch()) {
		$options .=  '<option value="' . $row['id'] . '"> ' . $row['category'] . '</option>';
	}

	echo $options;
}

if (isset($_POST['getdisstype'])) {
	$type = $_POST['ind_diss'];
	$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types  WHERE  id=:type");
	$query_diss_type->execute(array(":type" => $type));
	$row = $query_diss_type->fetch();
	$type = $row['type'];
	$data['success'] = false;

	if ($type == 0) {
		$data['success'] = true;
	}

	echo json_encode($data);
}

if (isset($_POST['get_method'])) {
	$data = '';
	if (isset($_POST['method']) && !empty($_POST['method'])) {
		$method = $_POST['method'];
		$outcome_type = $_POST['outcome_type'];

		$prefix = "";
		$measurement_variable = "";
		if ($outcome_type == 3) {
			$prefix = "impact";
			$measurement_variable = "Impact Measurement Variable";
		} else if ($outcome_type == 1) {
			$prefix = "direct_outcome";
			$measurement_variable = "Direct Measurement Variable";
		} else if ($outcome_type == 2) {
			$prefix = "indirect_outcome";
			$measurement_variable = "Indirect Measurement Variable";
		}

		if ($method == 1) {
			$data .=
				'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Summation Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="' . $prefix . 'aggragate" id="' . $prefix . 'aggragate" placeholder="' . $measurement_variable . '" class="form-control">
				</div>
			</div>';
		} else if ($method == 2) {
			$data .=
				'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="' . $prefix . 'numerator" id="' . $prefix . 'numerator" placeholder="' . $measurement_variable . '" class="form-control">
				</div>
			</div>
			<div class="col-md-12" id="">
				<label for="sum" id="denominator" class="control-label">Denominator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="' . $prefix . 'denominator" id="' . $prefix . 'denominator" placeholder="' . $measurement_variable . '" class="form-control">
				</div>
			</div>';
		} else if ($method == 3) {
			$data .=
				'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="' . $prefix . 'numerator" id="' . $prefix . 'numerator" placeholder="' . $measurement_variable . '" class="form-control">
				</div>
			</div>';
		}
	}
	echo $data;
}

if (isset($_POST['delete'])) {
	$indid = $_POST['indid'];
	$indicator = get_indicator_by_indid($indid);
	if ($indicator) {
		$delete_indicator = $db->prepare("DELETE FROM tbl_indicator WHERE indid =:indid ");
		$response1        = $delete_indicator->execute(array(":indid" => $indid));

		$delete_indicator_measurement_variables = $db->prepare("DELETE FROM tbl_indicator_measurement_variables WHERE indicatorid=:indid");
		$response2        =  $delete_indicator_measurement_variables->execute(array(":indid" => $indid));

		$delete_measurement_variables_disaggregation_type = $db->prepare("DELETE FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid=:indid");
		$response2        =  $delete_measurement_variables_disaggregation_type->execute(array(":indid" => $indid));

		$delete_indicator_disaggregations = $db->prepare("DELETE FROM tbl_indicator_disaggregations WHERE indicatorid=:indid");
		$response2        =  $delete_indicator_disaggregations->execute(array(":indid" => $indid));

		if ($response1 && $response2) {
			echo json_encode(array('success' => true, "msg" => "Success Deleting Checklist checklist"));
		} else {
			echo json_encode(array('success' => false, "msg" => "Error Deleting Checklist!!!"));
		}
	} else {
		echo json_encode(array('success' => false, "msg" => "Error Deleting Checklist!!!"));
	}
}

if (isset($_GET['more'])) {
	$indid = $_GET['indid'];
	$indicator = get_indicator_by_indid($indid);
	if ($indicator) {
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
		$dissagragated = $indicator['indicator_disaggregation'];
		$data_source = $indicator['indicator_data_source'];
		$ind_dir = ($inddir == 1) ? "Upward" : "Downward";

		$measurement_unit = get_measurement_unit($indunit);

		$unit = ($measurement_unit) ? $measurement_unit['unit'] : "";

		$calculation_method = get_indicator_calculation_method($calculationmethod);
		$calc_method = ($calculation_method) ? $calculation_method['method'] : "";


		$source_of_data = $calculation  = $direction = $category = "";

		if ($indicator_category == "Output") {
			if ($dissagragated == 1) {
				$type = 0;
				$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid=:indid  ");
				$query_diss_type->execute(array(":indid" => $indid));

				$query_dataSource =  $db->prepare("SELECT * FROM tbl_data_source  WHERE id='$data_source'");
				$query_dataSource->execute();
				$row_dataSource = $query_dataSource->fetch();
				$source_data = $row_dataSource['source'];

				$source_of_data .= '<li class="list-group-item"><strong>Source of Data: </strong>' . $source_data . ' </li> ';

				$category .= '  
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">   
							<div class="body">
								<div class="header"> 
									<li class="list-group-item list-group-item list-group-item-action active">Disaggregation Types</li>
								</div>';
								$options = ' 
								<ul class="list-group">';
									$count = 1;
									while ($row = $query_diss_type->fetch()) {
										$disaggregation_type = $row['disaggregation_type'];
										$query_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id=:disaggregation_type  ");
										$query_diss->execute(array(":disaggregation_type" => $disaggregation_type));
										$row_diss = $query_diss->fetch();
										$options .= '<li class="list-group-item">' . $count++ . ". " . $row_diss['category'] . '</li>';
									}
									$options .= '</ul>';
									$category .= $options;
									$category .= ' 
							</div>
						</div>
					</div>
				</div>';
			} else {
				$category .= '<div class="row clearfix"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div>';
			}


		} else if ($indicator_category == "Impact") {
			$calculation .= '<li class="list-group-item"><strong>Calculation Method: </strong>' . $calc_method . ' </li> ';
			$direction .= '<li class="list-group-item"><strong>Indicator Direction: </strong>' . $ind_dir . ' </li> ';
			$data = '';
			$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND  category='1' ORDER BY id");
			$query_measurement_variables->execute();
			$row_measurement_variables = $query_measurement_variables->fetch();

			$category .= '  
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">   
							<div class="body">
								<div class="header"> 
									<li class="list-group-item list-group-item list-group-item-action active">Measurement Variable/s</li>
								</div>';
								$data .= '
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<ul>';
										if ($calculationmethod == 1) {
											$variable = $row_measurement_variables['measurement_variable'];
											$data .=
												' <li class="list-group-item"><strong>Summation Measurement variables: </strong>' . $variable . ' </li> ';
										} else if ($calculationmethod == 2) {
											do {
												$variable = $row_measurement_variables['measurement_variable'];
												$type = $row_measurement_variables['type'];

												if ($type == 'n') {
													$data .=
														'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li> ';
												} else if ($type == 'd') {
													$data .=
														'<li class="list-group-item"><strong>Denominator Measurement variables: </strong>' . $variable . ' </li> ';
												}
											} while ($row_measurement_variables = $query_measurement_variables->fetch());
										} else if ($calculationmethod == 3) {
											$variable = $row_measurement_variables['measurement_variable'];
											$data .=
												'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li>';
										}
										$data .= "
									</ul>
								</div>";
									$category .= $data;
									if ($dissagragated == 1) {
										$category .= '  
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="header"> 
												<li class="list-group-item list-group-item list-group-item-action active">Disaggregations </li>
											</div>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
														<thead>
															<tr>
																<th width="10%">#</th>
																<th width="30%">Dissagragation Type </th>
																<th width="30%">Parent</th>
																<th width="30%">Disaggregations</th>  
															</tr>
														</thead>
														<tbody id="impact_table_body">';
															$query_rsIndType = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid= '$indid' and type=1 ORDER BY id");
															$query_rsIndType->execute();
															$row_rsIndType = $query_rsIndType->fetch();
															$indIndTypecount = $query_rsIndType->rowCount();

															if ($indIndTypecount > 0) {
																$rowno = 0;
																do {
																	$rowno++;

																	$impact_dissagragated_type = $row_rsIndType['disaggregation_type'];
																	$impact_dissagragated_parent = $row_rsIndType['parent'];

																	$query_rsInd_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$impact_dissagragated_type'");
																	$query_rsInd_type->execute();
																	$row_rsInd_type = $query_rsInd_type->fetch();
																	$total_rsp = $query_rsInd_type->rowCount();
																	$dissagragated_type = ($total_rsp > 0) ? $row_rsInd_type['category'] : '';

																	$query_rsInd_parent = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$impact_dissagragated_parent'");
																	$query_rsInd_parent->execute();
																	$row_rsInd_parent = $query_rsInd_parent->fetch();
																	$total_rs = $query_rsInd_parent->rowCount();
																	$parent = ($total_rs > 0) ? $row_rsInd_parent['category'] : '';


																	$query_rsInd_type_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregations WHERE indicatorid=:indicatorid AND disaggregation_type=:disaggregation_type");
																	$query_rsInd_type_diss->execute(array(":indicatorid" => $indid, ":disaggregation_type" => $impact_dissagragated_type));
																	$row_rsInd_type_diss = $query_rsInd_type_diss->fetch();
																	$data_diss = [];

																	do {
																		$data_diss[] = $row_rsInd_type_diss['disaggregation'];
																	} while ($row_rsInd_type_diss = $query_rsInd_type_diss->fetch());


																	$category .=
																		' <tr id="">
																														<td>' . $rowno . '</td> 
																														<td> 
																															' . $dissagragated_type . '
																														</td> 
																														<td>
																															' . $parent . '
																														</td> 
																														<td>
																														' . implode(",", $data_diss) . '
																														</td> 
																													</tr>';
																} while ($row_rsIndType = $query_rsIndType->fetch());
															}
															$category .=
														'</tbody>
													</table>
												</div>
											</div> 		
										</div>';
									} else {
										$category .= '<div class="row clearfix"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div>';
									}
									$category .= ' 
							</div>
						</div>
					</div>
				</div>';
		} else if ($indicator_category == "Outcome") {
			$calculation .= '<li class="list-group-item"><strong>Calculation Method: </strong>' . $calc_method . ' </li> ';
			$direction .= '<li class="list-group-item"><strong>Indicator Direction: </strong>' . $ind_dir . ' </li> ';
			$category .= '  
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">   
							<div class="body">
								<div class="header"> 
									<li class="list-group-item list-group-item list-group-item-action active"> Direct Measurement Variable/s</li>
								</div>';
									$data = '
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<ul>';
										$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND  category='2' ORDER BY id");
										$query_measurement_variables->execute();
										$row_measurement_variables = $query_measurement_variables->fetch();

										if ($calculationmethod == 1) {
											$variable = $row_measurement_variables['measurement_variable'];
											$data .=
												' <li class="list-group-item"><strong>Summation Measurement variables: </strong>' . $variable . ' </li> ';
										} else if ($calculationmethod == 2) {
											do {
												$variable = $row_measurement_variables['measurement_variable'];
												$type = $row_measurement_variables['type'];

												if ($type == 'n') {
													$data .=
														'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li> ';
												} else if ($type == 'd') {
													$data .=
														'<li class="list-group-item"><strong>Denominator Measurement variables: </strong>' . $variable . ' </li> ';
												}
											} while ($row_measurement_variables = $query_measurement_variables->fetch());
										} else if ($calculationmethod == 3) {
											$variable = $row_measurement_variables['measurement_variable'];
											$data .=
												'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li>';
										}
										$data .= "
																</ul>
															</div>";
										$category .= $data;


										$query_rsInd_Direct_Outcome_Type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid= '$indid' and type=2 ORDER BY id");
										$query_rsInd_Direct_Outcome_Type->execute();
										$row_rsInd_Direct_Outcome_Type = $query_rsInd_Direct_Outcome_Type->fetch();
										$indInd_Direct_Outcome_Typecount = $query_rsInd_Direct_Outcome_Type->rowCount();
										if ($indInd_Direct_Outcome_Typecount > 0) {
											$category .= '
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="header"> 
														<li class="list-group-item list-group-item list-group-item-action active">Direct Disaggregation </li>
													</div>
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
															<thead>
																<tr>
																	<th width="10%">#</th>
																	<th width="30%">Dissagragation Type </th>
																	<th width="30%">Parent</th>
																	<th width="30%">Disaggregations</th>  
																</tr>
															</thead>  
															<tbody id="direct_outcome_table_body">';
																if ($indInd_Direct_Outcome_Typecount > 0) {
																	$rowno = 0;
																	do {
																		$rowno++;
																		$direct_outcome_dissagragated_type = $row_rsInd_Direct_Outcome_Type['disaggregation_type'];
																		$direct_outcome_dissagragated_parent = $row_rsInd_Direct_Outcome_Type['parent'];

																		$query_rsInd_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$direct_outcome_dissagragated_type'");
																		$query_rsInd_type->execute();
																		$row_rsInd_type = $query_rsInd_type->fetch();
																		$dir_outcome_options = $row_rsInd_type['category'];


																		$query_rsInd_parent = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id= '$direct_outcome_dissagragated_parent'");
																		$query_rsInd_parent->execute();
																		$row_rsInd_parent = $query_rsInd_parent->fetch();
																		$dir_parent_options = $row_rsInd_parent['category'];


																		$query_rsInd_type_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregations WHERE indicatorid=:indicatorid AND disaggregation_type=:disaggregation_type");
																		$query_rsInd_type_diss->execute(array(":indicatorid" => $indid, ":disaggregation_type" => $direct_outcome_dissagragated_type));
																		$row_rsInd_type_diss = $query_rsInd_type_diss->fetch();
																		$data_diss = [];

																		do {
																			$data_diss[] = $row_rsInd_type_diss['disaggregation'];
																		} while ($row_rsInd_type_diss = $query_rsInd_type_diss->fetch());

																		$category .= '
																												<tr id="direct' . $rowno . '">
																													<td>' . $rowno . '</td> 
																													<td> 
																													' . $dir_outcome_options . '
																													</td> 
																													<td> 
																														' . $dir_parent_options . '
																													</td> 
																													<td>
																													' . implode(",", $data_diss) . '
																													</td>  
																												</tr>';
																	} while ($row_rsInd_Direct_Outcome_Type = $query_rsInd_Direct_Outcome_Type->fetch());
																}
																$category .= '
															</tbody>
														</table>
													</div>
												</div>
											</div>';
										} else {
											$category .= '
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												
												</div>
											</div>';
										}
								$category .= ' 
							</div>
						</div>
					</div>
				</div>';
		}


		$indicatordetails  = '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">  
					<div class="header"> 
						<li class="list-group-item list-group-item list-group-item-action active">Indicator Name: ' . $indname . ' </li>
					</div>
					<div class="body"> 
						<div class="col-md-6"> 
							<ul class="list-group"> 
								<li class="list-group-item"><strong>Indicator Code: </strong>' . $indcode . ' </li>
								' . $source_of_data . ' 
								' . $calculation . ' 
							</ul>
						</div>
						<div class="col-md-6"> 
							<ul class="list-group">
								<li class="list-group-item"><strong>Measurement Unit: </strong>' . $unit . ' </li> 
								' . $direction . '
							</ul>
						</div>
						<div class="col-md-12">
							<li class="list-group-item list-group-item list-group-item-action active">Indicator Description:</li>
							' . $inddesc . '
						</div>
						' . $category . ' 
					</div>
				</div>
			</div>
		</div>';
		echo json_encode(array('success' => true, "msg" => $indicatordetails));
	} else {
		echo json_encode(array('success' => false, "msg" => "Record Not found!!"));
	}
}
