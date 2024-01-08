<?php
require('includes/head.php');
if ($permission) {
	function projfy()
	{
		global $db;
		$sql = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
		$sql->execute();
		while ($row = $sql->fetch()) {
			echo '<option value="' . $row['id'] . '">' . $row['year'] . '</option>';
		}
	}

	function get_achieved($indid, $start_date, $end_date)
	{
		global $db;
		$query_quarter_four_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m
		inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created>=:start_date AND date_created<=:end_date)AND record_type=1");
		$query_quarter_four_actual->execute(array(":indid" => $indid, ":start_date" => $start_date, ":end_date" => $end_date));
		$rows_quarter_four_actual = $query_quarter_four_actual->fetch();
		return !is_null($rows_quarter_four_actual["achieved"]) ? $rows_quarter_four_actual["achieved"] : 0;
	}

	$yr = date("Y");
	$mnth = date("m");
	$startmnth = 07;
	$endmnth = 06;

	if ($mnth >= 7 && $mnth <= 12) {
		$startyear = $yr;
		$endyear = $yr + 1;
	} elseif ($mnth >= 1 && $mnth <= 6) {
		$startyear = $yr - 1;
		$endyear = $yr;
	}

	$base_url = "";
	$drilldown_link = false;

	$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where yr =:year ");
	$query_rsFscYear->execute(array(":year" => $startyear));
	$row_rsFscYear = $query_rsFscYear->fetch();

	$fyid = $row_rsFscYear['id'];

	if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
		$sector = isset($_GET['sector']) ? $_GET['sector'] : '';
		$dept = isset($_GET['department']) ? $_GET['department'] : '';
		$fyid = isset($_GET['indfy']) ? $_GET['indfy'] : '';
		$btn_search = isset($_GET['btn_search']) ? $_GET['btn_search'] : '';

		$drilldown_link = $sector != '' || $dept != '' ? true : false;

		if (!empty($sector) && !empty($fyid) && empty($dept)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector" => $sector));

			$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
			$query_rsFscYear->execute(array(":year" => $fyid));
			$row_rsFscYear = $query_rsFscYear->fetch();
			$startyear = $row_rsFscYear['yr'];
			$endyear = $startyear + 1;

			$base_url = "btn_search=$btn_search&indfy=$fyid&sector=$sector";
		}

		if (!empty($sector) && empty($dept) && empty($fyid)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector" => $sector));
			$base_url = "btn_search=$btn_search&sector=$sector";
		} elseif (!empty($sector) && !empty($dept) && empty($fyid)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector" => $sector, ":dept" => $dept));
			$base_url = "btn_search=$btn_search&dept=$dept&sector=$sector";
		} elseif (!empty($fyid) && empty($sector) && empty($dept)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();

			$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
			$query_rsFscYear->execute(array(":year" => $fyid));
			$row_rsFscYear = $query_rsFscYear->fetch();
			$startyear = $row_rsFscYear['yr'];
			$endyear = $startyear + 1;
			$base_url = "btn_search=$btn_search&indfy=$fyid";
		} elseif (empty($_GET['indfy']) && empty($sector) && empty($dept)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();
		} elseif (!empty($sector) && !empty($dept) && !empty($fyid)) {
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector" => $sector, ":dept" => $dept));
			$base_url = "btn_search=$btn_search&indfy=$fyid&dept=$dept&sector=$sector";
		}
	} else {
		$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
		$query_indicators->execute();
	}

	$financialyear = $startyear . "/" . $endyear;
	$totalRows_indicators = $query_indicators->rowCount();

	$basedate = $startyear . "-06-30";
	$startq1 = $startyear . "-07-01";
	$endq1 = $startyear . "-09-30";
	$startq2 = $startyear . "-10-01";
	$endq2 = $startyear . "-12-31";
	$startq3 = $endyear . "-01-01";
	$endq3 = $endyear . "-03-31";
	$startq4 = $endyear . "-04-01";
	$endq4 = $endyear . "-06-30";
?>


	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
				</h4>
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<div class="col-md-12 row">
									<ul class="list-inline pull-right">
										<li>
											<!-- <a href="reports/output-reports-quarter-doc.php" target="_blank" class="btn btn-primary">
												<i class="fa fa-file-word-o" aria-hidden="true"></i>
											</a> -->
										</li>
										<li>
											<a href="reports/output-reports-quarter-pdf.php?<?= $base_url ?>" target="_blank" class="btn btn-danger btn-sm" type="button">
												<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="body">
							<div class="row clearfix">
								<form id="searchform" name="searchform" method="get" style="margin-top:10px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<select name="indfy" id="indfy" onchange="indfinyear()" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
											<option value="" selected="selected">Select Financial Year</option>
											<?php
											projfy();
											?>
										</select>
									</div>
									<div class="col-md-3">
										<select name="sector" id="sector" onchange="sectors()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
											<option value="">Select <?= $ministrylabel ?></option>
											<?php
											$data = '';
											$query_sectors = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=0");
											$query_sectors->execute(array(":comm" => $comm));
											while ($row = $query_sectors->fetch()) {
												$stid = $row['stid'];
												$sector = $row['sector'];
												$data .= '<option value="' . $stid . '">' . $sector . '</option>';
											}
											echo $data;
											?>
										</select>
									</div>
									<div class="col-md-3">
										<select name="department" id="dept" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
											<option value="">No <?= $departmentlabelplural ?></option>
										</select>
									</div>
									<div class="col-md-2">
										<input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="FILTER" />
										<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='output-indicators-quarterly-progress-report.php'" id="btnback">
									</div>
								</form>
							</div>
						</div>
						<div class="body" style="margin-top:5px">
							<div class="row clearfix" id="rowcontainerrow">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white">
											<strong>Financial Year: <span id="fy"><?= $financialyear ?></span></strong>
										</legend>
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover dataTable" id="qapr_reports">
														<thead>
															<tr class="bg-light-blue">
																<th colspan="" rowspan="2">#</th>
																<th colspan="" rowspan="2">Indicator </th>
																<th colspan="" rowspan="2">Baseline</th>
																<th colspan="" rowspan="2">Annual Target</th>
																<?php
																$years = $target_rows = $target_quarters = '';
																$q1_link = $q2_link = $q3_link = $q4_link = "";
																if ($base_url != "" && $drilldown_link) {
																	$q1_link = '<a href="view-qpr-report?quarter=1&' . $base_url . '"> Q1 (' . $startq1 . ' to ' . $endq1 . ') </a>';
																	$q2_link = '<a href="view-qpr-report?quarter=2&' . $base_url . '"> Q2 (' . $startq2 . ' to ' . $endq2 . ') </a>';
																	$q3_link = '<a href="view-qpr-report?quarter=3&' . $base_url . '"> Q3 (' . $startq3 . ' to ' . $endq3 . ') </a>';
																	$q4_link = '<a href="view-qpr-report?quarter=4&' . $base_url . '"> Q4 (' . $startq4 . ' to ' . $endq4 . ') </a>';
																} else {
																	$q1_link = 'Q1 (' . $startq1 . ' to ' . $endq1 . ')';
																	$q2_link = 'Q2 (' . $startq2 . ' to ' . $endq2 . ')';
																	$q3_link = 'Q3 (' . $startq3 . ' to ' . $endq3 . ')';
																	$q4_link = 'Q4 (' . $startq4 . ' to ' . $endq4 . ')';
																}

																$target_quarters .= '
																		<th colspan="3"> ' . $q1_link . '</th>
																		<th colspan="3"> ' . $q2_link . '</th>
																		<th colspan="3"> ' . $q3_link . '</th>
																		<th colspan="3"> ' . $q4_link . '</th>';

																for ($jp = 0; $jp < 4; $jp++) {
																	$target_rows .= '
																		<th>Target</th>
																		<th>Achieved</th>
																		<th>Rate (%)</th>';
																}
																?>
																<?= $target_quarters ?>
															</tr>
															<tr class="bg-light-blue">
																<?= $target_rows ?>
															</tr>
														</thead>
														<tbody>
															<?php
															$sn = 0;
															if ($totalRows_indicators > 0) {
																while ($row_indicators = $query_indicators->fetch()) {
																	$initial_basevalue = 0;
																	$actual_basevalue = 0;
																	$annualtarget = 0;
																	$basevalue = 0;

																	$quarter_one_target = 0;
																	$quarter_two_target = 0;
																	$quarter_three_target = 0;
																	$quarter_four_target = 0;
																	$quarter_one_achived = 0;
																	$quarter_two_achived = 0;
																	$quarter_three_achived = 0;
																	$quarter_four_achived = 0;
																	$indid = $row_indicators['indid'];
																	$indicator = $row_indicators['indicator_name'];

																	$query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid");
																	$query_indbasevalue_year->execute(array(":indid" => $indid));
																	$totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();

																	if ($totalRows_indbasevalue_year > 0) {
																		$sn++;
																		$query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
																		$query_initial_indbasevalue->execute(array(":indid" => $indid));
																		$rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
																		$totalRows_initial_indbasevalue = $query_initial_indbasevalue->rowCount();

																		if ($totalRows_initial_indbasevalue > 0) {
																			$initial_basevalue = $rows_initial_indbasevalue["basevalue"];
																		}

																		$query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE d.indicator=:indid AND m.date_created < '$basedate' AND record_type=2");
																		$query_actual_ind_value->execute(array(":indid" => $indid));
																		$rows_actual_ind_value = $query_actual_ind_value->fetch();
																		$actual_basevalue = !is_null($rows_actual_ind_value["basevalue"]) ? $rows_actual_ind_value["basevalue"] : 0;


																		$basevalue = $initial_basevalue + $actual_basevalue;

																		$query_annual_target = $db->prepare("SELECT SUM(target) AS target FROM tbl_programs_based_budget WHERE indid=:indid AND finyear=:finyear");
																		$query_annual_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
																		$rows_annual_target = $query_annual_target->fetch();
																		$totalRows_annual_target = $query_annual_target->rowCount();

																		if ($totalRows_annual_target > 0) {
																			$annualtarget = $rows_annual_target["target"];
																		}

																		$query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
																		$query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
																		$rows_quarterly_target = $query_quarterly_target->fetch();
																		$totalRows_quarterly_target = $query_quarterly_target->rowCount();


																		$quarter_one_achived = get_achieved($indid, $startq1, $endq1);
																		$quarter_two_achived = get_achieved($indid, $startq2, $endq2);
																		$quarter_three_achived = get_achieved($indid, $startq3, $endq3);
																		$quarter_four_achived = get_achieved($indid, $startq4, $endq4);
																		$quarter_one_rate = 0 . "%";
																		$quarter_two_rate = 0 . "%";
																		$quarter_three_rate = 0 . "%";
																		$quarter_four_rate = 0 . "%";

																		if ($totalRows_quarterly_target > 0) {
																			$quarter_one_target = $rows_quarterly_target["Q1"];
																			$quarter_two_target = $rows_quarterly_target["Q2"];
																			$quarter_three_target = $rows_quarterly_target["Q3"];
																			$quarter_four_target = $rows_quarterly_target["Q4"];

																			if ($quarter_one_target > 0 && $quarter_one_achived > 0) {
																				$quarter_1_rate = number_format((($quarter_one_achived / $quarter_one_target) * 100), 2);
																				$quarter_one_rate = $quarter_1_rate . "%";
																			}

																			if ($quarter_two_target > 0 && $quarter_two_achived > 0) {
																				$quarter_2_rate = number_format((($quarter_two_achived / $quarter_two_target) * 100), 2);
																				$quarter_two_rate = $quarter_2_rate . "%";
																			}

																			if ($quarter_three_target > 0 && $quarter_three_achived > 0) {
																				$quarter_3_rate = number_format((($quarter_three_achived / $quarter_three_target) * 100), 2);
																				$quarter_three_rate = $quarter_3_rate . "%";
																			}

																			if ($quarter_four_target > 0 && $quarter_four_achived > 0) {
																				$quarter_4_rate = number_format((($quarter_four_achived / $quarter_four_target) * 100), 2);
																				$quarter_four_rate = $quarter_4_rate . "%";
																			}
																		}
															?>
																		<tr>
																			<td><?= $sn ?></td>
																			<td colspan=""><?= $indicator ?></td>
																			<td colspan=""><?= number_format($basevalue) ?></td>
																			<td colspan=""><?= number_format($annualtarget) ?></td>
																			<td><?= number_format($quarter_one_target) ?></td>
																			<td><?= number_format($quarter_one_achived) ?></td>
																			<td><?= $quarter_one_rate ?></td>
																			<td><?= number_format($quarter_two_target) ?></td>
																			<td><?= number_format($quarter_two_achived) ?></td>
																			<td><?= $quarter_two_rate ?></td>
																			<td><?= number_format($quarter_three_target) ?></td>
																			<td><?= number_format($quarter_three_achived) ?></td>
																			<td><?= $quarter_three_rate ?></td>
																			<td><?= number_format($quarter_four_target) ?></td>
																			<td><?= number_format($quarter_four_achived) ?></td>
																			<td><?= $quarter_four_rate ?></td>
																		</tr>
																<?php
																	}
																}
															} else {
																?>
																<tr>
																	<td colspan="16" style="color:red">
																		<h4>No record found!</h4>
																	</td>
																</tr>
															<?php
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>
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

<script type="text/javascript">
	function goBack() {
		window.history.back();
	}

	function sectors() {
		var sector = $("#sector").val();
		if (sector != "") {
			$.ajax({
				type: "post",
				url: "assets/processor/reports-processor",
				data: {
					get_dept: sector
				},
				dataType: "html",
				success: function(response) {
					$("#dept").html(response);
				},
			});
		}
	}

	$(document).ready(function() {
		$("#obj").on("change", function() {
			var objid = $(this).val();
			if (objid != '') {
				$.ajax({
					type: "post",
					url: "addProjectLocation.php",
					data: "objid=" + objid,
					dataType: "html",
					success: function(response) {
						$("#output").html(response);
					}
				});
			} else {
				$("#output").html('<option value="">... First Select Strategic Objective ...</option>');
			}
		});

		$('#qapr_reports').DataTable();

	});
</script>