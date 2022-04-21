<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
        $stage =$_POST['stage'];
        $parent = $_POST['parent'];
        if(empty($_POST['description']) || $_POST['description'] ==""){
          $description =Null;
        }else{
			$description = $_POST['description'];
        }
		
        $status = 1; 

        $sql = $db->prepare("INSERT INTO tbl_project_workflow_stage (stage, parent, description, active) VALUES(:stage, :parent, :description, :status )");
        $results = $sql->execute(array(":stage"=>$stage, ":parent"=>$parent, ":description"=>$description, ":status"=>$status));

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
        $stage =$_POST['editstage'];
        $parent = $_POST['editparent'];
        if(empty($_POST['editdescription']) || $_POST['editdescription'] ==""){
          $description =Null;
        }else{
			$description = $_POST['editdescription'];
        }
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];

        $sql = $db->prepare("UPDATE tbl_project_workflow_stage SET stage=:stage, parent=:parent, description=:description,  active=:status WHERE id =:id");
        $results = $sql->execute(
        array( 
        ":stage"=>$stage,
        ":parent"=>$parent,
        ":description"=>$description,
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
		$deleteQuery = $db->prepare("DELETE FROM `tbl_project_workflow_stage` WHERE id=:itemid");
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