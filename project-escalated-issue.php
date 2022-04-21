<?php 
include_once 'includes/head-alt.php';

try{		
	
	
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

	$query_tmembers =  $db->prepare("SELECT t.designation as role, ptleave, reassignee, datereassigned, d.designation, t.ptid, t.fullname, t.title, t.email, t.phone FROM tbl_projteam2 t inner join tbl_projmembers m on m.ptid=t.ptid inner join tbl_pmdesignation d on d.moid=t.designation where m.projid='$projid' GROUP BY pmid ORDER BY t.designation ASC");
	$query_tmembers->execute();		
	
	$query_teamleader = $db->prepare("SELECT pt_id, fullname, title FROM tbl_users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$escdby'");
	$query_teamleader->execute();		
	$teamleader = $query_teamleader->fetch();
	$escdbyid = $teamleader["pt_id"];
	$escalatedby = $teamleader["title"].".".$teamleader["fullname"];
	$style = 'style="padding:8px; border:#CCC thin solid; border-radius:5px"';
	
	$query_recordedby = $db->prepare("SELECT fullname, title FROM tbl_users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$recordedby'");
	$query_recordedby->execute();		
	$row_recordedby = $query_recordedby->fetch();
	$recordedby = $row_recordedby["title"].".".$row_recordedby["fullname"];

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based Monitoring &amp; Evaluation System: Escalated Issue Action</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
	
    <!-- Custom CSS -->
    <link href="css/popper-small.css" rel="stylesheet">

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!--WaitMe Css-->
    <link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Sweet Alert Css -->
    <link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	
	<!-- InstanceBeginEditable name="head" -->
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.tabs .tab-links a').on('click', function(e)  {
			var currentAttrValue = jQuery(this).attr('href');
	 
			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
	 
			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
	 
			e.preventDefault();
		});
	});
	</script>

	<script type="text/javascript" >
	$(document).ready(function()
	{
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});


	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
		
	});</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
	<script src="ckeditor/ckeditor.js"></script>
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  
	</style> 
	<script type="text/javascript">
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
	</script>
</head>

<body class="theme-blue">
	<!-- Modal -->
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
    <!-- Page Loader --
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
	<?php
		$pid = $row_rsMyP['projid'];
		$query_rsSDate =  $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
		$query_rsSDate->execute();		
		$row_rsSDate = $query_rsSDate->fetch();
				
		$projstartdate = $row_rsSDate["projstartdate"];
		//$start_date = date_format($projstartdate, "Y-m-d");
		$current_date = date("Y-m-d");
				
		$tndprojid = $row_rsMyP['projid'];
		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$tndprojid'");
		$query_rsTender->execute();		
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();
	?>
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars ->
    <!-- Top Bar -->
    <nav class="navbar" style="height:69px; padding-top:-10px">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <img src="images/logo.png" alt="logo" width="239" height="39">
            </div>
			
        </div>
    </nav>
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
				<?php
				include_once("includes/user-info.php");
				?>
            </div>
            <!-- #User Info -->
            <!-- Menu -->        
			<?php
			 include_once("includes/sidebar.php");
			?>
            <!-- #Menu -->
            <!-- Footer -->
			<div class="legal">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
					ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System.
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
					Copyright @ 2017 - 2019. ProjTrac Systems Ltd.
				</div>
			</div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
	<!-- Inner Section -->
		<?php  include_once('project-escalated-issue-inner.php');?>
    <!-- #END# Inner Section -->
	

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
    <!-- Jquery Core Js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/plugins/popper/popper.min.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
    <!--stickey kit -->
    <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?lang=css&amp;skin=default"></script>
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
	
</body>

</html>