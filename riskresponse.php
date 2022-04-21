<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	if (isset($_POST['projcode'])) {
		$projid = $_POST['projid'];
		$projcode = $_POST['projcode'];
		$projstatuschange = $_POST['projstatuschange'];
		$projchangedstatus = $_POST['projchangedstatus'];
		$projectactioncomments = $_POST['notes'];
		$eval = $_POST['evaluation'];
		$user = $_POST['username'];
		$changedon = date("Y-m-d H:i:s");
		
		if($projstatuschange == "Restored"){
			$riskresponse = $db->prepare("INSERT INTO tbl_projstatuschangereason (projid,status,type,reason,entered_by,date_entered) VALUES (:projid, :status, :type, :reason, :date, :user)");
			$riskresponse->execute(array(':projid' => $projid, ':status' => $projstatuschange, ':type' => 2, ':reason' => $projectactioncomments, ':date' => $changedon, ':user' => $user));
			
			$last_record = $db->lastInsertId();
			
			//upload random name/number
			$rd2 = mt_rand(1000,9999)."_File"; 
			 
			 //Check that we have a file
			if(!empty($_FILES["fileToUpload"])) {
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['fileToUpload']['name']);
			  
				$ext = substr($filename, strrpos($filename, '.') + 1);
			  
				if (($ext != "exe") && ($_FILES["fileToUpload"]["type"] != "application/x-msdownload"))  {
					
					//Determine the path to which we want to save this file      
					$newname="uploads/status change/".$rd2."_".$filename;   
					
					//Check if the file with the same name already exists in the server
					if (!file_exists($newname)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$newname)) {
							//successful upload	
							$riskresponsefile = $db->prepare("INSERT INTO tbl_projstatusfiles (projid,reasonid,projstatus,floc,created_by,date_created) VALUES (:projid, :reasonid, :projstatus, :floc, :user, :date)");
							$riskresponsefile->execute(array(':projid' => $projid, ':reasonid' => $last_record, ':projstatus' => $projstatuschange, ':floc' => $newname, ':user' => $user, ':date' => $changedon));			
						}	
					} 		  
				}	
			} 
			
			$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projorigstatus, deleted_by=:user, projstatusrestorereason=:restorereason, date_deleted=:date WHERE projid=:projid");
			$update = $updateQuery->execute(array(':projorigstatus' => $projchangedstatus, ':user' => $user, ':restorereason' => $projectactioncomments, ':date' => $changedon, ':projid' => $projid));
			
			if($update){
				$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$projid'");
				$query_rsOrigMilestone->execute();		
			
				while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()){
					$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
					$OrigMilestoneStatus =  $row_rsOrigMilestone['changedstatus'];
			
					$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:origmilestonestatus, changedby=:user, datechanged=:date WHERE msid=:msid");
					$UpdateMst = $updateQuery->execute(array(':origmilestonestatus' => $OrigMilestoneStatus, ':user' => $user, ':date' => $changedon, ':msid' => $OrigMilestoneID));
					
					if($UpdateMst){					
						$query_rsOrigTask =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
						$query_rsOrigTask->execute();

						while ($row_rsOrigTask = $query_rsOrigTask->fetch()){
							$OrigTaskId = $row_rsOrigTask["tkid"];
							$OrigTask = $row_rsOrigTask["changedstatus"];
							//$taskStatus = "On Hold Task";
			
							$updateQuery = $db->prepare("UPDATE tbl_task SET status=:origtask, changedby=:user, datechanged=:date WHERE tkid=:tkid");
							$updateQuery->execute(array(':origtask' => $OrigTask, ':user' => $user, ':date' => $changedon, ':tkid' => $OrigTaskId));
						}
					}
				}
			}
			
			
			$subject = "Project ".$projstatuschange;
			$message = "Project with project code. ".$projcode." is ".$projstatuschange;

			$origin = "Project";
			if(!empty($subject) || $subject !== ''){
				
				$query_rsNotification =  $db->prepare("SELECT * FROM tbl_notifications WHERE projid  = '$projid' AND status = '$projstatuschange'");
				$query_rsNotification->execute();
				$row_rsNotification = $query_rsNotification->fetch();
				$count_rsNotification = $query_rsNotification->rowCount();
				$stsid = $row_rsNotification["id"];
					
				if($count_rsNotification == 0){
					$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
					$statusquery->execute(array(':projid' => $projid, ':user' => $user, ':subject' => $subject, ':message' => $message, ':status' => $projstatuschange, ':date' => $changedon, ':origin' => $origin));
				}
				else{
					$updateQuery = $db->prepare("UPDATE tbl_notifications SET user=:user, subject=:subject,  message=:message,  status=:status, date=:date WHERE id=:stsid");
					$updatest = $updateQuery->execute(array(':user' => $statususer, ':subject' => $subject,  ':message' => $message,  ':status' => $projstatuschange, ':date' => $changedon, ':stsid' => $stsid));				
				}
			}
			if($projstatuschange=="Restored" || $projstatuschange=="Cancelled"){
				$query_issues =  $db->prepare("SELECT id FROM tbl_projissues WHERE projid = '$projid'");
				$query_issues->execute();
				
				while ($row_issues = $query_issues->fetch()){
					$issueid = $row_issues["id"];
					$issuestatus = 2;
					$updateissue = $db->prepare("UPDATE tbl_projissues SET status=:status, closed_by=:user, date_closed=:date WHERE id=:issueid");
					$updateissue->execute(array(':status' => $issuestatus, ':user' => $user, ':date' => $changedon, ':issueid' => $issueid));
				}
			}
			
			echo json_encode("success");
		}	
		else{			
			$insertquery = $db->prepare("INSERT INTO tbl_projstatuschangereason (projid, status, reason, entered_by, date_entered) VALUES (:projid, :status, :reason, :user, :date)");
			$insertquery->execute(array(':projid' => $projid, ':status' => $projstatuschange, ':reason' => $projectactioncomments, ':user' => $user, ':date' => $changedon));
			$last_record = $db->lastInsertId();
			
			//upload random name/number
			$rd2 = mt_rand(1000,9999)."_File"; 
			 
			 //Check that we have a file
			if(!empty($_FILES["fileToUpload"])) {
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['fileToUpload']['name']);
			  
				$ext = substr($filename, strrpos($filename, '.') + 1);
			  
				if (($ext != "exe") && ($_FILES["fileToUpload"]["type"] != "application/x-msdownload"))  {
					//Determine the path to which we want to save this file      
					//$newname = dirname(__FILE__).'/upload/'.$filename;
					$newname="uploads/status change/".$rd2."_".$filename;      
					//Check if the file with the same name already exists in the server
					if (!file_exists($newname)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$newname)) {
							//successful upload	
							$insertquery = $db->prepare("INSERT INTO tbl_projstatusfiles (`projid`,`reasonid`,`projstatus`,`floc`,`created_by`,`date_created`) VALUES (:projid, :reasonid, :projstatus, :floc, :user, :date)");
							$insertquery->execute(array(':projid' => $projid, ':reasonid' => $last_record, ':projstatus' => $projstatuschange, ':floc' => $newname, ':user' => $user, ':date' => $changedon));			
						}	
					} 		  
				}	
			} 
			
			$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, projchangedstatus=:projchangedstatus,  projstatuschangereason=:message, projevaluate=:eval, deleted_by=:user, date_deleted=:date WHERE projid=:projid");
			$updatest = $updateQuery->execute(array(':projstatus' => $projstatuschange, ':projchangedstatus' => $projchangedstatus,  ':message' => $projectactioncomments, ':eval' => $eval, ':user' => $user, ':date' => $changedon, ':projid' => $projid));	
			
			if($updatest){
				$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$projid'");
				$query_rsOrigMilestone->execute();
				
				while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()){
					$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
					$OrigMilestoneStatus =  $row_rsOrigMilestone['status'];
					
					$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:status, changedstatus=:changedstatus, changedby=:user, datechanged=:date WHERE msid=:msid");
					$UpdateMil = $updateQuery->execute(array(':status' => $projstatuschange, ':changedstatus' => $OrigMilestoneStatus, ':user' => $user, ':date' => $changedon, ':msid' => $OrigMilestoneID));
					
					if($UpdateMil){					
						$query_rsMilestone =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
						$query_rsMilestone->execute();

						while ($row_rsMilestone = $query_rsMilestone->fetch()){
							$row_rsOrigTaskID = $row_rsMilestone["tkid"];
							$row_rsOrigTask = $row_rsMilestone["status"];
							
							if($projstatuschange == "Cancelled"){
								$taskStatus = "Cancelled Task";
							}
							elseif($projstatuschange == "On Hold"){
								$taskStatus = "On Hold Task";
							}
							
							$SQLUpdates = $db->prepare("UPDATE tbl_task SET status=:status, changedstatus=:changedstatus WHERE tkid=:tkid");
							$SQLUpdates->execute(array(':status' => $taskStatus, ':changedstatus' => $row_rsOrigTask, ':tkid' => $row_rsOrigTaskID));
						}
					}
				}
			}
			if($projstatuschange=="On Hold"){
			}
			else{
				$query_issues =  $db->prepare("SELECT id FROM tbl_projissues WHERE projid = '$projid'");
				$query_issues->execute();
				
				while ($row_issues = $query_issues->fetch()){
					$issueid = $row_issues["id"];
					$issuestatus = 2;
					$updateissue = $db->prepare("UPDATE tbl_projissues SET status=:status, closed_by=:user, date_closed=:date WHERE id=:issueid");
					$updateissue->execute(array(':status' => $issuestatus, ':user' => $user, ':date' => $changedon, ':issueid' => $issueid));
				}
			}
			
			echo json_encode("success");
		}
	}

}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}

?>