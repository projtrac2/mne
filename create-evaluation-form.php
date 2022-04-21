<?php 
require 'authentication.php';

try{	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 
	
	
	if(isset($_GET["projid"]) && !empty($_GET["projid"])){
		$projid = $_GET["projid"];
	}
	
	$query_projname =  $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
	$query_projname->execute();
	$row_projname = $query_projname->fetch();
	$projname = $row_projname["projname"];	
	$projcategory = $row_projname["projcategory"];	
	$projcost = $row_projname["projcost"];
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
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/evaluation-form-style.css" rel="stylesheet" />

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
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> CREATE PROJECT EVALUATION FORM
				</h4>
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
													PROJ INFO
												</span>
											</a>
										</li>
										<!--<li role="presentation" class="disabled">
											<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Monitoring History">
												<span class="round-tab">
													MONITORING
												</span>
											</a>
										</li>-->
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
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Brief Project Information.
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="col-md-12">
														<strong>Project Name: <font color="#3F51B5"><?php echo $projname; ?></font></strong>
													</div>
													<div class="col-md-12">
														<label class="control-label">Project Description:</label>
														<div class="form-line">
															<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
																<strong><?php echo $row_projname["projdesc"]; ?></strong>
																<input name="projid" id="projid" type="hidden" value="<?=$projid?>">
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr class="bg-light-blue">
																	<th style="width:17%">Project Results</th>
																	<th style="width:23%">Indicator</th>
																	<th style="width:20%">Baseline</th>
																	<th style="width:20%">Target</th>
																	<th style="width:20%">Achieved</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="bg-light-blue">Outcome</td>
																	<td><?php echo $location; ?></td>
																	<td><?php echo $projstatus; ?></td>
																	<td><?php echo $location; ?></td>
																	<td><font color="red"><strong>??</strong></font></td>
																</tr>
																<tr>
																	<td class="bg-light-blue">Output</td>
																	<td><?php echo $row_PrjDet['indicator']; ?></td>
																	<td><?php echo $row_PrjDet['baseline']; ?></td>
																	<td><?php echo $row_PrjDet['target']; ?></td>
																	<td><font color="green"><strong>0</strong></font></td>
																</tr>
															</tbody>
														</table>
													</div>
													<div class="col-md-12">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr class="bg-light-blue">
																	<th style="width:17%">Project Constraint</th>
																	<th style="width:23%">Indicator</th>
																	<th style="width:20%">Planned</th>
																	<th style="width:20%">Actual</th>
																	<th style="width:20%">Rate(%)</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="bg-light-blue">Budget</td>
																	<td><?php echo "Kenyan Shilling"; ?></td>
																	<td><?php echo number_format($projbudget, 2); ?></td>
																	<td><?php echo number_format($amountpaid, 2); ?></td>
																	<td><?php echo $amntrate."%"; ?></td>
																</tr>
																<tr>
																	<td class="bg-light-blue">Timeframe</td>
																	<td><?php echo "Days"; ?></td>
																	<td><?php echo $projdatediff; ?></td>
																	<td><?php echo $projnowdiff; ?></td>
																	<td><?php echo $prjtimelinerate."%"; ?></td>
																</tr>
															</tbody>
														</table>
													</div>
													<div class="col-md-12">
														<ul class="list-inline pull-right" style="margin-top:20px">
															<li>
																<button type="button" class="btn btn-primary next-step" id="formfield">Continue</button>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<!--<div class="tab-pane" role="tabpanel" id="step2">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Monitoring History.
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													
													<div class="col-md-12">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr class="bg-light-blue">
																	<th style="width:16%">Project Constraint</th>
																	<th style="width:28%">Planned</th>
																	<th style="width:28%">Actual</th>
																	<th style="width:28%">Achieved</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="bg-light-blue">Budget</td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr>
																	<td class="bg-light-blue">Timeframe</td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr>
																	<td class="bg-light-blue">Output</td>
																	<td></td>
																	<td></td>
																	<td></td>
																</tr>
															</tbody>
														</table>
													</div>
													<div class="col-md-12">
														<ul class="list-inline pull-right" style="margin-top:20px">
															<li><button type="button" class="btn btn-default prev-step">Previous</button> <button type="button" class="btn btn-primary next-step" id="formtype">Continue</button></li>
														</ul>
													</div>
												</div>
											</div>
										</fieldset>
									</div>-->
									<div class="tab-pane" role="tabpanel" id="step2">
										<form action="" method="POST" role="form" id="formone">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Form Details. </legend>
												<div class="col-md-12">
													<label class="control-label">Projects *:</label>
													<div class="form-line">
														<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
															<strong><?php echo $projname; ?></strong>
															<input name="projid" id="projid" type="hidden" value="<?=$projid?>">
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<label class="control-label">Form Name *:</label>
													<div class="form-line">
														<input name="formname" id="formname" type="text" class="form-control" style="border:#CCC thin solid; border-radius: 5px"  required>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Ministry *:</label>
														<div class="form-line">
															<select name="projsector" id="projsector" class="form-control show-tick"
																style="border:#CCC thin solid; border-radius:5px"
																data-live-search="true" required>
																<option value="">.... Select Sector from list ....</option>
																<?php
																do {
																	?>
																<option value="<?php echo $row_rsSector['stid'] ?>">
																	<?php echo $row_rsSector['sector'] ?></option>
																<?php
																} while ($row_rsSector = $query_rsSector->fetch());
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Department *:</label>
														<div class="form-line">
															<select name="projdept" id="projdept" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">....Select Ministry first....</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<label class="control-label">Responsible *:</label>
													<div class="form-line">
														<select name="responsible" id="members" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
															<option value="">....Select Department first....</option>
														</select>
													</div>
												</div>		
												<!--<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">Responsible *:</label>
														<div class="form-line">
															<select name="responsible[]" class="ms" multiple="multiple">
																<optgroup label="All Risk Categories" id="members" >
																	<option value="">....Select Department first....</option>
																</optgroup>
															</select>
														</div>
													</div>
												</div>-->
												<div class="col-md-12">
													<label class="control-label"><font align="left" style="background-color:#CDDC39">Purpose of the evaluation.</font> *:</label>
													<p align="left">
														<textarea name="description" cols="45" rows="5"
															class="txtboxes" id="formdesc" required="required"
															style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
															placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."></textarea>
														<script>
														CKEDITOR.replace('formdesc', {
															on: {
																instanceReady: function(ev) {
																	// Output paragraphs as <p>Text</p>.
																	this.dataProcessor.writer.setRules('p', {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules('ol', {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules('ul', {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules('li', {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																}
															}
														});
														</script>
													</p>
												</div>
											</fieldset>
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Evaluation Criteria.
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="card" style="margin-bottom:-20px">
															<div class="header">
																<i class="ti-link"></i>MULTIPLE CRITERIA - WITH CLICK & ADD
															</div>
															<div class="body">
																<table class="table table-bordered" id="sections_table">
																	<tr>
																		<th style="width:98%">Evaluation Criteria</th>
																		<th style="width:2%"><button type="button" name="addplus"
																				onclick="add_row();" title="Add another field"
																				class="btn btn-success btn-sm"><span
																					class="glyphicon glyphicon-plus"></span></button>
																		</th>
																	</tr>
																	<tr>
																		<td>
																			<input type="text" name="section[]" id="section[]" class="form-control" placeholder="Enter Criterion" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																		</td>
																		<td></td>
																	</tr>
																</table>
																<script type="text/javascript">
																	// function add section 
																	function add_row() {
																		$rowno = $("#sections_table tr").length;
																		$rowno = $rowno + 1;
																		$("#sections_table tr:last").after('<tr id="row' + $rowno +
																			'"><td><input type="text" name="section[]" id="section[]" class="form-control"  placeholder="Enter Criterion" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' +
																			$rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
																	}

																	//delete existing row 
																	function delete_row(rowno) {
																		$('#' + rowno).remove();
																	}
																</script>
																<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
																<ul class="list-inline pull-right" style="margin-top:20px">
																	<li><button type="button" class="btn btn-default prev-step">Previous</button> <button type="submit" class="btn btn-primary next-step" id="formtype">Save and Continue</button></li>
																</ul>
															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
									</div>
									<div class="tab-pane" role="tabpanel" id="step3">
										<form action="" method="POST" class="form-inline" role="form" id="myform">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Form Question(s) and Field Type.
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="card" style="margin-bottom:-20px">
															<div class="header">
																<i class="ti-link"></i>ADD QUESTIONS FOR EACH SECTION
															</div>
															<div class="body">
																<h4>Sections</h4>
																<div class="body" id="two">	</div>
																<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
																<ul class="list-inline pull-right" style="margin-top:20px">
																	<li><button type="button" class="btn btn-default prev-step">Previous</button> <button type="submit" class="btn btn-primary next-step" id="formtype">Save and Continue</button></li>
																</ul>
															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
									</div>
									<div class="tab-pane" role="tabpanel" id="complete">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Complete.
											</legend>
											<p style="color:green"><strong>You have successfully completed all steps.</strong></p>
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
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>

	<!-- validation cdn files  -->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>

</body>

</html>