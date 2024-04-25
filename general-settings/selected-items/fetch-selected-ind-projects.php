<?php

include_once "controller.php";

if (isset($_GET["type"]) && $_GET["type"] == 1) {
	$sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE g.program_type=0 AND p.deleted='0' ORDER BY `projfscyear` DESC");
	$sql->execute();
}

$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	$sn = 0;
	while ($row_project = $sql->fetch()) {
		
		$itemId = $row_project['projid'];
		$projname = $row_project['projname'];
		$projcost = number_format($row_project['projcost'], 2);
		$projstatusid = $row_project['projstatus'];
		$projstatus = $row_project['statusname'];
		$projfscyear = $row_project['year'];
		$strategic_plan = $row_project['strategic_plan'];
		$projduration = $row_project['projduration'];
		$program_type = $row_project['program_type'];
		$approved = $row_project['projplanstatus'];
		$username = $row_project['user_name'];
		$projstage = $row_project['projstage'];


		$project_department = $row_project['projsector'];
		$project_section = $row_project['projdept'];
		$project_directorate = $row_project['directorate'];

		// edit delete approve_project unapprove_project
		$edit = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit1);
		$edit =   ($edit) ?  $permissions->verify_created_by($username) : false;

		//fetch budget
		$query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$projfscyear'");
		$query_projs->execute();
		$totalRows_projs = $query_projs->rowCount();

		$sp_link = "No";
		if ($program_type == 0 && $strategic_plan == 1) {
			$sp_link = 'Yes';
		}

		$projstatus = "<label class='label label-danger'>Pending</div>";
		$action = "";
		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>  ';
		if ($approved == 1) {
			$projstatus = "<label class='label label-success'>Approved</div>";
			$action = '';
			if ($unapprove && $projstage == 2) {
				$button .= '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
			}
		} else {
			$query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
			$query_rsOutput->execute(array(":projid"=>$itemId)); 
			$totalRows_rsOutput = $query_rsOutput->rowCount();

			if($totalRows_rsOutput > 0){
				if ($approve) {
					$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . base64_encode($itemId) . '"> <i class="glyphicon glyphicon-plus"></i>Edit Outputs</a></li>';
					$button .= '<li><a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approveItem(' . $itemId . ')"> <i class="fa fa-check-square-o"></i> Approve Project </a></li>';
				}	
			}else{
				if ($edit) {
					$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . base64_encode($itemId) . '"> <i class="glyphicon glyphicon-plus"></i>Add Outputs</a></li>';
					$button .= '<li><a type="button" href="add-project.php?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
				}
	 
				if ($delete) {
					$button .= '<li><a type="button" data-toggle="modal" data-target="#removeProjModal" id="removeItemModalBtn" onclick="removeProj(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>';
				}
			} 
		}

		$button .= '
			</ul>
        </div>';

		$filter_department = $permissions->open_permission_filter($project_department, $project_section, $project_directorate);

		if ($filter_department) {
			$sn++;
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
		}
	} // /while 
} // if num_rows

echo json_encode($output);
