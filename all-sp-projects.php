<?php
try {
	if ($rows_count > 0) {
		$num = 0;
		do {
			$num = $num + 1;
			$projID = $row_rsUpP['projid'];
			$currentStatus =  $row_rsUpP['projstatus'];
			$projcat = $row_rsUpP["projcategory"];
			$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = '$projID'");
			$query_rsProjissues->execute();
			$totalRows_rsProjissues = $query_rsProjissues->rowCount();

			if ($projcat == '2') {
				$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
				$query_rsContractDates->execute(array(":projid" => $projID));
				$row_rsContractDates = $query_rsContractDates->fetch();
				$totalRows_rsContractDates = $query_rsContractDates->rowCount();

				if ($totalRows_rsContractDates > 0) {
					$pjstdate = date("d M Y", strtotime($row_rsContractDates["startdate"]));
					$pjendate = date("d M Y", strtotime($row_rsContractDates["enddate"]));
				} else {
					$pjstdate = date("d M Y", strtotime($row_rsUpP["projstartdate"]));
					$pjendate = date("d M Y", strtotime($row_rsUpP["projenddate"]));
				}
				$projcost = number_format($row_rsContractDates['tenderamount'], 2);
			} else {
				$pjstdate = date("d M Y", strtotime($row_rsUpP["projstartdate"]));
				$pjendate = date("d M Y", strtotime($row_rsUpP["projenddate"]));
				$projcost = number_format($row_rsUpP['projcost'], 2);
			}

			$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$projID'");
			$query_rsMlsProg->execute();
			$row_rsMlsProg = $query_rsMlsProg->fetch();

			$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
			$percent2 = round($prjprogress, 2);

			$query_rsProjDetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid ='$projID'");
			$query_rsProjDetails->execute();
			$row_rsProjDetails = $query_rsProjDetails->fetch();

			$projstartdate = $row_rsProjDetails["projstartdate"];
			$penddate = $row_rsProjDetails["projenddate"];
			$pjstatus = $row_rsProjDetails["projstatus"];
			$pjtype = $row_rsProjDetails["projtype"];
			$projcode = $row_rsProjDetails["projcode"];
			$statususer = $row_rsProjDetails["user_name"];

			$current_date = date("Y-m-d");
			$statusdate = date("Y-m-d H:i:s");

			$query_LastUpdate = $db->prepare("SELECT DATE_FORMAT(dateadded,  '%d %M %Y' ) AS lastupdate FROM tbl_monitoring where mid = (select max(mid) from tbl_monitoring where projid='$projID')");
			$query_LastUpdate->execute();
			$row_rsLastUpdate = $query_LastUpdate->fetch();

			if (empty($row_rsLastUpdate['lastupdate']) || $row_rsLastUpdate['lastupdate'] == "") {
				$projlastmn = "Not Monitored Yet";
			} else {
				$projlastmn = $row_rsLastUpdate['lastupdate'];
			}

			$query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
			$query_dates->execute(array(":projid" => $projID));
			$row_dates = $query_dates->fetch();

			if ($row_dates['projcategory'] == 2) {
				$projcontractor = $row_dates['contractor_name'];
				$projcontractor_id = $row_dates['contrid'];
			} else {
				$projcontractor = "In House";
			}
?>
			<tr id="rows">
				<td><?php echo $num; ?></td>
				<td style="padding-right:0px; padding-left:0px; padding-top:0px">
					<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
						<a href="#" onclick="javascript:GetProjDetails(<?php echo $row_rsUpP['projid']; ?>)" style="color:#FFF; font-weight:bold"><?php echo $row_rsUpP['projname']; ?></a>
					</div>
					<div style="padding:5px; font-size:11px">
						<b>Project Code:</b> <?php echo $row_rsUpP['projcode']; ?>
						<?php if (!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != '') { ?>
							<br /><b>Project Cost:</b> Ksh.<?php echo $projcost; ?><br /><b>Start Date:</b> <?php echo $row_rsUpP['sdate']; ?><br /><b>End Date: </b> <?php echo $row_rsUpP['edate']; ?>
						<?php } ?>
						<br /><b>Implementer: </b>
						<font color="#4CAF50">
							<?php
							if ($projcontractor != "In House") {
							?>
								<a href="view-contractor-info.php?contrid=<?= $projcontractor_id ?>" style="color:#4CAF50"><?php echo $projcontractor; ?></a>
							<?php
							} else {
							?>
								<?php echo $projcontractor; ?>
						</font>
					<?php
							}
					?>
					</div>
				</td>
				<?php
				$row_progid = $row_rsUpP['progid'];

				$query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid='$row_progid'");
				$query_rsSect->execute();
				$row_rsSector = $query_rsSect->fetch();
				$totalRows_rsSect = $query_rsSect->rowCount();

				$subcounty = $row_rsUpP['projcommunity'];
				$ward = $row_rsUpP['projlga'];
				$location = $row_rsUpP['projstate'];
				$fscyear = $row_rsUpP['projfscyear'];

				$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$fscyear'");
				$query_FY->execute();
				$row_FY = $query_FY->fetch();
				$totalRows_rsFY = $query_FY->rowCount();

				$query_rsSubCounty = $db->prepare("SELECT state FROM tbl_state WHERE id = '$subcounty'");
				$query_rsSubCounty->execute();
				$row_rsSubCounty = $query_rsSubCounty->fetch();
				$totalRows_rsSubCounty = $query_rsSubCounty->rowCount();

				$query_rsWard = $db->prepare("SELECT state FROM tbl_state WHERE id = '$ward'");
				$query_rsWard->execute();
				$row_rsWard = $query_rsWard->fetch();
				$totalRows_rsWard = $query_rsWard->rowCount();

				$query_rsLocation = $db->prepare("SELECT state FROM tbl_state WHERE id = '$location'");
				$query_rsLocation->execute();
				$row_rsLocation = $query_rsLocation->fetch();
				$totalRows_rsLocation = $query_rsLocation->rowCount();

				if (!empty($row_rsSubCounty['state'])) {
					$projlocation = $row_rsSubCounty['state'] . ' ' . $level1label . '; ' . $row_rsWard['state'] . ' ' . $level2label . '; ' . $row_rsLocation['state'] . ' ' . $level3label . '';
				} else {
					$projlocation = 'All Conservancies; All Ecosystems; All Forest Stations';
				}
				?>
				<td><?php echo $row_rsSector['sector']; ?></td>
				<?php if (!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != '') { ?>
					<td style="padding-right:0px; padding-left:0px">
						<?php
						$sts = $row_rsUpP['projstatus'];
						$query_status = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$sts'");
						$query_status->execute();
						$row_status = $query_status->fetch();

						if ($sts == 3) {
							echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 1) {
							echo '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 4) {
							echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 11) {
							echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 5) {
							echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 0) { //"Unapproved"
							echo '<button type="button" class="btn bg-black waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						} else if ($sts == 2) {
							echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $row_status['statusname'] . '</button>';
						}
						?><br />
						<strong>
							<?php
							if ($percent2 < 100) {
								echo '
							<div class="progress" style="height:20px; font-size:10px; color:black">
								<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
								' . $percent2 . '%
								</div>
							</div>';
							} elseif ($percent2 == 100) {
								echo '
							<div class="progress" style="height:20px; font-size:10px; color:black">
								<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
								' . $percent2 . '%
								</div>
							</div>';
							}
							?>
						</strong><br />
					</td>
					<td align="center">
						<a href="#" onclick="javascript:GetProjIssues(<?php echo $row_rsUpP['projid']; ?>)" style="color:#FF5722"><?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i> <font size="5px">' . $totalRows_rsProjissues . '</font>'; ?></a>
					</td>
					<input type="hidden" name="myprojid" id="myprojid" value="<?php echo $row_rsUpP['projid']; ?>">
					<td>
						<a href="view-project-maps.php?projid=<?php echo $row_rsUpP['projid']; ?>" id="" class="" style="color:indigo"><?php echo $projlocation; ?></a>
					</td>
				<?php } ?>
				<td><?php echo $row_FY['year']; ?></td>
				<td><?php echo $projlastmn; ?></td>
				<td style="padding-right:0px; padding-left:0px">
					<?php
					$project_status = $row_rsUpP['projstatus'];
					if (!$project_status) {
					?>
						<a type="button" href="view-project-gallery.php?projid=<?= $row_rsUpP['projid']; ?>&orig=1" class="btn btn-info btn-block waves-effect" title="View this project's photos" id="view_images" style="width:100%; margin-bottom:5px">Gallery</a>
					<?php
					}
					?>
				</td>
			</tr>
<?php
		} while ($row_rsUpP = $query_rsUpP->fetch());
	}
} catch (PDOException $ex) {
	$result = flashMessage("An error occurred: " . $ex->getMessage());
	echo $result;
}
?>
