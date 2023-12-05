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


    function get_milestone_based_details($projid, $payment_plan_id)
    {
        global $db;
        $query_rsPayment_plan = $db->prepare("SELECT * FROM tbl_project_payment_plan WHERE id=:payment_plan_id");
        $query_rsPayment_plan->execute(array(":payment_plan_id" => $payment_plan_id));
        $Rows_rsPayment_plan = $query_rsPayment_plan->fetch();
        $totalRows_rsPayment_plan = $query_rsPayment_plan->rowCount();
        $milestones = $payment_plan = '';
        if ($totalRows_rsPayment_plan > 0) {
            $payment_plan_id = $Rows_rsPayment_plan['id'];
            $percentage = $Rows_rsPayment_plan['percentage'];
            $payment_plan = $Rows_rsPayment_plan['payment_plan'];

            $query_rsProcurement =  $db->prepare("SELECT SUM(unit_cost*units_no) as budget FROM tbl_project_tender_details WHERE projid=:projid ");
            $query_rsProcurement->execute(array(":projid" => $projid));
            $row_rsProcurement = $query_rsProcurement->fetch();
            $budget = $row_rsProcurement['budget'] != null ? $row_rsProcurement['budget'] : 0;
            $request_amount = ($percentage / 100) * $budget;

            $query_rsPayment_plan_details = $db->prepare("SELECT * FROM tbl_project_payment_plan_details d INNER JOIN tbl_project_milestone m ON m.id =d.milestone_id  WHERE m.projid=:projid AND payment_plan_id=:payment_plan_id ");
            $query_rsPayment_plan_details->execute(array(":projid" => $projid, ":payment_plan_id" => $payment_plan_id));
            $totalRows_rsPayment_plan_details = $query_rsPayment_plan_details->rowCount();
            if ($totalRows_rsPayment_plan_details > 0) {
                $counter = 0;
                while ($Rows_rsPayment_plan_details = $query_rsPayment_plan_details->fetch()) {
                    $counter++;
                    $milestone =  $Rows_rsPayment_plan_details['milestone'];
                    $milestones .= '
                        <tr>
                            <td>' . $counter . '</td>
                            <td>' . $milestone . '</td>
                        </tr>';
                }
            }
        }

        return array("success" => true, "payment_phase" => $payment_plan, "milestones" => $milestones, "request_percentage" => number_format($percentage, 2), "request_amount" => $request_amount);
    }

    function get_task_based_details($projid)
    {
        global $db;
        $query_rsProcurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid=:projid ");
        $query_rsProcurement->execute(array(":projid" => $projid));
        $totalRows_rsProcurement = $query_rsProcurement->rowCount();

        $task_amount = 0;
        $tasks = '';
        if ($totalRows_rsProcurement > 0) {
            $plan_counter = 0;
            while ($row_rsProcurement = $query_rsProcurement->fetch()) {
                $plan_counter++;
                $procurement_cost = $row_rsProcurement['unit_cost'];
                $costlineid = $row_rsProcurement['costlineid'];
                $procurement_units = $row_rsProcurement['units_no'];
                $unit = $row_rsProcurement['unit'];
                $site_id = $row_rsProcurement['site_id'];
                $output_id = $row_rsProcurement['outputid'];
                $tender_id = $row_rsProcurement['id'];

                $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE id=:costlineid");
                $query_rsTask_parameters->execute(array(":costlineid" => $costlineid));
                $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                $description =  ($totalRows_rsTask_parameters > 0) ? $row_rsTask_parameters['description'] : '';

                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :output_id");
                $query_Output->execute(array(":output_id" => $output_id));
                $row_rsOutput = $query_Output->fetch();
                $total_Output = $query_Output->rowCount();
                $output = ($total_Output > 0) ?  $row_rsOutput['indicator_name'] : '';

                $site = 'N/A';
                if ($site_id != 0) {
                    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id=:site_id");
                    $query_Sites->execute(array(":site_id" => $site_id));
                    $rows_sites = $query_Sites->rowCount();
                    $row_Sites = $query_Sites->fetch();
                    $site = $rows_sites > 0 ?  $row_Sites['site'] : "N/A";
                }

                $procurement_total_cost = $procurement_cost * $procurement_units;
                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                $query_rsIndUnit->execute(array(":unit_id" => $unit));
                $row_rsIndUnit = $query_rsIndUnit->fetch();
                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                $task_amount += $procurement_total_cost;
                $tasks .=
                    '<tr>
                    <input type="hidden" name="tender_id[]" value="' . $tender_id . '" />
                    <td> ' . $plan_counter . ' </td>
                    <td>' . $output . ' </td>
                    <td>' . $site . ' </td>
                    <td>' . $description . ' </td>
                    <td>' . number_format($procurement_units, 2) . '  ' . $unit_of_measure . '</td>
                    <td>' . number_format($procurement_cost, 2) . '</td>
                    <td>' . number_format($procurement_total_cost, 2) . '</td>
                </tr>';
            }
        }
        return array("success" => true, "tasks" => $tasks, "task_amount" => $task_amount);
    }

    function get_work_based_details($payment_request_id)
    {
        global $db;
        $query_rsProcurement =  $db->prepare("SELECT * FROM tbl_contractor_payment_request_details WHERE payment_request_id=:payment_request_id ");
        $query_rsProcurement->execute(array(":payment_request_id" => $payment_request_id));
        $totalRows_rsProcurement = $query_rsProcurement->rowCount();

        $task_amount = 0;
        $tasks = '';
        if ($totalRows_rsProcurement > 0) {
            $plan_counter = 0;
            while ($row_rsProcurement = $query_rsProcurement->fetch()) {
                $plan_counter++;
                $procurement_cost = $row_rsProcurement['unit_cost'];
                $subtask_id = $row_rsProcurement['subtask_id'];
                $procurement_units = $row_rsProcurement['units_no'];
                $site_id = $row_rsProcurement['site_id'];
                $output_id = $row_rsProcurement['output_id'];

                $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE subtask_id=:subtask_id AND site_id=:site_id");
                $query_rsTask_parameters->execute(array(":subtask_id" => $subtask_id, ":site_id" => $site_id));
                $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                $description =  ($totalRows_rsTask_parameters > 0) ? $row_rsTask_parameters['description'] : '';
                $unit_id =  ($totalRows_rsTask_parameters > 0) ? $row_rsTask_parameters['unit'] : '';
                $unit_of_measure = get_measurement_unit($unit_id);

                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :output_id");
                $query_Output->execute(array(":output_id" => $output_id));
                $row_rsOutput = $query_Output->fetch();
                $total_Output = $query_Output->rowCount();
                $output = ($total_Output > 0) ?  $row_rsOutput['indicator_name'] : '';

                $site = 'N/A';
                if ($site_id != 0) {
                    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id=:site_id");
                    $query_Sites->execute(array(":site_id" => $site_id));
                    $rows_sites = $query_Sites->rowCount();
                    $row_Sites = $query_Sites->fetch();
                    $site = $rows_sites > 0 ?  $row_Sites['site'] : "N/A";
                }

                $procurement_total_cost = $procurement_cost * $procurement_units;

                $task_amount += $procurement_total_cost;
                $tasks .=
                    '<tr>
                        <td> ' . $plan_counter . ' </td>
                        <td>' . $output . '</td>
                        <td>' . $site . '</td>
                        <td>' . $description . ' </td>
                        <td>' . number_format($procurement_units, 2) . '  ' . $unit_of_measure . '</td>
                        <td>' . number_format($procurement_cost, 2) . '</td>
                        <td>' . number_format($procurement_total_cost, 2) . '</td>
                    </tr>';
            }
        }
        return array("success" => true, "tasks" => $tasks, "task_amount" => number_format($task_amount, 2));
    }

    function store_comments($data)
    {
        global $db;
        $sql = $db->prepare("INSERT INTO tbl_contractor_payment_request_comments (request_id,stage,status,comments,created_by,created_at) VALUES (:request_id,:stage,:status,:comments,:created_by,:created_at)");
        return $sql->execute($data);
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
                $payment_stage =  $Rows_rsComments['stage'];
                $status =  $Rows_rsComments['status'];
                $comment =  $Rows_rsComments['comments'];
                $created_by =  $Rows_rsComments['created_by'];
                $created_at =  $Rows_rsComments['created_at'];
                $role = '';
                if ($payment_stage == 1) {
                    $role = "Contractor";
                } else if ($payment_stage == 2) {
                    $role = "Team Leader";
                } else if ($payment_stage == 3) {
                    $role = "Inspection and Acceptance";
                } else if ($payment_stage == 4) {
                    $role = "CO Department";
                } else if ($payment_stage == 5) {
                    $role = "CO Finance";
                } else if ($payment_stage == 6) {
                    $role = "Director Finance";
                }

                $full_name = '';
                if ($payment_stage > 1) {
                    $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                    $query_rsPMbrs->execute(array(":user_id" => $created_by));
                    $row_rsPMbrs = $query_rsPMbrs->fetch();
                    $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                    $full_name = $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
                } else {
                    $get_contractor = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid=:contrid");
                    $get_contractor->execute(array(":contrid" => $created_by));
                    $count_contractor = $get_contractor->rowCount();
                    $contractor = $get_contractor->fetch();
                    $full_name = $count_contractor > 0 ? $contractor['contractor_name'] : '';
                }

                $comments .= '
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item list-group-item-action active">Comment By: ' . $full_name . '</li>
                            <li class="list-group-item"><strong>Role: </strong> ' . $role . ' </li>
                            <li class="list-group-item"><strong>Comment: </strong> ' . $comment . '</li>
                            <li class="list-group-item"><strong>Comment Date: </strong> ' . date('d M Y', strtotime($created_at)) . ' </li>
                        </ul>
                    </div>
                </div>';
            }
        }
        return $comments;
    }

    if (isset($_GET['get_more_info'])) {
        $request_id = $_GET['request_id'];
        $query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_contractor_payment_requests WHERE id=:request_id");
        $query_rsPayement_reuests->execute(array(":request_id" => $request_id));
        $rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
        $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
        $data = [];
        $attachment = $comments = '';
        if ($total_rsPayement_reuests > 0) {
            $payment_plan_id = $rows_rsPayement_reuests['item_id'];
            $project_plan = $rows_rsPayement_reuests['project_plan'];
            $projid = $rows_rsPayement_reuests['projid'];
            $invoice =  $rows_rsPayement_reuests['invoice'];

            $attachment = $invoice != '' ? '<a href="' . $invoice . '" download><i class="fa fa-download" aria-hidden="true"></i>Download</a>' : '';

            $query_rsPayement_reuests_comments =  $db->prepare("SELECT * FROM  tbl_contractor_payment_request_comments WHERE request_id=:request_id AND stage=1 LIMIT 1");
            $query_rsPayement_reuests_comments->execute(array(":request_id" => $request_id));
            $rows_rsPayement_reuests_comments = $query_rsPayement_reuests_comments->fetch();
            $total_rsPayement_reuests_comments = $query_rsPayement_reuests_comments->rowCount();
            $comments = $total_rsPayement_reuests_comments > 0 ? $rows_rsPayement_reuests_comments['comments'] : "";
            if ($project_plan == 1) {
                $data = get_milestone_based_details($projid, $payment_plan_id);
            } else if ($project_plan == 2) {
                $data  = get_task_based_details($projid);
            } else if ($project_plan == 3) {
                $data =  get_work_based_details($request_id);
            }
        }

        echo json_encode(array("details" => $data, "comments" => $comments, "attachment" => $attachment, 'comments' => get_comments($request_id)));
    }

    if (isset($_POST['approve_contractor_payment'])) {
        $request_id = $_POST['request_id'];
        $payment_stage = $_POST['stage'];
        $comments = $_POST['comments'];
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");
        $status = 1;

        if ($payment_stage == 1) {
            $complete = $_POST['complete'];
            $status = 1;
            $stage =  $complete == 1 ? 2 : 3;
            $sql = $db->prepare("UPDATE tbl_contractor_payment_requests SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  id = :request_id");
            $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
            if ($results) {
                store_comments(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        } else if ($payment_stage == 3) {
            $status = 1;
            $stage = 4;
            $sql = $db->prepare("UPDATE tbl_contractor_payment_requests SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  id = :request_id");
            $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
            if ($results) {
                store_comments(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        } else if ($payment_stage == 4) {
            $status = 1;
            $stage = 5;
            $sql = $db->prepare("UPDATE tbl_contractor_payment_requests SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  id = :request_id");
            $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
            if ($results) {
                store_comments(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        }
        echo json_encode(array("success" => true));
    }

    if (isset($_POST['disburse_payment'])) {
        $request_id = $_POST['request_id'];
        $payment_mode = $_POST['payment_mode'];
        $comments = $_POST['comments'];
        $paid_to = 0;
        $date_paid = $_POST['date_paid'];
        $created_by = $_POST['user_name'];
        $created_at = date("Y-m-d");
        $status = 3;
        $stage = 6;
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
                        $receipt = "uploads/payments/" . $newname;
                    }
                }
            }
        }

        if ($msg) {
            $sql = $db->prepare("INSERT INTO tbl_payments_disbursed (request_id,payment_mode, comments, receipt,paid_to,request_type, date_paid,created_by,created_at) VALUES(:request_id,:payment_mode,:comments,:receipt,:paid_to,:request_type,:date_paid,:created_by,:created_at) ");
            $result = $sql->execute(array(":request_id" => $request_id, ":payment_mode" => $payment_mode, ":comments" => $comments, ":receipt" => $receipt, ":paid_to" => $paid_to,  ":request_type" => 2, ":date_paid" => $date_paid, ":created_by" => $created_by, ":created_at" => $created_at));



            if ($result) {
                $sql = $db->prepare("UPDATE tbl_payments_request SET status = :status WHERE  request_id = :request_id");
                $results  = $sql->execute(array(":status" => $status, ":request_id" => $request_id));
                $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at);
                store_comments($data);
            }
        }
        echo json_encode(array("success" => true));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
