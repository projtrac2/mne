<?php

try {
	//code...

require('includes/head.php');
if ($permission && isset($_GET['fyid'])) {
	$sector_id = $_GET['department'];
	$financial_year_id = $_GET['fyid'];

	$base_url = "department=$sector_id&fyid=$financial_year_id";

	function get_financial_year($financial_year_id)
	{
		global $db;
		$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
		$query_rsFscYear->execute(array(":year" => $financial_year_id));
		$row_rsFscYear = $query_rsFscYear->fetch();
		$totalRows_years = $query_rsFscYear->rowCount();
		return ($totalRows_years > 0) ? $row_rsFscYear['yr'] : 0;
	}

	function get_sector($sector_id)
	{
		global $db;
		$query_Sectors = $db->prepare("SELECT * FROM tbl_sectors WHERE stid=:sector_id");
		$query_Sectors->execute(array(":sector_id" => $sector_id));
		$Rows_Sectors = $query_Sectors->fetch();
		$totalRows_Sectors = $query_Sectors->rowCount();
		return ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "";
	}

	$start_year = get_financial_year($financial_year_id);

	$end_year = $start_year + 1;
	$financial_year = $start_year . "/" . $end_year;
	$start = $start_year - 1;
	$b_financial_year = $start . "/" . $start_year;
	$indicator_sector = '';


	$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
	$query_indicators->execute(array(":sector" => $sector_id));
	$sector = get_sector($sector_id);
	$totalRows_indicators = $query_indicators->rowCount();

	$query_rsConclusion = $db->prepare("SELECT * FROM `tbl_capr_report_conclusion` WHERE year=:year");
	$query_rsConclusion->execute(array(":year" => $financial_year_id));
	$Rows_rsConclusion = $query_rsConclusion->fetch();
	$totalRows_rsConclusion = $query_rsConclusion->rowCount();

	$base_date = $start_year . "-06-30";
	$start_date = $start_year . "-10-01";
	$end_date = $end_year . "-12-31";
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i><?= $financial_year ?> PROGRESS REPORT
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
							<a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
								Go Back
							</a>
						</div>
					</div>
				</h4>
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="header">
										<div class="row clearfix" style="margin-top:5px">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="btn-group pull-right">
													<a type="button" VALUE="PDF" class="btn btn-warning" target="_blank" href="reports/capr_report.php?<?= $base_url ?>" id="btnback" style="margin-right: 10px">
														<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class="body" style="margin-top:5px">
										<form action="" method="post" enctype="multipart/form-data">
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<h4>Chapter 1: Performance</h4>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<h5> 1.1 Mandate and overall goals </h5>
														<p>
															<?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['section_comments'] : "No data"; ?>
														</p>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<h5>1.2 Indicators Performance</h5>
														<div class="table-responsive">
															<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																<thead>

																	<tr class="bg-light-blue">
																		<th style="width:3%">#</th>
																		<th>Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																		<th>Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																		<th>Baseline&nbsp;&nbsp;(<?= $b_financial_year ?>)&nbsp;&nbsp;</th>
																		<th>Target at end of the CIDP period&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
																		<th>Target in review period &nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
																		<th>Achievement for&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
																		<th>Rate of achievement for&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
																		<th>Comments&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	if ($totalRows_indicators > 0) {
																		$counter = 0;
																		while ($row_indicators = $query_indicators->fetch()) {
																			$counter++;
																			$initial_basevalue = 0;
																			$actual_basevalue = 0;
																			$annualtarget = 0;
																			$basevalue = 0;
																			$indid = $row_indicators['indid'];
																			$indicator = $row_indicators['indicator_name'];
																			$unit = $row_indicators['indicator_unit'];

																			$query_indunit =  $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unit'");
																			$query_indunit->execute();
																			$row_indunit = $query_indunit->fetch();
																			$unit = ($row_indunit) ? $row_indunit["unit"] : "";

																			$query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid AND year <= :finyear");
																			$query_indbasevalue_year->execute(array(":indid" => $indid, ":finyear" => $start));
																			$totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();
																			$year_rate = $year_target = $year_achived = 0;

																			if ($totalRows_indbasevalue_year >= 0) {
																				$query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
																				$query_initial_indbasevalue->execute(array(":indid" => $indid));
																				$rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
																				$initial_basevalue = !is_null($rows_initial_indbasevalue["basevalue"]) ? $rows_initial_indbasevalue["basevalue"] : 0;

																				$query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE m.record_type=1 AND d.indicator=:indid AND m.date_created <= '" . $base_date . "'");
																				$query_actual_ind_value->execute(array(":indid" => $indid));
																				$rows_actual_ind_value = $query_actual_ind_value->fetch();
																				$actual_basevalue = !is_null($rows_actual_ind_value["basevalue"]) ? $rows_actual_ind_value["basevalue"] : 0;

																				$basevalue = $initial_basevalue + $actual_basevalue;

																				$query_annual_target = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator=:indid AND year=:finyear");
																				$query_annual_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
																				$rows_annual_target = $query_annual_target->fetch();
																				$annualtarget = !is_null($rows_annual_target["target"]) ? $rows_annual_target["target"] : 0;

																				$query_year_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
																				$query_year_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
																				$rows_year_target = $query_year_target->fetch();
																				$totalRows_year_target = $query_year_target->rowCount();


																				$query_year_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created >= '" . $start_date . "' AND date_created <= '" . $end_date . "')");
																				$query_year_one_actual->execute(array(":indid" => $indid));
																				$rows_year_one_actual = $query_year_one_actual->fetch();
																				$totalRows_year_one_actual = $query_year_one_actual->rowCount();
																				$year_achived =  !is_null($rows_year_one_actual["achieved"]) ? $rows_quarter_one_actual["achieved"] : 0;

																				$year_rate1 = 0;
																				if ($totalRows_year_target > 0) {
																					$year_target +=  $rows_year_target["Q1"];
																					$year_target +=  $rows_year_target["Q2"];
																					$year_target +=  $rows_year_target["Q3"];
																					$year_target +=  $rows_year_target["Q4"];
																					$year_rate1 = $year_achived > 0 && $year_target > 0 ? (($year_achived / $year_target) * 100) : 0;
																				}
																				$year_rate = number_format($year_rate1, 2) . "%";
																			}

																			$query_indRemarks =  $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE indid=:indid AND year=:financial_year_id");
																			$query_indRemarks->execute(array(":indid" => $indid, ":financial_year_id" => $financial_year_id));
																			$row_indRemarks = $query_indRemarks->fetch();
																			$count_row_indRemarks = $query_indRemarks->rowCount();
																			$remarks = ($count_row_indRemarks > 0) ?  $row_indRemarks['remarks'] : "No record";
																	?>
																			<tr>
																				<td><?= $counter ?></td>
																				<td colspan=""><?= $indicator ?></td>
																				<td colspan=""><?= $unit . " of " .  $indicator ?></td>
																				<td colspan=""><?= number_format($basevalue, 2) ?></td>
																				<td colspan=""><?= number_format($annualtarget, 2) ?></td>
																				<td><?= number_format($year_target, 2) ?></td>
																				<td><?= number_format($year_achived, 2) ?></td>
																				<td><?= $year_rate ?></td>
																				<td><?= $remarks ?></td>
																			</tr>
																	<?php
																		}
																	}
																	?>
																</tbody>
															</table>
														</div>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<h4>Chapter 2: Challenges and Recommendations </h4>
														<p><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['challenges'] : "No data";  ?> </p>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<h4>Chapter 3: Lessons learnt and Conclusion</h4>
														<p>
															<?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['conclusion'] : "No data";  ?>
														</p>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<h4>Appendices for additional documents/materials</h4>
														<p>
															<?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['appendices'] : "No data";  ?>
														</p>
													</div>
												</div>
											</div>
										</form>
									</div>
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

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>