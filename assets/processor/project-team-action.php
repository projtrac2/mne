<?php
include_once "controller.php";
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function send_email($projid, $ptid,$profession_id){
	global $db;
	$query_projmapping = $db->prepare("SELECT projname, projcode FROM `tbl_projects` WHERE projid=:projid");
	$query_projmapping->execute(array(":projid" => $projid));
	$row_projmapping = $query_projmapping->fetch();
	$projname = $row_projmapping["projname"];
	$projcode = $row_projmapping["projcode"];
	
	$query_team = $db->prepare("SELECT * FROM `tbl_projteam2` WHERE ptid = '$ptid'");
	$query_team->execute();
	$row_team = $query_team->fetch();
	$fullname = $row_team['fullname'];
	$to =$row_team['email'];

	// role 
	$query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE id=:profession_id");
	$query_projrole->execute(array(':profession_id'=>$profession_id));
	$row_projrole = $query_projrole->fetch();
	$profession = $row_projrole['role'];
	
	$mainmessage = '
	<p>Please note that you have been selected as '.$profession.' for the project detailed below.</p>
	<p>Project Code:' . $projcode . '<br>
	Project Name: ' . $projname . '</p>
	<p>Prepare the required resources. </p>';
	$detailslink = "";
	
	$mail = new PHPMailer(true);
	try {
		//Server settings
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host       = 'smtp.mailtrap.io';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'cffa73c2820a70';
		$mail->Password   = 'f4cfb6bf7b268d';
		// $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Port       = 2525;

		//Recipients
		$mail->setFrom('projtrac@info.co.ke', 'Projtrac');
		$mail->addAddress($to,$fullname);
		include('../../mapping-assignment-notification-body.php');
		//Content
		$mail->isHTML(true);
		$mail->Subject = "Team Selection";
		$mail->Body    = $body;

		$mail->send();
		// echo 'Message has been sent';
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}

// get add insoection checklist table 
if (isset($_POST['getAddProjectTeam'])) {

	$projid = $_POST['projid'];
	$query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projid = :projid and p.deleted='0' and p.projplanstatus=1");
	$query_rsProjects->execute(array(":projid" => $projid));
	$row_rsProjects = $query_rsProjects->fetch();
	$totalRows_rsProjects = $query_rsProjects->rowCount();
	$Projdept = $row_rsProjects["sector"];
	
	$query_departmentTeam =  $db->prepare("SELECT * FROM tbl_projteam2");
	$query_departmentTeam->execute(array(":taskid" => $taskid));
	$row_departmentTeam = $query_departmentTeam->fetch();

	return;
	echo '
	<fieldset class="scheduler-border">
        <div class="header" style="background-color:#c7e1e8; border-radius:3px; padding:5px">
			<h4>Please add project team member/s and their respective role/s</h4>
		</div>
		<div class="body">
			<input type="hidden" name="projid" id="projid"  value="' . $projid . '"> 
			<input type="hidden" name="department" id="dept"  value="' . $Projdept . '">  
			<input type="hidden" name="action" id="action"  value="1"> 		
			<div  class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
						<thead>
							<tr>
								<th width="5%">#</th>
								<th width="27%">Role</th> 
								<th width="65%">Department</th>
								<th width="65%">Member</th>
								<th width="5%">
									<button type="button" name="addplus" id="addplus_member" onclick="add_row_member();" class="btn btn-success btn-sm">
										<span class="fa fa-plus-square">
										</span>
									</button>
								</th>
							</tr>
						</thead>
						<tbody id="member_table_body">
							<tr></tr>
							<tr id="hideinfo">
								<td colspan="4">Add Member!!</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="checklist">
				</div>
			</div>
		</div>
	</fieldset>';
}

//Update Team
if (isset($_POST['getEditProjectTeam'])) {
	$projid = $_POST['projid'];
	$query_rsTeam = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid = :projid");
	$query_rsTeam->execute(array(":projid" => $projid));
	$row_rsTeam = $query_rsTeam->fetch();
	echo ' 
	<fieldset class="scheduler-border">
        <div class="header" style="background-color:#c7e1e8; border-radius:3px; padding:5px">
			<h4>Please add project team member/s and their respective role/s</h4>
		</div>
		<div class="body">
			<input type="hidden" name="projid" id="projid"  value="' . $projid . '"> 
			<input type="hidden" name="department" id="dept"  value="' . $Projdept . '">   
			<input type="hidden" name="action" id="action"  value="2"> 				
			<div  class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th width="27%">Role</th>
								<th width="65%">Department</th>
								<th width="65%">Member</th>
								<th width="5%">
									<button type="button" name="addplus" id="addplus_member" onclick="add_row_member();" class="btn btn-success btn-sm">
										<span class="fa fa-plus-square">
										</span>
									</button>
								</th>
							</tr>
						</thead>
						<tbody id="member_table_body">';
							$rowno = 0;
							do {
								$memberid =  $row_rsTeam['ptid'];
								$roleid =  $row_rsTeam['role'];
								
								$query_rsRole = $db->prepare("SELECT *  FROM tbl_project_team_roles WHERE id = :role");
								$query_rsRole->execute(array(":role" => $roleid));
								$row_rsRole = $query_rsRole->fetch();
								$role = $row_rsRole["role"];
								
								$query_rsMember = $db->prepare("SELECT *  FROM tbl_projteam2 ");
								$query_rsMember->execute(array(":member" => $memberid));
								$row_rsMember = $query_rsMember->fetch();

								
								$option ="";
								do{
									$ptid = $row_rsMember["ptid"];
									$title = $row_rsMember["title"];
									$firstname = $row_rsMember["firstname"];
									$middlename = $row_rsMember["middlename"];
									$lastname = $row_rsMember["lastname"];
									$member = $title.".".$firstname." ".$middlename." ".$lastname;
									$selected = ($ptid == $memberid) ?  "selected": "" ;
									$option .= '<option value="'.$ptid.'" '.$selected.'>'.$member.'</option>';
								}while($row_rsMember = $query_rsMember->fetch());

								$rowno++;
								echo
									'<tr id="row' . $rowno  . '">
										<td>' . $rowno . '</td>
										<td>
											<select data-id="'. 	$rowno .'" name="role[]" id="rolerow'.$rowno .'" class="form-control validrole selectRole" required="required">
												<option value="">Select Member Role</option>
												<option value="'.$roleid.'" selected>'.$role.'</option>
											</select>
										</td>
										<td>
											<select data-id="'. 	$rowno . '" name="member[]" id="memberrow' . $rowno .'" class="form-control selectpicker validmember selectMember" data-live-search="true"  required="required">
												<option value="">Select Project Member </option>
												'.$option.' 
											</select>
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_member("row'. $rowno . 	'")><span class="glyphicon glyphicon-minus"></span>
											</button>
										</td>
									</tr>';
							} while ($row_rsTeam = $query_rsTeam->fetch());
						echo '</tbody>
					</table>
				</div>
			</div>
		</div>
	</fieldset> ';
}

if (isset($_POST['getprojmember'])) {
	$projid = $_POST['projid'];
	$query_team = $db->prepare("SELECT * FROM `tbl_projteam2` ");
	$query_team->execute();
	$row_team = $query_team->fetch();

	$input = '';
	$input .= '<option value="">Select Member</option>';
	do {
		$ptid = $row_team['ptid'];
		$title = $row_team['title'];
		$firstname = $row_team['firstname'];
		$middlename = $row_team['middlename'];
		$lastname = $row_team['lastname'];
		$membername = $title.". ".$firstname." ".$middlename." ".$lastname;

		$input .= '<option value="' . $ptid . '">' . $membername . '</option>';
	} while ($row_team = $query_team->fetch());
	
	echo $input;
}

if (isset($_POST['get_department'])) {
	$query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent =0 AND  active=1");
	$query_department->execute();
	$row_department = $query_department->fetch();

	$input = '';
	$input .= '<option value="">Select Department</option>';
	do {
		$id = $row_projrole['id'];
		$sector = $row_projrole['sector'];
		$input .= '<option value="' . $id . '">' . $sector . '</option>';
	} while ($row_department = $query_department->fetch());
	
	echo $input;
}

if (isset($_POST['getmemberrole'])) {
	$query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE active=1");
	$query_projrole->execute();
	$row_projrole = $query_projrole->fetch();

	$input = '';
	$input .= '<option value="">Select Role</option>';
	do {
		$id = $row_projrole['id'];
		$role = $row_projrole['role'];
		$input .= '<option value="' . $id . '">' . $role . '</option>';
	} while ($row_projrole = $query_projrole->fetch());
	
	echo $input;
}

// ading checklist to the db 
if (isset($_POST['addProjTeam'])) { 
	$current_date = date("Y-m-d");
	$role = $_POST['role'];
	$projid = $_POST['projid'];
	$member =  $_POST['member'];
	$user_name = $_POST['user_name'];
	for ($i = 0; $i < count($_POST["member"]); $i++) {
		$ptid = $member[$i];
		$insertSQL = $db->prepare("INSERT INTO tbl_projmembers (ptid, projid, role, dateentered, user_name) VALUES (:ptid, :projid, :role, :datecreated, :createdby)");
		$results = $insertSQL->execute(array(':ptid' => $ptid, ':projid' => $projid, ':role' => $role[$i], ':datecreated' => $current_date, ':createdby' => $user_name));
		send_email($projid, $ptid, $role[$i]);
	}

	if ($results === TRUE) {
		$query_projmapping = $db->prepare("SELECT projmapping, projstage FROM `tbl_projects` WHERE projid=:projid");
		$query_projmapping->execute(array(":projid" => $projid));
		$row_projmapping = $query_projmapping->fetch();
		$projmapping = $row_projmapping["projmapping"];
		$projstage = $row_projmapping["projstage"];

		if($projmapping == 1){
			$stage = 3;
		}else{
			$stage = 4;
		}
		
		$insertQuery = $db->prepare("UPDATE `tbl_projects` set projstage=:stage WHERE projid=:projid");
		$insertQuery->execute(array(":stage" => $stage, ':projid' => $projid));
		
		$valid['success'] = true;
		$valid['messages'] = "Project Team Successfully Added";

	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the Project Team!!";
	}
	echo json_encode($valid);
}


// updating Project Team
if (isset($_POST["editProjTeam"])) {
	$current_date = date("Y-m-d");
	$role = $_POST['role'];
	$projid = $_POST['projid'];
	$member =  $_POST['member'];
	$user_name = $_POST['user_name'];
	
	$deleteQueryO = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid");
	$resultsO = $deleteQueryO->execute(array(':projid' => $projid));

	for ($i = 0; $i < count($_POST["member"]); $i++) {
		$insertSQL = $db->prepare("INSERT INTO tbl_projmembers (ptid, projid, role, dateentered, user_name) VALUES (:ptid, :projid, :role, :datecreated, :createdby)");
		$results = $insertSQL->execute(array(':ptid' => $member[$i], ':projid' => $projid, ':role' => $role[$i], ':datecreated' => $current_date, ':createdby' => $user_name));
	}
  
	if ($results === TRUE) {
		$query_projmapping = $db->prepare("SELECT projmapping FROM `tbl_projects` WHERE projid=:projid");
		$query_projmapping->execute();
		$row_projmapping = $query_projmapping->fetch();
		$projmapping = $row_projmapping["projmapping"];

		if($projmapping == 1){
			$stage = 3;
		}else{
			$stage = 4;
		}

		$insertQuery = $db->prepare("UPDATE `tbl_projects` set projstage=:stage WHERE projid=:projid");
		$insertQuery->execute(array(":stage" => $stage, ':projid' => $projid));

		$valid['success'] = true;
		$valid['messages'] = "Project Team Successfully Edited";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while editing the Project Team!!";
	}
	echo json_encode($valid);
}

// deleting checklist 
if (isset($_POST['deleteTeam'])) {
	$projid = $_POST['projid'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid");
	$results = $deleteQuery->execute(array(':projid' => $projid));

	if ($results === TRUE) {
		$stage = 2;
		$approveItemQuery = $db->prepare("UPDATE `tbl_projects` set projstage=:stage WHERE projid=:projid");
		$approveItemQuery->execute(array(":stage" => $stage, ':projid' => $projid));
		
		$valid['success'] = true;
		$valid['messages'] = "Project Team Successfully Deleted";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deletng the Project Team!!";
	}
	echo json_encode($valid);
}


if (isset($_POST['more'])) {
	$projid = $_POST['projid'];
	$query_rsTeam = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid = :projid");
	$query_rsTeam->execute(array(":projid" => $projid));
	$row_rsTeam = $query_rsTeam->fetch();

	echo ' 
	<fieldset class="scheduler-border">
        <div class="card-header" style="background-color:#c7e1e8; border-radius:3px; padding:5px">
			<h4>Please add project team member/s and their respective role/s</h4>
		</div>
		<div class="body">
			<input type="hidden" name="action" id="action"  value="2">
			<div  class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th width="27%">Role</th>
								<th width="65%">Member</th>
							</tr>
						</thead>
						<tbody id="member_table_body">';
							$rowno = 0;
							do {
								$memberid =  $row_rsTeam['ptid'];
								$roleid =  $row_rsTeam['role'];
								
								$query_rsRole = $db->prepare("SELECT *  FROM tbl_project_team_roles WHERE id = :role");
								$query_rsRole->execute(array(":role" => $roleid));
								$row_rsRole = $query_rsRole->fetch();
								$role = $row_rsRole["role"];
								
								$query_rsMember = $db->prepare("SELECT *  FROM tbl_projteam2 where ptid=:member ");
								$query_rsMember->execute(array(":member" => $memberid));
								$row_rsMember = $query_rsMember->fetch();

								$ptid = $row_rsMember["ptid"];
								$title = $row_rsMember["title"];
								$firstname = $row_rsMember["firstname"];
								$middlename = $row_rsMember["middlename"];
								$lastname = $row_rsMember["lastname"];
								$member = $title.".".$firstname." ".$middlename." ".$lastname; 

								$rowno++;
								echo
									'<tr id="row">
										<td>' . $rowno . '</td>
										<td>
										'.$role.'
										</td>
										<td>
											'.$member.'
										</td>
									</tr>';
							} while ($row_rsTeam = $query_rsTeam->fetch());
						echo '</tbody>
					</table>
				</div>
			</div>
		</div>
	</fieldset> ';
}
