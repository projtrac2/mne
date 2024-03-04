<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'Models/Connection.php';
require 'Models/Email.php';

function get_stage_details($workflow_stage)
{
	global $db;
	$sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority = :priority ");
	$sql->execute(array(":priority" => $workflow_stage));
	$row = $sql->fetch();
	$rows = $sql->rowCount();
	return $rows > 0 ? $row['stage'] : false;
}


function send_email($user_id, $projid, $notification_type_id, $title, $stage_id, $substage_id)
{
	$encrypted_id = base64_encode("projid54321{$projid}");
	$mail = new Email();
	$notification = $mail->get_notification($stage_id);
	$notification_id = $notification->id;
	$notification_group_id = $notification->notification_group_id;
	$approval = $notification->approval;
	$data_entry = $notification->data_entry;
	$page_url = $notification->page_url . $encrypted_id;
	$due_date = '';
	$today = date('Y-m-d');
	if ($notification_type_id == 1) {
		$substage_id = $substage_id - 1;
		if ($substage_id == 1) {
			$due_date =    date('Y-m-d', strtotime($today . ' + ' . $data_entry . ' days'));
		} else if ($substage_id == 3) {
			$due_date =    date('Y-m-d', strtotime($today . ' + ' . $approval . ' days'));
		}
	}

	// $project = get_project_details($projid);
	$mail_details = array(
		"notification_group_id" => $notification_group_id,
		"notification_type_id" => $notification_type_id,
		"notification_id" => $notification_id,
		"recipient_id" => $user_id,
		"item_id" => $projid,
		"page_url" => $page_url,
	);

	$content_details = array(
		"responsible_id" => '',
		"title" => $title,
		"action" => $title,
		"stage" => get_stage_details($stage_id),
		"project_name" => $project['projname'],
		"due_date" => $due_date,
	);

	return $mail->notification_template($mail_details, $content_details);
}

function get_escalate_to($project_id, $workflow_stage, $sub_stage, $section_id, $directorate_id)
{
	global $db;

	if ($workflow_stage == 9) {
	} else {
		$query_rsUser = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE directorate = :directorate ORDER BY ptid ASC");
		$query_rsUser->execute(array(":directorate" => $directorate_id));
		if ($sub_stage == 0 || $sub_stage == 2) {
			$query_rsUser = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE department_id = :section_id ORDER BY ptid ASC");
			$query_rsUser->execute(array(":section_id" => $section_id));
		}

		$row_rsUser = $query_rsUser->fetch();
		$count_rsUser = $query_rsUser->rowCount();

		if ($count_rsUser > 0) {
			$responsible = $row_rsUser['userid'];
			$activity = 0;
			$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
			$query_rsOutput_standin->execute(array(":projid" => $project_id, ":user_name" => $responsible, ":activity" => $activity));
			$row_rsOutput_standin = $query_rsOutput_standin->fetch();
			$total_rsOutput_standin = $query_rsOutput_standin->rowCount();

			if ($total_rsOutput_standin > 0) {
				$owner_id = $row_rsOutput_standin['owner'];
				$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
				$query_rsOutput->execute(array(":projid" => $project_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
				$rows_rsOutput = $query_rsOutput->fetch();
				$total_rsOutput = $query_rsOutput->rowCount();
				$standin_responsible = $total_rsOutput > 0 ? $rows_rsOutput['responsible'] : '';
			}
		}
	}
	return array("responsible" => $responsible, "stand_in" => $standin_responsible);
}

function get_reminder_to($project_id, $workflow_stage, $sub_stage)
{
	global $db;
	$activity = 1;
	$standin_responsible = $responsible = '';
	if ($workflow_stage == 9) {
		$team_type = 1;
		// issues // risk
		$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type");
		$query_rsOutput->execute(array(":projid" => $project_id, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type));
		$rows_rsOutput = $query_rsOutput->fetch();
		$total_rsOutput = $query_rsOutput->rowCount();
		$responsible = $total_rsOutput > 0 ? $rows_rsOutput['responsible'] : false;

		if ($responsible) {
			$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
			$query_rsOutput_standin->execute(array(":projid" => $project_id, ":user_name" => $responsible, ":activity" => $activity));
			$row_rsOutput_standin = $query_rsOutput_standin->fetch();
			$total_rsOutput_standin = $query_rsOutput_standin->rowCount();

			if ($total_rsOutput_standin > 0) {
				$owner_id = $row_rsOutput_standin['owner'];
				$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
				$query_rsOutput->execute(array(":projid" => $project_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
				$rows_rsOutput = $query_rsOutput->fetch();
				$total_rsOutput = $query_rsOutput->rowCount();
				$standin_responsible = $total_rsOutput > 0 ? $rows_rsOutput['responsible'] : '';
			}
		}
	} else {

		$responsible = '';
		if ($sub_stage == 0 || $sub_stage == 2) {
			$query_rsUser = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE directorate = :directorate ORDER BY ptid ASC");
			$query_rsUser->execute(array(":directorate" => $directorate_id));
			$row_rsUser = $query_rsUser->fetch();
			$count_rsUser = $query_rsUser->rowCount();
			$responsible = $row_rsUser['userid'];
		} else {
			$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage");
			$query_rsOutput->execute(array(":projid" => $project_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage));
			$rows_rsOutput = $query_rsOutput->fetch();
			$total_rsOutput = $query_rsOutput->rowCount();
			$responsible = $total_rsOutput > 0 ? $rows_rsOutput['responsible'] : false;
		}
		$standin_responsible = '';

		if ($responsible) {
			$query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND activity =:activity");
			$query_rsOutput_standin->execute(array(":projid" => $project_id, ":user_name" => $responsible, ":activity" => $activity));
			$row_rsOutput_standin = $query_rsOutput_standin->fetch();
			$total_rsOutput_standin = $query_rsOutput_standin->rowCount();

			if ($total_rsOutput_standin > 0) {
				$owner_id = $row_rsOutput_standin['owner'];
				$query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND sub_stage =:sub_stage AND responsible=:responsible");
				$query_rsOutput->execute(array(":projid" => $project_id, ":workflow_stage" => $workflow_stage, ":sub_stage" => $sub_stage, ":responsible" => $owner_id));
				$rows_rsOutput = $query_rsOutput->fetch();
				$total_rsOutput = $query_rsOutput->rowCount();
				$standin_responsible = $total_rsOutput > 0 ? $rows_rsOutput['responsible'] : '';
			}
		}
	}
	return array("responsible" => $responsible, "stand_in" => $standin_responsible);
}

function calculate_duration($date_one, $date_two)
{
	$date1 = new DateTime($date_one);
	$date2 = new DateTime($date_two);
	$interval = $date1->diff($date2);
	return $interval->days;
}

function index()
{
	global $db;
	$query_rsProjects = $db->prepare("SELECT * FROM `tbl_projects` p left join `tbl_programs` g on g.progid=p.progid WHERE WHERE p.projstage > 0 AND p.projstatus <> 5");
	$query_rsProjects->execute();
	$totalRows_rsProjects = $query_rsProjects->rowCount();
	if ($totalRows_rsProjects > 0) {
		while ($row_rsProjects = $query_rsProjects->fetch()) {
			$project_stage = $row_rsProjects['projstage'];
			$project_sub_stage = $row_rsProjects['proj_substage'];
			$project_id = $row_rsProjects['projid'];
			$sector_id = $row_rsProjects['projdept'];
			$directorate_id = $row_rsProjects['directorate'];
			$project_name = $row_rsProjects['proj'];

			$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE stage_id=:stage_id ");
			$query_rsNotification->execute(array(":stage_id" => $project_stage));
			$totalRows_rsNotification = $query_rsNotification->rowCount();
			$Rows_rsNotification = $query_rsNotification->fetch();
			if ($totalRows_rsNotification > 0) {
				$notification_id = $Rows_rsNotification['id'];
				$notification_group_id = $Rows_rsNotification['notification_group_id'];
				$encrypted_id = base64_encode("projid54321{$project_id}");
				$page_url = $Rows_rsNotification['page_url'] . $encrypted_id;

				$query_rsNotifications = $db->prepare("SELECT t.* FROM tbl_notification_timelines t INNER JOIN tbl_notification_types s ON s.id = t.notification_type_id WHERE s.category=1 AND notification_id=:notification_id ");
				$query_rsNotifications->execute(array(":notification_id" => $notification_id));
				$totalRows_rsNotifications = $query_rsNotifications->rowCount();

				if ($totalRows_rsNotifications > 0) {
					while ($row_rsNotification = $query_rsNotifications->fetch()) {
						$notification_type_id = $row_rsNotification['notification_type_id'];
						$timeline = $row_rsNotification['timeline'];
						$action_date = date('Y-m-d');
						$today = date('Y-m-d');
						if ($notification_type_id == 3) {
							if ($action_date < $today) { // escalate to authority
								$duration = calculate_duration($action_date, $today);
								if ($duration == $timeline) {
									$users = get_escalate_to($project_id, $project_stage, $project_sub_stage, $sector_id, $directorate_id);
									$stand_in = $users['stand_in'];
									$user_id = $users['responsible'];
									send_email($user_id, $project_id, $notification_type_id, "Reminder", $project_stage, $project_sub_stage, $page_url);
									if ($stand_in != '') {
										send_email($stand_in, $project_id, $notification_type_id, "Reminder", $project_stage, $project_sub_stage, $page_url);
									}
								}
							}
						} else if ($notification_type_id == 4) { // reminder
							$send_email = false;
							if ($action_date > $today) {
								$duration = calculate_duration($action_date, $today);
								if ($duration == $timeline) {
									$send_email = true;
								}
							}

							if ($today == $action_date || $send_email) {
								$users = get_reminder_to($project_id, $project_stage, $project_sub_stage, $directorate_id);
								$stand_in = $users['stand_in'];
								$user_id = $users['responsible'];
								send_email($user_id, $project_id, $notification_type_id, "Reminder", $project_stage, $project_sub_stage, $page_url); // responsible for the task!A
								if ($stand_in != '') {
									send_email($stand_in, $project_id, $notification_type_id, "Reminder", $project_stage, $project_sub_stage, $page_url); //stand in responsible
								}
							}
						}
					}
				}
			}
		}
	}
}
