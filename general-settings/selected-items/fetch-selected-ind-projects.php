<?php

include_once "controller.php";

if (isset($_GET["type"]) && $_GET["type"] == 1) {
	$sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE g.program_type=0 AND p.deleted='0' ORDER BY `projfscyear` ASC");
	$sql->execute();
}

$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	$sn = 0;
	while ($row_project = $sql->fetch()) {
		$sn++;
		$itemId = $row_project['projid'];
		$projname = $row_project['projname'];
		$projcost = number_format($row_project['projcost'],2);
		$projstatusid = $row_project['projstatus'];
		$projstatus = $row_project['statusname'];
		$projfscyear = $row_project['year'];
		$strategic_plan = $row_project['strategic_plan'];
		$projduration = $row_project['projduration'];
		$program_type = $row_project['program_type'];
		$approved = $row_project['projplanstatus'];
		//$editurl = 'edit-project?projid=' . $itemId;

		//fetch budget
		$query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$projfscyear'");
		$query_projs->execute();
		$totalRows_projs = $query_projs->rowCount();
		
		$sp_link = "No";
		if ($program_type == 0 && $strategic_plan == 1) {
			$sp_link = 'Yes';
		}
		
		$projstatus = "<label class='label label-danger'>Pending</div>";
	
		$action = '
		<li>
			<a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')">
			<i class="fa fa-check-square-o"></i> Approve Project
			</a>
		</li> 
		<li><a type="button" href="edit-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a>
		</li>      
		<li><a type="button" data-toggle="modal" data-target="#removeProjModal" id="removeItemModalBtn" onclick="removeProj(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a>
		</li>
		';
		if($approved==1){
			$projstatus = "<label class='label label-success'>Approved</div>";
			$action = '';
		}

		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>  
				</li>
				'.$action.'
			</ul>
        </div>';

		//get project department 
		$progdepart = $row_rsProgram['projdept'];
		$query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid='$progdepart' ");
		$query_rsDepart->execute();
		$row_rsDepart = $query_rsDepart->fetch();
		$dept = $row_rsDepart['sector'];

		$output['data'][] = array(
			$sn,
			$projname,
			$projcost,
			$projfscyear,
			$projduration,
			$sp_link,
			$projstatus,
			$button,
		);
	} // /while 

} // if num_rows

echo json_encode($output);
