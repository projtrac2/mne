<?php
$decode_stplanid = (isset($_GET['plan']) && !empty($_GET["plan"])) ? base64_decode($_GET['plan']) : "";
$stplanid_array = explode("strplan1", $decode_stplanid);
$stplan = $stplanid_array[1];
$stplane = $_GET['plan'];

require('includes/head.php');
if ($permission) {
	try {
		// get project risks
		$query_strategic_plan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id=:stplan");
		$query_strategic_plan->execute(array(":stplan" => $stplan));
		$row_strategic_plan = $query_strategic_plan->fetch();
		$currentplan = $row_strategic_plan ? $row_strategic_plan["plan"] : "";
		$currentplanid = $row_strategic_plan ? $row_strategic_plan["id"] : "";

		function get_source_categories()
		{
			global $db;
			$query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_funding_type");
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
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
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
									<span class="label bg-black" style="font-size:18px"><img src="assets/images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu </span>
									<a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
									<a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
									<a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
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
											<th width="10%">Financial Year</th>
											<th width="8%">Status</th>
											<th width="8%">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$currentyr = date("Y");
										$nextyr = $currentyr + 1;
										$currentfy = $currentyr . "/" . $nextyr;
										$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid WHERE g.program_type=1 and g.strategic_plan=:sp ORDER BY `projplanstatus`, `projfscyear` ASC");
										$sql->execute(array(":sp" => $stplan));
										$rows_count = $sql->rowCount();

										if ($rows_count > 0) {
											$active = "";
											$sn = 0;
											$plan = base64_encode("strplan1{$stplan}");
											while ($row = $sql->fetch()) {
												$projid = $row['projid'];
												$stid = $row['projsector'];
												$program_type = $row['program_type'];
												$username = $row['user_name'];
												$project_department = $row['projsector'];
												$project_section = $row['projdept'];
												$budget = $row['projcost'];
												$project_directorate = $row['directorate'];
												$projid_hashed = base64_encode("projid54321{$projid}");

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
												$srcfyear = $row["projfscyear"];

												//get program and department
												$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
												$prog->execute(array(":progid" => $progid));
												$rowprog = $prog->fetch();
												$projdept = $rowprog["projdept"];

												//get financial year
												$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
												$query_projYear->execute(array(":srcfyear" => $srcfyear));
												$rowprojYear = $query_projYear->fetch();
												$projYear  = $rowprojYear['year'];
												$yr  = $rowprojYear['yr'];

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

												if ($totalRows_adp == 1) {
													$status = $row_adp["year"] . " ADP";
													if ($adpstatus == 1) {
														$active = '<label class="label label-success" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Approved" >' . $status . '</label>';
													} else {
														$active = '<label class="label label-primary" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending Approval" >' . $status . '</label>';
													}

													if (in_array("remove_from_adp", $page_actions) && $adpstatus == 0) {
														$button .= '<li><a type="button" onclick="remove_from_adp(' . $projid . ')"> <i class="glyphicon glyphicon-edit"></i> Remove from ADP</a></li>';
													}
												} else {
													$query_outputs =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid");
													$query_outputs->execute(array(":projid" => $projid));
													$total_rows_outputs = $query_outputs->rowCount();
													$status = $total_rows_outputs == 0 ? "Pending Output/s" : "Pending ADP";
													$labelclass = $total_rows_outputs == 0 ? "label-danger" : "label-warning";

													$active = '<label class="label ' . $labelclass . '" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending ADP">' . $status . '</label>';
													$button =  '';
													if ($adpstatus == 0) {
														$query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid=:projid ");
														$query_rsOutput->execute(array(":projid" => $projid));
														$totalRows_rsOutput = $query_rsOutput->rowCount();
														if ($totalRows_rsOutput > 0) {
															if ($currentyr <= $yr) {
																if (in_array("add_to_adp", $page_actions)) {
																	$button .= '<li><a type="button" onclick="add_to_adp(' . $details . ')"><i class="glyphicon glyphicon-plus"></i> Add to ADP</a></li>';
																}
															} else {
																$button .= '<li><a type="button" data-toggle="modal" data-target="#fyItemModal" id="fyItemModalBtn" onclick="adjustFy(' . $details . ')"> <i class="glyphicon glyphicon-calendar"></i> Adjust Output FY</a></li>';
															}

															if (in_array("create", $page_actions)) {
																$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-pencil"></i> Edit Outputs</a></li>';
															}
														} else {
															if (in_array("update", $page_actions)) {
																$button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="add-project?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
															}

															if (in_array("create", $page_actions)) {
																$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-plus"></i>Add Outputs</a></li>';
															}

															if (in_array("delete", $page_actions)) {
																$button .= '<li><a type="button" id="removeItemModalBtn" onclick="removeItem(' . $projid . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>';
															}
														}
													}
												}
												$filter_department = view_record($project_department, $project_section, $project_directorate);
												if ($filter_department) {
													$sn++;
										?>
													<tr>

														<td><?= $sn ?> </td>
														<td><?= $projname ?> </td>
														<td><?= $progname ?> </td>
														<td><?= $sector ?> </td>
														<td><?= number_format($budget, 2) ?> </td>
														<td><?= $projYear ?> </td>
														<td><?= $active ?> </td>
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
																			<i class="glyphicon glyphicon-file"></i> More
																		</a>
																	</li>
																	<?= $button ?>
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

require('includes/footer.php');
?>

<script>
	const details = {
		partner_roles: '<?= $partner_roles ?>',
		source_categories: '<?= $source_categories ?>',
	}
</script>
<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
<script src="assets/js/projects/view-project.js"></script>