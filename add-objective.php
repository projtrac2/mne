<?php
try {
	require('includes/head.php');
	require('functions/strategicplan.php');
	if ($permission  && (isset($_GET['kra']) && !empty($_GET["kra"]))) {
		$decode_kra =   base64_decode($_GET['kra']);
		$kra_array = explode("kraid", $decode_kra);
		$kraid = $kra_array[1];
		$original_kraid = $_GET['kra'];
		$results_kra = get_kra($kraid);
		if ($results_kra) {
			$kraName = $results_kra['kra'];
			$stplanid = $results_kra['spid'];
			$stplan = base64_encode("strplan1{$stplanid}");

			if (isset($_POST['addplan'])) {
				if (validate_csrf_token($_POST['csrf_token'])) {
					$objective = $_POST['objective'];
					$desc = $_POST['objdesc'];
					$kpi = 1;
					$kraid = $_POST['kraid'];
					$current_date = date("Y-m-d");

					$ObjectivesInsert = $db->prepare("INSERT INTO tbl_strategic_plan_objectives (kraid, objective, description, kpi, created_by, date_created) VALUES (:kraid, :objective, :desc, :kpi, :user, :dates)");
					$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":desc" => $desc, ":kpi" => $kpi, ":user" => $user_name, ":dates" => $current_date));

					if ($resultObjectives) {
						$objectiveid = $db->lastInsertId();
						$stcount = count($_POST["strategic"]);
						for ($cnt = 0; $cnt < $stcount; $cnt++) {
							$strategy = $_POST['strategic'][$cnt];
							$sqlinsert = $db->prepare("INSERT INTO tbl_objective_strategy (objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
							$sqlinsert->execute(array(":objid" => $objectiveid, ":strategy" => $strategy, ":user" => $user_name, ":dates" => $current_date));
						}

						$results = success_message("Strategic Objective added successfully", 2, "view-strategic-plan-objectives.php?plan=$stplan");
					} else {
						$results = error_message("Error occured please try again later", 2, "view-strategic-plan-objectives.php?plan=$stplan");
					}
				} else {
					$results = error_message("Error occured please try again later", 2, "view-strategic-plan-objectives.php?plan=$stplan");
				}
			}
?>
			<!-- start body  -->
			<section class="content">
				<div class="container-fluid">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<?= $icon ?>
							<?php echo $pageTitle ?>
							<div class="btn-group" style="float:right">
								<div class="btn-group" style="float:right">
									<button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">
										Go Back
									</button>
								</div>
							</div>
						</h4>
					</div>
					<div class="row clearfix">
						<div class="block-header">
							<?= $results; ?>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="body">
									<div class="body" id="objective_table"></div>
									<h5 style="background-color:#c7e1e8;line-height:3;padding-left:10px;padding-right:10px;"> <strong>Key Result Area:</strong><u> <?php echo $kraName ?> </u></h5>
									<form action="" method="POST" class="form-inline" role="form" id="stratcplan">
										<?= csrf_token_html(); ?>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategic Objectives. </legend>
											<div class="col-md-12">
												<label class="control-label">Strategic Objective *:</label>
												<div class="form-line">
													<input name="kraid" type="hidden" id="kraid" value="<?php echo $kraid; ?>" />
													<input name="objective" type="text" class="form-control" placeholder="Enter strategic objective" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
												</div>
											</div>
											<div class="col-md-12">
												<label class="control-label">Strategic Objective Description : <font align="left" style="background-color:#eff2f4"> </font></label>
												<p align="left">
													<textarea name="objdesc" cols="45" rows="4" class="txtboxes" id="objdesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Describe strategic objective"></textarea>
													<script>
														CKEDITOR.replace('objdesc', {
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
										</fieldset>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategy(s).
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="card" style="margin-bottom:-20px">
														<div class="header">
															<i class="ti-link"></i>MULTIPLE STRATEGIES - WITH CLICK & ADD
														</div>
														<div class="body">
															<table class="table table-bordered" id="strategy_table">
																<tr>
																	<th style="width:98%">Strategy</th>
																	<th style="width:2%"><button type="button" name="addplus" onclick="add_strow();" title="Add another field" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
																	</th>
																</tr>
																<tr>
																	<td>
																		<input type="text" name="strategic[]" id="strategic" class="form-control" placeholder="Enter a strategy " style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																	</td>
																	<td></td>
																</tr>
															</table>
															<input name="addplan" type="hidden" id="addplan" value="addplan" />
															<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
															<div class="list-inline" align="center" style="margin-top:20px">
																<button type="submit" name="exit" class="btn btn-primary" id="">
																	Save
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
									</form>
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
		}
	} else {
		$results =  restriction();
		echo $results;
	}
	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/strategicplan/view-kra-objective.js"></script>