<?php
try {
	include '../controller.php';


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
		//$projid = $_GET['projid'];
		$decode_projid = base64_decode($_GET['projid']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];

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

				$query_issue_area =  $db->prepare("SELECT * FROM tbl_issue_areas WHERE id=:issueareaid");
				$query_issue_area->execute(array(":issueareaid" => $issueareaid));
				$row_issue_area = $query_issue_area->fetch();
				$issue_area = $row_issue_area["issue_area"];

				if ($priorityid == 1) {
					$priority = "High";
				} elseif ($priorityid == 2) {
					$priority = "Medium";
				} else {
					$priority = "Low";
				}

				$issues_scope = '';
				$textclass = "text-primary";

				if ($issueareaid == 1) {
					$issues_scope .= '';
				} elseif ($issueareaid == 2) {
					$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

					$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
					$query_site_id->execute(array(":issueid" => $issueid));

					$sites = 0;
					while ($rows_site_id = $query_site_id->fetch()) {
						$sites++;
						$site_id = $rows_site_id['site_id'];
						//$allsites = '';

						if ($site_id == 0) {
							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
							</tr>';
						} else {
							$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
							$query_site->execute(array(":site_id" => $site_id));
							$row_site = $query_site->fetch();
							$site = $row_site['site'];

							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
							</tr>';
						}

						$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
						$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

						$issues_scope .= $allsites . '
						<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
							<th>#</th>
							<th colspan="3">Sub-Task</th>
							<th>Requesting Units</th>
							<th>Additional Days</th>
							<th colspan="2">Additional Cost</th>
						</tr>';
						$scopecount = 0;
						while ($row_adjustments = $query_adjustments->fetch()) {
							$scopecount++;
							$subtask = $row_adjustments["task"];
							$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
							$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
							$timeline = $row_adjustments["timeline"] . " days";
							$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
								<td>' . $count . '.' . $sites . '.' . $scopecount . '</td>
								<td colspan="3">' . $subtask . '</td>
								<td>' . $units . ' </td>
								<td>' . $timeline . '</td>
								<td colspan="2">' . number_format($totalcost, 2) . ' </td>
							</tr>';
						}
					}
				} elseif ($issueareaid == 3) {
					$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

					$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
					$query_site_id->execute(array(":issueid" => $issueid));

					$sites = 0;
					while ($rows_site_id = $query_site_id->fetch()) {
						$sites++;
						$site_id = $rows_site_id['site_id'];
						$allsites = '';

						if ($site_id == 0) {
							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
							</tr>';
						} else {
							$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
							$query_site->execute(array(":site_id" => $site_id));
							$row_site = $query_site->fetch();
							$site = $row_site['site'];

							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
							</tr>';
						}

						$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
						$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

						$issues_scope .= $allsites . '
						<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
							<th>#</th>
							<th colspan="5">Sub-Task</th>
							<th colspan="2">Additional Days</th>
						</tr>';
						$scopecount = 0;
						while ($row_adjustments = $query_adjustments->fetch()) {
							$scopecount++;
							$subtask = $row_adjustments["task"];
							$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
							$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
							$timeline = $row_adjustments["timeline"] . " days";
							$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
								<td>' . $count . '.' . $sites . '.' . $scopecount . '</td>
								<td colspan="5">' . $subtask . '</td>
								<td colspan="2">' . $timeline . '</td>
							</tr>';
						}
					}
				} else {
					$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

					$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
					$query_site_id->execute(array(":issueid" => $issueid));

					$sites = 0;
					while ($rows_site_id = $query_site_id->fetch()) {
						$sites++;
						$site_id = $rows_site_id['site_id'];
						//$allsites = '';

						if ($site_id == 0) {
							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
							</tr>';
						} else {
							$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
							$query_site->execute(array(":site_id" => $site_id));
							$row_site = $query_site->fetch();
							$site = $row_site['site'];

							$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
								<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
							</tr>';
						}

						$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, a.cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
						$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

						$issues_scope .= $allsites . '
						<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
							<th>#</th>
							<th colspan="3">Sub-Task</th>
							<th colspan="2">Measurement Unit</th>
							<th colspan="2">Additional Cost</th>
						</tr>';
						$scopecount = 0;
						while ($row_adjustments = $query_adjustments->fetch()) {
							$scopecount++;
							$subtask = $row_adjustments["task"];
							$unit =  $row_adjustments["unit"];
							$cost = $row_adjustments["cost"];

							$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
								<td>' . $count . '.' . $sites . '.' . $scopecount . '</td>
								<td colspan="3">' . $subtask . '</td>
								<td colspan="2">' . $unit . ' </td>
								<td colspan="2">' . number_format($cost, 2) . ' </td>
							</tr>';
						}
					}
				}
				$issues_body .= '
                    <tr id="s_row">
                        <td>' . $count . '</td>
                        <td class="' . $textclass . '"><div onclick="adjustedscopes(' . $issueid . ')">' . $issue . '</div> </td>
                        <td>' . $category . '</td>
                        <td>' . $issue_area . ' </td>
                        <td>' . $impact . '</td>
                        <td>' . $priority . ' </td>
                        <td>' . $status . ' </td>
                        <td>' . date("d-m-Y", strtotime($issuedate)) . ' </td>
                    </tr>' . $issues_scope;
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
                                    <th style="width:37%">Issue</th>
                                    <th style="width:10%">Category</th>
                                    <th style="width:10%">Issue Area</th>
                                    <th style="width:10%">Impact</th>
                                    <th style="width:10%">Priority</th>
                                    <th style="width:10%">Resolution</th>
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
		if (validate_csrf_token($_POST['csrf_token'])) {
			$decode_projid = base64_decode($_POST['projid']);
			$projid_array = explode("projid54321", $decode_projid);
			$projid = $projid_array[1];
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


				if ($results) {
					$issue_id = $db->lastInsertId();
					if ($issue_area == 1) {
					} elseif ($issue_area == 2) {
						$count = count($_POST["units"]);
						for ($cnt = 0; $cnt < $count; $cnt++) {
							$units = $_POST["units"][$cnt];
							if (!empty($units) && $units != "" && !is_null($units)) {
								$duration = $_POST["duration"][$cnt];
								$subtaskid = $_POST["subtaskid"][$cnt];
								$pwid = $_POST["pwid"][$cnt];
								$site_id = $_POST["siteid"][$cnt];

								$query_insert = $db->prepare("INSERT INTO tbl_project_adjustments (projid, issueid, issue_area, sub_task_id, pw_id, site_id, units, timeline, created_by, date_created) VALUES (:projid, :issue_id, :issue_area, :subtask_id, :pwid, :site_id, :units, :duration, :user, :date)");
								$query_insert->execute(array(':projid' => $projid, ':issue_id' => $issue_id, ':issue_area' => $issue_area, ':subtask_id' => $subtaskid, ':pwid' => $pwid, ':site_id' => $site_id, ':units' => $units, ':duration' => $duration, ':user' => $user_name, ':date' => $currentdate));
							}
						}
					} elseif ($issue_area == 3) {
						$count = count($_POST["additional_duration"]);
						for ($cnt = 0; $cnt < $count; $cnt++) {
							$durations = $_POST["additional_duration"][$cnt];
							if (!empty($durations) && $durations != "" && !is_null($durations)) {
								$duration = $_POST["additional_duration"][$cnt];
								$subtaskid = $_POST["subtaskid"][$cnt];
								$pwid = $_POST["pwid"][$cnt];
								$site_id = $_POST["siteid"][$cnt];

								$query_insert = $db->prepare("INSERT INTO tbl_project_adjustments (projid, issueid, issue_area, sub_task_id, pw_id, site_id, timeline, created_by, date_created) VALUES (:projid, :issue_id, :issue_area, :subtask_id, :pwid, :site_id, :duration, :user, :date)");
								$query_insert->execute(array(':projid' => $projid, ':issue_id' => $issue_id, ':issue_area' => $issue_area, ':subtask_id' => $subtaskid, ':pwid' => $pwid, ':site_id' => $site_id, ':duration' => $duration, ':user' => $user_name, ':date' => $currentdate));
							}
						}
					} elseif ($issue_area == 4) {
						$count = count($_POST["additional_unit_cost"]);
						for ($cnt = 0; $cnt < $count; $cnt++) {
							$cost = $_POST["additional_unit_cost"][$cnt];
							if (!empty($cost) && $cost != "" && !is_null($cost)) {
								$price = $_POST["additional_unit_cost"][$cnt];
								$subtaskid = $_POST["subtaskid"][$cnt];
								$pwid = $_POST["pwid"][$cnt];
								$site_id = $_POST["siteid"][$cnt];

								$query_insert = $db->prepare("INSERT INTO tbl_project_adjustments (projid, issueid, issue_area, sub_task_id, pw_id, site_id, cost, created_by, date_created) VALUES (:projid, :issue_id, :issue_area, :subtask_id, :pwid, :site_id, :cost, :user, :date)");
								$query_insert->execute(array(':projid' => $projid, ':issue_id' => $issue_id, ':issue_area' => $issue_area, ':subtask_id' => $subtaskid, ':pwid' => $pwid, ':site_id' => $site_id, ':cost' => $price, ':user' => $user_name, ':date' => $currentdate));
							}
						}
					}

					if (isset($_POST["attachmentpurpose"])) {
						$filecategory = "Issue";
						$stage = $issue_id;
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
	}

	if (isset($_POST['issue_type'])) {

		$issue_type = $_POST['issue_type'];
		$decode_projid = base64_decode($_POST['projid']);
		$projid_array = explode("projid54321", $decode_projid);
		$projid = $projid_array[1];
		$issuefields = '';
		$success = false;

		$query_project_details = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_project_details->execute(array(':projid' => $projid));
		$rows_project_details = $query_project_details->fetch();
		$category = $rows_project_details['projcategory'];

		/* var_dump("issue type: ".$issue_type);
		return; */

		if ($issue_type == 2) {
			//--------------------- Start Scope Adjustment ----------------------------
			$issuefields .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="adjust_scope" style="margin-bottom:10px">';

			$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
			$query_Sites->execute(array(":projid" => $projid));
			$rows_sites = $query_Sites->rowCount();
			if ($rows_sites > 0) {
				$counter = 0;
				while ($row_Sites = $query_Sites->fetch()) {
					$site_id = $row_Sites['site_id'];
					$site = $row_Sites['site'];
					$counter++;

					$issuefields .= '<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								SITE ' . $counter . ':' . $site . '
							</legend>';

					$query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
					$query_Site_Output->execute(array(":site_id" => $site_id));
					$rows_Site_Output = $query_Site_Output->rowCount();
					if ($rows_Site_Output > 0) {
						$output_counter = 0;
						while ($row_Site_Output = $query_Site_Output->fetch()) {
							$output_counter++;
							$output_id = $row_Site_Output['outputid'];
							$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
							$query_Output->execute(array(":outputid" => $output_id));
							$row_Output = $query_Output->fetch();
							$total_Output = $query_Output->rowCount();
							if ($total_Output) {
								$output_id = $row_Output['id'];
								$output = $row_Output['indicator_name'];

								$issuefields .= '<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#f0f0f0; border-radius:3px">
												OUTPUT ' . $output_counter . ' : ' . $output . '
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr>
																	<th style="width:4%">#</th>
																	<th style="width:40%">Sub-Task</th>
																	<th style="width:12%">Start Date</th>
																	<th style="width:12%">End Date</th>
																	<th style="width:12%">Unit of Measure</th>
																	<th style="width:10%">Additional Units</th>
																	<th style="width:10%">Additional Duration</th>
																</tr>
															</thead>
															<tbody>';

								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
								$query_rsMilestone->execute(array(":output_id" => $output_id));
								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
								if ($totalRows_rsMilestone > 0) {
									$tcounter = 0;
									while ($row_rsMilestone = $query_rsMilestone->fetch()) {
										$milestone = $row_rsMilestone['milestone'];
										$msid = $row_rsMilestone['msid'];
										$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
										$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id));
										$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
										if ($totalRows_rsTask_Start_Dates > 0) {
											$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;

											$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
											$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
											$totalRows_rsTasks = $query_rsTasks->rowCount();
											if ($totalRows_rsTasks > 0) {
												while ($row_rsTasks = $query_rsTasks->fetch()) {
													$tcounter++;
													$task_name = $row_rsTasks['task'];
													$task_id = $row_rsTasks['tkid'];
													$unit =  $row_rsTasks['unit_of_measure'];
													$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
													$query_rsIndUnit->execute(array(":unit_id" => $unit));
													$row_rsIndUnit = $query_rsIndUnit->fetch();
													$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
													$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

													$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
													$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id, ":subtask_id" => $task_id));
													$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
													$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
													$start_date = $end_date = $duration =  "";
													if ($totalRows_rsTask_Start_Dates > 0) {
														$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
														$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
														$duration = number_format($row_rsTask_Start_Dates['duration']);
														$pw_id = $row_rsTask_Start_Dates['id'];
														$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'subtask_id' => $task_id);

														$issuefields .= '<tr>
																							<td style="width:4%">' . $tcounter . '</td>
																							<td style="width:40%">' . $task_name . '</td>
																							<td style="width:12%">' . $start_date . '</td>
																							<td style="width:12%">' . $end_date . '</td>
																							<td style="width:12%">' . $unit_of_measure . '</td>
																							<td style="width:10%">
																								<input type="number" name="units[]" class="form-control" placeholder="Units" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																								<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																								<input type="hidden" name="siteid[]" value="' . $site_id . '" />
																								<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																							</td>
																							<td style="width:10%">
																								<input type="number" name="duration[]" class="form-control" placeholder="Days" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																							</td>
																						</tr>';
													}
												}
											}
										}
									}
								}

								$issuefields .= '</tbody>
														</table>
													</div>
												</div>
											</div>
										</fieldset>';
							}
						}
					}

					$issuefields .= '</fieldset>';
				}
			}

			$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 OR indicator_mapping_type=0) AND projid = :projid");
			$query_Output->execute(array(":projid" => $projid));
			$total_Output = $query_Output->rowCount();
			$outputs = '';
			if ($total_Output > 0) {
				$outputs = '';
				if ($total_Output > 0) {
					$counter = 0;

					while ($row_rsOutput = $query_Output->fetch()) {
						$output_id = $row_rsOutput['id'];
						$output = $row_rsOutput['indicator_name'];
						$counter++;
						$site_id = 0;

						$issuefields .= '<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									OUTPUT ' . $counter . ': ' . $output . '
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr>
														<th style="width:4%">#</th>
														<th style="width:40%">Sub-Task</th>
														<th style="width:12%">Start Date</th>
														<th style="width:12%">End Date</th>
														<th style="width:12%">Unit of Measure</th>
														<th style="width:10%">Additional Units</th>
														<th style="width:10%">Additional Duration</th>
													</tr>
												</thead>
												<tbody>';

						$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
						$query_rsMilestone->execute(array(":output_id" => $output_id));
						$totalRows_rsMilestone = $query_rsMilestone->rowCount();
						if ($totalRows_rsMilestone > 0) {
							$tcounter = 0;
							while ($row_rsMilestone = $query_rsMilestone->fetch()) {
								$milestone = $row_rsMilestone['milestone'];
								$msid = $row_rsMilestone['msid'];
								$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
								$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0));
								$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
								if ($totalRows_rsTask_Start_Dates > 0) {
									$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
									$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
									//$task_counter++;

									$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
									$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
									$totalRows_rsTasks = $query_rsTasks->rowCount();
									if ($totalRows_rsTasks > 0) {
										while ($row_rsTasks = $query_rsTasks->fetch()) {
											$tcounter++;
											$task_name = $row_rsTasks['task'];
											$task_id = $row_rsTasks['tkid'];
											$unit =  $row_rsTasks['unit_of_measure'];
											$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
											$query_rsIndUnit->execute(array(":unit_id" => $unit));
											$row_rsIndUnit = $query_rsIndUnit->fetch();
											$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
											$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

											$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
											$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0, ":subtask_id" => $task_id));
											$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
											$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
											$start_date = $end_date = $duration =  "";
											if ($totalRows_rsTask_Start_Dates > 0) {
												$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
												$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
												$duration = number_format($row_rsTask_Start_Dates['duration']);
												$pw_id = $row_rsTask_Start_Dates['id'];

												$issuefields .= '<tr>
																				<td style="width:4%">' . $tcounter . '</td>
																				<td style="width:40%">' . $task_name . '</td>
																				<td style="width:12%">' . $start_date . ' </td>
																				<td style="width:12%">' . $end_date . '</td>
																				<td style="width:12%">' . $unit_of_measure . '</td>
																				<td style="width:10%">
																					<input type="number" name="units[]" class="form-control" placeholder="Units" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																					<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																					<input type="hidden" name="siteid[]" value="0" />
																					<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																				</td>
																				<td style="width:10%">
																					<input type="number" name="duration[]" class="form-control" placeholder="Days" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																				</td>
																			</tr>';
											}
										}
									}
								}
							}
						}

						$issuefields .= '</tbody>
											</table>
										</div>
									</div>
								</div>
							</fieldset>';
					}
				}
			}

			$issuefields .= '</div>';

			$success = true;
			//--------------------- End Scope Adjustment ----------------------------

		} elseif ($issue_type == 3) {

			//--------------------- Start Schedule Adjustment ----------------------------

			$issuefields .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="adjust_schedule" style="margin-bottom:10px">';

			$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
			$query_Sites->execute(array(":projid" => $projid));
			$rows_sites = $query_Sites->rowCount();
			if ($rows_sites > 0) {
				$counter = 0;
				while ($row_Sites = $query_Sites->fetch()) {
					$site_id = $row_Sites['site_id'];
					$site = $row_Sites['site'];
					$counter++;

					$issuefields .= '<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								SITE ' . $counter . ' : ' . $site . '
							</legend>';

					$query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
					$query_Site_Output->execute(array(":site_id" => $site_id));
					$rows_Site_Output = $query_Site_Output->rowCount();
					if ($rows_Site_Output > 0) {
						$output_counter = 0;
						while ($row_Site_Output = $query_Site_Output->fetch()) {
							$output_counter++;
							$output_id = $row_Site_Output['outputid'];
							$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
							$query_Output->execute(array(":outputid" => $output_id));
							$row_Output = $query_Output->fetch();
							$total_Output = $query_Output->rowCount();
							if ($total_Output) {
								$output_id = $row_Output['id'];
								$output = $row_Output['indicator_name'];

								$issuefields .= '<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#f0f0f0; border-radius:3px">
												OUTPUT ' . $output_counter . ' : ' . $output . '
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr>
																	<th style="width:4%">#</th>
																	<th style="width:40%">Sub-Task</th>
																	<th style="width:12%">Start Date</th>
																	<th style="width:12%">End Date</th>
																	<th style="width:12%">Unit of Measure</th>
																	<th style="width:10%">Current Duration</th>
																	<th style="width:10%">Additional Duration</th>
																</tr>
															</thead>
															<tbody>';

								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
								$query_rsMilestone->execute(array(":output_id" => $output_id));
								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
								if ($totalRows_rsMilestone > 0) {
									$tcounter = 0;
									while ($row_rsMilestone = $query_rsMilestone->fetch()) {
										$milestone = $row_rsMilestone['milestone'];
										$msid = $row_rsMilestone['msid'];
										$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
										$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id));
										$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
										if ($totalRows_rsTask_Start_Dates > 0) {
											$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;

											$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
											$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
											$totalRows_rsTasks = $query_rsTasks->rowCount();
											if ($totalRows_rsTasks > 0) {
												while ($row_rsTasks = $query_rsTasks->fetch()) {
													$tcounter++;
													$task_name = $row_rsTasks['task'];
													$task_id = $row_rsTasks['tkid'];
													$unit =  $row_rsTasks['unit_of_measure'];
													$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
													$query_rsIndUnit->execute(array(":unit_id" => $unit));
													$row_rsIndUnit = $query_rsIndUnit->fetch();
													$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
													$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

													$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
													$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id, ":subtask_id" => $task_id));
													$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
													$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
													$start_date = $end_date = $duration =  "";
													if ($totalRows_rsTask_Start_Dates > 0) {
														$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
														$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
														$duration = number_format($row_rsTask_Start_Dates['duration']);
														$pw_id = $row_rsTask_Start_Dates['id'];
														$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'subtask_id' => $task_id);

														$issuefields .= '<tr>
																							<td style="width:4%">' . $tcounter . '</td>
																							<td style="width:40%">' . $task_name . '</td>
																							<td style="width:12%">' . $start_date . '</td>
																							<td style="width:12%">' . $end_date . '</td>
																							<td style="width:12%">' . $unit_of_measure . '</td>
																							<td style="width:10%">
																								<input type="number" name="current_duration[]" class="form-control" readonly value="' . $duration . '" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																								<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																								<input type="hidden" name="siteid[]" value="' . $site_id . '" />
																								<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																							</td>
																							<td style="width:10%">
																								<input type="number" name="additional_duration[]" class="form-control" placeholder="Days" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																							</td>
																						</tr>';
													}
												}
											}
										}
									}
								}

								$issuefields .= '</tbody>
														</table>
													</div>
												</div>
											</div>
										</fieldset>';
							}
						}
					}

					$issuefields .= '</fieldset>';
				}
			}

			$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 OR indicator_mapping_type=0) AND projid = :projid");
			$query_Output->execute(array(":projid" => $projid));
			$total_Output = $query_Output->rowCount();
			$outputs = '';
			if ($total_Output > 0) {
				$outputs = '';
				if ($total_Output > 0) {
					$counter = 0;

					while ($row_rsOutput = $query_Output->fetch()) {
						$output_id = $row_rsOutput['id'];
						$output = $row_rsOutput['indicator_name'];
						$counter++;
						$site_id = 0;

						$issuefields .= '<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									OUTPUT ' . $counter . ': ' . $output . '
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr>
														<th style="width:4%">#</th>
														<th style="width:40%">Sub-Task</th>
														<th style="width:12%">Start Date</th>
														<th style="width:12%">End Date</th>
														<th style="width:12%">Unit of Measure</th>
														<th style="width:10%">Current Duration</th>
														<th style="width:10%">Additional Duration</th>
													</tr>
												</thead>
												<tbody>';

						$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
						$query_rsMilestone->execute(array(":output_id" => $output_id));
						$totalRows_rsMilestone = $query_rsMilestone->rowCount();
						if ($totalRows_rsMilestone > 0) {
							$tcounter = 0;
							while ($row_rsMilestone = $query_rsMilestone->fetch()) {
								$milestone = $row_rsMilestone['milestone'];
								$msid = $row_rsMilestone['msid'];
								$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
								$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0));
								$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
								if ($totalRows_rsTask_Start_Dates > 0) {
									$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
									$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
									//$task_counter++;

									$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
									$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
									$totalRows_rsTasks = $query_rsTasks->rowCount();
									if ($totalRows_rsTasks > 0) {
										while ($row_rsTasks = $query_rsTasks->fetch()) {
											$tcounter++;
											$task_name = $row_rsTasks['task'];
											$task_id = $row_rsTasks['tkid'];
											$unit =  $row_rsTasks['unit_of_measure'];
											$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
											$query_rsIndUnit->execute(array(":unit_id" => $unit));
											$row_rsIndUnit = $query_rsIndUnit->fetch();
											$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
											$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

											$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
											$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0, ":subtask_id" => $task_id));
											$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
											$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
											$start_date = $end_date = $duration =  "";
											if ($totalRows_rsTask_Start_Dates > 0) {
												$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
												$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
												$duration = number_format($row_rsTask_Start_Dates['duration']);
												$pw_id = $row_rsTask_Start_Dates['id'];

												$issuefields .= '<tr>
																				<td style="width:4%">' . $tcounter . '</td>
																				<td style="width:40%">' . $task_name . '</td>
																				<td style="width:12%">' . $start_date . '</td>
																				<td style="width:12%">' . $end_date . '</td>
																				<td style="width:12%">' . $unit_of_measure . '</td>
																				<td style="width:10%">
																					<input type="number" name="current_duration[]" value="' . $duration . '" class="form-control" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly />
																					<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																					<input type="hidden" name="siteid[]" value="0" />
																					<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																				</td>
																				<td style="width:10%">
																					<input type="number" name="additional_duration[]" class="form-control" placeholder="Days" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																				</td>
																			</tr>';
											}
										}
									}
								}
							}
						}

						$issuefields .= '</tbody>
											</table>
										</div>
									</div>
								</div>
							</fieldset>';
					}
				}
			}
			$issuefields .= '</div>';

			$success = true;
			//----------------- End Schedule Adjustment -----------------------

		} elseif ($issue_type == 4) {

			//----------------- Start Cost Adjustment -------------------------

			$issuefields .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="adjust_cost" style="margin-bottom:10px">';

			$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
			$query_Sites->execute(array(":projid" => $projid));
			$rows_sites = $query_Sites->rowCount();
			if ($rows_sites > 0) {
				$counter = 0;
				while ($row_Sites = $query_Sites->fetch()) {
					$site_id = $row_Sites['site_id'];
					$site = $row_Sites['site'];
					$counter++;

					$issuefields .= '<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
								SITE ' . $counter . ': ' . $site . '
							</legend>';

					$query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
					$query_Site_Output->execute(array(":site_id" => $site_id));
					$rows_Site_Output = $query_Site_Output->rowCount();
					if ($rows_Site_Output > 0) {
						$output_counter = 0;
						while ($row_Site_Output = $query_Site_Output->fetch()) {
							$output_counter++;
							$output_id = $row_Site_Output['outputid'];
							$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
							$query_Output->execute(array(":outputid" => $output_id));
							$row_Output = $query_Output->fetch();
							$total_Output = $query_Output->rowCount();
							if ($total_Output) {
								$output_id = $row_Output['id'];
								$output = $row_Output['indicator_name'];

								$issuefields .= '<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#f0f0f0; border-radius:3px">
												OUTPUT ' . $output_counter . ': ' . $output . '
											</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
															<thead>
																<tr>
																	<th style="width:4%">#</th>
																	<th style="width:40%">Sub-Task</th>
																	<th style="width:12%">Start Date</th>
																	<th style="width:12%">End Date</th>
																	<th style="width:12%">Unit of Measure</th>
																	<th style="width:10%">Current Unit Cost</th>
																	<th style="width:10%">Additional Unit Cost</th>
																</tr>
															</thead>
															<tbody>';

								$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
								$query_rsMilestone->execute(array(":output_id" => $output_id));
								$totalRows_rsMilestone = $query_rsMilestone->rowCount();
								if ($totalRows_rsMilestone > 0) {
									$tcounter = 0;
									while ($row_rsMilestone = $query_rsMilestone->fetch()) {
										$milestone = $row_rsMilestone['milestone'];
										$msid = $row_rsMilestone['msid'];
										$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
										$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id));
										$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
										if ($totalRows_rsTask_Start_Dates > 0) {
											$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;

											$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
											$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
											$totalRows_rsTasks = $query_rsTasks->rowCount();
											if ($totalRows_rsTasks > 0) {
												while ($row_rsTasks = $query_rsTasks->fetch()) {
													$tcounter++;
													$task_name = $row_rsTasks['task'];
													$task_id = $row_rsTasks['tkid'];
													$unit =  $row_rsTasks['unit_of_measure'];
													$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
													$query_rsIndUnit->execute(array(":unit_id" => $unit));
													$row_rsIndUnit = $query_rsIndUnit->fetch();
													$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
													$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

													if ($category == 1) {
														$query_subtask_price = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE subtask_id=:task_id");
														$query_subtask_price->execute(array(':task_id' => $task_id));
														$row_subtask_price = $query_subtask_price->fetch();
													} else {
														$query_subtask_price = $db->prepare("SELECT * FROM tbl_project_tender_details WHERE subtask_id=:task_id");
														$query_subtask_price->execute(array(':task_id' => $task_id));
														$row_subtask_price = $query_subtask_price->fetch();
													}
													$current_unit_cost = $row_subtask_price['unit_cost'];

													$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
													$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id, ":subtask_id" => $task_id));
													$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
													$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
													$start_date = $end_date = $duration =  "";
													if ($totalRows_rsTask_Start_Dates > 0) {
														$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
														$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
														$duration = number_format($row_rsTask_Start_Dates['duration']);
														$pw_id = $row_rsTask_Start_Dates['id'];
														$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'subtask_id' => $task_id);

														$issuefields .= '<tr>
																							<td style="width:4%">' . $tcounter . '</td>
																							<td style="width:40%">' . $task_name . '</td>
																							<td style="width:12%">' . $start_date . '</td>
																							<td style="width:12%">' . $end_date . '</td>
																							<td style="width:12%">' . $unit_of_measure . '</td>
																							<td style="width:10%">
																								<input type="number" name="current_unit_cost[]" value="' . $current_unit_cost . '" class="form-control" readonly style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																								<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																								<input type="hidden" name="siteid[]" value="' . $site_id . '" />
																								<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																							</td>
																							<td style="width:10%">
																								<input type="number" name="additional_unit_cost[]" class="form-control" placeholder="Cost" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																							</td>
																						</tr>';
													}
												}
											}
										}
									}
								}

								$issuefields .= '</tbody>
														</table>
													</div>
												</div>
											</div>
										</fieldset>';
							}
						}
					}

					$issuefields .= '</fieldset>';
				}
			}

			$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 OR indicator_mapping_type=0) AND projid = :projid");
			$query_Output->execute(array(":projid" => $projid));
			$total_Output = $query_Output->rowCount();
			$outputs = '';
			if ($total_Output > 0) {
				$outputs = '';
				if ($total_Output > 0) {
					$counter = 0;

					while ($row_rsOutput = $query_Output->fetch()) {
						$output_id = $row_rsOutput['id'];
						$output = $row_rsOutput['indicator_name'];
						$counter++;
						$site_id = 0;

						$issuefields .= '<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
									OUTPUT ' . $counter . ': ' . $output . '
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr>
														<th style="width:4%">#</th>
														<th style="width:40%">Sub-Task</th>
														<th style="width:12%">Start Date</th>
														<th style="width:12%">End Date</th>
														<th style="width:12%">Unit of Measure</th>
														<th style="width:10%">Current Unit Cost</th>
														<th style="width:10%">Additional Unit Cost</th>
													</tr>
												</thead>
												<tbody>';

						$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
						$query_rsMilestone->execute(array(":output_id" => $output_id));
						$totalRows_rsMilestone = $query_rsMilestone->rowCount();
						if ($totalRows_rsMilestone > 0) {
							$tcounter = 0;
							while ($row_rsMilestone = $query_rsMilestone->fetch()) {
								$milestone = $row_rsMilestone['milestone'];
								$msid = $row_rsMilestone['msid'];
								$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND complete=0");
								$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0));
								$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
								if ($totalRows_rsTask_Start_Dates > 0) {
									$edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
									$details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
									//$task_counter++;

									$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
									$query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
									$totalRows_rsTasks = $query_rsTasks->rowCount();
									if ($totalRows_rsTasks > 0) {
										while ($row_rsTasks = $query_rsTasks->fetch()) {
											$tcounter++;
											$task_name = $row_rsTasks['task'];
											$task_id = $row_rsTasks['tkid'];
											$unit =  $row_rsTasks['unit_of_measure'];
											$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
											$query_rsIndUnit->execute(array(":unit_id" => $unit));
											$row_rsIndUnit = $query_rsIndUnit->fetch();
											$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
											$unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

											$query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id AND complete=0");
											$query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0, ":subtask_id" => $task_id));
											$row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
											$totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
											$start_date = $end_date = $duration =  "";
											if ($totalRows_rsTask_Start_Dates > 0) {
												$start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
												$end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
												$duration = number_format($row_rsTask_Start_Dates['duration']);
												$pw_id = $row_rsTask_Start_Dates['id'];

												$issuefields .= '<tr>
																				<td style="width:4%">' . $tcounter . '</td>
																				<td style="width:40%">' . $task_name . '</td>
																				<td style="width:12%">' . $start_date . '</td>
																				<td style="width:12%">' . $end_date . '</td>
																				<td style="width:12%">' . $unit_of_measure . '</td>
																				<td style="width:10%">
																					<input type="number" name="current_unit_cost[]" value="' . $current_unit_cost . '" class="form-control" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly />
																					<input type="hidden" name="subtaskid[]" value="' . $task_id . '" />
																					<input type="hidden" name="siteid[]" value="0" />
																					<input type="hidden" name="pwid[]" value="' . $pw_id . '" />
																				</td>
																				<td style="width:10%">
																					<input type="number" name="additional_unit_cost[]" class="form-control" placeholder="Cost" style="height:30px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
																				</td>
																			</tr>';
											}
										}
									}
								}
							}
						}

						$issuefields .= '</tbody>
											</table>
										</div>
									</div>
								</div>
							</fieldset>';
					}
				}
			}

			$issuefields .= '</div>';

			$success = true;
			//------------------------- End Cost Adjustment -----------------------

		}else {

			//----------------- Start Cost Adjustment -------------------------
			/*
			$issuefields ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="adjust_cost" style="margin-bottom:10px">
				<label class="control-label">Other Issue Details *:</label>
				<div class="form-line">
					<textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter any other issue details here" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
				</div>
			</div>'; */
			//------------------------- End Cost Adjustment -----------------------
			$issuefields .= '';

			$success = true;

		}
		
		echo json_encode(array('success' => $success, 'issuefields' => $issuefields));
	}

	if (isset($_GET['get_project_issue_details'])) {
		$projid = $_GET['projid'];
		$issueid = $_GET['get_project_issue_details'];
		$success = false;

		$query_risk_details = $db->prepare("SELECT c.catid, c.category, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status, i.recommendation FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE i.id = :issueid and projid = :projid");
		$query_risk_details->execute(array(":issueid" => $issueid, ":projid" => $projid));
		$rows_risk_details = $query_risk_details->fetch();
		$totalrows_risk_details = $query_risk_details->rowCount();

		$query_project =  $db->prepare("SELECT projname, projcategory FROM tbl_projects WHERE projid=:projid");
		$query_project->execute(array(":projid" => $projid));
		$row_project = $query_project->fetch();
		$projname = $row_project["projname"];
		$projcategory = $row_project["projcategory"];

		$issues_body = $issues_header = '';
		if ($totalrows_risk_details > 0) {
			$success = true;

			$issueareaid = $rows_risk_details["issue_area"];
			$issue_category = $rows_risk_details["category"];
			$issue_description = $rows_risk_details["issue_description"];
			$impactid = $rows_risk_details["issue_impact"];
			$priorityid = $rows_risk_details["issue_priority"];
			$status_id = $rows_risk_details["status"];
			$issuedate = $rows_risk_details["date_created"];
			$recommendation = $rows_risk_details["recommendation"];
			$status = get_inspection_status($status_id);

			$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE id=:impactid");
			$query_risk_impact->execute(array(":impactid" => $impactid));
			$row_risk_impact = $query_risk_impact->fetch();
			$issue_impact = $row_risk_impact["description"];

			if ($priorityid == 1) {
				$issue_priority = "High";
			} elseif ($priorityid == 2) {
				$issue_priority = "Medium";
			} else {
				$issue_priority = "Low";
			}

			$issues_scope = '';
			$textclass = "";
			if ($issueareaid == 2) {
				$issue_area = "Scope";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					//$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="3">Sub-Task</th>
						<th>Requesting Units</th>
						<th>Additional Days</th>
						<th colspan="2">Additional Cost</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
						$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
						$timeline = $row_adjustments["timeline"] . " days";
						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="3">' . $subtask . '</td>
							<td>' . $units . ' </td>
							<td>' . $timeline . '</td>
							<td colspan="2">' . number_format($totalcost, 2) . ' </td>
						</tr>';
					}
				}
			} elseif ($issueareaid == 3) {
				$issue_area = "Schedule";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="5">Sub-Task</th>
						<th colspan="2">Additional Days</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
						$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
						$timeline = $row_adjustments["timeline"] . " days";
						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="5">' . $subtask . '</td>
							<td colspan="2">' . $timeline . '</td>
						</tr>';
					}
				}
			} else {
				$issue_area = "Cost";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					//$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, a.cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="3">Sub-Task</th>
						<th colspan="2">Measurement Unit</th>
						<th colspan="2">Additional Cost</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$unit =  $row_adjustments["unit"];
						$cost = $row_adjustments["cost"];

						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="3">' . $subtask . '</td>
							<td colspan="2">' . $unit . ' </td>
							<td colspan="2">' . number_format($cost, 2) . ' </td>
						</tr>';
					}
				}
			}
		}

		$issue_details  = '
        <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>Issue Details
                </legend>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Issue Description:</label>
					<div class="form-control" style="height: auto">' . $issue_description . '</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Issue Area:</label>
					<div class="form-control">' . $issue_area . '</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Issue Category:</label>
					<div class="form-control">' . $issue_category . '</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Issue Impact:</label>
					<div class="form-control">' . $issue_impact . '</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Issue Priority:</label>
					<div class="form-control">' . $issue_priority . '</div>
				</div>';
				
				if($issueareaid != 5){
					$issue_details.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
						<label class="control-label">Resolution Comments:</label>
						<div class="form-control" style="height: auto">' . $recommendation . '</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover js-basic-example">
								<thead>
									<h4 class="text-primary">Issue Details:</h4>
								</thead>
								<tbody>
									' . $issues_scope . '
								</tbody>
							</table>
						</div>
					</div>';
				}
            $issue_details.='</fieldset>';
		echo json_encode(array('success' => $success, 'projname' => $projname, 'issue_details' => $issue_details));
	}

	if (isset($_GET['get_closed_project_issue_details'])) {
		$issueid = $_GET['get_closed_project_issue_details'];
		$success = false;

		$query_risk_details = $db->prepare("SELECT c.catid, c.category, i.projid, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status, i.recommendation, i.closing_remarks FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE i.id = :issueid");
		$query_risk_details->execute(array(":issueid" => $issueid));
		$rows_risk_details = $query_risk_details->fetch();
		$totalrows_risk_details = $query_risk_details->rowCount();

		$issues_body = $issues_header = '';
		if ($totalrows_risk_details > 0) {
			$success = true;

			$projid = $rows_risk_details["projid"];
			$issueareaid = $rows_risk_details["issue_area"];
			$issue_category = $rows_risk_details["category"];
			$issue_description = $rows_risk_details["issue_description"];
			$impactid = $rows_risk_details["issue_impact"];
			$priorityid = $rows_risk_details["issue_priority"];
			$status_id = $rows_risk_details["status"];
			$issuedate = $rows_risk_details["date_created"];
			$recommendation = $rows_risk_details["recommendation"];
			$closing_remarks = $rows_risk_details["closing_remarks"];
			$status = get_inspection_status($status_id);

			$query_project =  $db->prepare("SELECT projname, projcategory FROM tbl_projects WHERE projid=:projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$projname = $row_project["projname"];
			$projcategory = $row_project["projcategory"];

			$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE id=:impactid");
			$query_risk_impact->execute(array(":impactid" => $impactid));
			$row_risk_impact = $query_risk_impact->fetch();
			$issue_impact = $row_risk_impact["description"];

			if ($priorityid == 1) {
				$issue_priority = "High";
			} elseif ($priorityid == 2) {
				$issue_priority = "Medium";
			} else {
				$issue_priority = "Low";
			}

			$issues_scope = '';
			$textclass = "";
			if ($issueareaid == 2) {
				$issue_area = "Scope";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					//$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="3">Sub-Task</th>
						<th>Requesting Units</th>
						<th>Additional Days</th>
						<th colspan="2">Additional Cost</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
						$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
						$timeline = $row_adjustments["timeline"] . " days";
						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="3">' . $subtask . '</td>
							<td>' . $units . ' </td>
							<td>' . $timeline . '</td>
							<td colspan="2">' . number_format($totalcost, 2) . ' </td>
						</tr>';
					}
				}
			} elseif ($issueareaid == 3) {
				$issue_area = "Schedule";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="5">Sub-Task</th>
						<th colspan="2">Additional Days</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
						$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
						$timeline = $row_adjustments["timeline"] . " days";
						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="5">' . $subtask . '</td>
							<td colspan="2">' . $timeline . '</td>
						</tr>';
					}
				}
			} else {
				$issue_area = "Cost";

				$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

				$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
				$query_site_id->execute(array(":issueid" => $issueid));

				$sites = 0;
				while ($rows_site_id = $query_site_id->fetch()) {
					$sites++;
					$site_id = $rows_site_id['site_id'];
					//$allsites = '';

					if ($site_id == 0) {
						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': Way Point Sub Tasks</th>
						</tr>';
					} else {
						$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
						$query_site->execute(array(":site_id" => $site_id));
						$row_site = $query_site->fetch();
						$site = $row_site['site'];

						$allsites = '<tr class="adjustments ' . $issueid . '" style="background-color:#a9a9a9">
							<th colspan="8">Site ' . $sites . ': ' . $site . '</th>
						</tr>';
					}

					$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, a.cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
					$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

					$issues_scope .= $allsites . '
					<tr class="adjustments ' . $issueid . '" style="background-color:#cccccc">
						<th>#</th>
						<th colspan="3">Sub-Task</th>
						<th colspan="2">Measurement Unit</th>
						<th colspan="2">Additional Cost</th>
					</tr>';
					$scopecount = 0;
					while ($row_adjustments = $query_adjustments->fetch()) {
						$scopecount++;
						$subtask = $row_adjustments["task"];
						$unit =  $row_adjustments["unit"];
						$cost = $row_adjustments["cost"];

						$issues_scope .= '<tr class="adjustments ' . $issueid . '" style="background-color:#e5e5e5">
							<td>' . $sites . '.' . $scopecount . '</td>
							<td colspan="3">' . $subtask . '</td>
							<td colspan="2">' . $unit . ' </td>
							<td colspan="2">' . number_format($cost, 2) . ' </td>
						</tr>';
					}
				}
			}
		}

		$issue_details  = '
        <fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
				<i class="fa fa-exclamation-circle" aria-hidden="true"></i>Issue Details
			</legend>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Issue Description:</label>
				<div class="form-control" style="height: auto">' . $issue_description . '</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Issue Area:</label>
				<div class="form-control">' . $issue_area . '</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Issue Category:</label>
				<div class="form-control">' . $issue_category . '</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Issue Impact:</label>
				<div class="form-control">' . $issue_impact . '</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Issue Priority:</label>
				<div class="form-control">' . $issue_priority . '</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Resolution Comments:</label>
				<div class="form-control" style="height: auto">' . $recommendation . '</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover js-basic-example">
						<thead>
							<h4 class="text-primary">Issue Details:</h4>
						</thead>
						<tbody>
							' . $issues_scope . '
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
				<label class="control-label">Closing Remarks:</label>
				<div class="form-control" style="height: auto">' . $closing_remarks . '</div>
			</div>
		</fieldset>';
		echo json_encode(array('success' => $success, 'projname' => $projname, 'closed_issue_details' => $issue_details));
	}

	if (isset($_POST['close_issue'])) {
		if (validate_csrf_token($_POST['csrf_token'])) {
			$success = false;
			$msg = "Update failed!";
			$closing_remarks = $_POST["closing_remarks"];
			$issueid = $_POST["issueid"];

			$query_issue_area = $db->prepare("SELECT issue_area FROM tbl_projissues WHERE id=:issueid");
			$query_issue_area->execute(array(":issueid" => $issueid));
			$row_issue_area = $query_issue_area->fetch();
			$issue_area = $row_issue_area['issue_area'];

			$status = $issue_area == 5 ? 1 : 7;

			$user_name = $_POST["user_name"];
			$current_date = date("Y-m-d");

			$query_insert = $db->prepare("UPDATE tbl_projissues SET closing_remarks=:closing_remarks, status=:status, closed_by=:user, date_closed=:date WHERE id=:issueid");
			$update_issue = $query_insert->execute(array(':closing_remarks' => $closing_remarks, ':status' => $status, ':user' => $user_name, ':date' => $current_date, ':issueid' => $issueid));

			if ($update_issue) {
				$success = true;
				$msg = "Data successfully updated";
			}
			echo json_encode(array('success' => $success, '$msg' => $msg));
		}
	}
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
