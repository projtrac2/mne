<?php
try {
    include "../controller.php";
    $valid['success'] = array('success' => false, 'messages' => array());
    if (isset($_GET['get_mesurement_units'])) {
        $query_measurement_units = $db->prepare("SELECT * FROM `tbl_measurement_units` ORDER BY `id` ASC");
        $query_measurement_units->execute();
        $rows_count = $query_measurement_units->rowCount();
        $output = array('data' => array());

        if ($rows_count > 0) {
            $active = "";
            $sn = 0;
            while ($row = $query_measurement_units->fetch()) {
                $sn++;
                $itemId = $row['id'];
                if ($row['active'] == 1) {
                    $active = "<label class='label label-success'>Enabled</label>";
                } else {
                    $active = "<label class='label label-danger'>Disabled</label>";
                }

                $button = "";
                if ($role_group == 1) {
                    $button = '<!-- Single button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" onclick="editItem(' . $itemId . ')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                            <li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $itemId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>
                        </ul>
                    </div>';
                }

                $unit = $row["unit"];
                $description = $row["description"];
                $output['data'][] = array(
                    $sn,
                    $unit,
                    $description,
                    $active,
                    $button
                );
            }
        }
        echo json_encode($output);
    }

    if (isset($_POST["newitem"])) {
        $unit = $_POST['unit'];
        $description = $_POST['description'];
        $status = 1;

        $sql = $db->prepare("INSERT INTO tbl_measurement_units (unit,description, active) VALUES(:unit,:description, :status )");
        $results = $sql->execute(array(":unit" => $unit, ":description" => $description, ":status" => $status));

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while adding the record!!";
        }
        echo json_encode($valid);
    }

    if (isset($_POST["edititem"])) {
        $unit = $_POST['editunit'];
        $description = $_POST['editdescription'];
        $status = $_POST['editStatus'];
        $itemid = $_POST['itemId'];

        $sql = $db->prepare("UPDATE tbl_measurement_units SET unit=:unit,description=:description, active=:status WHERE id =:id");
        $results = $sql->execute(array(":unit" => $unit, ":description" => $description, ":status" => $status, ":id" => $itemid));

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while updatng the record!!";
        }
        echo json_encode($valid);
    }

    if (isset($_POST["deleteItem"])) {
        $itemid = $_POST['itemId'];
        $deleteQuery = $db->prepare("DELETE FROM `tbl_measurement_units` WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
        echo json_encode($valid);
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
