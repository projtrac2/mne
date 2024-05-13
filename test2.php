<?php
try {
    include_once 'projtrac-dashboard/resource/Database.php';
    include_once 'projtrac-dashboard/resource/utilities.php';
    include_once 'includes/app-security.php';
    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE stage_id =1 AND projstage=13");
    $query_rsProjects->execute();
    $total_rsProjects = $query_rsProjects->rowCount();
    if ($total_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $projid = $row_rsProjects['projid'];
            $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND team_type = 4");
            $result = $sql->execute(array(':projid' => $projid));

            $sql = $db->prepare("DELETE FROM `tbl_member_subtasks` WHERE projid=:projid");
            $result = $sql->execute(array(':projid' => $projid));
        }
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
