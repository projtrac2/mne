<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$name =$_POST['name'];
		$description =$_POST['description'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_budget_lines (name, description,status) VALUES(:name, :description,:status)");
		$results = $sql->execute(array(":name"=>$name, ":description"=>$description,":status"=>$status));
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
		$name =$_POST['editname'];
		$description =$_POST['editdescription'];
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
		
		$updateQuery = $db->prepare("UPDATE tbl_budget_lines SET name=:name, description=:description,status=:status WHERE id=:itemid");
		$results = $updateQuery->execute(array(":name"=>$name, ":description"=>$description,":status"=>$status, ':itemid' => $itemid));

		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating the record!!";
		}
		echo json_encode($valid);
	}
	if(isset($_POST["deleteItem"])){
		$itemid = $_POST['itemId'];
		var_dump($itemid);
		$deleteQuery = $db->prepare("DELETE FROM `tbl_budget_lines` WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deleting the record!!";
		}
		echo json_encode($valid);
	}

}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}