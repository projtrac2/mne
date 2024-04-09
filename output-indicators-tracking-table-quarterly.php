<?php 
try{	

require 'authentication.php';

		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	 
	if(isset($_GET["plan"]) && !empty($_GET["plan"])){
		$stplan = $_GET["plan"];
	}
	
	if(isset($_GET["obj"]) && !empty($_GET["obj"])){
		$objid = $_GET["obj"];
		$query_stratobj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id='$objid'");
		$query_stratobj->execute();
		$row_stratobj = $query_stratobj->fetch();
		$objectiv = $row_stratobj["objective"];
	}
	
	if(isset($_GET["op"]) && !empty($_GET["op"])){
		$opid = $_GET["op"];
	
		$query_rsOp = $db->prepare("SELECT output,indicator_name FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.indicator='$opid'");
		$query_rsOp->execute();
		$row_rsOp = $query_rsOp->fetch();
		$specoutput = $row_rsOp["output"];
		$indicator = $row_rsOp["indicator_name"];
	}
	
	if(isset($_GET["fy"]) && !empty($_GET["fy"])){
		$fyid = $_GET["fy"];
	}
	
	$current_date = date("Y-m-d");

	$q1startdate = "07-01";
	$q1enddate = "09-30";
	$q2startdate = "10-01";
	$q2enddate = "12-31";
	$q3startdate = "01-01";
	$q3enddate = "03-30";
	$q4startdate = "04-01";
	$q4enddate = "06-30";
	
	$query_rsFy = $db->prepare("SELECT year, yr FROM tbl_fiscal_year WHERE id='$fyid'");
	$query_rsFy->execute();
	$row_rsFy = $query_rsFy->fetch();
	$fiscalyear = $row_rsFy["year"];
	$financialyear = $row_rsFy["yr"];
	$financialyear12 = $financialyear;
	$financialyear34 = $financialyear + 1;
	
	$q1sdate = $financialyear12."-".$q1startdate;
	$q1edate = $financialyear12."-".$q1enddate;
	$q2sdate = $financialyear12."-".$q2startdate;
	$q2edate = $financialyear12."-".$q2enddate;
	$q3sdate = $financialyear34."-".$q3startdate;
	$q3edate = $financialyear34."-".$q3enddate;
	$q4sdate = $financialyear34."-".$q4startdate;
	$q4edate = $financialyear34."-".$q4enddate;
	
	$query_stratplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan'");
	$query_stratplan->execute();
	$row_stratplan = $query_stratplan->fetch();
	$totalRows_stratplan = $query_stratplan->rowCount();
	
	$plan = $row_stratplan["plan"];	
	$vision = $row_stratplan["vision"];	
	$mission = $row_stratplan["mission"];
	$years = $row_stratplan["years"];
	$finyear = $row_stratplan["starting_year"] - 1;
	$fnyear = $row_stratplan["starting_year"] - 1;
	
	$query_fyobj = $db->prepare("SELECT o.id AS objid, o.objective AS obj FROM tbl_key_results_area k inner join tbl_strategic_plan_objectives o on k.id=o.kraid WHERE k.spid = '$stplan'");
	$query_fyobj->execute();
	$totalRows_fyobj = $query_fyobj->rowCount();
	
	$query_obj = $db->prepare("SELECT o.id AS objid, o.objective AS obj FROM tbl_key_results_area k inner join tbl_strategic_plan_objectives o on k.id=o.kraid WHERE k.spid = '$stplan'");
	$query_obj->execute();
	$totalRows_obj = $query_obj->rowCount();	


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
		include_once('output-indicators-tracking-table-quarterly-inner.php');
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

<?php 

}catch (PDOException $th){
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>