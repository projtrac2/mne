<?php
include_once "controller.php";
$sql = $db->prepare("SELECT * FROM `tbl_big_four_agenda` ORDER BY `id` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
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

		$agenda = $row["agenda"];
		$description = $row["description"];
		$output['data'][] = array(
			$sn,
			$agenda,
			$description, 
			$active,
			$button
		);
	}

}

echo json_encode($output);