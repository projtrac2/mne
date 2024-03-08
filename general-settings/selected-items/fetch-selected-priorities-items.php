<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_priorities` ORDER BY `id` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['id'];
		$priority = $row["priority"];

		// status 
		if ($row['status'] == 1) {
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
				<li>
					<a type="button" id="disableBtn" class="disableBtn" onclick="disable('.$itemId.',\''.$priority.'\',\''.$wordings.'\')">
						<i class="glyphicon glyphicon-trash"></i> ' . $wordingsCapital . '
					</a>
				</li>	
			</ul>
		</div>';

		$description = $row["description"];
		$weight = $row["weight"];
		$output['data'][] = array(
			$sn,
			$priority,
			$description,
			$weight,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);