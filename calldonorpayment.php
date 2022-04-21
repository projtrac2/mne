<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['gntid']) && !empty($_POST['gntid'])) 
{
	$gntid = $_POST["gntid"];
	
	$query_dndetails =  $db->prepare("SELECT donationcode, comments, receivedby FROM tbl_donor_grants WHERE gtid = '$gntid'");
	$query_dndetails->execute();		
	$row_dndetails = $query_dndetails->fetch();
	$donationcode = $row_dndetails["donationcode"];
	$comments = $row_dndetails["comments"];
	$receivedby = $row_dndetails["receivedby"];
	
	$query_dnfiles =  $db->prepare("SELECT * FROM tbl_files WHERE catid = '$gntid' and fcategory='Donation'");
	$query_dnfiles->execute();
	
	$current_date = date("Y-m-d");
	$datecreated = date("d M Y",strtotime($row_projdetails["date_created"]));

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="body">
					<div class="alert alert-warning" style="height:40px">
						<h4 align="center">DETAILS</h4>
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Donor Grant Code:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$donationcode.	
						'</div>
					</div>
					<div class="col-md-6">
					</div>
					<div class="col-md-3">
						<label><font color="#174082">Grant Received By:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.$receivedby.	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Donation Comments:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">'
							.strip_tags($comments).	
						'</div>
					</div>
					<div class="col-md-12">
						<label><font color="#174082">Donation Files/Documents:</font></label>
						<div class="form-line" style="background-color:#FFF; padding:5px; border:#CCC thin solid; border-radius:5px">';
						$nm = 0;
						while($dnfiles = $query_dnfiles->fetch()){
							$nm = $nm + 1;
							echo 'File '.$nm. ': <img src="images/files.png" alt="task" /><a href="'.$dnfiles["floc"].'" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new">'.$dnfiles["filename"].'</a>';
						}							
						echo '</div>
					</div>
				</div>
			</div>
		</div>';
}
?>