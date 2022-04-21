<?php
//Include database configuration file
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
require('../../functions/indicator.php');
require('../../functions/department.php');

// get department
if (isset($_POST['get_department'])) {
    $dept = $_POST['sector_id'];
    $departments = get_department_child($dept);
    if ($departments) {
        $department = '<option value="">Select Division</option>';
        for ($i = 0; $i < count($departments); $i++) {
            $department .= '<option value="' . $departments[$i]['stid'] . '"> ' . $departments[$i]['sector'] . '</option>';
        }
        echo json_encode(array('success' => true, 'department' => $department));
    } else {
        echo json_encode(array('success' => false));
    }
}


if (isset($_POST['leaveid'])) {
    $leaveid = $_POST["leaveid"];
    $ptid = $_POST["ptid"];

    $currentYear = date("Y");
    $query_rsLeaveDays =  $db->prepare("SELECT * FROM tbl_employee_leave_bal WHERE category='$leaveid' AND staff = '$ptid' AND year='$currentYear' ORDER BY id ASC");
    $query_rsLeaveDays->execute();
    $row_rsLeaveDays = $query_rsLeaveDays->fetch();

    $query_rsEmpDetails =  $db->prepare("SELECT title, fullname FROM tbl_projteam2 WHERE ptid = '$ptid'");
    $query_rsEmpDetails->execute();
    $row_rsEmpDetails = $query_rsEmpDetails->fetch();

    $current_date = date("Y-m-d");

    echo '
		<div class="col-md-12">
			<label>Employee Name : <font color="indigo">' . $row_rsEmpDetails["title"] . '. ' . $row_rsEmpDetails["fullname"] . '</font></label>
		</div>
		<div class="col-md-4">
			<label>Balance Brought Forward: <font color="indigo">' . $row_rsLeaveDays["balforward"] . ' Days</font></label>
		</div>
		<div class="col-md-4">
			<label>Days For Year ' . $row_rsLeaveDays["year"] . ': <font color="indigo">' . $row_rsLeaveDays["days"] . ' Days</font></label>
		</div>
		<div class="col-md-4">
			<label>Remaining Leave Days : <font color="indigo">' . $row_rsLeaveDays["totaldays"] . ' Days</font></label>
		</div>
		<input type="hidden" name="catid" id="catid" value="' . $leaveid . '"/>
		<input type="hidden" name="remleavedays" id="catid" value="' . $row_rsLeaveDays["totaldays"] . '"/>
	';
}



if ($_POST["action"] == "level") {
    $output = array();
    $statement = $db->prepare("SELECT level FROM tbl_pmdesignation WHERE moid = '" . $_POST["id"] . "' LIMIT 1");
    $statement->execute();
    while ($row = $statement->fetch()) {
        $output[] = array('level' => $row["level"]);
    }
    echo json_encode($output);
}

if ($_POST["action"] == "design") {
    $query = $db->prepare("SELECT level FROM tbl_pmdesignation WHERE moid = '" . $_POST["dnid"] . "' LIMIT 1");
    $query->execute();
    $level = $query->fetch();

    if ($level["level"] == 0) {
        echo '<option value="0">All Conservancies</option>';
    } else {
        $statement = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
        $statement->execute();
        $row_rsSubcounty = $statement->fetch();
        $rowcount = $statement->rowCount();
        if ($rowcount > 0) {
            echo '<option value="">... Select Conservancy ...</option>';
            while ($row = $statement->fetch()) {
                echo '<option value="' . $row['id'] . '">' . $row['state'] . '</option>';
            }
        } else {
            echo '<option value="">... Conservancies Not Defined ...</option>';
        }
    }
}

if ($_POST["action"] == "department") {
    $output = array();
    $statement = $db->prepare("SELECT * FROM tbl_sectors WHERE parent = '" . $_POST["stid"] . "'");
    $statement->execute();
    $rowcount = $statement->rowCount();
    if ($rowcount > 0) {
        echo '<option value="">... Select Department ...</option>';
        while ($row = $statement->fetch()) {
            echo '<option value="' . $row['stid'] . '">' . $row['sector'] . '</option>';
        }
    } else {
        echo '<option value="">... Department Not Defined ...</option>';
    }
}

if ($_POST["action"] == "ward") {
    $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '" . $_POST["wdid"] . "'");
    $statement->execute();
    $rowcount = $statement->rowCount();
    if ($rowcount > 0) {
        echo '<option value="">... Select Ecosystem ...</option>';
        while ($row = $statement->fetch()) {
            echo '<option value="' . $row['id'] . '">' . $row['state'] . '</option>';
        }
    } else {
        echo '<option value="">... Ecosystem Not Defined ...</option>';
    }
}

if ($_POST["action"] == "station") {
    $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '" . $_POST["lcid"] . "'");
    $statement->execute();
    $rowcount = $statement->rowCount();
    if ($rowcount > 0) {
        echo '<option value="">... Select Station ...</option>';
        while ($row = $statement->fetch()) {
            echo '<option value="' . $row['id'] . '">' . $row['state'] . '</option>';
        }
    } else {
        echo '<option value="">... Station Not Defined ...</option>';
    }
}
