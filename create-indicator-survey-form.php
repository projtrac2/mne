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
	
	$query_indid =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indid'");
	$query_indid->execute();
	$row_indid = $query_indid->fetch();
	$indicator = $row_indid["indname"];	
	$category = $row_indid["indcategory"];
	$mntry = $row_indid['indsector'];
	$dept = $row_indid['inddept'];
	$inddate = $row_indid['date_changed'];
	
	$overduedate = strtotime($inddate. " + 60 days");
	$svyoverduedate = date('Y-m-d', $overduedate);
	$surveyoverduedate = date("d M Y", $svyoverduedate);
	
	$query_ministry =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mntry'");
	$query_ministry->execute();		
	$row_ministry = $query_ministry->fetch();
	$ministry = $row_ministry["sector"];
	
	$query_dept =  $db->prepare("SELECT * FROM tbl_sectors WHERE stid = '$dept'");
	$query_dept->execute();		
	$row_dept = $query_dept->fetch();
	$department = $row_dept["sector"];
	$departmentid = $row_dept["stid"];
	
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();		
	
	//team members
	$query_member = $db->prepare("SELECT fullname, title, ptid FROM tbl_projteam2 WHERE department='$dept' group by ptid");
	$query_member->execute();
	$rowscount = $query_member->rowCount();
	
	

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
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
		type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/baseline-survey-form-style.css" rel="stylesheet" />

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
	<!--<script src="create-evaluation-form.js"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
	<script src="baseline-survey-process.js"></script>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}
		
		span.email-ids {
			float: left;
			/* padding: 4px; */
			border: 1px solid #ccc;
			margin-right: 5px;
			padding-left: 10px;
			padding-right: 10px;
			margin-bottom: 5px;
			background: #f5f5f5;
			padding-top: 3px;
			padding-bottom: 3px;
			border-radius: 5px;
		}

		span.cancel-email {
			border: 1px solid #ccc;
			width: 18px;
			display: block;
			float: right;
			text-align: center;
			margin-left: 20px;
			border-radius: 49%;
			height: 18px;
			line-height: 15px;
			margin-top: 1px;
			cursor: pointer;
		}

		.col-sm-12.email-id-row {
			border: 1px solid #ccc;
		}

		.col-sm-12.email-id-row input {
			border: 0px;
			outline: 0px;
		}

		span.to-input {
			display: block;
			float: left;
			padding-right: 11px;
		}

		.col-sm-12.email-id-row {
			padding-top: 6px;
			padding-bottom: 7px;
			margin-top: 23px;
		}

	</style>
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
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> CREATE INDICATOR BASELINE SURVEY FORM
				</h4>
            </div>
			<div class="block-header" id="sweetalert">
			</div>
			<div id="sweetalert2">
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body" style="margin-top:5px">
							<div class="wizard">
								<div class="wizard-inner" style="margin-top:-20px">
									<div class="connecting-line"></div>
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab"
												title="Project Information">
												<span class="round-tab">
													INDICATOR INFO
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step2" data-toggle="tab" aria-controls="step3" role="tab" title="Form & Section Details">
												<span class="round-tab">
													FORM DETAILS
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#step3" data-toggle="tab" aria-controls="step4" role="tab" title="Form Questions ">
												<span class="round-tab">
													QUESTIONS
												</span>
											</a>
										</li>
										<li role="presentation" class="disabled">
											<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab"
												title="Complete">
												<span class="round-tab">
													<i class="glyphicon glyphicon-ok"></i>
												</span>
											</a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane active" role="tabpanel" id="step1">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Brief Indicator Information
											</legend>
											<div class="row clearfix">
												<input name="evaluationid" id="evaluationid" type="hidden" value="<?=$evalid?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="col-md-12">
														<strong>Indicator: <font color="#3F51B5"><?php echo $indicator; ?></font></strong>
													</div>
													<div class="col-md-12">
														<label class="control-label"><strong>Category:</strong><font color="#3F51B5"> <?php echo $category; ?></font></label>
													</div>
													<div class="col-md-6">
														<label class="control-label"><strong><?=$ministrylabel?>:</strong><font color="#3F51B5"> <?php echo $ministry; ?></font></label>
													</div>
													<div class="col-md-6">
														<label class="control-label"><strong><?=$departmentlabel?>:</strong><font color="#3F51B5"> <?php echo $department; ?></font></label>
													</div>
													<div class="col-md-12">
														<ul class="list-inline" align="center" style="margin-top:20px">
															<li>
																<a href="indicator-baseline-survey" type="button" class="btn btn-warning">Cancel</a> &nbsp;<button type="button" class="btn btn-primary next-step" id="form">Continue</button>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<div class="tab-pane" role="tabpanel" id="step2">
										<form action="" method="POST" role="form" id="formone">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Form Details. </legend>
												<div class="col-md-12">
													<label class="control-label">Form Name :</label>
													<div class="form-line">
														<input name="formname" id="formname" type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px"  required>
														<input name="indid" type="hidden" id="indid" value="<?=$indid?>">
													</div>
												</div>
												<div class="col-md-4">
													<label class="control-label"><?=$departmentlabel?> *:</label>
													<div class="form-line">
														<input name="inddepartment" type="text" class="form-control" style="padding-left:10px; border:#CCC thin solid; border-radius:5px" value="<?=$department?>" readonly>
													</div>
												</div>
												<div class="col-md-4">
													<label class="control-label">Responsible *:</label>
													<div class="form-line">
														<select name="responsible" id="members" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
															<?php 
															if($rowscount){
																echo '<option value="">.... Select responsible ....</option>';
																while ($row = $query_member->fetch()) {
																	echo '<option value="' . $row['ptid'] . '">' . $row['title'] . '. ' . $row['fullname'] . '</option>';
																}
															}else{
																echo '<option value=""> No defined User for this department </option>';
															}
															?>
														</select>
													</div>
												</div>	
												<div class="col-md-4" style="float:left">
													<label class="control-label">Respondents Type*:</label>
													<div class="form-line">
															<select name="respondentstype" id="respondentstype" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">.... Select ....</option>
																<option value="1">Internal Respondents</option>
																<option value="2">External Respondents</option>
															</select>
													</div>
												</div>
												<div class="col-md-4">
													<label class="control-label">Responses Limit Type *:</label>
													<div class="form-line">
														<select name="responseslimit" id="responseslimit" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" 		data-live-search="true" required>
															<option value="">.... Select ....</option>
															<option value="1">Survey Dates</option>
															<option value="2">Number of Responses</option>
														</select>
													</div>
												</div>
												<div id="limittype">	
												</div>
												<div class="col-md-12">
													<label class="control-label"><font align="left" style="background-color:#CDDC39">Purpose of the survey.</font> *:</label>
													<p align="left">
														<textarea name="description" cols="45" rows="5"
															class="form-control" id="formdesc" required="required"
															style="height:100px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
															placeholder="Briefly describe the purpose of this indicator survey."></textarea>
													</p>
												</div>
												
												<div id="alert">
												</div>
												<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
												<ul class="list-inline pull-right" style="margin-top:20px">
													<li><button type="button" class="btn btn-default prev-step">Previous</button> <button type="submit" class="btn btn-primary next-step" id="formdetails">Save and Continue</button></li>
												</ul>
											</fieldset>
										</form>
									</div>
									
									<!-- add modal that shall be launched on click preview  -->
									<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header" style="background-color:#795548">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h3 class="modal-title" align="center"><font color="#FFF">Form Preview</font></h3>
												</div>
												<div class="modal-body">
													<div class="row clearfix">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="card">
																<div class="body">
																	<div id="render-wrap">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<!-- #END# Modal Issue Response -->
									
									<div class="tab-pane" role="tabpanel" id="step3">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Survey Form Question Fields.
											</legend>
											<ul class="list-inline pull-right" style="margin-top:20px; margin-bottom:20px">
												<li><button type="button" class="btn btn-primary" id="preview">Form Preview</button></li>
											</ul>	
											<div class="row clearfix">
												<div class="head">
													<div id="frmid"></div>
													<div class="col-md-12" style="margin-bottom:10px; margin-top:10px">
														<label class="control-label">Survey Objective *:</label>
														<div class="form-line">
															<input name="section" id="section" type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px; margin-bottom:10px" required="required">
														</div>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="card" style="margin-bottom:20px; padding-bottom:10px">
														<div class="body" style="padding-bottom:30px">
															<div class="col-md-12" style="margin-top:10px">
																<label class="control-label">Add Objective Questions Fields below:</label>
															</div>
															<div id="formbuilder"></div>
															<div class="col-md-12" align="center">
																<button class="btn btn-success btn-sm" id="save">Save Form Fields</button>
															</div>
														</div>
														<ul class="list-inline pull-right" style="margin-top:20px">
															<li><button type="button" class="btn btn-default prev-step">Previous</button> <button type="submit" class="btn btn-primary next-step" id="formobj">Complete Form Creation</button></li>
														</ul>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<div class="tab-pane" role="tabpanel" id="complete">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Complete.
											</legend>
											<p style="color:green"><strong>You have successfully created Indicator baseline survey form.</strong></p>
											<ul class="list-inline" style="margin-top:20px">
												<li id="frmfinish"></li>
											</ul>
										</fieldset>
									</div>
									<div class="clearfix"></div>
									
								</div>
							</div>
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