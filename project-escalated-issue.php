<?php
try {
	$decode_issue = (isset($_GET['issue']) && !empty($_GET["issue"])) ? base64_decode($_GET['issue']) : header("Location: projects-escalated-issues");
	$issueid_array = explode("issueid254", $decode_issue);
	$issueid = $issueid_array[1];
	require('PHPMailer/PHPMailerAutoload.php');
	require('includes/head.php');

	if ($permission) {
		if ((isset($_POST["issueid"])) && !empty($_POST["issueid"])) {
			/* echo $_POST["issueid"]."; ".$_POST["projstatuschange"];
			return; */
			if (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Continue") {
				$projissue = $_POST["issueid"];
				$projid = $_POST["projid"];
				$comments = $_POST["comments"];
				$subject = "Issue Ignored and Project to Continue";
				$status = 1;
				$actiondate = date("Y-m-d");
				$changedon = date("Y-m-d H:i:s");

				$insertSQL = $db->prepare("Update tbl_projissues SET recommendation=:comments, status=:status, closed_by=:user, date_closed=:date WHERE id=:projissue");
				$results = $insertSQL->execute(array(':comments' => $comments, ':status' => $status, ':user' => $user_name, ':date' => $actiondate, ':projissue' => $projissue));

				if ($results) {
					if (!empty($_FILES['attachment']['name'])) {
						$filecategory = "Issue";
						$reason = "Project Committee Action: " . $subject;
						return;
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['attachment']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
							$newname = $projid . "-" . $projissue . "-" . $filename;
							$filepath = "uploads/projissue/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
									//successful upload
									$fname = $newname;
									$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
									$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
								}
							} else {
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
						} else {
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
					} else {
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

					$query_details =  $db->prepare("SELECT projname, i.created_by FROM tbl_projects p left join tbl_projissues i on i.projid=p.projid WHERE i.id =:projissue");
					$query_details->execute(array(':projissue' => $projissue));
					$row_details = $query_details->fetch();
					if ($row_details) {
						$projectname = $row_details["projname"];
						$owner = $row_details["created_by"];

						$query_userowner =  $db->prepare("SELECT fullname, tt.title AS title, t.email AS email FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title WHERE userid = :owner");
						$query_userowner->execute(array(':owner' => $owner));
						$row = $query_userowner->fetch();

						$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
						$query_url->execute();
						$row_url = $query_url->fetch();
						$url = $row_url["main_url"];
						$org = $row_url["company_name"];
						$org_email = $row_url["email_address"];

						// Comments link back to the system
						$issuemessage = "The committee has decided to ignore the issue and allow the project <strong>TO CONTINUE</strong>. Please proceed to close the issue";
						$encrypted_issue = base64_encode("projid54321{$projissue}");
						$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Close Issue</a>';

						$receipient = $row["email"];
						$fullname = $row['title'] . "." . $row['fullname'];

						$mainmessage = ' Dear ' . $fullname . ',
						<p><br>' . $issuemessage . '</p>';
						$title = $subject;
						$receipientName = $fullname;

						include("assets/processor/email-body-orig.php");
						include("assets/processor/email-conf-settings.php");
					}
					$msg = 'Project issue status successfully updated';
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
			} elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "On Hold") {
				$projissue = $_POST["issueid"];
				$comments = $_POST["comments"];
				$subject = "Project On Hold";
				//$assessment = $_POST["assessment"];
				$actiondate = date("Y-m-d");
				$issue_status = $_POST["projstatuschange"];
				$reason = "Project Committee Action: " . $subject;
				$issuemessage = "The committee has decided to put the issue and the project <strong>ON HOLD</strong> based on the below reasons:";
				//$catid =$last_id;

				$query_userowner =  $db->prepare("SELECT projid, userid, ptid, fullname, tt.title, t.email AS email FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title left join tbl_projissues i on i.created_by=u.userid WHERE i.id=:issueid");
				$query_userowner->execute(array(":issueid" => $projissue));
				$row = $query_userowner->fetch();
				$projid = $row["projid"];

				$query_project =  $db->prepare("SELECT projcategory, projstatus FROM tbl_projects where projid=:projid");
				$query_project->execute(array(":projid" => $projid));
				$row_project = $query_project->fetch();
				$projcategory = $row_project["projcategory"];
				$origstatus = $row_project["projstatus"];

				$insertquery = $db->prepare("INSERT INTO tbl_proj_status_change_reason (projid, issue_id, status, reason, created_by, date_created) VALUES (:projid, :issue_id, :status, :reason, :user, :date)");
				$results = $insertquery->execute(array(':projid' => $projid, ':issue_id' => $projissue, ':status' => $origstatus, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));

				if ($results) {
					$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, issueid, issue_status, comments, created_by, date_created) VALUES (:projid, :issueid, :issue_status, :comments, :user, :date)");
					$insertSQL->execute(array(':projid' => $projid, ':issueid' => $projissue, ':issue_status' => $issue_status, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));

					if (!empty($_FILES['attachment']['name'])) {
						$filecategory = "Issue";
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['attachment']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);

						if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
							$newname = $projid . "-" . $projissue . "-" . $filename;
							$filepath = "uploads/projissue/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
									//successful upload
									$fname = $newname;

									$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
									$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
								}
							} else {
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
						} else {
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

					$newstatus = 6;
					$updateQuery = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus, projchangedstatus=:projchangedstatus WHERE projid=:projid");
					$updated = $updateQuery->execute(array(':projstatus' => $newstatus, ':projchangedstatus' => $origstatus, ':projid' => $projid));

					/* if ($assessment == 1) {
						$projeval = " <br><strong>Please NOTE Issue Assessment/Evaluation is required for this project</strong>";
					} */

					$status = 2;
					$insertSQL = $db->prepare("UPDATE tbl_projissues SET status = :status WHERE projid = :projid AND id = :issueid");
					$update_results = $insertSQL->execute(array(':status' => $status, ':projid' => $projid, ':issueid' => $projissue));

					$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
					$query_url->execute();
					$row_url = $query_url->fetch();
					$url = $row_url["main_url"];
					$org = $row_url["company_name"];
					$org_email = $row_url["email_address"];

					if ($update_results) {
						$owner = $row["userid"];

						// Comments link back to the system
						$issuemessage = "After enough deliberation, the committee has decided to put the project <strong>ON HOLD</strong> based on the issue weight.<br>";
						$encrypted_issue = base64_encode("projid54321{$projissue}");
						$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';

						$receipient = $row["email"];
						$fullname = $row['title'] . "." . $row['fullname'];

						$mainmessage = ' Dear ' . $fullname . ',
						<p><br>' . $issuemessage . '</p>
						<p>Please make the necessary arrangements as advised. </p>';
						$title = $subject;
						$receipientName = $fullname;

						include("assets/processor/email-body-orig.php");
						include("assets/processor/email-conf-settings.php");
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
			} elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Cancelled") {

				$projissue = $_POST["issueid"];
				$projid = $_POST["projid"];
				$comments = $_POST["comments"];
				$subject = "Project Cancellation";
				$projstatus = 2;
				$issuestatus = 7;
				$actiondate = date("Y-m-d");
				$changedon = date("Y-m-d H:i:s");

				$update_sql = $db->prepare("Update tbl_projects SET projstatus=:projstatus, updated_by=:user, date_updated=:date WHERE projid=:projid");
				$update_results = $update_sql->execute(array(':projstatus' => $projstatus, ':user' => $user_name, ':date' => $actiondate, ':projid' => $projid));

				if ($update_results) {
					$query_all_issues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
					$query_all_issues->execute(array(':projid' => $projid));

					while ($all_issues = $query_all_issues->fetch()) {
						$issue_id = $all_issues["id"];
						$insertSQL = $db->prepare("Update tbl_projissues SET recommendation=:comments, status=:status, closed_by=:user, date_closed=:date WHERE id=:issue_id");
						$results = $insertSQL->execute(array(':comments' => $comments, ':status' => $issuestatus, ':user' => $user_name, ':date' => $actiondate, ':issue_id' => $issue_id));
					}

					if (!empty($_FILES['attachment']['name'])) {
						$filecategory = "Issue";
						$reason = "Project Committee Action: " . $subject;
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['attachment']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
							$newname = $projid . "-" . $projissue . "-" . $filename;
							$filepath = "uploads/projissue/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
									//successful upload
									$fname = $newname;

									$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
									$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
								}
							} else {
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
						} else {
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

					$query_details =  $db->prepare("SELECT projname, i.created_by FROM tbl_projects p left join tbl_projissues i on i.projid=p.projid WHERE i.id =:projissue");
					$query_details->execute(array(':projissue' => $projissue));
					$row_details = $query_details->fetch();
					if ($row_details) {
						$projectname = $row_details["projname"];
						$owner = $row_details["created_by"];

						$query_userowner =  $db->prepare("SELECT fullname, tt.title AS title, t.email AS email FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title WHERE userid = :owner");
						$query_userowner->execute(array(':owner' => $owner));
						$row = $query_userowner->fetch();

						$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
						$query_url->execute();
						$row_url = $query_url->fetch();
						$url = $row_url["main_url"];
						$org = $row_url["company_name"];
						$org_email = $row_url["email_address"];

						// Comments link back to the system
						$issuemessage = "After enough deliberation, the committee has decided to cancel the project based on the issue weight.<br>";
						$encrypted_issue = base64_encode("projid54321{$projissue}");

						$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Close Issue</a>';

						$receipient = $row["email"];
						$fullname = $row['title'] . "." . $row['fullname'];

						$mainmessage = ' Dear ' . $fullname . ',
						<p><br>' . $issuemessage . '</p>
						<p>Please make the necessary arrangements as advised. </p>';
						$title = $subject;
						$receipientName = $fullname;

						include("assets/processor/email-body-orig.php");
						include("assets/processor/email-conf-settings.php");
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
			} elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "RestoreAndApprove") {
				$projissue = $_POST["issueid"];
				$projid = $_POST["projid"];
				$issue_status = $_POST["projstatuschange"];
				$comments = $_POST["comments"];
				$subject = "Project Restored and Request Approved";
				$user = $_POST["user_name"];
				$projadjustment = $_POST["projadjustment"];
				$actiondate = date("Y-m-d");

				$query_project_details =  $db->prepare("SELECT date_created FROM tbl_projissue_comments WHERE projid=:projid and issueid=:issueid");
				$query_project_details->execute(array(":projid" => $projid, ":issueid" => $projissue));
				$row_project_details = $query_project_details->fetch();
				$onhold_date = date_create($row_project_details["date_created"]);
				$today = date_create($actiondate);
				$diff = date_diff($onhold_date, $today);
				$onhold_days = $diff->format("%a days");

				$query_project_adjustments =  $db->prepare("SELECT * FROM tbl_project_adjustments WHERE projid=:projid and issueid=:issueid");
				$query_project_adjustments->execute(array(":projid" => $projid, ":issueid" => $projissue));
				while ($row_project_adjustments = $query_project_adjustments->fetch()) {
					$issue_area = $row_project_adjustments["issue_area"];
					$pw_id = $row_project_adjustments["pw_id"];
					if ($issue_area == 2) {
						$timeline = $row_project_adjustments["timeline"];
						$query_program_of_works =  $db->prepare("SELECT duration, end_date FROM tbl_program_of_works WHERE id=:pw_id");
						$query_program_of_works->execute(array(":pw_id" => $pw_id));

						$sub_task_duration = $row_project_adjustments["duration"];
						$new_duration = $sub_task_duration + $timeline;
						$sub_task_program_end_date = $row_project_adjustments["end_date"];
						$date = date_create($sub_task_program_end_date);
						date_add($date, date_interval_create_from_date_string($onhold_days));
						$new_end_date = date_format($date, "Y-m-d");

						$update_program_of_works = $db->prepare("UPDATE tbl_program_of_works SET duration=:duration, original_duration=:original_duration, end_date=:new_end_date WHERE id=:pw_id");
						$update_program_of_works->execute(array(':duration' => $new_duration, ':original_duration' => $sub_task_duration, ':new_end_date' => $new_end_date, ':pw_id' => $pw_id));
					} elseif ($issue_area == 3) {
						$timeline = $row_project_adjustments["timeline"];
						$query_program_of_works =  $db->prepare("SELECT duration, end_date FROM tbl_program_of_works WHERE id=:pw_id");
						$query_program_of_works->execute(array(":pw_id" => $pw_id));

						$sub_task_duration = $row_project_adjustments["duration"];
						$new_duration = $sub_task_duration + $timeline;
						$sub_task_program_end_date = $row_project_adjustments["end_date"];
						$date = date_create($sub_task_program_end_date);
						date_add($date, date_interval_create_from_date_string($onhold_days));
						$new_end_date = date_format($date, "Y-m-d");

						$update_program_of_works = $db->prepare("UPDATE tbl_program_of_works SET duration=:duration, original_duration=:original_duration, end_date=:new_end_date WHERE id=:pw_id");
						$update_program_of_works->execute(array(':duration' => $new_duration, ':original_duration' => $sub_task_duration, ':new_end_date' => $new_end_date, ':pw_id' => $pw_id));
					} elseif ($issue_area == 4) {
						$cost = $row_project_adjustments["cost"];
					}
				}

				//issuestatus = 1 is continue; 2 is on-hold; 3 is restore; 4 is approve; 5 is restore and approve; 6 is cancel; 7 is close

				$issuestatus = 5;

				$update_issue = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
				$results = $update_issue->execute(array(':status' => $issuestatus, ':projid' => $projid, ':issueid' => $projissue));

				if ($results) {
					$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, issueid, issue_status, comments, created_by, date_created) VALUES (:projid, :issueid, :issue_status, :comments, :user, :date)");
					$insertSQL->execute(array(':projid' => $projid, ':issueid' => $projissue, ':issue_status' => $issue_status, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));

					if (!empty($_FILES['attachment']['name'])) {
						$filecategory = "Issue Resolution";
						$reason = "Project Committee Action: " . $subject;
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['attachment']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
							$newname = $projid . "-" . $projissue . "-" . $filename;
							$filepath = "uploads/projissue/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
									//successful upload
									$fname = $newname;

									$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
									$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
								}
							} else {
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
						} else {
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
					} else {
						$msg = 'No attachmennt!!';
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

					$query_details =  $db->prepare("SELECT projname, projchangedstatus, i.issue_description, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid WHERE i.id = '$projissue'");
					$query_details->execute();
					$row_details = $query_details->fetch();
					$projectname = $row_details["projname"];
					$issue = $row_details["issue_description"];
					$project_previous_status = $row_details["projchangedstatus"];
					$created_by = $row_details["created_by"];

					$update_project = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus WHERE projid=:projid");
					$updated = $update_project->execute(array(':projstatus' => $project_previous_status, ':projid' => $projid));

					if ($updated) {
						$query_userowner =  $db->prepare("SELECT fullname, tt.title AS title, t.email AS email FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title WHERE userid = :owner");
						$query_userowner->execute(array(':owner' => $created_by));
						$row = $query_userowner->fetch();
						$totalrows_userowner = $query_userowner->rowCount();

						$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
						$query_url->execute();
						$row_url = $query_url->fetch();
						$url = $row_url["main_url"];
						$org = $row_url["company_name"];
						$org_email = $row_url["email_address"];

						if ($totalrows_userowner > 0) {
							// Comments link back to the system
							$issuemessage = "The committee has restored the project and approved the request. Please proceed to close the issue.";
							$encrypted_issue = base64_encode("projid54321{$projissue}");
							$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Close the Issue</a>';

							$receipient = $row["email"];
							$fullname = $row['title'] . "." . $row['fullname'];

							$mainmessage = ' Dear ' . $fullname . ',
							<p><br>' . $issuemessage . '</p>';
							$title = $subject;
							$receipientName = $fullname;

							include("assets/processor/email-body-orig.php");
							include("assets/processor/email-conf-settings.php");

							$msg = 'Record successfully saved';
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
				}
			} elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Restore") {
				$projissue = $_POST["issueid"];
				$projid = $_POST["projid"];
				$issue_status = $_POST["projstatuschange"];
				$comments = $_POST["comments"];
				$subject = "Project Restored";
				$user = $_POST["user_name"];
				$actiondate = date("Y-m-d");
				$changedon = date("Y-m-d H:i:s");

				$query_project_details =  $db->prepare("SELECT date_created FROM tbl_projissue_comments WHERE projid=:projid and issueid=:issueid");
				$query_project_details->execute(array(":projid" => $projid, ":issueid" => $projissue));
				$row_project_details = $query_project_details->fetch();
				$onhold_date = date_create($row_project_details["date_created"]);
				$today = date_create($actiondate);
				$diff = date_diff($onhold_date, $today);
				$onhold_days = $diff->format("%a days");

				$query_program_of_works =  $db->prepare("SELECT id, end_date FROM tbl_program_of_works WHERE projid=:projid and status<>5");
				$query_program_of_works->execute(array(":projid" => $projid));
				while ($row_program_of_works = $query_program_of_works->fetch()) {
					$sub_task_program_id = $row_program_of_works["id"];
					$sub_task_program_end_date = $row_program_of_works["end_date"];
					$date = date_create($sub_task_program_end_date);
					date_add($date, date_interval_create_from_date_string($onhold_days));
					$new_end_date = date_format($date, "Y-m-d");

					$update_program_of_works = $db->prepare("UPDATE tbl_program_of_works SET end_date=:new_end_date WHERE id=:sub_task_program_id");
					$update_program_of_works->execute(array(':new_end_date' => $new_end_date, ':sub_task_program_id' => $sub_task_program_id));
				}

				//issuestatus = 1 is continue; 2 is on-hold; 3 is restore; 4 is adjust; 5 is restore and adjust; 6 is cancel; 7 is close

				$issuestatus = 3;

				$update_issue = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
				$results = $update_issue->execute(array(':status' => $issuestatus, ':projid' => $projid, ':issueid' => $projissue));

				if ($results) {
					$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, issueid, issue_status, comments, created_by, date_created) VALUES (:projid, :issueid, :issue_status, :comments, :user, :date)");
					$insertSQL->execute(array(':projid' => $projid, ':issueid' => $projissue, ':issue_status' => $issue_status, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));

					if (!empty($_FILES['attachment']['name'])) {
						$filecategory = "Issue Resolution";
						$reason = "Project Committee Action: " . $subject;
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['attachment']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
							$newname = $projid . "-" . $projissue . "-" . $filename;
							$filepath = "uploads/projissue/" . $newname;
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
									//successful upload
									$fname = $newname;

									$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
									$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
								}
							} else {
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
						} else {
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
					} else {
						$msg = 'No attachmennt!!';
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

					$query_details =  $db->prepare("SELECT projname, projchangedstatus, i.issue_description, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid WHERE i.id = '$projissue'");
					$query_details->execute();
					$row_details = $query_details->fetch();
					$projectname = $row_details["projname"];
					$issue = $row_details["issue_description"];
					$project_previous_status = $row_details["projchangedstatus"];
					$created_by = $row_details["created_by"];

					$update_project = $db->prepare("UPDATE tbl_projects SET projstatus=:projstatus WHERE projid=:projid");
					$updated = $update_project->execute(array(':projstatus' => $project_previous_status, ':projid' => $projid));

					if ($updated) {
						$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, tt.title, t.email AS email FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title WHERE userid='$created_by'");
						$query_userowner->execute();
						$rows = $query_userowner->fetch();
						$totalrows_userowner = $query_userowner->rowCount();

						$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
						$query_url->execute();
						$row_url = $query_url->fetch();
						$url = $row_url["main_url"];
						$org = $row_url["company_name"];
						$org_email = $row_url["email_address"];

						if ($totalrows_userowner > 0) {
							// Comments link back to the system
							$issuemessage = "The committee has restored the project, please proceed to close the issue.";
							$encrypted_issue = base64_encode("issueid254{$projissue}");
							$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Close the Issue</a>';

							$receipient = $rows["email"];
							$fullname = $rows['title'] . "." . $rows['fullname'];

							$mainmessage = ' Dear ' . $fullname . ',
							<p><br>' . $issuemessage . '</p>';
							$title = $subject;
							$receipientName = $fullname;

							include("assets/processor/email-body-orig.php");
							include("assets/processor/email-conf-settings.php");

							$msg = 'Project restored successfully!!';
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
				}
			} elseif (isset($_POST["projstatuschange"]) && $_POST["projstatuschange"] == "Approved") {
				$projissue = $_POST["issueid"];
				$projid = $_POST["projid"];
				$comments = $_POST["comments"];
				$subject = "Request Approved";
				$user = $_POST["user_name"];
				$actiondate = date("Y-m-d");
				$changedon = date("Y-m-d H:i:s");

				$issuemessage = "The committee has approved your request, please proceed to close the issue.";


				if (!empty($_FILES['attachment']['name'])) {
					$filecategory = "Issue Resolution";
					$reason = "Project Committee Action: " . $subject;
					//Check if the file is JPEG image and it's size is less than 350Kb
					$filename = basename($_FILES['attachment']['name']);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
						$newname = $projid . "-" . $projissue . "-" . $filename;
						$filepath = "uploads/projissue/" . $newname;
						//Check if the file with the same name already exists in the server
						if (!file_exists($filepath)) {
							//Attempt to move the uploaded file to it's new place
							if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
								//successful upload
								$fname = $newname;

								$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
								$queryinsert->execute(array(':projid' => $projid, ':stage' => $projissue, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user_name, ':date' => $actiondate));
							}
						} else {
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
					} else {
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
				} else {
					$msg = 'No attachmennt!!';
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

				$query_project_adjustments =  $db->prepare("SELECT * FROM tbl_project_adjustments WHERE projid=:projid and issueid=:issueid");
				$query_project_adjustments->execute(array(":projid" => $projid, ":issueid" => $projissue));
				while ($row_project_adjustments = $query_project_adjustments->fetch()) {
					$issue_area = $row_project_adjustments["issue_area"];
					$pw_id = $row_project_adjustments["pw_id"];
					if ($issue_area == 2) {
						$timeline = $row_project_adjustments["timeline"];
						$query_program_of_works =  $db->prepare("SELECT duration, end_date FROM tbl_program_of_works WHERE id=:pw_id");
						$query_program_of_works->execute(array(":pw_id" => $pw_id));
						$row_program_of_works = $query_program_of_works->fetch();

						$sub_task_duration = $row_program_of_works["duration"];
						$new_duration = $sub_task_duration + $timeline;
						$sub_task_program_end_date = $row_program_of_works["end_date"];
						$new_end_date = date('Y-m-d', strtotime($sub_task_program_end_date. " + ".$timeline." days"));

						$update_program_of_works = $db->prepare("UPDATE tbl_program_of_works SET duration=:duration, original_duration=:original_duration, end_date=:new_end_date WHERE id=:pw_id");
						$update_program_of_works->execute(array(':duration' => $new_duration, ':original_duration' => $sub_task_duration, ':new_end_date' => $new_end_date, ':pw_id' => $pw_id));
					} elseif ($issue_area == 3) {
						$timeline = $row_project_adjustments["timeline"];
						$query_program_of_works =  $db->prepare("SELECT duration, end_date FROM tbl_program_of_works WHERE id=:pw_id");
						$query_program_of_works->execute(array(":pw_id" => $pw_id));
						$row_program_of_works = $query_program_of_works->fetch();

						$sub_task_duration = $row_program_of_works["duration"];
						$new_duration = $sub_task_duration + $timeline;
						$sub_task_program_end_date = $row_project_adjustments["end_date"];
						$new_end_date = date('Y-m-d', strtotime($sub_task_program_end_date. " + ".$timeline." days"));

						$update_program_of_works = $db->prepare("UPDATE tbl_program_of_works SET duration=:duration, original_duration=:original_duration, end_date=:new_end_date WHERE id=:pw_id");
						$update_program_of_works->execute(array(':duration' => $new_duration, ':original_duration' => $sub_task_duration, ':new_end_date' => $new_end_date, ':pw_id' => $pw_id));
					} elseif ($issue_area == 4) {
						$cost = $row_project_adjustments["cost"];
					}
				}

				//issuestatus = 1 is continue; 2 is on-hold; 3 is restore; 4 is adjust; 5 is restore and adjust; 6 is cancel; 7 is close

				$issuestatus = 4;

				$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status, recommendation=:comments WHERE projid=:projid and id=:issueid");
				$updatest = $updateQuery->execute(array(':status' => $issuestatus, ':comments' => $comments, ':projid' => $projid, ':issueid' => $projissue));

				$query_details =  $db->prepare("SELECT projname, i.issue_description, i.created_by FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid WHERE i.id = '$projissue'");
				$query_details->execute();
				$row_details = $query_details->fetch();
				$projectname = $row_details["projname"];
				$issue = $row_details["issue_description"];
				$created_by = $row_details["created_by"];

				$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, tt.title, t.email AS email, designation FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid left join tbl_titles tt on tt.id=t.title WHERE userid='$created_by'");
				$query_userowner->execute();
				$rows = $query_userowner->fetchAll();
				$totalrows_userowner = $query_userowner->rowCount();

				$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
				$query_url->execute();
				$row_url = $query_url->fetch();
				$url = $row_url["main_url"];
				$org = $row_url["company_name"];
				$org_email = $row_url["email_address"];

				if ($totalrows_userowner > 0) {
					foreach ($rows as $row) {
						$iowner = $row["userid"];
						// Comments link back to the system
						$encrypted_issue = base64_encode("projid54321{$projissue}");
						$detailslink  = '<a href="' . $url . 'my-project-issues?proj=' . $encrypted_issue . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Close the Issue</a>';

						$receipient = $row["email"];
						$fullname = $row['title'] . "." . $row['fullname'];

						$mainmessage = ' Dear ' . $fullname . ',
						<p><br>' . $issuemessage . '</p>';
						$title = $subject;
						$receipientName = $fullname;

						include("assets/processor/email-body-orig.php");
						include("assets/processor/email-conf-settings.php");
					}

					$msg = 'Request successfully approved';
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
		}

		$query_issue_details =  $db->prepare("SELECT p.projid, projname, projstatus, projcategory, i.issue_description, i.issue_area, c.category as category, s.description as impact, i.issue_priority, i.recommendation, i.created_by, i.date_created, i.status FROM tbl_projissues i inner join tbl_projrisk_categories c on c.catid=i.risk_category inner join tbl_projects p on p.projid=i.projid inner join tbl_risk_impact s on s.id=i.issue_impact where i.id=:issueid");
		$query_issue_details->execute(array(":issueid" => $issueid));
		$rows_issue_details = $query_issue_details->fetch();
		$totalrows_issue_details = $query_issue_details->rowCount();

		if ($rows_issue_details) {
			$projid = $rows_issue_details["projid"];
			$projname = $rows_issue_details["projname"];
			$projstatus = $rows_issue_details["projstatus"];
			$projcategory = $rows_issue_details["projcategory"];
			$issuename = $rows_issue_details["issue_description"];
			$issue_areaid = $rows_issue_details["issue_area"];
			$issue_category = $rows_issue_details["category"];
			$issue_severity = $rows_issue_details["impact"];
			$issue_priorityid = $rows_issue_details["issue_priority"];
			$issue_status = $rows_issue_details["status"];
			$created_by = $rows_issue_details["created_by"];
			$date_created = $rows_issue_details["date_created"];
			$analysisrecm = $rows_issue_details["recommendation"];
			$daterecorded = date("d M Y", strtotime($rows_issue_details["date_created"]));

			$query_issue_owner =  $db->prepare("SELECT d.title, fullname FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles d on d.id=t.title where userid=:created_by");
			$query_issue_owner->execute(array(":created_by" => $created_by));
			$rows_issue_owner = $query_issue_owner->fetch();

			$issue_owner = $rows_issue_owner["title"] . '.' . $rows_issue_owner["fullname"];

			if ($issue_priorityid == 1) {
				$issue_priority = "High";
			} elseif ($issue_priorityid == 2) {
				$issue_priority = "Medium";
			} elseif ($issue_priorityid == 3) {
				$issue_priority = "Low";
			}

			$query_issue_area =  $db->prepare("SELECT * FROM tbl_issue_areas WHERE id=:issue_areaid");
			$query_issue_area->execute(array(":issue_areaid" => $issue_areaid));
			$rows_issue_area = $query_issue_area->fetch();
			$issue_area = $rows_issue_area["issue_area"];
		}

		$query_project_members =  $db->prepare("SELECT t.ptid, t.fullname, t.email, t.phone, t.availability, d.title, r.role FROM tbl_projmembers m left join users u on u.userid=m.responsible left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles d on d.id=t.title left join tbl_project_team_roles r on r.id=m.role where projid=:projid and team_type=4");
		$query_project_members->execute(array(":projid" => $projid));

		$style = 'style="padding:8px; border:#CCC thin solid; border-radius:5px"';

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
		<!-- start body  -->
		<script type="text/javascript">
			$(document).ready(function() {

				$('#impact').on('change', function() {
					var statusID = $(this).val();
					var projID = $("#projid").val();
					var projOrigID = $("#projorigstatus").val();
					$.ajax({
						type: 'POST',
						url: 'callchangeimpact',
						//data: {'members_id': memberID},
						data: "status_id=" + statusID + "&proj_id=" + projID + "&projOrig_id=" + projOrigID,
						success: function(data) {
							$('#formcontent').html(data);
							$("#myModal").modal({
								backdrop: "static"
							});
						}
					});
				});
			});

			function CallRiskResponse(projid) {
				$.ajax({
					type: 'post',
					url: 'callriskresponse',
					data: {
						projid: projid
					},
					success: function(data) {
						$('#riskresponse').html(data);
						$("#riskModal").modal({
							backdrop: "static"
						});
					}
				});
			}
		</script><!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg span5">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							<font color="#000000">PROJECT STATUS CHANGE REASON(S)</font>
						</h4>
					</div>
					<div class="modal-body" id="formcontent">

					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon . ' ' . $pageTitle ?>
						<div class="btn-group" style="float:right; padding-right:5px">
							<input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='projects-escalated-issues'" id="btnback">
						</div>
					</h4>
				</div>
				<div class="block-header">
					<div>
						<?php echo $results; ?>
					</div>
				</div>
				<?php if ($totalrows_issue_details > 0) { ?>
					<div class="row clearfix" style="margin-top:10px">
						<!-- Advanced Form Example With Validation -->
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="body">
									<div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
										<div class="panel panel-col-grey">
											<div class="panel-heading" role="tab" id="headingOne_17">
												<h4 class="panel-title">
													<a role="button" data-toggle="collapse" data-parent="#accordion_17" href="#history" aria-expanded="true" aria-controls="history">
														<img src="images/task.png" alt="task" /> Issue History
													</a>
												</h4>
											</div>
											<div id="history" class="panel-collapse collapse in clearfix" role="tabpanel" aria-labelledby="headingOne_17" style="padding-top: 20px; padding-bottom:-50px">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label>Project:</label>
													<div <?= $style ?>><?= $projname ?></div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label>Issue Description:</label>
													<div <?= $style ?>><?= $issuename ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Issue Category:</label>
													<div <?= $style ?>><?= $issue_category ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Issue Area:</label>
													<div <?= $style ?>><?= $issue_area ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Issue Severity Level:</label>
													<div <?= $style ?>><?= $issue_severity ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Issue Priority:</label>
													<div <?= $style ?>><?= $issue_priority ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Issue Owner:</label>
													<div <?= $style ?>><?= $issue_owner ?></div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<label>Date Recorded:</label>
													<div <?= $style ?>><?= $daterecorded ?></div>
												</div>
												<?php
												if ($issue_areaid != 1) {
												?>
													<input type="hidden" value="0" id="clicked">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px;"><i class="fa fa-columns" aria-hidden="true"></i> Issue Area Details (<?= $issue_area ?>)</legend>
															<?php if ($issue_areaid == 1) { ?>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover">
																		<?php
																		$textclass = "text-primary";
																		$issues_scope = "";
																		$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

																		$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
																		$query_site_id->execute(array(":issueid" => $issueid));

																		$sites = 0;
																		while ($rows_site_id = $query_site_id->fetch()) {
																			$sites++;
																			$site_id = $rows_site_id['site_id'];

																			if ($site_id == 0) {
																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="5">Site ' . $sites . ': Way Point Sub Tasks</th>
																			</tr>';
																			} else {
																				$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
																				$query_site->execute(array(":site_id" => $site_id));
																				$row_site = $query_site->fetch();
																				$site = $row_site['site'];

																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="5">Site ' . $sites . ': ' . $site . '</th>
																			</tr>';
																			}

																			$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
																			$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

																			$issues_scope .= $allsites . '
																		<tr class="adjustments ' . $site_id . '" style="background-color:#cccccc">
																			<th style="width:5%">#</th>
																			<th style="width:50%">Sub-Task</th>
																			<th style="width:15%">Requesting Units</th>
																			<th style="width:15%">Additional Days</th>
																			<th style="width:15%">Additional Cost</th>
																		</tr>
																	</thead><tbody>';
																			$scopecount = 0;
																			while ($row_adjustments = $query_adjustments->fetch()) {
																				$scopecount++;
																				$subtask = $row_adjustments["task"];
																				$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
																				$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
																				$timeline = $row_adjustments["timeline"] . " days";
																				$issues_scope .= '<tr class="adjustments ' . $site_id . '" style="background-color:#e5e5e5">
																			<td>' . $scopecount . '</td>
																			<td>' . $subtask . '</td>
																			<td>' . $units . ' </td>
																			<td>' . $timeline . '</td>
																			<td>' . number_format($totalcost, 2) . ' </td>
																		</tr>';
																			}
																			$issues_scope .= '</tbody>';
																		}
																		echo $issues_scope;
																		?>
																	</table>
																</div>
															<?php } elseif ($issue_areaid == 2) { ?>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover">
																		<?php
																		$textclass = "text-primary";
																		$issues_scope = "";
																		$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

																		$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
																		$query_site_id->execute(array(":issueid" => $issueid));

																		$sites = 0;
																		while ($rows_site_id = $query_site_id->fetch()) {
																			$sites++;
																			$site_id = $rows_site_id['site_id'];

																			if ($site_id == 0) {
																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="5">Site ' . $sites . ': Way Point Sub Tasks</th>
																			</tr>';
																			} else {
																				$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
																				$query_site->execute(array(":site_id" => $site_id));
																				$row_site = $query_site->fetch();
																				$site = $row_site['site'];

																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="5">Site ' . $sites . ': ' . $site . '</th>
																			</tr>';
																			}

																			$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
																			$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

																			$issues_scope .= $allsites . '
																		<tr class="adjustments ' . $site_id . '" style="background-color:#cccccc">
																			<th style="width:5%">#</th>
																			<th style="width:50%">Sub-Task</th>
																			<th style="width:15%">Requesting Units</th>
																			<th style="width:15%">Additional Days</th>
																			<th style="width:15%">Additional Cost</th>
																		</tr>
																	</thead><tbody>';
																			$scopecount = 0;
																			while ($row_adjustments = $query_adjustments->fetch()) {
																				$scopecount++;
																				$subtask = $row_adjustments["task"];
																				$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
																				$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
																				$timeline = $row_adjustments["timeline"] . " days";
																				$issues_scope .= '<tr class="adjustments ' . $site_id . '" style="background-color:#e5e5e5">
																			<td>' . $scopecount . '</td>
																			<td>' . $subtask . '</td>
																			<td>' . $units . ' </td>
																			<td>' . $timeline . '</td>
																			<td>' . number_format($totalcost, 2) . ' </td>
																		</tr>';
																			}
																			$issues_scope .= '</tbody>';
																		}
																		echo $issues_scope;
																		?>
																	</table>
																</div>
															<?php } elseif ($issue_areaid == 3) { ?>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover">
																		<?php
																		$textclass = "text-primary";
																		$issues_scope = "";
																		$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

																		$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
																		$query_site_id->execute(array(":issueid" => $issueid));

																		$sites = 0;
																		while ($rows_site_id = $query_site_id->fetch()) {
																			$sites++;
																			$site_id = $rows_site_id['site_id'];

																			if ($site_id == 0) {
																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="3">Site ' . $sites . ': Way Point Sub Tasks</th>
																			</tr>';
																			} else {
																				$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
																				$query_site->execute(array(":site_id" => $site_id));
																				$row_site = $query_site->fetch();
																				$site = $row_site['site'];

																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="3">Site ' . $sites . ': ' . $site . '</th>
																			</tr>';
																			}


																			$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, c.unit_cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
																			$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

																			$issues_scope .= $allsites . '
																		<tr class="adjustments ' . $site_id . '" style="background-color:#cccccc">
																			<th style="width:5%">#</th>
																			<th style="width:80%">Sub-Task</th>
																			<th style="width:15%">Additional Days</th>
																		</tr>
																	</thead><tbody>';
																			$scopecount = 0;
																			while ($row_adjustments = $query_adjustments->fetch()) {
																				$scopecount++;
																				$subtask = $row_adjustments["task"];
																				$units =  number_format($row_adjustments["units"]) . " " . $row_adjustments["unit"];
																				$totalcost = $row_adjustments["unit_cost"] * $row_adjustments["units"];
																				$timeline = $row_adjustments["timeline"] . " days";
																				$issues_scope .= '<tr class="adjustments ' . $site_id . '" style="background-color:#e5e5e5">
																			<td>' . $sites . '.' . $scopecount . '</td>
																			<td>' . $subtask . '</td>
																			<td>' . $timeline . '</td>
																		</tr>';
																			}
																			$issues_scope .= '</tbody>';
																		}
																		echo $issues_scope;
																		?>
																	</table>
																</div>
															<?php } elseif ($issue_areaid == 4) { ?>
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover">
																		<?php
																		$textclass = "text-primary";
																		$issues_scope = "";
																		$leftjoin = $projcategory == 2 ? "left join tbl_project_tender_details c on c.subtask_id=a.sub_task_id" : "left join tbl_project_direct_cost_plan c on c.subtask_id=a.sub_task_id";

																		$query_site_id = $db->prepare("SELECT site_id FROM tbl_project_adjustments WHERE issueid=:issueid GROUP BY site_id");
																		$query_site_id->execute(array(":issueid" => $issueid));

																		$sites = 0;
																		while ($rows_site_id = $query_site_id->fetch()) {
																			$sites++;
																			$site_id = $rows_site_id['site_id'];

																			if ($site_id == 0) {
																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="4">Site ' . $sites . ': Way Point Sub Tasks</th>
																			</tr>';
																			} else {
																				$query_site = $db->prepare("SELECT site FROM tbl_project_sites WHERE site_id=:site_id");
																				$query_site->execute(array(":site_id" => $site_id));
																				$row_site = $query_site->fetch();
																				$site = $row_site['site'];

																				$allsites = '
																		<thead>
																			<tr style="background-color:#a9a9a9" onclick="adjustedscopes(' . $site_id . ')">
																				<th colspan="4">Site ' . $sites . ': ' . $site . '</th>
																			</tr>';
																			}

																			$query_adjustments = $db->prepare("SELECT t.task, a.units, a.timeline, a.cost, u.unit FROM tbl_project_adjustments a left join tbl_projissues i on i.id = a.issueid left join tbl_task t on t.tkid=a.sub_task_id " . $leftjoin . " left join tbl_measurement_units u on u.id=c.unit WHERE i.projid = :projid and issueid = :issueid and a.site_id=:site_id GROUP BY a.id");
																			$query_adjustments->execute(array(":projid" => $projid, ":issueid" => $issueid, ":site_id" => $site_id));

																			$issues_scope .= $allsites . '
																		<tr class="adjustments ' . $site_id . '" style="background-color:#cccccc">
																			<th style="width:5%">#</th>
																			<th style="width:65%">Sub-Task</th>
																			<th style="width:15%">Measurement Unit</th>
																			<th style="width:15%">Additional Cost</th>
																		</tr>
																	</thead><tbody>';
																			$scopecount = 0;
																			while ($row_adjustments = $query_adjustments->fetch()) {
																				$scopecount++;
																				$subtask = $row_adjustments["task"];
																				$unit =  $row_adjustments["unit"];
																				$cost = $row_adjustments["cost"];

																				$issues_scope .= '
																			<tr class="adjustments ' . $site_id . '" style="background-color:#e5e5e5">
																				<td>' . $sites . '.' . $scopecount . '</td>
																				<td>' . $subtask . '</td>
																				<td>' . $unit . ' </td>
																				<td>' . number_format($cost, 2) . ' </td>
																			</tr>';
																			}
																			$issues_scope .= '</tbody>';
																		}
																		echo $issues_scope;
																		?>
																	</table>
																</div>
															<?php } ?>
														</fieldset>
													</div>
												<?php
												}
												?>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px;"><i class="fa fa-paperclip" aria-hidden="true"></i> Issue Attachments</legend>
														<div class="table-responsive">
															<table class="table table-bordered table-striped table-hover">
																<thead>
																	<tr>
																		<th width="3%">#</th>
																		<th width="40%">File Name</th>
																		<th width="10%">File Type</th>
																		<th width="37%">File Purpose</th>
																		<th width="10%">Download</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$query_attachments = $db->prepare("SELECT * FROM tbl_files WHERE projid = :projid and projstage = :issueid and fcategory='Issue'");
																	$query_attachments->execute(array(":projid" => $projid, ":issueid" => $issueid));
																	//return;

																	$attachmentcount = 0;
																	while ($row_attachments = $query_attachments->fetch()) {
																		$attachmentcount++;
																		$filename = $row_attachments["filename"];
																		$filelocation =  $row_attachments["floc"];
																		$filepurpose = $row_attachments["reason"];
																		$filetype = $row_attachments["ftype"];

																		echo '<tr >
																	<td>' . $attachmentcount . '</td>
																	<td>' . $filename . '</td>
																	<td>' . $filetype . ' </td>
																	<td>' . $filepurpose . '</td>
																	<td><a href="' . $filelocation . '" download="' . $filename . '" type="button" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a></td>
																</tr>';
																	}
																	?>
																</tbody>
															</table>
														</div>
													</fieldset>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<fieldset class="scheduler-border">
														<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px; width:100%"><i class="fa fa-users" aria-hidden="true"></i> Project Team Members</legend>
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

																	while ($rows_project_members = $query_project_members->fetch()) {
																		$sn = $sn + 1;
																		$mbrid = $rows_project_members['ptid'];
																		$member = $rows_project_members["title"] . "." . $rows_project_members["fullname"];
																		$role = $rows_project_members['role'];
																		$email = $rows_project_members['email'];
																		$phone = $rows_project_members['phone'];
																		$available = $rows_project_members['availability'];

																		$query_member_designation =  $db->prepare("SELECT d.designation FROM tbl_projteam2 t inner join tbl_pmdesignation d on d.moid=t.designation where t.ptid='$mbrid'");
																		$query_member_designation->execute();
																		$row_member_designation = $query_member_designation->fetch();
																		$designation = $row_member_designation['designation'];


																		if ($available == 0) {

																			$query_member_leave_details =  $db->prepare("SELECT assignee FROM tbl_project_team_leave where owner='$mbrid' and resumed_at IS NULL and projid=:projid");
																			$query_member_leave_details->execute(array(":projid" => $projid));
																			$row_member_leave_details = $query_member_leave_details->fetch;
																			$totalRows_member_leave_details = $query_member_leave_details->rowCount();

																			$query_member_leave =  $db->prepare("SELECT startdate, enddate FROM tbl_employee_leave where employee='$mbrid' and status=2");
																			$query_member_leave->execute();
																			$row_member_leave = $query_member_leave->fetch;
																			$totalRows_member_leave = $query_member_leave->rowCount();
																			if ($row_member_leave) {
																				$leave_start_date = date("d M Y", strtotime($row_member_leave['startdate']));
																				$leave_end_date = date("d M Y", strtotime($row_member_leave['enddate']));
																			}

																			$availability = "Unavailable";
																			$reassignee = $rows['assignee'];

																			$query_reassignee =  $db->prepare("SELECT fullname, d.title, phone, email FROM tbl_projteam2 t left join tbl_titles d on d.id=t.title where ptid='$reassignee'");
																			$query_reassignee->execute();
																			$row_reassignee = $query_reassignee->fetch();
																			$reassigneedto = $row_reassignee["title"] . "." . $row_reassignee["fullname"];
																			$reassigneedphone = $row_reassignee["phone"];
																			$reassigneedemail = $row_reassignee["email"];
																			$availclass = 'class="text-warning"';
																		} else {
																			$availability = "Available";
																			$availclass = 'class="text-success"';
																		}
																	?>
																		<tr id="rowlines">
																			<td><?php echo $sn; ?></td>
																			<td><?php echo $member; ?></td>
																			<td><?php echo $role; ?></td>
																			<td><?php echo $designation; ?></td>
																			<?php if ($available == 0) { ?>
																				<td <?= $availclass ?>><span class="mytooltip tooltip-effect-1"><span class="tooltip-item2"><?php echo $availability; ?></span><span class="tooltip-content4 clearfix" style="background-color:#CDDC39; color:#000"><span class="tooltip-text2">
																								<h4 align="center"><u>Details</u></h4><strong>Unavailable From Date:</strong> <?php echo $leave_start_date; ?><br> <strong>In-Place:</strong> <?php echo $reassigneedto; ?><br> <strong>In-Place Phone:</strong> <?php echo $reassigneedphone; ?><br> <strong>In-Place Email:</strong> <?php echo $reassigneedemail; ?>
																							</span></span></span></td>
																			<?php } else { ?>
																				<td><?php echo $availability; ?></td>
																			<?php } ?>
																			<td><a href="tel:<?= $phone ?>"><?= $phone ?></a></td>
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
										</div>
										<div class="panel panel-col-teal">
											<div class="panel-heading" role="tab" id="headingTwo_17">
												<h4 class="panel-title">
													<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#recommendation" aria-expanded="false" aria-controls="recommendation">
														<i class="fa fa-plus-square" aria-hidden="true"></i> Project Committee Recommendation
													</a>
												</h4>
											</div>
											<div id="recommendation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17" style="padding-top: 20px">
												<form id="addcmtfrm" method="POST" name="addcmtfrm" action="" autocomplete="off">
													<?= csrf_token_html(); ?>
													<div class="row clearfix" style="padding-left:10px; padding-right:10px">
														<?php
														$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus FROM tbl_projects WHERE projid = :projid");
														$query_projstatuschange->execute(array(":projid" => $projid));
														$row_projstatuschange = $query_projstatuschange->fetch();
														$projstatus = $row_projstatuschange["projstatus"];
														$projcode = $row_projstatuschange["projcode"];

														$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and issueid='$issueid'");
														$query_escalationstage->execute();
														$escalationstage_count = $query_escalationstage->rowCount();
														$assessmentcomments = array();
														while ($row_escalationstage = $query_escalationstage->fetch()) {
															$assessmentcomments[] = $row_escalationstage["issue_status"];
														}

														//6 is On Hold status
														if ($projstatus == 6) {
															$query_issueassessment = $db->prepare("SELECT assessment FROM tbl_projissues WHERE id=:issueid and projid=:projid");
															$query_issueassessment->execute(array(":issueid" => $issueid, ":projid" => $projid));
															$row_issueassessment = $query_issueassessment->fetch();
															$assessment = $row_issueassessment["assessment"];

															$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid =:projid and issueid=:issueid");
															$query_escalationstage->execute(array(":projid" => $projid, ":issueid" => $issueid));
															$escalationstage_count = $query_escalationstage->rowCount();
															$assessmentcomments = array();
															while ($row_escalationstage = $query_escalationstage->fetch()) {
																$assessmentcomments[] = $row_escalationstage["issue_status"];
															}

															$query_escalationstage_comments = $db->prepare("SELECT comments FROM tbl_projissue_comments WHERE projid =:projid and issueid=:issueid and issue_status='On Hold'");
															$query_escalationstage_comments->execute(array(":projid" => $projid, ":issueid" => $issueid));
															$count_comments = $query_escalationstage_comments->rowCount();
															$escalationstage_comments = $query_escalationstage_comments->fetch();
														?>
															<div class="col-md-12">
																<label>
																	<font color="#174082"><i class="fa fa-bar-chart" aria-hidden="true"></i> On Hold Remarks:</font>
																	</font>
																</label>
																<div class="form-control" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">
																	<?php echo $escalationstage_comments["comments"]; ?>
																</div>
															</div>
															<div class="col-md-6">
																<label>
																	<font color="#174082">Committee Final Action:</font>
																</label>
																<div class="form-line">
																	<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true" onchange="committee_action(<?= $projid ?>,<?= $issueid ?>)" style="border:#CCC thin solid; border-radius:5px" required>
																		<option value="" selected="selected" class="selection">... Select ...</option>
																		<option value="RestoreAndApprove">Restore the Project and Approve the Request</option>
																		<option value="Restore">Restore the Project and Close the Issue!</option>
																		<option value="Cancelled">Cancel the Project</option>
																	</select>
																</div>
															</div>
														<?php
														} elseif ($projstatus !== 6) {
															//$option1 = $issue_areaid == 2 ? '<option value="ApprovedScope">Approve the Request</option>' : '<option value="adjust">Adjust Project Affected Parameters</option>';
														?>
															<div class="col-md-6">
																<label>
																	<font color="#174082">Committee Action:</font>
																</label>
																<div class="form-line">
																	<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true" onchange="committee_action(<?= $projid ?>,<?= $issueid ?>)" style="border:#CCC thin solid; border-radius:5px" required>
																		<option value="" selected="selected" class="selection">... Select ...</option>
																		<?php //echo $option1;
																		?>
																		<option value="Approved">Approve the Request</option>
																		<option value="On Hold">Put the Project On Hold</option>
																		<option value="Cancelled">Cancel the Project</option>
																		<option value="Continue">Ignore and Close the Issue!</option>
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
		<script src="assets/js/issues/index.js"></script>
<?php
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>