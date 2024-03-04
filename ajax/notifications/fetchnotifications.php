<?php
include '../controller.php';
try {
    if (isset($_GET['get_alerts'])) {
        $view = $_GET['view'];
        if ($view != '') {
            $sql = $db->prepare("UPDATE `tbl_notification_status` SET seen=1 AND read_at=:read_at WHERE user_id=:user_id AND notification_type_id=3");
            $result = $sql->execute(array(':user_id' => $user_name));
        } else {
            $query_rsAlerts = $db->prepare("SELECT * FROM tbl_notification_status WHERE user_id=:user_id AND  seen=0 AND notification_type_id=3 ");
            $query_rsAlerts->execute(array(":user_id" => $user_name));
            $totalRows_rsAlerts = $query_rsAlerts->rowCount();
            $alerts = '';
            if ($totalRows_rsAlerts > 0) {
                while ($row_rsAlerts = $query_rsAlerts->fetch()) {
                    $title = $row_rsAlerts['title'];
                    $id = $row_rsAlerts['id'];
                    $alerts .= '
                    <li class="body">
                        <a href="javascript:void(0);" onclick="load_notification_alert(' . $id . ')">' . $title . '</a>
                    </li>';
                }
            }
        }
        echo json_encode(array("success" => true, "total_alerts" => $totalRows_rsAlerts, "alerts" => $alerts));
    }

    if (isset($_GET['get_alert'])) {
        $alert_id = $_GET['alert_id'];
        $query_rsAlerts = $db->prepare("SELECT * FROM tbl_notification_status WHERE user_id=:user_id  AND id=:alert_id ");
        $query_rsAlerts->execute(array(":user_id" => $user_name, ":alert_id" => $alert_id));
        $row_rsAlerts = $query_rsAlerts->fetch();
        $totalRows_rsAlerts = $query_rsAlerts->rowCount();
        $success = $totalRows_rsAlerts > 0 ?  true : false;
        return json_encode(array("success" => $success, "alert" => $row_rsAlerts));
    }


    if (isset($_GET['get_notifications'])) {
        $read = $_GET['read'];
        if ($read != '') {
            $sql = $db->prepare("UPDATE `tbl_notification_status` SET seen=1 AND read_at=:read_at WHERE user_id=:user_id");
            $result = $sql->execute(array(':user_id' => $user_name));
        } else {
            $query_rsNotifications = $db->prepare("SELECT * FROM tbl_notification_status WHERE user_id=:user_id AND  seen=0 AND notification_type_id<>3 ");
            $query_rsNotifications->execute(array(":user_id" => $user_name));
            $totalRows_rsNotifications = $query_rsNotifications->rowCount();
            $notifications = '';
            if ($totalRows_rsNotifications > 0) {
                while ($row_rsNotifications = $query_rsNotifications->fetch()) {
                    $notifications .= '
                    <li class="body">
                        <a href="javascript:void(0);">Testing Notifications</a>
                    </li>';
                }
            }
        }

        echo json_encode(array("success" => true, "total_notifications" => $totalRows_rsNotifications, "notifications" => $notifications));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
