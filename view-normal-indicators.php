<?php
//include_once 'projtrac-dashboard/resource/session.php';

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
//$currentdate = date("Y-m-d");

if (!isset($_SESSION)) {
  session_start();
}

require 'authentication.php';

try {

	


	if (isset($_GET['indcode'])) {
		$indcode_rsUpP = $_GET['indcode'];
	}

	if (isset($_GET['srcsector'])) {
		$indsector_rsUpP = $_GET['srcsector'];
	}

	if (isset($_GET['srcdept'])) {
		$inddept_rsUpP = $_GET['srcdept'];
	}

	if (isset($_GET['srccat'])) {
		$indcat_rsUpP = $_GET['srccat'];
	}

	if(isset($_GET["btn_search"])){
		if(empty($indcode_rsUpP) && empty($indsector_rsUpP) && empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && empty($indsector_rsUpP) && empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && !empty($indsector_rsUpP) && empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_sector = '" . $indsector_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && empty($indsector_rsUpP) && !empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_dept = '" . $inddept_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && empty($indsector_rsUpP) && empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && !empty($indsector_rsUpP) && empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND indsector = '" . $indsector_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && empty($indsector_rsUpP) && !empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "'  AND indicator_dept = '" . $inddept_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && empty($indsector_rsUpP) && empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		} 
		elseif(!empty($indcode_rsUpP) && !empty($indsector_rsUpP) && !empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND indicator_sector = '" . $indsector_rsUpP . "' AND indicator_dept = '" . $inddept_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && !empty($indsector_rsUpP) && empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND indicator_sector = '" . $indsector_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && empty($indsector_rsUpP) && !empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_code = '" . $indcode_rsUpP . "' AND indicator_dept = '" . $inddept_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && !empty($indsector_rsUpP) && !empty($inddept_rsUpP) && empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_sector = '" . $indsector_rsUpP . "' AND indicator_dept = '" . $inddept_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && !empty($indsector_rsUpP) && empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_sector = '" . $indsector_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && !empty($indsector_rsUpP) && !empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_sector = '" . $indsector_rsUpP . "' AND indicator_dept = '" . $inddept_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(empty($indcode_rsUpP) && empty($indsector_rsUpP) && !empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 and indicator_dept = '" . $inddept_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		elseif(!empty($indcode_rsUpP) && !empty($indsector_rsUpP) && !empty($inddept_rsUpP) && !empty($indcat_rsUpP)){
			$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 AND indicator_code = '" . $indcode_rsUpP . "' AND indicator_sector = '" . $indsector_rsUpP . "' AND indicator_dept = '" . $inddept_rsUpP . "' AND indicator_category = '" . $indcat_rsUpP . "' AND active = '1' ORDER BY indid");
		}
		$query_rsAllIndicators->execute();
		$row_rsAllIndicators = $query_rsAllIndicators->fetch();
		$totalRows_rsAllIndicators = $query_rsAllIndicators->rowCount();
	}else{			
		$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 AND active = '1' ORDER BY indid");
		$query_rsAllIndicators->execute();
		$row_rsAllIndicators = $query_rsAllIndicators->fetch();
		$totalRows_rsAllIndicators = $query_rsAllIndicators->rowCount();
	}
	

	$query_rsSector = $db->prepare("SELECT stid, sector FROM  tbl_sectors WHERE parent = '0' AND deleted = '0' ORDER BY stid ASC");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	
	$query_rsDept = $db->prepare("SELECT stid, sector FROM  tbl_sectors WHERE parent != '0' AND deleted = '0' ORDER BY stid ASC");
	$query_rsDept->execute();
	$row_rsDept = $query_rsDept->fetch();
	
	$query_rsCat = $db->prepare("SELECT category FROM  tbl_indicator_categories WHERE active = '1' ORDER BY catid ASC");
	$query_rsCat->execute();
	$row_rsCat = $query_rsCat->fetch();
	
	
	
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
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
	
	<!-- InstanceBeginEditable name="head" -->
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.tabs .tab-links a').on('click', function(e)  {
			var currentAttrValue = jQuery(this).attr('href');
	 
			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
	 
			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
	 
			e.preventDefault();
		});
	});
	</script>

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
    .modal-lg {
        max-width: 100% !important;
        width: 90%;
      }
  </style>
   
</head>

<body class="theme-blue">
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
            <!-- Draggable Handles -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <!-- <div class="body"> -->
							<?php
							include_once('view-normal-indicators-inner.php');
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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>