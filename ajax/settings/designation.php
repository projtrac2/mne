<?php
include '../controller.php';

if(isset($_GET['designation_permission'])){
    $designation_id = $_GET['designation_id'];
    $sql = $db->prepare("SELECT * FROM tbl_designation_permissions WHERE designation_id=:designation_id");
    $result = $sql->execute(array(":designation_id" => $designation_id));
    $row = $sql->fetchAll();
    
    echo json_encode(array("success" => $result, "designation_permissions"=>$row));
}

if (isset($_POST['store_designation'])) {
    $created_at = date('Y-m-d');
    $designation_id = $_POST['designation_id'];
    $permissions = $_POST['permission'];

    $sql = $db->prepare("DELETE FROM tbl_designation_permissions WHERE designation_id=:designation_id");
    $result = $sql->execute(array(':designation_id' => $designation_id));

    for ($d = 0; $d < count($permissions); $d++) {
        $permission_id = $permissions[$d];
        $sql = $db->prepare("INSERT INTO tbl_designation_permissions(designation_id,permission_id,created_by,created_at) VALUES(:designation_id,:permission_id,:created_by,:created_at)");
        $result  = $sql->execute(array(":designation_id" => $designation_id, ":permission_id" => $permission_id, ":created_by" => $user_name, ":created_at" => $created_at));
    }
    echo json_encode(array("success" => true));
} 
