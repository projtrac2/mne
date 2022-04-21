<?php 
require 'authentication.php';

try{

	

 
	$currentdate = date("Y-m-d");
	$currentyear = date("Y");
	$nextyear = $currentyear + 1;
	$lastyear = $currentyear - 1;
	$currfinyear = $currentyear."/".$nextyear;
	$prevfinyear = $lastyear."/".$currentyear;
	
	$query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$currentyear'");
	$query_crfinyear->execute();
	$row_crfinyear = $query_crfinyear->fetch();
	$crfinyearid = $row_crfinyear["id"];
	
	$query_pvfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$lastyear'");
	$query_pvfinyear->execute();
	$row_pvfinyear = $query_pvfinyear->fetch();
	$pvfinyearid = $row_pvfinyear["id"];
	
	$query_crfinyearalloc = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy inner join tbl_strategic_plan_objectives o on o.id=s.objid inner join tbl_key_results_area k on k.id=o.kraid inner join tbl_strategicplan sp on sp.id=k.spid WHERE p.projfscyear = '$crfinyearid'");
	$query_crfinyearalloc->execute();
	$row_crfinyearalloc = $query_crfinyearalloc->fetch();
	$totalcrfybudget = number_format($row_crfinyearalloc["totalbudget"], 2);
	
	$query_crfinyearamtmain = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_main_funding WHERE financialyear = '$crfinyearid'");
	$query_crfinyearamtmain->execute();
	$row_crfinyearamtmain = $query_crfinyearamtmain->fetch();
	$totalcrfinyearamtmain = $row_crfinyearamtmain["totalamt"];
	
	$query_crfinyearamtothers = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_other_funding WHERE financialyear = '$crfinyearid'");
	$query_crfinyearamtothers->execute();
	$row_crfinyearamtothers = $query_crfinyearamtothers->fetch();
	$totalcrfinyearamtothers = $row_crfinyearamtothers["totalamt"];
	
	$query_crfinyearamtdonors = $db->prepare("SELECT * FROM tbl_donor_grants WHERE financialyear = '$crfinyearid'");
	$query_crfinyearamtdonors->execute();
	$totalcrfinyearamtdonors = 0;
	while($row_crfinyearamtdonors = $query_crfinyearamtdonors->fetch()){
		$donoramount = $row_crfinyearamtdonors["amount"];
		$donorrate = $row_crfinyearamtdonors["exchangerate"];
		$localamount = $donoramount * $donorrate;
		$totalcrfinyearamtdonors = $totalcrfinyearamtdonors + $localamount;
	}
	$totalcrfinyearamount = $totalcrfinyearamtmain + $totalcrfinyearamtothers + $totalcrfinyearamtdonors;
	$totalcrfinyearamt = number_format($totalcrfinyearamount, 2);
	$crfinyearrate = round(($totalcrfinyearamount / $row_crfinyearalloc["totalbudget"]) * 100,1);
	
	$query_pvfinyearalloc = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy inner join tbl_strategic_plan_objectives o on o.id=s.objid inner join tbl_key_results_area k on k.id=o.kraid inner join tbl_strategicplan sp on sp.id=k.spid WHERE p.projfscyear = '$pvfinyearid'");
	$query_pvfinyearalloc->execute();
	$row_pvfinyearalloc = $query_pvfinyearalloc->fetch();
	
	$query_pvfinyearamtmain = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_main_funding WHERE financialyear = '$pvfinyearid'");
	$query_pvfinyearamtmain->execute();
	$row_pvfinyearamtmain = $query_pvfinyearamtmain->fetch();
	$totalpvfinyearamtmain = $row_pvfinyearamtmain["totalamt"];
	
	$query_pvfinyearamtothers = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_other_funding WHERE financialyear = '$pvfinyearid'");
	$query_pvfinyearamtothers->execute();
	$row_pvfinyearamtothers = $query_pvfinyearamtothers->fetch();
	$totalpvfinyearamtothers = $row_pvfinyearamtothers["totalamt"];
	
	$query_pvfinyearamtdonors = $db->prepare("SELECT * FROM tbl_donor_grants WHERE financialyear = '$pvfinyearid'");
	$query_pvfinyearamtdonors->execute();
	$totalpvfinyearamtdonors = 0;
	while($row_pvfinyearamtdonors = $query_pvfinyearamtdonors->fetch()){
		$donoramount = $row_pvfinyearamtdonors["amount"];
		$donorrate = $row_pvfinyearamtdonors["exchangerate"];
		$localamount = $donoramount * $donorrate;
		$totalpvfinyearamtdonors = $totalpvfinyearamtdonors + $localamount;
	}
	$totalpvfinyearamount = $totalpvfinyearamtmain + $totalpvfinyearamtothers + $totalpvfinyearamtdonors;
	$totalpvfinyearamt = number_format($totalpvfinyearamount, 2);
	$pvfinyearrate = round(($totalpvfinyearamount / $row_pvfinyearalloc["totalbudget"]) * 100,1);
	
	$totalpvfybudget = number_format($row_pvfinyearalloc["totalbudget"], 2);
	
	
	if(isset($_GET["plan"]) && !empty($_GET["plan"])){
		$stplan = $_GET["plan"];
	}
	
	if(isset($_GET["obj"]) && !empty($_GET["obj"])){
		$objid = $_GET["obj"];
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
	
	$query_stratobj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id='$objid'");
	$query_stratobj->execute();
	$row_stratobj = $query_stratobj->fetch();
	$objective = $row_stratobj["objective"];
	
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
	
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();	
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
    <title>Result-Based Monitoring &amp; Evaluation System: Tender Information Request</title>
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
	
    <!-- chartist CSS -->
    <link href="assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="assets/plugins/css-chart/css-chart.css" rel="stylesheet">

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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Sources', 'Ksh. (in millions)'],
          ['Own Source', 1300], ['Donors', 830], ['Equitable Share', 8000],
          ['Govn Conditional Grant', 1000], ['Partners Conditional Grant', 4600], ['Others', 1500]
        ]);

        var options = {
          legend: 'none',
          pieSliceText: 'label',
          slices: {  3: {offset: 0.2},
                    5: {offset: 0.3},
                    6: {offset: 0.4},
                    1: {offset: 0.5},
          },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
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
            <div class="block-header bg-blue-grey align-self-center" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-dashboard" aria-hidden="true"></i> STRATEGIC PLAN DASHBOARD
				</h4>
            </div>
            <!-- Draggable Handles -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="info-box bg-lime hover-expand-effect" style="height:130px">
						<div class="icon" style="width:20%">
							<i class="material-icons">verified_user</i>
						</div>
						<div class="content" style="width:80%">
							<div class="text"><?=$prevfinyear?> FINANCIAL YEAR
								<h5 class="font-light" style="color:#673AB7"><i><strong>B:</strong> Ksh.<?=$totalpvfybudget?></i></h5>
								<h5 class="font-light"><i><strong>A:</strong> Ksh.<?=$totalpvfinyearamt?></i></h5>
							</div>
							<span class="text-danger"><?=$pvfinyearrate?>%</span>
							<div class="progress" style="height: 10px">
								<div class="progress-bar bg-primary" role="progressbar" style="width: <?=$pvfinyearrate?>%; height: 10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="info-box bg-light-blue hover-expand-effect" style="height:130px">
						<?php
						if($totalpvfinyearamount > $totalcrfinyearamount){
						?>
						<div class="icon" style="color:red; width:20%">
							<i class="material-icons"><font color="#F44336">trending_down</font></i>
						</div>
						<?php
						}else{
						?>
						<div class="icon" style="color:lime; width:20%">
							<i class="material-icons"><font color="#CDDC39">trending_up</font></i>
						</div>
						<?php
						}
						?>
						<div class="content">
							<div class="text"><?=$currfinyear?> FINANCIAL YEAR
								<h5 class="font-light" style="color:black"><i><strong>B:</strong> Ksh.<?=$totalcrfybudget?></i></h5>
								<h5 class="font-light"><i><strong>A:</strong> Ksh.<?=$totalcrfinyearamt?></i></h5>
							</div>
							<span class="text-danger"><?=$crfinyearrate?>%</span>
							<div class="progress" style="height: 10px">
								<div class="progress-bar bg-lime" role="progressbar" style="width: <?=$crfinyearrate?>%; height: 10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <?php
						include_once('strategic-plan-dashboard-inner.php');
						?>
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
	
    <!--stickey kit -->
    <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!--Custom JavaScript -->
    <script src="assets/js/custom.min.js"></script>

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

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
    <!-- Chart JS -->
    <script src="assets/plugins/echarts/echarts-all.js"></script>
    <script src="assets/plugins/echarts/echarts-init.js"></script>
    <!-- ============================================================== -->
    <script src="assets/js/widget-data.js"></script>
</body>

</html>
