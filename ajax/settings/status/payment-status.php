<?php
include_once "../../controller.php";
try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$status = $_POST['projstatus'];
		$active = 1;
		$sql = $db->prepare("INSERT INTO tbl_payment_status (status, active) VALUES(:status, :active)");
		$results = $sql->execute(array(":status" => $status, ":active" => $active));
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
		$itemid = $_POST['itemId'];


		$updateQuery = $db->prepare("UPDATE tbl_payment_status SET status=:status WHERE id=:itemid");
		$results = $updateQuery->execute(array(':status' => $projstatus, ':itemid' => $itemid));

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

		$stmt = $db->prepare("SELECT * FROM `tbl_payment_status` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$disableQuery = $db->prepare("UPDATE `tbl_payment_status` SET active=:active WHERE id=:itemid");
		$results = $disableQuery->execute(array(':active' => $updateStatus, ':itemid' => $itemid,));
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
