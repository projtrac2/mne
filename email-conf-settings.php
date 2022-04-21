<?php  

	$query_settings = $db->prepare("SELECT * FROM tbl_email_settings");
	$query_settings->execute();		
	$settings = $query_settings->fetch();
	$sender  = $org;
	//$receipient = "denkytheka@gmail.com";
	
	$mail = new PHPMailer;
	//Server settings
	//$mail->SMTPDebug = 2;                                       // Enable verbose debug output
	$mail->isSMTP();                                            // Set mailer to use SMTP
	$mail->Host       = $settings["host"];  // Specify main and backup SMTP servers
	$mail->SMTPAuth   = $settings["SMTPAuth"];                                // Enable SMTP authentication
	$mail->Username   = $settings["username"];                     // SMTP username
	$mail->Password   = $settings["password"];                             // SMTP password
	$mail->SMTPSecure = $settings["SMTPSecure"];                                // Enable TLS encryption, `ssl` also accepted
	$mail->Port       = $settings["port"];                                    // TCP port to connect to

	//Recipients
	$mail->setFrom($sender, $org);
	$mail->addAddress($receipient, $receipientName);

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(True);                                  // Set email format to HTML

	$mail->Subject = $subject; 
	$mail->Body    = $body;
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$mail->send();
