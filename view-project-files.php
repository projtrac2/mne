<?php
try {
	require('includes/head.php');

	if ($permission) {
		if (isset($_GET['projid'])) {
			$hash = $_GET['projid'];
			$decode_projid = base64_decode($hash);
			$projid_array = explode("prjfile5", $decode_projid);
			$projid = $projid_array[1];
		}

		$query_project_files = $db->prepare("SELECT f.*, s.stage, p.progid FROM tbl_files f INNER JOIN tbl_project_workflow_stage s ON s.id=f.projstage inner join tbl_projects p on p.projid=f.projid WHERE f.projid = $projid ORDER BY f.projstage, f.ftype");
		$query_project_files->execute();
		$total_project_files = $query_project_files->rowCount();

		$query_project_photos = $db->prepare("SELECT f.*, s.stage, p.progid FROM tbl_project_photos f INNER JOIN tbl_project_workflow_stage s ON s.id=f.projstage inner join tbl_projects p on p.projid=f.projid WHERE f.projid = $projid ORDER BY f.projstage, f.ftype");
		$query_project_photos->execute();
		$total_project_photos = $query_project_photos->rowCount();

?>

		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon ?>
						<?= $pageTitle ?>

						<div class="btn-group" style="float:right; margin-right:5px">
							<button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
								Go Back
							</button>
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
												<td width="4%"><strong>#</strong></td>
												<td width="25%"><strong>File Name</strong></td>
												<td width="16%"><strong>Project Stage</strong></td>
												<td width="10%"><strong>File Category</strong></td>
												<td width="15%"><strong>Purpose</strong></td>
												<td width="10%"><strong>File Type</strong></td>
												<td width="10%"><strong>File Date</strong></td>
												<td width="10%"><strong>Download</strong></td>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($total_project_files > 0 || $total_project_photos > 0) {
												$nm = 0;
												while ($row_files = $query_project_files->fetch()) {
													$progid = $row_files['progid'];
													$flupdate = strtotime($row_files['date_uploaded']);
													$filedate = date("d M Y", $flupdate);

													$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
													$query_rsPrograms->execute(array(":progid" => $progid));
													$row_rsPrograms = $query_rsPrograms->fetch();
													$totalRows_rsPrograms = $query_rsPrograms->rowCount();

													$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
													$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
													$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";

													$filter_department = view_record($project_department, $project_section, $project_directorate);
													if ($filter_department) {
														$nm++;
											?>
														<tr>
															<td><?php echo $nm; ?></td>
															<td><?php echo $row_files['filename']; ?></td>
															<td><?php echo $row_files['stage']; ?></td>
															<td><?php echo $row_files['fcategory']; ?></td>
															<td><?php echo $row_files['reason']; ?></td>
															<td><?php echo $row_files['ftype']; ?></td>
															<td><?php echo $filedate; ?></td>
															<td align="center"><a href="<?php echo $row_files['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a></td>
														</tr>
													<?php
													}
												}
												while ($row_files = $query_project_photos->fetch()) {
													$progid = $row_files['progid'];
													$flupdate = strtotime($row_files['date_uploaded']);
													$filedate = date("d M Y", $flupdate);

													$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
													$query_rsPrograms->execute(array(":progid" => $progid));
													$row_rsPrograms = $query_rsPrograms->fetch();
													$totalRows_rsPrograms = $query_rsPrograms->rowCount();

													$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
													$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
													$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";

													$filter_department = view_record($project_department, $project_section, $project_directorate);
													if ($filter_department) {
														$nm++;
													?>
														<tr>
															<td><?php echo $nm; ?></td>
															<td><?php echo $row_files['filename']; ?></td>
															<td><?php echo $row_files['stage']; ?></td>
															<td><?php echo $row_files['fcategory']; ?></td>
															<td><?php echo $row_files['reason']; ?></td>
															<td><?php echo $row_files['ftype']; ?></td>
															<td><?php echo $filedate; ?></td>
															<td align="center"><a href="<?php echo $row_files['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a></td>
														</tr>
											<?php
													}
												}
											}
											?>
										</tbody>
									</table>
								</div>
								<!-- end body -->
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>