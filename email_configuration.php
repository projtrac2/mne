<?php  
include_once 'includes/head-alt.php';
$Id = 16;
$subId =44;
   
$emailsetting = $db->query("select * from `tbl_email_settings`")->fetch();

if (isset($_POST["submit"])) { 
	$today = date('Y-m-d');
	$smtpAutoTLS = $_POST["autotls"]; 
	$SMTPAuth = $_POST["SMTPAuth"]; 
	$port = $_POST["Port"];  
	$host = $_POST["host"]; 
	$security = $_POST["security"];  
	$username = $_POST["username"];   
	$password = $_POST["password"];  
	$id = $_POST["id"];  
	$createdby = 1;  

 	if (empty($id)) {
		$save = $db->prepare("INSERT INTO tbl_email_settings SET smtpAutoTLS = :smtpAutoTLS, SMTPAuth = :SMTPAuth, port = :port, host = :host, SMTPSecure = :security, username = :username, password = :password, createdby = :createdby, dateCreated = :today");
		$result  = $save->execute(array(":smtpAutoTLS" => $smtpAutoTLS, ":SMTPAuth" => $SMTPAuth, ':port' => $port, ':host' => $host, ':security' => $security, ':username' => $username,":password"=>$password,":createdby"=>$createdby,":today" => $today));
	} else {
		$save = $db->prepare("UPDATE tbl_email_settings SET smtpAutoTLS = :smtpAutoTLS, SMTPAuth = :SMTPAuth, port = :port, host = :host, SMTPSecure = :security, username = :username, password = :password, createdby = :createdby, dateCreated = :today WHERE id = :id");
		$result  = $save->execute(array(":smtpAutoTLS" => $smtpAutoTLS, ":SMTPAuth" => $SMTPAuth, ':port' => $port, ':host' => $host, ':security' => $security, ':username' => $username,":password"=>$password,":createdby"=>$createdby,":today" => $today,":id" => $id));
	}
	
	if ($result) {
		$msg = 'Settings details successfully saved.';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'email_configuration.php';
			  }, 5000);
		</script>";	
	}else{
		$msg = 'Can not save the provided details!!';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Error!\",
				text: \" $msg \",
				type: 'Danger',
				timer: 5000,
				showConfirmButton: false });
		</script>";
	}
//$conn->query("SELECT * FROM events where status = 3")->num_rows;
//var_dump($emailsettingQuery);
}
?>

 <!DOCTYPE html>
 <html>

 <head>
 	<meta charset="UTF-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
 	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 	<title>Result-Based Monitoring &amp; Evaluation System: Indicators</title>
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
            <div class="block-header bg-black" width="100%" height="40" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> System Email Configuration</h4>
            </div>          
			<!-- <div class="body"> -->
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card" style="margin-bottom:-10">
						<div class="header" style="padding-bottom:5px; margin-left:-10px; margin-right:-12px">
						<?php include_once("settings-menu.php"); ?>
						</div>
					</div>
				</div>
				<!-- Draggable Handles -->
 				<div class="block-header">
 					<?php
					echo $results;
					?>
 				</div>
 				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
 					<div class="card">
 						<!-- <div class="body"> -->
 						<?php
							include_once('email-configuration-inner.php');
						?>
 						<!--</div> -->
 					</div>
 				</div>
 			</div>
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

 	<!-- Autosize Plugin Js -->
 	<script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

 	<!-- Moment Plugin Js -->
 	<script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

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
 	<script src="projtrac-dashboard/js/admin2.js"></script>
 	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
 	<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>

 	<!-- Demo Js -->
 	<script src="projtrac-dashboard/js/demo.js"></script>
 	<script src="assets/custom js/indicator-details.js"></script>

 </body>

 </html>