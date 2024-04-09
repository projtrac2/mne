<?php 
try{	

require 'authentication.php';

	if (isset($_GET['srcsct'])) {
	  $colsct_rsUpP = $_GET['srcsct'];
	}

	if (isset($_GET['srctype'])) {
	  $ptype_rsUpP = $_GET['srctype'];
	}

	if (isset($_GET['srccomm'])) {
	  $pcomm_rsUpP = $_GET['srccomm'];
	}

	if (isset($_GET['srcfyear'])) {
	  $pfy_rsUpP = $_GET['srcfyear'];
	}

	if (isset($_GET['srcstate'])) {
	  $pstate_rsUpP = $_GET['srcstate'];
	}

	if (isset($_GET['srcfyear']) || isset($_GET['srcsct']) || isset($_GET['srcdept']) || isset($_GET['srccomm']) || isset($_GET['srcstate']) || isset($_GET['srcstatus']) || isset($_GET['srctype'])){
		$query_rsUpP = $db->prepare("SELECT p.*, FORMAT(p.projcost, 2), p.projstartdate AS stdate, p.projenddate AS endate FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE g.projsector LIKE '%" . $colsct_rsUpP . "' AND p.projfscyear LIKE '%" . $pfy_rsUpP . "' AND p.projcommunity LIKE '%" . $pcomm_rsUpP . "%' AND p.projlga LIKE '%" . $pstate_rsUpP . "%' AND p.deleted = '0' ORDER BY p.projid DESC");
	}else{
		$query_rsUpP =  $db->prepare("SELECT p.*, FORMAT(p.projcost, 2), p.projstartdate AS stdate, p.projenddate AS endate FROM tbl_projects p WHERE p.deleted = '0' ORDER BY p.projid DESC");	
	}
	$query_rsUpP->execute();		
	$row_rsUpP = $query_rsUpP->fetch();

	$query_rsFY = $db->prepare("SELECT DISTINCT projfscyear FROM tbl_projects WHERE deleted = '0' ORDER BY projfscyear ASC");
	$query_rsFY->execute();		
	$row_rsFY = $query_rsFY->fetch();

	$query_rsSCT = $db->prepare("SELECT DISTINCT projsector FROM tbl_programs WHERE deleted = '0' ORDER BY projsector ASC");
	$query_rsSCT->execute();		
	$row_rsSCT = $query_rsSCT->fetch();

	$query_rsPName = $db->prepare("SELECT projname FROM tbl_projects WHERE deleted = '0' ORDER BY projname ASC");
	$query_rsPName->execute();		
	$row_rsPName = $query_rsPName->fetch();
	$totalRows_rsPName = $query_rsPName->rowCount();

	$query_rsComm = $db->prepare("SELECT id, state FROM tbl_projects p inner join tbl_state s on s.id=p.projcommunity WHERE p.deleted = '0' group by p.projcommunity ORDER BY s.state ASC");
	$query_rsComm->execute();		
	$row_rsComm = $query_rsComm->fetch();
	$totalRows_rsComm = $query_rsComm->rowCount();

	$query_rsWards = $db->prepare("SELECT id, state FROM tbl_projects p inner join tbl_state s on s.id=p.projlga WHERE p.deleted = '0' group by p.projlga ORDER BY s.state ASC");
	$query_rsWards->execute();		
	$row_rsWards = $query_rsWards->fetch();
	$totalRows_rsWards = $query_rsWards->rowCount();

	$query_rsState = $db->prepare("SELECT DISTINCT projstate FROM tbl_projects WHERE deleted = '0'");
	$query_rsState->execute();		
	$row_rsState = $query_rsState->fetch();
	$totalRows_rsState = $query_rsState->rowCount();

	$query_rsPType = $db->prepare("SELECT DISTINCT projtype FROM tbl_projects WHERE deleted = '0' ORDER BY projtype ASC");
	$query_rsPType->execute();		
	$row_rsPType = $query_rsPType->fetch();
	$totalRows_rsPType = $query_rsPType->rowCount();

	if (isset($_GET['srccost1'])) {
	  $colname_rsCost = $_GET['srccost1'];
	}
	
	if (isset($_GET['srccost2'])) {
	  $scrcost2_rsCost = $_GET['srccost2'];
	}

	$query_rsCost = $db->prepare("SELECT p.*, FORMAT(p.projcost, 2), DATE_FORMAT( p.projstartdate,  '%%d %%M %%Y' ) AS stdate, DATE_FORMAT( p.projenddate,  '%%d %%M %%Y' ) AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE (p.projcost >= '$colname_rsCost' AND p.projcost <= '$scrcost2_rsCost') OR (p.projcost >= '$colname_rsCost')  OR (p.projcost <= '$scrcost2_rsCost') ORDER BY p.projid DESC");
	$query_rsCost->execute();		
	$row_rsCost = $query_rsCost->fetch();
	$totalRows_rsCost = $query_rsCost->rowCount();

	$queryString_rsMyP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_rsMyP") == false && stristr($param, "totalRows_rsMyP") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
		}
	}

	$queryString_rsUpP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_rsUpP") == false && stristr($param, "totalRows_rsUpP") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsUpP = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_rsUpP = sprintf("&totalRows_rsUpP=%d%s", $totalRows_rsUpP, $queryString_rsUpP);
	
	

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
    <title>Projtrac M&E - General Report</title>
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
			<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header">
                            <div class="button-demo">
								<span class="label bg-black" style="font-size:19px"><img src="images/proj-icon.png" alt="img" style="vertical-align:middle" /> Reports Menu</span>
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">General Report</a>
                                <a href="view-workplan-reports" class="btn bg-light-blue waves-effect" style="margin-top:10px">Outputs Progress Report</a>
                                <a href="projfundingreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Financial Report</a>
                                <a href="projpendingbillsreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Pending Bills</a>
							</div>
						</div>
				</div>
            </div>
            <div class="block-header">
				<?php 
				if(isset($_GET["msg"]) && $_GET["type"] == "fail"){
				?>
					<div class="alert alert-warning">
						<strong>Warning!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				}
				elseif(isset($_GET["msg"]) && $_GET["type"] == "success"){
				?>
					<div class="alert alert-success">
						<strong>Success!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				}
				?>
            </div>
            <!-- Exportable Table -->
					<?php  include_once('projgeneralreport-inner.php');?>
            <!-- #END# Exportable Table -->
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

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

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
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>