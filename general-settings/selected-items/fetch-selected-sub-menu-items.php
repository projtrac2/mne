<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_inner_menu` ORDER BY `id` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['id'];
		// status 
		if ($row['status'] == 1) {
			$active = "<label class='label label-success'>Enabled</label>";
		} else {
			$active = "<label class='label label-danger'>Disabled</label>";
		}

		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" onclick="editItem(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
				<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
			</ul>
		</div>';

		$name = $row["name"];
		$parentid = $row["parent"];
		if($parentid==0){
			$parent = "Parent";
		}else{
			$sql_menu = $db->prepare("SELECT * FROM `tbl_inner_menu` where id='$parentid'");
			$sql_menu->execute();
			$row_menu = $sql_menu->fetch();
			$parent = $row_menu["name"];
		}
		$icons = $row["icons"];
		$url = $row["url"];
		$role = $row["role"];
		$output['data'][] = array(
            $sn,
			$name,
            $parent,
			$icons,
			$url,
			$role,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);