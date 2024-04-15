<?php
try {

require 'authentication.php';

	if (isset($_GET['projid'])) {
		$myprojectid_rsMyP = $_GET['projid'];
	}

	$query_rsTP =  $db->prepare("SELECT COUNT(projname) FROM tbl_projects WHERE tbl_projects.deleted='0'");
	$query_rsTP->execute();
	$row_rsTP = $query_rsTP->fetch();
	$totalRows_rsTP = $query_rsTP->rowCount();

	$query_rsTPList =  $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects WHERE tbl_projects.deleted='0' GROUP BY projname");
	$query_rsTPList->execute();
	$row_rsTPList = $query_rsTPList->fetch();
	$totalRows_rsTPList = $query_rsTPList->rowCount();

	$query_rsTPM =  $db->prepare("SELECT COUNT(projname) FROM tbl_monitoring");
	$query_rsTPM->execute();
	$row_rsTPM = $query_rsTPM->fetch();
	$totalRows_rsTPM = $query_rsTPM->rowCount();

	$maxRows_rsMyP = 50;
	$pageNum_rsMyP = 0;
	if (isset($_GET['pageNum_rsMyP'])) {
		$pageNum_rsMyP = $_GET['pageNum_rsMyP'];
	}
	
	$startRow_rsMyP = $pageNum_rsMyP * $maxRows_rsMyP;
	if (isset($_GET['projid'])) {
		$projectid_rsMyP = $_GET['projid'];
	}

	$query_rsMyP =  $db->prepare("SELECT tbl_projects.*, tbl_projects.projchangedstatus AS projchangedstatus, FORMAT(tbl_projects.projcost, 2), tbl_projects.projstartdate AS sdate, tbl_projects.projenddate AS edate, tbl_milestone.milestone, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`,    COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`,   COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,   COUNT(tbl_milestone.status) AS 'Total Status', @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_milestone ON tbl_projects.projid = tbl_milestone.projid WHERE tbl_projects.deleted='0' AND tbl_projects.user_name = '$user_name' AND tbl_projects.projid = '$projectid_rsMyP'");
	$query_rsMyP->execute();
	$row_rsMyP = $query_rsMyP->fetch();

	$subcounty = $row_rsMyP['projcommunity'];
	$ward = $row_rsMyP['projlga'];
	$location = $row_rsMyP['projstate'];
	$datafreq = $row_rsMyP['datafrequency'];
	$stdate = $row_rsMyP['projstartdate'];
	$projectStatus = $row_rsMyP['projstatus'];
	$projectprevstatus = $row_rsMyP['projchangedstatus'];
	$projchangedate = $row_rsMyP['date_deleted'];
	$projchangedby = $row_rsMyP['deleted_by'];
	$projcategory = $row_rsMyP['projcategory'];
	$nxtmonitoringdate = $row_rsMyP["projfirstmonitor"];

	$query_rsProjStatReason =  $db->prepare("SELECT tbl_projects.*, DATE_FORMAT( tbl_projstatuschangereason.date_entered,  '%%d %%M %%Y' ) AS rsdate, tbl_projstatuschangereason.type AS type, tbl_projstatuschangereason.id AS reasonid, @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_projstatuschangereason ON tbl_projects.projid = tbl_projstatuschangereason.projid WHERE tbl_projects.deleted='0' AND tbl_projects.user_name = '$user_name' AND tbl_projects.projid='$projectid_rsMyP' ORDER BY type ASC");
	$query_rsProjStatReason->execute();
	$row_rsProjStatReason = $query_rsProjStatReason->fetch();

	$query_rsSubCounty =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$subcounty'");
	$query_rsSubCounty->execute();
	$row_rsSubCounty = $query_rsSubCounty->fetch();
	$totalRows_rsSubCounty = $query_rsSubCounty->rowCount();

	if ($projectStatus == "On Hold") {
		$query_rsProjStatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = '2' OR statusid = '10'");
		$query_rsProjStatus->execute();
		$row_rsProjStatus = $query_rsProjStatus->fetch();
		$totalRows_rsProjStatus = $query_rsProjStatus->rowCount();
	} else {
		$query_rsProjStatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = '2' OR statusid = '6'");
		$query_rsProjStatus->execute();
		$row_rsProjStatus = $query_rsProjStatus->fetch();
		$totalRows_rsProjStatus = $query_rsProjStatus->rowCount();
	}

	$query_rsWard =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$ward'");
	$query_rsWard->execute();
	$row_rsWard = $query_rsWard->fetch();
	$totalRows_rsWard = $query_rsWard->rowCount();

	$query_rsLocation =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$location'");
	$query_rsLocation->execute();
	$row_rsLocation = $query_rsLocation->fetch();
	$totalRows_rsLocation = $query_rsLocation->rowCount();



	if ($row_rsSubCounty['state'] == "All") {
		$projlocation = $row_rsSubCounty['state'] . ' ' . $level1labelplural . '; ' . $row_rsWard['state'] . ' ' . $level2labelplural . '; ' . $row_rsLocation['state'] . ' ' . $level3labelplural;
	} else {
		$projlocation = $row_rsSubCounty['state'] . ' ' . $level1label . '; ' . $row_rsWard['state'] . ' ' . $level2label . '; ' . $row_rsLocation['state'] . ' ' . $level3label;
	}

	if (isset($_GET['totalRows_rsMyP'])) {
		$totalRows_rsMyP = $_GET['totalRows_rsMyP'];
	} else {
		$totalRows_rsMyP = $query_rsMyP->rowCount();
	}
	$totalPages_rsMyP = ceil($totalRows_rsMyP / $maxRows_rsMyP) - 1;

	$query_rsUsers =  $db->prepare("SELECT * FROM tbl_projteam WHERE user_name = '$user_name'");
	$query_rsUsers->execute();
	$row_rsUsers = $query_rsUsers->fetch();
	$totalRows_rsUsers = $query_rsUsers->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsPrjid = $_GET['projid'];
	}

	$query_rsMyPrjDet =  $db->prepare("SELECT tbl_projects.*, tbl_outputs.output AS output, tbl_indicator.indname AS indicator, tbl_expprojoutput.expoutputvalue AS target, tbl_expprojoutput.outputbaseline AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput ON tbl_projects.projid = tbl_expprojoutput.projid  LEFT JOIN tbl_indicator ON tbl_expprojoutput.expoutputindicator = tbl_indicator.indid  LEFT JOIN tbl_outputs ON tbl_expprojoutput.expoutputname = tbl_outputs.opid WHERE tbl_projects.deleted='0' AND tbl_projects.user_name = '$user_name' AND tbl_projects.projid = '$projectid_rsMyP'");
	$query_rsMyPrjDet->execute();
	$row_rsMyPrjDet = $query_rsMyPrjDet->fetch();

	$query_rsMyPrjFund =  $db->prepare("SELECT g.sourcecategory AS source, FORMAT(j.amountfunding, 2) AS amount, c.code AS currency, d.donorname AS funder FROM tbl_myprojfunding j inner join tbl_myprogfunding g LEFT JOIN tbl_currency c ON j.currency = c.id  LEFT JOIN tbl_donors d ON g.sourceid = d.dnid WHERE j.projid = '$colname_rsPrjid'");
	$query_rsMyPrjFund->execute();
	$row_rsMyPrjFund = $query_rsMyPrjFund->fetch();
	$totalRows_rsMyPrjFund = $query_rsMyPrjFund->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsPDecr = $_GET['projid'];
	}

	$query_rsPDecr =  $db->prepare("SELECT LEFT(projdesc, 500) FROM tbl_projects WHERE tbl_projects.deleted='0' AND projid = '$colname_rsPDecr'");
	$query_rsPDecr->execute();
	$row_rsPDecr = $query_rsPDecr->fetch();
	$totalRows_rsPDecr = $query_rsPDecr->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsExpOutcome = $_GET['projid'];
	}
	$query_rsExpOutcome =  $db->prepare("SELECT LEFT(Projexpoutcome, 500) FROM tbl_projects WHERE tbl_projects.deleted='0' AND projid = '$colname_rsExpOutcome'");
	$query_rsExpOutcome->execute();
	$row_rsExpOutcome = $query_rsExpOutcome->fetch();
	$totalRows_rsExpOutcome = $query_rsExpOutcome->rowCount();


	if (isset($_GET['projid'])) {
		$colname_rsAssump = $_GET['projid'];
	}
	$query_rsAssump =  $db->prepare("SELECT LEFT(assumptions, 500) FROM tbl_projects WHERE tbl_projects.deleted='0' AND projid = '$colname_rsAssump'");
	$query_rsAssump->execute();
	$row_rsAssump = $query_rsAssump->fetch();
	$totalRows_rsAssump = $query_rsAssump->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsDataFreqId = $_GET['projid'];
		$query_rsDataFreqID =  $db->prepare("SELECT datafrequency FROM tbl_projects WHERE projid = '$colname_rsDataFreqId'");
		$query_rsDataFreqID->execute();
		$row_rsDataFreqID = $query_rsDataFreqID->fetch();
		$colname_rsDataCollFreqID = $row_rsDataFreqID["datafrequency"];
	}

	$query_rsDataFreq =  $db->prepare("SELECT frequency FROM tbl_datacollectionfreq WHERE fqid = '$colname_rsDataCollFreqID'");
	$query_rsDataFreq->execute();
	$row_rsDataFreq = $query_rsDataFreq->fetch();

	if (isset($_GET['projid'])) {
		$colname_rsMSProgress = $_GET['projid'];
	}
	$query_rsMSProgress =  $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`, COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`, COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,  COUNT(tbl_milestone.status) AS 'Total Status' FROM tbl_projects  INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid  WHERE tbl_projects.deleted='0' AND tbl_projects.projid = '$colname_rsMSProgress' GROUP BY tbl_projects.projid");
	$query_rsMSProgress->execute();
	$row_rsMSProgress = $query_rsMSProgress->fetch();
	$totalRows_rsMSProgress = $query_rsMSProgress->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsProgress = $_GET['projid'];
	}
	$query_rsProgress =  $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, COUNT(CASE WHEN tbl_task.status = 'Completed' THEN 1 END) AS `Completed`,  COUNT(tbl_task.status) AS 'Total Status' FROM tbl_projects  INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid INNER JOIN tbl_task ON tbl_milestone.msid=tbl_task.msid WHERE tbl_projects.deleted='0' AND tbl_projects.projid = '$colname_rsProgress' GROUP BY tbl_projects.projid");
	$query_rsProgress->execute();
	$row_rsProgress = $query_rsProgress->fetch();
	$totalRows_rsProgress = $query_rsProgress->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsMilestone = $_GET['projid'];
	}
	$query_rsMilestone =  $db->prepare("SELECT tbl_milestone.msid, tbl_milestone.milestone, tbl_milestone.progress, tbl_milestone.status, tbl_milestone.milestonebudget, tbl_milestone.sdate, tbl_milestone.edate FROM tbl_milestone LEFT JOIN tbl_projects ON tbl_projects.projid=tbl_milestone.projid WHERE tbl_projects.projid = '$colname_rsMilestone' GROUP BY tbl_milestone.msid");
	$query_rsMilestone->execute();
	$row_rsMilestone = $query_rsMilestone->fetch();
	$totalRows_rsMilestone = $query_rsMilestone->rowCount();

	$query_rsProjAssumptions =  $db->prepare("SELECT C.category FROM tbl_projectrisks R INNER JOIN tbl_projrisk_categories C ON R.rskid=C.rskid WHERE R.projid = '$colname_rsMilestone' GROUP BY C.rskid");
	$query_rsProjAssumptions->execute();
	$cntAssump = $query_rsProjAssumptions->rowCount();

	$projcat = $row_rsMyP["projcategory"];

	if ($projcat == '2') {
		$tenderid = $row_rsMyP["projtender"];
		$contractorid = $row_rsMyP["projcontractor"];

		$query_tenderDetails =  $db->prepare("SELECT D.*, T.type, C.category AS cat FROM tbl_tenderdetails D inner join tbl_tendertype T ON T.id=D.tendertype inner join tbl_tendercategory C ON C.id=D.tendercat WHERE td_id = '$tenderid'");
		$query_tenderDetails->execute();
		$tenderDetails = $query_tenderDetails->fetch();
		$tendercount = $query_tenderDetails->rowCount();

		$query_contractor =  $db->prepare("SELECT contractor_name FROM tbl_contractor WHERE contrid = '$contractorid'");
		$query_contractor->execute();
		$contractor = $query_contractor->fetch();
	}

	$queryString_rsMyP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_rsMyP") == false && stristr($param, "totalRows_rsMyP") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_rsMyP = sprintf("&totalRows_rsMyP=%d%s", $totalRows_rsMyP, $queryString_rsMyP);

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Projtrac M&E - Project Logframe</title>
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

	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

	<!-- Sweet Alert Css -->
	<link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

	<!-- Custom Css -->
	<link href="projtrac-dashboard/css/style.css" rel="stylesheet">

	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

	<script type="text/javascript">
		$(document).ready(function() {
			$(".account").click(function() {
				var X = $(this).attr('id');

				if (X == 1) {
					$(".submenus").hide();
					$(this).attr('id', '0');
				} else {

					$(".submenus").show();
					$(this).attr('id', '1');
				}

			});

			//Mouseup textarea false
			$(".submenus").mouseup(function() {
				return false
			});
			$(".account").mouseup(function() {
				return false
			});


			//Textarea without editing.
			$(document).mouseup(function() {
				$(".submenus").hide();
				$(".account").attr('id', '');
			});

		});
	</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

	<?php
	$projectID =  $row_rsMyP['projid'];
	$currentStatus =  $row_rsMyP['projstatus'];

	$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$projectID'");
	$query_rsMlsProg->execute();
	$row_rsMlsProg = $query_rsMlsProg->fetch();

	$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

	$percent2 = round($prjprogress, 2);
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
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		margin-bottom:30px;
		border-radius: 0px;
	}


	.bar {
		background: #CDDC39;
		width: '<?php echo $percent2; ?>%';
		height:24px;
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
	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
* html .cf { zoom: 1; }
*:first-child+html .cf { zoom: 1; }
h1 { font-size: 1.75em; margin: 0 0 0.6em 0; }
a { color: #2996cc; }
a:hover { text-decoration: none; }
p { line-height: 1.5em; }
.small { color: #666; font-size: 0.875em; }
.large { font-size: 1.25em; }
/**
 * Nestable
 */
.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 100%; list-style: none; font-size: 13px; line-height: 20px; }
.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
.dd-list .dd-list { padding-left: 30px; }
.dd-collapsed .dd-list { display: none; }
.dd-item,
.dd-empty,
.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }
.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd-handle:hover { color: #2ea8e5; background: #fff; }
.dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
.dd-item > button[data-action="collapse"]:before { content: '-'; }
.dd-placeholder,
.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                      -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                              linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}
.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
            box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
}
/**
 * Nestable Extras
 */
.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }
#nestable-menu { padding: 0; margin: 20px 0; }
#nestable-output,
#nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }
#nestable2 .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
    background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
    background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
    background:         linear-gradient(top, #bbb 0%, #999 100%);
}
#nestable2 .dd-handle:hover { background: #bbb; }
#nestable2 .dd-item > button:before { color: #fff; }
@media only screen and (min-width: 100%) {
    .dd { float: left; width: 48%; }
    .dd + .dd { margin-left: 2%; }
}
.dd-hover > .dd-handle { background: #2ea8e5 !important; }
/**
 * Nestable Draggable Handles
 */
.dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd3-content:hover { color: #2ea8e5; background: #fff; }
.dd-dragel > .dd3-item > .dd3-content { margin: 0; }
.dd3-item > button { margin-left: 30px; }
.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
.dd3-handle:hover { background: #ddd; }
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
	</style>
</head>

<body class="theme-blue">
	<!-- Page Loader -->
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
	<?php
	$pid = $row_rsMyP['projid'];
	$query_rsSDate =  $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
	$query_rsSDate->execute();
	$row_rsSDate = $query_rsSDate->fetch();

	$projstartdate = $row_rsSDate["projstartdate"];
	//$start_date = date_format($projstartdate, "Y-m-d");
	$current_date = date("Y-m-d");

	$tndprojid = $row_rsMyP['projid'];
	$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$tndprojid'");
	$query_rsTender->execute();
	$row_rsTender = $query_rsTender->fetch();
	$totalRows_rsTender = $query_rsTender->rowCount();
	?>
	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	<!-- #END# Overlay For Sidebars ->
    <!-- Search Bar -->
	<div class="search-bar">
		<div class="search-icon">
			<i class="material-icons">search</i>
		</div>
		<input type="text" placeholder="START TYPING...">
		<div class="close-search">
			<i class="material-icons">close</i>
		</div>
	</div>
	<!-- #END# Search Bar -->
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
	<?php include_once('project-financial-details-inner.php'); ?>
	<!-- #END# Inner Section -->

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

	<!-- Jquery Nestable -->
	<script src="projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>

	<!-- Custom Js -->
	<script src="projtrac-dashboard/js/admin.js"></script>
	<script src="projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>

</body>

</html>

<?php 
} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>