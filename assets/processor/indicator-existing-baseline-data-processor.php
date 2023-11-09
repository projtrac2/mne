<?php
include_once "controller.php";

try {
	$valid['success'] = array('success' => false, 'messages' => array());


	if (isset($_POST['addbaseline'])) {
		$indid = $_POST['indid'];
		$level3 = $_POST['level3'];
		$location = 0;
		$base_value = $_POST['base_value'];
		$disstype = 0;
		$key = 0;

		$results = false;
		$deleteQueryD = $db->prepare("DELETE FROM `tbl_indicator_output_baseline_values` WHERE indid=:indid AND level3=:level3 AND location=:location");
		$resultsD = $deleteQueryD->execute(array(':indid' => $indid, ':level3' => $level3, ':location' => $location));

		$insertSQL = $db->prepare("INSERT INTO tbl_indicator_output_baseline_values (indid,key_unique, level3, location, disaggregations, value) VALUES (:indid, :key_unique, :level3, :location, :disaggregations, :value)");
		$results = $insertquery = $insertSQL->execute(array(':indid' => $indid, ":key_unique" => $key, ':level3' => $level3, ':location' => $location, ":disaggregations" => $disstype,  ":value" => $base_value));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while Adding the record!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST['delete_baseline'])) {
		$indicator_id = $_POST['indicator_id'];
		$ward_id = $_POST['ward_id'];
		$deleteQueryD = $db->prepare("DELETE FROM `tbl_indicator_output_baseline_values` WHERE indid=:indid AND level3=:level3");
		$resultsD = $deleteQueryD->execute(array(':indid' => $indicator_id, ':level3' => $ward_id));

		$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid=:indicator_id ");
		$query_rstotal->execute(array(":indicator_id"=>$indicator_id));
		$row_rstotal = $query_rstotal->fetch();
		$row_rstotals = $query_rstotal->rowCount();
		if ($resultsD === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = $row_rstotals > 0 ? 1 : 0;
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while Adding the record!!";
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
