<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	if(isset($_POST['statusid']) && !empty($_POST['statusid'])) 
	{
		$statusid = $_POST["statusid"];
		$projid = $_POST["projid"];
		$issueid = $_POST["issueid"];

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
			<div class="col-md-6">
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
		}elseif($statusid=="Restore"){
		
			$query_projrestore =  $db->prepare("SELECT * FROM tbl_projstatuschangereason WHERE projid = '$projid' and type = '1' ORDER BY id DESC LIMIT 1");
			$query_projrestore->execute();		
			$row_projrestore = $query_projrestore->fetch();
			$prevstatus = $row_projrestore["status"];
			
			$projstatusselect =	'
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<fieldset class="scheduler-border">
					<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-ol" aria-hidden="true"></i> How were the parameters below affected?</legend>
					<input name="prevstatus" type="hidden" value="'.$prevstatus.'">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr id="colrow">
									<th width="5%"><strong id="colhead">SN</strong></th>
									<th width="70%">Parameter</strong></td>
									<th width="25%">Option</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="center">
										1.
									</td>
									<td> 
										<strong>Cost (Kenya Shillings)</strong>
									</td>
									<td>
										<select name="costopt" id="costopt" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
											<option value="" selected="selected" class="selection">... Select ...</option>
											<option value="0">No Change</option>
											<option value="1">Has Changed</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style="center">
										2.
									</td>
									<td> 
										<strong>Timeline (Days)</strong>
									</td>
									<td>
										<select name="timelineopt" id="timelineopt" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
											<option value="" selected="selected" class="selection">... Select ...</option>
											<option value="0">No Change</option>
											<option value="1">Has Changed</option>
										</select>
									</td>
								</tr>';
								/* echo '<tr>
									<td style="center">
										3.
									</td>
									<td> 
										<strong>Scope ('.$projindicator.')</strong>
									</td>
									<td>
										<select name="scopeopt" id="scopeopt" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
											<option value="" selected="selected" class="selection">... Select ...</option>
											<option value="0">No Change</option>
											<option value="1">Has Changed</option>
										</select>
									</td>
								</tr>'; */
							$projstatusselect =	$projstatusselect.'</tbody>
						</table>
					</div>
				</fieldset>
			</diV>
			';
		}else{
			$projchangedstatus = $projstatus;
			$projstatusselect =	'
			<div class="col-md-6">
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
			<div class="col-md-12">
				<label><font color="#174082">Comments:</font></label>
				<div class="form-line">
					<textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
					<script src="js/issueescalationtextareajs.js"></script>
				</div>
			</div>
			<div class="col-md-12">
				<label><font color="#174082">Select and attach a file:</font></label>
				<div class="form-line">                 
					<input type="file" name="attachment" id="attachment">
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

	if(isset($_POST['coststatus']) && $_POST['coststatus']==1){
		$projid = $_POST['projid'];
		$issueid = $_POST['issueid'];
		
		$query_projcat =  $db->prepare("SELECT projcategory FROM tbl_projects WHERE projid = '$projid'");
		$query_projcat->execute();		
		$row_projcat = $query_projcat->fetch();
		$projcat = $row_projcat["projcategory"];

		$query_projtasks =  $db->prepare("SELECT * FROM tbl_task WHERE projid = '$projid' and changedstatus<>5 GROUP BY msid ORDER BY msid ASC");
		$query_projtasks->execute();
			
		echo '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<fieldset class="scheduler-border">
				<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-money" aria-hidden="true"></i> Budget Changes</legend>
				<input name="projid" type="hidden" value="'.$projid.'">
				<input name="issueid" type="hidden" value="'.$issueid.'">
				<input name="category" type="hidden" value="1">
				<input name="type" type="hidden" value="cost">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" style="width:100%">
						<thead>
							<tr id="colrow">
								<th width="5%"><strong id="colhead">SN</strong></th>
								<th width="50%">Task</th>
								<th width="15%">Status</th>
								<th width="10%">Budget</th>
								<th width="20%">Added Amount</th>
							</tr>
						</thead>
						<tbody>';
						$nm = 0;
						while($rows = $query_projtasks->fetch()){
							$nm++;
							$milestone = $rows["milestone"];
							$id = $rows["tkid"];
							$task = $rows["task"];
							$statusid = $rows["changedstatus"];
							$budget = $rows["taskbudget"];
							
							$query_status =  $db->prepare("SELECT statusname FROM tbl_task_status WHERE statusid = '$statusid'");
							$query_status->execute();		
							$row_status = $query_status->fetch();
							$status = $row_status["statusname"];
							
							echo '<tr>
								<td>'.$nm.'</td>
								<td>'.$task.'</td>
								<td>'.$status.'</td>
								<td>'.number_format($budget, 2).'</td>
								<td><input type="number" name="budget[]" class="form-control" required></td>
								<input type="hidden" name="itemid[]" value="'.$id.'">
							</tr>';
						}
						echo  '</tbody>
					</table>
				</div>
			</fieldset>
		</diV>';
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

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>