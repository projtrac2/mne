<?php
try {
	require('includes/head.php');
	if ($permission &&  (isset($_GET['projid']) && !empty($_GET["projid"]))) {
		$decode_projid =  base64_decode($_GET['projid']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];

		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_strategic_plan_programs s ON s.id=p.strategic_plan_program_id WHERE deleted='0' and projid=:projid AND projstage=:workflow_stage");
		$query_rsProjects->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();

		if ($totalRows_rsProjects > 0) {
			$projid = $row_rsProjects['projid'];
			$projcode = $row_rsProjects['projcode'];
			$projname = $row_rsProjects['projname'];
			$projstage = $row_rsProjects['projstage'];
			$strategic_plan_id = $row_rsProjects['strategic_plan_id'];
			$project_sub_stage =  $row_rsProjects['proj_substage'];
			$project_directorate = $row_rsProjects['directorate'];
			$strategic_plan_id = $row_rsProjects['strategic_plan_id'];

			$redirect_url = "strategic-plan-projects?plan=" . base64_encode("strplan1{$strategic_plan_id}");

			$query_proj_risks = $db->prepare("SELECT *, r.id AS riskid FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category WHERE projid=:projid GROUP BY r.id");
			$query_proj_risks->execute(array(":projid" => $projid));
			$totalRows_proj_risks = $query_proj_risks->rowCount();

			$query_risks_more_details = $db->prepare("SELECT fullname,tt.title, f.frequency FROM tbl_project_risk_details d left join users u on u.userid=d.responsible left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_datacollectionfreq f on f.fqid=d.frequency left join tbl_titles tt on tt.id=t.title WHERE projid=:projid");
			$query_risks_more_details->execute(array(":projid" => $projid));
			$row_risks_more_details = $query_risks_more_details->fetch();
			$totalRows_risks_more_details = $query_risks_more_details->rowCount();


			$frequency = $responsible = "";
			if ($row_risks_more_details) {
				$frequency = $row_risks_more_details["frequency"];
				$responsible = $row_risks_more_details["title"] . "." . $row_risks_more_details["fullname"];
			}

?>
			<section class="content">
				<div class="container-fluid">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<?= $icon ?>
							<?php echo $pageTitle ?>
							<div class="btn-group" style="float:right">
								<a type="button" data-toggle="modal" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-primary" style="margin-right: 10px;">
									Add Risk
								</a>
								<?php
								if ($totalRows_proj_risks > 0) {
								?>
									<a type="button" data-toggle="modal" data-target="#riskResponsibleModal" id="riskResponsibleModalBtnrow" class="btn btn-success" style="margin-right: 10px;">
										Add Other Risk Details
									</a>
								<?php
								}
								?>
								<a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right" style="margin-right:10px;">
									Go Back
								</a>
							</div>
						</h4>
					</div>
					<div class="row clearfix">
						<div class="block-header">
							<?= $results; ?>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="header">
									<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-alt" style="color:green" aria-hidden="true"></i> Project Details</legend>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
											<label class="control-label">Project Code:</label>
											<div class="form-line">
												<input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
											<label class="control-label">Project Name:</label>
											<div class="form-line">
												<input type="text" class="form-control" value=" <?= $projname ?>" readonly>
											</div>
										</div>
										<?php
										if ($totalRows_risks_more_details > 0) {
										?>
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
												<label class="control-label">Risks Monitoring Frequency:</label>
												<div class="form-line">
													<input type="text" class="form-control" value=" <?= $frequency ?>" readonly>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
												<label class="control-label">Risks Monitoring Responsible:</label>
												<div class="form-line">
													<input type="text" class="form-control" value=" <?= $responsible ?>" readonly>
												</div>
											</div>
										<?php
										}
										?>
									</fieldset>
								</div>
								<div class="body">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border row setup-content" style="padding:10px">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exclamation-circle" style="color:#F44336" aria-hidden="true"></i> Project Risks</legend>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr style="background-color:#0b548f; color:#FFF">
																	<th style="width:3%" align="center">#</th>
																	<th style="width:60%">Risk</th>
																	<th style="width:20%">Category</th>
																	<th style="width:12%">Risk Level</th>
																	<th style="width:5%" data-orderable="false">Action</th>
																</tr>
															</thead>
															<tbody>
																<?php
																if ($totalRows_proj_risks > 0) {
																	$counter = 0;
																	while ($row_proj_risks = $query_proj_risks->fetch()) {
																		$counter++;
																		$rskid = $row_proj_risks['riskid'];
																		$category = $row_proj_risks['category'];
																		$risk = $row_proj_risks['risk_description'];
																		$riskleveldigit = $row_proj_risks['risk_level'];

																		$query_risk_level = $db->prepare("SELECT * FROM tbl_risk_severity WHERE digit=:riskleveldigit");
																		$query_risk_level->execute(array(":riskleveldigit" => $riskleveldigit));
																		$row_risk_level = $query_risk_level->fetch();
																		$risklevel = $row_risk_level['description'];
																		$levelcolor = $row_risk_level['color'];
																?>
																		<tr style="background-color:#FFFFFF">
																			<td align="center"><?= $counter ?></td>
																			<td><?= $risk ?></td>
																			<td><?= $category ?></td>
																			<td class="<?= $levelcolor ?>"><?= $risklevel ?></td>
																			<td>
																				<div class="btn-group">
																					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																						Options <span class="caret"></span>
																					</button>
																					<ul class="dropdown-menu">
																						<li>
																							<a type="button" data-toggle="modal" data-target="#riskInfoModal" id="riskInfoModalBtn" onclick="risk_info(<?= $rskid ?>)">
																								<i class="fa fa-info"></i> More Info
																							</a>
																						</li>
																						<li>
																							<a type="button" data-toggle="modal" data-target="#outputItemModal" id="addFormModalBtn" onclick="editrisk(<?= $rskid ?>)">
																								<i class="fa fa-pencil-square"></i> Edit Risk
																							</a>
																						</li>
																						<li>
																							<a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="destroy_task(<?= $rskid ?>)">
																								<i class="fa fa-trash-o"></i> Delete Risk
																							</a>
																						</li>
																					</ul>
																				</div>
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
												<?php
												function check_risk_details()
												{
													global $db, $projid;
													$query_project_risk_details =  $db->prepare("SELECT * from tbl_project_risk_details WHERE projid =:projid");
													$query_project_risk_details->execute(array(":projid" => $projid));
													$row_project_risk_details = $query_project_risk_details->fetch();

													$query_project_risks =  $db->prepare("SELECT * FROM tbl_project_risks WHERE projid =:projid");
													$query_project_risks->execute(array(":projid" => $projid));
													$row_project_risks = $query_project_risks->fetch();

													$query_project_risk_strategic_measures =  $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE projid =:projid");
													$query_project_risk_strategic_measures->execute(array(":projid" => $projid));
													$row_project_risk_strategic_measures = $query_project_risk_strategic_measures->fetch();

													$result = $row_project_risk_details && $row_project_risks && $row_project_risk_strategic_measures ? true : false;
													return $result;
												}

												$proceed = check_risk_details() ? true : false;
												if ($proceed) {
													$approve_details = "{
														projid:$projid,
														workflow_stage:$workflow_stage,
														project_name:'$projname',
														sub_stage:'0',
													}";
												?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
														<button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Proceed</button>
													</div>
												<?php
												}
												?>
											</fieldset>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</section>

			<!-- StartRisks Responsible -->
			<div class="modal fade" tabindex="-1" role="dialog" id="riskResponsibleModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle" style="color:orange"></i> Project's Risks Responsible</h4>
						</div>
						<form class="form-horizontal" id="add_responsible" action="" method="POST">
							<?= csrf_token_html(); ?>
							<div class="modal-body">
								<fieldset class="scheduler-border" id="milestone_div">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Details </legend>
									<div class="row" id="details">
										<?php
										$query_risk_monitoring_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
										$query_risk_monitoring_frequency->execute();

										$query_risk_responsible = $db->prepare("SELECT *, tt.title AS user_title FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title where t.disabled=0");
										$query_risk_responsible->execute();
										?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Monitoring Frequency</label>
												<select name="frequency" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
													<option value="">.... Select Frequency ....</option>
													<?php
													while ($row_risk_monitoring_frequency = $query_risk_monitoring_frequency->fetch()) {
													?>
														<font color="black">
															<option value="<?php echo $row_risk_monitoring_frequency['fqid'] ?>"><?php echo $row_risk_monitoring_frequency['frequency'] ?></option>
														</font>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Monitoring Responsible</label>
												<select name="responsible" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
													<option value="">.... Select Responsible ....</option>
													<?php
													while ($row_risk_responsible = $query_risk_responsible->fetch()) {
														$fullname = $row_risk_responsible['user_title'] . '.' . $row_risk_responsible['fullname'];
													?>
														<font color="black">
															<option value="<?php echo $row_risk_responsible['userid'] ?>"><?php echo $fullname ?></option>
														</font>
													<?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</fieldset>
							</div>

							<div class="modal-footer">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
									<input type="hidden" name="store_responsible" id="store_responsible" value="responsible">
									<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="responsible-form-submit" value="Save" />
									<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
								</div>
							</div>
						</form>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<!-- End Risks Responsible -->

			<!-- Start Risks -->
			<div class="modal fade" tabindex="-1" role="dialog" id="outputItemModal">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle" style="color:orange"></i> Project's Potential Risks</h4>
						</div>
						<form class="form-horizontal" id="add_risk" action="" method="POST">
							<?= csrf_token_html(); ?>
							<div class="modal-body">
								<fieldset class="scheduler-border" id="milestone_div">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Details </legend>
									<div class="row" id="risk_details">
										<?php
										$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
										$query_risk_categories->execute();

										$query_risk_likelihood = $db->prepare("SELECT * FROM tbl_risk_probability where active=1");
										$query_risk_likelihood->execute();

										$query_risk_impact = $db->prepare("SELECT * FROM tbl_risk_impact where active=1");
										$query_risk_impact->execute();

										$query_risk_strategy = $db->prepare("SELECT * FROM tbl_risk_strategy where active=1");
										$query_risk_strategy->execute();

										$query_risk_monitoring_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
										$query_risk_monitoring_frequency->execute();

										$query_risk_responsible = $db->prepare("SELECT *, tt.title AS user_title FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title where t.disabled=0");
										$query_risk_responsible->execute();
										?>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Category</label>
												<select name="risk_category" id="risk_category" class="form-control require" onchange="category_risks();" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
													<option value="">.... Select Category ....</option>
													<?php
													while ($row_risk_categories = $query_risk_categories->fetch()) {
													?>
														<font color="black">
															<option value="<?php echo $row_risk_categories['catid'] ?>"><?php echo $row_risk_categories['category'] ?></option>
														</font>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Description</label>
												<select name="risk_id" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" id="risk_id" required>
													<option value="">.... First Select Risk Category ....</option>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Likelihood</label>
												<select name="likelihood" id="likelihood" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
													<option value="">.... Select Likelihood ....</option>
													<?php
													while ($row_risk_likelihood = $query_risk_likelihood->fetch()) {
													?>
														<font color="black">
															<option value="<?php echo $row_risk_likelihood['id'] ?>"><?php echo $row_risk_likelihood['description'] ?></option>
														</font>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Impact</label>
												<select name="impact" id="impact" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
													<option value="">.... Select Impact ....</option>
													<?php
													while ($row_risk_impact = $query_risk_impact->fetch()) {
													?>
														<font color="black">
															<option value="<?php echo $row_risk_impact['id'] ?>"><?php echo $row_risk_impact['description'] ?></option>
														</font>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
											<div class="form-inline">
												<label for="">Risk Level</label>
												<input name="risk_level" type="hidden" id="severity" required>
												<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">Auto Calculated</div>
											</div>
										</div>
									</div>
								</fieldset>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Risk Strategic Measures </legend>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="projRiskTable">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="tasks_table" style="width:100%">
												<thead>
													<tr>
														<th width="5%">#</th>
														<th width="90%">Measure</th>
														<th width="5%">
															<button type="button" name="addplus" id="addplus_output" onclick="add_row_items();" class="btn btn-success btn-sm addplus_output">
																<span class="glyphicon glyphicon-plus">
																</span>
															</button>
														</th>
													</tr>
												</thead>
												<tbody id="tasks_table_body">
													<tr></tr>
													<tr id="hideinfo2" align="center">
														<td colspan="5">Add Strategic Measures!!</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</fieldset>
							</div>

							<div class="modal-footer">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
									<input type="hidden" name="store_risk" id="store_risk" value="addrisk">
									<input type="hidden" name="riskid" id="riskid">
									<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
									<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
								</div>
							</div>
						</form>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<!-- End Add Risks -->

			<!-- Start Risk More -->
			<div class="modal fade" tabindex="-1" role="dialog" id="riskInfoModal">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle" style="color:orange"></i> Risk More Info</h4>
						</div>
						<div class="modal-body">
							<fieldset class="scheduler-border" id="milestone_div">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Details </legend>
								<div class="row" id="risk_more_info">

								</div>
							</fieldset>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Strategic Measures </legend>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="measures_table" style="width:100%">
											<thead>
												<tr>
													<th width="5%">#</th>
													<th width="95%">Measure</th>
												</tr>
											</thead>
											<tbody id="risk_measures">
											</tbody>
										</table>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="modal-footer">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<!-- End Item more -->
<?php
		} else {
			$results =  restriction();
			echo $results;
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

<script>
	const ajax_url = "ajax/risk/index";
	const redirect_url = '<?= $redirect_url ?>';
</script>

<script src="assets/js/risk/index.js"></script>
<script src="assets/js/master/index.js"></script>