<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
if ($permission) {
	try {
		$workflow_stage = 8;
		$general_substage = 1;
		$technical_substage = 2;
		$output_substage = 3;
		$outcome_substage = 4;
		$impact_substage = 5;


		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
		$query_rsProjects->execute(array(":projid" => $projid));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
		$projname = $totalRows_rsProjects > 0 ? $row_rsProjects['projname'] : "";
		$percent2 = $totalRows_rsProjects > 0 ? number_format($row_rsProjects['progress'], 2) : "";
		$projstage = $row_rsProjects["projstage"];
		$projcat = $row_rsProjects["projcategory"];

		function get_roles($role)
		{
			global $db;
			$query_mbrrole = $db->prepare("SELECT * FROM tbl_project_team_roles WHERE id =:role");
			$query_mbrrole->execute(array(":role" => $role));
			$row_mbrrole = $query_mbrrole->fetch();
			$count_row_mbrrole = $query_mbrrole->rowCount();
			return $count_row_mbrrole > 0 ? $row_mbrrole['role'] : "";
		}

		function get_designation($moid)
		{
			global $db;
			$query_rsPMbrDesg = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid =:moid ORDER BY moid ASC");
			$query_rsPMbrDesg->execute(array(":moid" => $moid));
			$row_rsPMbrDesg = $query_rsPMbrDesg->fetch();
			return $row_rsPMbrDesg ? $row_rsPMbrDesg['designation'] : "";
		}

		function get_issues($projid, $userid)
		{
			global $db;
			$query_assignedissues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid=:projid and owner=:userid and status<>1");
			$query_assignedissues->execute(array(":projid" => $projid, ":userid" => $userid));
			$count_assignedissues = $query_assignedissues->rowCount();
			return $count_assignedissues;
		}

		function get_sector($sector_id)
		{
			global $db;
			$query_rsMinistry = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :sector_id");
			$query_rsMinistry->execute(array(":sector_id" => $sector_id));
			$row_rsMinistry = $query_rsMinistry->fetch();
			$rows_rsMinistry = $query_rsMinistry->rowCount();
			return $rows_rsMinistry > 0 ? $row_rsMinistry['sector'] : "";
		}

		function get_caretaker($projid, $user_name, $workflow_stage, $sub_stage, $activity)
		{
			global $db;
			$standin_responsible = "";
			$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
			$query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":activity" => $activity));
			$row_rsOutput_standin = $query_rsOutput_standin->fetch();
			$total_rsOutput_standin = $query_rsOutput_standin->rowCount();

			if ($total_rsOutput_standin > 0) {
				$owner_id = $row_rsOutput_standin['owner'];
				$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage  AND responsible=:responsible");
				$query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
				$total_rsOutput = $query_rsOutput->rowCount();
				$standin_responsible = $total_rsOutput > 0 ? true : false;
			}

			$query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :standin_responsible ORDER BY ptid ASC");
			$query_rsPMbrs->execute(array(":standin_responsible" => $standin_responsible));
			$row_rsPMbrs = $query_rsPMbrs->fetch();
			$count_row_rsPMbrs = $query_rsPMbrs->rowCount();
			return $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon . "  " . $pageTitle ?>
					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='projects.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="" style="margin-top:-15px">
								<a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
								<a href="project-indicators.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Outputs</a>
								<a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<?php if($projcat == 2 && $projstage > 4){ ?>
									<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<?php } ?>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Issues</a>
								<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Map</a>
								<a href="project-media.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
									<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
										<?= $percent2 ?>%
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#menu1">TECHNICAL TEAM &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2">M&E TEAM &nbsp;<span class="badge bg-green">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade in active">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-grey">
													<th width="8%"><strong>Photo</strong></th>
													<th width="32%"><strong>Fullname</strong></th>
													<th width="15%"><strong>Designation</strong></th>
													<th width="10%"><strong>Role</strong></th>
													<th width="10%"><strong>Availability</strong></th>
													<th width="15%"><strong>Email</strong></th>
													<th width="10%"><strong>Phone</strong></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid AND team_type=4 GROUP BY responsible ORDER BY role ASC");
												$query_rsMembers->execute(array(":projid" => $projid));
												$total_rsMembers = $query_rsMembers->rowCount();
												if ($total_rsMembers > 0) {
													$rowno = 0;
													while ($row_rsMembers = $query_rsMembers->fetch()) {
														$rowno++;
														$role_id = $row_rsMembers['role'];
														$ptid = $row_rsMembers['responsible'];
														$query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
														$query_rsPMbrs->execute(array(":user_id" => $ptid));
														$row_rsPMbrs = $query_rsPMbrs->fetch();
														$count_row_rsPMbrs = $query_rsPMbrs->rowCount();
														if ($count_row_rsPMbrs > 0) {
															$mbrministry = $row_rsPMbrs['ministry'];
															$mbrdept = $row_rsPMbrs['department'];
															$mbrdesg = $row_rsPMbrs['designation'];
															$userid = $row_rsPMbrs['userid'];
															$availability = $row_rsPMbrs['availability'];
															$file_path = $row_rsPMbrs['floc'];
															$email = $row_rsPMbrs['email'];
															$phone = $row_rsPMbrs['phone'];
															$fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
															$user_role = get_roles($role_id);
															$count_assignedissues = get_issues($projid, $userid);
															$designation = get_designation($mbrdesg);
															$ministries = get_sector($mbrministry);
															$sections = get_sector($mbrdept);
															$directorates = get_sector($mbrministry);

															$ministry = $ministries != '' ? $ministries : "All " . $ministrylabelplural;
															$section = $sections != '' ? $sections : "All " . $departmentlabelplural;
															$directorate = $directorates != '' ? $directorates : "All " . $ministrylabelplural;

															$p_availability = "<p>Yes</p>";
															if ($availability == 0) {
																$caretaker =	get_caretaker($projid, $ptid, $workflow_stage, $technical_substage, 1);
																$p_availability = '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">No</p>';
															}
															?>
															<tr>
																<td>
																	<img src="<?= $file_path; ?>" style="width:30px; height:30px; margin-bottom:0px" />
																</td>
																<td><?= $fullname; ?></td>
																<td><?= $designation; ?></td>
																<td><?= $user_role; ?></td>
																<td><?= $p_availability ?></td>
																<td><?= $email; ?></td>
																<td><?= $phone; ?></td>
															</tr>
															<?php
														}
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<div id="menu2" class="tab-pane fade">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-grey">
													<th width="8%"><strong>Photo</strong></th>
													<th width="32%"><strong>Fullname</strong></th>
													<th width="15%"><strong>Designation</strong></th>
													<th width="10%"><strong>Role</strong></th>
													<th width="10%"><strong>Availability</strong></th>
													<th width="15%"><strong>Email</strong></th>
													<th width="10%"><strong>Phone</strong></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid  AND (team_type=1 OR team_type=2 OR team_type=3) GROUP BY responsible");
												$query_rsMembers->execute(array(":projid" => $projid));
												$total_rsMembers = $query_rsMembers->rowCount();
												if ($total_rsMembers > 0) {
													$rowno = 0;
													while ($row_rsMembers = $query_rsMembers->fetch()) {
														$rowno++;
														$role_id = $row_rsMembers['team_type'];
														if($role_id == 1){
															$user_role = "Output Monitor";
														}elseif($role_id == 2){
															$user_role = "Outcome Evaluator";
														}elseif($role_id == 3){
															$user_role = "Impact Evaluator";
														}
														$ptid = $row_rsMembers['responsible'];
														$query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
														$query_rsPMbrs->execute(array(":user_id" => $ptid));
														$row_rsPMbrs = $query_rsPMbrs->fetch();
														$count_row_rsPMbrs = $query_rsPMbrs->rowCount();
														if ($count_row_rsPMbrs > 0) {
															$mbrministry = $row_rsPMbrs['ministry'];
															$mbrdept = $row_rsPMbrs['department'];
															$mbrdesg = $row_rsPMbrs['designation'];
															$userid = $row_rsPMbrs['userid'];
															$availability = $row_rsPMbrs['availability'];
															$file_path = $row_rsPMbrs['floc'];
															$email = $row_rsPMbrs['email'];
															$phone = $row_rsPMbrs['phone'];
															$fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
															$count_assignedissues = get_issues($projid, $userid);
															$designation = get_designation($mbrdesg);
															$ministries = get_sector($mbrministry);
															$sections = get_sector($mbrdept);
															$directorates = get_sector($mbrministry);

															$ministry = $ministries != '' ? $ministries : "All " . $ministrylabelplural;
															$section = $sections != '' ? $sections : "All " . $departmentlabelplural;
															$directorate = $directorates != '' ? $directorates : "All " . $ministrylabelplural;

															$p_availability = "<p>Yes</p>";
															if ($availability == 0) {
																$caretaker =	get_caretaker($projid, $ptid, $workflow_stage, $technical_substage, 1);
																$p_availability = '<p  data-toggle="tooltip" data-placement="bottom" title="' . $caretaker . '">No</p>';
															}
															?>
															<tr>
																<td>
																	<img src="<?= $file_path; ?>" style="width:30px; height:30px; margin-bottom:0px" />
																</td>
																<td><?= $fullname; ?></td>
																<td><?= $designation; ?></td>
																<td><?= $user_role; ?></td>
																<td><?= $p_availability ?></td>
																<td><?= $email; ?></td>
																<td><?= $phone; ?></td>
															</tr>
															<?php
														}
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
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>