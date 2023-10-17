<?php 
require('includes/head.php');
if ($permission) {
	try {
		$query_all_projects = $db->prepare("SELECT p.projid, p.projname, p.projcategory, g.program_type, g.progid FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid=p.progid WHERE p.deleted = '0' AND p.projstage = 7 ORDER BY p.projstartdate ASC");
		$query_all_projects->execute();
		$total_all_projects_count = $query_all_projects->rowCount();
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
						<div class="body">
							<!-- start body -->
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr id="colrow">
											<td width="4%"><strong>#</strong></td>
											<td width="44%"><strong>Project Name</strong></td>
											<td width="13%"><strong>Project Type</strong></td>
											<td width="13%"><strong>Start Date</strong></td>
											<td width="13%"><strong>End Date</strong></td>
											<td width="13%"><strong>View Files</td>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($total_all_projects_count > 0) {
											$nm = 0;
											while ($row_all_projects = $query_all_projects->fetch()) {
												$projid = $row_all_projects['projid'];
												$progid = $row_all_projects['progid'];
												$project = $row_all_projects['projname'];
												$programtype = $row_all_projects['program_type'];
												$projcategory = $row_all_projects['projcategory'];

												$query_task_dates = $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid WHERE t.projid = :projid");
												$query_task_dates->execute(array(":projid" => $projid));
												$row_task_dates = $query_task_dates->fetch();
												$projenddate = $projstartdate = "";
												if (!is_null($row_task_dates['projstartdate'])) {
													$projenddate = date("d M Y", strtotime($row_task_dates['projenddate']));
													$projstartdate = date("d M Y", strtotime($row_task_dates['projstartdate']));
												}


												$projecttype = $programtype == 1 ? "Strategic Plan" : "Independent";

												$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
												$query_rsPrograms->execute(array(":progid" => $progid));
												$row_rsPrograms = $query_rsPrograms->fetch();
												$totalRows_rsPrograms = $query_rsPrograms->rowCount();

												$query_proj_files = $db->prepare("SELECT * FROM tbl_files f INNER JOIN tbl_project_workflow_stage s ON s.id=f.projstage inner join tbl_projects p on p.projid=f.projid WHERE f.projid = :projid ");
												$query_proj_files->execute(array(":projid" => $projid));
												$totalRows_proj_files = $query_proj_files->rowCount();

												$query_proj_photos = $db->prepare("SELECT *  FROM tbl_project_photos f INNER JOIN tbl_project_workflow_stage s ON s.id=f.projstage inner join tbl_projects p on p.projid=f.projid WHERE f.projid = :projid");
												$query_proj_photos->execute(array(":projid" => $projid));
												$totalRows_proj_photos = $query_proj_photos->rowCount();

												$totalRows_proj_files = $totalRows_proj_files + $totalRows_proj_photos;

												$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
												$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
												$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";

												$filter_department = view_record($project_department, $project_section, $project_directorate);
												if ($filter_department && $totalRows_proj_files > 0 && $projenddate != "") {
													$nm++;
										?>
													<tr>
														<td><?php echo $nm; ?></td>
														<td><?php echo $project; ?></td>
														<td><?php echo $projecttype; ?></td>
														<td><?php echo $projstartdate; ?></td>
														<td><?php echo $projenddate; ?></td>
														<td align="center">
															<span class="badge bg-brown"><?= $totalRows_proj_files ?></span> <a href="view-project-files?projid=<?= base64_encode("prjfile5{$projid}") ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="View Project File"><i class="fa fa-folder-open fa-2x" aria-hidden="true"></i></a>
														</td>
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
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>