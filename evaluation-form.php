<?php

require_once('Connections/ProjMonEva.php'); 

if (!isset($_SESSION)) {
  session_start();
}
 
$MM_authorizedUsers = "SuperAdmin,Admin,Operator,Officer";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "loginadminfail.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

try{
	//include_once 'projtrac-dashboard/resource/session.php';
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';	
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if (isset($_SESSION['MM_Username'])) {
	  $user_name = $_SESSION['MM_Username'];
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

	$query_PrjDet =  $db->prepare("SELECT tbl_projects.*, tbl_outputs.output AS output, tbl_indicator.indname AS indicator, tbl_expprojoutput.expoutputvalue AS target, tbl_expprojoutput.outputbaseline AS baseline FROM tbl_projects LEFT JOIN tbl_expprojoutput ON tbl_projects.projid = tbl_expprojoutput.projid  LEFT JOIN tbl_indicator ON tbl_expprojoutput.expoutputindicator = tbl_indicator.indid  LEFT JOIN tbl_outputs ON tbl_expprojoutput.expoutputname = tbl_outputs.opid WHERE tbl_projects.deleted='0' AND tbl_projects.projid = '$projid'");
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

	//Issues
	$query_rsIssues = $db->prepare("SELECT tbl_projissues.id, projname, origin, issue_type, category,observation, recommendation, status, tbl_projissues.created_by AS monitor, tbl_projissues.date_created AS issuedate FROM tbl_projissues INNER JOIN tbl_projects ON tbl_projects.projid=tbl_projissues.projid INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid=tbl_projissues.risk_category WHERE tbl_projects.projid='$projid'");
	$query_rsIssues->execute();	
	$count_rsIssues = $query_rsIssues->rowCount();	

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
    <link href="css/other-evaluation-form-style.css" rel="stylesheet" />

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
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> PROJECT EVALUATION FORM
				</h4>
            </div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body" style="margin-top:5px">
							<form id="wizard_with_validation" method="POST" name="submitevalfrm" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
								<?php
								if(isset($_GET['fm']) && !empty($_GET['fm'])){
									$projid = $_GET['prj'];
									$formid = $_GET['fm'];
									$query_rsForm = $db->prepare("SELECT * FROM tbl_project_evaluation_forms WHERE id=:formid");
									$query_rsForm->execute(array(":formid" => $formid));
									$row_rsForm = $query_rsForm->fetch();
									
									$query_rsSection = $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE  formid=:formid");
									$query_rsSection->execute(array(":formid" => $formid));
									$row_rsSection = $query_rsSection->fetchAll();
									$totalRows_rsSection = $query_rsSection->rowCount();

									echo '<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>FORM NAME: '.$row_rsForm["form_name"].'</strong></h5>';
									if ($totalRows_rsSection == 0) {
										echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
									} else {
										$count = 0;
										foreach ($row_rsSection as $key) {
											$section = $key['section'];
											$count++;
											$sectionid = $key['id'];
											$query_rsFormfield = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_fileds WHERE formid=:formid AND sectionid=:sectionid ORDER BY id");
											$query_rsFormfield->execute(array(":formid" => $formid, ":sectionid" => $sectionid));
											$row_rsFormfield = $query_rsFormfield->fetchAll();
											$totalRows_rsFormfield = $query_rsFormfield->rowCount();

											if ($totalRows_rsFormfield == 0) {
												echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
											} else {
												$cnt = 0;
												echo '
												<fieldset class="scheduler-border">
													<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Objective ' . $count . ':</label> ' . $section . '</legend>';
												echo '
												<div class="col-md-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" style="color:#000">
														<thead>
															<tr style="background-color:#607D8B; color:#FFF">
																<th width="3%">SN</th>
																<th width="97%">Question</th>
															</tr>
														</thead>
														<tbody>';
															foreach ($row_rsFormfield as $field){
																$cnt++;
																$type = $field['fieldtype'];
																$requireValidOption = $field['requirevalidoption'];
																$label = $field['label'];
																$subtype = $field['subtype'];
																$style = $field['style'];
																$name = $field['fieldname'];
																$placeholder = $field['placeholder'];
																$description = $field['fielddesc'];
																$other = $field['other'];
																$maxlength = $field['fieldmaxlength'];
																$max = $field['fieldmin'];
																$min = $field['fieldmax'];
																$multiple = $field['multiple'];
																$fieldid = $field['id'];

																if ($type == "textarea") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label">' . $label  . '*: <font align="left" style="background-color:#eff2f4">
																			(' .  $placeholder . '.) </font></label>
																			<div class="form-line">
																				<p align="left">
																					<textarea name="answer[]" cols="45" rows="5" class="txtboxes" id="' . $name .$fieldid. '" required="required"
																						style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
																						placeholder="' . $placeholder . '"></textarea>
																					<script>
																					CKEDITOR.replace("' . $name . $fieldid.'", {
																						on: {
																							instanceReady: function(ev) {
																								// Output paragraphs as <p>Text</p>.
																								this.dataProcessor.writer.setRules("p", {
																									indent: false,
																									breakBeforeOpen: false,
																									breakAfterOpen: false,
																									breakBeforeClose: false,
																									breakAfterClose: false
																								});
																								this.dataProcessor.writer.setRules("ol", {
																									indent: false,
																									breakBeforeOpen: false,
																									breakAfterOpen: false,
																									breakBeforeClose: false,
																									breakAfterClose: false
																								});
																								this.dataProcessor.writer.setRules("ul", {
																									indent: false,
																									breakBeforeOpen: false,
																									breakAfterOpen: false,
																									breakBeforeClose: false,
																									breakAfterClose: false
																								});
																								this.dataProcessor.writer.setRules("li", {
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
																		</td>
																	</tr>';
																} else if ($type == "radio-group") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label">' . $label  . '*:</label>
																			<div class="form-line">';
																				$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
																				$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
																				$row_rsValue = $query_rsValue->fetchAll();
																				$totalRows_rsValue = $query_rsValue->rowCount();
																				if ($totalRows_rsValue == 0) {
																					echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
																				} else {
																					$nm=0;
																					echo '
																					<div class="demo-radio-button">';
																						foreach ($row_rsValue as $row) {
																							$nm++;
																							$lab = $row['label'];
																							$option = $row['val'];
																							$vlid = $row['id'];
																							echo '
																							<input type="radio" name="answer[]" id="rd'.$sectionid.$fieldid.$vlid.'" value="' . $option . '" class="with-gap radio-col-green"  required/>
																							<label for="rd'.$sectionid.$fieldid.$vlid.'">' . $lab . '</label>';
																						}
																					echo '</div>';
																				}
																			echo '</div>
																		</td>
																	</tr>';
																} else if ($type == "select" || $type == "autocomplete") {
																	$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
																	$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
																	$row_rsValue = $query_rsValue->fetchAll();
																	$totalRows_rsValue = $query_rsValue->rowCount();
																	if ($totalRows_rsValue == 0) {
																		echo'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
																	} else{
																		if ($multiple == 1) {
																			echo '
																			<tr>
																				<td>'.$cnt.'</td>
																				<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																					<div class="form-line">
																						<select name="answer[]" id="' . $name . '" class="selectpicker" multiple style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																							<option value="">....Select ' . $name . ' first....</option>';
																							foreach ($row_rsValue as $row) {
																								$desc = $row['label'];
																								$option = $row['val'];
																								echo '<option value="' . $option . '">' . $desc . '</option>';
																							}
																						echo '</select>
																					</div>
																				</td>
																			</tr>';
																		} else {
																			echo '
																			<tr>
																				<td>'.$cnt.'</td>
																				<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																					<div class="form-line">
																						<select name="answer[]" id="' . $name . '" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																							<option value="">....Select ' . $name . ' first....</option>';
																							foreach ($row_rsValue as $row) {
																								$desc = $row['label'];
																								$option = $row['val'];
																								echo '<option value="' . $option . '">' . $desc . '</option>';
																							}
																						echo '</select>
																					</div>
																				</td>
																			</tr>';
																		}
																	}
																} else if ($type == "date") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																			<div class="form-line">
																				<input type="date" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId"">
																			</div>
																		</td>
																	</tr>';
																} else if ($type == "header") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																			<div class="form-line">
																			<' . $subtype . '>' . $label . '</' . $subtype . '>
																			</div>
																		</td>
																	</tr>';
																} else if ($type == "number") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																			<div class="form-line">
																				<input type="number" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId" max="' . $max . '" min=" ' . $min . ' ">
																				<small id="helpId" class="text-muted">' . $label . '</small>
																			</div>
																		</td>
																	</tr>';
																} else if ($type == "paragraph") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																			<div class="form-line">
																				<' . $subtype . '> </' . $subtype . '>
																			</div>
																		</td>
																	</tr>';
																} else if ($type == "checkbox-group") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label">' . $label  . '*:</label>
																			<div class="form-line">';
																				$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
																				$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
																				$row_rsValue = $query_rsValue->fetchAll();
																				$totalRows_rsValue = $query_rsValue->rowCount();
																				if ($totalRows_rsValue == 0) {
																					echo'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
																				}else {
																					$nm=0;
																					echo '
																					<div class="demo-checkbox">';
																						foreach ($row_rsValue as $row) {
																							$nm++;
																							$lab = $row['label'];
																							$option = $row['val'];
																							$vlid = $row['id'];
																							echo '
																							<input type="checkbox" name="answer[]" id="'.$sectionid.$fieldid.$vlid.'" value="' . $option . '" class="filled-in chk-col-light-blue"  required/>
																							<label for="'.$sectionid.$fieldid.$vlid.'">' . $lab . '</label>';
																						}
																					echo '</div>';
																				}
																			echo '</div>
																		</td>
																	</tr>';
																} else if ($type == "autocomplete") {
																	$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
																	$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
																	$row_rsValue = $query_rsValue->fetchAll();
																	$totalRows_rsValue = $query_rsValue->rowCount();
																	if ($totalRows_rsValue == 0) {
																		echo	'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
																	} else { }
																} else if ($type == "text") {
																	echo '
																	<tr>
																		<td>'.$cnt.'</td>
																		<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																			<div class="form-line">
																				<input type=" ' . $subtype . '" name="answer[]" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																			</div>
																		</td>
																	</tr>';
																} else if ($type == "file") {
																	if ($multiple == 1) {
																		echo '
																		<tr>
																			<td>'.$cnt.'</td>
																			<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																				<div class="form-line">
																					<input type="file" name="answer[]" id="' . $name . '" multiple class="form-control form-control-file">
																					<small id="helpId" class="text-muted">' . $placeholder . '</small>
																				</div>
																			</td>
																		</tr>';
																	} else {
																		echo '
																		<tr>
																			<td>'.$cnt.'</td>
																			<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
																				<div class="form-line">
																					<input type="file" name="answer[]" id="' . $name . '"  class="form-control form-control-file">
																					<small id="helpId" class="text-muted">' . $placeholder . '</small>
																				</div>
																			</td>
																		</tr>';
																	}
																}
															}
														echo '
														</tbody>
													</table>
												</div>
												</div>';
											}
											echo'</fieldset>';
										}
									}
								}
								?>
								<div class="row clearfix">
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">	
											<a href="evaluation-form?prj=<?php echo $projid; ?>&fm=<?php echo $formid; ?>" class="btn btn-warning" style="margin-right:10px">Cancel</a>
											<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
										<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
										<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
										<input name="formid" type="hidden" value="<?php echo $formid; ?>" />
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
									</div>
								</div>
							</form>	
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