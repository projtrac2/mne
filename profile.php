<?php
$page = "view";
require('includes/head.php');
include_once("Models/User.php");
include_once("Models/Email.php");
$user = new User();

if (isset($_POST["availability"]) && $_POST["availability"] == 0) {
	$reason = $_POST['reason'];
	$duration = $_POST['duration'];
	$roleowner = $user_name;
	$role = $_POST['role'];
	$current_date = date("Y-m-d");

	for ($i = 0; $i < count($_POST['assignee']); $i++) {

		$projid = $_POST['projid'][$i];
		$assignee = $_POST['assignee'][$i];

		if ($duration > 0 && ($assignee != "" || !empty($assignee))) {
			$insertSQL = $db->prepare("INSERT INTO tbl_project_team_member_unavailability (projid, role, role_owner, reason, duration, role_trasferred_to, date_recorded) VALUES (:projid, :role, :roleowner, :reason, :duration, :assignee, :recorddate)");
			$insertSQL->execute(array(':projid' => $projid, ':role' => $role, ':roleowner' => $roleowner, ':reason' => $reason, ':duration' => $duration, ':assignee' => $assignee, ':recorddate' => $current_date));

			$queryreassign = $db->prepare("UPDATE tbl_projmembers SET role_transferred=1, date_role_transferred=:rdate WHERE ptid=:ptid AND projid=:projid");
			$queryreassign->execute(array(":rdate" => $current_date, ":ptid" => $roleowner, ":projid" => $projid));
		}
	}

	if ($duration > 0) {
		$msg = 'Role successfully transferred!';
		$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					'icon':'success',
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'profile';
				}, 2000);
			</script>";
	} else {
		$msg = 'Stand in duration should be creater than zero!!';
		$results = "<script type=\"text/javascript\">
				swal({
					title: \"Warning!\",
					text: \" $msg\",
					type: 'Warning',
					timer: 2000,
					'icon':'warning',
					showConfirmButton: false });
				}, 2000);
			</script>";
	}
} elseif (isset($_POST["availability"]) && $_POST["availability"] == 1) {
	$transfer = $_POST['transfer'];
	$roleowner = $user_name;
	$role = $_POST['role'];
	$current_date = date("Y-m-d");

	if ($transfer == 1) {
		$insertSQL = $db->prepare("UPDATE tbl_project_team_member_unavailability SET status=0 WHERE role_owner=:roleowner AND status=1");
		$insertSQL->execute(array(":roleowner" => $roleowner));

		$queryreassign = $db->prepare("UPDATE tbl_projmembers SET role_transferred=0 WHERE ptid=:ptid AND role_transferred=1");
		$queryreassign->execute(array(":ptid" => $roleowner));

		$msg = 'Projects successfully transferred back!';
		$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					'icon':'success',
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'profile';
				}, 2000);
			</script>";
	} else {
		$msg = 'No change made!!';
		$results = "<script type=\"text/javascript\">
				swal({
					title: \"Warning!\",
					text: \" $msg\",
					type: 'Warning',
					timer: 2000,
					'icon':'warning',
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'profile';
				}, 2000);
			</script>";
	}
}

function alert_message($title, $msg, $icon, $url)
{
	return "<script type=\"text/javascript\">
                swal({
                    title: '$title',
                    text: '$msg',
                    type: '$title',
                    timer: 5000,
                    icon:'$icon',
                    showConfirmButton: false
                });
                setTimeout(function(){
                    window.location.href = '$url';
                }, 3000);
            </script>";
}


if (isset($_POST['change_profile']) && !empty($_POST['change_profile'])) {
	$updateSQL = $db->prepare("UPDATE tbl_projteam2 SET   WHERE indid=:indid");
	$result = $updateSQL->execute(array(':user_id'));
}

if (isset($_POST['change_password']) && !empty($_POST['change_password'])) {
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];

	if ($confirm_password == $new_password) {
		$auth = new Auth();
		$user_details = $user->get_user($user_name);
		$hashed_password = $user_details->password;
		if (password_verify($old_password, $hashed_password)) {
			$change_pass = $auth->change_password($user_name, $new_password);
			if ($change_pass) {
				$results = alert_message("Success", "Successfully changed password", "success", "logout.php");
			} else {
				$results = alert_message("Error", "Password could not be verified", "error", "profile.php");
			}
		} else {
			$results = alert_message("Error", "Error check your credentials passwords do not match", "error", "profile.php");
		}
	} else {
		$results = alert_message("Error", "Error check your credentials 3", "error", "profile.php");
	}
}


// get department children
function get_sector($dept)
{
	global $db;
	$query_rsdepartment = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE stid='$dept' AND deleted='0'");
	$query_rsdepartment->execute();
	$row_rsdepartment = $query_rsdepartment->fetch();
	$totalRows_rsdepartment = $query_rsdepartment->rowCount();

	if ($totalRows_rsdepartment > 0) {
		return $row_rsdepartment;
	} else {
		return false;
	}
}

$user_details = $user->get_user($user_name);
$sector_id = $user_details->ministry;
$department_id = $user_details->department;
$directorate_id = $user_details->directorate;



$ministry_details = get_sector($sector_id);
$ministry = $ministry_details ?  $ministry_details['sector'] : "N/A";

$department_details = get_sector($department_id);
$department = $department_details ? $department_details['sector'] : "N/A";

$directorate_data = get_sector($directorate_id);

$directorate = $directorate_data != "" ? $directorate_data['sector'] : "N/A";

$query_rsDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation WHERE position = '$designation'");
$query_rsDesignation->execute();
$row_rsDesignation = $query_rsDesignation->fetch();
$totalRows_rsDesignation = $query_rsDesignation->rowCount();

if ($designation  >= 5) {
	if ($designation == 5) {
		$query_project_status =  $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage > 0 AND g.projsector=$sector_id");
	} elseif ($designation == 6) {
		$query_project_status =  $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage > 0 AND g.projsector=$sector_id AND g.projdept=$department_id");
	} elseif ($designation == 7) {
		$query_project_status =  $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage > 0 AND g.projsector=$sector_id AND g.projdept=$department_id AND g.directorate=$directorate_id");
	} elseif ($designation > 7) {
		$query_project_status =  $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_projmembers m on m.projid=p.projid WHERE p.projstage > 0 AND g.projsector=$sector_id AND g.projdept=$department_id AND g.directorate=$directorate_id AND m.responsible=$user_name");
	}

	$query_project_status->execute();
	$totalrows_project_status = $query_project_status->rowCount();

	$awaitingprocurement = 0;
	$pending = 0;
	$ontrack = 0;
	$behindschedule = 0;
	$onhold = 0;
	$completed = 0;

	if ($totalrows_project_status > 0) {
		while ($rows_project_status = $query_project_status->fetch()) {
			$status = $rows_project_status["projstatus"];
			$stage = $rows_project_status["projstage"];
			if ($status == 0 && $stage < 6) {
				$awaitingprocurement++;
			} elseif ($status == 0 && ($stage > 5 && $stage < 10)) {
				$pending++;
			} elseif ($status == 4 && $stage == 10) {
				$ontrack++;
			} elseif ($status == 11 && $stage == 10) {
				$behindschedule++;
			} elseif ($status == 5 && ($stage == 10 || $stage == 11)) {
				$completed++;
			} elseif ($status == 6 && $stage == 10) {
				$onhold++;
			}
		}
	}
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
						<div class="row clearfix">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="profile-card">
									<div class="profile-header">&nbsp;</div>
									<div class="profile-body">
										<div class="image-area">
											<img src="<?= $avatar ?>" width="50px" height="50px" alt=" Profile Image" />
										</div>
										<div class="content-area">
											<h3><?= $user_details->fullname ?></h3>
											<p><?= $row_rsDesignation['designation'] ?></p>
										</div>
									</div>
									<?php
									if ($designation  >= 5) {
									?>
										<div class="profile-footer">
											<ul>
												<li>
													<span>Projects Awaiting Procurement</span>
													<span><?= $awaitingprocurement ?></span>
												</li>
												<li>
													<span>Projects Pending Implementation</span>
													<span><?= $pending ?></span>
												</li>
												<li>
													<span>Projects On Track</span>
													<span><?= $ontrack ?></span>
												</li>
												<li>
													<span>Projects Behind schedule</span>
													<span><?= $behindschedule ?></span>
												</li>
												<li>
													<span>Projects On-Hold</span>
													<span><?= $onhold ?></span>
												</li>
												<li>
													<span>Completed Projects</span>
													<span><?= $completed ?></span>
												</li>
											</ul>
										</div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active">
										<a href="#home" aria-controls="home" role="tab" data-toggle="tab">About me </a>
									</li>
									<li role="presentation">
										<a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Profile Settings</a>
									</li>
									<li role="presentation">
										<a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Change Password</a>
									</li>
									<?php
									if ($designation  >= 5) {
									?>
										<li role="presentation">
											<a href="#availability" aria-controls="settings" role="tab" data-toggle="tab">Unavailability</a>
										</li>
										<li role="presentation">
											<a href="#standin" aria-controls="settings" role="tab" data-toggle="tab">Stand-In Projects</a>
										</li>
									<?php
									}
									?>
								</ul>
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane fade in active" id="home">
										<div class="profile-card">
											<div class="profile-footer">
												<ul>
													<li>
														<div class="title">
															<i class="material-icons">library_books</i>
															Department
														</div>
														<div class="content">
															<?= $ministry ?>
														</div>
													</li>
													<li>
														<div class="title">
															<i class="material-icons">library_books</i>
															Section
														</div>
														<div class="content">
															<?= $department ?>
														</div>
													</li>
													<li>
														<div class="title">
															<i class="material-icons">library_books</i>
															Directorate
														</div>
														<div class="content">
															<?= $directorate ?>
														</div>
													</li>
													<li>
														<div class="title">
															<i class="material-icons">library_books</i>
															Email
														</div>
														<div class="content">
															<?= $user_details->email ?>
														</div>
													</li>
													<li>
														<div class="title">
															<i class="material-icons">library_books</i>
															Phone number
														</div>
														<div class="content">
															<?= $user_details->phone ?>
														</div>
													</li>
												</ul>
											</div>
											<div class="panel-footer">

											</div>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane fade in" id="profile_settings">
										<form class="form-horizontal">
											<?= csrf_token_html(); ?>
											<div class="form-group">
												<label for="NameSurname" class="col-sm-2 control-label">Name Surname</label>
												<div class="col-sm-10">
													<div class="form-line">
														<input type="text" class="form-control" id="username" name="username" placeholder="Name Surname" value="<?= $user_details->phone ?>" required>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="Email" class="col-sm-2 control-label">Email</label>
												<div class="col-sm-10">
													<div class="form-line">
														<input type="email" class="form-control" id="Email" name="Email" placeholder="Email" value="<?= $user_details->email ?>" required>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-2 col-sm-10">
													<button type="submit" class="btn btn-danger">SUBMIT</button>
												</div>
											</div>
										</form>
									</div>
									<div role="tabpanel" class="tab-pane fade in" id="change_password_settings">
										<form class="form-horizontal" action="" method="post">
											<div class="form-group">
												<label for="OldPassword" class="col-sm-3 control-label">Old Password</label>
												<div class="col-sm-9">
													<div class="form-line">
														<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" required>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="NewPassword" class="col-sm-3 control-label">New Password</label>
												<div class="col-sm-9">
													<div class="form-line">
														<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="NewPasswordConfirm" class="col-sm-3 control-label">New Password (Confirm)</label>
												<div class="col-sm-9">
													<div class="form-line">
														<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="New Password (Confirm)" required>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9">
													<input type="hidden" name="change_password" value="change_password">
													<button type="submit" class="btn btn-danger">SUBMIT</button>
												</div>
											</div>
										</form>
									</div>
									<?php
									if ($designation  >= 5) {
									?>
										<div role="tabpanel" class="tab-pane fade in" id="availability">
											<form class="form-horizontal" action="" method="post">
												<?php
												$query_assigned_projects = $db->prepare("SELECT * FROM tbl_project_team_member_unavailability WHERE role_owner='$user_name' AND status=1");
												$query_assigned_projects->execute();
												$count_rows = $query_assigned_projects->rowCount();

												if ($count_rows == 0) {
												?>
													<div class="form-group">
														<label for="reason" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label">Unavailability Reason</label>
														<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="reason">
															<div class="form-line">
																<input type="text" class="form-control" name="reason" placeholder="Explain the reason your are unavailability" required>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="duration" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label">Days Unavailable</label>
														<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="duration">
															<div class="form-line">
																<input type="number" class="form-control" name="duration" placeholder="Indicate the number of days unavailable" required>
															</div>
														</div>
													</div>

													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">REASSIGN YOUR PROJECTS</legend>
														<div class="row clearfix">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
																<table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
																	<thead>
																		<tr id="colrow">
																			<td width="4%"><strong>SN</strong></td>
																			<td width="46%"><strong>Project Name</strong></td>
																			<td width="15%"><strong>Status</strong></td>
																			<td width="15%"><strong>Role</strong></td>
																			<td width="20%"><strong>Reassign</strong></td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$query_mbrprojs =  $db->prepare("SELECT projid FROM tbl_projmembers WHERE responsible='$user_name' GROUP BY projid");
																		$query_mbrprojs->execute();
																		$count_mbrprojs = $query_mbrprojs->rowCount();

																		$nm = 0;
																		while ($row_mbrprojs = $query_mbrprojs->fetch()) {
																			$projid = $row_mbrprojs['projid'];

																			$query_rsProjects =  $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
																			$query_rsProjects->execute();
																			$row_rsProjects = $query_rsProjects->fetch();
																			$statusid = $row_rsProjects["projstatus"];
																			$project = $row_rsProjects['projname'];
																			$projstage = $row_rsProjects['projstage'];

																			$query_projroles =  $db->prepare("SELECT r.id, r.rank, r.role FROM tbl_projmembers m inner join tbl_project_team_roles r on r.id=m.role WHERE responsible='$user_name' and projid='$projid'");
																			$query_projroles->execute();
																			$row_projroles = $query_projroles->fetch();
																			$role = $row_projroles["role"];
																			$rank = $row_projroles["rank"];

																			$query_team_members =  $db->prepare("SELECT * FROM tbl_projmembers m inner join users u on u.userid=m.responsible inner join tbl_projteam2 t on t.ptid=u.pt_id WHERE m.responsible != '$user_name' and projid='$projid'");
																			$query_team_members->execute();

																			if ($projstage < 6) {
																				$projstatus = "Awaiting Procurement";
																			} elseif ($projstage > 5 && $projstage < 10) {
																				$projstatus = "Pending";
																			} elseif ($projstage == 10 || $projstage == 11) {
																				$query_projstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$statusid'");
																				$query_projstatus->execute();
																				$row_projstatus = $query_projstatus->fetch();
																				$projstatus = $row_projstatus['statusname'];
																			}

																			if ($rank == 2 && ($statusid == 4 || $statusid == 11 || $statusid == 3)) {
																				$nm++;
																		?>
																				<tr>
																					<td width="4%"><?php echo $nm; ?><input type="hidden" name="projid[]" value="<?= $projid ?>"></td>
																					<td width="46%"><?php echo $project; ?></td>
																					<td width="15%"><?php echo $projstatus; ?></td>
																					<td width="15%"><?php echo $role; ?></td>
																					<td width="20%">
																						<select name="assignee[]" class="form-control">
																							<option value="" selected>Select Member</option>
																							<?php
																							while ($row_team_members = $query_team_members->fetch()) {
																								$userid = $row_team_members["userid"];
																								$fullname = $row_team_members["fullname"];
																							?>
																								<option value="<?= $userid ?>"><?= $fullname ?></option>
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
															<input type="hidden" name="availability" value="0">
															<button type="submit" class="btn btn-primary">SUBMIT</button>
														</div>
													</div>
												<?php
												} else {
													$query_unique_assigned_project = $db->prepare("SELECT * FROM tbl_project_team_member_unavailability WHERE role_owner='$user_name' AND status=1 LIMIT 1");
													$query_unique_assigned_project->execute();
													$row_unique_assigned_project = $query_unique_assigned_project->fetch();
													$reason = $row_unique_assigned_project["reason"];
													$duration = $row_unique_assigned_project["duration"];

												?>
													<div class="form-group">
														<label for="reason" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label">Unavailability Reason</label>
														<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="reason">
															<div class="form-line">
																<div class="form-control"><?= $reason ?></div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="duration" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label">Days Unavailable</label>
														<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="duration">
															<div class="form-line">
																<div class="form-control"><?= $duration ?> Days</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="duration" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 control-label">Transfer back all your Projects</label>
														<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="duration">
															<div class="form-line">
																<select name="transfer" class="form-control" required="required">
																	<option value="" selected>Select Member</option>
																	<option value="0">No</option>
																	<option value="1">Yes</option>
																</select>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
															<input type="hidden" name="role" value="<?= $rank ?>">
															<input type="hidden" name="availability" value="1">
															<button type="submit" class="btn btn-success">SUBMIT</button>
														</div>
													</div>
												<?php
												}
												?>
											</form>
										</div>

										<!-- =============== STAND IN PROJECTS =============== -->
										<div role="tabpanel" class="tab-pane fade in" id="standin">
											<?php
											$query_standin_projects = $db->prepare("SELECT * FROM tbl_project_team_member_unavailability a inner join tbl_projects p on p.projid=a.projid WHERE role_trasferred_to='$user_name' AND status=1");
											$query_standin_projects->execute();
											$count_standin_rows = $query_standin_projects->rowCount();

											if ($count_standin_rows > 0) {
											?>
												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">PROJECTS</legend>
													<div class="row clearfix">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
															<table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
																<thead>
																	<tr id="colrow">
																		<td width="4%"><strong>SN</strong></td>
																		<td width="46%"><strong>Project Name</strong></td>
																		<td width="15%"><strong>Status</strong></td>
																		<td width="20%"><strong>Owner</strong></td>
																		<td width="15%"><strong>Stand-In Days</strong></td>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$nm = 0;
																	while ($row_standin_projects = $query_standin_projects->fetch()) {
																		$statusid = $row_standin_projects["projstatus"];
																		$project = $row_standin_projects['projname'];
																		$projownerin = $row_standin_projects['role_owner'];
																		$standin_duration = $row_standin_projects['duration'];

																		$query_proj_owner =  $db->prepare("SELECT l.title AS title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles l on l.id=t.title WHERE userid='$projownerin'");
																		$query_proj_owner->execute();
																		$row_proj_owner = $query_proj_owner->fetch();
																		$projowner = $row_proj_owner["title"] . "." . $row_proj_owner["fullname"];

																		$query_proj_status =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$statusid'");
																		$query_proj_status->execute();
																		$row_proj_status = $query_proj_status->fetch();
																		$projstatus = $row_proj_status["statusname"];

																		$nm++;
																	?>
																		<tr>
																			<td width="4%"><?php echo $nm; ?></td>
																			<td width="46%"><?php echo $project; ?></td>
																			<td width="15%"><?php echo $projstatus; ?></td>
																			<td width="20%"><?php echo $projowner; ?></td>
																			<td width="15%"><?php echo $standin_duration . " Days"; ?></td>
																		</tr>
																	<?php
																	}
																	?>
																</tbody>
															</table>
														</div>
													</div>
													<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
												</fieldset>
											<?php
											} else {
											?>

												<fieldset class="scheduler-border">
													<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">PROJECTS</legend>
													<div class="row clearfix">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
															<h4 class="text-danger">No data found!!</h4>
														</div>
													</div>
													<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
												</fieldset>
											<?php
											}
											?>
										</div>
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>
<!-- end body  -->
<?php
require('includes/footer.php');
?>