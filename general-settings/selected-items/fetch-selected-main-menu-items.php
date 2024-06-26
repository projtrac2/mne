<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_sidebar_menu` ORDER BY `id` ASC");
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

		$parent = $row["parent"];
		if($parent != 0){
			$sqlparent = $db->prepare("SELECT * FROM `tbl_sidebar_menu` WHERE id='$parent' ");
			$sqlparent->execute();
			$rowparent = $sqlparent->fetch();
			$parent =$rowparent['Name'];
		}else{
			$parent ="parent";
		}

		$name = $row["Name"];
		$icons = $row["icons"];
		$url = $row["url"];
		$output['data'][] = array(
            $sn,
            $parent,
			$name,
			$icons,
			$url,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);
