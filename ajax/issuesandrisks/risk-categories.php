<?php 
include '../controller.php';
if(isset($_POST['delete'])){
	$riskid = $_POST['riskid'];
	$query_rsRisk = $db->prepare("DELETE FROM tbl_projrisk_categories WHERE catid = '$riskid'");
	if($query_rsRisk->execute()){
		echo json_encode(array('success'=>true));
	}else{
		echo json_encode(array('success'=>false));
	}
}