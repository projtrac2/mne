<?php 
try {
	//code...

?>
<div class="body">
	<div  class="table-responsive">
			<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
				<thead>
					<tr class="bg-blue-grey">
						<th style="width:3%">#</th>
						<th style="width:37%">Issue</th>
						<th style="width:12%">Severity</th>
						<th style="width:15%">Escalated To</th>
						<th style="width:15%">Issue Owner</th>
						<th style="width:10%">Status Date</th>
						<th style="width:8%">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php		
					$nm = 0;
					if($count_escalatedissues > 0){
						do
						{ 
							$nm = $nm + 1;
							$id = $rows['id'];
							$projid = $rows['projid'];
							$project = $rows['projname'];
							$risk = $rows['category'];
							$observation = $rows['observation'];
							$monitor = $rows['monitor'];
							$ownerid = $rows['owner'];
							//$escownerid = $rows['escowner'];
							$priority = $rows['priority'];
							$status = $rows['status'];
							$issuedate = $rows['issuedate'];
							$dateassigned = $rows['date_assigned'];
								
							$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=4 and active=1");
							$query_timeline->execute();		
							$row_timeline = $query_timeline->fetch();
							$timelineid = $row_timeline["id"];
							$time = $row_timeline["time"];
							$units = $row_timeline["units"];
							$stgstatus = $row_timeline["status"];
							
							$duedate = strtotime($dateassigned."+ ".$time." ".$units);
							$actionnduedate = date("d M Y",$duedate);
							$assigneddate = date("d M Y",strtotime($dateassigned));

							$current_date = date("Y-m-d");
							$actduedate = date("Y-m-d", $duedate);
							
							$query_score = $db->prepare("SELECT score,date_analysed,notes FROM tbl_project_riskscore WHERE issueid='$id'");
							$query_score->execute();	
							$check_score = $query_score->fetch();
							$dateanalysed = date("d M Y",strtotime($check_score["date_analysed"]));
							$riskscore = $check_score["score"];
							$risknotes = $check_score["notes"];
							/* 
							if($status == 4 || $status == 6){
								$query_dateescalated = $db->prepare("SELECT date_escalated FROM tbl_projissues WHERE id='$id'");
								$query_dateescalated->execute();	
								$row_dateescalated = $query_dateescalated->fetch();
								
								$dateescalated = date("d M Y",strtotime($row_dateescalated["date_escalated"]));
								$actionstatus = "Escalated";
								$styled = 'style="color:#FFC107"';
								$actiondate = $dateescalated;
							}elseif($status == 5){
								$query_dateclosed = $db->prepare("SELECT date_closed FROM tbl_projissues WHERE id='$id'");
								$query_dateclosed->execute();	
								$row_dateclosed = $query_dateclosed->fetch();
								
								$dateclosed = date("d M Y",strtotime($row_dateclosed["date_closed"]));
								$actionstatus = "Closed";
								$styled = 'style="color:green"';
								$actiondate = $dateclosed;
							} */
								
							if($status == 2){
								$actionstatus = "Analysis";
								$styled = 'style="color:blue; width:9%"';
								$actiondate = date("d M Y", $duedate);
							
							}elseif($status == 3){
								$actionstatus = "Analysed";
								$styled = 'style="color:blue"';
								$actiondate = $dateanalysed;
							}elseif($status == 4){
								$query_dateescalated = $db->prepare("SELECT date_escalated FROM tbl_projissues WHERE id='$id'");
								$query_dateescalated->execute();	
								$row_dateescalated = $query_dateescalated->fetch();
								
								$dateescalated = date("d M Y",strtotime($row_dateescalated["date_escalated"]));
								$actionstatus = "Escalated";
								$styled = 'style="color:#FFC107; width:9%"';
								$actiondate = $dateescalated;
							}elseif($status == 5){
								$query_date_on_hold = $db->prepare("SELECT date_on_hold FROM tbl_escalations WHERE itemid='$id'");
								$query_date_on_hold->execute();	
								$row_date_on_hold = $query_date_on_hold->fetch();
								
								$date_on_hold = date("d M Y",strtotime($row_date_on_hold["date_on_hold"]));
								$actionstatus = "On Hold";
								$styled = 'style="color:red; width:9%"';
								$actiondate = $date_on_hold;
							}elseif($status == 6){
								$query_date_continue = $db->prepare("SELECT date_continue FROM tbl_escalations WHERE itemid='$id'");
								$query_date_continue->execute();	
								$row_date_continue = $query_date_continue->fetch();
								
								$date_continue = date("d M Y",strtotime($row_date_continue["date_continue"]));
								$actionstatus = "Continue";
								$styled = 'style="color:blue; width:9%"';
								$actiondate = $date_continue;
							}elseif($status == 7){
								$query_dateclosed = $db->prepare("SELECT date_closed FROM tbl_projissues WHERE id='$id'");
								$query_dateclosed->execute();	
								$row_dateclosed = $query_dateclosed->fetch();
								
								$dateclosed = date("d M Y",strtotime($row_dateclosed["date_closed"]));
								$actionstatus = "Closed";
								$styled = 'style="color:green; width:9%"';
								$actiondate = $dateclosed;
							}
							
							if($riskscore == 1){
								$level = "Negligible";
								$style = 'style="background-color:#4CAF50; color:#fff"';
							}elseif($riskscore == 2){
								$level = "Minor";
								$style = 'style="background-color:#CDDC39; color:#fff"';
							}elseif($riskscore == 3){
								$level = "Moderate";
								$style = 'style="background-color:#FFEB3B; color:#000"';
							}elseif($riskscore == 4){
								$level = "Significant";
								$style = 'style="background-color:#FF9800; color:#fff"';
							}elseif($riskscore == 5){
								$level = "Severe";
								$style = 'style="background-color:#F44336; color:#fff"';
							}
							
							?>
							<tr style="background-color:#fff">
								<td align="center"><?php echo $nm; ?></td>
								<td><a onclick="javascript:CallRiskAnalysis(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Issue Analysis Report"><?php echo $risk; ?></a></td>
								<td <?=$style?>>
									<?php echo $level; ?>
								</td>
								<?php
								$query_manager = $db->prepare("SELECT title, fullname FROM tbl_escalations e inner join users u on u.userid=e.owner inner join tbl_projteam2 t on t.ptid=u.pt_id WHERE itemid='$id'");
								$query_manager->execute();	
								$row_manager = $query_manager->fetch();
								$manager = $row_manager["title"].'.'.$row_manager["fullname"];
								?>
								<td><?php echo $manager; ?></td>
								<?php
								$query_owner = $db->prepare("SELECT title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid='$ownerid'");
								$query_owner->execute();	
								$row_owner = $query_owner->fetch();
								$owner = $row_owner["title"].'.'.$row_owner["fullname"];
								?>
								<td><?php echo $owner; ?></td>
								<td><?php echo $actiondate; ?></td>
								<td <?php echo $styled; ?>><strong><?php echo $actionstatus; ?></strong></td>
							</tr>
						<?php
						}while($rows = $query_escalatedissues->fetch());
					}
					?>
				</tbody>
			</table>
	</div>
</div>

<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>