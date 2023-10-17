<?php 
$year = (isset($_GET['indfy'])) ? $_GET['indfy'] : "";
$quarter = (isset($_GET['quarter'])) ? $_GET['quarter'] : "";
require('includes/head.php'); 
if ($permission) {	
    $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
    $query_rsFscYear->execute(array(":year" => $year));
    $row_rsFscYear = $query_rsFscYear->fetch();
    $totalRows_years = $query_rsFscYear->rowCount();

    $startyear = ($totalRows_years > 0) ? $row_rsFscYear['yr'] : 0;
    $endyear = $startyear + 1;
    $financial_year = $startyear . "/" . $endyear;
    $start = $startyear - 1;
    $b_financial_year = $start . "/" . $startyear;
	
    $query_Sectors = $db->prepare("SELECT * FROM tbl_sectors s left join tbl_projteam2 t on t.department=s.stid left join users u on t.ptid=u.pt_id WHERE u.userid=:userid");
    $query_Sectors->execute(array(":userid" => $user_name));
    $totalRows_Sectors = $query_Sectors->rowCount();
    $Rows_Sectors = $query_Sectors->fetch();
    $sector = ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "All Departments";
	$stid = ($totalRows_Sectors > 0) ? $Rows_Sectors['stid'] : 0;

    $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
    $query_indicators->execute(array(":sector" => $stid));
    $row_indicators = $query_indicators->fetch();
    $totalRows_indicators = $query_indicators->rowCount();

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addReport")) {
        //$stid = $_POST['stid'];
        $year = $_POST['year'];
        $quarter = $_POST['quarter'];
        $appendice = $_POST['appendice'];
        $conclusion = $_POST['conclusion'];
        $challenges = $_POST['challenges'];
        $sector = $_POST['sector'];
        $user_name = $_POST['username'];
        $insertSQL = $db->prepare("INSERT INTO tbl_qapr_report_conclusion (stid,year,quarter, section_comments,challenges,conclusion,appendices,created_by) VALUES (:stid,:year,:quarter, :section_comments,:challenges,:conclusion,:appendices,:created_by)");
        $result  = $insertSQL->execute(array(":stid" => $stid, ":year" => $year, ":quarter" => $quarter, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":created_by" => $user_name));
    }

    $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_qapr_report_conclusion` WHERE year=:year AND stid=:stid AND quarter= :quarter");
    $query_rsConclusion->execute(array(":year" => $year, ":stid" => $stid, ":quarter" => $quarter));
    $Rows_rsConclusion = $query_rsConclusion->fetch();
    $totalRows_rsConclusion = $query_rsConclusion->rowCount();

    $basedate = $startyear . "-06-30";
    $start_date = $end_date = "";
    if ($quarter == 1) {
        $start_date = $startyear . "-07-01";
        $end_date = $startyear . "-09-30";
    } else if ($quarter == 2) {
        $start_date = $startyear . "-10-01";
        $end_date = $startyear . "-12-31";
    } else if ($quarter == 3) {
        $start_date = $endyear . "-01-01";
        $end_date = $endyear . "-03-31";
    } else if ($quarter == 4) {
        $start_date = $endyear . "-04-01";
        $end_date = $endyear . "-06-30";
    }
    $financial_year_date = "Q$quarter (" . $start_date . " to " . $end_date . ")";
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
		  data: { get_dept: sector },
		  dataType: "html",
		  success: function (response) {
			$("#dept").html(response);
		  },
		});
	  }
	}
	
	$(document).ready(function(){
		
		$("#obj").on("change", function () {
			var objid =$(this).val();
			if(objid !=''){
				$.ajax({
					type: "post",
					url: "addProjectLocation.php",
					data: "objid="+objid,
					dataType: "html",
					success: function (response) {
						$("#output").html(response);
					}
				});
			}else{
				$("#output").html('<option value="">... First Select Strategic Objective ...</option>');
			}
		});
	});
	</script>
	<section class="content">	
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> <?= $financial_year ?> QUARTER <?=$quarter?> PROGRESS REPORT</h4>
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
														<a type="button" VALUE="PDF" class="btn btn-warning" target="_blank" href="reports/qpr_report?reportid=<?= base64_encode($Rows_rsConclusion['id']) ?>" id="btnback">
															<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
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
																	do {
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
																			$totalRows_initial_indbasevalue = $query_initial_indbasevalue->rowCount();

																			if ($totalRows_initial_indbasevalue > 0) {
																				$initial_basevalue = $rows_initial_indbasevalue["basevalue"];
																			}

																			$query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE d.indicator=:indid AND m.date_created <= '". $basedate."'");
																			$query_actual_ind_value->execute(array(":indid" => $indid));
																			$rows_actual_ind_value = $query_actual_ind_value->fetch();
																			$totalRows_actual_indbasevalue = $query_actual_ind_value->rowCount();

																			if ($totalRows_actual_indbasevalue > 0) {
																				$actual_basevalue = $rows_actual_ind_value["basevalue"];
																			}

																			$basevalue = $initial_basevalue + $actual_basevalue;

																			$query_annual_target = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator=:indid AND year=:finyear");
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

																			$query_quarter_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created >= '". $start_date ."' AND date_created <= '". $end_date ."')");
																			$query_quarter_one_actual->execute(array(":indid" => $indid));
																			$rows_quarter_one_actual = $query_quarter_one_actual->fetch();
																			$totalRows_quarter_one_actual = $query_quarter_one_actual->rowCount();
																			$quarter_achived =  ($totalRows_quarter_one_actual > 0) ? $rows_quarter_one_actual["achieved"] : 0;

																			/* $query_Indicator = $db->prepare("SELECT * FROM tbl_measurement_units  WHERE id='$unit' ");
																			$query_Indicator->execute();
																			$row = $query_Indicator->fetch();
																			$count_meas = $query_Indicator->rowCount();
																			$unit = $count_meas > 0 ? $row['unit'] : ""; */

																			$quarter_rate = "";
																			if ($totalRows_quarterly_target > 0) {
																				$quarter_target =  $rows_quarterly_target["Q$quarter"];
																				$quarter_1_rate = number_format((($quarter_achived / $quarter_target) * 100), 2);
																				$quarter_rate = $quarter_1_rate . "%";
																			}else{
																				$quarter_rate = number_format(0, 2)."%";
																			}
																		}

																		// startyear endyear

																		$query_indRemarks =  $db->prepare("SELECT * FROM tbl_qapr_report_remarks WHERE indid='$indid' AND year='$year' AND quarter='$quarter'");
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
																	} while ($row_indicators = $query_indicators->fetch());
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
														<input type="hidden" name="quarter" value="<?= $quarter ?>">
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
                                                                <input type="hidden" name="indicator" id="indicator" value="<?= $year ?>">
                                                                <input type="hidden" name="quarter" id="quarter" value="<?= $quarter ?>">
                                                                <input type="hidden" name="addquarterindicatorremarks" value="addquarterindicatorremarks">
                                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal">Cancel</button>
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