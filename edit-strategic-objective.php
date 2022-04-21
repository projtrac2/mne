<?php 

require 'authentication.php';

try{		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 
	
    $objid = $_GET['obj']; 
	
	$query_rsObjective = $db->prepare("SELECT o.*, p.starting_year, p.years FROM tbl_strategicplan p INNER JOIN tbl_key_results_area k ON p.id = k.spid inner join tbl_strategic_plan_objectives o on o.kraid=k.id WHERE o.id = :objid");
	$query_rsObjective->execute(array(":objid" => $objid));
	$row_Objective = $query_rsObjective->fetch();
	$row_Objectivecount = $query_rsObjective->rowCount();
	
    $kpi =$row_Objective['kpi'];

    $query_rsKPI = $db->prepare("SELECT * FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid = '$kpi'");
    $query_rsKPI->execute();
    $row_rsKPI = $query_rsKPI->fetch(); 
    $KPI =$row_rsKPI['indicator_name']; 
    $unit =$row_rsKPI['unit'];

	$startyear = $row_Objective['starting_year'];
	$years = $row_Objective['years'];
	$objdesc = $row_Objective['description'];
	$styear  = $startyear;
	$stryear = $startyear;
	
    $query_strategies = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = :objid");
    $query_strategies->execute(array(":objid" => $objid));
      
    $query_kpis = $db->prepare("SELECT * FROM tbl_indicator where indicator_type=1 and active='1'");
    $query_kpis->execute();
    $row_kpis =$query_kpis->fetch();

    $query_KPI = $db->prepare("SELECT * FROM tbl_kpi");
    $query_KPI->execute();
    $row_KPI =$query_KPI->fetch();

	$kraid = $row_Objective['kraid'];
	$query_rskra = $db->prepare("SELECT * FROM tbl_key_results_area WHERE id=:kraid");
	$query_rskra->execute(array(":kraid" => $kraid));
	$row_rsKra = $query_rskra->fetch();
	$totalRows_rskra = $query_rskra->rowCount(); 
    
 
	$current_date = date("Y-m-d");
	// $user = $_POST['username'];
	$user = $user_name;

	if (isset($_POST['editplan'])) {
		$objid = $_POST['objid'];
		$objective = $_POST['objective'];
		$desc = $_POST['objdesc'];
		$kpi = $_POST['kpi'];
		$kraid = $_POST['kraid']; 
		
		$ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, description=:desc, kpi=:kpi, created_by=:user, date_created=:dates WHERE id=:objid");
		$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":desc" => $desc, ":kpi" => $kpi, ":user" => $user, ":dates" => $current_date, ":objid" => $objid));

		if ($resultObjectives){ 
			if(count($_POST['target']) > 0){
				$sqldelete = $db->prepare("DELETE from tbl_strategic_plan_objective_targets WHERE objid=:objid");
				$result = $sqldelete->execute(array(":objid" => $objid));
				
				if($result){				
					$thresholdyr=0;
					$countyr = count($_POST["targetyr"]);
					for ($j = 0; $j < $countyr; $j++) {
						$thresholdyr++;
						$targetyear = $_POST['targetyr'][$j];
						$target = $_POST['target'][$j];
						$sqlinsert = $db->prepare("INSERT INTO tbl_strategic_plan_objective_targets (objid, year, target) VALUES (:objid, :year, :target)");
						$iresult = $sqlinsert->execute(array(":objid" => $objid, ":year" => $targetyear, ":target" => $target));
						
						if ($iresult){ 
							$targetid = $targetyear;
							
							$sqldelete = $db->prepare("DELETE from tbl_strategic_objective_targets_threshold WHERE objid=:objid");
							$sqldelete->execute(array(":objid" => $objid));
							
							$thdcount = count($_POST["thresholdyr".$thresholdyr]);
							for ($k = 0; $k < $thdcount; $k++) {
								$threshold = $_POST["thresholdyr".$thresholdyr][$j][$k];
								$sqlinsert = $db->prepare("INSERT INTO tbl_strategic_objective_targets_threshold (objid, year, threshold) VALUES (:objid, :year, :threshold)");
								$sqlinsert->execute(array(":objid" => $objid, ":year" => $targetid, ":threshold" => $threshold));
							}
						}
					}
				}
			}
			
			$stcount = count($_POST["strategic"]);
			if($stcount > 0){
				$query_delete = $db->prepare("DELETE FROM tbl_objective_strategy WHERE objid=:objid");
				$query_delete->execute(array(":objid" => $objid));
				
				for ($cnt = 0; $cnt < $stcount; $cnt++) {
					$strategy = $_POST['strategic'][$cnt];
					$sqlinsert = $db->prepare("INSERT INTO tbl_objective_strategy (objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
					$sqlinsert->execute(array(":objid" => $objid, ":strategy" => $strategy, ":user" => $user, ":dates" => $current_date));
				}
			}
			
			$msg = 'Strategic Objective successfully updated';
			if(isset($_POST['update'])){
				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 3000,
					showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'view-strategic-plan-objectives?kra=$kraid';
					}, 3000);
				</script>";
			}
		} 
	}
 
}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
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
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
		type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/strategy-style.css" rel="stylesheet" />

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
    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
	</script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="style.css" rel="stylesheet">
	<script src="ckeditor/ckeditor.js"></script>
	<script language='JavaScript' type='text/javascript' src='JScript/CalculatedFields.js'></script>
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  </style>
	<script>
		$(document).ready(function() {
			// get department
			$('#kpiindicator').on('change', function() {
				$("#sobjtarget").show();
				var kpiindicator = $(this).val();
				if (kpiindicator) {
					$.ajax({
						type: "post",
						url: "assets/processor/add-obj-process.php",
						data: {
							kpiindid: kpiindicator
						},
						dataType: "html",
						success: function(response) {
							$("#kpiunit").val(response);
							$("#kpiunty2").html(response);
						}
					});
				}
			});
		
			$('#kpiindicator').on('change', function() {
				var kraid = $('#kraid').val();
				console.log(kraid);
				if (kraid) {
					$.ajax({
						type: "post",
						url: "assets/processor/add-obj-process.php",
						data: {
							kraid: kraid
						},
						dataType: "html",
						success: function(response) {
							$("#objtargets").html(response);
						}
					});
				} else {
					$("#objtargets").html('<strong style="color:#F44336">Please select KPI first</strong>');
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

	<?php include_once('edit-strategic-objective-inner.php') ?>

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


	<!-- Bootstrap Material Datetime Picker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
	</script>

	<!-- Bootstrap Datepicker Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

	<!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script> -->
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>

	<!-- validation cdn files  -->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>

	<script src="add-strategic-plan.js"></script>
</body>

</html>