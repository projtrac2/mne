<?php 
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

require('../../functions/programs.php');
require('../../functions/department.php');
require('../../functions/funding.php');
include_once("../../includes/system-labels.php");
require('../../functions/strategicplan.php');

$valid['success'] = array('success' => false, 'messages' => array());


// get department 
if (isset($_POST['getdept'])) {
	$dept = $_POST['getdept'];
	$query_dep = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$dept' AND deleted='0'");
	$query_dep->execute();
	$data = '<select name="department" id="department" class=" form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%"  required><option value="">Select ' . $departmentlabel . '</option>';
	while ($row = $query_dep->fetch()) {
		$data .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
	}
	$data .= '</select>';
	echo $data;
}

if (isset($_POST['get_strategicplan'])) {
	$query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
	$query_rsStrategicPlan->execute();
	$row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
	$totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
	$syear = $row_rsStrategicPlan['starting_year'];
	$spid = $row_rsStrategicPlan['id'];
	$years = $row_rsStrategicPlan['years'];
	$endyear = ($syear + $years) - 1;  // strategic plan end year

	$query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area  k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
	$query_rsStrategicObjectives->execute();
	$row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch();
	$totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();

	$strategic_objective_options = '';
	if ($totalRows_rsStrategicObjectives > 0) {
		do {
			$strategic_objective_options .= '<option value="' . $row_rsStrategicObjectives['id'] . '">' . $row_rsStrategicObjectives['objective'] . '</option>';
		} while ($row_rsStrategicObjectives = $query_rsStrategicObjectives->fetch());
	}

	$data =
		'
		<div class="col-md-12" id="strat_div">
			<label>Strategic Objective *:</label>
			<div class="form-line">
				<select name="ind_strategic_objective" id="ind_strategic_objective" class="form-control show-tick" onchange="get_strategy()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
					<option value="">.... Select Strategic Objective ....</option>';
					$data .= $strategic_objective_options;
					$data .= '
				</select>
			</div>
		</div>
		<div class="col-md-12">
			<label>Strategy *:</label>
			<div class="form-line">
					<select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<option value="">.... Select Strategy from list ....</option>
					</select>
			</div>
		</div>
		<div class="col-md-4">
			<label for="syear">Strategic Plan Start Year *:</label>
			<div class="form-line">
					<input type="hidden" name="splan" value="'.$spid.'">
					<input type="text" name="stratplanstartYear" id="stratplanstartYear" value="' . $syear . '" class="form-control" disabled>
			</div>
		</div>
		<div class="col-md-4">
			<label for="syear">Strategic Plan End Year *:</label>
			<div class="form-line">
					<input type="text" name="stratplanendyear" id="stratplanendyear" value="' . $endyear . '" class="form-control" disabled>
			</div>
		</div>
		<div class="col-md-4">
			<label for="syear">Strategic Plan Years *:</label>
			<div class="form-line">
					<input type="text" name="stratplanyears" id="stratplanyears" value="' . $years . '" class="form-control" disabled>
			</div>
		</div>
		';

	echo $data;
}

if (isset($_POST['get_strategy'])) {
	$objid = $_POST['objid'];
	$strategies = get_strategic_objectives_strategy($objid);
	$strategy_options = '<option value="">.... Select Strategy from list ....</option>';
	if ($strategies) {
		foreach ($strategies as $strategy) {
			$strategy_options .= '<option value="' . $strategy['id'] . '">' . $strategy['strategy'] . '</option>';
		}
	}
	echo $strategy_options;
}

if (isset($_POST['get_program_years'])) {
	$program_year_options = "";
	$indipendent = $_POST['indipendent'];

	$end = "";
	if ($indipendent) {
		$query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
		$query_rsStrategicPlan->execute();
		$row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
		$totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
		$syear = $row_rsStrategicPlan['starting_year'];
		$years = $row_rsStrategicPlan['years'];
		$endyear = ($syear + $years) - 1;  // strategic plan end year
		$end = "AND yr <= '$endyear'";
	}

	$month =  date('m');
	$currentYear = ($month < 7) ? date("Y") - 1 : date("Y");
	$query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE yr >='$currentYear' $end");
	$query_rsYear->execute();
	$row_rsYear = $query_rsYear->fetch();
	$totalRows_rsYear = $query_rsYear->rowCount();

	if ($totalRows_rsYear > 0) {
		do {
			$program_year_options .= '<option value="' . $row_rsYear['yr'] . '">' . $row_rsYear['year'] . '</option>';
		} while ($row_rsYear = $query_rsYear->fetch());
	}

	$data = '
	<div class="col-md-6">
			<label class="control-label">Program Start Year *:</label>
			<div class="form-line">
					<select name="syear" id="starting_year" onchange="program_workplan_header()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
						<option value="">.... Select Year from list ....</option>';
	$data .= $program_year_options;
	$data .=
		'</select>
			</div>
		</div>
		<div class="col-md-6">
			<label for="years">Program Duration In Years *:</label>
			<div class="form-line">
					<input type="number" name="years" id="program_duration" onkeyup="program_workplan_header()" onchange="program_workplan_header()" placeholder="Program Duration" class="form-control" required>
					<span id="info1" style="color:red"></span>
			</div>
		</div>';
	echo $data;
}


if (isset($_POST['getUnits'])) {
	$getUnits = $_POST['getUnits'];
	$indicator = $getUnits;
	$program_starting_year = $_POST['program_starting_year'];
	$years = $_POST['years'];
	$program_type = $_POST['program_type'];

	$query_Indicator = $db->prepare("SELECT unit, indicator_name FROM tbl_indicator i INNER JOIN tbl_measurement_units u ON u.id = i.indicator_unit WHERE i.indid = :indid");
	$query_Indicator->execute(array(":indid" => $getUnits));
	$row = $query_Indicator->fetch();
	$total = $query_Indicator->rowCount();

	$targets = array();
	if ($program_type == 1) {
		for ($i = 0; $i < $years; $i++) {
			$query_program = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails WHERE indicator ='$indicator' AND year='$program_starting_year'");
			$query_program->execute();
			$row_program = $query_program->fetch();
			$count_program = $query_program->rowCount();
			$program_target = ($count_program > 0) ? $row_program['target'] : 0;

			$query_strategicplan = $db->prepare("SELECT year_target FROM tbl_strategic_plan_op_indicator_targets WHERE op_indicator_id ='$indicator' AND year='$program_starting_year' ");
			$query_strategicplan->execute();
			$row_strategicplan = $query_strategicplan->fetch();
			$count_strategicplan = $query_strategicplan->rowCount();
			$strategicplan_targets = ($count_strategicplan > 0) ? $row_strategicplan['year_target'] : 0;
			$targets[] = $strategicplan_targets - $program_target;
			$program_starting_year++;
		}
	}

	// $targets = [1000];

	if ($total > 0) {
		$unit = $row['unit'];
		$indicator_name = $row['indicator_name'];
		echo json_encode(array('success' => true, 'unit' => $unit, 'targets' =>  $targets, 'indicator_name' => $indicator_name));
	} else {
		echo json_encode(array('success' => false));
	}
}

if (isset($_POST["deleteItem"])) {
	$progid = $_POST['itemId'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_programs` WHERE progid=:progid");
	$results = $deleteQuery->execute(array(':progid' => $progid));
	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Deleted";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deletng the record!!";
	}
	echo json_encode($valid);
}

if (isset($_POST['get_financier'])) {
	$funding_types = get_funding_type();
	$input = '';
	if ($funding_types) {
		$input .= '<option value="">Select Funds Source Category</option>';
		foreach ($funding_types as $funding_type) {
			$input .= '<option value="' . $funding_type['id'] . '"> ' . $funding_type['type'] . '</option>';
		}
	} else {
		$input .= '<option value="">No Funding Category Found !!!</option>';
	}
	echo $input;
}



if (isset($_POST['getprogindicator'])) {
	$getindicator = $_POST['getprogindicator'];
	$query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_dept ='$getindicator' AND indicator_category='Output' AND active='1' AND baseline='1'");
	$query_Indicator->execute();
	echo '<option value="">Select Indicator</option>';
	while ($row = $query_Indicator->fetch()) {
		$unit = $row['indicator_unit'];
		
		$query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$unit'");
		$query_opunit->execute();
		$opunit_ro = $query_opunit->fetch();
		$count_opunit = $query_opunit->rowCount();
		$opunit = ($count_opunit > 0) ? $opunit_ro['unit'] : "";

		$indname = $row['indicator_name'];
		echo '<option value="' . $row['indid'] . '"> ' . $opunit . " of " . $indname. '</option>';
	}
}


if (isset($_POST['moreinfo'])) {
	$progid = $_POST['itemId'];
	$program = get_program($progid);
	$description = $program['description'];
	$progkpi = $program['kpi'];
	$progprobstat = $program['problem_statement'];
	$progYears = $program['years'];
	$progStartingYear = $program['syear'];
	$programName = $program['progname'];
	$sectorid = $program['projsector'];
	$projdepts = $program['projdept'];
	$departmentid = $program['projdept'];
	$sectors = get_department($sectorid); // get sector
	$departments = get_department($departmentid); // get department
	$sector = $sectors['sector'];
	$department = $departments['sector'];
	$progendingYear = ($progStartingYear + $progYears) - 1;

	$input = '';
	// program funding 
	$program_funding = get_program_funding_details($progid);
	$strategicPlanView = '';

	if ($progkpi == NULL) {
		$strategicPlanView .= '';
		$colSize = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	} else {
		$query_years = $db->prepare("SELECT * FROM `tbl_strategic_plan_objectives` o INNER JOIN tbl_key_results_area k ON k.id = o.kraid INNER JOIN tbl_strategicplan p ON p.id = k.spid WHERE o.id ='$progkpi' LIMIT 1");
		$query_years->execute();
		$row_years = $query_years->fetch();

		$years = $row_years['years'];
		$startyear = $row_years['starting_year'];
		$endyear = ($startyear + $years) - 1;
		$objective = $row_years['objective'];
		$strategicPlan = $row_years['plan'];
		$spsfinyear = $startyear + 1;
		$spefinyear = $endyear + 1;

		$strategicPlanView .= '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card"> 
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<h5><strong><font color="#9C27B0"> Program Name: </font></strong>' . $programName . '</h5> 
						</div>  
					</div>
					<div class="body" style="margin-top:5px; margin-bottom:5px">
						<div class="row"> 
							<div class="col-md-12"><strong><font color="#9C27B0">Strategic Plan: </font></strong>' . $strategicPlan . '</div>
							<div class="col-md-12"><strong><font color="#9C27B0">Strategic Objective: </font></strong>' . $objective . '</div>
						</div>
						<div class="row"> 
							<div class="col-md-4"><strong><font color="#9C27B0">Strategic Plan Start Year: </font></strong>' . $startyear . '/' . $spsfinyear . '</div>
							<div class="col-md-4"><strong><font color="#9C27B0">Strategic Plan End Year: </font></strong>' . $endyear . '/' . $spefinyear . '</div>
							<div class="col-md-4"><strong><font color="#9C27B0">Strategic Plan Duration: </font></strong>' . $years . ' Years</div>
						</div> 
						<div class="row">  
							<div class="col-md-4"><strong><font color="#9C27B0">' . $ministrylabel . ': </font></strong>' . $sector . ' 
							</div> 
							<div class="col-md-8"><strong><font color="#9C27B0">' . $departmentlabel . ': </font></strong>' . $department . ' 
							</div>
						</div>

						<div class="row">                
							<div class="col-md-12"><strong><font color="#9C27B0">Description: </font></strong>' . $description . '</div>
						</div> 
					</div>
				</div>
			</div>
		</div>';
		$colSize = 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
	}

	$sfinyear = $progStartingYear + 1;
	$endfinyear = $progendingYear + 1;

	if ($progkpi == NULL) {
		$input .= '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<h5><strong><font color="#9C27B0"> Program Name: </font></strong>' . $programName . '</h5> 
						</div>  
					</div>
					<div class="body" style="margin-top:5px; margin-bottom:5px"> 
						<div class="row"> 
							<div class="col-md-4"><strong><font color="#9C27B0">Program Start Year: </font></strong>' . $progStartingYear . '/' . $sfinyear . '</div>
							<div class="col-md-4"><strong><font color="#9C27B0">Program End Year: </font></strong>' . $progendingYear . '/' . $endfinyear . '</div>
							<div class="col-md-4"><strong><font color="#9C27B0">Program Duration: </font></strong>' . $progYears . ' Years</div>
						</div> 
						<div class="row">  
							<div class="col-md-4"><strong><font color="#9C27B0">' . $ministrylabel . ': </font></strong>' . $sector . ' 
							</div> 
							<div class="col-md-8"><strong><font color="#9C27B0">' . $departmentlabel . ': </font></strong>' . $department . ' 
							</div>
						</div>

						<div class="row">                
							<div class="col-md-12"><strong><font color="#9C27B0">Description: </font></strong>' . $description . '</div>
						</div> 
					</div>
				</div>
			</div>
		</div>';
	} else {
		$input .= '' . $strategicPlanView;
	}

	$input .= '
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card"> 
                <div class="header">
                    <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                        <h5><b><font color="#9C27B0">Program Output Details</font></b></h5>
                    </div>  
                </div>
                <div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="funding_table">
							<thead>
								<tr>
									<th rowspan="2">Output</th>
									<th rowspan="2">Indicator</th> ';
	$dispyear  = $progStartingYear;
	for ($j = 0; $j < $progYears; $j++) {
		$dispyear++;
		$input .= ' <th colspan="2"> ' . $progStartingYear . '/' . $dispyear . '</th>';
		$progStartingYear++;
	}
	$input .= ' 
								</tr>
								<tr>';
	for ($j = 0; $j < $progYears; $j++) {
		$input .= ' <th>Target</th>
										<th>Budget (ksh)</th>';
	}
	$input .= ' 
								</tr>
							</thead> 
							<tbody id="output_table" >';

	$query_outputIndicator = $db->prepare(" SELECT  g.indicator FROM tbl_progdetails g 
								INNER JOIN tbl_indicator i ON i.indid = g.indicator 
								inner join tbl_measurement_units u on u.id=i.indicator_unit 
								WHERE g.progid = '$progid' GROUP BY g.indicator");
	$query_outputIndicator->execute();
	$row_outputIndicator = $query_outputIndicator->fetch();
	$total_outputIndicator = $query_outputIndicator->rowCount();

	do {
		$progsyear = $program['syear'];
		$indicator = $row_outputIndicator['indicator'];

		$query_outputIndicators = $db->prepare(" SELECT  g.indicator, g.output, i.indicator_name, i.indid, unit FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = '$progid' and g.indicator='$indicator' ");
		$query_outputIndicators->execute();
		$row_outputIndicators = $query_outputIndicators->fetch();
		$total_outputIndicators = $query_outputIndicators->rowCount();

		$output = $row_outputIndicators['output'];
		$indname = $row_outputIndicators['indicator_name'];
		$indunit = $row_outputIndicators['unit'];
		$input .= '<tr>
										<td>' . $output . '</td>
										<td>' . $indname . '</td> ';

		for ($i = 0; $i < $progYears; $i++) {
			$query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = '$progid' and year = '$progsyear' and indicator = '$indicator' ");
			$query_progdetails->execute();
			$row_progdeatils = $query_progdetails->fetch();
			$total_progdetails = $query_progdetails->rowCount();

			do {
				$target =  number_format($row_progdeatils['target']);
				$budget =  number_format($row_progdeatils['budget'], 2);;
				$input .= ' <td>' . $target . ' ' . $indunit . '</td>
												<td>' . $budget . '</td>';
			} while ($row_progdeatils = $query_progdetails->fetch());

			$progsyear++;
		}
		$input .= '</tr>';
	} while ($row_outputIndicator = $query_outputIndicator->fetch());

	$input .= '    
							</tbody>
						</table> 
					</div>																				
                </div>
            </div>
        </div>
    </div>';

	$input .= '
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card"> 
				<div class="header">
					<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
					   <h5><b><font color="#9C27B0">Program Fund Sources</font></b></h5> 
					</div>  
				</div>
				<div class="body">
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="funding_table" style="width:100%">
						<thead>
							<tr class="bg-orange">
								<th width="5%">#</th>
								<th width="45%">Funds Source Category</th>
								<th width="50%">Amount (Ksh)</th>
							</tr>
						</thead>
						<tbody id="funding_table_body" >';
	$nmb = 0;
	if ($program_funding) {
		foreach ($program_funding as $funding) {
			$nmb++;
			$sourcecategory = $funding['type'];
			$amountfunding = number_format($funding['amountfunding'], 2);
			$input .= '   <tr>
                                        <td width="5%">' . $nmb . '</td>
                                        <td width="55%">' . ucfirst($sourcecategory) . '</td>
                                        <td width="40%">' . $amountfunding . '</td>
                                    </tr>';
		}
	}
	$input .= ' 
                        </tbody>
					</table> 
				</div>
			</div>
		</div>
	</div>';

	echo $input;
}
