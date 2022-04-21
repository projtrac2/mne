<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

				//Save cart items
if(isset($_POST['ckids'])){
	$tskid = $_POST['tskscid'];
	
	for($i = 0; $i<sizeof($_POST['ckids']); $i++)
	{
		$ckids = $_POST['ckids'][$i];
		$frmid = $_POST['frmid'][$i];
		$scores = $_POST['scores'.$ckids];	
		
		$scoredate= date('Y-m-d');
		$query_checklistid = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklistid='$ckids' and formid='$frmid'");
		$query_checklistid->execute();	
		$total_checklistid = $query_checklistid->rowCount();
		
		if($total_checklistid == 0){
			//create SQL insert statement			  
			$statusquery = $db->prepare("INSERT INTO tbl_project_monitoring_checklist_score (taskid, checklistid, formid, score, date) VALUES (:taskid, :checklistid, :formid, :score, :date)");
			$insertnot = $statusquery->execute(array(':taskid' => $tskid, ':checklistid' => $ckids, ':formid' => $frmid, ':score' => $scores, ':date' => $scoredate));
		}else{
			$sqlUpdate = $db->prepare("UPDATE tbl_project_monitoring_checklist_score SET score = :score WHERE checklistid =:checklistid and formid =:frmid");
			$sqlUpdate->execute(array(':score' => $scores, ':checklistid' => $ckids, ':frmid' => $frmid));	
		}
	}

	$query_checklistscore = $db->prepare("SELECT sum(score) AS totalscore FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and formid='$frmid'");
	$query_checklistscore->execute();	
	$row = $query_checklistscore->fetch();
	$total_checklistscore = $row["totalscore"];
	$percscore = round(($total_checklistscore/ ($i * 10)) * 100, 2);
	
	echo json_encode($percscore);
}
/* if(isset($_POST['memberid'])){
	$mbrid = $_POST['memberid'];
	
	echo json_encode($percscore);
} */
?>
