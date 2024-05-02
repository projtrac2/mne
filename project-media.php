<?php
try {
	require('includes/head.php');
	if ($permission && (isset($_GET['proj']) && !empty($_GET["proj"]))) {
		$decode_projid = base64_decode($_GET['proj']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];
		$original_projid = $_GET['proj'];

		$back_url = $_SESSION['back_url'];
		$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
		$query_rsProjects->execute(array(":projid" => $projid));
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
		$projname = $totalRows_rsProjects > 0 ? $row_rsProjects['projname'] : "";
		$projstage = $row_rsProjects["projstage"];
		$projcat = $row_rsProjects["projcategory"];
		$percent2 = number_format(calculate_project_progress($projid, $projcat), 2);

?>
		<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon . "  " . $pageTitle ?>
						<div class="btn-group" style="float:right; margin-right:10px">
							<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='<?= $back_url ?>'" id="btnback">
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="header" style="padding-bottom:0px">
								<div class="" style="margin-top:-15px">
									<a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
									<a href="project-mne-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px"> M&E </a>
									<a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
									<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
									<?php if ($projcat == 2 && $projstage > 4) { ?>
										<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
									<?php } ?>
									<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
									<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
									<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Map</a>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Media</a>
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
							<div class="card-header">
								<ul class="nav nav-tabs" style="font-size:14px">
									<li class="active">
										<a data-toggle="tab" href="#menu1"><i class="fa fa-file-text-o bg-green" aria-hidden="true"></i> Documents &nbsp;<span class="badge bg-green">|</span></a>
									</li>
									<li>
										<a data-toggle="tab" href="#menu2"><i class="fa fa-file-image-o bg-blue" aria-hidden="true"></i> Photos &nbsp;<span class="badge bg-blue">|</span></a>
									</li>
									<li>
										<a data-toggle="tab" href="#menu3"><i class="fa fa-file-video-o bg-orange" aria-hidden="true"></i> Videos &nbsp;<span class="badge bg-orange">|</span></a>
									</li>
								</ul>
							</div>
							<div class="body">
								<div class="tab-content">
									<div id="menu1" class="tab-pane fade in active">
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr class="bg-grey">
																<th width="5%"><strong>#</strong></th>
																<th width="30%"><strong>Name</strong></th>
																<th width="30%"><strong>Purpose</strong></th>
																<th width="10%"><strong>Stage</strong></th>
																<th width="10%"><strong>Created On</strong></th>
																<th width="10%"><strong>Action</strong></th>
															</tr>
														</thead>
														<tbody>
															<?php
															$query_project_docs = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid and (ftype<>'jpg' and ftype<>'jpeg' and ftype<>'png' and ftype<>'mp4')");
															$query_project_docs->execute(array(":projid" => $projid));
															$count_project_docs = $query_project_docs->rowCount();
															if ($count_project_docs > 0) {
																$rowno = 0;
																while ($rows_project_docs = $query_project_docs->fetch()) {
																	$rowno++;
																	$projstageid = $rows_project_docs['projstage'];
																	$filename = $rows_project_docs['filename'];
																	$filepath = $rows_project_docs['floc'];
																	$purpose = $rows_project_docs['reason'];
																	$created_at = $rows_project_docs['date_uploaded'];

																	$query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
																	$query_project_stage->execute(array(":projstageid" => $projstageid));
																	$rows_project_stage = $query_project_stage->fetch();
																	$projstage = $rows_project_stage['stage'];
															?>
																	<tr>
																		<td width="5%"><?= $rowno; ?></td>
																		<td width="30%"><?= $filename; ?></td>
																		<td width="30%"><?= $purpose; ?></td>
																		<td width="10%"><?= $projstage; ?></td>
																		<td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
																		<td width="15%">
																			<a href="<?= $filepath; ?>" download>Download</a>
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
									<div id="menu2" class="tab-pane fade">
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr class="bg-grey">
																<th width="5%"><strong>#</strong></th>
																<th width="5%"><strong>Photo</strong></th>
																<th width="40%"><strong>Name</strong></th>
																<th width="30%"><strong>Purpose</strong></th>
																<th width="10%"><strong>Created On</strong></th>
																<th width="10%"><strong>Stage</strong></th>
															</tr>
														</thead>
														<tbody>
															<?php
															$query_project_photos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid and (ftype='jpg' or ftype='jpeg' or ftype='png')");
															$query_project_photos->execute(array(":projid" => $projid));
															$count_project_photos = $query_project_photos->rowCount();
															if ($count_project_photos > 0) {
																$rowno = 0;
																while ($rows_project_photos = $query_project_photos->fetch()) {
																	$rowno++;
																	$fileid = $rows_project_photos['fid'];
																	$projstageid = $rows_project_photos['projstage'];
																	$filename = $rows_project_photos['filename'];
																	$filepath = $rows_project_photos['floc'];
																	$purpose = $rows_project_photos['reason'];
																	$created_at = $rows_project_photos['date_uploaded'];
																	$fileid = base64_encode("projid54321{$fileid}");

																	$photo = '<a href="project-gallery.php?photo=' . $fileid . '" class="gallery-item">
																	 <img class="img-fluid" src="' . $filepath . '" alt="Click to view the photo" style="width:30px; height:30px; margin-bottom:0px"/>
																</a>';

																	$query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
																	$query_project_stage->execute(array(":projstageid" => $projstageid));
																	$rows_project_stage = $query_project_stage->fetch();
																	$projstage = $rows_project_stage['stage'];
															?>
																	<tr>
																		<td width="5%"><?= $rowno; ?></td>
																		<td width="5%"><?= $photo; ?></td>
																		<td width="40%"><?= $filename; ?></td>
																		<td width="30%"><?= $purpose; ?></td>
																		<td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
																		<td width="10%"><?= $projstage; ?></td>
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
									<div id="menu3" class="tab-pane fade">
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr class="bg-grey">
																<th width="5%"><strong>#</strong></th>
																<th width="35%"><strong>Name</strong></th>
																<th width="30%"><strong>Purpose</strong></th>
																<th width="10%"><strong>Stage</strong></th>
																<th width="10%"><strong>Created On</strong></th>
																<th width="10%"><strong>Action</strong></th>
															</tr>
														</thead>
														<tbody>
															<?php
															$query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid and ftype='mp4'");
															$query_project_videos->execute(array(":projid" => $projid));
															$count_project_videos = $query_project_videos->rowCount();
															if ($count_project_videos > 0) {
																$rowno = 0;
																while ($rows_project_videos = $query_project_videos->fetch()) {
																	$rowno++;
																	$projstageid = $rows_project_videos['projstage'];
																	$filename = $rows_project_videos['filename'];
																	$filepath = $rows_project_videos['floc'];
																	$purpose = $rows_project_videos['reason'];
																	$created_at = $rows_project_videos['date_uploaded'];

																	$query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
																	$query_project_stage->execute(array(":projstageid" => $projstageid));
																	$rows_project_stage = $query_project_stage->fetch();
																	$projstage = $rows_project_stage['stage'];
															?>
																	<tr>
																		<td width="5%"><?= $rowno; ?></td>
																		<td width="35%"><?= $filename; ?></td>
																		<td width="35%"><?= $purpose; ?></td>
																		<td width="10%"><?= $projstage; ?></td>
																		<td width="10%"><?= date('d M Y', strtotime($created_at)) ?></td>
																		<td width="15%">
																			<a href="<?= $filepath; ?>" watch>Watch</a>
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
				</div>
			</div>
		</section>
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