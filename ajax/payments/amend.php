<?php
include '../controller.php';
try {

    function get_unit_of_measure($unit)
    {
        global $db;
        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
        $query_rsIndUnit->execute(array(":unit_id" => $unit));
        $row_rsIndUnit = $query_rsIndUnit->fetch();
        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
        return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
    }

    function get_unit_requested($request_id, $direct_cost_id)
    {
        global $db;
        $query_rsRequestDetails = $db->prepare("SELECT * FROM  tbl_payments_request_details WHERE request_id=:request_id AND  direct_cost_id = :direct_cost_id");
        $query_rsRequestDetails->execute(array(":request_id" => $request_id, ":direct_cost_id" => $direct_cost_id));
        $row_rsRequestDetails = $query_rsRequestDetails->fetch();
        $totalRows_rsRequestDetails = $query_rsRequestDetails->rowCount();
        return  $totalRows_rsRequestDetails > 0 ? $row_rsRequestDetails['no_of_units'] : 0;
    }

    function get_total_requested_units($direct_cost_id)
    {
        global $db;
        $query_rsRequestDetails = $db->prepare("SELECT * FROM  tbl_payments_request_details WHERE direct_cost_id = :direct_cost_id");
        $query_rsRequestDetails->execute(array(":direct_cost_id" => $direct_cost_id));
        $row_rsRequestDetails = $query_rsRequestDetails->fetch();
        $totalRows_rsRequestDetails = $query_rsRequestDetails->rowCount();
        return  $totalRows_rsRequestDetails > 0 ? $row_rsRequestDetails['no_of_units'] : 0;
    }

    function direct_cost($request_id, $task_id, $site_id)
    {
        global $db;
        $body = '';
        $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id ");
        $query_rsOther_cost_plan->execute(array(":task_id" => $task_id, ':site_id' => $site_id));
        $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
        if ($totalRows_rsOther_cost_plan > 0) {
            $counter = 0;
            while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
                $counter++;
                $direct_cost_id = $row_rsOther_cost_plan['id'];
                $description = $row_rsOther_cost_plan['description'];
                $unit = $row_rsOther_cost_plan['unit'];
                $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                $units_no = $row_rsOther_cost_plan['units_no'];
                $remaining_units = 0;

                $unit_of_measure = get_unit_of_measure($unit);
                $requested_units =    get_unit_requested($request_id, $direct_cost_id);
                $total_requested_units = get_total_requested_units($direct_cost_id);
                $remaining_units = $units_no -  ($total_requested_units - $requested_units);
                $subtotal_cost = $requested_units * $unit_cost;

                $body .= '
                <input type="hidden" name="unit_cost[]" id="unit_cost' . $counter . '" value="' . $unit_cost . '">
                <input type="hidden" name="h_no_units[]" id="h_no_units' . $counter . '" value="' . $remaining_units . '">
                <input type="hidden" name="direct_cost_id[]" id="direct_cost_id' . $counter . '" value="' . $direct_cost_id . '">
                <tr id="budget_line_cost_line' . $counter . '">
                    <td>
                    ' . $description . '
                    </td>
                    <td id="unit' . $counter . '"> ' . $unit_of_measure . ' </td>
                    <td>
                        ' . number_format($remaining_units, 2) . '
                    </td>
                    <td>
                        <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost(' . $counter . ')" onkeyup="calculate_total_cost(' . $counter . ')" id="no_units' . $counter . '" value="' . $requested_units . '">
                    </td>
                    <td>
                        ' . number_format($unit_cost, 2) . '
                    </td>
                    <td>
                        <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $counter . '" class="subamount sub" value="' . $subtotal_cost . '">
                        <span id="subtotal_cost' . $counter . '" style="color:red">' . number_format($subtotal_cost, 2) . '</span>
                    </td>
                </tr>';
            }
        }

        return $body;
    }

    function administrative_cost($request_id)
    {
        global $db;
        $body = '';
        $query_rsPayement_requests =  $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description, r.direct_cost_id, r.id as sub_request_id FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE request_id =:request_id");
        $query_rsPayement_requests->execute(array(":request_id" => $request_id));
        $total_rsPayement_requests = $query_rsPayement_requests->rowCount();

        if ($total_rsPayement_requests > 0) {
            $counter = 0;
            while ($rows_rsPayement_requests = $query_rsPayement_requests->fetch()) {
                $direct_cost_id = $rows_rsPayement_requests['direct_cost_id'];
                $description = $rows_rsPayement_requests['description'];
                $unit = $rows_rsPayement_requests['unit'];
                $unit_cost = $rows_rsPayement_requests['unit_cost'];
                $units_no = $rows_rsPayement_requests['no_of_units'];
                $unit_of_measure = get_unit_of_measure($unit);
                $requested_units =    get_unit_requested($request_id, $direct_cost_id);
                $total_requested_units = get_total_requested_units($direct_cost_id);
                $remaining_units = $units_no -  ($total_requested_units - $requested_units);
                $subtotal_cost = $requested_units * $unit_cost;
                $counter++;
                $body .= '
                <input type="hidden" name="unit_cost[]" id="unit_cost' . $counter . '" value="' . $unit_cost . '">
                <input type="hidden" name="h_no_units[]" id="h_no_units' . $counter . '" value="' . $remaining_units . '">
                <input type="hidden" name="direct_cost_id[]" id="direct_cost_id' . $counter . '" value="' . $direct_cost_id . '">
                <tr id="budget_line_cost_line' . $counter . '">
                    <td>
                    ' . $description . '
                    </td>
                    <td id="unit' . $counter . '"> ' . $unit_of_measure . ' </td>
                    <td>
                        ' . number_format($remaining_units, 2) . '
                    </td>
                    <td>
                        <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost(' . $counter . ')" onkeyup="calculate_total_cost(' . $counter . ')" id="no_units' . $counter . '" value="' . $requested_units . '">
                    </td>
                    <td>
                        ' . number_format($unit_cost, 2) . '
                    </td>
                    <td>
                        <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $counter . '" class="subamount sub" value="' . $subtotal_cost . '">
                        <span id="subtotal_cost' . $counter . '" style="color:red">' . number_format($subtotal_cost, 2) . '</span>
                    </td>
                </tr>';
            }
        }
        return $body;
    }


    if (isset($_GET['get_budget_lines'])) {
        $projid = $_GET['projid'];
        $site_id = $_GET['site_id'];
        $output_id = $_GET['output_id'];
        $task_id = $_GET['task_id'];
        $cost_type = $_GET['cost_type'];
        $request_id = $_GET['request_id'];
        $body = '';
        if ($cost_type == 1) {
            $body = direct_cost($request_id, $task_id, $site_id);
        } else {
            $body = administrative_cost($request_id);
        }
        echo json_encode(array("success" => true, "table_body" => $body));
    }

    if (isset($_POST['store'])) {
        $projid = $_POST['projid'];
        $site_id = $_POST['site_id'];
        $output_id = $_POST['output_id'];
        $task_id = $_POST['task_id'];
        $cost_type = $_POST['cost_type'];
        $request_id = $_POST['request_id'];

        if ($cost_type == 1) {
            if (isset($_POST["direct_cost_id"]) && !empty($_POST['direct_cost_id'])) {
                $_count = count($_POST['direct_cost_id']);
                for ($j = 0; $j < $_count; $j++) {
                    $direct_cost_id = $_POST['direct_cost_id'][$j];
                    $sql = $db->prepare("DELETE FROM `tbl_payments_request_details` WHERE request_id=:request_id AND direct_cost_id=:direct_cost_id");
                    $results = $sql->execute(array(':request_id' => $request_id, ":direct_cost_id" => $direct_cost_id));

                    if (isset($_POST['no_units'][$j])  && $_POST['no_units'][$j] != 0) {
                        $no_of_units = $_POST['no_units'][$j];
                        $unit_cost = $_POST['unit_cost'][$j];
                        $sql = $db->prepare("INSERT INTO tbl_payments_request_details (request_id, direct_cost_id,no_of_units, unit_cost) VALUES (:request_id, :direct_cost_id,:no_of_units, :unit_cost)");
                        $results  = $sql->execute(array(":request_id" => $request_id, ":direct_cost_id" => $direct_cost_id, ":no_of_units" => $no_of_units, ":unit_cost" => $unit_cost));
                    }
                }
            }
        } else {
            $sql = $db->prepare("DELETE FROM `tbl_payments_request_details` WHERE request_id=:request_id");
            $results = $sql->execute(array(':request_id' => $request_id));
            if (isset($_POST["direct_cost_id"]) && !empty($_POST['direct_cost_id'])) {
                $_count = count($_POST['direct_cost_id']);
                for ($j = 0; $j < $_count; $j++) {
                    $direct_cost_id = $_POST['direct_cost_id'][$j];
                    if (isset($_POST['no_units'][$j])  && $_POST['no_units'][$j] != 0) {
                        $no_of_units = $_POST['no_units'][$j];
                        $unit_cost = $_POST['unit_cost'][$j];
                        $sql = $db->prepare("INSERT INTO tbl_payments_request_details (request_id, direct_cost_id,no_of_units, unit_cost) VALUES (:request_id, :direct_cost_id,:no_of_units, :unit_cost)");
                        $results  = $sql->execute(array(":request_id" => $request_id, ":direct_cost_id" => $direct_cost_id, ":no_of_units" => $no_of_units, ":unit_cost" => $unit_cost));
                    }
                }
            }
        }
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
