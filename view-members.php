<?php
require('includes/head.php');

if ($permission) {
    try {
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

					if($total_user_projects_count == 0){
						$disable_user = $db->prepare("UPDATE tbl_projteam2 SET disabled = 1 WHERE ptid='$ptid'");
						$disabled = $disable_user->execute();

						if($disabled){
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
					}else{
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

					if($activate){
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
					}else{
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

		if($designation == 6){
			$where =" WHERE ministry = $department_id AND department=$section_id";
		} elseif ($designation == 7){
			$where =" WHERE ministry = $department_id AND department=$section_id AND directorate=$directorate_id";
		} elseif ($designation == 1){
			$where ="";
		}

        $query_rsPTeam = $db->prepare("SELECT *, t.designation AS designation FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id inner join tbl_pmdesignation d on d.moid=t.designation $where ORDER BY position ASC");
        $query_rsPTeam->execute();
        $totalRows_rsPTeam = $query_rsPTeam->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    } ?>

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

											$query_rsNoPrj = $db->prepare("SELECT projid FROM tbl_projmembers WHERE responsible='$mbrid' GROUP BY projid");
											$query_rsNoPrj->execute();
											$row_num = $query_rsNoPrj->fetch();
											$totalRows_rsNoPrj = $query_rsNoPrj->rowCount();

											$query_title = $db->prepare("SELECT title FROM tbl_titles WHERE id='$titleid'");
											$query_title->execute();
											$row_title = $query_title->fetch();

											$query_rsPMDesignation = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid='$desig'");
											$query_rsPMDesignation->execute();
											$row_rsPMDesignation = $query_rsPMDesignation->fetch();
											$totalRows_rsPMDesignation = $query_rsPMDesignation->rowCount();
											$projtmid = base64_encode("projmbr{$mbrid}");


											$numberofprojects = '<a href="view-member-projects.php?mbrid='.$projtmid.'" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">'.$totalRows_rsNoPrj.'</a>';

											if ($mnst == 0) {
												$ministry = "All " . $ministrylabelplural;
												$numberofprojects = "N/A";
											} else {
												$query_rsSC = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$mnst'");
												$query_rsSC->execute();
												$row_rsSC = $query_rsSC->fetch();
												$totalRows_rsSC = $query_rsSC->rowCount();
												$ministry = $row_rsSC["sector"];
											}

											if ($dept == 0) {
												$department = "All " . $departmentlabelplural;
											} else {
												$query_rsDept = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$dept'");
												$query_rsDept->execute();
												$row_rsDept = $query_rsDept->fetch();
												$totalRows_rsDept = $query_rsDept->rowCount();
												$department = $totalRows_rsDept > 0 ? $row_rsDept["sector"] : "";
											}

											if ($directorateid == 0) {
												$directorate = "All " . $directoratelabelplural;
											} else {
												$query_rsDirectorate = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$directorateid'");
												$query_rsDirectorate->execute();
												$row_rsDirectorate = $query_rsDirectorate->fetch();
												$totalRows_rsDirectorate = $query_rsDirectorate->rowCount();
												$directorate = $totalRows_rsDirectorate > 0 ? $row_rsDirectorate["sector"] : "";
											}


											if($disabled == 1){
												$disabledstyle = ';background-color:orange; color:white';
												$availability = "<font color='#FF5722'>Unavailable</font>";
											} else{
												$disabledstyle = "";
												if ($avail == 0) {
													$availability = "<font color='#FF5722'>Unavailable</font>";
												} else {
													$availability = "<font color='#4CAF50'>Available</font>";
												}
											}

											?>
											<tr style="border-bottom:thin solid #EEE <?=$disabledstyle?>">
												<td align="center"><img src="<?php echo $row_rsPTeam['floc']; ?>" alt="" style="width:30px; height:30px; margin-bottom:0px" /></td>
												<td><?php echo $row_title['title'].'.'.$row_rsPTeam['fullname'] ?></td>
												<td><?php echo ($totalRows_rsPMDesignation > 0) ? $row_rsPMDesignation['designation'] : ""; ?></td>
												<td><?php echo $availability; ?></td>
												<td><?php echo $ministry; ?></td>
												<td><?php echo $department; ?></td>
												<td><?php echo $directorate; ?></td>
												<td align="center"><span class="badge bg-purple"><?=$numberofprojects?></span></td>
												<td align="center">
													<div class="btn-group">
														<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
															Options <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li>
																<a type="button" href="view-member-info.php?staff=<?=$projtmid?>"><i class="fa fa-plus-square"></i> Manage</a>
															</li>
															<?php
															if (in_array("update", $page_actions)) {
															?>
																<li>
																	<a type="button" href="add-member.php?action=1&ptid=<?=$projtmid?>"><i class="glyphicon glyphicon-edit"></i> Edit </a>
																</li>
															<?php
															}
															if (in_array("delete", $page_actions)) {
																if ($disabled == 1){
																	?>
																	<li>
																		<a type="button" href="view-members.php?action=1&ptid=<?=$projtmid?>" onclick="return confirm('Are you sure you want to activate this user?')"><i class="glyphicon glyphicon-trash"></i> Activate </a>
																	</li>
																	<?php
																} else {
																	?>
																	<li>
																		<a type="button" href="view-members.php?action=2&ptid=<?=$projtmid?>" onclick="return confirm('Are you sure you want to deactivate this user?')"><i class="glyphicon glyphicon-trash"></i> Deactivate </a>
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
?>