<?php 
try {
	//code...

?>
<div class="body">
	<div class="table-responsive">
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr class="bg-brown">
							<th style="width:3%">#</th>
							<th style="width:37%">Project Name</th>
							<th style="width:13%">Project Status</th>
							<th style="width:6%">Issues</th>
							<th style="width:15%">Project Start Date</th>
							<th style="width:15%">Project End Date</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$nm = 0;
						while ($row_issues = $query_issues->fetch()) {
							$nm = $nm + 1;
							$projid = $row_issues['projid'];
							$project = $row_issues['projname'];
							$projstatus = $row_issues['projstatus'];
							$issuesno = $row_issues['issues'];
							$projcategory = $row_issues['projcategory'];

							$query_analysedissues = $db->prepare("SELECT count(id) AS analysedissues FROM tbl_projissues WHERE projid='$projid' AND status=1");
							$query_analysedissues->execute();
							$count_analysedissues = $query_analysedissues->fetch();
							$analysedissues = $count_analysedissues["analysedissues"];

							$query_projstatus = $db->prepare("SELECT statusname, projstatus, projchangedstatus FROM tbl_projects p left join tbl_status s on s.statusid=p.projstatus WHERE projid='$projid'");
							$query_projstatus->execute();
							$row_projstatus = $query_projstatus->fetch();
							$projectstatus = $row_projstatus["statusname"];
							$currentprojstatus = $row_projstatus["projstatus"];
							$projchangedstatus = $row_projstatus["projchangedstatus"];

							$level = "Not Analysed";
							$style = 'style="background-color:#9E9E9E; color:#fff"';
							if (empty($projchangedstatus) || $projchangedstatus == '' || $currentprojstatus == 'On Hold') {
								if ($issuesno == $analysedissues) {
									$query_projriskscore = $db->prepare("SELECT MAX(score) AS maxscore FROM tbl_project_riskscore WHERE projid='$projid'");
									$query_projriskscore->execute();
									$row_projriskscore = $query_projriskscore->fetch();
									$maxiscore = $row_projriskscore["maxscore"];
									if ($maxiscore == 1) {
										$level = "Negligible";
										$style = 'style="background-color:#4CAF50; color:#fff"';
									} elseif ($maxiscore == 2) {
										$level = "Minor";
										$style = 'style="background-color:#CDDC39; color:#fff"';
									} elseif ($maxiscore == 3) {
										$level = "Moderate";
										$style = 'style="background-color:#FFEB3B; color:#000"';
									} elseif ($maxiscore == 4) {
										$level = "Significant";
										$style = 'style="background-color:#FF9800; color:#fff"';
									} elseif ($maxiscore == 5) {
										$level = "Severe";
										$style = 'style="background-color:#F44336; color:#fff"';
									}
									$actionlink = '<a onclick="javascript:CallRiskResponse(' . $projid . ')" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Risk Response"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true"></i></a>';
								} else {
									$level = "Not Analysed";
									$style = 'style="background-color:#9E9E9E; color:#fff"';
								}
							} elseif ($currentprojstatus == $projchangedstatus || $currentprojstatus == "Cancelled") {
								$level = "Addressed";
								$style = 'style="background-color:#795548; color:#fff"';
								$actionlink = '<a onclick="javascript:CallRiskResponseReport(' . $projid . ')" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Risk Response History Report"><i class="fa fa-folder fa-2x text-success" aria-hidden="true"></i></a>';
							}

							if ($projcategory == 1) {
								$query_projdates = $db->prepare("SELECT projstartdate AS prjstartdate, projenddate AS prjenddate FROM tbl_projects WHERE projid='$projid'");
							} else {
								$query_projdates = $db->prepare("SELECT startdate AS prjstartdate, enddate AS prjenddate FROM tbl_tenderdetails WHERE projid='$projid'");
							}
							$query_projdates->execute();
							$row_projdates = $query_projdates->fetch();
							$startdate = date("d M Y", strtotime($row_projdates["prjstartdate"]));
							$enddate = date("d M Y", strtotime($row_projdates["prjenddate"]));
						?>
							<tr style="background-color:#eff9ca">
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $project; ?></td>
								<td><?php echo $projectstatus; ?></td>
								<td <?php echo $style; ?> align="center"><?php echo $level; ?></td>
								<td align="center">
									<a href="#" onclick="javascript:GetProjIssues(<?php echo $projid; ?>)"><span class="badge bg-purple"><?php echo $issuesno; ?></a></span></a>
								</td>
								<td><?php echo $startdate; ?></td>
								<td><?php echo $enddate; ?></td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>