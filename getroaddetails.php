<?php
try {
	//code...

	//include_once 'projtrac-dashboard/resource/session.php';
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';
    
	if(isset($_POST['getdetails']))
	{ 
		$getdetails = $_POST['getdetails'];
		$query_details = $db->prepare("SELECT tbl_projects.*, tbl_contractor.contractor_name, tbl_myprojfunding.sourcecategory AS SCat, tbl_myprojfunding.sourceid AS sourceid, tbl_projects.projname AS projname, sum(tbl_payments_disbursed.amountpaid) AS amntpaid, tbl_state.state AS subcounty FROM tbl_projects INNER JOIN tbl_state ON tbl_state.id = tbl_projects.projcommunity INNER JOIN tbl_contractor ON tbl_contractor.contrid = tbl_projects.projcontractor INNER JOIN tbl_payments_request ON tbl_projects.projid=tbl_payments_request.projid INNER JOIN tbl_payments_disbursed ON tbl_payments_disbursed.reqid = tbl_payments_request.id INNER JOIN tbl_myprojfunding ON tbl_projects.projid=tbl_myprojfunding.projid WHERE tbl_projects.projid ='$getdetails' AND tbl_projects.deleted='0'");
		$query_details->execute();		
		$row = $query_details->fetch();
		
		
		$query_projleader = $db->prepare("SELECT tbl_projteam.* FROM tbl_projmembers INNER JOIN tbl_projteam ON tbl_projmembers.ptid = tbl_projteam.ptid WHERE tbl_projmembers.projid ='$getdetails'");
		$query_projleader->execute();		
		$projleader = $query_projleader->fetch();
		
/* 		$SCat = $row['SCat'];
		$sourceid = $row['sourceid'];
		
		if($SCat == "donor"){
			$query_wardDetails = $db->prepare("SELECT state FROM tbl_donors WHERE dnid ='$sourceid'");

			$query_wardDetails->execute();		
			$ward= $query_wardDetails->fetch();
			$prjward = $ward['state'];			
		} else{
			$query_wardDetails = $db->prepare("SELECT state FROM tbl_state INNER JOIN tbl_projects ON tbl_projects.projlga = tbl_state.id WHERE tbl_projects.projid ='$getdetails'");

			$query_wardDetails->execute();		
			$ward= $query_wardDetails->fetch();
			$prjward = $ward['state'];			
		} */
		
		$sdate = strtotime($row['projstartdate']);
		$projstartdate = date("d M Y",$sdate);
											
		$edate = strtotime($row['projenddate']);
		$projenddate = date("d M Y",$edate);

		$query_wardDetails = $db->prepare("SELECT state FROM tbl_state INNER JOIN tbl_projects ON tbl_projects.projlga = tbl_state.id WHERE tbl_projects.projid ='$getdetails'");

		$query_wardDetails->execute();		
		$ward= $query_wardDetails->fetch();
		$prjward = $ward['state'];
     
		echo  '<div class="col-md-6">
					<h4><u>Main Details</u></h4>
					<ul>
						<li><b style="color:#3F51B5">Name:</b> '.$row["projname"] .'</li>
						<li><b style="color:#3F51B5">Type Of Road:</b> '.$row['projtype'] .'</li>
						<li><b style="color:#3F51B5">Start:</b> '.$projstartdate.' </li>
						<li><b style="color:#3F51B5">End:</b> '.$projenddate.'</li>					
						<li><b style="color:#3F51B5">Subcounty:</b> '.$row['subcounty'] .'</li>
						<li><b style="color:#3F51B5">Ward:</b> '.$prjward .'</li>
					</ul>
                </div> 
                <div class="col-md-3">
					<h4><u>Funds Details</u></h4>
					<ul>
						<li><b style="color:#3F51B5">FundedBy:</b> '.$row['sourcecategory'] .'-</li>
						<li><b style="color:#3F51B5">Budget:</b> Ksh.'. number_format($row['projcost'], 2) .'</li>
						<li><b style="color:#3F51B5">Cost:</b> Ksh.'. number_format($row['amntpaid'], 2) .'</li>
						<li><b style="color:#3F51B5">Status:</b> '.$row['projstatus'] .'</li>
						<li><b style="color:#3F51B5">Progress:</b> '.$row['projenddate'] .'</li>
					</ul>
                </div>
                
                <dic class="col-md-3">
					<h4><u>Responsible Authorities</u></h4>
					<ul>
						<li><b style="color:#3F51B5">Incharge:</b> '.$projleader['title'] .'. '.$projleader['fullname'].'</li>
						<li><b style="color:#3F51B5">Contractor:</b> '.$row['contractor_name'] .'</li>
					</ul>
                </div>
            ';
	}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>