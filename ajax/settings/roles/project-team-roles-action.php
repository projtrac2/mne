<?php

include_once '../../controller.php';

try {
    if (isset($_POST['newItem'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $rank = $_POST['rank'];
        $active = 1;

        $updateQuery = $db->prepare("INSERT INTO tbl_project_team_roles (role, description, `rank`, active) VALUES (:role, :description, :rank, :active)");
        $result = $updateQuery->execute([
            ':role' => $name,
            ':description' => $description,
            ':rank' => $rank,
            ':active' => $active,
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
        $description = $_POST['description'];
        $rank = $_POST['rank'];

        $updateQuery = $db->prepare("UPDATE tbl_project_team_roles SET role=:role, description=:description, `rank`=:rank WHERE id=:itemid");
        $result = $updateQuery->execute([
            ':itemid' => $itemid,
            ':role' => $name,
            ':description' => $description,
            ':rank' => $rank,
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

        $stmt = $db->prepare("SELECT * FROM `tbl_project_team_roles` where id=:itemid");
        $stmt->execute([':itemid' => $itemid]);
        $stmt_results = $stmt->fetch();

        $stmt_results['active'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

        $deleteQuery = $db->prepare("UPDATE `tbl_project_team_roles` SET active=:active WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid, ':active' => $updateStatus));

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
