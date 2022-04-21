<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_datacollectionfreq` ORDER BY `fqid` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['fqid'];
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

		$frequency = $row["frequency"];
		$days = $row["days"];
		$date_created = $row["date_created"];
		$date_modified = $row["date_modified"];

		//person who created the data
		$creatorid =$row["created_by"];
		$creator = $db->prepare("SELECT * FROM tbl_projteam2  WHERE ptid='$creatorid' LIMIT 1"); 
		$creator->execute();
		$resultC = $creator->fetch();
		$createdby =$resultC['fullname'];

		//the person who modified the data
		$modifiedby = $row["modified_by"];
		$modifier = $db->prepare("SELECT * FROM tbl_projteam2  WHERE ptid='$modifiedby' LIMIT 1"); 
		$modifier->execute();
		$resultM = $modifier->fetch();
		$modifiedby =$resultM['fullname'];				
		$output['data'][] = array(
			$sn,
			$frequency,
			$days,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);