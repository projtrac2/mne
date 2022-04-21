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
		
		// link back to the system 
		$issuelink = '<a href="'.$url.'project-escalated-issue?issueid='.$rskid.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details </a>';
		
		require 'PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;
		$subject = "Project Issue Escalation";
		
		if($rows_userowner > 0){			
			$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
			$receipient = $row["email"];
			//$receipient = "denkytheka@gmail.com";
			require_once("issue-escalation-email.php");
			
			//Server settings
			//$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'smtp.ionos.es';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'info@odesatv.es';                     // SMTP username
			$mail->Password   = 'Test@2021#';                               // SMTP password
			$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 587;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom('info@odesatv.es', 'Projtrac Systems Ltd');
			$mail->addAddress($receipient, $receipientName);

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(True);                                  // Set email format to HTML

			$mail->Subject = $subject; 
			$mail->Body    = $body;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
		}
			
		echo json_encode("success");
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>