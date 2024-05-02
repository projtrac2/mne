<?php
include '../controller.php';
try {
    if(isset($_POST['update_status'])){
        $financier_id = $_POST['financier_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE tbl_financiers SET active=:status WHERE  id=:financier_id");
        $result  = $sql->execute(array(":status" => $status, ":financier_id" => $financier_id));

        echo json_encode(array("success"=>true));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}