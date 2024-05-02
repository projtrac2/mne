<?php
try {
	require('includes/head.php');

	if ($permission) {
		$projid = $_GET['projid'];
		$query_rsMyP = $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE projid = :projid");
		$query_rsMyP->execute(array(":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$count_rsMyP = $query_rsMyP->rowCount();
		if ($row_rsMyP) {
			$projcategory = $row_rsMyP["projcategory"];
			$projstatusid = $row_rsMyP["projstatus"];
			$projectID =  $row_rsMyP['projid'];
			$currentStatus =  $row_rsMyP['projstatus'];
			$projcat = $row_rsMyP["projcategory"];
		}


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
			$query_rsContractDates->execute(array(":projid" => $projectID));

			$query_tenderdetails =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid = :projid");
			$query_tenderdetails->execute(array(":projid" => $projectID));

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
			$query_other_fin_lines->execute(array(":projid" => $projectID));
			while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
				$unitcost = $row_other_fin_lines["unit_cost"];
				$unitsno = $row_other_fin_lines["units_no"];
				$itemcost = $unitcost * $unitsno;
				$othercost = $othercost + $itemcost;
			}

			$consumed = 0;
			$query_consumed =  $db->prepare("SELECT SUM(amount_requested) AS consumed FROM tbl_payments_request WHERE status=3 AND projid = :projid");
			$query_consumed->execute(array(":projid" => $projectID));
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
				$query_proj_tasks_dates =  $db->prepare("SELECT MIN(sdate) AS projstartdate, MAX(edate) AS projenddate FROM tbl_task WHERE projid = :projid");
				$query_proj_tasks_dates->execute(array(":projid" => $projectID));
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

			$rate = ($consumed / $totalcost) * 100;
			$projcost = number_format($totalcost, 2);
			$balance = $totalcost - $consumed;
			$balance = number_format($balance, 2);
			$consumed = number_format($consumed, 2);
		} else {
			$query_proj_tasks_dates =  $db->prepare("SELECT MIN(sdate) AS projstartdate, MAX(edate) AS projenddate FROM tbl_task WHERE projid = :projid");
			$query_proj_tasks_dates->execute(array(":projid" => $projectID));
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
			$query_other_fin_lines->execute(array(":projid" => $projectID));
			while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
				$unitcost = $row_other_fin_lines["unit_cost"];
				$unitsno = $row_other_fin_lines["units_no"];
				$itemcost = $unitcost * $unitsno;
				$othercost = $othercost + $itemcost;
			}
			$consumed = 0;
			$query_consumed =  $db->prepare("SELECT SUM(amount_requested) AS consumed FROM tbl_payments_request WHERE status=3 AND projid = :projid");
			$query_consumed->execute(array(":projid" => $projectID));
			$row_consumed = $query_consumed->fetch();
			if ($row_consumed) {
				$consumed = $row_consumed["consumed"];
			}

			$rate = ($consumed / $othercost) * 100;
			$projcost = number_format($othercost, 2);
			$balance = $othercost - $consumed;
			$balance = number_format($balance, 2);
			$consumed = number_format($consumed, 2);
		}

		if ($durationtoenddate > $duration) {
			$durationtoenddate = 0;
		}

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$projid'");
		$query_rsMlsProg->execute();
		$row_rsMlsProg = $query_rsMlsProg->fetch();

		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

		$percent2 = round($prjprogress, 2);

?>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon ?>
						<?= $pageTitle ?>
						<div class="btn-group" style="float:right">
							<div class="btn-group" style="float:right">
							</div>
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<h4>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
							</div>
						</h4>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="header">
								<div class="row clearfix align-center" style="margin-top:5px">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px">
											<strong><img src="images/progress.png" alt="progress" style="width:16px; height:16px" /> Project Status:
												<?= $projstatus ?></strong>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px">&nbsp;
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px">
											<strong><img src="images/progress.png" alt="progress" style="width:16px; height:16px" /> Project Progress: <?php echo $percent2 . "%"; ?></strong>
										</div>
									</div>
								</div>
							</div>
							<div class="header">
								<div class="row clearfix align-center" style="margin-top:5px">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-calendar"> </i> Project Duration (Days):</strong></div>
										<h5 style="padding-top:5px; padding-bottom:5px"><?php echo $duration; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-calendar"> </i> Time consumed(Days)</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px"><?php echo $durationtodate; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-calendar"> </i> Remaining Days</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px"><?php echo $durationtoenddate; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-calendar"> </i> Percentage time consumed</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px"><?php echo round($durationrate, 2) . "%"; ?></h5>
									</div>
								</div>
							</div>
							<div class="header">
								<div class="row clearfix align-center" style="margin-top:5px">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><img src="images/money2.png" alt="img" style="width:16px; height:16px" /> Project Budget:</strong></div>
										<h5 style="padding-top:5px; padding-bottom:5px">Ksh. <?php echo $projcost; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><img src="images/money2.png" alt="img" style="width:16px; height:16px" /> Budget Consumed</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px">Ksh. <?php echo $consumed; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><img src="images/money2.png" alt="img" style="width:16px; height:16px" /> Budget Balance</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px">Ksh. <?php echo $balance; ?></h5>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><img src="images/money2.png" alt="img" style="width:16px; height:16px" /> Utilization Rate (%)</strong>:</div>
										<h5 style="padding-top:5px; padding-bottom:5px"><?php echo round($rate, 4) . "%"; ?></h5>
									</div>
								</div>
							</div>
							<div class="body">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output Indicator/s</legend>
									<div class="row clearfix" style="margin-top:5px">
										<?php
										$query_proj_inds =  $db->prepare("SELECT p.indicator AS indid, g.output AS output, p.id AS outputid FROM tbl_project_details p left join tbl_progdetails g on g.id=p.outputid WHERE projid = :projid GROUP BY p.indicator");
										$query_proj_inds->execute(array(":projid" => $projectID));
										while ($row_proj_inds = $query_proj_inds->fetch()) {
											$indid = $row_proj_inds["indid"];
											$output = $row_proj_inds["output"];
											$outputid = $row_proj_inds["outputid"];

											$query_proj_ind_target =  $db->prepare("SELECT SUM(total_target) AS target FROM tbl_project_details WHERE projid = :projid AND indicator = :indid");
											$query_proj_ind_target->execute(array(":projid" => $projectID, ":indid" => $indid));
											$row_proj_ind_target = $query_proj_ind_target->fetch();
											$target = $row_proj_ind_target["target"];

											$query_indicator =  $db->prepare("SELECT unit, indicator_name FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid = :indid");
											$query_indicator->execute(array(":indid" => $indid));
											$row_indicator = $query_indicator->fetch();
											$unit = $row_indicator["unit"];
											$indicatorname = $row_indicator["indicator_name"];

											$query_proj_op_achieved =  $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND opid = :outputid");
											$query_proj_op_achieved->execute(array(":projid" => $projectID, ":outputid" => $outputid));
											$row_proj_op_achieved = $query_proj_op_achieved->fetch();
											$achieved = $row_proj_op_achieved["achieved"];
										?>
											<h5><strong>Indicator:</strong> <?php echo $unit . " of " . $indicatorname ?></h5>
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
												<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-map"> </i> Target:</strong></div>
												<h5 style="padding-top:5px; padding-bottom:5px"><?php echo number_format($target); ?></h5>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
												<div align="left" style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; background-color:#EEE; padding-left:5px"><strong><i class="fa fa-bar-chart"> </i> Achieved:</strong></div>
												<h5 style="padding-top:5px; padding-bottom:5px"><?php echo number_format($achieved); ?></h5>
											</div>
										<?php
										}
										?>
									</div>
								</fieldset>
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');

			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {

				$(".submenus").show();
				$(this).attr('id', '1');
			}

		});

		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});


		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});
</script>