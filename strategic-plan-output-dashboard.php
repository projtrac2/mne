<?php 
try{

require 'authentication.php';


	
 
	$currentdate = date("Y-m-d");
	$currentyear = date("Y");
	$nextyear = $currentyear + 1;
	$lastyear = $currentyear - 1;
	$currfinyear = $currentyear."/".$nextyear;
	$prevfinyear = $lastyear."/".$currentyear;
	
	$query_currplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
	$query_currplan->execute();
	$row_currplan = $query_currplan->fetch();
	$currplan = $row_currplan["id"];
	
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

	
	$query_obj = $db->prepare("SELECT o.id AS id, objective FROM tbl_key_results_area k inner join tbl_strategic_plan_objectives o on k.id=o.kraid WHERE k.spid = '$currplan'");
	$query_obj->execute();
	$totalRows_obj = $query_obj->rowCount();
	
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();
	
	$query_finyears = $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$currplan'");
	$query_finyears->execute();
	$row_finyears = $query_finyears->fetch();
	$totalRows_finyears = $query_finyears->rowCount();
	
	$plan = $row_finyears["plan"]; 
	$noyears = $row_finyears["years"];
	$fnyear = $row_finyears["starting_year"];

	$fncyears =array();
	$dataset1 =array();
	$dataset2 =array();
	$dataset3 =array();
	$dataset4 =array();
	
	for($i=0; $i<$noyears; $i++){
		$Financialyear =$fnyear +$i;
		
		$query_financialyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$Financialyear'");
		$query_financialyear->execute();
		$rows_financialyear = $query_financialyear->fetch();
		$fnyearid = $rows_financialyear["id"];
	
		//===============START DATA FILTERING FROM HERE================//
		$obj = $_GET['obj'];
		$output = $_GET['output'];
		
		if(!empty($obj) && !empty($output)){
			
			$query_objoutput =  $db->prepare("SELECT output, indname FROM tbl_expprojoutput e inner join tbl_outputs o on o.opid=e.expoutputname inner join tbl_indicator i on i.indid=e.expoutputindicator WHERE o.opid='$output'");
			$query_objoutput->execute();
			$row_objoutput = $query_objoutput->fetch();
			
			$query_objname =  $db->prepare("SELECT objective FROM tbl_strategic_plan_objectives WHERE id='$obj'");
			$query_objname->execute();
			$row_objname = $query_objname->fetch();
			
			$objname = $row_objname["objective"];
			$objoutput = $row_objoutput["output"];
			$objopind = $row_objoutput["indname"];
		
			$query_targetop = $db->prepare("SELECT sum(o.expoutputvalue) AS target FROM tbl_expprojoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE o.expoutputname = '$output' AND p.projfscyear = '$fnyearid' AND s.objid='$obj'");
			$query_targetop->execute();
			
			while($rows = $query_targetop->fetch()){	
				extract($rows);
				$dataset1[] = (int)$target;	
			}
			
			$query_achievedop = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE o.opid = '$output' AND p.projfscyear = '$fnyearid' AND s.objid='$obj'");
			$query_achievedop->execute();
			
			while($rows = $query_achievedop->fetch()){	
				extract($rows);
				$dataset2[] = (int)$achieved;	
			}
			
			$query_budget = $db->prepare("SELECT sum(p.projcost) AS pbudget FROM tbl_expprojoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE o.expoutputname = '$output' AND p.projfscyear = '$fnyearid' AND s.objid='$obj'");
			$query_budget->execute();
			
			while($rows = $query_budget->fetch()){	
				extract($rows);
				$dataset3[] = (int)$pbudget / 1000000;	
			}
			
			$query_cost = $db->prepare("SELECT sum(d.amountpaid) AS pcost FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid inner join tbl_expprojoutput o on o.projid=p.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE o.expoutputname = '$output' AND p.projfscyear = '$fnyearid' AND s.objid='$obj'");
			$query_cost->execute();
			
			while($rows = $query_cost->fetch()){	
				extract($rows);
				$dataset4[] = (int)$pcost / 1000000;	
			}
		}else{
			$query_targetop = $db->prepare("SELECT sum(o.expoutputvalue) AS target FROM tbl_expprojoutput o inner join tbl_projects p on p.projid=o.projid WHERE p.projfscyear = '$fnyearid'");
			$query_targetop->execute();
			
			while($rows = $query_targetop->fetch()){	
				extract($rows);
				$dataset1[] = (int)$target;	
			}
			
			$query_achievedop = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid WHERE p.projfscyear = '$fnyearid'");
			$query_achievedop->execute();
			
			while($rows = $query_achievedop->fetch()){	
				extract($rows);
				$dataset2[] = (int)$achieved;	
			}
			
			$query_budget = $db->prepare("SELECT sum(p.projcost) AS pbudget FROM tbl_expprojoutput o inner join tbl_projects p on p.projid=o.projid WHERE p.projfscyear = '$fnyearid'");
			$query_budget->execute();
			
			while($rows = $query_budget->fetch()){	
				extract($rows);
				$dataset3[] = (int)$pbudget / 1000000;	
			}
			
			$query_cost = $db->prepare("SELECT sum(d.amountpaid) AS pcost FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid inner join tbl_expprojoutput o on o.projid=p.projid WHERE p.projfscyear = '$fnyearid'");
			$query_cost->execute();
			
			while($rows = $query_cost->fetch()){	
				extract($rows);
				$dataset4[] = (int)$pcost / 1000000;	
			}
		}
		
		//===============END DATA FILTERING FROM HERE================//
		
		if($i == ($noyears - 1)){
			$Financialyear = $Financialyear;
		}else{
			$Financialyear = $Financialyear.",";
		}
		array_push($fncyears, $Financialyear);
	}	
	$dtset1 = json_encode($dataset1);
	$dtset2 = json_encode($dataset2);
	$dtset3 = json_encode($dataset3);
	$dtset4 = json_encode($dataset4);
}
catch (PDOException $th){
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

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
        <div class="container-fluid">
            <div class="block-header bg-blue-grey align-self-center" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-dashboard" aria-hidden="true"></i> STRATEGIC PLAN OUTPUT DASHBOARD
				</h4>
            </div>
            <!-- Draggable Handles -->
            <!-- Advanced Select -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                USE BELOW SELECTION TO FILTER THE DASHBOARD 
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
								<form id="searchform" name="searchform" method="get" style="margin-top:5px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
									<div class="col-md-4">
										<select name="obj" id="obj" class="form-control show-tick" data-live-search="true" required>
											<option value="" selected="selected" >Select Strategic Objective</option>
											<?php
											while ($row = $query_obj->fetch()){  
											?>
												<option value="<?php echo $row['id']?>"><?php echo $row['objective']?></option>
											<?php
											}
											?>
										</select>
									</div>
									<div class="col-md-4">
										<select name="output" id="output" class="form-control show-tick" data-live-search="true" id="projsubcouty" required>
											<option value="" selected="selected" >Select Strategic Objective Output</option>
										</select>
									</div>
									<div class="col-md-2">
										<input type="submit" class="btn btn-primary"name="btn_search" id="btn_search" value="FILTER" />
										<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='strategic-plan-output-dashboard'" id="btnback">
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Filters -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <?php
						include_once('strategic-plan-output-dashboard-inner.php');
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
    <script src="projtrac-dashboard/js/admin2.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
    <!-- Chart JS -->
    <script src="assets/plugins/echarts/echarts-all.js"></script>
    
	<script type="text/javascript">
	// ============================================================== 
	// Bar chart option
	// ============================================================== 
	var myChart = echarts.init(document.getElementById('bar-chart'));

	// specify chart configuration item and data
	option = {
		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data: ['Planned Output', 'Achieved Output']
		},
		toolbox: {
			show: true,
			feature: {

				magicType: { show: true, type: ['line', 'bar'] },
				restore: { show: true },
				saveAsImage: { show: true }
			}
		},
		color: ["#009efb", "#55ce63"],
		calculable: true,
		xAxis: [{
			type: 'category',
			data: [<?php 
					foreach($fncyears as $key=>$value){ 
						echo $value;
					}
				?>]
		}],
		yAxis: [{
			type: 'value'
		}],
		series: [{
				name: 'Planned Output',
				type: 'bar',
				data: <?php echo $dtset1; ?>,
				markPoint: {
					data: [
						{ type: 'max', name: 'The highest'},
						{ type: 'min', name: 'The Lowest'}
					]
				},
				markLine: {
					data: [
						{ type: 'average', name: 'Average' }
					]
				}
			},
			{
				name: 'Achieved Output',
				type: 'bar',
				data: <?php echo $dtset2; ?>,
				markPoint: {
					data: [
						{ type: 'max', name: 'The highest'},
						{ type: 'min', name: 'The Lowest'}
					]
				},
				markLine: {
					data: [
						{ type: 'average', name: 'Average' }
					]
				}
			}
		]
	};


	// use configuration item and data specified to show chart
	myChart.setOption(option, true), $(function() {
		function resize() {
			setTimeout(function() {
				myChart.resize()
			}, 100)
		}
		$(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
	});

	// ============================================================== 
	// Line chart
	// ============================================================== 
	var dom = document.getElementById("main");
	var mytempChart = echarts.init(dom);
	var app = {};
	option = null;
	option = {

		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data: ['Budget', 'Cost']
		},
		toolbox: {
			show: true,
			feature: {
				magicType: { show: true, type: ['line', 'bar'] },
				restore: { show: true },
				saveAsImage: { show: true }
			}
		},
		color: ["#009efb", "#55ce63"],
		calculable: true,
		xAxis: [{
			type: 'category',

			boundaryGap: false,
			data: [<?php 
					foreach($fncyears as $key=>$value){ 
						echo $value;
					}
				?>]
		}],
		yAxis: [{
			type: 'value',
			axisLabel: {
				formatter: 'Ksh. {value}M'
			}
		}],

		series: [{
				name: 'Budget',
				type: 'line',
				color: ['#000'],
				data:  <?php echo $dtset3; ?>,
				markPoint: {
					data: [
						{ type: 'max', name: 'Highest Budget (M)' },
						{ type: 'min', name: 'Lowest Budget (M)' }
					]
				},
				itemStyle: {
					normal: {
						lineStyle: {
							shadowColor: 'rgba(0,0,0,0.3)',
							shadowBlur: 10,
							shadowOffsetX: 8,
							shadowOffsetY: 8
						}
					}
				},
				markLine: {
					data: [
						{ type: 'average', name: 'Average' }
					]
				}
			},
			{
				name: 'Cost',
				type: 'line',
				data:  <?php echo $dtset4; ?>,
				markPoint: {
					data: [
						{ type: 'max', name: 'Highest Cost (M)' },
						{ type: 'min', name: 'Lowest Cost (M)' }
					]
				},
				itemStyle: {
					normal: {
						lineStyle: {
							shadowColor: 'rgba(0,0,0,0.3)',
							shadowBlur: 10,
							shadowOffsetX: 8,
							shadowOffsetY: 8
						}
					}
				},
				markLine: {
					data: [
						{ type: 'average', name: 'Average' }
					]
				}
			}
		]
	};

	if (option && typeof option === "object") {
		mytempChart.setOption(option, true), $(function() {
			function resize() {
				setTimeout(function() {
					mytempChart.resize()
				}, 100)
			}
			$(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
		});
	}

	</script>
    <!-- ============================================================== -->
    <script src="assets/js/widget-data.js"></script>
</body>

</html>
