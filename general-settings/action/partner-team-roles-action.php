<?php

include_once "controller.php";

try {
    if (isset($_POST['newItem'])) {
        $name = $_POST['name'];
        $active = 1;

        $updateQuery = $db->prepare("INSERT INTO tbl_partner_roles (role, `status`) VALUES (:role, :status)");
        $result = $updateQuery->execute([
            ':role' => $name,
            ':status' => $active,
        ]);

        if ($result === TRUE) {
            $valid = true;
        } else {
            $valid = false;
        }
        echo json_encode($valid);
    }

    if (isset($_POST['editItem'])) {
        $itemid = $_POST['itemid'];
        $name = $_POST['name'];

        $updateQuery = $db->prepare("UPDATE tbl_partner_roles SET role=:role WHERE id=:itemid");
        $result = $updateQuery->execute([
            ':itemid' => $itemid,
            ':role' => $name,
        ]);

        if ($result === TRUE) {
            $valid = true;
        } else {
            $valid = false;
        }
        echo json_encode($valid);
    }

    if (isset($_POST['deleteItem'])) {
        $itemid = $_POST['itemId'];


        $updateStatus = '';

        $stmt = $db->prepare("SELECT * FROM `tbl_partner_roles` where id=:itemid");
        $stmt->execute([':itemid' => $itemid]);
        $stmt_results = $stmt->fetch();

        $stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

        $deleteQuery = $db->prepare("UPDATE `tbl_partner_roles` SET `status`=:status WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid, ':status' => $updateStatus));

        if ($results === TRUE) {
            $valid = true;
        } else {
            $valid = false;
        }

        echo json_encode($valid);
    }
} catch (\Throwable $th) {
    echo $th->getMessage();
}
