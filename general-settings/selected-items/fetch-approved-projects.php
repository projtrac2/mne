<?php
include_once "controller.php";
if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
	$prgid = $_GET["prg"];
	$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE progid = '$prgid' ORDER BY `projid` ASC");
	$sql->execute();
} else {
	$sql = $db->prepare("SELECT * FROM `tbl_projects` ORDER BY `projid` ASC");
	$sql->execute();
}

$rows_count = $sql->rowCount();
$output = array('data' => array());

if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['projid'];
		// status 
		if ($row['projstage'] == 2) {
			$active = "<label class='label label-success'>Approved</label>";
			$checkbox  = '
			<i class="fa fa-check" aria-hidden="true" style="font-size:20px;color:green"></i> 
				<input type="hidden" name="projects[]" id="projects' . $itemId . '" class="checkboxes" value="' . $itemId . '" onchange="checkBoxes( ' . $itemId . ' )" >
				 ';
			$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
			<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>
				<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>       
				<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
				<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
			</ul> 
		</div>';
		} elseif ($row['projstage'] == 1) {
			$active = "<label class='label label-danger'>Pending</label>";
			$checkbox  = '
			<div class="form-line">
				<input type="checkbox" name="projects[]" id="projects' . $itemId . '" class="checkboxes" value="' . $itemId . '" onchange="checkBoxes( ' . $itemId . ' )" >
				<label for="projects' . $itemId . '"></label>
			</div>';
			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Approve</a></li>
					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>       
					<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
					<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
				</ul>
			</div>';
		}

		$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
		$query_rsBudget->execute();
		$row_rsBudget = $query_rsBudget->fetch();
		$totalRows_rsBudget = $query_rsBudget->rowCount();
		$projbudget = $row_rsBudget['budget'];

		$projname = $row["projname"];
		$budget = number_format($projbudget, 2);
		$progid = $row["progid"];
		$srcfyear = $row["projfscyear"];

		//get program and department 
		$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
		$prog->execute(array(":progid" => $progid));
		$rowprog = $prog->fetch();
		$projdept = $rowprog["projdept"];
		$progname = $rowprog["progname"];

		//get financial year
		$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
		$query_projYear->execute(array(":srcfyear" => $srcfyear));
		$rowprojYear = $query_projYear->fetch();
		$projYear  = $rowprojYear['year'];

		// get department 
		$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
		$query_rsDept->execute(array(":sector" => $projdept));
		$row_rsDept = $query_rsDept->fetch();
		$department = $row_rsDept['sector'];
		$totalRows_rsDept = $query_rsDept->rowCount();
		$output['data'][] = array(
			$checkbox,
			$sn,
			$projname,
			$progname,
			$department,
			$budget,
			$projYear,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);
