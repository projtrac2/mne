<?php  
include_once "controller.php";
function send_email($projid, $user_id, $profession_id)
{
	global $db;
	$query_projmapping = $db->prepare("SELECT projname, projcode FROM `tbl_projects` WHERE projid=:projid");
	$query_projmapping->execute(array(":projid" => $projid));
	$row_projmapping = $query_projmapping->fetch();
	$projname = $row_projmapping["projname"];
	$projcode = $row_projmapping["projcode"];

	$query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
	$query_team->execute(array(":user_id" => $user_id));
	$row_team = $query_team->fetch();
	$fullname = $row_team['fullname'];
	$receipient = $row_team['email'];
	// $receipient = "biwottech@gmail.com";

	// role 
	$query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE id=:profession_id");
	$query_projrole->execute(array(':profession_id' => $profession_id));
	$row_projrole = $query_projrole->fetch();
	$profession = $row_projrole['role'];

	$data = array(
		"sitename" => "Projtrac Monitoring and Evaluation System",
		"firstname" => $fullname,
		"contact" => "072137045",
		"password" => $profession,
		"recipient" => $receipient,
	);

	$mail = new Email();
	$template = $mail->email_template(1, $data);
	$template .= '
	<p>Please note that you have been selected to partcipate in the project detailed below.</p>
	<p>Project Code:' . $projcode . '<br>
	Project Name: ' . $projname . '<br> 
	<p>Prepare the required resources. </p>';

	$data = array(
		"subject" => "Project Team Selection",
		"title" => "Project Team Selection",
		"receipient" => $receipient,
		"receipient_name" => $fullname,
		"template" => $template,
		"page_url" => "myprojects.php",
		"attachment" => ""
	);
	$mail_response = $mail->sendMail($data);
	return $mail_response;
}

// get add insoection checklist table 
if (isset($_POST['getAddProjectTeam'])) {
	$projid = $_POST['projid'];
	$query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projid = :projid and p.deleted='0' and p.projplanstatus=1");
	$query_rsProjects->execute(array(":projid" => $projid));
	$row_rsProjects = $query_rsProjects->fetch();
	$totalRows_rsProjects = $query_rsProjects->rowCount();
	$Projdept = $row_rsProjects["sector"];

	$query_departmentTeam =  $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid");
	$query_departmentTeam->execute();
	$row_departmentTeam = $query_departmentTeam->fetch();

	echo '
	<fieldset class="scheduler-border">
        <div class="header" style="background-color:#c7e1e8; border-radius:3px; padding:5px">
			<h4>Please add project team member/s and their respective role/s</h4>
		</div>
		<style>
			.bootstrap-select .dropdown-menu {
				margin: 15px 0 0; 
				padding:15px;
			}
		</style>
		<div class="body">
			<input type="hidden" name="projid" id="projid"  value="' . $projid . '"> 
			<input type="hidden" name="department" id="dept"  value="">  
			<input type="hidden" name="action" id="action"  value="1"> 		
			<div  class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th width="25%">Role</th>
								<th width="40%">Department</th>
								<th width="27%">Member</th>
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
	$total_rsTeam = $query_rsTeam->rowCount();

	echo ' 
	<fieldset class="scheduler-border">
        <div class="header" style="background-color:#c7e1e8; border-radius:3px; padding:5px">
			<h4>Please add project team member/s and their respective role/s</h4>
		</div>
		<div class="body">
			<input type="hidden" name="projid" id="projid"  value="' . $projid . '"> 
			<input type="hidden" name="department" id="dept"  value="">   
			<input type="hidden" name="action" id="action"  value="2">
			<style>
				.bootstrap-select .dropdown-menu {
					margin: 15px 0 0;
					padding:15px;
				}
			</style>
			<div  class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th width="25%">Role</th>
								<th width="40%">Department</th>
								<th width="27%">Member</th>
								<th width="5%">
									<button type="button" name="addplus" id="addplus_member" onclick="add_row_member();" class="btn btn-success btn-sm">
										<span class="fa fa-plus-square">
										</span>
									</button>
								</th>
							</tr>
						</thead>
						<tbody id="member_table_body"> 
						<tr>
						</tr>
						';

	$rowno = 0;
	if ($total_rsTeam > 0) {
		do {
			$memberid =  $row_rsTeam['ptid'];
			$roleid =  $row_rsTeam['role'];

			$query_rsRole = $db->prepare("SELECT *  FROM tbl_project_team_roles WHERE id = :role");
			$query_rsRole->execute(array(":role" => $roleid));
			$row_rsRole = $query_rsRole->fetch();
			$role = $row_rsRole["role"];

			$query_rsMembers = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:member");
			$query_rsMembers->execute(array(":member" => $memberid));
			$row_rsMembers = $query_rsMembers->fetch();

			$ministry = $row_rsMembers['ministry'];
			$query_rsMember = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE p.ministry='$ministry'");
			$query_rsMember->execute();
			$row_rsMember = $query_rsMember->fetch();

			$query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent =0 ");
			$query_department->execute();
			$row_department = $query_department->fetch();

			$input = '';
			$input .= '<option value="">Select Department</option>';
			do {
				$id = $row_department['stid'];
				$sector = $row_department['sector'];
				$selected = ($ministry == $id) ?  "selected" : "";
				$input .= '<option value="' . $id . '" ' . $selected . '>' . $sector . '</option>';
			} while ($row_department = $query_department->fetch());

			$option = "";
			do {
				$userid = $row_rsMember["userid"];
				$title = $row_rsMember["title"];
				$firstname = $row_rsMember["firstname"];
				$middlename = $row_rsMember["middlename"];
				$lastname = $row_rsMember["lastname"];
				$member = $title . "." . $firstname . " " . $middlename . " " . $lastname;
				$selected = ($userid == $memberid) ?  "selected" : "";
				$option .= '<option value="' . $userid . '" ' . $selected . '>' . $member . '</option>';
			} while ($row_rsMember = $query_rsMember->fetch());

			$rowno++;
			echo
			'<tr id="row' . $rowno  . '">
				<td>' . $rowno . '</td>
				<td>
					<select data-id="' . 	$rowno . '" name="role[]" id="rolerow' . $rowno . '" class="form-control validrole selectRole" required="required">
						<option value="">Select Member Role</option>
						<option value="' . $roleid . '" selected>' . $role . '</option>
					</select>
				</td>
				<td>
					<select data-id="' . 	$rowno . '" name="department[]" id="departmentrow' . $rowno . '" onchange="getmember(' . $rowno . ')" class="form-control selectpicker validmember selectMember" data-live-search="true"  required="required">
						<option value="">Select Project Department </option>
						' . $input . ' 
					</select>
				</td>
				<td>
					<select data-id="' . 	$rowno . '" name="member[]" id="memberrow' . $rowno . '" class="form-control selectpicker validmember selectMember" data-live-search="true"  required="required">
						<option value="">Select Project Member </option>
						' . $option . ' 
					</select>
				</td>
				<td> 
					<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_member("row' . $rowno . 	'")><span class="glyphicon glyphicon-minus"></span>
					</button>
				</td> 
			</tr>';
		} while ($row_rsTeam = $query_rsTeam->fetch());
	} else {
		echo '<tr> <td colspan="3">No Record found</td></tr>';
	}
	echo '</tbody>
					</table>
				</div>
			</div>
		</div>
	</fieldset> ';
}

if (isset($_POST['getprojmember'])) {
	$projid = $_POST['projid'];
	$department = $_POST['department'];
	$query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE  p.ministry='$department' ");
	$query_team->execute();
	$row_team = $query_team->fetch();
	$countrow_team = $query_team->rowCount();

	$input = '';
	if($countrow_team > 0){
		$input .= '<option value="">Select Member</option>';
		do {
			$userid = $row_team['userid'];
			$title = $row_team['title'];
			$firstname = $row_team['firstname'];
			$middlename = $row_team['middlename'];
			$lastname = $row_team['lastname'];
			$membername = $title . ". " . $firstname . " " . $middlename . " " . $lastname;
			$input .= '<option value="' . $userid . '">' . $membername . '</option>';
		} while ($row_team = $query_team->fetch());
	}else{
		$input .= '<option value="">Select Department</option>';
	}
	echo $input;
}

if (isset($_POST['get_department'])) {
	$query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent =0 ");
	$query_department->execute();
	$row_department = $query_department->fetch();
	$input = '';
	$input .= '<option value="">Select Department</option>';
	do {
		$id = $row_department['stid'];
		$sector = $row_department['sector'];
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

	$query_projMemebers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid");
	$query_projMemebers->execute(array(':projid' => $projid));
	$row_projMemebers = $query_projMemebers->fetch();
	$count_projMemebers = $query_projMemebers->rowCount();

	if ($count_projMemebers == 0) {
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
			$stage = 4;

			$insertQuery = $db->prepare("UPDATE `tbl_projects` set projstage=:stage WHERE projid=:projid");
			$insertQuery->execute(array(":stage" => $stage, ':projid' => $projid));

			$valid['success'] = true;
			$valid['messages'] = "Project Team Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the Project Team!!";
		}
	} else {
		$valid['success'] = true;
		$valid['messages'] = "Project Team Successfully Added";
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
		send_email($projid, $member[$i], $role[$i]);
	}
	if ($results === TRUE) {
		$query_projmapping = $db->prepare("SELECT projmapping FROM `tbl_projects` WHERE projid=:projid");
		$query_projmapping->execute(array(':projid' => $projid));
		$row_projmapping = $query_projmapping->fetch();
		$projmapping = $row_projmapping["projmapping"];

		$stage = 4;
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
		$stage = 3;
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
	$total_rsTeam = $query_rsTeam->rowCount();

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
	if ($total_rsTeam > 0) {
		do {
			$memberid = $row_rsTeam['ptid'];
			$roleid = $row_rsTeam['role'];

			$query_rsRole = $db->prepare("SELECT *  FROM tbl_project_team_roles WHERE id = :role");
			$query_rsRole->execute(array(":role" => $roleid));
			$row_rsRole = $query_rsRole->fetch();
			$role = $row_rsRole["role"];

			$query_rsMember = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:member ");
			$query_rsMember->execute(array(":member" => $memberid));
			$row_rsMember = $query_rsMember->fetch();
			$total_rsMember = $query_rsMember->rowCount();

			$member = "";
			if ($total_rsMember > 0) {
				$userid = $row_rsMember["userid"];
				$title = $row_rsMember["title"];
				$firstname = $row_rsMember["firstname"];
				$middlename = $row_rsMember["middlename"];
				$lastname = $row_rsMember["lastname"];
				$member = $title . "." . $firstname . " " . $middlename . " " . $lastname;
			}

			$rowno++;
			echo
			'<tr id="row">
				<td>' . $rowno . '</td>
				<td>
				' . $role . '
				</td>
				<td>
					' . $member . '
				</td>
			</tr>';
		} while ($row_rsTeam = $query_rsTeam->fetch());
	} else {
		echo '<tr> <td colspan="3">No Record found</td></tr>';
	}
	echo '</tbody>
					</table>
				</div>
			</div>
		</div>
	</fieldset> ';
}
