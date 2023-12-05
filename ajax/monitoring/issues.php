<?php
include '../controller.php';
try {

    if (isset($_GET['get_risk_category'])) {
        $projid = $_GET['projid'];
        $risk = '<option value="" selected="selected" class="selection">... Select ...</option>';
        $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.type=3 GROUP BY rskid ORDER BY R.id ASC");
        $query_allrisks->execute();
        $rows_allrisks = $query_allrisks->fetchAll();
        foreach ($rows_allrisks as $row) {
            $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
        }
        echo json_encode(array("success" => true, "issues" => $risk));
    }

    function get_inspection_status($status_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE statuskey = :status_id");
        $sql->execute(array(":status_id" => $status_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        return ($rows_count > 0) ? $row['status'] : "";
    }


    if (isset($_GET['get_project_issues'])) {
        $projid = $_GET['projid'];
        $success = false;
        $query_allrisks = $db->prepare("SELECT c.catid, c.category, i.id AS issueid, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE projid = :projid");
        $query_allrisks->execute(array(":projid" => $projid));
        $totalrows_allrisks = $query_allrisks->rowCount();

		$query_project =  $db->prepare("SELECT projcategory FROM tbl_projects WHERE projid=:projid");
		$query_project->execute(array(":projid" => $projid));
		$row_project = $query_project->fetch();
		$projcategory = $row_project["projcategory"];
		
        $issues_body = '<input type="hidden" value="0" id="clicked">';
        if ($totalrows_allrisks > 0) {
            $count =  0;
            $success = true;
			
            while ($rows_allrisks = $query_allrisks->fetch()) {
                $count++;
                $issueid = $rows_allrisks["issueid"];
                $issueareaid = $rows_allrisks["issue_area"];
                $category = $rows_allrisks["category"];
                $issue = $rows_allrisks["issue_description"];
                $impactid = $rows_allrisks["issue_impact"];
                $priorityid = $rows_allrisks["issue_priority"];
                $status_id = $rows_allrisks["status"];
				$issuedate = $rows_allrisks["date_created"];
                $status = get_inspection_status($status_id);

				$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE id=:impactid");
				$query_risk_impact->execute(array(":impactid" => $impactid));
				$row_risk_impact = $query_risk_impact->fetch();
				$impact = $row_risk_impact["description"];
				
				if($priorityid == 1){
					$priority = "High";
				}elseif($priorityid == 2){
					$priority = "Medium";
				}else{
					$priority = "Low";
				}
				
				$issues_scope = '';
				$textclass = "";
				if($issueareaid == 1){
					$issue_area = "Quality";
				}elseif($issueareaid == 2){
					$issue_area = "Scope";
					$textclass = "text-primary";
					$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";
					
					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id ".$leftjoin." left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid));
				
					$issues_scope .= '
					<tr class="adjustments '.$issueid.'" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="3">Sub-Task</th>
						<th>Requesting Units</th>
						<th>Additional Days</th>
						<th colspan="2">Additional Cost</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()){
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$units =  number_format($row_adjustments["units"])." ".$row_adjustments["unit"];
						$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
						$timeline = $row_adjustments["timeline"]." days";
						$issues_scope .= '<tr class="adjustments '.$issueid.'" style="background-color:#e5e5e5">
							<td>' . $count .'.'.$scopecount.'</td>
							<td colspan="3">' . $subtask . '</td>
							<td>' . $units . ' </td>
							<td>' . $timeline . '</td>
							<td colspan="2">' . number_format($totalcost, 2) . ' </td>
						</tr>';
					}
				}elseif($issueareaid == 3){
					$issue_area = "Schedule";
				}else{
					$issue_area = "Cost";
				}
                $issues_body .= '
                    <tr id="s_row">
                        <td>' . $count . '</td>
                        <td class="'.$textclass.'"><div onclick="adjustedscopes('.$issueid.')">' . $issue . '</div> </td>
                        <td>' . $category . '</td>
                        <td>' . $issue_area . ' </td>
                        <td>' . $impact . '</td>
                        <td>' . $priority . ' </td>
                        <td>' . $status . ' </td>
                        <td>' . date("d-m-Y", strtotime($issuedate)) . ' </td>
                    </tr>'.$issues_scope;
            }
        }

        $i  = '
        <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>Issue(s)
                </legend>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example">
                            <thead>
                                <tr>
                                    <th style="width:3%">#</th>
                                    <th style="width:38%">Issue</th>
                                    <th style="width:10%">Category</th>
                                    <th style="width:10%">Issue Area</th>
                                    <th style="width:10%">Impact</th>
                                    <th style="width:10%">Priority</th>
                                    <th style="width:9%">Status</th>
                                    <th style="width:10%">Issue Date</th>
                                </tr>
                            </thead>
                            <tbody id="previous_issues_table">
                            ' . $issues_body . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
			<script>
			$(document).ready(function () {			
				$(".adjustments").hide();
			});
			</script>';
        echo json_encode(array('success' => $success, 'issues' => $i));
    }

    if (isset($_POST['store_checklists'])) {
        $projid = $_POST['projid'];
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");

        if (isset($_POST["issue_description"])) {
            $issue_description = $_POST["issue_description"];
            $issue_area = $_POST["issue_area"];
            $risk_category = $_POST["risk_category"];
            $issue_priority = $_POST["issue_priority"];
            $issue_impact = $_POST["issue_impact"];
			
			$SQLinsert = $db->prepare("INSERT INTO tbl_projissues (projid, issue_description, issue_area, risk_category, issue_priority, issue_impact, created_by, date_created) VALUES (:projid, :issue_description, :issue_area, :risk_category, :issue_priority, :issue_impact, :user, :date)");
			$results  = $SQLinsert->execute(array(':projid' => $projid, ':issue_description' => $issue_description, ':issue_area' => $issue_area, ':risk_category' => $risk_category, ':issue_priority' => $issue_priority, ':issue_impact' => $issue_impact, ':user' => $user_name, ':date' => $currentdate));

		
			if($results){
				$issue_id = $db->lastInsertId();
				if ($issue_area == 2) {
					$count = count($_POST["units"]);
					for ($cnt = 0; $cnt < $count; $cnt++) {
						$units = $_POST["units"][$cnt];
						if(!empty($units) && $units !="" && !is_null($units)){
							$duration = $_POST["duration"][$cnt];
							$subtaskid = $_POST["subtaskid"][$cnt];
							
							$query_insert = $db->prepare("INSERT INTO tbl_project_adjustments (projid, issueid, issue_area, sub_task_id, units, timeline, created_by, date_created) VALUES (:projid, :issue_id, :issue_area, :subtask_id, :units, :duration, :user, :date)");
							$query_insert->execute(array(':projid' => $projid, ':issue_id' => $issue_id, ':issue_area' => $issue_area, ':subtask_id' => $subtaskid, ':units' => $units, ':duration' => $duration, ':user' => $user_name, ':date' => $currentdate));
						}
					}
				}
								
				if (isset($_POST["attachmentpurpose"])) {
					$filecategory = "monitoring checklist";
					$stage = 1;
					$count = count($_POST["attachmentpurpose"]);
					for ($cnt = 0; $cnt < $count; $cnt++) {
						if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
							if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
								$purpose = $_POST["attachmentpurpose"][$cnt];
								$filename = basename($_FILES['monitorattachment']['name'][$cnt]);
								$ext = substr($filename, strrpos($filename, '.') + 1);
								if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
									$newname = date("d-m-Y") . "-" . $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
									$filepath = "../../uploads/monitoring/other-files/" . $newname;
									$path = "uploads/monitoring/other-files/" . $newname;
									if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
										$filepath = "../../uploads/monitoring/photos/" . $newname;
										$path = "uploads/monitoring/photos/" . $newname;
									}

									if (!file_exists($filepath)) {
										if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
											$qry2 = $db->prepare("INSERT INTO tbl_files (projid,projstage,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:projstage,:formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
											$result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
										} else {
											echo "could not move the file ";
										}
									} else {
										$msg = 'File you are uploading already exists, try another file!!';
									}
								} else {
									$msg = 'This file type is not allowed, try another file!!';
								}
							} else {
								$msg = 'You have not attached any file!!';
							}
						}
					}
				}
				echo json_encode(array("success" => true, "message" => "Created successfully"));
			}
		}
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
