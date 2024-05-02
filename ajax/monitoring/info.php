<?php
try {
    include '../controller.php';

    function previous_remarks($site_id, $subtask_id, $task_id)
    {
        global $db;
        $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE site_id=:site_id and subtask_id=:subtask_id ");
        $query_allObservation->execute(array(":site_id" => $site_id, ":subtask_id" => $subtask_id));
        $totalrows_allObservation = $query_allObservation->rowCount();
        $comments_body = "";
        if ($totalrows_allObservation > 0) {
            $count =  0;
            while ($rows_allObservation = $query_allObservation->fetch()) {
                $count++;
                $comments = $rows_allObservation["observation"];
                $created_at = $rows_allObservation["created_at"];
                $comments_body .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td>' . $comments . '</td>
                        <td>' . date("d-m-Y", strtotime($created_at)) . '</td>
                    </tr>';
            }
        }
        $previous_records = $comments_body != "" ?  get_body($comments_body) : "<h4>No Records</h4>";
        return $previous_records;
    }

    function previous_attachment($site_id, $subtask_id, $task_id)
    {
        global $db;
        $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE site_id=:site_id and ftype='mp4'");
        $query_project_videos->execute(array(":site_id" => $site_id));
        $count_project_videos = $query_project_videos->rowCount();
        $data = '';
        if ($count_project_videos > 0) {
            $rowno = 0;
            while ($rows_project_videos = $query_project_videos->fetch()) {
                $rowno++;
                $projstageid = $rows_project_videos['projstage'];
                $filename = $rows_project_videos['filename'];
                $filepath = $rows_project_videos['floc'];
                $purpose = $rows_project_videos['reason'];

                $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                $query_project_stage->execute(array(":projstageid" => $projstageid));
                $rows_project_stage = $query_project_stage->fetch();
                $projstage = $rows_project_stage['stage'];
                $data .= '
                <tr>
                    <td width="5%">' . $rowno . '</td>
                    <td width="35%">' . $filename . '</td>
                    <td width="35%">' . $purpose . '</td>
                    <td width="10%">' . $projstage . '</td>
                    <td width="15%">
                        <a href="' . $filepath . '" watch target="_balnk">Download</a>
                    </td>
                </tr>';
            }
        }

        return $data;
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
