<?php

include_once "controller.php";

try{
	//$currentmonth = date("m");
	$currentyr = date("Y");
	/* if($currentmonth < 7){
		$currentyr = $currentyr - 1;
	} */
		
	$nextyr = $currentyr + 1;
	$currentfy = $currentyr."/".$nextyr;
	$plan= $_GET["sp"];

	if($plan){
		if(isset($_GET["prg"]) && !empty($_GET["prg"])){
			$prgid = $_GET["prg"];

			$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid inner join tbl_sectors s on s.stid=g.projsector WHERE g.strategic_plan=:sp and p.progid = :prgid ORDER BY `projplanstatus`, `projfscyear` ASC");
			$sql->execute(array(":sp" =>$plan, ":prgid" => $prgid));
		}else{
			$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid inner join tbl_sectors s on s.stid=g.projsector WHERE g.strategic_plan=:sp ORDER BY `projplanstatus`, `projfscyear` ASC");
			$sql->execute(array(":sp" =>$plan));
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
				
				$query_adp =  $db->prepare("SELECT *, p.status as status FROM tbl_annual_dev_plan p inner join tbl_fiscal_year y ON y.id=p.financial_year WHERE projid = :itemId");
				$query_adp->execute(array(":itemId" => $itemId));
				$row_adp = $query_adp->fetch();
				$totalRows_adp = $query_adp->rowCount();

				$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid = :itemId");
				$query_rsBudget->execute(array(":itemId" => $itemId));
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
				
				$progname = $rowprog["progname"];
				$sector = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="'.$department.'" style="color:#2196F3">'.$row["sector"].'</span>';
				
				// status
				if ($totalRows_adp == 1) {
					$adpstatus = $row_adp["status"];
					$status = $row_adp["year"]." ADP";
					if($adpstatus ==1){
						$active = '<label class="label label-success" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Approved" >'.$status.'</label>';
					} else {
						$active = '<label class="label label-primary" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending Approval" >'.$status.'</label>';
					}
					
					$button = '<!-- Single button -->
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>							
						</ul> 
					</div>';
				} else {
					$status = "Pending ADP";
					/* $currentYear = '';
					$month =  date('m');
					if ($month  < 7) {
						$currentYear =  date("Y") - 1;
					} else {
						$currentYear =  date("Y");
					} */
					$approve=''; 
					$active = '<label class="label label-warning" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending ADP">'.$status.'</label>';
					//$active = "<label class='label label-danger'>".$status."</label>";
					
					$approve .='
					<li>
						<a type="button" href="strategic-plan-projects?adp=1&proj='.$itemId.'" onclick="return confirm(\'Are you sure you want to add this project to ' . $currentfy . ' ADP?\')">
							<i class="glyphicon glyphicon-plus"></i> Add to ADP
						</a>
					</li>';
					 
					$button = '<!-- Single button -->
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
						'.$approve.
						'
							<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>     
						</ul>
					</div>';
				}
				$active .= '<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>';
				
				$output['data'][] = array(
					$sn,
					$projname,
					$progname,
					$sector,
					$budget,
					$projYear,
					$active,
					$button
				);
			} // /while 

		} // if num_rows

		echo json_encode($output);
	}
	
	$valid['success'] = array('success' => false, 'messages' => array());
	if(isset($_POST["removeadp"])){
		$projid = $_POST["itemId"];
		
		$deleteQuery = $db->prepare("DELETE FROM `tbl_annual_dev_plan` WHERE projid=:projid");
		$results = $deleteQuery->execute(array(':projid' => $projid));

		if ($results  === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Project successfully removed from ADP";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while removing the project from ADP!!";
		}
		//var_dump("Valid: ".$valid);
		echo json_encode($valid);
	}

} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	print($ex->getMessage());
}