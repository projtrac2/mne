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
		$owner = $row_owner["title"].".".$row_owner["fullname"];
		$owneremail = $row_owner["email"];
		
		$duedate = date("d M Y",strtotime($dateassigned."+ ".$time." ".$units));
		
		$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_url->execute();		
		$row_url = $query_url->fetch();	
		
		$url = $row_url["main_url"];
		
		// link back to the system 
		$issuelink = '<a href="'.$url.'projectissuesanalysis?proj='.$projid.'&user='.$ownerid.'" target="_blank">Check the assignment</a>';
		
		require 'PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;
		$subject = "Project Issue Assigned";
		$body = '<!doctype html>
		<html>
		  <head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>Project Issue Owner</title>
			<style>
			  
			  img {
				border: none;
				-ms-interpolation-mode: bicubic;
				max-width: 100%; 
			  }

			  body {
				background-color: #f6f6f6;
				font-family: sans-serif;
				-webkit-font-smoothing: antialiased;
				font-size: 14px;
				line-height: 1.4;
				margin: 0;
				padding: 0;
				-ms-text-size-adjust: 100%;
				-webkit-text-size-adjust: 100%; 
			  }

			  table {
				border-collapse: separate;
				mso-table-lspace: 0pt;
				mso-table-rspace: 0pt;
				width: 100%; }
				table td {
				  font-family: sans-serif;
				  font-size: 14px;
				  vertical-align: top; 
			  }

			  /* -------------------------------------
				  BODY & CONTAINER
			  ------------------------------------- */

			  .body {
				background-color: #f6f6f6;
				width: 100%; 
			  }

			  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
			  .container {
				display: block;
				margin: 0 auto !important;
				/* makes it centered */
				max-width: 580px;
				padding: 10px;
				width: 580px; 
			  }

			  /* This should also be a block element, so that it will fill 100% of the .container */
			  .content {
				box-sizing: border-box;
				display: block;
				margin: 0 auto;
				max-width: 580px;
				padding: 10px; 
			  }

			  /* -------------------------------------
				  HEADER, FOOTER, MAIN
			  ------------------------------------- */
			  .main {
				background: #ffffff;
				border-radius: 3px;
				width: 100%; 
			  }

			  .wrapper {
				box-sizing: border-box;
				padding: 20px; 
			  }

			  .content-block {
				padding-bottom: 10px;
				padding-top: 10px;
			  }

			  .footer {
				clear: both;
				margin-top: 10px;
				text-align: center;
				width: 100%; 
			  }
				.footer td,
				.footer p,
				.footer span,
				.footer a {
				  color: #999999;
				  font-size: 12px;
				  text-align: center; 
			  }

			  /* -------------------------------------
				  TYPOGRAPHY
			  ------------------------------------- */
			  h1,
			  h2,
			  h3,
			  h4 {
				color: #000000;
				font-family: sans-serif;
				font-weight: 400;
				line-height: 1.4;
				margin: 0;
				margin-bottom: 30px; 
			  }

			  h1 {
				font-size: 35px;
				font-weight: 300;
				text-align: center;
				text-transform: capitalize; 
			  }

			  p,
			  ul,
			  ol {
				font-family: sans-serif;
				font-size: 14px;
				font-weight: normal;
				margin: 0;
				margin-bottom: 15px; 
			  }
				p li,
				ul li,
				ol li {
				  list-style-position: inside;
				  margin-left: 5px; 
			  }

			  a {
				color: #3498db;
				text-decoration: underline; 
			  }

			  /* -------------------------------------
				  BUTTONS
			  ------------------------------------- */
			  .btn {
				box-sizing: border-box;
				width: 100%; }
				.btn > tbody > tr > td {
				  padding-bottom: 15px; }
				.btn table {
				  width: auto; 
			  }
				.btn table td {
				  background-color: #ffffff;
				  border-radius: 5px;
				  text-align: center; 
			  }
				.btn a {
				  background-color: #ffffff;
				  border: solid 1px #3498db;
				  border-radius: 5px;
				  box-sizing: border-box;
				  color: #3498db;
				  cursor: pointer;
				  display: inline-block;
				  font-size: 14px;
				  font-weight: bold;
				  margin: 0;
				  padding: 12px 25px;
				  text-decoration: none;
				  text-transform: capitalize; 
			  }

			  .btn-primary table td {
				background-color: #3498db; 
			  }

			  .btn-primary a {
				background-color: #3498db;
				border-color: #3498db;
				color: #ffffff; 
			  }

			  /* -------------------------------------
				  OTHER STYLES THAT MIGHT BE USEFUL
			  ------------------------------------- */
			  .last {
				margin-bottom: 0; 
			  }

			  .first {
				margin-top: 0; 
			  }

			  .align-center {
				text-align: center; 
			  }

			  .align-right {
				text-align: right; 
			  }

			  .align-left {
				text-align: left; 
			  }

			  .clear {
				clear: both; 
			  }

			  .mt0 {
				margin-top: 0; 
			  }

			  .mb0 {
				margin-bottom: 0; 
			  }

			  .preheader {
				color: transparent;
				display: none;
				height: 0;
				max-height: 0;
				max-width: 0;
				opacity: 0;
				overflow: hidden;
				mso-hide: all;
				visibility: hidden;
				width: 0; 
			  }

			  .powered-by a {
				text-decoration: none; 
			  }

			  hr {
				border: 0;
				border-bottom: 1px solid #f6f6f6;
				margin: 20px 0; 
			  }

			  /* -------------------------------------
				  RESPONSIVE AND MOBILE FRIENDLY STYLES
			  ------------------------------------- */
			  @media only screen and (max-width: 620px) {
				table[class=body] h1 {
				  font-size: 28px !important;
				  margin-bottom: 10px !important; 
				}
				table[class=body] p,
				table[class=body] ul,
				table[class=body] ol,
				table[class=body] td,
				table[class=body] span,
				table[class=body] a {
				  font-size: 16px !important; 
				}
				table[class=body] .wrapper,
				table[class=body] .article {
				  padding: 10px !important; 
				}
				table[class=body] .content {
				  padding: 0 !important; 
				}
				table[class=body] .container {
				  padding: 0 !important;
				  width: 100% !important; 
				}
				table[class=body] .main {
				  border-left-width: 0 !important;
				  border-radius: 0 !important;
				  border-right-width: 0 !important; 
				}
				table[class=body] .btn table {
				  width: 100% !important; 
				}
				table[class=body] .btn a {
				  width: 100% !important; 
				}
				table[class=body] .img-responsive {
				  height: auto !important;
				  max-width: 100% !important;
				  width: auto !important; 
				}
			  }

			  /* -------------------------------------
				  PRESERVE THESE STYLES IN THE HEAD
			  ------------------------------------- */
			  @media all {
				.ExternalClass {
				  width: 100%; 
				}
				.ExternalClass,
				.ExternalClass p,
				.ExternalClass span,
				.ExternalClass font,
				.ExternalClass td,
				.ExternalClass div {
				  line-height: 100%; 
				}
				.apple-link a {
				  color: inherit !important;
				  font-family: inherit !important;
				  font-size: inherit !important;
				  font-weight: inherit !important;
				  line-height: inherit !important;
				  text-decoration: none !important; 
				}
				.btn-primary table td:hover {
				  background-color: #34495e !important; 
				}
				.btn-primary a:hover {
				  background-color: #34495e !important;
				  border-color: #34495e !important; 
				} 
			  }

			</style>
		  </head>
		  <body class="">
			<span style=" color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0; ">This is preheader text. Some clients will show this text as a preview.</span>
			<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
			  <tr>
				<td>&nbsp;</td>
				<td class="container">
				  <div class="content">

					<!-- START CENTERED WHITE CONTAINER -->
					<table role="presentation" class="main">

					  <!-- START MAIN CONTENT AREA -->
					  <tr>
						<td class="wrapper">
						  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td>
								<p>Dear '.$owner.',</p>
								<p>You have been assigned a project issue to work on, please make the necessary arrangement start by '.$duedate.'</p>
								<p>Team Leader Comments:<br>'.$comments.'</p>
								<p>Click below for more details</p>
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
								  <tbody>
									<tr>
									  <td align="left">
										<table role="presentation" border="0" cellpadding="0" cellspacing="0">
										  <tbody>
											<tr>
											  <td>'.$issuelink.'</td>
											</tr>
										  </tbody>
										</table>
									  </td>
									</tr>
								  </tbody>
								</table>
								<p>Best Regards,<br>
								   Development Team<br>
								   Projtrac System Limited.</p>
							  </td>
							</tr>
						  </table>
						</td>
					  </tr>

					<!-- END MAIN CONTENT AREA -->
					</table>
					<!-- END CENTERED WHITE CONTAINER -->

				  </div>
				</td>
				<td>&nbsp;</td>
			  </tr>
			</table>
		  </body>
		</html>
		';

		//Server settings
		//$mail->SMTPDebug = 2;                                       // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'mail.projtrac.co.ke';  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = 'info@projtrac.co.ke';                     // SMTP username
		$mail->Password   = 'softcimes@2018';                               // SMTP password
		$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 587;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom('info@projtrac.co.ke', 'Projtrac Systems Ltd');
		$mail->addAddress($owneremail, $owner);     // Add a recipient              // Name is optional
		//$mail->addReplyTo('info@afyasend.com', 'Information');
		$mail->addCC('denkytheka@gmail.com', 'Dennis Kitheka');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(True);                                  // Set email format to HTML

		$mail->Subject = $subject; 
		$mail->Body    = $body;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
			
		echo json_encode("success");
	}
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>
