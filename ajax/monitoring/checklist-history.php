<?php
include '../controller.php';
try {
    function previous_remarks($site_id, $subtask_id)
    {
        global $db;
        $created_at =date('Y-m-d');
        $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE site_id=:site_id and subtask_id=:subtask_id AND created_at=:created_at");
        $query_allObservation->execute(array(":site_id" => $site_id, ":subtask_id" => $subtask_id, ":created_at"=>$created_at));
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

        $comments_bod = $comments_body != "" ?  $comments_body : "<h4>No Records</h4>";

        $c = '
        <fieldset class="scheduler-border">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                <i class="fa fa-comment" aria-hidden="true"></i> Remark(s)
            </legend>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:85%">Comment</th>
                                <th style="width:10%">Date Posted</th>
                            </tr>
                        </thead>
                        <tbody id="previous_comments">
                        ' . $comments_bod . '
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>';
        return $c;
    }

    function previous_attachment($site_id, $subtask_id)
    {
        global $db;
        $created_at =date('Y-m-d');
        $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE site_id=:site_id AND subtask_id=:subtask_id AND date_uploaded=:created_at AND fcategory='monitoring checklist' ");
        $query_project_videos->execute(array(":site_id" => $site_id, ":subtask_id" => $subtask_id,":created_at"=>$created_at));
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

    if (isset($_GET['get_info'])) {
        $subtask_id = $_GET['subtask_id'];
        $site_id = $_GET['site_id'];

        echo json_encode(array("success"=>true, "remarks"=>previous_remarks($site_id, $subtask_id), 'attachments' => previous_attachment($site_id, $subtask_id)));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
