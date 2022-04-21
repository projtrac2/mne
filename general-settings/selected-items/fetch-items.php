<?php 	
include_once "controller.php";
$sql = $db->prepare("SELECT * FROM `tbl_project_evaluation_types` ORDER BY `id` ASC"); 
$sql->execute();
$rows_count = $sql->rowCount();

$output = array('data' => array());

if($rows_count > 0) { 
	// $row = $result->fetch_array();
	$active = ""; 
	$sn=0;
	while($row = $sql->fetch()){
		$sn++;
		$itemId = $row[0];
		// active 
		if($row[4] == 1) {
			// activate member
			$active = "<label class='label label-success'>Enabled</label>";
		} else {
			// deactivate member
			$active = "<label class='label label-danger'>Disabled</label>";
		} // /else

		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" onclick="editItem('.$itemId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
				<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem('.$itemId.')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>       
			</ul>
		</div>';

		// $brandId = $row[3];
		// $brandSql = "SELECT * FROM brands WHERE brand_id = $brandId";
		// $brandData = $connect->query($sql);
		// $brand = "";
		// while($row = $brandData->fetch_assoc()) {
		// 	$brand = $row['brand_name'];
		// }

		$type = $row[1];
		$description = $row[2];

		$output['data'][] = array( 	
			$sn,
			// image
			$type,
			// product name
			$description,
			// active
			$active,
			// button
			$button 		
			); 	
	} // /while 

}// if num_rows

echo json_encode($output);