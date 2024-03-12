<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
		$year =$_POST['fscyear'];
		$yr =$_POST['year'];
		$sdate = $_POST['sdate'];
		$edate = $_POST['edate'];
		$status = 1;
		$date_created =  date('Y-m-d h:i:s');
		$sql = $db->prepare("INSERT INTO tbl_fiscal_year (year,yr, sdate,edate, status) VALUES(:year,:yr, :sdate, :edate, :status)");
		$results = $sql->execute(array(":year"=>$year,":yr"=>$yr,":sdate"=>$sdate, ":edate"=>$edate, ":status"=>$status));

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
		$year =$_POST['editfscyear'];
        $sdate = $_POST['editsdate'];
        $edate = $_POST['editedate'];
        $yr =$_POST['edityear'];
		$itemid = $_POST['itemId'];
		$date_modified = date('Y-m-d h:i:s');
        $sql = $db->prepare("UPDATE tbl_fiscal_year SET year=:year, yr=:yr, sdate=:sdate, edate=:edate  WHERE id =:id");
        $results = $sql->execute(
        array(
            ":year"=>$year,
            ":sdate"=>$sdate,
            ":yr"=>$yr, 
			":edate"=>$edate,
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

		$updateStatus = '';

		$stmt = $db->prepare("SELECT * FROM `tbl_fiscal_year` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';
		
		$deleteQuery = $db->prepare("UPDATE `tbl_fiscal_year` SET status=:status WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid, ':status' => $updateStatus));

		if($results === TRUE) {
			$valid = true;
		} else {
			$valid = false;
		}
		echo json_encode($valid);
	}

}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}