<?php
require('includes/head.php');

if ($permission) {
	try {
		$query_mne_projects = $db->prepare("SELECT * FROM tbl_projects where projstage > 7");
		$query_mne_projects->execute();
		$count_mne_projects = $query_mne_projects->rowCount();
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
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="table-responsive">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> LIST </legend>
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr class="bg-light-green">
												<th style="width:3%">#</th>
												<th style="width:60%">Project Name</th>
												<th style="width:15%">Start Date</th>
												<th style="width:15%">End Date</th>
												<th style="width:7%">Report</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$nm = 0;
											while ($rows_mne_projects = $query_mne_projects->fetch()) {
												$projid = $rows_mne_projects['projid'];
												$project = $rows_mne_projects['projname'];
												$projoutcome = $rows_mne_projects['projevaluation'];
												$projcategory = $rows_mne_projects['projcategory'];
												$projstartdate = $rows_mne_projects['projstartdate'];
												$projenddate = $rows_mne_projects['projenddate'];
												$duration = $rows_mne_projects['projduration'];

												$projsdate  = date('d M Y', strtotime($projstartdate));
												$projedate  = date('d M Y', strtotime($projenddate));

												if ($projcategory == '2') {
													$query_projdates = $db->prepare("SELECT startdate, enddate FROM tbl_tenderdetails  where projid=:projid");
													$query_projdates->execute(array(":projid" => $projid));
													$row_projdates = $query_projdates->fetch();
													$projsdate  = date('d M Y', strtotime($row_projdates['startdate']));
													$projedate  = date('d M Y', strtotime($row_projdates['enddate']));
												}

												$rows_mne_status = 1;
												if ($projoutcome == 1) {
													$query_mne_status = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid");
													$query_mne_status->execute(array(":projid" => $projid));
													$rows_mne_status = $query_mne_status->rowCount();
												}

												if ($rows_mne_status > 0) {
													$nm = $nm + 1;
													$project_id_hash = base64_encode("rept321{$projid}");
											?>
													<tr style="background-color:#eff9ca">
														<td align="center"><?php echo $nm; ?></td>
														<td><?php echo $project; ?></td>
														<td><?php echo $projsdate; ?></td>
														<td><?php echo $projedate; ?></td>
														<td>
															<div align="center">
																<a href="project-mne-report?proj=<?php echo $project_id_hash; ?>" alt="View Project M&E Report" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Project M&E Report"><i class="fa fa-bar-chart fa-2x text-success" aria-hidden="true"></i></a>
															</div>
														</td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</fieldset>
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
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>