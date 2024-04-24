<?php


function send_notification($notification_timeline_id, $project_id)
{
	global $db;
	$query_rsNotification_Status = $db->prepare("SELECT * FROM tbl_notification_status WHERE notification_timeline_id=:notification_timeline_id AND item_id=:item_id ");
	$query_rsNotification_Status->execute(array(":notification_timeline_id" => $notification_timeline_id, ":item_id" => $project_id));
	$totalRows_rsNotification_Status = $query_rsNotification_Status->rowCount();
	if ($totalRows_rsNotification_Status == 0) {
		// send escalation notification

		
	}
}

$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projstage >= 1 AND projstage <= 8");
$query_rsProjects->execute();
$totalRows_rsProjects = $query_rsProjects->rowCount();

if ($totalRows_rsProjects > 0) {
	while ($row_rsProgjects = $query_rsProjects->fetch()) {
		$project_stage = $row_rsProjects['projstage'];
		$project_sub_stage = $row_rsProjects['proj_substage'];
		$project_id = $row_rsProjects['projid'];

		$query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications WHERE stage_id=:stage_id ");
		$query_rsNotification->execute(array(":stage_id" => $stage_id));
		$totalRows_rsNotification = $query_rsNotification->rowCount();
		$Rows_rsNotification = $query_rsNotification->fetch();
		if ($totalRows_rsNotification > 0) {
			$notification_id = $Rows_rsNotification['id'];
			$query_rsNotifications = $db->prepare("SELECT t.*, n.type, n.category FROM tbl_notification_timelines t INNER JOIN tbl_notification_types n ON n.id= t.notification_type_id  WHERE notification_id=:notification_id WHERE t.status=1 AND n.status=1 AND n.category=1");
			$query_rsNotifications->execute(array(":notification_id" => $notification_id));
			$totalRows_rsNotifications = $query_rsNotifications->rowCount();

			if ($totalRows_rsNotifications > 0) {
				while ($row_rsNotification = $query_rsNotifications->fetch()) {
					$notification_timeline_id = $row_rsNotification['id'];
					$timeline = $row_rsNotification['timeline'];
					if ($totalRows_rsNotification_Status > 0) {
						if ($notification_timeline_id == 3) {
							if ($today > $action_date) {
								// escalate to authority
								send_notification($notification_timeline_id, $project_id);
							}
						} else if ($notification_timeline_id == 4) { // reminder


							if ($today == $action_date) {
								// send a reminder
								send_notification($notification_timeline_id, $project_id);
							}

							if ($action_date - $today == $timeline) {
								// send a reminder
								send_notification($notification_timeline_id, $project_id);
							}
						}
					}
				}
			}
		}
	}
}
