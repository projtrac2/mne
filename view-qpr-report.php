<?php
try {
	//code...

require('includes/head.php');
if ($permission && isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
	$financial_year_id = $b_financial_year = '';
	$sector_id = $_GET['sector'];
	$department_id = isset($_GET['department']) ? $_GET['department'] : '';
	$financial_year_id = $_GET['indfy'];
	$quarter = $_GET['quarter'];
	$btn_search = $_GET['btn_search'];
	$base_url = "btn_search=$btn_search&quarter=$quarter&fyid=$financial_year_id&sector=$sector_id&department=$department_id";

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

	if (!empty($dept)) {
		$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector_id and indicator_dept=:dept ORDER BY `indid` ASC");
		$query_indicators->execute(array(":sector_id" => $sector_id, ":department_id" => $department_id));
		$indicator_sector = get_sector($department_id);
	}
	$totalRows_indicators = $query_indicators->rowCount();

	$query_rsConclusion = $db->prepare("SELECT * FROM `tbl_qapr_report_conclusion` WHERE year=:year AND quarter= :quarter");
	$query_rsConclusion->execute(array(":year" => $financial_year_id, ":quarter" => $quarter));
	$Rows_rsConclusion = $query_rsConclusion->fetch();
	$totalRows_rsConclusion = $query_rsConclusion->rowCount();

	$base_date = $start_year . "-06-30";
	$start_date = $end_date = "";
	if ($quarter == 1) {
		$start_date = $start_year . "-07-01";
		$end_date = $start_year . "-09-30";
	} else if ($quarter == 2) {
		$start_date = $start_year . "-10-01";
		$end_date = $start_year . "-12-31";
	} else if ($quarter == 3) {
		$start_date = $end_year . "-01-01";
		$end_date = $end_year . "-03-31";
	} else if ($quarter == 4) {
		$start_date = $end_year . "-04-01";
		$end_date = $end_year . "-06-30";
	}
	$financial_year_date = "Q$quarter (" . $start_date . " to " . $end_date . ")";
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i><?= $financial_year ?> QUARTER <?= $quarter ?> PROGRESS REPORT
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
													<a type="button" VALUE="PDF" class="btn btn-warning" target="_blank" href="reports/qpr_report?<?= $base_url ?>" id="btnback" style="margin-right: 10px">
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
																		<th colspan="" rowspan="2" style="width:3%">#</th>
																		<th colspan="" rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																		<th colspan="" rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																		<th colspan="" rowspan="2">Baseline&nbsp;&nbsp;(<?= $b_financial_year ?>)&nbsp;&nbsp;</th>
																		<th colspan="" rowspan="2">Target at end of the FY&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
																		<th colspan="3" rowspan=""><?= $financial_year_date ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																		<th colspan="" rowspan="2">Comments&nbsp;&nbsp;&nbsp;&nbsp;</th>
																	</tr>
																	<tr class="bg-light-blue">
																		<th>Target</th>
																		<th>Achieved</th>
																		<th>Rate (%)</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	if ($totalRows_indicators > 0) {
																		$counter = 0;
																		$quarter_target = 0;
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

																				$query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
																				$query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
																				$rows_quarterly_target = $query_quarterly_target->fetch();
																				$totalRows_quarterly_target = $query_quarterly_target->rowCount();

																				$query_quarter_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created >= '" . $start_date . "' AND date_created <= '" . $end_date . "')");
																				$query_quarter_one_actual->execute(array(":indid" => $indid));
																				$rows_quarter_one_actual = $query_quarter_one_actual->fetch();
																				$totalRows_quarter_one_actual = $query_quarter_one_actual->rowCount();
																				$quarter_achived =  !is_null($rows_quarter_one_actual["achieved"]) ? $rows_quarter_one_actual["achieved"] : 0;


																				$quarter_rate = "";
																				if ($totalRows_quarterly_target > 0) {
																					$quarter_target =  $rows_quarterly_target["Q$quarter"];
																					$quarter_1_rate = $quarter_achived > 0 && $quarter_target > 0 ? number_format((($quarter_achived / $quarter_target) * 100), 2) : 0;
																					$quarter_rate = $quarter_1_rate . "%";
																				} else {
																					$quarter_rate = number_format(0, 2) . "%";
																				}
																			}

																			$query_indRemarks =  $db->prepare("SELECT * FROM tbl_qapr_report_remarks WHERE indid='$indid' AND year='$financial_year_id' AND quarter='$quarter'");
																			$query_indRemarks->execute();
																			$row_indRemarks = $query_indRemarks->fetch();
																			$count_row_indRemarks = $query_indRemarks->rowCount();
																	?>
																			<tr>
																				<td><?= $counter ?></td>
																				<td colspan=""><?= $indicator ?></td>
																				<td colspan=""><?= $unit . " of " .  $indicator ?></td>
																				<td colspan=""><?= number_format($basevalue) ?></td>
																				<td colspan=""><?= number_format($annualtarget) ?></td>
																				<td><?= number_format($quarter_target) ?></td>
																				<td><?= number_format($quarter_achived) ?></td>
																				<td><?= $quarter_rate ?></td>
																				<td>
																					<?= ($count_row_indRemarks > 0) ?  $row_indRemarks['remarks'] : ""; ?>
																				</td>
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