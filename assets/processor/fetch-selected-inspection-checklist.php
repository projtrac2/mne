<?php
include_once "controller.php";

try{
	$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstage > 6  ORDER BY `projid` ASC");
	$sql->execute();
	$rows_count = $sql->rowCount();

	$output = array('data' => array());

	if ($rows_count > 0) {
		$active = "";
		$sn = 0;
		while ($row = $sql->fetch()) {
			$sn++;
			$itemId = $row['projid'];	
			$approved_date = $row['approved_date'];
			$projstage = $row['projstage'];
			
			$query_rsOutcome =  $db->prepare("SELECT * FROM tbl_workplan_targets WHERE projid ='$itemId'");
			$query_rsOutcome->execute();
			$row_rsOutcome = $query_rsOutcome->fetch();
			$totalRows_rsOutcome = $query_rsOutcome->rowCount();
			
			$query_rsinspection_projects =  $db->prepare("SELECT * FROM tbl_projects WHERE projid ='$itemId'");
			$query_rsinspection_projects->execute();
			$row_rsinspection_projects = $query_rsinspection_projects->fetch();
			$totalRows_rsinspection_projects = $query_rsinspection_projects->rowCount();
			$projinspection  = $row_rsinspection_projects['projinspection'];

			 
			$query_rsTask =  $db->prepare("SELECT * FROM tbl_task WHERE projid ='$itemId'");
			$query_rsTask->execute();
			$row_rsTask = $query_rsTask->fetch();
			$totalRows_rsTask = $query_rsTask->rowCount();
			$handler =array();
			if($totalRows_rsTask > 0){
			   do{
					$taskid = $row_rsTask['tkid'];
					$query_rsChecklist = $db->prepare("SELECT * FROM tbl_project_inspection_checklist WHERE taskid='$taskid'");
					$query_rsChecklist->execute();
					$row_rsChecklist = $query_rsChecklist->fetch();
					$totalRows_rsChecklist = $query_rsChecklist->rowCount();

					if($totalRows_rsChecklist > 0){
						array_push($handler, true);
					}else{
						array_push($handler, false);
					}
			   } while($row_rsTask = $query_rsTask->fetch());
			}else{
				array_push($handler, false);
			}

			$timeline = $db->prepare("SELECT * FROM `tbl_project_workflow_stage_timelines` WHERE category='mne' LIMIT 1");
			$timeline->execute();
			$rowtimeline = $timeline->fetch();
			$timeline = $rowtimeline['time'];
			$units = $rowtimeline['units'];

			$duedate = date('Y-m-d', strtotime($approved_date  . ' + ' . $timeline . ' ' . $units));
			$today = date('Y-m-d');
			$datedue = date('d M Y', strtotime($duedate));

			if ($today > $duedate) {
				if(!in_array(false,$handler)){
					$statusColor = "success";
					$statusName = "Planned";
					$active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
				} else {
					$statusColor = "danger";
					$statusName = "Behind Schdule";
					$active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
				}
			} else {
				if(!in_array(false,$handler)){
					$statusColor = "success";
					$statusName = "Planned";
					$active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
				} else {
					$statusColor = "primary";
					$statusName = "Pending";
					$active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
				}
			}

			$checklist ='';
			if($projstage == 7){
				$checklist =
				'<li>
					<a type="button" data-toggle="modal" id="editmoneval"  href="add-inspection-checklist?projid=' . $itemId . '">
						<i class="fa fa-plus"></i> Add  Checklist
					</a>
				</li>';
			}

			if(!in_array(false,$handler)){
				if($projstage < 11){
					$checklist = '
					<li>
						<a type="button" data-toggle="modal" id="editmoneval"  href="add-inspection-checklist?projid=' . $itemId . '">
							<i class="fa fa-edit"></i> Edit Checklist
						</a>
					</li>';
				}
			}

			$button = '<!-- Single button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li>
						<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="more(' . $itemId . ')">
						<i class="fa fa-info"></i> Proj Info 
						</a>
					</li>
					'.$checklist.' 
				</ul> 
			</div>';

			$projname = $row["projname"];
			$progid = $row["progid"];
			$projplanStatus = $row['projplanstatus'];
			$projstage = $row['projstage'];

			$stage = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE id=:id LIMIT 1");
			$stage->execute(array(":id" => $projstage));
			$rowstage = $stage->fetch();
			$stage = $rowstage['stage'];

			//get program and department 
			$prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
			$prog->execute(array(":progid" => $progid));
			$rowprog = $prog->fetch();

			$prgtype = $rowprog["program_type"];
			$projdept = $rowprog['projdept'];
			$progname = $rowprog["progname"];
			$projtype = '';
			
			if ($prgtype == 1) {
				$projtype = "Independent";
			} else {
				$projtype = "Strategic Plan";
			}

			// get department 
			$query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
			$query_rsDept->execute(array(":sector" => $projdept));
			$row_rsDept = $query_rsDept->fetch();
			$department = $row_rsDept['sector'];
			$totalRows_rsDept = $query_rsDept->rowCount();

			$substatus = "Sub";
			$progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' .  $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';
			$output['data'][] = array(
				$sn,
				$projname,
				$progname,
				$projtype, 
				$active,
				$datedue,
				$button
			);
		} // /while 

	} // if num_rows
	echo json_encode($output);

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}