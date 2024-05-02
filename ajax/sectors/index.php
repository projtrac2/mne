<?php
try {
    include '../controller.php';

    if (isset($_POST["newitem"])) {
        $results = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $parent = $_POST['parent'];
            $sector = $_POST['sector'];
            $sql = $db->prepare("INSERT INTO `tbl_sectors` (parent,sector) VALUES(:parent, :sector)");
            $results = $sql->execute(array(":parent" => $parent, ":sector" => $sector));
        }
        echo json_encode($results);
    }

    if (isset($_POST["edititem"])) {
        $results = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $sector = $_POST['sector'];
            $parent = $_POST['parent'];
            $role_group = $_POST['role_group'];
            $stid = $_POST['stid'];
            $updateQuery = $db->prepare("UPDATE `tbl_sectors` SET sector=:sector WHERE stid=:stid");
            $results = $updateQuery->execute(array(":sector" => $sector, ":stid" => $stid));
        }
        echo json_encode($results);
    }

    if (isset($_POST["deleteItem"])) {
        $results = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $stid = $_POST['stid'];
            $status = $_POST['status'];
            $deleteQuery = $db->prepare("UPDATE tbl_sectors SET deleted=:deleted WHERE stid=:stid");
            $results = $deleteQuery->execute(array(':deleted' => $status, ':stid' => $stid));
        }
        echo json_encode($results);
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
