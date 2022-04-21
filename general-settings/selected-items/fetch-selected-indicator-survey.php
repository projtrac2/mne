<?php

include_once "controller.php";
if($_POST["formid"]){
	$formid = $_POST["formid"];
	$username = $_POST["username"];
	
	$query_rsForm = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id='$formid'");
	$query_rsForm->execute();
	$row_rsForm = $query_rsForm->fetch();
	$indid = $row_rsForm["indid"];
	
	$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indid' and active='1'");
	$query_rsIndicator->execute();
	$row_rsIndicator = $query_rsIndicator->fetch();

	$form='';
	$form .= '		
			<div  class="col-md-12">
				<label><font color="#3F51B5">Survey Form Name:</font></label> '.$row_rsForm["form_name"].'
			</div>
			<div class="col-md-12">
				<label><font color="#3F51B5">Indicator Code:</font></label> '.$row_rsIndicator["indcode"].'
			</div>
			<div  class="col-md-12">
				<label><font color="#3F51B5">Indicator Name:</font></label> '.$row_rsIndicator["indname"].'
			</div>
			<input type="hidden" name="MM_insert" value="addindfrm" />
			<input type="hidden" name="indid" value="'.$indid.'" />
			<input type="hidden" name="formid" value="'.$formid.'" />
			<input type="hidden" name="username" value="'.$username.'" />';
	echo $form;
}
?>