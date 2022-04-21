<?php
// require_once('../../Connections/ProjMonEva.php');
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../system-labels.php");

$valid['success'] = array('success' => false, 'messages' => array());
try {
	if (isset($_POST['get_dept'])) {
		$sectorid = $_POST['get_dept'];
		$data = '<option value="" >Select ' . $departmentlabel . '</option>';
		$query_dept = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:sectorid");
		$query_dept->execute(array(":sectorid" => $sectorid));
		while ($row = $query_dept->fetch()) {
			$dept = $row['stid'];
			$data .= '<option value="' . $dept . '"> ' . $row['sector'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST['get_fyto'])) {
		$fyid = $_POST['get_fyto'];
		$data = '<option value="" >Select FY to</option>';
		$query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :fyid");
		$query_fy->execute(array(":fyid" => $fyid));
		while ($row = $query_fy->fetch()) {
			$yrid = $row['id'];
			$data .= '<option value="' . $yrid . '"> ' . $row['year'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST['get_level2'])) {
		$getward = $_POST['level1'];
		$data = '<option value="" >Select Ward</option>';
		$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
		$query_ward->execute(array(":getward" => $getward));
		while ($row = $query_ward->fetch()) {
			$projlga = $row['id'];
			$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
			$query_rsLocations->execute(array(":id" => $projlga));
			$row_rsLocations = $query_rsLocations->fetch();
			$total_locations = $query_rsLocations->rowCount();
			if ($total_locations > 0) {
				$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
			}
		}
		echo $data;
	}

	if (isset($_POST['get_level3'])) {
		$getlocation = $_POST['level2'];
		$data = '<option value="" >Select Location</option>';
		$query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation'");
		$query_loca->execute();
		while ($row = $query_loca->fetch()) {
			$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST["addremarks"])) {
		$projid = $_POST['projid'];
		$remarks = $_POST['remarks'];
		$user = $_POST['username'];
		$currentdate = date("Y-m-d");

		$sql = $db->prepare("INSERT INTO tbl_projects_performance_report_remarks (projid, remarks, created_by, date_created) VALUES(:projid,:remarks,:user, :date)");
		$results = $sql->execute(array(":projid" => $projid, ":remarks" => $remarks, ":user" => $user, ":date" => $currentdate));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Remarks successfully saved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the remarks!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST["addindicatorremarks"])) {
		$indid = $_POST['indicator'];
		$year = $_POST['year'];
		$remarks = $_POST['remarks'];
		$user = $_POST['username'];
		$currentdate = date("Y-m-d");

		$sql = $db->prepare("INSERT INTO tbl_capr_report_remarks (indid, year, remarks, created_by, created_at) VALUES(:indid,:year, :remarks,:user, :date)");
		$results = $sql->execute(array(":indid" => $indid, ":year" => $year, ":remarks" => $remarks, ":user" => $user, ":date" => $currentdate));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Remarks successfully saved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the remarks!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST["addquarterindicatorremarks"])) {
		$indid = $_POST['indicator'];
		$year = $_POST['year'];
		$remarks = $_POST['remarks'];
		$user = $_POST['username'];
		$quarter = $_POST['quarter'];
		$currentdate = date("Y-m-d");

		$sql = $db->prepare("INSERT INTO tbl_qapr_report_remarks (indid, year, quarter, remarks, created_by, created_at) VALUES(:indid,:year,:quarter, :remarks,:user, :date)");
		$results = $sql->execute(array(":indid" => $indid, ":year" => $year,":quarter"=>$quarter,":remarks" => $remarks, ":user" => $user, ":date" => $currentdate));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Remarks successfully saved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the remarks!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST["deleteRemarks"])) {
		$itemid = $_POST['itemId'];
		$deleteQuery = $db->prepare("DELETE FROM `tbl_strategic_plan_objectives` WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		$deleteQuery = $db->prepare("DELETE FROM `tbl_objective_strategy` WHERE objid=:itemid");
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
