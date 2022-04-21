<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$name =$_POST['country'];
		$isocode = $_POST['isocode'];
		$countrycode = $_POST['code'];
		$value = $_POST['value']; 
		$status = 1;
		$sql = $db->prepare("INSERT INTO countries (country, iso_code, country_code, value, status) VALUES(:name,:isocode,:countrycode, :value, :status)");
		$results = $sql->execute(array(":name"=>$name,":isocode"=>$isocode, ":countrycode"=>$countrycode,":value"=>$value, ":status"=>$status));
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
		$name =$_POST['editcountry'];
		$isocode = $_POST['editisocode'];
		$countrycode = $_POST['editcode'];
		$value = $_POST['editvalue'];
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
		$sql = $db->prepare("UPDATE countries SET country=:name,iso_code=:isocode,country_code=:countrycode, value=:value, status=:status WHERE id =:id");
		$results = $sql->execute(
		array(
			":name"=>$name,
			":isocode"=>$isocode,
			":countrycode"=>$countrycode,
			":value"=>$value, 
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
		
		$deleteQuery = $db->prepare("DELETE FROM `countries` WHERE id=:itemid");
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
