<?php 	
include_once "controller.php";

if(isset($_GET["fnd"]) && !empty($_GET["fnd"])){
	$hash = $_GET['fnd'];
	$decode_fndid = base64_decode($hash);
	$fndid_array = explode("fd918273AxZID", $decode_fndid);
	$fndid = $fndid_array[1];

	$sql = $db->prepare("SELECT a.id AS aid, s.sector AS sector, a.allocation AS alloc FROM tbl_departments_allocation a inner join tbl_sectors s ON s.stid=a.department WHERE a.fundid = :fid ORDER BY `id` ASC"); 
	$sql->execute(array(":fid" => $fndid));
	$rows_count = $sql->rowCount();

	$output = array('data' => array());

	if($rows_count > 0) { 
		// $row = $result->fetch_array();
		$active = ""; 
		$sn=0;
		//$totalamount = 0;
		while($row = $sql->fetch()){
			$sn++;
			$itemId = $row[0];
			$dept = $row[1];
			$alloc = $row[2];
			//$totalamount = $totalamount + $alloc;

			$amount = '<a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" tittle="Click to edit" onclick="editItem('.$itemId.')"> '.number_format($alloc,2).' </a>';

			$output['data'][] = array( 	
				$sn,
				// department
				$dept,
				// allocated amount
				$amount		
				); 	
		} // /while 

	}// if num_rows

	echo json_encode($output);
}