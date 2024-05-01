<?php
//Include database configuration file
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

if (isset($_POST['get_output'])) {
    $projid = $_POST['projid'];
    $query_rsOutputs = $db->prepare("SELECT g.output, d.id FROM `tbl_projects` p INNER JOIN tbl_project_details d ON d.projid = p.projid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE p.projid = $projid");
    $query_rsOutputs->execute();
    $row_rsOutputs = $query_rsOutputs->fetch();
    $count_rsOutputs = $query_rsOutputs->rowCount();
    if ($count_rsOutputs > 0) {
        $outputs = '<option value="">.... Select Output ....</option>';
        do {
            $outputs .= '<option value="' . $row_rsOutputs['id'] . '">' . $row_rsOutputs['output'] . '</option>';
        } while ($row_rsOutputs = $query_rsOutputs->fetch());
        echo json_encode(array('success' => true, 'outputs' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'outputs' => ""));
    }
}

// get level 3
if (isset($_POST['get_level3'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $query_rsLevel3 = $db->prepare("SELECT s.id, s.state FROM `tbl_output_disaggregation` o INNER JOIN tbl_state s ON o.outputstate = s.id WHERE o.projid = $projid AND o.outputid = $outputid");
    $query_rsLevel3->execute();
    $row_rsLevel3 = $query_rsLevel3->fetch();
    $count_rsLevel3 = $query_rsLevel3->rowCount();

    if ($count_rsLevel3 > 0) {
        $level3 = '<option value="">.... Select Level 3 ....</option>';
        do {
            $level3 .= '<option value="' . $row_rsLevel3['id'] . '">' . $row_rsLevel3['state'] . '</option>';
        } while ($row_rsLevel3 = $query_rsLevel3->fetch());
        echo json_encode(array('success' => true, 'level3' => $level3));
    } else {
        echo json_encode(array('success' => false, 'level3' => ""));
    }
}

// get level 4
if (isset($_POST['get_level4'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $level3 = $_POST['level3'];
    $query_rsLevel4 = $db->prepare("SELECT d.id,d.disaggregations FROM `tbl_project_results_level_disaggregation` p INNER JOIN tbl_indicator_level3_disaggregations d ON d.id = p.name WHERE p.projid = $projid AND p.projoutputid = $outputid AND p.opstate = $level3");
    $query_rsLevel4->execute();
    $row_rsLevel4 = $query_rsLevel4->fetch();
    $count_rsLevel4 = $query_rsLevel4->rowCount();
    if ($count_rsLevel4 > 0) {
        $outputs = '<option value="">.... Select Output ....</option>';
        do {
            $outputs .= '<option value="' . $row_rsLevel4['id'] . '">' . $row_rsLevel4['disaggregations'] . '</option>';
        } while ($row_rsLevel4 = $query_rsLevel4->fetch());
        echo json_encode(array('success' => true, 'outputs' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'outputs' => ""));
    }
}

if (isset($_POST['getTasks'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['outputid'];
    $level3 = $_POST['level3'];
    $level4 = $_POST['level4'];
    $level4id = '';
    if ($level4 != 0) {
        $level4id = "AND level4 = $level4";
    }

    $query_rsTasks = $db->prepare("SELECT tkid, task FROM tbl_task where projid = $projid AND outputid =$outputid ");
    $query_rsTasks->execute();
    $row_rsTasks = $query_rsTasks->fetch();
    $count_rsTasks = $query_rsTasks->rowCount();


    if ($count_rsTasks) {
        $tasks = '';
        $nm = 0;
        do {
            $taskid = $row_rsTasks['tkid'];
            $task = $row_rsTasks['task'];
            $query_rsmonitoringTasks = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where score = 10 AND taskid = $taskid AND level3= $level3 $level4id");
            $query_rsmonitoringTasks->execute();
            $row_rsmonitoringTasks = $query_rsmonitoringTasks->fetch();
            $count_rsmonitoringTasks = $query_rsmonitoringTasks->rowCount();

            if ($count_rsmonitoringTasks > 0) {
                $query_rsInspectionTasks = $db->prepare("SELECT * FROM tbl_task_inspection_status where taskid = $taskid AND level3 = $level3 $level4id");
                $query_rsInspectionTasks->execute();
                $row_rsInspectionTasks = $query_rsInspectionTasks->fetch();
                $count_rsInspectionTasks = $query_rsInspectionTasks->rowCount();
                $nm++;
                if ($count_rsInspectionTasks == 0) {
                    $tasks .=
                        '<tr>
                            <td>' . $nm . '</td>
                            <td>' . $task . '</td>
                            <td>N/A</td>
                        </tr>';
                } else if ($row_rsInspectionTasks['status'] == 0) {
                    $tasks .=
                        '<tr>
                            <td>' . $nm . '</td>
                            <td>' . $task . '</td>
                            <td>Failed</td>
                        </tr>';
                }
            }
        } while ($row_rsTasks = $query_rsTasks->fetch());
        echo json_encode(array("success" => true, "tasks" => $tasks));
    }
}

if (isset($_POST['insepectform'])) {
    $uploadDir = '../../uploads/inspection/';
    $assignment_id = $_POST['formid'];
    $task_id = $_POST['task_id'];
    $question_id = $_POST['question_id'];
    $comments = $_POST['comments'];
    $attachment_path = "Test";
    $created_by = 3;
    $created_at = date('Y-m-d');
    $uploadStatus = 1;
    $uploadedFile = '';
    $attachment_path = '';
    if (!empty($_FILES["file"]["name"])) {

        // File path config
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $uploadedFile = $fileName;
                $attachment_path = $targetFilePath;
            } else {
                $uploadStatus = 0;
                $response['message'] = 'Sorry, there was an error uploading your file.';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
        }
    }

    if ($uploadStatus == 1) {
        $insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_noncompliance_comments (assignment_id, task_id, question_id, comments,attachment_path,created_by,created_at) VALUES (:assignment_id, :task_id, :question_id, :comments,:attachment_path,:created_by,:created_at)");
        $result = $insertSQL->execute(array(":assignment_id" => $assignment_id, ":task_id" => $task_id, ":question_id" => $question_id, ":comments" => $comments, ":attachment_path" => $attachment_path, ":created_by" => $created_by, ":created_at" => $created_at));
        if ($result) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }
}

if (isset($_POST['dept'])) {
    $dept = $_POST['dept'];
    $query_rsIndicator = $db->prepare("SELECT indid, indicator_name FROM tbl_indicator WHERE indicator_sector = '$dept'");
    $query_rsIndicator->execute();
    $row_rsIndicator = $query_rsIndicator->fetch();
    $indcount = $query_rsIndicator->rowCount();
    if ($indcount > 0) {
        $outputs = '<option value="">.... Select Output Indicator....</option>';
        do {
            $outputs .= '<option value="' . $row_rsIndicator['indid'] . '">' . $row_rsIndicator['indicator_name'] . '</option>';
        } while ($row_rsIndicator = $query_rsIndicator->fetch());
        echo json_encode(array('success' => true, 'indicators' => $outputs));
    } else {
        echo json_encode(array('success' => false, 'indicators' => ""));
    }
}


function get_locations($projid, $output_id)
{
    global $db;
    $query_projlocations = $db->prepare("SELECT s.id, s.state FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE projid=:projid AND outputid=:outputid ");
    $query_projlocations->execute(array(":projid" => $projid, ":outputid" => $output_id));
    $totalRows_projlocations = $query_projlocations->rowCount();

    $locdata = "<option value=''>Locations not defined</option>";
    if ($totalRows_projlocations > 0) {
        $locdata = "<option value=''>Select Location</option>";
        while ($row_projlocations = $query_projlocations->fetch()) {
            $locdata .= "<option value='" . $row_projlocations['id'] . "'>" . $row_projlocations['state'] . "</option>";
        }
    }
    return $locdata;
}

function validate_tasks($msid)
{
    global $db;
    $query_rs_tasks = $db->prepare("SELECT * FROM tbl_task WHERE msid = :msid");
    $query_rs_tasks->execute(array(":msid" => $msid));
    $totalRows_rs_tasks = $query_rs_tasks->rowCount();
    $msg = [];
    if ($totalRows_rs_tasks > 0) {
        while ($row_rs_tasks = $query_rs_tasks->fetch()) {
            $task_id = $row_rs_tasks['tkid'];
            $query_rs_direct_cost = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :task_id AND inspection_status <> 2");
            $query_rs_direct_cost->execute(array(":task_id" => $task_id));
            $totalRows_rs_direct_cost = $query_rs_direct_cost->rowCount();
            $msg[] = $totalRows_rs_direct_cost > 0 ? false : true;
        }
    }
    return (in_array(false, $msg)) ? true : false;
}

function get_milestones($projid, $output_id)
{
    global $db;
    $query_tkMs = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid  AND (status=4 OR status=11) ORDER BY msid DESC");
    $query_tkMs->execute(array(":projid" => $projid, ":outputid" => $output_id));
    $row_tkMs = $query_tkMs->fetch();
    $total_tkMs = $query_tkMs->rowCount();

    $milestone_options = "<option value=''>Milestones not defined</option>";
    if ($total_tkMs > 0) {
        $milestone_options = "<option value=''>Select Milestone</option>";
        do {
            $msid = $row_tkMs['msid'];
            $milestone = $row_tkMs['milestone'];
            $perm = validate_tasks($msid);
            if ($perm) {
                $milestone_options .= "<option value='" . $msid . "'>" . $milestone . "</option>";
            }
        } while ($row_tkMs = $query_tkMs->fetch());
    }
    return $milestone_options;
}

if (isset($_GET['get_project_details'])) {
    $projid = $_GET['projid'];
    $output_id = $_GET['output_id'];
    $locations  = get_locations($projid, $output_id);
    $milestone = get_milestones($projid, $output_id);
    echo json_encode(array("success" => true, "milestone" => $milestone, "location" => $locations));
}

if(isset($_GET["get_milestones"])) {
	$projid = $_GET['projid'];
	$output_id = $_GET['output_id'];
	$monitoringlocation = $_GET['location'];

	$query_milestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid and outputid = :opid and (status = 4 or status = 11)");
	$query_milestones->execute(array(":projid" => $projid, ":opid" => $output_id));
	$total_rows_milestones = $query_milestones->rowCount();

	if ($total_rows_milestones > 0) {
		$milestonelist = '<option value="" selected="selected" class="selection">... Select Milestone...</option>';
		while($row_milestones = $query_milestones->fetch()){
			if(!empty($row_milestones["location"]) || $row_milestones["location"] != ''){
				$milestonelocation = explode (",", $row_milestones["location"]);
				if(in_array($monitoringlocation, $milestonelocation)){
					$msid = $row_milestones['msid'];
					$milestone = $row_milestones['milestone'];
					$milestonelist .= '<option value="'.$msid.'">'.$milestone.'</option>';
				}
			} else {
				$milestonelist = '<option value="" selected="selected" class="selection">... Milestones location not defined ...</option>';
			}
		}
	} else {
		$milestonelist = '<option value="" selected="selected" class="selection">... No defined milestone for this location ...</option>';
	}
	echo $milestonelist;
}

if (isset($_GET['get_task'])) {
    $projid = $_GET['projid'];
    $output_id = $_GET['output_id'];
    $msid = $_GET['msid'];
    $query_rs_tasks = $db->prepare("SELECT * FROM tbl_task WHERE msid = :msid");
    $query_rs_tasks->execute(array(":msid" => $msid));
    $totalRows_rs_tasks = $query_rs_tasks->rowCount();
    $task_table_body = '';
    $counter = 0;
    if ($totalRows_rs_tasks > 0) {
        while ($row_rs_tasks = $query_rs_tasks->fetch()) {
            $task_id = $row_rs_tasks['tkid'];
            $task = $row_rs_tasks['task'];
            $hash_task_id = base64_encode($task_id);

            $query_rs_direct_cost = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :task_id AND inspection_status <> 2");
            $query_rs_direct_cost->execute(array(":task_id" => $task_id));
            $totalRows_rs_direct_cost = $query_rs_direct_cost->rowCount();
            if ($totalRows_rs_direct_cost > 0) {
                $counter++;
                $query_rsInspection_details = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid = :projid AND task_id=:task_id AND origin=2 ORDER BY created_at ASC");
                $query_rsInspection_details->execute(array(":projid" => $projid, ":task_id" => $task_id));
                $row_rsInspection_details = $query_rsInspection_details->fetch();
                $totalRows_rsInspection_details = $query_rsInspection_details->rowCount();

                $inspection_report = '';
                if ($totalRows_rsInspection_details > 0) {
                    $inspection_report = '
                    <li>
                        <a type="button" href="view-task-inspection-report.php?task_id=' . $hash_task_id . '">
                            <i class="fa fa-pencil-square"></i>  Inspection Report
                        </a>
                    </li>';
                }

                $task_table_body .= '
               <tr>
                <td>' . $counter . '</td>
                <td>' . $task . '</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="get_task_checklist(' . $task_id . ')">
                                    <i class="fa fa-pencil-square"></i> Inspect
                                </a>
                            </li>
                            ' . $inspection_report . '
                        </ul>
                    </div>
                </td>
               </tr>
               ';
            }
        }
    }
    echo json_encode(array("success" => true, "task" => $task_table_body));
}



if (isset($_GET['get_task_checklist'])) {
    $projid = $_GET['projid'];
    $output_id = $_GET['output_id'];
    $msid = $_GET['msid'];
    $task_id = $_GET['task_id'];

    $query_rs_tasks = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :task_id AND inspection_status <> 2 ");
    $query_rs_tasks->execute(array(":task_id" => $task_id));
    $totalRows_rs_tasks = $query_rs_tasks->rowCount();
    $task_checklist = ' <input type="hidden" name="task_id" value="' . $task_id . '">';
    $counter = 0;
    if ($totalRows_rs_tasks > 0) {
        while ($row_rs_tasks = $query_rs_tasks->fetch()) {
            $description = $row_rs_tasks['description'];
            $unit = $row_rs_tasks['unit'];
            $units_no = $row_rs_tasks['units_no'];
            $id = $row_rs_tasks['id'];
            $inspection_status = $row_rs_tasks['inspection_status'];
            $unit_st = $units_no . "   " . $unit;
            $counter++;
            $selected = $inspection_status == 1 ? "selected" : "";
            $task_checklist .= '
            <tr>
                <td  style="width:5%">' . $counter . '</td>
                <td style="width:65%">' . $description . '</td>
                <td style="width:25%">' . $unit_st . '</td>
                <td  style="width:5%">
                    <input type="hidden" name="description[]" value="' . $id . '">
                    <select name="compliance[]" id="compliance" class="form-control compliance" >
                        <option value="">Select Compliance</option>
                        <option value="2">Compliant</option>
                        <option value="1" ' . $selected . '>Non-compliant</option>
                    </select>
                </td>
            </tr>';
        }
    }


    $origin = "Inspection";
    $query_rs_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE task_id = :task_id AND origin=:origin ");
    $query_rs_issues->execute(array(":task_id" => $task_id, ":origin" => $origin));
    $totalRows_rs_issues = $query_rs_issues->rowCount();


    $query_rs_observation = $db->prepare("SELECT * FROM tbl_general_inspection WHERE task_id = :task_id AND origin=:origin ORDER BY id DESC LIMIT 1");
    $query_rs_observation->execute(array(":task_id" => $task_id, ":origin" => 2));
    $totalRows_rs_observation = $query_rs_observation->rowCount();
    $Rows_rs_observation = $query_rs_observation->fetch();
    $observation = $totalRows_rs_observation > 0 ? $Rows_rs_observation['observations'] : "<span style='color:blue'>No record found!</span>";

    echo json_encode(array("success" => true, "task_checklist" => $task_checklist, "issues" => $totalRows_rs_issues, "previous_observations" => $observation));
}

function next_monitoring_date($output_id)
{
    global $db;
    $query_rs_tasks = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE outputid = :output_id AND inspection_status <> 2 ");
    $query_rs_tasks->execute(array(":output_id" => $output_id));
    $totalRows_rs_tasks = $query_rs_tasks->rowCount();
    if ($totalRows_rs_tasks == 0) {
        $today = date("Y-m-d");
        $next_monitoring_date = date('Y-m-d', strtotime($today . ' + 1 days'));
        $sqlUpdate = $db->prepare("UPDATE tbl_project_outputs_mne_details SET next_monitoring_date=:next_monitoring_date WHERE outputid=:output_id");
        $sqlUpdate->execute(array(':next_monitoring_date' => $next_monitoring_date, ':output_id' => $output_id));
    }
}


function dateDiffInDays($date1, $date2)
{
    $diff = strtotime($date2) - strtotime($date1);
    return abs(round($diff / 86400));
}


function task_start_date($projid, $task_id)
{
    global $db;
    $query_rsInspection_details = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid = :projid AND task_id=:task_id AND origin=2 ORDER BY created_at ASC");
    $query_rsInspection_details->execute(array(":projid" => $projid, ":task_id" => $task_id));
    $row_rsInspection_details = $query_rsInspection_details->fetch();
    $totalRows_rsInspection_details = $query_rsInspection_details->rowCount();

    if ($totalRows_rsInspection_details == 0) {
        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task where tkid = :task_id ");
        $query_rsTasks->execute(array("task_id" => $task_id));
        $row_rsTasks = $query_rsTasks->fetch();
        $count_rsTasks = $query_rsTasks->rowCount();
        if ($count_rsTasks > 0) {
            $today = date("Y-m-d");
            $start_date = date("Y-m-d", strtotime($row_rsTasks['sdate']));
            $end_date = date("Y-m-d", strtotime($row_rsTasks['edate']));
            $msid = $row_rsTasks['msid'];

            if ($today > $start_date) {
                $query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
                $query_rsMilestones->execute(array(":msid" => $msid));
                $row_rsMilestones = $query_rsMilestones->fetch();
                $totalRows_rsMilestones = $query_rsMilestones->rowCount();

                if ($totalRows_rsMilestones > 0) {
                    $milestone_start_date = $row_rsMilestones['sdate'];
                    $milestone_end_date = $row_rsMilestones['edate'];
                    if ($today > $milestone_start_date) {
                        $dateDiff = dateDiffInDays($milestone_start_date, $milestone_end_date);
                        $m_end_date = date('Y-m-d', strtotime($today . ' + ' . $dateDiff . ' days'));
                        $insertSQL = $db->prepare("UPDATE tbl_milestone SET sdate=:sdate, edate=:edate WHERE msid=:msid ");
                        $results = $insertSQL->execute(array(':sdate' => $today, ":edate" => $m_end_date, ":msid" => $msid));
                    }
                }

                $dateDiff = dateDiffInDays($start_date, $end_date);
                $task_end_date = date('Y-m-d', strtotime($today . ' + ' . $dateDiff . ' days'));
                $insertSQL = $db->prepare("UPDATE tbl_task SET sdate=:sdate, edate=:edate WHERE tkid=:taskid ");
                $results = $insertSQL->execute(array(':sdate' => $today, ":edate" => $task_end_date, ":taskid" => $task_id));
            }
        }
    }

    return true;
}

if (isset($_POST['store'])) {

    $task_id = $_POST['task_id'];
    $user_name = $_POST['user_name'];
    $milestone_id = $_POST['milestone_id'];
    $formid = $_POST['formid'];
    $level3 = 1;
    $projid = $_POST['projid'];
    $output_id = $_POST['output_id'];
    $currentdate = date('Y-m-d');
    $origin = "Inspection";
    $stage = 10;
    task_start_date($projid, $task_id);


    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $compliance = $_POST['compliance'];
        $counter = count($description);
        for ($i = 0; $i < $counter; $i++) {
            $desc = $description[$i];
            $compl = $compliance[$i];
            if ($compl != "") {
                $insertSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET  inspection_status = :compliance WHERE  id = :description");
                $results  = $insertSQL->execute(array(":compliance" => $compl, ":description" => $desc));
            }
        }
    }

    if (isset($_POST["issuedescription"])) {
        $nmb = count($_POST["issuedescription"]);
        for ($k = 0; $k < $nmb; $k++) {
            if (trim($_POST["issuedescription"][$k]) !== '' || !empty(trim($_POST["issuedescription"][$k]))) {
                $SQLinsert = $db->prepare("INSERT INTO tbl_projissues (milestone_id,task_id, formid, projid,level3, origin, opid, risk_category, observation, created_by, date_created) VALUES (:milestone_id,:task_id, :formid, :projid,:level3, :origin, :opid, :riskcat, :obsv, :user, :date)");
                $Rst  = $SQLinsert->execute(array(":milestone_id" => $milestone_id, ":task_id" => $task_id, ":formid" => $formid, ":projid" => $projid, ':level3' => $level3, ':origin' => $origin, ':opid' => $output_id, ':riskcat' => $_POST['issue'][$k], ':obsv' => $_POST['issuedescription'][$k], ':user' => $user_name, ':date' => $currentdate));
            }
        }
    }

    if (isset($_POST['comments'])) {
        $observ = $_POST['comments'];
        $SQLinsert = $db->prepare("INSERT INTO tbl_general_inspection (projid,inspectionid,task_id,location,subject,observations,origin,created_at,created_by) VALUES (:projid,:inspectionid,:task_id,:location,:subject,:observations,:origin,:created_at,:created_by)");
        $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":inspectionid" => $formid, ":task_id" => $task_id, ':location' => $level3, ":subject" => 0, ':observations' => $observ, ':origin' => 2,  ':created_at' => $currentdate, ':created_by' => $user_name));
    }

    $filecategory = "Inspection";
    $count = count($_POST["attachmentpurpose"]);
    for ($cnt = 0; $cnt < $count; $cnt++) {
        if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
            if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                $purpose = $_POST["attachmentpurpose"][$cnt];
                $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                $ext = substr($filename, strrpos($filename, '.') + 1);
                if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                    $newname = $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                    if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                        $filepath = "../../uploads/inspection/photos/" . $newname;
                        if (!file_exists($filepath)) {
                            if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                $path = "uploads/inspection/photos/" . $newname;
                                $qry2 = $db->prepare("INSERT INTO tbl_project_photos (projid, task_id, form_id, opid, projstage, fcategory, filename, ftype, description, floc, uploaded_by, date_uploaded) VALUES (:projid,:task_id,:formid,:opid, :stage, :fcat, :filename, :ftype, :desc, :floc, :user, :date)");
                                $data = $qry2->execute(array(':projid' => $projid, ':task_id' => $task_id, ':formid' => $formid, ":opid" => $output_id, ':stage' => $stage, ':fcat' => $filecategory, ':filename' => $newname, ":ftype" => $ext, ":desc" => $purpose, ":floc" => $path, ':user' => $user_name, ':date' => $currentdate));
                            } else {
                                $msg = "Could not move the file";
                            }
                        } else {
                            $msg = "Sorry the filepath does not exist";
                            // $results = error_msg($msg);
                        }
                    } else {
                        $filepath = "../../uploads/inspection/other-files/" . $newname;
                        if (!file_exists($filepath)) {
                            $path = "uploads/inspection/other-files/" . $newname;
                            if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                $qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, task_id, form_id, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)  VALUES (:projid, :projstage, :task_id, :formid, :filename, :ftype, :floc, :fcat, :desc, :user, :date)");
                                $result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':task_id' => $task_id, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                            } else {
                                echo "could not move the file ";
                            }
                        } else {
                            $msg = 'File you are uploading already exists, try another file!!';
                            // $results = error_msg($msg);
                        }
                    }
                } else {
                    $msg = 'This file type is not allowed, try another file!!';
                    // $results = error_msg($msg);
                }
            } else {
                $msg = 'You have not attached any file!!';
                // $results = error_msg($msg);
            }
        }
    }

    next_monitoring_date($output_id);
    echo json_encode(array("success" => true));
}


if (isset($_GET['get_risk_category'])) {
    $projid = $_GET['projid'];
    $output_id = $_GET['output_id'];
    $risk = '<option value="" selected="selected" class="selection">... Select ...</option>';
    $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.projid = :projid and R.outputid = :opid and R.type=3 ORDER BY R.id ASC");
    $query_allrisks->execute(array(":projid" => $projid, ":opid" => $output_id));
    $rows_allrisks = $query_allrisks->fetchAll();

    foreach ($rows_allrisks as $row) {
        $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
    }
    echo json_encode(array("success" => true, "issues" => $risk));
}
