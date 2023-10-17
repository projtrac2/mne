<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "Escalated Issue Action",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

//if ($permission) {	
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	$username = $user_name;
	$projstage = 10;
	$actiondate = date("Y-m-d");

	if (isset($_GET['issueid'])) {
		$issueid = $_GET['issueid'];
	}
	
	$action = "Add";
	$submitAction = "MM_insert";
	$formName = "addterminology";
	$submitValue = "Submit";
	
	if ((isset($_POST["issueid"])) && !empty($_POST["issueid"])) {
		if (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Continue"){	
			$projissue = $_POST["issueid"];
			$project = $_POST["projid"];
			$comments = $_POST["comments"];
			$projissuename = $_POST["issuename"];
			$subject = "Issue resolved and project to continue";
			$stage = 4;
			$user = $_POST["user_name"];
			$origstatus = $_POST["projstatus"];
			$evaluation = $_POST["evaluation"];
			//$escalator = 45;
			$escalator = $_POST["escalator"];
			$actiondate = date("Y-m-d");
			$changedon = date("Y-m-d H:i:s");
			$issuemessage = "The committee have resolved the issue based on the below details:";
			//$catid =$last_id;

			$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid, :rskid, :stage, :comments, :user, :date)");	
			$insertSQL->execute(array(':projid' => $project, ':rskid' => $projissue, ':stage' => $stage, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));
			$formid = $db->lastInsertId();
					
			if(!empty($_FILES['attachment']['name'])) {
				$filecategory = "Issue";
				$reason = "Project Committee Action: ".$subject;
				return;
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['attachment']['name']);
				$ext = substr($filename, strrpos($filename, '.') + 1);
				if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")){
					$newname=$project."-".$projissue."-".$filename;
					$filepath="uploads/projissue/".$newname;       
					//Check if the file with the same name already exists in the server
					if (!file_exists($filepath)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['attachment']['tmp_name'],$filepath)) {
							//successful upload
							$fname = $newname;	
							
							$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `form_id`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :formid, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
							$queryinsert->execute(array(':projid' => $project, ':stage' => $projissue, ':formid' => $formid, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
						}	
					}
					else{ 
						$msg = 'File you are uploading already exists, try another file!!';
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
					} 		  
				}
				else{  
					$msg = 'This file type is not allowed, try another file!!';
					$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
				}		
			}
			else{  
				$msg = 'Please attach a file and try again!!';
				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Error!\",
					text: \" $msg \",
					icon: 'warning',
					dangerMode: true,
					timer: 5000,
					showConfirmButton: false });
				</script>";
			}
		
			$query_details =  $db->prepare("SELECT projname, r.category, v.name, i.owner, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$projissue' and i.status=4");
			$query_details->execute();		
			$row_details = $query_details->fetch();
			$projectname = $row_details["projname"];
			$issue = $row_details["category"];
			$severity = $row_details["name"];
			$owner = $row_details["owner"];
			$recorder = $row_details["created_by"];
			
			$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$owner' or userid='$escalator'");
			$query_userowner->execute();
			
			$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
			$query_url->execute();		
			$row_url = $query_url->fetch();
			$url = $row_url["main_url"];
			$org = $row_url["company_name"];
			$org_email = $row_url["email_address"];
			
			$issuestatus = 6;
			$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
			$updatest = $updateQuery->execute(array(':status' => $issuestatus, ':projid' => $project, ':issueid' => $projissue));	
			
			$query_update_esc = $db->prepare("UPDATE tbl_escalations SET date_continue=:date WHERE projid=:projid and itemid=:issueid");
			$query_update_esc->execute(array(':date' => $actiondate, ':projid' => $project, ':issueid' => $projissue));
			
			while($row = $query_userowner->fetch()){
				$iowner = $row["userid"];	
				// Comments link back to the system 
				$issuelink = '<a href="'.$url.'projectissuescomments?issueid='.$projissue.'&stage=6&owner='.$iowner.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';		
				
				$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
				$receipient = $row["email"];
				
				require_once("project-committee-issue-action-email.php");
				require 'PHPMailer/PHPMailerAutoload.php';	
				require("email-conf-settings.php");
			}
			$msg = 'Project issue comments successfully updated!!';
			$results = "<script type=\"text/javascript\">
				swal({
				title: \"Success!\",
				text: \" $msg \",
				icon: 'success',
				dangerMode: false,
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'projects-escalated-issues';
				}, 3000);
			</script>";
		}
		elseif(isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "On Hold"){
			$projissue = $_POST["issueid"];
			$project = $_POST["projid"];
			$comments = $_POST["comments"];
			$projissuename = $_POST["issuename"];
			$subject = "Project On Hold";
			$stage = 4;
			$user = $_POST["user_name"];
			//$escalator = 45;
			$escalator = $_POST["escalator"];
			$newstatus = 6;
			$origstatus = $_POST["projstatus"];
			$assessment = $_POST["assessment"];
			$actiondate = date("Y-m-d");
			$changedon = date("Y-m-d H:i:s");
			$issuemessage = "The committee has decided to put the issue and the project <strong>ON HOLD</strong> based on the below reasons:";
			//$catid =$last_id;
	
			$query_project =  $db->prepare("SELECT projcategory, projcost, projenddate FROM tbl_projects where projid='$project'");
			$query_project->execute();		
			$row_project = $query_project->fetch();
			$projcategory = $row_project["projcategory"];
			$projcost = $row_project["projcost"];

			$query_origenddate =  $db->prepare("SELECT MAX(edate) as edate FROM `tbl_task` WHERE projid=:projid");
			$query_origenddate->execute(array(":projid"=>$project));
			$row_origenddate = $query_origenddate->fetch();
			$count_origenddate = $query_origenddate->rowCount();
			$origenddate = $row_origenddate["edate"];
			
			$origtarget = 45;
			
			if($projcategory==1){
				$query_origcost =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM `tbl_project_direct_cost_plan` WHERE projid=:projid");
				$query_origcost->execute(array(":projid"=>$project));
				$row_origcost = $query_origcost->fetch();
				
				$origcost = $row_origcost["cost"] * $row_origcost["units"];
			} else{
				$query_projtendercost =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid=:projid");
				$query_projtendercost->execute(array(":projid"=>$project));		
				$row_projtendercost = $query_projtendercost->fetch();
				$tenderamount = $row_projtendercost["tenderamount"];
				
				$query_projothercost =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM tbl_project_direct_cost_plan where projid=:projid and tasks IS NULL");
				$query_projothercost->execute(array(":projid"=>$project));		
				$row_projothercost = $query_projothercost->fetch();
				$othercosts = $row_projothercost["cost"] * $row_projothercost["units"];
				$origcost =$tenderamount + $othercosts;
			}
			
			$insertquery = $db->prepare("INSERT INTO tbl_projstatuschangereason (projid, status, reason, originalcost, originalenddate, originaltarget, entered_by, date_entered) VALUES (:projid, :status, :reason, :origcost, :origenddate, :origtarget, :user, :date)");
			$insertquery->execute(array(':projid' => $project, ':status' => $origstatus, ':reason' => $projissuename, ':origcost' => $origcost, ':origenddate' => $origenddate, ':origtarget' => $origtarget, ':user' => $user_name, ':date' => $changedon));
			
			$last_record = $db->lastInsertId();

			$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid, :rskid, :stage, :comments, :user, :date)");
			$insertSQL->execute(array(':projid' => $project, ':rskid' => $projissue, ':stage' => $stage, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));
			
			if($assessment==1){
				$insertSQL = $db->prepare("UPDATE tbl_projissues SET assessment = :assessment WHERE projid = :projid AND id = :issueid");
				$insertSQL->execute(array(':assessment' => $assessment, ':projid' => $project, ':issueid' => $issueid));
			}
					
			if(!empty($_FILES['attachment']['name'])) {
				$filecategory = "Issue";
				$reason = "Project Committee Action: ".$subject;
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['attachment']['name']);
				$ext = substr($filename, strrpos($filename, '.') + 1);

				if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload"))  {
					$newname=$project."-".$projissue."-".$filename;
					$filepath="uploads/projissue/".$newname;       
					//Check if the file with the same name already exists in the server
					if (!file_exists($filepath)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['attachment']['tmp_name'],$filepath)) {
							//successful upload
							$fname = $newname;	
							
							$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `form_id`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :formid, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
							$queryinsert->execute(array(':projid' => $project, ':stage' => $projstage, ':formid' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
						}	
					}
					else{ 
						$msg = 'File you are uploading already exists, try another file!!';
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
					} 		  
				}
				else{  
					$msg = 'This file type is not allowed, try another file!!';
					$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
				}		
			}
			 
			$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, projchangedstatus=:projchangedstatus WHERE projid=:projid");
			$updated = $updateQuery->execute(array(':projstatus' => $newstatus, ':projchangedstatus' => $origstatus, ':projid' => $project));	
			
			if($updated){
				$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$project' AND status<>5");
				$query_rsOrigMilestone->execute();
				
				while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()){
					$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
					$OrigMilestoneStatus =  $row_rsOrigMilestone['status'];
					
					$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:status, changedstatus=:changedstatus, changedby=:user, datechanged=:date WHERE msid=:msid");
					$updateMilestone = $updateQuery->execute(array(':status' => $newstatus, ':changedstatus' => $OrigMilestoneStatus, ':user' => $user_name, ':date' => $changedon, ':msid' => $OrigMilestoneID));
					
					if($updateMilestone){					
						$query_rsMilestone =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
						$query_rsMilestone->execute();

						while ($row_rsMilestone = $query_rsMilestone->fetch()){
							$row_rsOrigTaskID = $row_rsMilestone["tkid"];
							$row_rsOrigTask = $row_rsMilestone["status"];
							
							$SQLUpdates = $db->prepare("UPDATE tbl_task SET status=:status, changedstatus=:changedstatus WHERE tkid=:tkid");
							$SQLUpdates->execute(array(':status' => $newstatus, ':changedstatus' => $row_rsOrigTask, ':tkid' => $row_rsOrigTaskID));
						}
					}
				}
			}
		
			$query_details =  $db->prepare("SELECT projname, assessment, r.category, v.name, i.owner, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$projissue' and i.status=4");
			$query_details->execute();		
			$row_details = $query_details->fetch();
			$projectname = $row_details["projname"];
			$issue = $row_details["category"];
			$severity = $row_details["name"];
			$owner = $row_details["owner"];
			$assessment = $row_details["assessment"];
			$recorder = $row_details["created_by"];
			$projeval = "";
			
			if($assessment == 1){		
				$projeval = " <br><strong>Please NOTE Issue Assessment/Evaluation is required for this project</strong>";
			}
			
			$status = 5;
			$insertSQL = $db->prepare("UPDATE tbl_projissues SET status = :status WHERE projid = :projid AND id = :issueid");
			$insertSQL->execute(array(':status' => $status, ':projid' => $project, ':issueid' => $issueid));
				
			$query_update_esc = $db->prepare("UPDATE tbl_escalations SET date_on_hold=:date WHERE projid=:projid and itemid=:issueid");
			$query_update_esc->execute(array(':date' => $actiondate, ':projid' => $project, ':issueid' => $projissue));
			
			$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid='$escalator'");
			$query_userowner->execute();
			
			$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
			$query_url->execute();		
			$row_url = $query_url->fetch();
			$url = $row_url["main_url"];
			$org = $row_url["company_name"];
			$org_email = $row_url["email_address"];	
			
			while($row = $query_userowner->fetch()){
				$iowner = $row["userid"];	
				// Comments link back to the system 
				$issuelink = '<a href="'.$url.'projectissuescomments?issueid='.$projissue.'&stage=6&owner='.$iowner.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';
				
				$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
				$receipient = $row["email"];
				
				require_once("project-committee-issue-action-email.php");
				require 'PHPMailer/PHPMailerAutoload.php';	
				require("email-conf-settings.php");
			}
			
			$msg = 'Project has successfully been put On Hold!!';
			$results = "<script type=\"text/javascript\">
				swal({
				title: \"Success!\",
				text: \" $msg \",
				icon: 'success',
				dangerMode: false,
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'projects-escalated-issues';
				}, 3000);
			</script>";
		}
		elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Cancelled"){
			$projissue = $_POST["issueid"];
			$project = $_POST["projid"];
			$comments = $_POST["comments"];
			$projissuename = $_POST["issuename"];
			$subject = "Project to continue";
			$stage = 4;
			$user = $_POST["user_name"];
			//$escalator = 45;
			$escalator = $_POST["escalator"];
			$newstatus = $_POST["projstatuschange"];
			$origstatus = $_POST["projstatus"];
			$evaluation = $_POST["evaluation"];
			$actiondate = date("Y-m-d");
			$changedon = date("Y-m-d H:i:s");
			$issuemessage = "The committee has decided to <strong>CANCEL</strong> the project based on the below reasons:";
			//$catid =$last_id;
	
			$query_project =  $db->prepare("SELECT projcategory, projcost, projenddate FROM tbl_projects where projid='$project'");
			$query_project->execute();		
			$row_project = $query_project->fetch();
			$projcategory = $row_project["projcategory"];
			$projcost = $row_project["projcost"];
			$origenddate = $row_project["projenddate"];
			
			if($projcategory==1){
				$origcost = $projcost;
			}else{
				$query_projtendercost =  $db->prepare("SELECT tenderamount FROM tbl_tenderdetails where projid='$project'");
				$query_projtendercost->execute();		
				$row_projtendercost = $query_projtendercost->fetch();
				$tenderamount = $row_projtendercost["tenderamount"];
				$origcost =$tenderamount;
			}
			
			$insertquery = $db->prepare("INSERT INTO tbl_projstatuschangereason (projid, status, reason, originalcost, originalenddate, originaltarget, entered_by, date_entered) VALUES (:projid, :status, :reason, :origcost, :origenddate, :origtarget, :user, :date)");
			$insertquery->execute(array(':projid' => $project, ':status' => $origstatus, ':reason' => $projissuename, ':origcost' => $origcost, ':origenddate' => $origenddate, ':origtarget' => $origtarget, ':user' => $user_name, ':date' => $changedon));
			
			$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid, :rskid, :stage, :comments, :user, :date)");
			$insertSQL->execute(array(':projid' => $project, ':rskid' => $projissue, ':stage' => $stage, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));
			$formid = $db->lastInsertId();
					
			if(!empty($_FILES['attachment']['name'])) {
				$filecategory = "Issue";
				$reason = "Project Committee Action: ".$subject;
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['attachment']['name']);
				$ext = substr($filename, strrpos($filename, '.') + 1);
				if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload"))  {
					$newname=$project."-".$projissue."-".$filename;
					$filepath="uploads/projissue/".$newname;       
					//Check if the file with the same name already exists in the server
					if (!file_exists($filepath)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['attachment']['tmp_name'],$filepath)) {
							//successful upload
							$fname = $newname;	
							
							$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `form_id`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :formid, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
							$queryinsert->execute(array(':projid' => $project, ':stage' => $projissue, ':formid' => $formid, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
						}	
					}
					else{ 
						$msg = 'File you are uploading already exists, try another file!!';
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
					} 		  
				}
				else{  
					$msg = 'This file type is not allowed, try another file!!';
					$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
				}
			}
			
			$issuestatus = 5;
			$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
			$updatest = $updateQuery->execute(array(':status' => $issuestatus, ':projid' => $project, ':issueid' => $projissue));	
			 
			$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, projchangedstatus=:projchangedstatus,  projevaluate=:eval, deleted_by=:user, date_deleted=:date WHERE projid=:projid");
			$updatest = $updateQuery->execute(array(':projstatus' => $newstatus, ':projchangedstatus' => $origstatus, ':eval' => $evaluation, ':user' => $user_name, ':date' => $changedon, ':projid' => $project));	
			
			if($updatest){
				$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$project'");
				$query_rsOrigMilestone->execute();
				
				while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()){
					$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
					$OrigMilestoneStatus =  $row_rsOrigMilestone['status'];
					
					$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:status, changedstatus=:changedstatus, changedby=:user, datechanged=:date WHERE msid=:msid");
					$UpdateMil = $updateQuery->execute(array(':status' => $newstatus, ':changedstatus' => $OrigMilestoneStatus, ':user' => $user_name, ':date' => $changedon, ':msid' => $OrigMilestoneID));
					
					if($UpdateMil){					
						$query_rsMilestone =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
						$query_rsMilestone->execute();

						while ($row_rsMilestone = $query_rsMilestone->fetch()){
							$row_rsOrigTaskID = $row_rsMilestone["tkid"];
							$row_rsOrigTask = $row_rsMilestone["status"];
							
							$SQLUpdates = $db->prepare("UPDATE tbl_task SET status=:status, changedstatus=:changedstatus WHERE tkid=:tkid");
							$SQLUpdates->execute(array(':status' => $newstatus, ':changedstatus' => $row_rsOrigTask, ':tkid' => $row_rsOrigTaskID));
						}
					}
				}
			}
		
			$query_details =  $db->prepare("SELECT projname, r.category, v.name, i.owner, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$projissue' and i.status=6");
			$query_details->execute();		
			$row_details = $query_details->fetch();
			$projectname = $row_details["projname"];
			$issue = $row_details["category"];
			$severity = $row_details["name"];
			$owner = $row_details["owner"];
			$recorder = $row_details["created_by"];
			
			$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid='$escalator'");
			$query_userowner->execute();
				
			$query_update_esc = $db->prepare("UPDATE tbl_escalations SET date_cancelled=:date WHERE projid=:projid and itemid=:issueid");
			$query_update_esc->execute(array(':date' => $actiondate, ':projid' => $project, ':issueid' => $projissue));
			
			$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
			$query_url->execute();		
			$row_url = $query_url->fetch();
			$url = $row_url["main_url"];
			$org = $row_url["company_name"];
			$org_email = $row_url["email_address"];	
			
			while($row = $query_userowner->fetch()){
				$iowner = $row["userid"];	
				// Comments link back to the system 
				$issuelink = '<a href="'.$url.'projectissuescomments?issueid='.$projissue.'&stage=6&owner='.$iowner.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';
				
				$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
				$receipient = $row["email"];
				
				require_once("project-committee-issue-action-email.php");
				require 'PHPMailer/PHPMailerAutoload.php';	
				require("email-conf-settings.php");
			}
			
			$msg = 'Project successfully cancelled!!';
			$results = "<script type=\"text/javascript\">
				swal({
				title: \"Success!\",
				text: \" $msg \",
				icon: 'warning',
				dangerMode: false,
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'projects-escalated-issues';
				}, 3000);
			</script>";
		}
		elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Restore"){
			$projissue = $_POST["issueid"];
			$project = $_POST["projid"];
			$comments = $_POST["comments"];
			$projissuename = $_POST["issuename"];
			$subject = "Project Restored to Continue";
			$stage = 5;
			$user = $_POST["user_name"];
			$newstatus = $_POST["prevstatus"];
			$origstatus = $_POST["projstatus"];
			$costchange = $_POST["costopt"];
			$timechange = $_POST["timelineopt"];
			//$escalator = 45;
			$escalator = $_POST["escalator"];
			$evaluation = $_POST["evaluation"];
			$actiondate = date("Y-m-d");
			$changedon = date("Y-m-d H:i:s");
			if($costchange==1 && $timechange==1){
				$changesmessage = "Both project budget and timelines increased.";
			}elseif($costchange==1 && $timechange==0){
				$changesmessage = "The project budget increased.";
			}elseif($costchange==0 && $timechange==1){
				$changesmessage = "The project timelines increased.";
			}elseif($costchange==0 && $timechange==0){
				$changesmessage = "There is no change in both the project budget and the timeline.";
			}
							
			$query_status =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$newstatus'");
			$query_status->execute();		
			$row_status = $query_status->fetch();
			$newstatusname = $row_status["statusname"];
			
			$issuemessage = "The committee has decided to <strong>Restore</strong> the project to its previous status (".$newstatusname.").<br> The Project parameters as been affected as follows: ".$changesmessage;
			$formid = 0;

			$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid, :rskid, :stage, :comments, :user, :date)");
			$insertSQL->execute(array(':projid' => $project, ':rskid' => $projissue, ':stage' => $stage, ':comments' => $issuemessage, ':user' => $user_name, ':date' => $actiondate));
					
			if(!empty($_FILES['attachment']['name'])) {
				$filecategory = "Issue";
				$reason = "Project Committee Action: ".$subject;
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['attachment']['name']);
				$ext = substr($filename, strrpos($filename, '.') + 1);
				if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload"))  {
					$newname=$project."-".$projissue."-".$filename;
					$filepath="uploads/projissue/".$newname;       
					//Check if the file with the same name already exists in the server
					if (!file_exists($filepath)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['attachment']['tmp_name'],$filepath)) {
							//successful upload
							$fname = $newname;	
							
							$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `form_id`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :formid, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
							$queryinsert->execute(array(':projid' => $project, ':stage' => $projissue, ':formid' => $formid, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
						}	
					}
					else{ 
						$msg = 'File you are uploading already exists, try another file!!';
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
					} 		  
				}
				else{  
					$msg = 'This file type is not allowed, try another file!!';
					$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
				}
			}
			
			$projevaluate = 0;
			$issuestatus = 6;
			$escstatus = 2;
			$cat = "issue";
			
			$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
			$updatest = $updateQuery->execute(array(':status' => $issuestatus, ':projid' => $project, ':issueid' => $projissue));	
			
			$updateQuery = $db->prepare("UPDATE tbl_escalations SET status=:status WHERE projid=:projid and itemid=:issueid and category=:cat");
			$updateQuery->execute(array(':status' => $issuestatus, ':projid' => $project, ':issueid' => $projissue, ':cat' => $cat));
			
			$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projorigstatus, projevaluate=:projevaluate, deleted_by=:user, projstatusrestorereason=:restorereason, date_deleted=:date WHERE projid=:projid");
			$update = $updateQuery->execute(array(':projorigstatus' => $newstatus, ":projevaluate" => $projevaluate, ':user' => $user_name, ':restorereason' => $comments, ':date' => $changedon, ':projid' => $project));
			
			if($update){
				$query_rsOrigMilestone =  $db->prepare("SELECT * FROM tbl_milestone WHERE projid = '$project'");
				$query_rsOrigMilestone->execute();		
			
				while ($row_rsOrigMilestone = $query_rsOrigMilestone->fetch()){
					$OrigMilestoneID =  $row_rsOrigMilestone['msid'];
					$OrigMilestoneStatus =  $row_rsOrigMilestone['changedstatus'];
			
					$updateQuery = $db->prepare("UPDATE tbl_milestone SET status=:origmilestonestatus, changedby=:user, datechanged=:date WHERE msid=:msid");
					$UpdateMst = $updateQuery->execute(array(':origmilestonestatus' => $OrigMilestoneStatus, ':user' => $user_name, ':date' => $changedon, ':msid' => $OrigMilestoneID));
					
					if($UpdateMst){					
						$query_rsOrigTask =  $db->prepare("SELECT * FROM tbl_task WHERE msid = '$OrigMilestoneID'");
						$query_rsOrigTask->execute();

						while ($row_rsOrigTask = $query_rsOrigTask->fetch()){
							$OrigTaskId = $row_rsOrigTask["tkid"];
							$OrigTask = $row_rsOrigTask["changedstatus"];
							//$taskStatus = "On Hold Task";
			
							$updateQuery = $db->prepare("UPDATE tbl_task SET status=:origtask, changedby=:user, datechanged=:date WHERE tkid=:tkid");
							$updateQuery->execute(array(':origtask' => $OrigTask, ':user' => $user_name, ':date' => $changedon, ':tkid' => $OrigTaskId));
						}
					}
				}
			}
				
			$query_update_esc = $db->prepare("UPDATE tbl_escalations SET date_continue=:date WHERE projid=:projid and itemid=:issueid");
			$query_update_esc->execute(array(':date' => $actiondate, ':projid' => $project, ':issueid' => $projissue));
		
			$query_details =  $db->prepare("SELECT projname, r.category, v.name, i.owner, i.created_by, i.escalated_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_categories r on r.rskid=i.risk_category WHERE i.id = '$projissue' and i.status=5");
			$query_details->execute();		
			$row_details = $query_details->fetch();
			$projectname = $row_details["projname"];
			$issue = $row_details["category"];
			$severity = $row_details["name"];
			$owner = $row_details["owner"];
			$recorder = $row_details["created_by"];
					
			$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid='$escalator'");
			$query_userowner->execute();
			$rows = $query_userowner->fetchAll();
			
			$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
			$query_url->execute();		
			$row_url = $query_url->fetch();
			$url = $row_url["main_url"];
			$org = $row_url["company_name"];
			$org_email = $row_url["email_address"];	
			
			foreach($rows as $row){
				$iowner = $row["userid"];	
				// Comments link back to the system 
				$issuelink = '<a href="'.$url.'projectissuescomments?issueid='.$projissue.'&stage=6&owner='.$iowner.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';
				
				$receipientName = $row["title"].'. '.$row["fullname"]; // The receipients names 
				$receipient = $row["email"];
				
				require_once("project-committee-issue-action-email.php");
				require 'PHPMailer/PHPMailerAutoload.php';	
				require("email-conf-settings.php");
			}
			
			$msg = 'Project successfully restored to its previuos status:- '.$newstatusname;
			$results = "<script type=\"text/javascript\">
				swal({
				title: \"Success!\",
				text: \" $msg \",
				icon: 'success',
				dangerMode: false,
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'projects-escalated-issues';
				}, 3000);
			</script>";
		}
	}

	$query_issuedetails =  $db->prepare("SELECT p.projid, projname, projstatus, c.category as issue, v.name as severity, r.response as mitigation, observation as description, i.created_by as recordedby, i.date_created as daterecorded, fullname as owner, title, s.notes as recommendation, s.date_analysed as dateanalysed, e.comments as leadercomments, e.date_escalated as dateescalated, e.escalated_by, e.owner AS managerid FROM tbl_escalations e inner join tbl_projissues i on i.id=e.itemid inner join tbl_projrisk_categories c on c.rskid=i.risk_category inner join tbl_projects p on p.projid=i.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_response r on r.id=s.mitigation inner join users u on u.userid=i.owner inner join tbl_projteam2 t on t.ptid=u.pt_id where itemid='$issueid' and e.category='issue' and e.owner='$user_name'");
	$query_issuedetails->execute();		
	$rows = $query_issuedetails->fetch();
	$rows_issuedetails = $query_issuedetails->rowCount();
	
	$projid = $rows["projid"];
	$projname = $rows["projname"];
	$projstatus = $rows["projstatus"];
	$issuename = $rows["issue"];
	$severity = $rows["severity"];
	$mitigation = $rows["mitigation"];
	$issuedescription = $rows["description"];
	$recordedby = $rows["recordedby"];
	$managerid = $rows["managerid"];
	$escdby = $rows["escalated_by"];
	$daterecorded = date("d M Y",strtotime($rows["daterecorded"]));
	$issueowner = $rows["title"].".".$rows["owner"];
	$analysisrecm = $rows["recommendation"];
	$dateanalysed = date("d M Y",strtotime($rows["dateanalysed"]));
	$tmleadercomments = $rows["leadercomments"];
	$dateescalated = date("d M Y",strtotime($rows["dateescalated"]));

	$query_tmembers =  $db->prepare("SELECT * FROM tbl_projmembers where projid='$projid' GROUP BY pmid ORDER BY role ASC");
	$query_tmembers->execute();		
	
	$query_teamleader = $db->prepare("SELECT pt_id, fullname, title FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$escdby'");
	$query_teamleader->execute();		
	$teamleader = $query_teamleader->fetch();
	$escdbyid = $teamleader["pt_id"];
	$escalatedby = $teamleader["title"].".".$teamleader["fullname"];
	$style = 'style="padding:8px; border:#CCC thin solid; border-radius:5px"';
	
	$query_recordedby = $db->prepare("SELECT fullname, title FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$recordedby'");
	$query_recordedby->execute();		
	$row_recordedby = $query_recordedby->fetch();
	$recordedby = $row_recordedby["title"].".".$row_recordedby["fullname"];
	?>
    <!-- start body  --><script type="text/javascript">
	$(document).ready(function(){
		$('#issueaction').on('change',function(){
			var statusID = $(this).val();
			var projID = $("#projid").val();
			var issueid = $("#issueid").val();
			$.ajax({
				type: 'POST',
				url: 'callcommitteeaction',
				data: "statusid="+statusID+"&projid="+projID+"&issueid="+issueid,
				success: function (data) {
				  $('#content').html(data);
				}
			});
		});
		
		
		$('#impact').on('change',function(){
			var statusID = $(this).val();
			var projID = $("#projid").val();
			var projOrigID = $("#projorigstatus").val();
			$.ajax({
				type: 'POST',
				url: 'callchangeimpact',
				//data: {'members_id': memberID},
				data: "status_id="+statusID+"&proj_id="+projID+"&projOrig_id="+projOrigID,
				success: function (data) {
				  $('#formcontent').html(data);
				  $("#myModal").modal({backdrop: "static"});
				}
			});
		});
	});

	function CallRiskResponse(projid)
	{
		$.ajax({
			type: 'post',
			url: 'callriskresponse',
			data: {projid:projid},
			success: function (data) {
				$('#riskresponse').html(data);
				 $("#riskModal").modal({backdrop: "static"});
			}
		});
	}
	</script><!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg span5">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title"><font color="#000000">PROJECT STATUS CHANGE REASON(S)</font></h4>
			</div>
			<div class="modal-body" id="formcontent">
			
			</div>
		  </div>
		</div>
	</div>
	<?php
		$query_rsSDate =  $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$projid'");
		$query_rsSDate->execute();		
		$row_rsSDate = $query_rsSDate->fetch();
				
		$projstartdate = $row_rsSDate["projstartdate"];
		//$start_date = date_format($projstartdate, "Y-m-d");
		$current_date = date("Y-m-d");
				
		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$projid'");
		$query_rsTender->execute();		
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();
	?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
				<div>
					<?php echo $results; ?>
				</div>
            </div>
            <div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Issue Details</h4>
            </div>
			<?php if($rows_issuedetails > 0){?>
				<div class="row clearfix" style="margin-top:10px">
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
									<div class="panel panel-col-grey">
										<div class="panel-heading" role="tab" id="headingOne_17">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="true" aria-controls="collapseOne_17">
													<img src="images/task.png" alt="task" /> Issue History
												</a>
											</h4>
										</div>
										<div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17" style="padding-top: 20px; padding-bottom:-50px">
											<div  class="col-md-12">
												<label>Project:</label>
												<div <?=$style?>><?=$projname?></div>
											</div>
											<div class="col-md-12">
												<label>Issue:</label>
												<div <?=$style?>><?=$issuename?></div>
											</div>
											<div class="col-md-12">
												<label>Issue Description:</label>
												<div <?=$style?>><?=$issuedescription?></div>
											</div>
											<div class="col-md-3">
												<label>Severity Level:</label>
												<div <?=$style?>><?=$severity?></div>
											</div>
											<div class="col-md-9">
												<label>Mitigation:</label>
												<div <?=$style?>><?=$mitigation?></div>
											</div>
											<div class="col-md-3">
												<label>Recorded By:</label>
												<div <?=$style?>><?=$recordedby?></div>
											</div>
											<div class="col-md-3">
												<label>Date Recorded:</label>
												<div <?=$style?>><?=$daterecorded?></div>
											</div>
											<div class="col-md-3">
												<label>Issue Owner:</label>
												<div <?=$style?>><?=$issueowner?></div>
											</div>
											<div class="col-md-3">
												<label>Date Analysed:</label>
												<div <?=$style?>><?=$dateanalysed?></div>
											</div>
											<div class="col-md-12">
												<label>Analysis Recommendation:</label>
												<div <?=$style?>><?=$analysisrecm?></div>
											</div>
											<div class="col-md-4">
												<label>Escalated By:</label>
												<div <?=$style?>><?=$escalatedby?></div>
											</div>
											<div class="col-md-4">
												<label>Date Escalated:</label>
												<div <?=$style?>><?=$dateescalated?></div>
											</div>
											<div class="col-md-12">
												<label>Team Leader Comments:</label>
												<div <?=$style?>><?=$tmleadercomments?></div>
											</div>
											<fieldset class="scheduler-border">
												<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-users" aria-hidden="true"></i> Project Team Members</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover">
														<thead>
															<tr id="colrow">
																<th width="2%"><strong id="colhead">SN</strong></th>
																<th width="23%">Name</strong></td>
																<th width="15%">Role</th>
																<th width="15%">Designation</th>
																<th width="10%">Availability</th>
																<th width="15%">Phone</th>
																<th width="20%">Email</th>
															</tr>
														</thead>
														<tbody>
														<?php
														//return; 
														$sn = 0;
														while ($rows = $query_tmembers->fetch()) { 
															$mbrid = $rows['ptid'];
														
															$query_tmember =  $db->prepare("SELECT t.designation as role, d.designation, t.ptid, t.fullname, t.title, t.email, t.phone FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_pmdesignation d on d.moid=t.designation where u.userid='$mbrid' ORDER BY t.designation ASC");
															$query_tmember->execute();
															$row = $query_tmember->fetch();
															
															$sn = $sn + 1;
															$name = $row['title'].".".$row['fullname'];
															$designation = $row['designation'];
															$role = $rows['role'];
															if($role==1){
																$role = "Team Leader";
															}elseif($role==2){
																$role = "Deputy Team Leader";
															}elseif($role==3){
																$role = "Officer";
															}else{
																$role = "monitoring Officer";
															}
															
															$email = $row['email'];
															$phone = $row['phone'];
															$avail = $rows['ptleave'];
															if($avail==1){
																$availability = "Unavailable";
																$reassignee = $rows['reassignee'];
																$datereassigned = date("d M Y",strtotime($rows['datereassigned']));
																
																$query_reassignee =  $db->prepare("SELECT fullname, title, phone, email FROM tbl_projteam2 where ptid='$reassignee'");
																$query_reassignee->execute();		
																$row_reassignee = $query_reassignee->fetch();
																$reassigneedto = $row_reassignee["title"].".".$row_reassignee["fullname"];
																$reassigneedphone = $row_reassignee["phone"];
																$reassigneedemail = $row_reassignee["email"];
																$availclass = 'class="text-warning"';
															}else{
																$availability = "Available";
																$availclass = 'class="text-success"';
															}
															?>
															<tr id="rowlines">
																<td><?php echo $sn; ?></td>
																<td><?php echo $name; ?></td>
																<td><?php echo $role; ?></td>
																<td><?php echo $designation; ?></td>
																<?php if($avail==1){ ?>
																<td <?=$availclass?>><span class="mytooltip tooltip-effect-1"><span class="tooltip-item2"><?php echo $availability; ?></span><span class="tooltip-content4 clearfix" style="background-color:#CDDC39; color:#000"><span class="tooltip-text2"><h4 align="center"><u>Details</u></h4><strong>Unavailable From Date:</strong> <?php echo $datereassigned; ?><br> <strong>In-Place:</strong> <?php echo $reassigneedto; ?><br> <strong>In-Place Phone:</strong> <?php echo $reassigneedphone; ?><br> <strong>In-Place Email:</strong> <?php echo $reassigneedemail; ?></span></span></span></td>
																<?php }else{ ?>
																<td><?php echo $availability; ?></td>
																<?php } ?>
																<td><?php echo $phone; ?></td>
																<td><?php echo $email; ?></td>
															</tr>
														<?php 
														}
														?>
														</tbody>
													</table>
												</div>
											</fieldset>
										</div>
									</div>
									<div class="panel panel-col-teal">
										<div class="panel-heading" role="tab" id="headingTwo_17">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false"
												   aria-controls="collapseTwo_17">
													<i class="fa fa-plus-square" aria-hidden="true"></i> Project Committee Recommendation
												</a>
											</h4>
										</div>
										<div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17" style="padding-top: 20px">
											<form id="addcmtfrm" method="POST" name="addcmtfrm" action="" autocomplete="off">
												<div class="row clearfix" style="padding-left:10px; padding-right:10px">
													<?php
													$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus FROM tbl_projects WHERE projid = '$projid'");
													$query_projstatuschange->execute();		
													$row_projstatuschange = $query_projstatuschange->fetch();
													$projstatus = $row_projstatuschange["projstatus"];
													$projcode = $row_projstatuschange["projcode"];
													
													$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid'");
													$query_escalationstage->execute();	
													$escalationstage_count = $query_escalationstage->rowCount();
													$assessmentcomments = array();
													while($row_escalationstage = $query_escalationstage->fetch()){
														$assessmentcomments[] = $row_escalationstage["stage"];
													}
													
													//6 is On Hold status
													if($projstatus==6){
														$query_issueassessment = $db->prepare("SELECT assessment FROM tbl_projissues WHERE id='$issueid' and projid = '$projid'");
														$query_issueassessment->execute();		
														$row_issueassessment = $query_issueassessment->fetch();
														$assessment = $row_issueassessment["assessment"];
		
														$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid'");
														$query_escalationstage->execute();	
														$escalationstage_count = $query_escalationstage->rowCount();
														$assessmentcomments = array();
														while($row_escalationstage = $query_escalationstage->fetch()){
															$assessmentcomments[] = $row_escalationstage["stage"];
														}
														
														if($assessment==1){
															if(in_array(5, $assessmentcomments)){
																$query_escalationstage_comments = $db->prepare("SELECT comments FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid' and stage=5 LIMIT 1");
																$query_escalationstage_comments->execute();	
																$count_comments = $query_escalationstage_comments->rowCount();
																$escalationstage_comments = $query_escalationstage_comments->fetch();
																?>
																<div class="col-md-12">
																	<label><font color="#174082"><i class="fa fa-bar-chart" aria-hidden="true"></i> Issue Assessment Report:</font>
																	</font></label>
																	<div class="form-control" >
																	<?php echo $escalationstage_comments["comments"]; ?>
																	</div>
																</div>
																<div class="col-md-6">
																	<label><font color="#174082">Committee Final Action:</font></label>
																	<div class="form-line">
																		<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																			<option value="" selected="selected" class="selection">... Select ...</option>
																			<option value="Restore">Restore Project</option>
																			<option value="Cancelled">Cancel Project</option>
																		</select>
																	</div>
																</div>
															<?php
															}elseif(!in_array(5, $assessmentcomments)){
															?>
																<div class="col-md-12" style="color:#FF5722">
																	<strong>Awaiting for Issue Assessment Report. Please come back later!!</strong>
																</div>
															<?php
															}	
														}
														elseif($assessment==0){
															?>
															<div class="col-md-6">
																<label><font color="#174082">Committee Final Action:</font></label>
																<div class="form-line">
																	<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																		<option value="" selected="selected" class="selection">... Select ...</option>
																		<option value="Restore">Restore Project</option>
																		<option value="Cancelled">Cancel Project</option>
																	</select>
																</div>
															</div>
															<?php
														}
													}
													elseif($projstatus !== 6){
													?>
														<div class="col-md-6">
															<label><font color="#174082">Committee Action:</font></label>
															<div class="form-line">
																<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																	<option value="" selected="selected" class="selection">... Select ...</option>
																	<option value="Continue">Close Issue and Let Project Continue</option>
																	<option value="On Hold">Put Project On Hold</option>
																	<option value="Cancelled">Cancel Project</option>
																</select>	
															</div>
														</div>
													<?php
													}
													?>
													<div id="content">
													</div> 
													<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
													<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
													<input name="deptid" type="hidden" id="deptid" value="<?php echo $opid; ?>" />
													<input name="issuename" type="hidden" id="issuename" value="<?php echo $issuename; ?>" />
													<input name="projstatus" type="hidden" id="projstatus" value="<?php echo $projstatus; ?>" />
													<input name="escalator" type="hidden" id="escalator" value="<?php echo $escdby; ?>" />
													<input name="projissueid" type="hidden" id="issueid" value="<?php echo $issueid; ?>" />
												</div>
											</form>
										</div>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
            <!-- #END# Advanced Form Example With Validation -->
			
			<?php } else {
				echo '	
				<div class="row clearfix" style="margin-top:10px">
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
									<div class="panel panel-col-red">
										Sorry no data found!
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
			?>
        </div>
    </section>
    <!-- end body  -->	
	

	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#escalation-response-form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "escalationresponse",
				data: form_data,
				dataType: "json",
				success:function(response)
				{   
					if(response){
						alert('Record Successfully Saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	</script>
	<!-- Modal Issue Response -->
	<div class="modal fade" id="escalationActionModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#795548">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">PROJECT RISK RESPONSE</font></h3>
				</div>
				<form class="tagForm" action="escalationresponse" method="post" id="escalation-response-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="riskresponse">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
				
					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $username; ?>"/>
							<input type="hidden" name="stchange" value="1"/>
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Issue Response -->	

	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#par-change-form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "parameterschange",
				data: form_data,
				dataType: "json",
				success:function(response)
				{   
					if(response){
						alert('Record Successfully Saved');
						$('.modal').each(function(){
							$(this).modal('hide');
						});
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	</script>
	<!-- Modal Project Constrain Parameters -->
	<div class="modal fade" id="parChangeModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#795548">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">PARAMETERS CHANGES</font></h3>
				</div>
				<form class="tagForm" action="parameterschange" method="post" id="par-change-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="parameterschange">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
				
					</div>
					<div class="modal-footer">
						<div class="col-md-3">
						</div>
						<div class="col-md-6" align="center">
							<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							<input type="hidden" name="username" id="username" value="<?php echo $username; ?>"/>
							<input type="hidden" name="stchange" value="1"/>
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Project Constrain Parameters -->
<?php
/* } else {
    $results =  restriction();
    echo $results;
} */

require('includes/footer.php');
?>
	
</body>
</html>