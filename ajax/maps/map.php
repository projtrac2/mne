<?php
try {
    include '../controller.php';

    if (isset($_GET['get_indicator_markers'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $site_id = $_GET['site_id'];
        $mapping_type = $_GET['mapping_type'];
        $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid");
        $query->execute(array(":output_id" => $output_id, ":projid" => $projid));
        if ($mapping_type == 1) {
            $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND site_id=:site_id");
            $query->execute(array(":output_id" => $output_id, ":projid" => $projid, ":site_id" => $site_id));
        }

        $row = $query->fetchAll();
        $rows = $query->rowCount();
        echo json_encode(array("success" => $rows > 0 ? true : false, "markers" => $row));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
