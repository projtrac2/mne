

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

                $marker = [];
                if ($mapping_type == 1 ) {
                    $sql = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                    $sql->execute(array(":output_id" => $output_id));
                    $total_sites = $sql->rowCount();
                    if ($total_sites > 0) {
                        while ($row_rsMaps = $sql->fetch()) {
                            $site_name = $row_rsMaps['site'];
                            $site_id = $row_rsMaps['site_id'];
                            $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND m.site_id=:site_id");
                            $query->execute(array(":output_id" => $output_id, ":projid" => $projid, ":site_id" => $site_id));
                            $row = $query->fetchAll();
                            $rows = $query->rowCount();
                            $marker[] = array("site" => $site_name, "markers" => $row);
                        }
                    }
                } else {
                    $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid");
                    $query->execute(array(":output_id" => $output_id, ":projid" => $projid));
                    $row = $query->fetchAll();
                    $rows = $query->rowCount();
                    $marker[] = array("site" => "", "markers" => $row);
                }

                $markers[] = array("indicator_details" => $row_rsOutput, "markers" => $marker, "locations" => get_wards($output_id));
            }
        }


        $query = $db->prepare("SELECT * FROM tbl_markers WHERE projid=:projid");
        $query->execute(array(":projid" => $projid));
        $project_rows = $query->rowCount();
        echo json_encode(array("success" => $project_rows > 0 ? true : false, "markers" => $markers));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
