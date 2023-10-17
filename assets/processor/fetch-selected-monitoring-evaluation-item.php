<?php
include_once "controller.php";

$projid = $_POST['itemId'];
$ME1 = '';
$ME2 = '';
$ME3 = '';


$query_rsOCProj =  $db->prepare("SELECT * FROM tbl_projects  WHERE projid ='$projid'");
$query_rsOCProj->execute();
$row_rsOCProj = $query_rsOCProj->fetch();
$totalRows_rsOCProj = $query_rsOCProj->rowCount();
$projoutcome =  $row_rsOCProj['outcome'];
$projoutcomeindicator =  $row_rsOCProj['outcome_indicator'];
$projcategory =  $row_rsOCProj['projcategory'];
$responsibleid =  $row_rsOCProj['mne_responsible'];
$mnereportuserids =  explode(",", $row_rsOCProj['mne_report_users']);

$query_responsibleid =  $db->prepare("SELECT tt.title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid ='$responsibleid'");
$query_responsibleid->execute();
$row_responsibleid = $query_responsibleid->fetch();
$ocresponsible = $row_responsibleid['title'].".".$row_responsibleid['fullname'];

$mnereportusers = [];
for ($i  = 0; $i < count($mnereportuserids); $i++) {
	$query_mnereportuserids =  $db->prepare("SELECT designation FROM tbl_pmdesignation WHERE moid ='$mnereportuserids[$i]'");
	$query_mnereportuserids->execute();
	$row_mnereportuserids = $query_mnereportuserids->fetch();
	$mnereportusers[] = $row_mnereportuserids['designation'];
}


$query_rsOutcome =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid ='$projid'");
$query_rsOutcome->execute();
$row_rsOutcome = $query_rsOutcome->fetch();
$totalRows_rsOutcome = $query_rsOutcome->rowCount();

if ($totalRows_rsOutcome > 0) {
    $expected_ocid = $row_rsOutcome['id'];
    $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator`   WHERE indid=:indid ");
    $query_indicator->execute(array(":indid" => $projoutcomeindicator));
    $row_indicator = $query_indicator->fetch();
    $unitid = $row_indicator['indicator_unit'];
    $ocindicator = $row_indicator['indicator_name'];
    $calcid = $row_indicator['indicator_calculation_method'];


    $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
    $query_Indicator->execute(array(":unit" => $unitid));
    $row = $query_Indicator->fetch();
    $ocunit = $row['unit'];

    $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
    $query_Indicator_cal->execute(array(':calcid' => $calcid));
    $row_cal = $query_Indicator_cal->fetch();
    $Outcomecalc_method = $row_cal['method'];

    $OutComeSourceid =   $row_rsOutcome['data_source'];
    $OutComeSource = "Primary";

    if ($OutComeSourceid == 2) {
        $OutComeSource = "Secondary";
    }

    $Outcomeevaluationfreq = $row_rsOutcome['evaluation_frequency'];

    $outcomereporting_timeline =  explode(",", $row_rsOutcome['reporting_timeline']);
    $OutComeTimeline = [];
    for ($i  = 0; $i < count($outcomereporting_timeline); $i++) {
        $query_rsOutcomeTimeline =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid ='$outcomereporting_timeline[$i]'");
        $query_rsOutcomeTimeline->execute();
        $row_rsOutcomeTimeline = $query_rsOutcomeTimeline->fetch();
        $totalRows_rsOutcomeTimeline  = $query_rsOutcomeTimeline->rowCount();

        $OutComeTimeline[] = $totalRows_rsOutcomeTimeline ?  $row_rsOutcomeTimeline['frequency'] : '';
        $counter = $i + 1;
    }

    $query_rsTotalOCbase =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid ='$projid'");
    $query_rsTotalOCbase->execute();
    $row_rsTotalOCbase = $query_rsTotalOCbase->fetch();
	

	$query_projoutcomerisk =  $db->prepare("SELECT category, assumption FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE r.projid=:projid and r.type=2");
	$query_projoutcomerisk->execute(array(":projid" => $projid));

	$projoutcomerisk = [];
	while ($row_projoutcomerisk = $query_projoutcomerisk->fetch()) {
		$projoutcomerisk[] = $row_projoutcomerisk["assumption"];
	}
	

	//--------------------------------------------OUTPUT DETAILS----------------------------------------------------------------------
	$query_projoutputdetails =  $db->prepare("SELECT d.id, d.projid, output, i.indicator_name, u.unit, g.indicator FROM tbl_project_details d inner join tbl_progdetails g on g.id=d.outputid inner join tbl_indicator i on i.indid=g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE d.projid=:projid");
	$query_projoutputdetails->execute(array(":projid" => $projid));

	//------------------------------------------------------END OF OUTPUT DETAILS--------------------------------------------------------------------------


    $ME2 = '
	<div class="header"> 
		<h4 class="list-group-item list-group-item list-group-item-action active">M&E Framework</h4>
	</div> 
	<div class="body table-responsive">   
		<table class="table table-bordered">
			<thead>
				<tr style="background-color:#eaf1fc">
					<th style="width:10%" align="center"><img src="images/status.png" alt="img" /></th>
					<th style="width:20%"><strong>Description</strong></th>
					<th style="width:20%"><strong>Indicator</strong></th>
					<th style="width:10%"><strong>Data Source</strong></th>
					<th style="width:12%"><strong>Frequency</strong></th>
					<th style="width:13%"><strong>Responsible</strong></th>
					<th style="width:15%"><strong>Reporting</strong></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width:10%"><strong>Purpose/Outcome</strong></td>
					<td style="width:20%">'.$projoutcome.'</td>
					<td style="width:20%">'.$ocunit.' of '.$ocindicator.'</td>
					<td style="width:10%">'.$OutComeSource.'</td>
					<td style="width:12%">'.$Outcomeevaluationfreq.' Year/s</td>
					<td style="width:13%">'. $ocresponsible .'</td>
					<td style="width:15%">'. implode("; ", $mnereportusers) .'</td>
				</tr>
				<tr>
					<td>
						<div class="clearfix m-b-20">
							<strong>Output/s</strong>
						</div>
					</td>
					<td colspan="9">';
								$nm = 0;
								while ($row_projoutputdetails = $query_projoutputdetails->fetch()) {
									$nm++;
									$projoutput = $row_projoutputdetails["output"];
									$projoutputindicator = $row_projoutputdetails["indicator_name"];
									$opunit = $row_projoutputdetails["unit"];
									// $projoutputtarget = $row_projoutputdetails["target"];
									$projoutputtarget = 0;
									$projoutputid = $row_projoutputdetails["id"];
									$projoutputindid = $row_projoutputdetails["indicator"];
									$projoutputbaseline = 0;

									$query_projoutputdatasources =  $db->prepare("SELECT data_source, frequency, reporting_timeline, s.title, fullname FROM tbl_project_outputs_mne_details o inner join tbl_datacollectionfreq q on q.fqid=o.monitoring_frequency left join users u on u.userid=o.responsible left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles s on s.id=t.title WHERE o.projid=:projid AND o.outputid=:opid");
									$query_projoutputdatasources->execute(array(":projid" => $projid, ":opid" => $projoutputid));
									$row_projoutputdatasources = $query_projoutputdatasources->fetch();
									$opfrequency = $row_projoutputdatasources["frequency"];
									$fullname = $row_projoutputdatasources["title"].".".$row_projoutputdatasources["fullname"];
									
									$query_projoutputrisk =  $db->prepare("SELECT category FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE r.projid=:projid and r.type=3");
									$query_projoutputrisk->execute(array(":projid" => $projid));
									$projoutputrisk = [];
									while ($row_projoutputrisk = $query_projoutputrisk->fetch()) {
										$projoutputrisk[] = $row_projoutputrisk["category"];
									}
									$ME2 .= '
									<table class="table table-bordered" style="margin:0px">
										<tbody>
											<tr class="bg-blue-grey">
												<td style="width:3%">'.$nm.'</td>
												<td style="width:17%">'. $projoutput .'</td>
												<td style="width:25%">' . $opunit. ' of ' .$projoutputindicator . '</td>
												<td style="width:13%">Primary</td>
												<td style="width:12%">'. $opfrequency .'</td>
												<td style="width:15%">' . $fullname . '</td>
												<td style="width:15%">All M&E Staff</td>
											</tr>
										</tbody>
									</table>';
								}
								$ME2 .= '
					</td>
				</tr>
			</tbody>
		</table>
	</div>';
}


$input =  '
<div class="card">
	' .  $ME2 . ' 
</div>';

echo $input;
