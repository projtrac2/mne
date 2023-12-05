

<?php
include '../controller.php';
try {
    function get_wards($output_id)
    {
        global $db;
        $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
        $query_rsState->execute(array(":output_id" => $output_id));
        $total_rsState = $query_rsState->rowCount();
        $state = [];
        if ($total_rsState > 0) {
            while ($row_rsState = $query_rsState->fetch()) {
                $state[] = $row_rsState['state'];
            }
        }
        return implode(",", $state);
    }


    if (isset($_GET['get_project_markers'])) {
        $projid = $_GET['projid'];
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();

        $markers = [];
        if ($total_Output > 0) {
            while ($row_rsOutput = $query_Output->fetch()) {
                $output_id = $row_rsOutput['id'];
                $mapping_type = $row_rsOutput['indicator_mapping_type'];
                $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid");
                $query->execute(array(":output_id" => $output_id, ":projid" => $projid));

                if ($mapping_type == 1 || $mapping_type == 3) {
                    $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid INNER JOIN tbl_project_sites s ON m.site_id=s.site_id  INNER JOIN tbl_state w ON s.state_id=w.id  WHERE d.id = :output_id AND d.projid=:projid");
                    $query->execute(array(":output_id" => $output_id, ":projid" => $projid));
                }
                $row = $query->fetchAll();
                $rows = $query->rowCount();

                $markers[] = array("indicator_details" => $row_rsOutput, "markers" => $row, "locations" => get_wards($output_id));
            }
        }

        echo json_encode(array("success" => $rows > 0 ? true : false, "markers" => $markers));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
