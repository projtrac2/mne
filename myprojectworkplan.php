<?php 
	try {

require('includes/head.php');
$pageTitle = "Quarterly Targets";

if ($permission) {

		if (isset($_GET['projid'])) {
			$projid = $_GET['projid'];
			$query_rsMyP =  $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE tbl_projects.deleted='0' AND  projid = :projid");
			$query_rsMyP->execute(array(":projid" => $projid));
			$row_rsMyP = $query_rsMyP->fetch();

			$projcategory = $row_rsMyP["projcategory"];
			$currentStatus =  $row_rsMyP['projstatus'];
		}

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE  projid = :projid");
		$query_rsMlsProg->execute(array(":projid" => $projid));
		$row_rsMlsProg = $query_rsMlsProg->fetch();
		$mlprogress = $row_rsMlsProg["mlprogress"];

		$nmb = $row_rsMlsProg["nmb"];
		$prjprogress = 0;
		if ($mlprogress != 0 && $nmb != 0) {
			$prjprogress = $mlprogress / $nmb;
		}
		$percent2 = round($prjprogress, 2);


		$tndprojid = $row_rsMyP['projid'];
		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$tndprojid'");
		$query_rsTender->execute();
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();
	
?>
	<!-- start body  -->
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
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
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
								<a href="myprojectdash.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
								<a href="myprojectmilestones.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Quarterly Targets</a>
								<a href="myprojectfinancialplan.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
								<a href="myproject-key-stakeholders.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
								<a href="projectissueslist.php?proj=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
								<a href="myprojectfiles.php?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
							</div>
						</div>
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
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<!-- Advanced Form Example With Validation -->
							<?php
							$projid = $row_rsMyP['projid'];
							$query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ORDER BY id ASC");
							$query_rsOutput->execute(array(":projid" => $projid));
							$totalRows_rsOutput = $query_rsOutput->rowCount();

							function get_targets($projid, $opid)
							{
								global $db;
								$query_rsYear_targets =  $db->prepare("SELECT * FROM tbl_workplan_targets WHERE projid=:projid AND outputid=:opid GROUP BY outputid");
								$query_rsYear_targets->execute(array(":projid" => $projid, ":opid" => $opid));
								$row_rsYear_targets = $query_rsYear_targets->fetchAll();
								$totalrow_rsYear_targets = $query_rsYear_targets->rowCount();

								if ($totalrow_rsYear_targets > 0) {
									return $row_rsYear_targets;
								} else {
									return false;
								}
							}

							if ($totalRows_rsOutput > 0) {
								$ops = 0;
								while($row_rsOutput = $query_rsOutput->fetch()) {
									$ops++;
									$opid = $row_rsOutput['id'];
									$oipid = $row_rsOutput['outputid'];
									$indicatorID = $row_rsOutput['indicator'];
									$outcomeYear = $row_rsOutput['year'];
									$outputDuration = $row_rsOutput['duration'];
									$outputBudget = $row_rsOutput['budget'];
									$workplan_interval = $row_rsOutput['workplan_interval'];

									/* $query_rsOPUnit =  $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid = :indid");
									$query_rsOPUnit->execute(array(":indid" => $indicatorID));
									$row_rsOPUnit = $query_rsOPUnit->fetch(); */

									$query_Indicator = $db->prepare("SELECT indicator_name, u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid = :indid");
									$query_Indicator->execute(array(":indid" => $indicatorID));
									$row = $query_Indicator->fetch();
									$indname = $row['indicator_name'];
									$opunit = $row['unit'];

									$query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id=:id");
									$query_out->execute(array(":id" => $oipid));
									$row_out = $query_out->fetch();
									$outputName = $row_out ?  $row_out['output'] : "";

									$query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE id=:id");
									$query_rsYear->execute(array(":id" => $outcomeYear));
									$row_rsYear = $query_rsYear->fetch();
									$fscyear = $row_rsYear['year'];
									$projstartyear = $row_rsYear['yr'];

									$workplan_targets = get_targets($projid, $opid);
									if ($workplan_targets) {
										?>
										<fieldset class="scheduler-border" style="padding:-15px">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">OUTPUT <?= $ops ?></legend>
											<div class="header">
												<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
													<div class="col-md-12 list-group-item list-group-item-action active"><strong> Output: </strong><?= $outputName ?></div>
													<div class="col-md-12 list-group-item list-group-item-action active"> <strong>Indicator: </strong><?= $opunit . " of " . $indname ?></div>
												</div>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="" style="width:100%">
													<thead>
														<tr>
															<?php
															$quarters = "<tr>";
															foreach ($workplan_targets as $targets) {
															?>
																<th colspan="4" class="text-center"><?= $targets['year'] ?></th>
															<?php
																$quarters .=
																	"<th>Q1</th>
																		<th>Q2</th>
																		<th>Q3</th>
																		<th>Q4</th>";
															}
															$quarters .= "</tr>"
															?>
														</tr>
														<?= $quarters ?>
													</thead>
													<tbody id="funding_table_body">
														<tr>
															<?php
															foreach ($workplan_targets as $targets) {
															?>
																<td><?= $targets['Q1'] ?></td>
																<td><?= $targets['Q2'] ?></td>
																<td><?= $targets['Q3'] ?></td>
																<td><?= $targets['Q4'] ?></td>
															<?php
															}
															?>
														</tr>
													</tbody>
												</table>
											</div>
										</fieldset>
										<?php
									}
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<!-- Modal Issue Action -->
	<div class="modal fade" id="ganttModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Project Issue Analysis</font>
					</h3>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="body">
									<div class="table-responsive" style="background:#eaf0f9">
										<div id="chart_gantt" style="width:100%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-5">
					</div>
					<div class="col-md-2" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
					<div class="col-md-5">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Action -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
<!-- <script src="general-settings/js/fetch-selected-project-activities.js"></script> -->