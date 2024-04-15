<?php 
try{	

require 'authentication.php';

		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if(isset($_GET["proj"]) && !empty($_GET["proj"])){
		$projid = $_GET["proj"];
	}
	
	if(isset($_GET["eval"]) && !empty($_GET["eval"])){
		$evalid = $_GET["eval"];
	}
	
	$query_formdetails =  $db->prepare("SELECT f.id, p.projname, p.projdesc, p.projcategory, p.projcost, e.evaluation_type FROM tbl_projects p inner join tbl_projects_evaluation e on e.projid=p.projid inner join tbl_project_evaluation_forms f on f.evaluation_id=e.id WHERE e.id='$evalid' and e.status=3 and f.status=2");
	$query_formdetails->execute();
	$row_formdetails = $query_formdetails->fetch();
	$formid = $row_formdetails["id"];
	$evaltype = $row_formdetails["evaluation_type"];	
	
	$current_date = date("Y-m-d");
	
	if (isset($_POST['submit'])) {
		$conclusion = $_POST['conclusion'];
		$recommendation = $_POST['recommendation'];
		if(!empty($conclusion)){
			$user = $_POST['username'];
			$projid = $_POST['projid'];
			$formid = $_POST['formid'];
			
			$formInsert = $db->prepare("INSERT INTO tbl_project_evaluation_conclusion (projid, formid, conclusion, recommendation, user, date) VALUES (:projid, :formid, :conclusion, :recommendation, :user, :date)");
			$resultform = $formInsert->execute(array(":projid" => $projid, ":formid" => $formid, ":conclusion" => $conclusion, ":recommendation" => $recommendation, ":user" => $user, ":date" => $current_date));
			//var_dump("YES");
			if($resultform){
				//$projstage = 3 for process evaluation; 4 for rapid evaluation; 5 for outcome evaluation 
				$evalstatus = 4;
				$formstatus = 3;
				$projevaluate = 0;
				$projmestage = 1;
				$query_formstatusupdate = $db->prepare("UPDATE tbl_project_evaluation_forms SET status=:formstatus WHERE id=:formid");
				$query_formstatusupdate->execute(array(":formid" => $formid, ":formstatus" => $formstatus));
				
				$query_projstatusupdate = $db->prepare("UPDATE tbl_projects_evaluation SET status=:status WHERE id=:evalid");
				$query_projstatusupdate->execute(array(":status" => $evalstatus, ":evalid" => $evalid));
				
				if($evaltype==3){
					$query_projstatusupdate = $db->prepare("UPDATE tbl_projects SET projmestage=:projmestage WHERE projid=:projid");
					$query_projstatusupdate->execute(array(":projmestage" => $projmestage, ":projid" => $projid));
				} else{
					$query_projstatusupdate = $db->prepare("UPDATE tbl_projects SET projmestage=:projmestage, projevaluate=:projevaluate WHERE projid=:projid");
					$query_projstatusupdate->execute(array(":projmestage" => $projmestage, ":projevaluate" => $projevaluate, ":projid" => $projid));
				}
				
				$msg = 'Data successfully submitted.';
				$results = "<script type=\"text/javascript\">
								swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 3000,
								showConfirmButton: false });
								setTimeout(function(){
									window.location.href = 'projects-evaluation';
								}, 3000);
							</script>";	
			}
		}else{
			$msg = "Please fill all fields";
			$results = "<script type=\"text/javascript\">
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
	
	$projname = $row_formdetails["projname"];	
	$projcategory = $row_formdetails["projcategory"];	
	$projcost = $row_formdetails["projcost"];
	$projdesc = $row_formdetails["projdesc"];
	
	if($projcategory==2){
		$query_rsBudget = $db->prepare("SELECT tenderamount FROM tbl_tenderdetails WHERE projid = '$projid'");
		$query_rsBudget->execute();
		$row_rsBudget = $query_rsBudget->fetch();
		$projbudget = $row_projname["tenderamount"];
	}else{
		$projbudget = $projcost;
	}
	
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();	

	$query_PrjDet =  $db->prepare("SELECT tbl_projects.*, tbl_outputs.output AS output, tbl_indicator.indicator_name AS indicator, tbl_expprojoutput.expoutputvalue AS target, tbl_expprojoutput.outputbaseline AS baseline FROM tbl_projects LEFT JOIN tbl_expprojoutput ON tbl_projects.projid = tbl_expprojoutput.projid  LEFT JOIN tbl_indicator ON tbl_expprojoutput.expoutputindicator = tbl_indicator.indid  LEFT JOIN tbl_outputs ON tbl_expprojoutput.expoutputname = tbl_outputs.opid WHERE tbl_projects.deleted='0' AND tbl_projects.projid = '$projid'");
	$query_PrjDet->execute();		
	$row_PrjDet = $query_PrjDet->fetch();	

	$query_evaluationobjs =  $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE formid = '$formid'");
	$query_evaluationobjs->execute();		
	$totalRows_evaluationobjs = $query_evaluationobjs->rowCount();	

	$query_evaluationsummary =  $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE formid = '$formid'");
	$query_evaluationsummary->execute();		
	$totalRows_evaluationsummary = $query_evaluationsummary->rowCount();

	$query_amntpaid =  $db->prepare("SELECT d.amountpaid AS amount FROM tbl_payments_disbursed d INNER JOIN tbl_payments_request r ON r.id = d.reqid WHERE r.projid = '$projid'");
	$query_amntpaid->execute();	
	$amountpaid	= 0;
	while($row_amntpaid = $query_amntpaid->fetch()){	
		$amntpaid = $row_amntpaid["amount"];
		$amountpaid = $amountpaid + $amntpaid;
	}
	
	$amntrate = $amountpaid / $projbudget;
	
	$query_dates = $db->prepare("SELECT projstartdate, projenddate FROM tbl_projects WHERE projid='$projid'");
	$query_dates->execute();		
	$row_dates = $query_dates->fetch();
						
	$now = time();
	$prjsdate = strtotime($row_dates['projstartdate']);
	$prjedate = strtotime($row_dates['projenddate']);
	$prjdatediff = $prjedate - $prjsdate;
	$prjnowdiff = $now - $prjsdate;
	$projdatediff = round(($prjedate - $prjsdate) / (60 * 60 * 24),0);
	$projnowdiff = round(($now - $prjsdate) / (60 * 60 * 24),0);
	$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
	if($prjtimelinerate >100):
		$prjtimelinerate = 100;
	else:
		$prjtimelinerate = $prjtimelinerate;
	endif;		

	//Issues
	$query_rsIssues = $db->prepare("SELECT tbl_projissues.id, projname, origin, issue_type, category,observation, recommendation, status, tbl_projissues.created_by AS monitor, tbl_projissues.date_created AS issuedate FROM tbl_projissues INNER JOIN tbl_projects ON tbl_projects.projid=tbl_projissues.projid INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid=tbl_projissues.risk_category WHERE tbl_projects.projid='$projid'");
	$query_rsIssues->execute();	
	$count_rsIssues = $query_rsIssues->rowCount();	

	//get the submission number and date 
	$query_rsSubmission = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid='$projid' AND formid='$formid'");
	$query_rsSubmission->execute();
	$totalRows_rsSubmission = $query_rsSubmission->rowCount();
	
	///////////////////////////////////////////
	//Query the project name and form Name 
	///////////////////////////////////////////
	$query_rsFormDetails = $db->prepare("SELECT f.form_name, p.projname, p.projdesc FROM `tbl_project_evaluation_submission` s INNER JOIN tbl_project_evaluation_forms f ON f.id=s.formid INNER JOIN tbl_projects p ON p.projid =s.projid WHERE  s.projid=:projid AND s.formid=:formid GROUP BY p.projname");
	$query_rsFormDetails->execute(array(":projid" => $projid, ":formid" => $formid));
	$row_rsFormDetails = $query_rsFormDetails->fetch();
	$totalRows_rsFormDetails = $query_rsFormDetails->rowCount();

	/////////////////////////////////////////////
	//	Query section for the answers 
	/////////////////////////////////////////////
	$query_rsSection = $db->prepare("SELECT o.id, o.section  FROM tbl_project_evaluation_answers a
	INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id=a.fieldid INNER JOIN tbl_project_evaluation_form_sections o ON o.id =q.sectionid WHERE o.formid=:formid GROUP BY q.sectionid ");
	$query_rsSection->execute(array(":formid" => $formid));
	$row_rsSection = $query_rsSection->fetchAll();
	$totalRows_rsSection = $query_rsSection->rowCount();
	
	///////////////////////////////////////////
	//	Query the form sections 
	///////////////////////////////////////////
	$query_rsSections = $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE  formid=:formid");
	$query_rsSections->execute(array(":formid" => $formid));
	$row_rsSections = $query_rsSections->fetchAll();
	$totalRows_rsSections = $query_rsSections->rowCount();


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
	<script src="evaluation-process-conclusion.js"></script>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}  
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
			include_once('project-evaluation-conclusion-inner.php');
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