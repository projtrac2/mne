<?php
include_once "controller.php";
try {
	$currentyr = date("Y");

	$nextyr = $currentyr + 1;
	$currentfy = $currentyr . "/" . $nextyr;
	$plan = isset($_GET['sp']) ? $_GET["sp"] : "";
	if ($plan) {
		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$prgid = $_GET["prg"];
			$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid WHERE g.strategic_plan=:sp and p.progid = :prgid ORDER BY `projplanstatus`, `projfscyear` ASC");
			$sql->execute(array(":sp" => $plan, ":prgid" => $prgid));
		} else {
			$sql = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g ON g.progid=p.progid WHERE g.strategic_plan=:sp ORDER BY `projplanstatus`, `projfscyear` ASC");
			$sql->execute(array(":sp" => $plan));
		}



		$rows_count = $sql->rowCount();
		$output = array('data' => array());
		if ($rows_count > 0) {
			$active = "";
			$sn = 0; 
			$plan = base64_encode("strplan1{$plan}");
			while ($row = $sql->fetch()) {
				$itemId = $row['projid'];
				$stid = $row['projsector'];
				$program_type = $row['program_type'];
				$username = $row['user_name'];


				// add_to_adp remove_adp edit delete 
				$project_department = $row['projsector'];
				$project_section = $row['projdept'];
				$project_directorate = $row['directorate'];

				// edit delete approve_project unapprove_project
				$edit = $permissions->verify_action($project_department, $project_section, $project_directorate, $edit1);
				$edit =   ($edit) ?  $permissions->verify_created_by($username) : false;
				// $delete = $permissions->verify_action($project_department, $project_section, $project_directorate, $delete1);
				// $add_to_adp = $permissions->verify_action($project_department, $project_section, $project_directorate, $add_to_adp1);
				// $remove_adp = $permissions->verify_action($project_department, $project_section, $project_directorate, $remove_adp1);

				$query_adp =  $db->prepare("SELECT *, p.status as status FROM tbl_annual_dev_plan p inner join tbl_fiscal_year y ON y.id=p.financial_year WHERE projid = :itemId");
				$query_adp->execute(array(":itemId" => $itemId));
				$row_adp = $query_adp->fetch();
				$totalRows_adp = $query_adp->rowCount();
				$adpstatus = $totalRows_adp > 0 ? $row_adp["status"] : "";


				$query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid = :itemId");
				$query_rsBudget->execute(array(":itemId" => $itemId));
				$row_rsBudget = $query_rsBudget->fetch();
				$totalRows_rsBudget = $query_rsBudget->rowCount();
				$projbudget = $totalRows_rsBudget > 0 ? $row_rsBudget['budget'] : 0;

				$query_sector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :stid");
				$query_sector->execute(array(":stid" => $stid));
				$row_sector = $query_sector->fetch();

				$projname = $row["projname"];
				$username = $row["user_name"];
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
				$sector = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $department . '" style="color:#2196F3">' . $row_sector["sector"] . '</span>';

				// status
				if ($totalRows_adp == 1) {
					$status = $row_adp["year"] . " ADP";
					if ($adpstatus == 1) {
						$active = '<label class="label label-success" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Approved" >' . $status . '</label>';
					} else {
						$active = '<label class="label label-primary" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending Approval" >' . $status . '</label>';
					}

					$button = '<!-- Single button -->
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>';
					if ($remove_adp && $adpstatus == 0) {
						$button .= '<li><a type="button" data-toggle="modal" id="approveItemModalBtns" data-target="#approveItemModals" onclick="Undo(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Remove from ADP</a></li>';
					}
					$button .= '							
						</ul> 
					</div>';
				} else {
					$status = "Pending ADP";
					$active = '<label class="label label-warning" data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="Pending ADP">' . $status . '</label>';
					$button = '<!-- Single button -->
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">';
					if ($adpstatus == 0) {

						$query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
						$query_rsOutput->execute(array(":projid" => $itemId));
						$totalRows_rsOutput = $query_rsOutput->rowCount();
						if ($totalRows_rsOutput > 0) {
							if ($add_to_adp) {
								$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . base64_encode($itemId) . '"> <i class="glyphicon glyphicon-plus"></i>Edit Outputs</a></li>';
								$button .= '
									<li>
										<a type="button" href="strategic-plan-projects?plan=' . $plan . '&adp=1&proj=' . $itemId . '" onclick="return confirm(\'Are you sure you want to add this project to ' . $currentfy . ' ADP?\')">
											<i class="glyphicon glyphicon-plus"></i> Add to ADP
										</a>
									</li>
									';
							}
						} else {
							if ($edit) {
								$button .= '<li><a type="button" href="add-project-outputs.php?projid=' . base64_encode($itemId) . '"> <i class="glyphicon glyphicon-plus"></i>Add Outputs</a></li>';
								$button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="add-project?projid=' . $itemId . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
							}

							if ($delete) {
								$button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>     ';
							}
						}
					}

					$button .= '
							<li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $itemId . ')"> <i class="glyphicon glyphicon-file"></i> More</a></li>         
						</ul>
					</div>';
				}
				$active .= '<script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>';
				$progbudgetbal = number_format(($row_rsBudget['budget'] - $projbudget), 2);
				// $link = ($program_type == 0) ? $sp_link : $button;
				$filter_department = $permissions->open_permission_filter($project_department, $project_section, $project_directorate);

				if ($filter_department) {
					$sn++;

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
				}
			} // /while 

		} // if num_rows

		echo json_encode($output);
	}

	$valid['success'] = array('success' => false, 'messages' => array());
	if (isset($_POST["removeadp"])) {
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
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	print($ex->getMessage());
}
