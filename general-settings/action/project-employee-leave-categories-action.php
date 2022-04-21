<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$status = 1;
		$leavename =$_POST['name'];
		$description =$_POST['description'];
		$days = $_POST['days'];
		$createdby = $_POST['createdby'];
		$date_created = date('Y-m-d');
		$sql = $db->prepare("INSERT INTO tbl_employees_leave_categories (leavename, description, days, status, addedby, date_added) VALUES(:leavename, :description, :days, :status, :createdby, :date_created)");
		$results = $sql->execute(array(":leavename"=>$leavename, ":description"=>$description, ":days"=>$days, ":status"=>$status, ":createdby"=>$createdby, ":date_created"=>$date_created));
		  
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
        $leavename =$_POST['editName'];
        $days = $_POST['editdays'];
        $createdby = $_POST['createdby'];
        $description =$_POST['editDescription'];
        $date_modified = date('Y-m-d');
		$itemid = $_POST['itemId'];
		$status = $_POST['editStatus'];
        $sql = $db->prepare("UPDATE tbl_employees_leave_categories SET leavename=:leavename, description=:description, days=:days, status=:status, 
        modifiedby=:createdby, date_modified=:date_modified WHERE id =:id");
        $results = $sql->execute(
        array(
            ":leavename"=>$leavename,
            ":days"=>$days,
            ":description"=>$description, 
            ":createdby"=>$createdby,
			":date_modified"=>$date_modified,
			":status"=>$status,
            ":id"=>$itemid
        ));
	
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
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_employees_leave_categories` WHERE id=:itemid");
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
