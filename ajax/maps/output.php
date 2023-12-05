

<?php
include '../controller.php';
try {

    function get_start_date($from)
    {
        global $db;
        $query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id = :fyid");
        $query_fy->execute(array(":fyid" => $from));
        $row = $query_fy->fetch();
        return date('Y-m-d', strtotime($row['yr'] . '-07-01'));
    }

    function get_end_date($to)
    {
        global $db;
        $query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id = :fyid");
        $query_fy->execute(array(":fyid" => $to));
        $row = $query_fy->fetch();
        return date('Y-m-d', strtotime($row['yr'] . '-06-30'));
    }

    if (isset($_POST['get_sector'])) {
        $sector = $_POST['get_sector'];
        $data = '<option value="" >Select Indicator</option>';


        $query_fy = $db->prepare("SELECT i.indicator_name, i.indid, m.unit FROM tbl_indicator i INNER JOIN tbl_measurement_units m ON m.id  = i.indicator_unit WHERE indicator_sector = :indicator_sector AND indicator_mapping_type != 0");
        $query_fy->execute(array(":indicator_sector" => $sector));
        while ($row = $query_fy->fetch()) {
            $indid = $row['indid'];
            $query_rsMarkers =  $db->prepare(" SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id =m.opid INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE i.indid=:indicator");
            $query_rsMarkers->execute(array(":indicator" => $indid));
            $row_rsMarkers = $query_rsMarkers->fetch();
            $totalRows_rsMarkers = $query_rsMarkers->rowCount();

            if ($totalRows_rsMarkers > 0) {
                $data .= '<option value="' . $indid . '"> ' . $row['unit'] . " of " . $row['indicator_name'] . '</option>';
            }
        }
        echo $data;
    }

    if (isset($_POST['get_fyto'])) {
        $fyid = $_POST['get_fyto'];
        $year = date('Y');
        $data = '<option value="" >Select Financial Year To</option>';
        $query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :fyid AND yr <=:year");
        $query_fy->execute(array(":fyid" => $fyid, ":year" => $year));
        while ($row = $query_fy->fetch()) {
            $yrid = $row['id'];
            $data .= '<option value="' . $yrid . '"> ' . $row['year'] . '</option>';
        }
        echo $data;
    }

    if (isset($_POST['get_level2'])) {
        $getward = $_POST['level1'];
        $data = '<option value="" >Select Ward</option>';
        $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
        $query_ward->execute(array(":getward" => $getward));
        while ($row = $query_ward->fetch()) {
            $projlga = $row['id'];
            $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
            $query_rsLocations->execute(array(":id" => $projlga));
            $row_rsLocations = $query_rsLocations->fetch();
            $total_locations = $query_rsLocations->rowCount();
            if ($total_locations > 0) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }
        }
        echo $data;
    }

    function project($projid)
    {
        global $db;

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();

        return $row_rsProgjects;
    }

    function get_indicator_project_markers($projid, $indicator_id, $mapping_type)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator = :indicator_id AND d.projid=:projid");
        $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
        if ($mapping_type == 1 || $mapping_type == 3) {
            $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid INNER JOIN tbl_project_sites s ON m.site_id=s.site_id  INNER JOIN tbl_state w ON s.state_id=w.id  WHERE d.indicator = :indicator_id AND d.projid=:projid");
            $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
        }
        $markers = $stmt->fetchAll();
        $total_markers = $stmt->rowCount();
        return array("project_details" => project($projid), "markers" => $total_markers ? $markers : '');
    }

    function filter_from_to_projects($projid, $from, $to)
    {
        global $db;
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid AND projfscyear >=:from");
        $query_rsProjects->execute(array(":projid" => $projid, ":from" => $from));
        if (!empty($to)) {
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid AND projfscyear>=:from AND projfscyear <=:to");
            $query_rsProjects->execute(array(":projid" => $projid, ":from" => $from, ":to" => $to));
        }
        return $query_rsProjects->rowCount();
    }

    function get_location_markers($projid, $indicator_id, $mapping_type, $subcounty_id, $ward_id)
    {
        global $db;

        $query_Output_Indicator = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state w ON d.state_id=w.id  INNER JOIN tbl_project_details w ON w.id = d.outputid WHERE w.indicator=:indicator AND w.parent=:parent");
        $query_Output_Indicator->execute(array(":projid" => $projid, ":indicator" => $indicator_id, ":parent" => $subcounty_id));
        $total_Output_Indicator = $query_Output_Indicator->rowCount();
        $project_details = [];
        if ($total_Output_Indicator > 0) {
            if (!empty($ward_id)) {
                $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator = :indicator_id AND d.projid=:projid");
                $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
                if ($mapping_type == 1 || $mapping_type == 3) {
                    $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid INNER JOIN tbl_project_sites s ON m.site_id=s.site_id  INNER JOIN tbl_state w ON s.state_id=w.id  WHERE d.indicator = :indicator_id AND d.projid=:projid AND s.state_id=:ward_id");
                    $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid, ":ward_id" => $ward_id));
                }
                $markers = $stmt->fetchAll();
                $total_markers = $stmt->rowCount();
                $project_details[] = array("project_details" => project($projid), "markers" => $total_markers ? $markers : '');
            } else {
                $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator = :indicator_id AND d.projid=:projid");
                $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
                if ($mapping_type == 1 || $mapping_type == 3) {
                    $stmt = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid INNER JOIN tbl_project_sites s ON m.site_id=s.site_id  INNER JOIN tbl_state w ON s.state_id=w.id  WHERE d.indicator = :indicator_id AND d.projid=:projid");
                    $stmt->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
                }
                $markers = $stmt->fetchAll();
                $total_markers = $stmt->rowCount();
                $project_details[] = array("project_details" => project($projid), "markers" => $total_markers ? $markers : '');
            }
        }
    }

    if (isset($_GET['get_indicator_markers'])) {
        $indicator_id = $_GET['indicator_id'];
        $from =  isset($_GET['fyto']) ? $_GET['fyfrom'] : '';
        $to = isset($_GET['fyto']) ? $_GET['fyto'] : '';
        $subcounty_id = isset($_GET['subcounty_id']) ? $_GET['subcounty_id'] : '';
        $ward_id = isset($_GET['ward_id']) ? $_GET['ward_id'] : '';

        $query_Output = $db->prepare("SELECT * FROM tbl_indicator  WHERE indid = :indicator_id ");
        $query_Output->execute(array(":indicator_id" => $indicator_id));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();
        $project_details = [];
        if ($total_Output > 0) {
            $mapping_type = $row_rsOutput['indicator_mapping_type'];
            $query = $db->prepare("SELECT m.projid FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator=:indicator_id GROUP BY m.projid");
            $query->execute(array(":indicator_id" => $indicator_id));
            $rows = $query->rowCount();
            if ($rows > 0) {
                while ($row = $query->fetch()) {
                    $projid = $row['projid'];
                    if (!empty($from) || !empty($subcounty_id)) {
                        if (!empty($from) && !empty($subcounty_id)) {
                            $exists =   filter_from_to_projects($projid, $from, $to);
                            if ($exists > 0) {
                                $project_details[] = get_location_markers($projid, $indicator_id, $mapping_type, $subcounty_id, $ward_id);
                            }
                        } else if (!empty($from) && empty($subcounty_id)) {
                            $exists =   filter_from_to_projects($projid, $from, $to);
                            if ($exists > 0) {
                                $project_details[] = get_indicator_project_markers($projid, $indicator_id, $mapping_type);
                            }
                        } else if (!empty($subcounty_id)) {
                            $project_details[] = get_location_markers($projid, $indicator_id, $mapping_type, $subcounty_id, $ward_id);
                        }
                    } else {
                        $project_details[] = get_indicator_project_markers($projid, $indicator_id, $mapping_type);
                    }
                }
            }
        }
        echo json_encode(array("success" => $rows > 0 ? true : false, "markers" => $project_details, "indicator" => $row_rsOutput));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
