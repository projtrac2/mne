<?php
try {
	if ($rows_count > 0) {
		$sn = 0;
		do {
			$sn = $sn + 1;
			$projectID =  $row_rsMyP['projid'];
			$currentStatus =  $row_rsMyP['projstatus'];
			$projcat = $row_rsMyP["projcategory"];
			$currentdate = date("Y-m-d");
			$statusdate = date("Y-m-d H:i:s");

			$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = '$projectID'");
			$query_rsProjissues->execute();
			$totalRows_rsProjissues = $query_rsProjissues->rowCount();

			if ($projcat == '2') {
				$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = '$projectID'");
				$query_rsContractDates->execute();
				$row_rsContractDates = $query_rsContractDates->fetch();
				$totalRows_rsContractDates = $query_rsContractDates->rowCount();

				if ($totalRows_rsContractDates > 0) {
					$pjstdate = date("d M Y", strtotime($row_rsContractDates["startdate"]));
					$pjendate = date("d M Y", strtotime($row_rsContractDates["enddate"]));
				} else {
					$pjstdate = date("d M Y", strtotime($row_rsMyP["projstartdate"]));
					$pjendate = date("d M Y", strtotime($row_rsMyP["projenddate"]));
				}
				$projcost = number_format($row_rsContractDates['tenderamount'], 2);
			} else {
				$pjstdate = date("d M Y", strtotime($row_rsMyP["projstartdate"]));
				$pjendate = date("d M Y", strtotime($row_rsMyP["projenddate"]));
				$projcost = number_format($row_rsMyP['projcost'], 2);
			}

			$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$projectID'");
			$query_rsMlsProg->execute();
			$row_rsMlsProg = $query_rsMlsProg->fetch();

			$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

			$percent2 = round($prjprogress, 2);

			$query_rsProjDetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid ='$projectID'");
			$query_rsProjDetails->execute();
			$row_rsProjDetails = $query_rsProjDetails->fetch();

			$pstartdate = $pjstdate;
			$penddate = $pjendate;
			$pjstatus = $row_rsProjDetails["projstatus"];
			$pjtype = $row_rsProjDetails["projtype"];
			$projcode = $row_rsProjDetails["projcode"];
			$statususer = $row_rsProjDetails["user_name"];
			$myprjname = $row_rsProjDetails["projname"];
			$myWardID = $row_rsMyP['projlga'];
			$mySubCountyID = $row_rsMyP['projcommunity'];


			$myLoc = [];
			$mystates = explode(",", $row_rsMyP['projstate']);
			foreach ($mystates as $mystate) {
				$query_rsLoc = $db->prepare("SELECT parent,state FROM tbl_state WHERE id=:mystate");
				$query_rsLoc->execute(array(":mystate" => $mystate));
				$row_rsLoc = $query_rsLoc->fetch();
				$totalRows_rsLoc = $query_rsLoc->rowCount();
				$myLoc[] = $row_rsLoc['state'];
			}

			$myWard = [];
			$myWardIDs = explode(",", $myWardID);
			foreach ($myWardIDs as $WardID) {
				$query_rsWard = $db->prepare("SELECT parent,state FROM tbl_state WHERE id='$WardID'");
				$query_rsWard->execute();
				$row_rsWard = $query_rsWard->fetch();
				$totalRows_rsWard = $query_rsWard->rowCount();
				$myWard[] = $row_rsWard['state'];
			}

			$fscyear = $row_rsMyP['projfscyear'];
			$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$fscyear'");
			$query_FY->execute();
			$row_FY = $query_FY->fetch();
			$totalRows_rsFY = $query_FY->rowCount();

			$mySubCounty = [];
			$SubCounties = explode(",", $mySubCountyID);
			foreach ($SubCounties as $SubCounty) {
				$query_SC = $db->prepare("SELECT parent,state FROM tbl_state WHERE id='$SubCounty'");
				$query_SC->execute();
				$row_SC = $query_SC->fetch();
				$totalRows_rsSC = $query_SC->rowCount();
				$mySubCounty[] = $row_SC['state'];
			}

			if (count($SubCounties) == 1) {
				$level1 = implode(",", $mySubCounty) . ' ' . $level1label;
			} else {
				$level1 = implode(",", $mySubCounty) . ' ' . $level1labelplural;
			}

			if (count($myWardIDs) == 1) {
				$level2 = implode(",", $myWard) . ' ' . $level2label;
			} else {
				$level2 = implode(",", $myWard) . ' ' . $level2labelplural;
			}

			if (count($mystates) == 1) {
				$level3 = implode(",", $myLoc) . ' ' . $level3label;
			} else {
				$level3 = implode(",", $myLoc) . ' ' . $level3labelplural;
			}

			$myLocation = $level1 . '; ' . $level2 . '; ' . $level3;

			$prjid = $row_rsMyP['projid'];
			$query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid='$prjid'");
			$query_dates->execute();
			$row_dates = $query_dates->fetch();

			$now = time();
			$prjsdate = strtotime($row_dates['projstartdate']);
			$prjedate = strtotime($row_dates['projenddate']);
			$prjdatediff = $prjedate - $prjsdate;
			$prjnowdiff = $now - $prjsdate;
			//$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
			$prjtimelinerate = round(($prjnowdiff / $prjdatediff) * 100, 1);
			if ($prjtimelinerate > 100) :
				$prjtimelinerate = 100;
			else :
				$prjtimelinerate = $prjtimelinerate;
			endif;

			if ($row_dates['projcategory'] == 2) {
				$projcontractor = $row_dates['contractor_name'];
			} else {
				$projcontractor = "In House";
			}
?>
			<tr id="rows" style="padding-bottom:1px">
				<td><?php echo $sn; ?></td>
				<td style="padding-right:0px; padding-left:0px; padding-top:0px">
					<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
						<a href="myprojectdash.php?projid=<?php echo $row_rsMyP['projid']; ?>" style="color:#FFF; font-weight:bold"><?php echo $row_rsMyP['projname']; ?></a>
					</div>
					<div style="padding:5px; font-size:11px">
						<b>Project Code:</b> <?php echo $row_rsMyP['projcode']; ?>
						<br /><b>Project Cost:</b> Ksh.<?php echo $projcost; ?><br /><b>Start Date:</b> <?php echo $pjstdate; ?><br /><b>End Date: </b> <?php echo $pjendate; ?><br /><b>Implementer: </b>
						<font color="#4CAF50"><?php echo $projcontractor; ?></font>
					</div>
				</td>
				<td style="padding-right:0px; padding-left:0px">
					<?php
					$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
					$query_Projstatus->execute(array(":projstatus" => $row_rsMyP['projstatus']));
					$row_Projstatus = $query_Projstatus->fetch();
					$projstatus = $row_Projstatus["statusname"];

					if ($row_rsMyP['projstatus'] == 3) {
						echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 4) {
						echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 11) {
						echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 5) {
						echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 1) {
						echo '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 2) {
						echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $projstatus . '</button>';
					} else if ($row_rsMyP['projstatus'] == 6) {
						echo '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $projstatus . '</button>';
					}
					?><input type="hidden" id="scardprog" value="<?php echo $percent2; ?>">
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
				</td>
				<td align="center">
					<a href="#" onclick="javascript:GetProjIssues(<?php echo $row_rsMyP['projid']; ?>)" style="color:#FF5722"><?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i> <font size="5px">' . $totalRows_rsProjissues . '</font>'; ?></a>
				</td>
				<input type="hidden" name="myprojid" id="myprojid" value="<?php echo $row_rsMyP['projid']; ?>">
				<td><a href="#" id="modal_map" class="modal_map" data-id="<?php echo $row_rsMyP['projid']; ?>" style="color:indigo"><?php echo $myLocation; ?></a></td>
				<td><?php echo $row_FY['year']; ?></td>
				<td><strong><a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>" style="color:#2196F3"><?php echo $row_rsMyP['Total Status']; ?> Milestones</a></strong><br />
					<?php
					if ($pjstatus == 2) {
						if ($row_rsMyP['Cancelled'] == 0) {
						} else {
					?><span class="badge bg-brown-grey" style="margin-bottom:2px"><?php echo $row_rsMyP['Cancelled']; ?></span> Cancelled<br />
						<?php
						}
					} elseif ($pjstatus == 'On Hold') {
						if ($row_rsMyP['On Hold'] == 0) {
						} else {
						?><span class="badge bg-pink" style="margin-bottom:2px"><?php echo $row_rsMyP['On Hold']; ?></span> On Hold<br />
						<?php
						}
					} else {
						if ($row_rsMyP['Approved'] == 0) {
						} else {
						?><span class="badge bg-blue-grey" style="margin-bottom:2px"><?php echo $row_rsMyP['Approved']; ?></span> Approved<br />
						<?php
						}
						if ($row_rsMyP['Pending'] == 0) {
						} else {
						?><span class="badge bg-yellow" style="margin-bottom:2px"><?php echo $row_rsMyP['Pending']; ?></span> Pending<br />
						<?php
						}
						if ($row_rsMyP['In Progress'] == 0) {
						} else {
						?><span class="badge bg-blue" style="margin-bottom:2px"> <?php echo $row_rsMyP['In Progress']; ?></span> In Progress<br />
						<?php
						}
						if ($row_rsMyP['Behind Schedule'] == 0) {
						} else {
						?><span class="badge bg-deep-orange" style="margin-bottom:2px"><?php echo $row_rsMyP['Behind Schedule']; ?></span> Behind Schedule<br />
						<?php
						}
						if ($row_rsMyP['Completed'] == 0) {
						} else {
						?><span class="badge bg-green" style="margin-bottom:2px"><?php echo $row_rsMyP['Completed']; ?></span> Completed<br />
					<?php
						}
					}
					?>
				</td>
				<?php
				$query_rsMProgress = $db->prepare("SELECT p.projid AS mypjid, m.msid, COUNT(CASE WHEN m.status = 5 THEN 1 END) AS `Completed`, COUNT(CASE WHEN m.status = 4 THEN 1 END) AS `In Progress`, COUNT(CASE WHEN m.status = 3 THEN 1 END) AS `Pending`, COUNT(m.status) AS 'Total Status', concat(round(COUNT(CASE WHEN m.status = 5 THEN 1 END)/COUNT(m.status) * 100 )) AS '%% Completed', concat(round(COUNT(CASE WHEN m.status = 4 THEN 1 END)/COUNT(m.status) * 100)) AS '%% In Progress', concat(round(COUNT(CASE WHEN m.status = 3 THEN 1 END)/COUNT(m.status) * 100)) AS '%% Pending' FROM tbl_projects p LEFT JOIN tbl_milestone m ON p.projid=m.projid WHERE p.projid='$projectID'");
				$query_rsMProgress->execute();
				$row_rsMProgress = $query_rsMProgress->fetch();
				$totalRows_rsMProgress = $query_rsMProgress->rowCount();

				if ($pjstdate <= $currentdate && ($row_rsMyP['projstatus'] == 4)) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" data-id="<?php echo $row_rsMyP['projid']; ?>" title="View this project's photos" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project can be evaluated" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; 
																																																										?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($row_rsMyP['projstatus'] == 1) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn bg-grey btn-block" data-toggle="tooltip" data-placement="left" title="This project does not have photos" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation this project now!" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available yet" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($pjstdate <= $currentdate && $row_rsMyP['projstatus'] == 5) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsMyP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; 
																																																											?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				}
				//elseif ($projstartdate <= $current_date && $row_rsMyP['projstatus'] == "Pending") {	
				elseif ($row_rsMyP['projstatus'] == 3) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn bg-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't view this project photos before its monitored" style="width:100%; margin-bottom:5px"> Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluate pending project" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button type="button" class="btn bg-grey waves-effect" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for pending project" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($pjstdate <= $currentdate && $row_rsMyP['projstatus'] == 11) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsMyP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; 
																																																											?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($row_rsMyP['projstatus'] == 6) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsMyP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; 
																																																											?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($row_rsMyP['projstatus'] == 1) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't view this project photo before monitoring" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation this project now" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for this project" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				} elseif ($row_rsMyP['projstatus'] == 2) {
				?>
					<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't view photo for cancelled project" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluation cancelled project" style="width:100%; margin-bottom:5px">Evaluate</a></button>-->
						<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for cancelled project" style="width:100%; margin-bottom:5px">Scorecard</button>
					</td>
				<?php
				}
				if ($row_rsMyP['projstatus'] == 2 || $row_rsMyP['projstatus'] == 6) {
				?>
					<td style="margin-left:0px; margin-right:0px">
						<div align="right" id="formcells">
							<div align="center"></div>
						</div> | <div align="center"></div> | <div align="center"></div>
					</td>
				<?php
				} elseif ($row_rsMyP['projstatus'] !== 2 && $row_rsMyP['projstatus'] !== 6) {
				?>
					<td width="8%" style="margin-left:0px; margin-right:0px">
						<div align="right" id="formcells">
							<div align="center"><a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>"><img src="images/preview.png" alt="View Project Details" name="view" width="16" height="16" id="view" title="View Project Details" /></a></div>
						</div>
						<div align="center"></div>
						</div>
					</td>
				<?php
				}
				?>
			</tr>
<?php
		} while ($row_rsMyP = $query_rsMyP->fetch());
	}
} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>