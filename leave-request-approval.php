<?php
require('functions/strategicplan.php');
require('includes/head.php');
if ($permission) {
	try {
		if (isset($_GET["staff"]) && !empty($_GET["staff"])) {
			$encoded_userid = $_GET["staff"];
			$decode_userid = base64_decode($encoded_userid);
			$userid_array = explode("mbrleave", $decode_userid);
			$userid = $userid_array[1];
		}

		$current_date = date("Y-m-d");

		if (isset($_POST["action"])) {
			$action = $_POST["action"];
			$comments = $_POST["comments"];
			$requestid = $_POST["requestid"];
			$roleowner = $_POST["roleowner"];
			$msg = '';

			if ($action == 1) {
				$query_update = $db->prepare("UPDATE tbl_employee_leave SET status=:status, comments=:comments WHERE id=:requestid");
				$query_update->execute(array(":status" => $action, ":comments" => $comments, ":requestid" => $requestid));

				$msg = 'The request successfully rejected!!';
			} else {
				$query_update = $db->prepare("UPDATE tbl_employee_leave SET status=:status, comments=:comments, authorized_by=:user, authorized_on=:currentdate WHERE id=:requestid");
				$actionupdate = $query_update->execute(array(":status" => $action, ":comments" => $comments, ":user" => $user_name, ":currentdate" => $current_date, ":requestid" => $requestid));

				if ($actionupdate) {
					for ($i = 0; $i < count($_POST['assignee']); $i++) {

						$projid = $_POST['projid'][$i];
						$assignee = $_POST['assignee'][$i];

						$queryreassign = $db->prepare("UPDATE tbl_projmembers SET ptleave=1, reassignee=:assignee, datereassigned=:date WHERE ptid=:ptid AND projid=:projid");
						$queryreassign->execute(array(":assignee" => $assignee, ":date" => $current_date, ":ptid" => $roleowner, ":projid" => $projid));
					}

					$query_team_id =  $db->prepare("SELECT pt_id FROM users WHERE userid='$roleowner'");
					$query_team_id->execute();
					$row_team_id = $query_team_id->fetch();
					$pt_id = $row_team_id["pt_id"];

					$update_availability = $db->prepare("UPDATE tbl_projteam2 SET availability=0 WHERE ptid=:ptid");
					$availability = $update_availability->execute(array(":ptid" => $pt_id));
					if ($availability) {
						$msg = 'The request successfully approved';
					}
				}
			}
			$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					'icon':'success',
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'leave-requests';
				}, 2000);
			</script>";
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
		}

		if ($row_rsStaff["availability"] == 1) {
			$emplststus =  "<font color='indigo'>Available</font>";
		} else {
			$emplststus =  "<font color='deep-orange'>Unavailable</font>";
		}

		$query_mbrprojs =  $db->prepare("SELECT projid FROM tbl_projmembers WHERE ptid='$userid' GROUP BY projid");
		$query_mbrprojs->execute();

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

		$query_rsOtherEMployees =  $db->prepare("SELECT * FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid<>'$ptid' AND availability=1 AND disabled=0" . $level);
		$query_rsOtherEMployees->execute();
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
							<fieldset class="scheduler-border" style="font-size:15px">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
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
									<strong>Position</strong>
									<font color="indigo"><?php echo $row_rsStaff["desgn"] . " " . $deptlebel; ?></font>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<strong>Current Status:</strong>
									<font color="indigo"><?php echo $emplststus; ?></font>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<strong>Phone Number:</strong>
									<font color="indigo"><?php echo $row_rsStaff['phone'] ?></font>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<strong>Email Address:</strong>
									<font color="indigo"><?php echo $row_rsStaff['email'] ?></font>
								</div>
								<hr style="border: 2px solid red">
								<input name="ptid" type="hidden" id="ptid" value="<?php echo $ptid; ?>" />
								<?php
								$query_rsLvDetails =  $db->prepare("SELECT C.leavename, L.id, L.days, L.startdate, L.enddate, L.comments, L.status FROM tbl_employee_leave L INNER JOIN tbl_employees_leave_categories C ON L.leavecategory=C.id WHERE L.employee = '$userid' ORDER BY L.id DESC LIMIT 1");
								$query_rsLvDetails->execute();
								$row_rsLvDetails = $query_rsLvDetails->fetch();
								$leavestatusid = $row_rsLvDetails["status"];
								$requestid = $row_rsLvDetails["id"];
								?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><u style="color:red">
										<h4>Leave Details</h4>
									</u></div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<label>Leave Type: <font color="indigo"><?php echo $row_rsLvDetails["leavename"]; ?></font></label>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<label>Leave Days: <font color="indigo"><?php echo $row_rsLvDetails["days"]; ?> Working Days</font></label>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<label>Leave Start Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["startdate"])); ?></font></label>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<label>Leave End Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["enddate"])); ?></font></label>
								</div>
								<?php
								if ($leavestatusid == 1 || $leavestatusid == 2) {
								?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Comments:</label>
										<div class="form-line">
											<textarea cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:99.5%" readonly><?php echo $row_rsLvDetails["comments"]; ?></textarea>
										</div>
									</div>
								<?php
								} else {
								?>
									<form class="form-horizontal" action="" method="post">
										<?= csrf_token_html(); ?>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<label>Request Action:</label>
											<div class="form-line">
												<select name="action" id="action" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
													<option value="" select>... Select Action ...</option>
													<option value="1">Reject the request</option>
													<option value="2">Approve the request</option>
												</select>
											</div>
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Comments:</label>
											<div class="form-line">
												<textarea name="comments" id="comments" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:99.5%"></textarea>
											</div>
										</div>
										<fieldset class="scheduler-border" id="reassign">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">REASSIGN PROJECTS</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr id="colrow">
																<td width="3%"><strong>SN</strong></td>
																<td width="40%"><strong>Project Name</strong></td>
																<td width="13%"><strong>Project Status</strong></td>
																<td width="12%"><strong>Start Date</strong></td>
																<td width="12%"><strong>End Date</strong></td>
																<td width="12%"><strong>Role</strong></td>
																<td width="8%"><strong>Action</strong></td>
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

																$query_projroles =  $db->prepare("SELECT r.id, r.rank, r.role FROM tbl_projmembers m inner join tbl_project_team_roles r on r.id=m.role WHERE ptid='$userid' and projid='$projid'");
																$query_projroles->execute();
																$row_projroles = $query_projroles->fetch();
																$role = $row_projroles["role"];
																$rank = $row_projroles["rank"];

																$query_team_members =  $db->prepare("SELECT * FROM tbl_projmembers m inner join users u on u.userid=m.ptid inner join tbl_projteam2 t on t.ptid=u.pt_id WHERE m.ptid != '$userid' and projid='$projid'");
																$query_team_members->execute();

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

																$query_projresp = $db->prepare("SELECT T.title, T.fullname FROM tbl_projteam2 T INNER JOIN tbl_projmembers M ON T.ptid=M.reassignee WHERE M.projid='$projid' AND M.ptleave=1");
																$query_projresp->execute();
																$row_projresp = $query_projresp->fetch();
																$count_row_projresp = $query_projresp->rowCount();

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

																if ($rank == 2 && ($statusid == 4 || $statusid == 11 || $statusid == 3)) {
																	$nm++;
															?>
																	<tr>
																		<td><?php echo $nm; ?><input type="hidden" name="projid[]" value="<?= $projid ?>"></td>
																		<td><?php echo $project; ?></td>
																		<td align="center"><?php echo $projstatus; ?></td>
																		<td><?php echo $projsdate; ?></td>
																		<td><?php echo $projedate; ?></td>
																		<td><?php echo $role; ?></td>
																		<td width="20%">
																			<select name="assignee[]" class="form-control assignee">
																				<option value="" selected>Select Member</option>
																				<?php
																				while ($row_team_members = $query_team_members->fetch()) {
																					$newmbrid = $row_team_members["userid"];
																					$fullname = $row_team_members["fullname"];
																				?>
																					<option value="<?= $newmbrid ?>"><?= $fullname ?></option>
																				<?php
																				}
																				?>
																			</select>
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
											<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
										</fieldset>
										<div class="form-group">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
												<input type="hidden" name="role" value="<?= $rank ?>">
												<input type="hidden" name="requestid" value="<?= $requestid ?>">
												<input type="hidden" name="roleowner" value="<?= $userid ?>">
												<input type="hidden" name="availability" value="1">
												<button type="submit" class="btn btn-success">SUBMIT</button>
											</div>
										</div>
									</form>
								<?php
								}
								?>
							</fieldset>
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

<script type="text/javascript">
	$(document).ready(function() {
		$("#reassign").hide();
		$('#action').on('change', function(event) {
			var action = $("#action").val();
			var staffid = $("#ptid").val();
			if (action == 2) {
				$("#reassign").show();
				$(".assignee").attr("required", "required");
			} else if (action == 1) {
				$("#reassign").hide();
				$(".assignee").removeAttr("required");
			}
		});
	});
</script>