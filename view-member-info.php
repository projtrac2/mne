<?php
require('functions/strategicplan.php');
require('includes/head.php');
if ($permission) {
	try {
		$deptlebel = "";

		if (isset($_GET["staff"]) && !empty($_GET["staff"])) {
			$encoded_userid = $_GET["staff"];
			$decode_userid = base64_decode($encoded_userid);
			$userid_array = explode("projmbr", $decode_userid);
			$userid = $userid_array[1];
		}

		$current_date = date("Y-m-d");

		if (isset($_POST["leave"])) {
			$catid = $_POST['leave'];
			$startdate = $_POST['startdate'];
			$employeeid = $_POST['employee'];
			$leavedays = $_POST['leavedays'];

			function add_work_days($stdate, $day)
			{
				if ($day == 0)
					return $stdate;
				$stdate->add(new DateInterval('P1D'));
				if (!in_array($stdate->format('N'), array('6', '7')))
					$day--;
				return add_work_days($stdate, $day);
			}
			$sdate = strtotime($startdate);
			$stdate  = add_work_days(new DateTime(), $leavedays);
			$leavenddate = $stdate->format('Y-m-d');

			if (!empty($leavedays)) {
				$insertSQL = $db->prepare("INSERT INTO tbl_employee_leave (employee, leavecategory, days, startdate, enddate, created_by, created_on) VALUES (:ptid, :catid, :days, :startdate, :enddate, :user, :date)");
				$insertSQL->execute(array(':ptid' => $employeeid, ':catid' => $catid, ':days' => $leavedays, ':startdate' => $startdate, ':enddate' => $leavenddate, ':user' => $user_name, ':date' => $current_date));
				$last_id = $db->lastInsertId();

				$msg = 'Leave requested successfully';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						'icon':'success',
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'view-member-info.php?staff=$encoded_userid';
					}, 2000);
				</script>";
			} else {

				$msg = 'Error saving the record!';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Warning!\",
						text: \" $msg\",
						type: 'Warning',
						timer: 2000,
						'icon':'warning',
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'view-member-info.php?staff=$encoded_userid';
					}, 2000);
				</script>";
			}
		}

		$query_rsStaff =  $db->prepare("SELECT t.*, t.designation AS design, d.designation AS desgn FROM tbl_projteam2 t inner join tbl_pmdesignation d ON t.designation=d.position inner join users u on u.pt_id=t.ptid WHERE userid = '$userid'");
		$query_rsStaff->execute();
		$row_rsStaff = $query_rsStaff->fetch();
		$count_row_rsStaff = $query_rsStaff->rowCount();

		$titleid = $row_rsStaff["title"];
		$query_title =  $db->prepare("SELECT title FROM tbl_titles where id='$titleid'");
		$query_title->execute();
		$row_title = $query_title->fetch();
		$title = $row_title['title'];

		$mydesign = $myministry = $mydept = $mydirectorate = $emplststus = "";

		if ($count_row_rsStaff > 0) {
			$mydesign = $row_rsStaff['design'];
			$myministry = $row_rsStaff['ministry'];
			$mydept = $row_rsStaff['department'];
			$mydirectorate = $row_rsStaff['directorate'];
			$availability = $row_rsStaff["availability"];
			$designation = $row_rsStaff["designation"];
		}

		if ($availability == 1) {
			$emplststus =  "<font color='indigo'>Available</font>";
		} else {
			$emplststus =  "<font color='deep-orange'>Unavailable</font>";
		}

		$query_rsLeave =  $db->prepare("SELECT id, leavename FROM tbl_employees_leave_categories ORDER BY id ASC");
		$query_rsLeave->execute();
		$row_rsLeave = $query_rsLeave->fetch();

		$query_mbrprojs =  $db->prepare("SELECT projid FROM tbl_projmembers WHERE responsible='$userid' GROUP BY projid");
		$query_mbrprojs->execute();
		$count_mbrprojs = $query_mbrprojs->rowCount();

		if ($mydesign == 6) {
			$level =  " AND department='$mydept' AND directorate=0";
			$query_sector =  $db->prepare("SELECT * FROM tbl_sectors where stid='$mydept'");
			$query_sector->execute();
			$row_sector = $query_sector->fetch();
			$deptlebel = $row_sector["sector"];
		} elseif ($mydesign > 6) {
			$level =  " AND department='$mydept' AND directorate='$mydirectorate'";
			$query_sector =  $db->prepare("SELECT * FROM tbl_sectors where stid='$mydirectorate'");
			$query_sector->execute();
			$row_sector = $query_sector->fetch();
			$deptlebel = $row_sector["sector"];
		}

		$query_rsLvDetails =  $db->prepare("SELECT C.leavename, L.days, L.startdate, L.enddate, L.status FROM tbl_employee_leave L INNER JOIN tbl_employees_leave_categories C ON L.leavecategory=C.id WHERE L.employee = '$userid'  ORDER BY L.id DESC LIMIT 1");
		$query_rsLvDetails->execute();
		$row_rsLvDetails = $query_rsLvDetails->fetch();
		$rows_rsLvDetails = $query_rsLvDetails->rowCount();
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
							<?php if ($designation < 7) { ?>
								<fieldset class="scheduler-border" style="font-size:15px">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="left">
											<div class="form-line">
												<img src="<?php echo $row_rsStaff['floc']; ?>" id="mbr" class="img img-rounded" style="border: 1px solid red; border-radius: 5px;" width="120" />
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<strong>Full Name:</strong>
											<font color="indigo"><?php echo $title . ". " . $row_rsStaff["fullname"]; ?></font>
										</div>
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
											<strong>Position:</strong>
											<font color="indigo"><?php echo $row_rsStaff["desgn"] . " " . $deptlebel; ?></font>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<strong>Phone Number:</strong>
											<font color="indigo"><?php echo $row_rsStaff['phone'] ?></font>
										</div>
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
											<strong>Email Address:</strong>
											<font color="indigo"><?php echo $row_rsStaff['email'] ?></font>
										</div>
									</div>
								</fieldset>
							<?php } else { ?>
								<fieldset class="scheduler-border" style="font-size:15px">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="left">
											<div class="form-line">
												<img src="<?php echo $row_rsStaff['floc']; ?>" id="mbr" class="img img-rounded" style="border: 1px solid red; border-radius: 5px;" width="120" />
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<strong>Full Name:</strong>
											<font color="indigo"><?php echo $title . ". " . $row_rsStaff["fullname"]; ?></font>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<strong>Position:</strong>
											<font color="indigo"><?php echo $row_rsStaff["desgn"] . " " . $deptlebel; ?></font>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<strong>Current Status:</strong>
											<font color="indigo"><?php echo $emplststus; ?></font>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<strong>Phone Number:</strong>
											<font color="indigo"><?php echo $row_rsStaff['phone'] ?></font>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<strong>Email Address:</strong>
											<font color="indigo"><?php echo $row_rsStaff['email'] ?></font>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">LEAVE DETAILS</legend>
											<?php
											if ($availability == 1) {
												if ($rows_rsLvDetails > 0 && $row_rsLvDetails["enddate"] > $current_date && $row_rsLvDetails["status"] == 0) {
											?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label class="text-danger">Leave request is still pending approval!!</label>
													</div>
												<?php
												} else {
												?>
													<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<label>Leave Type *:</label>
															<div class="form-line">
																<select name="leave" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																	<option value="">... Select Leave ...</option>
																	<?php
																	do {
																	?>
																		<option value="<?php echo $row_rsLeave['id'] ?>"><?php echo $row_rsLeave['leavename'] ?></option>
																	<?php
																	} while ($row_rsLeave = $query_rsLeave->fetch());
																	?>
																</select>
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
															<label>Leave Start Dat *:</label>
															<div class="form-line">
																<input name="startdate" type="date" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required>
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
															<label>Leave days *:</label>
															<div class="form-line">
																<input name="leavedays" type="number" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required>
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
															<div class="form-line" align="center" style="padding-top:15px">
																<input name="employee" type="hidden" value="<?= $userid ?>" />
																<input name="submit" type="submit" class="btn btn-success" id="submit" value="Submit" />
															</div>
														</div>
													</form>
												<?php
												}
											} else if ($row_rsStaff["availability"] != 1) {
												if ($row_rsLvDetails["enddate"] < $current_date) {
													$classcolor = "bg-danger";
												}
												?>
												<div class="row <?= $classcolor ?>">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Leave Type: <font color="indigo"><?php echo $row_rsLvDetails["leavename"]; ?></font></label>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Leave Days: <font color="indigo"><?php echo $row_rsLvDetails["days"]; ?> Working Days</font></label>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Leave start Date: <font color="indigo"><?php echo $row_rsLvDetails["startdate"]; ?></font></label>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Leave End Date: <font color="indigo"><?php echo $row_rsLvDetails["enddate"]; ?></font></label>
													</div>
												</div>
											<?php
											}
											?>
										</fieldset>
									</div>
								</fieldset>

								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">PROJECTS INVOLVED</legend>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<td width="3%"><strong>SN</strong></td>
														<td width="40%"><strong>Project Name</strong></td>
														<td width="15%"><strong>Project Status</strong></td>
														<td width="14%"><strong>Start Date</strong></td>
														<td width="14%"><strong>End Date</strong></td>
														<td width="14%"><strong>Role</strong></td>
													</tr>
												</thead>
												<tbody>
													<?php
													$nm = 0;
													while ($row_mbrprojs = $query_mbrprojs->fetch()) {
														$projid = $row_mbrprojs['projid'];

														$query_rsProjects =  $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
														$query_rsProjects->execute();
														$row_rsProjects = $query_rsProjects->fetch();
														$statusid = $row_rsProjects["projstatus"];

														$query_projroles =  $db->prepare("SELECT r.role FROM tbl_projmembers m inner join tbl_project_team_roles r on r.id=m.role WHERE responsible='$userid' and projid='$projid'");
														$query_projroles->execute();
														$row_projroles = $query_projroles->fetch();
														$role = $row_projroles["role"];

														if ($statusid == 0) {
															$projstatus = "Under Planning";
														} else {
															$query_projstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$statusid'");
															$query_projstatus->execute();
															$row_projstatus = $query_projstatus->fetch();
															$projstatus = $row_projstatus['statusname'];
														}

														$project = $row_rsProjects['projname'];
														$projstage = $row_rsProjects['projstage'];

														if ($projstage > 9) {
															$query_tenderdates = $db->prepare("SELECT startdate, enddate FROM tbl_tenderdetails WHERE projid='$projid'");
															$query_tenderdates->execute();
															$row_tender = $query_tenderdates->fetch();

															$prjsdate = $row_tender ?  $row_tender['startdate'] : "";
															$prjedate = $row_tender ?  $row_tender['enddate'] : "";
														} else {
															$prjsdate = $row_rsProjects['projstartdate'];
															$prjedate = $row_rsProjects['projenddate'];
														}

														$projsdate = date("d M Y", strtotime($prjsdate));
														$projedate = date("d M Y", strtotime($prjedate));

														if ($statusid == 4 || $statusid == 11 || $statusid == 3) {
															$nm++;
													?>
															<tr>
																<td><?php echo $nm; ?></td>
																<td><?php echo $project; ?></td>
																<td align="center"><?php echo $projstatus; ?></td>
																<td><?php echo $projsdate; ?></td>
																<td><?php echo $projedate; ?></td>
																<td><?php echo $role; ?></td>
															</tr>
													<?php
														}
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
								</fieldset>

							<?php
							}
							?>
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