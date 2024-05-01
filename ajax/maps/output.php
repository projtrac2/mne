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

    function validate_location()
    {
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
            $query = $db->prepare("SELECT projid FROM tbl_project_details WHERE indicator=:indicator_id GROUP BY projid");
            $query->execute(array(":indicator_id" => $indicator_id));
            $rows = $query->rowCount();
            if ($rows > 0) {
                while ($row = $query->fetch()) {
                    $projid = $row['projid'];
                    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
                    $query_rsProjects->execute(array(":projid" => $projid));
                    if (!empty($from) && !empty($to)) {
                        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid AND projfscyear >=:from");
                        $query_rsProjects->execute(array(":projid" => $projid, ":from" => $from));
                        if (!empty($to)) {
                            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid AND projfscyear>=:from AND projfscyear <=:to");
                            $query_rsProjects->execute(array(":projid" => $projid, ":from" => $from, ":to" => $to));
                        }
                    }

                    $total_projects = $query_rsProjects->rowCount();
                    $row_projects = $query_rsProjects->fetch();

                    if ($total_projects > 0) {
                        $projname = $row_projects['projname'];
                        $query_output = $db->prepare("SELECT id FROM tbl_project_details WHERE indicator=:indicator_id AND projid=:projid");
                        $query_output->execute(array(":indicator_id" => $indicator_id, ":projid" => $projid));
                        $row_outputs = $query_output->fetch();
                        $rows_outputs = $query_output->rowCount();

                        if ($rows_outputs > 0) {
                            $output_id = $row_outputs['id'];
                            if ($mapping_type == 1 ) {
                                $query_sites = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                                $query_sites->execute(array(":output_id" => $output_id));
                                if (!empty($subcounty_id)) {
                                    $query_sites = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id INNER JOIN tbl_state t ON t.id = p.state_id  WHERE outputid = :output_id AND t.parent=:parent");
                                    $query_sites->execute(array(":output_id" => $output_id, ":parent" => $subcounty_id));
                                    if (!empty($ward_id)) {
                                        $query_sites = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id INNER JOIN tbl_state t ON t.id = p.state_id  WHERE outputid = :output_id AND t.id=:ward_id ");
                                        $query_sites->execute(array(":output_id" => $output_id, ":ward_id" => $ward_id));
                                    }
                                }
                                $total_sites = $query_sites->rowCount();

                                if ($total_sites > 0) {
                                    while ($row_rsMaps = $query_sites->fetch()) {
                                        $site_name = $row_rsMaps['site'];
                                        $site_id = $row_rsMaps['site_id'];

                                        $query_markers = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND m.site_id=:site_id");
                                        $query_markers->execute(array(":output_id" => $output_id, ":projid" => $projid, ":site_id" => $site_id));
                                        $row_markers = $query_markers->fetchAll();
                                        $total_markers = $query_markers->rowCount();
                                        if ($total_markers > 0) {
                                            $project_details[] = array("project_name" => $projname, "site" => $site_name, "markers" =>  $row_markers, "locations" => '');
                                        }
                                    }
                                }
                            } else {
                                $level1_ids =  explode(",", $row_projects['projcommunity']);
                                $level2_ids =  explode(",", $row_projects['projlga']);
                                $option = true;
                                if (!empty($subcounty_id)) {
                                    $option = false;
                                    if (in_array($subcounty_id, $level1_ids)) {
                                        if (!empty($ward_id)) {
                                            $option = (in_array($ward_id, $levels_ids)) ? true : false;
                                        } else {
                                            $option = true;
                                        }
                                    }
                                }

                                if ($option) {
                                    $site_id = 0;
                                    $query_markers = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND m.site_id=:site_id");
                                    $query_markers->execute(array(":output_id" => $output_id, ":projid" => $projid, ":site_id" => $site_id));
                                    $row_markers = $query_markers->fetchAll();
                                    $total_markers = $query_markers->rowCount();
                                    if ($total_markers > 0) {
                                        $project_details[] = array("project_name" => $projname, "site" => "", "markers" =>  $row_markers, "locations" => '');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo json_encode(array("success" => true, "markers" => $project_details, "indicator" => $row_rsOutput));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
