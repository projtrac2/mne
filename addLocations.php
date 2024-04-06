<?php
try {
	//code...

//include_once 'projtrac-dashboard/resource/session.php';
date_default_timezone_set("Africa/Nairobi");
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
//$currentdate = date("Y-m-d");

if (isset($_POST["sc_id"]) && $_POST["sc_id"] !== "0") {
	//Get all state data
	$locid = $_POST['sc_id'];
	if ($_POST["sc_id"] == 1) {
		echo '
		<option value="">Select Level-2</option>
        <option value="">Level-2 not required</option>';
	} else {
		$myquery = $db->prepare("SELECT id,state FROM tbl_state WHERE parent = " . $locid . " AND parent IS NOT NULL ORDER BY id ASC");
		$myquery->execute();
		//Count total number of rows
		$rowCount = $myquery->rowCount();
		//Display states list
		if ($rowCount > 0) {
			echo '<option value="">Select Level-2</option>
				  <option value="' . $_POST["sc_id"] . '">Level-2</option>';
			while ($row = $myquery->fetch()) {
				echo '<option value="' . $row['id'] . '">' . $row['state'] . '</option>';
			}
		} else {
			echo '<option value="">Select Level-2</option>
				  <option value="' . $_POST["sc_id"] . '">Level-2</option>';
		}
	}
} elseif (isset($_POST["sc_id"]) && $_POST["sc_id"] == "1") {
	echo '<input type="text" name="location" class="form-control" id="location" placeholder="Enter Level-1" required="required" style="height:35px; width:98%"/>';
}
} catch (\Throwable $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}