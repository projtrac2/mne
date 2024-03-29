<?php
require('includes/head.php');
if ($permission) {
	try {
		$decode_progid = (isset($_GET['prg']) && !empty($_GET["prg"])) ? base64_decode($_GET['prg']) : "";
		$progid_array = explode("progid54321", $decode_progid);
		$progid = $progid_array[1];

		function check_approve_project($projid, $projevaluation, $projimpact)
		{
			global $db, $projid;
			$query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
			$query_rsOutput->execute(array(":projid" => $projid));
			$totalRows_rsOutput = $query_rsOutput->rowCount();
			$output = $totalRows_rsOutput > 0 ? true : false;
			$outcome = true;
			if ($projevaluation == 1) {
				$sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
				$sql->execute(array(":projid" => $projid));
				$rows_count = $sql->rowCount();
				$outcome = $rows_count > 0 ? true : false;
			}

			$impact = true;
			if ($projimpact == 1) {
				$sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
				$sql->execute(array(":projid" => $projid));
				$row_count = $sql->rowCount();
				$impact = $row_count > 0 ? true : false;
			}
			return $output && $outcome && $impact ? true : false;
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
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
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="28%">Project Name</th>
											<th width="27%">Program Name</th>
											<th width="10%">Budget</th>
											<th width="10%">Financial Year</th>
											<th width="8%">Status</th>
											<th width="8%">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE progid = :progid ORDER BY `projfscyear` ASC");
										$sql->execute(array(":progid" => $progid));
										$rows_count = $sql->rowCount();
										if ($rows_count > 0) {
											$sn = 0;
											$active = "";
											while ($row = $sql->fetch()) {
												$projid = $row['projid'];
												$budget = $row['projcost'];
												$username = $row['user_name'];
												$projevaluation = $row['projevaluation'];
												$projimpact = $row['projimpact'];
												$projname = $row["projname"];
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
												$projYear  = $rowprojYear['year'];
												$yr  = $rowprojYear['yr'];

												// get department
												$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
												$query_rsDept->execute(array(":sector" => $projdept));
												$row_rsDept = $query_rsDept->fetch();
												$department = $row_rsDept['sector'];
												$totalRows_rsDept = $query_rsDept->rowCount();

												$query_adp =  $db->prepare("SELECT *, p.status as status FROM tbl_annual_dev_plan p inner join tbl_fiscal_year y ON y.id=p.financial_year WHERE projid = :projid");
												$query_adp->execute(array(":projid" => $projid));
												$row_adp = $query_adp->fetch();
												$totalRows_adp = $query_adp->rowCount();
												$adpstatus = $totalRows_adp > 0 ? $row_adp["status"] : "";

												$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

												if ($totalRows_adp == 1) {
													$status = $row_adp["year"] . " ADP";
													if ($adpstatus == 1) {
														$active = '<label class="label label-success" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Approved" >' . $status . '</label>';
													} else {
														$active = '<label class="label label-primary" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending Approval" >' . $status . '</label>';
													}
												} else {
													$mneplan = check_approve_project($projid, $projevaluation, $projimpact);
													$status = !$mneplan ? "Pending M&E Plan" : "Pending ADP";
													$labelclass = !$mneplan ? "label-danger" : "label-warning";

													$active = '<label class="label ' . $labelclass . '" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending ADP">' . $status . '</label>';
												}

												$month =  date('m');
												$currentYear = ($month  < 7) ? date("Y") - 1 : date("Y");
												$filter_department = view_record($project_department, $project_section, $project_directorate);

												if ($filter_department) {
													$sn++;
										?>
													<tr>
														<td><?= $sn ?> </td>
														<td><?= $projname ?> </td>
														<td><?= $progname ?> </td>
														<td><?= number_format($budget, 2) ?> </td>
														<td><?= $projYear ?> </td>
														<td><?= $active ?> </td>
														<td>
															<a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(<?= $projid ?>)" class="btn btn-info btn-xm"> <i class="fa fa-info fa-lg" aria-hidden="true"></i> More Info</a>
														</td>
													</tr>
										<?php
												}
											}
										} // if num_rows
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->



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
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
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
<script src="assets/js/projects/view-project.js"></script>