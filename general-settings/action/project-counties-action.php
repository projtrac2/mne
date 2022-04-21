<?php

include_once "controller.php";	
try{

	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$name =$_POST['name'];
		$code = $_POST['code'];
		$size = $_POST['size'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO counties (code, name, size,status) VALUES(:code,:name,:size, :status)");
		$results = $sql->execute(array(":code"=>$code, ":name"=>$name,":size"=>$size,":status"=>$status));
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
		$code = $_POST['editcode'];
		$size = $_POST['editsize'];
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
		$sql = $db->prepare("UPDATE counties SET code=:code,name=:name,size=:size,status=:status WHERE id =:id");
		$results = $sql->execute(
		array(
			":code"=>$code, 
			":name"=>$name,
			":size"=>$size,
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
		
		$deleteQuery = $db->prepare("DELETE FROM `counties` WHERE id=:itemid");
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
