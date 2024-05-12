<?php
try {
	include_once '../controller.php';
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$frequency = $_POST['frequency'];
		$days = $_POST['days'];
		$createdby = $_POST['createdby'];
		$date_created = date('Y-m-d h:i:s');
		$status = 1;
		$level = 8;
		$sql = $db->prepare("INSERT INTO tbl_datacollectionfreq (frequency,days,created_by,date_created,status,level)
		VALUES(:frequency, :days, :createdby, :date_created, :status, :level)");
		$results = $sql->execute(array(":frequency" => $frequency, ":days" => $days, ":createdby" => $createdby, ":date_created" => $date_created, ":status" => $status, ':level' => $level));

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
		$frequency = $_POST['editfrequency'];
		$days = $_POST['editdays'];
		$createdby = $_POST['createdby'];
		$date_modified = date('Y-m-d h:i:s');
		$itemid = $_POST['itemId'];
		$sql = $db->prepare("UPDATE tbl_datacollectionfreq SET frequency=:frequency,days=:days, modified_by=:createdby,
		date_modified=:date_modified WHERE fqid =:id");
		$results = $sql->execute(
			array(
				":frequency" => $frequency,
				":days" => $days,
				":createdby" => $createdby,
				":date_modified" => $date_modified,
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

		$stmt = $db->prepare("SELECT * FROM `tbl_datacollectionfreq` where fqid=:itemid");
		$stmt->execute([':itemid' => $itemid]);
		$stmt_results = $stmt->fetch();

		$stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

		$deleteQuery = $db->prepare("UPDATE `tbl_datacollectionfreq` SET status=:status WHERE fqid=:itemid");
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
