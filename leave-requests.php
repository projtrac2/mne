<?php
require('includes/head.php');

if ($permission) {
	try {
		if ($designation == 6) {
			$where = " WHERE t.ministry = $department_id AND t.department=$section_id";
		} elseif ($designation == 7) {
			$where = " WHERE t.ministry = $department_id AND t.department=$section_id AND t.directorate=$directorate_id";
		} elseif ($designation == 1) {
			$where = "";
		}

		$query_leave_request = $db->prepare("SELECT l.*, tt.title, t.fullname, t.floc, u.userid, c.leavename, t.directorate, t.designation FROM tbl_employee_leave l inner join users u on u.userid=l.employee inner join tbl_projteam2 t on t.ptid=u.pt_id inner join tbl_titles tt on tt.id=t.title inner join tbl_employees_leave_categories c on c.id=l.leavecategory $where ORDER BY l.id ASC");
		$query_leave_request->execute();
		$totalRows_leave_request = $query_leave_request->rowCount();
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
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<!-- start body -->
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr id="colrow">
											<th width="4%"><strong>Photo</strong></th>
											<th width="18%"><strong>Full Name</strong></th>
											<th width="12%"><strong>Designation</strong></th>
											<th width="12%"><strong><?= $directoratelabel ?></strong></th>
											<th width="12%"><strong>Leave Type</strong></th>
											<th width="12%"><strong>Requested Days</strong></th>
											<th width="12%"><strong>Start Date</strong></th>
											<th width="10%"><strong>Requested Status</strong></th>
											<?php
											if (in_array("leave_approval", $page_actions)) {
											?>
												<th width="8%"><strong>Action</strong></th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<!-- =========================================== -->
										<?php
										while ($rows = $query_leave_request->fetch()) {
											$mbrid = $rows['userid'];
											$title = $rows['title'];
											$fullname = $rows["fullname"];
											$employeephoto = $rows["floc"];
											$designationid = $rows["designation"];
											$directorateid = $rows["directorate"];
											$leavename = $rows["leavename"];
											$requestdate = $rows["created_on"];
											$startdate = $rows["startdate"];
											$enddate = $rows["enddate"];
											$leavedays = $rows["days"];
											$requeststatus = $rows["status"];

											if ($requeststatus == 0) {
												$status = '<span class="badge bg-blue">Request Pending</span>';
											} elseif ($requeststatus == 1) {
												$status = '<span class="badge bg-orange">Request Rejected</span>';
											} elseif ($requeststatus == 2) {
												$status = '<span class="badge bg-green">Request Approved</span>';
											}

											$query_designation = $db->prepare("SELECT designation FROM tbl_pmdesignation WHERE position='$designationid'");
											$query_designation->execute();
											$row_designation = $query_designation->fetch();
											$designation = $row_designation["designation"];

											$query_directorate = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$directorateid'");
											$query_directorate->execute();
											$row_directorate = $query_directorate->fetch();
											$directorate = $row_directorate["sector"];

											$mbrid = base64_encode("mbrleave{$mbrid}");
										?>
											<tr>
												<td align="center"><img src="<?php echo $employeephoto; ?>" alt="" style="width:30px; height:30px; margin-bottom:0px" /></td>
												<td><?php echo $title . '.' . $fullname; ?></td>
												<td><?php echo $designation; ?></td>
												<td><?php echo $directorate; ?></td>
												<td><?php echo $leavename; ?></td>
												<td><?php echo $leavedays; ?></td>
												<td><?php echo $startdate; ?></td>
												<td><?= $status ?></td>
												<?php
												if (in_array("leave_approval", $page_actions)) {
												?>
													<td align="center">
														<a type="button" href="leave-request-approval.php?staff=<?= $mbrid ?>" class="btn btn-default" style="color:black">
															<i class="fa fa-check"></i> Details
														</a>
													</td>
											</tr>
									<?php
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