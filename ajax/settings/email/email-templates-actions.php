<?php
try {
    include_once '../controller.php';
    if (isset($_POST['store'])) {
        $content = $_POST['content'];
        $updateQuery = $db->prepare("UPDATE `tbl_email_templates` SET content=:content WHERE id=:itemid");
        $results = $updateQuery->execute([':itemid' => 6, ':content' => $content]);
        echo json_encode(array("success" => $results));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
