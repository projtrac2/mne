<?php 

function risk_category_select_box($db,$projid,$opdetailsid)
{ 
	$risk = '';
	$query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_output_risks R ON C.rskid=R.rskid where R.projid = :projid and R.outputid = :opid and R.type=3 ORDER BY R.id ASC");
	$query_allrisks->execute(array(":projid" => $projid, ":opid" => $opdetailsid));
	$rows_allrisks = $query_allrisks->fetchAll();
	foreach($rows_allrisks as $row)
	{
		$risk .= '<option value="'.$row["rskid"].'">'.$row["category"].'</option>';
	}
	return $risk;
}

function incrementalHash($len = 5){
	$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$base = strlen($charset);
	$result = '';

	$now = explode(' ', microtime())[1];
	while ($now >= $base){
	  $i = $now % $base;
	  $result = $charset[$i] . $result;
	  $now /= $base;
	}

	return substr($result, -5);
}

try{	

require 'authentication.php';

		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	$currentdate = date("Y-m-d");

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pmfrm")) {  
		if(trim($_POST["mainformid"]) !== '' || !empty(trim($_POST["mainformid"])))
		{ 			
			$projid = $_POST['projid'];
			$mainformid = $_POST['mainformid'];
			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$geoerror = $_POST['geoerror'];
			$lessons = $_POST['lessons'];
			$projuser = $_POST['user_name'];
			
			//$mntdate = strtotime($_POST['adate']);
			
			$insertSQL = $db->prepare("INSERT INTO tbl_monitoring (projid, formid, projlatitude, projlongitude, projgeopositionerror, lessons, adate, user_name) VALUES (:projid, :formid, :projlat, :projlong, :projgeopositionerror, :lessons, :adate, :username)");
			$Result1  = $insertSQL->execute(array(":projid" => $projid, ":formid" => $mainformid, ':projlat' => $latitude, ':projlong' => $longitude, ':projgeopositionerror' => $geoerror, ":lessons" => $lessons, ":adate" => $currentdate, ":username" => $projuser));
			
			$count = count($_POST["opid"]);
			for($k=0; $k<$count; $k++){
				$insertquery = $db->prepare("INSERT INTO tbl_monitoringoutput (formid, opid, projid, actualoutput, created_by, date_created) VALUES (:formid, :opid, :projid, :output, :username, :adate)");
				$insertquery->execute(array(":formid" => $mainformid, ':opid' => $_POST['opid'][$k], ":projid" => $projid, ':output' =>  $_POST['outputprogress'][$k], ":username" => $projuser, ":adate" => $currentdate));
			}
			
			//  $affected = $mysqli -> affected_rows;
			if($Result1){	
				$origin = "monitoring";
				if(trim($_POST["opid"]) !== '' || !empty(trim($_POST["opid"]))){
					$opcount = count($_POST["opid"]);
					for($j=0; $j<$opcount; $j++)
					{
						$projopid = $_POST['opid'][$j];
						$opdetailsid = $_POST["opdetailsid"][$j];
						if(trim($_POST["progress".$projopid]) !== '' || !empty(trim($_POST["progress".$projopid]))){
							$number = count($_POST["progress".$projopid]);
							for($i=0; $i<$number; $i++)
							{
								$tskPrgid = $_POST['tskid'.$projopid][$i];
								$tskPrg = trim($_POST['progress'.$projopid][$i]);
								//var_dump($tskPrg);
								$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
								$query_rsTasks->execute(array(":tkid" => $tskPrgid));	
								$row_rsTasks = $query_rsTasks->fetch();
								$taskProgress = $row_rsTasks["progress"];
								$taskStatus = $row_rsTasks["status"];
								$msid = $row_rsTasks["msid"];
								
								$SQLinsert = $db->prepare("INSERT INTO tbl_task_progress (formid, opid, opdetailsid, tkid, progress, date) VALUES (:formid, :opid, :opdetailsid, :tkid, :progress, :date)");
								$Rst  = $SQLinsert->execute(array(':formid' => $mainformid, ":opid" => $projopid, ":opdetailsid" => $opdetailsid, ":tkid" =>$tskPrgid, ':progress' => $tskPrg, ":date" => $currentdate));
						
								if($Rst){
									if($taskStatus == 2 || $taskStatus == 6)
									{
										$tskStatus = $taskStatus;
									}
									else{
										if($tskPrg == 0)
										{
											$tskStatus = 11;
										}
										elseif($tskPrg > 0 && $tskPrg < 100)
										{
											$tskStatus = 4;
										}
										elseif($tskPrg == $taskProgress)
										{
											$tskStatus = 11;
										}
										elseif($tskPrg == 100)
										{
											$tskStatus = 5;
										}
										/* else
										{
											$tskStatus = "Task In Progress";
										} */
									}
								
									$SQLUpdate = $db->prepare("UPDATE tbl_task SET progress = :tskPrg, monitored = :mon, status = :status WHERE tkid = :tkid");
									$updt = $SQLUpdate->execute(array(':tskPrg' => $tskPrg, ':mon' => '1', ':status' => $tskStatus, ':tkid' => $tskPrgid));
							
									$query_rsMSStatus = $db->prepare("SELECT COUNT(*) as num, MIN(sdate) AS SmallestDate, MAX(edate) AS BiggestDate, COUNT(CASE WHEN status = 4 THEN 1 END) AS `Task In Progress`, COUNT(CASE WHEN status = 5 THEN 1 END) AS `Completed Task`, COUNT(CASE WHEN status = 3 THEN 1 END) AS `Pending Task`, COUNT(CASE WHEN status = 11 THEN 1 END) AS `Behind Schedule`, COUNT(CASE WHEN status = 9 THEN 1 END) AS `Overdue Task`,  COUNT(status) AS 'Total Task', sum(progress) AS tskprogress FROM tbl_task WHERE msid  = '$msid'");
									$query_rsMSStatus->execute();
									$row_rsMSStatus = $query_rsMSStatus->fetch();
									
									$progressaverage = $row_rsMSStatus['tskprogress']/$row_rsMSStatus['num'];
									$percentmsprog = round($progressaverage,2);
									
									$updatems = $db->prepare("UPDATE tbl_milestone SET progress = :msPrg WHERE msid = :msid");
									$updatems->execute(array(':msPrg' => $percentmsprog, ':msid' => $msid));
								}
								else{ 
									$msg = 'Can not insert progress!!';
									$results = "<script type=\"text/javascript\">
										swal({
										title: \"Error!\",
										text: \" $msg \",
										type: 'Danger',
										timer: 10000,
										showConfirmButton: false });
									</script>";
								}
							}
						}
									
						$observtype = $_POST['observtype'.$projopid];
						if($observtype == 2){
							$nmb = count($_POST["issuedescription".$projopid]);
							for($k=0; $k<$nmb; $k++)
							{
								if(trim($_POST["issuedescription".$projopid][$k]) !== '' || !empty(trim($_POST["issuedescription".$projopid][$k])))
								{
									$SQLinsert = $db->prepare("INSERT INTO tbl_projissues (formid, projid, origin, opid, risk_category, observation, created_by, date_created) VALUES (:formid, :projid, :origin, :opid, :riskcat, :obsv, :user, :date)");
									$Rst  = $SQLinsert->execute(array(":formid" =>$mainformid, ":projid" => $projid, ':origin' => $origin, ':opid' => $projopid, ':riskcat' => $_POST['issue'.$projopid][$k], ':obsv' => $_POST['issuedescription'.$projopid][$k], ':user' => $projuser, ':date' => $currentdate));
								}
							}
						}
						else{
							$observ = $_POST['observation'.$projopid];
							
							$insertquery = $db->prepare("INSERT INTO tbl_monitoring_observations (projid, formid, opid, observation, created_by, date_created) VALUES (:projid, :formid, :opid, :observ, :user, :date)");
							$insertquery->execute(array(':projid' => $projid, ':formid' => $mainformid, ':opid' => $projopid, ':observ' => $observ, ':user' => $projuser, ':date' => $currentdate));
						}
						
						$stage = 11;
						$myprojid = $projid;
						$filecategory = $mainformid;
						$count = count($_POST["attachmentpurpose".$projopid]);
						
						for($cnt=0; $cnt<$count; $cnt++)
						{ 
							//Check that we have a file
							if(!empty($_FILES['monitorattachment'.$projopid]['name'][$cnt])) {
								$purpose = $_POST["attachmentpurpose".$projopid][$cnt];
								//Check if the file is JPEG image and it's size is less than 350Kb
								$filename = basename($_FILES['monitorattachment'.$projopid]['name'][$cnt]);
							  
								$ext = substr($filename, strrpos($filename, '.') + 1);
								  
								if (($ext != "exe") && ($_FILES["monitorattachment".$projopid]["type"][$cnt] != "application/x-msdownload"))  {
									//Determine the path to which we want to save this file      
									//$newname = dirname(__FILE__).'/upload/'.$filename;
									$newname=$projid."-".$filecategory."-".$projopid."-".$filename; 
									if($ext == "jpg" || $ext == "png" || $ext == "jpeg"){
										$filepath="uploads/monitoring/photos/".$newname;        
										//Check if the file with the same name already exists in the server
										if (!file_exists($filepath)) {
											//Attempt to move the uploaded file to it's new place
											if(move_uploaded_file($_FILES['monitorattachment'.$projopid]['tmp_name'][$cnt],$filepath)) {
												//successful upload
												$qry2 = $db->prepare("INSERT INTO tbl_project_photos (`projid`, `projstage`, `fcategory`, `filename`, `ftype`, `description`, `floc`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fcat, :filename, :ftype, :desc, :floc, :user, :date)");
												$qry2->execute(array(':projid' => $projid, ':stage' => $stage, ':fcat' => $filecategory, ':filename' => $newname, ":ftype" => $ext, ":desc" => $purpose, ":floc" => $filepath, ':user' => $projuser, ':date' => $currentdate));								
											}	
										}
										else{ 
											$msg = 'File you are uploading already exists, try another file!!';
											$results = "<script type=\"text/javascript\">
												swal({
												title: \"Error!\",
												text: \" $msg \",
												type: 'Danger',
												timer: 10000,
												showConfirmButton: false });
											</script>";
										}
									}	
									else {
										$filepath="uploads/monitoring/other-files/".$newname;        
										//Check if the file with the same name already exists in the server
										if (!file_exists($filepath)) {
											//Attempt to move the uploaded file to it's new place
											if(move_uploaded_file($_FILES['monitorattachment'.$projopid]['tmp_name'][$cnt],$filepath)) {
												//successful upload
												$qry2 = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :filename, :ftype, :floc, :fcat, :desc, :user, :date)");
												$qry2->execute(array(':projid' => $projid, ':stage' => $stage, ':filename' => $newname, ":ftype" => $ext, ":floc" => $filepath, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $projuser, ':date' => $currentdate));								
											}
										}
										else{ 
											$msg = 'File you are uploading already exists, try another file!!';
											$results = "<script type=\"text/javascript\">
												swal({
												title: \"Error!\",
												text: \" $msg \",
												type: 'Danger',
												timer: 10000,
												showConfirmButton: false });
											</script>";
										}
									}		  
								}
								else{  
									$msg = 'This file type is not allowed, try another file!!';
									$results = "<script type=\"text/javascript\">
										swal({
										title: \"Error!\",
										text: \" $msg \",
										type: 'Danger',
										timer: 10000,
										showConfirmButton: false });
									</script>";
								}		
							}
							else{   
								$msg = 'You have not attached any file!!';
								$results = "<script type=\"text/javascript\">
									swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 10000,
									showConfirmButton: false });
								</script>";
							}
						}
						
						/* $query_rsProjStatus = $db->prepare("SELECT projstatus, datafrequency FROM tbl_projects WHERE projid = :projid");
						$query_rsProjStatus->execute(array(":projid" => $_POST['projstate']));	
						$row_rsProjStatus = $query_rsProjStatus->fetch();
						$datafreq = $row_rsProjStatus["datafrequency"];
						
						if(!empty($datafreq)){		
							$query_rsDataFreq = $db->prepare("SELECT days FROM tbl_datacollectionfreq WHERE fqid = :datafreq");
							$query_rsDataFreq->execute(array(":datafreq" => $datafreq));	
							$row_rsDataFreq = $query_rsDataFreq->fetch();
							
							$FreqDays = $row_rsDataFreq["days"];

							$nextmonitoringdate = date("Y-m-d", strtotime("+$FreqDays", strtotime($currentdate)));
							
							$SQLUpdatemonitor = $db->prepare("UPDATE tbl_projects SET projfirstmonitor = :nxtmndate WHERE projid = :projid");
							$update = $SQLUpdatemonitor->execute(array(':nxtmndate' => $nextmonitoringdate, ':projid' => $projid));
						} */
						$msg = 'The Project Successfully Monitored.';
						$results = "<script type=\"text/javascript\">
								swal({
									title: \"Success!\",
									text: \" $msg\",
									type: 'Success',
									timer: 5000,
									showConfirmButton: false });
								setTimeout(function(){
									window.location.href = 'projects-monitoring';
								}, 5000);
							</script>";	
					}		
				}
			}				
		}else{
			$type = 'error';
			$msg = 'Please fill all mandatory fields and try again.';
					
			$results = "<script type=\"text/javascript\">
				swal({
				title: \"Error!\",
				text: \" $msg \",
				type: 'Danger',
				timer: 10000,
				showConfirmButton: false });
			</script>";
		}
	} 

	if (isset($_GET['projid'])) {
	  $projid = $_GET['projid'];
	}
	
	$query_rsMyP = $db->prepare("SELECT p.*, m.milestone, m.msid, COUNT(CASE WHEN m.status = 5 THEN 1 END) AS `Completed`, COUNT(CASE WHEN m.status = 4 THEN 1 END) AS `In Progress`, COUNT(CASE WHEN m.status = 3 THEN 1 END) AS `Pending`, COUNT(m.status) AS 'Total Status' FROM tbl_projects p INNER JOIN tbl_milestone m ON m.projid = p.projid WHERE p.user_name = :username AND p.projid = :projid");
	$query_rsMyP->execute(array(":projid" => $projid, ":username" => $username));	
	$row_rsMyP = $query_rsMyP->fetch();
	$totalRows_rsMyP = $query_rsMyP->rowCount();
	
	$query_projstatus = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = :status");
	$query_projstatus->execute(array(":status" => $row_rsMyP['projstatus']));	
	$row_projstatus = $query_projstatus->fetch();
	$projstatus = $row_projstatus["statusname"];
	
	/* $query_rsPrjOP = $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid = :projid AND next_monitoring_date = :currentdate ORDER BY opid ASC");
	$query_rsPrjOP->execute(array(":projid" => $projid, ":currentdate" => $currentdate)); */
	
	$query_rsPrjOP = $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid = :projid ORDER BY opid ASC");
	$query_rsPrjOP->execute(array(":projid" => $projid));		
	$row_rsPrjOP = $query_rsPrjOP->fetch();
	$totalRows_rsPrjOP = $query_rsPrjOP->rowCount();
	
	$query_rsDGMethod = $db->prepare("SELECT methods FROM tbl_datagatheringmethods ORDER BY methods ASC");
	$query_rsDGMethod->execute(array(":projid" => $projid));	
	$row_rsDGMethod = $query_rsDGMethod->fetch();
	$totalRows_rsDGMethod = $query_rsDGMethod->rowCount();
	
	$query_rsProjRisk =  $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_output_risks R ON C.rskid=R.rskid ORDER BY R.id ASC");
	$query_rsProjRisk->execute();		
	$totalRows_rsProjRisk = $query_rsProjRisk->rowCount();
	
	

	$queryString_rsMyP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rsMyP") == false && 
			stristr($param, "totalRows_rsMyP") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rsMyP = sprintf("&totalRows_rsMyP=%d%s", $totalRows_rsMyP, $queryString_rsMyP);
						
	

	$pmtid = incrementalHash();
	

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Projtrac M&E - Project Result-Based Monitoring</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />

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
	
	<script src="ckeditor/ckeditor.js"></script>

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

	<?php
	$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
	$query_rsMlsProg->execute(array(":projid" => $projid));	
	$row_rsMlsProg = $query_rsMlsProg->fetch();
						
	$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

	$percent2 = round($prjprogress,2);
	?>
	<style type="text/CSS">

	/* If you want the label inside the progress bar */
	#label {
		text-align: center; /* If you want to center it */
		line-height: 25px; /* Set the line-height to the same as the height of the progress bar container, to center it vertically */
		color: black;
	}

	/* Defining the animation */

	@-webkit-keyframes progress
	{
		to {background-position: 30px 0;}
	}

	@-moz-keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	@keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	/* Set the base of our loader */

	.barBg {
		background:#CCCCCC;
		width:100%;
		height:25px;
		border:1px solid #CCCCCC;
		border-radius: 0px;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		margin-bottom:30px;
	}


	.bar {
		background: #CDDC39;
		width: '<?php echo $percent2; ?>%';
		height: 24px;
		border-radius: 0px;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
	}

	/* Set the linear gradient tile for the animation and the playback */

	.barFill {
		width: 100%;
		height: 25px; 
		-webkit-animation: progress 1s linear infinite;
		-moz-animation: progress 1s linear infinite;
		animation: progress 1s linear infinite;
		background-repeat: repeat-x;
		background-size: 30px 30px;
		background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	}

	/* Here's some predefined widths to control the fill. Add these classes to the "bar" div */

	.ten {
		width: 10%; /* Sets the progress to 10% */
	}	

	.twenty {
		width: 20%; /* Sets the progress to 20% */
	}	

	.thirty {
		width: 30%; /* Sets the progress to 30% */
	}	

	.forty {
		width: 40%; /* Sets the progress to 40% */
	}	

	.fifty {
		width: 50%; /* Sets the progress to 50% */
	}

	.sixty {
		width: 60%; /* Sets the progress to 60% */
	}	

	.seventy {
		width: 70%; /* Sets the progress to 70% */
	}	

	.eighty {
		width: 80%; /* Sets the progress to 80% */
	}	

	.ninety {
		width: 90%; /* Sets the progress to 90% */
	}	

	.hundred {
		width: 100%; /* Sets the progress to 100% */
	}	

	/* Some colour classes to get you started. Add the colour class to the "bar" div */

	.aquaGradient{
		background: -moz-linear-gradient(top,  #7aff32 0%, #54a6e5 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7aff32), color-stop(100%,#54a6e5));
		background: -webkit-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -o-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -ms-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: linear-gradient(to bottom,  #7aff32 0%,#54a6e5 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7aff32', endColorstr='#54a6e5',GradientType=0 );
	}

	.roseGradient {
		background: #ff3232;
		background: -moz-linear-gradient(top,  #ff3232 0%, #ed89ff 47%, #ff8989 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff3232), color-stop(47%,#ed89ff), color-stop(100%,#ff8989));
		background: -webkit-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -o-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -ms-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: linear-gradient(to bottom,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff3232', endColorstr='#ff8989',GradientType=0 );
	}

	.madras { /* Credit to Divya Manian via http://lea.verou.me/css3patterns/#madras */
		background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);; background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);
	}

	.cornflowerblue {
		background-color: CornflowerBlue;
		box-shadow:inset 0px 0px 6px 2px rgba(255,255,255,.3);
		width: '<?php echo $percent2; ?>%';
	}

	.carrot {
		background: #f2a130;
		background: -moz-linear-gradient(-45deg,  #f2a130 0%, #e5bd6e 100%);
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#f2a130), color-stop(100%,#e5bd6e));
		background: -webkit-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -o-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -ms-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: linear-gradient(135deg,  #f2a130 0%,#e5bd6e 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2a130', endColorstr='#e5bd6e',GradientType=1 );

	}
	</style>
    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}  
		.outsideradius, .withinradius{
			display:none;
		}
	</style>
		
	<script type="text/javascript">
	$(document).ready(function() {
		disable_refresh();
	});
	function GetTaskChecklist(tkid, pmtid)
	{
		$.ajax({
			type: 'post',
			url: 'gettaskchecklist.php',
			data: {tskid:tkid, pmtid:pmtid},
			success: function (data) {
				$('#formcontent').html(data);
				 $("#myModal").modal({backdrop: "static"});
			}
		});
	}
	
	// function disable refreshing functionality
	function disable_refresh() {
	  //
	  return (window.onbeforeunload = function(e) {
		return "you can not refresh the page";
	  });
	}
	</script>
</head>

<body class="theme-blue" onload="getLocation()">
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
	<input type="hidden" name="projectid" id="projectid" value="<?php echo $projid; ?>">
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
	<!-- Inner Section -->
		<?php  include_once('monitorproj-inner.php');?>
    <!-- #END# Inner Section -->
	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#tag-form').on('submit', function(event){
			event.preventDefault();
			var taskscore = $("#tskscid").val();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "savechecklistscore.php",
				data:form_data,
				dataType:"json",
				success:function(data)
				{
					$('#'+taskscore).val(data);
					$('#btn'+taskscore).html('Edit Score');
					$('#tag-form')[0].reset();
					$('#myModal').modal('hide');
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	</script>
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">Task CheckList </font></h3>
				</div>
				<form class="tagForm" action="savechecklistscore.php" method="post" id="tag-form" enctype="multipart/form-data">
					<div class="modal-body" id="formcontent">
				
					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Assign Score" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal -->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="current-monitoring-position.js"></script>
	<!-- <script src="../assets/custom/js/.js"></script> -->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANeDcXUz-GQssz7EHTzGGUHU-VPlAtMGY&callback=initMap&libraries=places">
	</script>

    <!-- Jquery Core Js -->
    <script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="projtrac-dashboard/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-steps/jquery.steps.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/form-validation.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>

<?php 

}catch (PDOException $ex){
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>