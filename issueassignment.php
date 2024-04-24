<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	if(isset($_POST['owner']) && !empty($_POST['owner'])){
		$issueid = $_POST['issueid'];
		$ownerid = $_POST['owner'];
		$priority = $_POST['priority'];
		$projid = $_POST['projid'];
		$user = $_POST['username'];
		$comments = $_POST['comments'];
		$dateassigned = date("Y-m-d");
		$status = 2;
								
		$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=2 and active=1");
		$query_timeline->execute();		
		$row_timeline = $query_timeline->fetch();
		$timelineid = $row_timeline["id"];
		$timelinestatus = $row_timeline["status"];
		$timelinestage = $row_timeline["stage"];
		$time = $row_timeline["time"];
		$units = $row_timeline["units"];
		
		//--------------------------------------------------------------------------
		// 1) create SQL insert statement
		//--------------------------------------------------------------------------	
		
		$updateQuery = $db->prepare("UPDATE tbl_projissues SET owner=:owner, status=:status, priority=:priority, assigned_by=:user, date_assigned=:date WHERE id=:issueid");
		$update = $updateQuery->execute(array(':owner' => $ownerid, ':status' => $status, ':priority' => $priority, ':user' => $user, ':date' => $dateassigned, ':issueid' => $issueid));
		
		$assignercomments = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid,:rskid,:stage,:comments,:user, :date)");
		$assignercomments->execute(array(':projid' => $projid, ':rskid' => $issueid, ':stage' => $timelinestage, ':comments' => $comments, ':user' => $user, ':date' => $dateassigned));
								
		$query_owner =  $db->prepare("SELECT fullname, title, email FROM tbl_projteam2 WHERE ptid = '$ownerid'");
		$query_owner->execute();		
		$row_owner = $query_owner->fetch();
		
		$duedate = date("d M Y",strtotime($dateassigned."+ ".$time." ".$units));
		
		$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_url->execute();		
		$row_url = $query_url->fetch();	
		
		$url = $row_url["main_url"];		
		
		if($rows_userowner > 0){
			require 'PHPMailer/PHPMailerAutoload.php';
			$receipientName = $row_owner["title"].".".$row_owner["fullname"]; // The receipients names 
			$receipient = $row_owner["email"];
			
			//email body
			$detailslink = '<a href="'.$url.'projectissuesanalysis?proj='.$projid.'&user='.$ownerid.'" target="_blank">Check the assignment</a>';
		
			$mainmessage = ' 			
			<p>Dear '.$receipientName.',</p>
			<p>You have been assigned a project issue to work on, please make the necessary arrangement start by '.$duedate.'</p>
			<p>Team Leader Comments:<br>'.$comments.'</p>
			<p>Click below for more details</p>';
			
			$title = "Project issue escalation to CO";
			$subject = "Project Issue Assigned";
			
			include("assets/processor/email-body.php");
			include("email-conf-settings.php");
		}
			
		echo json_encode("success");
	}
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
