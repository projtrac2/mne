<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "Annual Development Plans";

if ($permission) {
	$month = date("M");
	$currentYear = ($month > 7 && $month < 12) ? date("Y") : date("Y") - 1;

	try {
		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$progid = $_GET["prg"];
		}

		$query_userdetails =  $db->prepare("SELECT ptid FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$user_name'");
		$query_userdetails->execute();
		$row_userdetails = $query_userdetails->fetch();


		//get financial years 
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$totalRows_rsYear = $query_rsYear->rowCount();

		//get subcounty
		$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
		$query_rsComm->execute();
		$row_rsComm = $query_rsComm->fetch();
		$totalRows_rsComm = $query_rsComm->rowCount();

		//get mapping type 
		$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
		$query_rsMapType->execute();
		$row_rsMapType = $query_rsMapType->fetch();
		$totalRows_rsMapType = $query_rsMapType->rowCount();

		//get project implementation methods 
		$query_rsProjImplMethod =  $db->prepare("SELECT id, method FROM tbl_project_implementation_method");
		$query_rsProjImplMethod->execute();
		$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
		$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

		// get project risks 
		$query_rsRiskCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
		$query_rsRiskCategories->execute();
		$row_rsRiskCategories = $query_rsRiskCategories->fetch();
		$totalRows_rsRiskCategories = $query_rsRiskCategories->rowCount();

		// get adps
		$query_adps =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year GROUP BY financial_year ORDER BY d.id ASC");
		$query_adps->execute();
		$row_adps = $query_adps->fetchAll();
		$totalRows_adps = $query_adps->rowCount();

		$currentdatetime = date("Y-m-d H:i:s");
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<style>
		.modal-lg {
			max-width: 100% !important;
			width: 90%;
		}
	</style>

	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
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
										$fyid = $adpfyid;

										$query_tasksprojs =  $db->prepare("SELECT p.projid AS projid, p.projname AS projname, p.progid AS progid, p.projcategory AS projcategory, a.status AS status FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' GROUP BY t.projid");
										$query_tasksprojs->execute();
										$totalRows_tasksprojs = $query_tasksprojs->rowCount();

										// get adps
										$query_adpprojs =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE financial_year=:fy");
										$query_adpprojs->execute(array(":fy" => $adpfyid));
										$totalRows_adpprojs = $query_adpprojs->rowCount();

										// get active adp
										$query_activeadp =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE y.sdate < '$currentdatetime' and y.edate > '$currentdatetime' AND financial_year = '$adpfyid' GROUP BY d.financial_year");
										$query_activeadp->execute();
										$totalRows_activeadp = $query_activeadp->rowCount();
										$totalcount =  $totalRows_adpprojs + $totalRows_tasksprojs;
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

										// get adps
										$query_adpprojs =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE financial_year=:fy");
										$query_adpprojs->execute(array(":fy" => $adpfyid));
										$totalRows_adpprojs = $query_adpprojs->rowCount();

										// get active adp
										$query_activeadpbody =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE y.sdate < '$currentdatetime' and y.edate > '$currentdatetime' AND financial_year = '$adpfyid' GROUP BY d.financial_year");
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
													$query_tasksprojs =  $db->prepare("SELECT p.projid AS projid, p.projname AS projname, p.progid AS progid, p.projcategory AS projcategory, a.status AS status FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' GROUP BY t.projid");
													$query_tasksprojs->execute();
													$totalRows_tasksprojs = $query_tasksprojs->rowCount();

													// get active adp
													$query_activenewadp =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE y.sdate < '$currentdatetime' and y.edate > '$currentdatetime' AND financial_year = '$adpfyid' GROUP BY d.financial_year");
													$query_activenewadp->execute();
													$totalRows_activenewadp = $query_activenewadp->rowCount();

													?>
													<li class="active">
														<a data-toggle="tab" href="#ongoing<?= $adpfyid ?>"><i class="fa fa-refresh bg-blue" aria-hidden="true"></i> Ongoing Projects &nbsp;<span class="badge bg-blue"><?php echo $totalRows_tasksprojs; ?></span></a>
													</li>
													<li>
														<a data-toggle="tab" href="#new<?= $adpfyid ?>"><i class="fa fa-caret-square-o-down bg-orange" aria-hidden="true"></i> New Projects &nbsp;<span class="badge bg-orange"><?php echo $totalRows_adpprojs; ?></span></a>
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
																if ($totalRows_tasksprojs > 0) {
																	$nm = 0;
																	$budget = 0;
																	while ($row = $query_tasksprojs->fetch()) {
																		$nm++;
																		$projname = $row['projname'];
																		$projid = $row['projid'];
																		$projyear = $adpfy;
																		$projcat = $row['projcategory'];
																		$progid = $row['progid'];

																		// get progname
																		$query_progdetails =  $db->prepare("SELECT * FROM tbl_programs WHERE progid = '$progid'");
																		$query_progdetails->execute();
																		$row_prog = $query_progdetails->fetch();
																		$progname = $row_prog["progname"];

																		if ($projcat == 1 || $projcat == "1") { //In House

																			// get project tasks from financial plan
																			$query_projtasks =  $db->prepare("SELECT * FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' AND t.projid='$projid'");
																			$query_projtasks->execute();

																			$totaltaskscost = 0;
																			while ($rowtasks = $query_projtasks->fetch()) {
																				$projtaskid = $rowtasks["tkid"];
																				// get budget
																				$query_taskcost =  $db->prepare("SELECT SUM(unit_cost * units_no) AS totaltaskcost FROM tbl_project_direct_cost_plan WHERE tasks = '$projtaskid'");
																				$query_taskcost->execute();
																				$row_taskcost = $query_taskcost->fetch();
																				$totaltaskcost = $row_taskcost["totaltaskcost"];
																				$totaltaskscost += $totaltaskcost;
																			}
																		} elseif ($projcat == 2 || $projcat == "2") { //Contractor

																			// get project tasks from procurement plan
																			$query_projtasks =  $db->prepare("SELECT * FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' AND t.projid='$projid'");
																			$query_projtasks->execute();

																			$totaltaskscost = 0;
																			while ($rowtasks = $query_projtasks->fetch()) {
																				$projtaskid = $rowtasks["tkid"];
																				// get budget
																				$query_taskcost =  $db->prepare("SELECT SUM(unit_cost * units_no) AS totaltaskcost FROM tbl_project_tender_details WHERE tasks = '$projtaskid'");
																				$query_taskcost->execute();
																				$row_taskcost = $query_taskcost->fetch();
																				$totaltaskcost = $row_taskcost["totaltaskcost"];
																				$totaltaskscost += $totaltaskcost;
																			}
																		}

																		// get project other costlines 
																		$query_projothers =  $db->prepare("SELECT  SUM(unit_cost * units_no) AS totalotherscost FROM tbl_project_direct_cost_plan c inner join tbl_project_expenditure_timeline e on e.plan_id=c.id WHERE e.disbursement_date >= '$startdate' and e.disbursement_date <= '$enddate' AND c.projid='$projid'");
																		$query_projothers->execute();
																		$row_othercost = $query_projothers->fetch();
																		$totalotherscost = $row_othercost["totalotherscost"];

																		$budget = $totaltaskscost + $totalotherscost;
																		$budget = number_format($budget, 2);

																		$querybudgetstatus = $db->prepare("SELECT * FROM tbl_project_approved_yearly_budget WHERE projid = '$projid' AND year = '$yr'");
																		$querybudgetstatus->execute();
																		$count_budgetstatus =  $querybudgetstatus->rowCount();

																		// status
																		if ($count_budgetstatus > 0) {
																			$active = "<label class='label label-success'>Approved</label>";

																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Project Info</a></li> 
																					<li><a type="button" data-toggle="modal" data-target="#approvedBudgetItemModal" id="approvedBudgetItemModalBtn" onclick="approvedBudget(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Approved Budget</a></li>     
																				</ul> 
																			</div>';
																		} else {
																			// get program annual plan 
																			$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_pbb = $query_pbb->rowCount();

																			$approve = '';
																			$active = "<label class='label label-danger'>Pending</label>";

																			//if($currentYear >= $yr){
																			if ($norows_pbb >  0 && $file_rights->add ) {
																				$approve .= '
																				<li>
																					<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
																					<i class="fa fa-check-square-o"></i> Add Budget 
																					</a>
																				</li>';
																			}
																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																				' . $approve .
																						'
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Project Info</a></li>        
																				</ul>
																			</div>';
																		}

																?>
																		<tr>
																			<td align="center"><?php echo $nm; ?></td>
																			<td><?php echo $projname; ?></td>
																			<td><?php echo $progname; ?></td>
																			<td><?php echo $budget; ?></td>
																			<td><?php echo $projyear; ?></td>
																			<td><?php echo $active; ?></td>
																			<td><?php echo $button; ?></td>
																		</tr>
																<?php
																	}
																}
																?>
															</tbody>
														</table>
													</div>

													<div id="new<?= $adpfyid ?>" class="tab-pane fade">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTableHome" style="width:100%">
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
																$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join tbl_annual_dev_plan d ON d.projid=p.projid WHERE financial_year='$adpfyid' ORDER BY financial_year ASC");
																$sql->execute();

																$rows_count = $sql->rowCount();

																if ($rows_count > 0) {
																	// $row = $result->fetch_array();
																	$active = "";
																	$sn = 0;
																	while ($row = $sql->fetch()) {
																		$sn++;
																		$itemId = $row['projid'];

																		$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
																		$query_rsBudget->execute();
																		$row_rsBudget = $query_rsBudget->fetch();
																		$totalRows_rsBudget = $query_rsBudget->rowCount();
																		$projbudget = $row_rsBudget['budget'];

																		$projname = $row["projname"];
																		$budget = number_format($projbudget, 2);
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

																		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

																		// get department 
																		$query_buttonunapprov =  $db->prepare("SELECT * FROM tbl_projects WHERE projstage = 2 and projid =:projid");
																		$query_buttonunapprov->execute(array(":projid" => $itemId));
																		$row_buttonunapprov = $query_buttonunapprov->rowCount();


																		if ($row_buttonunapprov > 0) {
																			$buttonunapprov = '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
																			//var_dump($row_buttonunapprov);
																		} else {
																			$buttonunapprov = '';
																		}

																		// status
																		if ($row['status'] == 1) {
																			$active = "<label class='label label-success'>Approved</label>";

																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					' . $buttonunapprov . '
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>      
																				</ul> 
																			</div>';
																		} else {
																			//get program targets
																			$query_prog_targets =  $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
																			$query_prog_targets->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_prog_targets = $query_prog_targets->rowCount();

																			// get program annual plan 
																			$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_pbb = $query_pbb->rowCount();

																			$approve = '';
																			$active = "<label class='label label-danger'>Pending</label>";

																			//if($currentYear >= $yr){
																			if ($norows_pbb >  0 && $norows_prog_targets > 0 && $file_rights->add)  {
																				$approve .= '
																				<li>
																					<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
																					<i class="fa fa-check-square-o"></i> Approve Project
																					</a>
																				</li>';
																			}
																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																				' . $approve .
																						'
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>        
																				</ul>
																			</div>';
																		}
																?>
																		<tr>
																			<td align="center" width="5%"><?php echo $sn; ?></td>
																			<td width="30%"><?php echo $adpyr; ?></td>
																			<td width="29%"><?php echo $progname; ?></td>
																			<td width="10%"><?php echo $budget; ?></td>
																			<td width="10%"><?php echo $projYear; ?></td>
																			<td width="8%"><?php echo $active; ?></td>
																			<td width="8%"><?php echo $button; ?></td>
																		</tr>
																<?php
																	} // /while 
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

											<div id="menu<?= $adpfyid ?>" class="tab-pane fade">
												<div style="color:#333; background-color:#EEE; width:100%; height:30px">
													<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#FF9800"></i> <?= $adp ?> Projects</h4>
												</div>
												<input type="hidden" id="tblid" value="<?= $adpfyid ?>">
												<ul class="nav nav-tabs" style="font-size:14px">
													<?php

													//$startdate = "2021-07-01";
													//$enddate = "2022-06-30";
													//$adpfyid = 5;
													// get adps
													/* $query_adpprojs =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_projects p on p.projid=d.projid WHERE financial_year=:fy");
											$query_adpprojs->execute(array(":fy" => $adpfyid));
											$totalRows_adpprojs = $query_adpprojs->rowCount(); */

													// get task-projects 
													$query_tasksprojs =  $db->prepare("SELECT p.projid AS projid, p.projname AS projname, p.progid AS progid, p.projcategory AS projcategory, a.status AS status FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' GROUP BY t.projid");
													$query_tasksprojs->execute();
													$totalRows_tasksprojs = $query_tasksprojs->rowCount();

													// get active adp
													$query_activenewadp =  $db->prepare("SELECT * FROM tbl_annual_dev_plan d inner join tbl_fiscal_year y on y.id=d.financial_year inner join tbl_projects p on p.projid=d.projid WHERE y.sdate < '$currentdatetime' and y.edate > '$currentdatetime' AND financial_year = '$adpfyid' GROUP BY d.financial_year");
													$query_activenewadp->execute();
													$totalRows_activenewadp = $query_activenewadp->rowCount();

													?>
													<li class="active">
														<a data-toggle="tab" href="#ongoing<?= $adpfyid ?>"><i class="fa fa-refresh bg-blue" aria-hidden="true"></i> Ongoing Projects &nbsp;<span class="badge bg-blue"><?php echo $totalRows_tasksprojs; ?></span></a>
													</li>
													<li>
														<a data-toggle="tab" href="#new<?= $adpfyid ?>"><i class="fa fa-caret-square-o-down bg-orange" aria-hidden="true"></i> New Projects &nbsp;<span class="badge bg-orange"><?php echo $totalRows_adpprojs; ?></span></a>
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
																if ($totalRows_tasksprojs > 0) {
																	$nm = 0;
																	$budget = 0;
																	while ($row = $query_tasksprojs->fetch()) {
																		$nm++;
																		$projname = $row['projname'];
																		$projid = $row['projid'];
																		$projyear = $adpfy;
																		$projcat = $row['projcategory'];
																		$progid = $row['progid'];

																		// get progname
																		$query_progdetails =  $db->prepare("SELECT * FROM tbl_programs WHERE progid = '$progid'");
																		$query_progdetails->execute();
																		$row_prog = $query_progdetails->fetch();
																		$progname = $row_prog["progname"];

																		if ($projcat == 1 || $projcat == "1") { //In House

																			// get project tasks from financial plan
																			$query_projtasks =  $db->prepare("SELECT * FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' AND t.projid='$projid'");
																			$query_projtasks->execute();

																			$totaltaskscost = 0;
																			while ($rowtasks = $query_projtasks->fetch()) {
																				$projtaskid = $rowtasks["tkid"];
																				// get budget
																				$query_taskcost =  $db->prepare("SELECT SUM(unit_cost * units_no) AS totaltaskcost FROM tbl_project_direct_cost_plan WHERE tasks = '$projtaskid'");
																				$query_taskcost->execute();
																				$row_taskcost = $query_taskcost->fetch();
																				$totaltaskcost = $row_taskcost["totaltaskcost"];
																				$totaltaskscost += $totaltaskcost;
																			}
																		} elseif ($projcat == 2 || $projcat == "2") { //Contractor

																			// get project tasks from procurement plan
																			$query_projtasks =  $db->prepare("SELECT * FROM tbl_task t inner join tbl_projects p on p.projid=t.projid inner join  tbl_annual_dev_plan a on a.projid=p.projid  WHERE t.sdate >= '$startdate' and t.edate <= '$enddate' AND financial_year < '$adpfyid' AND t.projid='$projid'");
																			$query_projtasks->execute();

																			$totaltaskscost = 0;
																			while ($rowtasks = $query_projtasks->fetch()) {
																				$projtaskid = $rowtasks["tkid"];
																				// get budget
																				$query_taskcost =  $db->prepare("SELECT SUM(unit_cost * units_no) AS totaltaskcost FROM tbl_project_tender_details WHERE tasks = '$projtaskid'");
																				$query_taskcost->execute();
																				$row_taskcost = $query_taskcost->fetch();
																				$totaltaskcost = $row_taskcost["totaltaskcost"];
																				$totaltaskscost += $totaltaskcost;
																			}
																		}

																		// get project other costlines 
																		$query_projothers =  $db->prepare("SELECT SUM(unit_cost * units_no) AS totalotherscost FROM tbl_project_direct_cost_plan c inner join tbl_project_expenditure_timeline e on e.plan_id=c.id WHERE e.disbursement_date >= '$startdate' and e.disbursement_date <= '$enddate' AND c.projid='$projid'");
																		$query_projothers->execute();
																		$row_othercost = $query_projothers->fetch();
																		$totalotherscost = $row_othercost["totalotherscost"];

																		$budget = $totaltaskscost + $totalotherscost;
																		$budget = number_format($budget, 2);

																		$querybudgetstatus = $db->prepare("SELECT * FROM tbl_project_approved_yearly_budget WHERE projid = '$projid' AND year = '$yr'");
																		$querybudgetstatus->execute();
																		$count_budgetstatus =  $querybudgetstatus->rowCount();

																		// status
																		if ($count_budgetstatus > 0) {
																			$active = "<label class='label label-success'>Approved</label>";

																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Project Info</a></li> 
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="fa fa-money"></i> Approved Budget</a></li>     
																				</ul> 
																			</div>';
																		} else {
																			// get program annual plan 
																			$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_pbb = $query_pbb->rowCount();

																			$approve = '';
																			$active = "<label class='label label-danger'>Pending</label>";

																			//if($currentYear >= $yr){
																			if ($norows_pbb >  0 && $file_rights->add) {
																				$approve .= '
																				<li>
																					<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
																					<i class="fa fa-check-square-o"></i> Add Budget 
																					</a>
																				</li>';
																			}
																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																				' . $approve .
																						'
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> Project Info</a></li>        
																				</ul>
																			</div>';
																		}

																?>
																		<tr>
																			<td align="center"><?php echo $nm; ?></td>
																			<td><?php echo $projname; ?></td>
																			<td><?php echo $progname; ?></td>
																			<td><?php echo $budget; ?></td>
																			<td><?php echo $projyear; ?></td>
																			<td><?php echo $active; ?></td>
																			<td><?php echo $button; ?></td>
																		</tr>
																<?php
																	}
																}
																?>
															</tbody>
														</table>
													</div>

													<div id="new<?= $adpfyid ?>" class="tab-pane fade">
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
																$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join tbl_annual_dev_plan d ON d.projid=p.projid WHERE financial_year='$adpfyid' ORDER BY financial_year ASC");
																$sql->execute();

																$rows_count = $sql->rowCount();

																if ($rows_count > 0) {
																	// $row = $result->fetch_array();
																	$active = "";
																	$sn = 0;
																	while ($row = $sql->fetch()) {
																		$sn++;
																		$itemId = $row['projid'];

																		$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
																		$query_rsBudget->execute();
																		$row_rsBudget = $query_rsBudget->fetch();
																		$totalRows_rsBudget = $query_rsBudget->rowCount();
																		$projbudget = $row_rsBudget['budget'];

																		$projname = $row["projname"];
																		$budget = number_format($projbudget, 2);
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

																		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

																		// get department 
																		$query_buttonunapprov =  $db->prepare("SELECT * FROM tbl_projects WHERE projstage = 2 and projid =:projid");
																		$query_buttonunapprov->execute(array(":projid" => $itemId));
																		$row_buttonunapprov = $query_buttonunapprov->rowCount();

																		if ($row_buttonunapprov > 0 && $file_rights->add) {
																			$buttonunapprov = '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
																			//var_dump($row_buttonunapprov);
																		} else {
																			$buttonunapprov = '';
																		}

																		// status
																		if ($row['status'] == 1) {
																			$active = "<label class='label label-success'>Approved</label>";

																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					' . $buttonunapprov . '
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>      
																				</ul> 
																			</div>';
																		} else {
																			//get program targets
																			$query_prog_targets =  $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
																			$query_prog_targets->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_prog_targets = $query_prog_targets->rowCount();

																			// get program annual plan 
																			$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
																			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $adpyr));
																			$norows_pbb = $query_pbb->rowCount();

																			$approve = '';
																			$active = "<label class='label label-danger'>Pending</label>";

																			//if($currentYear >= $yr){
																			if ($norows_pbb >  0 && $currentYear >= $yr && $norows_prog_targets > 0 && $file_rights->add) {
																				$approve .= '
																				<li>
																					<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
																					<i class="fa fa-check-square-o"></i> Approve Project
																					</a>
																				</li>';
																			}
																			$button = '<!-- Single button -->
																			<div class="btn-group">
																				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Options <span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																				' . $approve .
																						'
																					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>        
																				</ul>
																			</div>';
																		}
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
																?>
															</tbody>
														</table>
													</div>
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

	<!-- Start Item Delete -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
				</div>
				<div class="modal-body">
					<div class="removeItemMessages"></div>
					<p align="center">Are you sure you want to delete this record?</p>
				</div>
				<div class="modal-footer removeProductFooter">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

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
				<div class="modal-body" style="max-height:450px; overflow:auto;">
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
				<div class="modal-body" style="max-height:450px; overflow:auto;">
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
?>

<script src="general-settings/js/fetch-adp.js"></script>
<script src="assets/custom js/approve-adp.js"></script> 
<script type="text/javascript">
	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');
			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {
				$(".submenus").show();
				$(this).attr('id', '1');
			}
		});
		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});

		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});
</script>