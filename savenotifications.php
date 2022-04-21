<?php
//insert.php
if(isset($_POST["subject"]))
{
	include("connect.php");
	$username = "Admin0";
	$currentdate = "2018-12-17";
	$origin = $_POST["origin"];
	$status = $_POST["projstatus"];
	$subject = mysqli_real_escape_string($con, $_POST["subject"]);
	$message = mysqli_real_escape_string($con, $_POST["message"]);
	$query = "INSERT INTO tbl_notifications (user, subject, message, status, date, origin) VALUES ('$username', '$subject', '$message', '$status', '$currentdate', '$origin')";
	mysqli_query($con, $query);
}
?>