<?php
try {
    include '../controller.php';
    if (isset($_POST['store_handover'])) {
        $projid = $_POST['projid'];
        $remarks = $_POST['comments'];
        $workflow_stage = $_POST['workflow_stage'];
        $stage = $workflow_stage;
        $workflow_stage = 17;
        $sub_stage = 0;
        $today = date('Y-m-d');

        $sql = $db->prepare("INSERT INTO tbl_project_handover (projid,remarks, created_by, created_at) VALUES (:projid,:remarks,:created_by, :created_at)");
        $result = $sql->execute(array(":projid" => $projid, ':remarks' => $remarks, ':created_by' => $user_name, ':created_at' => $today));

        $formid = $db->lastInsertId();
        if (isset($_POST["attachmentpurpose"])) {
            $filecategory = "Handover";
            $count = count($_POST["attachmentpurpose"]);
            for ($cnt = 0; $cnt < $count; $cnt++) {
                if (isset($_POST["attachmentpurpose"][$cnt]) && !empty(["attachmentpurpose"][$cnt])) {
                    if (!empty($_FILES['handoverattachment']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['handoverattachment']['name'][$cnt]);
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["handoverattachment"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = date("d-m-Y") . "-" . $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                            $filepath = "../../uploads/handover/other-files/" . $newname;
                            $path = "uploads/handover/other-files/" . $newname;
                            
                            if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                                $filepath = "../../uploads/handover/photos/" . $newname;
                                $path = "uploads/handover/photos/" . $newname;
                            }
                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['handoverattachment']['tmp_name'][$cnt], $filepath)) {
                                    $qry2 = $db->prepare("INSERT INTO tbl_files (projid,projstage,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:projstage,:formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
                                    $result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $today));
                                }
                            }
                        }
                    }
                }
            }
        }

        $sql = $db->prepare("UPDATE tbl_projects SET projstage=:projstage, proj_substage=:proj_substage WHERE  projid=:projid");
        $result  = $sql->execute(array(":projstage" => $workflow_stage, ":proj_substage" => $sub_stage, ":projid" => $projid));
        $results =  $mail->send_master_data_email($projid, 6, '');

        $sql = $db->prepare("INSERT INTO tbl_project_stage_actions (projid,stage,sub_stage,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:created_by,:created_at)");
        $result = $sql->execute(array(":projid" => $projid, ':stage' => $workflow_stage, ':sub_stage' => $sub_stage, ':created_by' => $user_name, ':created_at' => $today));
        echo json_encode(array('success' => $result));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
