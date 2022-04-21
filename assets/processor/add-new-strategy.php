<?php
include_once "controller.php";

try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["addstrategy"])){
		$objid =$_POST['objid'];
		$strategy = $_POST['strategy'];
		$user = $_POST['username'];
		$currentdate = date("Y-m-d");
		
		$sql = $db->prepare("INSERT INTO tbl_objective_strategy (objid, strategy, created_by, date_created) VALUES(:objid,:strategy,:user, :date)");
		$results = $sql->execute(array(":objid"=>$objid,":strategy"=>$strategy, ":user"=>$user,":date"=>$currentdate));
		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the record!!";
		}
		echo json_encode($valid);
	}	
}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}