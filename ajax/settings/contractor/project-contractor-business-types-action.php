<?php
try {
	include_once "../controller.php";
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$type = $_POST['type'];
		$description = $_POST['description'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_contractorbusinesstype (type, description, status) VALUES(:type, :description, :status)");
		$results = $sql->execute(array(":type" => $type, ":description" => $description, ":status" => $status));

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
		$type = $_POST['editType'];
		$description = $_POST['editDescription'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_contractorbusinesstype SET type=:type, description=:description WHERE id=:itemid");
		$results = $updateQuery->execute(array(':type' => $type, ':description' => $description, ':itemid' => $itemid));

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

		$stmt = $db->prepare("SELECT * FROM `tbl_contractorbusinesstype` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_contractorbusinesstype` SET status=:status WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid, ':status' => $updateStatus));

		if ($results === TRUE) {
			$valid = true;
		} else {
			$valid = false;
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
