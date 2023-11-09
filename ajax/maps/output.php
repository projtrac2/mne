

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
            $data .= '<option value="' . $indid . '"> ' . $row['unit'] . " of " . $row['indicator_name'] . '</option>';
        }
        echo $data;
    }

    if (isset($_POST['get_fyto'])) {
        $fyid = $_POST['get_fyto'];
        $data = '<option value="" >Select Financial Year To</option>';
        $query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :fyid");
        $query_fy->execute(array(":fyid" => $fyid));
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

        $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator=:indicator_id");
        $query->execute(array(":indicator_id" => $indicator_id));
        $row = $query->fetchAll();
        $rows = $query->rowCount();
        if ($subcounty_id != '' && $from != '') {
            if($ward_id != '' && $to != ''){
                
            }else if($ward_id != '' && $to == ''){

            }else if($ward_id == '' && $to != ''){

            }else{

            }
        } else if($subcounty_id != '' && $from == ''){

        }else if ($subcounty_id !='' && $from != '') {
            $start_date = get_start_date($from);
            if ($to != '') {
                $end_date = get_end_date($to);
                $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator=:indicator_id WHERE mapped_date BETWEEN :start_date AND :end_date");
                $query->execute(array(":indicator_id" => $indicator_id, ":start_date" => $start_date, ":end_date" => $end_date));
                $row = $query->fetchAll();
                $rows = $query->rowCount();
            } else {
                $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator=:indicator_id WHERE mapped_date >= :start_date");
                $query->execute(array(":indicator_id" => $indicator_id, ":start_date" => $start_date));
                $row = $query->fetchAll();
                $rows = $query->rowCount();
            }
        }

        echo json_encode(array("success" => $rows > 0 ? true : false, "markers" => $row, "indicator" => $row_rsOutput));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
