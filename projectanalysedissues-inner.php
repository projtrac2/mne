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
						<tr class="bg-grey">
							<th style="width:3%">#</th>
							<th style="width:20%">Issue</th>
							<th style="width:7%">Priority</th>
							<th style="width:12%">Recorded By</th>
							<th style="width:10%">Date Recorded</th>
							<th style="width:12%">Assigned By</th>
							<th style="width:10%">Date Assigned</th>
							<th style="width:10%">Due Date</th>
							<th style="width:9%">Status</th>
							<th style="width:7%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$nm = 0;
						do
						{ 
							$nm = $nm + 1;
							$id = $rows['id'];
							$projid = $rows['projid'];
							$project = $rows['projname'];
							$risk = $rows['category'];
							$observation = $rows['observation'];
							$priority = $rows['priority'];
							$monitor = $rows['monitor'];
							$issuedate = $rows['issuedate'];
							$assignee = $rows['assigned_by'];
							$dateassigned = $rows['date_assigned'];
							$status = $rows['status'];
								
							$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=2 and active=1");
							$query_timeline->execute();		
							$row_timeline = $query_timeline->fetch();
							$timelineid = $row_timeline["id"];
							$time = $row_timeline["time"];
							$units = $row_timeline["units"];
							$stgstatus = $row_timeline["status"];
							
							$duedate = strtotime($dateassigned."+ ".$time." ".$units);
							$actionnduedate = date("d M Y",$duedate);

							$current_date = date("Y-m-d");
							$actduedate = date("Y-m-d", $duedate);
							
							$query_score = $db->prepare("SELECT score,date_analysed FROM tbl_project_riskscore WHERE issueid='$id'");
							$query_score->execute();	
							$check_score = $query_score->fetch();
							
							$dateassigned = date("d M Y",strtotime($dateassigned));
							
							if($status == 2){
								if($actduedate >= $current_date){
									$actionstatus = "Analysis";
									$styled = 'style="color:blue"';
								}elseif($actduedate < $current_date){
									$actionstatus = "Overdue";
									$styled = 'style="color:red"';
								}
								$actiondate = $actionnduedate;
							}elseif($status == 3){
								$actionstatus = "Analysed";
								$styled = 'style="color:blue"';
								$actiondate = $dateanalysed;
							}else{
								$query_dateclosed = $db->prepare("SELECT date_closed FROM tbl_projissues WHERE id='$id'");
								$query_dateclosed->execute();	
								$row_dateclosed = $query_dateclosed->fetch();
								
								$dateclosed = date("d M Y",strtotime($row_dateclosed["date_closed"]));
								$actionstatus = "Closed";
								$styled = 'style="color:green"';
								$actiondate = $dateclosed;
							}
							
							?>
							<tr style="background-color:#fff">
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $risk; ?></td>
								<td><?php echo $priority; ?></td>
								<td><?php echo $monitor; ?></td>
								<td><?php echo date("d M Y",strtotime($issuedate)); ?></td>
								<td><?php echo $assignee; ?></td>
								<td><?php echo $dateassigned; ?></td>
								<td><?php echo $actiondate; ?></td>
								<td <?php echo $styled; ?>><strong><?php echo $actionstatus; ?></strong></td>
								<td>
									<div align="center">
									<?php if($status==2){?>
										<a onclick="javascript:CallRiskAction(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Analyse this Issue"><i class="fa fa-folder-open fa-2x text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;<a onclick="javascript:CallRiskAction(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Collaborate on this Issue"><i class="fa fa-comments-o fa-2x text-primary" aria-hidden="true"></i></a>
									<?php }elseif($status==3){?>
										<a onclick="javascript:CallRiskAnalysis(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Issue Analysis Report"><i class="fa fa-bar-chart fa-2x text-primary" aria-hidden="true"></i></a>&nbsp;&nbsp;<a onclick="javascript:CallRiskAction(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Collaborate on this Issue"><i class="fa fa-comments-o fa-2x text-primary" aria-hidden="true"></i></a>
									<?php }else{ ?>
										<a onclick="javascript:CallRiskAnalysis(<?php echo $rows['id']; ?>)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Closed Issue History Report"><i class="fa fa-folder fa-2x text-success" aria-hidden="true"></i></a>
									<?php } ?>
									</div>
								</td>
							</tr>
						<?php
						}while($rows = $query_issuesanalysis->fetch());
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