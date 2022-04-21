<?php
include_once 'includes/head-alt.php';
$Id = 16;
$subId =44;

try{
		
    
	
	
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
    <title>Result-Based Monitoring &amp; Evaluation System: Escalated Issues</title>
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

    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
	function CallRiskAction(id)
	{
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {rskid:id},
			success: function (data) {
				$('#riskaction').html(data);
				 $("#riskModal").modal({backdrop: "static"});
			}
		});
	}
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

    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">  
            <div class="block-header bg-black" width="100%" height="40" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> System Global Configurations</h4>
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
				<div class="block-header">
					<?php 
						echo $results;
					?>
				</div>
				<?php
				if(isset($_GET["mainmenu"]) && !empty($_GET["mainmenu"])){
					include_once('project-main-menu-inner.php');
				}
				elseif(isset($_GET["workflow"]) && !empty($_GET["workflow"])){
					include_once('system-workflow-stages-inner.php');
				}
				elseif(isset($_GET["evtype"]) && !empty($_GET["evtype"])){
					include_once('project-evaluation-types-inner.php');
				}
				elseif(isset($_GET["bigfour"]) && !empty($_GET["bigfour"])){
					include_once('project-big-four-agenda-inner.php');
				}
				elseif(isset($_GET["biztype"]) && !empty($_GET["biztype"])){
					include_once('project-contractor-business-types-inner.php');
				}
				elseif(isset($_GET["contractornationality"]) && !empty($_GET["contractornationality"])){
					include_once('project-contractor-nationality-inner.php');
				}
				elseif(isset($_GET["cooptype"]) && !empty($_GET["cooptype"])){
					include_once('project-coorporate-types-inner.php');
				}
				elseif(isset($_GET["counties"]) && !empty($_GET["counties"])){
					include_once('project-counties-inner.php');
				}
				elseif(isset($_GET["countries"]) && !empty($_GET["countries"])){
					include_once('project-countries-inner.php');
				}
				elseif(isset($_GET["currency"]) && !empty($_GET["currency"])){
					include_once('project-currency-inner.php');
				}
				elseif(isset($_GET["dtfreq"]) && !empty($_GET["dtfreq"])){
					include_once('project-data-collection-frequency-inner.php');
				}
				elseif(isset($_GET["donationtype"]) && !empty($_GET["donationtype"])){
					include_once('project-donation-type-inner.php');
				}
				elseif(isset($_GET["leavecat"]) && !empty($_GET["leavecat"])){
					include_once('project-employee-leave-categories-inner.php');
				}
				elseif(isset($_GET["fiscalyear"]) && !empty($_GET["fiscalyear"])){
					include_once('project-fiscal-year-inner.php');
				}
				elseif(isset($_GET["fundingtype"]) && !empty($_GET["fundingtype"])){
					include_once('project-funding-type-inner.php');
				}
				elseif(isset($_GET["implmethod"]) && !empty($_GET["implmethod"])){
					include_once('project-implementation-method-inner.php');
				}
				elseif(isset($_GET["severity"]) && !empty($_GET["severity"])){
					include_once('project-issue-severity-inner.php');
				}
				elseif(isset($_GET["maptype"]) && !empty($_GET["maptype"])){
					include_once('project-map-type-inner.php');
				}
				elseif(isset($_GET["paymentstatus"]) && !empty($_GET["paymentstatus"])){
					include_once('project-payment-status-inner.php');
				}
				elseif(isset($_GET["projectstatus"]) && !empty($_GET["projectstatus"])){
					include_once('project-statuses-inner.php');
				}
				elseif(isset($_GET["designations"]) && !empty($_GET["designations"])){
					include_once('designation-inner.php');
				}
				elseif(isset($_GET["procurementmethod"]) && !empty($_GET["procurementmethod"])){
					include_once('project-procurement-method-inner.php');
				}
				elseif(isset($_GET["tendercat"]) && !empty($_GET["tendercat"])){
					include_once('project-tender-category-inner.php');
				}
				elseif(isset($_GET["tendertype"]) && !empty($_GET["tendertype"])){
					include_once('project-tender-type-inner.php');
				}
				elseif(isset($_GET["titles"]) && !empty($_GET["titles"])){
					include_once('project-titles-inner.php');
				}
				elseif(isset($_GET["timelines"]) && !empty($_GET["timelines"])){
					include_once('project-workflow-stage-timelines-inner.php');
				}
				elseif(isset($_GET["priorities"]) && !empty($_GET["priorities"])){
					include_once('project-priorities-inner.php');
				}
				elseif(isset($_GET["innermenu"]) && !empty($_GET["innermenu"])){
					include_once('project-sub-menu-inner.php');
				}
				elseif(isset($_GET["indcat"]) && !empty($_GET["indcat"])){
					include_once('project-indicator-category-inner.php');
				}
				elseif(isset($_GET["financialplan"]) && !empty($_GET["financialplan"])){
					include_once('project-financial-plan-inner.php');
				}
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
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>