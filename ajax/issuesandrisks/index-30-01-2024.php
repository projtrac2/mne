<?php
include '../controller.php';
try {
    if (isset($_GET['get_issue_more_info'])) {
        $issueid = $_GET['issueid'];
		
		$query_issue_details = $db->prepare("SELECT i.projid, i.issue_area, i.issue_priority, issue_description, m.description AS impact, category, recommendation, i.status, i.created_by AS monitor, i.date_created AS issuedate FROM tbl_projissues i INNER JOIN tbl_projrisk_categories c ON c.catid=i.risk_category inner join tbl_risk_impact m on m.id=i.issue_impact WHERE i.id=:issueid");
		$query_issue_details->execute(array(":issueid" =>$issueid));
		$row_issue_details = $query_issue_details->fetch();
		
		$category = $row_issue_details['category'];
		$issue_description = $row_issue_details['issue_description'];
		$issue_priority = $row_issue_details['issue_priority'];
		$issue_areaid = $row_issue_details['issue_area'];
		$issue_impact = $row_issue_details['impact'];
		$monitorid = $row_issue_details['monitor'];
		$recommendation = $row_issue_details['recommendation'];
		$issuedate = $row_issue_details['issuedate'];
		$projid = $row_issue_details['projid'];
		$issue_status_id = $row_issue_details['status'];
												
		if($issue_priority == 1){
			$priority = "High";
		}elseif($issue_priority == 2){
			$priority = "Medium";
		}elseif($issue_priority == 3){
			$priority = "Low";
		}		
		
		$query_project = $db->prepare("SELECT projcategory FROM tbl_projects WHERE projid=:projid");
		$query_project->execute(array(":projid" => $projid));
		$row_project = $query_project->fetch();
		$projcategory = $row_project["projcategory"];
		
		$query_owner = $db->prepare("SELECT tt.title, fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid='$monitorid'");
		$query_owner->execute();
		$row_owner = $query_owner->fetch();
		$monitor = $row_owner["title"] . '.' . $row_owner["fullname"];
		
		$issues_scope = '';
		$textclass = "";
		if($issue_areaid == 2){
			$issue_area = "Scope";
			
			$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";
							
			$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
			$query_site_id->execute(array(":issueid" => $issueid));
	
			$sites = 0;
			while ($rows_site_id = $query_site_id->fetch()) {
				$sites++;
				$site_id = $rows_site_id['site_id'];
				//$allsites = '';
				
				if($site_id==0){
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': Way Point Sub Tasks</th>
					</tr>';
				} else {
					$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
					$query_site->execute(array(":site_id" => $site_id));
					$row_site = $query_site->fetch();
					$site = $row_site['site'];
				
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': '.$site.'</th>
					</tr>';
				}
			
				$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id ".$leftjoin." left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
				$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));
			
				$issues_scope .= $allsites.'
				<tr class="adjustments '.$issueid.'" style="background-color:#cccccc">
					<th width="5%">#</th>
					<th width="50%">Sub-Task</th>
					<th width="15%">Requesting Units</th>
					<th width="15%">Additional Days</th>
					<th width="15%">Additional Cost</th>
				</tr>';
				$scopecount = 0;
				while ($row_adjustments = $query_adjustments->fetch()){
					$scopecount++;
					$subtask = $row_adjustments["task"];
					$units =  number_format($row_adjustments["units"])." ".$row_adjustments["unit"];
					$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
					$timeline = $row_adjustments["timeline"]." days";
					$issues_scope .= '<tr class="adjustments '.$issueid.'" style="background-color:#e5e5e5">
						<td>'.$sites.'.'.$scopecount.'</td>
						<td>' . $subtask . '</td>
						<td>' . $units . ' </td>
						<td>' . $timeline . '</td>
						<td>' . number_format($totalcost, 2) . ' </td>
					</tr>';
				}
			}
		}elseif($issue_areaid == 3){
			$issue_area = "Schedule";
			
			$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";
			
			$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
			$query_site_id->execute(array(":issueid" => $issueid));
	
			$sites = 0;
			while ($rows_site_id = $query_site_id->fetch()) {
				$sites++;
				$site_id = $rows_site_id['site_id'];
				$allsites = '';
				
				if($site_id==0){
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': Way Point Sub Tasks</th>
					</tr>';
				} else {
					$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
					$query_site->execute(array(":site_id" => $site_id));
					$row_site = $query_site->fetch();
					$site = $row_site['site'];
				
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': '.$site.'</th>
					</tr>';
				}
			
				$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id ".$leftjoin." left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
				$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));
			
				$issues_scope .= $allsites.'
				<tr class="adjustments '.$issueid.'" style="background-color:#cccccc">
					<th width="5%">#</th>
					<th width="80%">Sub-Task</th>
					<th width="15%">Additional Days</th>
				</tr>';
				$scopecount = 0;
				while ($row_adjustments = $query_adjustments->fetch()){
					$scopecount++;
					$subtask = $row_adjustments["task"];
					$units =  number_format($row_adjustments["units"])." ".$row_adjustments["unit"];
					$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
					$timeline = $row_adjustments["timeline"]." days";
					$issues_scope .= '<tr class="adjustments '.$issueid.'" style="background-color:#e5e5e5">
						<td>'.$sites.'.'.$scopecount.'</td>
						<td>' . $subtask . '</td>
						<td>' . $timeline . '</td>
					</tr>';
				}
			}
		}else{
			$issue_area = "Cost";
			
			$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";
			
			$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
			$query_site_id->execute(array(":issueid" => $issueid));
	
			$sites = 0;
			while ($rows_site_id = $query_site_id->fetch()) {
				$sites++;
				$site_id = $rows_site_id['site_id'];
				//$allsites = '';
				
				if($site_id==0){
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': Way Point Sub Tasks</th>
					</tr>';
				} else {
					$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
					$query_site->execute(array(":site_id" => $site_id));
					$row_site = $query_site->fetch();
					$site = $row_site['site'];
				
					$allsites = '<tr class="adjustments '.$issueid.'" style="background-color:#a9a9a9">
						<th colspan="8">Site '.$sites.': '.$site.'</th>
					</tr>';
				}
		
				$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, a.cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id ".$leftjoin." left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
				$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));
			
				$issues_scope .= $allsites.'
				<tr class="adjustments '.$issueid.'" style="background-color:#cccccc">
					<th width="5%">#</th>
					<th width="65%">Sub-Task</th>
					<th width="15%">Measurement Unit</th>
					<th width="15%">Additional Cost</th>
				</tr>';
				$scopecount = 0;
				while ($row_adjustments = $query_adjustments->fetch()){
					$scopecount++;
					$subtask = $row_adjustments["task"];
					$unit =  $row_adjustments["unit"];
					$cost = $row_adjustments["cost"];
					
					$issues_scope .= '<tr class="adjustments '.$issueid.'" style="background-color:#e5e5e5">
						<td>'.$sites.'.'.$scopecount.'</td>
						<td>' . $subtask . '</td>
						<td>' . $unit . ' </td>
						<td>' . number_format($cost, 2) . ' </td>
					</tr>';
				}
			}
		}
		
		$issue_more_info = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Description</label>
				<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$issue_description.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Category</label>
				<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$category.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Area</label>
				<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$issue_area.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Severity</label>
				<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$issue_impact.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Priority</label>
				<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$priority.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Issue Owner</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$monitor.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Date Recorded</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$issuedate.'</div>
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example">
					<thead>
						<h4 class="text-primary">Issue Details:</h4>
					</thead>
					<tbody>
						' . $issues_scope . '
					</tbody>
				</table>
			</div>
		</div>';
		
		if($issue_status_id > 0){
			if($issue_status_id == 2){			
				$query_issue_comments = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid = :projid and issueid = :issueid");
				$query_issue_comments->execute(array(":projid" => $projid, ":issueid" => $issueid));
				$totalrows_issue_comments = $query_issue_comments->rowCount();
				if($totalrows_issue_comments > 0){
					$issue_more_info .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<fieldset class="scheduler-border">
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px;"><i class="fa fa-columns" aria-hidden="true"></i> Management Resolution/s</legend>';
							$actionscount=0;
							while($row_issue_comments = $query_issue_comments->fetch()){
								$actionscount++;
								$issue_status = $row_issue_comments["issue_status"];
								$issue_comment = $row_issue_comments["comments"];
								$issue_more_info .= '
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
									<div class="form-inline">
										<label for="">Action '.$actionscount.': '.$issue_status.'</label>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
									<div class="form-inline">
										<label for="">Remarks</label>
										<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$issue_comment.'</div>
									</div>
								</div>';
							}
						$issue_more_info .= '</fieldset>
					</div>';
				}
			}	else {
				$issue_more_info .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
					<label class="control-label">Resolution Comments:</label>
					<div class="form-control" style="height: auto">'.$recommendation.'</div>
				</div>';
			}
		}			

		$query_issue_files = $db->prepare("SELECT * FROM tbl_files WHERE projid = :projid and projstage = :issueid and (fcategory='Issue' OR fcategory='Issue Resolution')");
		$query_issue_files->execute(array(":projid" => $projid, ":issueid" => $issueid));	
		$rowcount_files = $query_issue_files->rowCount();
	
		if($rowcount_files > 0){							
			$issue_more_info .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<fieldset class="scheduler-border">
					<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px;"><i class="fa fa-paperclip" aria-hidden="true"></i> Issue Attachments</legend>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr style="background-color:#cccccc">
									<th width="3%">#</th>
									<th width="35%">File Name</th>
									<th width="8%">File Type</th>
									<th width="36%">File Purpose</th>
									<th width="10%">Issue Stage</th>
									<th width="8%">Download</th>
								</tr>
							</thead>
							<tbody>';
								$files_count = 0;
								while($row_issue_files = $query_issue_files->fetch()){
									$files_count++;
									$filename = $row_issue_files["filename"];
									$filetype = $row_issue_files["ftype"];
									$filelocation = $row_issue_files["floc"];
									$filedescription = $row_issue_files["reason"];
									$issue_stage = $row_issue_files["fcategory"];
									$issue_more_info .= '
									<tr style="background-color:#e5e5e5">
										<td>' . $files_count.'</td>
										<td>' . $filename . '</td>
										<td>' . $filetype . ' </td>
										<td>' . $filedescription . '</td>
										<td>' . $issue_stage . '</td>
										<td><a href="' . $filelocation . '" download="' . $filename . '" type="button" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a></td>
									</tr>';
								}
							$issue_more_info .= '</tbody>
						</table>
					</div>
				</fieldset>
			</div>';
		}

		
        echo json_encode(["issue_more_info" => $issue_more_info]);
		
       // echo json_encode(["issue_more_info" => $issue_more_info, "issue_attachments" => $issue_attachments]);
	}
	
	if(isset($_GET['issue_status']) && !empty($_GET['issue_status'])) 
	{
		$statusid = $_GET["statusid"];
		$projid = $_GET["projid"];
		$issueid = $_GET["issueid"];

		$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus FROM tbl_projects WHERE projid = '$projid'");
		$query_projstatuschange->execute();		
		$row_projstatuschange = $query_projstatuschange->fetch();
		$projstatus = $row_projstatuschange["projstatus"];
		$projcode = $row_projstatuschange["projcode"];
		
		$query_projindicator =  $db->prepare("SELECT i.indicator_name FROM tbl_indicator i inner join tbl_project_details d on d.indicator=i.indid WHERE d.projid = '$projid'");
		$query_projindicator->execute();		
		$row_projindicator = $query_projindicator->fetch();
		//$projindicator = $row_projindicator["indicator_name"];
		
		if($statusid=="On Hold"){
			$projchangedstatus = $row_projstatuschange["projchangedstatus"];
			$projstatusselect =	'
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082"> Is Assessment Required?</font></label>
				<div class="form-line">
					<select name="assessment" id="assessment" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
						<option value="" selected="selected" class="selection">... Select ...</option>
						<option value="1">YES</option>
						<option value="0">NO</option>
					</select>	
				</div>
			</div>';
		}elseif($statusid=="Cancelled"){
			$projstatusselect =	'';
		}elseif($statusid=="Continue"){
			$projstatusselect =	'';
		}elseif($statusid=="Approved"){
			$projstatusselect =	'';
		}elseif($statusid=="Restore"){
			$projstatusselect =	'';
		}elseif($statusid=="adjust"){
			$projchangedstatus = $projstatus;
			$projstatusselect =	'
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Project Area to Adjust:</font></label>
				<div class="form-line">
					<select name="projadjustment" id="projadjustment" class="form-control show-tick" data-live-search="true" onchange="project_adjustments('.$projid.','.$issueid.')" style="border:#CCC thin solid; border-radius:5px" required>
						<option value="" selected="selected" class="selection">... Select ...</option>
						<option value="1">Cost</option>
						<option value="2">Timeline</option>
						<option value="3">Both Cost & Timeline</option>
					</select>	
				</div>
			</div>
			<div id="adjustments">
			</div>';
		}elseif($statusid=="RestoreAndApprove"){
			$projchangedstatus = $projstatus;
			$projstatusselect =	'
			';
		}else{
			$projchangedstatus = $projstatus;
			$projstatusselect =	'
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Project Status Action:</font></label>
				<div class="form-line">
					<select name="projstatuschange" id="projstatuschange" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
						<option value="" selected="selected" class="selection">... Select ...</option>
						<option value="'.$projstatus.'">Project To Continue</option>
						<option value="On Hold">Put Project On Hold</option>
						<option value="Cancelled">Cancel Project</option>
					</select>	
				</div>
			</div>';
		}

		echo $projstatusselect.'	
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label><font color="#174082">Comments:</font></label>
				<div class="form-line">
					<textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
					<script src="js/issueescalationtextareajs.js"></script>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label><font color="#174082">Select and attach a file:</font></label>
				<div class="form-line">                 
					<input type="file" name="attachment" id="attachment" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
					&nbsp;
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
					<div class="btn-group">
						<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit"  value="Save" />
					</div>
					<div class="btn-group">
						<a type="button" class="btn btn-warning" href="project-escalated-issue">Cancel</a>
					</div>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
					<input name="issueid" type="hidden" value="'.$issueid.'" />
				</div>
			</div>';
	}

	if(isset($_GET['project_adjustments']) && $_GET['project_adjustments']==1){
		/* $projid = $_GET['projid'];
		$issueid = $_GET['issueid']; */
		$changearea = $_GET['change_area'];
		
		if($changearea == 1){
		echo '
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Amount Added:</font></label>
				<div class="form-line">
					<input type="number" name="addedamount" class="form-control" placeholder="Enter the agreed amount to add to the project" style="border:#CCC thin solid; border-radius:5px" required>
				</div>
			</div>';
		}
		elseif($changearea == 2){
		echo '
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Time Added (Days):</font></label>
				<div class="form-line">
					<input type="number" name="addedtime" class="form-control" placeholder="Enter the agreed number of days to add to the project" style="border:#CCC thin solid; border-radius:5px" required>
				</div>
			</div>';
		}
		else{
		echo '
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Amount Added:</font></label>
				<div class="form-line">
					<input type="number" name="addedamount" class="form-control" placeholder="Enter the agreed amount to add to the project" style="border:#CCC thin solid; border-radius:5px" required>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label><font color="#174082">Time Added (Days):</font></label>
				<div class="form-line">
					<input type="number" name="addedtime" class="form-control" placeholder="Enter the agreed number of days to add to the project" style="border:#CCC thin solid; border-radius:5px" required>
				</div>
			</div>';
		}
	}

	if(isset($_POST['timelinestatus']) && $_POST['timelinestatus']==1){
		$projid = $_POST['projid'];
		$issueid = $_POST['issueid'];
		
		$query_projcat =  $db->prepare("SELECT * FROM tbl_projects WHERE projid = '$projid'");
		$query_projcat->execute();		
		$row_projcat = $query_projcat->fetch();
		$project = $row_projcat["projname"];
		$projstatus = $row_projcat["projstatus"];
		$projsdate = $row_projcat["projstartdate"];
		$projedate = $row_projcat["projenddate"];
		$nmb = 1;
		
		$query_projmils =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$projid' and changedstatus<>5 GROUP BY msid ORDER BY msid ASC");
		$query_projmils->execute();	
		
		/* $query_projchange =  $db->prepare("SELECT * FROM tbl_projstatuschangereason WHERE projid = '$projid'");
		$query_projchange->execute();		
		$row_projchange = $query_projchange->fetch();
		$onhold_date = $row_projchange["originalenddate"]; */
		
		echo '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<fieldset class="scheduler-border">
				<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-clock-o" aria-hidden="true"></i> Timeline Changes</legend>
				<input name="projid" type="hidden" value="'.$projid.'">
				<input name="issueid" type="hidden" value="'.$issueid.'">
				<input name="type" type="hidden" value="time">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" style="width:100%">
						<thead>
							<tr id="colrow">
								<th width="4%"><strong id="colhead">SN</strong></th>
								<th width="40%">Item Name</th>
								<th width="15%">Status</th>
								<th width="14%">Start Date</th>
								<th width="14%">End Date</th>
								<th width="13%">Added Days</th>
							</tr>
						</thead>
						<tbody>';
						
							$nm = 0;
							while($rows = $query_projmils->fetch()){
								$nm++;
								$msid = $rows["msid"];
								$milestone = $rows["milestone"];
								$statusid = $rows["changedstatus"];
								$sdate = date("d M Y",strtotime($rows["sdate"]));
								$edate = date("d M Y",strtotime($rows["edate"]));
								
								$query_status =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$statusid'");
								$query_status->execute();		
								$row_status = $query_status->fetch();
								$status = $row_status["statusname"];
								echo 
								'<tr style="background-color:#9E9E9E; color:#FFF">
									<td>'.$nmb.'.'.$nm.'</td>
									<td>'.$milestone.'</td>
									<td>'.$status.'</td>
									<td>'.$sdate.'</td>
									<td>'.$edate.'</td>
									<td></td>
								</tr>';
		
								$query_projtasks =  $db->prepare("SELECT * FROM tbl_task WHERE projid = '$projid' and msid = '$msid' and changedstatus<>5 ORDER BY tkid ASC");
								$query_projtasks->execute();
								$sr = 0;
								while($rows_tsk = $query_projtasks->fetch()){
									$sr++;
									$tkid = $rows_tsk["tkid"];
									$task = $rows_tsk["task"];
									$statusid = $rows_tsk["changedstatus"];
									$tsksdate = date("d M Y",strtotime($rows_tsk["sdate"]));
									$tskedate = date("d M Y",strtotime($rows_tsk["edate"]));
								
									$query_status =  $db->prepare("SELECT statusname FROM tbl_task_status WHERE statusid = '$statusid'");
									$query_status->execute();		
									$row_status = $query_status->fetch();
									$tskstatus = $row_status["statusname"];
									echo '<tr>
										<td>'.$nmb.'.'.$nm.'.'.$sr.'</td>
										<td>'.$task.'</td>
										<td>'.$tskstatus.'</td>
										<td>'.$tsksdate.'</td>
										<td>'.$tskedate.'</td>
										<td><input type="number" name="timeline[]" class="form-control" required></td>
										<input type="hidden" name="itemid[]" value="'.$tkid.'">
										<input type="hidden" name="category[]" value="3">
									</tr>';
								}
							}
						echo  '</tbody>
					</table>
				</div>
			</fieldset>
		</diV>';
	}
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
