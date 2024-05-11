<?php
try {
	require('includes/head.php');
	if ($permission) {
		if (isset($_GET["ptid"]) && !empty($_GET["ptid"])) {
			$encoded_userid = $_GET["ptid"];
			$decode_userid = base64_decode($encoded_userid);
			$userid_array = explode("projmbr", $decode_userid);
			$userid = $userid_array[1];
			if (isset($_GET["action"]) && !empty($_GET["action"])) {
				$query_user = $db->prepare("SELECT pt_id FROM users WHERE userid='$userid'");
				$query_user->execute();
				$row_user = $query_user->fetch();
				$ptid = $row_user["pt_id"];

				if ($_GET["action"] == 2) {
					$query_user_projects = $db->prepare("SELECT * FROM tbl_projmembers m inner join tbl_projects p on p.projid=m.projid WHERE projstage > 3 AND ptid='$userid' AND (projstatus<>5 AND projstatus<>2)");
					$query_user_projects->execute();
					$total_user_projects_count = $query_user_projects->rowCount();

					if ($total_user_projects_count == 0) {
						$disable_user = $db->prepare("UPDATE tbl_projteam2 SET disabled = 1 WHERE ptid='$ptid'");
						$disabled = $disable_user->execute();

						if ($disabled) {
							$msg = 'You have successfully disabled the user!';
							$results =
								"<script type=\"text/javascript\">
								swal({
									title: \"Success!\",
									text: \" $msg\",
									type: 'Success',
									timer: 2000,
									'icon':'success',
									showConfirmButton: false });
								setTimeout(function(){
									window.location.href = 'view-members.php';
								}, 2000);
							</script>";
						}
					} else {
						$msg = 'Sorry you cannot disable this user, has assigned projects!';
						$results =
							"<script type=\"text/javascript\">
							swal({
								title: \"Warning!\",
								text: \" $msg\",
								type: 'Warning',
								timer: 2000,
								'icon':'warning',
								showConfirmButton: false });
						</script>";
					}
				} else {
					$activate_user = $db->prepare("UPDATE tbl_projteam2 SET disabled = 0 WHERE ptid='$ptid'");
					$activate = $activate_user->execute();

					if ($activate) {
						$msg = 'You have successfully activated the user!';
						$results =
							"<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 2000,
								'icon':'success',
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'view-members.php';
							}, 2000);
						</script>";
					} else {
						$msg = 'Sorry you cannot activate this user, error occured!!';
						$results =
							"<script type=\"text/javascript\">
							swal({
								title: \"Warning!\",
								text: \" $msg\",
								type: 'Warning',
								timer: 2000,
								'icon':'warning',
								showConfirmButton: false });
						</script>";
					}
				}
			}
		}

		if ($designation == 6) {
			$where = " WHERE ministry = $department_id AND department=$section_id";
		} elseif ($designation == 7) {
			$where = " WHERE ministry = $department_id AND department=$section_id AND directorate=$directorate_id";
		} elseif ($designation == 1) {
			$where = "";
		}

		$query_rsPTeam = $db->prepare("SELECT *, t.designation AS designation FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id inner join tbl_pmdesignation d on d.moid=t.designation $where ORDER BY position ASC");
		$query_rsPTeam->execute();
		$totalRows_rsPTeam = $query_rsPTeam->rowCount();


		function get_department_list($user_id, $department_id)
		{
			global $db;
			$workflow_stage = 10;
			$query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND (team_type=2 OR team_type=3 OR team_type=4 OR team_type=5) AND g.projsector<>:department_id GROUP BY m.projid");
			$query_rsNoPrj->execute(array(":responsible" => $user_id, ":department_id" => $department_id));
			$technical_projects = $query_rsNoPrj->rowCount();

			$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND g.projsector=:department_id ORDER BY p.projid DESC");
			$query_rsProjects->execute(array(":workflow_stage" => $workflow_stage, ":department_id" => $department_id));
			$department_projects = $query_rsProjects->rowCount();

			return $technical_projects + $department_projects;
		}

		function get_section_list($user_id, $section_id)
		{
			global $db;
			$workflow_stage = 10;
			$query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND (team_type=2 OR team_type=3 OR team_type=4 OR team_type=5) AND g.projdept<>:section_id GROUP BY m.projid");
			$query_rsNoPrj->execute(array(":responsible" => $user_id, ":section_id" => $section_id));
			$technical_projects = $query_rsNoPrj->rowCount();

			$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND g.projdept=:section_id ORDER BY p.projid DESC");
			$query_rsProjects->execute(array(":workflow_stage" => $workflow_stage, ":section_id" => $section_id));
			$section_projects = $query_rsProjects->rowCount();
			return $technical_projects + $section_projects;
		}

		function get_directorate_list($user_id, $directorate_id)
		{
			global $db;
			$workflow_stage = 10;
			$query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND (team_type=2 OR team_type=3 OR team_type=4 OR team_type=5) AND g.directorate<>:directorate_id GROUP BY m.projid");
			$query_rsNoPrj->execute(array(":responsible" => $user_id, ":directorate_id" => $directorate_id));
			$technical_projects = $query_rsNoPrj->rowCount();

			$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND g.directorate=:directorate_id ORDER BY p.projid DESC");
			$query_rsProjects->execute(array(":workflow_stage" => $workflow_stage, ":directorate_id" => $directorate_id));
			$directorate_projects = $query_rsProjects->rowCount();
			return $technical_projects + $directorate_projects;
		}

		function get_sectors($sector_id)
		{
			global $db;
			$query_rsDept = $db->prepare("SELECT * FROM tbl_sectors WHERE stid=:sector_id");
			$query_rsDept->execute(array(":sector_id" => $sector_id));
			$row_rsDept = $query_rsDept->fetch();
			$totalRows_rsDept = $query_rsDept->rowCount();
			return $totalRows_rsDept > 0 ? $row_rsDept["sector"] : "";
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
								<!-- start body -->
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr id="colrow">
												<th width="3%"><strong>Photo</strong></th>
												<th width="18%"><strong>Full name</strong></th>
												<th width="13%"><strong>Designation</strong></th>
												<th width="12%"><strong>Availability</strong></th>
												<th width="13%"><strong><?= $ministrylabel ?></strong></th>
												<th width="13%"><strong><?= $departmentlabel ?></strong></th>
												<th width="12%"><strong><?= $directoratelabel ?></strong></th>
												<th width="8%"><strong>Projects</strong></th>
												<!--COLSPAN=4-->
												<th width="8%"><strong>Action</strong></th>
												<!--COLSPAN=4-->
											</tr>
										</thead>
										<tbody>
											<!-- =========================================== -->
											<?php
											while ($row_rsPTeam = $query_rsPTeam->fetch()) {
												$mbrid = $row_rsPTeam['userid'];
												$titleid = $row_rsPTeam['title'];
												$desig = $row_rsPTeam["designation"];
												$mnst = $row_rsPTeam["ministry"];
												$dept = $row_rsPTeam["department"];
												$directorateid = $row_rsPTeam["directorate"];
												$avail = $row_rsPTeam["availability"];
												$disabled = $row_rsPTeam["disabled"];
												$projtmid = base64_encode("projmbr{$mbrid}");
												$row_rsPTeam['floc'];

												$query_title = $db->prepare("SELECT title FROM tbl_titles WHERE id=:titleid");
												$query_title->execute(array(":titleid" => $titleid));
												$row_title = $query_title->fetch();
												$full_name = $row_title['title'] . '.' . $row_rsPTeam['fullname'];

												$query_rsPMDesignation = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid=:desig");
												$query_rsPMDesignation->execute(array(":desig" => $desig));
												$row_rsPMDesignation = $query_rsPMDesignation->fetch();
												$totalRows_rsPMDesignation = $query_rsPMDesignation->rowCount();
												$designation_name = ($totalRows_rsPMDesignation > 0) ? $row_rsPMDesignation['designation'] : "";

												$ministry = "All " . $ministrylabelplural;
												$department = "All " . $departmentlabelplural;
												$directorate = "All " . $directoratelabelplural;
												if ($mnst != 0) {
													$ministry = get_sector($mnst);
													$total_projects = get_department_list($mbrid, $mnst);
													if ($dept != 0) {
														$department = get_sector($dept);
														$total_projects = get_section_list($mbrid, $dept);
														if ($directorateid != 0) {
															$directorate = get_sector($directorateid);
															$total_projects =  get_directorate_list($mbrid, $directorateid);
														}
													}
												}

												if ($disabled == 1) {
													$disabledstyle = ';background-color:orange; color:white';
													$availability = "<font color='#FF5722'>Unavailable</font>";
												} else {
													$disabledstyle = "";
													if ($avail == 0) {
														$availability = "<font color='#FF5722'>Unavailable</font>";
													} else {
														$availability = "<font color='#4CAF50'>Available</font>";
													}
												}

											?>
												<tr style="border-bottom:thin solid #EEE <?= $disabledstyle ?>">
													<td align="center"><img src="<?= $avatar; ?>" alt="" style="width:30px; height:30px; margin-bottom:0px" /></td>
													<td><?= $full_name ?></td>
													<td><?= $designation_name ?></td>
													<td><?= $availability; ?></td>
													<td><?= $ministry . $mnst; ?></td>
													<td><?= $department; ?></td>
													<td><?= $directorate; ?></td>
													<td align="center">
														<span class="badge bg-purple">
															<a href="view-member-projects.php?mbrid=<?= $projtmid ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
																<?= $total_projects ?>
															</a>
														</span>
													</td>
													<td align="center">
														<div class="btn-group">
															<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
																Options <span class="caret"></span>
															</button>
															<ul class="dropdown-menu">
																<li>
																	<a type="button" href="view-member-info.php?staff=<?= $projtmid ?>"><i class="fa fa-plus-square"></i> Manage</a>
																</li>
																<?php
																if (in_array("update", $page_actions)) {
																?>
																	<li>
																		<a type="button" href="add-member.php?action=1&ptid=<?= $projtmid ?>"><i class="glyphicon glyphicon-edit"></i> Edit </a>
																	</li>
																	<?php
																}
																if (in_array("delete", $page_actions)) {
																	if ($disabled == 1) {
																	?>
																		<li>
																			<a type="button" href="view-members.php?action=1&ptid=<?= $projtmid ?>" onclick="return confirm('Are you sure you want to activate this user?')"><i class="glyphicon glyphicon-trash"></i> Activate </a>
																		</li>
																	<?php
																	} else {
																	?>
																		<li>
																			<a type="button" href="view-members.php?action=2&ptid=<?= $projtmid ?>" onclick="return confirm('Are you sure you want to deactivate this user?')"><i class="glyphicon glyphicon-trash"></i> Deactivate </a>
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
											} ?>
										</tbody>
									</table>
								</div>
								<!-- end body -->
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>