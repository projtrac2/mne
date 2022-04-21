<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$status = 1;
		$code =$_POST['code'];
		$currency = $_POST['currency'];
		$sympol = $_POST['sympol'];
		$sql = $db->prepare("INSERT INTO tbl_currency (code,  currency, sympol,active ) VALUES(:code, :currency, :sympol, :active)");
		$results = $sql->execute(array(":code"=>$code,":currency"=>$currency, ":sympol"=>$sympol, ":active"=>$status));

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
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
		$code =$_POST['editcode'];
        $currency = $_POST['editcurrency'];
        $sympol = $_POST['editsympol'];
        $sql = $db->prepare("UPDATE tbl_currency SET code=:code,currency=:currency, sympol=:sympol, active=:active WHERE id =:id");
        $results= $sql->execute(
        array(
            ":code"=>$code,
            ":currency"=>$currency,
            ":sympol"=>$sympol,
            ":active"=>$status,
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
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_currency` WHERE id=:itemid");
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
