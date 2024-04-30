<?php

include_once '../../controller.php';

try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$category = $_POST['category'];
		$description = $_POST['description'];
		$type = $_POST['editType'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_indicator_categories (category, description, active, indicator_type) VALUES(:category, :description, :status, :indicator_type)");
		$results = $sql->execute(array(":category" => $category, ":description" => $description, ":status" => $status, 'indicator_type' => $type));

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
		$category = $_POST['editCategory'];
		$description = $_POST['editDescription'];
		$type = $_POST['editType'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_indicator_categories SET category=:category, description=:description, indicator_type=:indicator_type WHERE catid=:itemid");
		$results = $updateQuery->execute(array(':category' => $category, ':description' => $description, ':indicator_type' => $type, ':itemid' => $itemid));

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

		$stmt = $db->prepare("SELECT * FROM `tbl_indicator_categories` where catid=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_indicator_categories` SET active=:active WHERE catid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid, ':active' => $updateStatus));

		if ($results === TRUE) {
			$valid = true;
		} else {
			$valid = false;
		}

		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	echo $ex->getMessage();
}
