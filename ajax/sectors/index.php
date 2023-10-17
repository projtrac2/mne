<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
try {
    if (isset($_POST["newitem"])) {
        $parent = $_POST['parent'];
        $sector = $_POST['sector'];
        $role_group = $_POST['role_group'];

        $sql = $db->prepare("INSERT INTO `tbl_sectors` (parent,sector,role_id) VALUES(:parent, :sector, :role_group)");
        $results = $sql->execute(array(":parent" => $parent, ":sector" => $sector, ":role_group" => $role_group));
        echo json_encode($results);
    }

    if (isset($_POST["edititem"])) {
        $sector = $_POST['sector'];
        $parent = $_POST['parent'];
        $role_group = $_POST['role_group'];
        $stid = $_POST['stid'];
        $updateQuery = $db->prepare("UPDATE `tbl_sectors` SET parent=:parent, sector=:sector,  role_id=:role_group WHERE stid=:stid");
        $results = $updateQuery->execute(array(":parent" => $parent, ":sector" => $sector, ":role_group" => $role_group, ":stid" => $stid));
        echo json_encode($results);
    }

    if (isset($_POST["deleteItem"])) {
        $stid = $_POST['stid'];
        $status = $_POST['status'];
        $deleteQuery = $db->prepare("UPDATE tbl_sectors SET deleted=:deleted WHERE stid=:stid");
        $results = $deleteQuery->execute(array(':deleted' => $status, ':stid' => $stid));
        echo json_encode($results);
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
