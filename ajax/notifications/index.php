<?php
include '../controller.php';
try {
    // notification types
    if (isset($_POST['update_notification_type_status'])) {
        $notification_type_id = $_POST['notification_type_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE `tbl_notification_types` SET status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:notification_type_id");
        $result = $sql->execute(array(':status' => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":notification_type_id" => $notification_type_id));
        echo json_encode(array("success" => $result));
    }

    // notification groups
    if (isset($_POST['update_notification_group_status'])) {
        $notification_group_id = $_POST['notification_group_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE `tbl_notification_groups` SET status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:notification_group_id");
        $result = $sql->execute(array(':status' => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":notification_group_id" => $notification_group_id));
        echo json_encode(array("success" => $result));
    }

    // Notifications
    if (isset($_POST['update_notification_status'])) {
        $notification_id = $_POST['notification_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE `tbl_notifications` SET status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:notification_id");
        $result = $sql->execute(array(':status' => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":notification_id" => $notification_id));
        echo json_encode(array("success" => $result));
    }

    if (isset($_POST['store_notification'])) {
        $notification_id = $_POST['notification_id'];
        $data_entry = $_POST['data_entry'];
        $approval = $_POST['approval'];
        $page_url = $_POST['page_url'];
        $sql = $db->prepare("UPDATE `tbl_notifications` SET data_entry=:data_entry,approval=:approval,page_url=:page_url, updated_by=:updated_by, updated_at=:updated_at WHERE id=:notification_id");
        $result = $sql->execute(array(':data_entry' => $data_entry, ':approval' => $approval, ":page_url" => $page_url, ":updated_by" => $user_name, ":updated_at" => $today, ":notification_id" => $notification_id));
        echo json_encode(array("success" => $result));
    }

    // Notification Timelines
    if (isset($_GET['get_notification_timeline_details'])) {
        $timeline_id = $_GET['timeline_id'];
        $query_rsNotifications = $db->prepare("SELECT * FROM tbl_notification_timelines WHERE id=:timeline_id");
        $query_rsNotifications->execute(array(":timeline_id" => $timeline_id));
        $row_rsNotifications = $query_rsNotifications->fetch();
        $totalRows_rsNotifications = $query_rsNotifications->rowCount();
        echo json_encode(array("success" => $totalRows_rsNotifications > 0 ? true : false, "timeline" => $row_rsNotifications));
    }

    if (isset($_POST['store_notifications_timelines'])) {
        $notification_id = $_POST['notification_id'];
        $timeline = $_POST['timeline'];
        $store_notifications_timelines = $_POST['store_notifications_timelines'];
        $timeline_id = $_POST['timeline_id'];

        $sql = $db->prepare("UPDATE `tbl_notification_timelines` SET timeline=:timeline, updated_by=:updated_by, updated_at=:updated_at WHERE id=:timeline_id");
        $result = $sql->execute(array(":timeline" => $timeline, ":updated_by" => $user_name, ":updated_at" => $today, ":timeline_id" => $timeline_id));
        echo json_encode(array("success" => $result));
    }

    if (isset($_POST['update_notification_timeline_status'])) {
        $timeline_id = $_POST['timeline_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE `tbl_notification_timelines` SET status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:timeline_id");
        $result = $sql->execute(array(':status' => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":timeline_id" => $timeline_id));
        echo json_encode(array("success" => $result));
    }

    // Notification templates
    if (isset($_GET['get_notification_template_details'])) {
        $template_id = $_GET['template_id'];
        $query_rsNotification_template = $db->prepare("SELECT * FROM tbl_notification_templates WHERE id=:template_id");
        $query_rsNotification_template->execute(array(":template_id" => $template_id));
        $row_rsNotification_template = $query_rsNotification_template->fetch();
        $totalRows_rsNotification_template = $query_rsNotification_template->rowCount();
        echo json_encode(array("success" => $totalRows_rsNotification_template > 0 ? true : false, "template" => $row_rsNotification_template));
    }

    if (isset($_POST['store_notification_template'])) {
        $template_id = $_POST['template_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = $db->prepare("UPDATE `tbl_notification_templates` SET title=:title,content=:content, updated_by=:updated_by, updated_at=:updated_at WHERE id=:template_id");
        $result = $sql->execute(array(':title' => $title, ":content" => $content, ":updated_by" => $user_name, ":updated_at" => $today, ":template_id" => $template_id));
        echo json_encode(array("success" => $result));
    }

    if (isset($_POST['update_notification_template_status'])) {
        $template_id = $_POST['template_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE `tbl_notification_templates` SET status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:template_id");
        $result = $sql->execute(array(':status' => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":template_id" => $template_id));
        echo json_encode(array("success" => $result));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
