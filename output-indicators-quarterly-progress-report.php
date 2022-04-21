<?php 
require 'authentication.php';

try{	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 
	
	function projfy(){
		GLOBAL $db;
		$projfy = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
		$projfy->execute();
		while ($row = $projfy->fetch()){  
            echo '<option value="'.$row['id'].'">'.$row['year'].'</option>';
        }
	}
	
	$yr = date("Y");
	$mnth = date("m");
	$startmnth = 07;
	$endmnth = 06;

	if($mnth >= 7 && $mnth <= 12){
		$startyear = $yr;
		$endyear = $yr+1;
	} elseif($mnth >= 1 && $mnth <= 6){
		$startyear = $yr - 1;
		$endyear = $yr;
	}
	
	$base_url = "";
	
	//$quarter_dates_arr = ["-07-01", "-09-30", "-10-01", "-12-31", "-01-01", "-03-30", "-04-01","-06-30"];

	$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where yr =:year ");
	$query_rsFscYear->execute(array(":year"=>$startyear));
	$row_rsFscYear = $query_rsFscYear->fetch();

	$fyid = $row_rsFscYear['id'];
	
	if(isset($_GET['btn_search']) and $_GET['btn_search']=="FILTER"){
		$sector = $_GET['sector'];
		$dept = $_GET['department'];
		$fyid = $_GET['indfy'];

		if(!empty($sector) && !empty($fyid)){
			$base_url = "indfy=$fyid&sector=$sector";
		}

		if(!empty($sector) && empty($dept)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector"=>$sector));
		} elseif(!empty($sector) && !empty($dept)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector"=>$sector, ":dept"=>$dept));
		} elseif(!empty($_GET['indfy']) && empty($sector) && empty($dept)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();
			
			$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
			$query_rsFscYear->execute(array(":year"=>$fyid));
			$row_rsFscYear = $query_rsFscYear->fetch();
			$startyear = $row_rsFscYear['yr'];
			$endyear = $startyear + 1;
			
		} elseif(empty($_GET['indfy']) && empty($sector) && empty($dept)){	
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();
		}
	}else{
		$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output'");
		$query_indicators->execute();
	}
	$financialyear = $startyear."/".$endyear;
	$totalRows_indicators = $query_indicators->rowCount();
    
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
	<title>Result-Based Monitoring &amp; Evaluation System</title>
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/project-evaluation-conclusion.css" rel="stylesheet" />

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
	<link href="style.css" rel="stylesheet">
	<script src="ckeditor/ckeditor.js"></script>

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
		
	});
	
		
	function goBack() {
	  window.history.back();
	}
	
	function sectors() {
	  var sector = $("#sector").val();
	  if (sector != "") {
		$.ajax({
		  type: "post",
		  url: "assets/processor/reports-processor",
		  data: { get_dept: sector },
		  dataType: "html",
		  success: function (response) {
			$("#dept").html(response);
		  },
		});
	  }
	}
	</script>
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
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
		
		$("#obj").on("change", function () {
			var objid =$(this).val();
			if(objid !=''){
				$.ajax({
					type: "post",
					url: "addProjectLocation.php",
					data: "objid="+objid,
					dataType: "html",
					success: function (response) {
						$("#output").html(response);
					}
				});
			}else{
				$("#output").html('<option value="">... First Select Strategic Objective ...</option>');
			}
		});
	});

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
		<?php 
			include_once('output-indicators-quarterly-progress-report-inner.php');
		?>
	</section>

	<!-- Bootstrap Core Js -->
	<script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

	<!-- Autosize Plugin Js -->
	<script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

	<!-- Moment Plugin Js -->
	<script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

	<!-- Jquery Spinner Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>

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
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>

</body>

</html>