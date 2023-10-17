<?php 
include_once 'includes/head-alt.php';
$crud_permissions = $role_group == 2 ? true : false;
try {

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if (isset($_POST["catid"])) {
		$catid = $_POST['catid'];
		$startdate = $_POST['startdate'];
		$comments = $_POST['comments'];
		$employeeid = $_POST['employee'];
		$reassigneeid = $_POST['reassignee'];
		$username = $_POST['username'];
		$leavedays = $_POST['leavedays'];
		$remainingleavedays = $_POST['remleavedays'];
		$current_date = date("Y-m-d");
		function add_work_days($stdate, $day)
		{
			if ($day == 0)
				return $stdate;
			$stdate->add(new DateInterval('P1D'));
			if (!in_array($stdate->format('N'), array('6', '7')))
				$day--;
			return add_work_days($stdate, $day);
		}
		$stdate  = add_work_days(new DateTime(), $leavedays);
		$leavenddate = $stdate->format('Y-m-d');
		$sdate = strtotime($startdate);
		$leavestartdate = date("d m Y", $sdate);

		if (!empty($leavedays)) {
			$insertSQL = $db->prepare("INSERT INTO tbl_employee_leave (employee, leavecategory, days, startdate, enddate, comments, added_by, date_added) VALUES (:ptid, :catid, :days, :startdate, :enddate, :comments, :user, :recorddate)");
			$insertSQL->execute(array(':ptid' => $employeeid, ':catid' => $catid, ':days' => $leavedays, ':startdate' => $startdate, ':enddate' => $leavenddate, ':comments' => $_POST['comments'],  ':user' => $username, ':recorddate' => $current_date));
			$last_id = $db->lastInsertId();

			if ($insertSQL->rowCount() == 1) {
				$availability = 0;
				$leave = 1;
				$queryupdate = $db->prepare("UPDATE tbl_projteam2 SET availability=:availability WHERE ptid=:ptid");
				$queryupdate->execute(array(":availability" => $availability, ":ptid" => $employeeid));

				$query_rsProjLeaved =  $db->prepare("SELECT p.projid AS prjid FROM tbl_projects p inner join tbl_projmembers m on m.projid=p.projid WHERE m.ptid = '$employeeid' AND (p.projstatus <> 'Completed' OR p.projstatus <> 'Cancelled' OR p.projstatus <> 'On Hold')");
				$query_rsProjLeaved->execute();

				while ($row_rsProjLeaved = $query_rsProjLeaved->fetch()) {
					$LProjid = $row_rsProjLeaved["prjid"];
					$queryreassign = $db->prepare("UPDATE tbl_projmembers SET ptleave=:leave, reassignee=:reassignee, datereassigned=:rdate WHERE ptid=:ptid AND projid=:projid");
					$queryreassign->execute(array(":leave" => $leave, ":reassignee" => $reassigneeid, ":rdate" => $current_date, ":ptid" => $employeeid, ":projid" => $LProjid));
				}

				$query_rsLeavename =  $db->prepare("SELECT leavename FROM tbl_employees_leave_categories WHERE id = '$catid'");
				$query_rsLeavename->execute();
				$leavename = $query_rsLeavename->fetch();

				$filecategory = "Leave";
				$fileid = $last_id;
				$reason = $leavename["leavename"];

				$date_uploaded = date('Y-m-d');
				if (!empty($_FILES['leavefile']['name'])) {
					$filename = basename($_FILES['leavefile']['name']);
					$ext = substr($filename, strrpos($filename, '.') + 1);

					if (($ext != "exe") && ($_FILES["leavefile"]["type"] != "application/x-msdownload")) {
						$newname = $employeeid . "-" . $fileid . "-" . $filename;
						$filepath = "uploads/leave/" . $newname;

						if (!file_exists($filepath)) {
							if (move_uploaded_file($_FILES['leavefile']['tmp_name'], $filepath)) {
								$qry2 = $db->prepare("INSERT INTO tbl_files (`filename`, `projstage`, `fcategory`, `catid`, `ftype`, `reason`, `floc`, `uploaded_by`, `date_uploaded`) VALUES (:fname,:projstage, :filecategory, :catid, :ext, :reason, :floc, :myUser, :date_uploaded)");
								$qry2->execute(array(':fname' => $newname, ':projstage' => 0, ':filecategory' => $filecategory, ':catid' => $fileid, ':ext' => $ext, ':reason' => $reason, ':floc' => $filepath, ':myUser' => $username, ":date_uploaded" => $date_uploaded));
							}
						} else {
							$msg = 'File you are uploading already exists, try another file!!';
							$results = "<script type=\"text/javascript\">
									swal({
										title: \"Error!\",
										text: \" $msg \",
										type: 'Danger',
										timer: 3000,
                                        'icon':'warning',
										showConfirmButton: false });
								</script>";
						}
					} else {
						$msg = 'This file type is not allowed, try another file!!';
						$results = "<script type=\"text/javascript\">
								swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 3000,
                                    'icon':'warning',
									showConfirmButton: false });
								</script>";
					}
				} else {
					$msg = 'You have not attached any file!!';
					$results = "<script type=\"text/javascript\">
							swal({
								title: \"Error!\",
								text: \" $msg \",
								type: 'Danger',
								timer: 3000,
                                'icon':'warning',
								showConfirmButton: false });
						</script>";
				}


				$msg = 'Record successfully updated.';
				$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
                            'icon':'success',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'view-project-team-info.php?staff=$employeeid';
						}, 2000);
					</script>";
			}
		} else {
			echo "empty leave days ";
		}
	}

	if (isset($_SESSION['MM_Username'])) {
		$user = $_SESSION['MM_Username'];
	}

	if (isset($_GET['staff'])) {
		$ptid = $_GET['staff'];
	}

	$query_rsStaff =  $db->prepare("SELECT t.*, t.designation AS design, d.designation AS desgn FROM tbl_projteam2 t inner join tbl_pmdesignation d ON t.designation=d.moid WHERE t.ptid = '$ptid'");
	$query_rsStaff->execute();
	$row_rsStaff = $query_rsStaff->fetch();


	$mydesign = $row_rsStaff['design'];
	$myministry = $row_rsStaff['ministry'];
	$mydept = $row_rsStaff['department'];

	if ($row_rsStaff["availability"] == 1) {
		$emplststus = "<font color='indigo'>Available</font>";
	} else {
		$emplststus = "<font color='deep-orange'>Unavailable</font>";
	}

	$query_rsLeave =  $db->prepare("SELECT id, leavename FROM tbl_employees_leave_categories ORDER BY id ASC");
	$query_rsLeave->execute();
	$row_rsLeave = $query_rsLeave->fetch();


	$query_mbrprojs =  $db->prepare("SELECT projid FROM tbl_projmembers WHERE ptid='$ptid' GROUP BY projid");
	$query_mbrprojs->execute();
	$count_mbrprojs = $query_mbrprojs->rowCount();

	$query_rsOtherEMployees =  $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid<>'$ptid'  AND ministry='$myministry' AND availability=1");
	$query_rsOtherEMployees->execute();
	$row_rsOtherEMployees = $query_rsOtherEMployees->fetch();
} catch (PDOException $ex) {
	$results = flashMessage("An error occurred: " . $ex->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Projtrac M&E - Add Task</title>
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<!--CUSTOM MAIN STYLES-->
	<link href="css/custom.css" rel="stylesheet" />

	<!-- Bootstrap Core Css -->
	<link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Waves Effect Css -->
	<link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

	<!-- Animation Css -->
	<link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

	<!--WaitMe Css-->
	<link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

	<!-- Multi Select Css -->
	<link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

	<!-- Bootstrap Spinner Css -->
	<link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

	<!-- Bootstrap Tagsinput Css -->
	<link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

	<!-- Bootstrap Select Css -->
	<link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

	<!-- JQuery DataTable Css -->
	<link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<!-- Sweet Alert Css -->
	<link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

	<!-- Custom Css -->
	<link href="projtrac-dashboard/css/style.css" rel="stylesheet">

	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	<link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#leave').on('change', function(event) {
				var stleave = $("#leave").val();
				var staffid = $("#ptid").val();
				$.ajax({
					type: 'post',
					url: 'staffleaverequest',
					data: {
						leaveid: stleave,
						ptid: staffid
					},
					success: function(data) {
						$('#leaveformcontent').html(data);
						$("#staffleaveModal").modal({
							backdrop: "static"
						});
					}
				});
			});
		});
	</script>
</head>

<body class="theme-blue">
	<!-- Page Loader --
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	<!-- #END# Overlay For Sidebars -->
	<!-- Top Bar -->
	<nav class="navbar" style="height:69px; padding-top:-10px">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
				<a href="javascript:void(0);" class="bars"></a>
				<img src="images/logo.png" alt="logo" width="239" height="39">
			</div>
		</div>
	</nav>
	<!-- #Top Bar -->
	<section>
		<!-- Left Sidebar -->
		<aside id="leftsidebar" class="sidebar">
			<!-- User Info -->
			<div class="user-info">
				<div class="image">
					<img src="images/user.png" width="48" height="48" alt="User" />
				</div>
				<?php
				include_once("includes/user-info.php");
				?>
			</div>
			<!-- #User Info -->
			<!-- Menu -->
			<?php
			include_once("includes/sidebar.php");
			?>
			<!-- #Menu -->
			<!-- Footer -->
			<div class="legal">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
					ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System.
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
					Copyright @ 2017 - 2019. ProjTrac Systems Ltd.
				</div>
			</div>
			<!-- #Footer -->
		</aside>
		<!-- #END# Left Sidebar -->
	</section>


	<!-- Leave modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="staffleaveModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-blue">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center"><i class="glyphicon glyphicon-edit"></i> Leave Authorization</h4>
				</div>
				<form class="leaveForm" action="managemember" method="post" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="leaveformcontent"></div>
											<div class="col-md-6">
												<label>Leave Days</label>
												<div class="form-line">
													<input name="leavedays" type="number" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
												</div>
											</div>
											<div class="col-md-6">
												<label>Leave Start Date</label>
												<div class="form-line">
													<input name="startdate" type="date" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
												</div>
											</div>
											<!--<div class="col-md-4">
												<label>Leave End Date</label>
												<div class="form-line">
													<input name="enddate" type="date" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px">
												</div>
											</div>-->
											<div class="col-md-6">
												<label>Reassign Projects</label>
												<div class="form-line">
													<select name="reassignee" id="reassignee" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
														<option value="">... Select a Member ...</option>
														<?php
														do {
														?>
															<option value="<?php echo $row_rsOtherEMployees['ptid'] ?>"><?php echo $row_rsOtherEMployees['title'] . ". " . $row_rsOtherEMployees['fullname'] ?></option>
														<?php
														} while ($row_rsOtherEMployees = $query_rsOtherEMployees->fetch());
														?>
													</select>
												</div>
											</div>
											<div class="col-md-12">
												<label>Comments:</label>
												<div class="form-line">
													<textarea name="comments" id="comments" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:99.5%"></textarea>
												</div>
											</div>
											<div class="col-md-6">
												<label>Attachments:</label>
												<div class="form-line">
													<input type="file" name="leavefile" id="leavefile" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/modal-body-->
					<div class="modal-footer">
						<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" data-loading-text="Loading...">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
						<input type="hidden" name="username" id="username" value="<?php echo $user; ?>" />
						<input type="hidden" name="employee" id="employee" value="<?php echo $ptid; ?>" />
					</div>
				</form>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- /edit Leave modal-->


	<!-- Inner Section -->
	<?php include_once('managemember-inner.php'); ?>
	<!-- #END# Inner Section -->

	<!-- Jquery Core Js -->
	<script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

	<!-- Bootstrap Core Js -->
	<script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Multi Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

	<!-- Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

	<!-- Slimscroll Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- Waves Effect Plugin Js -->
	<script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

	<!-- Input Mask Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

	<!-- Sweet Alert Plugin Js -->
	<script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

	<!-- Jquery Spinner Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>

	<!-- Bootstrap Tags Input Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

	<!-- Autosize Plugin Js -->
	<script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

	<!-- Moment Plugin Js -->
	<script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

	<!-- Bootstrap Material Datetime Picker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

	<!-- Bootstrap Datepicker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>


	<script>
		$(document).ready(function() {
			$('#team_projects').DataTable();
		});
	</script>

</body>

</html>