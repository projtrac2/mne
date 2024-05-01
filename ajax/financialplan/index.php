<?php
try {
   include '../controller.php';
   if (isset($_POST['store'])) {
      $success = true;
      if (validate_csrf_token($_POST['csrf_token'])) {
         $projid = $_POST['projid'];
         $outputid = $_POST['output_id'];
         $created_by  = $_POST['user_name'];
         $date_created = date("Y-m-d");
         $cost_type = $_POST['cost_type'];
         $tasks = $_POST['task_id'];
         $other_plan_id = $_POST['budget_line_id'];
         $plan_id = $_POST['plan_id'];
         $site_id = $_POST['site_id'];
         $response = false;
         if ($cost_type == 2) {
            $sql = $db->prepare("DELETE FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=2");
            $sql->execute(array(':projid' => $projid));
         } else {
            $sql = $db->prepare("DELETE FROM tbl_project_direct_cost_plan WHERE projid=:projid AND  tasks=:task_id AND site_id=:site_id AND cost_type=1");
            $sql->execute(array(':projid' => $projid, ":task_id" => $tasks, ":site_id" => $site_id));
         }

         $units_count = count($_POST['no_units']);
         if (isset($_POST['no_units']) && !empty($_POST['no_units'])) {
            for ($j = 0; $j < $units_count; $j++) {
               $description = $_POST['description'][$j];
               $unit = $_POST['unit_of_measure'][$j];
               $subtask_id = $_POST['subtask_id'][$j];
               $unit_cost = $_POST['unit_cost'][$j];
               $units_no = $_POST['no_units'][$j];
               $task_type = $_POST['task_type'][$j];
               $order = $_POST['order'][$j];
               $sql = $db->prepare("INSERT INTO tbl_project_direct_cost_plan (projid,outputid,site_id,plan_id,tasks,subtask_id,other_plan_id,description,unit,unit_cost,units_no,cost_type,task_type,item_order,created_by,date_created) VALUES (:projid,:outputid,:site_id,:plan_id,:tasks,:subtask_id,:other_plan_id, :description,:unit,:unit_cost,:units_no,:cost_type,:task_type,:item_order,:created_by, :date_created)");
               $result[]  = $sql->execute(array(":projid" => $projid, ":outputid" => $outputid, ":site_id" => $site_id, ":plan_id" => $plan_id, ":tasks" => $tasks, ":subtask_id" => $subtask_id, ":other_plan_id" => $other_plan_id, ":description" => $description, ":unit" => $unit, ":unit_cost" => $unit_cost, ":units_no" => $units_no, ":cost_type" => $cost_type, ":task_type" => $task_type, ":item_order" => $order, ":created_by" => $created_by, ":date_created" => $date_created));
            }
         }
      }

      echo json_encode(array("success" => $success));
   }

   function unit_of_measurement($unit_of_measure)
   {
      global $db;
      $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
      $query_rsIndUnit->execute();
      $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
      $unit_options = '<option value="">..Select Unit of Measure..</option>';
      if ($totalRows_rsIndUnit > 0) {
         while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
            $unit_id = $row_rsIndUnit['id'];
            $unit = $row_rsIndUnit['unit'];
            $selected = $unit_of_measure == $unit_id ? "selected" : '';
            $unit_options .= '<option value="' . $unit_id . '" ' . $selected . '>' . $unit . '</option>';
         }
      }
      return $unit_options;
   }

   if (isset($_GET['get_budgetline_details'])) {
      $projid = $_GET['projid'];
      $cost_type = $_GET['cost_type'];
      $edit = $_GET['edit'];
      $output_id = $_GET['output_id'];
      $task_id = $_GET['task_id'];
      $site_id = $_GET['site_id'];
      $direct_cost = $_GET['direct_cost'];
      $administrative_cost = $_GET['administrative_cost'];
      $table_body = '';
      $sub_total_amount = $sub_total_percentage = 0;
      if ($cost_type == 1) {
         if ($edit == 1) {
            $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type AND tasks=:task_id AND site_id=:site_id");
            $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":task_id" => $task_id, ':site_id' => $site_id));
            $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
            if ($totalRows_rs_output_cost_plan > 0) {
               $table_counter = 0;
               while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
                  $table_counter++;
                  $description = $row_rsOther_cost_plan['description'];
                  $unit = $row_rsOther_cost_plan['unit'];
                  $task_parameter_id = $row_rsOther_cost_plan['id'];
                  $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                  $units_no = $row_rsOther_cost_plan['units_no'];
                  $task_type = $row_rsOther_cost_plan['task_type'];
                  $order = $row_rsOther_cost_plan['item_order'];
                  $subtask_id = $row_rsOther_cost_plan['subtask_id'];
                  $total_cost = $unit_cost * $units_no;
                  $sub_total_amount += $total_cost;
                  if ($task_type == 1) {
                     $measurements = unit_of_measurement($unit);
                     $table_body .= '
                  <tr id="budget_line_cost_line' . $table_counter . '">
                     <td>
                        <input type="text" name="order[]"  class="form-control sequence" id="order' . $table_counter . '" value="' . $order . '" required>
                     </td>
                     <td>
                        <input type="text" name="description[]" class="form-control" id="description' . $table_counter . '" value="' . htmlspecialchars($description) . '">
                     </td>
                     <td>
                        <select name="unit_of_measure[]" id="unit_of_measure' . $table_counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                           ' . $measurements . '
                        </select>
                     </td>
                     <td>
                        <input type="number" name="no_units[]" min="0" value="' . $units_no . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter . ')" id="no_units' . $table_counter . '">
                     </td>
                     <td>
                        <input type="number" name="unit_cost[]" min="0" value="' . $unit_cost . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter .   ')" id="unit_cost' . $table_counter . '">
                     </td>
                     <td>
                        <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id' . $table_counter . '" value="' . $subtask_id . '"/>
                        <input type="hidden" name="task_type[]"  class="form-control" id="task_type' . $table_counter . '" value="' . $task_type . '"/>
                        <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $table_counter . '" class="subtotal_amount subamount" value="' . $total_cost . '">
                        <span id="subtotal_cost' . $table_counter . '" style="color:red">' . number_format($total_cost, 2) . '</span>
                     </td>
                     <td style="width:2%">
                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_budget_costline("budget_line_cost_line' . $table_counter . '")>
                           <span class="glyphicon glyphicon-minus"></span>
                        </button>
                     </td>
                  </tr>';
                  } else {
                     $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                     $query_rsIndUnit->execute(array(":unit_id" => $unit));
                     $row_rsIndUnit = $query_rsIndUnit->fetch();
                     $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                     $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                     $table_body .= '
                  <tr id="budget_line_cost_line' . $table_counter . '">
                     <td>
                        <input type="text" name="order[]" class="form-control sequence" id="order' . $table_counter . '" value="' . $order . '" required>
                     </td>
                     <td>
                        ' . $description . '
                        <input type="hidden" name="description[]"  class="form-control" id="description' . $table_counter . '" value="' . htmlspecialchars($description) . '"/>
                     </td>
                     <td>
                        <input type="hidden" name="unit_of_measure[]"  class="form-control" id="unit_of_measure' . $table_counter . '" value="' . $unit . '"/>
                        ' . $unit_of_measure . '
                     </td>
                     <td>
                        <input type="number" name="no_units[]" min="0" value="' . $units_no . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter . ')" id="no_units' . $table_counter . '">
                     </td>
                     <td>
                        <input type="number" name="unit_cost[]" min="0" value="' . $unit_cost . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter .   ')" id="unit_cost' . $table_counter . '">
                     </td>
                     <td>
                        <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id' . $table_counter . '" value="' . $subtask_id . '"/>
                        <input type="hidden" name="task_type[]"  class="form-control" id="task_type' . $table_counter . '" value="0"/>
                        <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $table_counter . '" class="subtotal_amount subamount" value="' . $total_cost . '">
                        <span id="subtotal_cost' . $table_counter . '" style="color:red">' . number_format($total_cost, 2) . '</span>
                     </td>
                     <td style="width:2%"></td>
                  </tr>';
                  }
               }
            }
            $sub_total_percentage = ($sub_total_amount > 0) ? ($sub_total_amount / $direct_cost) * 100 : 0;
         } else {
            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:task_id ORDER BY parenttask");
            $query_rsTasks->execute(array(":output_id" => $output_id, ":task_id" => $task_id));
            $totalRows_rsTasks = $query_rsTasks->rowCount();
            $sub_total_amount = 0;
            if ($totalRows_rsTasks > 0) {
               $table_counter = 0;
               while ($row_rsTasks = $query_rsTasks->fetch()) {
                  $table_counter++;
                  $description = $row_rsTasks['task'];
                  $sub_task_id = $row_rsTasks['tkid'];
                  $unit = $row_rsTasks['unit_of_measure'];
                  $unit_cost = 0;
                  $units_no =  0;
                  $total_cost = $unit_cost * $units_no;
                  $sub_total_amount += $total_cost;
                  $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                  $query_rsIndUnit->execute(array(":unit_id" => $unit));
                  $row_rsIndUnit = $query_rsIndUnit->fetch();
                  $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                  $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                  $table_body .= '
               <tr id="budget_line_cost_line' . $table_counter . '">
                  <td>
                     <input type="text" name="order[]" class="form-control sequence" id="order' . $table_counter . '" required>
                  </td>
                  <td>
                     ' . $description . '
                     <input type="hidden" name="description[]"  class="form-control" id="description' . $table_counter . '" value="' . htmlspecialchars($description) . '"/>
                  </td>
                  <td>
                     <input type="hidden" name="unit_of_measure[]"  class="form-control" id="unit_of_measure' . $table_counter . '" value="' . $unit . '"/>
                     ' . $unit_of_measure . '
                  </td>
                  <td>
                     <input type="number" name="no_units[]" min="0" value="' . $units_no . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter . ')" id="no_units' . $table_counter . '">
                  </td>
                  <td>
                     <input type="number" name="unit_cost[]" min="0" value="' . $unit_cost . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter .   ')" id="unit_cost' . $table_counter . '">
                  </td>
                  <td>
                     <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id' . $table_counter . '" value="' . $sub_task_id . '"/>
                     <input type="hidden" name="task_type[]"  class="form-control" id="task_type' . $table_counter . '" value="0"/>
                     <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $table_counter . '" class="subtotal_amount subamount" value="' . $total_cost . '">
                     <span id="subtotal_cost' . $table_counter . '" style="color:red">' . number_format($total_cost, 2) . '</span>
                  </td>
                  <td style="width:2%">
                  </td>
               </tr>';
               }
            }
         }
      } else {
         $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ");
         $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
         $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
         if ($totalRows_rs_output_cost_plan > 0) {
            $table_counter = 0;
            while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
               $table_counter++;
               $description = $row_rsOther_cost_plan['description'];
               $unit_of_measure = $row_rsOther_cost_plan['unit'];
               $task_parameter_id = $row_rsOther_cost_plan['id'];
               $unit_cost = $row_rsOther_cost_plan['unit_cost'];
               $units_no = $row_rsOther_cost_plan['units_no'];
               $order = $row_rsOther_cost_plan['item_order'];
               $subtask_id = $row_rsOther_cost_plan['subtask_id'];
               $total_cost = $unit_cost * $units_no;
               $sub_total_amount += $total_cost;
               $measurements = unit_of_measurement($unit_of_measure);
               $table_body .= '
               <tr id="budget_line_cost_line' . $table_counter . '">
                  <td>
                     <input type="text" name="order[]" class="form-control sequence" id="order' . $table_counter . '" value="' . $order . '">
                  </td>
                  <td>
                     <input type="text" name="description[]" class="form-control" id="description' . $table_counter . '" value="' . htmlspecialchars($description) . '">
                  </td>
                  <td>
                     <select name="unit_of_measure[]" id="unit_of_measure' . $table_counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                        ' . $measurements . '
                     </select>
                  </td>
                  <td>
                     <input type="number" name="no_units[]" min="0" value="' . $units_no . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter . ')" id="no_units' . $table_counter . '">
                  </td>
                  <td>
                     <input type="number" name="unit_cost[]" min="0" value="' . $unit_cost . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter .   ')" id="unit_cost' . $table_counter . '">
                  </td>
                  <td>
                     <input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id' . $table_counter . '" value="' . $subtask_id . '"/>
                     <input type="hidden" name="task_type[]"  class="form-control" id="task_type' . $table_counter . '" value="0"/>
                     <input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $table_counter . '" class="subtotal_amount subamount" value="' . $total_cost . '">
                     <span id="subtotal_cost' . $table_counter . '" style="color:red">' . number_format($total_cost, 2) . '</span>
                  </td>
                  <td style="width:2%">';
               if ($table_counter != 1) {
                  $table_body .= '
                  <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_budget_costline("budget_line_cost_line' . $table_counter . ')>
                        <span class="glyphicon glyphicon-minus"></span>
                  </button>';
               }
               $table_body .= '</td></tr>';
            }
         }

         $cost = $cost_type == 2 ? $administrative_cost : $direct_cost;
         $sub_total_percentage  = ($sub_total_amount / $cost) * 100;
      }

      $balance = 0;
      if ($cost_type == 1) {
         $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 ");
         $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
         $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
         $direct_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;

         $balance = ($direct_cost - $direct_budget) + $sub_total_amount;
      } else {
         $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
         $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => 2));
         $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
         $administrative_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
         $balance = ($administrative_cost - $administrative_budget) + $sub_total_amount;
      }

      $footer =
         '
      <tr>
         <td><strong>Balance</strong></td>
         <td>
            <input type="hidden" name="remaining_balance" id="remaining_balance" value="' . $balance . '" />
            <input type="text" name="remaining" value="' . number_format($balance, 2) . '" id="remaining_balance1" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
         </td>
         <td><strong>Sub Total</strong></td>
         <td>
            <input type="text" name="subtotal_amount" value="' . number_format($sub_total_amount, 2) . '" id="psub_total_amount3" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
         </td>
         <td> <strong>% Sub Total</strong></td>
         <td colspan="2">
            <input type="text" name="subtotal_percentage" value="' . number_format($sub_total_percentage, 2) . '%" id="psub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
         </td>
      </tr>
      ';
      echo json_encode(array("success" => true, "body" => $table_body, "footer" => $footer, "budget" => $sub_total_amount));
   }


   if (isset($_GET['get_unit_of_measure'])) {
      $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
      $query_rsIndUnit->execute();
      $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
      $unit_options = '<option value="">..Select Unit of Measure..</option>';
      if ($totalRows_rsIndUnit > 0) {
         while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
            $unit_id = $row_rsIndUnit['id'];
            $unit = $row_rsIndUnit['unit'];
            $unit_options .= '<option value="' . $unit_id . '">' . $unit . '</option>';
         }
      }
      echo json_encode(array("measurements" => $unit_options, "success" => true));
   }
} catch (PDOException $ex) {
   customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
