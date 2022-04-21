<?php

require_once('Connections/ProjMonEva.php'); 

try{
	//include_once 'projtrac-dashboard/resource/session.php';
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if(isset($_GET["ind"]) && !empty($_GET["ind"])){
		$encoded_indid = $_GET["ind"];
		$decode_ind = base64_decode($encoded_indid);
		$ind_array = explode("svyind", $decode_ind);
		$indid = $ind_array[1];	
	}
	if(isset($_GET["fm"]) && !empty($_GET["fm"])){
		$encoded_formid = $_GET["fm"];
		$decode_fm = base64_decode($encoded_formid);
		$fm_array = explode("svyind", $decode_fm);
		$formid = $fm_array[1];	
	}
	if(isset($_GET["em"]) && !empty($_GET["em"])){
		$encoded_email = $_GET["em"];
		$decode_em = base64_decode($encoded_email);
		$em_array = explode("svyind", $decode_em);
		$email = $em_array[1];	
	}	
	if(isset($_GET["lc"]) && !empty($_GET["lc"])){
		$encoded_loc = $_GET["lc"];
		$decode_loc = base64_decode($encoded_loc);
		$loc_array = explode("svyind", $decode_loc);
		$locid = $loc_array[1];	
	}
	
	$query_rsEmail = $db->prepare("SELECT * FROM  tbl_projteam2 WHERE email='$email'");
	$query_rsEmail->execute();
	$row_rsEmail = $query_rsEmail->fetch();
	$formuser = $row_rsEmail["ptid"];
	
	$query_rsSurveyDetails = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_details WHERE formid='$formid' and level3='$locid'");
	$query_rsSurveyDetails->execute();
	$row_rsSurveyDetails = $query_rsSurveyDetails->fetch();
	
	$query_rsSvyDates = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_forms f inner join tbl_indicator i on i.indid=f.indid WHERE f.id=:frmid AND f.indid=:indid");
	$query_rsSvyDates->execute(array(":frmid" => $formid, ":indid" => $indid));
	$row_rsSvyDates = $query_rsSvyDates->fetch();
	$totalRows_rsSvyDates = $query_rsSvyDates->rowCount();
	$indicator = $row_rsSvyDates["indname"];
	$svylimittype = $row_rsSvyDates["limit_type"];
	$svyrespnumber = $row_rsSvyDates["responses_number"];
	$svystartdate = $row_rsSvyDates["startdate"];
	$svyenddate = $row_rsSvyDates["enddate"];
	$current_date = date("Y-m-d");
	//$email ="denkytheka@gmail.com";	
	
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
		$indid =  $_POST['indid'];
		$formid = $_POST['formid'];
		$lev3id = $_POST['locid'];
		$sectionid = $_POST['sectionid'];
		$subcode = $formid.$submission;

		$sqlinsert = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_submission(indid, formid, level3id, submission_code, email, submission_date) VALUES (:indid, :formid, :level3, :subcode, :email, :date)");
		$submit = $sqlinsert->execute(array(':indid' => $indid, ':formid' => $formid, ':level3' => $lev3id, ':subcode' => $subcode, ':email' => $email, ':date' => $current_date));
		if($submit){
			$submissionid = $db->lastInsertId();
			if (isset($_POST['answer']) && !empty($_POST['answer'])) {
				$count = count($_POST['answer']);
				for ($j = 0; $j < $count; $j++) {
					$fieldid = $_POST['fieldid'][$j];
					$answer = $_POST['answer'][$j];
		
					$sql = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
					$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
				}
			}	
			
			$query_rsFieldRadio = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields WHERE formid=:frmid");
			$query_rsFieldRadio->execute(array(":frmid" => $formid));
			$row_rsFieldRadio = $query_rsFieldRadio->fetchAll();
			$totalRows_rsFieldRadio = $query_rsFieldRadio->rowCount();
			
			foreach($row_rsFieldRadio as $frow){
				if ($frow["fieldtype"] == 'radio-group'){
					$fieldid = $frow['id'];
					$answer = $_POST['rd'.$fieldid];
					if(!empty($answer)){
						$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
						$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
					}
				}elseif($frow["fieldtype"] == 'checkbox-group'){
					$fieldid = $frow['id'];
					$answer = $_POST['chk'.$fieldid];
					if(!empty($answer)){
						for ($i = 0; $i < count($answer); $i++) {
							$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
							$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer[$i]));
						}
					}
				}elseif($frow["fieldtype"] == 'select' && $frow["multiple"] == 1){
					$fieldid = $frow['id'];
					$answer = implode(",", $_POST['sm'.$fieldid]);
					if(!empty($answer)){
						$sql = $db->prepare("INSERT INTO  tbl_indicator_baseline_survey_answers(submissionid, fieldid, answer) VALUES (:subid, :fieldid, :answer)");
						$sql->execute(array(':subid' => $submissionid, ':fieldid' => $fieldid, ':answer' => $answer));
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
								window.location.href = 'baseline-survey-form?ind=$encoded_indid&fm=$encoded_formid&em=$encoded_email&lc=$encoded_loc';
							}, 2000);
						</script>";
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
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> INDICATOR BASELINE SURVEY FORM
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
						<h5 class="text-align-center bg-light-blue" style="border-radius:4px; padding:10px"><strong>INDICATOR NAME: <?=$indicator?></strong></h5>
						<?php
						$level3 = $row_rsSurveyDetails["level3"];
						$respondents = $row_rsSurveyDetails["respondents"];
						$respondentids = explode(",", $respondents);
						foreach($respondentids as $respondentid){
							if($respondentid==$formuser){
								$query_surveyLevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id='$level3'");
								$query_surveyLevel3->execute();
								$row_surveyLevel3 = $query_surveyLevel3->fetch();
								$surveyLevel3 = $row_surveyLevel3["state"];
								$surveyLevel3parent = $row_surveyLevel3["parent"];
								
								$query_surveyLevel2 = $db->prepare("SELECT * FROM tbl_state WHERE id='$surveyLevel3parent'");
								$query_surveyLevel2->execute();
								$row_surveyLevel2 = $query_surveyLevel2->fetch();
								$surveyLevel2 = $row_surveyLevel2["state"];
								$surveyLevel2parent = $row_surveyLevel2["parent"];
								
								$query_surveyLevel1 = $db->prepare("SELECT * FROM tbl_state WHERE id='$surveyLevel2parent'");
								$query_surveyLevel1->execute();
								$row_surveyLevel1 = $query_surveyLevel1->fetch();
								$surveyLevel1 = $row_surveyLevel1["state"];
								
								if($svylimittype==1){
									if($current_date < $svystartdate){ 
										echo '<h4 style="color:red"> PLEASE NOTE THAT THIS INDICATOR BASELINE SURVEY START DATE IS NOT YET!!</h4>';
									}elseif($current_date > $svyenddate){ 
									
										$currstatus = 1;
										$formstatus = 2;
										$svystatus = 3;
										$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
										$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));
										
										$query_svystatusupdate = $db->prepare("UPDATE tbl_indicator SET surveystatus=:svystatus WHERE indid=:indid");
										$query_svystatusupdate->execute(array(":svystatus" => $svystatus, ":indid" => $indid));
										
										echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE THAT THIS INDICATOR BASELINE SURVEY PERIOD HAS ENDED AND YOU CAN NOT ACCESS THE SURVEY FORM!!</strong></h5>';
									}
									elseif($current_date >= $svystartdate || $current_date <= $svyenddate){ 
										require_once("baseline-survey-form-inner.php");
									}
								}
								else{
									$query_TotalSub = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid ='$indid' AND formid='$formid' GROUP BY submission_code");
									$query_TotalSub->execute();		
									$row_TotalSub = $query_TotalSub->fetch();
									$totalRows_TotalSub = $query_TotalSub->rowCount();
									
									if($svyrespnumber == $totalRows_TotalSub ){							
										$currstatus = 1;
										$formstatus = 2;
										$svystatus = 3;
										
										$query_formstatusupdate = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:formstatus WHERE id=:formid AND status=:currstatus");
										$query_formstatusupdate->execute(array(":formid" => $formid, ":currstatus" => $currstatus, ":formstatus" => $formstatus));
										
										$query_svystatusupdate = $db->prepare("UPDATE tbl_indicator SET surveystatus=:svystatus WHERE indid=:indid");
										$query_svystatusupdate->execute(array(":svystatus" => $svystatus, ":indid" => $indid));
										
										echo '<h5 style="color:#FF5722"><strong> PLEASE NOTE THAT RESPONSES FOR THIS INDICATOR BASELINE SURVEY HAS REACHED THE REQUIRED NUMBER HENCE YOU CAN NOT ACCESS THE SURVEY FORM!!</strong></h5>';
									}elseif($svyrespnumber > $totalRows_TotalSub ){
										require_once("baseline-survey-form-inner.php");
									}
								}
							}
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