<?php
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['projid']) && !empty($_POST['projid'])) 
{
	$projid = $_POST["projid"];
	
	$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus FROM tbl_projects WHERE projid = '$projid'");
	$query_projstatuschange->execute();		
	$row_projstatuschange = $query_projstatuschange->fetch();
	$projstatus = $row_projstatuschange["projstatus"];
	$projcode = $row_projstatuschange["projcode"];
	
	if($projstatus=="On Hold"){
		$projchangedstatus = $row_projstatuschange["projchangedstatus"];
		$projstatusselect =	'
		<div class="col-md-6">
			<label><font color="#174082">Project Status Action:</font></label>
			<div class="form-line">
				<select name="projstatuschange" id="projstatuschange" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
					<option value="" selected="selected" class="selection">... Select ...</option>
					<option value="'.$projchangedstatus.'">Restore Project</option>
					<option value="Cancelled">Cancel Project</option>
				</select>
			</div>
		</div>
		<div class="col-md-6">
		</div>';
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
		</div>
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
	}

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff9ca">
				<div class="body">
					<div class="alert bg-lime" style="height:35px; padding-top:0px">
						<h5 align="center">Please Select Project Status </h5>
					</div>
					'.$projstatusselect.'	
					<div class="col-md-12">
						<label><font color="#174082">Comments:</font></label>
						<div class="form-line">                 
							<textarea name="notes" id="notes" cols="60" rows="5" class="form-control" style="padding:5px; font-size:13px; color:#000; width:99.5%" required></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Select and attach a file:</font></label>
						<div class="form-line">                 
							<input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="projid" value="'.$projid.'"/>
			<input type="hidden" name="projchangedstatus" value="'.$projchangedstatus.'"/>
			<input type="hidden" name="projcode" value="'.$projcode.'"/>
		</div>';
}
} catch (\PDOException $th) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());

}
?>