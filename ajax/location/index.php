<?php
try {
    include '../controller.php';
    if (isset($_GET['get_location_details'])) {
        $state_id = $_GET['state_id'];
        $query_rsComm =  $db->prepare("SELECT * FROM tbl_state WHERE id=:state_id");
        $query_rsComm->execute(array(":state_id" => $state_id));
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();
        echo json_encode(array("success" => $totalRows_rsComm > 0 ? true : false, "state" => $row_rsComm));
    }

    if (isset($_POST['deleteItem'])) {
        $status_id = $_POST['status_id'];
        $state_id = $_POST['state_id'];
        $sql = $db->prepare("UPDATE tbl_state SET active=:status WHERE id=:state_id");
        $success = $sql->execute(array(":status" => $status_id, ":state_id" => $state_id));
        echo json_encode(array("success" => $success));
    }

    if (isset($_POST['store_location'])) {
        $state_id = $_POST['id'];
        $store_location = $_POST['store_location'];
        $parent = $_POST['parent'] != 0 ? $_POST['parent'] : Null;
        $state = $_POST['location'];
        $success = false;
        if ($store_location == "new") {
            $message = "Successfully created record";
            $sql = $db->prepare("INSERT INTO tbl_state (parent,state) VALUES(:parent,:state)");
            $success = $sql->execute(array(":parent" => $parent, ":state" => $state));
        } else {
            $message = "Successfully updated record";
            $sql = $db->prepare("UPDATE tbl_state SET parent=:parent,state=:state WHERE id=:state_id");
            $success = $sql->execute(array(":parent" => $parent, ":state" => $state, ":state_id" => $state_id));
        }
        echo json_encode(array("success" => $success, "message" => $message));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
