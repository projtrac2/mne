<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$workflow =$_POST['workflow'];
		$category =$_POST['category'];
		$stage =$_POST['stage'];
		$status = $_POST['statusname'];
		$description = $_POST['description'];
		$time =$_POST['time'];
		$escalateafter =$_POST['escalateafter'];
		$units = $_POST['units'];
		$escalateto =$_POST['escalateto'];
		$active =1;
		  $sql = $db->prepare("INSERT INTO `tbl_project_workflow_stage_timelines`(`workflow`, `category`, `stage`, `status`, `description`, `time`, `escalate_after`, `units`, `escalate_to`, `active`)
		  VALUES(:workflow, :category, :stage, :status, :description, :time, :escalateafter, :units, :escalateto, :active)");
		  $results = $sql->execute(array(":workflow"=>$workflow, ":category"=>$category, ":stage"=>$stage, ":status"=>$status, ":description"=>$description, ":time"=>$time, ":escalateafter"=>$escalateafter, ":units"=>$units, ":escalateto"=>$escalateto, ":active"=>$active));
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
		$category =$_POST['editcategory'];
        $stage =$_POST['editstage'];
        $status = $_POST['editstatusname'];
        $description = $_POST['editdescription'];
        $time =$_POST['edittime'];
        $units = $_POST['editunits'];
		$active = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
        $sql = $db->prepare("UPDATE tbl_project_workflow_stage_timelines SET category=:category, stage=:stage, 
        status=:status, description=:description, time=:time, units=:units, active=:active WHERE id =:id");
		$results = $sql->execute(array(":category"=>$category, ":stage"=>$stage, ":status"=>$status, ":description"=>$description, ":time"=>$time, ":units"=>$units, ":active"=>$active, ":id"=>$itemid));
		
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
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_project_workflow_stage_timelines` WHERE id=:itemid");
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