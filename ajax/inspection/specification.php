<?php
try {
    include '../controller.php';
    if (isset($_POST['store_specifications'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $standards = $_POST['standard'];
        $task_id = $_POST['task_id'];
        $site_id = $_POST['site_id'];
        $specifications = $_POST['specifications'];
        $total_specifications = count($specifications);
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");

        $sql = $db->prepare("DELETE FROM `tbl_project_specifications` WHERE task_id=:task_id AND site_id=:site_id");
        $results = $sql->execute(array(':task_id' => $task_id, ":site_id" => $site_id));
        if ($total_specifications > 0) {
            for ($i = 0; $i < $total_specifications; $i++) {
                $specification = $specifications[$i];
                $standard = $standards[$i];
                $sql = $db->prepare("INSERT INTO tbl_project_specifications (projid,output_id,task_id,site_id,specification,standard_id, created_by,created_at) VALUES(:projid,:output_id,:task_id,:site_id,:specification,:standard_id,:created_by,:created_at)");
                $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":task_id" => $task_id, ":site_id" => $site_id, ":specification" => $specification, ":standard_id" => $standard, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        }
        echo json_encode(array("success" => true, "message" => "Created successfully"));
    }

    function get_standards($edit_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id  ORDER BY `standard_id` ASC");
        $sql->execute();
        $rows_count = $sql->rowCount();

        $options = '<option value="">..Select Standards..</option>';
        if ($rows_count > 0) {
            while ($row = $sql->fetch()) {
                $standard_id = $row['standard_id'];
                $standard = $row['standard'];
                $selected = $standard_id == $edit_id ? 'selected' : '';
                $options .= '<option value="' . $standard_id . '" ' . $selected . '>' . $standard . '</option>';
            }
        }
        return $options;
    }


    if (isset($_GET['get_standards'])) {
        $standards = get_standards("");
        echo json_encode(array("success" => true, "standard" => $standards));
    }


    if (isset($_GET['get_specification'])) {
        $site_id = $_GET['site_id'];
        $task_id = $_GET['task_id'];
        $query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE site_id=:site_id AND task_id=:task_id");
        $query_rsSpecifions->execute(array(":site_id" => $site_id, ":task_id" => $task_id));
        $totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
        $data = "";
        $msg = false;
        if ($totalRows_rsSpecifions > 0) {
            $rowno = 0;
            while ($row_rsSpecifions = $query_rsSpecifions->fetch()) {
                $specification = $row_rsSpecifions['specification'];
                $standard_id = $row_rsSpecifions['standard_id'];
                $standards = get_standards($standard_id);
                $msg = true;
                $rowno++;
                $data .= '
                <tr id="s_row' . $rowno . '">
                    <td>' . $rowno . '</td>
                    <td>
                        <input type="text" name="specifications[]" id="specificationsrow' . $rowno . '" value="' . $specification . '" placeholder="Enter" class="form-control" required/>
                    </td>
                    <td>
                        <select name="standard[]" id="standard' . $rowno . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false">
                            ' . $standards . '
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_specifications("s_row"' . $rowno . ')>
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => $msg, "specifications" => $data));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
