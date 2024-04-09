<?php
try {
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: projects");
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];

$original_projid = $_GET['proj'];
require('includes/head.php');

if ($permission) {
		$back_url = $_SESSION['back_url'];
		$query_rsMyP = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
		$query_rsMyP->execute(array(":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$count_rsMyP = $query_rsMyP->rowCount();
		$projstage = 0;
		$projcat = 0;
		if ($row_rsMyP) {
			$projstatusid = $row_rsMyP["projstatus"];
			$projcat = $row_rsMyP["projcategory"];
			$projstage = $row_rsMyP["projstage"];
			$projdesc = $row_rsMyP["projdesc"];
			$projcommunity = explode(",", $row_rsMyP['projcommunity']);
			$projlga = explode(",", $row_rsMyP['projlga']);
			//$projstate = explode(",", $row_rsMyP['projstate']);
		}
		$projname = $row_rsMyP['projname'];

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

		$currentdate = date("Y-m-d");
		$statusdate = date("Y-m-d H:i:s");

		if ($projcat == '2') {
			$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
			$query_rsContractDates->execute(array(":projid" => $projid));
			$row_rsContractDates = $query_rsContractDates->fetch();
			$totalRows_rsContractDates = $query_rsContractDates->rowCount();

			$query_tenderdetails =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid = :projid");
			$query_tenderdetails->execute(array(":projid" => $projid));

			$totalRows_tenderdetails = $query_tenderdetails->rowCount();
			$tenderamount = 0;

			if ($totalRows_tenderdetails > 0) {
				while ($row_tenderdetails = $query_tenderdetails->fetch()) {
					$unitcost = $row_tenderdetails["unit_cost"];
					$unitsno = $row_tenderdetails["units_no"];
					$itemcost = $unitcost * $unitsno;
					$tenderamount = $tenderamount + $itemcost;
				}
			}

			$othercost = 0;
			$query_other_fin_lines =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid = :projid AND tasks=0");
			$query_other_fin_lines->execute(array(":projid" => $projid));
			while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
				$unitcost = $row_other_fin_lines["unit_cost"];
				$unitsno = $row_other_fin_lines["units_no"];
				$itemcost = $unitcost * $unitsno;
				$othercost = $othercost + $itemcost;
			}

			$consumed = 0;
			$query_consumed =  $db->prepare("SELECT SUM(amount) AS consumed FROM tbl_payment_request_financiers WHERE projid = :projid");
			$query_consumed->execute(array(":projid" => $projid));
			$row_consumed = $query_consumed->fetch();

			if ($row_consumed) {
				$consumed = $row_consumed["consumed"];
			}

			$totalcost = $othercost + $tenderamount;

			if ($totalRows_rsContractDates > 0) {
				$date1 = new DateTime($row_rsContractDates["startdate"]);
				$date2 = new DateTime($row_rsContractDates["enddate"]);
				$date3 = new DateTime($currentdate);

				$duration = $date1->diff($date2);
				$durations = $duration->format('%a');
				$durationtodate = $date1->diff($date3);
				$durationtodates = $durationtodate->format('%a');
				$durationtoenddate = $date3->diff($date2);

				$durationrate = $durationtodates > 0  && $durations > 0 ? ($durationtodates / $durations) * 100 : 0;
				if ($durationrate > 100) {
					$durationrate = 100;
				}

				$duration = $duration->format('%a');
				$durationtodate = $durationtodate->format('%a');
				$durationtoenddate = $durationtoenddate->format('%a');

				$pjstdate = date("d M Y", strtotime($row_rsContractDates["startdate"]));
				$pjendate = date("d M Y", strtotime($row_rsContractDates["enddate"]));
			} else {
				$query_proj_tasks_dates =  $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid WHERE t.projid = :projid");
				$query_proj_tasks_dates->execute(array(":projid" => $projid));
				$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

				$date1 = new DateTime($row_proj_tasks_dates["projstartdate"]);
				$date2 = new DateTime($row_proj_tasks_dates["projenddate"]);
				$date3 = new DateTime($currentdate);

				$duration = $date1->diff($date2);
				$durationtodate = $date1->diff($date3);
				$durationtoenddate = $date3->diff($date2);
				$durations = $duration->format('%a');
				$durationtodates = $durationtodate->format('%a');

				$durationrate = $durationtodates > 0  && $durations > 0 ? ($durationtodates / $durations) * 100 : 0;
				if ($durationrate > 100) {
					$durationrate = 100;
				}

				$duration = $duration->format('%r%a');
				$durationtodate = $durationtodate->format('%r%a');
				$durationtoenddate = $durationtoenddate->format('%r%a');

				$pjstdate = date("d M Y", strtotime($row_proj_tasks_dates["projstartdate"]));
				$pjendate = date("d M Y", strtotime($row_proj_tasks_dates["projenddate"]));
			}

			$rate = $totalcost > 0 ? (($consumed / $totalcost) * 100) : 0;
			$projcost = number_format($totalcost, 2);
			$balance = $totalcost - $consumed;
			$balance = number_format($balance, 2);
			$consumed = number_format($consumed, 2);
		} else {
			$query_proj_tasks_dates =  $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid WHERE t.projid = :projid");
			$query_proj_tasks_dates->execute(array(":projid" => $projid));
			$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

			$date1 = new DateTime($row_proj_tasks_dates["projstartdate"]);
			$date2 = new DateTime($row_proj_tasks_dates["projenddate"]);
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

			$pjstdate = date("d M Y", strtotime($row_proj_tasks_dates["projstartdate"]));
			$pjendate = date("d M Y", strtotime($row_proj_tasks_dates["projenddate"]));

			$othercost = 0;
			$query_other_fin_lines =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid = :projid");
			$query_other_fin_lines->execute(array(":projid" => $projid));
			while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
				$unitcost = $row_other_fin_lines["unit_cost"];
				$unitsno = $row_other_fin_lines["units_no"];
				$itemcost = $unitcost * $unitsno;
				$othercost = $othercost + $itemcost;
			}
			$consumed = 0;
			$query_consumed =  $db->prepare("SELECT SUM(amount) AS consumed FROM tbl_payment_request_financiers WHERE projid = :projid");
			$query_consumed->execute(array(":projid" => $projid));
			$row_consumed = $query_consumed->fetch();
			if ($row_consumed) {
				$consumed = $row_consumed["consumed"];
			}

			$rate = $othercost > 0 ? (($consumed / $othercost) * 100) : 0;
			$projcost = number_format($othercost, 2);
			$balance = $othercost - $consumed;
			$balance = number_format($balance, 2);
			$consumed = number_format($consumed, 2);
		}

		if ($durationtoenddate > $duration) {
			$durationtoenddate = 0;
		}

		$percent = calculate_project_progress($projid, $projcat);
		$percent2 = number_format($percent, 2);
		$percentage_progress_remaining = 100 - $percent;
		$percentage_duration_consumed = round($durationrate, 2);
		$percentage_duration_remaining = 100 - $percentage_duration_consumed;
		$rate_balance = 100 - $rate;

		function get_budget_chart()
		{
			global $db, $projcat, $projid;
			$query_rsSubtasks = $db->prepare("SELECT * FROM tbl_program_of_works w INNER JOIN tbl_task t ON t.tkid = w.subtask_id WHERE t.projid=:projid AND complete=1 ");
			$query_rsSubtasks->execute(array(":projid" => $projid));
			$total_rsSubtasks = $query_rsSubtasks->rowCount();
			$subtasks = $spline_data = [];
			$series_data = [];
			$chart_series = "[{data: [";

			$spline = 0;
			if ($total_rsSubtasks > 0) {
				$counter = 0;
				while ($row_rsSubtasks = $query_rsSubtasks->fetch()) {
					$subtask_id = $row_rsSubtasks['tkid'];
					$site_id = $row_rsSubtasks['site_id'];
					$subtask = $row_rsSubtasks['task'];
					$site = '';

					if ($site_id != 0) {
						$query_Output = $db->prepare("SELECT * FROM tbl_project_sites  WHERE site_id = :site_id ");
						$query_Output->execute(array(":site_id" => $site_id));
						$row_rsOutput = $query_Output->fetch();
						$total_Output = $query_Output->rowCount();
						$site = ($total_Output > 0) ? $row_rsOutput['site'] : '';
					}

					$subtask_name  = $site != '' ? $subtask . '(' . $site . ')' : $subtask;
					$sum_cost = 0;
					$unit_cost = 0;
					if ($projcat == 1) {
						$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND subtask_id=:subtask_id AND site_id=:site_id ");
						$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":subtask_id" => $subtask_id, ":site_id" => $site_id));
						$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
						$sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;


						$query_rsCost =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND subtask_id=:subtask_id AND site_id=:site_id ");
						$query_rsCost->execute(array(":projid" => $projid, ":subtask_id" => $subtask_id, ":site_id" => $site_id));
						$row_rsCost = $query_rsCost->fetch();
						$unit_cost = $row_rsCost ? $row_rsCost['unit_cost'] : 0;
					} else {
						$query_rsProcurement =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE projid =:projid AND subtask_id=:subtask_id AND site_id=:site_id ");
						$query_rsProcurement->execute(array(":projid" => $projid, ":subtask_id" => $subtask_id, ":site_id" => $site_id));
						$row_rsProcurement = $query_rsProcurement->fetch();
						$sum_cost = $row_rsProcurement['sum_cost'] != null ? $row_rsProcurement['sum_cost'] : 0;

						$query_rsCost =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid =:projid AND subtask_id=:subtask_id AND site_id=:site_id ");
						$query_rsCost->execute(array(":projid" => $projid, ":subtask_id" => $subtask_id, ":site_id" => $site_id));
						$row_rsCost = $query_rsCost->fetch();
						$unit_cost = $row_rsCost ? $row_rsCost['unit_cost'] : 0;

						$query_rsOther =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND subtask_id=:subtask_id AND site_id=:site_id  AND cost_type <> 1");
						$query_rsOther->execute(array(":projid" => $projid, ":subtask_id" => $subtask_id, ":site_id" => $site_id));
						$row_rsOther = $query_rsOther->fetch();
						$sum_cost += $row_rsOther['sum_cost'] != null ? $row_rsOther['sum_cost'] : 0;
					}

					$query_rsMilestone_cummulative =  $db->prepare("SELECT SUM(achieved) AS cummulative FROM tbl_project_monitoring_checklist_score WHERE subtask_id=:subtask_id AND site_id=:site_id ");
					$query_rsMilestone_cummulative->execute(array(":subtask_id" => $subtask_id, ':site_id' => $site_id));
					$row_rsMilestone_cummulative = $query_rsMilestone_cummulative->fetch();
					$cummulative = $row_rsMilestone_cummulative['cummulative'] != null ? $row_rsMilestone_cummulative['cummulative'] : 0;
					$subtask_cost = $unit_cost * $cummulative;
					$difference = $sum_cost - $subtask_cost;
					if ($difference !=  0) {
						$counter++;
						$spline += $difference;
						$subtasks[] = $subtask_name;
						$spline_data[] = round($spline / 1000000, 2);
						$series_data[] = $difference;
						$difference = round($difference / 1000000, 2);

						if ($difference > 0) {
							$chart_series .= '{
								name: "' . $subtask_name . '",
								x:' . $counter . ',
								y: ' . $difference . ',
								color: "blue"
							},';
						} else {
							$chart_series .= '{
								x:' . $counter . ',
								name: "' . $subtask_name . '",
								y: ' . $difference . ',
								color: "red"
							},';
						}
					}
				}
			}

			$spline_data  = json_encode($spline_data);
			$series_data  = json_encode($series_data);

			$chart_series .=
				"]},{
					type: 'spline',
					name: 'Average',
					data: $spline_data,
					marker: {
						lineWidth: 2,
						lineColor: Highcharts.getOptions().colors[3],
						fillColor: 'white'
					}
				},]";


			return array("subtasks_data" => json_encode($subtasks), "series_data" => $chart_series);
		}

		$chart_data = get_budget_chart();
		$series_chart = $chart_data['series_data'];
		$subtasks_data = $chart_data['subtasks_data'];
	
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='<?= $back_url ?>'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="" style="margin-top:-15px">
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
								<a href="project-mne-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px"> M&E </a>
								<a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<?php if ($projcat == 2 && $projstage > 4) { ?>
									<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<?php } ?>
								<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
								<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Map</a>
								<a href="project-media.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?= $percent2 ?>%
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>
										Project Description:
									</label>
									<div>
										<li class="list-group-item"><?= $projdesc ?></li>
									</div>
								</div>
								&nbsp;
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>
										Project Program:
									</label>
									<div>
										<li class="list-group-item"><?= $progname ?></li>
									</div>
								</div>
							</div>
						</div>
						<div class="body">
							<div class="row clearfix">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="highcharts-progress"></div>
									</figure>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="highcharts-time"></div>
									</figure>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="highcharts-funds"></div>
									</figure>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<li class="list-group-item">
											<label>
												<strong>PROJECT LOCATION</strong>
											</label>
											<div>
												<strong><?= $level1label ?>:</strong> <?php echo implode(",", $level1); ?>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong><?= $level2label ?>:</strong> <?php echo implode(",", $level2); ?>
											</div>
											<hr style="border-top: 1px dashed red;">
											<!--<div>
												<strong><? //= $level3label
														?>:</strong> <?php //echo implode(",", $level3);
																		?>
											</div>-->
										</li>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<figure class="highcharts-figure">
										<div id="container_project_cost" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
									</figure>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script>
		Highcharts.chart('highcharts-progress', {
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Project % Progress',
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
						y: <?php echo $percent2; ?>,
						sliced: true,
						selected: true
					},
					['Pending', <?php echo $percentage_progress_remaining; ?>]
				],
				colors: [
					'#db03fc',
					'#03e3fc'
				]
			}]
		});

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
					'#FFF263'
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
					'#6AF9C4',
					'#CB2326'
				]
			}]
		});

		$(function() {
			var chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container_project_cost',
					type: 'column'
				},
				xAxis: {
					categories: <?= $subtasks_data ?>
				},
				plotOptions: {
					series: {
						dataLabels: {
							enabled: false
						}
					}
				},
				series: <?= $series_chart ?>,
			});
		});
	</script>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $ex) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>