<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
	
$current_date = date("Y-m-d");
$current_date_time = date("Y-m-d H:m:s");

try{
	if(isset($_POST['issueid']) && !empty($_POST['issueid'])){
		$rskid = $_POST['issueid'];
		$projid = $_POST['projid'];
		$comments = $_POST['comments'];
		$manager = $_POST['manager'];
		$date = $current_date;
		$emaildate = date("d M Y",strtotime($current_date));
		$datetime = $current_date_time;
		$user = $_POST['username'];
		$status = 4;
		$category = 'issue';
		//--------------------------------------------------------------------------
		// 1) create SQL insert statement
		//--------------------------------------------------------------------------							  
		$query_escalate = $db->prepare("INSERT INTO tbl_escalations (category,owner,projid,itemid,comments,escalated_by,date_escalated) VALUES (:cat, :owner, :projid, :itemid, :comments, :user, :date)");
		$query_escalate->execute(array(':cat' => $category, ':owner' => $manager, ':projid' => $projid, ':itemid' => $rskid, ':comments' => $comments, ':user' => $user, ':date' => $date));
		
		$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status,escalated_by=:user,date_escalated=:date WHERE id=:rskid");
		$update = $updateQuery->execute(array(':status' => $status, ':user' => $user, ':date' => $datetime, ':rskid' => $rskid));
		
		$query_details =  $db->prepare("SELECT projname, r.category, v.name, i.owner, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$rskid' and i.status=4");
		$query_details->execute();		
		$row_details = $query_details->fetch();
		$project = $row_details["projname"];
		$issue = $row_details["category"];
		$severity = $row_details["name"];
		$owner = $row_details["owner"];
		$recorder = $row_details["created_by"];
				
		$query_userowner =  $db->prepare("SELECT ptid, fullname, title, t.email as email FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$manager'");
		$query_userowner->execute();
		$row = $query_userowner->fetch();
		$rows_userowner = $query_userowner->rowCount();
		
		$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_url->execute();		
		$row_url = $query_url->fetch();	
		
		$url = $row_url["main_url"];		
					
		$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_url->execute();		
		$row_url = $query_url->fetch();
		$url = $row_url["main_url"];
		$org = $row_url["company_name"];
		$org_email = $row_url["email_address"];
		
		if($rows_userowner > 0){
			require 'PHPMailer/PHPMailerAutoload.php';
			$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
			$receipient = $row["email"];
			
			//email body
			$detailslink = '<a href="'.$url.'project-escalated-issue?issueid='.$rskid.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details </a>';
		
			$mainmessage = ' 
			<p>Dear '.$receipientName. ',</p>
			<p>This is to notify you that the issue with the details below has been escalated to you</p><P>Issue: '.$issue.'. <br> Project Name: '.$project.' <br> Issue Severity: '.$severity.' <br> Date escalated: '.$emaildate.'</p>
			<p>Click the link below for more details</p>';
			
			$title = "Project issue escalation to CO";
			$subject = "Project Issue Escalation";
			
			include("assets/processor/email-body.php");
			include("email-conf-settings.php");
		}
			
		echo json_encode("success");
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
