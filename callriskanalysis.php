<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
if(isset($_POST['rskid']) && !empty($_POST['rskid'])) 
{
	$rskid = $_POST["rskid"]; 
	
	$query_projdetails =  $db->prepare("SELECT P.projid, P.projname, P.projcategory, P.projcost, P.projstartdate, P.projenddate, I.observation, I.recommendation, I.created_by, I.date_created, I.status, S.mitigation, S.date_analysed, S.score, S.notes, R.category FROM tbl_projissues I INNER JOIN tbl_projects P ON I.projid=P.projid INNER JOIN tbl_project_riskscore S ON I.id=S.issueid INNER JOIN tbl_projrisk_categories R ON R.rskid=I.risk_category WHERE I.id = '$rskid' AND I.status<>1");
	$query_projdetails->execute();		
	$row_projdetails = $query_projdetails->fetch();
	$projid = $row_projdetails["projid"];
	$riskcat = $row_projdetails["category"];
	$mit = $row_projdetails["mitigation"];
	$issuestatus = $row_projdetails["status"];
	$userid = $row_projdetails["created_by"];
	
	if($issuestatus==3){
		$istatus="Analysed";
	}elseif($issuestatus==4){
		$istatus="Escalated";
	}elseif($issuestatus==5){
		$istatus="On Hold";
	}elseif($issuestatus==6){
		$istatus="Continue";
	}elseif($issuestatus==7){
		$istatus="Closed";
	}
	
	$query_riskmitigation =  $db->prepare("SELECT * FROM tbl_projrisk_response WHERE id = '$mit'");
	$query_riskmitigation->execute();
	$row_riskmitigation = $query_riskmitigation->fetch();
	$mitigation = $row_riskmitigation["response"];
	
	$query_rsUsers = $db->prepare("SELECT * FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$userid'");
	$query_rsUsers->execute();
	$row_rsUsers = $query_rsUsers->fetch();
	$createdby = $row_rsUsers["title"].".".$row_rsUsers["fullname"];
	
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
	$dateanalysed = date("d M Y",strtotime($row_projdetails["date_analysed"]));
	
	
	if($row_projdetails["score"] == 1){
		$risklevel = "Negligible";
		$style = 'style="padding:5px; border:#CCC thin solid; border-radius:5px; background-color:#4CAF50; color:#fff"';
	}elseif($row_projdetails["score"] == 2){
		$risklevel = "Minor";
		$style = 'style="padding:5px; border:#CCC thin solid; border-radius:5px; background-color:#CDDC39; color:#fff"';
	}elseif($row_projdetails["score"] == 3){
		$risklevel = "Moderate";
		$style = 'style="padding:5px; border:#CCC thin solid; border-radius:5px; background-color:#FFEB3B; color:#000"';
	}elseif($row_projdetails["score"] == 4){
		$risklevel = "Significant";
		$style = 'style="padding:5px; border:#CCC thin solid; border-radius:5px; background-color:#FF9800; color:#fff"';
	}elseif($row_projdetails["score"] == 5){
		$risklevel = "Severe";
		$style = 'style="padding:5px; border:#CCC thin solid; border-radius:5px; background-color:#F44336; color:#fff"';
	}

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="body">
					<div class="alert bg-brown" style="height:40px">
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
						<label><font color="#174082">Issue:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$riskcat.	
						'</div>
					</div>
					<div class="col-md-6">
						<label><font color="#174082">Issue Status:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$istatus.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Issue Description:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["observation"].	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Date Recorded:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$datecreated.	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Recorded By:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$createdby.	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Date Analysed:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$dateanalysed.	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Issue Severity Level:</font></label>
						<div class="form-line" '.$style.' align="center">'
							.$risklevel.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Issue Mitigation Method:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$mitigation.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Analysis Notes:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["notes"].	
						'</div>
					</div>
				</div>
			</div>
		</div>';
}
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>