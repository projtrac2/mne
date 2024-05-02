<?php
try {
	include '../controller.php';

	$valid['success'] = array('success' => false, 'messages' => array());
	if (isset($_POST['approveitem'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = 1;
		$datecreated = date("Y-m-d");

		for ($i = 0; $i < count($_POST['opbudget']); $i++) {
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$budget = $_POST['opbudget'][$i];
			$target = $_POST['optarget'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_based_budget`(progid, opid, indid, finyear, budget, target, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :budget, :target, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":budget" => $budget, ":target" => $target, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST['editpbbitem'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs_based_budget` WHERE progid=:progid AND finyear=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));

		for ($i = 0; $i < count($_POST['opbudget']); $i++) {
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$budget = $_POST['opbudget'][$i];
			$target = $_POST['optarget'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_based_budget`(progid, opid, indid, finyear, budget, target, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :budget, :target, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":budget" => $budget, ":target" => $target, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST['quarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));

		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {
			$pbbid = $_POST['pbbid'][$i];
			if (empty($pbbid)) {
				$pbbid = 0;
			}
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_quarterly_targets`(pbbid, progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:pbbid, :progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":pbbid" => $pbbid, ":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Quarterly targets successfully added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding quarterly targets!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST['indepedentProgramsquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_independent_programs_quarterly_targets`(progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Quarterly targets successfully added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding quarterly targets!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST['editquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));

		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {
			$pbbid = $_POST['pbbid'][$i];
			if (empty($pbbid)) {
				$pbbid = 0;
			}
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_quarterly_targets`(pbbid, progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:pbbid, :progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":pbbid" => $pbbid, ":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Quarterly targets successfully updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating quarterly targets!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST['editindependentprogramquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table
		$deleteQuery = $db->prepare("DELETE FROM `tbl_independent_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));

		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {
			$opid = $_POST['opid'][$i];
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];

			$insertSQL1 = $db->prepare("INSERT INTO `tbl_independent_programs_quarterly_targets`(progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId();
		}

		if ($last_id) {
			$valid['success'] = true;
			$valid['messages'] = "Quarterly targets successfully updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating quarterly targets!!";
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
