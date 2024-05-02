<?php
include '../controller.php';
try {
    if (isset($_POST['store'])) {
        $record_type = $_POST['record_type'];
        $store = $_POST['store'];
        $remarks = $_POST['remarks'];
        $year = $_POST['year'];
        $quarter = $_POST['quarter'];
        $indicator_id = $_POST['indicator'];
        $id = $_POST['remarks_id'];
        $today = date('Y-m-d');
        $result = "False";
        if ($record_type == 1) {
            if ($store == "edit") {
                $sql = $db->prepare("UPDATE tbl_qapr_report_remarks SET remarks=:remarks,updated_by=:updated_by,updated_at=:updated_at WHERE  id=:id");
                $result = $sql->execute(array(":remarks" => $remarks, ":updated_by" => $user_name, ":updated_at" => $today, ":id" => $id));
            } else {
                $sql = $db->prepare("INSERT INTO tbl_qapr_report_remarks (indid,year,quarter,remarks,created_by,created_at) VALUES (:indid,:year,:quarter,:remarks,:created_by,:created_at)");
                $result = $sql->execute(array(":indid" => $indicator_id, ":year" => $year, ":quarter" => $quarter, ":remarks" => $remarks, ":created_by" => $user_name, ":created_at" => $today));
            }
        } else {
            if ($store == "edit") {
                $sql = $db->prepare("UPDATE tbl_capr_report_remarks SET remarks=:remarks,updated_by=:updated_by,updated_at=:updated_at WHERE id=:id");
                $result  = $sql->execute(array(":remarks" => $remarks, ":updated_by" => $user_name, ":updated_at" => $today, ":id" => $id));
            } else {
                $sql = $db->prepare("INSERT INTO tbl_capr_report_remarks (indid,year,remarks,created_by,created_at) VALUES (:indid,:year,:remarks,:created_by,:created_at)");
                $sql->execute(array(":indid" => $indicator_id, ":year" => $year, ":remarks" => $remarks, ":created_by" => $user_name, ":created_at" => $today));
            }
        }
        echo json_encode(array("success" => true, "message" => "Successfully created"));
    }

    if (isset($_GET['get_edit_details'])) {
        $remarks = '';
        $remarks_id = $_GET['remarks_id'];
        $record_type = $_GET['record_type'];
        if ($record_type == 1) {
            $query_indRemarks =  $db->prepare("SELECT * FROM tbl_qapr_report_remarks WHERE id=:id");
            $query_indRemarks->execute(array(":id" => $remarks_id));
            $row_indRemarks = $query_indRemarks->fetch();
            $count_row_indRemarks = $query_indRemarks->rowCount();
            $remarks = $count_row_indRemarks > 0 ? $row_indRemarks['remarks'] : '';
        } else {
            $query_indRemarks =  $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE id=:id ");
            $query_indRemarks->execute(array(":id" => $remarks_id));
            $row_indRemarks = $query_indRemarks->fetch();
            $count_row_indRemarks = $query_indRemarks->rowCount();
            $remarks = $count_row_indRemarks > 0 ? $row_indRemarks['remarks'] : '';
        }
        echo json_encode(array("success" => true, "remarks" => $remarks));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
