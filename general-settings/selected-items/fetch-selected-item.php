<?php 	

include_once "controller.php";

$itemId = $_POST['itemId'];
//var_dump($itemId);
	
$query_item = $db->prepare("SELECT * FROM tbl_project_evaluation_types WHERE id = '$itemId'");
$query_item->execute();	
$rows_count = $query_item->rowCount();

if($rows_count > 0) { 
 $row =  $query_item->fetch();
} // if num_rows

echo json_encode($row);