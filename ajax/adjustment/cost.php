<?php
include '../controller.php';
try {
    if (isset($_POST['store_cost'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $task_id = $_POST['task_id'];
        $site_id = $_POST['site_id'];
        $adjustment_id = $_POST['adjustment_id'];
        $subtask_id = $_POST['subtask_id'];
        $unit_cost = $_POST['unit_cost'];
        $units_no = $_POST['no_units'];
        $result = false;

        if (isset($_POST['unit_cost']) && !empty($_POST['unit_cost'])) {
            $query_rsAdjustments = $db->prepare("SELECT * FROM tbl_project_cost_adjustment WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id  AND projid = :projid AND id=:adjustment_id");
            $query_rsAdjustments->execute(array(':task_id' => $task_id, ':site_id' => $site_id, ":subtask_id" => $subtask_id, ":projid" => $projid, ":adjustment_id" => $adjustment_id));
            $Rows_Adjustments = $query_rsAdjustments->rowCount();
            if ($Rows_Adjustments == 0) {
                $sql = $db->prepare("UPDATE tbl_project_cost_adjustment SET  unit_cost=:unit_cost WHERE site_id=:site_id AND subtask_id=:subtask_id AND adjustment_id=:adjustment_id");
                $result  = $sql->execute(array(":unit_cost" => $unit_cost, ":site_id" => $site_id, ":subtask_id" => $subtask_id, ":adjustment_id" => $adjustment_id));
            } else {
                $sql = $db->prepare("INSERT INTO tbl_project_cost_adjustment (projid,output_id,site_id,task_id,subtask_id,adjustment_id,units,unit_cost,created_by) VALUES (:projid,:output_id,:site_id,:task_id,:subtask_id,:adjustment_id,:units,:unit_cost,:created_by)");
                $result  = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id, ":task_id" => $task_id, ":subtask_id" => $subtask_id, ":adjustment_id" => $adjustment_id, ":units" => $units_no, ":unit_cost" => $unit_cost,  ":created_by" => $user_name));
            }
        }

        var_dump($result);
        return;
        echo json_encode(array("success" => $result));
    }


    if (isset($_GET['get_subtasks_edit'])) {
        $site_id = $_GET['site_id'];
        $projid = $_GET['projid'];
        $task_id = $_GET['task_id'];
        $subtask_id = $_GET['subtask_id'];
        $adjustment_id = $_GET['adjustment_id'];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_task WHERE msid=:task_id AND tkid=:subtask_id");
        $query_rsMilestone->execute(array(":task_id" => $task_id, ":subtask_id" => $subtask_id));
        $row_rsMilestone = $query_rsMilestone->fetch();
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();

        $input = $task ="";
        $remaining_amount = 0;
        if ($totalRows_rsMilestone > 0) {
            $task = $row_rsMilestone['task'];
            $tkid = $row_rsMilestone['tkid'];

            $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id AND subtask_id=:subtask_id");
            $query_rsTask_parameters->execute(array(":task_id" => $task_id, ':site_id' => $site_id, ":subtask_id" => $subtask_id));
            $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
            $row_rsTask_parameters = $query_rsTask_parameters->fetch();
            $planned_units =  ($totalRows_rsTask_parameters > 0) ? $row_rsTask_parameters['units_no'] : 0;


            $query_rsRequested_Units = $db->prepare("SELECT SUM(units_no) units_no FROM tbl_contractor_payment_request_details WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
            $query_rsRequested_Units->execute(array(':task_id' => $task_id, ':site_id' => $site_id, ":subtask_id" => $tkid));
            $row_rsRequested_Units = $query_rsRequested_Units->fetch();
            $requested_units = (!is_null($row_rsRequested_Units['units_no'])) ? $row_rsRequested_Units['units_no'] : 0;

            $remaining_units = $planned_units - $requested_units;
            if ($remaining_units > 0) {
                $query_rsAdjustments = $db->prepare("SELECT * FROM tbl_project_adjustments WHERE projid = :projid AND cost_status=0 AND id=:adjustment_id");
                $query_rsAdjustments->execute(array(":projid" => $projid, ":adjustment_id" => $adjustment_id));
                $Rows_Adjustments = $query_rsAdjustments->fetch();
                $totalRows_Adjustments = $query_rsAdjustments->rowCount();
                $cost_adjusted =  $totalRows_Adjustments > 0 ? $Rows_Adjustments['cost'] : 0;

                $query_rsProject_Adjusted = $db->prepare("SELECT SUM(units * unit_cost)  as cost FROM tbl_project_cost_adjustment WHERE projid = :projid AND id=:adjustment_id");
                $query_rsProject_Adjusted->execute(array(":projid" => $projid, ":adjustment_id" => $adjustment_id));
                $Rows_Project_Adjusted = $query_rsProject_Adjusted->fetch();
                $adjusted_cost =  is_null($Rows_Project_Adjusted['cost']) > 0 ? $Rows_Project_Adjusted['cost'] : 0;


                $query_rsAdjusted_unit_cost = $db->prepare("SELECT SUM(units * unit_cost)  as cost FROM tbl_project_cost_adjustment WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id  AND projid = :projid AND id=:adjustment_id");
                $query_rsAdjusted_unit_cost->execute(array(':task_id' => $task_id, ':site_id' => $site_id, ":subtask_id" => $tkid, ":projid" => $projid, ":adjustment_id" => $adjustment_id));
                $Rows_Adjusted_unit_cost = $query_rsAdjusted_unit_cost->fetch();
                $subtask_cost =  !is_null($Rows_Adjusted_unit_cost['cost']) > 0 ? $Rows_Adjusted_unit_cost['cost'] : 0;
                $remaining_amount = ($cost_adjusted - $adjusted_cost) + $subtask_cost;

                $input .= '
                <tr>
                    <td>
                        ' . $task . '
                    </td>
                    <td>
                        ' . number_format($remaining_units, 2) . '
                    </td>
                    <td>
                        <input name="unit_cost" type="number" min="1"  max="' . $remaining_amount . '" step="1" placeholder="Enter Unit Cost Adjustment" value="'.$subtask_cost.'" id="unit_cost' . $tkid . '" class="form-control" required="required" onkeyup="change_calculate_adjust_cost(' . $tkid . ', ' . $remaining_units . ', ' . $remaining_amount . ')" onchange="change_calculate_adjust_cost(' . $tkid . ', ' . $remaining_units . ', ' . $remaining_amount . ')" required/>
                        <input name="tasks" type="hidden" value="' . $tkid . '" />
                        <input name="no_units" type="hidden" value="' . $remaining_units . '" id="no_units' . $tkid . '"/>
                        <input name="remaining_amount" type="hidden" value="' . $remaining_amount . '" id="remaining_amount' . $tkid . '"/>
                    </td>
                    <td id="subtotal_amount"> </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => true, "tasks" => $input, "remaining_amount" => number_format($remaining_amount, 2), "task"=>$task));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
