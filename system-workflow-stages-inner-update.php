<?php
// update status
include './includes/permission.php';

// $update_stage_status = $_POST['update_stage_status'];
// $stage_id = (int) $_POST['stage_id'];

$update_title_status =$_POST['update_title_status'];
$title_id = (int) $_POST['title_id'];


// if (isset($update_stage_status) && isset($stage_id)) {
//     $sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE id=:id");
//     $sql->bindParam(':id', $stage_id);
//     $sql->execute();
//     $row = $sql->fetch(PDO::FETCH_OBJ);

//     if ($row) {
//         //update
//         $active = $row->active;
//         $status = $row->active;
//         $active  == 1 ? $status = 0 : $status = 1;
//         $up = $db->prepare("UPDATE tbl_project_workflow_stage SET active=:active WHERE id=:id");
//         $up->bindParam(':active', $status);
//         $up->bindParam(':id', $stage_id);
//         if ($up->execute()) {
//             echo json_encode(true);
//         } else {
//             echo json_encode(false);
//         }
//     } else {
//         echo json_encode(false);
//     }
// }


if (isset($update_title_status) && isset($title_id)) {
    $sql = $db->prepare("SELECT * FROM `tbl_titles` WHERE id=:id");
    $sql->bindParam(':id', $title_id);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_OBJ);
    if ($row) {
        //update
        $active = $row->status;
        $status = $row->status;
        $active  == 1 ? $status = 0 : $status = 1;
        $up = $db->prepare("UPDATE tbl_titles SET `status`=:status WHERE id=:id");
        $up->bindParam(':status', $status);
        $up->bindParam(':id', $title_id);
        if ($up->execute()) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(false);
    }
}