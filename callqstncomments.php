<?php
if(isset($_POST['cklst'])) 
{
	$ckid = $_POST["cklst"];	
	echo '<input type="hidden" name="checklistid" id="checklistid" value="'.$ckid.'"/>';
}
?>