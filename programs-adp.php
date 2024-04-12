<?php
require('includes/head.php');
if ($permission) {
	try {
		//get financial years
		$year = date("Y");
		$month = date("m");

		if ($month >= 1 && $month <= 6) {
			$year = $year - 1;
		}
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year where yr='$year'");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$yearid = $row_rsYear["id"];

		// get adps
		$query_gadpyr =  $db->prepare("SELECT *, y.id AS yrid FROM tbl_annual_dev_plan a inner join tbl_fiscal_year y on y.id=a.financial_year inner join tbl_projects p on p.projid=a.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_strategicplan s on s.id=g.strategic_plan WHERE current_plan=1 GROUP BY a.financial_year ORDER BY y.yr ASC");
		$query_gadpyr->execute();
		$row_gadpyr = $query_gadpyr->fetchAll();
		$gadpyr_rows_count = $query_gadpyr->rowCount();

		$currentdatetime = date("Y-m-d H:i:s");
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
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
							<ul class="nav nav-tabs" style="font-size:14px">
								<?php
								if ($gadpyr_rows_count > 0) {
									foreach ($row_gadpyr as $adp) {
										$adpfyid = $adp["financial_year"];
										$adpfy = $adp["year"];
										$adpyr = $adp["yr"];
										$adp = $adpfy . " ADP";

										if ($adpyr == $year) {
											$link = "home";
											$linkclass = "-up bg-green";
											$linkclassbadge = "bg-green";
											$classstatus = "active";
											$classstatusin = " in active";
										} elseif ($adpyr < $year) {
											$link = "menu" . $adpfyid;
											$linkclass = "-down bg-orange";
											$linkclassbadge = "bg-orange";
											$classstatus = "";
											$classstatusin = "";
										} elseif ($adpyr > $year) {
											$link = "menu" . $adpfyid;
											$linkclass = "-down bg-blue";
											$linkclassbadge = "bg-blue";
											$classstatus = "";
											$classstatusin = "";
										}
								?>
										<li class="<?= $classstatus ?>">
											<a data-toggle="tab" href="#<?= $link ?>"><i class="fa fa-caret-square-o<?= $linkclass ?>" aria-hidden="true"></i> <?= $adp ?> &nbsp;<span class="badge <?= $linkclassbadge ?>">||</span></a>
										</li>
								<?php
									}
								}
								?>
							</ul>
						</div>
						<div class="body">
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="table-responsive">
								<div class="tab-content">
									<?php
									if ($gadpyr_rows_count > 0) {
										foreach ($row_gadpyr as $adp) {
											$adpfyid = $adp["financial_year"];
											$adpfy = $adp["year"];
											$adpyr = $adp["yr"];
											$yrid = $adp["yrid"];
											$adp = $adpfy . " ADP";

											if ($adpyr == $year) {
												$link = "home";
												$linkclass = "-up bg-green";
												$linkclassbadge = "bg-green";
												$classstatusin = " in active";
												$color = "green";
											} elseif ($adpyr < $year) {
												$link = "menu" . $adpfyid;
												$linkclass = "-down bg-orange";
												$linkclassbadge = "bg-orange";
												$classstatusin = "";
												$color = "orange";
											} elseif ($adpyr > $year) {
												$link = "menu" . $adpfyid;
												$linkclass = "-down bg-blue";
												$linkclassbadge = "bg-blue";
												$classstatusin = "";
												$color = "blue";
											}
									?>
											<div id="<?= $link ?>" class="tab-pane fade<?= $classstatusin ?>">
												<div style="color:#333; background-color:#EEE; width:100%; height:30px">
													<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:<?= $color ?>"></i> <?= $adp; ?> Programs </h4>
												</div>
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="<?= $linkclassbadge ?>" style="width:100%">
															<th width="3%">#</th>
															<th width="15%"><?= $ministrylabel ?></th>
															<th width="35%">Program</th>
															<th width="14%">Requested Budget (ksh)</th>
															<th width="18%">Approved Budget (ksh)</th>
															<th width="8%">Status</th>
															<th width="8%">No. Projects</th>
															<th width="7%" data-orderable="false">Action</th>
														</tr>
													</thead>
													<tbody>
														<?php
														// get programs adps
														$query_gadps = $db->prepare("SELECT g.* FROM tbl_annual_dev_plan a inner join tbl_projects p on p.projid=a.projid inner join tbl_programs g on g.progid=p.progid WHERE financial_year='$adpfyid' GROUP BY g.progid");
														$query_gadps->execute();
														$rows_count = $query_gadps->rowCount();

														if ($rows_count > 0) {
															//$month = 8;
															$sn = 0;
															while ($row = $query_gadps->fetch()) {
																$progid = $row['progid'];
																$progname = $row["progname"];
																$progsector = $row["projsector"];
																$progdept = $row["projdept"];
																$progstartyear = $row["syear"];
																$progstartyr = $progstartyear + 1;
																$progduration = $row["years"];
																$active = "";
																$buttonunapprov = '';
																$button = '';

																$project_department = $row['projsector'];
																$project_section = $row['projdept'];
																$project_directorate = $row['directorate'];


																//get program sector
																$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
																$query_year->execute(array(":adpyr" => $adpyr));
																$rowyear = $query_year->fetch();
																$year_id = $rowyear["id"];

																//get program budget
																$query_prgbudget =  $db->prepare("SELECT SUM(amount) as budget FROM tbl_adp_projects_budget WHERE progid ='$progid' AND year='$adpfyid'");
																$query_prgbudget->execute();
																$row_prgbudget = $query_prgbudget->fetch();
																$progbudget = number_format($row_prgbudget['budget'], 2);

																//get program sector
																$query_sector = $db->prepare("SELECT stid, sector FROM `tbl_sectors` WHERE stid=:progsector");
																$query_sector->execute(array(":progsector" => $progsector));
																$rowsector = $query_sector->fetch();
																$sector = $rowsector["sector"];

																//get adjusted program based budget
																$query_sum = $db->prepare("SELECT SUM(budget) AS amount FROM `tbl_programs_based_budget` WHERE progid=:progid and finyear=:adpyr");
																$query_sum->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																$rowsum = $query_sum->fetch();
																$amount = number_format($rowsum["amount"], 2);

																// get department
																$query_dept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE stid=:progdept");
																$query_dept->execute(array(":progdept" => $progdept));
																$row_dept = $query_dept->fetch();
																$department = $row_dept['sector'];
																//$adpfyid = 2021;

																$ministry = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $sector . '</span>';

																// get program annual plan
																$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																$norows_pbb = $query_pbb->rowCount();

																// get program quarterly targets
																$query_pbbtargets =  $db->prepare("SELECT * FROM  tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
																$query_pbbtargets->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																$norows_pbbtargets = $query_pbbtargets->rowCount();

																if ($norows_pbb > 0) {
																	if ($norows_pbbtargets > 0) {
																		$query_projects_count = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid' AND projstage > 7");
																		$query_projects_count->execute();
																		$count_projects_count = $query_projects_count->rowCount();
																		//if (in_array("edit_quarterly_targets", $page_actions) && $count_projects_count == 0) {
																		if ($count_projects_count == 0) {
																			$buttonunapprov .= '
																			<li>
																				<a type="button" data-toggle="modal" id="editquarterlyTargetsModalBtn" data-target="#editquarterlyTargetsModal" onclick="editQuarterlytargets(' . $progid . ', ' . $adpyr . ')">
																					<i class="glyphicon glyphicon-edit"></i> Edit Quarterly Targets
																				</a>
																			</li>';
																		}
																	} else {
																		$query_projects_count = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid' AND projstage > 1");
																		$query_projects_count->execute();
																		$count_projects_count = $query_projects_count->rowCount();
																		//if (in_array("edit_budget", $page_actions) && $count_projects_count == 0) {
																		if ($count_projects_count == 0) {
																			if ($month >= 7 && $month <= 12) {
																				$buttonunapprov .= '
																					<li>
																						<a type="button" data-toggle="modal" id="editItemModalBtns" data-target="#editItemModal" onclick="editPADP(' . $progid . ', ' . $adpyr . ')"> <i class="glyphicon glyphicon-edit"></i> Edit Budget</a>
																					</li>';
																			}
																		}

																		//if (in_array("add_quarterly_targets", $page_actions)) {
																		$buttonunapprov .= '
																			<li>
																				<a type="button" data-toggle="modal" id="quarterlyTargetsModalBtn" data-target="#quarterlyTargetsModal" onclick="addQuarterlytargets(' . $progid . ', ' . $adpyr . ')">
																				<i class="fa fa-plus-square-o"></i> Add Quarterly Targets
																				</a>
																			</li>';
																		//}
																	}
																	$active = "<label class='label label-success'>Adjusted</label>";
																	$button = '<!-- Single button -->
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			' . $buttonunapprov . '
																			<li><a type="button" data-toggle="modal" data-target="#moreInfoModal" id="moreItemModalBtn" onclick="moreInfo(' . $progid . ')"> <i class="glyphicon glyphicon-file"></i> Program Info</a></li>
																		</ul>
																	</div>';
																} else {
																	$buttonunapprov = "";
																	//if (in_array("add_budget", $page_actions)) {
																	if ($month >= 7 && $month <= 12) {
																		$buttonunapprov = '
																			<li>
																				<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approvePADP(' . $progid . ')">
																				<i class="fa fa-check-square-o"></i> Add Approved Budget
																				</a>
																			</li>';
																	}
																	//}

																	$active = "<label class='label label-danger'>Pending</label>";
																	$button = '<!-- Single button -->
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			' . $buttonunapprov . '
																			<li><a type="button" data-toggle="modal" data-target="#moreInfoModal" id="moreItemModalBtn" onclick="moreInfo(' . $progid . ')"> <i class="glyphicon glyphicon-file"></i> Program Info</a></li>
																		</ul>
																	</div>';
																}
																$filter_department = view_record($project_department, $project_section, $project_directorate);
																if ($filter_department) {
																	$sn++;
														?>
																	<tr>
																		<td align="center"><?php echo $sn; ?></td>
																		<td><?php echo $progname; ?></td>
																		<td><?php echo $progbudget; ?></td>
																		<td><?php echo $amount  ?></td>
																		<td><?php echo $ministry; ?></td>
																		<td><?php echo $active; ?></td>
																		<td><?php echo $button; ?></td>
																	</tr>
														<?php
																}
															} // /while
														}
														?>
													</tbody>
												</table>
											</div>
									<?php
										}
									}
									?>
								</div>
							</div>

							<!-- ============================================================== -->
							<!-- End PAge Content -->
							<!-- ============================================================== -->
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<!-- Start Item Delete -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Undo? Approval of Project</h4>
				</div>
				<div class="modal-body">
					<div class="removeItemMessages"></div>
					<p align="center">Are you sure you want to unapprove this Project?</p>
				</div>
				<div class="modal-footer removeProductFooter">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<!-- Start Modal Item approve -->
	<div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Add Approve Program Budget</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="approveItemForm" action="general-settings/action/approve-PADP-action.php" method="POST">
							<br />
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="card" id="aproveBody">

									</div>
								</div>
							</div>
							<div class="modal-footer approveItemFooter">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
									<input type="hidden" name="approveitem" id="approveitem" value="1">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Approve" />
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

	<!-- Start Edit Modal Item approve -->
	<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Approved Program Budget</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="editpbbItemForm" action="general-settings/action/adp-edit-action.php" method="POST">
							<br />
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="card" id="editBody">

									</div>
								</div>
							</div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="editpbbitem" id="editpbbitem" value="1">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
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


	<!-- Start Modal Add Quarterly Targets -->
	<div class="modal fade" id="quarterlyTargetsModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Program Quarterly Targets</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="quarterlyTargetsForm" action="general-settings/action/adp-edit-action.php" method="POST">
							<br />
							<div class="col-md-12" id="quarterlyTargetsBody">

							</div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="quarterlytargets" id="quarterlytargets" value="1">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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

	<!-- Start Modal Add Quarterly Targets -->
	<div class="modal fade" id="editquarterlyTargetsModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Program Quarterly Targets</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="editquarterlyTargetsForm" action="general-settings/action/adp-edit-action.php" method="POST">
							<br />
							<div class="col-md-12" id="editquarterlyTargetsBody">

							</div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="editquarterlytargets" id="editquarterlytargets" value="1">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
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

	<!-- Start Edit Modal Item approve -->
	<div class="modal fade" id="viewQTargetsModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> View Program Quarterly Targets</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<br />
						<div class="col-md-12" id="viewQTargetsBody">

						</div>
						<div class="modal-footer approveItemFooter">
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
							</div>
						</div> <!-- /modal-footer -->
					</div>
				</div> <!-- /modal-body -->
			</div>
			<!-- /modal-content -->
		</div>
	</div>

	<!-- Start Item more -->
	<div class="modal fade" tabindex="-1" role="dialog" id="moreInfoModal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
				</div>
				<div class="modal-body" id="moreinfo">
				</div>
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Item more -->

<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>

<script src="general-settings/js/fetch-programs.js"></script>
<script>
	$(document).ready(function() {
		$('.tables').DataTable();


		// submit approved pbb details
		$("#approveItemForm").submit(function(e) {
			e.preventDefault();
			var form_data = $(this).serialize();
			//console.log(form_data);
			$.ajax({
				type: "post",
				url: "assets/processor/padp-process",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert(response.messages);
						$(".modal").each(function() {
							$(this).modal("hide");
						});
					}
					window.location.reload(true);
				}
			});
		});

		// submit editted approved pbb details
		$("#editpbbItemForm").submit(function(e) {
			e.preventDefault();
			var form_data = $(this).serialize();
			//console.log(form_data);
			$.ajax({
				type: "post",
				url: "assets/processor/padp-process",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert(response.messages);
						$(".modal").each(function() {
							$(this).modal("hide");
						});
					}
					window.location.reload(true);
				}
			});
		});

		// submit program quarterly targets
		$("#quarterlyTargetsForm").submit(function(e) {
			e.preventDefault();
			var form_data = $(this).serialize();
			//console.log(form_data);
			$.ajax({
				type: "post",
				url: "assets/processor/padp-process",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert(response.messages);
						$(".modal").each(function() {
							$(this).modal("hide");
						});
					}
					window.location.reload(true);
				}
			});
		});

		// submit editted program quarterly targets
		$("#editquarterlyTargetsForm").submit(function(e) {
			e.preventDefault();
			var form_data = $(this).serialize();
			//console.log(form_data);
			$.ajax({
				type: "post",
				url: "assets/processor/padp-process",
				data: form_data,
				dataType: "json",
				success: function(response) {
					if (response) {
						alert(response.messages);
						$(".modal").each(function() {
							$(this).modal("hide");
						});
					}
					window.location.reload(true);
				}
			});
		});
	});
	// get the program budget/target div from db
	function approvePADP(progid = null) {
		if (progid) {
			$.ajax({
				type: "post",
				url: "general-settings/action/adp-edit-action",
				data: {
					create_padp_div: "create_padp_div",
					progid: progid
				},
				dataType: "html",
				success: function(response) {
					$("#aproveBody").html(response);
				}
			});
		}
	}

	// get the program budget/target div from db
	function editPADP(progid = null, adpyr = null) {
		if (progid) {
			$.ajax({
				type: "post",
				url: "general-settings/action/adp-edit-action",
				data: {
					edit_padp_div: "edit_padp_div",
					progid: progid,
					adpyr: adpyr
				},
				dataType: "html",
				success: function(response) {
					$("#editBody").html(response);
				}
			});
		}
	}

	// get the program budget/target div from db
	function addQuarterlytargets(progid = null, adpyr = null) {
		//console.log(progid);
		if (progid) {
			$.ajax({
				type: "post",
				url: "general-settings/action/adp-edit-action",
				data: {
					create_qtargets_div: "create_qtargets_div",
					progid: progid,
					adpyr: adpyr
				},
				dataType: "html",
				success: function(response) {
					$("#quarterlyTargetsBody").html(response);
				}
			});
		}
	}

	// get the program budget/target div from db
	function editQuarterlytargets(progid = null, adpyr = null) {
		//console.log(progid);
		if (progid) {
			$.ajax({
				type: "post",
				url: "general-settings/action/adp-edit-action",
				data: {
					edit_qtargets_div: "edit_qtargets_div",
					progid: progid,
					adpyr: adpyr
				},
				dataType: "html",
				success: function(response) {
					$("#editquarterlyTargetsBody").html(response);
				}
			});
		}
	}
</script>