<?php
include_once "controller.php";
$fyrid = $_GET["fy"];
$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join tbl_annual_dev_plan d ON d.projid=p.projid WHERE financial_year='$fyrid' ORDER BY financial_year ASC");
$sql->execute();

$rows_count = $sql->rowCount();

$output = array('data' => array());
if ($rows_count > 0) {
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$itemId = $row['projid'];
		$projname = $row["projname"];
		$progid = $row["progid"];
		$srcfyear = $row["projfscyear"];

		//get financial year
		$query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
		$query_projYear->execute(array(":srcfyear" => $srcfyear));
		$rowprojYear = $query_projYear->fetch();
		$projYear  = $rowprojYear['year'];
		$yr  = $rowprojYear['yr'];

		$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_output_details WHERE projid ='$itemId' AND year = '$yr'");
		$query_rsBudget->execute();
		$row_rsBudget = $query_rsBudget->fetch();
		$totalRows_rsBudget = $query_rsBudget->rowCount();
		$projbudget = $row_rsBudget['budget'];
		$budget = number_format($projbudget, 2);

		//get program and department 
		$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
		$prog->execute(array(":progid" => $progid));
		$rowprog = $prog->fetch();
		$projdept = $rowprog["projdept"];


		// add_to_adp remove_adp edit delete 
		$project_department = $rowprog['projsector'];
		$project_section = $rowprog['projdept'];
		$project_directorate = $rowprog['directorate'];
		// remove_adp approve unapprove
		$approve = $permissions->verify_action($project_department, $project_section, $project_directorate, $approve1);
		$unapprove = $permissions->verify_action($project_department, $project_section, $project_directorate, $unapprove1);
		$remove_adp = $permissions->verify_action($project_department, $project_section, $project_directorate, $remove_adp1);

		// get department 
		$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
		$query_rsDept->execute(array(":sector" => $projdept));
		$row_rsDept = $query_rsDept->fetch();
		$department = $row_rsDept['sector'];
		$totalRows_rsDept = $query_rsDept->rowCount();

		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';
		// get department 
		$query_buttonunapprov =  $db->prepare("SELECT * FROM tbl_projects WHERE projstage = 2 and projid =:projid");
		$query_buttonunapprov->execute(array(":projid" => $itemId));
		$row_buttonunapprov = $query_buttonunapprov->rowCount();


		if ($row_buttonunapprov > 0 && $unapprove) {
			$buttonunapprov = '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
		} else {
			$buttonunapprov = '';
		}

		// status
		if ($row['projplanstatus'] == 1) {
			$active = "<label class='label label-success'>Approved</label>";
			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					' . $buttonunapprov . '
					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>      
				</ul> 
			</div>';
		} else {
			// get approved program based annual plan 
			$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid = :progid and finyear = :adpyr");
			$query_pbb->execute(array(":progid" => $progid, ":adpyr" => $yr));
			$norows_pbb = $query_pbb->rowCount();

			// get approved program based annual plan 
			$query_quarterly_targets =  $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid = :progid and year = :adpyr");
			$query_quarterly_targets->execute(array(":progid" => $progid, ":adpyr" => $yr));
			$norows_quarterly_targets = $query_quarterly_targets->rowCount();

			$currentYear = '';
			$month =  date('m');
			if ($month  < 7) {
				$currentYear =  date("Y") - 1;
			} else {
				$currentYear =  date("Y");
			}

			$active = "<label class='label label-danger'>Pending</label>";

			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu"> 
			<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>
			';
			if ($approve && $currentYear >= $yr && $norows_pbb >  0 && $norows_quarterly_targets >  0) {
				$button .= '
					<li>
						<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
						<i class="fa fa-check-square-o"></i> Approve Project
						 </a>
					 </li>';
			}
			if ($remove_adp) {
				$button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove From ADP</a></li>';
			}
			$button .= '</ul>
			</div>';
		}

		$filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);

		if ($filter_department) {
			$sn++;
			$output['data'][] = array(
				$sn,
				$projname,
				$progname,
				$budget,
				$projYear,
				$active,
				$button
			);
		}
	} // /while        
} // if num_rows

echo json_encode($output);
