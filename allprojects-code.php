<?php 
try{
	if($rows_count > 0){
		$num =0;
		do { 				
			$num = $num+1; 
			$projID = $row_rsUpP['projid']; 				
			$currentStatus =  $row_rsUpP['projstatus'];
			$projcat = $row_rsUpP["projcategory"];
			
			
			$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = '$projID'");
			$query_rsProjissues->execute();		
			$totalRows_rsProjissues = $query_rsProjissues->rowCount();
				
			if($projcat == '2'){
				$query_rsContractDates =  $db->prepare("SELECT startdate, enddate FROM tbl_tenderdetails WHERE projid = '$projID'");
				$query_rsContractDates->execute();		
				$row_rsContractDates = $query_rsContractDates->fetch();
				$totalRows_rsContractDates = $query_rsContractDates->rowCount();
				
				if($totalRows_rsContractDates > 0){
					$pjstdate = date("d M Y",strtotime($row_rsContractDates["startdate"]));
					$pjendate = date("d M Y",strtotime($row_rsContractDates["enddate"]));
				}else{
					$pjstdate = date("d M Y",strtotime($row_rsUpP["sdate"]));
					$pjendate = date("d M Y",strtotime($row_rsUpP["edate"]));
				}
			}else{
				$pjstdate = date("d M Y",strtotime($row_rsUpP["sdate"]));
				$pjendate = date("d M Y",strtotime($row_rsUpP["edate"]));
			}			

			$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$projID'");
			$query_rsMlsProg->execute();		
			$row_rsMlsProg = $query_rsMlsProg->fetch();

			$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];
			$percent2 = round($prjprogress,2);

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
					
			if(empty($row_rsLastUpdate['lastupdate']) || $row_rsLastUpdate['lastupdate']==""){
				$projlastmn = "Not Monitored Yet";
			}else{
				$projlastmn = $row_rsLastUpdate['lastupdate'];
			}
							
	?>
			<tr  id="rows">
				<td><?php echo $num; ?></td>
				<td style="padding-right:0px; padding-left:0px; padding-top:0px">
					<div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
						<a href="#" onclick="javascript:GetProjDetails(<?php echo $row_rsUpP['projid']; ?>)" style="color:#FFF; font-weight:bold"><?php echo $row_rsUpP['projname']; ?></a>
					</div>
					<div style="padding:5px; font-size:11px">
						<b>Project Code:</b> <?php echo $row_rsUpP['projcode']; ?>
						<?php if(!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != ''){ ?>
							<br /><b>Project Cost:</b> Ksh.<?php echo $row_rsUpP['FORMAT(p.projcost, 2)']; ?><br /><b>Start Date:</b> <?php echo $row_rsUpP['sdate']; ?><br /><b>End Date: </b> <?php echo $row_rsUpP['edate']; ?>
						<?php } ?>
					</div>
				</td>
				<?php
					$row_sect = $row_rsUpP['projsector'];
					$row_progid = $row_rsUpP['progid'];
				
					$query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid='$row_progid'");
					$query_rsSect->execute();		
					$row_rsSector = $query_rsSect->fetch();
					$totalRows_rsSect = $query_rsSect->rowCount();

					$subcounty = $row_rsUpP['projcommunity'];
					$ward = $row_rsUpP['projlga'];
					$location = $row_rsUpP['projstate'];
					$fscyear=$row_rsUpP['projfscyear'];
					
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

					if(!empty($row_rsSubCounty['state'])){
					$projlocation = $row_rsSubCounty['state'].' '.$level1label.'; '.$row_rsWard['state'].' '.$level2label.'; '.$row_rsLocation['state'].' '.$level3label.'';
					}else
					{
						$projlocation = 'All Conservancies; All Ecosystems; All Forest Stations';
					}
				?>
				<td><?php echo $row_rsSector['sector']; ?></td>
				<?php if(!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != ''){ ?>
					<td style="padding-right:0px; padding-left:0px">
					<?php
						$sts = $row_rsUpP['projstatus'];
						$query_status = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$sts'");
						$query_status->execute();		
						$row_status = $query_status->fetch();
					
						if($sts == 3){
							echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}else if($sts == 1){
							echo '<button type="button" class="btn bg-grey waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}else if($sts == 4){
							echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">'.$row_status['statusname'].'</button>';
						}else if($sts == 11){
							echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}else if($sts == 5){
							echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}else if($sts == 0){ //"Unapproved"
							echo '<button type="button" class="btn bg-black waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}else if($sts == 2){
							echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">'.$row_status['statusname']. '</button>';
						}	
					?><br />
					<strong>
						<?php
						if ($percent2 < 100) {
							echo '
							<div class="progress" style="height:20px; font-size:10px; color:black">
								<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%; height:20px; font-size:10px; color:black">
								'.$percent2.'%
								</div>
							</div>';
						} 
						elseif ($percent2 ==100){
							echo '
							<div class="progress" style="height:20px; font-size:10px; color:black">
								<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%; height:20px; font-size:10px; color:black">
								'.$percent2.'%                                   
								</div>
							</div>';
						}
						?>
						</strong><br />
					</td>
					<td align="center">
						<a href="#" onclick="javascript:GetProjIssues(<?php echo $row_rsUpP['projid']; ?>)" style="color:#FF5722"><?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i> <font size="5px">'.$totalRows_rsProjissues.'</font>'; ?></a>
					</td>
					<input type="hidden" name="myprojid" id="myprojid" value="<?php echo $row_rsUpP['projid']; ?>">
					<td><a href="#" id="modal_map" class="modal_map" data-id="<?php echo $row_rsUpP['projid']; ?>" style="color:indigo"><?php echo $projlocation; ?></a></td>
				<?php } ?>
				<td><?php echo $row_FY['year']; ?></td>
				<?php if(!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != ''){ ?>
					<?php
					$pid = $row_rsUpP['projid'];
					$query_rsSDate = $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
					$query_rsSDate->execute();		
					$row_rsSDate = $query_rsSDate->fetch();
					
					$projstartdate = $row_rsSDate["projstartdate"];
					//$start_date = date_format($projstartdate, "Y-m-d");
					$current_date = date("Y-m-d");
					
					/* $prjid = $row_rsUpP['projid'];
					$query_rsMProgress = $db->prepare("SELECT tbl_projects.projid AS mypjid, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 5 THEN 1 END) AS `Completed`, COUNT(CASE WHEN tbl_milestone.status = 4 THEN 1 END) AS `In Progress`,   COUNT(CASE WHEN tbl_milestone.status = 3 THEN 1 END) AS `Pending`,   COUNT(tbl_milestone.status) AS 'Total Status', concat(round(COUNT(CASE WHEN tbl_milestone.status = 5 THEN 1 END)/COUNT(tbl_milestone.status) * 100 )) AS '%% Completed', concat(round(COUNT(CASE WHEN tbl_milestone.status = 4 THEN 1 END)/COUNT(tbl_milestone.status) * 100)) AS '%% In Progress', concat(round(COUNT(CASE WHEN tbl_milestone.status = 3 THEN 1 END)/COUNT(tbl_milestone.status) * 100)) AS '%% Pending' FROM tbl_projects  LEFT JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid WHERE tbl_projects.projid='$prjid'");
					$query_rsMProgress->execute();		
					$row_rsMProgress = $query_rsMProgress->fetch();
					$totalRows_rsMProgress = $query_rsMProgress->rowCount(); */
								
					if ($projstartdate <= $current_date && ($row_rsUpP['projstatus'] == 4)) {					
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" data-id="<?php echo $row_rsUpP['projid']; ?>" title="View this project's photos" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project can be evaluated" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; ?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect"  onclick="javascript:GetScorecard(<?php echo $row_rsUpP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($row_rsUpP['projstatus'] == 1) {			
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn bg-grey btn-block" data-toggle="tooltip" data-placement="left" title="This project does not have photos" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation this project now!" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available yet" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($projstartdate <= $current_date && $row_rsUpP['projstatus'] == 5) {				
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsUpP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; ?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsUpP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					//elseif ($projstartdate <= $current_date && $row_rsMyP['projstatus'] == "Pending") {	
					elseif ($row_rsUpP['projstatus'] == 3) {				
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info waves-effect" data-toggle="tooltip" data-placement="left" title="You can't view this project photos before its monitored" style="width:100%; margin-bottom:5px"> Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluate pending project" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for pending project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($projstartdate <= $current_date && $row_rsUpP['projstatus'] == 11) {			
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsUpP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; ?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsUpP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($row_rsUpP['projstatus'] == 6) {			
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect projphoto" data-toggle="tooltip" data-placement="left" title="View this project's photos" data-id="<?php echo $row_rsUpP['projid']; ?>" id="modal_button" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php //echo $row_rsMyP['projid']; ?>">Evaluate</a></button>-->
						<button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsUpP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($row_rsUpP['projstatus'] == 1) {			
					?>
						<td style="padding-right:0px; padding-left:0px"><button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't view this project photo before monitoring" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation this project now" style="width:100%; margin-bottom:5px">Evaluate</button>-->
						<button class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for this project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					}
					elseif ($row_rsUpP['projstatus'] == 2) {			
					?>
						<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't view photo for cancelled project" style="width:100%; margin-bottom:5px">Gallery</button>
						<!--<button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluation cancelled project" style="width:100%; margin-bottom:5px">Evaluate</a></button>-->
						<button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for cancelled project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
					<?php
					} ?>
					<td><?php echo $projlastmn; ?></td>
					<!--<td width="2%" id="formcells2"><div align="center"><a href="myprojectdash?projid=<?php //echo $row_rsUpP['projid']; ?>"><img src="images/preview.png" alt="view" title="View Details"/></a></div></td>-->
				</tr>
			<?php } 
		} while ($row_rsUpP = $query_rsUpP->fetch());
	}
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
echo $result;
}
?>