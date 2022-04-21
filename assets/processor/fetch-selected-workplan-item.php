<?php
include_once "controller.php";

try {
	$projid = $_POST['itemId'];

	$query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid ='$projid' ORDER BY id ASC");
	$query_rsOutput->execute();
	$row_rsOutput = $query_rsOutput->fetch();
	$totalRows_rsOutput = $query_rsOutput->rowCount();

	if ($totalRows_rsOutput > 0) {
		$ME3 = '  
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">  
				<div class="header"> 
					<ul class="list-group"> 
						<li class="list-group-item list-group-item list-group-item-action active">Output Quarterly Targets</li>
					</ul>
				</div> 
				<div class="body">';
		if ($totalRows_rsOutput > 0) {
			$opcounter = 0;
			do {
				$opcounter++;
				$opid = $row_rsOutput['id'];
				$oipid = $row_rsOutput['outputid'];
				$indicatorID = $row_rsOutput['indicator'];

				$query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid=:indid ");
				$query_indicator->execute(array(":indid" => $indicatorID));
				$row_indicator = $query_indicator->fetch();
				$unitid = $row_indicator['indicator_unit'];
				$indname = $row_indicator['indicator_name'];

				$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
				$query_Indicator->execute(array(":unit" => $unitid));
				$row = $query_Indicator->fetch();
				$opunit = $row['unit'];

				$query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid'");
				$query_out->execute();
				$row_out = $query_out->fetch();
				$outputName = $row_out['output'];


				$month =  date('m');
				$financial_year = ($month >= 7 && $month <= 12) ?  date('Y') :  date('Y') - 1;
				$query_rsProjTargets = $db->prepare("SELECT * FROM tbl_workplan_targets WHERE projid='$projid' AND year='$financial_year' AND indid='$indicatorID'");
				$query_rsProjTargets->execute();
				$row_rsProjTargets = $query_rsProjTargets->fetch();
				$total_row_rsProjTargets = $query_rsProjTargets->rowCount();
				$proj_q1 = $proj_q2 = $proj_q3 = $proj_q4 = 0;
				if ($total_row_rsProjTargets > 0) {
					$proj_q1 = $row_rsProjTargets['Q1'];
					$proj_q2 = $row_rsProjTargets['Q2'];
					$proj_q3 = $row_rsProjTargets['Q3'];
					$proj_q4 = $row_rsProjTargets['Q4'];
				}

				$ME3 .= '
				<div class="header"> 
					<ul class="list-group"> 
						<li class="list-group-item"><strong>Output ' . $opcounter . ': ' . $outputName . ' </strong></li>
						<li class="list-group-item"><strong>Indicator: </strong>' . $opunit . " of " . $indname . ' </li>   
					</ul>
				</div> 
				<div class="row clearfix">
					<div class="col-md-12">
						<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
							<thead>
								<tr>
									<th width="">Q1</th>
									<th width="">Q2</th> 
									<th width="">Q3</th> 
									<th width="">Q4</th> 
								</tr>
							</thead>
							<tbody id="funding_table_body" >
							<tr>
									<td width="">' . $proj_q1 . '</td>
									<td width="">' . $proj_q2 . '</td> 
									<td width="">' . $proj_q3 . '</td> 
									<td width="">' . $proj_q4 . '</td> 
								</tr>
							</tbody>
						</table> 
					</div>
					</div> 
				</div>';
			} while ($row_rsOutput = $query_rsOutput->fetch());
		}
		$ME3 .= '
				</div>
			</div>
		</div>
	</div>';
	}

	echo $ME3;
} catch (PDOException $ex) {
	//$result = flashMessage("An error occurred: " .$ex->getMessage());
	print($ex->getMessage());
}
