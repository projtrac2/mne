<?php
include_once 'includes/head-alt.php';

$Id = 13;
$subId = 1;

try{

	if(isset($_GET['mbrid'])){
		$mbrid = $_GET['mbrid'];
		
		if(!empty($mbrid)){
			$query_mbrprojs = $db->prepare("SELECT * FROM tbl_projmembers WHERE ptid='$mbrid' GROUP BY projid");
			$query_mbrprojs->execute();
			
			$query_mbrdetails = $db->prepare("SELECT title, fullname FROM tbl_projteam2 WHERE ptid='$mbrid' AND disabled='0'");
			$query_mbrdetails->execute();
			$row_mbrdetails = $query_mbrdetails->fetch();
		}
	}
	
	
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based Monitoring &amp; Evaluation System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Colorpicker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="projtrac-dashboard/plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="projtrac-dashboard/plugins/nouislider/nouislider.min.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/SyntaxHighlighter/3.0.83/styles/shCoreDefault.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

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
<style>
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #29426b; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
</style>
<style type="text/css">
#imgeee{
 max-width:50%;
 max-height:50%;
}

#projmenu {
	background-image: url(images/wbgn.png);
	height: 30px;
	width: 2px;
}

#projmenu:hover {
	background-image: url(images/wbgnhover.png);
	background-position: left;
	background-repeat:no-repeat;
	height: 30px;
	width: 2px;
}

#colrow {	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	color: #FFF;
	background-color:#069;
	height: 30px;
}
#formcells {	
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	color:#000;
}
#pagehead {	font-family: Verdana, Geneva, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #000;
}
#rows {	
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	height: 40px;
	border:#EEE thin solid;
}
.topmenu {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#000;
}
</style>
<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>
<style>
* {
  margin: 0;
  padding: 0;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

ul { list-style-type: none; }

a {
  color: #b63b4d;
  text-decoration: none;
}

/** =======================
 * Contenedor Principal
 ===========================*/

.accordion {
	width: 100%;
	max-width: 250px;
	background: #036;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 20px;
	margin-left: auto;
}

.accordion .link {
	cursor: pointer;
	display: block;
	color: #FFF;
	font-size: 12px;
	font-weight: 500;
	border-bottom: 1px solid #CCC;
	position: relative;
	-webkit-transition: all 0.4s ease;
	-o-transition: all 0.4s ease;
	transition: all 0.4s ease;
	padding-top: 10px;
	padding-right: 15px;
	padding-bottom: 10px;
	padding-left: 42px;
}

.accordion li:last-child .link { border-bottom: 0; }

.accordion li i {
  position: absolute;
  top: 16px;
  left: 12px;
  font-size: 12px;
  color: #595959;
  -webkit-transition: all 0.4s ease;
  -o-transition: all 0.4s ease;
  transition: all 0.4s ease;
}

a:visited {
  color: white;
}
.accordion li i.fa-chevron-down {
  right: 12px;
  left: auto;
  font-size: 16px;
}

.accordion li.open .link { color: #CCC; }

.accordion li.open i { color: #CCC; }

.accordion li.open i.fa-chevron-down {
  -webkit-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(180deg);
}

/**
 * Submenu
 -----------------------------*/


.submenu {
  display: none;
  background: #444359;
  font-size: 12px;
}

.submenu li { border-bottom: 1px solid #4b4a5e; }

.submenu a {
  display: block;
  text-decoration: none;
  color: #d9d9d9;
  padding: 12px;
  padding-left: 5px;
  -webkit-transition: all 0.25s ease;
  -o-transition: all 0.25s ease;
  transition: all 0.25s ease;
}

.submenu a:hover {
  background: #b63b4d;
  color: #FFF;
}
.menutext {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
.sidemenu{
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	color: #FFF;
	margin-top: 0px;
	padding-bottom: 5px;
	padding-top: 5px;
	max-width:100%;
	background-color:#036;
}
.loginfloat{
	float:right;
}
.logocss{
	margin-bottom: 8px;
	margin-top: 12px;
	margin-left: 5px;
	}
.headercontainer{
	background-color: #0080C0;
	height: 60px;
	margin-top: -5px;
}
.bodycontainer{
	background-color:#036}
.footercss{
	background-color:#036;
}
.dropdowns 
{
color: #000;
margin: 3px -22px 0 0;
width: 143px;
position: relative;
height: 17px;
text-align:left;
font-size:12px;
float:right;
margin-top:25px;
font-weight:bold;
font-family:Verdana, Geneva, sans-serif;
}
.submenus
{
background: #FFF;
position: absolute;
top: -12px;
left: -20px;
z-index: 100;
width: 135px;
display: none;
margin-left: 10px;
padding: 40px 0 5px;
border-radius: 6px;
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
}
.dropdowns li a 
{
color: #000;
display: block;
font-family:Verdana, Geneva, sans-serif;
font-weight: normal;
border-bottom:#CCC thin dotted;
padding: 6px 15px;
cursor: pointer;
text-decoration:none;
}

.dropdowns li a:hover
{
background:#155FB0;
color: #000;
text-decoration: none;
background-color:#0080C0;
}
a.account 
{
	font-size: 12px;
	line-height: 16px;
	color: #000;
	position: absolute;
	z-index: 110;
	display: block;
	padding: 11px 0 0 20px;
	height: 47px;
	width: 135px;
	margin: -12px 0 0 -10px;
	text-decoration: none;
	cursor: pointer;
	background-color: #0080C0;
	background-image: url(images/arrow.png);
	background-repeat: no-repeat;
	background-position: 105px 10px;
}
.root
{
list-style:none;
margin:0px;
padding:0px;
font-size: 12px;
padding: 11px 0 0 0px;
border-top:1px solid #dedede;
}
.menutxtright{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#fff;
	width:300px;
	float:right;
}
</style>
<link href="projtrac-dashboard/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="projtrac-dashboard/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
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

    <section class="content"  style="margin-bottom:20px">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<h2>
                                <span class="label bg-brown"><?=$row_mbrdetails["title"]?>. <?=$row_mbrdetails["fullname"]?>'s Projects</span>
                                <a href="projteam" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">Go Back to Team</a>
							</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr style="background-color:#607D8B; color:#FFF">
                                            <th>#</th>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th><?=$departmentlabel?></th>
                                            <th>Cost (Ksh)</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color:#CCC">
                                            <th>#</th>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th><?=$departmentlabel?></th>
                                            <th>Cost (Ksh)</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
									<?php	
									$nm = 0;
									while($mbrproj = $query_mbrprojs->fetch()){ 
										$mbrprojid = $mbrproj["projid"];
										
										$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid='$mbrprojid'");
										$query_projdetails->execute();
										$detail = $query_projdetails->fetch();
										
										$progid = $detail['progid'];
										$projstatusid = $detail['projstatus'];
										
										$query_projstatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid='$projstatusid'");
										$query_projstatus->execute();
										$row_status = $query_projstatus->fetch();
										
										$projdept = $db->prepare("SELECT s.sector FROM tbl_sectors s inner join tbl_programs g ON g.projdept=s.stid WHERE progid='$progid'");
										$projdept->execute();
										$prjdept = $projdept->fetch();
										
										$projstatus = $row_status["statusname"];
										if($projstatusid == 0){
											$projstatus = "Awaiting Procurement";
										}
										
										$nm++;
										?>
                                        <tr>
                                            <td><?php echo $nm; ?></td>
                                            <td><?php echo $detail['projname']; ?></td>
                                            <td <?php if($projstatusid==9 || $projstatusid==11){ echo 'class="bg-red"';}elseif($projstatusid==5){ echo 'class="bg-green"';} elseif($projstatusid==4){ echo 'class="bg-light-blue"';} elseif($projstatusid==6){ echo 'class="bg-pink"';} else{ echo 'class="bg-grey"';} ?>><?php echo $projstatus; ?></td>
                                            <td><?php echo $prjdept['sector']; ?></td>
                                            <td><?php echo number_format($detail['projcost'], 2); ?></td>
                                            <td><?php echo $detail['projstartdate']; ?></td>
                                            <td><?php echo $detail['projenddate']; ?></td>
                                        </tr>
									<?php 
									} 
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="projtrac-dashboard/plugins/raphael/raphael.min.js"></script>
    <script src="projtrac-dashboard/plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="projtrac-dashboard/plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="projtrac-dashboard/plugins/flot-charts/jquery.flot.js"></script>
    <script src="projtrac-dashboard/plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="projtrac-dashboard/plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="projtrac-dashboard/plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="projtrac-dashboard/plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin.js"></script>
    <!--<script src="js/pages/index.js"></script>-->
	
    <!-- Bootstrap Colorpicker Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="projtrac-dashboard/plugins/dropzone/dropzone.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	
    <!-- noUISlider Plugin Js -->
    <script src="projtrac-dashboard/plugins/nouislider/nouislider.js"></script>

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
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>
