<?php
//
include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$status =$_POST['projstatus'];
		$level =$_POST['statuslevel'];
		$active = 1;
		$sql = $db->prepare("INSERT INTO tbl_status (statusname, level, active) VALUES(:status, :level, :active)");
		$results = $sql->execute(array(":status"=>$status, ":level"=>$level, ":active"=>$active));
		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the record!!";
		}
		echo json_encode($valid);
	}
	
	if(isset($_POST["edititem"])){
		$active =$_POST['editStatus'];
		$projstatus = $_POST['editprojstatus'];
		$level = $_POST['editstatuslevel'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_status SET statusname=:status,  level=:level,  active=:active WHERE statusid=:itemid");
		$results = $updateQuery->execute(array(':status' => $projstatus, ':level' => $level, ':active' => $active, ':itemid' => $itemid));

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
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_status` WHERE statusid=:itemid");
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