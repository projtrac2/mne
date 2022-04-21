<?php
include_once "controller.php";
if(isset($_POST['rskid']) && !empty($_POST['rskid'])) 
{
	$rskid = $_POST["rskid"];
	$query_projdetails =  $db->prepare("SELECT P.projid, g.projsector, g.projdept FROM tbl_projissues I INNER JOIN tbl_projects P ON I.projid=P.projid INNER JOIN tbl_programs g on g.progid=P.progid INNER JOIN tbl_projrisk_categories R ON R.rskid=I.risk_category WHERE I.id = '$rskid'");
	$query_projdetails->execute();		
	$row_projdetails = $query_projdetails->fetch();
	$projid = $row_projdetails["projid"];
	$sector = $row_projdetails["projsector"];
	$department = $row_projdetails["projdept"];
	
	$query_managers =  $db->prepare("SELECT ptid, fullname, title FROM tbl_projteam2 t inner join tbl_role_escalation e on e.designation=t.designation WHERE e.module = 'issues' and t.ministry = '$sector' and t.department = '$department' ORDER BY t.ptid ASC");
	$query_managers->execute();

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff9ca">
				<div class="body">
					<div class="alert bg-cyan" style="height:40px">
						<h4 align="center">Escalate to Management</h4>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Higher Authority</font></label>
						<div class="form-line">
							<select name="manager" id="manager" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
								<option value="" selected="selected" class="selection">.... Select ...</option>';
								while($row_managers = $query_managers->fetch()){ 
									$ptid = $row_managers['ptid'];
									$query_userid =  $db->prepare("SELECT userid FROM users WHERE pt_id = '$ptid'");
									$query_userid->execute(); 
									$row_userid = $query_userid->fetch();
									$userid = $row_userid["userid"];
									
									echo '<option value="'.$userid.'">'.$row_managers['title'].". ".$row_managers['fullname'].'</option>';
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
