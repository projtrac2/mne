<?php
include '../controller.php';
try {

   function incrementalHash($len = 5)
   {
      $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $base = strlen($charset);
      $result = '';

      $now = explode(' ', microtime())[1];
      while ($now >= $base) {
         $i = $now % $base;
         $result = $charset[$i] . $result;
         $now /= $base;
      }
      return substr($result, -5);
   }

   function get_measurement_unit($unit_id)
   {
      global $db;
      $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
      $query_rsIndUnit->execute(array(":unit_id" => $unit_id));
      $row_rsIndUnit = $query_rsIndUnit->fetch();
      $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
      return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
   }

   function get_outputs($projid)
   {
      global $db;
      $options = '<option value="">.... Select from list ....</option>';

      $query_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE d.projid=:projid ");
      $query_Output->execute(array(":projid" => $projid));
      $total_Output = $query_Output->rowCount();

      if ($total_Output > 0) {
         $options = '<option value="">.... State not Applicable ....</option>';
      }

      $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE p.projid = :projid ");
      $query_Output->execute(array(":projid" => $projid));
      $total_Output = $query_Output->rowCount();
      if ($total_Output > 0) {
         while ($row_rsOutput = $query_Output->fetch()) {
            $site_name = $row_rsOutput['site'];
            $site_id = $row_rsOutput['site_id'];
            $options .= '<option value="' . $site_id . '">' . $site_name . '</option>';
         }
      }

      return $options;
   }

   function get_costline_details($projid, $description)
   {
      global $db;
      $query_rs_output_cost_plan =  $db->prepare("SELECT d.*, m.unit as unit_of_measure FROM `tbl_project_direct_cost_plan` d INNER JOIN tbl_measurement_units m ON m.id = d.unit  WHERE projid=:projid AND d.id=:id");
      $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":id" => $description));
      $row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
      $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();

      $msg = false;
      $no_of_units = 0;
      if ($totalRows_rs_output_cost_plan > 0) {
         $msg = true;
         $units_no = $row_rsOther_cost_plan['units_no'];
         $query_rs_plans =  $db->prepare("SELECT SUM(no_of_units) units_no FROM tbl_payments_request_details WHERE direct_cost_id=:direct_cost_id ");
         $query_rs_plans->execute(array(":direct_cost_id" => $description));
         $row_rs_plans = $query_rs_plans->fetch();
         $totalRows_rs_plans = $query_rs_plans->rowCount();
         $used_units = $totalRows_rs_plans > 0 ? $row_rs_plans['units_no'] : 0;
         $no_of_units = $units_no - $used_units;
      }
      return array("success" => $msg, "budgetline_details" => $row_rsOther_cost_plan, "remaining_units" => $no_of_units);
   }

   function get_financial_cost($projid, $cost_type)
   {
      global $db;
      $query_rs_output_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as budget FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type");
      $query_rs_output_cost_plan->execute(array(":projid" => $projid, ':cost_type' => $cost_type));
      $row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch();
      return $row_rsOther_cost_plan['budget'] != null ? $row_rsOther_cost_plan['budget'] : 0;
   }

   function get_budget_used($projid, $stage, $purpose)
   {
      global $db;
      $query_rsPayement_requests =  $db->prepare("SELECT SUM(amount_requested) as budget FROM tbl_payments_request WHERE projid=:projid AND project_stage=:stage AND purpose=:purpose");
      $query_rsPayement_requests->execute(array(":projid" => $projid, ':stage' => $stage, ":purpose" => $purpose));
      $rows_rsPayement_requests = $query_rsPayement_requests->fetch();
      return $rows_rsPayement_requests['budget'] != null ? $rows_rsPayement_requests['budget'] : 0;
   }

   function get_used_budget($request_id)
   {
      global $db;
      $query_rsPayement_requests =  $db->prepare("SELECT SUM(no_of_units * unit_cost) as budget FROM tbl_payments_request_details WHERE request_id=:request_id");
      $query_rsPayement_requests->execute(array(":request_id" => $request_id));
      $row_rsPayement_requests = $query_rsPayement_requests->fetch();
      return $row_rsPayement_requests['budget'] != null ? $row_rsPayement_requests['budget'] : 0;
   }

   function store_comments($data)
   {
      global $db;
      $sql = $db->prepare("INSERT INTO tbl_payment_request_comments (request_id,stage,status,comments,role,created_by,created_at) VALUES (:request_id,:stage,:status,:comments,:role,:created_by,:created_at)");
      $result  = $sql->execute($data);
      return $result;
   }

   function get_comments($request_id)
   {
      global $db;
      $query_rsComments = $db->prepare("SELECT * FROM tbl_payment_request_comments  WHERE request_id=:request_id  ORDER BY id DESC");
      $query_rsComments->execute(array(":request_id" => $request_id));
      $totalRows_rsComments = $query_rsComments->rowCount();
      $comments = '';
      if ($totalRows_rsComments > 0) {
         $counter = 0;
         while ($Rows_rsComments = $query_rsComments->fetch()) {
            $counter++;
            $comment =  $Rows_rsComments['comments'];
            $role =  $Rows_rsComments['role'];
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
                        <li class="list-group-item"><strong>Role: </strong> ' . $role . '</li>
                        <li class="list-group-item"><strong>Comment: </strong> ' . $comment . '</li>
                        <li class="list-group-item"><strong>Comment Date: </strong> ' . date('d M Y', strtotime($created_at)) . ' </li>
                     </ul>
                  </div>
            </div>';
         }
      }
      return $comments;
   }

   function get_requested_items($costlineid)
   {
      global $db;
      $query_rsTask_parameters = $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE r.id=:costlineid");
      $query_rsTask_parameters->execute(array(":costlineid" => $costlineid));
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

   if (isset($_GET['get_details'])) {
      $projid = $_GET['projid'];
      $stage = $_GET['stage'];
      $purpose = $_GET['purpose'];
      $request_id = $_GET['request_id'];
      $personnel = $used_budget =  $cost = '';
      $budget = 0;
      if ($stage == 8) {
         $cost =  get_financial_cost($projid, 3);
         $used_budget = get_budget_used($projid, $stage, 3);
      } else if ($stage == 9) {
         $cost =  get_financial_cost($projid, 4);
         $used_budget = get_budget_used($projid, $stage, 4);
      } else {
         $cost =  get_financial_cost($projid, 4);
         $used_budget = get_budget_used($projid, $stage, 4);
      }

      $edit_details = "";
      // $request_budget = 0;
      // if ($request_id != '') {
      //    $request_budget =  get_used_budget($request_id);
      //    $edit_details = get_edit_details($request_id);
      // }
      echo json_encode(array("success" => true, "budget" => $budget, "edit_details" => $edit_details));
   }

   function get_tasks($output_id)
   {
      global $db;
      $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
      $query_rsMilestone->execute(array(":output_id" => $output_id));
      $totalRows_rsMilestone = $query_rsMilestone->rowCount();
      $options = '<option value="">.... Select from list ....</option>';
      if ($totalRows_rsMilestone > 0) {
         while ($row_rsMilestone = $query_rsMilestone->fetch()) {
            $milestone_name = $row_rsMilestone['milestone'];
            $milestone_id = $row_rsMilestone['msid'];
            $options .= '<option value="' . $milestone_id . '">' . $milestone_name . '</option>';
         }
      }
      return $options;
   }


   if (isset($_GET['get_sites'])) {
      $projid = $_GET['projid'];
      $output_id = $_GET['output_id'];

      $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid AND d.id=:output_id");
      $query_Output->execute(array(":projid" => $projid, ":output_id" => $output_id));
      $row_rsOutput = $query_Output->fetch();
      $total_Output = $query_Output->rowCount();
      $success = false;
      $indicator_mapping_type = '';
      $options = '<option value="">.... Select from list ....</option>';
      if ($total_Output > 0) {
         $indicator_mapping_type = $row_rsOutput['indicator_mapping_type'];
         $success = true;
         if ($indicator_mapping_type == 1 || $indicator_mapping_type == 3) {
            $query_Site = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE p.projid = :projid AND outputid=:output_id ");
            $query_Site->execute(array(":projid" => $projid, ":output_id" => $output_id));
            $total_Site = $query_Site->rowCount();
            if ($total_Output > 0) {
               while ($row_rsSite = $query_Site->fetch()) {
                  $site_name = $row_rsSite['site'];
                  $site_id = $row_rsSite['site_id'];
                  $options .= '<option value="' . $site_id . '">' . $site_name . '</option>';
               }
            }
         }
      }

      echo json_encode(array("success" => $success, "sites" => $options, "mapping_type" => $indicator_mapping_type, "tasks" => get_tasks($output_id)));
   }

   if (isset($_GET['get_tasks'])) {
      $projid = $_GET['projid'];
      $output_id = $_GET['output_id'];
      $tasks =  get_tasks($output_id);
      echo json_encode(array("success" => true, "tasks" => $tasks));
   }

   if (isset($_GET['get_budgetline_info'])) {
      $projid = $_GET['projid'];
      $cost_type = $_GET['cost_type'];
      $msg = false;
      $options = '<option value="">.... Select from list ....</option>';
      if ($cost_type == 1) {
         $task_ids = $_GET['task_id'];
         $total_tasks = count($task_ids);
         for ($i = 0; $i < $total_tasks; $i++) {
            $task_id = $task_ids[$i];
            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid=:task_id");
            $query_rsMilestone->execute(array(":task_id" => $task_id));
            $row_rsMilestone = $query_rsMilestone->fetch();
            $totalRows_rsMilestone = $query_rsMilestone->rowCount();
            if ($totalRows_rsMilestone > 0) {
               $milestone_name = $row_rsMilestone['milestone'];
               $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type AND tasks=:task_id ORDER BY id ASC");
               $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":task_id" => $task_id));
               $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
               if ($totalRows_rs_output_cost_plan > 0) {
                  $msg = true;
                  $options .= '<optgroup label="Task: (' .  $milestone_name . ')">';
                  while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
                     $description = $row_rsOther_cost_plan['description'];
                     $id = $row_rsOther_cost_plan['id'];
                     $options .= '<option value="' . $id . '">' . $description . '</option>';
                  }
                  $options .= '</optgroup>';
               }
            }
         }
      } else {
         $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ORDER BY id ASC");
         $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
         $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
         if ($totalRows_rs_output_cost_plan > 0) {
            $msg = true;
            while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
               $description = $row_rsOther_cost_plan['description'];
               $id = $row_rsOther_cost_plan['id'];
               $options .= '<option value="' . $id . '">' . $description . '</option>';
            }
         }
      }

      echo json_encode(array("success" => $msg, "description" => $options));
   }

   if (isset($_GET['get_costline_details'])) {
      $projid = $_GET['projid'];
      $description = $_GET['direct_cost_id'];
      $data_details = get_costline_details($projid, $description);
      echo json_encode($data_details);
   }

   if (isset($_POST['store'])) {
      $projid = $_POST['projid'];
      $project_stage = $_POST['project_stage'];
      $cost_type = $_POST['cost_type'];

      $tasks = isset($_POST['tasks']) ? $_POST['tasks'] : 0;
      $amount_requested = 0;
      $requested_by = $_POST['user_name'];
      $date_requested = date("Y-m-d");
      $due_date = date("Y-m-d");
      $status = $cost_type != 1 ? 1 : 0;
      $stage = $cost_type != 1 ? 1 : 0;
      $output_id = $_POST['output'];
      $results = $data =  array();

      if (isset($_POST['request_id']) && !empty($_POST['request_id'])) {
         $sql = $db->prepare("UPDATE tbl_payments_request  SET due_date=:due_date,date_requested=:date_requested, status=:status,comments=:comments WHERE request_id =:request_id");
         $result = $sql->execute(array(":due_date" => $due_date, ":date_requested" => $date_requested, ":status" => $status, ":comments" => $comments, ":request_id" => $request_id,));
         if ($result) {
            $deleteQueryI = $db->prepare("DELETE FROM `tbl_payments_request_details` WHERE request_id=:request_id");
            $resultsI = $deleteQueryI->execute(array(':request_id' => $request_id));
            for ($j = 0; $j < $_count; $j++) {
               $direct_cost_id = $_POST['direct_cost_id'][$j];
               $no_of_units = $_POST['no_units'][$j];
               $unit_cost = $_POST['unit_cost'][$j];
               $sql = $db->prepare("INSERT INTO tbl_payments_request_details (request_id,task_id, direct_cost_id,no_of_units, unit_cost) VALUES (:request_id,:task_id, :direct_cost_id,:no_of_units, :unit_cost)");
               $results[]  = $sql->execute(array(":request_id" => $request_id, ":task_id" => $task_id, ":direct_cost_id" => $direct_cost_id, ":no_of_units" => $no_of_units, ":unit_cost" => $unit_cost));
            }


            if ($cost_type != 1) {
               $comments =  $_POST['comments'];
               $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":role" => "Request Owner", ":created_by" => $requested_by, ":created_at" => $date_requested);
               store_comments($data);
            }
         }
      } else {
         $requested_for = $user_name;
         $sql = $db->prepare("INSERT INTO tbl_payments_request (projid,requested_for,due_date,amount_requested,requested_by,date_requested,status,stage,project_stage,purpose) VALUES(:projid,:requested_for,:due_date,:amount_requested,:requested_by,:date_requested,:status,:stage,:project_stage,:purpose) ");
         $result = $sql->execute(array(":projid" => $projid, ":requested_for" => $requested_for, ":due_date" => $due_date, ":amount_requested" => $amount_requested, ":requested_by" => $user_name, ":date_requested" => $date_requested, ":status" => $status, ":stage" => $stage, ":project_stage" => $project_stage, ":purpose" => $cost_type));
         if ($result) {
            $request_id = $db->lastInsertId();
            if (isset($_POST["direct_cost_id"]) && !empty($_POST['direct_cost_id'])) {
               $_count = count($_POST['direct_cost_id']);
               for ($j = 0; $j < $_count; $j++) {
                  $direct_cost_id = $_POST['direct_cost_id'][$j];
                  $no_of_units = $_POST['no_units'][$j];
                  $unit_cost = $_POST['unit_cost'][$j];
                  $sql = $db->prepare("INSERT INTO tbl_payments_request_details (request_id, direct_cost_id,no_of_units, unit_cost) VALUES (:request_id, :direct_cost_id,:no_of_units, :unit_cost)");
                  $results[]  = $sql->execute(array(":request_id" => $request_id, ":direct_cost_id" => $direct_cost_id, ":no_of_units" => $no_of_units, ":unit_cost" => $unit_cost));
               }
            }

            if ($cost_type != 1) {
               $comments =  $_POST['comments'];
               $response = store_comments(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":role" => "Request Owner", ":created_by" => $requested_by, ":created_at" => $date_requested));
            }
         }
      }
      $msg = !in_array(false, $results) ? true : false;
      echo json_encode(array("success" => $msg));
   }

   if (isset($_POST['approve'])) {
      $request_id = $_POST['request_id'];
      $stage = $_POST['stage'];
      $comments = $_POST['comments'];
      $created_by = $_POST['user_name'];
      $created_at = date("Y-m-d");

      if ($_POST['stage'] == "2") {
         $status = 1;
         $stage = 3;
         $sql = $db->prepare("UPDATE tbl_payments_request SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  id = :request_id");
         $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
         if ($results) {
            $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":role" => "CO Finance", ":created_by" => $created_by, ":created_at" => $created_at);
            store_comments($data);
         }
      } else {
         $status = $_POST['status'];
         $stage = $status == 1 ? 2 : 1;
         $sql = $db->prepare("UPDATE tbl_payments_request SET  stage = :stage,status=:status, cod=:cod, cod_action_date=:cod_action_date  WHERE  id = :request_id");
         $results  = $sql->execute(array(":stage" => $stage, ":status" => $status, ":cod" => $created_by, "cod_action_date" => $created_at, ":request_id" => $request_id));
         if ($results) {
            $data =  array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":role" => "CO " . $departmentlabel, ":created_by" => $created_by, ":created_at" => $created_at);
            store_comments($data);
         }
      }
      echo json_encode(array("success" => true));
   }

   if (isset($_POST['disburse'])) {
      $request_id = $_POST['request_id'];
      $payment_mode = $_POST['payment_mode'];
      $comments = $_POST['comments'];
      $request_type = 1;
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
         $sql = $db->prepare("INSERT INTO tbl_payments_disbursed (request_id,payment_mode,comments,receipt,date_paid,request_type,created_by,created_at) VALUES(:request_id,:payment_mode,:comments,:receipt,:date_paid,:request_type,:created_by,:created_at)");
         $result = $sql->execute(array(":request_id" => $request_id, ":payment_mode" => $payment_mode, ":comments" => $comments, ":receipt" => $receipt, ":request_type" => $request_type, ":date_paid" => $date_paid, ":created_by" => $created_by, ":created_at" => $created_at));

         if ($result) {
            $sql = $db->prepare("UPDATE tbl_payments_request SET status = :status WHERE  id = :request_id");
            $results  = $sql->execute(array(":status" => $status, ":request_id" => $request_id));
            store_comments(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":role" => "Director Finance ", ":created_by" => $created_by, ":created_at" => $created_at));
         }
      }
      echo json_encode(array("success" => true));
   }

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

      $query_rsTask_parameters = $db->prepare("SELECT SUM(unit_cost * no_of_units) as request_amount FROM tbl_payments_request_details  WHERE id=:request_id");
      $query_rsTask_parameters->execute(array(":request_id" => $request_id));
      $Rows_rsTask_parameters = $query_rsTask_parameters->fetch();
      $request_amount = !is_null($Rows_rsTask_parameters['request_amount']) ? $Rows_rsTask_parameters['request_amount'] : 0;

      echo json_encode(array("success" => true, "details" => $rows_rsPayement_requests, 'project_details' => $rows_rsprojects, 'data' => $data, 'comments' => get_comments($request_id), 'disbursement' => $rows_rsDisbursement, "request_amount" => number_format($request_amount, 2)));
   }
} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
   echo $ex->getMessage();
}
