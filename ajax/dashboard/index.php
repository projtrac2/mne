<?php


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
if (isset($_POST['get_indicator'])) {
	$department_id = $_POST['department'];
	// get output indicators 
	$query_rsOutputIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_sector='$department_id' AND indicator_category='Output' AND active = '1' ORDER BY indid");
	$query_rsOutputIndicators->execute(); 
	$totalRows_rsOutputIndicators = $query_rsOutputIndicators->rowCount();

	$input ='';
	if ($totalRows_rsOutputIndicators > 0) {
		$input .=  '<option value=""> Select Indicator</option>';
		while ($row_rsOutputIndicators = $query_rsOutputIndicators->fetch()) {
			$input .= '<option value="'. $row_rsOutputIndicators['indid'].'">'.$row_rsOutputIndicators['indicator_name'].'</option>';
		}
	} else {
		$input .=  '<option value=""> Indicators Not Found</option>';
	}
	echo $input;
}

if (isset($_POST['get_level1'])) {
}

if (isset($_POST['get_level2'])) {
}

if (isset($_POST['get_level3'])) {
}
