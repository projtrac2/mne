<?php
include '../controller.php';
try {
    if (isset($_POST['store_checklists'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $site_id =isset( $_POST['site_id']) && !empty( $_POST['site_id']) ?  $_POST['site_id'] : 0;
        $task_id = $_POST['task_id'];
        $checklists = $_POST['checklist'];
        $total_checklists = count($checklists);
        $units = $_POST['unit'];
        $targets = $_POST['target'];
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");

        $sql = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist` WHERE task_id=:task_id AND site_id=:site_id");
        $results = $sql->execute(array(':task_id' => $task_id, 'site_id'=>$site_id));

        if ($total_checklists > 0) {
            for ($i = 0; $i < $total_checklists; $i++) {
                $checklist = $checklists[$i];
                $unit = $units[$i];
                $target = $targets[$i];
                $sql = $db->prepare("INSERT INTO tbl_project_monitoring_checklist (projid,output_id,site_id,task_id,checklist,unit_of_measure,target,created_by,created_at) VALUES(:projid,:output_id,:site_id,:task_id,:checklist,:unit_of_measure,:target,:created_by,:created_at)");
                $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id,":site_id"=>$site_id, ":task_id" => $task_id, ":checklist" => $checklist,":unit_of_measure"=>$unit,":target"=>$target, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        }
        echo json_encode(array("success" => true, "message" => "Created successfully"));
    }

    function get_measurement($edit_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active = 1");
        $sql->execute();
        $rows_count = $sql->rowCount();
        $options = '<option value="">..Select Measurement Unit..</option>';
        if ($rows_count > 0) {
            while ($row = $sql->fetch()) {
                $unit_id = $row['id'];
                $unit = $row['unit'];
                $selected = $unit_id == $edit_id ? 'selected' : '';
                $options .= '<option value="' . $unit_id . '" ' . $selected . '>' . $unit . '</option>';
            }
        }
        return $options;
    }

    if (isset($_GET['get_measurement_unit'])) {
        $measurement_units = get_measurement("");
        echo json_encode(array("success" => true, "measurement_units" => $measurement_units));
    }

    if (isset($_GET['get_checklist'])) {
        $task_id = $_GET['task_id'];
        $site_id = $_GET['site_id'];
        $query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE task_id=:task_id AND site_id=:site_id");
        $query_rsMonitoring->execute(array(":task_id" => $task_id, ":site_id"=>$site_id));
        $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
        $data = "";
        $msg = false;
        if ($totalRows_rsMonitoring > 0) {
            $rowno = 0;
            while ($row_rsMonitoring = $query_rsMonitoring->fetch()) {
                $checklist = $row_rsMonitoring['checklist'];
                $unit_id = $row_rsMonitoring['unit_of_measure'];
                $target = $row_rsMonitoring['target'];
                $units = get_measurement($unit_id);
                $msg = true;
                $rowno++;
                $data .= '
                <tr id="s_row' . $rowno . '">
                    <td>' . $rowno . '</td>
                    <td>
                        <input type="text" name="checklist[]" id="checklistrow' . $rowno . '" value="' . $checklist . '" placeholder="Enter" class="form-control" required/>
                    </td>
                    <td>
                        <select name="unit[]" id="unitrow'.$rowno.'" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                            ' . $units . '
                        </select>
                    </td>
                    <td>
                        <input type="number" name="target[]" id="targetrow' . $rowno . '" value="' . $target . '" placeholder="Enter" class="form-control" required/>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_checklist("s_row' . $rowno . '")>
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => $msg, "checklists" => $data));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}