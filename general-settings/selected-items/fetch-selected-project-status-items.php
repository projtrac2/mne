<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM tbl_status ORDER BY `statusid` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['statusid'];
		$status = $row["statusname"];
		$ac = $row['active'];
		$wordings = '';
		$wordingsCapital = '';
		// status 
		if ($ac == 1) {
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
					<a type="button" id="disableBtn" class="disableBtn" onclick="disable('.$itemId.',\''.$status.'\',\''.$wordings.'\')">
						<i class="glyphicon glyphicon-trash"></i> ' . $wordingsCapital . '
					</a>
				</li>	
			';
				//<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
			
			$button .= '</ul>
		</div>';

		$level = $row["level"];
		$output['data'][] = array(
			$sn,
			$status,
			$level,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);