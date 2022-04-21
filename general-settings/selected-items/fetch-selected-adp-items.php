<?php
include_once "controller.php";
$fyrid = $_GET["fy"];
$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join tbl_annual_dev_plan d ON d.projid=p.projid WHERE financial_year='$fyrid' ORDER BY financial_year ASC");
$sql->execute();

$rows_count = $sql->rowCount();

$output = array('data' => array());
if ($rows_count > 0) {
	// $row = $result->fetch_array();
	$active = "";
	$sn = 0;
	while ($row = $sql->fetch()) {
		$sn++;
		$itemId = $row['projid'];

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
		
		$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="'.$department.'" style="color:#2196F3">'.$rowprog["progname"].'</span>';
		// get department 
		$query_buttonunapprov =  $db->prepare("SELECT * FROM tbl_projects WHERE projstage = 2 and projid =:projid");
		$query_buttonunapprov->execute(array(":projid" => $itemId));
		$row_buttonunapprov = $query_buttonunapprov->rowCount();
		
		
		if($row_buttonunapprov > 0){
			$buttonunapprov = '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
			//var_dump($row_buttonunapprov);
		}else{
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
				'.$buttonunapprov.'
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
			$approve=''; 
			$active = "<label class='label label-danger'>Pending</label>";
			
			if($currentYear >= $yr && $norows_pbb >  0 && $norows_quarterly_targets >  0){
				$approve .='
				<li>
					<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
					<i class="fa fa-check-square-o"></i> Approve Project
			 		</a>
			 	</li>';
			} 
			 
			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
				'.$approve.
				'
					<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>
					<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove From ADP</a></li>       
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
			//echo		'<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit Project</a></li>';

} // if num_rows

echo json_encode($output);
