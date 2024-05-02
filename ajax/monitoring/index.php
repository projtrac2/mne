<?php
try {
	include '../controller.php';

	if (isset($_POST['impact_details'])) {
		$impactindid = $_POST['impact_id'];
		$query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid=:indid ");
		$query_indicator->execute(array(":indid" => $impactindid));
		$row_indicator = $query_indicator->fetch();
		$total_indicator = $query_indicator->rowCount();

		if ($total_indicator > 0) {
			$unitid = $row_indicator['indicator_unit'];
			$impactIndicator = $row_indicator['indicator_name'];
			$calcid = $row_indicator['indicator_calculation_method'];

			$query_indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
			$query_indicator_cal->execute(array(':calcid' => $calcid));
			$row_cal = $query_indicator_cal->fetch();
			$impact_calc_method = $row_cal['method'];

			$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
			$query_Indicator->execute(array(":unit" => $unitid));
			$row = $query_Indicator->fetch();
			$unitofmeasure = $row['unit'];

			echo json_encode(array('success' => true, "impactIndicator" => $impactIndicator, "impact_calc_method" => $impact_calc_method, "impactunitofmeasure" => $unitofmeasure));
		} else {
			echo json_encode(array('success' => false));
		}
	}

	if (isset($_POST['outcome_details'])) {
		$outcomeindid = $_POST['outcome_id'];
		$query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid=:indid ");
		$query_indicator->execute(array(":indid" => $outcomeindid));
		$row_indicator = $query_indicator->fetch();
		$total_indicator = $query_indicator->rowCount();

		if ($total_indicator > 0) {
			$unitid = $row_indicator['indicator_unit'];
			$ocindid = $row_indicator['indid'];
			$outcomeIndicator = $row_indicator['indicator_name'];
			$occalcid = $row_indicator['indicator_calculation_method'];

			$query_outcome_indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
			$query_outcome_indicator_cal->execute(array(':calcid' => $occalcid));
			$row_outcome_cal = $query_outcome_indicator_cal->fetch();
			$outcome_calc_method = $row_outcome_cal['method'];

			$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
			$query_Indicator->execute(array(":unit" => $unitid));
			$row = $query_Indicator->fetch();
			$unitofmeasure = $row['unit'];

			echo json_encode(array('success' => true, "outcomeIndicator" => $outcomeIndicator, "outcom_calc_method" => $outcome_calc_method, "outcomeunitofmeasure" => $unitofmeasure));
		} else {
			echo json_encode(array('success' => false));
		}
	}

	if (isset($_GET['check_task_progress'])) {
		$level3 = $_GET['level3'];
		$opid = $_GET['opid'];

		$query_checklist = $db->prepare("SELECT ckid, taskid, name FROM tbl_project_monitoring_checklist c INNER JOIN tbl_task t ON t.tkid = c.taskid WHERE t.outputid=:opid");
		$query_checklist->execute(array(":opid" => $opid));
		$row = $query_checklist->fetch();
		$totalRows_rsChk = $query_checklist->rowCount();

		$current_date = date("Y-m-d");
		$query_checkform = $db->prepare("SELECT sum(score) as score FROM tbl_project_monitoring_checklist_score c INNER JOIN tbl_task t ON t.tkid = c.taskid WHERE c.level3=:level3 AND t.outputid=:opid AND date=:current_date");
		$query_checkform->execute(array(":level3" => $level3, ":opid" => $opid, ":current_date" => $current_date));
		$row_rsscore = $query_checkform->fetch();
		$totalRows_checkform = $query_checkform->rowCount();

		$score = $totalRows_checkform > 0 ? $row_rsscore['score'] : 0;
		$total_score = $totalRows_rsChk > 0 ? (($score / $totalRows_rsChk) / 10) * 100 : 0;
		return ($total_score == 100) ? true : false;
	}

	function get_milestones($projid, $outputid)
	{
		global $db;
		$query_rsTasks =  $db->prepare("SELECT * FROM tbl_task WHERE outputid=:outputid");
		$query_rsTasks->execute(array(":outputid" => $outputid));
		$row_rsTasks = $query_rsTasks->fetchAll();
		$totalRows_rsTasks = $query_rsTasks->rowCount();
		return ($totalRows_rsTasks > 0) ? $row_rsTasks : false;
	}

	function check_milestones($milestone, $level3)
	{
		global $db;

		$miletone = true;
		$query_tkMs = $db->prepare("SELECT * FROM tbl_task WHERE msid=:msid");
		$query_tkMs->execute(array(":msid" => $milestone));
		$total_tkMs = $query_tkMs->rowCount();

		$taskcount = 0;
		while ($row_tkMs = $query_tkMs->fetch()) {
			$tkid = $row_tkMs["tkid"];
			$query_monitored_tkMs = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE taskid=:tkid AND level3=:level3 AND score=10");
			$query_monitored_tkMs->execute(array(":tkid" => $tkid, ":level3" => $level3));
			$total_monitored_tkMs = $query_monitored_tkMs->rowCount();
			if ($total_monitored_tkMs > 0) {
				$taskcount++;
			}
		}

		if ($total_tkMs == $taskcount) {
			$miletone = false;
		}
		return $miletone;
	}

	function get_tasks($outputid)
	{
		global $db;
		$query_rsTasks =  $db->prepare("SELECT * FROM tbl_task WHERE outputid=:outputid");
		$query_rsTasks->execute(array(":outputid" => $outputid));
		$row_rsTasks = $query_rsTasks->fetchAll();
		$totalRows_rsTasks = $query_rsTasks->rowCount();
		return ($totalRows_rsTasks > 0) ? $row_rsTasks : false;
	}

	function get_task_questions($task_id)
	{
		global $db;
		$query_rsTasks =  $db->prepare("SELECT * FROM `tbl_project_monitoring_checklist` WHERE taskid='$task_id' ");
		$query_rsTasks->execute();
		$row_rsTasks = $query_rsTasks->fetchAll();
		$totalRows_rsTasks = $query_rsTasks->rowCount();
		return ($totalRows_rsTasks > 0) ? $row_rsTasks : false;
	}

	function monitoring($task_id, $location_id, $question_id)
	{
		global $db;
		$query_rsMonitoring =  $db->prepare("SELECT * FROM `tbl_project_monitoring_checklist_score` WHERE taskid = '$task_id' AND checklistid = '$question_id' AND level3='$location_id' ORDER BY id DESC LIMIT 1");
		$query_rsMonitoring->execute();
		$row_rsMonitoring = $query_rsMonitoring->fetch();
		$totalRows_rsMonitoring = $query_rsMonitoring->rowCount();

		$score = 0;
		if ($totalRows_rsMonitoring > 0) {
			$score = !empty($row_rsMonitoring->score) ? $row_rsMonitoring->score : 0;
		}
		return $score;
	}

	function get_project_issues($projid, $location_id, $opid)
	{
		global $db;
		$query_rsIssues =  $db->prepare("SELECT * FROM `tbl_projissues` WHERE projid=:projid AND level3=:level3 AND opid=:opid AND status != 7");
		$query_rsIssues->execute(array(":projid" => $projid, ":level3" => $location_id, ":opid" => $opid));
		$row_rsIssues = $query_rsIssues->fetchAll();
		$totalRows_rsIssues = $query_rsIssues->rowCount();
		return ($totalRows_rsIssues > 0) ? false : true;
	}


	if (isset($_GET['validate_data'])) {
		$output_id = $_GET['output_id'];
		$location_id = $_GET['location_id'];
		$projid = $_GET['projid'];

		$tasks = get_tasks($outputid);
		$total_tasks = ($tasks) ?  count($tasks) : 0;
		$avarage_percentage_score = 0;

		foreach ($tasks as $task) { // tasks for one output
			$task_id = $task['tkid'];
			$questions = get_task_questions($task_id);
			$total_questions = ($questions) ? count($questions) : 0;
			$total_score = 0;
			foreach ($questions as $question) { // task question s
				$total_score += monitoring($task_id, $location_id, $question_id);
			}
			$avarage_percentage_score += (($total_score / $number_of_questions) / 10) * 100;
		}

		$output_avarage_percentage_score = $avarage_percentage_score / $total_tasks;
		$issues = get_project_issues($projid, $location_id, $output_id);

		echo ($output_avarage_percentage_score == 100 && $issues) ? true : false;
	}

	if (isset($_GET["get_milestones"])) {
		$projid = $_GET['projid'];
		$output_id = $_GET['output_id'];
		$monitoringlocation = $_GET['location'];

		$query_milestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid and outputid = :opid and (status = 4 or status = 11)");
		$query_milestones->execute(array(":projid" => $projid, ":opid" => $output_id));
		$total_rows_milestones = $query_milestones->rowCount();

		if ($total_rows_milestones > 0) {
			$milestonelist = '<option value="" selected="selected" class="selection">... Select Milestone...</option>';
			while ($row_milestones = $query_milestones->fetch()) {
				if (!empty($row_milestones["location"]) || $row_milestones["location"] != '') {
					$msid = $row_milestones['msid'];
					$milestone = $row_milestones['milestone'];
					$milestonelocation = explode(",", $row_milestones["location"]);

					$exist_milestone = check_milestones($msid, $monitoringlocation);

					if (in_array($monitoringlocation, $milestonelocation) && $exist_milestone) {
						$milestonelist .= '<option value="' . $msid . '">' . $milestone . '</option>';
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

	if (isset($_GET['get_tasks'])) {
		$projid = $_GET['projid'];
		$output_id = $_GET['output_id'];
		$level3 = $_GET['location'];
		$milestone = $_GET['milestone'];
		$pmtid = $_GET['pmtid'];
		$table_body = "";

		$query_moncount = $db->prepare("SELECT * FROM tbl_task t inner join tbl_task_progress p ON p.opid=t.outputid WHERE  projid = :projid and outputid = :opid ");
		$query_moncount->execute(array(":projid" => $projid, ":opid" => $output_id));
		$totalRows_moncount = $query_moncount->rowCount();

		if ($totalRows_moncount > 0) {
			$query_rsTaskPrg = $db->prepare("SELECT * FROM tbl_task_progress p inner join tbl_task t ON p.tkid=t.tkid WHERE formid=:formid and (status=4 OR status=11) ORDER BY t.tkid ASC");
			$query_rsTaskPrg->execute(array(':formid' => $pmtid));
		} else {
			$query_rsTaskPrg = $db->prepare("SELECT * FROM tbl_task WHERE projid = :projid AND outputid = :opid AND msid=:msid AND (status=4 OR status=11) ORDER BY msid ASC");
			$query_rsTaskPrg->execute(array(":projid" => $projid, ":opid" => $output_id, ":msid" => $milestone));
		}

		$row_rsTaskPrg = $query_rsTaskPrg->fetch();
		$totalRows_rsTaskPrg = $query_rsTaskPrg->rowCount();


		if ($totalRows_rsTaskPrg > 0) {
			$num = 0;
			do {
				$num = $num + 1;
				$tskid = $row_rsTaskPrg['tkid'];
				$tkmsid = $row_rsTaskPrg['msid'];
				$tsksts = $row_rsTaskPrg['status'];


				//$Prg = ($totalRows_moncount > 0)  ? $row_rsTaskPrg["progress"] : 0;

				$query_check_task_percentage = $db->prepare("SELECT sum(score) AS totalscore, COUNT(id) as ids FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and level3='$level3'");
				$query_check_task_percentage->execute();
				$row_task_percentage = $query_check_task_percentage->fetch();
				$totalscore = $row_task_percentage["totalscore"];
				$totalids = $row_task_percentage["ids"];

				$Prg = 0;
				if ($totalids > 0) {
					$Prg = (($totalscore / $totalids) / 10) * 100;
				}

				$query_tskstatus = $db->prepare("SELECT statusname FROM tbl_task_status WHERE statusid='$tsksts'");
				$query_tskstatus->execute();
				$row_tskstatus = $query_tskstatus->fetch();
				$tasksts = $row_tskstatus["statusname"];

				$query_tkMs = $db->prepare("SELECT milestone FROM tbl_milestone WHERE msid='$tkmsid' ORDER BY msid DESC LIMIT 1");
				$query_tkMs->execute();
				$row_tkMs = $query_tkMs->fetch();

				if ($Prg < 100) {
					$Testno = "'" . $pmtid . "'";
					$tskid = $row_rsTaskPrg['tkid'];

					$query_rsTest = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' ORDER BY id DESC LIMIT 1");
					$query_rsTest->execute();
					$row_rsTest = $query_rsTest->fetch();
					$tkid_form_id = $row_rsTest > 0 ? $row_rsTest["formid"] : "";

					$query_rsProgress = $db->prepare("SELECT sum(score) AS totalscore, COUNT(id) as id FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and level3='$level3' and formid='$tkid_form_id'");
					$query_rsProgress->execute();
					$row_rsProgress = $query_rsProgress->fetch();
					$task_progress_in_location = $row_rsProgress ?  $row_rsProgress["totalscore"] : 0;
					$task_progress_in_location_id = $row_rsProgress ?  $row_rsProgress["id"] : 0;
					$location_score = $task_progress_in_location > 0 ? round(($task_progress_in_location / ($task_progress_in_location_id * 10)) * 100, 2) : 0;


					$query_checklistscore = $db->prepare("SELECT sum(score) AS totalscore, COUNT(id) as id FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and level3='$level3' and formid='$pmtid'");
					$query_checklistscore->execute();
					$row = $query_checklistscore->fetch();
					$total_checklistscore = $row["totalscore"];
					$id = $row["id"];
					$percscore = '';

					$link = '<td><button type="button" class="btn bg-light-green waves-effect" onclick="GetTaskChecklist(' . $tskid . ',' . $Testno . ')" data-toggle="tooltip" data-placement="bottom" id="btn' . $tskid . '" title="Click here to see the checklist">Add Score</button></td>';
					if ($total_checklistscore > 0) {
						$percscore = round(($total_checklistscore / ($id * 10)) * 100, 2);
						$link = '<td><button type="button" class="btn bg-light-green waves-effect" onclick="GetTaskChecklist(' . $tskid . ',' . $Testno . ')" data-toggle="tooltip" data-placement="bottom" id="btn' . $tskid . '" title="Click here to see the checklist">Edit Score</button></td>';
					}

					$table_body .= '
				<tr id="rowlines">
					<td>' . $num . '</td>
					<td>' . $row_rsTaskPrg['task'] . '</td>
					<td>' . $row_tkMs['milestone'] . '</td>
					<td>' . $tasksts . '</td>
					<td>' . $location_score . "%" . '</td>
                    ' . $link . '
					<td>
					<input type="hidden" name="tskid[]" id="tskid" value="' . $tskid . '" />
					<input type="text" class="form-control tasks class_tskid" name="progress[]" id="' . $tskid . '" value="' . $percscore . '" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px; width:60px" />
					</td>
				</tr>';
				}
			} while ($row_rsTaskPrg = $query_rsTaskPrg->fetch());
		}

		$data = get_targets($level3, $output_id);
		$query_rsIssues = $db->prepare("SELECT * FROM `tbl_projissues` WHERE projid=:projid AND opid=:opid AND status <> 7");
		$query_rsIssues->execute(array(":projid" => $projid, ":opid" => $output_id));
		$total_issues = $query_rsIssues->rowCount();


		$issues = $total_issues > 0 ? "true" : "false";
		echo json_encode(array("success" => true, "message" => $table_body, "targets" => $data, "issues" => $issues));
	}

	if (isset($_POST['get_issues'])) {
		$projid = $_POST['projid'];
		$output_id = $_POST['output_id'];
		$level3 = $_POST['location'];
		$milestone = $_POST['milestone'];

		$query_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid and milestone_id= :milestone and opid = :opid and level3 = :level3");
		$query_issues->execute(array(":projid" => $projid, ":milestone" => $milestone, ":opid" => $output_id, ":level3" => $level3));
		$totalRows_issues = $query_issues->rowCount();

		$issueslist = '';
		if ($totalRows_issues > 0) {
			$sn = 0;
			while ($row = $query_issues->fetch()) {
				$sn++;
				$rskid = $row['risk_category'];
				$issue = $row['observation'];
				$statusid = $row['status'];
				if ($statusid == 1) {
					$status = "Pending";
				} elseif ($statusid == 2) {
					$status = "Analysis on going";
				} elseif ($statusid == 3) {
					$status = "Analysed";
				} elseif ($statusid == 4) {
					$status = "Escalated";
				} elseif ($statusid == 5) {
					$status = "Management Report";
				} elseif ($statusid == 6) {
					$status = "On Hold";
				} elseif ($statusid == 7) {
					$status = "Closed";
				}

				$query_issue_cat = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid = :rskid");
				$query_issue_cat->execute(array(":rskid" => $rskid));
				$row_cat = $query_issue_cat->fetch();
				$category = $row_cat["category"];

				$issueslist .= '<tr><td>' . $sn . '</td><td><div class="form-line">' . $category . '</div></td><td><div class="form-line">' . $issue . '</div></td><td><div class="form-line">' . $status . '</div></td></tr>';
			}
		} else {
			$issueslist .= '<tr><td colspan="4"><div class="form-line">No issues recorded for the selected milestone under the selected location!</div></td></tr>';
		}

		echo $issueslist;
	}


	if (isset($_POST['ckids'])) {
		$tskid = $_POST['tskscid'];
		$responsible = $_POST['user_name'];
		$lev3id = $_POST['lev3id'];
		$lev4id = (isset($_POST['lev4id'])) ? $level4 = $_POST['lev4id'] : null;

		for ($i = 0; $i < sizeof($_POST['ckids']); $i++) {
			$ckids = $_POST['ckids'][$i];
			$frmid = $_POST['frmid'][$i];
			$scores = $_POST['scores' . $ckids];

			$scoredate = date('Y-m-d');
			$query_checklistid = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklistid=:ckids and formid=:frmid AND responsible=:responsible ");
			$query_checklistid->execute(array(":ckids" => $ckids, ":frmid" => $frmid, ':responsible' => $responsible));
			$total_checklistid = $query_checklistid->rowCount();

			if ($total_checklistid == 0) {
				$statusquery = $db->prepare("INSERT INTO tbl_project_monitoring_checklist_score (taskid, checklistid, formid, score, level3, level4,responsible, date) VALUES (:taskid, :checklistid,  :formid, :score, :level3, :level4,:responsible,  :date)");
				$insertnot = $statusquery->execute(array(':taskid' => $tskid, ':checklistid' => $ckids, ':formid' => $frmid, ':score' => $scores, ":level3" => $lev3id, ':level4' => $lev4id, ':responsible' => $responsible, ':date' => $scoredate));
			} else {
				$sqlUpdate = $db->prepare("UPDATE tbl_project_monitoring_checklist_score SET score = :score WHERE checklistid =:checklistid and formid =:frmid");
				$sqlUpdate->execute(array(':score' => $scores, ':checklistid' => $ckids, ':frmid' => $frmid));
			}
		}

		$query_checklistscore = $db->prepare("SELECT sum(score) AS totalscore FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and formid='$frmid'");
		$query_checklistscore->execute();
		$row = $query_checklistscore->fetch();
		$total_checklistscore = $row["totalscore"];
		$percscore = round(($total_checklistscore / ($i * 10)) * 100, 2);
		echo json_encode($percscore);
	}


	function get_targets($level3, $opdetailsid)
	{
		global $db;
		$query_rslocation_targets = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputstate = :level3 AND outputid=:opdetailsid");
		$query_rslocation_targets->execute(array(":level3" => $level3, ":opdetailsid" => $opdetailsid));
		$Rows_rslocation_targets = $query_rslocation_targets->fetch();
		$totalRows_rslocation_targets = $query_rslocation_targets->rowCount();
		$output_target = $totalRows_rslocation_targets > 0 ? $Rows_rslocation_targets['total_target'] : 0;

		$query_rslocation_previous_measurement = $db->prepare("SELECT * FROM tbl_monitoring WHERE level3 = :level3 AND output_id=:opdetailsid ORDER BY mid DESC LIMIT 1");
		$query_rslocation_previous_measurement->execute(array(":level3" => $level3, ":opdetailsid" => $opdetailsid));
		$totalRows_rslocation_previous_measurement = $query_rslocation_previous_measurement->rowCount();
		$Rows_rslocation_previous_measurement = $query_rslocation_previous_measurement->fetch();
		$previous_measurement = $totalRows_rslocation_previous_measurement > 0 ? $Rows_rslocation_previous_measurement['progress'] : 0;

		$query_rslocation_cumulative_measurement = $db->prepare("SELECT SUM(progress) as total FROM tbl_monitoring WHERE level3 = :level3 AND output_id=:opdetailsid ");
		$query_rslocation_cumulative_measurement->execute(array(":level3" => $level3, ":opdetailsid" => $opdetailsid));
		$Rows_rslocation_cumulative_measurement = $query_rslocation_cumulative_measurement->fetch();
		$cumulative_measurement = $Rows_rslocation_cumulative_measurement['total'] != null ? $Rows_rslocation_cumulative_measurement['total'] : 0;

		$query_location_observation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE location = :level3 AND opid=:opdetailsid ORDER BY id DESC LIMIT 1");
		$query_location_observation->execute(array(":level3" => $level3, ":opdetailsid" => $opdetailsid));
		$rows_location_observation = $query_location_observation->fetch();
		$total_rows = $query_location_observation->rowCount();
		$previous_observation = $rows_location_observation ? $rows_location_observation['observation'] : "Nothing recorded previously!";

		return array("output_target" => $output_target,  'cumulative_measurement' => $cumulative_measurement, 'previous_measurement' => $previous_measurement, 'previous_observation' => $previous_observation);
	}
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
