<?php

include_once "controller.php";

if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
	$prgid = $_GET["prg"];
	$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE progid = '$prgid' ORDER BY `projfscyear` ASC");
	$sql->execute();
} else {
	$sql = $db->prepare("SELECT * FROM `tbl_projects` ORDER BY `projfscyear` ASC");
	$sql->execute();
}

$org = isset($_GET['org']) ? $_GET['org'] : 1;



$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['projid'];
		$username = $row['user_name'];

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


		// add_to_adp remove_adp edit delete 
		$project_department = $rowprog['projsector'];
		$project_section = $rowprog['projdept'];
		$project_directorate = $rowprog['directorate'];

		// edit delete approve_project unapprove_project
		$edit = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit1);
		$edit =   ($edit) ?  $permissions->verify_created_by($username) : false;
		$delete = $permissions->verify_action($project_department, $project_section, $project_directorate, $delete1);
		$unapprove_project = $permissions->verify_action($project_department, $project_section, $project_directorate, $unapprove_project1);


		//get financial year
		$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
		$query_projYear->execute(array(":srcfyear" => $srcfyear));
		$rowprojYear = $query_projYear->fetch();
		$projYear  = $rowprojYear['year'];
		$yr  = $rowprojYear['yr'];

		// get department 
		$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
		$query_rsDept->execute(array(":sector" => $projdept));
		$row_rsDept = $query_rsDept->fetch();
		$department = $row_rsDept['sector'];
		$totalRows_rsDept = $query_rsDept->rowCount();

		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';

		$button = "";
		// status
		if ($row['projplanstatus'] == 1) {
			$active = "<label class='label label-success'>Approved</label>";
			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">';

			if ($org == 1 && $unapprove_project) {
				$button .= '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
			}
			$button .= '
					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>      
				</ul> 
			</div>';
		} else {
			$currentYear = '';
			$month =  date('m');
			if ($month  < 7) {
				$currentYear =  date("Y") - 1;
			} else {
				$currentYear =  date("Y");
			}
			$approve = '';
			$active = "<label class='label label-danger'>Pending</label>";
			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">';

			// if ($edit) {
			// 	$button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
			// }

			// if ($delete) {
			// 	$button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>';
			// }
			$button .= '
					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>      
				</ul> 
			</div>';
		}

		$output['data'][] = array(
			$sn,
			$projname,
			$progname,
			$budget,
			$projYear,
			$active,
			$button
		);
	} // /while 

} // if num_rows

echo json_encode($output);
