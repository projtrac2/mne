<?php
date_default_timezone_set("Africa/Nairobi");
//include_once 'projtrac-dashboard/resource/session.php';
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';

$itemId = $_POST['itemId'];
$query_item = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id = '$itemId'");
$query_item->execute();
$rows_count = $query_item->rowCount();

if ($rows_count > 0) {
    $row =  $query_item->fetch();
} 

echo json_encode($row);
