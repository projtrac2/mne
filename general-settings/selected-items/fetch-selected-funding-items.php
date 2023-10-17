<?php


include_once "controller.php"; 

$valid['success'] = array('success' => false, 'messages' => array());
if(isset($_POST["deleteItem"])){
	$itemid = $_POST['itemId'];
	$cat = "Funding";
	
	$deleteQuery = $db->prepare("DELETE FROM `tbl_funds` WHERE id = :fndid");
	$deleteQuery->execute(array(":fndid" => $itemid)); 

	$query_select =  $db->prepare("SELECT * FROM tbl_files WHERE projstage=:itemid AND fcategory=:cat");
	$query_select->execute(array(':itemid' => $itemid, ':cat' => $cat));
	
	while($row_select = $query_select->fetch()){
		$fid = $row_select["fid"];
		$floc = $row_select["floc"];
		unlink($floc);
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE fid=:fid");
		$results = $deleteQuery->execute(array(':fid' => $fid)); 	
	}
	
	if($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Deleted";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deletng the record!!";
	}  
	echo json_encode($valid); 
}