<?php
include '../controller.php';
try {

    if (isset($_GET['get_risk_category'])) {
        $projid = $_GET['projid'];
        $risk = '<option value="" selected="selected" class="selection">... Select ...</option>';
        $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.type=3 GROUP BY rskid ORDER BY R.id ASC");
        $query_allrisks->execute();
        $rows_allrisks = $query_allrisks->fetchAll();
        foreach ($rows_allrisks as $row) {
            $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
        }
        echo json_encode(array("success" => true, "issues" => $risk));
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


    if (isset($_GET['get_project_issues'])) {
        $projid = $_GET['projid'];
        $success = false;
        $query_allrisks = $db->prepare("SELECT C.rskid, R.issue_area,C.category,R.observation, R.priority,R.date_created, R.status FROM tbl_projrisk_categories C INNER JOIN tbl_projissues R ON C.rskid = R.risk_category WHERE projid = :projid AND origin=3");
        $query_allrisks->execute(array(":projid" => $projid));
        $totalrows_allrisks = $query_allrisks->rowCount();
        $issues_body = "";
        if ($totalrows_allrisks > 0) {
            $count =  0;
            $success = true;
            while ($rows_allrisks = $query_allrisks->fetch()) {
                $count++;
                $issueareaid = $rows_allrisks["issue_area"];
                $category = $rows_allrisks["category"];
                $issue = $rows_allrisks["observation"];
                //$issue_area = $rows_allrisks["issue_area"];
                $priorityid = $rows_allrisks["priority"];
                $status_id = $rows_allrisks["status"];
				$issuedate = $rows_allrisks["date_created"];
                $status = get_inspection_status($status_id);
				if($priorityid == 1){
					$priority = "High";
				}elseif($priorityid == 2){
					$priority = "Medium";
				}else{
					$priority = "Low";
				}
				
				if($issueareaid == 1){
					$issue_area = "Quality";
				}elseif($issueareaid == 2){
					$issue_area = "Scope";
				}elseif($issueareaid == 3){
					$issue_area = "Schedule";
				}else{
					$issue_area = "Cost";
				}
                $issues_body .= '
                    <tr id="s_row">
                        <td>' . $count . '</td>
                        <td>' . $issue . ' </td>
                        <td>' . $issue_area . ' </td>
                        <td>' . $category . '</td>
                        <td>' . $priority . ' </td>
                        <td>' . $status . ' </td>
                        <td>' . date("d-m-Y", strtotime($issuedate)) . ' </td>
                    </tr>';
            }
        }

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
                                    <th style="width:3%">#</th>
                                    <th style="width:40%">Issue Description</th>
                                    <th style="width:13%">Issue Area</th>
                                    <th style="width:13%">Risk Category</th>
                                    <th style="width:13%">Issue Priority</th>
                                    <th style="width:8%">Status</th>
                                    <th style="width:10%">Issue Date</th>
                                </tr>
                            </thead>
                            <tbody id="previous_issues_table">
                            ' . $issues_body . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>';
        echo json_encode(array('success' => $success, 'issues' => $i));
    }

    if (isset($_POST['store_checklists'])) {
        $projid = $_POST['projid'];
        $created_at = date("Y-m-d");
        $formid = date("Y-m-d");
        $origin = 3;

        if (isset($_POST["issuedescription"])) {
            $nmb = count($_POST["issuedescription"]);
            for ($k = 0; $k < $nmb; $k++) {
                if (trim($_POST["issuedescription"][$k]) !== '' || !empty(trim($_POST["issuedescription"][$k]))) {
					$riskcategory = $_POST['issue'][$k];
					$issue =  $_POST['issuedescription'][$k];
					$issuearea =  $_POST['issuearea'][$k];
					$priority =  $_POST['issuepriority'][$k];
                    $SQLinsert = $db->prepare("INSERT INTO tbl_projissues (projid,formid,origin,issue_area,risk_category,observation,priority,created_by,date_created) VALUES (:projid,:formid,:origin,:issuearea,:riskcat,:issue,:priority,:user, :date)");
                    $Rst  = $SQLinsert->execute(array(':projid' => $projid, ":formid" => $formid, ':origin' => $origin, ':issuearea' => $issuearea, ':riskcat' => $riskcategory, ':issue' => $issue, ':priority' => $priority, ':user' => $user_name, ':date' => $currentdate));
                }
            }
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
                                    $qry2 = $db->prepare("INSERT INTO tbl_files (projid,projstage,form_id,filename,ftype,floc,fcategory,reason,uploaded_by,date_uploaded)  VALUES (:projid,:projstage,:formid,:filename,:ftype,:floc,:fcat,:desc,:user,:date)");
                                    $result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':formid' => $formid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $path, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
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
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
