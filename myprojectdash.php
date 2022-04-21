<?php
$projid =  (isset($_GET['projid'])) ? $_GET['projid'] : header("location:myprojects.php");
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
	try {
		$query_rsMyP =  $db->prepare("SELECT tbl_projects.*, tbl_projects.projchangedstatus AS projchangedstatus, FORMAT(tbl_projects.projcost, 2), tbl_projects.projstartdate AS sdate, tbl_projects.projenddate AS edate FROM tbl_projects WHERE tbl_projects.deleted='0' AND tbl_projects.user_name = '$user_name' AND tbl_projects.projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$total_row_rsMyP = $query_rsMyP->rowCount();

		if ($total_row_rsMyP > 0) {
			$subcounty = $row_rsMyP['projcommunity'];
			$ward = $row_rsMyP['projlga'];
			$location = $row_rsMyP['projstate'];
			// $datafreq = $row_rsMyP['datafrequency'];
			$stdate = $row_rsMyP['projstartdate'];
			$projectStatus = $row_rsMyP['projstatus'];
			$projectprevstatus = $row_rsMyP['projchangedstatus'];
			$projchangedate = $row_rsMyP['date_deleted'];
			$projchangedby = $row_rsMyP['deleted_by'];
			$projcategory = $row_rsMyP['projcategory'];
			$projduration = $row_rsMyP['projduration'];
			// $nxtmonitoringdate = $row_rsMyP["projfirstmonitor"];

			$currentStatus =  $row_rsMyP['projstatus'];

			$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$projid'");
			$query_rsMlsProg->execute();
			$row_rsMlsProg = $query_rsMlsProg->fetch();

			$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

			$percent2 = round($prjprogress, 2);
		}

		$projstage = 10;
		//get the project name 
		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projid = :projid AND projstage = :projstage");
		$query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $projstage));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();

		//get the project name  
		$progid = $row_rsProjects['progid'];
		$projcode = $row_rsProjects['projcode'];
		$projname = $row_rsProjects['projname'];
		$projtype = $row_rsProjects['projtype'];
		$projfscyear = $row_rsProjects['projfscyear'];
		//$projduration = $row_rsProjects['projduration'];

		$projinspection = $row_rsProjects['projinspection'];
		$projins = "";
		if ($projinspection == 0) {
			$projins = "Yes";
		} else if ($projinspection == 0) {
			$projins = "No";
		}

		$projstatement = $row_rsProjects['projstatement'];
		$projsolution = $row_rsProjects['projsolution'];
		$projcase = $row_rsProjects['projcase'];
		// $projfocus = $row_rsProjects['projfocus'];
		$projcommunity = explode(",", $row_rsProjects['projcommunity']);
		$projlga = explode(",", $row_rsProjects['projlga']);
		$projstate = explode(",", $row_rsProjects['projstate']);
		$projlocation = $row_rsProjects['projlocation'];
		$projbigfouragenda = $row_rsProjects['projbigfouragenda'];
		// $projwaypoints = $row_rsProjects['projwaypoints'];
		$user_name = $row_rsProjects['user_name'];
		$dateentered = $row_rsProjects['date_created'];

		$level1  = [];
		for ($i = 0; $i < count($projcommunity); $i++) {
			$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projcommunity[$i]' ");
			$query_rslga->execute();
			$row_rslga = $query_rslga->fetch();
			$level1[] = $row_rslga['state'];
		}


		$level2  = [];
		for ($i = 0; $i < count($projlga); $i++) {
			$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projlga[$i]' ");
			$query_rslga->execute();
			$row_rslga = $query_rslga->fetch();
			$level2[] = $row_rslga['state'];
		}

		$level3  = [];
		for ($i = 0; $i < count($projstate); $i++) {
			$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projstate[$i]' ");
			$query_rslga->execute();
			$row_rslga = $query_rslga->fetch();
			$level3[] = $row_rslga['state'];
		}

		$query_rsProgram = $db->prepare("SELECT progname, syear, years FROM tbl_programs WHERE deleted='0' and progid='$progid'");
		$query_rsProgram->execute();
		$row_rsProgram = $query_rsProgram->fetch();
		$totalRows_rsProgram = $query_rsProgram->rowCount();
		$progname = $row_rsProgram['progname'];
		$syear = $row_rsProgram['syear'];
		$years = $row_rsProgram['years'];

		//get project implementation methods 
		$query_rsProjImplMethod =  $db->prepare("SELECT DISTINCT id, method FROM tbl_project_implementation_method WHERE id='$projcategory'");
		$query_rsProjImplMethod->execute();
		$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
		$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();
		$projimplementationMethod = $row_rsProjImplMethod['method'];

		$query_bigfour =  $db->prepare("SELECT * FROM tbl_big_four_agenda WHERE status = 1 AND  id='$projbigfouragenda ' ");
		$query_bigfour->execute();
		$row_bigfour = $query_bigfour->fetch();
		$BigFourAgenda =  $row_bigfour['agenda'];

		$query_rsUser =  $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid ='$user_name'");
		$query_rsUser->execute();
		$row_rsUser = $query_rsUser->fetch();
		$userName  = $row_rsUser['fullname'];

		$query_rsprojectYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE  id='$projfscyear'");
		$query_rsprojectYear->execute();
		$row_rsprojectYear = $query_rsprojectYear->fetch();
		$projectfscyear = $row_rsprojectYear['year'];
		$projstartYear = $row_rsprojectYear['yr'];


		$durationYears = floor($projduration / 365);
		$remaining  = $projduration % 365;
		if ($remaining > 0) {
			$durationYears = $durationYears  + 1;
		}

		$sdate = $projstartYear . '-07-01';
		$newDate = date('Y-m-d', strtotime($sdate . " + {$projduration} days"));
		$projectendYear = $durationYears + $projstartYear;

		$query_directlinebudget =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_tender_details WHERE projid=:projid");
		$query_directlinebudget->execute(array(":projid" => $projid));
		$directlinebudget = 0;
		while ($row_directlinebudget = $query_directlinebudget->fetch()) {
			$itembudget = $row_directlinebudget['unit_cost'] * $row_directlinebudget['units_no'];
			$directlinebudget = $directlinebudget + $itembudget;
		}
		//$directlinebudget = number_format($mstbudget, 2);
		//var_dump($directlinebudget);

		$query_otherlinebudget =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid=:projid and (cost_type=2 or cost_type=3)");
		$query_otherlinebudget->execute(array(":projid" => $projid));
		$otherlinebudget = 0;
		while ($row_otherlinebudget = $query_otherlinebudget->fetch()) {
			$tskbudget = $row_otherlinebudget['unit_cost'] * $row_otherlinebudget['units_no'];
			$otherlinebudget = $otherlinebudget + $tskbudget;
		}
		//$otherlinebudget = number_format($otherlinebudget, 2);

		$projbudget = number_format($directlinebudget + $otherlinebudget, 2);

		if ($projcategory == 1) {
			$projsdate = date("d M Y", strtotime($row_rsMyP["sdate"]));
			$projedate = date("d M Y", strtotime($row_rsMyP["edate"]));

			$date1 = strtotime($row_rsMyP["sdate"]);
			$date2 = strtotime($row_rsMyP["edate"]);
			$projduration = ($date2 - $date1) / 60 / 60 / 24;
		} else {
			$query_projdates =  $db->prepare("SELECT startdate, enddate FROM  tbl_tenderdetails WHERE projid=:projid");
			$query_projdates->execute(array(":projid" => $projid));
			$row_projdates = $query_projdates->fetch();

			$projsdate = date("d M Y", strtotime($row_projdates['startdate']));
			$projedate = date("d M Y", strtotime($row_projdates['enddate']));

			$date1 = strtotime($row_projdates['startdate']);
			$date2 = strtotime($row_projdates['enddate']);
			$projduration = ($date2 - $date1) / 60 / 60 / 24;
		}
		//--------------------------------------------OUTCOME DETAILS--------------------------------------------------------------------------------//

		$query_projoutcomedetails =  $db->prepare("SELECT d.*, methods FROM tbl_project_expected_outcome_details d inner join tbl_datagatheringmethods g on g.id=d.data_collection_method WHERE projid=:projid");
		$query_projoutcomedetails->execute(array(":projid" => $projid));
		$row_projoutcomedetails = $query_projoutcomedetails->fetch();

		$query_projtype =  $db->prepare("SELECT g.progid FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid = p.progid WHERE p.projid=:projid");
		$query_projtype->execute(array(":projid" => $projid));
		$row_projtype = $query_projtype->fetch();
		$progid = $row_projtype["progid"];

		$query_projoutcome =  $db->prepare("SELECT g.outcome, i.indicator_name AS ocindicator, u.unit as unit FROM tbl_projects g inner join tbl_indicator i ON i.indid = g.outcome_indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.projid=:projid");
		$query_projoutcome->execute(array(":projid" => $projid));

		$row_projoutcome = $query_projoutcome->fetch();
		$projoutcome = $row_projoutcome["outcome"];
		$projoutcomeindicator = $row_projoutcome["ocindicator"];
		$unit = $row_projoutcome["unit"];
		// $projoutcometarget = $row_projoutcomedetails["target"];
		// $projoutcomebaseline = $row_projoutcomedetails["baseline_value"];
		// $projoutcomemethodology = $row_projoutcomedetails["methods"];
		// $projoutcomedatasources = explode(",", $row_projoutcomedetails["data_source"]);
		// $projoutcomeevalfrequency = $row_projoutcomedetails["evaluation_frequency"];

		// $outcomedatasource = [];
		// foreach ($projoutcomedatasources as $projoutcomedatasource) {
		// 	$query_projoutcomedatasource =  $db->prepare("SELECT source FROM tbl_data_source WHERE id=:source");
		// 	$query_projoutcomedatasource->execute(array(":source" => $projoutcomedatasource));
		// 	$row_projoutcomedatasource = $query_projoutcomedatasource->fetch();
		// 	$outcomedatasource[] = $row_projoutcomedatasource["source"];
		// }

		$query_projoutcomerisk =  $db->prepare("SELECT category FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE r.projid=:projid and r.type=2");
		$query_projoutcomerisk->execute(array(":projid" => $projid));
		$projoutcomerisk = [];
		while ($row_projoutcomerisk = $query_projoutcomerisk->fetch()) {
			$projoutcomerisk[] = $row_projoutcomerisk["category"];
		}

		// if (!is_null($row_projoutcomedetails["reporting_timeline"]) || !empty($row_projoutcomedetails["reporting_timeline"]) || $row_projoutcomedetails["reporting_timeline"] != "") {
		// 	$outcomereporting = [];
		// 	$projoutcomereportingtimelines = explode(",", $row_projoutcomedetails["reporting_timeline"]);
		// 	foreach ($projoutcomereportingtimelines as $projoutcomereportingtimeline) {
		// 		$query_projoutcomereportingtimeline =  $db->prepare("SELECT frequency FROM tbl_datacollectionfreq WHERE fqid=:timeline");
		// 		$query_projoutcomereportingtimeline->execute(array(":timeline" => $projimpactreportingtimeline));
		// 		$row_projoutcomereportingtimeline = $query_projoutcomereportingtimeline->fetch();
		// 		$outcomereporting[] = $row_projoutcomereportingtimeline["frequency"];
		// 	}
		// 	$outcomereportingtimeline = implode("; ", $outcomereporting);
		// } else {
		// 	$outcomereportingtimeline = "N/A";
		// }

		//--------------------------------------------OUTPUT DETAILS----------------------------------------------------------------------
		$query_projoutputdetails =  $db->prepare("SELECT d.id, output, i.indicator_name, u.unit, g.indicator FROM tbl_project_details d inner join tbl_progdetails g on g.id=d.outputid inner join tbl_indicator i on i.indid=g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE d.projid=:projid");
		$query_projoutputdetails->execute(array(":projid" => $projid));

		//------------------------------------------------------END OF OUTPUT DETAILS--------------------------------------------------------------------------

		$query_rsSubCounty =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$subcounty'");
		$query_rsSubCounty->execute();
		$row_rsSubCounty = $query_rsSubCounty->fetch();
		$totalRows_rsSubCounty = $query_rsSubCounty->rowCount();

		if ($projectStatus == "On Hold") {
			$query_rsProjStatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = '2' OR statusid = '10'");
			$query_rsProjStatus->execute();
			$row_rsProjStatus = $query_rsProjStatus->fetch();
			$totalRows_rsProjStatus = $query_rsProjStatus->rowCount();
		} else {
			$query_rsProjStatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = '2' OR statusid = '6'");
			$query_rsProjStatus->execute();
			$row_rsProjStatus = $query_rsProjStatus->fetch();
			$totalRows_rsProjStatus = $query_rsProjStatus->rowCount();
		}

		$query_rsWard =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$ward'");
		$query_rsWard->execute();
		$row_rsWard = $query_rsWard->fetch();
		$totalRows_rsWard = $query_rsWard->rowCount();

		$query_rsLocation =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$location'");
		$query_rsLocation->execute();
		$row_rsLocation = $query_rsLocation->fetch();
		$totalRows_rsLocation = $query_rsLocation->rowCount();



		if ($row_rsSubCounty['state'] == "All") {
			$projlocation = $row_rsSubCounty['state'] . ' ' . $level1labelplural . '; ' . $row_rsWard['state'] . ' ' . $level2labelplural . '; ' . $row_rsLocation['state'] . ' ' . $level3labelplural;
		} else {
			$projlocation = $row_rsSubCounty['state'] . ' ' . $level1label . '; ' . $row_rsWard['state'] . ' ' . $level2label . '; ' . $row_rsLocation['state'] . ' ' . $level3label;
		}

		if (isset($_GET['totalRows_rsMyP'])) {
			$totalRows_rsMyP = $_GET['totalRows_rsMyP'];
		} else {
			$totalRows_rsMyP = $query_rsMyP->rowCount();
		}
		// $totalPages_rsMyP = ceil($totalRows_rsMyP / $maxRows_rsMyP) - 1;



		if (isset($_GET['projid'])) {
			$colname_rsPrjid = $_GET['projid'];
		}

		$query_rsMyPrjDet =  $db->prepare("SELECT p.*, o.output AS output, i.indicator_name AS indicator, d.total_target AS target FROM tbl_projects p LEFT JOIN tbl_project_details d ON p.projid = d.projid LEFT JOIN tbl_indicator i ON d.indicator = i.indid  LEFT JOIN tbl_progdetails o ON d.outputid = o.id WHERE p.deleted='0' AND p.projid = :prjid");
		$query_rsMyPrjDet->execute(array(":prjid" => $colname_rsPrjid));
		$row_rsMyPrjDet = $query_rsMyPrjDet->fetch();

		$query_rsMyPrjFund =  $db->prepare("SELECT sourcecategory AS source, FORMAT(amountfunding, 2) AS amount, financier AS funder FROM tbl_myprojfunding WHERE projid = :prjid");
		$query_rsMyPrjFund->execute(array(":prjid" => $colname_rsPrjid));
		$row_rsMyPrjFund = $query_rsMyPrjFund->fetch();
		$totalRows_rsMyPrjFund = $query_rsMyPrjFund->rowCount();

		$projcat = $row_rsMyP["projcategory"];

		if ($projcat == '2') {
			$tenderid = $row_rsMyP["projtender"];
			$contractorid = $row_rsMyP["projcontractor"];

			$query_tenderDetails =  $db->prepare("SELECT D.*, T.type, C.category AS cat FROM tbl_tenderdetails D inner join tbl_tender_type T ON T.id=D.tendertype inner join tbl_tender_category C ON C.id=D.tendercat WHERE td_id = '$tenderid'");
			$query_tenderDetails->execute();
			$tenderDetails = $query_tenderDetails->fetch();
			$tendercount = $query_tenderDetails->rowCount();

			$query_contractor =  $db->prepare("SELECT contractor_name FROM tbl_contractor WHERE contrid = '$contractorid'");
			$query_contractor->execute();
			$contractor = $query_contractor->fetch();
		}

		$queryString_rsMyP = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
			$params = explode("&", $_SERVER['QUERY_STRING']);
			$newParams = array();
			foreach ($params as $param) {
				if (stristr($param, "pageNum_rsMyP") == false && stristr($param, "totalRows_rsMyP") == false) {
					array_push($newParams, $param);
				}
			}
			if (count($newParams) != 0) {
				$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
			}
		}
		$queryString_rsMyP = sprintf("&totalRows_rsMyP=%d%s", $totalRows_rsMyP, $queryString_rsMyP);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">

	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<h4>
						<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
							Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
						</div>
						<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
							<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
								<div class="bar hundred cornflowerblue">
									<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
								</div>
							</div>
						</div>
					</h4>
					<div class="header" style="padding-bottom:0px">
						<div class="button-demo" style="margin-top:-15px">
							<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
							<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
							<a href="myprojectmilestones.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
							<a href="myprojectworkplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Quarterly Targets</a>
							<a href="myprojectfinancialplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
							<a href="myproject-key-stakeholders.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
							<a href="projectissueslist.php?proj=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
							<a href="myprojectfiles.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> PROJECT OVERVIEW &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> PROJECT LOGFRAME &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="header">
										<div style="color:#333; background-color:#EEE; width:100%; height:30px">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
												<tr>
													<td width="100%" height="35" style="padding-left:5px; background-color:#2196F3; color:#FFF">
														<div align="left"><img src="images/projbrief.png" alt="img" /> <strong>Project Overview</strong></div>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="body table-responsive">
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card">
													<div class="header">
														<li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
													</div>
													<div class="body">
														<div class="row clearfix">
															<div class="col-md-12">
																<ul class="list-group">
																	<li class="list-group-item"><strong>Project Code: </strong><?= $projcode ?></li>
																	<li class="list-group-item"><strong>Project Program: </strong><?= $progname ?></li>
																</ul>
															</div>
															<div class="col-md-6">
																<ul class="list-group">
																	<li class="list-group-item"><strong>Project Budget (Ksh): </strong><?php echo $projbudget; ?></li>
																	<li class="list-group-item"><strong>Implementation Method: </strong><?= $projimplementationMethod ?></li>
																	<li class="list-group-item"><strong>Big Four Agenda Category: </strong><?= $BigFourAgenda ?></li>
																</ul>
															</div>
															<div class="col-md-6">
																<ul class="list-group">
																	<li class="list-group-item"><strong>Project Duration: </strong><?= $projduration ?> Days </li>
																	<li class="list-group-item"><strong>Project Start Date: </strong><?php echo $projsdate; ?></li>
																	<li class="list-group-item"><strong>Project End Date: </strong><?php echo $projedate; ?></li>
																</ul>
															</div>
															<div class="col-md-12 table-responsive">
																<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
																	<tr>
																		<th width="27%"><?= $level1label ?></th>
																		<th width="27%"><?= $level2label ?></th>
																		<th width="27%"><?= $level3label ?></th>
																	</tr>
																	<tr>
																		<td>
																			<?php echo implode(",", $level1); ?>
																		</td>
																		<td>
																			<?php echo implode(",", $level2); ?>
																		</td>
																		<td>
																			<?php echo implode(",", $level3); ?>
																		</td>
																	</tr>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="header">
										<div style="color:#333; background-color:#EEE; width:100%; height:30px">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
												<tr>
													<td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
														<div align="left"><img src="images/projbrief.png" alt="img" /> <strong>Project Logical Framework</strong></div>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="body table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr style="background-color:#eaf1fc">
													<th style="width:10%" align="center"><img src="images/status.png" alt="img" /></th>
													<th style="width:30%"><strong>Description</strong></th>
													<th style="width:30%"><strong>Indicator</strong></th>
													<th style="width:30%"><strong>Assumptions/Risks</strong></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><strong>Purpose/Outcome</strong></td>
													<td style="width:30%"><?php echo $projoutcome; ?></td>
													<td style="width:30%"><?php echo $projoutcomeindicator; ?></td>
													<!--<td><?php //echo $projoutcomebaseline." ".$unit 
															?></td>
												<td><?php //echo $projoutcometarget." ".$unit; 
													?></td>
												<td><?php //echo implode("; ", $outcomedatasource); 
													?></td>
												<td><?php //echo $projoutcomemethodology; 
													?></td>
												<td><?php //echo $projoutcomeevalfrequency." Years" 
													?></td>
												<td><?php //echo $outcomereportingtimeline; 
													?></td>-->
													<td style="width:30%"><?php echo implode("; ", $projoutcomerisk) ?></td>
												</tr>
												<tr>
													<td>
														<div class="clearfix m-b-20">
															<strong>Output/s</strong>
														</div>
													</td>
													<td colspan="9">
														<div class="clearfix m-b-20">
															<div class="dd" id="nestable">
																<?php
																$nm = 0;
																while ($row_projoutputdetails = $query_projoutputdetails->fetch()) {
																	$nm++;
																?>
																	<table class="table table-bordered">
																		<tbody>
																			<?php
																			$projoutput = $row_projoutputdetails["output"];
																			$projoutputindicator = $row_projoutputdetails["indicator_name"];
																			$opunit = $row_projoutputdetails["unit"];
																			// $projoutputtarget = $row_projoutputdetails["target"];
																			$projoutputtarget = 0;
																			$projoutputid = $row_projoutputdetails["id"];
																			$projoutputindid = $row_projoutputdetails["indicator"];
																			$projoutputbaseline = 0;

																			$query_projoutputdatasources =  $db->prepare("SELECT data_source, monitoring_frequency, reporting_timeline, methods FROM tbl_project_outputs o inner join tbl_datagatheringmethods g on g.id=o.data_collection_method WHERE o.outputid=:opid");
																			$query_projoutputdatasources->execute(array(":opid" => $projoutputid));
																			$row_projoutputdatasources = $query_projoutputdatasources->fetch();

																			// $projoutputdatasources = explode(",", $row_projoutputdatasources["data_source"]);
																			// $projoutputmonfreqs = explode(",", $row_projoutputdatasources["monitoring_frequency"]);
																			// $projoutputreportings = explode(",", $row_projoutputdatasources["reporting_timeline"]);
																			// $projoutputmethodology = $row_projoutputdatasources["methods"];

																			// $outputdatasource = [];
																			// foreach ($projoutputdatasources as $projoutputdatasource) {
																			// 	$query_projoutputdatasource =  $db->prepare("SELECT source FROM tbl_data_source WHERE id=:source");
																			// 	$query_projoutputdatasource->execute(array(":source" => $projoutputdatasource));
																			// 	$row_projoutputdatasource = $query_projoutputdatasource->fetch();
																			// 	$outputdatasource[] = $row_projoutputdatasource["source"];
																			// }

																			// $outputmonfreq = [];
																			// foreach ($projoutputmonfreqs as $projoutputmonfreq) {
																			// 	$query_projoutputmonfreq =  $db->prepare("SELECT frequency FROM tbl_datacollectionfreq WHERE fqid=:freq");
																			// 	$query_projoutputmonfreq->execute(array(":freq" => $projoutputmonfreq));
																			// 	$row_projoutputmonfreq = $query_projoutputmonfreq->fetch();
																			// 	$outputmonfreq[] = $row_projoutputmonfreq["frequency"];
																			// }

																			// $outputreportingfreq = [];
																			// foreach ($projoutputreportings as $projoutputreporting) {
																			// 	$query_projoutputreportingfreq =  $db->prepare("SELECT frequency FROM tbl_datacollectionfreq WHERE fqid=:freq");
																			// 	$query_projoutputreportingfreq->execute(array(":freq" => $projoutputreporting));
																			// 	$row_projoutputreportingfreq = $query_projoutputreportingfreq->fetch();
																			// 	$outputreportingfreq[] = $row_projoutputreportingfreq["frequency"];
																			// }

																			$query_projoutputrisk =  $db->prepare("SELECT category FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE r.projid=:projid and r.type=3");
																			$query_projoutputrisk->execute(array(":projid" => $projid));
																			$projoutputrisk = [];
																			while ($row_projoutputrisk = $query_projoutputrisk->fetch()) {
																				$projoutputrisk[] = $row_projoutputrisk["category"];
																			}

																			?>
																			<tr>
																				<td style="width:30%"><?php echo $projoutput; ?></td>
																				<td style="width:30%"><?php echo $projoutputindicator; ?></td>
																				<!--<td><?php //echo $projoutputbaseline." ".$opunit 
																						?></td>
																			<td><?php //echo $projoutputtarget." ".$opunit; 
																				?></td>
																			<td><?php //echo implode("; ", $outputdatasource); 
																				?></td>
																			<td><?php //echo $projoutputmethodology; 
																				?></td>
																			<td><?php //echo implode("; ", $outputmonfreq); 
																				?></td>
																			<td><?php //echo implode("; ", $outputreportingfreq); 
																				?></td>-->
																				<td style="width:30%"><?php echo implode("; ", $projoutputrisk) ?></td>
																			</tr>
																		</tbody>
																	</table>
																	<?php
																	$query_rsMSTask =  $db->prepare("SELECT task, tkid, taskbudget, responsible, parenttask, status, progress AS tskprogress, sdate, edate FROM tbl_task WHERE projid = :projid GROUP BY tkid ORDER BY tkid");
																	$query_rsMSTask->execute(array(":projid" => $projid));
																	$totalRows_rsMSTask = $query_rsMSTask->rowCount();

																	if ($totalRows_rsMSTask == 0) {
																		echo '<div style="color:red">NO ACTIVITIES DEFINED FOR THIS OUTPUT!!</div>';
																	} else {
																		$sr = 0;
																	?>
																		<table class="table table-bordered">
																			<thead>
																				<tr style="background-color:#eaf1fc">
																					<th width="10%">Output <?= $nm ?> Activities</th>
																					<th width="45%">Description</th>
																					<th width="10%">Budget (Ksh)</th>
																					<th width="15%">Responsible</th>
																					<th width="10%">Start Date</th>
																					<th width="10%">End Date</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				while ($row_rsMSTask = $query_rsMSTask->fetch()) {

																					$sr++;
																					$taskid = $row_rsMSTask['tkid'];
																					$tskResp = $row_rsMSTask['responsible'];
																					$tskcost = number_format($row_rsMSTask['responsible'], 2);
																					$tskstartdate = date("d M Y", strtotime($row_rsMSTask['sdate']));
																					$tskenddate = date("d M Y", strtotime($row_rsMSTask['edate']));

																					$query_rsTaskResp =  $db->prepare("SELECT T.*, D.designation AS desgn FROM tbl_projteam2 T INNER JOIN tbl_pmdesignation D ON T.designation=D.moid WHERE ptid = :tskResp");
																					$query_rsTaskResp->execute(array(":tskResp" => $tskResp));
																					$row_rsTaskResp = $query_rsTaskResp->fetch();

																					$responsible = $row_rsTaskResp ? $row_rsTaskResp['title'] . ". " . $row_rsTaskResp['fullname'] . " (" . $row_rsTaskResp['desgn'] . ")" : "";

																					$query_taskbudget =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_tender_details WHERE projid=:projid and tasks = :tskid");
																					$query_taskbudget->execute(array(":projid" => $projid, ":tskid" => $taskid));
																					$totaltskcost = 0;
																					while ($row_taskbudget = $query_taskbudget->fetch()) {
																						$tskcost = $row_taskbudget['unit_cost'] * $row_taskbudget['units_no'];
																						$totaltskcost = $totaltskcost + $tskcost;
																					}
																					$totaltskcost = number_format($totaltskcost, 2);
																					$current_date = date("Y-m-d");
																				?>
																					<tr>
																						<td>Task <?= $sr ?></td>
																						<td>
																							<font color="#9C27B0"><?php echo $row_rsMSTask['task']; ?></font>
																						</td>
																						<td> <?= $totaltskcost ?></td>
																						<td><?= $responsible ?></td>
																						<td><?= $tskstartdate ?></td>
																						<td><?= $tskenddate ?></td>
																					</tr>
																				<?php
																				}
																				?>
																			</tbody>
																		</table>
																	<?php
																	}
																	?>
																<?php
																}
																?>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<!-- Jquery Nestable -->
<script src="assets/projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
<script src="assets/projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>