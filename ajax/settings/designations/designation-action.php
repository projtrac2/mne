<?php
try {
	include_once "../controller.php";

	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if (isset($_POST["newitem"])) {
		$designation = $_POST['designation'];
		$reporting = $_POST['reporting'];
		$level = $_POST['level'];
		$sql = $db->prepare("INSERT INTO tbl_pmdesignation (designation, Reporting, level) VALUES(:design,:report, :level)");
		$results = $sql->execute(array(":design" => $designation, ":report" => $reporting, ':level' => $level));
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
		$designation = $_POST['designation'];
		$reporting = $_POST['reporting'];
		$level = $_POST['level'];
		$itemid = $_POST['itemId'];

		$sql = $db->prepare("UPDATE tbl_pmdesignation SET designation=:designation, reporting=:reporting, level=:level WHERE moid =:id");
		$results = $sql->execute(
			array(
				":designation" => $designation,
				":reporting" => $reporting,
				":level" => $level,
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

		$deleteQuery = $db->prepare("DELETE FROM `tbl_pmdesignation` WHERE moid=:itemid");
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
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
