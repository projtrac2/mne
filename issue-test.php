<?php
include_once 'includes/head-alt.php';



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$user_name = 37;
$projstage = 10;

if (isset($_GET['issueid'])) {
	$issueid = $_GET['issueid'];
}
$newstatus = 11;
$origstatus = 4;
$project = 8;
$evaluation = 0;
$user = $user_name;
$changedon = date("Y-m-d");



$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, projchangedstatus=:projchangedstatus,  projevaluate=:eval, deleted_by=:user, date_deleted=:date WHERE projid=:projid");
$updatest = $updateQuery->execute(array(':projstatus' => $newstatus, ':projchangedstatus' => $origstatus, ':eval' => $evaluation, ':user' => $user, ':date' => $changedon, ':projid' => $project));

if ($updatest) {
	$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$project'");
	$query_rsOrigMilestone->execute();

	while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()) {
		$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
		$OrigMilestoneStatus =  $row_rsOrigMilestone['status'];

		$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:status, changedstatus=:changedstatus, changedby=:user, datechanged=:date WHERE msid=:msid");
		$UpdateMil = $updateQuery->execute(array(':status' => $newstatus, ':changedstatus' => $OrigMilestoneStatus, ':user' => $user, ':date' => $changedon, ':msid' => $OrigMilestoneID));

		if ($UpdateMil) {
			$query_rsMilestone =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
			$query_rsMilestone->execute();

			while ($row_rsMilestone = $query_rsMilestone->fetch()) {
				$row_rsOrigTaskID = $row_rsMilestone["tkid"];
				$row_rsOrigTask = $row_rsMilestone["status"];

				$SQLUpdates = $db->prepare("UPDATE tbl_task SET status=:status, changedstatus=:changedstatus WHERE tkid=:tkid");
				$SQLUpdates->execute(array(':status' => $newstatus, ':changedstatus' => $row_rsOrigTask, ':tkid' => $row_rsOrigTaskID));
			}
		}
	}
}

echo "successful!!";
