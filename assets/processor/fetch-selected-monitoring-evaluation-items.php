<?php
include_once "controller.php";

$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstage=4 ORDER BY `projid` ASC");
$sql->execute();
$rows_count = $sql->rowCount();
$output = array('data' => array());

if ($rows_count > 0) {
    $sn = 0;
    while ($row = $sql->fetch()) {
        $sn++;
        $projid = $row['projid'];
        $approved_date = $row['approved_date'];
        $projname = $row["projname"];
        $progid = $row["progid"];
        $projplanStatus = $row['projplanstatus'];
        $projstage = $row['projstage'];
        $evaluation = $row['projevaluation'];
        $mapping = $row['projmapping'];
        $mne_budget = $row['mne_budget'];
        $progname = get_program($progid);
        $datedue = get_due_date($approved_date);
        $status = get_project_status($datedue, $projstage);
        $button = get_button($projid, $evaluation, $mapping, $mne_budget, $projstage);

        $projname = $projname;

        $output['data'][] = array(
            $sn,
            $projname,
            $progname,
            $status,
            $datedue,
            $button
        );
    } // /while 
} // if num_rows


echo json_encode($output);


function get_program($progid)
{
    global $db;
    $prog = $db->prepare("SELECT * FROM `tbl_programs` WHERE progid=:progid LIMIT 1");
    $prog->execute(array(":progid" => $progid));
    $rowprog = $prog->fetch();
    $total_programs = $prog->rowCount();

    $program_name = "";
    if ($total_programs > 0) {
        $projdept = $rowprog["projdept"];
        $progname = $rowprog["progname"];
        $query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
        $query_rsDept->execute(array(":sector" => $projdept));
        $row_rsDept = $query_rsDept->fetch();
        $totalRows_rsDept = $query_rsDept->rowCount();
        $department = $totalRows_rsDept > 0 ? $row_rsDept['sector'] : "";
        $program_name = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $progname . '</span>';
    }

    return $program_name;
}

function get_due_date($approved_date)
{
    global $db;
    $timeline = $db->prepare("SELECT * FROM `tbl_project_workflow_stage_timelines` WHERE category='mne' LIMIT 1");
    $timeline->execute();
    $rowtimeline = $timeline->fetch();
    $duedate = "";
    if ($rowtimeline) {
        $timeline = $rowtimeline['time'];
        $units = $rowtimeline['units'];

        $duedate = date('Y-m-d', strtotime($approved_date  . ' + ' . $timeline . ' ' . $units));
        $duedate =  date('d M Y', strtotime($duedate));
    }
    return $duedate;
}


function get_project_status($duedate, $stage)
{
    $today = date('d-m-Y');
    $duedate =  date('d-m-Y', strtotime($duedate));
    $status = "";
    if ($stage > 7) {
        $status = '<label class="label label-success">Planned</label>';
    } else {
        $status = '<label class="label label-primary">Pending</label>';
        if ($today > $duedate) {
            $status = '<label class="label label-danger">Behind Schdule</label>';
        }
    }
    return $status;
}


function get_button($projid, $evaluation, $mapping, $mne_budget, $project_stage)
{
    global $add, $edit, $add_checklist;

    $budget_details = mne_budget($projid, $evaluation, $mapping, $mne_budget);
    $monitoring_details = monitoring($projid);
    $evaluation_details = evaluation($projid, $evaluation);
    $monitoring_checklist_details = monitoring_checklist($projid);


    $planned_buttons = "";
	$proj = $projid;
	$projid = base64_encode("projid04{$projid}");
	
    if (($budget_details && $monitoring_details && $evaluation_details)) {
        if ($project_stage == 4) {
            if ($edit && !$monitoring_checklist_details) {
                $planned_buttons .= '
                <li>
                    <a type="button"  id="addmoneval"  href="add-project-mne-plan?proj=' . $projid . '">
                        <i class="fa fa-plus-square"></i> Edit M&E Plan
                    </a>
                </li>';
            }

            if ($add_checklist) {
                if ($monitoring_checklist_details) {
                    $planned_buttons .= '
                    <li>
                        <a type="button" data-toggle="modal" id="editmoneval"  href="add-monitoring-checklist?projid=' . $projid . '">
                            <i class="fa fa-pencil-square"></i> Edit Checklist
                        </a>
                    </li>';
                } else {
                    $planned_buttons .= '
                    <li>
                        <a type="button" data-toggle="modal" id="editmoneval"  href="add-monitoring-checklist?projid=' . $projid . '">
                            <i class="fa fa-plus-square"></i> Add Checklist
                        </a>
                    </li>';
                }
            }
        }

        if ($project_stage > 4) {
            $planned_buttons .= '
            <li>
                <a type="button" data-toggle="modal" id="checklistItemBtn" data-target="#checklistModal" onclick="viewchecklist(' . $proj . ')">
                <i class="glyphicon glyphicon-list"></i> View Checklist 
                </a>
            </li>';
        }

        $planned_buttons .= '
        <li>
            <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="moreme(' . $proj . ')">
                <i class="fa fa-info"></i> M&E More Info
            </a>
        </li>';
    } else {
        $evaluation_status = $evaluation ? $evaluation_details : false;

        if ($budget_details || $monitoring_details || $evaluation_status ) {
            if ($edit) {
                $planned_buttons .= '
                <li>
                    <a type="button"  id="addmoneval"  href="add-project-mne-plan?proj=' . $projid . '">
                        <i class="fa fa-plus-square"></i> Edit M&E Plan Draft 
                       ' . $evaluation . '
                    </a> 
                </li>';
            }
        } else {
            if ($add) {
                $planned_buttons .= '
                <li>
                    <a type="button"  id="addmoneval"  href="add-project-mne-plan?proj=' . $projid . '">
                        <i class="fa fa-plus-square"></i> Add M&E Plan
                    </a>
                </li>';
            }
        }
    }


    $button = '<!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    ' . $planned_buttons . '
                    <li>
                        <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="more(' . $proj . ')">
                        <i class="fa fa-info"></i> Proj More Info 
                        </a>
                    </li>
                </ul> 
            </div>';

    return $button;
}


function evaluation($projid, $evaluation)
{
    global $db;
    $response = true;
    if ($evaluation == 1) {
        $query_rsOutcome =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid =:projid");
        $query_rsOutcome->execute(array(':projid' => $projid));
        $totalRows_rsOutcome = $query_rsOutcome->rowCount();
        $response = $totalRows_rsOutcome > 0 ? true : false;
    }
    return $response;
}

function monitoring($projid)
{
    global $db;
    $query_OutputData = $db->prepare("SELECT * FROM  tbl_project_details WHERE projid = '$projid' ORDER BY id ASC");
    $query_OutputData->execute();
    $countrows_OutpuData = $query_OutputData->rowCount();
    $row_OutputData =  $query_OutputData->fetch();
    $output_array = array();
    if ($countrows_OutpuData > 0) {
        do {
            $outputid = $row_OutputData['id'];
            $query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid AND outputid=:outputid");
            $query_rsDetails->execute(array(":projid" => $projid, ":outputid" => $outputid));
            $totalRows_rsDetails = $query_rsDetails->rowCount();
            $output_array[] = $totalRows_rsDetails > 0 ? true : false;
        } while ($row_OutputData =  $query_OutputData->fetch());
    } else {
        $output_array[] = false;
    }
    return (!in_array(false, $output_array)) ? true : false;
}

function mne_budget($projid, $evaluation, $mapping, $mne_budget)
{
    global $db;
    $evaluation_data = true;
    if ($evaluation) {
        $evaluation_data = get_budget_data($projid, 'C');
    }

    $mapping_data = true;
    if ($mapping) {
        $mapping_data = get_budget_data($projid, 'B');
    }

    $monitoring_data = get_budget_data($projid, 'A');
    $response = false;

    if ($evaluation_data && $mapping_data && $monitoring_data) {
        global $db;
        $query_rs_output_cost_plan =  $db->prepare("SELECT SUM(unit_cost*units_no) as budget FROM tbl_project_direct_cost_plan WHERE projid=:projid AND (other_plan_id='A' OR other_plan_id='B' OR other_plan_id='C')");
        $query_rs_output_cost_plan->execute(array(":projid" => $projid));
        $row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
        $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
        $allocated_budget = $row_rsOther_cost_plan['budget'];
        $response = $allocated_budget == $mne_budget ? true : false;
    }
    return $response;
}

function get_budget_data($projid, $other_plan_id)
{
    global $db;
    $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND other_plan_id=:other_plan_id ");
    $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":other_plan_id" => $other_plan_id));
    $row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
    $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
    return $totalRows_rs_output_cost_plan > 0 ? true : false;
}

function monitoring_checklist($projid)
{
    global $db;
    $query_rsChecklist = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE projid=:projid");
    $query_rsChecklist->execute(array(":projid" => $projid));
    $totalRows_rsChecklist = $query_rsChecklist->rowCount();
    return $totalRows_rsChecklist > 0 ? true : false;
}
