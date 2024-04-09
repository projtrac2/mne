<?php
try {

require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

	$query_user = $db->prepare("SELECT fullname, email FROM tbl_admin WHERE username='dmk'");
	$query_rsTPList->execute();		
	$row_rsTPList = $query_rsTPList->fetch();
	$userfullname = $row_rsTPList['fullname'];
	$useremail = $row_rsTPList['email'];

    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.projtrac.co.ke';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'info@projtrac.co.ke';                     // SMTP username
    $mail->Password   = 'softcimes@2018';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
	$mail->setFrom('info@projtrac.co.ke', 'Projtrac Systems Ltd');
	$mail->addAddress($useremail, $userfullname);     // Add a recipient
	$mail->addCC('denkytheka@gmail.com', 'Dennis Kitheka');               // Name is optional
	//$mail->addReplyTo('info@afyasend.com', 'Information');
	//$mail->addCC($contactemail, $contactperson);

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(True);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = '<table border="0">';
	$mail->Body   .= '<tr><td>Hello ' .$userfullname. ',<br><br></td></tr>';
	$mail->Body   .= '<tr><td>'.$message.'.<br></td></tr>';
	$mail->Body   .= '<tr><td>Below are the details for this notification:<br><br></td></tr>';
	$mail->Body   .= '<tr><td><b>Project Name:</b> ' .$TransactionReference. '</td></tr>';
	$mail->Body   .= '<tr><td><b>Project Start Date:</b> ' .$pstartdate. ' <b>Project End Date:</b> ' .$penddate. '<br><br></td></tr>';
	$mail->Body   .= '<tr><td>Thanks & Regards,</td></tr>';
	$mail->Body   .= '<tr><td><i>Projtrac Systems Ltd</i></td></tr>';
	$mail->Body   .= '<tr><td><i>Phone : +254 20 367787 / +254 727 044818</i></td></tr>';
	$mail->Body   .= '<tr><td><i>Email : info@projtrac.co.ke </i></td></tr>';
	$mail->Body   .= '<tr><td><i>Our Link: www.projtrac.co.ke</i></td></tr>';
	$mail->Body   .= '</table>';
	$mail->AltBody = 'Projtrac M&E Platform is a Health Care Market Place where you can buy health packages for yourself, family, friends and others in need.';
} catch (Exception $th) {
	customErrorHandler($th->getCode(), "Message could not be sent. Mailer Error: $mail->ErrorInfo", $th->getFile(), $th->getLine());
}