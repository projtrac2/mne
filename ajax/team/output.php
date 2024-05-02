<?php
include '../controller.php';
try {
    if (isset($_GET['get_project_outputs'])) {
        $projid = $_GET['projid'];
        $outputs = '<option value="">Select Output from list</option>';
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        $success = false;
        $outputs = '<option value="">.... Select Output ....</option>';
        if ($total_Output > 0) {
            $success = true;
            while ($row_rsOutput = $query_Output->fetch()) {
                $output_id = $row_rsOutput['id'];
                $output = $row_rsOutput['indicator_name'];
                $outputs .= '<option value="' . $output_id . '">' . $output . '</option>';
            }
        }
        echo json_encode(array("success" => $success, "outputs" => $outputs));
    }

    function get_site_target($site_id, $output_id)
    {
        global $db;
        $query_rsSite = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE site_id=:site_id AND outputid=:output_id");
        $query_rsSite->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
        $Rows_rsSite = $query_rsSite->fetch();
        $totalRows_rsSite = $query_rsSite->rowCount();
        $target =  ($totalRows_rsSite > 0) ? $Rows_rsSite['total_target'] : 0;

        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE site_id=:site_id AND output_id=:output_id");
        $query_rsTargetUsed->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
        $achieved =$Rows_rsTargetUsed['achieved'] !=null ? $Rows_rsTargetUsed['achieved'] : 0;
        return  $target -  $achieved;
    }

    function get_output_milestone_target($milestone_id, $output_id)
    {
        global $db;
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id AND output_id=:output_id");
        $query_rsOutput->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $target = $totalRows_rsOutput > 0 ? $Rows_rsOutput[''] : 0;

        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE milestone_id=:milestone_id AND output_id=:output_id");
        $query_rsTargetUsed->execute(array(":site_id" => $milestone_id, ":output_id" => $output_id));
        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
        $achieved =$Rows_rsTargetUsed['achieved'] !=null ? $Rows_rsTargetUsed['achieved'] : 0;
        return  $target -  $achieved;
    }

    function get_output_details($output_id, $site_id, $milestone_id)
    {
        global $db;
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details WHERE milestone_id=:milestone_id AND id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $output_target = ($totalRows_rsOutput > 0) ? $Rows_rsOutput['total_target'] : 0 ;

        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
        $query_rsTargetUsed->execute(array(":output_id" => $output_id));
        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
        $achieved_output_target =$Rows_rsTargetUsed['achieved'] !=null ? $Rows_rsTargetUsed['achieved'] : 0;

        $site_target = get_site_target($site_id, $output_id);
        $milestone_target = get_output_milestone_target($milestone_id, $output_id);

        $output_ceiling = $site_target = $cumulative_measurement = $previous = 0;

        $milestone_achieved = 0;

        $data = array("site_target" => $site_target, "site_achieved" => $cumulative_measurement, "milestone_target" => $previous, 'milestone_achieved'=>$milestone_achieved);
        return $data;
    }

    function get_milestones($projid, $output_id, $site_id)
    {
        global $db;
        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid ");
        $query_rsMilestone->execute(array(":projid" => $projid));
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $milestones = ' <option value="">.... Select Milestone ....</option>';
        if ($totalRows_rsMilestone > 0) {
            while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                $milestone_name = $row_rsMilestone['milestone'];
                $milestone_id = $row_rsMilestone['id'];
                $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id AND output_id=:output_id");
                $query_rsOutput->execute(array(":milestone_id" => $milestone_id, ":output_id" => $output_id));
                $totalRows_rsOutput = $query_rsOutput->rowCount();
                if ($totalRows_rsOutput > 0) {
                    $milestones .= '<option value="' . $milestone_id . '">' . $milestone_name . '</option>';
                }
            }
        }
        return $milestones;
    }

    if (isset($_GET['get_output_sites'])) {
        $output_id = $_GET['output_id'];
        $success = true;
        $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.id=:output_id");
        $query_rsOutput->execute(array(":output_id" => $output_id));
        $totalRows_rsOutput = $query_rsOutput->rowCount();
        $Rows_rsOutput = $query_rsOutput->fetch();

        $query_rsMl =  $db->prepare("SELECT * FROM tbl_project_milestone m INNER JOIN tbl_project_milestone_outputs o ON  o.milestone_id=m.id  WHERE output_id =:output_id AND milestone_type = 2");
        $query_rsMl->execute(array(":output_id" => $output_id));
        $Rows_rsMl = $query_rsMl->rowCount();
        $output_project_type = $Rows_rsMl > 0 ?  2 : 1;

        $sites = '<option value="">... Select Site ...</option>';
        $mapping_type = '';
        $projid = '';
        if ($totalRows_rsOutput > 0) {
            $mapping_type = $Rows_rsOutput['indicator_mapping_type'];
            $projid = $Rows_rsOutput['projid'];
            $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
            $query_Output->execute(array(":output_id" => $output_id));
            $total_Output = $query_Output->rowCount();
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $site_id = $row_rsOutput['site_id'];
                    $site_name = $row_rsOutput['site'];
                    $sites .= '<option value="' . $site_id . '">' . $site_name . '</option>';
                }
            }
        }

        $output_details = get_output_details($output_id, 0, 0);
        $output_type = $mapping_type == 2 || $mapping_type == 0 ? 2 : 1;
        echo json_encode(array("success" => $success, "sites" => $sites, 'output_type' => $output_type, 'output_project_type' => $output_project_type, "output_details" => $output_details, "milestones" => get_milestones($projid, $output_id, 0)));
    }

    if (isset($_GET['get_milestone_outputs'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $site_id = $_GET['site_id'];
        echo json_encode(array("success" => true, "milestones" => get_milestones($projid, $output_id, $site_id)));
    }

    if (isset($_GET['get_output_details'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $site_id = $_GET['site_id'];
        $milestone_id = $_GET['milestone_id'];
        $output_details =  get_output_details($output_id, $site_id, $milestone_id);

        echo json_encode(array("success" => true, "output_details" => $output_details));
    }

    if (isset($_POST['monitoring_type'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $milestone_id = $_POST['milestone_id'];
        $task_id = $_POST['task_id'];
        $site_id = $_POST['site_id'];
        $subtask_id = $_POST['subtask_id'];
        $achieved = $_POST['current_measure'];
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");
        $origin = 4;

        $sql = $db->prepare("INSERT INTO tbl_monitoringoutput (projid,output_id,design_id,site_id,state_id,form_id,achieved,created_by,date_created) VALUES(:projid,:output_id,:design_id,:site_id,:state_id,:formid,:achieved,:created_by,:created_at)");
        $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":design_id" => $design_id, ":site_id" => $site_id, ":state_id" => $state_id, ":formid" => $formid, ":achieved" => $achieved, ":created_by" => $created_by, ":created_at" => $created_at));

        if ($results) {
            if (isset($_POST['comments'])) {
                $observ = $_POST['comments'];
                $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid,output_id,milestone_id,site_id,task_id,subtask_id,formid,observation,created_at,created_by) VALUES (:projid,:output_id,:milestone_id,:site_id,:task_id,:subtask_id,:formid,:observation,:created_at,:created_by)");
                $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => $task_id, ":subtask_id" => $subtask_id, ':formid' => $formid, ':observation' => $observ,':created_at' => $currentdate, ':created_by' => $user_name));
            }

            if (isset($_POST["attachmentpurpose"])) {
                $filecategory = "monitoring checklist";
                $stage = 1;
                $count = count($_POST["attachmentpurpose"]);
                for ($cnt = 0; $cnt < $count; $cnt++) {
                    if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
                        if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                            $purpose = $_POST["attachmentpurpose"][$cnt];
                            $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                            $ext = substr($filename, strrpos($filename, '.') + 1);
                            if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                                $newname = date("d-m-Y") . "-" . $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                                $filepath = "../../uploads/monitoring/other-files/" . $newname;
                                $path = "uploads/monitoring/other-files/" . $newname;
                                if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                                    $filepath = "../../uploads/monitoring/photos/" . $newname;
                                    $path = "uploads/monitoring/photos/" . $newname;
                                }

                                if (!file_exists($filepath)) {
                                    if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                        $qry2 = $db->prepare("INSERT INTO tbl_files (projid,opid,milestone_id,site_id,task_id,subtask_id,projstage,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:output_id, :milestone_id,:site_id,:task_id,:subtask_id,:projstage,:formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
                                        $result =  $qry2->execute(array(':projid' => $projid, ":output_id" => $output_id, ":milestone_id" => $milestone_id, ":site_id" => $site_id, ":task_id" => $task_id, ':subtask_id' => $subtask_id, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                                    } else {
                                        echo "could not move the file ";
                                    }
                                } else {
                                    $msg = 'File you are uploading already exists, try another file!!';
                                }
                            } else {
                                $msg = 'This file type is not allowed, try another file!!';
                            }
                        } else {
                            $msg = 'You have not attached any file!!';
                        }
                    }
                }
            }
        }
    }

    function previous_remarks($design_id, $site_id, $state_id)
    {
        global $db;
        $query_allObservation = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE design_id = :design_id AND site_id=:site_id and state_id = :state_id");
        $query_allObservation->execute(array(":design_id" => $design_id, ":site_id" => $site_id, "state_id" => $state_id));
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
                        ' . $comments_body . '
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>';
        return $comments_body != "" ?  $c : "<h4>No Record</h4>";
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
