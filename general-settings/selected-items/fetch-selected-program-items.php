<?php
include_once "controller.php";

if (isset($_GET["sp"]) && !empty($_GET["sp"])) {
	$spid = $_GET["sp"];
	$sql = $db->prepare("SELECT * FROM `tbl_programs` WHERE strategic_plan = '$spid' ORDER BY `syear`,`progid` ASC");
	$sql->execute();
} else {
	$sql = $db->prepare("SELECT * FROM `tbl_programs` ORDER BY `syear`,`progid` ASC");
	$sql->execute();
}

$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	$sn = 0;
	while ($row_rsProgram = $sql->fetch()) {
		$itemId = $row_rsProgram['progid'];
		$spid = $row_rsProgram['strategic_plan'];

		$project_department = $row_rsProgram['projsector'];
		$project_section = $row_rsProgram['projdept'];
		$project_directorate = $row_rsProgram['directorate'];
		$created_by = $row_rsProgram['createdby'];

		$edit = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit1);
		$edit =   ($edit) ?  $permissions->verify_created_by($created_by) : false;
		$delete = $permissions->verify_action($project_department, $project_section, $project_directorate, $delete1);
		$add_quarterly_targets = $permissions->verify_action($project_department, $project_section, $project_directorate, $add_quarterly_targets1);
		$edit_quarterly_targets = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit_quarterly_targets1);
		$add_project = $permissions->verify_action($project_department, $project_section, $project_directorate, $add_project1);
		$editurl = 'edit-program?progid=' . $itemId;

		$query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$itemId'");
		$query_projs->execute();
		$totalRows_projs = $query_projs->rowCount();

		if ($spid == NULL) {
			$progttype = 'Independent';
		} else {
			$progttype = 'Strategic Plan';
		}

		$button = '<!-- Single button --> 
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li>
					<a type="button" data-toggle="modal" data-target="#moreInfoModal" id="moreInfoModalBtn" onclick="more(' . $itemId . ')">
					<i class="glyphicon glyphicon-file"></i> More Info</a>
				</li>';

		if ($add_project) {
			$button .= '<li><a type="button" id="addproject"  href="add-project?progid=' . $itemId . '" > <i class="fa fa-plus-square"></i> Add Project</a></li>';
		}

		if ($totalRows_projs == 0) {
			if ($edit) {
				$button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="' . $editurl . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li> ';
			}

			if ($delete) {
				$button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>';
			}
		}
		$button .= '</ul>
        </div>';

		$progname =  $row_rsProgram['progname'];
		$projduration = $row_rsProgram['years'] . " Years";
		$projsyear = $row_rsProgram['syear'];

		//get financial years 
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr='$projsyear'");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$totalRows_rsYear = $query_rsYear->rowCount();
		$projsyear = $row_rsYear['year'];

		//fetch budget
		$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid='$itemId'");
		$query_rsBudget->execute();
		$row_rsBudget = $query_rsBudget->fetch();
		$totalRows_rsBudget = $query_rsBudget->rowCount();
		$progbudget = number_format($row_rsBudget['budget'], 2);

		//get project department 
		$progdepart = $row_rsProgram['projdept'];
		$query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid='$progdepart' ");
		$query_rsDepart->execute();
		$row_rsDepart = $query_rsDepart->fetch();
		$dept = $row_rsDepart['sector'];

		//get total projects
		$query_projsbudget = $db->prepare("SELECT COUNT(*) as projectscount, SUM(projcost) as budget FROM tbl_projects WHERE progid = '$itemId'");
		$query_projsbudget->execute();
		$row_projsbudget = $query_projsbudget->fetch();
		$count_projsbudget = $query_projsbudget->rowCount();

		$projsbudget = ($count_projsbudget > 0) ? $row_projsbudget['budget'] : 0;
		$projectscount = ($count_projsbudget > 0) ? $row_projsbudget['projectscount'] : 0;
		$progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);

		//get total projects
		$query_projs = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$itemId'");
		$query_projs->execute();
		$count_projs = $query_projs->rowCount();

		$projectscount = "";
		if ($count_projs > 0) {
			$projectscount = '<a href="view-project?prg=' . base64_encode($itemId) . '"><span class="badge bg-purple">' . $count_projs . '</span></a>';
		} else {
			$projectscount = '<a href="#"><span class="badge bg-purple">' . $count_projs . '</span></a>';
		}

		$filter_department = $permissions->open_permission_filter($project_department, $project_section, $project_directorate);

		if ($filter_department) {
			$sn++;
			$output['data'][] = array(
				$sn,
				$progname,
				$progbudget,
				$progbudgetbal,
				$projectscount,
				$projsyear,
				$projduration,
				$button
			);
		}
	} // /while 

} // if num_rows

echo json_encode($output);
