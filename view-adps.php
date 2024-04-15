<?php
try {

require('includes/head.php');

if ($permission) {

	$month = date("m");
	$currentYear = ($month > 6 && $month < 13) ? date("Y") : date("Y") - 1;

		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$progid = $_GET["prg"];
		}
		// get adps
		$query_adps =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d left join tbl_fiscal_year y on y.id=d.financial_year GROUP BY financial_year ORDER BY d.id ASC");
		$query_adps->execute();
		$row_adps = $query_adps->fetchAll();
		$totalRows_adps = $query_adps->rowCount();

		$currentdatetime = date("Y-m-d H:i:s");

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

		$source_categories = get_source_categories();
		$partner_roles  = get_partner_roles();
		$partners  = get_partners();

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
								if ($totalRows_adps > 0) {
									foreach ($row_adps as $adp) {
										$adpfyid = $adp["financial_year"];
										$adpfy = $adp["year"];
										$adpyr = $adp["yr"];
										$adp = $adpfy . " ADP PROJECTS";

										$yr = $adpyr;
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

										$startdate = $startyear . "-" . $startmnth . "-01";
										$enddate = $endyear . "-" . $endmnth . "-30";

										$totalcount = 0;

										// count number of on-going projects in the adp
										if ($currentYear == $adpyr) {
											$query_on_going_projs =  $db->prepare("SELECT p.projid AS projid, p.projname AS projname, p.progid AS progid, p.projcategory AS projcategory FROM tbl_projects p left join tbl_annual_dev_plan a on a.projid=p.projid WHERE a.status = 1 AND financial_year < '$adpfyid' AND (p.projstatus=4 OR p.projstatus=11) GROUP BY a.projid");
											$query_on_going_projs->execute();
											$totalRows_on_going_projs = $query_on_going_projs->rowCount();

											if ($totalRows_on_going_projs > 0) {
												while ($Rows_on_going_projs = $query_on_going_projs->fetch()) {
													$ongoing_projid = $Rows_on_going_projs['projid'];
													$progid = $Rows_tasksprojs['progid'];
													//get program and department
													$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
													$prog->execute(array(":progid" => $progid));
													$rowprog = $prog->fetch();
													$project_department = $rowprog['projsector'];
													$project_section = $rowprog['projdept'];
													$project_directorate = $rowprog['directorate'];
													$filter_department = view_record($project_department, $project_section, $project_directorate);

													$query_proj_stasks =  $db->prepare("SELECT end_date FROM tbl_program_of_works WHERE projid = :projid AND (status=4 OR status=11)");
													$query_proj_stasks->execute(array(":projid" => $ongoing_projid));
													$totalRows_proj_stasks = $query_proj_stasks->rowCount();

													if ($totalRows_proj_stasks > 0) {
														$taskcount = 0;
														while ($Rows_proj_stasks = $query_proj_stasks->fetch()) {
															$stask_edate = $Rows_proj_stasks['end_date'];
															if ($stask_edate >= $startdate && $stask_edate <= $enddate) {
																$taskcount++;
															}
														}
														if ($filter_department && $taskcount > 0) {
															$totalcount++;
														}
													}
												}
											}
										}

										// count number of new projects in the adp
										$query_adpprojs =  $db->prepare("SELECT * FROM tbl_annual_dev_plan a left join tbl_fiscal_year y on y.id=a.financial_year left join tbl_projects p on p.projid=a.projid WHERE financial_year=:fy");
										$query_adpprojs->execute(array(":fy" => $adpfyid));
										$totalRows_adpprojs = $query_adpprojs->rowCount();

										if ($totalRows_adpprojs > 0) {
											while ($Rows_adpprojs = $query_adpprojs->fetch()) {
												$progid = $Rows_adpprojs['progid'];
												$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid");
												$prog->execute(array(":progid" => $progid));
												$rowprog = $prog->fetch();
												if ($rowprog) {
													$project_department = $rowprog['projsector'];
													$project_section = $rowprog['projdept'];
													$project_directorate = $rowprog['directorate'];
													$filter_department = view_record($project_department, $project_section, $project_directorate);
													if ($filter_department) {
														$totalcount++;
													}
												}
											}
										}

										// get active adp
										$query_activeadp =  $db->prepare("SELECT * FROM tbl_annual_dev_plan a left join tbl_fiscal_year y on y.id=a.financial_year left join tbl_projects p on p.projid=a.projid WHERE y.sdate <= '$currentdatetime' and y.edate >= '$currentdatetime' AND a.financial_year = '$adpfyid' GROUP BY a.financial_year");
										$query_activeadp->execute();
										$totalRows_activeadp = $query_activeadp->rowCount();
										if ($totalRows_activeadp > 0) {
								?>
											<li class="active">
												<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-up bg-green" aria-hidden="true"></i> <?= $adp ?> &nbsp;<span class="badge bg-green"><?php echo $totalcount; ?></span></a>
											</li>
										<?php
										} else {
										?>
											<li>
												<a data-toggle="tab" href="#menu<?= $adpfyid ?>"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> <?= $adp ?> &nbsp;<span class="badge bg-deep-orange"><?php echo $totalcount; ?></span></a>
											</li>
								<?php
										}
									}
								}
								?>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<?php
								function newprojects($adpfyid)
								{
									global $db;
									// querry new projects in the adp
									$query_adpprojs =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d left join tbl_fiscal_year y on y.id=d.financial_year left join tbl_projects p on p.projid=d.projid WHERE d.financial_year=:fy");
									$query_adpprojs->execute(array(":fy" => $adpfyid));
									$totalRows_adpprojs = $query_adpprojs->rowCount();

									$new_projects = 0;
									if ($totalRows_adpprojs > 0) {
										while ($Rows_adpprojs = $query_adpprojs->fetch()) {
											$progid = $Rows_adpprojs['progid'];
											$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid");
											$prog->execute(array(":progid" => $progid));
											$rowprog = $prog->fetch();
											if ($rowprog) {
												$project_department = $rowprog['projsector'];
												$project_section = $rowprog['projdept'];
												$project_directorate = $rowprog['directorate'];
												$filter_department = view_record($project_department, $project_section, $project_directorate);

												if ($filter_department) {
													$new_projects++;
												}
											}
										}
									}
									return $new_projects;
								}

								if ($totalRows_adps > 0) {
									foreach ($row_adps as $adp) {
										$adpfyid = $adp["financial_year"];
										$adpfy = $adp["year"];
										$adpyr = $adp["yr"];
										$adp = $adpfy . " ADP";

										$yr = $adpyr;
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

										$startdate = $startyear . "-" . $startmnth . "-01";
										$enddate = $endyear . "-" . $endmnth . "-30";

										// get active adp
										$query_activeadpbody =  $db->prepare("SELECT * FROM tbl_annual_dev_plan a left join tbl_fiscal_year y on y.id=a.financial_year WHERE y.sdate <= '$currentdatetime' and y.edate >= '$currentdatetime' AND financial_year = '$adpfyid' GROUP BY a.financial_year");
										$query_activeadpbody->execute();
										$totalRows_activeadpbody = $query_activeadpbody->rowCount();

										if ($totalRows_activeadpbody > 0) {
								?>
											<div id="home" class="tab-pane fade in active">
												<div style="color:#fff; background-color:green; width:100%; height:30px">
													<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px"></i> <?= $adp ?> Projects</h4>
												</div>
												<ul class="nav nav-tabs" style="font-size:14px">
													<?php
													// querry new projects in the adp
													$new_projects = newprojects($adpfyid);

													// querry on-going projects in the adp
													$query_on_going_projs =  $db->prepare("SELECT p.projid AS projid, p.projname AS projname, p.progid AS progid, p.projcategory AS projcategory, projbudget FROM tbl_projects p left join tbl_annual_dev_plan a on a.projid=p.projid WHERE a.status = 1 AND financial_year < '$adpfyid' AND (p.projstatus=4 OR p.projstatus=11) GROUP BY a.projid");
													$query_on_going_projs->execute();
													$totalRows_on_going_projs = $query_on_going_projs->rowCount();
													$Rows_on_going_projs = $query_on_going_projs->fetchAll();
													$ongoing_projects = 0;
													if ($totalRows_on_going_projs > 0) {
														foreach ($Rows_on_going_projs as $taskrow) {
															$ongoing_projid = $taskrow['projid'];
															$progid = $taskrow['progid'];
															//get program and department
															$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
															$prog->execute(array(":progid" => $progid));
															$rowprog = $prog->fetch();
															$project_department = $rowprog['projsector'];
															$project_section = $rowprog['projdept'];
															$project_directorate = $rowprog['directorate'];
															$filter_department = view_record($project_department, $project_section, $project_directorate);

															$query_proj_stasks =  $db->prepare("SELECT end_date FROM tbl_program_of_works WHERE projid = :projid AND (status=4 OR status=11)");
															$query_proj_stasks->execute(array(":projid" => $ongoing_projid));
															$totalRows_proj_stasks = $query_proj_stasks->rowCount();

															if ($totalRows_proj_stasks > 0) {
																$taskcount = 0;
																while ($Rows_proj_stasks = $query_proj_stasks->fetch()) {
																	$stask_edate = $Rows_proj_stasks['end_date'];
																	if ($stask_edate >= $startdate && $stask_edate <= $enddate) {
																		$taskcount++;
																	}
																}
																if ($filter_department && $taskcount > 0) {
																	$ongoing_projects++;
																}
															}
														}
													}
													?>
													<li class="active">
														<a data-toggle="tab" href="#ongoing<?= $adpfyid ?>"><i class="fa fa-refresh bg-green" aria-hidden="true"></i> Ongoing Projects &nbsp;<span class="badge bg-green"><?php echo $ongoing_projects; ?></span></a>
													</li>
													<li>
														<a data-toggle="tab" href="#new<?= $adpfyid ?>"><i class="fa fa-caret-square-o-down bg-blue" aria-hidden="true"></i> New Projects &nbsp;<span class="badge bg-blue"><?php echo $new_projects; ?></span></a>
													</li>
												</ul>
												<div class="tab-content">
													<div id="ongoing<?= $adpfyid ?>" class="tab-pane fade in active">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTableOnGoing">
															<thead>
																<tr style="color:#333; background-color:#EEE;">
																	<th width="5%">#</th>
																	<th width="29%">Project Name</th>
																	<th width="28%">Program Name</th>
																	<th width="10%">Budget (KSH)</th>
																	<th width="12%">Financial Year</th>
																	<th width="8%">Status</th>
																	<th width="8%">Action</th>
																</tr>
															</thead>
															<tbody>
																<?php
																if ($totalRows_on_going_projs > 0) {
																	$nm = 0;
																	$budget = 0;
																	$totalotherscost3 = 0;
																	$totalotherscost4 = 0;
																	foreach ($Rows_on_going_projs as $row) {
																		//while ($row = $query_tasksprojs->fetch()) {
																		$nm++;
																		$projname = $row['projname'];
																		$projid = $row['projid'];
																		$projyear = $adpfy;
																		$projcat = $row['projcategory'];
																		$progid = $row['progid'];
																		$budget = $row['projcost'];
																		$budget = number_format($budget, 2);

																		// get progname
																		$query_progdetails = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
																		$query_progdetails->execute(array(":progid" => $progid));
																		$row_prog = $query_progdetails->fetch();
																		$progname = $row_prog["progname"];

																		// add_to_adp remove_adp edit delete
																		$project_department = $row_prog['projsector'];
																		$project_section = $row_prog['projdept'];
																		$project_directorate = $row_prog['directorate'];

																		//get program targets
																		$query_prog_targets = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
																		$query_prog_targets->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																		$norows_prog_targets = $query_prog_targets->rowCount();

																		// get program annual plan
																		$query_pbb = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																		$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																		$norows_pbb = $query_pbb->rowCount();

																		//check if project has adp budget tbl_adp_projects_budget
																		$query_adp_budget = $db->prepare("SELECT * FROM tbl_adp_projects_budget WHERE projid = :projid AND year = :adpfyid");
																		$query_adp_budget->execute(array(":projid" => $projid, ":adpfyid" => $adpfyid));
																		$row_adp_budget = $query_adp_budget->rowCount();

																		$querybudgetstatus = $db->prepare("SELECT * FROM tbl_project_approved_yearly_budget WHERE projid = :projid AND year = :year");
																		$querybudgetstatus->execute(array(":projid" => $projid, ":year" => $yr));
																		$count_budgetstatus = $querybudgetstatus->rowCount();

																		// status
																		$button = '';
																		$active = "<label class='label label-success'>Approved</label>";
																		if ($row_adp_budget == 0) {
																			$active = "<label class='label label-danger'>Pending</label>";
																			$button .= '
																			<li><a type="button" data-toggle="modal" data-target="#addADPBudgetModal" id="addADPBudgetModalBtn" onclick="addADPBudget(' . $itemId . ',' . $adpfyid . ')"> <i class="glyphicon glyphicon-plus"></i> Add ADP Budget</a></li>
																			';
																		} else {
																			if ($norows_pbb > 0 && $norows_prog_targets > 0 && $count_budgetstatus == 0) {
																				$active = "<label class='label label-danger'>Pending</label>";
																				if ($approve) {
																					$button .= '<li><a type="button" data-toggle="modal" data-target="#approvedBudgetItemModal" id="approvedBudgetItemModalBtn" onclick="approvedBudget(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Approve Budget</a></li>';
																				}
																			}
																		}

																		$action = '<!-- Single button -->
																		<div class="btn-group">
																			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				Options <span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu">
																				<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Project Info</a></li>
																				' . $button . '
																			</ul>
																		</div>';

																		$filter_department = view_record($project_department, $project_section, $project_directorate);
																		if ($filter_department) {
																?>
																			<tr>
																				<td align="center"><?php echo $nm; ?></td>
																				<td><?php echo $projname . " " . $norows_pbb . " " . $norows_prog_targets . " " . $count_budgetstatus . " "; ?></td>
																				<td><?php echo $progname; ?></td>
																				<td><?php echo $budget; ?></td>
																				<td><?php echo $projyear; ?></td>
																				<td><?php echo $active; ?></td>
																				<td><?php echo $action; ?></td>
																			</tr>
																<?php
																		}
																	}
																}
																?>
															</tbody>
														</table>
													</div>

													<div id="new<?= $adpfyid ?>" class="tab-pane fade">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
															<thead>
																<tr style="color:#333; background-color:#EEE; ">
																	<th width="5%">#</th>
																	<th width="30%">Project Name</th>
																	<th width="29%">Program Name</th>
																	<th width="10%">Budget (KSH)</th>
																	<th width="10%">Financial Year</th>
																	<th width="8%">Status</th>
																	<th width="8%">Action</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$sql = $db->prepare("SELECT * FROM `tbl_projects` p left join tbl_annual_dev_plan d ON d.projid=p.projid WHERE d.financial_year=:adpfyid ORDER BY financial_year ASC");
																$sql->execute(array(":adpfyid" => $adpfyid));
																$rows_count = $sql->rowCount();
																if ($rows_count > 0) {
																	// $row = $result->fetch_array();
																	$active = "";
																	$sn = 0;

																	while ($row = $sql->fetch()) {
																		$project_id = $row['projid'];
																		$projname = $row['projname'];
																		$budget = $row['projcost'];
																		$budget = number_format($budget, 2);
																		$progid = $row["progid"];
																		$srcfyear = $row["projfscyear"];
																		$projstage = $row["projstage"];
																		$adpstatus = $row["status"];

																		//get program and department
																		$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
																		$prog->execute(array(":progid" => $progid));
																		$rowprog = $prog->fetch();
																		$projdept = $rowprog["projdept"];

																		// add_to_adp remove_adp edit delete
																		$project_department = $rowprog['projsector'];
																		$project_section = $rowprog['projdept'];
																		$project_directorate = $rowprog['directorate'];

																		//get financial year
																		$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
																		$query_projYear->execute(array(":srcfyear" => $srcfyear));
																		$rowprojYear = $query_projYear->fetch();
																		$projYear = $rowprojYear['year'];
																		$yr = $rowprojYear['yr'];

																		// get department
																		$query_rsDept = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
																		$query_rsDept->execute(array(":sector" => $projdept));
																		$row_rsDept = $query_rsDept->fetch();
																		$department = $row_rsDept['sector'];
																		$totalRows_rsDept = $query_rsDept->rowCount();

																		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

																		//check if project has adp budget tbl_adp_projects_budget
																		$query_adp_budget = $db->prepare("SELECT * FROM tbl_adp_projects_budget WHERE projid = :projid AND year = :adpfyid");
																		$query_adp_budget->execute(array(":projid" => $project_id, ":adpfyid" => $adpfyid));
																		$row_adp_budget = $query_adp_budget->rowCount();

																		$buttonunapprov = $button = '';

																		$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid");
																		$query_rsMilestone->execute(array(":projid" => $project_id));
																		$row_rsMilestone = $query_rsMilestone->rowCount();

																		if ($projstage == 1 && $row_rsMilestone == 0) {
																			$buttonunapprov .= '
																			<li>
																				<a type="button" onclick="unapprove_project(' . $project_id . ')">
																					<i class="glyphicon glyphicon-edit"></i> Unapprove
																				</a>
																			</li>';
																		}

																		// && $adpstatus == 0 && in_array("un_approve", $page_actions)

																		// status
																		if ($projstage > 0 && $adpstatus == 1) {
																			$active = "<label class='label label-success'>Approved</label>";
																		} elseif ($projstage == 0 && $adpstatus == 0) {
																			//get program targets
																			$query_prog_targets = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
																			$query_prog_targets->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_prog_targets = $query_prog_targets->rowCount();

																			// get program annual plan
																			$query_pbb = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_pbb = $query_pbb->rowCount();
																			$active = "<label class='label label-danger'>Pending</label>";
																			if ($currentYear >= $yr) {
																				if ($row_adp_budget == 0) {
																					$buttonunapprov .= '
																							<li>
																								<a type="button" data-toggle="modal" data-target="#addADPBudgetModal" id="addADPBudgetModalBtn" onclick="addADPBudget(' . $project_id . ',' . $adpfyid . ')">
																									<i class="glyphicon glyphicon-plus"></i> Add ADP Budget
																								</a>
																							</li>';
																				} else {
																					if ($norows_pbb > 0 && $norows_prog_targets > 0 && in_array("approve_project", $page_actions)) {
																						$buttonunapprov .= '
																						<li>
																							<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approve_project(' . $project_id . ')">
																								<i class="fa fa-check-square-o"></i> Approve Project
																							</a>
																						</li>';
																					}
																				}
																			}
																		}

																		$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					' . $buttonunapprov . '
																					<li>
																						<a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(' . $project_id . ')">
																							<i class="glyphicon glyphicon-file"></i> More Info
																						</a>
																					</li>
																				</ul>
																			</div>';
																		$filter_department = view_record($project_department, $project_section, $project_directorate);
																		if ($filter_department) {
																			$sn++;
																?>
																			<tr>
																				<td align="center" width="5%"><?php echo $sn; ?></td>
																				<td width="30%"><?php echo $projname ?></td>
																				<td width="29%"><?php echo $progname; ?></td>
																				<td width="10%"><?php echo $budget; ?></td>
																				<td width="10%"><?php echo $projYear; ?></td>
																				<td width="8%"><?php echo $active; ?></td>
																				<td width="8%"><?php echo $button; ?></td>
																			</tr>
																<?php
																		} // /while
																	}
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<script type="text/javascript">
												var url;
												url = "general-settings/selected-items/fetch-selected-adp-items?fy=<?php echo $adpfyid; ?>";
											</script>
										<?php
										} else {
										?>

											<div id="menu<?= $adpfyid ?>" class="tab-pane fade clearfix">
												<div class="bg-orange text-white" style="width:100%; height:30px">
													<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#FF9800"></i> <?= $adp ?> Projects</h4>
												</div>
												<input type="hidden" id="tblid" value="<?= $adpfyid ?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="" style="width:100%">
														<thead>
															<tr style="color:#333; background-color:#EEE; ">
																<th width="5%">#</th>
																<th width="30%">Project Name</th>
																<th width="29%">Program Name</th>
																<th width="10%">Budget (KSH)</th>
																<th width="10%">Financial Year</th>
																<th width="8%">Status</th>
																<th width="8%">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$sql = $db->prepare("SELECT * FROM `tbl_projects` p left join tbl_annual_dev_plan d ON d.projid=p.projid WHERE financial_year='$adpfyid' ORDER BY financial_year ASC");
															$sql->execute();

															$rows_count = $sql->rowCount();

															if ($rows_count > 0) {
																// $row = $result->fetch_array();
																$active = "";
																$sn = 0;
																while ($row = $sql->fetch()) {
																	$sn++;
																	$itemId = $row['projid'];
																	$budget = $row['projcost'];
																	$budget = number_format($budget, 2);

																	$query_rsBudget = $db->prepare("SELECT SUM(o.budget) as budget FROM tbl_project_output_details o left join tbl_project_details d on d.id=o.outputid WHERE d.projid ='$itemId' AND o.year = '$adpyr'");
																	$query_rsBudget->execute();
																	$row_rsBudget = $query_rsBudget->fetch();
																	$totalRows_rsBudget = $query_rsBudget->rowCount();
																	//$projbudget = $row_rsBudget['budget'];

																	$projname = $row["projname"];
																	//$budget = number_format($projbudget, 2);
																	$progid = $row["progid"];
																	$srcfyear = $row["projfscyear"];

																	//get program and department
																	$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
																	$prog->execute(array(":progid" => $progid));
																	$rowprog = $prog->fetch();
																	$projdept = $rowprog["projdept"];

																	// add_to_adp remove_adp edit delete
																	$project_department = $rowprog['projsector'];
																	$project_section = $rowprog['projdept'];
																	$project_directorate = $rowprog['directorate'];

																	//get financial year
																	$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
																	$query_projYear->execute(array(":srcfyear" => $srcfyear));
																	$rowprojYear = $query_projYear->fetch();
																	$projYear = $rowprojYear['year'];
																	$yr = $rowprojYear['yr'];

																	// get department
																	$query_rsDept = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
																	$query_rsDept->execute(array(":sector" => $projdept));
																	$row_rsDept = $query_rsDept->fetch();
																	$department = $row_rsDept['sector'];

																	//check if project has adp budget tbl_adp_projects_budget
																	$query_adp_budget = $db->prepare("SELECT * FROM tbl_adp_projects_budget WHERE projid = :projid AND year = :adpfyid");
																	$query_adp_budget->execute(array(":projid" => $itemId, ":adpfyid" => $adpfyid));
																	$row_adp_budget = $query_adp_budget->rowCount();

																	if ($row_adp_budget == 0) {
																		$buttonunapprov = '
																		<li>
																			<a type="button" data-toggle="modal" data-target="#addADPBudgetModal" id="addADPBudgetModalBtn" onclick="addADPBudget(' . $itemId . ',' . $adpfyid . ')">
																				<i class="glyphicon glyphicon-plus"></i> Add ADP Budget
																			</a>
																		</li>';
																	}

																	$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

																	//$active = "<label class='label label-success'>Approved</label>";
																	$active = "<label class='label label-danger'>Pending</label>";
																	$button = '<!-- Single button -->
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			' . $buttonunapprov . '
																			<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>
																		</ul>
																	</div>';


																	$filter_department = view_record($project_department, $project_section, $project_directorate);
																	if ($filter_department) {
															?>
																		<tr>
																			<td align="center" width="5%"><?php echo $sn; ?></td>
																			<td width="30%"><?php echo $projname; ?></td>
																			<td width="29%"><?php echo $progname; ?></td>
																			<td width="10%"><?php echo $budget; ?></td>
																			<td width="10%"><?php echo $projYear; ?></td>
																			<td width="8%"><?php echo $active; ?></td>
																			<td width="8%"><?php echo $button; ?></td>
																		</tr>
															<?php
																	} // /while
																}
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
								<?php
										}
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<div class="modal fade" tabindex="-1" role="dialog" id="approveItemModals">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Undo? Approval of Project</h4>
				</div>
				<div class="modal-body">
					<div class="undotemMessages"></div>
					<p align="center">Are you sure you want to unapprove this Project?</p>
				</div>
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="Unapprove"> <i class="fa fa-check-square-o"></i> Unapprove</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Start Modal Item approve -->
	<div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Project</h4>
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

	<!-- Start Modal Add approved budget -->
	<div class="modal fade" id="approvedBudgetItemModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-money"></i> Add Approved Project Budget</h4>
				</div>
				<div class="modal-body" style=" overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="approvedBudgetForm" action="general-settings/action/project-edit-action.php" method="POST">
							<br />
							<div class="col-md-12" id="aprovedBudgetBody"></div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="approvedbudget" id="approvedbudget" value="1">
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

	<!-- Start Modal Add approved budget -->
	<div class="modal fade" id="addADPBudgetModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-money"></i> Add Project ADP Budget</h4>
				</div>
				<div class="modal-body" style=" overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="addADPBudgetForm" action="general-settings/action/project-edit-action.php" method="POST">
							<br />
							<div class="col-md-12" id="addADPBudgetBody"></div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="addProjADPBudget" id="addProjADPBudget" value="1">
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
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<!-- End  Item more Info -->

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script>
	const details = {
		partner_roles: '<?= $partner_roles ?>',
		source_categories: '<?= $source_categories ?>',
		partners: '<?= $partners ?>',
	}
</script>
<!-- <script src="general-settings/js/fetch-adp.js"></script>
<script src="assets/custom js/approve-adp.js"></script>
  -->
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/projects/approve.js"></script>