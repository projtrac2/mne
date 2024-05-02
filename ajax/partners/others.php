
<?php
include '../controller.php';
try {
    if(isset($_POST['update_status'])){
        $partner_id = $_POST['partner_id'];
        $active = $_POST['status'];
        $sql = $db->prepare("UPDATE tbl_partners SET active=:active WHERE  id=:partner_id");
        $result  = $sql->execute(array(":active" => $active, ":partner_id" => $partner_id));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}