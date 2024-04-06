<?php
try {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';
	include_once("includes/system-labels.php");


	$query_all_projects = $db->prepare("SELECT * FROM tbl_projects WHERE EXTRACT(YEAR FROM projenddate) = 1970 ");
	$query_all_projects->execute();
	$total_all_projects_count = $query_all_projects->rowCount();

	if ($total_all_projects_count > 0) {
		while ($row_all_projects = $query_all_projects->fetch()) {
			$projstartdate = $row_all_projects['projstartdate'];
			$projduration = $row_all_projects['projduration'];
			$projid = $row_all_projects['projid'];
			$projenddate = date('Y-m-d', strtotime($projstartdate . ' + ' . $projduration . ' days'));

			$sql = $db->prepare("UPDATE tbl_projects SET projenddate=:projenddate WHERE projid=:projid");
			$results = $sql->execute(array(':projenddate' => $projenddate, ":projid" => $projid));
		}
	}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
