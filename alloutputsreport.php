<?php
require 'authentication.php';

try {
	if (isset($_GET['projid'])) {
		$projectid_rsMyP = $_GET['projid'];
	}

	$query_rsMyP =  $db->prepare("SELECT tbl_projects.*, FORMAT(tbl_projects.projcost, 2), DATE_FORMAT( tbl_projects.projstartdate,  '%%d %%M %%Y' ) AS sdate, DATE_FORMAT( tbl_projects.projenddate,  '%%d %%M %%Y' ) AS edate, tbl_milestone.milestone, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`,    COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`,   COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,   COUNT(tbl_milestone.status) AS 'Total Status', @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_milestone ON tbl_projects.projid = tbl_milestone.projid WHERE tbl_projects.user_name = '$user_name' AND tbl_projects.projid='$projectid_rsMyP' AND tbl_projects.deleted='0'");
	$query_rsMyP->execute();
	$row_rsMyP = $query_rsMyP->fetch();

	if (isset($_GET['totalRows_rsMyP'])) {
		$totalRows_rsMyP = $_GET['totalRows_rsMyP'];
	} else {
		$totalRows_rsMyP = $query_rsMyP->rowCount();
	}
	$totalPages_rsMyP = ceil($totalRows_rsMyP / $maxRows_rsMyP) - 1;


	$maxRows_rsAllOutputs = 50;
	$pageNum_rsAllOutputs = 0;
	if (isset($_GET['pageNum_rsAllOutputs'])) {
		$pageNum_rsAllOutputs = $_GET['pageNum_rsAllOutputs'];
	}
	$startRow_rsAllOutputs = $pageNum_rsAllOutputs * $maxRows_rsMyP;

	if (isset($_GET['pageNum_rsUpP'])) {
		$pageNum_rsUpP = $_GET['pageNum_rsUpP'];
	}
	$startRow_rsUpP = $pageNum_rsUpP * $maxRows_rsUpP;

	if (isset($_GET['srcfyear'])) {
		$pfscyr_rsUpP = $_GET['srcfyear'];
	}

	if (isset($_GET['startdate']) && !empty($_GET['startdate'])) {
		$startdate_rsUpP = date('Y-m-d', strtotime($_GET['startdate']));
	} else {
		$startdate_rsUpP = '1970-01-01';
	}

	if (isset($_GET['enddate']) && !empty($_GET['enddate'])) {
		$enddate_rsUpP = date('Y-m-d', strtotime($_GET['enddate']));
	} else {
		$enddate_rsUpP = date("Y-m-d");
	}

	if (isset($_GET['srcsct'])) {
		$psector_rsUpP = $_GET['srcsct'];
	}

	if (isset($_GET['srcdept'])) {
		$pdept_rsUpP = $_GET['srcdept'];
	}

	if (isset($_GET['srccomm'])) {
		$pcomm_rsUpP = $_GET['srccomm'];
	}

	if (isset($_GET['srcward'])) {
		$pward_rsUpP = $_GET['srcward'];
	}

	if (isset($_GET['srcstate'])) {
		$pstate_rsUpP = $_GET['srcstate'];
	}

	if (isset($_GET["btn_search"])) {
		if (empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE p.deleted ='0' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projcommunity = '$pcomm_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projdepartment = '$pdept_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP'  AND p.projcommunity = '$pcomm_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP'  AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projdepartment = '$pdept_rsUpP'  AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projdepartment = '$pdept_rsUpP'  AND p.projcommunity = '$pcomm_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' GROUP BY o.opid");
		} elseif (empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projdepartment = '$pdept_rsUpP'  AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		} elseif (!empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pward_rsUpP)) {
			$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE  p.deleted ='0' AND p.projsector = '$psector_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pward_rsUpP' GROUP BY o.opid");
		}
		$query_rsAllOutputs->execute();
		$row_rsAllOutputs = $query_rsAllOutputs->fetch();
	} else {
		$query_rsAllOutputs =  $db->prepare("SELECT p.*, o.opid AS outputsid, o.output AS opname, o.*, e.expoutputindicator, SUM(e.expoutputvalue) AS expopvalue, SUM(e.outputbaseline) AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput e ON p.projid = e.projid LEFT JOIN tbl_outputs o ON e.expoutputname = o.opid WHERE p.deleted ='0' GROUP BY o.opid");
	}
	$query_rsAllOutputs->execute();
	$row_rsAllOutputs = $query_rsAllOutputs->fetch();

	if (isset($_GET['totalRows_rsAllOutputs'])) {
		$totalRows_rsAllOutputs = $_GET['totalRows_rsAllOutputs'];
	} else {
		$totalRows_rsAllOutputs = $query_rsAllOutputs->rowCount();
	}
	$totalPages_rsAllOutputs = ceil($totalRows_rsAllOutputs / $maxRows_rsAllOutputs) - 1;

	if (isset($_GET['projid'])) {
		$colname_rsMSProgress = $_GET['projid'];
	}

	$query_rsMSProgress =  $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`, COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`, COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`, COUNT(tbl_milestone.status) AS 'Total Status' FROM tbl_projects INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid  WHERE tbl_projects.projid = '$colname_rsMSProgress' AND tbl_projects.deleted ='0'GROUP BY tbl_projects.projid");
	$query_rsMSProgress->execute();
	$row_rsMSProgress = $query_rsMSProgress->fetch();
	$totalRows_rsMSProgress = $query_rsMSProgress->rowCount();

	if (isset($_GET['projid'])) {
		$colname_rsProgress = $_GET['projid'];
	}
	$query_rsProgress =  $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, COUNT(CASE WHEN tbl_task.status = 'Completed' THEN 1 END) AS `Completed`,  COUNT(tbl_task.status) AS 'Total Status' FROM tbl_projects  INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid INNER JOIN tbl_task ON tbl_milestone.msid=tbl_task.msid WHERE tbl_projects.projid = '$colname_rsProgress' AND tbl_projects.deleted ='0' GROUP BY tbl_projects.projid");
	$query_rsProgress->execute();
	$row_rsProgress = $query_rsProgress->fetch();
	$totalRows_rsProgress = $query_rsProgress->rowCount();

	$query_rsIndActivity =  $db->prepare("SELECT activities FROM tbl_indicator_activities ORDER BY activities ASC");
	$query_rsIndActivity->execute();
	$row_rsIndActivity = $query_rsIndActivity->fetch();
	$totalRows_rsIndActivity = $query_rsIndActivity->rowCount();

	$maxRows_rsAllIndicators = 50;
	$pageNum_rsAllIndicators = 0;
	if (isset($_GET['pageNum_rsAllIndicators'])) {
		$pageNum_rsAllIndicators = $_GET['pageNum_rsAllIndicators'];
	}
	$startRow_rsAllIndicators = $pageNum_rsAllIndicators * $maxRows_rsAllIndicators;

	$query_rsAllIndicators =  $db->prepare("SELECT *, @curRow := @curRow + 1 AS sn FROM tbl_indicator ORDER BY sn");
	$query_rsAllIndicators->execute();
	$row_rsAllIndicators = $query_rsAllIndicators->fetch();

	if (isset($_GET['totalRows_rsAllIndicators'])) {
		$totalRows_rsAllIndicators = $_GET['totalRows_rsAllIndicators'];
	} else {
		$totalRows_rsAllIndicators = $query_rsAllIndicators->rowCount();
	}
	$totalPages_rsAllIndicators = ceil($totalRows_rsAllIndicators / $maxRows_rsAllIndicators) - 1;

	if (isset($_GET['projid'])) {
		$colname_rsIndGrps = $_GET['projid'];
	}

	if (isset($_GET['ingid'])) {
		$indgrpid_rsIndGrps = $_GET['ingid'];
	}
	$query_rsIndGrps =  $db->prepare("SELECT tbl_projects.projid, tbl_indicatorgroup.* FROM tbl_projects left JOIN tbl_indicatorgroup on tbl_projects.projid = tbl_indicatorgroup.projid WHERE tbl_projects.projid = '$colname_rsIndGrps' AND tbl_indicatorgroup.ingid = '$indgrpid_rsIndGrps' AND tbl_projects.deleted='0'");
	$query_rsIndGrps->execute();
	$row_rsIndGrps = $query_rsIndGrps->fetch();
	$totalRows_rsIndGrps = $query_rsIndGrps->rowCount();

	$queryString_rsAllIndicators = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (
				stristr($param, "pageNum_rsAllIndicators") == false &&
				stristr($param, "totalRows_rsAllIndicators") == false
			) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsAllIndicators = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_rsAllIndicators = sprintf("&totalRows_rsAllIndicators=%d%s", $totalRows_rsAllIndicators, $queryString_rsAllIndicators);

	$query_rsYear =  $db->prepare("SELECT DISTINCT projfscyear FROM tbl_projects WHERE projfscyear IS NOT NULL AND deleted='0' ORDER BY projid ASC");
	$query_rsYear->execute();
	$row_rsYear = $query_rsYear->fetch();
	$totalRows_rsYear = $query_rsYear->rowCount();

	$query_rsSector =  $db->prepare("SELECT DISTINCT projsector FROM tbl_projects WHERE projsector IS NOT NULL AND deleted='0'ORDER BY projid ASC");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();

	$query_rsDept =  $db->prepare("SELECT DISTINCT projdepartment FROM tbl_projects WHERE projdepartment IS NOT NULL AND deleted='0' ORDER BY projid ASC");
	$query_rsDept->execute();
	$row_rsDept = $query_rsDept->fetch();
	$totalRows_rsDept = $query_rsDept->rowCount();

	$query_rsPName =  $db->prepare("SELECT projname FROM tbl_projects WHERE deleted='0' ORDER BY projname ASC");
	$query_rsPName->execute();
	$row_rsPName = $query_rsPName->fetch();
	$totalRows_rsPName = $query_rsPName->rowCount();

	$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_projects p inner join tbl_state s on s.id =p.projcommunity WHERE deleted='0' GROUP BY p.projcommunity ORDER BY state ASC");
	$query_rsComm->execute();
	$row_rsComm = $query_rsComm->fetch();
	$totalRows_rsComm = $query_rsComm->rowCount();

	$query_rsWard =  $db->prepare("SELECT id, state FROM tbl_projects p inner join tbl_state s on s.id =p.projlga WHERE deleted='0' GROUP BY p.projlga ORDER BY state ASC");
	$query_rsWard->execute();
	$row_rsWard = $query_rsWard->fetch();
	$totalRows_rsWard = $query_rsWard->rowCount();

	$query_rsState =  $db->prepare("SELECT id, state FROM tbl_projects p inner join tbl_state s on s.id =p.projstate WHERE deleted='0' GROUP BY p.projstate ORDER BY state ASC");
	$query_rsState->execute();
	$row_rsState = $query_rsState->fetch();
	$totalRows_rsState = $query_rsState->rowCount();


	$queryString_rsMyP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (
				stristr($param, "pageNum_rsMyP") == false &&
				stristr($param, "totalRows_rsMyP") == false
			) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_rsMyP = sprintf("&totalRows_rsMyP=%d%s", $totalRows_rsMyP, $queryString_rsMyP);
} catch (PDOException $ex) {
	$result = flashMessage("An error occurred: " . $ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Projtrac M&E - All Outputs Report</title>
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

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
	<script type="text/javascript">
		function GetProjInfo(id) {
			$.ajax({
				type: 'post',
				url: 'getprojinfo.php',
				data: {
					member: id,
					req: '1'
				},
				success: function(data) {
					$('#formcontent').html(data);
					$("#myModal").modal({
						backdrop: "static"
					});
				}
			});
		}
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
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="header">
						<div class="button-demo">
							<span class="label bg-black" style="font-size:19px"><img src="images/proj-icon.png" alt="img" style="vertical-align:middle" /> Reports Menu</span>
							<a href="projgeneralreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">General Report</a>
							<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Outputs Report</a>
							<a href="projfundingreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Financial Report</a>
							<a href="projpendingbillsreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Pending Bills Report</a>
						</div>
					</div>
				</div>
			</div>
			<div class="block-header">
				<?php
				if (isset($_GET["msg"]) && $_GET["type"] == "fail") {
				?>
					<div class="alert alert-warning">
						<strong>Warning!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				} elseif (isset($_GET["msg"]) && $_GET["type"] == "success") {
				?>
					<div class="alert alert-success">
						<strong>Success!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				}
				?>
			</div>
			<!-- Exportable Table -->
			<?php include_once('alloutputsreport-insider.php'); ?>
			<!-- #END# Exportable Table -->
		</div>
	</section>

	<!-- Jquery Core Js -->
	<script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

	<!-- Bootstrap Core Js -->
	<script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

	<!-- Slimscroll Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- Waves Effect Plugin Js -->
	<script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

	<!-- Input Mask Plugin Js -->
	<script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

	<!-- Multi Select Plugin Js -->
	<script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

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
	<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>
	<script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
</body>

</html>