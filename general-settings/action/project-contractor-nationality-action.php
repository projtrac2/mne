<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$nationality =$_POST['nationality'];
        $description = $_POST['description'];
        $createdby = $_POST['createdby'];
		$datecreated = date('Y-m-d'); 
		$status = 1;
        $sql = $db->prepare("INSERT INTO tbl_contractornationality (nationality, description, active, created_by, date_created)
		 VALUES(:nationality, :description, :status, :createdby, :datecreated)");
        $results = $sql->execute(array(
            ":nationality"=>$nationality,
            ":description"=>$description,
            ":createdby"=>$createdby,
			":datecreated"=>$datecreated,
			":status"=>$status
		));
		
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
		$nationality =$_POST['editNationality'];
        $description = $_POST['editDescription'];
        $createdby = $_POST['createdby'];
        $dateupdated = date('Y-m-d H:m:s');  
		$itemid = $_POST['itemId'];
		$status = $_POST['editStatus'];
		
        $sql = $db->prepare("UPDATE tbl_contractornationality SET nationality=:nationality,description=:description, active=:status, created_by=:createdby, 
		date_created=:dateupdated WHERE id =:id");
        $results = $sql->execute(array(":nationality"=>$nationality, ":description"=>$description, ":status"=>$status, ":createdby"=>$createdby, ":dateupdated"=>$dateupdated, ":id"=>$itemid));
		
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
		$deleteQuery = $db->prepare("DELETE FROM `tbl_contractornationality` WHERE id=:itemid");
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