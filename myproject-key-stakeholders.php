<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {

	try {
		if (isset($_GET['projid'])) {
			$projid = $_GET['projid'];
		}
		$query_rsMyP =  $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE tbl_projects.deleted='0' AND user_name = :user AND projid = :projid");
		$query_rsMyP->execute(array(":user" => $user_name, ":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$projcategory = $row_rsMyP["projcategory"];

		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
		$query_rsProjects->execute(array(":projid" => $projid));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
		$projname = $row_rsProjects['projname'];
		$projcode = $row_rsProjects['projcode'];
		$projcost = $row_rsProjects['projcost'];
		$progid = $row_rsProjects['progid'];
		$projstartdate = $row_rsProjects['projstartdate'];
		$projenddate = $row_rsProjects['projenddate'];

		$query_rsOutputs = $db->prepare("SELECT p.output as output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid=:projid");
		$query_rsOutputs->execute(array(":projid" => $projid));
		$row_rsOutputs = $query_rsOutputs->fetch();
		$totalRows_rsOutputs = $query_rsOutputs->rowCount();

		// query the 
		$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = :projid ORDER BY amountfunding desc");
		$query_rsProjFinancier->execute(array(":projid" => $projid));
		$row_rsProjFinancier = $query_rsProjFinancier->fetch();
		$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();


		$query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE projid=:projid");
		$query_rsEvalDates->execute(array(":projid" => $projid));
		$row_rsrsEvalDates = $query_rsEvalDates->fetch();
		$totalRows_rsEvalDates = $query_rsEvalDates->rowCount();

		$formname = $row_rsrsEvalDates["form_name"];
		//$projid = $row_rsrsEvalDates["projid"];
		$enumeratortype = $row_rsrsEvalDates["enumerator_type"];
		$sample = $row_rsrsEvalDates["sample_size"];
		$evalstartdate = $row_rsrsEvalDates["startdate"];
		$evalenddate = $row_rsrsEvalDates["enddate"];
		$current_date = date("Y-m-d");
		$evalid = $row_rsrsEvalDates["id"];
		$indid = $row_rsrsEvalDates["indid"];
		$sdate = date_create($evalstartdate);
		$startdate = date_format($sdate, "d M Y");
		$edate = date_create($evalenddate);
		$enddate = date_format($edate, "d M Y");

		$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_proj->execute(array(":projid" => $projid));
		$row_proj = $query_proj->fetch();
		$project = $row_proj["projname"];

		$query_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid");
		$query_questions->execute(array(":projid" => $projid));
		//$row_questions = $query_questions->fetchAll();
		$count_questions = $query_questions->rowCount();

		$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
		$query_indicator->execute(array(":indid" => $indid));
		$row_indicator = $query_indicator->fetch();
		$rowspan = 2;
		$colspan = 2;
		if (!empty($row_indicator)) {
			$change = $row_indicator["indicator_name"];
			$unit = $row_indicator["unit"];
			$disaggregated = $row_indicator["indicator_disaggregation"];
			$indicator = $unit . " of " . $change;
			$count_disaggregations = '';

			if ($disaggregated == 1) {
				$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
				$query_indicator_disag_type->execute(array(":indid" => $indid));
				$row_indicator_disag_type = $query_indicator_disag_type->fetch();
				$category = $row_indicator_disag_type["category"];

				$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
				$query_indicator_disaggregations->execute(array(":indid" => $indid));
				$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
				$count_disaggregations = $query_indicator_disaggregations->rowCount();
				$colspan = $count_disaggregations;
				$rowspan = 2 + 1;
			}
		}

		$currentStatus =  $row_rsMyP['projstatus'];
		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
		$query_rsMlsProg->execute(array(":projid" => $projid));
		$row_rsMlsProg = $query_rsMlsProg->fetch();
		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
		$percent2 = round($prjprogress, 2);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
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
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="header" style="padding-bottom:0px">
						<div class="button-demo" style="margin-top:-15px">
							<span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
							<a href="myprojectdash.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
							<a href="myprojectmilestones.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
							<a href="myprojectworkplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Quarterly Targets</a>
							<a href="myprojectfinancialplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
							<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
							<a href="projectissueslist.php?proj=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
							<a href="myprojectfiles.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
							<a href="projreports.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
						<h4>
							<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
							</div>
							<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
									<div class="bar hundred cornflowerblue">
										<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#home"><i class="fa fa-users bg-brown" aria-hidden="true"></i> PROJECT TEAM &nbsp;<span class="badge bg-brown">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-money bg-deep-orange" aria-hidden="true"></i> PROJECT FINANCIERS &nbsp;<span class="badge bg-deep-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2"><i class="fa fa-university bg-blue" aria-hidden="true"></i> PROJECT IMPLEMENTORS &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu3"><i class="fa fa-slideshare bg-purple" aria-hidden="true"></i> PROJECT BENEFICIARIES &nbsp;<span class="badge bg-purple">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<?php
									$query_rsPMembers =  $db->prepare("SELECT * FROM tbl_projmembers WHERE projid = '$projid' ORDER BY pmid ASC");
									$query_rsPMembers->execute();
									$row_rsPMembers = $query_rsPMembers->fetch();
									$count_row_rsPMembers = $query_rsPMembers->rowCount();
									?>
									<div class="header">
										<div class="clearfix" style="margin-top:5px; margin-bottom:5px">
											<h4 class="list-group-item bg-brown"><img src="images/members.png" alt="task" /> Project Team Members</h4>
										</div>
									</div>
									<div class="body">
										<div <?php
												if ($row_rsPMembers['pmid'] <= 0) {
													echo 'style="display:none;"';
												}
												?>>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-grey">
															<th width="5%"><strong>Photo</strong></th>
															<th width="20%"><strong>Fullname</strong></th>
															<th width="13%"><strong>Designation</strong></th>
															<th width="10%"><strong>Project Role</strong></th>
															<th width="10%"><strong>Availability</strong></th>
															<th width="12%"><strong>Assigned Issues</strong></th>
															<th width="20%"><strong>Email</strong></th>
															<th width="10%"><strong>Phone</strong></th>
														</tr>
													</thead>
													<tbody>
														<!-- =========================================== -->
														<?php
														if ($count_row_rsPMembers > 0) {
															do {
																$myprjmbrid = $row_rsPMembers['ptid'];
																$role = $row_rsPMembers['role'];

																$query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE ptid = '$myprjmbrid' ORDER BY ptid ASC");
																$query_rsPMbrs->execute();
																$row_rsPMbrs = $query_rsPMbrs->fetch();
																$count_row_rsPMbrs = $query_rsPMbrs->rowCount();

																$query_mbrrole = $db->prepare("SELECT * FROM tbl_project_team_roles WHERE id = '$role'");
																$query_mbrrole->execute();
																$row_mbrrole = $query_mbrrole->fetch();
																$count_row_mbrrole = $query_mbrrole->rowCount();

																$availability =1;
																$mbrministry =  $mbrdept =$mbrdesg = $userid  = "";
																if ($count_row_mbrrole > 0 && $count_row_rsPMbrs) {
																	$mbrministry = $row_rsPMbrs['ministry'];
																	$mbrdept = $row_rsPMbrs['department'];
																	$mbrdesg = $row_rsPMbrs['designation'];
																	$mbrrole = $row_mbrrole['role'];
																	$userid = $row_rsPMbrs['userid'];
																	$availability = $row_rsPMbrs['availability'];
																}

																if ($availability) {
																	$availability = "<font color='#4CAF50'>Available</font>";
																} else {
																	$query_reassignee = $db->prepare("SELECT reassignee FROM tbl_projmembers WHERE ptid = '$myprjmbrid'");
																	$query_reassignee->execute();
																	$row_reassignee = $query_reassignee->fetch();
																	$reassignee = $row_reassignee["reassignee"];

																	$query_caretaker = $db->prepare("SELECT title, fullname FROM tbl_projteam2  WHERE ptid = '$reassignee'");
																	$query_caretaker->execute();
																	$row_caretaker = $query_caretaker->fetch();

																	$caretaker = $row_caretaker["title"] . ". " . $row_caretaker["fullname"];
																	$availability = "<font color='red'>Unavailable</font>";
																}

																$query_rsMinistry = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mbrministry'");
																$query_rsMinistry->execute();
																$row_rsMinistry = $query_rsMinistry->fetch();
																if (empty($row_rsMinistry['sector']) || $row_rsMinistry['sector'] == '') {
																	$ministry = "All " . $ministrylabelplural;
																} else {
																	$ministry = $row_rsMinistry['sector'];
																}

																$query_rsDept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mbrdept'");
																$query_rsDept->execute();
																$row_rsDept = $query_rsDept->fetch();
																if (empty($row_rsDept['sector']) || $row_rsDept['sector'] == '') {
																	$department = "All " . $departmentlabelplural;
																} else {
																	$department = $row_rsMinistry['sector'];
																}

																$query_rsPMbrDesg = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid = '$mbrdesg' ORDER BY moid ASC");
																$query_rsPMbrDesg->execute();
																$row_rsPMbrDesg = $query_rsPMbrDesg->fetch();
																$designation = $row_rsPMbrDesg ? $row_rsPMbrDesg['designation'] : "";

																$query_assignedissues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid='$projid' and owner='$userid' and status<>1");
																$query_assignedissues->execute();
																$count_assignedissues = $query_assignedissues->rowCount();
																if($count_row_rsPMbrs > 0){
														?>
																<tr>
																	<td>
																		<img src="<?php echo $row_rsPMbrs['floc']; ?>" style="width:30px; height:30px; margin-bottom:0px" />
																	</td>
																	<td><?php echo $row_rsPMbrs['title']; ?>. <?php echo $row_rsPMbrs['fullname']; ?></td>
																	<td><?php echo $designation; ?></td>
																	<td><?php echo $mbrrole; ?></td>
																	<td><?php if ($row_rsPMbrs['availability'] == 0) {
																			echo '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">';
																		} else {
																			echo '<p>';
																		}
																		echo $availability; ?></p>
																	</td>
																	<td align="center"><span class="badge bg-brown"><?php echo $count_assignedissues; ?></span></td>
																	<td><?php echo $row_rsPMbrs['email']; ?></td>
																	<td><?php echo $row_rsPMbrs['phone']; ?></td>
																</tr>
														<?php
																}
															} while ($row_rsPMembers = $query_rsPMembers->fetch());
															
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
												<div class="header">
													<div class="clearfix" style="margin-top:5px; margin-bottom:5px">
														<h4 class="list-group-item bg-deep-orange"><i class="fa fa-money" aria-hidden="true"></i> Project Funding Details</h4>
													</div>
												</div>
												<div class="body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="" style="width:100%">
															<thead>
																<tr class="bg-grey">
																	<th width="3%">#</th>
																	<th width="70%">Financier</th>
																	<th width="27%">Amount Funding(Ksh)</th>
																</tr>
															</thead>
															<tbody id="">
																<?php
																// query the 
																$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = :projid ORDER BY amountfunding desc");
																$query_rsProjFinancier->execute(array(":projid" => $projid));
																$row_rsProjFinancier = $query_rsProjFinancier->fetch();
																$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																$rowno = 0;
																$totalAmount = 0;
																if ($totalRows_rsProjFinancier > 0) {
																	do {
																		$rowno++;
																		$progfundid =  $row_rsProjFinancier['progid'];
																		$projfinancierid =  $row_rsProjFinancier['financier'];
																		$projamountfunding =  $row_rsProjFinancier['amountfunding'];
																		$totalAmount = $projamountfunding + $totalAmount;

																		$query_rsFunding =  $db->prepare("SELECT financier FROM tbl_financiers WHERE id ='$projfinancierid'");
																		$query_rsFunding->execute();
																		$row_rsFunding = $query_rsFunding->fetch();
																		$totalRows_rsFunding = $query_rsFunding->rowCount();

																		$projfinancier = '<span>' . $row_rsFunding["financier"] . '</span>';

																		echo ' 
																		<tr id="row<?= $rowno ?>">
																			<td>
																				' . $rowno . '
																			</td>
																			<td>
																				' .  $projfinancier . '
																			</td>
																			<td>
																				' .  number_format($projamountfunding, 2) . '
																			</td>
																		</tr>';
																	} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																}
																echo ' 
														<tfoot>
															<tr>
																<td colspan="2"><strong>Total Amount</strong></td>
																<td><strong>' . number_format($totalAmount, 2) . '</strong></td>
															</tr>
														</tfoot>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>';
																?>
													</div>
													<div id="menu2" class="tab-pane fade">
														<?php
														$query_implementors =  $db->prepare("SELECT * FROM tbl_myprojpartner WHERE projid = '$projid'");
														$query_implementors->execute();
														$row_implementors = $query_implementors->fetch();

														$leadimplementers = $row_implementors["lead_implementer"];
														$leadimplementerrole = "Lead Implementor";

														$query_leadimplementor =  $db->prepare("SELECT * FROM tbl_financiers WHERE id = '$leadimplementers'");
														$query_leadimplementor->execute();
														$row_leadimplementor = $query_leadimplementor->fetch();
														$leadimplementor = $row_leadimplementor ?  $row_leadimplementor["partnername"] : "";

														if (!empty($row_implementors["implementing_partner"])) {
															$implementingpartners = explode(",", $row_implementors["implementing_partner"]);
															$implementingpartner = [];
															foreach ($implementingpartners as $ipartner) {
																$query_implementor =  $db->prepare("SELECT * FROM tbl_financiers WHERE id = '$ipartner'");
																$query_implementor->execute();
																$row_implementor = $query_implementor->fetch();
																$implementingpartner[] = $row_implementor["partnername"];
															}
															$implementingpartnername = implode("; ", $implementingpartner);
														} else {
															$implementingpartnername = "N/A";
														}
														$implementingpartnerrole = "Implementing Partner/s";

														$query_contractor =  $db->prepare("SELECT contractor_name FROM tbl_contractor c inner join tbl_projects p on p.projcontractor = c.contrid where p.projid=:projid");
														$query_contractor->execute(array(":projid" => $projid));
														$row_contractor = $query_contractor->fetch();
														$contractor = $row_contractor["contractor_name"];
														?>
														<div class="header">
															<div class="clearfix" style="margin-top:5px; margin-bottom:5px">
																<h4 class="list-group-item bg-blue"><i class="fa fa-university" aria-hidden="true"></i> Project Implementors</h4>
															</div>
														</div>
														<div class="body">
															<div>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																		<thead>
																			<tr class="bg-grey">
																				<th width="2%"><strong>#</strong></th>
																				<th width="30%"><strong>Role</strong></th>
																				<th width="68%"><strong>Implementor</strong></th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>
																					1.
																				</td>
																				<td>
																					<?php echo $leadimplementerrole; ?>
																				</td>
																				<td>
																					<?php echo $leadimplementor; ?>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					2.
																				</td>
																				<td>
																					<?php echo $implementingpartnerrole; ?>
																				</td>
																				<td>
																					<?php echo $implementingpartnername; ?>
																				</td>
																			</tr> 
																			<?php if ($projcategory == 2) { ?>
																				<tr>
																					<td>
																						4.
																					</td>
																					<td>
																						<?php echo "Contactor"; ?>
																					</td>
																					<td>
																						<?php echo $contractor; ?>
																					</td>
																				</tr>
																			<?php }	?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
													<div id="menu3" class="tab-pane fade">
														<?php
														$query_implementors =  $db->prepare("SELECT * FROM tbl_myprojpartner WHERE projid = '$projid'");
														$query_implementors->execute();
														$row_implementors = $query_implementors->fetch();

														$leadimplementers = $row_implementors["lead_implementer"];
														$leadimplementerrole = "Lead Implementor";
														if ($leadimplementers == 0) {
															$query_leadimplementor =  $db->prepare("SELECT company_name FROM tbl_company_settings");
															$query_leadimplementor->execute();
															$row_leadimplementor = $query_leadimplementor->fetch();
															$leadimplementor = $row_leadimplementor["company_name"];
														} else {
															$query_leadimplementor =  $db->prepare("SELECT * FROM tbl_partners WHERE ptnid = '$leadimplementers'");
															$query_leadimplementor->execute();
															$row_leadimplementor = $query_leadimplementor->fetch();
															$leadimplementor = $row_leadimplementor["partnername"];
														}

														if ($row_implementors["implementing_partner"] != 0) {
															$implementingpartners = explode(",", $row_implementors["implementing_partner"]);
															$implementingpartner = [];
															foreach ($implementingpartners as $ipartner) {
																$query_implementor =  $db->prepare("SELECT * FROM tbl_partners WHERE ptnid = '$ipartner'");
																$query_implementor->execute();
																$row_implementor = $query_implementor->fetch();
																$implementingpartner[] = $row_implementor["partnername"];
															}
															$implementingpartnername = implode("; ", $implementingpartner);
														} else {
															$implementingpartnername = "N/A";
														}
														$implementingpartnerrole = "Implementing Partner/s";

														if ($row_implementors["collaborative_partner"] != 0) {
															$collaborativepartners = explode(",", $row_implementors["collaborative_partner"]);
															$collaborativepartner = [];
															foreach ($collaborativepartners as $cpartner) {
																$query_collaborator =  $db->prepare("SELECT * FROM tbl_partners WHERE ptnid = '$cpartner'");
																$query_collaborator->execute();
																$row_collaborator = $query_collaborator->fetch();
																$collaborativepartner[] = $row_collaborator["partnername"];
															}
															$collaborativepartnername = implode("; ", $collaborativepartner);
														} else {
															$collaborativepartnername = "N/A";
														}
														$collaborativepartnerrole = "Collaborative Partner/s";
														?>
														<div class="header">
															<div class="clearfix" style="margin-top:5px; margin-bottom:5px">
																<h4 class="list-group-item bg-purple"><i class="fa fa-slideshare" aria-hidden="true"></i> Project Beneficiaries</h4>
															</div>
														</div>
														<div class="body">
															<div class="clearfix">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<?php
																		if ($disaggregated == 0) {
																		?>
																			<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																				<thead>
																					<tr class="bg-grey">
																						<th style="width:40%">Beneficiary</th>
																						<th style="width:20%">Baseline</th>
																						<th style="width:20%">Endline</th>
																						<th style="width:20%">Change</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					$query_baseline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_indicator_baseline_survey_forms f ON f.projid=p.projid INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE f.status=4 and survey_type='Baseline' and p.projid=:projid");
																					$query_baseline_survey->execute(array(":projid" => $projid));
																					$rows_baseline_survey = $query_baseline_survey->fetch();

																					$query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
																					$query_variables_cat->execute();
																					$row_variables_cat = $query_variables_cat->fetch();
																					$cat = $row_variables_cat["indicator_calculation_method"];

																					//$cat = $rows_baseline_survey["cat"];
																					$numerator = $rows_baseline_survey ?  $rows_baseline_survey["numerator"] : 0;
																					$denominator = $rows_baseline_survey ?  $rows_baseline_survey["denominator"] : 0;
																					$baseline = '';
																					if ($cat == 2) {
																						$baseline = number_format(($numerator / $denominator) * 100, 2);
																					} else {
																						$baseline = $numerator;
																					}


																					$query_endline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_indicator_baseline_survey_forms f ON f.projid=p.projid INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE f.status=4 and survey_type='Endline' and p.projid=:projid");
																					$query_endline_survey->execute(array(":projid" => $projid));
																					$rows_endline_survey = $query_endline_survey->fetch();
																					$count_endline_surveys = $query_endline_survey->rowCount();

																					$endline = 'Pending';
																					$difference = 'Pending';
																					if ($count_endline_surveys > 0) {
																						$endnumerator = $rows_endline_survey["numerator"];
																						$enddenominator = $rows_endline_survey["denominator"];
																						if ($cat == 2) {
																							$endline = number_format(($endnumerator / $enddenominator) * 100, 2);
																						} else {
																							$endline = $endnumerator;
																						}
																						$difference = $endline - $baseline;
																					}

																					?>
																					<tr>
																						<td><?php echo $unit . " of " . $change; ?></td>
																						<td><?php echo $baseline; ?></td>
																						<td><?php echo $endline; ?></td>
																						<td><?php echo $difference; ?></td>
																					</tr>
																				</tbody>
																			</table>
																		<?php
																		} else {
																		?>
																			<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																				<thead>
																					<tr class="bg-grey">
																						<th style="width:40%">Disaggregated Beneficiary</th>
																						<th style="width:20%" colspan="<?= $colspan ?>">Baseline</th>
																						<th style="width:20%" colspan="<?= $colspan ?>">Endline</th>
																						<th style="width:20%" colspan="<?= $colspan ?>">Change</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					$query_baseline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_indicator_baseline_survey_forms f ON f.projid=p.projid INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE f.status=4 and survey_type='Baseline' and p.projid=:projid");
																					$query_baseline_survey->execute(array(":projid" => $projid));
																					$rows_baseline_survey = $query_baseline_survey->fetch();

																					$query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
																					$query_variables_cat->execute();
																					$row_variables_cat = $query_variables_cat->fetch();
																					$cat = $row_variables_cat["indicator_calculation_method"];

																					$numerator = $rows_baseline_survey["numerator"];
																					$denominator = $rows_baseline_survey["denominator"];
																					$baseline = '';
																					if ($cat == 2) {
																						$baseline = number_format(($numerator / $denominator) * 100, 2);
																					} else {
																						$baseline = $numerator;
																					}


																					$query_endline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_indicator_baseline_survey_forms f ON f.projid=p.projid INNER JOIN tbl_survey_conclusion c ON f.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE f.status=4 and survey_type='Endline' and p.projid=:projid");
																					$query_endline_survey->execute(array(":projid" => $projid));
																					$rows_endline_survey = $query_endline_survey->fetch();
																					$count_endline_surveys = $query_endline_survey->rowCount();

																					$endline = 'Pending';
																					$difference = 'Pending';
																					if ($count_endline_surveys > 0) {
																						$endnumerator = $rows_endline_survey["numerator"];
																						$enddenominator = $rows_endline_survey["denominator"];
																						if ($cat == 2) {
																							$endline = number_format(($endnumerator / $enddenominator) * 100, 2);
																						} else {
																							$endline = $endnumerator;
																						}
																						$difference = $endline - $baseline;
																					}

																					?>
																					<tr>
																						<td><?php echo $unit . " of " . $change; ?></td>
																						<td><?php echo $baseline; ?></td>
																						<td><?php echo $endline; ?></td>
																						<td><?php echo $difference; ?></td>
																					</tr>
																				</tbody>
																				<thead>
																					<tr class="bg-light-blue">
																						<th colspan="" rowspan="<?= $rowspan ?>" style="width:30%">Questions</th>
																						<th colspan="" rowspan="<?= $rowspan ?>" style="width:20%">Project&nbsp;Location/sIndicator</th>
																						<th colspan="<?= $colspan ?>" rowspan="">Answers</th>
																					</tr>
																					<?php
																					if ($disaggregated == 1) { ?>
																						<tr>
																							<?php
																							foreach ($row_indicator_disaggregations as $disaggregations) { ?>
																								<th><?php echo $disaggregations["disaggregation"] ?></th>
																							<?php
																							}
																							?>
																						</tr>
																						<tr>
																							<?php
																							foreach ($row_indicator_disaggregations as $disaggregations) { ?>
																								<th>Yes </th>
																								<th>No </th>
																							<?php
																							} ?>
																						</tr>
																					<?php
																					} else {
																					?>
																						<tr>
																							<th>Yes </th>
																							<th>No </th>
																						</tr>
																					<?php
																					}
																					?>
																				</thead>
																				<tbody>
																					<?php
																					if ($count_questions > 0) {
																						$query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=1");
																						$query_answers_yes_total->execute();
																						$count_answers_yes_total = $query_answers_yes_total->rowCount();

																						$query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=0");
																						$query_answers_no_total->execute();
																						$count_answers_no_total = $query_answers_no_total->rowCount();

																						while ($row_questions = $query_questions->fetch()) {
																							$question = $row_questions["question"];
																							$questionid = $row_questions["id"];

																							$query_proj_location =  $db->prepare("SELECT projstate FROM tbl_projects WHERE projid='$projid'");
																							$query_proj_location->execute();
																							$row_locatios = $query_proj_location->fetch();
																							$proj_locations = $row_locatios["projstate"];
																							$projlocations = explode(",", $proj_locations);
																							$proj_location_count = count($projlocations)

																					?>
																							<tr>
																								<td rowspan="2"><?php echo $question ?></td>
																								<?php
																								foreach ($projlocations as $locations) {
																									$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
																									$query_location->execute();
																									$row_location = $query_location->fetch();
																									$location = $row_location["state"];
																								?>
																									<td class="bg-lime">
																										<font color="#000"><?php echo $location; ?></font>
																									</td>
																									<?php
																									if ($disaggregated == 1) {
																										foreach ($row_indicator_disaggregations as $rows) {
																											$disaggregationid = $rows["disid"];

																											$query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=1");
																											$query_answers_yes->execute();
																											$count_answers_yes = $query_answers_yes->rowCount();

																											$query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=0");
																											$query_answers_no->execute();
																											$count_answers_no = $query_answers_no->rowCount();
																									?>
																											<td class="bg-lime text-center">
																												<font color="#f7070b"><?= $count_answers_yes ?></font>
																											</td>
																											<td class="bg-lime text-center">
																												<font color="#f7070b"><?= $count_answers_no ?></font>
																											</td>
																										<?php
																										}
																									} else {
																										$query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=1");
																										$query_answers_yes->execute();
																										$count_answers_yes = $query_answers_yes->rowCount();

																										$query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=0");
																										$query_answers_no->execute();
																										$count_answers_no = $query_answers_no->rowCount();
																										?>
																										<td class="bg-lime text-center">
																											<font color="#f7070b"><?= $count_answers_yes ?></font>
																										</td>
																										<td class="bg-lime text-center">
																											<font color="#f7070b"><?= $count_answers_no ?></font>
																										</td>
																								<?php
																									}
																									echo "</tr>";
																								}
																								?>
																							</tr>
																						<?php
																						} ?>
																						<tr>
																							<td class="bg-green" colspan="2" align="right">Total</td>
																							<td class="bg-green" align="center"><?php echo $count_answers_yes_total ?></td>
																							<td class="bg-green" align="center"><?php echo $count_answers_no_total ?></td>
																						</tr>

																					<?php
																					}
																					?>
																				</tbody>
																			</table>
																		<?php
																		}
																		?>
																	</div>
																</div>
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
<script src="general-settings/js/fetch-selected-project-activities.js"></script>