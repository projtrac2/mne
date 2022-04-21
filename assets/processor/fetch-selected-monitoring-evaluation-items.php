<?php

include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstage>7 ORDER BY `projid` ASC");
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
        $approved_date = $row['approved_date'];

        $query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid");
        $query_rsDetails->execute(array(":projid" => $itemId));
        $row_rsRisk = $query_rsDetails->fetch();
        $totalRows_rsDetails = $query_rsDetails->rowCount();

        $query_rsOutcome =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid ='$itemId'");
        $query_rsOutcome->execute();
        $row_rsOutcome = $query_rsOutcome->fetch();
        $totalRows_rsOutcome = $query_rsOutcome->rowCount();

        $query_rsTask =  $db->prepare("SELECT * FROM tbl_task WHERE projid ='$itemId'");
        $query_rsTask->execute();
        $row_rsTask = $query_rsTask->fetch();
        $totalRows_rsTask = $query_rsTask->rowCount();

        $handler = array();

        if ($totalRows_rsTask > 0) {
            do {
                $taskid = $row_rsTask['tkid'];
                $query_rsChecklist = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE taskid='$taskid'");
                $query_rsChecklist->execute();
                $row_rsChecklist = $query_rsChecklist->fetch();
                $totalRows_rsChecklist = $query_rsChecklist->rowCount();

                if ($totalRows_rsChecklist > 0) {
                    array_push($handler, true);
                } else {
                    array_push($handler, false);
                }
            } while ($row_rsTask = $query_rsTask->fetch());
        } else {
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
            if (($totalRows_rsOutcome > 0 || $totalRows_rsDetails > 0) && !in_array(false, $handler)) {
                $statusColor = "success";
                $statusName = "Planned";
                $active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
            } else {
                $statusColor = "danger";
                $statusName = "Behind Schdule";
                $active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
            }
        } else {
            if (($totalRows_rsOutcome > 0 || $totalRows_rsDetails > 0) && !in_array(false, $handler)) {
                $statusColor = "success";
                $statusName = "Planned";
                $active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
            } else {
                $statusColor = "primary";
                $statusName = "Pending";
                $active = '<label class="label label-' . $statusColor . '">' . $statusName . '</label>';
            }
        }

        $remove = '';
        if ($row['projstage'] == 7 && $role_group == 1) {
            $remove = '
            <li>
                <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')">
                    <i class="glyphicon glyphicon-trash"></i> Remove
                </a>
            </li>';
        }

        $query_rsChecklist1 = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid='$taskid'");
        $query_rsChecklist1->execute();
        $row_rsChecklist1 = $query_rsChecklist1->fetch();
        $totalRows_rsChecklist1 = $query_rsChecklist1->rowCount();

        $view_checklist = '';

        if ($totalRows_rsChecklist1) {
            $view_checklist = '           
            <li>
                <a type="button" data-toggle="modal" id="checklistItemBtn" data-target="#checklistModal" onclick="viewchecklist(' . $itemId . ')">
                <i class="glyphicon glyphicon-list"></i> View Checklist 
                </a>
            </li>';
        }

        $mneedit = '';
        $checklist = '';

        if ($totalRows_rsOutcome > 0 || $totalRows_rsDetails > 0) {

            if ($row['projstage'] < 10 && $role_group == 1) {
                $checklist =
                    '<li>
                        <a type="button" data-toggle="modal" id="editmoneval"  href="add-monitoring-checklist?projid=' . $itemId . '">
                            <i class="fa fa-plus-square"></i> Add Checklist
                        </a>
                    </li>';

                if (!in_array(false, $handler)) {
                    $checklist = '
                    <li>
                        <a type="button" data-toggle="modal" id="editmoneval"  href="add-monitoring-checklist?projid=' . $itemId . '">
                            <i class="fa fa-pencil-square"></i> Edit Checklist
                        </a>
                    </li>';
                }
            }

            if ($totalRows_rsbaseline == 0 && $role_group == 1) {
                $mneedit = '
                    <li>
                        <a type="button" data-toggle="modal" id="editmoneval"  href="edit-monitoring-evaluation-plan?projid=' . $itemId . '">
                            <i class="fa fa-pencil-square"></i> Edit M&E
                        </a> 
                    </li>';
            }
            $button = '<!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="more(' . $itemId . ')">
                        <i class="fa fa-info"></i> Proj More Info 
                        </a>
                    </li>
                    <li>
                        <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="moreme(' . $itemId . ')">
                            <i class="fa fa-info"></i> M&E More Info
                        </a>
                    </li>
                    ' . $mneedit . '
                    ' . $remove . '       
                    ' . $checklist . '
                    ' . $view_checklist . '
                </ul> 
            </div>';
        } else {
            $button = '<!-- Single button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
                <li>
                    <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="more(' . $itemId . ')"> 
                        <i class="fa fa-info"></i> Proj More Info  
                    </a>
                </li>';
            if ($role_group == 1) {
                $button .=
                    '<li>
                    <a type="button"  id="addmoneval"  href="add-project-mne-plan?proj=' . $itemId . '">
                        <i class="fa fa-plus-square"></i> Add M&E Plan
                    </a>
                </li>';
            }
            $button = '
            </ul> 
        </div>';
        }

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

        $kpi = $rowprog["kpi"];
        $projdept = $rowprog['projdept'];
        $progname = $rowprog["progname"];
        $projtype = '';
        if ($kpi == null) {
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
        $progname = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $rowprog["progname"] . '</span>';
        $output['data'][] = array(
            $sn,
            $projname,
            $progname,
            $active,
            $datedue,
            $button
        );
    } // /while 

} // if num_rows
echo json_encode($output);
