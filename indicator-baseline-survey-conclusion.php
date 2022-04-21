<?php 

require 'authentication.php';

try{	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 
	
	if(isset($_GET["ind"]) && !empty($_GET["ind"])){
		$indid = $_GET["ind"];
	}
	
	if(isset($_GET["fm"]) && !empty($_GET["fm"])){
		$formid = $_GET["fm"];
	}
	
	$query_formdetails =  $db->prepare("SELECT * FROM tbl_indicator i inner join tbl_indicator_baseline_survey_forms f on f.indid=i.indid inner join tbl_measurement_units m on m.id=i.unit WHERE i.surveystatus=3 and i.baseline=0 and (f.status=2 or f.status=3) and i.indid='$indid' and f.id='$formid'");
	$query_formdetails->execute();
	$row_formdetails = $query_formdetails->fetch();	
	
	$current_date = date("Y-m-d");
	
	if (isset($_POST['submit'])) {
		$conclusion = $_POST['conclusion'];
		$recommendation = $_POST['recommendation'];
		if(!empty($conclusion)){
			$user = $_POST['username'];
			$indid = $_POST['indid'];
			$formid = $_POST['formid'];
			
			$formInsert = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_conclusion (indid, formid, conclusion, recommendation, user, date) VALUES (:indid, :formid, :conclusion, :recommendation, :user, :date)");
			$resultform = $formInsert->execute(array(":indid" => $indid, ":formid" => $formid, ":conclusion" => $conclusion, ":recommendation" => $recommendation, ":user" => $user, ":date" => $current_date));
			//var_dump("YES");
			if($resultform){
				//$projstage = 3 for process evaluation; 4 for rapid evaluation; 5 for outcome evaluation 
				$svystatus = 4;
				$formstatus = 4;
				$baseline = 1;
				
				$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid");
				$query_formstatusupdate->execute(array(":formid" => $formid, ":formstatus" => $formstatus));
				
				$updatequery = $db->prepare("UPDATE tbl_indicator SET baseline=:baseline, surveystatus=:surveystatus WHERE indid=:indid");
				$updateresult = $updatequery->execute(array(":baseline" => $baseline, ":surveystatus" => $svystatus, ":indid" => $indid));
				
				if($updateresult){
					$msg = 'Data successfully submitted.';
					$recommendationresults = "<script type=\"text/javascript\">
						swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 3000,
						showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'indicator-baseline-survey';
						}, 3000);
					</script>";	
				}else{
					$msg = "Error while saving your information!!!";
					$recommendationresults = "<script type=\"text/javascript\">
						swal({
							title: \"Warning\",
							text: \" $msg \",
							icon: 'warning',
							buttons: false,
							dangerMode: true,
							timer: 3000,
							showConfirmButton: false 
						});
					</script>";
				}
			}
		}else{
			$msg = "Please fill all fields";
			$recommendationresults = "<script type=\"text/javascript\">
				swal({
					title: \"Warning\",
					text: \" $msg \",
					icon: 'warning',
					buttons: false,
					dangerMode: true,
					timer: 3000,
					showConfirmButton: false 
				});
			</script>";
		}
	}
	
	$indicator = $row_formdetails["indname"];	
	$indcategory = $row_formdetails["indcategory"];	
	$indunit = $row_formdetails["unit"];
	$indunitdesc = $row_formdetails["description"];
	
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();		

	$query_surveyobjs =  $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE formid = '$formid'");
	$query_surveyobjs->execute();		
	$totalRows_surveyobjs = $query_surveyobjs->rowCount();	

	$query_surveysummary =  $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE formid = '$formid'");
	$query_surveysummary->execute();		
	$totalRows_surveysummary = $query_surveysummary->rowCount();			

	//get the submission number and date 
	$query_rsSubmission = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid'");
	$query_rsSubmission->execute();
	$totalRows_rsSubmission = $query_rsSubmission->rowCount();

	//get the location and date 
	$query_rsSubLoc = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission s inner join tbl_state t on t.id=s.level3id WHERE indid='$indid' AND formid='$formid' GROUP BY level3id");
	$query_rsSubLoc->execute();
	$totalRows_rsSubLoc = $query_rsSubLoc->rowCount();
	
	///////////////////////////////////////////
	//Query the project name and form Name 
	///////////////////////////////////////////
	$query_rsFormDetails = $db->prepare("SELECT f.form_name, i.indname, i.indcategory FROM `tbl_indicator_baseline_survey_submission` s INNER JOIN tbl_indicator_baseline_survey_forms f ON f.id=s.formid INNER JOIN tbl_indicator i ON i.indid =s.indid WHERE  s.indid=:indid AND s.formid=:formid GROUP BY i.indname");
	$query_rsFormDetails->execute(array(":indid" => $indid, ":formid" => $formid));
	$row_rsFormDetails = $query_rsFormDetails->fetch();
	$totalRows_rsFormDetails = $query_rsFormDetails->rowCount();

	/////////////////////////////////////////////
	//	Query section for the answers 
	/////////////////////////////////////////////
	$query_rsSection = $db->prepare("SELECT o.id, o.section  FROM tbl_indicator_baseline_survey_answers a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id=a.fieldid INNER JOIN tbl_indicator_baseline_survey_form_sections o ON o.id =q.sectionid WHERE o.formid=:formid GROUP BY q.sectionid ");
	$query_rsSection->execute(array(":formid" => $formid));
	$row_rsSection = $query_rsSection->fetchAll();
	$totalRows_rsSection = $query_rsSection->rowCount();
	
	///////////////////////////////////////////
	//	Query the form sections 
	///////////////////////////////////////////
	$query_rsSections = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE  formid=:formid");
	$query_rsSections->execute(array(":formid" => $formid));
	$row_rsSections = $query_rsSections->fetchAll();
	$totalRows_rsSections = $query_rsSections->rowCount();
	
	$query_rsForm = $db->prepare("SELECT indid FROM tbl_indicator_baseline_survey_forms WHERE id='$formid'");
	$query_rsForm->execute();
	$row_rsForm = $query_rsForm->fetch();
	
	$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indid' and active='1'");
	$query_rsIndicator->execute();
	$row_rsIndicator = $query_rsIndicator->fetch();
	
	$query_rsOpDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' AND deleted='0'");
	$query_rsOpDept->execute();
	$row_rsOpDept = $query_rsOpDept->fetch();
	
	$query_baseyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
	$query_baseyear->execute();
	
	$current_datetime = date("Y-m-d H:i:s");
	while($row_baseyear = $query_baseyear->fetch()){
		$sdate = date("Y-m-d H:i:s", strtotime($row_baseyear["sdate"]));
		$edate = date("Y-m-d H:i:s", strtotime($row_baseyear["edate"]));
		if($sdate <= $current_datetime && $edate >= $current_datetime){
			$baseyear = $row_baseyear["id"];
			$baseyr = $row_baseyear["year"];
			
		}
	}
	
	

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
    <link href="css/indicator-baseline-survey-conclusion.css" rel="stylesheet" />

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
	<script src="evaluation-process-conclusion.js"></script>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}  
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>			
	<script type="text/javascript">
	$(document).ready(function(){
		$('#baselinesurveyForm').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				type: "post",
				url: "indicator-baseline-survey-form-processing.php",
				data: $(this).serialize(),
				dataType: "html",
				success: function (response) {
					if(response){
						$("#baselineupdate").html(response);
					}
				}
			});
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
			include_once('indicator-baseline-survey-conclusion-inner.php');
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