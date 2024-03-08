<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage_timelines` ORDER BY `id` ASC");
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
		$category = $row["category"];
		$status = $row["status"];
		$wordings = '';
		$wordingsCapital = '';
		$title = "$category $status";
		// status 
		if ($row['active'] == 1) {
			$active = "<label class='label label-success'>Enabled</label>";
			$wordings = 'disable';
			$wordingsCapital = 'Disable';
		} else {
			$active = "<label class='label label-danger'>Disabled</label>";
			$wordings = 'enable';
			$wordingsCapital = 'Enable';
		}

		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" onclick="editItem(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
				<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
				<a type="button" id="disableBtn" class="disableBtn" onclick=disable(' . $itemId . ',"' . $title . '","' . $wordings . '")>
					<i class="glyphicon glyphicon-trash"></i> ' . $wordingsCapital . '
				</a>
			</ul>
		</div>';

		
		$stage = $row["stage"];
		$description = $row["description"];
		$time = $row["time"];
		$units = $row["units"];
		$output['data'][] = array(
			$sn,
			$category,
			$stage,
			$status,
			$description,
			$time,
			$units,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);