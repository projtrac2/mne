<?php
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../system-labels.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


if (isset($_POST['tskid'])) {
	$tkid = $_POST["tskid"];
	$frmid = $_POST["pmtid"];
	$milestone = $_POST["milestone"];
	$level3 = $_POST["level3"];
	$query_checklist = $db->prepare("SELECT ckid, taskid, name FROM tbl_project_monitoring_checklist WHERE taskid=:tkid");
	$query_checklist->execute(array(':tkid' => $tkid));
	$row = $query_checklist->fetch();
	$totalRows_rsChk = $query_checklist->rowCount();

	// milestone_id
	$query_rsIssues =  $db->prepare("SELECT * FROM `tbl_projissues` WHERE milestone_id=:milestone AND status != 7");
	$query_rsIssues->execute(array(":milestone" => $milestone));
	$row_rsIssues = $query_rsIssues->fetchAll();
	$totalRows_rsIssues = $query_rsIssues->rowCount();
	$issues = ($totalRows_rsIssues > 0) ? true : false;

	$query_rs_direct_cost = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :task_id AND inspection_status <> 2");
	$query_rs_direct_cost->execute(array(":task_id" => $tkid));
	$totalRows_rs_direct_cost = $query_rs_direct_cost->rowCount();
	$inspection_status = $totalRows_rs_direct_cost > 0 ? true : false;

	$task_checkist_body = '
	<tr id="rowlines">
		<td colspan="3" style="color:RED" align="center">NO CHECKLIST DEFINED!!!</td>
	</tr>';
	if ($totalRows_rsChk > 0) {
		$num = 0;
		$task_checkist_body = '';
		do {
			$checklistid = $row['ckid'];
			$query_checkform = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklistid=:checklistid and taskid=:tkid and formid=:frmid and level3=:level3");
			$query_checkform->execute(array(":checklistid" => $checklistid, ':tkid' => $tkid, ':frmid' => $frmid, ':level3' => $level3));
			$totalRows_checkform = $query_checkform->rowCount();

			if ($totalRows_checkform > 0) {
				$query_checklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid=:checklistid and taskid=:tkid and level3=:level3 and formid<>:frmid");
				$query_checklistscore->execute(array(":checklistid" => $checklistid, ':tkid' => $tkid, ':level3' => $level3, ':frmid' => $frmid));
				$row_checklistscore = $query_checklistscore->fetch();
				$totalRows_checklistscore = $query_checklistscore->rowCount();
				$total_checklistscore = $row_checklistscore["score"];

				$scoreoptions = '';
				do {
					$query_editchecklistscore = $db->prepare("SELECT score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and level3='$level3' and formid = '$frmid' ");
					$query_editchecklistscore->execute();
					$row_editchecklistscore = $query_editchecklistscore->fetch();
					$totalRows_editchecklistscore = $query_editchecklistscore->rowCount();

					if (is_null($row_checklistscore["score"])) {
						$max_checklistscore = 0;
					} else {
						$max_checklistscore = $row_checklistscore["score"];
					}

					for ($i = $max_checklistscore; $i <= 10; $i++) {
						if ($row_editchecklistscore["score"] == $i) {
							if (($inspection_status  || $issues) && $i == 10) {
							} else {
								$scoreoptions .= '<option value="' . $row_editchecklistscore["score"] . '" selected="selected" class="selection">' . $row_editchecklistscore["score"] . '</option>';
							}
						} else {
							if (($inspection_status || $issues) && $i == 10) {
							} else {
								$scoreoptions .= '<option value="' . $i . '">' . $i . '</option>';
							}
						}
					}
				} while ($row_checklistscore = $query_checklistscore->fetch());

				if ($total_checklistscore < 10) {
					$num = $num + 1;
					$task_checkist_body .= '
					<tr id="rowlines">
						<td>' . $num . '</td>
						<td>' . $row['name'] . '</td>
						<td align="center">
							<div align="center">									
								<div class="form-line" align="center">
									<select name="scores' . $row['ckid'] . '" id="md_checkbox1_' . $row['ckid'] . '" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" align="center" required>
										' . $scoreoptions . '
									</select>
								</div>
							</div>
						</td>
						<input type="hidden" name="ckids[]" id="checklistid" value="' . $row['ckid'] . '"/>
						<input type="hidden" name="frmid[]" id="checklistid" value="' . $frmid . '"/>
					</tr>';
				} else {
					$query_finalchecklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and level3='$level3' and formid <> '$frmid'");
					$query_finalchecklistscore->execute();
					$row_finalchecklistscore = $query_finalchecklistscore->fetch();
					$totalRows_finalchecklistscore = $query_finalchecklistscore->rowCount();
					$num = $num + 1;
					$task_checkist_body .= '
					<tr id="rowlines">
						<td>' . $num . '</td>
						<td>' . $row['name'] . '</td>
						<td align="center">
							<div align="center">									
								<div class="form-line" align="center">
									<input type="text" name="scores' . $row['ckid'] . '" id="md_checkbox1_' . $row['ckid'] . '" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" value="' . $row_finalchecklistscore["score"] . '" align="center" readonly>
								</div>
							</div>
						</td>
						<input type="hidden" name="ckids[]" id="checklistid" value="' . $row['ckid'] . '"/>
						<input type="hidden" name="frmid[]" id="checklistid" value="' . $frmid . '"/>
					</tr>';
				}
			} else {
				$query_checklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and level3='$level3' and formid <> '$frmid'");
				$query_checklistscore->execute();
				$row_checklistscore = $query_checklistscore->fetch();
				$totalRows_checklistscore = $query_checklistscore->rowCount();
				$total_checklistscore = $row_checklistscore["score"];
				$scoreoptions = '';

				// do{
				if (is_null($row_checklistscore["score"])) {
					$max_checklistscore = 0;
				} else {
					$max_checklistscore = $row_checklistscore["score"];
				}

				$scoreoptions .= '<option value="" selected="selected" class="selection">Add Score</option>';
				for ($i = $max_checklistscore; $i <= 10; $i++) {
					if (($inspection_status || $issues) && $i == 10) {
					} else {
						$scoreoptions .= '<option value="' . $i . '">' . $i . '</option>';
					}
				}

				if ($total_checklistscore < 10) {
					$num = $num + 1;
					$task_checkist_body .= '
					<tr id="rowlines">
						<td>' . $num . '</td>
						<td>' . $row['name'] . '</td>
						<td align="center">
							<div align="center">									
								<div class="form-line" align="center">
									<select name="scores' . $row['ckid'] . '" id="md_checkbox1_' . $row['ckid'] . '" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" align="center" required>
										' . $scoreoptions . '
									</select>
								</div>
							</div>
						</td>
						<input type="hidden" name="ckids[]" id="checklistid" value="' . $row['ckid'] . '"/>
						<input type="hidden" name="frmid[]" id="checklistid" value="' . $frmid . '"/>
					</tr>';
				} else {
					$query_finalchecklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and level3='$level3' and formid <> '$frmid'");
					$query_finalchecklistscore->execute();
					$row_finalchecklistscore = $query_finalchecklistscore->fetch();
					$totalRows_finalchecklistscore = $query_finalchecklistscore->rowCount();

					$num = $num + 1;
					$task_checkist_body .= '
					<tr id="rowlines">
						<td>' . $num . '</td>
						<td>' . $row['name'] . '</td>
						<td align="center">
							<div align="center">									
								<div class="form-line" align="center">
									<input type="text" name="scores' . $row['ckid'] . '" id="md_checkbox1_' . $row['ckid'] . '" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" value="' . $row_finalchecklistscore["score"] . '" align="center" readonly>
								</div>
							</div>
						</td>
						<input type="hidden" name="ckids[]" id="checklistid" value="' . $row['ckid'] . '"/>
						<input type="hidden" name="frmid[]" id="checklistid" value="' . $frmid . '"/>
					</tr>';
				}
			}
		} while ($row = $query_checklist->fetch());
	}

	$task_checkist = '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="body">
					<div class="row clearfix"> 
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover dataTable js-exportable">
									<thead>
										<tr style="background-color:#607D8B; color:#FFF">
											<td width="3%">SN</td>
											<td width="80%">Checklist</td>
											<td width="17%">Progress Score</td>
										</tr>
									</thead>
									<tbody> 
									<input type="hidden" name="tskscid" id="tskscid" value="' . $tkid . '"/> 
									' . $task_checkist_body . '
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	echo $task_checkist;
}

if (isset($_POST['delete'])) {
	$formid = $_POST['formid'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist_score` WHERE formid=:formid");
	$results = $deleteQuery->execute(array(':formid' => $formid));
}
