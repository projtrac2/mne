<?php


include_once "controller.php";
$itemId = $_POST['itemId'];
$query_item = $db->prepare("SELECT * FROM tbl_programs WHERE progid = '$itemId'");
$query_item->execute();
$row_item = $query_item->fetch();
$rows_count = $query_item->rowCount();
$input = '';

if ($rows_count > 0) {
	$description = $row_item['description'];
	$progkpi = $row_item['strategic_obj'];
	$progprobstat = $row_item['problem_statement'];
	$progYears = $row_item['years'];
	$progStartingYear = $row_item['syear'];
	$programName = $row_item['progname'];
	$projdepts = $row_item['projdept'];
	$progendingYear = ($progStartingYear + $progYears) - 1;
	$program_start_year = $progStartingYear;


	$strategicPlanView = '';

	if ($progkpi == 0) {
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

		//get sector 
		$sectorid = $row_item['projsector'];
		$query_rsSector = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and stid='$sectorid'");
		$query_rsSector->execute();
		$row_rsSector = $query_rsSector->fetch();
		$sector = $row_rsSector['sector'];

		//get department 
		$deptid = $row_item['projdept'];
		$query_dept = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and stid='$deptid'");
		$query_dept->execute();
		$row_dept = $query_dept->fetch();
		$dept = $row_dept['sector'];


		$strategicPlanView .= '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card"> 
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<h5><strong><font color="#9C27B0"> Program Name: </font></strong>' . $programName  . "  " . $progkpi . '</h5> 
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
							<div class="col-md-4"><strong><font color="#9C27B0">Strategic Plan Duration: </font></strong>' . $years . ' Year(s)</div>
						</div> 
						<div class="row">  
							<div class="col-md-4"><strong><font color="#9C27B0">' . $ministrylabel . ': </font></strong>' . $sector . ' 
							</div> 
							<div class="col-md-8"><strong><font color="#9C27B0">' . $departmentlabel . ': </font></strong>' . $dept . ' 
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



	//get sector 
	$sectorid = $row_item['projsector'];
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0' and stid='$sectorid' ");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();
	$sector = $totalRows_rsSector > 0 ? $row_rsSector['sector'] : "";

	//get department 
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and stid='$projdepts' ");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();
	$projdept = $row_rsSector['sector'];
	$sfinyear = $progStartingYear + 1;
	$endfinyear = $progendingYear + 1;

	if ($progkpi == 0) {
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
							<div class="col-md-4"><strong><font color="#9C27B0">Program Duration: </font></strong>' . $progYears . ' Year(s)</div>
						</div> 
						<div class="row">  
							<div class="col-md-4"><strong><font color="#9C27B0">' . $ministrylabel . ': </font></strong>' . $sector . ' 
							</div> 
							<div class="col-md-8"><strong><font color="#9C27B0">' . $departmentlabel . ': </font></strong>' . $projdept . ' 
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
		$input .= $strategicPlanView;
	}

	//get outputs and indicators
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
		$input .= ' <th colspan="2" style="color:#001440"> ' . $progStartingYear . '/' . $dispyear . '</th>';
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
								WHERE g.progid = '$itemId' GROUP BY g.indicator");
	$query_outputIndicator->execute();
	$row_outputIndicator = $query_outputIndicator->fetch();
	$total_outputIndicator = $query_outputIndicator->rowCount();

	do {
		$progsyear = $row_item['syear'];
		$indicator = $row_outputIndicator['indicator'];
		$query_outputIndicators = $db->prepare(" SELECT  g.indicator, g.output, i.indicator_name, i.indid, unit FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = '$itemId' and g.indicator='$indicator' ");
		$query_outputIndicators->execute();
		$row_outputIndicators = $query_outputIndicators->fetch();
		$total_outputIndicators = $query_outputIndicators->rowCount();

		$output = $row_outputIndicators['output'];
		$indname = $row_outputIndicators['indicator_name'];
		$indunit = $row_outputIndicators['unit'];
		$input .= '<tr>
										<td>' . $output . '</td>
										<td>' . $indunit . " of " . $indname . '</td> ';

		for ($i = 0; $i < $progYears; $i++) {
			$query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = '$itemId' and year = '$progsyear' and indicator = '$indicator' ");
			$query_progdetails->execute();
			$row_progdeatils = $query_progdetails->fetch();
			$total_progdetails = $query_progdetails->rowCount();
			do {
				$target =  number_format($row_progdeatils['target']);
				$budget =  number_format($row_progdeatils['budget'], 2);;
				$input .= ' <td>' . $target . '</td>
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
	$query_targetsdetails = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid ='$itemId'");
	$query_targetsdetails->execute();
	$total_approved_target = $query_targetsdetails->rowCount();


	$query_budgetsdetails = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$itemId' ");
	$query_budgetsdetails->execute();
	$row_budgetsdetails = $query_budgetsdetails->fetch();
	$total_approved_budget = $query_budgetsdetails->rowCount();

	if ($total_approved_budget > 0) {
		$input .= '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card"> 
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<h5><b><font color="#9C27B0">Program Outputs Quarterly Plan</font></b></h5>
						</div>  
					</div>
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="funding_table">
								<thead>
									<tr >
										<th rowspan="2" align="center">Output</th>';
		$dispyear  = $program_start_year;
		for ($j = 0; $j < $progYears; $j++) {
			$dispyear++;
			$input .= ' <th colspan="5" style="color:#001440"> ' . $program_start_year . '/' . $dispyear . '</th>';
			$program_start_year++;
		}
		$input .= ' 
									</tr>
									<tr>';
		for ($j = 0; $j < $progYears; $j++) {
			$input .=
				'
											<th>Q1 Target</th> 
											<th>Q2 Target</th> 
											<th>Q3 Target</th> 
											<th>Q4 Target</th>
											<th>Budget</th>
											';
		}
		$input .= ' 
									</tr>
								</thead>
								<tbody id="output_table" >';
		$query_outputIndicator = $db->prepare(" SELECT  g.indicator FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = '$itemId' GROUP BY g.indicator");
		$query_outputIndicator->execute();
		$row_outputIndicator = $query_outputIndicator->fetch();
		$total_outputIndicator = $query_outputIndicator->rowCount();

		do {
			$progsyear = $row_item['syear'];
			$indicator = $row_outputIndicator['indicator'];
			$query_outputIndicators = $db->prepare(" SELECT g.id,  g.indicator, g.output, i.indicator_name, i.indid, unit FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = '$itemId' and g.indicator='$indicator' ");
			$query_outputIndicators->execute();
			$row_outputIndicators = $query_outputIndicators->fetch();
			$total_outputIndicators = $query_outputIndicators->rowCount();

			$output = $row_outputIndicators['output'];
			$outputid = $row_outputIndicators['id'];
			$input .=
				'<tr>
											<td>' . $output . '</td>';

			for ($i = 0; $i < $progYears; $i++) {
				$query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = '$itemId' and year = '$progsyear' and indicator = '$indicator' ");
				$query_progdetails->execute();
				$row_progdeatils = $query_progdetails->fetch();
				$total_progdetails = $query_progdetails->rowCount();

				$query_targetdetails = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid ='$itemId' AND year='$progsyear' AND opid='$outputid'");
				$query_targetdetails->execute();
				$row_targetdetails = $query_targetdetails->fetch();
				$count_row_targetdetails = $query_targetdetails->rowCount();

				$targetQ1 = $targetQ2 = $targetQ3 = $targetQ4 = "N/A";
				if ($count_row_targetdetails > 0) {
					$targetQ1 = number_format($row_targetdetails['Q1'], 2);
					$targetQ2 = number_format($row_targetdetails['Q2'], 2);
					$targetQ3 = number_format($row_targetdetails['Q3'], 2);
					$targetQ4 = number_format($row_targetdetails['Q4'], 2);
				}

				$query_budgetdetails = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$itemId' AND finyear='$progsyear' AND opid='$outputid'");
				$query_budgetdetails->execute();
				$row_budgetdetails = $query_budgetdetails->fetch();
				$count_budgetdetails = $query_budgetdetails->rowCount();
				$budget = $count_budgetdetails > 0 ? number_format($row_budgetdetails['budget'], 2) : number_format(0, 2);
				do {
					$target =  number_format($row_progdeatils['target']);
					$input .=
						'<td>' . $targetQ1 . '</td> 
													<td>' . $targetQ2 . '</td> 
													<td>' . $targetQ3 . '</td> 
													<td>' . $targetQ4 . '</td>
													<td>' . $budget  . '</td>';
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
	}




	//get project funding details 
	$query_funding = $db->prepare("SELECT * FROM tbl_myprogfunding f inner join tbl_funding_type t on t.id=f.sourcecategory WHERE progid = '$itemId'");
	$query_funding->execute();
	$rows_funding = $query_funding->fetch();
	$totalrows_funding = $query_funding->rowCount();
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
					<table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
						<thead>
							<tr>
								<th width="5%">#</th>
								<th width="45%">Funds Source Category</th>
								<th width="50%">Amount (Ksh)</th>
							</tr>
						</thead>
						<tbody id="funding_table_body" >';
	$nmb = 0;
	do {
		$nmb++;
		$sourcecategory = $rows_funding['type'];
		//$sourceid = $rows_funding['sourceid'];
		$amountfunding = number_format($rows_funding['amountfunding'], 2);
		//$currency = $rows_funding['currency'];

		/* //get currency
								$query_rsDnCurrency = $db->prepare("SELECT * FROM tbl_currency WHERE active='1' and  id='$currency' ");
								$query_rsDnCurrency->execute();
								$row_rsDnCurrency = $query_rsDnCurrency->fetch();
								$totalRows_rsDnCurrency = $query_rsDnCurrency->rowCount();
								$currency = $row_rsDnCurrency['currency'];

								$rate = $rows_funding['rate'];
								$source = "";

								if ($sourcecategory == "donor") {
									$query_dep = $db->prepare("SELECT * FROM tbl_donors where dnid='$sourceid' ");
									$query_dep->execute();
									$row = $query_dep->fetch();
									$source = $row['donorname'];
								} else if ($sourcecategory == "others") {
									$query_dep = $db->prepare("SELECT id, name FROM tbl_funder where id='$sourceid'");
									$query_dep->execute();
									$row = $query_dep->fetch();
									$source = $row['name'];  //shall give the proper name 
								} */

		$input .= '   <tr>
									<td width="5%">' . $nmb . '</td>
									<td width="55%">' . ucfirst($sourcecategory) . '</td>
									<td width="40%">' . $amountfunding . '</td>
								</tr>';
	} while ($rows_funding = $query_funding->fetch());
	$input .= ' </tbody>
					</table> 
				</div>
			</div>
		</div>
	</div>';
	echo $input;
}
