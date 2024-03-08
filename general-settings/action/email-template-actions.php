<?php

include_once "controller.php";

try {
    //code...
    $content = $_POST['content'];
    $updateQuery = $db->prepare("UPDATE `tbl_email_templates` SET content=:content WHERE id=:itemid");
    $results = $updateQuery->execute([':itemid' => 6, ':content' => $content]);

    if ($results === TRUE) {
        $valid = true;
    } else {
        $valid = false;
    }

    echo json_encode($valid);
   
} catch (\Throwable $th) {
    echo json_encode($th->getMessage());
}
