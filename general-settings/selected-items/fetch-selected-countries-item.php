<?php

include_once "controller.php";

$itemId = $_POST['itemId'];
$query_item = $db->prepare("SELECT * FROM countries WHERE id = '$itemId'");
$query_item->execute();
$rows_count = $query_item->rowCount();

if ($rows_count > 0) {
    $row =  $query_item->fetch();
} 

echo json_encode($row);