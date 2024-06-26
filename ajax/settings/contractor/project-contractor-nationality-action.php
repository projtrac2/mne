<?php
try {
	include_once "../controller.php";
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$nationality = $_POST['nationality'];
		$description = $_POST['description'];
		$createdby = $_POST['createdby'];
		$datecreated = date('Y-m-d');
		$status = 1;
		$sql = $db->prepare("INSERT INTO tbl_contractornationality (nationality, description, active, created_by, date_created) VALUES(:nationality, :description, :status, :createdby, :datecreated)");
		$results = $sql->execute(array(
			":nationality" => $nationality,
			":description" => $description,
			":createdby" => $createdby,
			":datecreated" => $datecreated,
			":status" => $status
		));

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
		$nationality = $_POST['editNationality'];
		$description = $_POST['editDescription'];
		$createdby = $_POST['createdby'];
		$dateupdated = date('Y-m-d H:m:s');
		$itemid = $_POST['itemId'];

		$sql = $db->prepare("UPDATE tbl_contractornationality SET nationality=:nationality,description=:description, created_by=:createdby,
		date_created=:dateupdated WHERE id =:id");
		$results = $sql->execute(array(":nationality" => $nationality, ":description" => $description, ":createdby" => $createdby, ":dateupdated" => $dateupdated, ":id" => $itemid));

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

		$stmt = $db->prepare("SELECT * FROM `tbl_contractornationality` where id=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_contractornationality` SET active=:status WHERE id=:itemid");
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
