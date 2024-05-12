<?php
try {
	include_once "../controller.php";
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$status = $_POST['projstatus'];
		$level = $_POST['statuslevel'];
		$active = 1;
		$class_name = 'btn bg-grey waves-effect';
		$sql = $db->prepare("INSERT INTO tbl_status (statusname, level, active) VALUES(:status, :level, :active)");
		$results = $sql->execute(array(":status" => $status, ":level" => $level, ":active" => $active));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the record!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST["edititem"])) {
		$projstatus = $_POST['editprojstatus'];
		$level = $_POST['editstatuslevel'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_status SET statusname=:status,  level=:level WHERE statusid=:itemid");
		$results = $updateQuery->execute(array(':status' => $projstatus, ':level' => $level, ':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updatng the record!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST["deleteItem"])) {
		$itemid = $_POST['itemId'];

		$updateStatus = '';

		$stmt = $db->prepare("SELECT * FROM `tbl_status` where statusid=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_status` SET active=:active WHERE statusid=:itemid");
		$results = $deleteQuery->execute(array(':active' => $updateStatus, ':itemid' => $itemid));

		if ($results === TRUE) {
			$valid = true;
		} else {
			$valid = false;
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
