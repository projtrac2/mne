<?php
include '../controller.php';

if (isset($_GET['permission'])) {
    $id = $_GET['id'];
    $sql = $db->prepare("SELECT * FROM tbl_permissions WHERE id=:id");
    $result = $sql->execute(array(":id" => $id));
    $row = $sql->fetch();
    echo json_encode(array("success" => $result, "permission" => $row));
}

if (isset($_POST['store'])) {
    $name = $_POST['name'];
    $phrase  = $_POST['phrase'];
    $id = $_POST['id'];
    $created_at = date('Y-m-d');
    if ($_POST['store'] == 'edit') {
        $sql = $db->prepare("UPDATE tbl_permissions SET name=:name,phrase=:phrase, updated_by=:updated_by,updated_at=:updated_at  WHERE id =:id");
        $result  = $sql->execute(array(":name" => $name, ":phrase" => $phrase, ":updated_by" => $user_name, ":updated_at" => $created_at, ":id" => $id));
    } else {
        $status = 1;
        $sql = $db->prepare("INSERT INTO tbl_permissions (name,phrase,status,created_by,created_at) VALUES(:name,:phrase,:status,:created_by,:created_at)");
        $result  = $sql->execute(array(":name" => $name, ":phrase" => $phrase, ":status" => $status, ":created_by" => $user_name, ":created_at" => $created_at));
    }
    echo json_encode(array("success" => $result));
}

if (isset($_POST['store_designation'])) {
    $created_at = date('Y-m-d');
    $designations = $_POST['designation_id'];
    $permission_id = $_POST['permission_id'];

    $sql = $db->prepare("DELETE FROM tbl_designation_permissions WHERE permission_id=:permission_id");
    $result = $sql->execute(array(':permission_id' => $permission_id));

    for ($d = 0; $d < count($designations); $d++) {
        $designation_id = $designations[$d];
        $sql = $db->prepare("INSERT INTO tbl_designation_permissions(designation_id,permission_id,created_by,created_at) VALUES(:designation_id,:permission_id,:created_by,:created_at)");
        $result  = $sql->execute(array(":designation_id" => $designation_id, ":permission_id" => $permission_id, ":created_by" => $user_name, ":created_at" => $created_at));
    }
    echo json_encode(array("success" => true));
}

if (isset($_POST['destroy'])) {
    $id = $_POST['id'];
    $updateStatus = '';

    $stmt = $db->prepare("SELECT * FROM `tbl_permissions` where id=:id");
    $stmt->execute([':id' => $id]);
    $stmt_results = $stmt->fetch();

    $stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

    $sql = $db->prepare("UPDATE tbl_permissions SET status=:status WHERE id=:id");
    $result = $sql->execute(array(':id' => $id, ':status' => $updateStatus));

    if ($result) {
        $valid = true;
    } else {
        $valid = false;
    }

    echo json_encode($valid);
}
