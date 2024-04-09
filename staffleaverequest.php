<?php
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['leaveid'])) 
{
	$leaveid = $_POST["leaveid"];
	$ptid = $_POST["ptid"];
	
	$currentYear = date("Y");							
	$query_rsLeaveDays =  $db->prepare("SELECT * FROM tbl_employee_leave_bal WHERE category='$leaveid' AND staff = '$ptid' AND year='$currentYear' ORDER BY id ASC");
	$query_rsLeaveDays->execute();		
	$row_rsLeaveDays = $query_rsLeaveDays->fetch();
	
	$query_rsEmpDetails =  $db->prepare("SELECT title, fullname FROM tbl_projteam2 WHERE ptid = '$ptid'");
	$query_rsEmpDetails->execute();		
	$row_rsEmpDetails = $query_rsEmpDetails->fetch();
	
	$current_date = date("Y-m-d");

	echo '
		<div class="col-md-12">
			<label>Employee Name : <font color="indigo">'.$row_rsEmpDetails["title"].'. '.$row_rsEmpDetails["fullname"].'</font></label>
		</div>
		<div class="col-md-4">
			<label>Balance Brought Forward: <font color="indigo">'.$row_rsLeaveDays["balforward"].' Days</font></label>
		</div>
		<div class="col-md-4">
			<label>Days For Year '.$row_rsLeaveDays["year"].': <font color="indigo">'.$row_rsLeaveDays["days"].' Days</font></label>
		</div>
		<div class="col-md-4">
			<label>Remaining Leave Days : <font color="indigo">'.$row_rsLeaveDays["totaldays"].' Days</font></label>
		</div>
		<input type="hidden" name="catid" id="catid" value="'.$leaveid.'"/>
		<input type="hidden" name="remleavedays" id="catid" value="'.$row_rsLeaveDays["totaldays"].'"/>
	';
}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 
}
?>