<?php 
$fscyear = (isset($_GET['fscyear'])) ? base64_decode($_GET['fscyear']) : "";
$stid = (isset($_GET['stid'])) ? base64_decode($_GET['stid']) : "";
$origin = (isset($_GET['orig'])) ? $_GET['orig'] : 1;

$fscend = $fscyear + 1;
$financial_year = $fscyear . "/" . $fscend;
$start = $fscyear - 1;
$b_financial_year = $start . "/" . $fscyear;

require('includes/head.php');
$current_date = date("Y-m-d");
if ($permission) {
	require('functions/strategicplan.php');

	if (!empty($stid) && !empty($fscyear)) {
		$query_ind = $db->prepare("SELECT indid, indicator_name, indicator_unit FROM tbl_indicator WHERE indicator_sector='$stid' AND indicator_category = 'Output' AND baseline=1");
		$query_ind->execute();
		$totalRows_ind = $query_ind->rowCount();
		$row_ind = $query_ind->fetch();
	}

	$query_years = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$fscyear'");
	$query_years->execute();
	$totalRows_years = $query_years->rowCount();
	$Rows_years = $query_years->fetch();
	$year = ($totalRows_years > 0) ? $Rows_years['id'] : "";

	$query_rsConclusion = $db->prepare("SELECT * FROM `tbl_capr_report_conclusion` WHERE year='$year'  AND stid='$stid' ");
	$query_rsConclusion->execute();
	$Rows_rsConclusion = $query_rsConclusion->fetch();
	$totalRows_rsConclusion = $query_rsConclusion->rowCount();

	$query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
	$query_rsStrategicPlan->execute();
	$row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
	$totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
	($totalRows_rsStrategicPlan > 0) ? $row_rsStrategicPlan : 0;
	$stp_start_year = $row_rsStrategicPlan['starting_year'];
	$stp_duration = $row_rsStrategicPlan['years'];
	$stp_end_year = $stp_start_year + $stp_duration - 1;

	$query_Sectors = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$stid'");
	$query_Sectors->execute();
	$totalRows_Sectors = $query_Sectors->rowCount();
	$Rows_Sectors = $query_Sectors->fetch();
	$sector = ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "";

	$query_sector = $db->prepare("SELECT indicator_sector,stid,sector FROM tbl_indicator i inner join tbl_sectors s on s.stid=i.indicator_sector WHERE i.indicator_category = 'Output' AND s.deleted='0' GROUP BY stid ORDER BY stid");
	$query_sector->execute();
	$totalRows_sector = $query_sector->rowCount();

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addReport")) {
		$stid = $_POST['stid'];
		$year = $_POST['year'];
		$appendice = $_POST['appendice'];
		$conclusion = $_POST['conclusion'];
		$challenges = $_POST['challenges'];
		$sector = $_POST['sector'];
		$user_name = $_POST['username'];

		$query_report = $db->prepare("SELECT * FROM tbl_capr_report_conclusion WHERE stid = '$stid' AND year='$year'");
		$query_report->execute();
		$totalRows_report = $query_report->rowCount();

		if ($totalRows_report > 0) {
			$updateSQL = $db->prepare("UPDATE tbl_capr_report_conclusion SET section_comments=:comments, challenges=:challenges, conclusion=:conclusion, appendices=:appendices, updated_by=:user, date_updated=:date WHERE stid=:stid AND year=:year");
			$result = $updateSQL->execute(array(':comments' => $sector, ':challenges' => $challenges, ':conclusion' => $conclusion, ':appendices' => $appendice, ':user' => $user_name, ':date' => $current_date, ':stid' => $stid, ':year' => $year));
			if ($result) {
				$url = 'view-yearly-objective-performance?fscyear=' . $_GET["fscyear"] . '&stid=' . $_GET["stid"];
				$msg = 'Details successfully updated!';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 3000,
						icon:'success',
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = \"$url\";
					}, 3000);
					</script>";
			}
		} else {
			$insertSQL = $db->prepare("INSERT INTO tbl_capr_report_conclusion (stid,year,section_comments,challenges,conclusion,appendices,created_by) VALUES (:stid,:year,:section_comments,:challenges,:conclusion,:appendices,:created_by)");
			$result  = $insertSQL->execute(array(":stid" => $stid, ":year" => $year, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":created_by" => $user_name));
			if ($result) {
				$url = 'view-yearly-objective-performance?fscyear=' . $_GET["fscyear"] . '&stid=' . $_GET["stid"];
				$msg = 'Details successfully saved!';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 3000,
						icon:'success',
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = \"$url\";
					}, 3000);
					</script>";
			}
		}
	}
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
		});
	</script>
	<section class="content">
		<div class="container-fluid">
			<?php
			echo $results;
			?>
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> <?= $financial_year ?> ANNUAL PROGRESS REPORT</h4>
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
											<?php
											if ($totalRows_rsConclusion > 0) {
											?>
												<div class="col-md-12">
													<div class="btn-group pull-right">
														<a href="reports/capr_report?reportid=<?php echo  base64_encode($Rows_rsConclusion['id']) ?>" target="_blank" class="btn btn-danger btn-sm" type="button">
															<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
														</a>
													</div>
												</div>
											<?php
											} else {
											?>
												<div class="col-md-12">
													<button onclick="history.back()" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
														Go Back
													</button>
												</div>
											<?php
											}
											?>
										</div>
									</div>
									<div class="body" style="margin-top:5px">

										<form action="" method="post" enctype="multipart/form-data">
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="col-md-12">
														<label class="control-label">Sector: <?= $sector ?> *: <font align="left" style="background-color:#eff2f4">(In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.) </font></label>
														<p align="left">
															<textarea name="sector" cols="45" rows="5" class="txtboxes" id="sector" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['section_comments'] : ""; ?></textarea>
															<script>
																CKEDITOR.replace('sector', {
																	height: 200,
																	on: {
																		instanceReady: function(ev) {
																			// Output paragraphs as <p>Text</p>.
																			this.dataProcessor.writer.setRules('p', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ol', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ul', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('li', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																		}
																	}
																});
															</script>
														</p>
													</div>
													<div class="col-md-12 table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr class="bg-light-blue">
																	<th colspan="" rowspan="2" style="width:3%">#</th>
																	<th colspan="" rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																	<th colspan="" rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																	<th colspan="" rowspan="2">Baseline&nbsp;&nbsp;(<?= $b_financial_year ?>)&nbsp;&nbsp;</th>
																	<th colspan="" rowspan="2">Target at end of the CIDP period &nbsp;&nbsp;(<?= $stp_end_year ?>)&nbsp;&nbsp;</th>
																	<th colspan="3" rowspan=""><?= $financial_year ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
																if ($totalRows_ind > 0) {
																	$i = 0;
																	do {
																		$i++;
																		$indicator = $row_ind["indicator_name"];
																		$unitid = $row_ind["indicator_unit"];
																		$indid = $row_ind["indid"];
																		$query_indunit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unitid'");
																		$query_indunit->execute();
																		$row_indunit = $query_indunit->fetch();
																		$unit = ($row_indunit) ? $row_indunit["unit"] : "";

																		$query_rscidpTargets = $db->prepare("SELECT SUM(target) AS target FROM `tbl_progdetails` WHERE year >= $stp_start_year and year <= $stp_end_year AND indicator = $indid");
																		$query_rscidpTargets->execute();
																		$row_rscidpTargets = $query_rscidpTargets->fetch();
																		$count_row_rscidpTargets = $query_rscidpTargets->rowCount();
																		$cidp_target = ($count_row_rscidpTargets > 0) ? $row_rscidpTargets["target"] : '0';

																		$query_indRemarks = $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE indid='$indid' AND year='$year'");
																		$query_indRemarks->execute();
																		$row_indRemarks = $query_indRemarks->fetch();
																		$count_row_indRemarks = $query_indRemarks->rowCount();

																		$query_rsProgramTargets = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator='$indid' AND year='$fscyear' ");
																		$query_rsProgramTargets->execute();
																		$row_rsProgramTargets = $query_rsProgramTargets->fetch();
																		$count_row_rsProgramTargets = $query_rsProgramTargets->rowCount();
																		$targets = ($count_row_rsProgramTargets > 0) ? $row_rsProgramTargets["target"] : '0';

																		$query_indbaseline = $db->prepare("SELECT SUM(value) as baseline FROM tbl_indicator_output_baseline_values WHERE indid ='$indid'");
																		$query_indbaseline->execute();
																		$row_indbaseline = $query_indbaseline->fetch();
																		$count_row_indbaseline = $query_indbaseline->rowCount();
																		$basevalue = $row_indbaseline["baseline"] > 0 ?  $row_indbaseline["baseline"] : 0;

																		//$createddate = $fscyear."-06-30";
																		$enddate = $fscyear . "-06-30";

																		$query_indBaselineBase = $db->prepare("SELECT SUM(actualoutput) as actual FROM tbl_monitoringoutput m INNER JOIN tbl_project_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE date_created <= '" . $enddate . "' AND g.indicator='$indid'");
																		$query_indBaselineBase->execute();
																		$row_indBaselineBase = $query_indBaselineBase->fetch();
																		$achived_output_base = $row_indBaselineBase["actual"] > 0 ?  $row_indBaselineBase["actual"] : 0;
																		$basevalue = $basevalue + $achived_output_base;
																		$stdate = $fscyear . "-07-01";
																		$enddate = $fscend . "-06-30";

																		$query_inAchieved = $db->prepare("SELECT SUM(actualoutput) as actual FROM tbl_monitoringoutput m INNER JOIN tbl_project_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE g.indicator='$indid' AND (date_created >= '" . $stdate . "' AND date_created <= '" . $enddate . "')");
																		$query_inAchieved->execute();
																		$row_inAchieved = $query_inAchieved->fetch();
																		$achieved_output = $row_inAchieved["actual"] > 0 ?  $row_inAchieved["actual"] : 0;
																		$rate = ($targets > 0) ? (($achieved_output / $targets) * 100) : 0;
																		?>
																		<tr>
																			<td><?= $i ?></td>
																			<td><?= $indicator ?></td>
																			<td><?= $unit . " of " . $indicator ?></td>
																			<td><?= number_format($basevalue) ?></td>
																			<td><?= number_format($cidp_target) ?></td>
																			<td><?= number_format($targets) ?></td>
																			<td><?= number_format($achieved_output) ?></td>
																			<td><?= number_format($rate, 2) ?> </td>
																			<td>
																				<?php
																				if ($count_row_indRemarks > 0) {
																					echo $row_indRemarks['remarks'];
																				} else {
																				?>
																					<button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn" class="btn btn-success btn-sm" onclick="remarks(<?= $indid ?>)">
																						<i class="glyphicon glyphicon-file"></i>
																						<strong> Add Remarks</strong>
																					</button>
																				<?php
																				}
																				?>
																			</td>
																		</tr>
																<?php
																	} while ($row_ind = $query_ind->fetch());
																}
																?>
															</tbody>
														</table>
													</div>
													<div class="col-md-12">
														<label class="control-label">Challenges and Recommendations *: <font align="left" style="background-color:#eff2f4">(The sub-sector/department to provide major implementation challenges that they faced during the period under review and recommendations on how to address them.)</font></label>
														<p align="left">
															<textarea name="challenges" cols="45" rows="5" class="txtboxes" id="challenges" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['challenges'] : "";  ?></textarea>
															<script>
																CKEDITOR.replace('challenges', {
																	height: 200,
																	on: {
																		instanceReady: function(ev) {
																			// Output paragraphs as <p>Text</p>.
																			this.dataProcessor.writer.setRules('p', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ol', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ul', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('li', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																		}
																	}
																});
															</script>
														</p>
													</div>
													<div class="col-md-12">
														<label class="control-label">Lessons learnt and Conclusion *: <font align="left" style="background-color:#eff2f4"> (In this section, the sub-sector/department should present the lessons learnt and conclusion in regard to implementation of the CIDP.) </font></label>
														<p align="left">
															<textarea name="conclusion" cols="45" rows="5" class="txtboxes" id="conclusion" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder=""><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['conclusion'] : "";  ?></textarea>
															<script>
																CKEDITOR.replace('conclusion', {
																	height: 200,
																	on: {
																		instanceReady: function(ev) {
																			// Output paragraphs as <p>Text</p>.
																			this.dataProcessor.writer.setRules('p', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ol', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ul', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('li', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																		}
																	}
																});
															</script>
														</p>
													</div>
													<div class="col-md-12">
														<label class="control-label">Appendices for additional documents/materials *: <font align="left" style="background-color:#eff2f4">(The appendices offer an opportunity to provide additional information that otherwise might not be presented elsewhere.) </font></label>
														<p align="left">
															<textarea name="appendice" cols="45" rows="5" class="txtboxes" id="appendice" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="">  <?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['appendices'] : "";  ?></textarea>
															<script>
																CKEDITOR.replace('appendice', {
																	height: 200,
																	on: {
																		instanceReady: function(ev) {
																			// Output paragraphs as <p>Text</p>.
																			this.dataProcessor.writer.setRules('p', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ol', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('ul', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																			this.dataProcessor.writer.setRules('li', {
																				indent: false,
																				breakBeforeOpen: false,
																				breakAfterOpen: false,
																				breakBeforeClose: false,
																				breakAfterClose: false
																			});
																		}
																	}
																});
															</script>
														</p>
													</div>
													<div class="col-md-12" style="margin-top:15px" align="center">
														<input type="hidden" name="MM_insert" value="addReport">
														<input type="hidden" name="year" value="<?= $year ?>">
														<input type="hidden" name="stid" value="<?= $stid ?>">
														<input type="hidden" name="username" id="username" value="<?= $user_name ?>">
														<button class="btn btn-success" type="submit">Save</button>
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
				<div class="modal fade" tabindex="-1" role="dialog" id="remarksItemModal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header" style="background-color:#03A9F4">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Indicator Remarks</h4>
							</div>
							<div class="modal-body" style="max-height:450px; overflow:auto;">
								<div class="card">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="body">
												<div class="div-result">
													<form class="form-horizontal" id="addIndRemarksForm" action="" method="POST" autocomplete="off">
														<br>
														<div id="result">
															<div class="col-md-12">
																<label>Comments:</label>
																<div class="form-line">
																	<textarea name="remarks" id="remarks" rows="5" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter project remarks"></textarea>
																</div>
															</div>
														</div>
														<div class="modal-footer editItemFooter">
															<div class="col-md-12 text-center strat">
																<input type="hidden" name="username" id="username" value="<?= $user_name ?>">
																<input type="hidden" name="year" id="year" value="<?= $year ?>">
																<input type="hidden" name="indicator" id="indicator" value="">
																<input type="hidden" name="addindicatorremarks" value="addindicatorremarks">
																<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
																<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save">
															</div>
														</div> <!-- /modal-footer -->
													</form> <!-- /.form -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div> <!-- /modal-body -->
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
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
	function remarks(indicator) {
		$("#indicator").val(indicator);
	}

	$("#addIndRemarksForm").submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "post",
			url: "assets/processor/reports-processor",
			data: form_data,
			dataType: "json",
			success: function(response) {
				if (response.success) {
					alert("Successfully added comment");
					window.location.reload(true);
				}
			}
		});
	});
</script>