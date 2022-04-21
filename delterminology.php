<?php
require 'authentication.php';

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
	$myid =$_GET['id'];

	$deleteSQL =  $db->prepare("DELETE FROM tbl_terminologies WHERE id=:id");
	$query = $deleteSQL->execute(array(":id" => $myid));

	$deleteGoTo = "system-terminologies";
	if (isset($query)) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $deleteGoTo));
}
?>
