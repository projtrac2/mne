<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$frequency =$_POST['frequency'];
		$days = $_POST['days'];
		$createdby = $_POST['createdby'];
		$date_created = date('Y-m-d h:i:s');
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_datacollectionfreq (frequency,days,created_by,date_created,status) 
		VALUES(:frequency, :days, :createdby, :date_created, :status)");
		$results = $sql->execute(array(":frequency"=>$frequency,":days"=>$days,":createdby"=>$createdby,":date_created"=>$date_created,":status"=>$status));
        
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
		$frequency =$_POST['editfrequency'];
        $days = $_POST['editdays'];
        $createdby = $_POST['createdby'];
        $date_modified = date('Y-m-d h:i:s');
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
        $sql = $db->prepare("UPDATE tbl_datacollectionfreq SET frequency=:frequency,days=:days, modified_by=:createdby, 
		date_modified=:date_modified, status=:status WHERE fqid =:id");
        $results = $sql->execute(
        array(
            ":frequency"=>$frequency,
            ":days"=>$days,
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
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_datacollectionfreq` WHERE fqid=:itemid");
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
