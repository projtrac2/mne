<?php

include_once "controller.php";
try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$unit = $_POST['unit'];
		$description = $_POST['description'];
		$status = 1;

		$sql = $db->prepare("INSERT INTO tbl_measurement_units (unit,description, active) VALUES(:unit,:description, :status )");
		$results = $sql->execute(array(":unit" => $unit, ":description" => $description, ":status" => $status));

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
		$unit = $_POST['editunit'];
		$description = $_POST['editdescription'];
		$itemid = $_POST['itemId'];

		$sql = $db->prepare("UPDATE tbl_measurement_units SET unit=:unit,description=:description WHERE id =:id");
		$results = $sql->execute(
			array(
				":unit" => $unit,
				":description" => $description,
				":id" => $itemid
			)
		);

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

		$stmt = $db->prepare("SELECT * FROM `tbl_measurement_units` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_measurement_units` SET active=:active WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid, ':active' => $updateStatus));

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
