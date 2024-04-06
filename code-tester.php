
					<?php 
                try{
					do { 				
						$projectID =  $row_rsMyP['projid']; 				
						$currentStatus =  $row_rsMyP['projstatus'];

						$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$projectID'");
						$query_rsMlsProg->execute();		
						$row_rsMlsProg = $query_rsMlsProg->fetch();

						$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

						$percent2 = round($prjprogress,2);

						$query_rsProjDetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid ='$projectID'");
						$query_rsProjDetails->execute();		
						$row_rsProjDetails = $query_rsProjDetails->fetch();
										
						$pstartdate = $row_rsProjDetails["projstartdate"];
						$penddate = $row_rsProjDetails["projenddate"];
						$pjstatus = $row_rsProjDetails["projstatus"];
						$pjtype = $row_rsProjDetails["projtype"];
						$projcode = $row_rsProjDetails["projcode"];
						$statususer = $row_rsProjDetails["user_name"];

						$currentdate = date("Y-m-d");
						$statusdate = date("Y-m-d H:i:s");

						$query_rsProjStatus = $db->prepare("SELECT COUNT(CASE WHEN status = 'In Progress' THEN 1 END) AS `Milestone In Progress`, COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS `Completed Milestone`,   COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS `Pending Milestone`,   COUNT(CASE WHEN status = 'Overdue' THEN 1 END) AS `Overdue`,   COUNT(CASE WHEN status = 'Behind Schedule' THEN 1 END) AS `Behind Schedule`,   COUNT(CASE WHEN status = 'Approved' THEN 1 END) AS `Approved`,  COUNT(status) AS 'Total Milestone', MIN(sdate) AS SmallestDate, MAX(edate) AS BiggestDate FROM tbl_milestone WHERE projid ='$projectID'");
						$query_rsProjStatus->execute();		
						$row_rsProjStatus = $query_rsProjStatus->fetch(); 

						//Below milestone status
						$Pending =  $row_rsProjStatus['Pending Milestone']; //counting number of occurence
						$Approved =  $row_rsProjStatus['Approved']; //counting number of occurence
						$Inprogress =  $row_rsProjStatus['Milestone In Progress']; //counting number of occurence
						$complete =  $row_rsProjStatus['Completed Milestone']; //counting number of occurence
						$BehindSchedule =  $row_rsProjStatus['Behind Schedule']; //counting number of occurence
						$Overdue =  $row_rsProjStatus['Overdue']; //counting number of occurence
						$projtotal = $row_rsProjStatus['Total Milestone']; //Total of all status
						$SmallestStartDate = $row_rsProjStatus["SmallestDate"];
						$BiggestEndDate = $row_rsProjStatus["BiggestDate"];
						//$percent1 = 0;
						$percentage = round(($complete/$projtotal) * 100,1);


						if($pjstatus == 'Cancelled' || $pjstatus == 'On Hold')
						{
							$projStatus = $pjstatus;
							$subject = "Project ".$projStatus;
							$message = "Project with project code. ".$projcode." is ".$projStatus;

							$origin = "Project";
							if(!empty($subject) || $subject !== ''){
								
								$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid  = '$projectID' AND status = '$projStatus'");
								$query_rsNotification->execute();		
								$row_rsNotification = $query_rsNotification->fetch();
								$numRows_rsNot = $query_rsNotification->rowCount();
								$stsid = $row_rsNotification["id"];
									
								if($numRows_rsNot == 0){
									//create SQL insert statement			  
									$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) 
												  VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
															  
									$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, 
															  ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
								}
								else{
									$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
									$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));
								}
							}
						}
						else{
							if($projtotal > 0){
								if($percentage == 100){
									$projStatus = "Completed";
									$subject = "Project Complete";
									$message = "Project with project code. ".$projcode." is complete";

									$origin = "Project";
									if(!empty($subject) || $subject !== ''){								
										$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid  = '$projectID' AND status = '$projStatus'");
										$query_rsNotification->execute();		
										$row_rsNotification = $query_rsNotification->fetch();
										$numRows_rsNot = $query_rsNotification->rowCount();
										$stsid = $row_rsNotification["id"];
											
										if($numRows_rsNot == 0){
											//create SQL insert statement			  
											$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) 
														  VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																	  
											$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, 
															  ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
										}
										else{
											$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
											$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
										}
									}
								}
								elseif($Overdue == $projtotal){
									$projStatus = "Overdue";
									$subject = "Project Overdue";
									$message = "Project with project code. ".$projcode." is overdue";

									$origin = "Project";
									if(!empty($subject) || $subject !== ''){
										$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid  = '$projectID' AND status = '$projStatus'");
										$query_rsNotification->execute();		
										$row_rsNotification = $query_rsNotification->fetch();
										$numRows_rsNot = $query_rsNotification->rowCount();
										$stsid = $row_rsNotification["id"];
												
										if($numRows_rsNot == 0){
											//create SQL insert statement			  
											$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) 
														  VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																	  
											$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, 
															  ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
										}
										else{
											$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
											$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
										}
									}
								}
								elseif($Overdue > 0){
									if($currentdate > $penddate){
										$projStatus = "Overdue";
										$subject = "Project Overdue";
										$message = "Project with project code. ".$projcode." is overdue";
										$origin = "Project";
										if(!empty($subject) || $subject !== ''){
											$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid  = '$projectID' AND status = '$projStatus'");
											$query_rsNotification->execute();		
											$row_rsNotification = $query_rsNotification->fetch();
											$numRows_rsNot = $query_rsNotification->rowCount();
											$stsid = $row_rsNotification["id"];
												
											if($numRows_rsNot == 0){
												//create SQL insert statement			  
												$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
												$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
											}
											else{
												$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
												$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
											}
										}

									}
									else{
										$projStatus = "In Progress";
									}
								}
								elseif($Pending == $projtotal){
									$projStatus = "Pending";
								}
								elseif($Pending > 0 && $Approved > 0 && $Inprogress == 0 &&  $Overdue==0 && $BehindSchedule==0 && $complete==0){
									$projStatus = "Pending";
								}
								elseif($BehindSchedule > 0){
									$milstatus = "Behind Schedule";
									$query_rsBehindSch = $db->prepare("SELECT * FROM tbl_milestone WHERE status = '$milstatus' AND sdate = '$SmallestStartDate' LIMIT 1");
									$query_rsBehindSch->execute();		
									$row_rsBehindSch = $query_rsBehindSch->fetch();
									$totalRows_rsBehindSch = $query_rsBehindSch->rowCount();
									$row_rsSt = $row_rsBehindSch["status"];
									if ($totalRows_rsBehindSch > 0) {
										$projStatus = "Behind Schedule";
										$subject = "Project Behind Schedule";
										$message = "Project with project code. ".$projcode." is behind schedule";
										$origin = "Project";
										if(!empty($subject) || $subject !== ''){
											$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
											$query_rsNotification->execute();		
											$row_rsNotification = $query_rsNotification->fetch();
											$numRows_rsNot = $query_rsNotification->rowCount();
											$stsid = $row_rsNotification["id"];
												
											if($numRows_rsNot == 0){
												//create SQL insert statement			  
												$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
												$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
											}
											else{
												$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
												$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
											}
										}
									}
									elseif($BehindSchedule == $projtotal){
										$projStatus = "Behind Schedule";
										$subject = "Project Behind Schedule";
										$message = "Project with project code. ".$projcode." is behind schedule";
										$origin = "Project";
										if(!empty($subject) || $subject !== ''){
											$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
											$query_rsNotification->execute();		
											$row_rsNotification = $query_rsNotification->fetch();
											$numRows_rsNot = $query_rsNotification->rowCount();
											$stsid = $row_rsNotification["id"];
												
											if($numRows_rsNot == 0){
												//create SQL insert statement			  
												$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
												$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
											}
											else{
												$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
												$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));				
											}
										}
									}
								}
								elseif($Overdue==0 && $BehindSchedule==0){
									if($percentage <= 0 && $Inprogress > 0){
										$projStatus = "In Progress";
										$subject = "Project Started";
										$message = "Project with project code. ".$projcode." has started";

										$origin = "Project";
										if(!empty($subject) || $subject !== ''){
											$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
											$query_rsNotification->execute();		
											$row_rsNotification = $query_rsNotification->fetch();
											$numRows_rsNot = $query_rsNotification->rowCount();
											$stsid = $row_rsNotification["id"];
												
											if($numRows_rsNot == 0){
												//create SQL insert statement			  
												$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																			  
												$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
											}
											else{
												$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
												$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
											}
										}
									}
									elseif($percentage > 0 && $percentage < 100){
										$projStatus = "In Progress";
										$subject = "Project In Progress";
										$message = "Project with project code. ".$projcode." is in progress";

										$origin = "Project";
										if(!empty($subject) || $subject !== ''){
											$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
											$query_rsNotification->execute();		
											$row_rsNotification = $query_rsNotification->fetch();
											$numRows_rsNot = $query_rsNotification->rowCount();
											$stsid = $row_rsNotification["id"];
												
											if($numRows_rsNot == 0){
												//create SQL insert statement			  
												$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																			  
												$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
											}
											else{
												$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
												$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
											}
										}
									}
								}

							}
							elseif($projtotal == 0){
								//if($projtotal == 0){
								//}
								if($pstartdate > $currentdate){
									$projStatus = "Approved";
									$subject = "Project Approved";
									$message = "Project with project code. ".$projcode." has been approved";

									$origin = "Project";
									if(!empty($subject) || $subject !== ''){
										$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
										$query_rsNotification->execute();		
										$row_rsNotification = $query_rsNotification->fetch();
										$numRows_rsNot = $query_rsNotification->rowCount();
										$stsid = $row_rsNotification["id"];
											
										if($numRows_rsNot == 0){
											//create SQL insert statement			  
											$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
											$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
										}
										else{
											$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
											$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
										}
									}
								}
								elseif($pstartdate <= $currentdate && $pjtype == "Existing infrastructure"){
								$projStatus = "Approved";
								$subject = "Project Approved";
								$message = "Project with project code. ".$projcode." has been approved";

								$origin = "Project";
								if(!empty($subject) || $subject !== ''){
									$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
									$query_rsNotification->execute();		
									$row_rsNotification = $query_rsNotification->fetch();
									$numRows_rsNot = $query_rsNotification->rowCount();
									$stsid = $row_rsNotification["id"];
										
									if($numRows_rsNot == 0){
										//create SQL insert statement			  
										$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
										$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
									}
									else{
										$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
										$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));		
									}
								}
								}
								elseif($pstartdate < $currentdate && $pjtype == "New"){
									$projStatus = "Unapproved";
									$subject = "Project Unapproved";
									$message = "Project with project code. ".$projcode." is awaiting approval";

									$origin = "Project";
									if(!empty($subject) || $subject !== ''){
										$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE projid = '$projectID' AND status = '$projStatus'");
										$query_rsNotification->execute();		
										$row_rsNotification = $query_rsNotification->fetch();
										$numRows_rsNot = $query_rsNotification->rowCount();
										$stsid = $row_rsNotification["id"];
											
										if($numRows_rsNot == 0){
											//create SQL insert statement			  
											$statusquery = $db->prepare("INSERT INTO tbl_notifications (projid, user, subject, message, status, date, origin) VALUES (:projid, :user, :subject, :message, :status, :date, :origin)");
																		  
											$insertnot = $statusquery->execute(array(':projid' => $projectID, ':user' => $statususer, ':subject' => $subject, ':message' => $message, ':status' => $projStatus, ':date' => $statusdate, ':origin' => $origin));
										}
										else{
											$sqlUpdate = $db->prepare("UPDATE tbl_notifications SET user = :user, subject = :subject, message = :message, status = :status, date = :date WHERE id =:id");
											$updatest = $sqlUpdate->execute(array(':user' => $statususer,':subject' => $subject,':message' => $message,':status' => $projStatus,':date' => $statusdate, ':id' => $stsid));			
										}
									}
								}
							}
						}

						$sqlUpdate = $db->prepare("UPDATE tbl_projects SET projstatus = :projStatus WHERE projid =:projectID");
						$updt = $sqlUpdate->execute(array(':projStatus' => $projStatus, ':projectID' => $projectID));
										
						$mystate = $row_rsMyP['projstate'];
						$query_rsLoc = $db->prepare("SELECT parent,state FROM tbl_state WHERE id='$mystate'");
						$query_rsLoc->execute();		
						$row_rsLoc = $query_rsLoc->fetch();
						$totalRows_rsLoc = $query_rsLoc->rowCount();
						$myWardID = $row_rsMyP['projlga'];
						$myLoc = $row_rsLoc['state'];

						$query_rsWard = $db->prepare("SELECT parent,state FROM tbl_state WHERE id='$myWardID'");
						$query_rsWard->execute();		
						$row_rsWard = $query_rsWard->fetch();
						$totalRows_rsWard = $query_rsWard->rowCount();
						$mySubCountyID = $row_rsMyP['projcommunity'];
						$myWard = $row_rsWard['state'];

						$fscyear=$row_rsMyP['projfscyear'];
						$query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$fscyear'");
						$query_FY->execute();		
						$row_FY = $query_FY->fetch();
						$totalRows_rsFY = $query_FY->rowCount();

						$fscyear=$row_rsMyP['projfscyear'];
						$query_SC = $db->prepare("SELECT parent,state FROM tbl_state WHERE id='$mySubCountyID'");
						$query_SC->execute();		
						$row_SC = $query_SC->fetch();
						$totalRows_rsSC = $query_SC->rowCount();
						$mySubCounty = $row_SC['state'];

						$myLocation = $mySubCounty.' Sub-County;&nbsp;'.$myWard.' Ward;&nbsp;'.$myLoc.' Location ';

						if(!empty($mySubCounty)){
							$myLocation = $mySubCounty.' Sub-County;&nbsp;'.$myWard.' Ward;&nbsp;'.$myLoc.' Location ';
						}else
						{
							$myLocation = 'All Sub-Counties;&nbsp;All Wards;&nbsp;All Locations';
						}

						$prjid=$row_rsMyP['projid'];
						$query_dates = $db->prepare("SELECT projstartdate, projenddate FROM tbl_projects WHERE projid='$prjid'");
						$query_dates->execute();		
						$row_dates = $query_dates->fetch();
						
						$now = time();
						$prjsdate = strtotime($row_dates['projstartdate']);
						$prjedate = strtotime($row_dates['projenddate']);
						$prjdatediff = $prjedate - $prjsdate;
						$prjnowdiff = $now - $prjsdate;
						//$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
						$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
						if($prjtimelinerate >100):
							$prjtimelinerate = 100;
						else:
							$prjtimelinerate = $prjtimelinerate;
						endif;
						
						?>
						<tr  id="rows" style="padding-bottom:1px">
							<td width="33%" style="padding-right:0px; padding-left:0px; padding-top:0px">
									<div class="links" style="background-color:skyblue; color:white; padding:5px;">
											<a href="myprojectdash.php?projid=<?php echo $row_rsMyP['projid']; ?>" style="color:#36C; font-weight:bold"><?php echo $row_rsMyP['projname']; ?></a>
									</div>
									<div style="padding:5px; font-size:11px">
										<b>Project Code:</b> <?php echo $row_rsMyP['projcode']; ?><br /><b>Project Cost:</b> Ksh.<?php echo $row_rsMyP['FORMAT(tbl_projects.projcost, 2)']; ?><br /><b>Start Date:</b> <?php echo $row_rsMyP['sdate']; ?><br /><b>End Date: </b> <?php echo $row_rsMyP['edate']; ?>
									</div>
							</td>
							<td width="8%" style="padding-right:0px; padding-left:0px">
								<?php	
								if($row_rsMyP['projstatus'] == "Pending"){
									echo "<div style='background-color:#f4d742; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "Approved"){
									echo "<div style='background-color:#41f4d9; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "In Progress"){
									echo "<div style='background-color:blue; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "InProgress"){
									echo "<div style='background-color:blue; padding:5px; height:100%;'><p style='color:white;'>In Progress</p></div>";
								}else if($row_rsMyP['projstatus'] == "Behind Schedule"){
									echo "<div style='background-color:#f96d03; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "Overdue"){
									echo "<div style='background-color:red; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "Completed"){
									echo "<div style='background-color:green; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "Unapproved"){
									echo "<div style='background-color:#c998f9; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "Cancelled"){
									echo "<div style='background-color:#381f04; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}else if($row_rsMyP['projstatus'] == "On Hold"){
									echo "<div style='background-color:#f725d0; padding:5px; height:100%;'><p style='color:white;'>".$row_rsMyP['projstatus']. "</p></div>";
								}
							?>
							</td>
							<td width="11%" align="center">
								<input type="hidden" id="scardprog" value="<?php echo $percent2; ?>">
								<?php
								if ($percent2 < 100) {
									echo '
									
									<div class="progress">
										<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%;">
											'.$percent2.'%
										</div>
									</div>';
								} 
								elseif ($percent2 ==100){
									echo '
									<div class="progress">
										<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%;">
										'.$percent2.'%                                   
										</div>
									</div>';
								}
								?>
							</td>
							<td width="8%"><?php echo $myLocation; ?></td>
							<td width="10%"><?php echo $row_FY['year']; ?></td>
							<td width="12%"><strong><?php echo $row_rsMyP['Total Status']; ?> Milestones</strong><br />
								<?php
								if($pjstatus == 'Cancelled')
								{
									if($row_rsMyP['Cancelled'] == 0){

									}
									else{
									?><span class="badge bg-brown-grey" style="margin-bottom:2px"><?php echo $row_rsMyP['Cancelled']; ?></span> Cancelled<br />
									<?php
									}
								}
								elseif($pjstatus == 'On Hold')
								{
									if($row_rsMyP['On Hold'] == 0){

									}
									else{
									?><span class="badge bg-purple" style="margin-bottom:2px"><?php echo $row_rsMyP['On Hold']; ?></span> On Hold<br />
									<?php
									}
								}
								else{
									if($row_rsMyP['Approved'] == 0){

									}
									else{
									?><span class="badge bg-teal" style="margin-bottom:2px"><?php echo $row_rsMyP['Approved']; ?></span> Approved<br />
									<?php
									}
									if($row_rsMyP['Pending'] == 0){

									}
									else{
									?><span class="badge bg-yellow" style="margin-bottom:2px"><?php echo $row_rsMyP['Pending']; ?></span> Pending<br /> 
									<?php
									}
									if($row_rsMyP['In Progress'] == 0){

									}
									else{
									?><span class="badge bg-blue" style="margin-bottom:2px"> <?php echo $row_rsMyP['In Progress']; ?></span> In Progress<br />
									<?php
									}
									if($row_rsMyP['Behind Schedule'] == 0){

									}
									else{
									?><span class="badge bg-deep-orange" style="margin-bottom:2px"><?php echo $row_rsMyP['Behind Schedule']; ?></span> Behind Schedule<br />
									<?php
									}
									if($row_rsMyP['Overdue'] == 0){

									}
									else{
									?><span class="badge bg-red" style="margin-bottom:2px"><?php echo $row_rsMyP['Overdue']; ?></span> Overdue<br />
									<?php
									}
									if($row_rsMyP['Completed'] == 0){

									}
									else{
									?><span class="badge bg-green" style="margin-bottom:2px"><?php echo $row_rsMyP['Completed']; ?></span> Completed<br />
									<?php
									}
								}
								?>
							</td>
							<?php
							$pid = $row_rsMyP['projid'];
							$query_rsSDate = $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
							$query_rsSDate->execute();		
							$row_rsSDate = $query_rsSDate->fetch();
							
							$projstartdate = $row_rsSDate["projstartdate"];
							//$start_date = date_format($projstartdate, "Y-m-d");
							$current_date = date("Y-m-d");
							
							$prjid = $row_rsMyP['projid'];
							$query_rsMProgress = $db->prepare("SELECT tbl_projects.projid AS mypjid, tbl_milestone.msid,		   COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`,    COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`,   COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,   COUNT(tbl_milestone.status) AS 'Total Status', concat(round(COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END)/COUNT(tbl_milestone.status) * 100 )) AS '%% Completed', concat(round(COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END)/COUNT(tbl_milestone.status) * 100)) AS '%% In Progress', concat(round(COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END)/COUNT(tbl_milestone.status) * 100)) AS '%% Pending' FROM tbl_projects  LEFT JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid WHERE tbl_projects.projid='$prjid'");
							$query_rsMProgress->execute();		
							$row_rsMProgress = $query_rsMProgress->fetch();
							$totalRows_rsMProgress = $query_rsMProgress->rowCount();
										
							if ($projstartdate <= $current_date && ($row_rsMyP['projstatus'] == "In Progress")) {					
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect" data-toggle="tooltip" data-placement="left" title="This project requires monitoring" style="width:100%; margin-bottom:5px"><a href="mympdash?projid=<?php echo $row_rsMyP['projid']; ?>">Monitor</a></button><button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project can be evaluated" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>"><a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate</a></button><button type="button" class="btn bg-deep-purple waves-effect"  onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($row_rsMyP['projstatus'] == "Approved") {			
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn bg-grey btn-block" data-toggle="tooltip" data-placement="left" title="This project not ready for monitoring" style="width:100%; margin-bottom:5px">Monitor</button><button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation this project now!" style="width:100%; margin-bottom:5px">Evaluate</button><button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available yet" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($projstartdate <= $current_date && $row_rsMyP['projstatus'] == "Completed") {				
							?>
								<td style="padding-right:0px; padding-left:0px"><button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't monitor completed project" style="width:100%; margin-bottom:5px">Monitor</button><button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate</a></button><button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							//elseif ($projstartdate <= $current_date && $row_rsMyP['projstatus'] == "Pending") {	
							elseif ($row_rsMyP['projstatus'] == "Pending") {				
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info waves-effect" data-toggle="tooltip" data-placement="left" title="You can't monitor pending project" style="width:100%; margin-bottom:5px"> Monitor</button><button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluate pending project" style="width:100%; margin-bottom:5px">Evaluate</button><button type="button" class="btn bg-deep-purple waves-effect" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for pending project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($projstartdate <= $current_date && ($row_rsMyP['projstatus'] == "Behind Schedule" || $row_rsMyP['projstatus'] == "Overdue")) {			
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-info btn-block waves-effect" data-toggle="tooltip" data-placement="left" title="This project requires monitoring" style="width:100%; margin-bottom:5px"><a href="mympdash?projid=<?php echo $row_rsMyP['projid']; ?>">Monitor</a></button><button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate</a></button><button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($row_rsMyP['projstatus'] == "On Hold") {			
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't monitor on hold project" style="width:100%; margin-bottom:5px">Monitor</button><button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" data-placement="left" title="This Project requires Evaluation" style="width:100%; margin-bottom:5px"><a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate</a></button><button type="button" class="btn bg-deep-purple waves-effect" onclick="javascript:GetScorecard(<?php echo $row_rsMyP['projid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="You can view scorecard for this project here" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($row_rsMyP['projstatus'] == "Unapproved") {			
							?>
								<td style="padding-right:0px; padding-left:0px"><button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't monitor unapproved project" style="width:100%; margin-bottom:5px">Monitor</button><button class="btn bg-grey" data-toggle="tooltip" data-placement="left" title="You can't evaluation unapproved project" style="width:100%; margin-bottom:5px">Evaluate</button><button class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for unapproved project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							elseif ($row_rsMyP['projstatus'] == "Cancelled") {			
							?>
								<td style="padding-right:0px; padding-left:0px"><button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't monitor cancelled project" style="width:100%; margin-bottom:5px">Monitor</button><button type="button" class="btn btn-grey waves-effect" data-toggle="tooltip" data-placement="left" title="You can't evaluation cancelled project" style="width:100%; margin-bottom:5px">Evaluate</a></button><button type="button" class="btn bg-grey" data-toggle="tooltip" data-placement="bottom" title="Scorecard not available for cancelled project" style="width:100%; margin-bottom:5px">Scorecard</button></td>
							<?php
							}
							if ($row_rsMyP['projstatus'] == "Cancelled" || $row_rsMyP['projstatus'] == "On Hold") {
							?>
								<td width="8%" style="margin-left:0px; margin-right:0px"><div align="right" id="formcells"><div align="center"></div></div> | <div align="center"></div> | <div align="center"></div></td>
							<?php
							}
							elseif ($row_rsMyP['projstatus'] == "Cancelled" || $row_rsMyP['projstatus'] == "On Hold") {
							?>
								<td width="8%" style="margin-left:0px; margin-right:0px"><div align="right" id="formcells">
								<div align="center"><a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>"><img src="images/preview.png" alt="View Project Details" name="view" width="16" height="16" id="view" title="View Project Details" /></a></div>
								</div><div align="center"></div></div></td>
							<?php
							}
							else{			
							?>
								<td width="8%" style="margin-left:0px; margin-right:0px"><div align="right" id="formcells">
									<div align="center"><a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>"><img src="images/preview.png" alt="View Project Details" name="view" width="16" height="16" id="view" title="View Project Details" /></a> <a href="updatemyproject.php?projid=<?php echo $row_rsMyP['projid']; ?>"><img src="images/edit.png" alt="Edit Project" width="16" height="16" title="Edit Project" /></a> <a href="delmyproject.php?projid=<?php echo $row_rsMyP['projid']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="Delete Project" width="16" height="16" title="Delete Project" /></a></div>
								</td>
							<?php 
							}
							?>
						</tr>
				<?php 
					} while ($row_rsMyP = $query_rsMyP->fetch()); 
				}catch (PDOException $ex){
                    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
                }
				?>