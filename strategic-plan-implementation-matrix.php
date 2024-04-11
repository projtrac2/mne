<?php
$decode_stplanid = (isset($_GET['plan']) && !empty($_GET["plan"])) ? base64_decode($_GET['plan']) : header("Location: view-strategic-plans.php");
$stplanid_array = explode("strplan1", $decode_stplanid);
$stplan = $stplanid_array[1];

$stplane = $_GET['plan'];
$pageTitle = $planlabel;

require('includes/head.php');
if ($permission) {
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php
					echo $pageTitle;
					?>
				</h4>
			</div>
			<!-- body  -->
			<div class="row clearfix">
				<div class="block-header">
					<?php
					echo $results;
					?>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<?php
						require('functions/strategicplan.php');
						try {
							$strategicPlan = get_splan($stplan);
							if (!$strategicPlan) {
								// redirect back to strategic plan
								// header("Location: view-strategic-plans.php");
							} else {
								$plan = $strategicPlan["plan"];
								$vision = $strategicPlan["vision"];
								$mission = $strategicPlan["mission"];
								$years = $strategicPlan["years"];
								$mission = $strategicPlan["mission"];
								$datecreated = $strategicPlan["date_created"];
								$finyear = $strategicPlan["starting_year"] - 1;
								$fnyear = $strategicPlan["starting_year"] - 1;
							}


							$editFormAction = $_SERVER['PHP_SELF'];
							if (isset($_SERVER['QUERY_STRING'])) {
								$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
							}

							if (isset($_SESSION['MM_Username'])) {
								$user_name = $_SESSION['MM_Username'];
							}

							$query_years = $db->prepare("SELECT * FROM tbl_fiscal_year");
							$query_years->execute();

							$q1startdate = "07-01";
							$q1enddate = "09-30";
							$q2startdate = "10-01";
							$q2enddate = "12-31";
							$q3startdate = "01-01";
							$q3enddate = "03-30";
							$q4startdate = "04-01";
							$q4enddate = "06-30";

							$query_sector = $db->prepare("SELECT indicator_sector,stid,sector FROM tbl_indicator i inner join tbl_sectors s on s.stid=i.indicator_sector WHERE i.indicator_category = 'Output' AND s.deleted='0' GROUP BY stid ORDER BY stid");
							$query_sector->execute();
							$totalRows_sector = $query_sector->rowCount();
						} catch (PDOException $ex) {
							$result = flashMessage("An error occurred: " . $ex->getMessage());
							echo $result;
						}
						?>
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
								<a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
								<a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                <!-- <a href="portfolios.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a> -->
								<a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
								<a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
								<a href="" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px" disabled>Implementation Matrix</a>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="header">
										<div class="row clearfix" style="margin-top:5px">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
													Go Back
												</button>
											</div>
										</div>
									</div>
									<div class="body" style="margin-top:5px">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label class="control-label"><strong><?= $planlabel ?>:</strong></label>
													<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
														<strong><?php echo $plan; ?></strong>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label class="control-label">Vision:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
															<strong><?php echo $vision; ?></strong>
														</div>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label class="control-label">Mission:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
															<strong><?php echo $mission; ?></strong>
														</div>
													</div>
												</div>
											</div>
											<script>
												$(function() {
													$('#dynamic_select').on('change', function() {
														var url = $(this).val();
														if (url) {
															window.location = url;
														}
														return false;
													});
												});
											</script>
										</div>

										<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
											<?php
											if ($totalRows_sector > 0) {
												$no = 0;
												while ($sector_rows = $query_sector->fetch()) {
													$sector = $sector_rows["sector"];
													$stid = $sector_rows["stid"];

													$query_ind = $db->prepare("SELECT indid, indicator_name, indicator_unit FROM tbl_indicator WHERE indicator_sector='$stid' AND indicator_category = 'Output' AND baseline=1");
													$query_ind->execute();
													$totalRows_ind = $query_ind->rowCount();

													$no = $no + 1;
													$baseyear = "";
													?>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> <?= $ministrylabel ?>: </strong><?= $sector ?>
														</legend>
														<div class="row clearfix">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																		<thead>
																			<tr class="bg-light-blue">
																				<th colspan="" rowspan="2" style="width:3%">#</th>
																				<th colspan="" rowspan="2">Output&nbsp;Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																				<?php
																				$query_spyr =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan'");
																				$query_spyr->execute();
																				$row_spyr = $query_spyr->fetch();
																				$totalRows_spyr = $query_spyr->rowCount();

																				$spyear = $row_spyr["years"];
																				$spfinyear = $row_spyr["starting_year"];

																				$opfinyears = [];
																				for ($i = 1; $i <= $spyear; $i++) {
																					//$spfnyear = $spfnyear + 1;
																					$spfnyr = $spfinyear + $i - 1;
																					$opfinyears[] = $spfnyr;
																					$spfnyear34 = $spfinyear + $i;
																					$fiscalyear = $spfnyr . "/" . $spfnyear34;

																					$url = $fiscalyear;
																					?>
																					<th colspan="2"><?= $url ?></a></th>
																				<?php } ?>
																				<th colspan="" rowspan="2">Total&nbsp;Budget</th>
																			</tr>
																			<tr class="bg-light-blue">
																				<?php
																				$query_stratplanyr =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan'");
																				$query_stratplanyr->execute();
																				$row_stratplanyr = $query_stratplanyr->fetch();
																				$totalRows_stratplanyr = $query_stratplanyr->rowCount();

																				$spyears = $row_stratplanyr["years"];
																				$spfnyear = $row_stratplanyr["starting_year"];

																				$opfinyears = [];
																				for ($i = 1; $i <= $spyears; $i++) {
																					//$spfnyear = $spfnyear + 1;
																					$spfnyr = $spfnyear + $i - 1;
																					$opfinyears[] = $spfnyr;
																					$spfnyear34 = $spfnyear + $i;
																					$fiscalyear = $spfnyr . "/" . $spfnyear34;
																					?>
																					<th>Baseline </th>
																					<th>Target </th>
																				<?php
																				} ?>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			if ($totalRows_ind > 0) {
																				$nm = 0;
																				while ($row_ind = $query_ind->fetch()) {
																					$nm = $nm + 1;
																					$indicator = $row_ind["indicator_name"];
																					$unitid = $row_ind["indicator_unit"];
																					$indid = $row_ind["indid"];
																					$target = 0;
																					$basevalue = 0;
																					$baseyear = "N/A";

																					$query_indunit =  $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unitid'");
																					$query_indunit->execute();
																					$row_indunit = $query_indunit->fetch();

																					if ($row_indunit) {
																						$unit = $row_indunit["unit"];
																					}

																					$query_indbaseyr =  $db->prepare("SELECT yr FROM tbl_indicator_baseline_years b inner join  tbl_fiscal_year y on y.id=b.year WHERE indid='$indid'");
																					$query_indbaseyr->execute();
																					$totalRows_baseyr = $query_indbaseyr->rowCount();
																					if ($totalRows_baseyr > 0) {
																						$row_indbaseyr = $query_indbaseyr->fetch();
																						$baseyear = $row_indbaseyr["yr"];
																					}

																					$query_indbaseline =  $db->prepare("SELECT SUM(value) as baseline FROM tbl_indicator_output_baseline_values WHERE indid='$indid'");
																					$query_indbaseline->execute();
																					$row_indbaseline = $query_indbaseline->fetch();
																					$basevalue = $row_indbaseline["baseline"];
																					if ($basevalue > 0) {
																						$basevalue = number_format($basevalue);
																					} else {
																						$basevalue = 0;
																					}

																					$budget = 0;
																					$query_indbudget =  $db->prepare("SELECT SUM(d.budget) AS budget FROM tbl_progdetails d left join tbl_programs g on g.progid=d.progid WHERE indicator='$indid' AND strategic_plan='$stplan'");
																					$query_indbudget->execute();
																					$totalRows_indbudget = $query_indbudget->rowCount();
																					if ($totalRows_indbudget > 0) {
																						$row_indbudget = $query_indbudget->fetch();
																						$budget = number_format($row_indbudget["budget"], 2);
																					}
																					?>
																					<tr class="bg-lime">
																						<input name="indicators[]" type="hidden" value="<?= $indid ?>" />
																						<td class="bg-light-green" style="width:3%"><?php echo $no . "." . $nm ?></td>
																						<td class="bg-lime">
																							<font color="#000"><?php echo $unit . " of " . $indicator; ?></font>
																						</td>
																						<?php
																						$spfinyear = $row_stratplanyr["years"];
																						for ($k = 0; $k < $spfinyear; $k++) {
																							$year = $spfnyear + $k;
																							$targetraw = get_strategic_plan_yearly_target($indid, $year);
																							$basevalue = get_strategic_plan_yearly_baseline($indid, $year);


																							//if (!empty($targetraw)) {
																								$target = get_strategic_plan_yearly_target($indid, $year);

																								echo '
																								<td style="color: black;" class="text-center bg-lime"><font color="#f7070b"><strong>' . $basevalue . '</strong></font></td>
																								<td style="color: black;" class="text-center bg-lime">' . number_format($target) . '</td>';
																								unset($target);
																							//}
																						}
																						?>
																						<td class="bg-lime text-center">
																							<font color="#000"><?php echo $budget; ?></font>
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
														</div>
													</fieldset>
												<?php
												}
											} else {
												?>
												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong>!!!!</strong><?= $spobjective ?>
													</legend>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<font color="red">Sorry no record found for the selected financial year (<?= $annualplanyear ?>)!!</font>
													</div>
												</fieldset>
											<?php
											}
											?>
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
?>
<script>
	function update_total(indicator_id) {
		var total = 0;
		$(".indicator" + indicator_id).each(function() {
			if ($(this).val() != "") {
				total += parseInt($(this).val());
			}
		});
		$("#indtotaltarget" + indicator_id).html(total);
	}
	$(document).ready(function() {
		$('.dataTable').DataTable();
	});

	function openInNewTab(url) {
		window.open(url, '_blank').focus();
	}
</script>