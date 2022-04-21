<?php 
$Id = 13;
$subId = 2;


function sendMail($fullname, $email, $password){
	global $db;
	$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
	$query_url->execute();
	$row_url = $query_url->fetch();
	$url = $row_url["main_url"];
	$org = $row_url["company_name"];
	$org_email = $row_url["email_address"];


	$detailslink = '<a href="' . $url . 'login.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Login</a>';
	$mainmessage = ' Dear ' . $fullname . ',
		<p>Use the following details to login in to the system:</p>
		<p>Email:' . $email . '<br>
			Password: ' . $password . '<br> 
		<p>Prepare the required resources. </p>';

	$subject = "Login";
	$receipientName = $fullname;

	include("baseline-email-body.php");
	require 'PHPMailer/PHPMailerAutoload.php';
	include("email-conf-settings.php");
}

sendMail("Evans Koech", "biwottech@gmail.com", "password");

return;

try {
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	// email conif-settings 
	
	if (isset($_GET["ptid"]) && !empty($_GET["ptid"])) {
		$ptid = $_GET["ptid"];
		if (isset($_GET["action"]) && $_GET["action"] == '2') {
			$query_rsAdmin = $db->prepare("SELECT * FROM tbl_projcountyadmin WHERE ptid='$ptid'");
			$query_rsAdmin->execute();
			$row_rsAdmin = $query_rsAdmin->fetch();
		} elseif (isset($_GET["action"]) && $_GET["action"] == '3') {
			$delete_rsAdmin = $db->prepare("DELETE FROM tbl_projcountyadmin WHERE ptid='$ptid'");
			$delete_rsAdmin->execute();
		}
	}

	function create_password($str_length)
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $str_length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}

	$userid = "";
	if (isset($_GET['ptid'])) {
		$userid = $_GET['ptid'];
		$mbraction = "Edit";
		$formname = "MM_update";
		$formvalue = "editmemberfrm";
	} else {
		$mbraction = "Add New";
		$formname = "MM_insert";
		$formvalue = "addmemberfrm";
	}

	if (isset($_POST["submit"]) && $_POST["MM_insert"] == "addmemberfrm") {
		if (!empty($_POST['title']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['username'])  && !empty($_POST['password']) && !empty($_POST['cpassword'])) {

			$existusername = $_POST['username'];
			$existemail = $_POST['email'];

			$query_rsExistMember = $db->prepare("SELECT * FROM tbl_projteam2 WHERE email = '$existemail'");
			$query_rsExistMember->execute();
			$totalRows_rsExistMember = $query_rsExistMember->rowCount();

			if ($totalRows_rsExistMember > 0) {
				$msg = 'Sorry, this member already exists. Please use unique username and email address';
				$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 5000,
							showConfirmButton: false });
					</script>";
			} else {
				if ($_FILES['photofile']['size'] >= 1048576 * 500) {
					$msg = 'The file selected exceeds 500MB size limit';
					$results = "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 5000,
								showConfirmButton: false });
						</script>";
				} else {
					//upload random name/number
					$rd2 = mt_rand(1000, 9999) . "_File";

					//Check that we have a file
					if (!empty($_FILES["photofile"])) {
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['photofile']['name']);

						$ftype = substr($filename, strrpos($filename, '.') + 1);

						if (($ftype != "exe") && ($_FILES["photofile"]["type"] != "application/x-msdownload")) {
							//Determine the path to which we want to save this file      
							$newname = $rd2 . "_" . $filename;
							$floc = "uploads/staff/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($floc)) {
								//Attempt to move the uploaded file to it's new place
								move_uploaded_file($_FILES['photofile']['tmp_name'], $floc);
							} else {
								$msg = 'Selected file exists';
								$results = "<script type=\"text/javascript\">
											swal({
												title: \"Warning!\",
												text: \" $msg\",
												type: 'Warning',
												timer: 5000,
												showConfirmButton: false });
										</script>";
							}
						} else {
							$msg = 'Selected file type not allowed';
							$results = "<script type=\"text/javascript\">
										swal({
											title: \"Warning!\",
											text: \" $msg\",
											type: 'Warning',
											timer: 5000,
											showConfirmButton: false });
									</script>";
						}
					}
				}

				$fullname = $_POST['firstname'] . " " . $_POST['lastname'];

				if (empty($_POST['conservancy']) || $_POST['conservancy'] == '') {
					$level1 = 0;
				} else {
					$level1 = $_POST['conservancy'];
				}

				if (empty($_POST['ecosystem']) || $_POST['ecosystem'] == '') {
					$level2 = 0;
				} else {
					$level2 = $_POST['ecosystem'];
				}

				if (empty($_POST['station']) || $_POST['station'] == '') {
					$level3 = 0;
				} else {
					$level3 = $_POST['station'];
				}

				$department = isset($_POST['department']) && !empty($_POST['department']) ? $_POST['department'] : 0;
				$ministry = isset($_POST['ministry']) && !empty($_POST['ministry']) ? $_POST['ministry'] : 0;

				// $createdby = $_POST['user_id'];
				$createdby = 4;
				$insertSQL = $db->prepare("INSERT INTO tbl_projteam2 (fullname, firstname, middlename, lastname, title, designation,ministry, department, levelA, levelB, levelC, floc, filename, ftype, email, phone, createdby, datecreated)  VALUES( :fullname, :firstname, :middlename, :lastname, :title, :designation,:ministry,:department, :level1, :level2, :level3,:floc, :filename, :ftype, :email, :phone, :createdby, :datecreated)");
				$Rest = $insertSQL->execute(array(":fullname" => $fullname, ":firstname" => $_POST['firstname'], ":middlename" => $_POST['middlename'], ":lastname" => $_POST['lastname'], ":title" => $_POST['title'], ":designation" => $_POST['designation'], ":ministry" => $ministry, ":department" => $department, ":level1" => $level1, ":level2" => $level2, ":level3" => $level3, ":floc" => $floc, ":filename" => $newname, ":ftype" => $ftype, ":email" => $_POST['email'], ":phone" => $_POST['phone'], ":createdby" => $createdby, ":datecreated" => date('Y-m-d')));


				if ($Rest) {
					$last_id = $db->lastInsertId();
					$type = 1;
					$password = create_password(8);
					$hash_pass = md5($password);

					$insertSQL = $db->prepare("INSERT INTO `users` (pt_id, username, password, type) VALUES( :ptid, :username, :password, :type)");
					$insertSQL->execute(array(":ptid" => $last_id, ":username" => $_POST['username'], ":password" => $hash_pass, ":type" => $type));


					sendMail($email, $password);
 
					$msg = 'You have successfully registered ' . $fullname . ' as an administrator.';
					$results = "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 5000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'projteam';
							}, 3000);
						</script>";
				} else {
					$msg = 'Can not add administrator, please review your info and try again.';
					$results = "<script type=\"text/javascript\">
							swal({
								title: \"Warning!\",
								text: \" $msg\",
								type: 'Warning',
								timer: 5000,
								showConfirmButton: false });
						</script>";
				}
			}
		} else {
			$msg = 'Some fields have not been filled.';
			$results = "<script type=\"text/javascript\">
					swal({
						title: \"Warning!\",
						text: \" $msg\",
						type: 'Warning',
						timer: 5000,
						showConfirmButton: false });
				</script>";
		}
	} elseif (isset($_POST["submit"]) && $_POST["MM_update"] == "editmemberfrm") {
		//Check that we have a file
		$myphoto = '';
		if ($_FILES['photofile']['size'] != 0) {
			if ($_FILES['photofile']['size'] >= 1048576 * 500) {
				$msg = 'File selected exceeds 500MB size limit';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Warning!\",
						text: \" $msg\",
						type: 'Warning',
						timer: 5000,
						showConfirmButton: false });
				</script>";
			} else {
				//upload random name/number
				$rd2 = mt_rand(1000, 9999) . "_File";

				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['photofile']['name']);

				$ftype = substr($filename, strrpos($filename, '.') + 1);

				if (($ftype != "exe") && ($_FILES["photofile"]["type"] != "application/x-msdownload")) {
					//Determine the path to which we want to save this file      
					$newname = $rd2 . "_" . $filename;
					$floc = "uploads/staff/" . $newname;
					//Check if the file with the same name already exists in the server
					if (!file_exists($floc)) {
						//Attempt to move the uploaded file to it's new place
						if (move_uploaded_file($_FILES['photofile']['tmp_name'], $floc)) {
							$myphoto = $floc;
						}
					} else {
						$msg = 'The selected file already exists';
						$results = "<script type=\"text/javascript\">
							swal({
								title: \"Warning!\",
								text: \" $msg\",
								type: 'Warning',
								timer: 5000,
								showConfirmButton: false });
						</script>";
					}
				} else {
					$msg = 'Selected file type not allowed';
					$results = "<script type=\"text/javascript\">
						swal({
							title: \"Warning!\",
							text: \" $msg\",
							type: 'Warning',
							timer: 5000,
							showConfirmButton: false });
					</script>";
				}
			}
		} else {
			$query_rsPhoto = $db->prepare("SELECT floc FROM tbl_projteam2 WHERE ptid='$userid'");
			$query_rsPhoto->execute();
			$row_rsPhoto = $query_rsPhoto->fetch();
			$totalRows_rsPhoto = $query_rsPhoto->rowCount();
			$myphoto = $row_rsPhoto['floc'];
		}

		$username = $_POST['username'];
		$title = $_POST['title'];
		$firstname = $_POST['firstname'];
		$middlename = $_POST['middlename'];
		$lastname = $_POST['lastname'];
		$fullname = $firstname . " " . $lastname;
		$designation = $_POST['designation'];
		$department = isset($_POST['department']) && !empty($_POST['department']) ? $_POST['department'] : 0;
		$ministry = isset($_POST['ministry']) && !empty($_POST['ministry']) ? $_POST['ministry'] : 0;
		$office = $_POST['office'];
		$floc = $myphoto;
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$username = $_POST['username'];


		$queryupdate = $db->prepare("UPDATE tbl_projteam2 SET fullname=:fullname, firstname=:firstname, middlename=:middlename, lastname=:lastname, title=:title, designation=:designation,ministry=:ministry, department=:department, floc=:floc, filename=:filename, ftype=:ftype, level=:level, email=:email, phone=:phone WHERE ptid=:ptid");
		$retval = $queryupdate->execute(array(":fullname" => $fullname, ":firstname" => $firstname, ":middlename" => $middlename, ":lastname" => $lastname, ":title" => $title, ":designation" => $designation, ":ministry" => $ministry, ":department" => $department, ":floc" => $floc, ":filename" => $filename, ":ftype" => $ftype, ":level" => $_POST['level'], ":email" => $_POST['email'], ":phone" => $_POST['phone'], ":ptid" => $userid));

		if ($retval) {
			if (isset($username) && !empty($username)) {

				$query_rsExistMember = $db->prepare("SELECT * FROM tbl_users WHERE pt_id = '$userid'");
				$query_rsExistMember->execute();
				$totalRows_rsExistMember = $query_rsExistMember->rowCount();

				if ($totalRows_rsExistMember > 0) {
					$queryupdate = $db->prepare("UPDATE tbl_users SET username=:username, level=:level WHERE pt_id=:ptid");
					$queryupdate->execute(array(":username" => $username, ":level" => $_POST['level'], ":ptid" => $userid));
				} else {
					$password = md5("password123");
					$type = 1;
					$insertSQL = $db->prepare("INSERT INTO `users` (pt_id, username, password, type) VALUES( :ptid, :username, :password, :type)");
					$insertSQL->execute(array(":ptid" => $userid, ":username" => $_POST['username'], ":password" => $password, ":type" => $type));
				}

				$msg = 'User info successfully updated.';
				$results = "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 5000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'projteam';
							}, 3000);
						</script>";
			}
		} else {
			$msg = 'User details was not updated. Please confirm the information provided';
			$results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 5000,
							showConfirmButton: false });
					</script>";
		}
	}

	$query_rsTitle =  $db->prepare("SELECT id,title FROM tbl_mbrtitle");
	$query_rsTitle->execute();
	$row_rsTitle = $query_rsTitle->fetch();

	$query_rsPMDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation ORDER BY moid ASC");
	$query_rsPMDesignation->execute();
	$row_rsPMDesignation = $query_rsPMDesignation->fetch();

	$query_rsSector =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 and deleted='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();

	$query_country =  $db->prepare("SELECT id,country FROM countries");
	$query_country->execute();

	$query_rsPTeam = $db->prepare("SELECT *, CONCAT(`lastname`,', ',`firstname`,' ', '(',`title`,')') AS fullnames FROM tbl_projteam2 WHERE ptid = '$userid'");
	$query_rsPTeam->execute();
	$row_rsPTeam = $query_rsPTeam->fetch();
	$totalRows_rsPTeam = $query_rsPTeam->rowCount();


	$query_rsUser = $db->prepare("SELECT * FROM tbl_users WHERE pt_id = '$userid'");
	$query_rsUser->execute();
	$row_rsUser = $query_rsUser->fetch();
	$totalRows_rsUser = $query_rsUser->rowCount();




	$query_rsPMOffice = $db->prepare("SELECT * FROM tbl_projmemoffices ORDER BY moid ASC");
	$query_rsPMOffice->execute();
	$row_rsPMOffice = $query_rsPMOffice->fetch();
	$totalRows_rsPMOffice = $query_rsPMOffice->rowCount();

	$query_rsPMLevel = $db->prepare("SELECT * FROM tbl_level ORDER BY level_id ASC");
	$query_rsPMLevel->execute();
	$row_rsPMLevel = $query_rsPMLevel->fetch();
	$totalRows_rsPMLevel = $query_rsPMLevel->rowCount();

	$query_rsTitle = $db->prepare("SELECT * FROM tbl_mbrtitle ORDER BY id ASC");
	$query_rsTitle->execute();
	$row_rsTitle = $query_rsTitle->fetch();
	$totalRows_rsTitle = $query_rsTitle->rowCount();

	$query_rsLocation = $db->prepare("SELECT id,state FROM tbl_state WHERE location=0 and parent IS NULL");
	$query_rsLocation->execute();
	$row_rsLocation = $query_rsLocation->fetch();
	$totalRows_rsLocation = $query_rsLocation->rowCount();

	$query_rsDesignation = $db->prepare("SELECT moid, designation FROM tbl_pmdesignation");
	$query_rsDesignation->execute();
	$row_rsDesignation = $query_rsDesignation->fetch();
	$totalRows_rsDesignation = $query_rsDesignation->rowCount();

	
} catch (PDOException $ex) {
	$result = flashMessage("An error occurred: " . $ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Result-Based Monitoring &amp; Evaluation System: Tender Details</title>
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<!--CUSTOM MAIN STYLES-->
	<link href="css/custom.css" rel="stylesheet" />

	<!-- Bootstrap Core Css -->
	<link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Waves Effect Css -->
	<link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

	<!-- Animation Css -->
	<link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Bootstrap DatePicker Css -->
	<link href="projtrac-dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

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

	<!-- Custom Css -->
	<link href="projtrac-dashboard/css/style.css" rel="stylesheet">

	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />


	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>
	<link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <link href="style.css" rel="stylesheet"> -->
	<script src="ckeditor/ckeditor.js"></script>
	<script language='JavaScript' type='text/javascript' src='JScript/CalculatedField.js'></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".account").click(function() {
				var X = $(this).attr('id');

				if (X == 1) {
					$(".submenus").hide();
					$(this).attr('id', '0');
				} else {
					$(".submenus").show();
					$(this).attr('id', '1');
				}

			});

			//Mouseup textarea false
			$(".submenus").mouseup(function() {
				return false
			});
			$(".account").mouseup(function() {
				return false
			});


			//Textarea without editing.
			$(document).mouseup(function() {
				$(".submenus").hide();
				$(".account").attr('id', '');
			});

		});
	</script>
	<style>
		.showconservancy {
			display: none;
		}

		.showecosystem {
			display: none;
		}

		.showstation {
			display: none;
		}
	</style>
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
			<?php
			//include_once("allnotifications.php");
			?>
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

	<section class="content" style="margin-top:-20px; padding-bottom:0px">
		<div class="container-fluid">
			<div class="block-header">
				<h4 class="contentheader"><i class="fa fa-tasks" aria-hidden="true"></i> <?php echo $mbraction; ?>Member</h4>
				<div>
					<?php echo $results; ?>
				</div>
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<?php
							include_once('addeditmember-inner.php');
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Bootstrap Core Js -->
	<script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

	<!-- Multi Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

	<!-- Slimscroll Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- Waves Effect Plugin Js -->
	<script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

	<!-- Autosize Plugin Js -->
	<script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

	<!-- Moment Plugin Js -->
	<script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

	<!-- Sparkline Chart Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>

	<!-- Bootstrap Colorpicker Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

	<!-- Input Mask Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

	<!-- Jquery Spinner Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>

	<!-- Bootstrap Tags Input Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

	<!-- noUISlider Plugin Js -->
	<script src="projtrac-dashboard/plugins/nouislider/nouislider.js"></script>


	<!-- Bootstrap Material Datetime Picker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

	<!-- Bootstrap Datepicker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

	<!-- Custom Js --
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script> -->
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>

	<!-- validation cdn files  -->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>
</body>

</html>