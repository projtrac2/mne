<?php
try {
	include_once "../controller.php";

	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$name = $_POST['name'];
		$description = $_POST['description'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_tender_category (category, description,status) VALUES(:name, :description,:status)");
		$results = $sql->execute(array(":name" => $name, ":description" => $description, ":status" => $status));
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
		$name = $_POST['editname'];
		$description = $_POST['editdescription'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_tender_category SET category=:name, description=:description WHERE id=:itemid");
		$results = $updateQuery->execute(array(":name" => $name, ":description" => $description, ':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating the record!!";
		}
		echo json_encode($valid);
	}
	if (isset($_POST["deleteItem"])) {
		$itemid = $_POST['itemId'];

		$updateStatus = '';

		$stmt = $db->prepare("SELECT * FROM `tbl_tender_category` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_tender_category` SET status=:status WHERE id=:itemid");
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
