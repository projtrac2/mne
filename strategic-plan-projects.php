<?php
try {
	require('includes/head.php');
	if ($permission && (isset($_GET['plan']) && !empty($_GET["plan"]))) {
		$decode_stplanid =   base64_decode($_GET['plan']);
		$stplanid_array = explode("strplan1", $decode_stplanid);
		$stplan = $stplanid_array[1];
		$stplane = $_GET['plan'];

		// get project risks
		$query_strategic_plan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id=:stplan");
		$query_strategic_plan->execute(array(":stplan" => $stplan));
		$row_strategic_plan = $query_strategic_plan->fetch();

		if ($row_strategic_plan) {
			$currentplan = $row_strategic_plan["plan"];
			$currentplanid = $row_strategic_plan["id"];

			function get_source_categories()
			{
				global $db;
				$query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_financier_type WHERE status=1");
				$query_rsFunding_type->execute();
				$totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
				$input = '';
				if ($totalRows_rsFunding_type > 0) {
					while ($row_rsFunding_type = $query_rsFunding_type->fetch()) {
						$input .= '<option value="' . $row_rsFunding_type['id'] . '"> ' . $row_rsFunding_type['type'] . '</option>';
					}
				}
				return $input;
			}

			function get_partners()
			{
				global $db;
				$query_rsParners =  $db->prepare("SELECT * FROM tbl_partners WHERE active=1");
				$query_rsParners->execute();
				$totalRows_rsParners = $query_rsParners->rowCount();
				$input = '';
				if ($totalRows_rsParners > 0) {
					while ($row_rsParners = $query_rsParners->fetch()) {
						$input .= '<option value="' . $row_rsParners['id'] . '"> ' . $row_rsParners['partner'] . '</option>';
					}
				}
				return $input;
			}

			function get_partner_roles()
			{
				global $db;
				$query_rsParners =  $db->prepare("SELECT * FROM tbl_partner_roles");
				$query_rsParners->execute();
				$totalRows_rsParners = $query_rsParners->rowCount();
				$input = '';
				if ($totalRows_rsParners > 0) {
					while ($row_rsParners = $query_rsParners->fetch()) {
						$input .= '<option value="' . $row_rsParners['id'] . '"> ' . $row_rsParners['role'] . '</option>';
					}
				}
				return $input;
			}

			$source_categories = get_source_categories();
			$partner_roles  = get_partner_roles();
			$partners = get_partners();

			function mne_plan($projevaluation, $projimpact, $monitoring_frequency)
			{
				global $db, $projid;
				$query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
				$query_rsOutput->execute(array(":projid" => $projid));
				$totalRows_rsOutput = $query_rsOutput->rowCount();
				$output = $totalRows_rsOutput > 0 ? true : false;


				$outcome = true;
				if ($projevaluation == 1) {
					$sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
					$sql->execute(array(":projid" => $projid));
					$rows_count = $sql->rowCount();
					$outcome = $rows_count > 0 ? true : false;
				}

				$impact = true;
				if ($projimpact == 1) {
					$sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
					$sql->execute(array(":projid" => $projid));
					$row_count = $sql->rowCount();
					$impact = $row_count > 0 ? true : false;
				}

				$result = false;

				if ($projevaluation == 1) {
					if ($projimpact == 1) {
						$result = $output || $outcome || $impact || $monitoring_frequency != '' ? true : false;
					} else {
						$result = $output || $outcome  || $monitoring_frequency != '' ? true : false;
					}
				} else {
					$result = $output  || $monitoring_frequency != '' ? true : false;
				}

				return $result;
			}

			function check_risk_details($projid)
			{
				global $db;
				$query_project_risk_details =  $db->prepare("SELECT * from tbl_project_risk_details WHERE projid =:projid");
				$query_project_risk_details->execute(array(":projid" => $projid));
				$row_project_risk_details = $query_project_risk_details->fetch();

				$query_project_risks =  $db->prepare("SELECT * FROM tbl_project_risks WHERE projid =:projid");
				$query_project_risks->execute(array(":projid" => $projid));
				$row_project_risks = $query_project_risks->fetch();

				$query_project_risk_strategic_measures =  $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE projid =:projid");
				$query_project_risk_strategic_measures->execute(array(":projid" => $projid));
				$row_project_risk_strategic_measures = $query_project_risk_strategic_measures->fetch();

				return $row_project_risk_details || $row_project_risks || $row_project_risk_strategic_measures ? true : false;
			}
?>
			<!-- start body  -->
			<section class="content">
				<div class="container-fluid">
					<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
						<h4 class="contentheader">
							<i class="fa fa-columns" aria-hidden="true"></i>
							<?php echo $pageTitle ?>
							<div class="btn-group" style="float:right">
								<div class="btn-group" style="float:right">
									<button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
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
								<div class="card-header">
									<div class="header" style="padding-bottom:0px">
										<div class="button-demo" style="margin-top:-15px">
											<a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
											<a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
											<a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
											<!-- <a href="portfolios.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a> -->
											<a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
											<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
											<a href="strategic-plan-implementation-matrix.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Implementation Matrix</a>
										</div>
									</div>
								</div>
								<div class="body">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr>
													<th width="5%">#</th>
													<th width="20%">Project Name</th>
													<th width="20%">Program Name</th>
													<th width="15%"><?= $ministrylabel ?></th>
													<th width="10%">Budget</th>
													<th width="8%">Stage / Substage / Status</th>
													<th width="8%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$currentyr = date("Y");
												$nextyr = $currentyr + 1;
												$currentfy = $currentyr . "/" . $nextyr;
												$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid  INNER JOIN tbl_strategic_plan_programs s ON  s.progid=g.progid WHERE s.strategic_plan_id=:strategic_plan_id ORDER BY `projplanstatus` ASC");
												$sql->execute(array(":strategic_plan_id" => $stplan));
												$rows_count = $sql->rowCount();
												if ($rows_count > 0) {
													$active = "";
													$sn = 0;
													$plan = base64_encode("strplan1{$stplan}");
													while ($row = $sql->fetch()) {
														$projid = $row['projid'];
														$stid = $row['projsector'];
														$username = $row['user_name'];
														$project_department = $row['projsector'];
														$project_section = $row['projdept'];
														$budget = $row['projcost'];
														$project_directorate = $row['directorate'];
														$projevaluation = $row['projevaluation'];
														$projimpact = $row['projimpact'];
														$monitoring_frequency = $row['monitoring_frequency'];
														$stage_id = $row['stage_id'];
														$child_stage_id = $row['projstage'];
														$status_id = $row['projstatus'];
														$projid_hashed = base64_encode("projid54321{$projid}");
														$project_status = get_all_projects_status($status_id);

														$query_adp =  $db->prepare("SELECT *, p.status as status FROM tbl_annual_dev_plan p inner join tbl_fiscal_year y ON y.id=p.financial_year WHERE projid = :projid");
														$query_adp->execute(array(":projid" => $projid));
														$row_adp = $query_adp->fetch();
														$totalRows_adp = $query_adp->rowCount();
														$adpstatus = $totalRows_adp > 0 ? $row_adp["status"] : "";

														$query_sector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :stid");
														$query_sector->execute(array(":stid" => $stid));
														$row_sector = $query_sector->fetch();

														$projname = htmlspecialchars($row["projname"]);
														$username = $row["user_name"];
														$progid = $row["progid"];

														//get program and department
														$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
														$prog->execute(array(":progid" => $progid));
														$rowprog = $prog->fetch();
														$projdept = $rowprog["projdept"];

														// get department
														$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
														$query_rsDept->execute(array(":sector" => $projdept));
														$row_rsDept = $query_rsDept->fetch();
														$department = $row_rsDept['sector'];
														$totalRows_rsDept = $query_rsDept->rowCount();
														$progname = $rowprog["progname"];
														$sector = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $row_sector["sector"] . '</span>';
														$button = '';
														$details = "{
															plan:'$plan',
															projid:'$projid',
															currentfy:'$currentfy'
														}";


														$stage =  get_project_stage($stage_id);
														$substage =  get_project_stage($child_stage_id);
														$filter_department = view_record($project_department, $project_section, $project_directorate);
														$add_mne_plan =	mne_plan($projevaluation, $projimpact, $monitoring_frequency);
														$add_risk_plan = check_risk_details($projid);
														if ($filter_department) {
															$sn++;
												?>
															<tr>
																<td><?= $sn ?> </td>
																<td><?= $projname ?> </td>
																<td><?= $progname ?> </td>
																<td><?= $sector  ?> </td>
																<td><?= number_format($budget, 2) ?> </td>
																<td><strong><?= "Stage:&nbsp;</strong>" . $stage . " <br/> <strong>Substage:&nbsp;</strong>" . $substage . " <br/><strong>Status: </strong>" . $project_status  ?> </td>
																<td>
																	<input type="hidden" name="projname" id="projname<?= $projid ?>" value="<?= $projname ?>">
																	<!-- Single button -->
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			<li>
																				<a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(<?= $projid ?>)">
																					<i class="glyphicon glyphicon-file"></i> More Info
																				</a>
																			</li>
																			<?php
																			if ($stage_id == 0) {
																				if ($child_stage_id == 4) {
																			?>
																					<li>
																						<a type="button" href="add-project-mne-plan.php?projid=<?= $projid_hashed ?>">
																							<i class="glyphicon glyphicon-plus"></i> <?= $add_mne_plan ?  "Edit" : "Add" ?> M&E Plan
																						</a>
																					</li>
																					<li>
																						<a type="button" data-toggle="modal" id="editprogram" href="add-project?projid=<?= $projid_hashed ?>">
																							<i class="glyphicon glyphicon-edit"></i> Edit
																						</a>
																					</li>
																					<li>
																						<a type="button" id="removeItemModalBtn" onclick="removeItem(<?= $projid ?>)">
																							<i class="glyphicon glyphicon-trash"></i> Remove
																						</a>
																					</li>
																				<?php
																				} else if ($child_stage_id == 5) {
																				?>
																					<li>
																						<a type="button" href="add-project-risks.php?projid=<?= $projid_hashed ?>">
																							<i class="glyphicon glyphicon-plus"></i> <?= $add_risk_plan ?  "Edit" : "Add" ?> Risk Plan
																						</a>
																					</li>
																				<?php
																				} else if ($child_stage_id == 6) {
																				?>
																					<li>
																						<a type="button" onclick="add_to_adp(<?= $details ?>)">
																							<i class="glyphicon glyphicon-plus"></i> Add to ADP
																						</a>
																					</li>
																				<?php
																				} else if ($child_stage_id == 7) {
																				?>
																					<li>
																						<a type="button" onclick="remove_from_adp(<?= $projid ?>)">
																							<i class="glyphicon glyphicon-edit"></i> Remove from ADP
																						</a>
																					</li>
																				<?php
																				} else if ($child_stage_id == 8 && $project_type == 0) {
																				?>
																					<li>
																						<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approve_project(<?= $projid ?>)">
																							<i class="fa fa-check-square-o"></i> Add Partners
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
													} // /while

												} // if num_rows
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
			</section>
			<!-- end body  -->

			<!-- Start Modal Item approve -->
			<div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Add Project Partners</h4>
						</div>
						<div class="modal-body" style=" overflow:auto;">
							<div class="div-result">
								<form class="form-horizontal" id="approveItemForm" action="general-settings/action/project-edit-action.php" method="POST">
									<br />
									<div class="col-md-12" id="aproveBody"></div>
									<div class="modal-footer approveItemFooter">
										<div class="col-md-12 text-center">
											<input type="hidden" name="approveitem" id="approveitem" value="1">
											<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
											<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Submit" />
											<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
										</div>
									</div> <!-- /modal-footer -->
								</form> <!-- /.form -->
							</div>
						</div> <!-- /modal-body -->
					</div>
					<!-- /modal-content -->
				</div>
			</div>

			<!-- Start Item more Info -->
			<div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> More Information</h4>
						</div>
						<div class="modal-body" id="moreinfo">
						</div>
						<div class="modal-footer">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<!-- End  Item more Info -->

			<!-- Start Adjust Financial Year -->
			<div class="modal fade" tabindex="-1" role="dialog" id="fyItemModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header" style="background-color:#03A9F4">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-calendar"></i> Adjust Output Financial Year/s</h4>
						</div>
						<div class="modal-body" id="adjustfy">
							<form class="form-horizontal" id="adjustFyForm" action="general-settings/action/project-edit-action.php" method="POST">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label class="control-label">Project Name: <span id="projname"></span></label>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
								</div>
								<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
									<label class="control-label">Project Start Financial Year *:</label>
									<div class="form-line">
										<select name="financialyear" id="financialyear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
											<option value="">.... Select Year from list ....</option>
											<?php
											//get financial years
											$month = date("M");
											$previousyear = ($month > 7 && $month < 12) ? date("Y") : date("Y") - 1;
											$query_financial_year =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr > $previousyear and status=1");
											$query_financial_year->execute();
											while ($row_Years = $query_financial_year->fetch()) {
												$finyearid = $row_Years['id'];
												$yr = $row_Years["yr"];
												$year = $row_Years['year'];
												echo '<option value="' . $yr . '">' . $year . '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
								</div>
								<div class="modal-footer approveItemFooter">
									<div class="col-md-12 text-center">
										<input type="hidden" name="projid" id="projid">
										<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									</div>
								</div> <!-- /modal-footer -->
						</div>
						<div class="modal-footer">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
								<input name="Updatefy" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit-year" value="Update" />
							</div>
						</div>
						</form> <!-- /.form -->
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<!-- End Adjust Financial Year -->
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
	const details = {
		partners: '<?= $partners ?>',
		partner_roles: '<?= $partner_roles ?>',
		source_categories: '<?= $source_categories ?>',
	}
</script>
<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/projects/approve.js"></script>

<!-- <script src="assets/js/master/index.js"></script> -->