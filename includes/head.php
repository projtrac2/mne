<?php
include 'includes/controller.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Result-Based Monitoring &amp; Evaluation System: Indicators</title>

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

	<!-- fontawesome  -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Favicon-->
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

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

	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />

	<!-- Jquery Core Js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Sweet Alert Css -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

	<!--CUSTOM MAIN STYLES-->
	<link href="assets/css/custom.css" rel="stylesheet" />
	<link href="assets/css/sidebar.css" rel="stylesheet">

	<!-- date-picker -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />

</head>

<body class="theme-blue">
	<!-- Page Loader --
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-red">
					<div class="circle-clipper left">
						<div class="circle">...</div>
					</div>
					<div class="circle-clipper right">
						<div class="circle">...</div>
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
				<img src="assets/images/logo.png" alt="logo" width="239" height="39">
			</div>
			<?php
			include_once("includes/notifications.php");
			?>
		</div>
	</nav>
	<!-- #Top Bar -->

	<!-- #Top Bar -->
	<section>

		<!-- Left Sidebar -->
		<aside id="leftsidebar" class="sidebar">
			<!-- User Info -->
			<div class="user-info">
				<div class="image">
					<img src="assets/images/user.png" width="48" height="48" alt="User" />
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
		</aside>
		<!-- #END# Left Sidebar -->
	</section>