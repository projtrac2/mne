<?php
//include_once 'projtrac-dashboard/resource/session.php';

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if (!isset($_SESSION)) {
  session_start();
}

require 'authentication.php';

try{	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	$query_rsCommunity = $db->prepare("SELECT id,state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");	
	$query_rsCommunity->execute();
	$row_rsCommunity = $query_rsCommunity->fetch();
	
	// project map type
	$query_rsMapType = $db->prepare("SELECT * FROM tbl_map_type ORDER BY type");	
	$query_rsMapType->execute();
	$row_rsMapType = $query_rsMapType->fetch();
	
	
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
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
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
	<link rel="stylesheet" href="custom-map.css"> 

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

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

	<script type="text/javascript" >
	$(document).ready(function()
	{
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});


	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
		
	});</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>
	<style type="text/CSS">

	/* If you want the label inside the progress bar */
	#label {
		text-align: center; /* If you want to center it */
		line-height: 25px; /* Set the line-height to the same as the height of the progress bar container, to center it vertically */
		color: black;
	}

	/* Defining the animation */

	@-webkit-keyframes progress
	{
		to {background-position: 30px 0;}
	}

	@-moz-keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	@keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	/* Set the base of our loader */

	.barBg {
		background:#CCCCCC;
		width:100%;
		height:25px;
		border:1px solid #CCCCCC;
		-moz-border-radius: 0px;
		border-radius: 0px;
		margin-bottom:30px;
	}
 

	/* Set the linear gradient tile for the animation and the playback */

	.barFill {
		width: 100%;
		height: 25px; 
		-webkit-animation: progress 1s linear infinite;
		-moz-animation: progress 1s linear infinite;
		animation: progress 1s linear infinite;
		background-repeat: repeat-x;
		background-size: 30px 30px;
		background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	}

	/* Here's some predefined widths to control the fill. Add these classes to the "bar" div */

	.ten {
		width: 10%; /* Sets the progress to 10% */
	}	

	.twenty {
		width: 20%; /* Sets the progress to 20% */
	}	

	.thirty {
		width: 30%; /* Sets the progress to 30% */
	}	

	.forty {
		width: 40%; /* Sets the progress to 40% */
	}	

	.fifty {
		width: 50%; /* Sets the progress to 50% */
	}

	.sixty {
		width: 60%; /* Sets the progress to 60% */
	}	

	.seventy {
		width: 70%; /* Sets the progress to 70% */
	}	

	.eighty {
		width: 80%; /* Sets the progress to 80% */
	}	

	.ninety {
		width: 90%; /* Sets the progress to 90% */
	}	

	.hundred {
		width: 100%; /* Sets the progress to 100% */
	}	

	/* Some colour classes to get you started. Add the colour class to the "bar" div */

	.aquaGradient{
		background: -moz-linear-gradient(top,  #7aff32 0%, #54a6e5 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7aff32), color-stop(100%,#54a6e5));
		background: -webkit-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -o-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -ms-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: linear-gradient(to bottom,  #7aff32 0%,#54a6e5 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7aff32', endColorstr='#54a6e5',GradientType=0 );
	}

	.roseGradient {
		background: #ff3232;
		background: -moz-linear-gradient(top,  #ff3232 0%, #ed89ff 47%, #ff8989 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff3232), color-stop(47%,#ed89ff), color-stop(100%,#ff8989));
		background: -webkit-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -o-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -ms-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: linear-gradient(to bottom,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff3232', endColorstr='#ff8989',GradientType=0 );
	}

	.madras { /* Credit to Divya Manian via http://lea.verou.me/css3patterns/#madras */
		background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);; background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);
	}
 
	.carrot {
		background: #f2a130;
		background: -moz-linear-gradient(-45deg,  #f2a130 0%, #e5bd6e 100%);
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#f2a130), color-stop(100%,#e5bd6e));
		background: -webkit-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -o-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -ms-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: linear-gradient(135deg,  #f2a130 0%,#e5bd6e 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2a130', endColorstr='#e5bd6e',GradientType=1 );

	}
	</style>
    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  </style>
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
	<?php
	$tndprojid = $row_rsMilestone['projid'];	
	$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$tndprojid'");
	$query_rsTender->execute();		
	$row_rsTender = $query_rsTender->fetch();
	$totalRows_rsTender = $query_rsTender->rowCount();
	?>
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
	<!-- Inner Section -->
		<?php  include_once('projects-mapping-inner.php');?>
    <!-- #END# Inner Section -->


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
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>