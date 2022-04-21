<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['projid']) && !empty($_POST['projid'])) 
{
	$projid = $_POST["projid"];
	
	$query_projdetails =  $db->prepare("SELECT P.projid, P.projname, P.Projexpoutcome, P.projcategory, P.projcost, P.projstartdate, P.projenddate, I.observation, I.recommendation, I.created_by, I.date_created, S.date_analysed, S.score, S.notes FROM tbl_projissues I INNER JOIN tbl_projects P ON I.projid=P.projid INNER JOIN tbl_project_riskscore S ON I.id=S.issueid WHERE P.projid = '$projid' AND I.status=2");
	$query_projdetails->execute();		
	$row_projdetails = $query_projdetails->fetch();
	$projid = $row_projdetails["projid"];
	
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
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["projname"].	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Project Outcome:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.strip_tags($row_projdetails["Projexpoutcome"]).	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Project Amount:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">Ksh.'
							.number_format($projectcost, 2).	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Project Start Date:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$startdate.	
						'</div>
					</div>
					<div class="col-md-4">
						<label><font color="#174082">Project End Date:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$enddate.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Issue Recorded:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["observation"].	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Recommendation Recorded:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["recommendation"].	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Date Recorded:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$datecreated.	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Recorded By:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["created_by"].	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Date Analysed:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$dateanalysed.	
						'</div>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Analysis Risk Level:</font></label>
						<div class="form-line" '.$style.' align="center">'
							.$risklevel.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Analysis Notes:</font></label>
						<div class="form-line" style="padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$row_projdetails["notes"].	
						'</div>
					</div>
				</div>
			</div>
		</div>';
}
?>