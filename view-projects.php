<?php 
require 'authentication.php';
try {
	 
	if (isset($_GET["progid"]) && !empty($_GET["progid"])) {
		$progid = base64_decode($_GET["progid"]);
	}
	
	$current_date = date("Y-m-d");
	if (isset($_GET["adp"]) && isset($_GET["proj"]) && !empty($_GET["proj"])) {
		$currentfy = date("Y");
		
		//get financial years 
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr=:yr");
		$query_rsYear->execute(array(":yr" => $currentfy));
		$row_rsYear = $query_rsYear->fetch();
		
		$currentfyid = $row_rsYear["id"];
		$projid = $_GET['proj'];
		
		$insert_query = $db->prepare("INSERT INTO tbl_annual_dev_plan (projid, financial_year, created_by, date_created) VALUES (:projid, :finyear, :user, :dates)");
		$results = $insert_query->execute(array(":projid" => $projid, ":finyear" => $currentfyid, ":user" => $user_name, ":dates" => $current_date));

		if ($results) {
			$msg = 'The project successfully added to ADP.';
			$results = "<script type=\"text/javascript\">
					swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					showConfirmButton: false });
					setTimeout(function(){
							window.location.href = 'strategic-plan-projects';
						}, 2000);
				</script>";
		}  
	}
	
	$query_userdetails =  $db->prepare("SELECT ptid FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$user_name'");
	$query_userdetails->execute();
	$row_userdetails = $query_userdetails->fetch();


	//get financial years 
	$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year");
	$query_rsYear->execute();
	$row_rsYear = $query_rsYear->fetch();
	$totalRows_rsYear = $query_rsYear->rowCount();

	//get subcounty
	$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
	$query_rsComm->execute();
	$row_rsComm = $query_rsComm->fetch();
	$totalRows_rsComm = $query_rsComm->rowCount();

	//get mapping type 
	$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
	$query_rsMapType->execute();
	$row_rsMapType = $query_rsMapType->fetch();
	$totalRows_rsMapType = $query_rsMapType->rowCount();

	//get project implementation methods 
	$query_rsProjImplMethod =  $db->prepare("SELECT id, method FROM tbl_project_implementation_method");
	$query_rsProjImplMethod->execute();
	$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
	$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

	// get project risks 
	$query_rsRiskCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
	$query_rsRiskCategories->execute();
	$row_rsRiskCategories = $query_rsRiskCategories->fetch();
	$totalRows_rsRiskCategories = $query_rsRiskCategories->rowCount();
	
	// get project risks 
	$query_strategic_plan =  $db->prepare("SELECT * FROM tbl_strategicplan where current_plan=1");
	$query_strategic_plan->execute();
	$row_strategic_plan = $query_strategic_plan->fetch();
	$currentplan =$row_strategic_plan["plan"];
	$currentplanid =$row_strategic_plan["id"];
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	print($ex->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Result-Based Monitoring &amp; Project Titles </title>
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

	<link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="ckeditor/ckeditor.js"></script>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}

		hr {
			display: block;
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-width: 1px;
		}
	</style>

	<script type="text/javascript">
		function CallRiskAction(id) {
			$.ajax({
				type: 'post',
				url: 'callriskaction.php',
				data: {
					rskid: id
				},
				success: function(data) {
					$('#riskaction').html(data);
					$("#riskModal").modal({
						backdrop: "static"
					});
				}
			});
		}

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
		.modal-lg {
			max-width: 100% !important;
			width: 90%;
		}
	</style>
</head>

<body class="theme-blue">
	<!-- Page Loader -->
	<!-- <div class="page-loader-wrapper"> -->
	<!-- <div class="loader">
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
</div> -->
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

	<section class="content" style="margin-top:-20px; padding-bottom:0px">
		<div class="container-fluid">
			<!-- <div class="body"> -->
			<?php
			echo $results;
			include_once('view-projects-inner.php');
			?>
			<!--</div> -->
		</div>
	</section>

	<!-- Jquery Core Js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

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

	<!-- Sweet Alert Plugin Js -->
	<script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

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

	<!-- Jquery Knob Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-knob/jquery.knob.min.js"></script>

	<!-- Jquery DataTable Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
	<script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

	<!-- Custom Js -->
	<script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
	<script src="projtrac-dashboard/js/admin.js"></script>
	<script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>