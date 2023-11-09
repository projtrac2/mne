

<?php
include '../controller.php';
try {
    if (isset($_GET['get_project_markers'])) {
        $projid = $_GET['projid'];
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();

        $markers =[];
        if ($total_Output > 0) {
            while($row_rsOutput = $query_Output->fetch()){
                $output_id = $row_rsOutput['id'];
                $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid");
                $query->execute(array(":output_id" => $output_id, ":projid" => $projid));
                $row = $query->fetchAll();
                $rows = $query->rowCount();
                $markers[] = array("indicator_details"=>$row_rsOutput, "markers"=>$row);
            }
        }

        echo json_encode(array("success" => $rows > 0 ? true : false, "markers" => $markers));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
