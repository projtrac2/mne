<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
include_once('projects-functions.php');
if ($permission) {
	try {
		$query_rsMyP = $db->prepare("SELECT *, projstartdate AS sdate, projenddate AS edate FROM tbl_projects WHERE projid = :projid");
		$query_rsMyP->execute(array(":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$count_rsMyP = $query_rsMyP->rowCount();
		$projname = $projstartdate = $projenddate = $implimentation_method = "";
		if ($count_rsMyP > 0) {
			$projstatusid = $row_rsMyP["projstatus"];
			$implimentation_method = $row_rsMyP["projcategory"];
			$projstartdate = $row_rsMyP["sdate"];
			$projenddate = $row_rsMyP["edate"];
			$projdesc = $row_rsMyP["projdesc"];
			$projname = $row_rsMyP["projname"];
			$projcommunity = explode(",", $row_rsMyP['projcommunity']);
			$projlga = explode(",", $row_rsMyP['projlga']);
		}

		$level1  = [];
		for ($i = 0; $i < count($projcommunity); $i++) {
			$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projcommunity[$i]' ");
			$query_rslga->execute();
			$row_rslga = $query_rslga->fetch();
			$level1[] = $row_rslga['state'];
		}


		$level2  = [];
		for ($i = 0; $i < count($projlga); $i++) {
			$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projlga[$i]'");
			$query_rslga->execute();
			$row_rslga = $query_rslga->fetch();
			$level2[] = $row_rslga['state'];
		}

		$query_prog = $db->prepare("SELECT progname FROM tbl_programs g inner join tbl_projects p on p.progid=g.progid WHERE p.projid = :projid");
		$query_prog->execute(array(":projid" => $projid));
		$row_prog = $query_prog->fetch();
		$progname = $row_prog["progname"];

		$query_proj_status = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = :statusid");
		$query_proj_status->execute(array(":statusid" => $projstatusid));
		$row_proj_status = $query_proj_status->fetch();
		if ($row_proj_status) {
			$projstatus = $row_proj_status["statusname"];
		}

		$statusdate = date("Y-m-d H:i:s");

		function get_timelines($projid, $implimentation_method, $date1, $date2)
		{
			global $db;
			$currentdate = date("Y-m-d");
			$query_proj_tasks_dates =  $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_program_of_works WHERE projid = :projid");
			$query_proj_tasks_dates->execute(array(":projid" => $projid));
			$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();
			if (!is_null($row_proj_tasks_dates['projstartdate'])) {
				$date1 = $row_proj_tasks_dates["projstartdate"];
				$date2 = $row_proj_tasks_dates["projenddate"];
			} else {
				if ($implimentation_method == 2) {
					$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
					$query_rsContractDates->execute(array(":projid" => $projid));
					$row_rsContractDates = $query_rsContractDates->fetch();
					$total_rsContractDates = $query_rsContractDates->rowCount();
					if ($total_rsContractDates > 0) {
						$date1 = $row_rsContractDates["startdate"];
						$date2 = $row_rsContractDates["enddate"];
					}
				}
			}


			$durationrate = 0;
			if ($date1 != "" && $date2 != "") {
				$date1 = new DateTime($date1);
				$date2 = new DateTime($date2);
				$date3 = new DateTime($currentdate);
				$duration = $date1->diff($date2);
				$durationtodate = $date1->diff($date3);
				$durationtodates = $durationtodate->format('%a');
				$durationtoenddate = $date3->diff($date2);

				$duration = $duration->format('%a');
				$durationtodate = $durationtodate->format('%a');
				$durationtoenddate = $durationtoenddate->format('%a');

				$durationrate = $durationtodates > 0  && $duration > 0 ? ($durationtodates / $duration) * 100 : 0;
				if ($durationrate > 100) {
					$durationrate = 100;
				}
			}
			return array("duration" => $duration, "durationtodate" => $durationtodate, "durationtoenddate" => $durationtoenddate, "durationrate" => $durationrate);
		}

		function get_budget_utilization($projid, $implimentation_method)
		{
			global $db;
			$query_other_fin_lines =  $db->prepare("SELECT SUM(unit_cost * units_no) as planned_amount FROM tbl_project_direct_cost_plan WHERE projid = :projid");
			if ($implimentation_method == 2) {
				$query_other_fin_lines =  $db->prepare("SELECT SUM(unit_cost * units_no) as planned_amount FROM tbl_project_tender_details WHERE projid = :projid");
			}

			$query_other_fin_lines->execute(array(":projid" => $projid));
			$row_other_fin_lines = $query_other_fin_lines->fetch();

			$planned_amount = !is_null($row_other_fin_lines['planned_amount']) ? $row_other_fin_lines['planned_amount'] : 0;

			$query_consumed =  $db->prepare("SELECT SUM(amount) AS consumed FROM tbl_payments_disbursed WHERE  projid = :projid");
			$query_consumed->execute(array(":projid" => $projid));
			$row_consumed = $query_consumed->fetch();
			$consumed = !is_null($row_consumed['consumed']) ? $row_consumed["consumed"] : 0;

			$rate  = $consumed > 0 && $planned_amount ? ($consumed / $planned_amount) * 100 : 0;
			$projcost = number_format($planned_amount, 2);
			$balance = $planned_amount - $consumed;
			$balance = number_format($balance, 2);
			$consumed = number_format($consumed, 2);
			return array("rate" => $rate, "balance" => $balance, "consumed" => $consumed, "projcost" => $projcost);
		}


		$timelines_arr = get_timelines($projid, $implimentation_method, $projstartdate, $projenddate);
		$project_budget = get_budget_utilization($projid, $implimentation_method);
		$rate = $project_budget['rate'];
		$balance = $project_budget['balance'];
		$consumed = $project_budget['consumed'];
		$projcost = $project_budget['projcost'];

		$duration = $timelines_arr['duration'];
		$durationtodate = $timelines_arr['durationtodate'];
		$durationtoenddate = $timelines_arr['durationtoenddate'];
		$durationrate = $timelines_arr['durationrate'];

		$progress = calculate_project_progress($projid, $implimentation_method);
		$percentage_progress_remaining = 100 - $progress;
		$percentage_duration_consumed = round($durationrate, 2);
		$percentage_duration_remaining = 100 - $percentage_duration_consumed;
		$rate_balance = 100 - $rate;
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<!-- start body  -->
	<link rel="stylesheet" href="css/highcharts.css">
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>

					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Projects Activity Monitoring" class="btn btn-warning pull-right" onclick="location.href='project-output-monitoring-checklist.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; padding-left:-5px">Dashboard</a>
								<a href="myprojectmilestones.php?proj=<?= $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Performance</a>
								<a href="myproject-key-stakeholders.php?proj=<?= $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team</a>
								<a href="my-project-issues.php?proj=<?= $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues</a>
								<a href="myprojectfiles.php?proj=<?= $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?= $progress ?>%
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="row clearfix">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="highcharts-time"></div>
									</figure>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="highcharts-funds"></div>
									</figure>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<li class="list-group-item">
											<label>
												<strong>PROJECT TIMELINES</strong>
											</label>
											<div>
												<strong>Duration Assigned: </strong><?= $duration ?> Days
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Duration Consumed: </strong><?= $durationtodate ?> Days
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Remaining Duration: </strong><?= $durationtoenddate ?> Days
											</div>
										</li>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<li class="list-group-item">
											<label>
												<strong>PROJECT FUNDS</strong>
											</label>
											<div>
												<strong>Budget Allocated: </strong>Ksh.<?php echo $projcost; ?>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Budget Consumed: </strong>Ksh.<?php echo $consumed; ?>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Budget Balance: </strong>Ksh.<?php echo $balance; ?>
											</div>
										</li>
									</div>
								</div>
							</div>

							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

									<fieldset class="scheduler-border row setup-content" id="step-2">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> % Target Achieved</legend>
										<?php
										$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ORDER BY d.id");
										$query_Output->execute(array(":projid" => $projid));
										$total_Output = $query_Output->rowCount();
										$output_details = '';
										if ($total_Output > 0) {
											$counter = 0;
											while ($row_rsOutput = $query_Output->fetch()) {
												$counter++;
												$output_id = $row_rsOutput['id'];
												$indicator_name = $row_rsOutput['indicator_name'];
												$unit_id = $row_rsOutput['indicator_unit'];
												$target = $row_rsOutput['total_target'];

												$query_rsSite_cumulative = $db->prepare("SELECT SUM(achieved) as cummulative FROM tbl_monitoringoutput  WHERE output_id = :output_id AND record_type=1");
												$query_rsSite_cumulative->execute(array(":output_id" => $output_id));
												$rows_rsSite_cumulative = $query_rsSite_cumulative->fetch();
												$total_rsSite_cumulative = $query_rsSite_cumulative->rowCount();
												$cummulative =  $rows_rsSite_cumulative['cummulative'] != null ? $rows_rsSite_cumulative['cummulative'] : 0;

												$percentage_target_achieved = $cummulative > 0 &&  $target > 0 ? $cummulative / $target * 100 : 0;
												$percentage_target_remaininng = 100 - $percentage_target_achieved;
												$remaining = $target - $cummulative;

												$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = $unit_id");
												$query_rsIndUnit->execute();
												$row_rsIndUnit = $query_rsIndUnit->fetch();
												$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
												$unit = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : "";

												// $output_details .= '
												// <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												// 	<li class="list-group-item">
												// 		<div>
												// 			<strong>Target: </strong> ' . $target . "  " . $unit . '
												// 		</div>
												// 		<hr style="border-top: 1px dashed red;">
												// 		<div>
												// 			<strong>Target Achieved: </strong> ' . $cummulative . "  " . $unit . '
												// 		</div>
												// 		<hr style="border-top: 1px dashed red;">
												// 		<div>
												// 			<strong>Remaining Target: </strong> ' . $remaining . "  " . $unit . '
												// 		</div>
												// 	</li> ';
										?>
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<figure class="highcharts-achieved<?= $output_id ?>">
														<div id="highcharts-time<?= $output_id ?>"></div>
													</figure>
													<li class="list-group-item">
														<div>
															<strong>Target: </strong> <?= $target . "  " . $unit ?>
														</div>
														<hr style="border-top: 1px dashed red;">
														<div>
															<strong>Target Achieved: </strong> <?= $cummulative . "  " . $unit ?>
														</div>
														<hr style="border-top: 1px dashed red;">
														<div>
															<strong>Remaining Target: </strong> <?= $remaining . "  " . $unit ?>
														</div>
													</li>
												</div>
												<script>
													$(document).ready(function() {
														Highcharts.chart('highcharts-time<?= $output_id ?>', {
															colors: ['#FF9655', '#FFF263', '#24CBE5', '#64E572', '#50B432', '#ED561B', '#DDDF00', '#6AF9C4'],
															chart: {
																type: 'pie',
																options3d: {
																	enabled: true,
																	alpha: 45,
																	beta: 0
																}
															},
															title: {
																text: 'Output <?= $counter ?>: <?= $indicator_name ?>',
																align: 'left'
															},
															accessibility: {
																point: {
																	valueSuffix: '%'
																}
															},
															tooltip: {
																pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
															},
															plotOptions: {
																pie: {
																	allowPointSelect: true,
																	cursor: 'pointer',
																	depth: 35,
																	dataLabels: {
																		enabled: true,
																		format: '{point.name}'
																	}
																}
															},
															series: [{
																type: 'pie',
																name: 'Percentage',
																data: [{
																		name: 'Achieved',
																		y: <?php echo $percentage_target_achieved ?>,
																		sliced: true,
																		selected: true
																	},
																	['Pending', <?php echo $percentage_target_remaininng ?>]
																],
																colors: [
																	'#50B432',
																	'#7BB4EC'
																]
															}]
														});
													});
												</script>
										<?php
											}
										} else {
											echo "Sorry Project Has no outputs";
										}

										echo $output_details;
										?>
									</fieldset>
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


<script>
	Highcharts.chart('highcharts-time', {
		colors: ['#FF9655', '#FFF263', '#24CBE5', '#64E572', '#50B432', '#ED561B', '#DDDF00', '#6AF9C4'],
		chart: {
			type: 'pie',
			options3d: {
				enabled: true,
				alpha: 45,
				beta: 0
			}
		},
		title: {
			text: 'Project % Time Consumed',
			align: 'left'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				depth: 35,
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'Percentage',
			data: [{
					name: 'Consumed',
					y: <?php echo $percentage_duration_consumed ?>,
					sliced: true,
					selected: true
				},
				['Pending', <?php echo $percentage_duration_remaining ?>]
			],
			colors: [
				'#50B432',
				'#F7A35B'
			]
		}]
	});

	//Highcharts.chart('highcharts-funds', {colors: ['#6AF9C4', '#CB2326', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
	Highcharts.chart('highcharts-funds', {
		chart: {
			type: 'pie',
			options3d: {
				enabled: true,
				alpha: 45,
				beta: 0
			}
		},
		title: {
			text: 'Project % Funds Consumed',
			align: 'left'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				depth: 35,
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'Percentage',
			data: [{
					name: 'Consumed',
					y: <?php echo $rate ?>,
					sliced: true,
					selected: true
				},
				['Pending', <?php echo $rate_balance ?>]
			],
			colors: [
				'#7BB4EC',
				'#90ED7D',
			]
		}]
	});
</script>