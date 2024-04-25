<?php

include_once "controller.php";
try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$title = $_POST['title'];
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_titles (title, status) VALUES(:title, :status)");
		$results = $sql->execute(array(":title" => $title, ":status" => $status));
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
		$title = $_POST['editTitle'];
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];

		$updateQuery = $db->prepare("UPDATE tbl_titles SET title=:title,  status=:status WHERE id=:itemid");
		$results = $updateQuery->execute(array(':title' => $title, ':status' => $status, ':itemid' => $itemid));

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

		$deleteQuery = $db->prepare("DELETE FROM `tbl_titles` WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
