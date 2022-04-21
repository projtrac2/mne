<?php

//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	if(isset($_POST["edititem"])){
		$code =$_POST['editCode'];
		$outcome = $_POST['editOutcome'];
		$indid = $_POST['editIndicator'];
		$user = $_POST['username'];
		$deptid = $_POST['editDept'];
		$itemid = $_POST['itemId'];
		$current_date = date("Y-m-d");
		
		$updateQuery = $db->prepare("UPDATE tbl_outcomes SET deptid=:deptid, code=:code, outcome=:outcome, indicator=:indicator , changed_by=:user , date_changed=:dates WHERE ocid=:itemid");
		$results = $updateQuery->execute(array(':deptid' => $deptid, ':code' => $code, ':outcome' => $outcome, ':indicator' => $indid, ':user' => $user, ':dates' => $current_date, ':itemid' => $itemid));

		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updatng the record!!";
		}
		echo json_encode($valid);
	}
	if(isset($_POST["deleteItem"])){
		$itemid = $_POST['itemId'];
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_outcomes` WHERE ocid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}

}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
