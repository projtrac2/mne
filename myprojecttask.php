<?php 

require 'authentication.php';

try{	

	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 
	if (isset($_GET['projid'])) {
	  $projectid_rsMyP = $_GET['projid'];
	}
	$query_rsMyP =  $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE tbl_projects.deleted='0' AND user_name = '$user_name' AND projid = '$projectid_rsMyP'");
	$query_rsMyP->execute();		
	$row_rsMyP = $query_rsMyP->fetch();
	$projcategory = $row_rsMyP["projcategory"];
	
	if (isset($_GET['projid'])) {
	  $colname_rsMSTask = $_GET['projid'];
	}
	$query_rsMSTask =  $db->prepare("SELECT tbl_projects.projid, tbl_projects.projstatus AS pjst, tbl_milestone.msid, tbl_milestone.milestone, tbl_task.tkid, tbl_task.task, tbl_task.taskbudget AS taskbudget, tbl_task.status, tbl_task.progress AS tskprogress, tbl_task.sdate AS stdate, tbl_task.edate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid INNER JOIN tbl_task ON tbl_milestone.msid=tbl_task.msid WHERE tbl_projects.projid = '$colname_rsMSTask' GROUP BY tbl_task.task ORDER BY tbl_milestone.milestone, tbl_task.sdate ASC");
	$query_rsMSTask->execute();		
	$row_rsMSTask = $query_rsMSTask->fetch();

	if (isset($_GET['projid'])) {
	  $colname_rsMSProgress = $_GET['projid'];
	}	
	$query_rsMSProgress =  $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`, COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`, COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,  COUNT(tbl_milestone.status) AS 'Total Status' FROM tbl_projects  INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid  WHERE tbl_projects.projid = '$colname_rsMSProgress' GROUP BY tbl_projects.projid");
	$query_rsMSProgress->execute();		
	$row_rsMSProgress = $query_rsMSProgress->fetch();
	$totalRows_rsMSProgress = $query_rsMSProgress->rowCount();

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
    <title>Projtrac M&E - Financial Report</title>
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

	<?php
	$projectID =  $row_rsMyP['projid']; 				
	$currentStatus =  $row_rsMyP['projstatus'];
	
	$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$projectID'");
	$query_rsMlsProg->execute();		
	$row_rsMlsProg = $query_rsMlsProg->fetch();

	$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

	$percent2 = round($prjprogress,2);
	?>
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
		-webkit-border-radius: 0px;
		margin-bottom:30px;
		border-radius: 0px;
	}


	.bar {
		background: #CDDC39;
		width: <?php echo $percent2; ?>%;
		height:24px;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
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

	.cornflowerblue {
		background-color: CornflowerBlue;
		box-shadow:inset 0px 0px 6px 2px rgba(255,255,255,.3);
		width: <?php echo $percent2; ?>%;
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

<body class="theme-blue" onload="getLocation()">
    <!-- Page Loader -->
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
	$tndprojid = $row_rsMyP['projid'];	
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
		<?php  include_once('myprojecttask-inner.php');?>
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

    <!-- Jquery Validation Plugin Css -->
    <script src="projtrac-dashboard/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-steps/jquery.steps.js"></script>

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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/form-validation.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>