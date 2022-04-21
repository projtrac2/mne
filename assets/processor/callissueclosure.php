<?php
include_once "controller.php";

if(isset($_POST['rskid']) && !empty($_POST['rskid'])) 
{
	$rskid = $_POST["rskid"];
	
	$query_projdetails =  $db->prepare("SELECT P.projid, P.projname, P.projcategory, P.projcost, P.projstartdate, P.projenddate, I.observation, I.recommendation, I.risk_category, I.created_by, I.date_created, R.category FROM tbl_projissues I INNER JOIN tbl_projects P ON I.projid=P.projid INNER JOIN tbl_projrisk_categories R ON R.rskid=I.risk_category WHERE I.id = '$rskid'");
	$query_projdetails->execute();		
	$row_projdetails = $query_projdetails->fetch();
	$projid = $row_projdetails["projid"];
	$icat = $row_projdetails["risk_category"];
	$riskcat = $row_projdetails["category"];
	$userid = $row_projdetails["created_by"];
	
	$query_rsUsers = $db->prepare("SELECT * FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$userid'");
	$query_rsUsers->execute();
	$row_rsUsers = $query_rsUsers->fetch();
	$createdby = $row_rsUsers["title"].".".$row_rsUsers["fullname"];
	
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

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="body">
					<div class="alert alert-warning" style="height:40px">
						<h4 align="center">DETAILS</h4>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Issue Recorded:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$riskcat.	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Date Recorded:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$datecreated.	
						'</div>
					</div>
					<div class="col-md-8">
						<label><font color="#174082">Recorded By:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$createdby.	
						'</div>
					</div>
				</div>
			</div>
							
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff9ca">
				<div class="body">
					<div class="form-group">
						<div class="col-sm-12 inputGroupContainer">
							<div class="input-group">
								<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
								<textarea name="comments" id="comments" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:100%"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="projid" value="'.$projid.'"/>
			<input type="hidden" name="issueid" value="'.$rskid.'"/>
		</div>';
}
?>