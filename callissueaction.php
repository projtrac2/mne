<?php
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['rskid']) && !empty($_POST['rskid'])) 
{
	$rskid = $_POST["rskid"];
	
	$query_projdetails =  $db->prepare("SELECT P.projid, P.projname, P.projcategory, P.projcost, P.projstartdate, P.projenddate, I.observation, I.risk_category, I.created_by, I.date_created, R.category FROM tbl_projissues I INNER JOIN tbl_projects P ON I.projid=P.projid INNER JOIN tbl_projrisk_categories R ON R.rskid=I.risk_category WHERE I.id = '$rskid'");
	$query_projdetails->execute();		
	$row_projdetails = $query_projdetails->fetch();
	$projid = $row_projdetails["projid"];
	$icat = $row_projdetails["risk_category"];
	$riskcat = $row_projdetails["category"];
	$userid = $row_projdetails["created_by"];
	
	$query_riskmitigation =  $db->prepare("SELECT * FROM tbl_projrisk_response WHERE cat = '$icat'");
	$query_riskmitigation->execute();		
	
	if($row_projdetails["projcategory"]==2){
		$query_projtender =  $db->prepare("SELECT tenderamount, startdate, enddate  FROM tbl_tenderdetails WHERE projid = '$projid'");
		$query_projtender->execute();		
		$row_projtender = $query_projtender->fetch();
		
		$projectcost = $row_projtender["tenderamount"];
		$startdate = date("d M Y",strtotime($row_projtender["startdate"]));
		$enddate = date("d M Y",strtotime($row_projtender["enddate"]));
	}else{
		$projectcost = $row_projdetails["projcost"];
		$startdate = date("d M Y",strtotime($row_projdetails["projstartdate"]));
		$enddate = date("d M Y",strtotime($row_projdetails["projenddate"]));
	}
	
	$current_date = date("Y-m-d");
	$datecreated = date("d M Y",strtotime($row_projdetails["date_created"]));
	
	$query_rsPMembers =  $db->prepare("SELECT pmid, ptid FROM tbl_projmembers WHERE projid = '$projid' ORDER BY pmid ASC");
	$query_rsPMembers->execute();		
	$row_rsPMembers = $query_rsPMembers->fetch();
	
	$query_issuepriority =  $db->prepare("SELECT * FROM tbl_priorities where status=1");
	$query_issuepriority->execute();		
	
	$query_user =  $db->prepare("SELECT title, fullname FROM tbl_projteam2 t INNER JOIN users u ON t.ptid=u.pt_id WHERE userid = '$userid'");
	$query_user->execute();		
	$row_user = $query_user->fetch();

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="body">
					<div class="alert alert-warning" style="height:40px">
						<h4 align="center">DETAILS</h4>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Project Name:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["projname"].	
						'</div>
					</div>';
					/* <div class="col-md-12">
						<label><font color="#174082">Project Outcome:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.strip_tags($row_projdetails["Projexpoutcome"]).	
						'</div>
					</div> */
					echo '<div class="col-md-4">
						<label><font color="#174082">Project Amount:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">Ksh.'
							.number_format($projectcost, 2).	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Project Start Date:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$startdate.	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Project End Date:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$enddate.	
						'</div>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Issue Recorded:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$riskcat.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Issue Description:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["observation"].	
						'</div>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Date Recorded:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$datecreated.	
						'</div>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Recorded By:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_user["title"].'.'.$row_user["fullname"].	
						'</div>
					</div>
				</div>
			</div>
							
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff9ca">
				<div class="body">
					<div class="alert bg-green" style="height:40px">
						<h4 align="center">Assign Owner</h4>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Members:</font></label>
						<div class="form-line">
							<select name="owner" id="owner" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
								<option value="" selected="selected" class="selection">Select Issue Owner</option>';
								do { 
									$ptid = $row_rsPMembers['ptid'];
									
									$query_user =  $db->prepare("SELECT t.*, u.userid FROM users u LEFT JOIN tbl_projteam2 t ON t.ptid=u.pt_id WHERE u.userid = '$ptid' ORDER BY u.userid ASC");
									$query_user->execute();		
									$row_user = $query_user->fetch(); 
									echo '<option value="'.$row_user['userid'].'">'.$row_user['title'].". ".$row_user['fullname'].'</option>';
								} while ($row_rsPMembers = $query_rsPMembers->fetch());
							echo '</select>
						</div>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Issue Priority:</font></label>
						<div class="form-line">
							<select name="priority" id="priority" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
								<option value="" selected="selected" class="selection">Select Issue Priority</option>';
								while ($rows = $query_issuepriority->fetch()){  
									echo '<option value="'.$rows['id'].'">'.$rows['priority'].'</option>';
								}
							echo '</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 inputGroupContainer">
							<div class="input-group">
								<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
								<textarea name="comments" id="notes" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:100%"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="projid" value="'.$projid.'"/>
			<input type="hidden" name="issueid" value="'.$rskid.'"/>
		</div>';
}

} catch (\Throwable $th) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>