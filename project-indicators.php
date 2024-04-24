<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: project-mne-details?proj=".$_GET['proj']);
$decode_indid = (isset($_GET['ind']) && !empty($_GET["ind"])) ? base64_decode($_GET['ind']) : header("Location: project-mne-details?proj=".$_GET['proj']);
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$indid_array = explode("indid54321", $decode_indid);
$indid = $indid_array[1];
$currentdate = date("Y-m-d");

$original_projid = $_GET['proj'];
require('includes/head.php');

if ($permission) {
	try {
		$query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
		$query_project->execute(array(":projid" => $projid));
		$row_project = $query_project->fetch();
		$project_locations = explode(",", $row_project["projlga"]);
		$projname = $row_project['projname'];
		$projstage = $row_project["projstage"];
		$projcat = $row_project["projcategory"];
		$percent2 = number_format(calculate_project_progress($projid, $projcat),2);

		$query_proj_inds =  $db->prepare("SELECT indicator_name AS indicator, unit, output FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit left join tbl_progdetails g on g.indicator=i.indid left join tbl_project_details p on p.outputid=g.id WHERE projid = :projid AND indid=:indid GROUP BY i.indid ORDER BY p.indicator ASC");
		$query_proj_inds->execute(array(":projid" => $projid, ":indid" => $indid));
		$row_proj_inds = $query_proj_inds->fetch();		
		$proj_indicator = $row_proj_inds["indicator"];
		$proj_indicator_unit = $row_proj_inds["unit"];
		$project_indicator = $proj_indicator_unit." of ".$proj_indicator;

		$query_default_proj_ind =  $db->prepare("SELECT p.id AS projopid, p.total_target, indicator_name,indicator_description,duration,unit FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit left join tbl_progdetails g on g.indicator=i.indid left join tbl_project_details p on p.outputid=g.id WHERE p.projid = :projid AND indid=:indid");
		$query_default_proj_ind->execute(array(":projid" => $projid, ":indid" => $indid));
		$row_default_proj_ind = $query_default_proj_ind->fetch();

		$opid = $row_default_proj_ind["projopid"];
		$optarget = intval($row_default_proj_ind["total_target"]);
		$default_proj_indicator_unit = $row_default_proj_ind["unit"];
		$default_proj_indicator_description = $row_default_proj_ind["indicator_description"];
		$default_proj_indicator_duration = $row_default_proj_ind["duration"];

		$query_proj_tasks_dates =  $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid WHERE w.projid = :projid");
		$query_proj_tasks_dates->execute(array(":projid" => $projid));
		$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

		$date1 = $row_proj_tasks_dates["projstartdate"];
		$date2 = $row_proj_tasks_dates["projenddate"];

		$months = [];
		$achieved = [];
		$targeted = [];
		$diff = [];

		$time   = strtotime($date1);
		$last   = date('M-Y', strtotime($date2));
		do {
			$year = date('Y', $time);
			$month = date('M-Y', $time);
			$total = date('t', $time);

			$months[] = $month;

			$time = strtotime('+1 month', $time);

			$startdate = date('Y-m-01', $time);
			$edate = new DateTime($startdate);
			$enddate = $edate->format('Y-m-t');

			$query_achieved =  $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND output_id = :opid AND (date_created >= :sdate and date_created <= :edate)");
			$query_achieved->execute(array(":projid" => $projid, ":opid" => $opid, ":sdate" => $startdate, ":edate" => $enddate));
			$row_achieved = $query_achieved->fetch();

			$targetachieved = 0;
			if(!IS_NULL($row_achieved["achieved"])){
				$targetachieved = $row_achieved["achieved"];
			}

			$achieved[] = $targetachieved;
			$targeted[] = $optarget;
		} while ($month != $last);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://uicdn.toast.com/chart/latest/toastui-chart.min.css" />
	<script src="https://uicdn.toast.com/chart/latest/toastui-chart.min.js"></script>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:-60px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>

					<div class="btn-group" style="float:right; margin-right:10px">
						<button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">
							Go Back
						</button>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<li class="list-group-item">
										<div style="background-color: #a5d6a7;" class="header">
											<label>
												<strong>INDICATOR DETAILS</strong>
											</label>
										</div>
										<div class="body bg-success p-2 text-dark bg-opacity-50">
											<div id="respondent" class="bg-secondary">
												<label>Output Indicator:</label>
												<div class="form-line">
													<div class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px">
														<?php echo $project_indicator;?>
													</div>
												</div>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<label><strong>Indicator Description: </strong></label><textarea style="width:100%; padding:10px" id="inddesc" readonly><?= strip_tags($default_proj_indicator_description)  ?></textarea>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Indicator Measurement Unit: </strong><span id="unit"><?= $default_proj_indicator_unit ?></span>
											</div>
											<hr style="border-top: 1px dashed red;">
											<div>
												<strong>Indicator Duration: </strong><span id="duration"><?= $default_proj_indicator_duration ?> Days</span>
											</div>
											<hr style="border-top: 1px dashed red;">
										</div>
									</li>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<li class="list-group-item">
										<div id="chart-area"></div>
									</li>
								</div>
							</div>
						</div>
						<div class="body">
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<li class="list-group-item table-responsive" id="progresstable">
										<table class="table table-bordered text-dark">
											<caption>Indicator Progress Table</caption>
											<thead style="background-color: #a5d6a7;">
												<tr>
													<th scope="col">Location</th>
													<th scope="col">Baseline</th>
													<th scope="col">Target</th>
													<?php
													$ptime   = strtotime($date1);
													$plast   = date('M-Y', strtotime($date2));
													do {
														$pmonth = date('M-Y', $ptime);
														$total = date('t', $ptime);

														$ptime = strtotime('+1 month', $ptime);
														echo '<th scope="col">'.$pmonth.'</th>';
													} while ($pmonth != $plast);
													?>
													<th scope="col">Rate</th>
												</tr>
											</thead>
											<tbody  class="bg-success p-2 text-dark">
												<?php
												foreach($project_locations AS $projloc){
													$query_location =  $db->prepare("SELECT state FROM tbl_state WHERE id = :projloc");
													$query_location->execute(array(":projloc" => $projloc));
													$row_location = $query_location->fetch();
													$location = $row_location["state"];

													$query_basevalues =  $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid = :indid AND level3 = :level3");
													$query_basevalues->execute(array(":indid" => $indid, ":level3" => $projloc));
													$row_basevalues = $query_basevalues->fetch();

													$query_proj_op_target =  $db->prepare("SELECT d.total_target AS target FROM tbl_output_disaggregation d left join tbl_project_details o on o.id=d.outputid WHERE o.projid = :projid AND o.id = :opid AND d.outputstate = :location");
													$query_proj_op_target->execute(array(":projid" => $projid, ":opid" => $opid, ":location" => $projloc));
													$row_proj_op_target = $query_proj_op_target->fetch();
													$op_location_target = $row_proj_op_target &&  !is_null($row_proj_op_target['target']) ? intval($row_proj_op_target["target"]) : 0;

													$query_achieved_basevalues =  $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND output_id = :opid AND state_id = :level3 AND date_created < :date");
													$query_achieved_basevalues->execute(array(":projid" => $projid, ":opid" => $opid, ":level3" => $projloc, ":date" => $date1));
													$row_achieved_basevalues = $query_achieved_basevalues->fetch();

													$projindbasevalue = $row_basevalues["basevalue"] + $row_achieved_basevalues["achieved"];
													echo '<tr>
														<td scope="row">'.$location.'</td>
														<td scope="row">'.$projindbasevalue.'</td>
														<td scope="row">'.$op_location_target.'</td>';
															$totalopprogress = 0;
															$opprogress = 0;

															$date   = strtotime($date1);
															$final   = date('M-Y', strtotime($date2));
															do {
																$mnth = date('M-Y', $date);
																$total = date('t', $date);

																$date = strtotime('+1 month', $date);

																$startdate = date('Y-m-01', $date);
																$edate = new DateTime($startdate);
																$enddate = $edate->format('Y-m-t');

																$query_achieved_cumulatively =  $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND output_id = :opid AND state_id = :level3 AND (date_created >= :sdate and date_created <= :edate)");
																$query_achieved_cumulatively->execute(array(":projid" => $projid, ":opid" => $opid, ":level3" => $projloc, ":sdate" => $startdate, ":edate" => $enddate));
																$row_achieved_cumulatively = $query_achieved_cumulatively->fetch();

																$opachieved = 0;
																if(!IS_NULL($row_achieved_cumulatively["achieved"])){
																	$opachieved = $row_achieved_cumulatively["achieved"];
																}
																echo '<td scope="row">'.$opachieved.'</td>';
															} while ($mnth != $final);
															$rate = $op_location_target != '' || !empty($op_location_target) ? ($opachieved / $op_location_target) * 100 : 0;
													echo '<td scope="row">'.$rate.'%</td>
													</tr>';
												}
												?>
											</tbody>
										</table>
									</li>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
	const el = document.getElementById('chart-area');
	const data = {
		categories: <?php echo json_encode($months); ?>,
		series: {
		  column: [
			{
			  name: 'Achieved',
			  data: <?php echo json_encode($achieved); ?>,
			},
		  ],
		  line: [
			{
			  name: 'Target',
			  data: <?php echo json_encode($targeted); ?>,
			},
		  ],
		},
	};
	const options = {
		chart: { title: 'Indicator Performance', width: 470, height: 400 },
		yAxis: { title: 'Units (<?php echo $default_proj_indicator_unit; ?>)' },
		xAxis: { title: 'Month' },
	};

	const chart = toastui.Chart.columnLineChart({ el, data, options });
	</script>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<!-- Jquery Nestable -->
<script src="assets/js/dashboard/project-dashboard.js"></script>
<script src="assets/projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
<script src="assets/projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>