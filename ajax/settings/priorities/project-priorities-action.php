<?php

include_once '../../controller.php';

try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$priority = $_POST['priority'];
		$description = $_POST['description'];
		$weight = $_POST['weight'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_priorities (priority, description,weight, status) VALUES(:priority, :description,:weight, :status)");
		$results = $sql->execute(array(":priority" => $priority, ":description" => $description, ":weight" => $weight,  ":status" => $status));
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
		$priority = $_POST['editpriority'];
		$description = $_POST['editdescription'];
		$weight = $_POST['editweight'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_priorities SET priority=:priority, description=:description,weight=:weight WHERE id=:itemid");
		$results = $updateQuery->execute(array(":priority" => $priority, ":description" => $description, ":weight" => $weight, ':itemid' => $itemid));

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

		$stmt = $db->prepare("SELECT * FROM `tbl_priorities` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_priorities` SET status=:status WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid, ':status' => $updateStatus));

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
