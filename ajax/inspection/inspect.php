<?php 
include '../controller.php';
try {
    if (isset($_POST['store'])) {
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $design_id = $_POST['design_id'];
        $task_id = $_POST['task_id'];
        $site_id = $_POST['site_id'];
        $state_id = $_POST['state_id'];
        $parameter_id = $_POST['parameter_id'];
        $specification_id = $_POST['specification_id'];
        $compliance = $_POST['compliance'];
        $created_by = $user_name;
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");
        $inspection_type = $_POST['inspection_type'];
        $origin = $inspection_type == 2 ? 2 : 1;
        $inspection_id = 0;
        if ($inspection_type == 2) {
            $sql = $db->prepare("INSERT INTO tbl_project_inspection_specification_compliance (projid,output_id,design_id,site_id,state_id,task_id,parameter_id,specification_id,formid,compliance,created_by,created_at) VALUES(:projid,:output_id,:design_id,:site_id,:state_id,:task_id,:parameter_id,:specification_id,:formid,:compliance,:created_by,:created_at)");
            $results = $sql->execute(array(":projid" => $projid, ":output_id" => $output_id, ":design_id" => $design_id, ":site_id" => $site_id, ":state_id" => $state_id, ":task_id" => $task_id, ":parameter_id" => $parameter_id, ":specification_id" => $specification_id, ":formid" => $formid, ":compliance" => $compliance, ":created_by" => $created_by, ":created_at" => $created_at));
            $inspection_id = $db->lastInsertId();
        }

        if (isset($_POST['comments']) && !empty($_POST['comments'])) {
            $observ = $_POST['comments'];
            $SQLinsert = $db->prepare("INSERT INTO tbl_inspection_observations (projid,output_id,design_id,site_id,state_id,task_id,parameter_id,specification_id,inspection_id,formid,observation,created_at,created_by) VALUES (:projid,:output_id,:design_id,:site_id,:state_id,:task_id,:parameter_id,:specification_id,:inspection_id,:formid,:observation,:created_at,:created_by)");
            $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => $output_id, ":design_id" => $design_id, ":site_id" => $site_id, ":state_id" => $state_id, ":task_id" => $task_id, ":parameter_id" => $parameter_id, ':specification_id' => $specification_id, ':formid' => $formid, ':inspection_id' => $inspection_id, ':observation' => $observ, ':created_at' => $currentdate, ':created_by' => $user_name));
        }

        if (isset($_POST["issuedescription"])) {
            $nmb = count($_POST["issuedescription"]);
            for ($k = 0; $k < $nmb; $k++) {
                if (trim($_POST["issuedescription"][$k]) !== '' || !empty(trim($_POST["issuedescription"][$k]))) {
                    $SQLinsert = $db->prepare("INSERT INTO tbl_projissues (projid,output_id,design_id,site_id,state_id,task_id,parameter_id,specification_id,inspection_id,formid,origin,risk_category,observation,created_by,date_created) VALUES (:projid,:output_id,:design_id,:site_id,:state_id,:task_id,:parameter_id,:specification_id,:inspection_id,:formid,:origin,:riskcat,:obsv,:user, :date)");
                    $Rst  = $SQLinsert->execute(array(':projid' => $projid, ":output_id" => $output_id, ":design_id" => $design_id, ":site_id" => $site_id, ":state_id" => $state_id, ':task_id' => $task_id, ":parameter_id" => $parameter_id, ":specification_id" => $specification_id, ':inspection_id' => $inspection_id, ":formid" => $formid, ':origin' => $origin, ':riskcat' => $_POST['issue'][$k], ':obsv' => $_POST['issuedescription'][$k], ':user' => $user_name, ':date' => $currentdate));
                }
            }
        }

        if (isset($_POST["attachmentpurpose"])) {
            $filecategory = "Inspection";
            $stage = 10;
            $count = count($_POST["attachmentpurpose"]);
            for ($cnt = 0; $cnt < $count; $cnt++) {
                if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
                    if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = date("d-m-Y") . "-" . $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                            $filepath = "../../uploads/inspection/other-files/" . $newname;
                            $path = "uploads/inspection/other-files/" . $newname;
                            if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                                $filepath = "../../uploads/inspection/photos/" . $newname;
                                $path = "uploads/inspection/photos/" . $newname;
                            }

                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                    $qry2 = $db->prepare("INSERT INTO tbl_files (projid,opid,design_id,site_id,state_id,task_id,parameter_id,specification_id,projstage,inspection_id,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:output_id, :design_id,:site_id,:state_id,:task_id,:parameter_id,:specification_id,:projstage,:inspection_id, :formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
                                    $result =  $qry2->execute(array(':projid' => $projid, ":output_id" => $output_id, ":design_id" => $design_id, ":site_id" => $site_id, ":state_id" => $state_id, ':task_id' => $task_id, ":parameter_id" => $parameter_id, ':specification_id' => $specification_id, ':projstage' => $stage, ':inspection_id' => $inspection_id, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
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

        echo json_encode(array("success" => true, "message" => "Created successfully"));
    }

    function get_measurement($edit_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE unit = :unit_id");
        $sql->execute(array(":unit_id" => $edit_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        return ($rows_count > 0) ?   $row['unit'] : "";
    }

    function get_inspection_status($status_id)
    {
        global $db;
        $sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE id = :status_id");
        $sql->execute(array(":status_id" => $status_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        return ($rows_count > 0) ? $row['status'] : "";
    }

    if (isset($_GET['get_checklist'])) {
        $parameter_id = $_GET['param_id'];
        $site_id = $_GET['site_id'];
        $mapping_type = $_GET['mapping_type'];
        $state_id = $_GET['state_id'];
        $query_rsMonitoring = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE parameter_id=:parameter_id");
        $query_rsMonitoring->execute(array(":parameter_id" => $parameter_id));
        $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
        $data = "";
        $msg = false;
        if ($totalRows_rsMonitoring > 0) {
            $rowno = 0;
            while ($row_rsMonitoring = $query_rsMonitoring->fetch()) {
                $checklist_id = $row_rsMonitoring['checklist_id'];
                $checklist = $row_rsMonitoring['checklist'];
                $unit_id = $row_rsMonitoring['unit_of_measure'];
                $target = $row_rsMonitoring['target'];
                $units = get_measurement($unit_id);
                $msg = true;
                $rowno++;

                $query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND parameter_id=:parameter_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
                $query_rsMonitoring_Achieved->execute(array(":checklist_id" => $checklist_id, ":parameter_id" => $parameter_id, ":site_id" => $site_id));
                if ($mapping_type == 2 || $mapping_type == 3) {
                    $query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND parameter_id=:parameter_id AND state_id=:state_id ORDER BY id DESC LIMIT 1");
                    $query_rsMonitoring_Achieved->execute(array(":checklist_id" => $checklist_id, ":parameter_id" => $parameter_id, ":state_id" => $state_id));
                }

                $Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
                $totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();
                $achieved = $totalRows_rsMonitoring_Achieved > 0 ? $Rows_rsMonitoring_Achieved['achieved'] : 0;

                $data .= '
                <tr id="s_row' . $rowno . '">
                    <td>' . $rowno . '</td>
                    <td>' . $target . " " . $units . "  " . $checklist . ' </td>  
                    <td>  
                        <input type="number" min="' . $achieved . '" max="' . $target . '" name="achieved[]" id="achievedrow' . $rowno . '" value="" placeholder="Achived ' . $achieved . '" class="form-control" required/>
                    </td> 
                    <input type="hidden" name="target[]" id="targetrow' . $rowno . '" value="' . $target . '"/>
                    <input type="hidden" name="checklist[]" id="checklistrow' . $rowno . '" value="' . $checklist_id . '"/>
                </tr>';
            }
        }
        echo json_encode(array("success" => $msg, "checklists" => $data));
    }
 
    if (isset($_GET['get_issues'])) {
        $specification_id = $_GET['specification_id'];
        $query_allrisks = $db->prepare("SELECT C.rskid, C.category,R.observation, R.date_created, R.status FROM tbl_projrisk_categories C INNER JOIN tbl_projissues R ON C.rskid = R.risk_category WHERE origin = 2 AND  R.specification_id = :specification_id");
        $query_allrisks->execute(array(":specification_id" => $specification_id));
        $totalrows_allrisks = $query_allrisks->rowCount();
        $data = "";
        if ($totalrows_allrisks > 0) {
            $count =  0;
            while ($rows_allrisks = $query_allrisks->fetch()) {
                $count++;
                $category = $rows_allrisks["category"];
                $reason = $rows_allrisks["observation"];
                $issuedate = $rows_allrisks["date_created"];
                $status_id = $rows_allrisks["status"];
                $status = get_inspection_status($status_id);
                $data .= '
                <tr id="s_row">
                    <td>' . $count . '</td>
                    <td>' . $category . '</td>  
                    <td>' . $reason . ' </td>
                    <td>' . $status . ' </td>
                    <td>' . date("Y-m-d", strtotime($issuedate)) . ' </td>
                </tr>';
            }
        }
        echo json_encode(array("success" => true, "issues" => $data));
    }


    function get_body($comments_body, $issues_body)
    {
        $data =  '';
        $c = $i = "";
        if ($comments_body != "") {
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
                                    <th style="width:75%">Comment</th>
                                    <th style="width:20%">Created At</th>
                                </tr>
                            </thead>
                            <tbody id="previous_comments">
                            ' . $comments_body . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>';
        }
        if ($issues_body != "") {
            $i  = '
            <fieldset class="scheduler-border">
                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>Issue(s)
                    </legend>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example">
                                <thead>
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th style="width:30%">Category</th>
                                        <th style="width:45%">Description</th>
                                        <th style="width:10%">Status</th>
                                        <th style="width:10%">Issue Date</th>
                                    </tr>
                                </thead>
                                <tbody id="previous_issues_table">
                                ' . $issues_body . '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            ';
        }
        $data .= '
        <div class="row clearfix">
            ' . $i . $c . '
        </div>';
        return $data;
    }

    if (isset($_GET['get_previous_remarks'])) {
        $specification_id = $_GET['specification_id'];
        $query_allObservation = $db->prepare("SELECT * FROM tbl_inspection_observations WHERE specification_id = :specification_id");
        $query_allObservation->execute(array(":specification_id" => $specification_id));
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
                    <td>' . date("Y-m-d", strtotime($created_at)) . '</td>
                </tr>';
            }
        }

        $query_allrisks = $db->prepare("SELECT C.rskid, C.category,R.observation, R.date_created, R.status FROM tbl_projrisk_categories C INNER JOIN tbl_projissues R ON C.rskid = R.risk_category WHERE origin = 2 AND  R.specification_id = :specification_id");
        $query_allrisks->execute(array(":specification_id" => $specification_id));
        $totalrows_allrisks = $query_allrisks->rowCount();
        $issues_body = "";
        if ($totalrows_allrisks > 0) {
            $count =  0;
            while ($rows_allrisks = $query_allrisks->fetch()) {
                $count++;
                $category = $rows_allrisks["category"];
                $reason = $rows_allrisks["observation"];
                $issuedate = $rows_allrisks["date_created"];
                $status_id = $rows_allrisks["status"];
                $status = get_inspection_status($status_id);
                $issues_body .= '
                <tr id="s_row">
                    <td>' . $count . '</td>
                    <td>' . $category . '</td>  
                    <td>' . $reason . ' </td>
                    <td>' . $status . ' </td>
                    <td>' . date("Y-m-d", strtotime($issuedate)) . ' </td>
                </tr>';
            }
        }
 

        $previous_records = $comments_body != "" || $issues_body != "" ?  get_body($comments_body, $issues_body) : "<h4>No Record</h4>";
        echo json_encode(array("success" => true, "issues" => $previous_records));
    }

    if (isset($_GET['get_previous_records'])) {
        $state_id = $_GET['state_id'];
        $site_id = $_GET['site_id'];
        $design_id = $_GET['design_id'];

        $query_allObservation = $db->prepare("SELECT * FROM tbl_inspection_observations WHERE design_id = :design_id AND site_id=:site_id and state_id = :state_id  AND inspection_id=0");
        $query_allObservation->execute(array(":design_id" => $design_id, ":site_id" => $site_id, "state_id" => $state_id));
        $totalrows_allObservation = $query_allObservation->rowCount();
        $comments_body = "";
        if ($totalrows_allObservation > 0) {
            $count =  0;
            while ($rows_allObservation = $query_allObservation->fetch()) {
                $count++;
                $comments = $rows_allObservation["observation"];
                $created = $rows_allObservation["created_at"];
                $comments_body .= '
                <tr>
                    <td>' . $count . '</td>
                    <td>' . $comments . '</td>
                    <td>' . date("Y-m-d", strtotime($created)) . '</td>
                </tr>';
            }
        }

        $query_allrisks = $db->prepare("SELECT C.rskid, C.category,R.observation, R.date_created, R.status FROM tbl_projrisk_categories C INNER JOIN tbl_projissues R ON C.rskid = R.risk_category WHERE design_id = :design_id AND site_id=:site_id and state_id = :state_id AND inspection_id=0");
        $query_allrisks->execute(array(":design_id" => $design_id, ":site_id" => $site_id, "state_id" => $state_id));
        $totalrows_allrisks = $query_allrisks->rowCount();
        $issues_body = "";
        if ($totalrows_allrisks > 0) {
            $count =  0;
            while ($rows_allrisks = $query_allrisks->fetch()) {
                $count++;
                $category = $rows_allrisks["category"];
                $reason = $rows_allrisks["observation"];
                $issuedate = $rows_allrisks["date_created"];
                $status_id = $rows_allrisks["status"];
                $status = get_inspection_status($status_id);
                $issues_body .= '
                <tr id="s_row">
                    <td>' . $count . '</td>
                    <td>' . $category . '</td>  
                    <td>' . $reason . ' </td>
                    <td>' . $status . ' </td>
                    <td>' . date("Y-m-d", strtotime($issuedate)) . ' </td>
                </tr>';
            }
        }
        $previous_records = $comments_body != "" && $issues_body != "" ?  get_body($comments_body, $issues_body) : "<h4>No Record</h4>";
        echo json_encode(array("success" => true, "previous_records" => $previous_records));
    }
    if(isset($_GET['get_standard'])) {
        $standard_id = $_GET['standard_id'];
        $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id WHERE standard_id=:standard_id  ORDER BY `standard_id` ASC");
        $sql->execute(array(":standard_id" => $standard_id));
        $row = $sql->fetch();
        $rows_count = $sql->rowCount();
        $msg = ($rows_count > 0) ? true : false;
        echo json_encode(array('success'=>$msg, "standard"=>$row['standard'], "description"=>$row['description']));
    }

    if (isset($_GET['get_risk_category'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $risk = '<option value="" selected="selected" class="selection">... Select ...</option>';
        $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.type=3 GROUP BY rskid ORDER BY R.id ASC");
        $query_allrisks->execute();
        $rows_allrisks = $query_allrisks->fetchAll();

        foreach ($rows_allrisks as $row) {
            $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
        }
        echo json_encode(array("success" => true, "issues" => $risk));
    }

} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
