<?php
include '../controller.php';
try {
    function get_measurement_unit($unit_id)
    {
        global $db;
        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
        $query_rsIndUnit->execute(array(":unit_id" => $unit_id));
        $row_rsIndUnit = $query_rsIndUnit->fetch();
        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
        return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
    }

    function get_comments($request_id)
    {
        global $db;
        $query_rsComments = $db->prepare("SELECT * FROM tbl_contractor_payment_request_comments  WHERE request_id=:request_id  ORDER BY id DESC");
        $query_rsComments->execute(array(":request_id" => $request_id));
        $totalRows_rsComments = $query_rsComments->rowCount();
        $comments = '';
        if ($totalRows_rsComments > 0) {
            $counter = 0;
            while ($Rows_rsComments = $query_rsComments->fetch()) {
                $counter++;
                $comment =  $Rows_rsComments['comments'];
                $created_by =  $Rows_rsComments['created_by'];
                $created_at =  $Rows_rsComments['created_at'];

                $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                $query_rsPMbrs->execute(array(":user_id" => $created_by));
                $row_rsPMbrs = $query_rsPMbrs->fetch();
                $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                $full_name = $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";

                $comments .= '
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item list-group-item-action active">Comment By: ' . $full_name . '</li>
                            <li class="list-group-item"><strong>Comment: </strong> ' . $comment . '</li>
                            <li class="list-group-item"><strong>Comment Date: </strong> ' . date('d M Y', strtotime($created_at)) . ' </li>
                        </ul>
                    </div>
                </div>';
            }
        }
        return $comments;
    }

    function get_requested_items($request_id)
    {
        global $db;
        $query_rsTask_parameters = $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE r.request_id=:request_id");
        $query_rsTask_parameters->execute(array(":request_id" => $request_id));
        $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
        $tasks = '';
        if ($totalRows_rsTask_parameters > 0) {
            $plan_counter = 0;
            while ($row_rsTask_parameters = $query_rsTask_parameters->fetch()) {
                $plan_counter++;
                $description = $row_rsTask_parameters['description'];
                $unit_no = $row_rsTask_parameters['no_of_units'];
                $unit_cost = $row_rsTask_parameters['unit_cost'];
                $unit_id = $row_rsTask_parameters['unit'];
                $unit_of_measure = get_measurement_unit($unit_id);
                $total_cost = $unit_cost * $unit_no;
                $tasks .=
                    '<tr>
                    <td>' . $description . ' </td>
                    <td>' . number_format($unit_no, 2) . '  ' . $unit_of_measure . '</td>
                    <td>' . number_format($unit_cost, 2) . '</td>
                    <td>' . number_format($total_cost, 2) . '</td>
                </tr>';
            }
        }
        return $tasks;
    }

    function store_comments($data)
    {
        global $db;
        $sql = $db->prepare("INSERT INTO tbl_payment_request_comments (request_id,stage,status,comments,created_by,created_at) VALUES (:request_id,:stage,:status,:comments,:created_by,:created_at)");
        $result  = $sql->execute($data);
        return $result;
    }

    // approve
    if (isset($_POST['approve'])) {
        $request_id = $_POST['request_id'];
        $stage = $_POST['stage'];
        $status = $_POST['status'];
        $comments = $_POST['comments'];
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");

        if ($_POST['stage'] == "2") {
            $stage = $stage + 1;
            $sql = $db->prepare("UPDATE tbl_payments_request SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  request_id = :request_id");
            $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
            if ($results) {
                $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at);
                store_comments($data);
            }
        } else {
            $status = $_POST['status'];
            $stage = $status == 1 ? 2 : 1;
            $sql = $db->prepare("UPDATE tbl_payments_request SET  stage = :stage,status=:status, cod=:cod, cod_action_date=:cod_action_date  WHERE  request_id = :request_id");
            $results  = $sql->execute(array(":stage" => $stage, ":status" => $status, ":cod" => $created_by, "cod_action_date" => $created_at, ":request_id" => $request_id));
            if ($results) {
                $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at);
                store_comments($data);
            }
        }
        echo json_encode(array("success" => true));
    }

    // disburse
    if (isset($_POST['disburse'])) {
        $request_id = $_POST['request_id'];
        $payment_mode = $_POST['payment_mode'];
        $comments = $_POST['comments'];
        $date_paid = $_POST['date_paid'];
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");
        $status = 3;
        $stage = 4;
        $msg = false;
        $receipt = "";
        if (!empty($_FILES['receipt']['name'])) {
            $filename = basename($_FILES['receipt']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            if (($ext != "exe") && ($_FILES["receipt"]["type"] != "application/x-msdownload")) {
                $newname = time() . "_" . $stage . "_" . $filename;
                $filepath = "../../uploads/payments/" . $newname;
                if (!file_exists($filepath)) {
                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $filepath)) {
                        $msg = true;
                        $receipt = $filepath;
                    }
                }
            }
        }

        if ($msg) {
            $sql = $db->prepare("INSERT INTO tbl_payments_disbursed (request_id,payment_mode, comments, receipt,paid_to, date_paid,created_by,created_at) VALUES(:request_id,:payment_mode,:comments,:receipt,:paid_to,:date_paid,:created_by,:created_at) ");
            $result = $sql->execute(array(":request_id" => $request_id, ":payment_mode" => $payment_mode, ":comments" => $comments, ":receipt" => $receipt, ":paid_to" => $user_name, ":date_paid" => $date_paid, ":created_by" => $created_by, ":created_at" => $created_at));

            if ($result) {
                $sql = $db->prepare("UPDATE tbl_payments_request SET status = :status WHERE  request_id = :request_id");
                $results  = $sql->execute(array(":status" => $status, ":request_id" => $request_id));
                $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at);
                store_comments($data);
            }
        }

        echo json_encode(array("success" => true));
    }


    // more info
    if (isset($_GET['get_more_info'])) {
        $request_id = $_GET['request_id'];
        $query_rsPayement_requests =  $db->prepare("SELECT r.*,t.fullname, tt.title AS ttitle FROM  tbl_payments_request r INNER JOIN users u ON u.userid = r.requested_by INNER JOIN  tbl_projteam2 t ON  u.pt_id=t.ptid INNER JOIN tbl_titles tt ON tt.id=t.title WHERE r.id=:request_id");
        $query_rsPayement_requests->execute(array(":request_id" => $request_id));
        $rows_rsPayement_requests = $query_rsPayement_requests->fetch();
        $total_rsPayement_requests = $query_rsPayement_requests->rowCount();
        $attachment = $comments = '';
        $data = '';
        $rows_rsDisbursement = '';
        $rows_rsprojects = [];
        if ($total_rsPayement_requests > 0) {
            $projid = $rows_rsPayement_requests['projid'];
            $purpose = $rows_rsPayement_requests['purpose'];
            $status = $rows_rsPayement_requests['status'];
            $query_rsprojects =  $db->prepare("SELECT projname, projcode FROM  tbl_projects WHERE  projid=:projid ");
            $query_rsprojects->execute(array(":projid" => $projid));
            $total_rsprojects = $query_rsprojects->rowCount();
            $rows_rsprojects = $query_rsprojects->fetch();

            if ($status == 3) {
                $query_rsDisbursement =  $db->prepare("SELECT * FROM  tbl_payments_disbursed WHERE  request_id=:request_id ");
                $query_rsDisbursement->execute(array(":request_id" => $request_id));
                $total_rsDisbursement = $query_rsDisbursement->rowCount();
                $rows_rsDisbursement = $query_rsDisbursement->fetch();
            }

            $attachment = '';
            if ($purpose == 1) {
            } else {
                $data = get_requested_items($request_id);
            }
        }

        echo json_encode(array("success" => true, "details" => $rows_rsPayement_requests, 'project_details' => $rows_rsprojects, 'data' => $data, 'comments' => get_comments($request_id), 'disbursement' => $rows_rsDisbursement));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
