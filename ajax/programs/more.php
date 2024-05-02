<?php
try {
    include '../controller.php';
    if (isset($_POST["deleteItem"])) {
        $progid = $_POST['progid'];
        $stmt = $db->prepare("DELETE FROM `tbl_programs` WHERE progid=:progid");
        $results = $stmt->execute(array(':progid' => $progid));

        $sql = $db->prepare("DELETE FROM `tbl_progdetails` WHERE progid=:progid");
        $results = $sql->execute(array(':progid' => $progid));
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
        echo json_encode($valid);
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
