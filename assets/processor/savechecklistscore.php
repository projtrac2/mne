<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';

//Save cart items
if (isset($_POST['ckids'])) {
	$lev3id = $_POST['level3'];
	$responsible = $_POST['user_name'];
	$scoredate = date('Y-m-d');
	$tskid = $_POST['tskscid'];
	$milestone_id = $_POST['milestone_id'];
	$mne_code = $_POST['mne_code'];
	$lev4id = null;
	$formid = $_POST['formid'];

	$deleteQuery = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist_score` WHERE formid=:formid AND milestone_id <> :milestone_id");
	$results = $deleteQuery->execute(array(':formid' => $formid, ":milestone_id" => $milestone_id));

	for ($i = 0; $i < sizeof($_POST['ckids']); $i++) {
		$ckids = $_POST['ckids'][$i];
		$frmid = $_POST['frmid'][$i];
		$scores = $_POST['scores' . $ckids];
		$query_checklistid = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklistid=:ckids and formid=:frmid AND responsible=:responsible ");
		$query_checklistid->execute(array(":ckids" => $ckids, ":frmid" => $frmid, ':responsible' => $responsible));
		$total_checklistid = $query_checklistid->rowCount();

		if ($total_checklistid == 0) {
			$statusquery = $db->prepare("INSERT INTO tbl_project_monitoring_checklist_score (mne_code,milestone_id,taskid, checklistid, formid, score, level3, level4,responsible, date) VALUES (:mne_code,:milestone_id,:taskid, :checklistid,  :formid, :score, :level3, :level4,:responsible,  :date)");
			$insertnot = $statusquery->execute(array('mne_code' => $mne_code, ":milestone_id" => $milestone_id, ':taskid' => $tskid, ':checklistid' => $ckids, ':formid' => $frmid, ':score' => $scores, ":level3" => $lev3id, ':level4' => $lev4id, ':responsible' => $responsible, ':date' => $scoredate));
		} else {
			$sqlUpdate = $db->prepare("UPDATE tbl_project_monitoring_checklist_score SET score = :score WHERE checklistid =:checklistid and formid =:frmid");
			$sqlUpdate->execute(array(':score' => $scores, ':checklistid' => $ckids, ':frmid' => $frmid));
		}
	}

	$query_checklistscore = $db->prepare("SELECT sum(score) AS totalscore FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and formid='$frmid'");
	$query_checklistscore->execute();
	$row = $query_checklistscore->fetch();
	$total_checklistscore = $row["totalscore"];
	$percscore = round(($total_checklistscore / ($i * 10)) * 100, 2);
	echo json_encode($percscore);
}
