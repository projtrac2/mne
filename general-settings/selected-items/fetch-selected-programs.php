<?php
include_once "controller.php";

if (isset($_GET["type"]) && $_GET["type"] == 1) {
	$sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid WHERE g.program_type=0 ORDER BY `projfscyear` ASC");
	$sql->execute();
} else {
	$sql = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=0 ORDER BY `syear` ASC");
	$sql->execute(); 
}

$rows_count = $sql->rowCount();
$output = array('data' => array());
if ($rows_count > 0) {
	$sn = 0;
	while ($row_rsProgram = $sql->fetch()) {
		
		$itemId = $row_rsProgram['progid'];
		$program_type = $row_rsProgram['program_type'];
		$strategic_plan = $row_rsProgram['strategic_plan'];
		$created_by = $row_rsProgram['createdby'];
		$editurl = 'edit-program?progid=' . $itemId;

		// edit delete  edit_quarterly_targets add_project add_quarterly_targets
		$project_department = $row_rsProgram['projsector'];
		$project_section = $row_rsProgram['projdept'];
		$project_directorate = $row_rsProgram['directorate'];

		$edit = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit1);
		$edit =   ($edit) ?  $permissions->verify_created_by($created_by) : false;
		$delete = $permissions->verify_action($project_department, $project_section, $project_directorate, $delete1);
		$add_quarterly_targets = $permissions->verify_action($project_department, $project_section, $project_directorate, $add_quarterly_targets1);
		$edit_quarterly_targets = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit_quarterly_targets1);
		$add_project = $permissions->verify_action($project_department, $project_section, $project_directorate, $add_project1);
		$editurl = 'edit-program?progid=' . $itemId;

		$yr = date("Y");
		$mnth = date("m");
		$startmnth = 07;
		$endmnth = 06;

		if ($mnth >= 7 && $mnth <= 12) {
			$fnyear = $yr;
		} elseif ($mnth >= 1 && $mnth <= 6) {
			$fnyear = $yr - 1;
		}

		//fetch budget
		$query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$itemId'");
		$query_projs->execute();
		$totalRows_projs = $query_projs->rowCount();
		$sp_link = "No";
		if ($program_type != 1 && $strategic_plan != 0) {
			$sp_link = 'Yes';
		}

		// get program quarterly targets 
		$query_pbbtargets =  $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid = :progid and year = :fnyear");
		$query_pbbtargets->execute(array(":progid" => $itemId, ":fnyear" => $fnyear));
		$norows_pbbtargets = $query_pbbtargets->rowCount();


		$button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" data-target="#progmoreInfoModal" id="progmoreInfoModalBtn" onclick="progmoreInfo(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>';

		if ($totalRows_projs == 0) {
			if ($edit) {
				$button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="' . $editurl . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li> ';
			}

			if ($delete) {
				$button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeProg(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>';
			}
		}

		if ($norows_pbbtargets > 0) {
			$button .= '<li><a type="button" data-toggle="modal" id="viewQTargetsModalBtns" data-target="#viewQTargetsModal" onclick="viewPBB(' . $itemId . ', ' . $fnyear . ')"> <i class="fa fa-eye"></i> View Quarterly Targets</a></li>';
			$query_projects_count = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$itemId' AND projstage > 7");
			$query_projects_count->execute();
			$count_projects_count = $query_projects_count->rowCount();
			if ($edit_quarterly_targets && $count_projects_count == 0) {
				$button .= ' 
					<li>
						<a type="button" data-toggle="modal" id="editquarterlyTargetsModalBtn" data-target="#editquarterlyTargetsModal" onclick="editQuarterlytargets(' . $itemId . ', ' . $fnyear . ')">
							<i class="glyphicon glyphicon-edit"></i> Edit Quarterly Targets
						</a>
					</li>';
			}
		} else {
			if ($add_quarterly_targets) {
				$button .= '
				<li>
					<a type="button" data-toggle="modal" id="quarterlyTargetsModalBtn" data-target="#quarterlyTargetsModal" onclick="addQuarterlytargets(' . $itemId . ', ' . $fnyear . ')">
						<i class="fa fa-plus-square-o"></i> Add Quarterly Targets
					</a>
				</li>';
			}
		}

		if ($add_project) {
			$button .= '<li><a type="button" id="addproject"  href="add-project?progid=' . $itemId . '" > <i class="fa fa-plus-square"></i> Add Project</a></li>';
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
		$query_projs = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$itemId'");
		$query_projs->execute();
		$count_projs = $query_projs->rowCount();

		if ($count_projs > 0) {
			$projectscount = '<a href="view-project?prg=' . $itemId . '"><span class="badge bg-purple">' . $count_projs . '</span></a>';
		} else {
			$projectscount = '<a href="#"><span class="badge bg-purple">' . $count_projs . '</span></a>';
		}


		//get total projects
		$query_projsbudget = $db->prepare("SELECT SUM(projcost) as budget FROM tbl_projects WHERE progid = '$itemId'");
		$query_projsbudget->execute();
		$row_projsbudget = $query_projsbudget->fetch();
		$count_projsbudget = $query_projsbudget->rowCount();

		if ($count_projsbudget > 0) {
			$projsbudget = $row_projsbudget['budget'];
		} else {
			$projsbudget = 0;
		}

		$progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);
		$link = ($program_type == 0) ? $sp_link : $button;


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
				$link,
				$button,
			);
		}
	} // /while 

} // if num_rows

echo json_encode($output);
