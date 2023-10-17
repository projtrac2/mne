<?php 
include '../controller.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
try { 

    if(isset($_GET['get_category'])) {
        $category_id = $_GET['category_id'];
        $sql = $db->prepare("SELECT * FROM tbl_standard_categories where category_id=:category_id");
        $sql->execute(array(":category_id"=>$category_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        $msg = ($rows_count > 0) ? true : false;
        echo json_encode(array('success'=>$msg, "standard"=>$row));
    }

    if(isset($_POST['store_category'])) {
        $store_category = $_POST['store_category']; 
        $category = $_POST['category'];
        $category_id = $_POST['category_id'];
        $status = $_POST['category_status'];
        $results = false;
        $today = date('Y-m-d');
        if($store_category == "new"){
            $sql = $db->prepare("INSERT INTO tbl_standard_categories (category,status,created_by,created_at) VALUES (:category,:status,:created_by,:created_at)");
            $results = $sql->execute(array(":category" => $category, ':status'=>$status, ':created_by' => $user_name, ":created_at" => $today));
        }else{
            $sql = $db->prepare("UPDATE tbl_standard_categories SET category=:category,status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE category_id=:category_id");
            $results = $sql->execute(array(":category" => $category,":status"=>$status, ":updated_by" => $user_name,":updated_at" => $today, ":category_id" => $category_id));
        }
        echo json_encode(array("success" => $results));
    }

    if(isset($_POST['update_category_status'])) {  
        $category_id = $_POST['category_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE tbl_standard_categories SET status=:status WHERE category_id=:category_id");
        $results = $sql->execute(array(":status" => $status, ":category_id" => $category_id));
        echo json_encode(array("success" => $results));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}