<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';	
		
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["prj"]) && !empty($_GET["prj"])){
	$encoded_projid = $_GET["prj"];
	$decode_prj = base64_decode($encoded_projid);
	$prj_array = explode("evprj", $decode_prj);
	$projid = $prj_array[1];	
}
if(isset($_GET["fm"]) && !empty($_GET["fm"])){
	$encoded_formid = $_GET["fm"];
	$decode_fm = base64_decode($encoded_formid);
	$fm_array = explode("evfrm", $decode_fm);
	$formid = $fm_array[1];	
}
if(isset($_GET["em"]) && !empty($_GET["em"])){
	$encoded_email = $_GET["em"];
	$decode_em = base64_decode($encoded_email);
	$em_array = explode("eveml", $decode_em);
	$email = $em_array[1];	
}

$query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:frmid AND projid=:prjid");
$query_rsEvalDates->execute(array(":frmid" => $formid, ":prjid" => $projid));
$row_rsrsEvalDates = $query_rsEvalDates->fetch();
$totalRows_rsEvalDates = $query_rsEvalDates->rowCount();
$formname = $row_rsrsEvalDates["form_name"];
$enumeratortype = $row_rsrsEvalDates["enumerator_type"];
$sample = $row_rsrsEvalDates["sample_size"];
$evalstartdate = $row_rsrsEvalDates["startdate"];
$evalenddate = $row_rsrsEvalDates["enddate"];
$current_date = date("Y-m-d");
$evalid = $row_rsrsEvalDates["id"];
$indid = $row_rsrsEvalDates["indid"];
//$email ="denkytheka@gmail.com";

$sdate = date_create($evalstartdate);
$startdate = date_format($sdate, "d M Y");
$edate = date_create($evalenddate);
$enddate = date_format($edate, "d M Y");	

function submission()
{
	$digits = "";
	$length = 3;
	$numbers = range(0, 9);
	shuffle($numbers);
	for ($i = 0; $i < $length; $i++) {
		$digits .= $numbers[$i];
	}
	return $digits;
}
$submission = submission();
	
if (isset($_POST['submit'])) {
	$projid = $_POST['projid'];
	$formid = $_POST['formid'];
	//$sectionid = $_POST['sectionid'];
	$subcode = $formid.$submission;
	$level3 = $_POST['location'];
	
	$query_submissions = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid = :projid and formid = :formid");
	$query_submissions->execute(array(':projid' => $projid, ':formid' => $formid));
	$count_submissions = $query_submissions->rowCount();

	if($sample > $count_submissions){
		$sqlinsert = $db->prepare("INSERT INTO tbl_project_evaluation_submission(projid, formid, submission_code, email, level3, submission_date) VALUES (:projid, :formid, :subcode, :email, :level3, :date)");
		$submit = $sqlinsert->execute(array(':projid' => $projid, ':formid' => $formid, ':subcode' => $subcode, ':email' => $email, ':level3' => $level3, ':date' => $current_date));
		if($submit){
			$submissionid = $db->lastInsertId();
			if(isset($_POST['disaggregationid'])){
				if (isset($_POST['answer']) && !empty($_POST['answer'])) {
					$disaggregation = $_POST['disaggregationid'];
					$count = count($_POST['answer']);
					for ($j = 0; $j < $count; $j++) {
						$questionid = $_POST['questionid'][$j];
						$answer = $_POST['answer'][$j];
			
						$sql = $db->prepare("INSERT INTO tbl_project_evaluation_answers(submissionid, questionid, answer, disaggregation) VALUES (:subid, :questionid, :answer, :disaggregation)");
						$sql->execute(array(':subid' => $submissionid, ':questionid' => $questionid, ':answer' => $answer, ':disaggregation' => $disaggregation));
					}
				}
			} else {
				if (isset($_POST['answer']) && !empty($_POST['answer'])) {
					$count = count($_POST['answer']);
					for ($j = 0; $j < $count; $j++) {
						$questionid = $_POST['questionid'][$j];
						$answer = $_POST['answer'][$j];
			
						$sql = $db->prepare("INSERT INTO tbl_project_evaluation_answers(submissionid, questionid, answer) VALUES (:subid, :questionid, :answer)");
						$sql->execute(array(':subid' => $submissionid, ':questionid' => $questionid, ':answer' => $answer));
					}
				}
			}
			$msg = 'The form successfully submitted.';
			$results = "<script type=\"text/javascript\">
							swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
							showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'public-survey-form?prj=$encoded_projid&fm=$encoded_formid&em=$encoded_email';
							}, 2000);
						</script>";
		}
	}else{
		$msg = 'Warning! Sorry you are not alloweed to submit more data, you have already reached the required sample size.';
		$results = "<script type=\"text/javascript\">
						swal({
						title: \"Warning!\",
						text: \" $msg\",
						type: 'Warning',
						timer: 5000,
						showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'public-survey-form?prj=$encoded_projid&fm=$encoded_formid&em=$encoded_email';
						}, 5000);
					</script>";
	}
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
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/other-evaluation-form-style.css" rel="stylesheet" />
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

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/ev-style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
	</script>
	<link href="style.css" rel="stylesheet">
	<script src="ckeditor/ckeditor.js"></script>
	<script src="process-app.js"></script>
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
    <section>
        <!-- Left Sidebar -->
        <div class="sidebar">
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
        </div>
        <!-- #END# Left Sidebar -->
    </section>
	<section class="content" style="margin-top:-20px; padding-bottom:0px">
		<div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> <strong>SURVEY TYPE: Project <?=$formname?> Survey </strong>
				</h4>
            </div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="block-header">
					<?php 
						echo $results;
					?>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body" style="margin-top:5px">
						<?php
							$query_TotalSub = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid ='$projid' AND formid='$formid'");
							$query_TotalSub->execute();		
							$row_TotalSub = $query_TotalSub->fetch();
							$totalRows_TotalSub = $query_TotalSub->rowCount();
						
							if($current_date < $evalstartdate){ 
								echo '<h4 style="color:red"> PLEASE NOTE THAT THIS PROJECT EVALUATION START DATE IS NOT YET!!</h4>';
							}elseif($current_date > $evalenddate){ 
							
								$currstatus = 2;
								$formstatus = 3;
								//$evalstatus = 3;
								$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
								$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));
								
								/* $query_evalstatusupdate = $db->prepare("UPDATE tbl_projects_evaluation SET status=:evalstatus WHERE id=:evalid");
								$query_evalstatusupdate->execute(array(":evalstatus" => $evalstatus, ":evalid" => $evalid)); */
								
								echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE YOU CAN NOT ACCESS THE EVALUATION FORM ANYMORE BECAUSE EVALUATION PERIOD FOR THIS PROJECT ENDED ON '.$evalenddate.'!!</strong></h5>';
							}
							elseif($sample == $totalRows_TotalSub){ 						
								$currstatus = 2;
								$formstatus = 3;
								//$evalstatus = 3;
								
								$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
								$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));
								
								/* $query_evalstatusupdate = $db->prepare("UPDATE tbl_projects_evaluation SET status=:evalstatus WHERE id=:evalid");
								$query_evalstatusupdate->execute(array(":evalstatus" => $evalstatus, ":evalid" => $evalid)); */
								
								echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE THAT RESPONSES FOR THIS PROJECT EVALUATION HAS REACHED THE REQUIRED NUMBER HENCE YOU CAN NOT ACCESS THE EVALUATION FORM!!</strong></h5>';
							}
							elseif($sample > $totalRows_TotalSub && ($current_date >= $evalstartdate && $current_date <= $evalenddate)){
								require_once("public-survey-form-inner.php");
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
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

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>
<?php
/* }catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
} */
?>