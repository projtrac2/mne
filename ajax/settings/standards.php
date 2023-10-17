<?php 
include '../controller.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try { 

    if(isset($_GET['get_standard'])) {
		$stdid = $_GET['standard_id'];
		
        $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id  WHERE standard_id=:stdid ORDER BY `standard_id` ASC");
        $sql->execute(array(":stdid" => $stdid));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        $msg = ($rows_count > 0) ? true : false;
        echo json_encode(array('success'=>$msg, "standard"=>$row));
    }

    /* if(isset($_GET['get_standard'])) {
        $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id  ORDER BY `standard_id` ASC");
        $sql->execute();
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        $msg = ($rows_count > 0) ? true : false;
        echo json_encode(array('success'=>$msg, "standard"=>$row));
    } */

    if(isset($_GET['view_standard'])) {
		$stdid = $_GET['standard_id'];
        $sql = $db->prepare("SELECT description FROM tbl_standards WHERE standard_id=:stdid");
        $sql->execute(array(":stdid" => $stdid));
        $row = $sql->fetch();
		$description = $row["description"];
        echo '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label>
					<font color="#174082">Standard Description : </font>
				</label>
				<div>'.$description.'</div>
			</div>
		';
    }
                    // standard category title

    if(isset($_POST['store'])) {
        $store_standard = $_POST['store'];
        $standard_code = $_POST['code']; 
        $standard_name = $_POST['title'];
        $standard_type = $_POST['category'];
        $standard_description = $_POST['standard']; 
        $standard_id = $_POST['standard_id'];
        $status = $_POST['standard_status'];
        $results = false;
        $today = date('Y-m-d');
 
        if($store_standard == "new"){
            $sql = $db->prepare("INSERT INTO tbl_standards (category_id,code,standard,description,status,created_by,created_at) VALUES (:category_id,:code,:standard,:description,:status,:created_by,:created_at)");
            $results = $sql->execute(array(":category_id" => $standard_type,':code' => $standard_code, ':standard' => $standard_name, ":description" => $standard_description,":status"=>$status, ':created_by' => $user_name, ":created_at" => $today));
        }else{ 
            $sql = $db->prepare("UPDATE tbl_standards SET category_id=:category_id, code=:code, standard=:standard, description=:description, status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE standard_id=:standard_id");
            $results = $sql->execute(array(":category_id" => $standard_type, ':code' => $standard_code, ":standard" => $standard_name, ":description" => $standard_description,":status"=>$status, ":updated_by" => $user_name,"updated_at" => $today, ":standard_id" => $standard_id));
        }

        echo json_encode(array("success" => $results));
    }

    if(isset($_POST['update_standard_status'])) {  
        $standard_id = $_POST['standard_id'];
        $status = $_POST['status'];
        $sql = $db->prepare("UPDATE tbl_standards SET status=:status WHERE standard_id=:standard_id");
        $results = $sql->execute(array(":status" => $status, ":standard_id" => $standard_id));
        echo json_encode(array("success" => $results));
    }

} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
