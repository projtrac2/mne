<?php
//Include database configuration file
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';

if (isset($_POST['fundtype'])) {
	$fundtype = $_POST['fundtype'];
	#echo 'department selected';
	$query_fundtype = $db->prepare("SELECT f.id, financier FROM tbl_funding_type t inner join tbl_financiers f ON f.type=t.category WHERE t.id=:fundtype and f.active=1");
	$query_fundtype->execute(array(":fundtype" => $fundtype));
	$rowcount = $query_fundtype->rowCount();
	
	$data = "";
	if($rowcount > 0){
		$data .= '<select name="department" id="department" class=" form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%"  required><option value="">...Select Financier...</option>';
		while ($row = $query_fundtype->fetch()) {
			$data .= '<option value="' . $row['id'] . '"> ' . $row['financier'] . '</option>';
		}
		$data .= '</select>';
	} else {
		$data .= '<select name="department" id="department" class=" form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%"  required><option value="">...Financier/s not defined...</option>';
		$data .= '</select>';
	}
	echo $data;
}

$valid['success'] = array('success' => false, 'messages' => array());
if(isset($_POST["deleteItem"])){
	$itemid = $_POST['itemId'];
	
	$query_select =  $db->prepare("SELECT floc FROM tbl_files WHERE fid = :fid");
	$query_select->execute(array(":fid" => $itemid));
	$row_select = $query_select->fetch();
	$floc = $row_select["floc"];
	unlink($floc);
	
	$deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE fid=:itemid");
	$results = $deleteQuery->execute(array(':itemid' => $itemid)); 
	
	if($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Deleted";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deletng the record!!";
	}  
	echo json_encode($valid); 
}