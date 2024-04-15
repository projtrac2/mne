<?php
try {
	if ($count_rows_indProjs > 0) {
		$num = 0;
		do {
			$num++;
			$projid = $row_indProjs['projid'];
			$projectid = base64_encode("projid54321{$projid}");
			$projname =  $row_indProjs['projname'];
			$progid =  $row_indProjs['progid'];
			$projcode =  $row_indProjs['projcode'];
			$projcost =  $row_indProjs['projcost'];
			$projstage =  $row_indProjs['projstage'];
			$projstatus =  $row_indProjs['projstatus'];
			$location = explode(",", $row_indProjs['projlga']);
			$fscyear = $row_indProjs['projfscyear'];
			$row_progid = $row_indProjs['progid'];
			$project_start_date =  $row_indProjs['projstartdate'];
			$project_end_date =  $row_indProjs['projenddate'];
			$projcategory =  $row_indProjs['projcategory'];
			$percent2 =  $row_indProjs['progress'];


			$query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
			$query_rsTask_Start_Dates->execute(array(':projid' => $projid));
			$rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
			$total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

			if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
				$project_start_date =  $rows_rsTask_Start_Dates['start_date'];
				$project_end_date =  $rows_rsTask_Start_Dates['end_date'];
			} else {
				if ($projcategory == 2) {
					$query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
					$query_rsTender_start_Date->execute(array(':projid' => $projid));
					$rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
					$total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
					if ($total_rsTender_start_Date > 0) {
						$project_start_date =  $rows_rsTender_start_Date['startdate'];
						$project_end_date =  $rows_rsTender_start_Date['enddate'];
					}
				}
			}

			$query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid=:progid");
			$query_rsSect->execute(array(":progid" => $progid));
			$row_rsSector = $query_rsSect->fetch();
			$totalRows_rsSect = $query_rsSect->rowCount();

			$sector = $totalRows_rsSect > 0 ? $row_rsSector['sector'] : "";

			$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:fscyear");
			$query_FY->execute(array(":fscyear" => $fscyear));
			$row_FY = $query_FY->fetch();
			$totalRows_rsFY = $query_FY->rowCount();
			$financial_year = $totalRows_rsFY > 0 ? $row_FY['year'] : "";

			$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
			$query_rsProjissues->execute(array(":projid" => $projid));
			$totalRows_rsProjissues = $query_rsProjissues->rowCount();

			$query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
			$query_dates->execute(array(":projid" => $projid));
			$row_dates = $query_dates->fetch();

			$projcontractor = "In House";
			if ($row_dates['projcategory'] == 2) {
				$contractor = $row_dates['contractor_name'];
				$projcontractor_id = $row_dates['contrid'];
				$projcontractor_ids = base64_encode("projid54321{$projcontractor_id}");
				$projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_ids . '" style="color:#4CAF50">' . $contractor . '</a>';
			}


			$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
			$query_Projstatus->execute(array(":projstatus" => $projstatus));
			$row_Projstatus = $query_Projstatus->fetch();
			$total_Projstatus = $query_Projstatus->rowCount();
			$status = "";
			if ($total_Projstatus > 0) {
				$status_name = $row_Projstatus['statusname'];
				$status_class = $row_Projstatus['class_name'];
				$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
			}

			$project_progress = '
			<div class="progress" style="height:20px; font-size:10px; color:black">
				<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
					' . $percent2 . '%
				</div>
			</div>';
			if ($percent2 == 100) {
				$project_progress = '
				<div class="progress" style="height:20px; font-size:10px; color:black">
					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
					' . $percent2 . '%
					</div>
				</div>';
			}

			$locations = [];
			foreach ($location as $mystate) {
				$query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
				$query_rsLoc->execute(array(":mystate" => $mystate));
				$row_rsLoc = $query_rsLoc->fetch();
				$totalRows_rsLoc = $query_rsLoc->rowCount();
				$locations[] = $row_rsLoc['state'];
			}

			$query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE projid=:projid  ORDER BY id DESC LIMIT 1");
			$query_rsMonitoring_Achieved->execute(array(":projid" => $projid));
			$Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
			$totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();

			$projlastmn = $totalRows_rsMonitoring_Achieved > 0 ? date("d M Y", strtotime($Rows_rsMonitoring_Achieved['created_at'])) : '';
?>
			<tr id="rows">
				<td><?php echo $num; ?></td>
				<td style="padding-right:0px; padding-left:0px; padding-top:0px">
					<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
						<a href="project-dashboard?proj=<?=$projectid?>" style="color:#FFF; font-weight:bold"><?= $projname ?></a>
					</div>
					<div style="padding:5px; font-size:11px">
						<b>Project Code:</b> <?= $projcode ?><br />
						<b>Project Cost:</b> Ksh.<?= $projcost; ?><br />
						<b>Start Date:</b> <?= $project_start_date; ?><br />
						<b>End Date: </b> <?= $project_end_date; ?><br />
						<b>Implementer: </b>
						<font color="#4CAF50">
							<?= $projcontractor; ?>
						</font>
					</div>
				</td>
				<td><?= $sector ?></td>
				<td style="padding-right:0px; padding-left:0px">
					<?= $status  ?>
					<br />
					<strong>
						<?= $project_progress ?>
					</strong>
					<br />
				</td>
				<td align="center">
					<a href="#" onclick="javascript:GetProjIssues(<?= $projid ?>)" style="color:#FF5722">
						<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i>
						<font size="5px"><?= $totalRows_rsProjissues ?></font>
					</a>
				</td>
				<td>
					<a href="view-project-maps.php?projid=<?= $projectid ?>" id="" class="" style="color:indigo"><?= implode(",", $locations); ?></a>
				</td>
				<td><?= $financial_year ?></td>
				<td><?= $projlastmn ?></td>
				<td style="padding-right:0px; padding-left:0px">
					<a type="button" href="project-dashboard?proj=<?=$projectid?>" class="btn btn-success btn-block waves-effect" title="View this project's statistics" id="view_images" style="width:100%; margin-bottom:5px">Stats</a>
				</td>
			</tr>
			<input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>">
<?php
			} while ($row_indProjs = $query_indProjs->fetch());
		}
	} catch (PDOException $ex) {
		customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
	}
?>
