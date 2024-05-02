<?php
try {
	require('includes/head.php');
	if ($permission) {
		$query_risks_register = $db->prepare("SELECT * FROM tbl_risk_register g left join tbl_projrisk_categories c on c.catid=g.risk_category WHERE active=1 ORDER BY category ASC");
		$query_risks_register->execute();
		$totalRows_risks_register = $query_risks_register->rowCount();
?>
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon ?>
						<?php
						echo $pageTitle;
						if (in_array("create", $page_actions)) {
						?>
							<div class="btn-group" style="float:right">
								<a type="button" data-toggle="modal" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-primary" style="margin-right: 10px;">
									Add Risk
								</a>
							</div>
						<?php } ?>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<?= $results; ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="header">
							</div>
							<div class="body">
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr id="colrow">
															<th style="width:3%" align="center">#</th>
															<th style="width:55%">Risk</th>
															<th style="width:15%">Category</th>
															<th style="width:15%">Risk Level</th>
															<th style="width:12%">Action</th>
														</tr>
													</thead>
													<tbody>
														<?php
														if ($totalRows_risks_register > 0) {
															$counter = 0;
															while ($rows_risks_register = $query_risks_register->fetch()) {
																$counter++;
																$rskid = $rows_risks_register['id'];
																$category = $rows_risks_register['category'];
																$risk = $rows_risks_register['risk_description'];

																$query_projects_risks = $db->prepare("SELECT * FROM tbl_project_risks WHERE risk_id=:riskid");
																$query_projects_risks->execute(array(":riskid" => $rskid));
																$totalrows_projects_risks = $query_projects_risks->rowCount();

																$average_risk_level = $number_monitored = $total_risk_monitored_level = 0;
																while ($rows_projects_risks = $query_projects_risks->fetch()) {
																	$project_risk_id = $rows_projects_risks["id"];
																	$query_risk_monitored = $db->prepare("SELECT risk_level FROM tbl_project_risk_monitoring WHERE riskid=:project_risk_id ORDER BY id DESC Limit 1");
																	$query_risk_monitored->execute(array(":project_risk_id" => $project_risk_id));
																	$row_risk_monitored = $query_risk_monitored->fetch();
																	$total_risk_monitored = $query_risk_monitored->rowCount();

																	if ($total_risk_monitored > 0) {
																		$number_monitored++;
																		$total_risk_monitored_level += $row_risk_monitored["risk_level"];
																	}
																}

																if ($number_monitored > 0) {
																	$average_risk_level = round($total_risk_monitored_level / $number_monitored);

																	//if($average_risk_level > 0){
																	/* $query_risk_level = $db->prepare("SELECT * FROM tbl_risk_severity WHERE digit=:average_risk_level");
																		$query_risk_level->execute(array(":average_risk_level" =>$average_risk_level));
																		$row_risk_level = $query_risk_level->fetch();
																		$total_risk_level = $query_risk_level->rowCount();

																		if($total_risk_level > 0){
																			$risklevel = $row_risk_level["description"];
																			$levelcolor = $row_risk_level["color"]; */
																	if ($average_risk_level >= 0 && $average_risk_level < 4) {
																		$risklevel = "Low";
																		$levelcolor = "bg-green";
																	} elseif ($average_risk_level >= 4 && $average_risk_level < 8) {
																		$risklevel = "Moderate";
																		$levelcolor = "bg-lime";
																	} elseif ($average_risk_level >= 8 && $average_risk_level < 15) {
																		$risklevel = "High";
																		$levelcolor = "bg-orange";
																	} elseif ($average_risk_level >= 15) {
																		$risklevel = "Extreme";
																		$levelcolor = "bg-red";
																	}
																	//}
																	//}
																} else {
																	$risklevel = "Not Determined";
																	$levelcolor = "bg-grey";
																}

														?>
																<tr style="background-color:#FFFFFF">
																	<td align="center"><?= $counter ?></td>
																	<td><?= $risk ?></td>
																	<td><?= $category ?></td>
																	<td align="center" class="<?= $levelcolor ?>">
																		<?php if ($number_monitored > 0) { ?>
																			<a type="button" data-toggle="modal" data-target="#riskInfoModal" id="riskInfoModalBtn" onclick="risk_info(<?= $rskid ?>)" style="text-decoration: none; color:white">
																			<?php } ?>
																			<?= $risklevel . " (" . $average_risk_level . ")" ?>
																			<?php if ($number_monitored > 0) { ?>
																			</a>
																		<?php } ?>
																	</td>
																	<td>
																		<div class="btn-group">
																			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				Options <span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu">
																				<?php if ($number_monitored > 0) { ?>
																					<li>
																						<a type="button" data-toggle="modal" data-target="#riskInfoModal" id="riskInfoModalBtn" onclick="risk_register_info(<?= $rskid ?>)">
																							<i class="fa fa-info"></i> More Info
																						</a>
																					</li>
																					<?php
																				}

																				if ($totalrows_projects_risks == 0) {
																					if (in_array("update", $page_actions)) {
																					?>
																						<li>
																							<a type="button" data-toggle="modal" data-target="#outputItemModal" id="addFormModalBtn" onclick="edit_register_risk(<?= $rskid ?>)">
																								<i class="fa fa-pencil-square"></i> Edit Risk
																							</a>
																						</li>
																					<?php
																					}
																					if (in_array("delete", $page_actions)) {
																					?>
																						<li>
																							<a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="destroy_register_risk(<?= $rskid ?>)">
																								<i class="fa fa-trash-o"></i> Delete Risk
																							</a>
																						</li>
																				<?php
																					}
																				}
																				?>
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
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>

		<!-- Start Risks -->
		<div class="modal fade" tabindex="-1" role="dialog" id="outputItemModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#03A9F4">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" style="color:#fff" align="center" id="edit-modal-title"><i class="fa fa-info-circle" style="color:orange"></i> Define Risk</h4>
					</div>
					<form class="form-horizontal" id="add_risk_register" action="" method="POST">
						<div class="modal-body">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Details </legend>
								<div class="row" id="risk_details">
									<?php
									$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
									$query_risk_categories->execute();
									?>
									<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Category</label>
											<select name="risk_category" id="risk_category" class="form-control" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
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
											<input name="risk" id="risk" class="form-control" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Describe the risk" required>
										</div>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="modal-footer">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<input type="hidden" name="save_risk" id="save_risk" value="save_risk">
								<input type="hidden" name="riskid" id="riskid">
								<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
								<input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="add_risk_form_submit" value="Save" />
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
					<div class="modal-body" id="risk_more_info">

					</div>

					<div class="modal-footer">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<button type="button" class="btn btn-info waves-effect waves-light" data-dismiss="modal"> Close</button>
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

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script>
	const ajax_url = "ajax/risk/index";
</script>

<script src="assets/js/risk/index.js"></script>