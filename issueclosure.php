<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
	
$current_date = date("Y-m-d");
$current_date_time = date("Y-m-d H:m:s");

try{
	if(isset($_POST['issueid']) && !empty($_POST['issueid'])){
		$rskid = $_POST['issueid'];
		$projid = $_POST['projid'];
		$notes = $_POST['comments'];
		$date = $current_date;
		$emaildate = date("d M Y",strtotime($current_date));
		$datetime = $current_date_time;
		$user = $_POST['username'];
		$status = 7;
		//--------------------------------------------------------------------------
		// 1) create SQL insert statement
		//--------------------------------------------------------------------------							  
		$assignrisk = $db->prepare("INSERT INTO tbl_projissue_comments (projid,rskid,stage,comments,created_by,date_created) VALUES (:projid, :rskid, :stage, :notes, :user, :date)");
		$assignrisk->execute(array(':projid' => $projid, ':rskid' => $rskid, ':stage' => $status, ':notes' => $notes, ':user' => $user, ':date' => $date));
		
		$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status,closed_by=:user,date_closed=:date WHERE id=:rskid");
		$update = $updateQuery->execute(array(':status' => $status, ':user' => $user, ':date' => $datetime, ':rskid' => $rskid));
		
		$query_details =  $db->prepare("SELECT projname, r.category, v.name, i.owner, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$rskid'");
		$query_details->execute();		
		$row_details = $query_details->fetch();
		$project = $row_details["projname"];
		$issue = $row_details["category"];
		$severity = $row_details["name"];
		$owner = $row_details["owner"];
		$recorder = $row_details["created_by"];
		
		$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$owner'");
		$query_userowner->execute();
		
		$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_url->execute();		
		$row_url = $query_url->fetch();
		$url = $row_url["main_url"];
		$org = $row_url["company_name"];
		$org_email = $row_url["email_address"];		
	
		// link back to the system 
		$issuelink = '<a href="'.$url.'project-closed-issue?issueid="'.$rskid.' class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issue Details</a>';		
		
		$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
		$receipient = $row["email"];
		$subject = "Project Issue Closure";
		
		require_once("issue-close-email.php");
		require 'PHPMailer/PHPMailerAutoload.php';	
		require("email-conf-settings.php");		
			
		echo json_encode("success");
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>